<?php

namespace App\Http\Controllers;

use App\Models\Artisan;
use App\Models\Category;
use App\Models\SavoirFaire;
use Illuminate\Http\Request;

class ArtisanPublicController extends Controller
{
    public function index(Request $request)
    {
        $query = Artisan::with('savoirFaires')->where('status', 'published');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'ilike', "%{$search}%")
                  ->orWhere('last_name', 'ilike', "%{$search}%")
                  ->orWhere('professional_name', 'ilike', "%{$search}%")
                  ->orWhere('description', 'ilike', "%{$search}%");
            });
        }

        if ($request->filled('savoir_faire')) {
            $sfId = $request->savoir_faire;
            $query->whereHas('savoirFaires', function($q) use ($sfId) {
                $q->where('savoir_faires.id', $sfId);
            });
        }

        $artisans = $query->latest()->paginate(12)->withQueryString();
        $savoirFaires = SavoirFaire::orderBy('name')->get();
        $categories = Category::with('savoirFaires')->get();

        $favoriteIds = auth()->check()
            ? \App\Models\ArtisanFavorite::where('user_id', auth()->id())->pluck('artisan_id')->all()
            : [];

        return view('artisans.index', compact('artisans', 'savoirFaires', 'categories', 'favoriteIds'));
    }

    public function show($id)
    {
        $artisan = Artisan::with([
            'savoirFaires',
            'media' => fn ($query) => $query->where('status', 'published'),
            'experiences' => fn ($query) => $query->where('is_published', true),
            'reviews' => fn ($query) => $query->where('status', 'published'),
            'reviews.user',
            'reservations' => fn ($query) => $query->where('user_id', auth()->id())->where('status', 'completed'),
        ])->findOrFail($id);
        return view('artisan', compact('artisan'));
    }
}
