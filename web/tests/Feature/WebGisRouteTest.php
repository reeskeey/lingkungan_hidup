<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WebGisRouteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_webgis_home_page_returns_ok(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('WebGIS Nasional Limbah B3 Medis');
        $response->assertSee('id="webgis-map"', false);
    }

    public function test_webgis_alias_route_returns_ok(): void
    {
        $response = $this->get('/webgis');
        $response->assertStatus(200);
    }

    public function test_webgis_geojson_api_returns_valid_data(): void
    {
        $response = $this->getJson('/webgis/data');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'fasyankes',
            'treatment_facilities',
            'transfer_locations',
            'provinces',
        ]);
    }
}
