@extends('layouts.app')

@section('title', 'Matriks Peta Jalan (Roadmap) 10 Tahun - KLH / BPLH')

@section('content')
<div class="container-fluid py-4 px-4">
    <!-- Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <div>
            <h4 class="fw-bold text-dark mb-1 d-flex align-items-center">
                <i class="bi bi-signpost-split-fill text-info me-2"></i> Matriks Peta Jalan (Roadmap) 10 Tahun Pengelolaan Limbah B3 Medis
            </h4>
            <p class="text-muted small mb-0">Format Wajib Struktur Rencana Aksi Sesuai Pasal 10 Kerangka Acuan Kerja (KAK) KLH / BPLH 2026</p>
        </div>
        <div class="mt-2 mt-md-0 d-flex gap-2">
            <a href="{{ url('/roadmap/export-csv' . (request()->getQueryString() ? '?' . request()->getQueryString() : '')) }}" class="btn btn-sm btn-outline-success">
                <i class="bi bi-file-earmark-spreadsheet me-1"></i> Unduh CSV Matriks
            </a>
            <button onclick="window.print()" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-printer me-1"></i> Cetak Laporan
            </button>
        </div>
    </div>

    <!-- Horizon Pills & Filter Bar -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-3 bg-light rounded">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <!-- Horizon Navigation Tabs -->
                <ul class="nav nav-pills small fw-semibold">
                    <li class="nav-item">
                        <a class="nav-link {{ empty($horizon) || $horizon === 'all' ? 'active bg-success' : 'text-dark' }}" 
                           href="{{ url('/roadmap') }}">
                            Semua Tahapan (10 Tahun)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $horizon === 'Jangka Pendek (Tahun 1-2)' ? 'active bg-primary' : 'text-dark' }}" 
                           href="{{ url('/roadmap?horizon=' . urlencode('Jangka Pendek (Tahun 1-2)')) }}">
                            Jangka Pendek (Tahun 1-2)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $horizon === 'Jangka Menengah (Tahun 3-5)' ? 'active bg-success' : 'text-dark' }}" 
                           href="{{ url('/roadmap?horizon=' . urlencode('Jangka Menengah (Tahun 3-5)')) }}">
                            Jangka Menengah (Tahun 3-5)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $horizon === 'Jangka Panjang (Tahun 6-10)' ? 'active bg-warning text-dark' : 'text-dark' }}" 
                           href="{{ url('/roadmap?horizon=' . urlencode('Jangka Panjang (Tahun 6-10)')) }}">
                            Jangka Panjang (Tahun 6-10)
                        </a>
                    </li>
                </ul>

                <!-- Agency Filter -->
                <form method="GET" action="{{ url('/roadmap') }}" class="d-flex align-items-center gap-2">
                    @if(!empty($horizon))
                        <input type="hidden" name="horizon" value="{{ $horizon }}">
                    @endif
                    <select name="agency" class="form-select form-select-sm" onchange="this.form.submit()" style="max-width: 250px;">
                        <option value="">-- Semua Penanggung Jawab --</option>
                        <option value="KLH" {{ $agency === 'KLH' ? 'selected' : '' }}>KLH / BPLH</option>
                        <option value="Kesehatan" {{ $agency === 'Kesehatan' ? 'selected' : '' }}>Kementerian Kesehatan</option>
                        <option value="Daerah" {{ $agency === 'Daerah' ? 'selected' : '' }}>Pemerintah Daerah (Pemda/DLH)</option>
                        <option value="Perhubungan" {{ $agency === 'Perhubungan' ? 'selected' : '' }}>Kementerian Perhubungan</option>
                    </select>
                    @if(!empty($agency))
                        <a href="{{ url('/roadmap' . (!empty($horizon) ? '?horizon=' . urlencode($horizon) : '')) }}" class="btn btn-sm btn-outline-secondary" title="Clear Filter">
                            <i class="bi bi-x-circle"></i>
                        </a>
                    @endif
                </form>
            </div>
        </div>
    </div>

    <!-- Table of Roadmap Actions -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0 text-dark">
                <i class="bi bi-list-check text-success me-2"></i> Matriks Rencana Aksi Nasional Terpadu
            </h6>
            <span class="badge bg-secondary">{{ count($actions) }} Program Aksi Terdaftar</span>
        </div>
        <div class="table-responsive">
            <table class="table table-gov table-hover align-middle mb-0" style="font-size: 0.84rem;">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 40px;">No</th>
                        <th style="min-width: 220px;">Program / Aksi</th>
                        <th style="min-width: 180px;">Baseline (2026)</th>
                        <th style="min-width: 180px;">Target Capaian</th>
                        <th style="min-width: 130px;">Lokasi / Prioritas</th>
                        <th style="min-width: 120px;">Tahap Waktu</th>
                        <th style="min-width: 160px;">Penanggung Jawab & Pendukung</th>
                        <th class="text-end" style="min-width: 130px;">Kebutuhan Indikatif</th>
                        <th style="min-width: 200px;">KPI & Output Program</th>
                        <th style="min-width: 120px;">Sumber Dana</th>
                        <th class="text-center" style="min-width: 90px;">Capaian</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($actions as $index => $act)
                    <tr>
                        <td class="text-center text-muted fw-semibold">{{ $index + 1 }}</td>
                        <td>
                            <div class="fw-bold text-dark">{{ $act->program_name }}</div>
                        </td>
                        <td class="text-muted small">
                            {{ $act->baseline ?: '-' }}
                        </td>
                        <td class="text-dark small">
                            {{ $act->target ?: '-' }}
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">{{ $act->priority_location }}</span>
                        </td>
                        <td>
                            @if(str_contains($act->time_horizon, 'Tahun 1-2'))
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle">Th 1-2 (Pendek)</span>
                            @elseif(str_contains($act->time_horizon, 'Tahun 3-5'))
                                <span class="badge bg-success-subtle text-success border border-success-subtle">Th 3-5 (Menengah)</span>
                            @else
                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">Th 6-10 (Panjang)</span>
                            @endif
                        </td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $act->responsible_agency }}</div>
                            @if($act->supporting_agency)
                                <div class="text-muted" style="font-size: 0.72rem;">Pendukung: {{ $act->supporting_agency }}</div>
                            @endif
                        </td>
                        <td class="text-end fw-bold text-dark font-monospace">
                            Rp {{ number_format($act->indicative_budget, 0, ',', '.') }}
                        </td>
                        <td>
                            <div class="small">
                                <div class="text-dark"><strong>KPI:</strong> {{ $act->kpi ?: '-' }}</div>
                                <div class="text-muted mt-1" style="font-size: 0.75rem;"><strong>Output:</strong> {{ $act->program_output ?: '-' }}</div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-secondary-subtle text-secondary border">{{ $act->funding_source }}</span>
                        </td>
                        <td class="text-center">
                            <div class="small fw-bold mb-1">{{ $act->progress_percent }}%</div>
                            <div class="progress" style="height: 5px;">
                                <div class="progress-bar {{ $act->progress_percent > 50 ? 'bg-success' : ($act->progress_percent > 0 ? 'bg-primary' : 'bg-secondary') }}" 
                                     style="width: {{ $act->progress_percent }}%;"></div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="text-center py-4 text-muted">
                            <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                            Tidak ada program aksi yang sesuai dengan kriteria penyaringan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
