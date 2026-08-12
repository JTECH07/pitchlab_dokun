<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MapController extends Controller
{
    public function index()
    {
        $artisans = \App\Models\Artisan::with(['savoirFaires', 'media'])
                        ->where('status', 'published')
                        ->whereNotNull('latitude')
                        ->whereNotNull('longitude')
                        ->get();
        
        $artisans->each(function ($artisan) {
            $artisan->append('image_url');
        });
        return view('map', compact('artisans'));
    }
}
