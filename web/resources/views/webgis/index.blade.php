@extends('layouts.app')

@section('title', 'WebGIS Nasional Limbah B3 Medis Fasyankes')

@section('content')
<div class="webgis-container">
    <!-- Map Canvas -->
    <div id="webgis-map"></div>

    <!-- Floating Filter & Layer Control Panel -->
    <div class="map-sidebar-card p-3">
        <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
            <h6 class="fw-bold mb-0 text-success d-flex align-items-center">
                <i class="bi bi-layers-fill me-2"></i> Kontrol Lapisan & Filter
            </h6>
            <button class="btn btn-sm btn-outline-secondary py-0 px-2" type="button" data-bs-toggle="collapse" data-bs-target="#filterBody" aria-expanded="true" title="Minimize / Expand">
                <i class="bi bi-chevron-up"></i>
            </button>
        </div>

        <div class="collapse show" id="filterBody">
            <!-- Filter Form -->
            <form id="mapFilterForm" class="mb-3">
                <div class="mb-2">
                    <label class="form-label small fw-semibold text-muted mb-1">Provinsi / Wilayah</label>
                    <select class="form-select form-select-sm" id="filterProvince">
                        <option value="">-- Seluruh Indonesia (38 Provinsi) --</option>
                        @foreach($provinces as $prov)
                            <option value="{{ $prov->encrypted_id }}" data-lat="{{ $prov->latitude }}" data-lng="{{ $prov->longitude }}" data-zoom="{{ $prov->zoom_level }}">
                                {{ $prov->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-2">
                    <label class="form-label small fw-semibold text-muted mb-1">Jenis Fasyankes</label>
                    <select class="form-select form-select-sm" id="filterType">
                        <option value="">-- Semua Jenis Fasilitas --</option>
                        <option value="RS Kelas A">Rumah Sakit Kelas A</option>
                        <option value="RS Kelas B">Rumah Sakit Kelas B</option>
                        <option value="RS Kelas C">Rumah Sakit Kelas C</option>
                        <option value="RS Kelas D">Rumah Sakit Kelas D</option>
                        <option value="Puskesmas">Puskesmas</option>
                        <option value="Klinik Pratama">Klinik Pratama / Utama</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold text-muted mb-1">Status Kesenjangan Wilayah</label>
                    <select class="form-select form-select-sm" id="filterGapStatus">
                        <option value="">-- Semua Status Wilayah --</option>
                        <option value="Defisit Kritis">🔴 Defisit Kritis (< 50%)</option>
                        <option value="Defisit Sedang">🟠 Defisit Sedang (50 - 99%)</option>
                        <option value="Surplus">🟢 Surplus / Aman (>= 100%)</option>
                    </select>
                </div>

                <div class="d-grid gap-2 d-flex">
                    <button type="button" class="btn btn-sm btn-success flex-grow-1" id="btnApplyFilter">
                        <i class="bi bi-funnel-fill me-1"></i> Terapkan
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="btnResetFilter" title="Reset Filter">
                        <i class="bi bi-arrow-clockwise"></i> Reset
                    </button>
                </div>
            </form>

            <hr class="my-2">

            <!-- Layer Switches -->
            <div class="small fw-semibold text-muted mb-2">Lapisan Tematik Peta:</div>
            <div class="form-check form-switch mb-1 small">
                <input class="form-check-input" type="checkbox" id="layerFasyankes" checked>
                <label class="form-check-label d-flex justify-content-between align-items-center" for="layerFasyankes">
                    <span><i class="bi bi-hospital text-danger me-1"></i> Titik Fasyankes</span>
                    <span class="badge bg-danger-subtle text-danger" id="countFasyankes">0</span>
                </label>
            </div>
            <div class="form-check form-switch mb-1 small">
                <input class="form-check-input" type="checkbox" id="layerTreatment" checked>
                <label class="form-check-label d-flex justify-content-between align-items-center" for="layerTreatment">
                    <span><i class="bi bi-fire text-warning me-1"></i> Pengolah B3 Berizin</span>
                    <span class="badge bg-warning-subtle text-warning-emphasis" id="countTreatment">0</span>
                </label>
            </div>
            <div class="form-check form-switch mb-1 small">
                <input class="form-check-input" type="checkbox" id="layerTransfer" checked>
                <label class="form-check-label d-flex justify-content-between align-items-center" for="layerTransfer">
                    <span><i class="bi bi-box-seam text-primary me-1"></i> Lokasi Pemindahan</span>
                    <span class="badge bg-primary-subtle text-primary" id="countTransfer">0</span>
                </label>
            </div>
            <div class="form-check form-switch mb-1 small">
                <input class="form-check-input" type="checkbox" id="layerBuffer" checked>
                <label class="form-check-label" for="layerBuffer">
                    <i class="bi bi-record-circle text-success me-1"></i> Radius Buffer Jangkauan (50 & 100km)
                </label>
            </div>
            <div class="form-check form-switch mb-2 small">
                <input class="form-check-input" type="checkbox" id="layerChoropleth" checked>
                <label class="form-check-label" for="layerChoropleth">
                    <i class="bi bi-grid-fill text-info me-1"></i> Peta Choropleth Defisit Provinsi
                </label>
            </div>

            <!-- Quick Stats in panel -->
            <div class="bg-light p-2 rounded small mt-3 border">
                <div class="fw-bold text-dark mb-1"><i class="bi bi-info-circle me-1 text-primary"></i> Neraca Nasional:</div>
                <div class="d-flex justify-content-between text-muted mb-1">
                    <span>Timbulan Nasional:</span>
                    <strong class="text-dark">{{ number_format($nationalSummary['total_waste_ton_day'], 1) }} ton/hari</strong>
                </div>
                <div class="d-flex justify-content-between text-muted mb-1">
                    <span>Kapasitas Izin:</span>
                    <strong class="text-success">{{ number_format($nationalSummary['total_capacity_ton_day'], 1) }} ton/hari</strong>
                </div>
                <div class="d-flex justify-content-between text-muted">
                    <span>Rasio Ketercakupan:</span>
                    <strong class="{{ $nationalSummary['national_coverage_ratio'] < 100 ? 'text-danger' : 'text-success' }}">
                        {{ $nationalSummary['national_coverage_ratio'] }}%
                    </strong>
                </div>
            </div>
        </div>
    </div>

    <!-- Map Legend -->
    <div class="map-legend-card">
        <div class="fw-bold text-dark mb-2 pb-1 border-bottom d-flex justify-content-between align-items-center">
            <span>Legenda Peta WebGIS</span>
            <i class="bi bi-map text-muted"></i>
        </div>
        <div class="legend-item">
            <span class="legend-color bg-danger"></span>
            <span>Fasyankes (RS / Puskesmas / Klinik)</span>
        </div>
        <div class="legend-item">
            <span class="legend-color bg-warning"></span>
            <span>Pengolah Berizin (Insinerator/Autoklaf)</span>
        </div>
        <div class="legend-item">
            <span class="legend-color bg-primary"></span>
            <span>Lokasi Pemindahan (Depo B3)</span>
        </div>
        <div class="legend-item">
            <span class="legend-color border border-success" style="background-color: rgba(46, 125, 50, 0.2);"></span>
            <span>Radius Layanan Pengolah (50 - 100 km)</span>
        </div>
        <div class="border-top pt-1 mt-1">
            <div class="text-muted fw-semibold mb-1" style="font-size: 0.72rem;">Status Defisit Provinsi:</div>
            <div class="d-flex align-items-center justify-content-between" style="font-size: 0.72rem;">
                <span class="badge badge-deficit-kritis px-2">Defisit Kritis</span>
                <span class="badge badge-deficit-sedang px-2">Defisit Sedang</span>
                <span class="badge badge-surplus px-2">Surplus</span>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Profil Fasyankes / Fasilitas -->
<div class="modal fade" id="modalFeatureDetail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-success text-white">
                <h6 class="modal-title fw-bold" id="modalDetailTitle">Detail Profil Fasilitas</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalDetailBody">
                <!-- Injected via JavaScript -->
            </div>
            <div class="modal-footer py-1 bg-light">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/webgis-map.js') }}"></script>
@endpush
