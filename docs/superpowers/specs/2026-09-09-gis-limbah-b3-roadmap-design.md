# Desain Sistem: WebGIS & Peta Jalan (Roadmap) Pengelolaan Limbah B3 Medis Fasyankes Nasional

- **Tanggal Dokumen**: 2026-09-09
- **Dasar Acuan**: Kerangka Acuan Kerja (KAK) Penyusunan Peta Jalan (Roadmap) Pengelolaan Limbah B3 Medis dari Fasilitas Pelayanan Kesehatan di Indonesia Tahun Anggaran 2026, Kementerian Lingkungan Hidup / Badan Pengendalian Lingkungan Hidup (KLH / BPLH).
- **Status**: Validated Design Specification

---

## 1. Ringkasan Eksekutif & Tujuan

Aplikasi ini adalah platform sistem informasi geospasial (WebGIS) dan monitoring kebijakan berbasis web untuk mendukung **Peta Jalan (Roadmap) 10 Tahun (2026–2036)** Pengelolaan Limbah B3 Medis dari Fasilitas Pelayanan Kesehatan (Fasyankes) di Indonesia.

Sistem menyediakan instrumen digital untuk:
1. **Penyajian Baseline Nasional Terverifikasi**: Menampilkan sebaran data Fasyankes (Rumah Sakit Kelas A/B/C/D, Puskesmas, Klinik), timbulan limbah B3 medis harian/tahunan, serta status izin TPS Limbah B3.
2. **Visualisasi Spasial Terpadu (WebGIS)**: Memetakan lokasi Fasyankes, Fasilitas Pengolahan berizin (Insinerator & Autoklaf), dan Lokasi Pemindahan (Depo Transfer dengan fasilitas *cold storage*), dilengkapi radius jangkauan layanan (*buffer*) dan analisis spasial kesenjangan (*choropleth map*).
3. **Analisis Kesenjangan Kapasitas (*Capacity Gap Analysis*)**: Menghitung neraca timbulan limbah medis terhadap kapasitas pengolahan aktif secara nasional dan regional (provinsi/kabupaten), serta mengklasifikasikan wilayah ke dalam status **Defisit Kritis**, **Defisit Sedang**, atau **Surplus**.
4. **Matriks Rencana Aksi 10 Tahun**: Menyajikan dan mengelola matriks rencana aksi terstruktur sesuai mandat Pasal 10 KAK dengan horizon waktu Jangka Pendek (Tahun 1–2), Jangka Menengah (Tahun 3–5), dan Jangka Panjang (Tahun 6–10), lengkap dengan target, indikator kinerja (KPI), alokasi pendanaan indikatif, dan pemantauan realisasi.

---

## 2. Arsitektur & Tumpukan Teknologi (Tech Stack)

Sistem dibangun menggunakan pola arsitektur **Monolitik Terstruktur Berbasis Layanan (Service-Layer Monolith)**:

* **Backend Framework**: Laravel 12 (PHP >= 8.2)
* **Database**: MySQL 8.x (dikelola via local environment / XAMPP)
* **Frontend UI**:
  * Laravel Blade Engine
  * Bootstrap 5.3 + Bootstrap Icons (Tema Enterprise Klasik Pemerintahan: Hijau Konservasi Lingkungan `#1b5e20`, Biru Tua Kebijakan `#0d47a1`, dan Aksen Emas Indikator `#f57f17`)
  * DataTables / Responsive Bootstrap Tables untuk penyajian data tabular berkinerja tinggi
* **Geospatial & Visualization Library**:
  * **Leaflet.js 1.9+**: Render peta interaktif client-side yang ringan dan cepat
  * **Leaflet.markercluster**: Pengelompokan titik fasyankes untuk menjaga performa rendering saat memuat ribuan data
  * **Leaflet Measure & Fullscreen Plugin**: Alat navigasi dan pengukuran jarak antar fasilitas
  * **Chart.js**: Visualisasi grafik tren proyeksi 10 tahun, komposisi jenis limbah, dan perbandingan gap regional
* **Exporting**:
  * Dukungan cetak laporan ringkas dan ekspor tabular ke format Excel/CSV.

---

## 3. Struktur Basis Data & Model Data

### 3.1. `provinces` & `regencies`
Menyimpan wilayah administrasi Indonesia untuk agregasi statistik dan pemetaan.
* `id` (PK)
* `code` (Kode BPS/Kemendagri)
* `name` (Nama Provinsi / Kabupaten)
* `latitude`, `longitude` (Titik tengah koordinat wilayah)
* `zoom_level` (Tingkat pembesaran peta default)

