<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\GeoSpatialService;
use App\Support\UrlCrypt;

class WebGisApiController extends Controller
{
    protected GeoSpatialService $geoService;

    public function __construct(GeoSpatialService $geoService)
    {
        $this->geoService = $geoService;
    }

    /**
     * Endpoint data geospasial untuk mobile GIS.
     */
    public function data(Request $request)
    {
        $rawProvinceId = $request->query('province_id');
        $provinceId = $rawProvinceId ? (UrlCrypt::decodeId($rawProvinceId) ?? (is_numeric($rawProvinceId) ? (int)$rawProvinceId : null)) : null;
        $fasyankesType = $request->query('fasyankes_type') ?: null;
        $gapStatus = $request->query('gap_status') ?: null;

        $features = $this->geoService->getMapFeatures($provinceId, $fasyankesType, $gapStatus);

        return response()->json([
            'success' => true,
            'data' => $features,
        ]);
    }
}
