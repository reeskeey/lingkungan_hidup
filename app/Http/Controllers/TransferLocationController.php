<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransferLocation;
use App\Models\Province;
use App\Models\Regency;

class TransferLocationController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $provinceId = $request->query('province_id');

        $query = TransferLocation::with(['province:id,name', 'regency:id,name'])
            ->orderBy('name', 'asc');

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }
        if ($provinceId) {
            $query->where('province_id', $provinceId);
        }

        $locations = $query->paginate(15)->withQueryString();
        $provinces = Province::orderBy('name', 'asc')->get();

        return view('transfer-locations.index', compact('locations', 'provinces', 'search', 'provinceId'));
    }

    public function create()
    {
        $provinces = Province::orderBy('name', 'asc')->get();
        $regencies = Regency::orderBy('name', 'asc')->get();
        return view('transfer-locations.create', compact('provinces', 'regencies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:200',
            'province_id' => 'required|exists:provinces,id',
            'regency_id' => 'required|exists:regencies,id',
            'address' => 'nullable|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'holding_capacity_ton' => 'required|numeric|min:0',
            'has_cold_storage' => 'boolean',
            'service_status' => 'required|string',
            'target_served_fasyankes' => 'required|integer|min:0',
        ]);

        $validated['has_cold_storage'] = $request->has('has_cold_storage');

        TransferLocation::create($validated);

        return redirect()->route('transfer-locations.index')->with('success', 'Lokasi Pemindahan berhasil ditambahkan!');
    }

    public function destroy(TransferLocation $transferLocation)
    {
        $transferLocation->delete();
        return redirect()->route('transfer-locations.index')->with('success', 'Lokasi Pemindahan berhasil dihapus!');
    }
}
