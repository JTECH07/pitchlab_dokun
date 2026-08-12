<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\SavoirFaire;
use Illuminate\Http\Request;

class SavoirFairePublicController extends Controller
{
    public function index()
    {
        $categories = Category::with('savoirFaires.artisans')->get();
        $savoirFaires = SavoirFaire::with('category')->withCount('artisans')->get();

        return view('savoir_faire.index', compact('categories', 'savoirFaires'));
    }

    public function show($slug)
    {
        $savoirFaire = SavoirFaire::with(['category', 'artisans' => function($q) {
            $q->where('status', 'published');
        }])->where('slug', $slug)->firstOrFail();

        return view('savoir_faire.show', compact('savoirFaire'));
    }
}
