<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Fasyankes;
use App\Models\TreatmentFacility;
use App\Models\TransferLocation;
use App\Models\RoadmapAction;
use App\Models\Province;
use App\Models\Regency;
use App\Support\UrlCrypt;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EncryptedRouteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_fasyankes_route_generates_encrypted_url_without_plain_id(): void
    {
        $fasyankes = Fasyankes::first();
        $this->assertNotNull($fasyankes);

        $url = route('fasyankes.edit', $fasyankes);

        // URL tidak boleh mengandung plain integer ID seperti /fasyankes/1/edit
        $this->assertStringNotContainsString("/fasyankes/{$fasyankes->id}/edit", $url);
        
        // Ekstrak token dari URL dan verifikasi dekripsi berhasil
        preg_match('#/fasyankes/([^/]+)/edit#', $url, $matches);
        $this->assertNotEmpty($matches[1]);
        $this->assertEquals($fasyankes->id, UrlCrypt::decodeId($matches[1]));
    }

    public function test_can_access_fasyankes_edit_with_valid_encrypted_route_key(): void
    {
        $user = User::where('role', 'superadmin')->first();
        $fasyankes = Fasyankes::first();

        $response = $this->actingAs($user)->get(route('fasyankes.edit', $fasyankes));

        $response->assertStatus(200);
        $response->assertSee($fasyankes->name);
    }

    public function test_accessing_with_invalid_or_tampered_token_redirects_with_flash_error(): void
    {
        $user = User::where('role', 'superadmin')->first();

        // Mengakses dengan token rusak / hasil manipulasi
        $response = $this->actingAs($user)->get('/fasyankes/tampered_token_xyz_123/edit');

        $response->assertRedirect(route('fasyankes.index'));
        $response->assertSessionHas('error');
    }

    public function test_accessing_with_raw_unencrypted_integer_id_redirects_with_flash_error(): void
    {
        $user = User::where('role', 'superadmin')->first();
        $fasyankes = Fasyankes::first();

        // Mengakses dengan integer numerik mentah tanpa enkripsi
        $response = $this->actingAs($user)->get("/fasyankes/{$fasyankes->id}/edit");

        $response->assertRedirect(route('fasyankes.index'));
        $response->assertSessionHas('error');
    }

    public function test_fasyankes_can_be_updated_using_encrypted_route_and_encrypted_dropdowns(): void
    {
        $user = User::where('role', 'superadmin')->first();
        $fasyankes = Fasyankes::first();
        $province = Province::where('id', '!=', $fasyankes->province_id)->first();
        $regency = $province->regencies->first();

        $response = $this->actingAs($user)->put(route('fasyankes.update', $fasyankes), [
            'name' => 'RSUD Percontohan Hijau Medika',
            'type' => 'RS Kelas B',
            'province_id' => $province->encrypted_id,
            'regency_id' => $regency->encrypted_id,
            'address' => 'Jl. Lingkungan Hidup Lestari No. 1',
            'latitude' => -6.208800,
            'longitude' => 106.845600,
            'bed_capacity' => 120,
            'tps_permit_status' => 'Memiliki Izin',
            'storage_method' => 'Ruang Berpendingin/Cold Storage',
            'daily_generation_kg' => 96.0,
        ]);

        $response->assertRedirect(route('fasyankes.index'));
        $this->assertDatabaseHas('fasyankes', [
            'id' => $fasyankes->id,
            'name' => 'RSUD Percontohan Hijau Medika',
            'province_id' => $province->id,
            'regency_id' => $regency->id,
        ]);
    }

    public function test_roadmap_action_progress_can_be_updated_with_encrypted_key(): void
    {
        $user = User::where('role', 'superadmin')->first();
        $action = RoadmapAction::first();

        $response = $this->actingAs($user)->patch(route('roadmap.progress', $action), [
            'progress_percent' => 70,
        ]);

        $response->assertRedirect();
        $this->assertEquals(70, $action->fresh()->progress_percent);
    }

    public function test_geojson_api_returns_encrypted_ids_for_all_features(): void
    {
        $response = $this->get('/webgis/data');

        $response->assertStatus(200);
        $data = $response->json();

        $this->assertNotEmpty($data['fasyankes']);
        $firstFas = $data['fasyankes'][0];
        // ID harus string URL-safe terenkripsi
        $this->assertIsString($firstFas['id']);
        $this->assertNotNull(UrlCrypt::decodeId($firstFas['id']));

        $this->assertNotEmpty($data['treatment_facilities']);
        $firstFac = $data['treatment_facilities'][0];
        $this->assertIsString($firstFac['id']);
        $this->assertNotNull(UrlCrypt::decodeId($firstFac['id']));

        $this->assertNotEmpty($data['transfer_locations']);
        $firstLoc = $data['transfer_locations'][0];
        $this->assertIsString($firstLoc['id']);
        $this->assertNotNull(UrlCrypt::decodeId($firstLoc['id']));
    }

    public function test_webgis_api_filters_correctly_with_encrypted_province_id(): void
    {
        $province = Province::first();
        $response = $this->get("/webgis/data?province_id={$province->encrypted_id}");

        $response->assertStatus(200);
        $data = $response->json();

        foreach ($data['fasyankes'] as $fas) {
            $this->assertEquals($province->name, $fas['province']);
        }
    }
}
