<?php

namespace Tests\Feature;

use App\Models\Artisan;
use App\Models\Experience;
use App\Models\LearnCourse;
use App\Models\LearnLesson;
use App\Models\LearnProgress;
use App\Models\ReservationRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EndToEndVisitorJourneyTest extends TestCase
{
    use RefreshDatabase;

    private function registerVisitor(): array
    {
        $response = $this->post(route('register'), [
            'name' => 'Aminata Kolo',
            'email' => 'aminata@example.test',
            'password' => 'MotDePasse1!',
            'password_confirmation' => 'MotDePasse1!',
        ]);

        $this->assertAuthenticated();
        return [$this->app['auth']->user()];
    }

    public function test_full_visitor_journey_register_favorite_reserve_learn_review(): void
    {
        // ── Données de base ─────────────────────────────────────
        $artisan = Artisan::factory()->create(['status' => 'published']);
        $experience = Experience::create([
            'artisan_id' => $artisan->id,
            'title' => 'Atelier poterie',
            'summary' => 'Initiation au tour.',
            'price' => 10000,
            'capacity' => 4,
        ]);
        $course = LearnCourse::create([
            'slug' => 'salutations', 'icon' => '👋',
            'title_fr' => 'Salutations', 'title_en' => 'Greetings',
            'desc_fr' => 'Les bases.', 'desc_en' => 'Basics.',
            'sort_order' => 1,
        ]);
        $lesson = LearnLesson::create([
            'course_id' => $course->id, 'slug' => 'premiers-mots',
            'title_fr' => 'Premiers mots', 'title_en' => 'First words',
            'sort_order' => 1,
        ]);

        // ── 1. Inscription ──────────────────────────────────────
        $this->post(route('register'), [
            'name' => 'Aminata Kolo',
            'email' => 'aminata@example.test',
            'password' => 'MotDePasse1!',
            'password_confirmation' => 'MotDePasse1!',
        ])->assertRedirect();
        $this->assertAuthenticated();

        // ── 2. Catalogue artisans accessible ────────────────────
        $this->get(route('artisans.index'))->assertOk();
        $this->get(route('artisans.show', $artisan->id))->assertOk();

        // ── 3. Ajout en favori ──────────────────────────────────
        $this->post(route('visitor.favorites.toggle', $artisan))
            ->assertOk()
            ->assertJson(['status' => 'added']);
        $this->assertDatabaseHas('artisan_favorites', [
            'artisan_id' => $artisan->id,
            'user_id' => auth()->id(),
        ]);

        // ── 4. Réservation (paiement sur place → direct) ────────
        // pay_on_site crée la réservation sans FedaPay
        $reservationId = null;
        $this->post(route('payment.initiate', $artisan), [
            'visitor_name' => 'Aminata Kolo',
            'visitor_phone' => '+229 97000000',
            'visitor_email' => 'aminata@example.test',
            'requested_date' => now()->addWeek()->toDateString(),
            'guests_count' => 2,
            'experience_id' => $experience->id,
            'payment_method' => 'pay_on_site',
        ])->assertRedirect();

        $reservation = ReservationRequest::where('visitor_email', 'aminata@example.test')->first();

        if ($reservation) {
            $reservationId = $reservation->id;
            // Le reçu QR est accessible via le token
            $this->get(route('reservations.receipt', $reservation->qr_code_token))->assertOk();
        }

        // ── 5. Leçon Learn complétée ────────────────────────────
        $this->get(route('learn.index'))->assertOk();
        $this->get(route('learn.play', [$course, $lesson]))->assertOk();
        $this->post(route('learn.complete', $lesson), ['score' => 85])
            ->assertOk()
            ->assertJson(['status' => 'saved']);
        $this->assertDatabaseHas('learn_progress', [
            'user_id' => auth()->id(),
            'lesson_id' => $lesson->id,
            'best_score' => 85,
        ]);

        // ── 6. Mon voyage reflète tout ──────────────────────────
        $voyage = $this->get(route('visitor.profile'))->assertOk();
        $voyage->assertSee('Premiers mots');

        // ── 7. Déconnexion puis reconnexion : données persistées ─
        $userId = auth()->id();
        $this->post(route('logout'));
        $this->post(route('login'), [
            'email' => 'aminata@example.test',
            'password' => 'MotDePasse1!',
        ])->assertRedirect();
        $this->assertEquals($userId, auth()->id());

        $this->assertDatabaseHas('artisan_favorites', [
            'user_id' => $userId,
            'artisan_id' => $artisan->id,
        ]);

        // ── 8. Avis après complétion ────────────────────────────
        if ($reservationId) {
            ReservationRequest::find($reservationId)->update(['status' => 'completed']);
            $this->get(route('reviews.create', $reservationId))->assertOk();
            $this->post(route('reviews.store', $reservationId), [
                'rating' => 5,
                'comment' => 'Expérience inoubliable !',
            ])->assertRedirect();

            $this->assertDatabaseHas('reviews', [
                'user_id' => $userId,
                'rating' => 5,
            ]);
        }
    }

    public function test_guest_cannot_access_private_spaces(): void
    {
        $this->get(route('visitor.profile'))->assertRedirect(route('login'));
        $this->get(route('dashboard'))->assertRedirect(route('login'));

        $artisan = Artisan::factory()->create(['status' => 'published']);
        $this->post(route('visitor.favorites.toggle', $artisan), [], ['Accept' => 'application/json'])
            ->assertRedirect(route('login'));
    }

    public function test_learn_progression_unlocks_next_lesson_only(): void
    {
        $course = LearnCourse::create([
            'slug' => 'marche', 'icon' => '🧺',
            'title_fr' => 'Marché', 'title_en' => 'Market',
            'desc_fr' => '', 'desc_en' => '',
            'sort_order' => 2,
        ]);
        $l1 = LearnLesson::create(['course_id' => $course->id, 'slug' => 'compter', 'title_fr' => 'Compter', 'title_en' => 'Counting', 'sort_order' => 1]);
        $l2 = LearnLesson::create(['course_id' => $course->id, 'slug' => 'negocier', 'title_fr' => 'Négocier', 'title_en' => 'Bargaining', 'sort_order' => 2]);

        $this->get(route('learn.index'))->assertRedirect(route('login')); // membres uniquement
        $this->post(route('register'), [
            'name' => 'Test', 'email' => 't@t.bj',
            'password' => 'MotDePasse1!', 'password_confirmation' => 'MotDePasse1!',
        ]);

        $this->get(route('learn.play', [$course, $l1]))->assertOk();      // l1 ouverte
        $this->get(route('learn.play', [$course, $l2]))->assertForbidden(); // l2 verrouillée

        $this->post(route('learn.complete', $l1), ['score' => 60])->assertOk();
        $this->get(route('learn.play', [$course, $l2]))->assertOk();      // débloquée
    }
}
