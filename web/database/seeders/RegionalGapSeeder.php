<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Province;
use App\Models\RegionalCapacityGap;
use App\Models\Fasyankes;
use App\Models\TreatmentFacility;

class RegionalGapSeeder extends Seeder
{
    public function run(): void
    {
        $provinces = Province::with(['treatmentFacilities'])->get();

        foreach ($provinces as $province) {
            // Hitung total timbulan harian dari fasyankes di provinsi tersebut
            $fasyankesIds = Fasyankes::where('province_id', $province->id)->pluck('id');
            $rawWasteKg = \App\Models\WasteGeneration::whereIn('fasyankes_id', $fasyankesIds)->sum('daily_generation_kg');
            
            // Konversi ke ton/hari dan estimasi faktor skala nasional (karena seeder hanya sampel fasyankes)
            // Untuk provinsi padat penduduk, berikan bobot timbulan realistis
            $multiplier = 1.0;
            if (in_array($province->code, ['31', '32', '33', '35'])) {
                $multiplier = 6.0; // Pulau Jawa menghasilkan ribuan fasyankes riil
            } elseif (in_array($province->code, ['12', '14', '16', '73'])) {
                $multiplier = 3.5;
            } else {
                $multiplier = 2.0;
            }

            $totalWasteTonDay = round(($rawWasteKg * $multiplier) / 1000, 2);
            if ($totalWasteTonDay < 1.5) {
                $totalWasteTonDay = 2.50;
            }

            // Hitung total kapasitas pengolahan aktif di provinsi tersebut
            $totalCapacityTonDay = round($province->treatmentFacilities->where('operational_status', 'Aktif Beroperasi')->sum('licensed_capacity_ton_day'), 2);

            $gapTonDay = round($totalCapacityTonDay - $totalWasteTonDay, 2);
            $coverageRatio = ($totalWasteTonDay > 0) ? round(($totalCapacityTonDay / $totalWasteTonDay) * 100, 2) : 100;

            if ($coverageRatio < 50) {
                $status = 'Defisit Kritis';
                $priority = 'Prioritas 1 (Mendesak)';
            } elseif ($coverageRatio < 100) {
                $status = 'Defisit Sedang';
                $priority = 'Prioritas 2';
            } else {
                $status = 'Surplus';
                $priority = 'Prioritas 3';
            }

            RegionalCapacityGap::create([
                'province_id' => $province->id,
                'year' => 2026,
                'total_waste_ton_day' => $totalWasteTonDay,
                'total_treatment_capacity_ton_day' => $totalCapacityTonDay,
                'capacity_gap_ton_day' => $gapTonDay,
                'coverage_ratio_percent' => $coverageRatio,
                'status' => $status,
                'priority_level' => $priority,
            ]);
        }
    }
}
