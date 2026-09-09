<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TreatmentFacility;
use App\Models\Province;
use App\Models\Regency;

class TreatmentFacilityController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $type = $request->query('type');
        $provinceId = $request->query('province_id');

        $query = TreatmentFacility::with(['province:id,name', 'regency:id,name'])
            ->orderBy('name', 'asc');

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }
        if ($type) {
            $query->where('facility_type', $type);
        }
        if ($provinceId) {
            $query->where('province_id', $provinceId);
        }

        $facilities = $query->paginate(15)->withQueryString();
        $provinces = Province::orderBy('name', 'asc')->get();

        return view('treatment-facilities.index', compact('facilities', 'provinces', 'search', 'type', 'provinceId'));
    }

    public function create()
    {
        $provinces = Province::orderBy('name', 'asc')->get();
        $regencies = Regency::orderBy('name', 'asc')->get();
        return view('treatment-facilities.create', compact('provinces', 'regencies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:200',
            'facility_type' => 'required|string',
            'operator_category' => 'required|string',
            'province_id' => 'required|exists:provinces,id',
            'regency_id' => 'required|exists:regencies,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'installed_capacity_kg_h' => 'required|numeric|min:0',
            'licensed_capacity_ton_day' => 'required|numeric|min:0',
            'permit_number' => 'nullable|string',
            'operational_status' => 'required|string',
        ]);

        TreatmentFacility::create($validated);

        return redirect()->route('treatment-facilities.index')->with('success', 'Fasilitas Pengolahan berhasil ditambahkan!');
    }

    public function destroy(TreatmentFacility $treatmentFacility)
    {
        $treatmentFacility->delete();
        return redirect()->route('treatment-facilities.index')->with('success', 'Fasilitas Pengolahan berhasil dihapus!');
    }
}
