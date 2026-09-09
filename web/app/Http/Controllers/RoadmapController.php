<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RoadmapAction;
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
     * Memperbarui persentase capaian progres rencana aksi (Akses Cepat).
     */
    public function updateProgress(Request $request, RoadmapAction $action)
    {
        $validated = $request->validate([
            'progress_percent' => 'required|integer|min:0|max:100',
        ]);

        $action->update([
            'progress_percent' => $validated['progress_percent'],
        ]);

        return redirect()->back()->with('success', "Progres rencana aksi '{$action->program_name}' berhasil diperbarui menjadi {$action->progress_percent}%!");
    }

    /**
     * Menyimpan butir rencana aksi baru (Khusus Superadmin).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'program_name' => 'required|string|max:255',
            'baseline' => 'nullable|string',
            'target' => 'nullable|string',
            'priority_location' => 'required|string|max:255',
            'time_horizon' => 'required|string',
            'responsible_agency' => 'required|string|max:150',
            'supporting_agency' => 'nullable|string|max:255',
            'indicative_budget' => 'required|numeric|min:0',
            'kpi' => 'nullable|string',
            'program_output' => 'nullable|string',
            'funding_source' => 'required|string|max:100',
            'progress_percent' => 'nullable|integer|min:0|max:100',
        ]);

        RoadmapAction::create($validated);

        return redirect()->route('roadmap.index')->with('success', 'Butir Rencana Aksi baru berhasil ditambahkan!');
    }

    /**
     * Menghapus butir rencana aksi (Khusus Superadmin).
     */
    public function destroy(RoadmapAction $action)
    {
        $action->delete();
        return redirect()->route('roadmap.index')->with('success', 'Rencana Aksi berhasil dihapus!');
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
