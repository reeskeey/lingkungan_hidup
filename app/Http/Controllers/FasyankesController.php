<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fasyankes;
use App\Models\Province;
use App\Models\Regency;
use App\Models\WasteGeneration;

class FasyankesController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $provinceId = $request->query('province_id');
        $type = $request->query('type');

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

        $fasyankesList = $query->paginate(15)->withQueryString();
        $provinces = Province::orderBy('name', 'asc')->get();

        return view('fasyankes.index', compact('fasyankesList', 'provinces', 'search', 'provinceId', 'type'));
    }

    public function create()
    {
        $provinces = Province::orderBy('name', 'asc')->get();
        $regencies = Regency::orderBy('name', 'asc')->get();

        return view('fasyankes.create', compact('provinces', 'regencies'));
    }

    public function store(Request $request)
    {
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
            'address' => $validated['address'],
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

        return redirect()->route('fasyankes.index')->with('success', 'Data Fasyankes berhasil ditambahkan!');
    }

    public function edit(Fasyankes $fasyanke)
    {
        $fasyankes = $fasyanke->load('latestWasteGeneration');
        $provinces = Province::orderBy('name', 'asc')->get();
        $regencies = Regency::where('province_id', $fasyankes->province_id)->orderBy('name', 'asc')->get();

        return view('fasyankes.edit', compact('fasyankes', 'provinces', 'regencies'));
    }

    public function update(Request $request, Fasyankes $fasyanke)
    {
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

        $fasyanke->update($validated);

        if (isset($validated['daily_generation_kg'])) {
            $waste = $fasyanke->latestWasteGeneration;
            $dailyWaste = $validated['daily_generation_kg'];
            if ($waste) {
                $waste->update([
                    'daily_generation_kg' => $dailyWaste,
                    'annual_generation_ton' => round(($dailyWaste * 365) / 1000, 2),
                ]);
            }
        }

        return redirect()->route('fasyankes.index')->with('success', 'Data Fasyankes berhasil diperbarui!');
    }

    public function destroy(Fasyankes $fasyanke)
    {
        $fasyanke->delete();
        return redirect()->route('fasyankes.index')->with('success', 'Data Fasyankes berhasil dihapus!');
    }
}
