<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if ($request->user()->role === 'artisan') {
            return redirect()->route('artisan-space.index');
        }

        if ($request->user()->role !== 'admin') {
            $reservations = \App\Models\ReservationRequest::where('user_id', $request->user()->id)->latest()->get();
            return view('dashboard', ['mode' => 'visitor', 'reservations' => $reservations]);
        }

        $stats = [
            'artisans_count' => \App\Models\Artisan::count(),
            'categories_count' => \App\Models\Category::count(),
            'reservations_count' => \App\Models\ReservationRequest::count(),
            'pending_reservations' => \App\Models\ReservationRequest::where('status', 'pending')->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
