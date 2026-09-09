<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GapAnalysisService;
use App\Services\RoadmapService;

class DashboardController extends Controller
{
    protected GapAnalysisService $gapService;
    protected RoadmapService $roadmapService;

    public function __construct(GapAnalysisService $gapService, RoadmapService $roadmapService)
    {
        $this->gapService = $gapService;
        $this->roadmapService = $roadmapService;
    }

    public function index()
    {
        $nationalSummary = $this->gapService->getNationalSummary();
        $wasteComposition = $this->gapService->getWasteCompositionSummary();
        $topDeficits = $this->gapService->getTopDeficitProvinces(10);
        $tenYearProjections = $this->roadmapService->getTenYearProjections();
        $horizonSummary = $this->roadmapService->getHorizonSummary();

        return view('dashboard.index', compact(
            'nationalSummary',
            'wasteComposition',
            'topDeficits',
            'tenYearProjections',
            'horizonSummary'
        ));
    }
}
