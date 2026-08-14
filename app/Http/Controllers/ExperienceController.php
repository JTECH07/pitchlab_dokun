<?php

namespace App\Http\Controllers;

use App\Models\Experience;
use Illuminate\Http\Request;

class ExperienceController extends Controller
{
    public function index(Request $request)
    {
        $experiences = Experience::with(['artisan.media', 'artisan.savoirFaires'])
            ->where('is_published', true)
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = $request->string('q');
                $query->where(function ($nested) use ($term) {
                    $nested->where('title', 'like', "%{$term}%")
                        ->orWhere('summary', 'like', "%{$term}%")
                        ->orWhereHas('artisan', fn ($artisan) => $artisan->where('professional_name', 'like', "%{$term}%"));
                });
            })
            ->orderBy('price')
            ->paginate(12)
            ->withQueryString();

        return view('experiences.index', compact('experiences'));
    }
}
