<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'artisans_count' => \App\Models\Artisan::count(),
            'categories_count' => \App\Models\Category::count(),
            'reservations_count' => \App\Models\ReservationRequest::count(),
            'pending_reservations' => \App\Models\ReservationRequest::where('status', 'pending')->count(),
        ];

        return view('dashboard', compact('stats'));
    }
}
