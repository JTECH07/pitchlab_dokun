<?php

namespace Tests\Feature;

use App\Models\Artisan;
use App\Models\Moment;
use App\Models\ReservationRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class MomentFeatureTest extends TestCase
{
    use RefreshDatabase;

    private function completedReservation(User $user): ReservationRequest
    {
        $artisan = Artisan::factory()->create(['status' => 'published']);
        return ReservationRequest::factory()->create([
            'user_id' => $user->id,
            'artisan_id' => $artisan->id,
            'status' => 'completed',
        ]);
    }

    public function test_public_feed_shows_published_moments(): void
    {
        $user = User::factory()->create(['role' => 'tourist']);
        $reservation = $this->completedReservation($user);

        $published = Moment::create([
            'user_id' => $user->id,
            'reservation_request_id' => $reservation->id,
            'artisan_id' => $reservation->artisan_id,
            'title' => 'Mon atelier poterie',
            'status' => 'published',
        ]);
        Moment::create([
            'user_id' => $user->id,
            'reservation_request_id' => $reservation->id,
            'artisan_id' => $reservation->artisan_id,
            'title' => 'En attente',
            'status' => 'pending',
        ]);

        $this->get(route('moments.index'))
            ->assertOk()
            ->assertSee('Mon atelier poterie')
            ->assertDontSee('En attente');
    }

    public function test_member_can_create_moment_for_completed_reservation_and_earn_xp(): void
    {
        $user = User::factory()->create(['role' => 'tourist']);
        $reservation = $this->completedReservation($user);

        $this->actingAs($user)
            ->get(route('moments.create', $reservation->qr_code_token))
            ->assertOk();

        $this->actingAs($user)
            ->post(route('moments.store'), [
                'reservation_request_id' => $reservation->id,
                'title' => 'Une journée mémorable à l\'atelier',
                'description' => 'La poterie de Porto-Novo est fascinante.',
            ])
            ->assertRedirect();

        $moment = Moment::where('user_id', $user->id)->first();
        $this->assertNotNull($moment);
        $this->assertEquals('pending', $moment->status);
        $this->assertDatabaseHas('loyalty_events', [
            'user_id' => $user->id,
            'code' => 'moment_shared',
            'points' => 30,
        ]);
    }

    public function test_duplicate_moment_for_same_reservation_is_blocked(): void
    {
        $user = User::factory()->create(['role' => 'tourist']);
        $reservation = $this->completedReservation($user);

        $this->actingAs($user)->post(route('moments.store'), [
            'reservation_request_id' => $reservation->id,
            'title' => 'Premier souvenir',
            'description' => 'Première description de souvenir.',
        ])->assertRedirect();

        $this->actingAs($user)->post(route('moments.store'), [
            'reservation_request_id' => $reservation->id,
            'title' => 'Second souvenir',
            'description' => 'Seconde description de souvenir.',
        ])->assertStatus(403);
    }

    public function test_admin_can_moderate_moment(): void
    {
        $user = User::factory()->create(['role' => 'tourist']);
        $reservation = $this->completedReservation($user);
        $moment = Moment::create([
            'user_id' => $user->id,
            'reservation_request_id' => $reservation->id,
            'artisan_id' => $reservation->artisan_id,
            'title' => 'À modérer',
            'status' => 'pending',
        ]);

        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->patch(route('admin.moments.moderate', $moment->id), ['action' => 'published'])
            ->assertRedirect();

        $this->assertEquals('published', $moment->fresh()->status);
    }
}
