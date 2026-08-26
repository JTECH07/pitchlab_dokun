<?php

namespace Tests\Feature;

use App\Models\Artisan;
use App\Models\ArtisanApplication;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArtisanApplicationTest extends TestCase
{
    use RefreshDatabase;

    private function createCategory(): Category
    {
        return Category::create(['name' => 'Test', 'slug' => 'test-cat']);
    }

    public function test_verified_tourist_can_access_application_form(): void
    {
        $user = User::factory()->create(['role' => 'tourist']);
        $user->markEmailAsVerified();

        $this->actingAs($user)->get(route('artisan.apply'))->assertOk();
    }

    public function test_unverified_user_redirected_to_verify(): void
    {
        $user = User::factory()->unverified()->create(['role' => 'tourist']);

        $this->actingAs($user)->get(route('artisan.apply'))
            ->assertRedirect(route('verification.notice'));
    }

    public function test_tourist_can_submit_application(): void
    {
        $user = User::factory()->create(['role' => 'tourist']);
        $user->markEmailAsVerified();
        $category = $this->createCategory();

        $this->actingAs($user)->post(route('artisan.apply.submit'), [
            'first_name'       => 'Koffi',
            'last_name'        => 'Test',
            'phone'            => '+22997000000',
            'description'      => 'Artisan potier',
            'experience_years' => 5,
            'address'          => 'Quartier Test',
            'category_id'      => $category->id,
        ])->assertRedirect(route('artisan.apply.confirmation'));

        $this->assertDatabaseHas('artisan_applications', [
            'user_id' => $user->id,
            'status'  => 'pending',
        ]);
    }

    public function test_admin_can_approve_application(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $admin->markEmailAsVerified();
        $user = User::factory()->create(['role' => 'tourist']);
        $category = $this->createCategory();

        $application = ArtisanApplication::create([
            'user_id'          => $user->id,
            'first_name'       => 'Koffi',
            'last_name'        => 'Test',
            'phone'            => '+22997000000',
            'description'      => 'Artisan potier',
            'experience_years' => 5,
            'address'          => 'Quartier Test',
            'category_id'      => $category->id,
            'status'           => 'pending',
        ]);

        $this->actingAs($admin)->post(route('admin.applications.approve', $application))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('users', ['id' => $user->id, 'role' => 'artisan']);
        $this->assertDatabaseHas('artisans', ['user_id' => $user->id]);
        $this->assertDatabaseHas('artisan_applications', ['id' => $application->id, 'status' => 'approved']);
    }

    public function test_admin_can_reject_application(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $admin->markEmailAsVerified();
        $user = User::factory()->create(['role' => 'tourist']);
        $category = $this->createCategory();

        $application = ArtisanApplication::create([
            'user_id' => $user->id, 'first_name' => 'Koffi', 'last_name' => 'Test',
            'phone' => '+22997000000', 'description' => 'Test', 'experience_years' => 5,
            'address' => 'Test', 'category_id' => $category->id, 'status' => 'pending',
        ]);

        $this->actingAs($admin)->post(route('admin.applications.reject', $application), [
            'admin_notes' => 'Profil incomplet',
        ])->assertSessionHas('success');

        $this->assertDatabaseHas('artisan_applications', ['id' => $application->id, 'status' => 'rejected']);
        $this->assertDatabaseHas('users', ['id' => $user->id, 'role' => 'tourist']);
    }

    public function test_non_admin_cannot_approve(): void
    {
        $user = User::factory()->create(['role' => 'tourist']);
        $user->markEmailAsVerified();
        $category = $this->createCategory();
        $application = ArtisanApplication::create([
            'user_id' => $user->id, 'first_name' => 'K', 'last_name' => 'T',
            'phone' => '+22997000000', 'description' => 'Test', 'experience_years' => 5,
            'address' => 'Test', 'category_id' => $category->id, 'status' => 'pending',
        ]);

        $this->actingAs($user)->post(route('admin.applications.approve', $application))->assertStatus(403);
    }
}
