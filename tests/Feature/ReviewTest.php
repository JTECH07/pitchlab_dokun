<?php

namespace Tests\Feature;

use App\Models\Artisan;
use App\Models\ReservationRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewTest extends TestCase
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

    public function test_user_can_submit_review_and_earn_loyalty(): void
    {
        $user = User::factory()->create(['role' => 'tourist']);
        $reservation = $this->completedReservation($user);

        $this->actingAs($user)->post(route('reviews.store'), [
            'reservation_request_id' => $reservation->id,
            'rating' => 5,
            'comment' => 'Une expérience exceptionnelle et authentique.',
        ])->assertRedirect(route('artisans.show', $reservation->artisan_id));

        $this->assertDatabaseHas('reviews', [
            'user_id' => $user->id, 'rating' => 5, 'status' => 'pending',
        ]);
        $this->assertDatabaseHas('loyalty_events', [
            'user_id' => $user->id, 'code' => 'review_published',
        ]);
    }

    public function test_duplicate_review_is_blocked(): void
    {
        $user = User::factory()->create(['role' => 'tourist']);
        $reservation = $this->completedReservation($user);

        $this->actingAs($user)->post(route('reviews.store'), [
            'reservation_request_id' => $reservation->id,
            'rating' => 5,
            'comment' => 'Premier avis très positif sur cette expérience.',
        ]);

        $this->actingAs($user)->post(route('reviews.store'), [
            'reservation_request_id' => $reservation->id,
            'rating' => 4,
            'comment' => 'Second avis maintenant sur cette expérience.',
        ])->assertStatus(403);
    }
}
