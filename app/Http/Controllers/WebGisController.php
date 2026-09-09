<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Province;
use App\Services\GeoSpatialService;
use App\Services\GapAnalysisService;

class WebGisController extends Controller
{
    protected GeoSpatialService $geoService;
    protected GapAnalysisService $gapService;

    public function __construct(GeoSpatialService $geoService, GapAnalysisService $gapService)
    {
        $this->geoService = $geoService;
        $this->gapService = $gapService;
    }

    /**
     * Tampilan utama WebGIS Nasional.
     */
    public function index(Request $request)
    {
        $provinces = Province::orderBy('name', 'asc')->get();
        $nationalSummary = $this->gapService->getNationalSummary();

        return view('webgis.index', compact('provinces', 'nationalSummary'));
    }

    /**
     * Endpoint API JSON untuk data titik peta (Fasyankes, Pengolah, Lokasi Pemindahan, Choropleth).
     */
    public function apiGeoData(Request $request)
    {
        $provinceId = $request->query('province_id') ? (int) $request->query('province_id') : null;
        $fasyankesType = $request->query('fasyankes_type') ?: null;
        $gapStatus = $request->query('gap_status') ?: null;

        $features = $this->geoService->getMapFeatures($provinceId, $fasyankesType, $gapStatus);

        return response()->json($features);
    }
}