### 3.2. `fasyankes`
Menyimpan data fasilitas pelayanan kesehatan penghasil limbah B3 medis.
* `id` (PK)
* `name` (Nama Fasyankes)
* `type` (`RS Kelas A`, `RS Kelas B`, `RS Kelas C`, `RS Kelas D`, `Puskesmas`, `Klinik Utama`, `Klinik Pratama`)
* `regency_id`, `province_id` (FK)
* `address` (Alamat Lengkap)
* `latitude`, `longitude` (Koordinat Spasial)
* `bed_capacity` (Jumlah Tempat Tidur, integer)
* `tps_permit_status` (`Memiliki Izin`, `Dalam Proses Perpanjangan`, `Belum Memiliki Izin`)
* `storage_method` (`Ruang Berpendingin/Cold Storage`, `TPS B3 Standar`, `Penyimpanan Sederhana`)

### 3.3. `waste_generations`
Menyimpan data timbulan limbah B3 medis fasyankes per periode waktu.
* `id` (PK)
* `fasyankes_id` (FK)
* `year` (Tahun pencatatan data)
* `daily_generation_kg` (Rata-rata timbulan harian dalam kg/hari)
* `annual_generation_ton` (Total estimasi tahunan dalam ton/tahun)
* `infectious_kg` (Limbah Infeksius)
* `sharps_kg` (Limbah Benda Tajam)
* `pathological_kg` (Limbah Patologis)
* `chemical_pharmaceutical_kg` (Limbah Farmasi & Bahan Kimia Kedaluwarsa)
* `management_method` (`Olah Mandiri Berizin`, `Kerjasama Pengolah Berizin Pihak ke-3`, `Belum Terkelola Optimal`)

### 3.4. `treatment_facilities`
Menyimpan fasilitas pengolahan limbah B3 medis berizin resmi.
* `id` (PK)
* `name` (Nama Fasilitas / Operator Pengolah)
* `facility_type` (`Insinerator Berizin`, `Autoklaf/Sterilisasi Berizin`, `Microwave`)
* `operator_category` (`Mandiri Fasyankes`, `Jasa Komersial Pihak Ketiga`)
* `province_id`, `regency_id` (FK)
* `latitude`, `longitude`
* `installed_capacity_kg_h` (Kapasitas terpasang kg/jam)
* `licensed_capacity_ton_day` (Kapasitas izin operasional ton/hari)
* `permit_number` (Nomor SK Izin Operasional KLH/BPLH)
* `operational_status` (`Aktif Beroperasi`, `Pemeliharaan`, `Rencana Pengembangan`)

### 3.5. `transfer_locations`
Menyimpan data titik simpul penampungan sementara regional (sebelumnya depo pemindahan / TPS B3 regional).
* `id` (PK)
* `name` (Nama Lokasi Pemindahan)
* `province_id`, `regency_id` (FK)
* `address` (Alamat lokasi)
* `latitude`, `longitude`
* `holding_capacity_ton` (Kapasitas daya tampung sementara dalam ton)
* `has_cold_storage` (Boolean: true/false)
* `service_status` (`Aktif Beroperasi`, `Rencana`)
* `target_served_fasyankes` (Jumlah estimasi fasyankes yang dilayani)

### 3.6. `regional_capacity_gaps`
Tabel hasil komputasi neraca kapasitas limbah per provinsi.
* `id` (PK)
* `province_id` (FK)
* `year` (Tahun dasar analisis)
* `total_waste_ton_day` (Total timbulan limbah medis wilayah)
* `total_treatment_capacity_ton_day` (Total kapasitas pengolahan terpasang aktif di wilayah)
* `capacity_gap_ton_day` (Selisih: Kapasitas - Timbulan)
* `coverage_ratio_percent` (Rasio Kapasitas / Timbulan * 100%)
* `status` (`Defisit Kritis`, `Defisit Sedang`, `Surplus`)
* `priority_level` (`Prioritas 1 (Mendesak)`, `Prioritas 2`, `Prioritas 3`)

### 3.7. `roadmap_actions`
Menyimpan matriks rencana aksi 10 tahun sesuai struktur Pasal 10 KAK.
* `id` (PK)
* `program_name` (Nama Program / Aksi Strategis)
* `baseline` (Kondisi baseline tahun dasar 2026)
* `target` (Target capaian kuantitatif/kualitatif)
* `priority_location` (Lokasi / Wilayah Prioritas Pelaksanaan)
* `time_horizon` (`Jangka Pendek (Tahun 1-2)`, `Jangka Menengah (Tahun 3-5)`, `Jangka Panjang (Tahun 6-10)`)
* `responsible_agency` (Penanggung Jawab Utama, misal: KLH/BPLH, Kemenkes, Pemda)
* `supporting_agency` (Instansi Pendukung)
* `indicative_budget` (Kebutuhan anggaran indikatif dalam Rupiah)
* `kpi` (Indikator Kinerja Utama)
* `program_output` (Keluaran Program yang terukur)
* `funding_source` (Sumber Pendanaan: APBN KLH, APBD, Swasta/KPBU, DAK, Hibah)
* `progress_percent` (Persentase capaian: 0-100%)

---

## 4. Modul Fungsional Aplikasi

