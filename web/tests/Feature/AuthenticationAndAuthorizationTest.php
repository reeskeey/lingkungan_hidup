<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Province;
use App\Models\RoadmapAction;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthenticationAndAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_guest_can_access_webgis_and_dashboard(): void
    {
        $this->get('/')->assertStatus(200);
        $this->get('/webgis')->assertStatus(200);
        $this->get('/dashboard')->assertStatus(200);
    }

    public function test_guest_is_redirected_to_login_when_accessing_data_master_or_roadmap(): void
    {
        $this->get('/fasyankes')->assertRedirect('/login');
        $this->get('/treatment-facilities')->assertRedirect('/login');
        $this->get('/transfer-locations')->assertRedirect('/login');
        $this->get('/gap-analysis')->assertRedirect('/login');
        $this->get('/roadmap')->assertRedirect('/login');
    }

    public function test_login_page_renders_with_demo_credentials(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Masuk Sistem KLH / BPLH');
        $response->assertSee('admin@klh.go.id');
    }

    public function test_superadmin_can_login_and_access_all_modules(): void
    {
        $user = User::where('email', 'admin@klh.go.id')->first();
        $this->assertNotNull($user, 'Seeder akun superadmin harus ada');

        $this->post('/login', [
            'email' => 'admin@klh.go.id',
            'password' => 'password',
        ])->assertRedirect('/dashboard');

        $this->assertAuthenticatedAs($user);

        // Akses halaman yang diproteksi
        $this->actingAs($user)->get('/fasyankes')->assertStatus(200);
        $this->actingAs($user)->get('/roadmap')->assertStatus(200);
    }

    public function test_superadmin_can_update_roadmap_action_progress(): void
    {
        $user = User::where('email', 'admin@klh.go.id')->first();
        $action = RoadmapAction::first();
        $this->assertNotNull($action);

        $response = $this->actingAs($user)->patch(route('roadmap.progress', $action), [
            'progress_percent' => 85,
        ]);

        $response->assertRedirect();
        $this->assertEquals(85, $action->fresh()->progress_percent);
    }
}
