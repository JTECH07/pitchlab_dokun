<?php

namespace Tests\Feature;

use App\Models\ActorRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActorRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_access_form(): void
    {
        $this->get(route('actor-requests.form'))->assertOk();
    }

    public function test_guest_can_submit_request(): void
    {
        $this->post(route('actor-requests.submit'), [
            'role'         => 'guide',
            'name'         => 'Jean Test',
            'email'        => 'jean@test.com',
            'motivation'   => 'Je souhaite guider des visiteurs.',
        ])->assertRedirect(route('actor-requests.confirmation'));

        $this->assertDatabaseHas('actor_requests', [
            'email'  => 'jean@test.com',
            'role'   => 'guide',
            'status' => 'pending',
        ]);
    }

    public function test_admin_can_approve_and_creates_user(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $admin->markEmailAsVerified();

        $request = ActorRequest::create([
            'role'       => 'guide',
            'name'       => 'Jean Guide',
            'email'      => 'guide@test.com',
            'motivation' => 'Je veux guider.',
            'status'     => 'pending',
        ]);

        $this->actingAs($admin)->post(route('admin.actor-requests.approve', $request))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('users', ['email' => 'guide@test.com', 'role' => 'guide']);
        $this->assertDatabaseHas('actor_requests', ['id' => $request->id, 'status' => 'approved']);
    }

    public function test_admin_can_reject(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $admin->markEmailAsVerified();

        $request = ActorRequest::create([
            'role' => 'partner', 'name' => 'Part Test', 'email' => 'p@test.com',
            'motivation' => 'Partenariat', 'status' => 'pending',
        ]);

        $this->actingAs($admin)->post(route('admin.actor-requests.reject', $request), [
            'admin_notes' => 'Non retenu',
        ])->assertSessionHas('success');

        $this->assertDatabaseHas('actor_requests', ['id' => $request->id, 'status' => 'rejected']);
        $this->assertDatabaseMissing('users', ['email' => 'p@test.com']);
    }

    public function test_non_admin_cannot_approve(): void
    {
        $user = User::factory()->create(['role' => 'tourist']);
        $user->markEmailAsVerified();
        $request = ActorRequest::create([
            'role' => 'guide', 'name' => 'G', 'email' => 'g@t.com',
            'motivation' => 'Test', 'status' => 'pending',
        ]);

        $this->actingAs($user)->post(route('admin.actor-requests.approve', $request))->assertStatus(403);
    }
}