### 4.1. Modul WebGIS Nasional Limbah B3 Medis Fasyankes
* **Peta Layar Penuh Interaktif**:
  * Peta navigasi responsif dengan dukungan kontrol layer tematik.
  * Fitur Layer:
    1. Marker Fasyankes (Kategorisasi ikon/warna: RS Rujukan, RS Daerah, Puskesmas, Klinik).
    2. Marker Fasilitas Pengolahan (Insinerator & Autoklaf berizin).
    3. Marker Lokasi Pemindahan (Depo dengan/tanpa *cold storage*).
    4. Layer Buffer Radius Jangkauan (Lingkaran jangkauan layanan 50 km dan 100 km).
    5. Layer Choropleth Provinsi (Pewarnaan wilayah berdasarkan tingkat defisit/surplus).
  * Filter Interaktif di Panel Peta: Saring berdasarkan Provinsi, Jenis Fasyankes, Kepatuhan Izin TPS, dan Status Kesenjangan.
  * Popup Informatif & Interaksi: Menampilkan ringkasan instan profil timbulan dan jarak fasilitas saat marker diklik.

### 4.2. Modul Dashboard Eksekutif & Analitik
* Ringkasan KPI Utama (Total Timbulan Nasional, Total Kapasitas Olah, % Limbah Terkelola, Jumlah Wilayah Defisit Kritis).
* Grafik Distribusi Komposisi Limbah B3 Medis.
* Grafik Tren & Proyeksi 10 Tahun (Kebutuhan Kapasitas vs Timbulan hingga 2036).
* Grafik Peringkat Provinsi Defisit Kapasitas Terbesar.

### 4.3. Modul Analisis Kesenjangan Kapasitas (*Capacity Gap*)
* Tabel Neraca Kapasitas Spasial Nasional per Provinsi.
* Perhitungan otomatis selisih kapasitas terhadap timbulan.
* Filter wilayah prioritas intervensi (Jawa, Luar Jawa, Wilayah 3T / Kepulauan).
* Rekomendasi arah kebijakan intervensi per wilayah.

### 4.4. Modul Matriks Rencana Aksi 10 Tahun (Roadmap Matrix)
* Tabel matriks rencana aksi terstruktur sesuai format wajib Pasal 10 KAK.
* Tab penyaringan berdasarkan tahapan waktu:
  * **Jangka Pendek (Tahun 1–2)**: Fokus baseline, konsolidasi data, quick wins, dan peningkatan kepatuhan izin TPS.
  * **Jangka Menengah (Tahun 3–5)**: Penutupan gap layanan, penguatan sistem regional, optimalisasi fasilitas pengolahan.
  * **Jangka Panjang (Tahun 6–10)**: Pemerataan layanan nasional, eliminasi wilayah defisit, dan keberlanjutan fasilitas.
* Fitur pencarian, filter instansi penanggung jawab, progress bar capaian, dan ekspor ke Excel/CSV.

### 4.5. Modul Pengelolaan Data Master & Baseline
* Antarmuka CRUD data Fasyankes, Fasilitas Pengolahan, dan Lokasi Pemindahan.
* Fitur import data baseline terverifikasi dari format Excel / CSV.

---

## 5. Rencana Seeder Data Komprehensif Nasional

Aplikasi akan dilengkapi dengan *Database Seeder* komprehensif yang memuat:
1. **Master 38 Provinsi di Indonesia** lengkap dengan koordinat geografis pusat wilayah.
2. **Sampel Fasyankes Representatif Nasional (150+ titik)** yang tersebar di Pulau Jawa, Sumatera, Kalimantan, Sulawesi, Bali-Nusa Tenggara, Maluku, dan Papua.
3. **Data Fasilitas Pengolahan (Insinerator & Autoklaf)** di sentra-sentra industri dan fasyankes rujukan.
4. **Data Lokasi Pemindahan** strategis di wilayah kepulauan dan daerah yang jauh dari pengolah komersial.
5. **Data Gap Wilayah** yang mencerminkan realitas geografis Indonesia (wilayah pulau Jawa relatif surplus/cukup, sedangkan wilayah timur dan kepulauan mengalami defisit kritis).
6. **Matriks Rencana Aksi Lengkap 10 Tahun** berisi butir-butir program konkret sesuai substansi KAK KLH 2026.

---

## 6. Verifikasi & Pengujian

* **Unit & Feature Testing**:
  * Pengujian rute controller dan respons HTTP (WebGIS, Dashboard, Gap Analysis, Roadmap, CRUD).
  * Pengujian formula perhitungan neraca kesenjangan kapasitas (*Gap Calculation logic*).
  * Pengujian keabsahan seeder database.
* **Pengujian Tampilan & Interaktivitas**:
  * Pengujian rendering peta Leaflet dan layer toggle di peramban.
  * Pengujian responsivitas layout Bootstrap 5 di layar desktop maupun perangkat bergerak.
