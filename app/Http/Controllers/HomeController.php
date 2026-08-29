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
        // Compteurs réels affichés dans la barre de statistiques
        $artisansCount = \App\Models\Artisan::where('status', 'published')->count();
        $categoriesCount = \App\Models\Category::count();
        // Nombre d'artisans publiés par catégorie (badges des cartes savoir-faire)
        $artisanCounts = \App\Models\Artisan::where('status', 'published')
            ->whereNotNull('category_id')
            ->selectRaw('category_id, COUNT(*) as total')
            ->groupBy('category_id')
            ->pluck('total', 'category_id');
        // TOUS les artisans publiés géolocalisés pour la carte d'accueil
        $mapArtisans = \App\Models\Artisan::with('savoirFaires')
            ->where('status', 'published')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get()
            ->each(fn ($a) => $a->append('image_url'));
        $quartiers = \App\Http\Controllers\MapController::quartiersWithArtisans();
        
        $visitorCount = \App\Models\User::count();

        return view('welcome', compact('categories', 'artisans', 'mapArtisans', 'quartiers', 'artisansCount', 'categoriesCount', 'artisanCounts', 'visitorCount'));
    }
}
