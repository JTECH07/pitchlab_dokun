<?php

namespace App\Http\Controllers;

use App\Models\Artisan;

class FeatureController extends Controller
{
    public function showBridge(Artisan $artisan)
    {
        $artisan->load('savoirFaires');

        return view('features.bridge', compact('artisan'));
    }

    public function showVoice(Artisan $artisan)
    {
        return view('features.voice', compact('artisan'));
    }

    public function showLearn(Artisan $artisan)
    {
        return view('features.learn', compact('artisan'));
    }
}
