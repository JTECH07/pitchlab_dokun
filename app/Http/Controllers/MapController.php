<?php

namespace App\Http\Controllers;

use App\Models\Artisan;
use App\Models\Category;
use App\Models\Quartier;
use Illuminate\Http\Request;

class MapController extends Controller
{
    public function index(Request $request)
    {
        $query = Artisan::with(['savoirFaires.category', 'media', 'experiences'])
            ->where('status', 'published')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude');

        if ($request->filled('category')) {
            $query->whereHas('savoirFaires.category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('professional_name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        $artisans = $query->get()->each(function ($a) {
            $a->append('image_url');
        });

        $categories = Category::withCount('savoirFaires')->get();
        $quartiers = self::quartiersWithArtisans();

        return view('map', compact('artisans', 'categories', 'quartiers'));
    }

    public function adminMap()
    {
        $artisans = Artisan::with(['user', 'savoirFaires.category'])
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        return view('admin.map', compact('artisans'));
    }

    /**
     * Quartiers où des artisans publiés sont réellement localisés
     * (dans le rayon défini par l'admin autour du centre du quartier).
     */
    public static function quartiersWithArtisans()
    {
        $artisanCoords = Artisan::where('status', 'published')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get(['latitude', 'longitude']);

        return Quartier::orderBy('sort_order')->orderBy('name')->get()
            ->filter(function ($q) use ($artisanCoords) {
                $radiusKm = $q->radius_km ?: 0.4;
                return $artisanCoords->contains(function ($a) use ($q, $radiusKm) {
                    return self::haversineKm($q->lat, $q->lng, $a->latitude, $a->longitude) <= $radiusKm;
                });
            })->values();
    }

    private static function haversineKm($lat1, $lng1, $lat2, $lng2): float
    {
        $r = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        $a = sin($dLat / 2) ** 2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) ** 2;
        return $r * 2 * atan2(sqrt($a), sqrt(1 - $a));
    }
}
