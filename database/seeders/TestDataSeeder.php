<?php

namespace Database\Seeders;

use App\Models\ActorRequest;
use App\Models\Artisan;
use App\Models\ArtisanApplication;
use App\Models\ArtisanFavorite;
use App\Models\Badge;
use App\Models\Experience;
use App\Models\LearnCourse;
use App\Models\LearnLesson;
use App\Models\LearnProgress;
use App\Models\LoyaltyEvent;
use App\Models\LoyaltySummary;
use App\Models\Moment;
use App\Models\ReservationRequest;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        $password = bcrypt('password');

        // ═══════════════════════════════════════════════════════════
        // TOURISTES
        // ═══════════════════════════════════════════════════════════
        $touristes = [];
        $touristeData = [
            ['email' => 'jean.touriste@email.com',   'name' => 'Jean-Pierre TOURE'],
            ['email' => 'marie.visiteur@email.com',   'name' => 'Marie CLAIRE'],
            ['email' => 'paul.explorer@email.com',    'name' => 'Paul AKPlogan'],
            ['email' => 'fatouma.guide@email.com',    'name' => 'Fatouma IBRAHIM'],
            ['email' => 'lucas.photo@email.com',      'name' => 'Lucas BENFRECH'],
        ];

        foreach ($touristeData as $td) {
            $touristes[$td['email']] = User::firstOrCreate(
                ['email' => $td['email']],
                [
                    'name' => $td['name'],
                    'password' => $password,
                    'role' => 'tourist',
                    'email_verified_at' => now(),
                ]
            );
        }

        // ═══════════════════════════════════════════════════════════
        // CANDIDATURES ARTISANS (pending / approved / rejected)
        // ═══════════════════════════════════════════════════════════
        $catArtisanat = \App\Models\Category::where('slug', 'artisanat-creation')->first();
        $catTextile   = \App\Models\Category::where('slug', 'textile-tissage')->first();

        $applications = [
            [
                'user' => User::firstOrCreate(
                    ['email' => 'candidat.pending@email.com'],
                    ['name' => 'Kossi AGUEHO', 'password' => $password, 'role' => 'tourist', 'email_verified_at' => now()]
                ),
                'first_name' => 'Kossi',
                'last_name' => 'AGUEHO',
                'professional_name' => 'Atelier Agueho',
                'phone' => '+229 91 00 11 22',
                'whatsapp' => '+22991001122',
                'description' => 'Jeune potier passionné par les formes modernes.',
                'experience_years' => 3,
                'address' => 'Quartier Togba, Porto-Novo',
                'category_id' => $catArtisanat?->id,
                'trade' => 'Potière',
                'status' => 'pending',
            ],
            [
                'user' => User::firstOrCreate(
                    ['email' => 'candidat.approved@email.com'],
                    ['name' => 'Amina SIDIBE', 'password' => $password, 'role' => 'tourist', 'email_verified_at' => now()]
                ),
                'first_name' => 'Amina',
                'last_name' => 'SIDIBE',
                'professional_name' => 'Tissage Sidibe',
                'phone' => '+229 92 33 44 55',
                'whatsapp' => '+22992334455',
                'description' => 'Tisserand expert en pagne traditionnel.',
                'experience_years' => 8,
                'address' => 'Quartier Guèvié, Porto-Novo',
                'category_id' => $catTextile?->id,
                'trade' => 'Tisserand',
                'status' => 'approved',
                'reviewed_at' => now()->subDays(2),
            ],
            [
                'user' => User::firstOrCreate(
                    ['email' => 'candidat.rejected@email.com'],
                    ['name' => 'Ibrahim DIALLO', 'password' => $password, 'role' => 'tourist', 'email_verified_at' => now()]
                ),
                'first_name' => 'Ibrahim',
                'last_name' => 'DIALLO',
                'professional_name' => 'Bois Diallo',
                'phone' => '+229 93 66 77 88',
                'description' => 'Menuisier semi-professionnel.',
                'experience_years' => 2,
                'address' => 'Quartier Kpota, Porto-Novo',
                'category_id' => $catArtisanat?->id,
                'trade' => 'Menuisier',
                'status' => 'rejected',
                'admin_notes' => 'Expérience insuffisante pour le moment. Réessayer dans 6 mois.',
                'reviewed_at' => now()->subDays(5),
            ],
        ];

        foreach ($applications as $appData) {
            $user = $appData['user'];
            unset($appData['user']);
            ArtisanApplication::firstOrCreate(
                ['user_id' => $user->id],
                $appData
            );
        }

        // ═══════════════════════════════════════════════════════════
        // DEMANDES D'ACTEURS (guide / institution / partner)
        // ═══════════════════════════════════════════════════════════
        ActorRequest::firstOrCreate(
            ['email' => 'guide.pending@email.com'],
            [
                'role' => 'guide',
                'name' => 'Chantal HOUNGNIBO',
                'phone' => '+229 94 11 22 33',
                'organization' => 'Indépendante',
                'motivation' => 'Guide touristique certifiée, je souhaite promouvoir le patrimoine béninois via la plateforme.',
                'status' => 'pending',
            ]
        );

        $institutionUser = User::firstOrCreate(
            ['email' => 'institution.approved@email.com'],
            ['name' => 'Fondation HOZO', 'password' => $password, 'role' => 'tourist', 'email_verified_at' => now()]
        );
        ActorRequest::firstOrCreate(
            ['email' => 'institution.approved@email.com'],
            [
                'role' => 'institution',
                'name' => 'Fondation HOZO',
                'phone' => '+229 95 44 55 66',
                'organization' => 'Fondation HOZO pour la culture',
                'motivation' => 'Institution culturelle de renom, nous voulons partenaires avec ƉƆKUN pour numériser nos expositions.',
                'status' => 'approved',
                'reviewed_at' => now()->subDays(10),
            ]
        );

        ActorRequest::firstOrCreate(
            ['email' => 'partner.rejected@email.com'],
            [
                'role' => 'partner',
                'name' => 'Marc LEGRAND',
                'phone' => '+33 6 12 34 56 78',
                'organization' => 'TravelTech France',
                'motivation' => 'Nous voulons distribuer les expériences ƉƆKUN sur notre plateforme européenne.',
                'status' => 'rejected',
                'admin_notes' => 'Partenariat non pertinent pour le moment.',
                'reviewed_at' => now()->subDays(3),
            ]
        );

        // ═══════════════════════════════════════════════════════════
        // RÉSERVATIONS (tous statuts)
        // ═══════════════════════════════════════════════════════════
        $artisans = Artisan::with('user')->get();
        $experiences = Experience::all();

        if ($artisans->count() === 0 || $experiences->count() === 0) {
            $this->command->warn('⚠ Aucun artisan ou expérience trouvé. Les données dépendantes seront ignorées.');
        }

        $reservationsData = [
            // ─── PENDING (3) ───
            [
                'tourist_email' => 'jean.touriste@email.com',
                'artisan' => $artisans->first(),
                'experience' => $experiences->first(),
                'status' => 'pending',
                'requested_date' => now()->addDays(14),
                'guests_count' => 2,
                'total_amount' => 10000,
                'payment_method' => 'mobile_money',
                'payment_status' => 'paid',
                'message' => 'Nous sommes deux et très impatients !',
            ],
            [
                'tourist_email' => 'marie.visiteur@email.com',
                'artisan' => $artisans->skip(1)->first(),
                'experience' => $experiences->skip(1)->first(),
                'status' => 'pending',
                'requested_date' => now()->addDays(20),
                'guests_count' => 1,
                'total_amount' => 7500,
                'payment_method' => 'pay_on_site',
                'payment_status' => 'not_required',
                'message' => 'Première visite au Bénin, hâte de découvrir !',
            ],
            [
                'tourist_email' => 'paul.explorer@email.com',
                'artisan' => $artisans->skip(2)->first(),
                'experience' => $experiences->skip(2)->first(),
                'status' => 'pending',
                'requested_date' => now()->addDays(7),
                'guests_count' => 4,
                'total_amount' => 40000,
                'payment_method' => 'mobile_money',
                'payment_status' => 'pending',
                'message' => 'Groupe de 4 amis aventuriers.',
            ],
            // ─── ACCEPTED (3) ───
            [
                'tourist_email' => 'fatouma.guide@email.com',
                'artisan' => $artisans->first(),
                'experience' => $experiences->first(),
                'status' => 'accepted',
                'requested_date' => now()->addDays(5),
                'guests_count' => 2,
                'total_amount' => 10000,
                'payment_method' => 'mobile_money',
                'payment_status' => 'paid',
            ],
            [
                'tourist_email' => 'lucas.photo@email.com',
                'artisan' => $artisans->skip(3)->first(),
                'experience' => $experiences->skip(3)->first(),
                'status' => 'accepted',
                'requested_date' => now()->addDays(3),
                'guests_count' => 1,
                'total_amount' => 3000,
                'payment_method' => 'mobile_money',
                'payment_status' => 'paid',
            ],
            [
                'tourist_email' => 'jean.touriste@email.com',
                'artisan' => $artisans->skip(4)->first(),
                'experience' => $experiences->skip(4)->first(),
                'status' => 'accepted',
                'requested_date' => now()->subDays(2),
                'guests_count' => 3,
                'total_amount' => 24000,
                'payment_method' => 'mobile_money',
                'payment_status' => 'paid',
            ],
            // ─── COMPLETED (3) ───
            [
                'tourist_email' => 'marie.visiteur@email.com',
                'artisan' => $artisans->first(),
                'experience' => $experiences->first(),
                'status' => 'completed',
                'requested_date' => now()->subDays(15),
                'guests_count' => 2,
                'total_amount' => 10000,
                'payment_method' => 'mobile_money',
                'payment_status' => 'paid',
            ],
            [
                'tourist_email' => 'paul.explorer@email.com',
                'artisan' => $artisans->skip(2)->first(),
                'experience' => $experiences->skip(2)->first(),
                'status' => 'completed',
                'requested_date' => now()->subDays(30),
                'guests_count' => 1,
                'total_amount' => 10000,
                'payment_method' => 'mobile_money',
                'payment_status' => 'paid',
            ],
            [
                'tourist_email' => 'fatouma.guide@email.com',
                'artisan' => $artisans->skip(1)->first(),
                'experience' => $experiences->skip(1)->first(),
                'status' => 'completed',
                'requested_date' => now()->subDays(20),
                'guests_count' => 2,
                'total_amount' => 15000,
                'payment_method' => 'pay_on_site',
                'payment_status' => 'not_required',
            ],
            // ─── REJECTED (3) ───
            [
                'tourist_email' => 'lucas.photo@email.com',
                'artisan' => $artisans->skip(5)->first(),
                'experience' => $experiences->skip(5)->first(),
                'status' => 'rejected',
                'requested_date' => now()->subDays(10),
                'guests_count' => 1,
                'total_amount' => 6000,
                'payment_method' => 'mobile_money',
                'payment_status' => 'failed',
            ],
            [
                'tourist_email' => 'jean.touriste@email.com',
                'artisan' => $artisans->skip(3)->first(),
                'experience' => $experiences->skip(2)->first(),
                'status' => 'rejected',
                'requested_date' => now()->subDays(25),
                'guests_count' => 6,
                'total_amount' => 60000,
                'payment_method' => 'pay_on_site',
                'payment_status' => 'not_required',
            ],
            [
                'tourist_email' => 'marie.visiteur@email.com',
                'artisan' => $artisans->skip(4)->first(),
                'experience' => $experiences->first(),
                'status' => 'rejected',
                'requested_date' => now()->subDays(5),
                'guests_count' => 1,
                'total_amount' => 5000,
                'payment_method' => 'mobile_money',
                'payment_status' => 'failed',
            ],
        ];

        $reservations = [];
        if ($artisans->count() > 0 && $experiences->count() > 0) {
        foreach ($reservationsData as $rd) {
            $tourist = $touristes[$rd['tourist_email']] ?? User::where('email', $rd['tourist_email'])->first();
            if (!$tourist) continue;

            unset($rd['tourist_email']);
            $rd['user_id'] = $tourist->id;
            $rd['artisan_id'] = $rd['artisan']->id;
            $rd['experience_id'] = $rd['experience']->id;
            $rd['visitor_name'] = $tourist->name;
            $rd['visitor_phone'] = '+229 9' . rand(10, 99) . ' ' . rand(10, 99) . ' ' . rand(10, 99);
            $rd['visitor_email'] = $tourist->email;
            $rd['experience_type'] = $rd['experience']->title;
            $rd['currency'] = 'XOF';
            $rd['reference'] = 'DKN-' . strtoupper(Str::random(8));
            $rd['qr_code_token'] = Str::random(40);

            unset($rd['artisan'], $rd['experience']);

            $reservations[] = ReservationRequest::firstOrCreate(
                ['reference' => $rd['reference']],
                $rd
            );
        }
        } // end if artisans+experiences

        // ═══════════════════════════════════════════════════════════
        // AVIS SUPPLÉMENTAIRES
        // ═══════════════════════════════════════════════════════════
        $completedReservations = array_filter($reservations, fn($r) => $r->status === 'completed');
        $reviewComments = [
            'Une expérience magique. L\'artisan est passionné et le résultat est bluffant.',
            'J\'ai adoré chaque minute. Le quartier est charmant et l\'atelier est un vrai joyau.',
            'Un peu court mais très instructif. Je recommande pour découvrir la culture béninoise.',
            'Inoubliable. Les gestes ancestraux m\'ont profondément touché.',
        ];

        $i = 0;
        foreach ($completedReservations as $res) {
            Review::firstOrCreate(
                [
                    'user_id' => $res->user_id,
                    'artisan_id' => $res->artisan_id,
                ],
                [
                    'reservation_request_id' => $res->id,
                    'rating' => rand(4, 5),
                    'comment' => $reviewComments[$i % count($reviewComments)],
                    'status' => 'published',
                    'moderated_at' => now(),
                ]
            );
            $i++;
        }

        // ═══════════════════════════════════════════════════════════
        // MOMENTS (shorts post-expérience)
        // ═══════════════════════════════════════════════════════════
        $momentsData = [
            [
                'tourist_email' => 'marie.visiteur@email.com',
                'artisan' => $artisans->first(),
                'reservation' => $completedReservations[0] ?? null,
                'title' => 'Mon premier canari en argile rouge',
                'description' => 'Avec Koffi DOSSOU, j\'ai appris à modeler l\'argile comme ses ancêtres. Une expérience inoubliable !',
                'status' => 'published',
            ],
            [
                'tourist_email' => 'paul.explorer@email.com',
                'artisan' => $artisans->skip(2)->first(),
                'reservation' => $completedReservations[1] ?? null,
                'title' => 'Les masques Gèlèdè ont du caractère',
                'description' => 'Sculpter un masque avec Ayadji, c\'est toucher à 3000 ans d\'histoire. Chaque coup de ciseau raconte une légende.',
                'status' => 'published',
            ],
            [
                'tourist_email' => 'fatouma.guide@email.com',
                'artisan' => $artisans->skip(1)->first(),
                'reservation' => $completedReservations[2] ?? null,
                'title' => 'L\'indigo de Gnonhawa est hypnotisant',
                'description' => 'Le bleu profond de l\'indigo naturel. Gnonhawa est une vraie artiste de la teinture.',
                'status' => 'pending',
            ],
            [
                'tourist_email' => 'jean.touriste@email.com',
                'artisan' => $artisans->skip(3)->first(),
                'reservation' => null,
                'title' => 'Le Kanvo royal de Messan',
                'description' => 'Tisser le pagne Kanvo, c\'est écrire l\'histoire du Bénin avec du fil.',
                'status' => 'rejected',
            ],
        ];

        foreach ($momentsData as $md) {
            $tourist = $touristes[$md['tourist_email']] ?? User::where('email', $md['tourist_email'])->first();
            if (!$tourist) continue;

            Moment::firstOrCreate(
                [
                    'user_id' => $tourist->id,
                    'artisan_id' => $md['artisan']->id,
                ],
                [
                    'reservation_request_id' => $md['reservation']?->id,
                    'title' => $md['title'],
                    'description' => $md['description'],
                    'status' => $md['status'],
                    'share_token' => Str::random(40),
                    'moderated_at' => $md['status'] !== 'pending' ? now() : null,
                ]
            );
        }

        // ═══════════════════════════════════════════════════════════
        // FAVORIS
        // ═══════════════════════════════════════════════════════════
        $favoritesMap = [
            'jean.touriste@email.com'   => [0, 2, 4],
            'marie.visiteur@email.com'  => [0, 1],
            'paul.explorer@email.com'   => [1, 3, 5],
            'fatouma.guide@email.com'   => [0, 2],
            'lucas.photo@email.com'     => [3, 4],
        ];

        foreach ($favoritesMap as $email => $artisanIndices) {
            $tourist = $touristes[$email] ?? User::where('email', $email)->first();
            if (!$tourist) continue;

            foreach ($artisanIndices as $idx) {
                $artisan = $artisans->get($idx);
                if (!$artisan) continue;

                ArtisanFavorite::firstOrCreate(
                    [
                        'user_id' => $tourist->id,
                        'artisan_id' => $artisan->id,
                    ]
                );
            }
        }

        // ═══════════════════════════════════════════════════════════
        // PROGRESSION LEARN
        // ═══════════════════════════════════════════════════════════
        $courses = LearnCourse::with('lessons')->get();

        if ($courses->isNotEmpty()) {
            $progressData = [
                ['email' => 'jean.touriste@email.com',   'course_idx' => 0, 'lesson_idx' => 0, 'score' => 90, 'completed' => true],
                ['email' => 'jean.touriste@email.com',   'course_idx' => 0, 'lesson_idx' => 1, 'score' => 75, 'completed' => true],
                ['email' => 'marie.visiteur@email.com',  'course_idx' => 0, 'lesson_idx' => 0, 'score' => 100, 'completed' => true],
                ['email' => 'paul.explorer@email.com',   'course_idx' => 0, 'lesson_idx' => 0, 'score' => 60, 'completed' => false],
                ['email' => 'paul.explorer@email.com',   'course_idx' => 0, 'lesson_idx' => 1, 'score' => 85, 'completed' => true],
                ['email' => 'fatouma.guide@email.com',   'course_idx' => 0, 'lesson_idx' => 0, 'score' => 95, 'completed' => true],
                ['email' => 'lucas.photo@email.com',     'course_idx' => 0, 'lesson_idx' => 0, 'score' => 70, 'completed' => false],
                ['email' => 'lucas.photo@email.com',     'course_idx' => 0, 'lesson_idx' => 1, 'score' => 80, 'completed' => true],
            ];

            foreach ($progressData as $pd) {
                $tourist = $touristes[$pd['email']] ?? User::where('email', $pd['email'])->first();
                $course = $courses->get($pd['course_idx']);
                if (!$tourist || !$course) continue;

                $lesson = $course->lessons->get($pd['lesson_idx']);
                if (!$lesson) continue;

                LearnProgress::firstOrCreate(
                    [
                        'user_id' => $tourist->id,
                        'lesson_id' => $lesson->id,
                    ],
                    [
                        'best_score' => $pd['score'],
                        'completed_at' => $pd['completed'] ? now()->subDays(rand(1, 30)) : null,
                    ]
                );
            }
        }

        // ═══════════════════════════════════════════════════════════
        // ÉVÉNEMENTS FIDÉLITÉ
        // ═══════════════════════════════════════════════════════════
        $loyaltyEvents = [
            ['email' => 'jean.touriste@email.com',   'code' => 'reservation_completed', 'points' => 50,  'meta' => ['reservation_id' => null]],
            ['email' => 'jean.touriste@email.com',   'code' => 'review_published',      'points' => 20,  'meta' => []],
            ['email' => 'jean.touriste@email.com',   'code' => 'lesson_completed',       'points' => 10,  'meta' => ['course' => 'Salutations']],
            ['email' => 'marie.visiteur@email.com',  'code' => 'reservation_completed', 'points' => 50,  'meta' => []],
            ['email' => 'marie.visiteur@email.com',  'code' => 'review_published',      'points' => 20,  'meta' => []],
            ['email' => 'marie.visiteur@email.com',  'code' => 'lesson_completed',       'points' => 10,  'meta' => ['course' => 'Salutations']],
            ['email' => 'paul.explorer@email.com',   'code' => 'reservation_completed', 'points' => 50,  'meta' => []],
            ['email' => 'paul.explorer@email.com',   'code' => 'lesson_completed',       'points' => 10,  'meta' => ['course' => 'Salutations']],
            ['email' => 'fatouma.guide@email.com',   'code' => 'reservation_completed', 'points' => 50,  'meta' => []],
            ['email' => 'fatouma.guide@email.com',   'code' => 'lesson_completed',       'points' => 10,  'meta' => ['course' => 'Salutations']],
        ];

        foreach ($loyaltyEvents as $le) {
            $tourist = $touristes[$le['email']] ?? User::where('email', $le['email'])->first();
            if (!$tourist) continue;

            // Prevent duplicates
            $exists = LoyaltyEvent::where('user_id', $tourist->id)
                ->where('code', $le['code'])
                ->exists();

            if (!$exists) {
                LoyaltyEvent::create([
                    'user_id' => $tourist->id,
                    'code' => $le['code'],
                    'points' => $le['points'],
                    'meta' => $le['meta'],
                ]);
            }
        }

        // ═══════════════════════════════════════════════════════════
        // RÉSUMÉS FIDÉLITÉ
        // ═══════════════════════════════════════════════════════════
        foreach ($touristes as $tourist) {
            $totalPoints = LoyaltyEvent::where('user_id', $tourist->id)->sum('points');

            LoyaltySummary::firstOrCreate(
                ['user_id' => $tourist->id],
                [
                    'total_points' => $totalPoints,
                    'streak_days' => rand(0, 15),
                    'last_activity_date' => now()->subDays(rand(0, 7)),
                ]
            );
        }

        // ═══════════════════════════════════════════════════════════
        // BADGES (attribution partielle)
        // ═══════════════════════════════════════════════════════════
        $badges = Badge::all();

        if ($badges->isNotEmpty()) {
            // Jean : 2 badges
            $jean = $touristes['jean.touriste@email.com'] ?? User::where('email', 'jean.touriste@email.com')->first();
            if ($jean && $badges->count() >= 2) {
                $jean->badges()->attach($badges->first()->id, ['earned_at' => now()->subDays(10)]);
                $jean->badges()->attach($badges->skip(1)->first()->id, ['earned_at' => now()->subDays(5)]);
            }

            // Marie : 3 badges
            $marie = $touristes['marie.visiteur@email.com'] ?? User::where('email', 'marie.visiteur@email.com')->first();
            if ($marie && $badges->count() >= 3) {
                $marie->badges()->attach($badges->first()->id, ['earned_at' => now()->subDays(20)]);
                $marie->badges()->attach($badges->skip(1)->first()->id, ['earned_at' => now()->subDays(12)]);
                $marie->badges()->attach($badges->skip(2)->first()->id, ['earned_at' => now()->subDays(3)]);
            }

            // Paul : 1 badge
            $paul = $touristes['paul.explorer@email.com'] ?? User::where('email', 'paul.explorer@email.com')->first();
            if ($paul && $badges->isNotEmpty()) {
                $paul->badges()->attach($badges->first()->id, ['earned_at' => now()->subDays(8)]);
            }
        }

        $this->command->info('✅ TestDataSeeder : données de test insérées avec succès !');
        $this->command->info('   - ' . count($touristes) . ' touristes');
        $this->command->info('   - ' . count($applications) . ' candidatures artisans');
        $this->command->info('   - 3 demandes acteurs');
        $this->command->info('   - ' . count($reservations) . ' réservations (tous statuts)');
        $this->command->info('   - 4 avis supplémentaires');
        $this->command->info('   - 4 moments');
        $this->command->info('   - ' . count($favoritesMap) . ' groupes de favoris');
        $this->command->info('   - 8 progressions learn');
        $this->command->info('   - 10 événements fidélité');
        $this->command->info('   - Badges attribués');
    }
}
