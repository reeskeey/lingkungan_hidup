<?php

namespace App\Services;

use App\Models\Province;
use App\Models\Fasyankes;
use App\Models\WasteGeneration;
use App\Models\TreatmentFacility;
use App\Models\TransferLocation;
use App\Models\RegionalCapacityGap;

class GapAnalysisService
{
    /**
     * Menghitung ringkasan metrik nasional untuk kartu KPI eksekutif.
     */
    public function getNationalSummary(): array
    {
        $totalFasyankes = Fasyankes::count();
        $totalHospital = Fasyankes::where('type', 'like', 'RS%')->count();
        $totalPuskesmas = Fasyankes::where('type', 'Puskesmas')->count();
        $totalKlinik = Fasyankes::where('type', 'like', 'Klinik%')->count();

        $totalWasteTonDay = (float) RegionalCapacityGap::sum('total_waste_ton_day');
        $totalCapacityTonDay = (float) RegionalCapacityGap::sum('total_treatment_capacity_ton_day');
        $nationalGapTonDay = round($totalCapacityTonDay - $totalWasteTonDay, 2);
        $nationalCoverageRatio = ($totalWasteTonDay > 0) 
            ? round(($totalCapacityTonDay / $totalWasteTonDay) * 100, 1) 
            : 100.0;

        $criticalDeficitCount = RegionalCapacityGap::where('status', 'Defisit Kritis')->count();
        $moderateDeficitCount = RegionalCapacityGap::where('status', 'Defisit Sedang')->count();
        $surplusCount = RegionalCapacityGap::where('status', 'Surplus')->count();

        $totalTreatmentFacility = TreatmentFacility::count();
        $totalTransferLocation = TransferLocation::count();
        $licensedTpsCount = Fasyankes::where('tps_permit_status', 'Memiliki Izin')->count();
        $licensedTpsPercent = ($totalFasyankes > 0) ? round(($licensedTpsCount / $totalFasyankes) * 100, 1) : 0;

        return [
            'total_fasyankes' => $totalFasyankes,
            'total_hospital' => $totalHospital,
            'total_puskesmas' => $totalPuskesmas,
            'total_klinik' => $totalKlinik,
            'total_waste_ton_day' => round($totalWasteTonDay, 2),
            'total_waste_ton_year' => round($totalWasteTonDay * 365, 2),
            'total_capacity_ton_day' => round($totalCapacityTonDay, 2),
            'national_gap_ton_day' => $nationalGapTonDay,
            'national_coverage_ratio' => $nationalCoverageRatio,
            'critical_deficit_count' => $criticalDeficitCount,
            'moderate_deficit_count' => $moderateDeficitCount,
            'surplus_count' => $surplusCount,
            'total_treatment_facility' => $totalTreatmentFacility,
            'total_transfer_location' => $totalTransferLocation,
            'licensed_tps_count' => $licensedTpsCount,
            'licensed_tps_percent' => $licensedTpsPercent,
        ];
    }

    /**
     * Mengambil daftar neraca kesenjangan per provinsi dengan filter.
     */
    public function getRegionalGaps(?string $status = null, ?string $island = null): array
    {
        $query = RegionalCapacityGap::with(['province:id,code,name,latitude,longitude'])
            ->orderBy('capacity_gap_ton_day', 'asc'); // Mulai dari defisit paling besar

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        $items = $query->get()->map(function ($gap) {
            return [
                'id' => $gap->id,
                'province_id' => $gap->province_id,
                'province_code' => $gap->province->code,
                'province_name' => $gap->province->name,
                'total_waste' => (float) $gap->total_waste_ton_day,
                'total_capacity' => (float) $gap->total_treatment_capacity_ton_day,
                'gap' => (float) $gap->capacity_gap_ton_day,
                'coverage_ratio' => (float) $gap->coverage_ratio_percent,
                'status' => $gap->status,
                'priority_level' => $gap->priority_level,
            ];
        });

        // Filter pulau jika diminta
        if ($island && $island !== 'all') {
            $items = $items->filter(function ($item) use ($island) {
                $code = substr($item['province_code'], 0, 1);
                return match ($island) {
                    'sumatera' => in_array(substr($item['province_code'], 0, 2), ['11','12','13','14','15','16','17','18','19','21']),
                    'jawa' => in_array(substr($item['province_code'], 0, 2), ['31','32','33','34','35','36']),
                    'bali_nusa' => in_array(substr($item['province_code'], 0, 2), ['51','52','53']),
                    'kalimantan' => in_array(substr($item['province_code'], 0, 2), ['61','62','63','64','65']),
                    'sulawesi' => in_array(substr($item['province_code'], 0, 2), ['71','72','73','74','75','76']),
                    'maluku_papua' => in_array(substr($item['province_code'], 0, 2), ['81','82','91','92','93','94','95','96']),
                    default => true,
                };
            })->values();
        }

        return $items->toArray();
    }

    /**
     * Mengambil 10 provinsi dengan defisit kapasitas terbesar untuk grafik.
     */
    public function getTopDeficitProvinces(int $limit = 10): array
    {
        return RegionalCapacityGap::with('province')
            ->where('capacity_gap_ton_day', '<', 0)
            ->orderBy('capacity_gap_ton_day', 'asc')
            ->limit($limit)
            ->get()
            ->map(function ($gap) {
                return [
                    'province' => $gap->province->name,
                    'deficit_ton_day' => abs((float) $gap->capacity_gap_ton_day),
                    'waste_ton_day' => (float) $gap->total_waste_ton_day,
                    'capacity_ton_day' => (float) $gap->total_treatment_capacity_ton_day,
                    'coverage_ratio' => (float) $gap->coverage_ratio_percent,
                ];
            })
            ->toArray();
    }

    /**
     * Komposisi jenis limbah B3 medis nasional.
     */
    public function getWasteCompositionSummary(): array
    {
        $infectious = (float) WasteGeneration::sum('infectious_kg');
        $sharps = (float) WasteGeneration::sum('sharps_kg');
        $pathological = (float) WasteGeneration::sum('pathological_kg');
        $chemical = (float) WasteGeneration::sum('chemical_pharmaceutical_kg');
        $total = $infectious + $sharps + $pathological + $chemical;

        if ($total == 0) {
            return [
                'infectious' => 65,
                'sharps' => 12,
                'pathological' => 8,
                'chemical' => 15,
            ];
        }

        return [
            'infectious' => round(($infectious / $total) * 100, 1),
            'sharps' => round(($sharps / $total) * 100, 1),
            'pathological' => round(($pathological / $total) * 100, 1),
            'chemical' => round(($chemical / $total) * 100, 1),
        ];
    }
}
