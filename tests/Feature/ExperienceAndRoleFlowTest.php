<?php

namespace Tests\Feature;

use App\Models\Artisan;
use App\Models\Experience;
use App\Models\ReservationRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExperienceAndRoleFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_can_browse_experiences(): void
    {
        $artisan = Artisan::factory()->create(['status' => 'published']);
        Experience::create(['artisan_id' => $artisan->id, 'title' => 'Initiation à la poterie', 'summary' => 'Un atelier de découverte.', 'price' => 12000]);

        $this->get(route('experiences.index'))->assertOk()->assertSee('Initiation à la poterie');
    }

    public function test_artisan_is_redirected_to_its_professional_space(): void
    {
        $artisanUser = User::factory()->create(['role' => 'artisan']);
        Artisan::factory()->create(['user_id' => $artisanUser->id]);

        $this->actingAs($artisanUser)->get(route('dashboard'))
            ->assertRedirect(route('artisan-space.index'));
    }

    public function test_visitor_cannot_open_admin_space(): void
    {
        $visitor = User::factory()->create(['role' => 'tourist']);

        $this->actingAs($visitor)->get(route('admin.reservations.index'))->assertForbidden();
    }

    public function test_artisan_can_update_only_its_own_reservations(): void
    {
        $artisanUser = User::factory()->create(['role' => 'artisan']);
        $artisan = Artisan::factory()->create(['user_id' => $artisanUser->id]);
        $reservation = ReservationRequest::factory()->create(['artisan_id' => $artisan->id]);

        $this->actingAs($artisanUser)
            ->patch(route('artisan-space.reservations.update', $reservation), ['status' => 'accepted'])
            ->assertSessionHas('success');

        $this->assertDatabaseHas('reservation_requests', ['id' => $reservation->id, 'status' => 'accepted']);
    }
}
