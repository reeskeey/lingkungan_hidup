<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Fasyankes;
use App\Models\Province;
use App\Models\Regency;
use App\Models\WasteGeneration;
use App\Support\UrlCrypt;

class FasyankesApiController extends Controller
{
    /**
     * Mengambil daftar fasyankes untuk katalog dan inspeksi lapangan.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $rawProvinceId = $request->query('province_id');
        $type = $request->query('type');

        $provinceId = $rawProvinceId ? (UrlCrypt::decodeId($rawProvinceId) ?? (is_numeric($rawProvinceId) ? (int)$rawProvinceId : null)) : null;

        $query = Fasyankes::with(['province:id,name', 'regency:id,name', 'latestWasteGeneration'])
            ->orderBy('name', 'asc');

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }
        if ($provinceId) {
            $query->where('province_id', $provinceId);
        }
        if ($type) {
            $query->where('type', $type);
        }

        $list = $query->paginate(20);

        $items = collect($list->items())->map(function ($item) {
            return [
                'id' => $item->encrypted_id,
                'name' => $item->name,
                'type' => $item->type,
                'province' => $item->province->name ?? '',
                'province_id' => $item->province ? $item->province->encrypted_id : null,
                'regency' => $item->regency->name ?? '',
                'regency_id' => $item->regency ? $item->regency->encrypted_id : null,
                'address' => $item->address,
                'latitude' => (float) $item->latitude,
                'longitude' => (float) $item->longitude,
                'bed_capacity' => (int) $item->bed_capacity,
                'tps_permit_status' => $item->tps_permit_status,
                'storage_method' => $item->storage_method,
                'daily_generation_kg' => $item->latestWasteGeneration ? (float) $item->latestWasteGeneration->daily_generation_kg : 0.0,
                'created_at' => $item->created_at ? $item->created_at->toIso8601String() : null,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $items,
            'meta' => [
                'current_page' => $list->currentPage(),
                'last_page' => $list->lastPage(),
                'per_page' => $list->perPage(),
                'total' => $list->total(),
            ],
        ]);
    }

    /**
     * Menyimpan data fasyankes baru hasil inspeksi petugas lapangan via mobile.
     */
    public function store(Request $request)
    {
        $input = $request->all();
        if (isset($input['province_id'])) {
            $input['province_id'] = UrlCrypt::decodeId($input['province_id']) ?? $input['province_id'];
        }
        if (isset($input['regency_id'])) {
            $input['regency_id'] = UrlCrypt::decodeId($input['regency_id']) ?? $input['regency_id'];
        }
        $request->merge($input);

        $validated = $request->validate([
            'name' => 'required|string|max:200',
            'type' => 'required|string|max:50',
            'province_id' => 'required|exists:provinces,id',
            'regency_id' => 'required|exists:regencies,id',
            'address' => 'nullable|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'bed_capacity' => 'required|integer|min:0',
            'tps_permit_status' => 'required|string',
            'storage_method' => 'required|string',
            'daily_generation_kg' => 'nullable|numeric|min:0',
        ]);

        $fasyankes = Fasyankes::create([
            'name' => $validated['name'],
            'type' => $validated['type'],
            'province_id' => $validated['province_id'],
            'regency_id' => $validated['regency_id'],
            'address' => $validated['address'] ?? null,
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'bed_capacity' => $validated['bed_capacity'],
            'tps_permit_status' => $validated['tps_permit_status'],
            'storage_method' => $validated['storage_method'],
        ]);

        $dailyWaste = $validated['daily_generation_kg'] ?? ($validated['bed_capacity'] * 0.8);
        WasteGeneration::create([
            'fasyankes_id' => $fasyankes->id,
            'year' => 2026,
            'daily_generation_kg' => $dailyWaste,
            'annual_generation_ton' => round(($dailyWaste * 365) / 1000, 2),
            'infectious_kg' => round($dailyWaste * 0.65, 2),
            'sharps_kg' => round($dailyWaste * 0.12, 2),
            'pathological_kg' => round($dailyWaste * 0.08, 2),
            'chemical_pharmaceutical_kg' => round($dailyWaste * 0.15, 2),
            'management_method' => ($fasyankes->tps_permit_status === 'Memiliki Izin') ? 'Kerjasama Pengolah Berizin Pihak ke-3' : 'Belum Terkelola Optimal',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data Fasyankes berhasil ditambahkan dari lapangan.',
            'data' => [
                'id' => $fasyankes->encrypted_id,
                'name' => $fasyankes->name,
                'type' => $fasyankes->type,
                'bed_capacity' => $fasyankes->bed_capacity,
                'daily_generation_kg' => $dailyWaste,
            ],
        ], 201);
    }

    /**
     * Mengambil daftar master provinsi untuk dropdown.
     */
    public function provinces()
    {
        $provinces = Province::orderBy('name', 'asc')->get()->map(function ($prov) {
            return [
                'id' => $prov->encrypted_id,
                'name' => $prov->name,
                'latitude' => (float) $prov->latitude,
                'longitude' => (float) $prov->longitude,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $provinces,
        ]);
    }

    /**
     * Mengambil daftar kabupaten/kota untuk provinsi terpilih.
     */
    public function regencies(Province $province)
    {
        $regencies = $province->regencies()->orderBy('name', 'asc')->get()->map(function ($reg) {
            return [
                'id' => $reg->encrypted_id,
                'name' => $reg->name,
                'latitude' => (float) $reg->latitude,
                'longitude' => (float) $reg->longitude,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $regencies,
        ]);
    }
}
