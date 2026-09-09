<?php

namespace App\Services;

use App\Models\Province;
use App\Models\Fasyankes;
use App\Models\TreatmentFacility;
use App\Models\TransferLocation;
use App\Models\RegionalCapacityGap;

class GeoSpatialService
{
    /**
     * Mengambil payload data spasial lengkap untuk visualisasi peta Leaflet.
     */
    public function getMapFeatures(?int $provinceId = null, ?string $fasyankesType = null, ?string $gapStatus = null): array
    {
        // 1. Data Fasyankes
        $fasyankesQuery = Fasyankes::with(['province:id,name', 'regency:id,name', 'latestWasteGeneration'])
            ->select('id', 'name', 'type', 'province_id', 'regency_id', 'address', 'latitude', 'longitude', 'bed_capacity', 'tps_permit_status', 'storage_method');

        if ($provinceId) {
            $fasyankesQuery->where('province_id', $provinceId);
        }
        if ($fasyankesType) {
            $fasyankesQuery->where('type', $fasyankesType);
        }

        $fasyankesList = $fasyankesQuery->get()->map(function ($item) {
            $waste = $item->latestWasteGeneration;
            return [
                'id' => $item->encrypted_id,
                'name' => $item->name,
                'type' => $item->type,
                'province' => $item->province->name ?? '',
                'regency' => $item->regency->name ?? '',
                'address' => $item->address,
                'lat' => (float) $item->latitude,
                'lng' => (float) $item->longitude,
                'beds' => $item->bed_capacity,
                'permit_status' => $item->tps_permit_status,
                'storage_method' => $item->storage_method,
                'daily_waste_kg' => $waste ? (float) $waste->daily_generation_kg : 0.0,
                'annual_waste_ton' => $waste ? (float) $waste->annual_generation_ton : 0.0,
                'infectious_kg' => $waste ? (float) $waste->infectious_kg : 0.0,
            ];
        });

        // 2. Data Fasilitas Pengolahan
        $facilityQuery = TreatmentFacility::with(['province:id,name', 'regency:id,name'])
            ->select('id', 'name', 'facility_type', 'operator_category', 'province_id', 'regency_id', 'latitude', 'longitude', 'installed_capacity_kg_h', 'licensed_capacity_ton_day', 'permit_number', 'operational_status');

        if ($provinceId) {
            $facilityQuery->where('province_id', $provinceId);
        }

        $treatmentFacilities = $facilityQuery->get()->map(function ($fac) {
            return [
                'id' => $fac->encrypted_id,
                'name' => $fac->name,
                'type' => $fac->facility_type,
                'category' => $fac->operator_category,
                'province' => $fac->province->name ?? '',
                'regency' => $fac->regency->name ?? '',
                'lat' => (float) $fac->latitude,
                'lng' => (float) $fac->longitude,
                'installed_cap_kg_h' => (float) $fac->installed_capacity_kg_h,
                'licensed_cap_ton_day' => (float) $fac->licensed_capacity_ton_day,
                'permit_number' => $fac->permit_number,
                'status' => $fac->operational_status,
                'buffer_radius_meters' => [50000, 100000], // 50 km dan 100 km radius
            ];
        });

        // 3. Data Lokasi Pemindahan
        $transferQuery = TransferLocation::with(['province:id,name', 'regency:id,name'])
            ->select('id', 'name', 'province_id', 'regency_id', 'address', 'latitude', 'longitude', 'holding_capacity_ton', 'has_cold_storage', 'service_status', 'target_served_fasyankes');

        if ($provinceId) {
            $transferQuery->where('province_id', $provinceId);
        }

        $transferLocations = $transferQuery->get()->map(function ($loc) {
            return [
                'id' => $loc->encrypted_id,
                'name' => $loc->name,
                'province' => $loc->province->name ?? '',
                'regency' => $loc->regency->name ?? '',
                'address' => $loc->address,
                'lat' => (float) $loc->latitude,
                'lng' => (float) $loc->longitude,
                'holding_cap_ton' => (float) $loc->holding_capacity_ton,
                'has_cold_storage' => (bool) $loc->has_cold_storage,
                'status' => $loc->service_status,
                'target_fasyankes' => (int) $loc->target_served_fasyankes,
            ];
        });

        // 4. Data Provinsi untuk Choropleth & Statistik
        $provinces = Province::with(['capacityGap'])->get()->map(function ($prov) {
            $gap = $prov->capacityGap->first();
            return [
                'id' => $prov->encrypted_id,
                'code' => $prov->code,
                'name' => $prov->name,
                'lat' => (float) $prov->latitude,
                'lng' => (float) $prov->longitude,
                'zoom' => (int) $prov->zoom_level,
                'total_waste' => $gap ? (float) $gap->total_waste_ton_day : 0.0,
                'total_capacity' => $gap ? (float) $gap->total_treatment_capacity_ton_day : 0.0,
                'gap' => $gap ? (float) $gap->capacity_gap_ton_day : 0.0,
                'coverage_ratio' => $gap ? (float) $gap->coverage_ratio_percent : 0.0,
                'status' => $gap ? $gap->status : 'Belum Terhitung',
                'priority_level' => $gap ? $gap->priority_level : 'Prioritas 2',
            ];
        });

        if ($gapStatus) {
            $provinces = $provinces->where('status', $gapStatus)->values();
        }

        return [
            'fasyankes' => $fasyankesList,
            'treatment_facilities' => $treatmentFacilities,
            'transfer_locations' => $transferLocations,
            'provinces' => $provinces,
        ];
    }
}
