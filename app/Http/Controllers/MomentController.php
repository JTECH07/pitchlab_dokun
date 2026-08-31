<?php

namespace App\Http\Controllers;

use App\Models\Moment;
use App\Models\ReservationRequest;
use App\Services\LoyaltyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MomentController extends Controller
{
    /** Flux public des souvenirs publiés. */
    public function index()
    {
        $moments = Moment::published()
            ->with('user', 'artisan', 'reservation.experience')
            ->latest()
            ->paginate(12);

        return view('moments.index', compact('moments'));
    }

    /** Formulaire de publication du souvenir après une expérience complétée. */
    public function create(Request $request, $reservation_id)
    {
        $reservation = ReservationRequest::with('artisan', 'experience')
            ->where(function($q) use($reservation_id) {
                $q->where('id', $reservation_id)->orWhere('qr_code_token', $reservation_id);
            })
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $existing = Moment::where('user_id', $request->user()->id)
            ->where('reservation_request_id', $reservation->id)->exists();

        if ($existing) {
            return redirect()->route('reservations.receipt', $reservation->qr_code_token)
                ->with('info', "Un ƉƆKUN Moment a déjà été publié pour cette expérience.");
        }

        return view('moments.create', compact('reservation'));
    }

    /** Enregistre le souvenir (status pending, en attente de modération). */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'reservation_request_id' => 'required|exists:reservation_requests,id',
            'title'                  => 'required|string|max:120',
            'description'            => 'nullable|string|max:1000',
            'video'                  => 'nullable|file|mimes:mp4,webm,mov,ogg|max:51200',
            'cover'                  => 'nullable|file|image|mimes:jpeg,png,webp,jpg|max:5120',
        ]);

        $reservation = ReservationRequest::where('id', $validated['reservation_request_id'])
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $already = Moment::where('user_id', $request->user()->id)
            ->where('reservation_request_id', $reservation->id)->exists();
        abort_if($already, 403, 'Un souvenir a déjà été publié pour cette expérience.');

        $videoPath = null;
        if ($request->hasFile('video')) {
            $file = $request->file('video');
            $name = 'moment_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $videoPath = 'moments/' . $name;
            Storage::disk('public')->put($videoPath, file_get_contents($file));
        }

        $coverPath = null;
        if ($request->hasFile('cover')) {
            $file = $request->file('cover');
            $name = 'cover_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $coverPath = 'moments/' . $name;
            Storage::disk('public')->put($coverPath, file_get_contents($file));
        }

        $moment = Moment::create([
            'user_id'                => $request->user()->id,
            'reservation_request_id' => $reservation->id,
            'artisan_id'             => $reservation->artisan_id,
            'title'                  => $validated['title'],
            'description'            => $validated['description'] ?? null,
            'video_path'             => $videoPath,
            'cover_path'             => $coverPath,
            'status'                 => 'pending',
        ]);

        app(LoyaltyService::class)->award($request->user(), 'moment_shared', ['moment_id' => $moment->id]);

        return redirect()->route('moments.show', $moment->share_token)
            ->with('success', "Merci ! Votre ƉƆKUN Moment sera publié après modération.");
    }

    /** Page publique de partage d'un souvenir (accessible par lien / QR). */
    public function show($share_token)
    {
        $moment = Moment::with('user', 'artisan', 'reservation.experience')
            ->where('share_token', $share_token)
            ->firstOrFail();

        if ($moment->status !== 'published' && optional(auth()->user())->id !== $moment->user_id && !auth()->user()?->isAdmin()) {
            abort(403);
        }

        return view('moments.show', compact('moment'));
    }

    /** Admin : liste des souvenirs en attente. */
    public function adminIndex(Request $request)
    {
        $moments = Moment::with('user', 'artisan')
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->latest()->paginate(20);

        return view('admin.moments.index', compact('moments'));
    }

    /** Admin : modérer un souvenir (publish/reject). */
    public function adminModerate(Request $request, Moment $moment)
    {
        $validated = $request->validate(['action' => 'required|in:published,rejected']);
        $moment->update([
            'status'       => $validated['action'],
            'moderated_by' => $request->user()->id,
            'moderated_at' => now(),
        ]);

        return back()->with('success', 'Souvenir ' . ($validated['action'] === 'published' ? 'publié' : 'rejeté') . ' avec succès.');
    }
}
