<?php

namespace Database\Seeders;

use App\Models\Artisan;
use App\Models\Category;
use App\Models\Experience;
use App\Models\Media;
use App\Models\Review;
use App\Models\SavoirFaire;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        Model::unguard();

        $this->call(QuartierSeeder::class);
        $this->call(LearnContentSeeder::class);
        $this->call(BadgeSeeder::class);

        // ─── Admin ────────────────────────────────────────────────
        $admin = User::firstOrCreate(
            ['email' => 'admin@dokun.bj'],
            [
                'name' => 'Admin DOKUN',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ]
        );
        $admin =User::firstOrCreate(
            ['email' => 'alayejoseph1@gmail.com'],
            [
                'name' => 'J-Admin DOKUN',
                'password' => bcrypt('J-Admin'),
                'role' => 'admin',
            ]
        );

        // ─── Catégories ──────────────────────────────────────────
        $catArtisanat = Category::firstOrCreate(
            ['slug' => 'artisanat-creation'],
            [
                'name' => 'Artisanat & Création',
                'description' => 'Poterie, céramique, objets du quotidien façonnés à la main dans les ateliers de Porto-Novo.',
            ]
        );

        $catArtMasques = Category::firstOrCreate(
            ['slug' => 'art-masques-sacres'],
            [
                'name' => 'Art & Masques Sacrés',
                'description' => 'Sculpture, fétiches, masques Gelede — expressions artistiques rituelles et patrimoniales.',
            ]
        );

        $catTextile = Category::firstOrCreate(
            ['slug' => 'textile-tissage'],
            [
                'name' => 'Textile & Tissage',
                'description' => 'Tissage Kanvo, indigo, pagne — les arts du fil transmis de mère en fille.',
            ]
        );

        $catVannerie = Category::firstOrCreate(
            ['slug' => 'vannerie-fibres'],
            [
                'name' => 'Vannerie & Fibres',
                'description' => 'Raphia, sisal, objets tressés — la vannerie d\'art béninoise dans toute sa finesse.',
            ]
        );

        // ─── Savoir-Faires ────────────────────────────────────────
        $sfPoterie = SavoirFaire::firstOrCreate(
            ['slug' => 'poterie-terre-cuite'],
            [
                'name' => 'Poterie & Terre Cuite',
                'category_id' => $catArtisanat->id,
                'description' => 'Fabrication de canaris, jarres et pièces décoratives en argile cuite selon les techniques ancestrales de Porto-Novo.',
            ]
        );

        $sfSculpture = SavoirFaire::firstOrCreate(
            ['slug' => 'sculpture-masques-gelede'],
            [
                'name' => 'Sculpture sur Bois & Masques Gèlèdè',
                'category_id' => $catArtMasques->id,
                'description' => 'Taille de masques rituels Gèlèdè, fétiches et statuettes en bois d\'iroko et de gmelina — un patrimoine inscrit par l\'UNESCO.',
            ]
        );

        $sfKanvo = SavoirFaire::firstOrCreate(
            ['slug' => 'tissage-kanvo-indigo'],
            [
                'name' => 'Tissage Kanvo & Teinture à l\'Indigo',
                'category_id' => $catTextile->id,
                'description' => 'Tissage à la main de bandes de coton coloré (Kanvo) et teinture naturelle à l\'indigo — habit traditionnel royal et d\'apparat.',
            ]
        );

        $sfVannerie = SavoirFaire::firstOrCreate(
            ['slug' => 'vannerie-raphia'],
            [
                'name' => 'Vannerie & Tressage de Raphia',
                'category_id' => $catVannerie->id,
                'description' => 'Confection de paniers, nattes cérémonielles, chapeaux et objets décoratifs en raphia, sisal et fibres de palmier.',
            ]
        );

        // ─── Utilisateurs Artisans ────────────────────────────────
        $passwordBcrypt = bcrypt('dokun2026');

        $userData = [
            [
                'email' => 'artisan1@dokun.bj',
                'name' => 'Koffi DOSSOU',
            ],
            [
                'email' => 'artisan2@dokun.bj',
                'name' => 'Yvette GBAGUIDI',
            ],
            [
                'email' => 'artisan3@dokun.bj',
                'name' => 'Ayadji HOUNDAGNON',
            ],
            [
                'email' => 'artisan4@dokun.bj',
                'name' => 'Messan AKAKPO',
            ],
            [
                'email' => 'artisan5@dokun.bj',
                'name' => 'Adjovi CÉLINE',
            ],
            [
                'email' => 'artisan6@dokun.bj',
                'name' => 'Gnonhawa PATIENCE',
            ],
            [
                'email' => 'artisan7@dokun.bj',
                'name' => 'Bénédicte HOUNSA',
            ],
            [
                'email' => 'artisan8@dokun.bj',
                'name' => 'Damien SOSSA',
            ],
        ];

        $users = [];
        foreach ($userData as $ud) {
            $users[$ud['email']] = User::firstOrCreate(
                ['email' => $ud['email']],
                [
                    'name' => $ud['name'],
                    'password' => $passwordBcrypt,
                    'role' => 'artisan',
                ]
            );
        }

        // ─── Artisans ────────────────────────────────────────────
        $artisansData = [
            [
                'user_email' => 'artisan1@dokun.bj',
                'first_name' => 'Koffi',
                'last_name' => 'DOSSOU',
                'professional_name' => 'Atelier Terre Cuite DOSSOU',
                'phone' => '+229 97 12 34 56',
                'whatsapp' => '+22997123456',
                'description' => 'Maître potier passionné par les argiles rouges de la région de Porto-Novo, Koffi façonne canaris, jarres et pièces décoratives qui racontent l\'histoire de son quartier.',
                'history' => 'Né dans une lignée de potiers du quartier Catchi, Koffi a appris les gestes ancestraux dès l\'âge de huit ans aux côtés de son grand-père. Après un passage à l\'école des Beaux-Arts de Porto-Novo, il est revenu dans l\'atelier familial pour marier tradition et modernité. Aujourd\'hui, ses pièces sont vendues sur les marchés de Cotonou et exposées lors des festivals culturels béninois.',
                'experience_years' => 22,
                'address' => 'Quartier Catchi, Porto-Novo',
                'latitude' => 6.4920,
                'longitude' => 2.6280,
                'status' => 'published',
                'savoir_faire' => $sfPoterie,
                'image_path' => 'images/artisans/koffi_dossou.png',
                'image_title' => 'Koffi DOSSOU façonnant un canari en argile rouge',
            ],
            [
                'user_email' => 'artisan2@dokun.bj',
                'first_name' => 'Yvette',
                'last_name' => 'GBAGUIDI',
                'professional_name' => 'Maison de la Vannerie d\'Art',
                'phone' => '+229 95 44 22 11',
                'whatsapp' => '+22995442211',
                'description' => 'Artisane experte dans l\'art du tressage des fibres de raphia et de palmier, Yvette crée des paniers, nattes et chapeaux d\'une finesse remarquable.',
                'history' => 'Yvette a grandi à Djradjè, un quartier où les femmes vanières se transmettent le savoir du tressage depuis des siècles. Elle a fondé une coopérative de douze femmes artisanes qui produit des pièces pour les marchés de Porto-Novo et de Calavi. Son travail a été sélectionné pour représenter le Bénin lors d\'une exposition artisanale à Dakar.',
                'experience_years' => 18,
                'address' => 'Quartier Djradjè, Porto-Novo',
                'latitude' => 6.4890,
                'longitude' => 2.6310,
                'status' => 'published',
                'savoir_faire' => $sfVannerie,
                'image_path' => 'images/artisans/yvette_gbaguidi.png',
                'image_title' => 'Yvette GBAGUIDI tressant des nattes en raphia',
            ],
            [
                'user_email' => 'artisan3@dokun.bj',
                'first_name' => 'Ayadji',
                'last_name' => 'HOUNDAGNON',
                'professional_name' => 'Atelier des Masques du Bénin',
                'phone' => '+229 61 88 99 00',
                'whatsapp' => '+22961889900',
                'description' => 'Sculpteur sur bois traditionnel, créateur de masques rituels Gèlèdè reconnus dans tout le sud-ouest béninois, gardien d\'un patrimoine culturel immatériel.',
                'history' => 'Initié dès son plus jeune âge aux secrets du bois sacré par son père sculpteur à Haussoumi, Ayadji a passé trente ans à perfectionner l\'art du ciseau et du marteau. Ses masques Gèlèdè et Zangbéto sont aujourd\'hui exposés au Musée Honmé de Porto-Novo et dans des collections privées à l\'étranger. Il encadre régulièrement des ateliers de transmission pour les jeunes du quartier.',
                'experience_years' => 30,
                'address' => 'Quartier Haussoumi, Porto-Novo',
                'latitude' => 6.4970,
                'longitude' => 2.6350,
                'status' => 'published',
                'savoir_faire' => $sfSculpture,
                'image_path' => 'images/artisans/ayadji_houndagnon.png',
                'image_title' => 'Ayadji HOUNDAGNON sculptant un masque Gèlèdè',
            ],
            [
                'user_email' => 'artisan4@dokun.bj',
                'first_name' => 'Messan',
                'last_name' => 'AKAKPO',
                'professional_name' => 'Tissage Royal Kanvo',
                'phone' => '+229 96 33 22 11',
                'whatsapp' => '+22996332211',
                'description' => 'Maître tisserand du pagne traditionnel Kanvo, Messan crée des pièces aux motifs géométriques et symboliques qui honorent les traditions textiliers du Bénin.',
                'history' => 'Issu d\'une famille de tisserands de Guèvié, Messan a appris le maniement du métier à tisser enfil et à navette dès l\'adolescence. Il est devenu le tisserand attitré de plusieurs chefs traditionnels de Porto-Novo pour les cérémonies officielles. Il explore désormais la teinture à l\'indigo pour créer des coloris profonds et durables.',
                'experience_years' => 25,
                'address' => 'Quartier Guèvié, Porto-Novo',
                'latitude' => 6.5010,
                'longitude' => 2.6240,
                'status' => 'published',
                'savoir_faire' => $sfKanvo,
                'image_path' => 'images/artisans/messan_akakpo.png',
                'image_title' => 'Messan AKAKPO tissant le Kanvo sur son métier traditionnel',
            ],
            [
                'user_email' => 'artisan5@dokun.bj',
                'first_name' => 'Adjovi',
                'last_name' => 'CÉLINE',
                'professional_name' => 'Atelier Céline — Poterie Moderne',
                'phone' => '+229 91 55 66 77',
                'whatsapp' => '+22991556677',
                'description' => 'Patière moderne alliant techniques ancestrales et design contemporain, Adjovi crée des pièces de décoration et de vaisselle qui réinventent la céramique béninoise.',
                'history' => 'Adjovi a découvert la poterie en accompagnant sa mère au four du quartier Togba à l\'âge de six ans. Après des études en design produit, elle a ouvert son propre atelier où elle forme de jeunes apprentis tout en développant une ligne de céramique minimaliste. Ses créations se vendent dans des boutiques artisanales de Cotonou et sur commande pour des hôtels et restaurants.',
                'experience_years' => 12,
                'address' => 'Quartier Togba, Porto-Novo',
                'latitude' => 6.4840,
                'longitude' => 2.6200,
                'status' => 'published',
                'savoir_faire' => $sfPoterie,
                'image_path' => 'images/artisans/adjovi_celine.png',
                'image_title' => 'Adjovi CÉLINE en train de modeler une pièce contemporaine',
            ],
            [
                'user_email' => 'artisan6@dokun.bj',
                'first_name' => 'Gnonhawa',
                'last_name' => 'PATIENCE',
                'professional_name' => 'Atelier Indigo Honvié',
                'phone' => '+229 94 77 88 33',
                'whatsapp' => '+22994778833',
                'description' => 'Maîtresse teinturière spécialisée dans la teinture naturelle à l\'indigo, Gnonhawa perpétue l\'art ancestral du bleu profond transmis par les femmes de Honvié.',
                'history' => 'Gnonhawa a grandi au milieu des cuves d\'indigo de sa grand-mère à Honvié, un quartier réputé pour sa tradition de teinture. Après avoir perfectionné sa technique lors de stages au Mali et au Niger, elle est revenue établir un atelier moderne qui allie procédés traditionnels et contrôle de la qualité. Elle forme désormais un groupe de jeunes femmes à la teinture indigo comme moyen d\'autonomisation économique.',
                'experience_years' => 20,
                'address' => 'Quartier Honvié, Porto-Novo',
                'latitude' => 6.5050,
                'longitude' => 2.6300,
                'status' => 'published',
                'savoir_faire' => $sfKanvo,
                'image_path' => 'images/artisans/gnonhawa_patience.png',
                'image_title' => 'Gnonhawa PATIENCE plongeant un tissu dans la cuve d\'indigo',
            ],
            [
                'user_email' => 'artisan7@dokun.bj',
                'first_name' => 'Bénédicte',
                'last_name' => 'HOUNSA',
                'professional_name' => 'Sculpture Féminine HOUNSA',
                'phone' => '+229 93 22 44 55',
                'whatsapp' => '+22993224455',
                'description' => 'Sculpteuse sur bois, Bénédicte se distingue par sa représentation féminine des fétiches et statuettes, apportant un regard nouveau et fort sur la tradition sculpturale.',
                'history' => 'Bénédicte a commencé la sculpture à Kpota à l\'âge de quinze ans, défiant les conventions qui réservaient ce métier aux hommes. En quinze ans, elle a développé un style reconnaissable qui met en valeur la figure féminine dans les masques et statuettes traditionnelles. Elle a participé à plusieurs biennales d\'art contemporain en Afrique de l\'Ouest et encadre des ateliers pour les jeunes filles du quartier.',
                'experience_years' => 15,
                'address' => 'Quartier Kpota, Porto-Novo',
                'latitude' => 6.4950,
                'longitude' => 2.6180,
                'status' => 'published',
                'savoir_faire' => $sfSculpture,
                'image_path' => 'images/artisans/benedicte_hounsa.png',
                'image_title' => 'Bénédicte HOUNSA sculptant une statuette féminine',
            ],
            [
                'user_email' => 'artisan8@dokun.bj',
                'first_name' => 'Damien',
                'last_name' => 'SOSSA',
                'professional_name' => 'Vannerie Moderne SOSSA',
                'phone' => '+229 98 66 77 44',
                'whatsapp' => '+22998667744',
                'description' => 'Vannier moderne qui réinvente les objets tressés en les adaptant aux besoins contemporains, Damien marie raphia, sisal et matériaux recyclés dans ses créations.',
                'history' => 'Damien a appris la vannerie à Agblangandan auprès de son oncle avant de se spécialiser dans des pièces à usage moderne — lampes, étagères, mobilier de jardin. En dix ans, il a construit une clientèle fidèle parmi les décorateurs d\'intérieur de Cotonou et de Porto-Novo. Il collabore régulièrement avec des architectes pour des projets hôteliers et résidentiels.',
                'experience_years' => 10,
                'address' => 'Quartier Agblangandan, Porto-Novo',
                'latitude' => 6.5100,
                'longitude' => 2.6340,
                'status' => 'published',
                'savoir_faire' => $sfVannerie,
                'image_path' => 'images/artisans/damien_sossa.png',
                'image_title' => 'Damien SOSSA tressant une lampe décorative en raphia',
            ],
        ];

        $artisans = [];

        foreach ($artisansData as $data) {
            $user = $users[$data['user_email']];

            $artisan = Artisan::firstOrCreate(
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
                    'category_id' => $data['savoir_faire']->category_id ?? null,
                ]
            );

            $artisan->savoirFaires()->syncWithoutDetaching([$data['savoir_faire']->id]);

            Media::firstOrCreate(
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

            $artisans[$data['user_email']] = $artisan;
        }

        // ─── Expériences ──────────────────────────────────────────
        $experiencesData = [
            [
                'artisan_email' => 'artisan1@dokun.bj',
                'title' => 'Initiation à la Poterie',
                'summary' => 'Apprenez les gestes ancestraux de la poterie aux côtés de Koffi : préparation de l\'argile, modelage au colombin et finition d\'un canari que vous repartirez avec vous.',
                'duration_minutes' => 120,
                'capacity' => 8,
                'price' => 5000,
                'image_path' => 'images/experiences/initiation_poterie.png',
            ],
            [
                'artisan_email' => 'artisan3@dokun.bj',
                'title' => 'Visite de l\'Atelier de Masques',
                'summary' => 'Découvrez l\'univers des masques Gèlèdè : histoire des symboles, démonstration de sculpteur et initiation aux premiers coups de ciseau sur bois tendre.',
                'duration_minutes' => 90,
                'capacity' => 6,
                'price' => 7500,
                'image_path' => 'images/experiences/atelier_masques.png',
            ],
            [
                'artisan_email' => 'artisan4@dokun.bj',
                'title' => 'Apprendre le Tissage Kanvo',
                'summary' => 'Maniez le métier à tisser traditionnel pour créer votre première bande de Kanvo sous la supervision de Messan, maître tisserand.',
                'duration_minutes' => 180,
                'capacity' => 4,
                'price' => 10000,
                'image_path' => 'images/experiences/tissage_kanvo.png',
            ],
            [
                'artisan_email' => 'artisan2@dokun.bj',
                'title' => 'Découvrir la Vannerie en Raphia',
                'summary' => 'Initiez-vous au tressage du raphia avec Yvette : apprenez à trier les fibres, à les préparer et à confectionner un petit panier traditionnel.',
                'duration_minutes' => 90,
                'capacity' => 10,
                'price' => 3000,
                'image_path' => 'images/experiences/vannerie_raphia.png',
            ],
            [
                'artisan_email' => 'artisan6@dokun.bj',
                'title' => 'Teinture à l\'Indigo',
                'summary' => 'Plongez dans l\'art millénaire de la teinture indigo : préparation de la cuve, trempage du tissu et découvrez les différents tons de bleu profond.',
                'duration_minutes' => 150,
                'capacity' => 6,
                'price' => 8000,
                'image_path' => 'images/experiences/teinture_indigo.png',
            ],
            [
                'artisan_email' => 'artisan5@dokun.bj',
                'title' => 'Poterie Moderne & Design',
                'summary' => 'Adjovi vous guide dans la création d\'une pièce de céramique moderne alliant formes contemporaines et savoir-faire traditionnel de Togba.',
                'duration_minutes' => 120,
                'capacity' => 8,
                'price' => 6000,
                'image_path' => 'images/experiences/poterie_moderne.png',
            ],
        ];

        foreach ($experiencesData as $exp) {
            $artisan = $artisans[$exp['artisan_email']];
            Experience::firstOrCreate(
                ['artisan_id' => $artisan->id, 'title' => $exp['title']],
                [
                    'summary' => $exp['summary'],
                    'duration_minutes' => $exp['duration_minutes'],
                    'capacity' => $exp['capacity'],
                    'price' => $exp['price'],
                    'currency' => 'XOF',
                    'language' => 'Français',
                    'image_path' => $exp['image_path'],
                    'is_published' => true,
                ]
            );
        }

        // ─── Utilisateurs testeurs pour les avis ──────────────────
        $reviewUsers = [];
        $reviewerData = [
            ['email' => 'marie.dupont@email.com', 'name' => 'Marie DUPONT'],
            ['email' => 'jean.baptiste@email.com', 'name' => 'Jean-Baptiste AMOUSSOU'],
            ['email' => 'sophia.rahman@email.com', 'name' => 'Sophia RAHMAN'],
            ['email' => 'antoine.lefevre@email.com', 'name' => 'Antoine LEFEVRE'],
        ];

        foreach ($reviewerData as $rd) {
            $reviewUsers[] = User::firstOrCreate(
                ['email' => $rd['email']],
                [
                    'name' => $rd['name'],
                    'password' => bcrypt('password'),
                    'role' => 'artisan',
                ]
            );
        }

        // ─── Reviews ──────────────────────────────────────────────
        $reviewsData = [
            [
                'user' => $reviewUsers[0],
                'artisan_email' => 'artisan1@dokun.bj',
                'rating' => 5,
                'comment' => 'Une expérience incroyable ! Koffi est un maître potier passionné qui sait transmettre son savoir avec patience. J\'ai créé mon premier canari et je suis très fier du résultat. Le quartier de Catchi est charmant.',
            ],
            [
                'user' => $reviewUsers[1],
                'artisan_email' => 'artisan3@dokun.bj',
                'rating' => 5,
                'comment' => 'La visite de l\'atelier de masques d\'Ayadji est un voyage dans l\'histoire. Les masques Gèlèdè qu\'il sculpte sont magnifiques. On comprend tout le symbolisme derrière chaque geste. À ne pas manquer.',
            ],
            [
                'user' => $reviewUsers[2],
                'artisan_email' => 'artisan4@dokun.bj',
                'rating' => 4,
                'comment' => 'Le tissage Kanvo avec Messan est un apprentissage complet. Le métier à tisser est impressionnant et le tissu que l\'on repart avec est magnifique. Seul bémol : les trois heures passent vite, j\'aurais aimé plus de temps.',
            ],
            [
                'user' => $reviewUsers[3],
                'artisan_email' => 'artisan6@dokun.bj',
                'rating' => 5,
                'comment' => 'Gnonhawa est une artiste de l\'indigo. La teinture naturelle produit des bleus d\'une profondeur unique. J\'ai appris des techniques que je ne connaissais pas du tout. Je recommande vivement cette expérience immersive.',
            ],
        ];

        foreach ($reviewsData as $rd) {
            Review::firstOrCreate(
                [
                    'user_id' => $rd['user']->id,
                    'artisan_id' => $artisans[$rd['artisan_email']]->id,
                ],
                [
                    'rating' => $rd['rating'],
                    'comment' => $rd['comment'],
                    'status' => 'published',
                    'moderated_by' => $admin->id,
                    'moderated_at' => now(),
                ]
            );
        }
    }
}
