@extends('layouts.app')

@section('title', 'Data Fasyankes & Timbulan Limbah B3 - KLH / BPLH')

@section('content')
<div class="container-fluid py-4 px-4">
    <!-- Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <div>
            <h4 class="fw-bold text-dark mb-1 d-flex align-items-center">
                <i class="bi bi-hospital text-danger me-2"></i> Data Fasyankes & Timbulan Limbah B3
            </h4>
            <p class="text-muted small mb-0">Inventarisasi Rumah Sakit, Puskesmas, dan Klinik Penghasil Limbah Medis Nasional</p>
        </div>
        <div class="mt-2 mt-md-0 d-flex gap-2">
            <a href="{{ route('fasyankes.create') }}" class="btn btn-sm btn-success">
                <i class="bi bi-plus-circle me-1"></i> Tambah Fasyankes
            </a>
            <a href="{{ url('/webgis') }}" class="btn btn-sm btn-outline-secondary">
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
            <form method="GET" action="{{ route('fasyankes.index') }}" class="row g-2 align-items-center">
                <div class="col-12 col-md-4">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Cari nama fasyankes..." value="{{ $search }}">
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <select name="type" class="form-select form-select-sm">
                        <option value="">-- Semua Jenis Fasilitas --</option>
                        <option value="RS Kelas A" {{ $type === 'RS Kelas A' ? 'selected' : '' }}>RS Kelas A</option>
                        <option value="RS Kelas B" {{ $type === 'RS Kelas B' ? 'selected' : '' }}>RS Kelas B</option>
                        <option value="RS Kelas C" {{ $type === 'RS Kelas C' ? 'selected' : '' }}>RS Kelas C</option>
                        <option value="RS Kelas D" {{ $type === 'RS Kelas D' ? 'selected' : '' }}>RS Kelas D</option>
                        <option value="Puskesmas" {{ $type === 'Puskesmas' ? 'selected' : '' }}>Puskesmas</option>
                        <option value="Klinik Pratama" {{ $type === 'Klinik Pratama' ? 'selected' : '' }}>Klinik Pratama</option>
                    </select>
                </div>
                <div class="col-12 col-md-3">
                    <select name="province_id" class="form-select form-select-sm">
                        <option value="">-- Semua Provinsi --</option>
                        @foreach($provinces as $prov)
                            <option value="{{ $prov->id }}" {{ $provinceId == $prov->id ? 'selected' : '' }}>{{ $prov->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-sm btn-success flex-grow-1">Filter</button>
                    <a href="{{ route('fasyankes.index') }}" class="btn btn-sm btn-outline-secondary" title="Reset">
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
                        <th>Nama Fasyankes</th>
                        <th>Jenis</th>
                        <th>Wilayah</th>
                        <th class="text-center">Kapasitas TT</th>
                        <th class="text-end">Timbulan (kg/hari)</th>
                        <th class="text-center">Izin TPS B3</th>
                        <th>Penyimpanan</th>
                        <th class="text-center" style="width: 100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($fasyankesList as $index => $item)
                    <tr>
                        <td class="text-center text-muted small">{{ $fasyankesList->firstItem() + $index }}</td>
                        <td>
                            <div class="fw-bold text-dark">{{ $item->name }}</div>
                            <div class="text-muted" style="font-size: 0.72rem;"><i class="bi bi-geo"></i> {{ $item->address ?: '-' }}</div>
                        </td>
                        <td><span class="badge bg-danger-subtle text-danger border">{{ $item->type }}</span></td>
                        <td class="small">
                            <div>{{ $item->regency->name ?? '-' }}</div>
                            <div class="text-muted">{{ $item->province->name ?? '-' }}</div>
                        </td>
                        <td class="text-center fw-bold">{{ $item->bed_capacity }} TT</td>
                        <td class="text-end fw-bold text-danger">
                            {{ $item->latestWasteGeneration ? number_format($item->latestWasteGeneration->daily_generation_kg, 1) : '0' }}
                        </td>
                        <td class="text-center">
                            @if($item->tps_permit_status === 'Memiliki Izin')
                                <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Berizin</span>
                            @elseif($item->tps_permit_status === 'Dalam Proses Perpanjangan')
                                <span class="badge bg-warning text-dark">Proses</span>
                            @else
                                <span class="badge bg-secondary">Belum Izin</span>
                            @endif
                        </td>
                        <td class="small text-muted">{{ $item->storage_method }}</td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('fasyankes.edit', $item->id) }}" class="btn btn-outline-primary" title="Edit Data">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('fasyankes.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus data Fasyankes ini?');" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" title="Hapus Data">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">
                            <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                            Tidak ada data fasyankes yang ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($fasyankesList->hasPages())
        <div class="card-footer bg-white py-2">
            {{ $fasyankesList->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</div>
@endsection
