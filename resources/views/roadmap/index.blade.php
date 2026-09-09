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
            @if(auth()->check() && auth()->user()->isSuperadmin())
            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#modalAddAction">
                <i class="bi bi-plus-circle me-1"></i> Tambah Program Aksi
            </button>
            @endif
            <a href="{{ url('/roadmap/export-csv' . (request()->getQueryString() ? '?' . request()->getQueryString() : '')) }}" class="btn btn-sm btn-outline-success">
                <i class="bi bi-file-earmark-spreadsheet me-1"></i> Unduh CSV Matriks
            </a>
            <button onclick="window.print()" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-printer me-1"></i> Cetak Laporan
            </button>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show small py-2" role="alert">
        <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

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
                        <th style="min-width: 170px;">Baseline (2026)</th>
                        <th style="min-width: 170px;">Target Capaian</th>
                        <th style="min-width: 120px;">Lokasi</th>
                        <th style="min-width: 110px;">Tahap</th>
                        <th style="min-width: 150px;">Penanggung Jawab</th>
                        <th class="text-end" style="min-width: 130px;">Kebutuhan Indikatif</th>
                        <th style="min-width: 190px;">KPI & Output</th>
                        <th style="min-width: 110px;">Sumber Dana</th>
                        <th class="text-center" style="min-width: 120px;">Capaian Progres</th>
                        @if(auth()->check() && auth()->user()->isSuperadmin())
                        <th class="text-center" style="width: 60px;">Aksi</th>
                        @endif
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
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle">Th 1-2</span>
                            @elseif(str_contains($act->time_horizon, 'Tahun 3-5'))
                                <span class="badge bg-success-subtle text-success border border-success-subtle">Th 3-5</span>
                            @else
                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">Th 6-10</span>
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
                            <div class="d-flex align-items-center justify-content-center gap-1 mb-1">
                                <span class="fw-bold small">{{ $act->progress_percent }}%</span>
                                @if(auth()->check())
                                <button type="button" class="btn btn-sm btn-link p-0 text-primary" 
                                        onclick="openProgressModal({{ $act->id }}, '{{ addslashes($act->program_name) }}', {{ $act->progress_percent }})"
                                        title="Ubah Progres Status">
                                    <i class="bi bi-pencil-square fs-6"></i>
                                </button>
                                @endif
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar {{ $act->progress_percent > 50 ? 'bg-success' : ($act->progress_percent > 0 ? 'bg-primary' : 'bg-secondary') }}" 
                                     style="width: {{ $act->progress_percent }}%;"></div>
                            </div>
                        </td>
                        @if(auth()->check() && auth()->user()->isSuperadmin())
                        <td class="text-center">
                            <form action="{{ route('roadmap.destroy', $act->id) }}" method="POST" onsubmit="return confirm('Hapus program aksi ini?');" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger p-1" title="Hapus Aksi">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="12" class="text-center py-4 text-muted">
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

