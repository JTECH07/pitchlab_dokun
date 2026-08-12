<?php

namespace Tests\Feature;

use App\Models\Artisan;
use App\Models\Category;
use App\Models\SavoirFaire;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArtisanAndAdminManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_can_view_artisans_index(): void
    {
        $response = $this->get(route('artisans.index'));
        $response->assertStatus(200);
    }

    public function test_public_can_view_savoir_faire_index(): void
    {
        $response = $this->get(route('savoir-faire.index'));
        $response->assertStatus(200);
    }

    public function test_admin_can_view_artisans_admin_index(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $response = $this->actingAs($admin)->get(route('admin.artisans.index'));
        $response->assertStatus(200);
    }

    public function test_admin_can_create_artisan(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $data = [
            'first_name' => 'Koffi',
            'last_name' => 'Dossou',
            'professional_name' => 'Atelier Koffi',
            'email' => 'koffi.dossou@example.com',
            'phone' => '+229 97000000',
            'whatsapp' => '+229 97000000',
            'description' => 'Maitre potier depuis 15 ans',
            'history' => 'Originaire de Porto-Novo',
            'experience_years' => 15,
            'address' => 'Gbekon, Porto-Novo',
            'latitude' => 6.4969,
            'longitude' => 2.6289,
            'status' => 'published',
        ];

        $response = $this->actingAs($admin)->post(route('admin.artisans.store'), $data);

        $response->assertRedirect(route('admin.artisans.index'));
        $this->assertDatabaseHas('artisans', [
            'first_name' => 'Koffi',
            'last_name' => 'Dossou',
        ]);
        $this->assertDatabaseHas('users', [
            'email' => 'koffi.dossou@example.com',
            'role' => 'artisan',
        ]);
    }
}
