<?php

namespace Tests\Feature;

use App\Models\Artisan;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class BridgeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['services.gemini.api_key' => null]);
    }

    private function makeArtisan(): Artisan
    {
        $category = Category::create(['name' => 'Poterie', 'slug' => 'poterie']);
        return Artisan::factory()->create(['category_id' => $category->id, 'status' => 'published']);
    }

    public function test_bridge_requires_auth(): void
    {
        $artisan = $this->makeArtisan();
        $this->post(route('features.bridge', $artisan), [
            'message' => 'Bonjour', 'language' => 'fr',
        ])->assertRedirect(route('login'));
    }

    public function test_bridge_chat_stores_messages(): void
    {
        $user = User::factory()->create(['role' => 'tourist']);
        $artisan = $this->makeArtisan();

        $this->actingAs($user)->post(route('features.bridge', $artisan), [
            'message' => 'Bonjour, comment ça va ?', 'language' => 'fr',
        ])->assertJson(['status' => 'success']);

        $this->assertDatabaseHas('dokun_messages', [
            'artisan_id' => $artisan->id, 'sender_type' => 'visitor',
        ]);
        $this->assertDatabaseHas('dokun_messages', [
            'artisan_id' => $artisan->id, 'sender_type' => 'artisan',
        ]);
    }

    public function test_bridge_validation(): void
    {
        $user = User::factory()->create();
        $artisan = $this->makeArtisan();

        $this->actingAs($user)->post(route('features.bridge', $artisan), [
            'message' => '', 'language' => 'fr',
        ])->assertSessionHasErrors('message');
    }

    public function test_bridge_history_returns_messages(): void
    {
        $user = User::factory()->create();
        $artisan = $this->makeArtisan();

        DB::table('dokun_messages')->insert([
            'artisan_id' => $artisan->id, 'sender_type' => 'visitor',
            'original_text' => 'Bonjour', 'original_language' => 'fr',
            'translated_text' => 'Bonjour', 'translated_language' => 'fr',
            'created_at' => now(), 'updated_at' => now(),
        ]);

        $this->actingAs($user)->get(route('features.bridge.history', $artisan))
            ->assertJson(['status' => 'success', 'total' => 1]);
    }

    public function test_bridge_chat_awards_loyalty(): void
    {
        $user = User::factory()->create(['role' => 'tourist']);
        $artisan = $this->makeArtisan();

        $this->actingAs($user)->post(route('features.bridge', $artisan), [
            'message' => 'Bonjour', 'language' => 'fr',
        ])->assertJson(['status' => 'success']);

        $this->assertDatabaseHas('loyalty_events', [
            'user_id' => $user->id, 'code' => 'bridge_chat',
        ]);
    }
}
