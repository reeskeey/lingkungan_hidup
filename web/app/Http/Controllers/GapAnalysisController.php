<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GapAnalysisService;

class GapAnalysisController extends Controller
{
    protected GapAnalysisService $gapService;

    public function __construct(GapAnalysisService $gapService)
    {
        $this->gapService = $gapService;
    }

    public function index(Request $request)
    {
        $status = $request->query('status');
        $island = $request->query('island');

        $nationalSummary = $this->gapService->getNationalSummary();
        $regionalGaps = $this->gapService->getRegionalGaps($status, $island);

        return view('gap-analysis.index', compact(
            'nationalSummary',
            'regionalGaps',
            'status',
            'island'
        ));
    }
}
