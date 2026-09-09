@extends('layouts.app')

@section('title', 'Lokasi Pemindahan (Depo Limbah B3) - KLH / BPLH')

@section('content')
<div class="container-fluid py-4 px-4">
    <!-- Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <div>
            <h4 class="fw-bold text-dark mb-1 d-flex align-items-center">
                <i class="bi bi-box-seam text-primary me-2"></i> Lokasi Pemindahan (Depo Transfer Limbah B3 Medis)
            </h4>
            <p class="text-muted small mb-0">Titik Simpul Pengumpulan Sementara dan Konsolidasi Logistik Antar-Pulau & Wilayah Regional</p>
        </div>
        <div class="mt-2 mt-md-0 d-flex gap-2">
            <a href="{{ url('/webgis') }}" class="btn btn-sm btn-outline-success">
                <i class="bi bi-geo-alt me-1"></i> Lihat di Peta
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show small" role="alert">
        <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Search & Filter Card -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-3 bg-light rounded">
            <form method="GET" action="{{ route('transfer-locations.index') }}" class="row g-2 align-items-center">
                <div class="col-12 col-md-6">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Cari nama lokasi pemindahan..." value="{{ $search }}">
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <select name="province_id" class="form-select form-select-sm">
                        <option value="">-- Semua Provinsi --</option>
                        @foreach($provinces as $prov)
                            <option value="{{ $prov->id }}" {{ $provinceId == $prov->id ? 'selected' : '' }}>{{ $prov->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-sm btn-success flex-grow-1">Filter</button>
                    <a href="{{ route('transfer-locations.index') }}" class="btn btn-sm btn-outline-secondary" title="Reset">
                        <i class="bi bi-arrow-clockwise"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-gov table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 50px;">No</th>
                        <th>Nama Lokasi Pemindahan</th>
                        <th>Wilayah Administrasi</th>
                        <th class="text-end">Daya Tampung</th>
                        <th class="text-center">Fasilitas Cold Storage</th>
                        <th class="text-center">Fasyankes Terlayani</th>
                        <th class="text-center">Status Layanan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($locations as $index => $item)
                    <tr>
                        <td class="text-center text-muted small">{{ $locations->firstItem() + $index }}</td>
                        <td>
                            <strong class="text-dark">{{ $item->name }}</strong>
                            <div class="text-muted small">{{ $item->address ?: '-' }}</div>
                        </td>
                        <td class="small">
                            <div>{{ $item->regency->name ?? '-' }}</div>
                            <div class="text-muted">{{ $item->province->name ?? '-' }}</div>
                        </td>
                        <td class="text-end fw-bold text-primary">
                            {{ number_format($item->holding_capacity_ton, 1) }} ton
                        </td>
                        <td class="text-center">
                            @if($item->has_cold_storage)
                                <span class="badge bg-info text-dark"><i class="bi bi-snow me-1"></i> Tersedia</span>
                            @else
                                <span class="badge bg-light text-muted border">Tidak Ada</span>
                            @endif
                        </td>
                        <td class="text-center fw-semibold text-dark">
                            {{ $item->target_served_fasyankes }} Fasyankes
                        </td>
                        <td class="text-center">
                            <span class="badge bg-success">{{ $item->service_status }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            Tidak ada lokasi pemindahan yang sesuai.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($locations->hasPages())
        <div class="card-footer bg-white py-2">
            {{ $locations->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</div>
@endsection
