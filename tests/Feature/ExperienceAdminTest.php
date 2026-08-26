<?php

namespace Tests\Feature;

use App\Models\Experience;
use App\Models\Artisan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExperienceAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_list_experiences(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $admin->markEmailAsVerified();

        $this->actingAs($admin)->get(route('admin.experiences.index'))->assertOk();
    }

    public function test_admin_can_create_experience(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $admin->markEmailAsVerified();
        $artisan = Artisan::factory()->create(['status' => 'published']);

        $this->actingAs($admin)->post(route('admin.experiences.store'), [
            'artisan_id'       => $artisan->id,
            'title'            => 'Initiation Poterie',
            'summary'          => 'Apprenez la poterie.',
            'duration_minutes' => 120,
            'capacity'         => 6,
            'price'            => 5000,
            'currency'         => 'XOF',
            'is_published'     => true,
        ])->assertRedirect(route('admin.experiences.index'));

        $this->assertDatabaseHas('experiences', [
            'title'   => 'Initiation Poterie',
            'price'   => 5000,
        ]);
    }

    public function test_admin_can_toggle_experience(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $admin->markEmailAsVerified();
        $artisan = Artisan::factory()->create();
        $exp = Experience::create([
            'artisan_id' => $artisan->id, 'title' => 'Test', 'summary' => 'Desc',
            'duration_minutes' => 60, 'capacity' => 4, 'price' => 2000, 'is_published' => true,
        ]);

        $this->actingAs($admin)->patch(route('admin.experiences.toggle', $exp))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('experiences', ['id' => $exp->id, 'is_published' => false]);
    }

    public function test_non_admin_cannot_manage_experiences(): void
    {
        $user = User::factory()->create(['role' => 'tourist']);
        $user->markEmailAsVerified();

        $this->actingAs($user)->get(route('admin.experiences.index'))->assertStatus(403);
    }
}
