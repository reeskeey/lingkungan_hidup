<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Province;
use App\Models\Regency;
use App\Models\Fasyankes;
use App\Models\WasteGeneration;
use App\Models\TreatmentFacility;
use App\Models\TransferLocation;
use App\Models\RegionalCapacityGap;
use App\Models\RoadmapAction;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DomainModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_province_and_regency_relationship(): void
    {
        $province = Province::create([
            'code' => '31',
            'name' => 'DKI JAKARTA',
            'latitude' => -6.2088,
            'longitude' => 106.8456,
            'zoom_level' => 11,
        ]);

        $regency = Regency::create([
            'province_id' => $province->id,
            'code' => '3171',
            'name' => 'KOTA JAKARTA PUSAT',
            'latitude' => -6.1805,
            'longitude' => 106.8284,
        ]);

        $this->assertEquals('DKI JAKARTA', $regency->province->name);
        $this->assertCount(1, $province->regencies);
    }

    public function test_can_create_fasyankes_and_waste_generation(): void
    {
        $province = Province::create([
            'code' => '32',
            'name' => 'JAWA BARAT',
            'latitude' => -6.9175,
            'longitude' => 107.6191,
            'zoom_level' => 9,
        ]);

        $regency = Regency::create([
            'province_id' => $province->id,
            'code' => '3273',
            'name' => 'KOTA BANDUNG',
            'latitude' => -6.9175,
            'longitude' => 107.6191,
        ]);

        $fasyankes = Fasyankes::create([
            'name' => 'RSUP Dr. Hasan Sadikin',
            'type' => 'RS Kelas A',
            'province_id' => $province->id,
            'regency_id' => $regency->id,
            'address' => 'Jl. Pasteur No. 38, Bandung',
            'latitude' => -6.8967,
            'longitude' => 107.5982,
            'bed_capacity' => 900,
            'tps_permit_status' => 'Memiliki Izin',
            'storage_method' => 'Ruang Berpendingin/Cold Storage',
        ]);

        $waste = WasteGeneration::create([
            'fasyankes_id' => $fasyankes->id,
            'year' => 2026,
            'daily_generation_kg' => 450.50,
            'annual_generation_ton' => 164.43,
            'infectious_kg' => 300.00,
            'sharps_kg' => 50.50,
            'pathological_kg' => 40.00,
            'chemical_pharmaceutical_kg' => 60.00,
            'management_method' => 'Kerjasama Pengolah Berizin Pihak ke-3',
        ]);

        $this->assertEquals($fasyankes->id, $waste->fasyankes->id);
        $this->assertEquals(450.50, $fasyankes->wasteGenerations->first()->daily_generation_kg);
    }

    public function test_can_create_treatment_facility_and_transfer_location(): void
    {
        $province = Province::create([
            'code' => '35',
            'name' => 'JAWA TIMUR',
            'latitude' => -7.5360,
            'longitude' => 112.2384,
            'zoom_level' => 9,
        ]);

        $regency = Regency::create([
            'province_id' => $province->id,
            'code' => '3578',
            'name' => 'KOTA SURABAYA',
            'latitude' => -7.2575,
            'longitude' => 112.7521,
        ]);

        $facility = TreatmentFacility::create([
            'name' => 'PT Pengolah Limbah Jawa Timur',
            'facility_type' => 'Insinerator Berizin',
            'operator_category' => 'Jasa Komersial Pihak Ketiga',
            'province_id' => $province->id,
            'regency_id' => $regency->id,
            'latitude' => -7.2800,
            'longitude' => 112.7400,
            'installed_capacity_kg_h' => 1000.00,
            'licensed_capacity_ton_day' => 24.00,
            'permit_number' => 'SK.123/MENLHK/2024',
            'operational_status' => 'Aktif Beroperasi',
        ]);

        $transfer = TransferLocation::create([
            'name' => 'Lokasi Pemindahan Surabaya Barat',
            'province_id' => $province->id,
            'regency_id' => $regency->id,
            'address' => 'Kawasan Industri Gresik-Surabaya',
            'latitude' => -7.2200,
            'longitude' => 112.6500,
            'holding_capacity_ton' => 10.00,
            'has_cold_storage' => true,
            'service_status' => 'Aktif Beroperasi',
            'target_served_fasyankes' => 45,
        ]);

        $this->assertEquals('PT Pengolah Limbah Jawa Timur', $facility->name);
        $this->assertTrue($transfer->has_cold_storage);
    }

    public function test_can_create_regional_gap_and_roadmap_action(): void
    {
        $province = Province::create([
            'code' => '51',
            'name' => 'BALI',
            'latitude' => -8.4095,
            'longitude' => 115.1889,
            'zoom_level' => 10,
        ]);

        $gap = RegionalCapacityGap::create([
            'province_id' => $province->id,
            'year' => 2026,
            'total_waste_ton_day' => 8.50,
            'total_treatment_capacity_ton_day' => 4.00,
            'capacity_gap_ton_day' => -4.50,
            'coverage_ratio_percent' => 47.06,
            'status' => 'Defisit Kritis',
            'priority_level' => 'Prioritas 1 (Mendesak)',
        ]);

        $roadmap = RoadmapAction::create([
            'program_name' => 'Pengembangan Insinerator Regional Bali-Nusa Tenggara',
            'baseline' => 'Kapasitas terpasang 4.0 ton/hari',
            'target' => 'Peningkatan kapasitas menjadi 10 ton/hari',
            'priority_location' => 'Provinsi Bali',
            'time_horizon' => 'Jangka Pendek (Tahun 1-2)',
            'responsible_agency' => 'KLH / BPLH',
            'supporting_agency' => 'Pemerintah Provinsi Bali, Kemenkes',
            'indicative_budget' => 25000000000,
            'kpi' => 'Tingkat olah limbah B3 medis Bali mencapai 100%',
            'program_output' => '1 unit fasilitas pengolah regional operasional',
            'funding_source' => 'APBN KLH & SBSN',
            'progress_percent' => 15,
        ]);

        $this->assertEquals('Defisit Kritis', $gap->status);
        $this->assertEquals('Jangka Pendek (Tahun 1-2)', $roadmap->time_horizon);
    }
}
