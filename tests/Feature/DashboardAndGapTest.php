<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DashboardAndGapTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_dashboard_page_returns_ok_with_analytics(): void
    {
        $response = $this->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Dashboard Eksekutif Limbah B3 Medis');
        $response->assertSee('chartWasteComposition');
        $response->assertSee('chartTopDeficit');
        $response->assertSee('chartTenYearProjection');
    }

    public function test_gap_analysis_page_returns_ok_with_table(): void
    {
        $user = \App\Models\User::first();
        $response = $this->actingAs($user)->get('/gap-analysis');
        $response->assertStatus(200);
        $response->assertSee('Analisis Kesenjangan Kapasitas Pengolahan');
        $response->assertSee('Neraca Wilayah Provinsi');
    }

    public function test_gap_analysis_filters_by_island_and_status(): void
    {
        $user = \App\Models\User::first();
        $response = $this->actingAs($user)->get('/gap-analysis?island=jawa&status=Surplus');
        $response->assertStatus(200);
    }
}
