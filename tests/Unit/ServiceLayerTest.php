<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\GeoSpatialService;
use App\Services\GapAnalysisService;
use App\Services\RoadmapService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ServiceLayerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_geospatial_service_generates_map_features_payload(): void
    {
        $geoService = new GeoSpatialService();
        $features = $geoService->getMapFeatures();

        $this->assertArrayHasKey('fasyankes', $features);
        $this->assertArrayHasKey('treatment_facilities', $features);
        $this->assertArrayHasKey('transfer_locations', $features);
        $this->assertArrayHasKey('provinces', $features);

        $this->assertNotEmpty($features['fasyankes']);
        $this->assertNotEmpty($features['treatment_facilities']);
        $this->assertNotEmpty($features['transfer_locations']);
        $this->assertCount(38, $features['provinces']);
    }

    public function test_gap_analysis_service_calculates_national_metrics(): void
    {
        $gapService = new GapAnalysisService();
        $summary = $gapService->getNationalSummary();

        $this->assertArrayHasKey('total_fasyankes', $summary);
        $this->assertArrayHasKey('total_waste_ton_day', $summary);
        $this->assertArrayHasKey('total_capacity_ton_day', $summary);
        $this->assertArrayHasKey('national_coverage_ratio', $summary);
        $this->assertArrayHasKey('critical_deficit_count', $summary);

        $this->assertGreaterThan(0, $summary['total_fasyankes']);
        $this->assertGreaterThan(0, $summary['total_waste_ton_day']);
    }

    public function test_roadmap_service_provides_matrix_and_projections(): void
    {
        $roadmapService = new RoadmapService();
        $matrix = $roadmapService->getRoadmapMatrix();
        $projections = $roadmapService->getTenYearProjections();

        $this->assertNotEmpty($matrix);
        $this->assertCount(11, $projections['years']); // 2026 s.d. 2036
        $this->assertEquals(2026, $projections['years'][0]);
        $this->assertEquals(2036, $projections['years'][10]);
    }
}
