<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MapController extends Controller
{
    public function index()
    {
        $artisans = \App\Models\Artisan::with('savoirFaires')
                        ->where('status', 'published')
                        ->whereNotNull('latitude')
                        ->whereNotNull('longitude')
                        ->get();
        return view('map', compact('artisans'));
    }
}
