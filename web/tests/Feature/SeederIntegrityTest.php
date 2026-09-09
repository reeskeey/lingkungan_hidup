<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Province;
use App\Models\Fasyankes;
use App\Models\TreatmentFacility;
use App\Models\TransferLocation;
use App\Models\RegionalCapacityGap;
use App\Models\RoadmapAction;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SeederIntegrityTest extends TestCase
{
    use RefreshDatabase;

    public function test_database_seeder_populates_comprehensive_national_dataset(): void
    {
        $this->seed();

        // 38 Provinsi di Indonesia
        $this->assertEquals(38, Province::count(), 'Harus terdapat 38 provinsi di Indonesia');

        // Fasyankes representatif nasional >= 100
        $this->assertGreaterThanOrEqual(100, Fasyankes::count(), 'Harus terdapat minimal 100 titik fasyankes');

        // Fasilitas pengolah berizin >= 20
        $this->assertGreaterThanOrEqual(20, TreatmentFacility::count(), 'Harus terdapat minimal 20 fasilitas pengolah');

        // Lokasi pemindahan >= 20
        $this->assertGreaterThanOrEqual(20, TransferLocation::count(), 'Harus terdapat minimal 20 lokasi pemindahan');

        // Data gap 38 provinsi
        $this->assertEquals(38, RegionalCapacityGap::count(), 'Harus terdapat 38 data gap kapasitas provinsi');

        // Matriks Rencana Aksi 10 Tahun >= 15
        $this->assertGreaterThanOrEqual(15, RoadmapAction::count(), 'Harus terdapat minimal 15 butir program aksi roadmap');

        // Cek fasyankes memiliki data timbulan
        $sampleFasyankes = Fasyankes::with('wasteGenerations')->first();
        $this->assertNotNull($sampleFasyankes);
        $this->assertGreaterThan(0, $sampleFasyankes->wasteGenerations->count());
    }
}
