@extends('layouts.app')

@section('title', 'Analisis Kesenjangan Kapasitas Pengolahan - KLH / BPLH')

@section('content')
<div class="container-fluid py-4 px-4">
    <!-- Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <div>
            <h4 class="fw-bold text-dark mb-1 d-flex align-items-center">
                <i class="bi bi-pie-chart-fill text-success me-2"></i> Analisis Kesenjangan Kapasitas Pengolahan (Capacity Gap)
            </h4>
            <p class="text-muted small mb-0">Neraca Komparasi Timbulan Limbah Medis terhadap Kapasitas Pengolahan Aktif per Wilayah Provinsi</p>
        </div>
        <div class="mt-2 mt-md-0 d-flex gap-2">
            <a href="{{ url('/webgis') }}" class="btn btn-sm btn-outline-success">
                <i class="bi bi-geo-alt me-1"></i> Lihat Visualisasi Peta
            </a>
            <a href="{{ url('/dashboard') }}" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-bar-chart-line me-1"></i> Dashboard Eksekutif
            </a>
        </div>
    </div>

    <!-- Filter Bar Card -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-3 bg-light rounded">
            <form method="GET" action="{{ url('/gap-analysis') }}" class="row g-2 align-items-center">
                <div class="col-12 col-md-4">
                    <label class="form-label small fw-semibold text-muted mb-1">Filter Gugus Kepulauan / Regional</label>
                    <select name="island" class="form-select form-select-sm">
                        <option value="all">-- Seluruh Wilayah Indonesia --</option>
                        <option value="sumatera" {{ $island === 'sumatera' ? 'selected' : '' }}>Pulau Sumatera (10 Provinsi)</option>
                        <option value="jawa" {{ $island === 'jawa' ? 'selected' : '' }}>Pulau Jawa & Banten (6 Provinsi)</option>
                        <option value="bali_nusa" {{ $island === 'bali_nusa' ? 'selected' : '' }}>Kepulauan Bali & Nusa Tenggara (3 Provinsi)</option>
                        <option value="kalimantan" {{ $island === 'kalimantan' ? 'selected' : '' }}>Pulau Kalimantan (5 Provinsi)</option>
                        <option value="sulawesi" {{ $island === 'sulawesi' ? 'selected' : '' }}>Pulau Sulawesi (6 Provinsi)</option>
                        <option value="maluku_papua" {{ $island === 'maluku_papua' ? 'selected' : '' }}>Kepulauan Maluku & Papua (8 Provinsi)</option>
                    </select>
                </div>

                <div class="col-12 col-md-4">
                    <label class="form-label small fw-semibold text-muted mb-1">Filter Status Neraca Kesenjangan</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="all">-- Semua Status Neraca --</option>
                        <option value="Defisit Kritis" {{ $status === 'Defisit Kritis' ? 'selected' : '' }}>🔴 Defisit Kritis (Rasio < 50%)</option>
                        <option value="Defisit Sedang" {{ $status === 'Defisit Sedang' ? 'selected' : '' }}>🟠 Defisit Sedang (50% - 99%)</option>
                        <option value="Surplus" {{ $status === 'Surplus' ? 'selected' : '' }}>🟢 Surplus (≥ 100%)</option>
                    </select>
                </div>

                <div class="col-12 col-md-4 d-flex align-items-end gap-2 mt-3 mt-md-auto">
                    <button type="submit" class="btn btn-sm btn-success flex-grow-1">
                        <i class="bi bi-funnel-fill me-1"></i> Terapkan Filter
                    </button>
                    <a href="{{ url('/gap-analysis') }}" class="btn btn-sm btn-outline-secondary" title="Reset">
                        <i class="bi bi-arrow-clockwise"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Table of Provinces -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0 text-dark">
                <i class="bi bi-table text-primary me-2"></i> Neraca Wilayah Provinsi: Timbulan vs Kapasitas Pengolahan
            </h6>
            <span class="badge bg-secondary">Total: {{ count($regionalGaps) }} Provinsi Ditampilkan</span>
        </div>
        <div class="table-responsive">
            <table class="table table-gov table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 50px;">No</th>
                        <th>Provinsi</th>
                        <th class="text-end">Timbulan (ton/hari)</th>
                        <th class="text-end">Kapasitas (ton/hari)</th>
                        <th class="text-end">Gap / Selisih</th>
                        <th class="text-center">Ketercakupan</th>
                        <th class="text-center">Status Wilayah</th>
                        <th>Prioritas & Rekomendasi Arah Kebijakan</th>
                        <th class="text-center" style="width: 80px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($regionalGaps as $index => $gap)
                    <tr>
                        <td class="text-center text-muted small">{{ $index + 1 }}</td>
                        <td>
                            <strong class="text-dark">{{ $gap['province_name'] }}</strong>
                            <div class="text-muted" style="font-size: 0.72rem;">Kode BPS: {{ $gap['province_code'] }}</div>
                        </td>
                        <td class="text-end fw-semibold text-danger">
                            {{ number_format($gap['total_waste'], 2) }}
                        </td>
                        <td class="text-end fw-semibold text-success">
                            {{ number_format($gap['total_capacity'], 2) }}
                        </td>
                        <td class="text-end fw-bold {{ $gap['gap'] < 0 ? 'text-danger' : 'text-success' }}">
                            {{ $gap['gap'] > 0 ? '+' : '' }}{{ number_format($gap['gap'], 2) }}
                        </td>
                        <td class="text-center" style="min-width: 140px;">
                            <div class="d-flex justify-content-between small text-muted mb-1">
                                <span>Rasio:</span>
                                <strong>{{ $gap['coverage_ratio'] }}%</strong>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar {{ $gap['coverage_ratio'] < 50 ? 'bg-danger' : ($gap['coverage_ratio'] < 100 ? 'bg-warning' : 'bg-success') }}" 
                                     style="width: {{ min(100, $gap['coverage_ratio']) }}%;"></div>
                            </div>
                        </td>
                        <td class="text-center">
                            @if($gap['status'] === 'Defisit Kritis')
                                <span class="badge badge-deficit-kritis px-2 py-1"><i class="bi bi-x-circle me-1"></i> Defisit Kritis</span>
                            @elseif($gap['status'] === 'Defisit Sedang')
                                <span class="badge badge-deficit-sedang px-2 py-1"><i class="bi bi-exclamation-triangle me-1"></i> Defisit Sedang</span>
                            @else
                                <span class="badge badge-surplus px-2 py-1"><i class="bi bi-check-circle me-1"></i> Surplus</span>
                            @endif
                        </td>
                        <td>
                            <div class="small">
                                @if($gap['status'] === 'Defisit Kritis')
                                    <span class="badge bg-danger-subtle text-danger mb-1">{{ $gap['priority_level'] }}</span>
                                    <div class="text-muted">Perlu percepatan pembangunan fasilitas pengolah regional & depo cold storage.</div>
                                @elseif($gap['status'] === 'Defisit Sedang')
                                    <span class="badge bg-warning-subtle text-warning-emphasis mb-1">{{ $gap['priority_level'] }}</span>
                                    <div class="text-muted">Optimalisasi rute angkut transporter dan penguatan izin TPS fasyankes.</div>
                                @else
                                    <span class="badge bg-success-subtle text-success mb-1">{{ $gap['priority_level'] }}</span>
                                    <div class="text-muted">Fasilitas mencukupi kebutuhan internal; dapat melayani limpahan limbah provinsi sekitar.</div>
                                @endif
                            </div>
                        </td>
                        <td class="text-center">
                            <a href="{{ url('/webgis?province_id=' . $gap['province_id']) }}" class="btn btn-sm btn-outline-success p-1 px-2" title="Lihat di Peta WebGIS">
                                <i class="bi bi-pin-map-fill"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">
                            <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                            Tidak ada data wilayah yang sesuai dengan filter yang dipilih.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
