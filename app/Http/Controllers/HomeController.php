<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = \App\Models\Category::all();
        // 3 artisans pour les cartes de présentation
        $artisans = \App\Models\Artisan::with('savoirFaires')->where('status', 'published')->take(3)->get();
        // TOUS les artisans publiés géolocalisés pour la carte d'accueil
        $mapArtisans = \App\Models\Artisan::with('savoirFaires')
            ->where('status', 'published')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get()
            ->each(fn ($a) => $a->append('image_url'));
        $quartiers = \App\Http\Controllers\MapController::quartiersWithArtisans();

        return view('welcome', compact('categories', 'artisans', 'mapArtisans', 'quartiers'));
    }
}
