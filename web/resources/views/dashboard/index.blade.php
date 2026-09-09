@extends('layouts.app')

@section('title', 'Dashboard Eksekutif Limbah B3 Medis')

@section('content')
<div class="container-fluid py-4 px-4">
    <!-- Breadcrumb & Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <div>
            <h4 class="fw-bold text-dark mb-1 d-flex align-items-center">
                <i class="bi bi-speedometer2 text-success me-2"></i> Dashboard Eksekutif Limbah B3 Medis
            </h4>
            <p class="text-muted small mb-0">Pemantauan Baseline Nasional, Kinerja Pengolahan, dan Proyeksi Roadmap 10 Tahun (2026 - 2036)</p>
        </div>
        <div class="mt-2 mt-md-0 d-flex gap-2">
            <a href="{{ url('/webgis') }}" class="btn btn-sm btn-outline-success">
                <i class="bi bi-geo-alt me-1"></i> Buka Peta Spasial
            </a>
            <a href="{{ url('/gap-analysis') }}" class="btn btn-sm btn-success">
                <i class="bi bi-pie-chart me-1"></i> Analisis Gap Detail
            </a>
        </div>
    </div>

    <!-- 5 KPI Cards -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-2-4 col-lg-3">
            <div class="card card-kpi p-3 h-100 bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small fw-semibold">Timbulan Nasional</div>
                        <h4 class="fw-bold text-dark mb-0 mt-1">{{ number_format($nationalSummary['total_waste_ton_day'], 1) }} <span class="fs-6 fw-normal text-muted">ton/hari</span></h4>
                        <div class="text-muted" style="font-size: 0.75rem;">≈ {{ number_format($nationalSummary['total_waste_ton_year'], 0) }} ton/tahun</div>
                    </div>
                    <div class="kpi-icon bg-danger-subtle text-danger">
                        <i class="bi bi-trash3-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-2-4 col-lg-3">
            <div class="card card-kpi p-3 h-100 bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small fw-semibold">Kapasitas Olah Aktif</div>
                        <h4 class="fw-bold text-success mb-0 mt-1">{{ number_format($nationalSummary['total_capacity_ton_day'], 1) }} <span class="fs-6 fw-normal text-muted">ton/hari</span></h4>
                        <div class="text-muted" style="font-size: 0.75rem;">{{ $nationalSummary['total_treatment_facility'] }} fasilitas berizin</div>
                    </div>
                    <div class="kpi-icon bg-success-subtle text-success">
                        <i class="bi bi-fire"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-2-4 col-lg-3">
            <div class="card card-kpi p-3 h-100 bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small fw-semibold">Rasio Ketercakupan</div>
                        <h4 class="fw-bold {{ $nationalSummary['national_coverage_ratio'] < 100 ? 'text-danger' : 'text-success' }} mb-0 mt-1">
                            {{ $nationalSummary['national_coverage_ratio'] }}%
                        </h4>
                        <div class="text-muted" style="font-size: 0.75rem;">Target Roadmap: ≥ 120%</div>
                    </div>
                    <div class="kpi-icon bg-primary-subtle text-primary">
                        <i class="bi bi-percent"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-2-4 col-lg-3">
            <div class="card card-kpi p-3 h-100 bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small fw-semibold">Kepatuhan Izin TPS</div>
                        <h4 class="fw-bold text-dark mb-0 mt-1">{{ $nationalSummary['licensed_tps_percent'] }}%</h4>
                        <div class="text-muted" style="font-size: 0.75rem;">{{ $nationalSummary['licensed_tps_count'] }} dari {{ $nationalSummary['total_fasyankes'] }} Fasyankes</div>
                    </div>
                    <div class="kpi-icon bg-info-subtle text-info">
                        <i class="bi bi-patch-check-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-2-4 col-lg-3">
            <div class="card card-kpi p-3 h-100 bg-white border-danger">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-danger small fw-semibold">Provinsi Defisit Kritis</div>
                        <h4 class="fw-bold text-danger mb-0 mt-1">{{ $nationalSummary['critical_deficit_count'] }} <span class="fs-6 fw-normal text-muted">provinsi</span></h4>
                        <div class="text-muted" style="font-size: 0.75rem;">Prioritas Intervensi Th 1-5</div>
                    </div>
                    <div class="kpi-icon bg-danger-subtle text-danger">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row 1: Waste Composition & Top 10 Deficits -->
    <div class="row g-4 mb-4">
        <!-- Donut: Waste Composition -->
        <div class="col-12 col-lg-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0 text-dark">
                        <i class="bi bi-pie-chart-fill text-danger me-2"></i> Komposisi Limbah B3 Medis
                    </h6>
                    <span class="badge bg-light text-muted border">Nasional</span>
                </div>
                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                    <div style="width: 260px; height: 260px;">
                        <canvas id="chartWasteComposition"></canvas>
                    </div>
                    <div class="row w-100 text-center mt-3 pt-2 border-top g-2" style="font-size: 0.8rem;">
                        <div class="col-6"><span class="badge bg-danger p-1 me-1"></span> Infeksius: <strong>{{ $wasteComposition['infectious'] }}%</strong></div>
                        <div class="col-6"><span class="badge bg-warning p-1 me-1"></span> Benda Tajam: <strong>{{ $wasteComposition['sharps'] }}%</strong></div>
                        <div class="col-6"><span class="badge bg-primary p-1 me-1"></span> Patologis: <strong>{{ $wasteComposition['pathological'] }}%</strong></div>
                        <div class="col-6"><span class="badge bg-success p-1 me-1"></span> Farmasi/Kimia: <strong>{{ $wasteComposition['chemical'] }}%</strong></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bar: Top 10 Deficit Provinces -->
        <div class="col-12 col-lg-8">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0 text-dark">
                        <i class="bi bi-bar-chart-fill text-warning me-2"></i> 10 Provinsi Defisit Pengolahan Terbesar (Prioritas Utama)
                    </h6>
                    <span class="badge bg-danger-subtle text-danger">Kesenjangan Timbulan vs Kapasitas (ton/hari)</span>
                </div>
                <div class="card-body">
                    <div style="height: 320px;">
                        <canvas id="chartTopDeficit"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row 2: 10-Year Trend Line Chart -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fw-bold mb-0 text-dark">
                            <i class="bi bi-graph-up-arrow text-primary me-2"></i> Proyeksi Timbulan vs Kapasitas Pengolahan 10 Tahun (2026 - 2036)
                        </h6>
                        <span class="text-muted small">Simulasi pemenuhan target roadmap: Kapasitas melampaui timbulan untuk menjamin redundansi sistem</span>
                    </div>
                    <span class="badge bg-success">Horizon 10 Tahun</span>
                </div>
                <div class="card-body">
                    <div style="height: 340px;">
                        <canvas id="chartTenYearProjection"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Horizon Action Summary Cards -->
    <div class="row g-3">
        <div class="col-12 mb-1">
            <h6 class="fw-bold text-dark mb-0 d-flex align-items-center">
                <i class="bi bi-calendar-range text-success me-2"></i> Alokasi Horizon Waktu Peta Jalan (Roadmap 2026 - 2036)
            </h6>
        </div>
        @foreach($horizonSummary['horizons'] as $horizonKey => $horizon)
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm p-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="badge bg-{{ $horizon['color'] }}-subtle text-{{ $horizon['color'] }} fw-bold">
                        {{ $horizon['label'] }} ({{ $horizon['period'] }})
                    </span>
                    <span class="text-muted small">{{ $horizon['count'] }} Program Aksi</span>
                </div>
                <h5 class="fw-bold text-dark mb-1">Rp {{ number_format($horizon['budget'], 0, ',', '.') }}</h5>
                <div class="text-muted small mb-3">Estimasi Kebutuhan Pendanaan Indikatif</div>
                <div class="d-flex justify-content-between small text-muted mb-1">
                    <span>Realisasi Capaian:</span>
                    <strong>{{ $horizon['avg_progress'] }}%</strong>
                </div>
                <div class="progress" style="height: 8px;">
                    <div class="progress-bar bg-{{ $horizon['color'] }}" role="progressbar" style="width: {{ $horizon['avg_progress'] }}%;"></div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Chart Komposisi Limbah
    const ctxWaste = document.getElementById('chartWasteComposition').getContext('2d');
    new Chart(ctxWaste, {
        type: 'doughnut',
        data: {
            labels: ['Infeksius', 'Benda Tajam', 'Patologis', 'Farmasi & Kimia'],
            datasets: [{
                data: [
                    {{ $wasteComposition['infectious'] }},
                    {{ $wasteComposition['sharps'] }},
                    {{ $wasteComposition['pathological'] }},
                    {{ $wasteComposition['chemical'] }}
                ],
                backgroundColor: ['#dc3545', '#ffc107', '#0d6efd', '#198754'],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.label + ': ' + context.raw + '%';
                        }
                    }
                }
            },
            cutout: '65%'
        }
    });

    // 2. Chart Top 10 Defisit
    const topDeficitData = @json($topDeficits);
    const ctxDeficit = document.getElementById('chartTopDeficit').getContext('2d');
    new Chart(ctxDeficit, {
        type: 'bar',
        data: {
            labels: topDeficitData.map(d => d.province),
            datasets: [
                {
                    label: 'Timbulan Limbah (ton/hari)',
                    data: topDeficitData.map(d => d.waste_ton_day),
                    backgroundColor: 'rgba(220, 53, 69, 0.85)',
                    borderRadius: 4
                },
                {
                    label: 'Kapasitas Pengolahan (ton/hari)',
                    data: topDeficitData.map(d => d.capacity_ton_day),
                    backgroundColor: 'rgba(25, 135, 84, 0.85)',
                    borderRadius: 4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'top' }
            },
            scales: {
                x: {
                    ticks: { maxRotation: 45, minRotation: 25, font: { size: 11 } }
                },
                y: {
                    beginAtZero: true,
                    title: { display: true, text: 'Ton / Hari' }
                }
            }
        }
    });

    // 3. Chart Proyeksi 10 Tahun
    const projectionData = @json($tenYearProjections);
    const ctxProj = document.getElementById('chartTenYearProjection').getContext('2d');
    new Chart(ctxProj, {
        type: 'line',
        data: {
            labels: projectionData.years,
            datasets: [
                {
                    label: 'Proyeksi Timbulan Limbah Medis',
                    data: projectionData.waste_generation,
                    borderColor: '#dc3545',
                    backgroundColor: 'rgba(220, 53, 69, 0.08)',
                    fill: true,
                    tension: 0.3,
                    borderWidth: 2.5
                },
                {
                    label: 'Target Kapasitas Pengolahan Aktif',
                    data: projectionData.treatment_capacity,
                    borderColor: '#198754',
                    backgroundColor: 'rgba(25, 135, 84, 0.08)',
                    fill: true,
                    tension: 0.3,
                    borderWidth: 2.5
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'top' }
            },
            scales: {
                y: {
                    beginAtZero: false,
                    title: { display: true, text: 'Total Kapasitas & Timbulan (Ton / Hari)' }
                }
            }
        }
    });
});
</script>
@endpush
