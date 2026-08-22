<?php

namespace App\Http\Controllers;

use App\Models\Artisan;
use App\Models\Review;
use App\Models\ReservationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    /** Affiche le formulaire d'avis pour une réservation complétée. */
    public function create(Request $request, $reservation_id)
    {
        $reservation = ReservationRequest::with('artisan', 'experience')
            ->where('qr_code_token', $reservation_id)
            ->where('user_id', $request->user()->id)
            ->where('status', 'completed')
            ->firstOrFail();

        // Vérifier qu'aucun avis n'existe déjà
        $existing = Review::where('user_id', $request->user()->id)
            ->where('reservation_request_id', $reservation->id)->first();

        if ($existing) {
            return redirect()->route('reservations.receipt', $reservation->qr_code_token)
                ->with('success', 'Vous avez déjà laissé un avis pour cette expérience.');
        }

        return view('reviews.create', compact('reservation'));
    }

    /** Enregistre l'avis (status pending, en attente de modération). */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'reservation_request_id' => 'required|exists:reservation_requests,id',
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:2000',
        ]);

        $reservation = ReservationRequest::where('id', $validated['reservation_request_id'])
            ->where('user_id', $request->user()->id)
            ->where('status', 'completed')
            ->firstOrFail();

        // Un seul avis par réservation, même en POST direct
        $already = Review::where('user_id', $request->user()->id)
            ->where('reservation_request_id', $reservation->id)->exists();
        abort_if($already, 403, 'Un avis a déjà été soumis pour cette réservation.');

        app(\App\Services\LoyaltyService::class)->award($request->user(), 'review_published', ['reservation_id' => $reservation->id]);

        Review::create([
            'user_id'                => $request->user()->id,
            'artisan_id'             => $reservation->artisan_id,
            'reservation_request_id' => $reservation->id,
            'rating'                 => $validated['rating'],
            'comment'                => $validated['comment'],
            'status'                 => 'pending',
        ]);

        return redirect()->route('artisans.show', $reservation->artisan_id)
            ->with('success', 'Merci pour votre avis ! Il sera publié après modération.');
    }

    /** Admin : liste des avis en attente. */
    public function adminIndex(Request $request)
    {
        $reviews = Review::with('user', 'artisan')
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->latest()->paginate(20);

        return view('admin.reviews.index', compact('reviews'));
    }

    /** Admin : modérer un avis (publish/reject). */
    public function adminModerate(Request $request, Review $review)
    {
        $validated = $request->validate(['action' => 'required|in:published,rejected']);
        $review->update([
            'status'       => $validated['action'],
            'moderated_by' => $request->user()->id,
            'moderated_at' => now(),
        ]);

        return back()->with('success', 'Avis ' . ($validated['action'] === 'published' ? 'publié' : 'rejeté') . ' avec succès.');
    }
}
