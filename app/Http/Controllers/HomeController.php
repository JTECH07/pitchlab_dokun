<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = \App\Models\Category::all();
        $artisans = \App\Models\Artisan::with('savoirFaires')->where('status', 'published')->take(3)->get();
        
        return view('welcome', compact('categories', 'artisans'));
    }
}
