<?php

namespace App\Services;

use App\Models\RoadmapAction;
use App\Models\RegionalCapacityGap;

class RoadmapService
{
    /**
     * Mengambil daftar matriks rencana aksi terstruktur sesuai format Pasal 10 KAK.
     */
    public function getRoadmapMatrix(?string $horizon = null, ?string $agency = null)
    {
        $query = RoadmapAction::query()->orderBy('id', 'asc');

        if ($horizon && $horizon !== 'all') {
            $query->where('time_horizon', $horizon);
        }

        if ($agency && $agency !== 'all') {
            $query->where('responsible_agency', 'like', "%{$agency}%");
        }

        return $query->get();
    }

    /**
     * Ringkasan kebutuhan anggaran dan jumlah aksi per horizon waktu.
     */
    public function getHorizonSummary(): array
    {
        $horizons = [
            'Jangka Pendek (Tahun 1-2)' => ['label' => 'Jangka Pendek', 'period' => '2026 - 2027', 'color' => 'primary'],
            'Jangka Menengah (Tahun 3-5)' => ['label' => 'Jangka Menengah', 'period' => '2028 - 2030', 'color' => 'success'],
            'Jangka Panjang (Tahun 6-10)' => ['label' => 'Jangka Panjang', 'period' => '2031 - 2036', 'color' => 'warning'],
        ];

        $summary = [];
        $totalBudget = 0;
        $totalActions = 0;

        foreach ($horizons as $key => $meta) {
            $actions = RoadmapAction::where('time_horizon', $key)->get();
            $budget = $actions->sum('indicative_budget');
            $count = $actions->count();
            $avgProgress = $count > 0 ? round($actions->avg('progress_percent')) : 0;

            $totalBudget += $budget;
            $totalActions += $count;

            $summary[$key] = [
                'name' => $key,
                'label' => $meta['label'],
                'period' => $meta['period'],
                'color' => $meta['color'],
                'count' => $count,
                'budget' => (float) $budget,
                'avg_progress' => $avgProgress,
            ];
        }

        return [
            'horizons' => $summary,
            'total_budget' => $totalBudget,
            'total_actions' => $totalActions,
        ];
    }

    /**
     * Proyeksi timbulan limbah vs kapasitas pengolahan 10 tahun (2026 s.d. 2036).
     */
    public function getTenYearProjections(): array
    {
        $baseWaste = (float) RegionalCapacityGap::sum('total_waste_ton_day');
        $baseCapacity = (float) RegionalCapacityGap::sum('total_treatment_capacity_ton_day');

        if ($baseWaste <= 0) $baseWaste = 350.0;
        if ($baseCapacity <= 0) $baseCapacity = 300.0;

        $years = [];
        $wasteProjection = [];
        $capacityProjection = [];
        $gapProjection = [];

        // Asumsi pertumbuhan timbulan: ~4.5% per tahun (faktor penambahan fasyankes & tempat tidur)
        // Target roadmap: kapasitas mengejar dan mencapai 120% di tahun ke-10
        $currentWaste = $baseWaste;
        $currentCap = $baseCapacity;

        for ($yr = 2026; $yr <= 2036; $yr++) {
            $years[] = $yr;
            $wasteProjection[] = round($currentWaste, 1);
            $capacityProjection[] = round($currentCap, 1);
            $gapProjection[] = round($currentCap - $currentWaste, 1);

            // Pertumbuhan tahun berikutnya
            $currentWaste *= 1.045; // 4.5% pertumbuhan timbulan
            // Pertumbuhan kapasitas terakselerasi roadmap
            if ($yr < 2028) {
                $currentCap *= 1.08; // Th 1-2 akselerasi quick wins
            } elseif ($yr < 2031) {
                $currentCap *= 1.10; // Th 3-5 pembangunan fasilitas regional
            } else {
                $currentCap *= 1.05; // Th 6-10 stabilisasi & pemeliharaan
            }
        }

        return [
            'years' => $years,
            'waste_generation' => $wasteProjection,
            'treatment_capacity' => $capacityProjection,
            'capacity_gap' => $gapProjection,
        ];
    }
}