<!-- Modal Update Progress Cepat -->
<div class="modal fade" id="modalProgressUpdate" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form id="formUpdateProgress" method="POST" action="">
                @csrf
                @method('PATCH')
                <div class="modal-header bg-primary text-white">
                    <h6 class="modal-title fw-bold"><i class="bi bi-arrow-up-right-circle me-1"></i> Perbarui Status & Progres Capaian</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="small text-muted mb-2">Program / Aksi Strategis:</p>
                    <h6 class="fw-bold text-dark mb-3" id="modalActionName">-</h6>

                    <label class="form-label small fw-semibold text-muted">Persentase Capaian (%)</label>
                    <div class="d-flex align-items-center gap-3">
                        <input type="range" class="form-range flex-grow-1" id="rangeProgress" min="0" max="100" step="5" oninput="document.getElementById('inputProgressPercent').value = this.value">
                        <div class="input-group input-group-sm" style="width: 90px;">
                            <input type="number" name="progress_percent" id="inputProgressPercent" class="form-control text-center fw-bold" min="0" max="100" required oninput="document.getElementById('rangeProgress').value = this.value">
                            <span class="input-group-text">%</span>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between text-muted small mt-1">
                        <span>0% (Belum Mulai)</span>
                        <span>50% (Sedang Jalan)</span>
                        <span>100% (Selesai Penuh)</span>
                    </div>
                </div>
                <div class="modal-footer py-2 bg-light">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary px-4">
                        <i class="bi bi-save me-1"></i> Simpan Perubahan Status
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@if(auth()->check() && auth()->user()->isSuperadmin())
<!-- Modal Tambah Program Aksi Baru -->
<div class="modal fade" id="modalAddAction" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('roadmap.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h6 class="modal-title fw-bold"><i class="bi bi-plus-circle me-1"></i> Tambah Program Aksi Rencana Aksi Baru</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Nama Program / Aksi <span class="text-danger">*</span></label>
                            <input type="text" name="program_name" class="form-control form-control-sm" placeholder="Contoh: Pembangunan Insinerator Regional..." required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-semibold">Tahap Waktu (Horizon) <span class="text-danger">*</span></label>
                            <select name="time_horizon" class="form-select form-select-sm" required>
                                <option value="Jangka Pendek (Tahun 1-2)">Jangka Pendek (Tahun 1-2: 2026 - 2027)</option>
                                <option value="Jangka Menengah (Tahun 3-5)">Jangka Menengah (Tahun 3-5: 2028 - 2030)</option>
                                <option value="Jangka Panjang (Tahun 6-10)">Jangka Panjang (Tahun 6-10: 2031 - 2036)</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-semibold">Lokasi / Prioritas <span class="text-danger">*</span></label>
                            <input type="text" name="priority_location" class="form-control form-control-sm" placeholder="Contoh: Kawasan Timur Indonesia / Nasional" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-semibold">Kondisi Baseline (2026)</label>
                            <textarea name="baseline" class="form-control form-control-sm" rows="2" placeholder="Kondisi awal tahun 2026..."></textarea>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-semibold">Target Capaian</label>
                            <textarea name="target" class="form-control form-control-sm" rows="2" placeholder="Target kuantitatif / kualitatif..."></textarea>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-semibold">Penanggung Jawab Utama <span class="text-danger">*</span></label>
                            <input type="text" name="responsible_agency" class="form-control form-control-sm" placeholder="Contoh: KLH / BPLH" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-semibold">Instansi Pendukung</label>
                            <input type="text" name="supporting_agency" class="form-control form-control-sm" placeholder="Contoh: Kemenkes, Pemda">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-semibold">Kebutuhan Anggaran Indikatif (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="indicative_budget" class="form-control form-control-sm" placeholder="10000000000" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-semibold">Sumber Pendanaan <span class="text-danger">*</span></label>
                            <input type="text" name="funding_source" class="form-control form-control-sm" placeholder="Contoh: DIPA KLH / APBN" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-semibold">Indikator Kinerja Utama (KPI)</label>
                            <input type="text" name="kpi" class="form-control form-control-sm" placeholder="Indikator terukur...">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-semibold">Output Program</label>
                            <input type="text" name="program_output" class="form-control form-control-sm" placeholder="Keluaran nyata...">
                        </div>
                    </div>
                </div>
                <div class="modal-footer py-2 bg-light">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-success px-4">
                        <i class="bi bi-check-circle me-1"></i> Simpan Program Aksi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
function openProgressModal(actionId, actionName, currentPercent) {
    document.getElementById('modalActionName').textContent = actionName;
    document.getElementById('inputProgressPercent').value = currentPercent;
    document.getElementById('rangeProgress').value = currentPercent;
    document.getElementById('formUpdateProgress').action = '/roadmap/' + actionId + '/progress';
    
    const modal = new bootstrap.Modal(document.getElementById('modalProgressUpdate'));
    modal.show();
}
</script>
@endpush
