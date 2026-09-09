<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Province;
use App\Models\Regency;
use App\Models\Fasyankes;
use App\Models\TreatmentFacility;
use App\Models\TransferLocation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MasterDataCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_fasyankes_index_displays_data(): void
    {
        $user = User::first();
        $response = $this->actingAs($user)->get('/fasyankes');
        $response->assertStatus(200);
        $response->assertSee('Data Fasyankes & Timbulan Limbah B3');
    }

    public function test_can_create_new_fasyankes(): void
    {
        $user = User::first();
        $province = Province::first();
        $regency = $province->regencies->first();

        $postData = [
            'name' => 'RSUD Baru Percontohan Medika',
            'type' => 'RS Kelas B',
            'province_id' => $province->id,
            'regency_id' => $regency->id,
            'address' => 'Jl. Lingkungan Hidup No. 10',
            'latitude' => -6.123456,
            'longitude' => 106.890123,
            'bed_capacity' => 300,
            'tps_permit_status' => 'Memiliki Izin',
            'storage_method' => 'Ruang Berpendingin/Cold Storage',
            'daily_generation_kg' => 150.00,
        ];

        $response = $this->actingAs($user)->post('/fasyankes', $postData);
        $response->assertRedirect('/fasyankes');

        $this->assertDatabaseHas('fasyankes', [
            'name' => 'RSUD Baru Percontohan Medika',
            'bed_capacity' => 300,
        ]);
    }

    public function test_treatment_facilities_index_displays_data(): void
    {
        $user = User::first();
        $response = $this->actingAs($user)->get('/treatment-facilities');
        $response->assertStatus(200);
        $response->assertSee('Fasilitas Pengolahan Limbah B3 Berizin');
    }

    public function test_transfer_locations_index_displays_data(): void
    {
        $user = User::first();
        $response = $this->actingAs($user)->get('/transfer-locations');
        $response->assertStatus(200);
        $response->assertSee('Lokasi Pemindahan');
    }
}
