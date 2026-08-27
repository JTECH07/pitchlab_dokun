<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Badge;
use App\Services\LoyaltyService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class LoyaltyTest extends TestCase
{
    use RefreshDatabase;

    public function test_daily_visit_awards_points_once_per_day(): void
    {
        $user = User::factory()->create();
        $svc = app(LoyaltyService::class);

        $svc->touchDailyVisit($user);
        $svc->touchDailyVisit($user);

        $this->assertDatabaseCount('loyalty_events', 1);
        $this->assertEquals(5, $svc->total($user));
    }

    public function test_level_mapping(): void
    {
        $this->assertEquals('Découvreur', LoyaltyService::levelFor(0)['current']['fr']);
        $this->assertEquals('Explorateur', LoyaltyService::levelFor(250)['current']['fr']);
        $this->assertEquals('Gardien du Patrimoine', LoyaltyService::levelFor(3000)['current']['fr']);
    }

    public function test_summary_tracks_points(): void
    {
        $user = User::factory()->create();
        $svc = app(LoyaltyService::class);

        $svc->award($user, 'reservation_made');

        $this->assertEquals(100, $svc->total($user));
        $this->assertNotNull($svc->summary($user));
    }

    public function test_bridge_chat_limited_to_three_per_day(): void
    {
        $user = User::factory()->create();
        $svc = app(LoyaltyService::class);

        for ($i = 0; $i < 5; $i++) {
            $svc->award($user, 'bridge_chat');
        }

        $this->assertEquals(15, $svc->total($user)); // 3 x 5 points
    }

    public function test_favorite_added_max_ten(): void
    {
        $user = User::factory()->create();
        $svc = app(LoyaltyService::class);

        for ($i = 0; $i < 15; $i++) {
            $svc->award($user, 'favorite_added', ['artisan_id' => $i]);
        }

        $this->assertEquals(50, $svc->total($user)); // 10 x 5 points
    }

    public function test_award_grants_badges(): void
    {
        Badge::create([
            'code' => 'ambassador', 'name_fr' => 'Ambassadeur', 'name_en' => 'Ambassador',
            'desc_fr' => '1200 pts', 'desc_en' => '1200 pts', 'icon' => 'gem',
        ]);
        $user = User::factory()->create();
        $svc = app(LoyaltyService::class);

        for ($i = 0; $i < 13; $i++) {
            $svc->award($user, 'reservation_made');
        }

        $this->assertDatabaseHas('badge_user', ['user_id' => $user->id]);
        $this->assertEquals(1300, $svc->total($user));
    }
}
