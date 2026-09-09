@extends('layouts.app')

@section('title', 'Edit Data Fasyankes - KLH / BPLH')

@section('content')
<div class="container py-4" style="max-width: 900px;">
    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
        <h5 class="fw-bold text-dark mb-0">
            <i class="bi bi-pencil-square text-primary me-2"></i> Edit Data Fasyankes: {{ $fasyankes->name }}
        </h5>
        <a href="{{ route('fasyankes.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form action="{{ route('fasyankes.update', $fasyankes) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-12 col-md-8">
                        <label class="form-label small fw-semibold">Nama Fasilitas Pelayanan Kesehatan <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control form-control-sm" value="{{ old('name', $fasyankes->name) }}" required>
                    </div>

                    <div class="col-12 col-md-4">
                        <label class="form-label small fw-semibold">Jenis Fasyankes <span class="text-danger">*</span></label>
                        <select name="type" class="form-select form-select-sm" required>
                            <option value="RS Kelas A" {{ old('type', $fasyankes->type) == 'RS Kelas A' ? 'selected' : '' }}>RS Kelas A</option>
                            <option value="RS Kelas B" {{ old('type', $fasyankes->type) == 'RS Kelas B' ? 'selected' : '' }}>RS Kelas B</option>
                            <option value="RS Kelas C" {{ old('type', $fasyankes->type) == 'RS Kelas C' ? 'selected' : '' }}>RS Kelas C</option>
                            <option value="RS Kelas D" {{ old('type', $fasyankes->type) == 'RS Kelas D' ? 'selected' : '' }}>RS Kelas D</option>
                            <option value="Puskesmas" {{ old('type', $fasyankes->type) == 'Puskesmas' ? 'selected' : '' }}>Puskesmas</option>
                            <option value="Klinik Pratama" {{ old('type', $fasyankes->type) == 'Klinik Pratama' ? 'selected' : '' }}>Klinik Pratama / Utama</option>
                        </select>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label small fw-semibold">Provinsi <span class="text-danger">*</span></label>
                        <select name="province_id" id="province_id" class="form-select form-select-sm" required>
                            @foreach($provinces as $prov)
                                <option value="{{ $prov->encrypted_id }}" data-prov-id="{{ $prov->id }}" {{ (old('province_id', $fasyankes->province_id) == $prov->id || old('province_id') === $prov->encrypted_id) ? 'selected' : '' }}>{{ $prov->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label small fw-semibold">Kabupaten / Kota <span class="text-danger">*</span></label>
                        <select name="regency_id" id="regency_id" class="form-select form-select-sm" required>
                            @foreach($regencies as $reg)
                                <option value="{{ $reg->encrypted_id }}" data-prov-id="{{ $reg->province_id }}" {{ (old('regency_id', $fasyankes->regency_id) == $reg->id || old('regency_id') === $reg->encrypted_id) ? 'selected' : '' }}>{{ $reg->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label small fw-semibold">Alamat Lengkap</label>
                        <textarea name="address" class="form-control form-control-sm" rows="2">{{ old('address', $fasyankes->address) }}</textarea>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label small fw-semibold">Latitude <span class="text-danger">*</span></label>
                        <input type="text" name="latitude" class="form-control form-control-sm" value="{{ old('latitude', $fasyankes->latitude) }}" required>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label small fw-semibold">Longitude <span class="text-danger">*</span></label>
                        <input type="text" name="longitude" class="form-control form-control-sm" value="{{ old('longitude', $fasyankes->longitude) }}" required>
                    </div>

                    <div class="col-12 col-md-4">
                        <label class="form-label small fw-semibold">Kapasitas Tempat Tidur (TT) <span class="text-danger">*</span></label>
                        <input type="number" name="bed_capacity" class="form-control form-control-sm" value="{{ old('bed_capacity', $fasyankes->bed_capacity) }}" required>
                    </div>

                    <div class="col-12 col-md-4">
                        <label class="form-label small fw-semibold">Status Izin TPS Limbah B3 <span class="text-danger">*</span></label>
                        <select name="tps_permit_status" class="form-select form-select-sm" required>
                            <option value="Memiliki Izin" {{ old('tps_permit_status', $fasyankes->tps_permit_status) == 'Memiliki Izin' ? 'selected' : '' }}>Memiliki Izin</option>
                            <option value="Dalam Proses Perpanjangan" {{ old('tps_permit_status', $fasyankes->tps_permit_status) == 'Dalam Proses Perpanjangan' ? 'selected' : '' }}>Dalam Proses Perpanjangan</option>
                            <option value="Belum Memiliki Izin" {{ old('tps_permit_status', $fasyankes->tps_permit_status) == 'Belum Memiliki Izin' ? 'selected' : '' }}>Belum Memiliki Izin</option>
                        </select>
                    </div>

                    <div class="col-12 col-md-4">
                        <label class="form-label small fw-semibold">Metode Penyimpanan TPS <span class="text-danger">*</span></label>
                        <select name="storage_method" class="form-select form-select-sm" required>
                            <option value="Ruang Berpendingin/Cold Storage" {{ old('storage_method', $fasyankes->storage_method) == 'Ruang Berpendingin/Cold Storage' ? 'selected' : '' }}>Ruang Berpendingin / Cold Storage</option>
                            <option value="TPS B3 Standar" {{ old('storage_method', $fasyankes->storage_method) == 'TPS B3 Standar' ? 'selected' : '' }}>TPS B3 Standar</option>
                            <option value="Penyimpanan Sederhana" {{ old('storage_method', $fasyankes->storage_method) == 'Penyimpanan Sederhana' ? 'selected' : '' }}>Penyimpanan Sederhana</option>
                        </select>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label small fw-semibold">Timbulan Harian (kg/hari)</label>
                        <input type="number" step="0.01" name="daily_generation_kg" class="form-control form-control-sm" value="{{ old('daily_generation_kg', $fasyankes->latestWasteGeneration->daily_generation_kg ?? '') }}">
                    </div>

                    <div class="col-12 pt-3 border-top d-flex justify-content-end gap-2">
                        <a href="{{ route('fasyankes.index') }}" class="btn btn-sm btn-outline-secondary">Batal</a>
                        <button type="submit" class="btn btn-sm btn-primary px-4">
                            <i class="bi bi-check-circle me-1"></i> Perbarui Data
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const provSelect = document.getElementById('province_id');
    const regSelect = document.getElementById('regency_id');
    if (!provSelect || !regSelect) return;

    const allRegOptions = Array.from(regSelect.options).slice(1);

    function filterRegencies() {
        const selectedOption = provSelect.options[provSelect.selectedIndex];
        const selectedProvRawId = selectedOption ? selectedOption.getAttribute('data-prov-id') : null;

        const currentVal = regSelect.value;
        regSelect.innerHTML = '<option value="">-- Pilih Kabupaten/Kota --</option>';
        allRegOptions.forEach(opt => {
            if (!selectedProvRawId || opt.getAttribute('data-prov-id') === selectedProvRawId) {
                const clone = opt.cloneNode(true);
                if (clone.value === currentVal) clone.selected = true;
                regSelect.appendChild(clone);
            }
        });
    }

    provSelect.addEventListener('change', filterRegencies);
});
</script>
@endsection
