<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoadmapTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_roadmap_page_returns_ok_with_actions(): void
    {
        $user = \App\Models\User::first();
        $response = $this->actingAs($user)->get('/roadmap');
        $response->assertStatus(200);
        $response->assertSee('Matriks Peta Jalan (Roadmap) 10 Tahun');
        $response->assertSee('Jangka Pendek (Tahun 1-2)');
    }

    public function test_roadmap_page_filters_by_horizon(): void
    {
        $user = \App\Models\User::first();
        $response = $this->actingAs($user)->get('/roadmap?horizon=' . urlencode('Jangka Pendek (Tahun 1-2)'));
        $response->assertStatus(200);
    }

    public function test_roadmap_csv_export_returns_file_download(): void
    {
        $user = \App\Models\User::first();
        $response = $this->actingAs($user)->get('/roadmap/export-csv');
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'text/csv; charset=UTF-8');
    }
}
