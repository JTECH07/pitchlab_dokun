<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleRedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_tourist_redirects_to_voyage(): void
    {
        $user = User::factory()->create([
            'role' => 'tourist',
            'password' => bcrypt('secret123'),
        ]);
        $user->markEmailAsVerified();

        $this->post(route('login'), [
            'email'    => $user->email,
            'password' => 'secret123',
        ])->assertRedirect(route('visitor.profile'));
    }

    public function test_artisan_redirects_to_atelier(): void
    {
        $user = User::factory()->create([
            'role' => 'artisan',
            'password' => bcrypt('secret123'),
        ]);
        $user->markEmailAsVerified();

        $this->post(route('login'), [
            'email'    => $user->email,
            'password' => 'secret123',
        ])->assertRedirect(route('artisan-space.index'));
    }

    public function test_admin_redirects_to_dashboard(): void
    {
        $user = User::factory()->create([
            'role' => 'admin',
            'password' => bcrypt('secret123'),
        ]);
        $user->markEmailAsVerified();

        $this->post(route('login'), [
            'email'    => $user->email,
            'password' => 'secret123',
        ])->assertRedirect(route('dashboard'));
    }

    public function test_tourist_cannot_access_artisan_space(): void
    {
        $user = User::factory()->create(['role' => 'tourist']);
        $user->markEmailAsVerified();

        $this->actingAs($user)->get(route('artisan-space.index'))->assertStatus(403);
    }

    public function test_artisan_cannot_access_admin(): void
    {
        $user = User::factory()->create(['role' => 'artisan']);
        $user->markEmailAsVerified();

        $this->actingAs($user)->get(route('admin.users.index'))->assertStatus(403);
    }
}
