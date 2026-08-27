<?php

namespace Tests\Feature;

use App\Models\Badge;
use App\Models\User;
use App\Services\LoyaltyService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PassportFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_passport(): void
    {
        $this->get(route('visitor.passport'))->assertRedirect(route('login'));
    }

    public function test_tourist_can_access_passport_page(): void
    {
        $user = User::factory()->create(['role' => 'tourist']);

        $this->actingAs($user)
            ->get(route('visitor.passport'))
            ->assertOk();
    }

    public function test_passport_shows_awarded_points_and_level(): void
    {
        $user = User::factory()->create(['role' => 'tourist']);
        app(LoyaltyService::class)->award($user, 'lesson_completed', ['lesson_id' => 1]);
        app(LoyaltyService::class)->award($user, 'reservation_made');

        $total = app(LoyaltyService::class)->total($user);

        $this->actingAs($user)
            ->get(route('visitor.passport'))
            ->assertOk()
            ->assertSee(number_format($total));
    }

    public function test_passport_displays_badge_catalog(): void
    {
        Badge::create([
            'code' => 'first_steps', 'name_fr' => 'Premiers Pas', 'name_en' => 'First Steps',
            'desc_fr' => 'Terminer ta première leçon', 'desc_en' => 'Complete your first lesson',
            'icon' => 'check-circle',
        ]);

        $user = User::factory()->create(['role' => 'tourist']);

        // Locale FR par défaut dans les tests → on vérifie le nom français.
        $this->actingAs($user)
            ->get(route('visitor.passport'))
            ->assertOk()
            ->assertSee('Premiers Pas');
    }
}
