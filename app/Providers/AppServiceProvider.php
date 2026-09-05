<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Forcer HTTPS en production (derrière proxy Render/Cloudflare)
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}
