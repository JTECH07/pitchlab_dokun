<?php

namespace Tests\Feature;

use App\Models\Artisan;
use App\Models\Experience;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_visitor_can_request_a_priced_experience(): void
    {
        $artisan = Artisan::factory()->create(['status' => 'published']);
        $experience = Experience::create([
            'artisan_id' => $artisan->id,
            'title' => 'Tissage du Kanvo',
            'summary' => 'Atelier d’initiation.',
            'price' => 15000,
            'capacity' => 5,
        ]);

        $response = $this->post(route('reservations.store', $artisan), [
            'visitor_name' => 'Aminata K.',
            'visitor_phone' => '+229 97000000',
            'visitor_email' => 'aminata@example.test',
            'requested_date' => now()->addWeek()->toDateString(),
            'guests_count' => 2,
            'experience_id' => $experience->id,
            'payment_method' => 'pay_on_site',
        ]);

        $response->assertRedirect(route('artisans.show', $artisan));
        $this->assertDatabaseHas('reservation_requests', [
            'artisan_id' => $artisan->id,
            'experience_id' => $experience->id,
            'experience_type' => 'Tissage du Kanvo',
            'total_amount' => 30000,
            'payment_status' => 'not_required',
            'status' => 'pending',
        ]);
    }
}
