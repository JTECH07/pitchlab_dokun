<?php

namespace App\Http\Controllers;

use App\Models\Artisan;
use App\Models\ReservationRequest;
use App\Models\SavoirFaire;
use Illuminate\Http\Request;

class ArtisanSpaceController extends Controller
{
    public function editProfile(Request $request)
    {
        $artisan = Artisan::with('savoirFaires')->where('user_id', $request->user()->id)->firstOrFail();
        $savoirFaires = SavoirFaire::all();
        $quartiers = \App\Models\Quartier::orderBy('sort_order')->orderBy('name')->get();

        return view('artisan-space.edit-profile', compact('artisan', 'savoirFaires', 'quartiers'));
    }

    public function updateProfile(Request $request)
    {
        $artisan = Artisan::where('user_id', $request->user()->id)->firstOrFail();

        $data = $request->validate([
            'first_name'         => 'required|string|max:255',
            'last_name'          => 'required|string|max:255',
            'professional_name'  => 'nullable|string|max:255',
            'phone'              => 'required|string|max:255',
            'whatsapp'           => 'nullable|string|max:255',
            'description'        => 'nullable|string',
            'history'            => 'nullable|string',
            'experience_years'   => 'nullable|integer|min:0',
            'address'            => 'required|string|max:255',
            'latitude'           => 'nullable|numeric',
            'longitude'          => 'nullable|numeric',
            'quartier'           => 'nullable|string|exists:quartiers,slug',
            'savoir_faires'      => 'nullable|array',
            'savoir_faires.*'    => 'exists:savoir_faires,id',
        ]);

        // Si un quartier est choisi et qu'aucune coordonnée précise n'existe,
        // on positionne l'artisan au centre du quartier (localisation exacte garantie).
        if (!empty($data['quartier']) && (empty($data['latitude']) || empty($data['longitude']))) {
            $quartier = \App\Models\Quartier::where('slug', $data['quartier'])->first();
            if ($quartier) {
                $data['latitude'] = $quartier->lat;
                $data['longitude'] = $quartier->lng;
            }
        }
        unset($data['quartier']);

        $data['savoir_faires'] = $data['savoir_faires'] ?? [];

        $artisan->update([
            'pending_profile_data' => $data,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Votre profil a été soumis pour validation. Les modifications seront visibles après approbation par un administrateur.');
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $artisan = Artisan::where('user_id', $user->id)->first();

        if (! $artisan && $user->role === 'artisan') {
            $artisan = Artisan::create([
                'user_id'        => $user->id,
                'first_name'     => $user->name ?? '',
                'last_name'      => '',
                'professional_name' => '',
                'phone'          => '',
                'address'        => '',
                'status'         => 'draft',
            ]);
        }

        if (! $artisan) {
            return view('artisan-space.index', compact('artisan'))->with('notice', 'Votre profil est en cours de création.');
        }

        $reservations = ReservationRequest::with('experience')
            ->where('artisan_id', $artisan->id)
            ->latest()
            ->get();

        $stats = [
            'pending'   => $reservations->where('status', 'pending')->count(),
            'accepted'  => $reservations->where('status', 'accepted')->count(),
            'completed' => $reservations->where('status', 'completed')->count(),
        ];

        return view('artisan-space.index', compact('artisan', 'stats', 'reservations'));
    }

    public function updateReservation(Request $request, ReservationRequest $reservation)
    {
        abort_unless($reservation->artisan?->user_id === $request->user()->id, 403);
        $oldStatus = $reservation->status;
        $data = $request->validate(['status' => 'required|in:accepted,rejected,completed']);
        $reservation->update($data);

        if (($data['status'] ?? null) === 'accepted' && $oldStatus !== 'accepted') {
            $this->notifyVisitorAccepted($reservation);
        }

        return back()->with('success', 'La réservation a été mise à jour.');
    }

    public function updateReservationStatus(Request $request, ReservationRequest $reservation)
    {
        abort_unless($reservation->artisan?->user_id === $request->user()->id, 403);
        $oldStatus = $reservation->status;
        $data = $request->validate(['status' => 'required|in:accepted,rejected,completed']);
        $reservation->update($data);

        if (($data['status'] ?? null) === 'accepted' && $oldStatus !== 'accepted') {
            $this->notifyVisitorAccepted($reservation);
        }

        return response()->json(['status' => 'success', 'reservation' => $reservation->fresh()]);
    }

    private function notifyVisitorAccepted(ReservationRequest $reservation): void
    {
        if (! empty($reservation->visitor_email)) {
            try {
                \Mail::to($reservation->visitor_email)->send(
                    new \App\Mail\VisitorBookingAccepted($reservation)
                );
            } catch (\Throwable $e) {
                \Log::warning('Failed to send acceptance email to visitor: ' . $e->getMessage());
            }
        }

        if (! empty($reservation->visitor_phone)) {
            try {
                $phone = preg_replace('/\D/', '', $reservation->visitor_phone);
                $artisanName = $reservation->artisan?->professional_name
                    ?? ($reservation->artisan?->first_name . ' ' . $reservation->artisan?->last_name);
                $message = "Votre réservation {$reservation->reference} chez {$artisanName} a été acceptée ! "
                    . "Date : {$reservation->requested_date}. "
                    . "Montant : " . ($reservation->total_amount ? number_format($reservation->total_amount, 0, ',', ' ') . ' FCFA' : 'Visite libre') . ". "
                    . route('reservations.receipt', $reservation->qr_code_token);

                \App\Services\WhatsAppService::send($phone, $message);
            } catch (\Throwable $e) {
                \Log::warning('Failed to send WhatsApp to visitor: ' . $e->getMessage());
            }
        }
    }

    public function uploadPhoto(Request $request)
    {
        $artisan = Artisan::where('user_id', $request->user()->id)->firstOrFail();

        $request->validate([
            'photo' => 'required|file|image|mimes:jpeg,png,webp,gif|max:5120',
        ]);

        $file = $request->file('photo');
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('photos', $filename, 'public');
        $path = 'photos/' . $filename;

        $artisan->pending_photo_path = $path;

        if (!$artisan->photo_path) {
            $artisan->photo_path = $path;
        }

        $artisan->save();

        return response()->json([
            'status' => 'success',
            'url' => asset('storage/' . $path),
            'photo_path' => $artisan->photo_path,
            'pending_photo_path' => $artisan->pending_photo_path,
        ]);
    }
}
