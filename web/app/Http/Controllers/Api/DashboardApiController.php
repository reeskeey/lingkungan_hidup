<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\GapAnalysisService;
use App\Services\RoadmapService;

class DashboardApiController extends Controller
{
    protected GapAnalysisService $gapService;
    protected RoadmapService $roadmapService;

    public function __construct(GapAnalysisService $gapService, RoadmapService $roadmapService)
    {
        $this->gapService = $gapService;
        $this->roadmapService = $roadmapService;
    }

    /**
     * Ringkasan KPI dan Analitik Dashboard Nasional.
     */
    public function summary(Request $request)
    {
        $kpi = $this->gapService->getNationalSummary();
        $wasteComposition = $this->gapService->getWasteCompositionSummary();
        $topDeficits = $this->gapService->getTopDeficitProvinces(10);
        $tenYearProjections = $this->roadmapService->getTenYearProjections();
        $horizonSummary = $this->roadmapService->getHorizonSummary();

        return response()->json([
            'success' => true,
            'data' => [
                'kpi' => $kpi,
                'waste_composition' => $wasteComposition,
                'top_deficits' => $topDeficits,
                'ten_year_projections' => $tenYearProjections,
                'horizon_summary' => $horizonSummary,
            ],
        ]);
    }
}
