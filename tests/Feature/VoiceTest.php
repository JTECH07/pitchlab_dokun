<?php

namespace Tests\Feature;

use App\Models\Artisan;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class VoiceTest extends TestCase
{
    use RefreshDatabase;

    private function makeArtisan(User $user): Artisan
    {
        $category = Category::create(['name' => 'Poterie', 'slug' => 'poterie']);
        return Artisan::factory()->create(['category_id' => $category->id, 'user_id' => $user->id]);
    }

    public function test_owner_can_upload_voice(): void
    {
        config(['services.gemini.api_key' => null]);
        $user = User::factory()->create(['role' => 'artisan']);
        $artisan = $this->makeArtisan($user);

        $file = UploadedFile::fake()->create('voice.mp3', 100, 'audio/mpeg');

        $this->actingAs($user)->post(route('artisan-space.voice.upload', $artisan), [
            'audio' => $file,
            'title' => 'Ma poterie',
            'language' => 'fon',
        ])->assertJson(['status' => 'success']);

        $this->assertDatabaseHas('dokun_audio_archives', [
            'artisan_id' => $artisan->id, 'title' => 'Ma poterie', 'status' => 'pending',
        ]);
    }

    public function test_non_owner_cannot_upload(): void
    {
        $user = User::factory()->create(['role' => 'artisan']);
        $artisan = $this->makeArtisan($user);
        $intruder = User::factory()->create(['role' => 'artisan']);

        $file = UploadedFile::fake()->create('voice.mp3', 100, 'audio/mpeg');

        $this->actingAs($intruder)->post(route('artisan-space.voice.upload', $artisan), [
            'audio' => $file,
        ])->assertStatus(403);
    }

    public function test_voice_archives_hide_unpublished_for_others(): void
    {
        $user = User::factory()->create(['role' => 'artisan']);
        $artisan = $this->makeArtisan($user);

        DB::table('dokun_audio_archives')->insert([
            'artisan_id' => $artisan->id, 'audio_path' => 'archives/voice/a.mp3',
            'status' => 'published', 'title' => 'Publié', 'language' => 'fon',
            'created_at' => now(), 'updated_at' => now(),
        ]);
        DB::table('dokun_audio_archives')->insert([
            'artisan_id' => $artisan->id, 'audio_path' => 'archives/voice/b.mp3',
            'status' => 'pending', 'title' => 'Attente', 'language' => 'fon',
            'created_at' => now(), 'updated_at' => now(),
        ]);

        $visitor = User::factory()->create(['role' => 'tourist']);
        $response = $this->actingAs($visitor)->get(route('features.voice.archives', $artisan))
            ->assertJson(['status' => 'success']);

        $archives = $response->json('archives');
        $this->assertCount(1, $archives);
        $this->assertEquals('published', $archives[0]['status']);
    }

    public function test_owner_can_delete_voice(): void
    {
        $user = User::factory()->create(['role' => 'artisan']);
        $artisan = $this->makeArtisan($user);

        $id = DB::table('dokun_audio_archives')->insertGetId([
            'artisan_id' => $artisan->id, 'audio_path' => 'archives/voice/a.mp3',
            'status' => 'pending', 'title' => 'À supprimer', 'language' => 'fon',
            'created_at' => now(), 'updated_at' => now(),
        ]);

        $this->actingAs($user)->delete(route('artisan-space.voice.delete', $id))
            ->assertJson(['status' => 'success']);

        $this->assertDatabaseMissing('dokun_audio_archives', ['id' => $id]);
    }
}
