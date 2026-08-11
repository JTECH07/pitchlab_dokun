<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ArtisanPublicController extends Controller
{
    public function show($id)
    {
        $artisan = \App\Models\Artisan::with('savoirFaires')->findOrFail($id);
        return view('artisan', compact('artisan'));
    }
}
