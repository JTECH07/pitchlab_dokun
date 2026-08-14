<?php

namespace App\Http\Controllers;

use App\Models\Artisan;
use App\Models\ReservationRequest;
use Illuminate\Http\Request;

class ArtisanSpaceController extends Controller
{
    public function index(Request $request)
    {
        $artisan = Artisan::where('user_id', $request->user()->id)->first();
        if (! $artisan) {
            return view('artisan-space.index', compact('artisan'))->with('notice', 'Votre profil est en cours de création.');
        }

        $reservations = ReservationRequest::with('experience')
            ->where('artisan_id', $artisan->id)->latest()->paginate(12);
        $stats = [
            'pending' => ReservationRequest::where('artisan_id', $artisan->id)->where('status', 'pending')->count(),
            'accepted' => ReservationRequest::where('artisan_id', $artisan->id)->where('status', 'accepted')->count(),
            'completed' => ReservationRequest::where('artisan_id', $artisan->id)->where('status', 'completed')->count(),
        ];

        return view('artisan-space.index', compact('artisan', 'reservations', 'stats'));
    }

    public function updateReservation(Request $request, ReservationRequest $reservation)
    {
        abort_unless($reservation->artisan?->user_id === $request->user()->id, 403);
        $data = $request->validate(['status' => 'required|in:accepted,rejected,completed']);
        $reservation->update($data);

        return back()->with('success', 'La réservation a été mise à jour.');
    }
}
