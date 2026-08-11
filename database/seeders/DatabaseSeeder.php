<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \Illuminate\Database\Eloquent\Model::unguard();
        // 1. Créer l'Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@dokun.bj'],
            [
                'name' => 'Joseph ALAYE (Admin)',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ]
        );

        // 2. Créer les Catégories
        $catArtisanat = \App\Models\Category::firstOrCreate(['slug' => 'artisanat'], ['name' => 'Artisanat & Création', 'description' => 'Métiers manuels traditionnels']);
        $catArt = \App\Models\Category::firstOrCreate(['slug' => 'art-traditionnel'], ['name' => 'Art Traditionnel', 'description' => 'Expressions artistiques historiques']);
        
        // 3. Créer les Savoir-Faire
        $sfPoterie = \App\Models\SavoirFaire::firstOrCreate(['slug' => 'poterie-traditionnelle'], ['name' => 'Poterie Traditionnelle', 'category_id' => $catArtisanat->id, 'description' => 'Fabrication de canaris et pots en argile selon des techniques ancestrales.']);
        $sfVannerie = \App\Models\SavoirFaire::firstOrCreate(['slug' => 'vannerie'], ['name' => 'Vannerie', 'category_id' => $catArtisanat->id, 'description' => 'Tressage de paniers, nattes et chapeaux.']);
        
        // 4. Créer un Artisan Test
        $artisanUser = User::firstOrCreate(
            ['email' => 'artisan@dokun.bj'],
            [
                'name' => 'Koffi',
                'password' => bcrypt('password'),
                'role' => 'artisan',
            ]
        );

        $artisan = \App\Models\Artisan::firstOrCreate(
            ['phone' => '+229 01 23 45 67 89'],
            [
                'user_id' => $artisanUser->id,
                'first_name' => 'Koffi',
                'last_name' => 'DOSSOU',
                'professional_name' => 'Atelier DOSSOU',
                'whatsapp' => '+2290123456789',
                'description' => 'Passionné par la terre cuite depuis 20 ans.',
                'history' => 'Héritage transmis de père en fils depuis 3 générations dans le quartier de Catchi.',
                'experience_years' => 20,
                'address' => 'Quartier Catchi, Porto-Novo',
                'latitude' => 6.4969,
                'longitude' => 2.6289,
                'status' => 'published',
            ]
        );

        // 5. Lier l'artisan à la Poterie
        $artisan->savoirFaires()->syncWithoutDetaching([$sfPoterie->id]);
    }
}
