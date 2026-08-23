<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_account_with_any_role(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'Sylvain Guide',
            'email' => 'guide@dokun.bj',
            'password' => 'MotDePasse1!',
            'password_confirmation' => 'MotDePasse1!',
            'role' => 'guide',
        ])->assertRedirect();

        $this->assertDatabaseHas('users', [
            'email' => 'guide@dokun.bj',
            'role' => 'guide',
        ]);
        // Créé par l'admin : email déjà vérifié
        $this->assertNotNull(User::where('email', 'guide@dokun.bj')->first()->email_verified_at);
    }

    public function test_non_admin_cannot_access_user_management(): void
    {
        $tourist = User::factory()->create(['role' => 'tourist']);

        $this->actingAs($tourist)->get(route('admin.users.index'))->assertForbidden();
        $this->actingAs($tourist)->post(route('admin.users.store'), [
            'name' => 'X', 'email' => 'x@x.bj', 'password' => 'MotDePasse1!',
            'password_confirmation' => 'MotDePasse1!', 'role' => 'admin',
        ])->assertForbidden();
    }

    public function test_admin_can_change_role_and_is_protected_from_self_demotion(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $guide = User::factory()->create(['role' => 'guide']);

        $this->actingAs($admin)->patch(route('admin.users.role', $guide), ['role' => 'partner'])
            ->assertRedirect();
        $this->assertDatabaseHas('users', ['id' => $guide->id, 'role' => 'partner']);

        // L'admin ne peut pas se rétrograder lui-même
        $this->actingAs($admin)->patch(route('admin.users.role', $admin), ['role' => 'tourist'])
            ->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $admin->id, 'role' => 'admin']);
    }
}
