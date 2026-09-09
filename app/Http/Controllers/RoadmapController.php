<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RoadmapService;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RoadmapController extends Controller
{
    protected RoadmapService $roadmapService;

    public function __construct(RoadmapService $roadmapService)
    {
        $this->roadmapService = $roadmapService;
    }

    public function index(Request $request)
    {
        $horizon = $request->query('horizon');
        $agency = $request->query('agency');

        $actions = $this->roadmapService->getRoadmapMatrix($horizon, $agency);
        $horizonSummary = $this->roadmapService->getHorizonSummary();

        return view('roadmap.index', compact('actions', 'horizonSummary', 'horizon', 'agency'));
    }

    /**
     * Ekspor seluruh data matriks aksi ke format file CSV.
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $horizon = $request->query('horizon');
        $agency = $request->query('agency');
        $actions = $this->roadmapService->getRoadmapMatrix($horizon, $agency);

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="Matriks_Roadmap_Limbah_B3_Medis_2026_2036.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($actions) {
            $handle = fopen('php://output', 'w');
            
            // UTF-8 BOM untuk kompatibilitas Microsoft Excel
            fputs($handle, "\xEF\xBB\xBF");

            // Header kolom sesuai format Pasal 10 KAK
            fputcsv($handle, [
                'No',
                'Program / Aksi',
                'Baseline (2026)',
                'Target Capaian',
                'Lokasi / Prioritas',
                'Tahap Waktu',
                'Penanggung Jawab',
                'Instansi Pendukung',
                'Kebutuhan Indikatif (Rp)',
                'Indikator Kinerja Utama (KPI)',
                'Output Program',
                'Sumber Pendanaan',
                'Progres (%)',
            ]);

            foreach ($actions as $index => $act) {
                fputcsv($handle, [
                    $index + 1,
                    $act->program_name,
                    $act->baseline,
                    $act->target,
                    $act->priority_location,
                    $act->time_horizon,
                    $act->responsible_agency,
                    $act->supporting_agency,
                    $act->indicative_budget,
                    $act->kpi,
                    $act->program_output,
                    $act->funding_source,
                    $act->progress_percent,
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
