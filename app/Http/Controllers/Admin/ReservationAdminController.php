<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReservationRequest;
use Illuminate\Http\Request;

class ReservationAdminController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');

        $query = ReservationRequest::with('artisan')->latest();

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $reservations = $query->paginate(15);
        $counts = [
            'all'       => ReservationRequest::count(),
            'pending'   => ReservationRequest::where('status', 'pending')->count(),
            'accepted'  => ReservationRequest::where('status', 'accepted')->count(),
            'rejected'  => ReservationRequest::where('status', 'rejected')->count(),
            'completed' => ReservationRequest::where('status', 'completed')->count(),
        ];

        return view('admin.reservations.index', compact('reservations', 'counts', 'status'));
    }

    public function updateStatus(Request $request, ReservationRequest $reservation)
    {
        $request->validate(['status' => 'required|in:pending,accepted,rejected,completed']);
        $reservation->update(['status' => $request->status]);

        $labels = [
            'accepted'  => 'acceptée',
            'rejected'  => 'refusée',
            'completed' => 'complétée',
            'pending'   => 'mise en attente',
        ];

        return back()->with('success', "Demande de {$reservation->visitor_name} " . ($labels[$request->status] ?? ''));
    }
}
