<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Province;
use App\Models\Fasyankes;
use App\Models\RoadmapAction;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiEndpointsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_api_login_success_and_returns_bearer_token(): void
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'admin@klh.go.id',
            'password' => 'password',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message',
            'token',
            'user' => ['id', 'name', 'email', 'role', 'is_superadmin'],
        ]);
        $this->assertTrue($response->json('success'));
        $this->assertNotEmpty($response->json('token'));
    }

    public function test_api_login_fails_with_invalid_credentials(): void
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'admin@klh.go.id',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(422);
        $this->assertFalse($response->json('success'));
    }

    public function test_api_dashboard_summary_returns_valid_kpi_payload(): void
    {
        $response = $this->getJson('/api/v1/dashboard/summary');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                'kpi' => [
                    'total_fasyankes',
                    'total_waste_ton_day',
                    'total_capacity_ton_day',
                    'national_coverage_ratio',
                ],
                'waste_composition',
                'top_deficits',
                'ten_year_projections',
                'horizon_summary',
            ],
        ]);
    }

    public function test_api_webgis_data_returns_features_payload(): void
    {
        $response = $this->getJson('/api/v1/webgis/data');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                'fasyankes',
                'treatment_facilities',
                'transfer_locations',
                'provinces',
            ],
        ]);
    }

    public function test_api_fasyankes_can_be_created_from_field_with_bearer_token(): void
    {
        // Login untuk mendapatkan token
        $loginRes = $this->postJson('/api/v1/auth/login', [
            'email' => 'admin@klh.go.id',
            'password' => 'password',
        ]);
        $token = $loginRes->json('token');

        $province = Province::first();
        $regency = $province->regencies->first();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/v1/fasyankes', [
                'name' => 'RS Lapangan Penanganan B3 Medis',
                'type' => 'RS Kelas C',
                'province_id' => $province->encrypted_id,
                'regency_id' => $regency->encrypted_id,
                'address' => 'Jl. Lapangan Medika Hijau No. 10',
                'latitude' => -6.175392,
                'longitude' => 106.827153,
                'bed_capacity' => 80,
                'tps_permit_status' => 'Memiliki Izin',
                'storage_method' => 'Ruang Berpendingin/Cold Storage',
                'daily_generation_kg' => 64.0,
            ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('fasyankes', [
            'name' => 'RS Lapangan Penanganan B3 Medis',
            'province_id' => $province->id,
            'regency_id' => $regency->id,
        ]);
    }

    public function test_api_roadmap_progress_can_be_updated_with_bearer_token(): void
    {
        $loginRes = $this->postJson('/api/v1/auth/login', [
            'email' => 'admin@klh.go.id',
            'password' => 'password',
        ]);
        $token = $loginRes->json('token');

        $action = RoadmapAction::first();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->patchJson("/api/v1/roadmap/{$action->encrypted_id}/progress", [
                'progress_percent' => 92,
            ]);

        $response->assertStatus(200);
        $this->assertEquals(92, $action->fresh()->progress_percent);
    }
}
