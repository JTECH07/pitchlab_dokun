<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\SavoirFaire;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlayFeatureTest extends TestCase
{
    use RefreshDatabase;

    private function category(): Category
    {
        return Category::create(['name' => 'Artisanat', 'slug' => 'artisanat-creation']);
    }

    public function test_game_page_loads_for_guest(): void
    {
        $cat = $this->category();
        SavoirFaire::create(['name' => 'Poterie', 'slug' => 'poterie', 'description' => 'Argile cuite', 'category_id' => $cat->id]);
        SavoirFaire::create(['name' => 'Vannerie', 'slug' => 'vannerie', 'description' => 'Fibres tressées', 'category_id' => $cat->id]);

        $this->get(route('play.index'))->assertOk();
    }

    public function test_correct_guess_awards_xp_to_member(): void
    {
        $cat = $this->category();
        SavoirFaire::create(['name' => 'Poterie', 'slug' => 'poterie', 'description' => 'Argile cuite', 'category_id' => $cat->id]);
        SavoirFaire::create(['name' => 'Vannerie', 'slug' => 'vannerie', 'description' => 'Fibres tressées', 'category_id' => $cat->id]);

        // Le contrôleur prend la première carte comme cible de la partie.
        $target = SavoirFaire::first();

        $user = User::factory()->create(['role' => 'tourist']);

        $this->actingAs($user)
            ->post(route('play.guess'), ['target_id' => $target->id, 'answer_id' => $target->id])
            ->assertRedirect(route('play.index'));

        $this->assertDatabaseHas('loyalty_events', [
            'user_id' => $user->id,
            'code' => 'play_win',
            'points' => 15,
        ]);
    }

    public function test_wrong_guess_awards_no_xp(): void
    {
        $cat = $this->category();
        $target = SavoirFaire::create(['name' => 'Poterie', 'slug' => 'poterie', 'description' => 'Argile cuite', 'category_id' => $cat->id]);
        $other = SavoirFaire::create(['name' => 'Vannerie', 'slug' => 'vannerie', 'description' => 'Fibres tressées', 'category_id' => $cat->id]);

        $user = User::factory()->create(['role' => 'tourist']);

        $this->actingAs($user)
            ->post(route('play.guess'), ['target_id' => $target->id, 'answer_id' => $other->id])
            ->assertRedirect(route('play.index'));

        $this->assertDatabaseMissing('loyalty_events', [
            'user_id' => $user->id,
            'code' => 'play_win',
        ]);
    }
}
