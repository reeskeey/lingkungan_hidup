@extends('layouts.app')

@section('title', 'Fasilitas Pengolahan Limbah B3 Berizin - KLH / BPLH')

@section('content')
<div class="container-fluid py-4 px-4">
    <!-- Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <div>
            <h4 class="fw-bold text-dark mb-1 d-flex align-items-center">
                <i class="bi bi-fire text-warning me-2"></i> Fasilitas Pengolahan Limbah B3 Berizin
            </h4>
            <p class="text-muted small mb-0">Daftar Insinerator, Autoklaf, dan Teknologi Pengolahan Limbah Medis Berizin Resmi KLH / BPLH</p>
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
            <form method="GET" action="{{ route('treatment-facilities.index') }}" class="row g-2 align-items-center">
                <div class="col-12 col-md-5">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Cari nama fasilitas..." value="{{ $search }}">
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <select name="type" class="form-select form-select-sm">
                        <option value="">-- Semua Jenis Teknologi --</option>
                        <option value="Insinerator Berizin" {{ $type === 'Insinerator Berizin' ? 'selected' : '' }}>Insinerator Berizin</option>
                        <option value="Autoklaf/Sterilisasi Berizin" {{ $type === 'Autoklaf/Sterilisasi Berizin' ? 'selected' : '' }}>Autoklaf / Sterilisasi Berizin</option>
                    </select>
                </div>
                <div class="col-12 col-md-3">
                    <select name="province_id" class="form-select form-select-sm">
                        <option value="">-- Semua Provinsi --</option>
                        @foreach($provinces as $prov)
                            <option value="{{ $prov->encrypted_id }}" {{ ($provinceId == $prov->id || (isset($rawProvinceId) && $rawProvinceId === $prov->encrypted_id)) ? 'selected' : '' }}>{{ $prov->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-1 d-flex gap-2">
                    <button type="submit" class="btn btn-sm btn-success flex-grow-1">Filter</button>
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
                        <th>Nama Fasilitas / Pengolah</th>
                        <th>Teknologi</th>
                        <th>Kategori Operator</th>
                        <th>Wilayah</th>
                        <th class="text-end">Kapasitas Izin (ton/hari)</th>
                        <th class="text-end">Kapasitas Terpasang</th>
                        <th>No. Izin SK KLH</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($facilities as $index => $item)
                    <tr>
                        <td class="text-center text-muted small">{{ $facilities->firstItem() + $index }}</td>
                        <td>
                            <strong class="text-dark">{{ $item->name }}</strong>
                        </td>
                        <td><span class="badge bg-warning-subtle text-warning-emphasis border">{{ $item->facility_type }}</span></td>
                        <td><span class="badge bg-light text-dark border">{{ $item->operator_category }}</span></td>
                        <td class="small">
                            <div>{{ $item->regency->name ?? '-' }}</div>
                            <div class="text-muted">{{ $item->province->name ?? '-' }}</div>
                        </td>
                        <td class="text-end fw-bold text-success">
                            {{ number_format($item->licensed_capacity_ton_day, 1) }} ton/hari
                        </td>
                        <td class="text-end text-muted small">
                            {{ number_format($item->installed_capacity_kg_h, 0) }} kg/jam
                        </td>
                        <td class="small font-monospace text-muted">{{ $item->permit_number ?: '-' }}</td>
                        <td class="text-center">
                            <span class="badge bg-success">{{ $item->operational_status }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">
                            Tidak ada fasilitas pengolahan yang sesuai.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($facilities->hasPages())
        <div class="card-footer bg-white py-2">
            {{ $facilities->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</div>
@endsection
