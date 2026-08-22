<?php

namespace Database\Seeders;

use App\Models\Badge;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    public function run(): void
    {
        $badges = [
            ['code' => 'first_steps', 'name_fr' => 'Premiers Pas',   'name_en' => 'First Steps',    'desc_fr' => 'Terminer ta première leçon Learn',            'desc_en' => 'Complete your first Learn lesson',       'icon' => 'check-circle'],
            ['code' => 'golden_voice','name_fr' => 'Bouche d\'Or',   'name_en' => 'Golden Voice',   'desc_fr' => 'Obtenir 100% à un quiz',                      'desc_en' => 'Score 100% on a quiz',                   'icon' => 'volume'],
            ['code' => 'scholar',     'name_fr' => 'Érudit du Fon',  'name_en' => 'Fon Scholar',    'desc_fr' => 'Terminer 5 leçons Learn',                     'desc_en' => 'Complete 5 Learn lessons',               'icon' => 'graduation'],
            ['code' => 'collector',   'name_fr' => 'Collectionneur', 'name_en' => 'Collector',      'desc_fr' => 'Ajouter 3 artisans en favoris',               'desc_en' => 'Add 3 artisans to favorites',            'icon' => 'heart'],
            ['code' => 'critic',      'name_fr' => 'Critique Émérite','name_en' => 'Renowned Critic','desc_fr' => 'Publier ton premier avis',                   'desc_en' => 'Publish your first review',              'icon' => 'star'],
            ['code' => 'explorer',    'name_fr' => 'Explorateur',    'name_en' => 'Explorer',       'desc_fr' => 'Effectuer ta première réservation',           'desc_en' => 'Make your first reservation',            'icon' => 'compass'],
            ['code' => 'on_fire',     'name_fr' => 'En Feu',         'name_en' => 'On Fire',        'desc_fr' => 'Se connecter 7 jours de suite',               'desc_en' => 'Sign in 7 days in a row',                'icon' => 'flame'],
            ['code' => 'ambassador',  'name_fr' => 'Ambassadeur',    'name_en' => 'Ambassador',     'desc_fr' => 'Atteindre 1 200 points fidélité',             'desc_en' => 'Reach 1,200 loyalty points',             'icon' => 'gem'],
        ];

        foreach ($badges as $b) {
            Badge::updateOrCreate(['code' => $b['code']], $b);
        }
    }
}
