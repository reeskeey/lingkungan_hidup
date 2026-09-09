<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RoadmapAction;
use App\Services\RoadmapService;

class RoadmapApiController extends Controller
{
    protected RoadmapService $roadmapService;

    public function __construct(RoadmapService $roadmapService)
    {
        $this->roadmapService = $roadmapService;
    }

    /**
     * Mengambil matriks rencana aksi 10 tahun.
     */
    public function index(Request $request)
    {
        $horizon = $request->query('horizon');
        $agency = $request->query('agency');

        $actions = $this->roadmapService->getRoadmapMatrix($horizon, $agency)->map(function ($act) {
            return [
                'id' => $act->encrypted_id,
                'program_name' => $act->program_name,
                'baseline' => $act->baseline,
                'target' => $act->target,
                'priority_location' => $act->priority_location,
                'time_horizon' => $act->time_horizon,
                'responsible_agency' => $act->responsible_agency,
                'supporting_agency' => $act->supporting_agency,
                'indicative_budget' => (float) $act->indicative_budget,
                'kpi' => $act->kpi,
                'program_output' => $act->program_output,
                'funding_source' => $act->funding_source,
                'progress_percent' => (int) $act->progress_percent,
            ];
        });

        $summary = $this->roadmapService->getHorizonSummary();

        return response()->json([
            'success' => true,
            'data' => [
                'actions' => $actions,
                'summary' => $summary,
            ],
        ]);
    }

    /**
     * Memperbarui capaian progres rencana aksi dari mobile.
     */
    public function updateProgress(Request $request, RoadmapAction $action)
    {
        $validated = $request->validate([
            'progress_percent' => 'required|integer|min:0|max:100',
        ]);

        $action->update([
            'progress_percent' => $validated['progress_percent'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Progres aksi berhasil diperbarui.',
            'data' => [
                'id' => $action->encrypted_id,
                'program_name' => $action->program_name,
                'progress_percent' => $action->progress_percent,
            ],
        ]);
    }
}
