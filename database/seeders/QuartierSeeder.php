<?php

namespace Database\Seeders;

use App\Models\Quartier;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class QuartierSeeder extends Seeder
{
    public function run(): void
    {
        $quartiers = [
            ['name' => 'Catchi',       'lat' => 6.5050, 'lng' => 2.6200, 'radius_km' => 0.48, 'color' => '#064E3B', 'sort_order' => 1],
            ['name' => 'Djradjè',     'lat' => 6.5100, 'lng' => 2.6350, 'radius_km' => 0.42, 'color' => '#C99424', 'sort_order' => 2],
            ['name' => 'Haussoumi',   'lat' => 6.5000, 'lng' => 2.6400, 'radius_km' => 0.40, 'color' => '#9B59B6', 'sort_order' => 3],
            ['name' => 'Guèvié',     'lat' => 6.4900, 'lng' => 2.6150, 'radius_km' => 0.45, 'color' => '#E67E22', 'sort_order' => 4],
            ['name' => 'Togba',       'lat' => 6.4850, 'lng' => 2.6300, 'radius_km' => 0.38, 'color' => '#27AE60', 'sort_order' => 5],
            ['name' => 'Honvié',      'lat' => 6.4950, 'lng' => 2.6500, 'radius_km' => 0.42, 'color' => '#2980B9', 'sort_order' => 6],
            ['name' => 'Kpota',       'lat' => 6.4880, 'lng' => 2.6400, 'radius_km' => 0.40, 'color' => '#E74C3C', 'sort_order' => 7],
            ['name' => 'Agblangandan','lat' => 6.5080, 'lng' => 2.6500, 'radius_km' => 0.44, 'color' => '#16A085', 'sort_order' => 8],
        ];

        foreach ($quartiers as $q) {
            $q['slug'] = Str::slug($q['name']);
            Quartier::updateOrCreate(['slug' => $q['slug']], $q);
        }
    }
}
