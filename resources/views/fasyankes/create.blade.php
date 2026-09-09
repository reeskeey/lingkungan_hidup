@extends('layouts.app')

@section('title', 'Tambah Data Fasyankes Baru - KLH / BPLH')

@section('content')
<div class="container py-4" style="max-width: 900px;">
    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
        <h5 class="fw-bold text-dark mb-0">
            <i class="bi bi-hospital text-danger me-2"></i> Tambah Data Fasyankes Baru
        </h5>
        <a href="{{ route('fasyankes.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    @if($errors->any())
    <div class="alert alert-danger small py-2">
        <ul class="mb-0">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form action="{{ route('fasyankes.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-12 col-md-8">
                        <label class="form-label small fw-semibold">Nama Fasilitas Pelayanan Kesehatan <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control form-control-sm" placeholder="Contoh: RSUD Dr. Soetomo" value="{{ old('name') }}" required>
                    </div>

                    <div class="col-12 col-md-4">
                        <label class="form-label small fw-semibold">Jenis Fasyankes <span class="text-danger">*</span></label>
                        <select name="type" class="form-select form-select-sm" required>
                            <option value="">-- Pilih Jenis --</option>
                            <option value="RS Kelas A" {{ old('type') == 'RS Kelas A' ? 'selected' : '' }}>RS Kelas A</option>
                            <option value="RS Kelas B" {{ old('type') == 'RS Kelas B' ? 'selected' : '' }}>RS Kelas B</option>
                            <option value="RS Kelas C" {{ old('type') == 'RS Kelas C' ? 'selected' : '' }}>RS Kelas C</option>
                            <option value="RS Kelas D" {{ old('type') == 'RS Kelas D' ? 'selected' : '' }}>RS Kelas D</option>
                            <option value="Puskesmas" {{ old('type') == 'Puskesmas' ? 'selected' : '' }}>Puskesmas</option>
                            <option value="Klinik Pratama" {{ old('type') == 'Klinik Pratama' ? 'selected' : '' }}>Klinik Pratama / Utama</option>
                        </select>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label small fw-semibold">Provinsi <span class="text-danger">*</span></label>
                        <select name="province_id" id="province_id" class="form-select form-select-sm" required>
                            <option value="">-- Pilih Provinsi --</option>
                            @foreach($provinces as $prov)
                                <option value="{{ $prov->id }}" {{ old('province_id') == $prov->id ? 'selected' : '' }}>{{ $prov->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label small fw-semibold">Kabupaten / Kota <span class="text-danger">*</span></label>
                        <select name="regency_id" id="regency_id" class="form-select form-select-sm" required>
                            <option value="">-- Pilih Kabupaten/Kota --</option>
                            @foreach($regencies as $reg)
                                <option value="{{ $reg->id }}" data-prov="{{ $reg->province_id }}" {{ old('regency_id') == $reg->id ? 'selected' : '' }}>{{ $reg->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label small fw-semibold">Alamat Lengkap</label>
                        <textarea name="address" class="form-control form-control-sm" rows="2" placeholder="Alamat jalan, nomor, kecamatan...">{{ old('address') }}</textarea>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label small fw-semibold">Latitude (Garis Lintang) <span class="text-danger">*</span></label>
                        <input type="text" name="latitude" class="form-control form-control-sm" placeholder="-6.208800" value="{{ old('latitude', '-6.208800') }}" required>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label small fw-semibold">Longitude (Garis Bujur) <span class="text-danger">*</span></label>
                        <input type="text" name="longitude" class="form-control form-control-sm" placeholder="106.845600" value="{{ old('longitude', '106.845600') }}" required>
                    </div>

                    <div class="col-12 col-md-4">
                        <label class="form-label small fw-semibold">Kapasitas Tempat Tidur (TT) <span class="text-danger">*</span></label>
                        <input type="number" name="bed_capacity" class="form-control form-control-sm" placeholder="100" value="{{ old('bed_capacity', 50) }}" required>
                    </div>

                    <div class="col-12 col-md-4">
                        <label class="form-label small fw-semibold">Status Izin TPS Limbah B3 <span class="text-danger">*</span></label>
                        <select name="tps_permit_status" class="form-select form-select-sm" required>
                            <option value="Memiliki Izin" {{ old('tps_permit_status') == 'Memiliki Izin' ? 'selected' : '' }}>Memiliki Izin</option>
                            <option value="Dalam Proses Perpanjangan" {{ old('tps_permit_status') == 'Dalam Proses Perpanjangan' ? 'selected' : '' }}>Dalam Proses Perpanjangan</option>
                            <option value="Belum Memiliki Izin" {{ old('tps_permit_status') == 'Belum Memiliki Izin' ? 'selected' : '' }}>Belum Memiliki Izin</option>
                        </select>
                    </div>

                    <div class="col-12 col-md-4">
                        <label class="form-label small fw-semibold">Metode Penyimpanan TPS <span class="text-danger">*</span></label>
                        <select name="storage_method" class="form-select form-select-sm" required>
                            <option value="Ruang Berpendingin/Cold Storage">Ruang Berpendingin / Cold Storage</option>
                            <option value="TPS B3 Standar">TPS B3 Standar</option>
                            <option value="Penyimpanan Sederhana">Penyimpanan Sederhana</option>
                        </select>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label small fw-semibold">Estimasi Timbulan Medis (kg/hari)</label>
                        <input type="number" step="0.01" name="daily_generation_kg" class="form-control form-control-sm" placeholder="Otomatis terisi jika kosong" value="{{ old('daily_generation_kg') }}">
                        <div class="text-muted" style="font-size: 0.72rem;">Jika dikosongkan, dihitung otomatis berdasarkan kapasitas tempat tidur.</div>
                    </div>

                    <div class="col-12 pt-3 border-top d-flex justify-content-end gap-2">
                        <a href="{{ route('fasyankes.index') }}" class="btn btn-sm btn-outline-secondary">Batal</a>
                        <button type="submit" class="btn btn-sm btn-success px-4">
                            <i class="bi bi-save me-1"></i> Simpan Data Fasyankes
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
