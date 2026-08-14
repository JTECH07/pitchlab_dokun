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
        $catArtisanat = \App\Models\Category::firstOrCreate(['slug' => 'artisanat'], ['name' => 'Artisanat & Création', 'description' => 'Métiers manuels traditionnels et terre cuite']);
        $catArt = \App\Models\Category::firstOrCreate(['slug' => 'art-traditionnel'], ['name' => 'Art & Masques Sacrés', 'description' => 'Sculptures rituelles et expressions artistiques historiques']);
        $catTextile = \App\Models\Category::firstOrCreate(['slug' => 'textile-tissage'], ['name' => 'Textile & Tissage', 'description' => 'Tissage traditionnel Kanvo et arts du fil']);
        $catVannerie = \App\Models\Category::firstOrCreate(['slug' => 'vannerie-fibres'], ['name' => 'Vannerie & Fibres', 'description' => 'Tressage de raffia, nattes et vanneries d\'art']);

        // 3. Créer les Savoir-Faire
        $sfPoterie = \App\Models\SavoirFaire::firstOrCreate(
            ['slug' => 'poterie-traditionnelle'],
            ['name' => 'Poterie & Terre Cuite', 'category_id' => $catArtisanat->id, 'description' => 'Fabrication de canaris, jarres et pots en argile selon des techniques ancestrales d\'Akpro-Missérété et Porto-Novo.']
        );
        $sfVannerie = \App\Models\SavoirFaire::firstOrCreate(
            ['slug' => 'vannerie-raffia'],
            ['name' => 'Vannerie & Tressage de Raphia', 'category_id' => $catVannerie->id, 'description' => 'Confection de paniers, nattes cérémonielles et chapeaux traditionnels en paille et raphia naturel.']
        );
        $sfSculpture = \App\Models\SavoirFaire::firstOrCreate(
            ['slug' => 'sculpture-masques'],
            ['name' => 'Sculpture sur Bois & Masques Gèlèdè', 'category_id' => $catArt->id, 'description' => 'Taille de masques rituels Gèlèdè et Zangbéto, fétiches et statuettes en bois d\'iroko et de gmelina.']
        );
        $sfKanvo = \App\Models\SavoirFaire::firstOrCreate(
            ['slug' => 'tissage-kanvo'],
            ['name' => 'Tissage Kanvo (Pagne Tissé)', 'category_id' => $catTextile->id, 'description' => 'Tissage à la main de bandes de coton coloré (Kanvo), habit traditionnel royal et d\'apparat.']
        );

        // 4. Artisans et Médias associés
        $artisansData = [
            [
                'email' => 'koffi.dossou@dokun.bj',
                'name' => 'Koffi DOSSOU',
                'first_name' => 'Koffi',
                'last_name' => 'DOSSOU',
                'professional_name' => 'Atelier Terre Cuite DOSSOU',
                'phone' => '+229 01 97 12 34 56',
                'whatsapp' => '+2290197123456',
                'description' => 'Maître potier depuis plus de 20 ans, spécialiste du travail des argiles rouges naturelles de la région de Porto-Novo.',
                'history' => 'Savoir-faire transmis de génération en génération depuis 3 siècles dans le quartier historique de Catchi.',
                'experience_years' => 22,
                'address' => 'Quartier Catchi, Porto-Novo',
                'latitude' => 6.4969,
                'longitude' => 2.6289,
                'status' => 'published',
                'savoir_faire' => $sfPoterie,
                'image_path' => 'images/artisans/koffi_dossou.png',
                'image_title' => 'Koffi DOSSOU façonnant un canari en argile'
            ],
            [
                'email' => 'yvette.gbaguidi@dokun.bj',
                'name' => 'Yvette GBAGUIDI',
                'first_name' => 'Yvette',
                'last_name' => 'GBAGUIDI',
                'professional_name' => 'Maison de la Vannerie d\'Art',
                'phone' => '+229 01 95 44 22 11',
                'whatsapp' => '+2290195442211',
                'description' => 'Artisane experte dans l\'art du tressage des fibres de raphia et feuilles de palmier séchées.',
                'history' => 'Fondatrice de la coopérative des femmes vanières de Djradjè, elle perpétue un art végétal d\'une grande finesse.',
                'experience_years' => 18,
                'address' => 'Quartier Djradjè, Porto-Novo',
                'latitude' => 6.4850,
                'longitude' => 2.6200,
                'status' => 'published',
                'savoir_faire' => $sfVannerie,
                'image_path' => 'images/artisans/yvette_gbaguidi.png',
                'image_title' => 'Yvette GBAGUIDI tressant des nattes et paniers en raphia'
            ],
            [
                'email' => 'ayadji.houndagnon@dokun.bj',
                'name' => 'Ayadji HOUNDAGNON',
                'first_name' => 'Ayadji',
                'last_name' => 'HOUNDAGNON',
                'professional_name' => 'Atelier des Masques du Bénin',
                'phone' => '+229 01 61 88 99 00',
                'whatsapp' => '+2290161889900',
                'description' => 'Sculpteur sur bois traditionnel, créateur de masques rituels Gèlèdè reconnus par le patrimoine UNESCO.',
                'history' => 'Initié dès son plus jeune âge aux secrets du bois et aux symboles cultuels sacrés de la culture Yoruba-Nagô.',
                'experience_years' => 30,
                'address' => 'Quartier Hassoumi, Porto-Novo',
                'latitude' => 6.4780,
                'longitude' => 2.6310,
                'status' => 'published',
                'savoir_faire' => $sfSculpture,
                'image_path' => 'images/artisans/ayadji_houndagnon.png',
                'image_title' => 'Ayadji HOUNDAGNON sculptant un masque Gèlèdè'
            ],
            [
                'email' => 'messan.akakpo@dokun.bj',
                'name' => 'Messan AKAKPO',
                'first_name' => 'Messan',
                'last_name' => 'AKAKPO',
                'professional_name' => 'Tissage Royal Kanvo',
                'phone' => '+229 01 96 33 22 11',
                'whatsapp' => '+2290196332211',
                'description' => 'Maître tisserand du pagne traditionnel Kanvo, expert en motifs géométriques et symboliques béninoises.',
                'history' => 'Tisserand officiel des tenues d\'apparat pour les cérémonies traditionnelles et festivals de Porto-Novo.',
                'experience_years' => 25,
                'address' => 'Quartier Guévié, Porto-Novo',
                'latitude' => 6.4910,
                'longitude' => 2.6150,
                'status' => 'published',
                'savoir_faire' => $sfKanvo,
                'image_path' => 'images/artisans/messan_akakpo.png',
                'image_title' => 'Messan AKAKPO tissant le Kanvo traditionnel sur son métier'
            ]
        ];

        foreach ($artisansData as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => bcrypt('password'),
                    'role' => 'artisan',
                ]
            );

            $artisan = \App\Models\Artisan::firstOrCreate(
                ['phone' => $data['phone']],
                [
                    'user_id' => $user->id,
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'professional_name' => $data['professional_name'],
                    'whatsapp' => $data['whatsapp'],
                    'description' => $data['description'],
                    'history' => $data['history'],
                    'experience_years' => $data['experience_years'],
                    'address' => $data['address'],
                    'latitude' => $data['latitude'],
                    'longitude' => $data['longitude'],
                    'status' => $data['status'],
                ]
            );

            $artisan->savoirFaires()->syncWithoutDetaching([$data['savoir_faire']->id]);

            // Ajouter le média photo pour chaque artisan
            \App\Models\Media::firstOrCreate(
                [
                    'artisan_id' => $artisan->id,
                    'path' => $data['image_path'],
                ],
                [
                    'type' => 'image',
                    'title' => $data['image_title'],
                    'description' => $data['description'],
                ]
            );

            $experiences = [
                'poterie-traditionnelle' => ['Initiation à la poterie traditionnelle', 'Façonnez un objet en argile aux côtés du maître artisan.', 120, 6, 12000, 'images/poterie_en_action.png'],
                'vannerie-raffia' => ['Tresser les fibres de Porto-Novo', 'Apprenez les gestes essentiels du tressage de raphia.', 150, 8, 10000, 'images/artisans/yvette_gbaguidi.png'],
                'sculpture-masques' => ['Découvrir la sculpture sur bois', 'Une visite commentée de l’atelier et de ses matières.', 90, 6, 8000, 'images/artisans/ayadji_houndagnon.png'],
                'tissage-kanvo' => ['Tisser le Kanvo avec le maître', 'Découvrez le métier à tisser et créez votre première bande.', 120, 5, 15000, 'images/artisans/messan_akakpo.png'],
            ];
            $experience = $experiences[$data['savoir_faire']->slug] ?? ['Visite de l’atelier', 'Découvrez les gestes et l’histoire de ce savoir-faire avec l’artisan.', 90, 8, 8000, $data['image_path']];
            \App\Models\Experience::firstOrCreate(
                ['artisan_id' => $artisan->id, 'title' => $experience[0]],
                ['summary' => $experience[1], 'duration_minutes' => $experience[2], 'capacity' => $experience[3], 'price' => $experience[4], 'image_path' => $experience[5]]
            );
        }
    }
}
