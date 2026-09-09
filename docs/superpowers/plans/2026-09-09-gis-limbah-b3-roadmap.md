# WebGIS & Peta Jalan (Roadmap) Pengelolaan Limbah B3 Medis Fasyankes Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Membangun aplikasi web Laravel 12 lengkap dengan WebGIS interaktif (Leaflet.js), Dashboard Analitik Eksekutif (Chart.js), Analisis Kesenjangan Kapasitas (Capacity Gap), Matriks Rencana Aksi 10 Tahun (sesuai Pasal 10 KAK KLH/BPLH 2026), serta Seeder Data Nasional Terpadu.

**Architecture:** Arsitektur Monolitik Terstruktur (Service-Layer Monolith) menggunakan Laravel 12, MySQL, Blade Templates dengan Bootstrap 5.3 enterprise, Leaflet.js untuk pemetaan geospasial client-side berkinerja tinggi, dan Chart.js untuk analitik kebijakan.

**Tech Stack:** PHP 8.2+, Laravel 12, MySQL, Bootstrap 5.3, Bootstrap Icons, Leaflet 1.9+, Leaflet.markercluster, Chart.js 4.x.

**Spec:** [`docs/superpowers/specs/2026-09-09-gis-limbah-b3-roadmap-design.md`](file:///Users/Data/Work/project/lingkungan_hidup/docs/superpowers/specs/2026-09-09-gis-limbah-b3-roadmap-design.md)

## Global Constraints
- Bahasa & Penamaan Dokumen: Bahasa Indonesia untuk UI publik dan istilah kebijakan KAK KLH/BPLH 2026.
- Entitas Depo Pemindahan dinamai **Lokasi Pemindahan** (`transfer_locations`).
- Styling: Bootstrap 5.3 (enterprise klasik pemerintahan) bernuansa Hijau Konservasi Lingkungan (`#1b5e20`) dan Biru Tua Kebijakan (`#0d47a1`).
- Peta: Leaflet.js dengan OpenStreetMap, Carto Positron, dan Esri World Imagery base maps.
- Data Awal: Seeder data komprehensif nasional (38 provinsi, sampel fasyankes multi-pulau, fasilitas pengolah, lokasi pemindahan, gap status, dan matriks roadmap 10 tahun).

---

### Task 1: Inisialisasi Project Laravel 12 & Konfigurasi Lingkungan

**Files:**
- Create: `composer.json`, `.env`, `artisan`, `config/app.php`, `config/database.php`
- Modify: `.env`

**Interfaces:**
- Consumes: PHP 8.2 & Composer
- Produces: Basis aplikasi Laravel 12 yang siap dijalankan dengan database MySQL

- [ ] **Step 1: Inisialisasi kerangka kerja Laravel 12**
Run:
```bash
composer create-project laravel/laravel:^12.0 temp_laravel --prefer-dist --no-interaction
cp -rn temp_laravel/* .
cp -n temp_laravel/.[!.]* . 2>/dev/null || true
rm -rf temp_laravel
```

- [ ] **Step 2: Konfigurasi koneksi database MySQL di `.env`**
Pastikan `.env` terkonfigurasi untuk MySQL (Database: `gis_limbah_b3`, host: `127.0.0.1`, port: `3306`, user: `root`, password: ``).
Jika MySQL lokal belum memiliki database, buat otomatis:
```bash
mysql -u root -e "CREATE DATABASE IF NOT EXISTS gis_limbah_b3 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" 2>/dev/null || true
```

- [ ] **Step 3: Uji koneksi dan status artisan**
Run: `php artisan --version && php artisan env`
Expected: Laravel Framework 12.x.x

- [ ] **Step 4: Commit**
```bash
git add .
git commit -m "chore: initialize Laravel 12 project framework and environment config"
```

---

### Task 2: Migrasi Basis Data & Model Domain

**Files:**
- Create:
  - `database/migrations/2026_09_09_000001_create_provinces_and_regencies_tables.php`
  - `database/migrations/2026_09_09_000002_create_fasyankes_table.php`
  - `database/migrations/2026_09_09_000003_create_waste_generations_table.php`
  - `database/migrations/2026_09_09_000004_create_treatment_facilities_table.php`
  - `database/migrations/2026_09_09_000005_create_transfer_locations_table.php`
  - `database/migrations/2026_09_09_000006_create_regional_capacity_gaps_table.php`
  - `database/migrations/2026_09_09_000007_create_roadmap_actions_table.php`
  - `app/Models/Province.php`
  - `app/Models/Regency.php`
  - `app/Models/Fasyankes.php`
  - `app/Models/WasteGeneration.php`
  - `app/Models/TreatmentFacility.php`
  - `app/Models/TransferLocation.php`
  - `app/Models/RegionalCapacityGap.php`
  - `app/Models/RoadmapAction.php`
- Test: `tests/Unit/DomainModelTest.php`

**Interfaces:**
- Consumes: Skema tabel sesuai Bab 3 Dokumen Spesifikasi Desain.
- Produces: Model Eloquent dengan relasi lengkap:
  - `Province` `hasMany` `Regency`, `Fasyankes`, `TreatmentFacility`, `TransferLocation`, `RegionalCapacityGap`
  - `Fasyankes` `belongsTo` `Province`, `Regency`; `hasMany` `WasteGeneration`
  - `TreatmentFacility` `belongsTo` `Province`, `Regency`
  - `TransferLocation` `belongsTo` `Province`, `Regency`
  - `RegionalCapacityGap` `belongsTo` `Province`

- [ ] **Step 1: Tulis unit test untuk relasi dan atribut model**
Buat `tests/Unit/DomainModelTest.php` untuk memverifikasi instansiasi model dan relasi dasarnya.
- [ ] **Step 2: Jalankan unit test untuk memastikan test gagal sebelum migrasi**
Run: `php artisan test --filter=DomainModelTest`
Expected: FAIL (tabel atau class belum ada).
- [ ] **Step 3: Buat file migrasi dan model Eloquent**
Terapkan skema tabel dengan indeks koordinat dan kunci asing terintegrasi.
- [ ] **Step 4: Jalankan migrasi database**
Run: `php artisan migrate:fresh`
Expected: Migration tables created successfully.
- [ ] **Step 5: Jalankan unit test untuk memastikan lolos**
Run: `php artisan test --filter=DomainModelTest`
Expected: PASS.
- [ ] **Step 6: Commit**
```bash
git add database/migrations app/Models tests/Unit/DomainModelTest.php
git commit -m "feat: implement database migrations and eloquent models for all domain entities"
```

---

### Task 3: Database Seeder Komprehensif Skala Nasional

**Files:**
- Create:
  - `database/seeders/ProvinceAndRegencySeeder.php`
  - `database/seeders/FasyankesSeeder.php`
  - `database/seeders/TreatmentFacilitySeeder.php`
  - `database/seeders/TransferLocationSeeder.php`
  - `database/seeders/RegionalGapSeeder.php`
  - `database/seeders/RoadmapActionSeeder.php`
- Modify: `database/seeders/DatabaseSeeder.php`
- Test: `tests/Feature/SeederIntegrityTest.php`

**Interfaces:**
- Consumes: Model Eloquent dari Task 2.
- Produces: Data realistis 38 provinsi di Indonesia, 150+ sampel Fasyankes (RSUP, RSUD, Puskesmas, Klinik), Fasilitas Pengolah B3 (Insinerator & Autoklaf komersial & mandiri), Lokasi Pemindahan B3 dengan cold storage, data neraca gap per provinsi, dan butir program matriks rencana aksi 10 tahun sesuai Pasal 10 KAK.

- [ ] **Step 1: Tulis test integritas seeder**
Buat `tests/Feature/SeederIntegrityTest.php` yang mengecek apakah jumlah data provinsi = 38, fasyankes > 100, pengolah > 20, lokasi pemindahan > 20, dan roadmap action > 15 butir.
- [ ] **Step 2: Implementasikan dataset seeder komprehensif**
Isi data koordinat akurat untuk kota-kota besar dan regional di Sumatera, Jawa, Kalimantan, Sulawesi, Bali, Nusa Tenggara, Maluku, dan Papua.
- [ ] **Step 3: Jalankan seeder database**
Run: `php artisan db:seed`
Expected: Seeding completed successfully.
- [ ] **Step 4: Jalankan test integritas seeder**
Run: `php artisan test --filter=SeederIntegrityTest`
Expected: PASS.
- [ ] **Step 5: Commit**
```bash
git add database/seeders tests/Feature/SeederIntegrityTest.php
git commit -m "feat: add comprehensive national seeders for 38 provinces, fasyankes, facilities, and 10-year roadmap"
```

---

### Task 4: Lapisan Layanan (Service Layer: Spatial & Gap Analysis)

**Files:**
- Create:
  - `app/Services/GeoSpatialService.php`
  - `app/Services/GapAnalysisService.php`
  - `app/Services/RoadmapService.php`
- Test: `tests/Unit/ServiceLayerTest.php`

**Interfaces:**
- Consumes: Model database (`Fasyankes`, `TreatmentFacility`, `TransferLocation`, `RegionalCapacityGap`, `RoadmapAction`).
- Produces:
  - `GeoSpatialService::getMapFeaturesJson()`: GeoJSON format titik Fasyankes, Pengolah, Lokasi Pemindahan, dan radius buffer.
  - `GapAnalysisService::calculateNationalSummary()`: Total timbulan, total kapasitas olah, rasio cakupan, daftar provinsi defisit kritis.
  - `RoadmapService::getRoadmapMatrix($horizon)`: Data matriks aksi terfilter dengan kalkulasi progres agregat.
  - `RoadmapService::getTenYearProjections()`: Array proyeksi tahun 2026–2036 untuk grafik tren Chart.js.

- [ ] **Step 1: Tulis unit test untuk Service Layer**
Buat `tests/Unit/ServiceLayerTest.php` untuk menguji output JSON GeoJSON, kalkulasi neraca gap, dan kalkulasi proyeksi 10 tahun.
- [ ] **Step 2: Jalankan test untuk memverifikasi kegagalan awal**
Run: `php artisan test --filter=ServiceLayerTest`
Expected: FAIL.
- [ ] **Step 3: Implementasikan `GeoSpatialService`, `GapAnalysisService`, dan `RoadmapService`**
Terapkan logika perhitungan jarak, pembuatan payload GeoJSON yang optimal, dan agregasi data.
- [ ] **Step 4: Jalankan test untuk memastikan lolos**
Run: `php artisan test --filter=ServiceLayerTest`
Expected: PASS.
- [ ] **Step 5: Commit**
```bash
git add app/Services tests/Unit/ServiceLayerTest.php
git commit -m "feat: implement service layer for geospatial, capacity gap, and 10-year roadmap logic"
```

---

### Task 5: Master Layout & Portal WebGIS Interaktif (Blade + Leaflet.js)

**Files:**
- Create:
  - `resources/views/layouts/app.blade.php`
  - `resources/views/webgis/index.blade.php`
  - `app/Http/Controllers/WebGisController.php`
  - `public/js/webgis-map.js`
  - `public/css/webgis-custom.css`
- Modify: `routes/web.php`
- Test: `tests/Feature/WebGisRouteTest.php`

**Interfaces:**
- Consumes: `GeoSpatialService` & endpoint data GeoJSON.
- Produces: Tampilan peta layar penuh interaktif dengan kontrol layer, filter sidebar dinamis, pop-up marker kaya informasi, visualisasi buffer jangkauan, dan legenda resmi.

- [ ] **Step 1: Tulis feature test untuk rute WebGIS**
Uji rute `/` dan `/webgis` mengembalikan status HTTP 200 dan memuat komponen peta.
- [ ] **Step 2: Buat layout enterprise `resources/views/layouts/app.blade.php`**
Sertakan Bootstrap 5.3, Bootstrap Icons, Leaflet CSS/JS, Leaflet MarkerCluster CSS/JS, dan navigasi navbar instansi KLH/BPLH.
- [ ] **Step 3: Implementasikan `WebGisController` & view `resources/views/webgis/index.blade.php`**
Buat template blade dengan canvas peta Leaflet, panel filter di samping (sidebar), serta modal detail fasyankes.
- [ ] **Step 4: Implementasikan script `public/js/webgis-map.js`**
Inisialisasi Leaflet Map, layer group (Fasyankes, Pengolah, Lokasi Pemindahan, Buffer, Choropleth), marker clustering, dan filter dinamis.
- [ ] **Step 5: Jalankan test untuk memastikan lolos**
Run: `php artisan test --filter=WebGisRouteTest`
Expected: PASS.
- [ ] **Step 6: Commit**
```bash
git add resources/views app/Http/Controllers/WebGisController.php public routes/web.php tests/Feature/WebGisRouteTest.php
git commit -m "feat: create responsive enterprise layout and interactive WebGIS portal with Leaflet"
```

---

### Task 6: Dashboard Eksekutif & Modul Analisis Kesenjangan Kapasitas

**Files:**
- Create:
  - `resources/views/dashboard/index.blade.php`
  - `resources/views/gap-analysis/index.blade.php`
  - `app/Http/Controllers/DashboardController.php`
  - `app/Http/Controllers/GapAnalysisController.php`
- Modify: `routes/web.php`
- Test: `tests/Feature/DashboardAndGapTest.php`

**Interfaces:**
- Consumes: `GapAnalysisService` dan `RoadmapService`.
- Produces:
  - Halaman Dashboard Eksekutif dengan Kartu KPI Utama, Donut Chart Komposisi Limbah, Bar Chart Top 10 Provinsi Defisit, dan Line Chart Tren 10 Tahun (Chart.js).
  - Halaman Analisis Kesenjangan (*Capacity Gap*) dengan tabel neraca interaktif per provinsi, indikator warna badge status (Defisit Kritis, Defisit Sedang, Surplus), dan rekomendasi intervensi.

- [ ] **Step 1: Tulis feature test untuk Dashboard dan Gap Analysis**
Pastikan rute `/dashboard` dan `/gap-analysis` mengembalikan status 200 dan data neraca.
- [ ] **Step 2: Implementasikan `DashboardController` & `resources/views/dashboard/index.blade.php`**
Integrasikan Chart.js dengan data real dari service layer.
- [ ] **Step 3: Implementasikan `GapAnalysisController` & `resources/views/gap-analysis/index.blade.php`**
Buat tabel agregat provinsi dengan sorting, indikator persentase ketercakupan, dan filter pulau.
- [ ] **Step 4: Jalankan test untuk memastikan lolos**
Run: `php artisan test --filter=DashboardAndGapTest`
Expected: PASS.
- [ ] **Step 5: Commit**
```bash
git add app/Http/Controllers resources/views/dashboard resources/views/gap-analysis routes/web.php tests/Feature/DashboardAndGapTest.php
git commit -m "feat: implement executive analytics dashboard and capacity gap analysis module"
```

---

### Task 7: Modul Matriks Rencana Aksi 10 Tahun (Roadmap Action Plan)

**Files:**
- Create:
  - `resources/views/roadmap/index.blade.php`
  - `app/Http/Controllers/RoadmapController.php`
- Modify: `routes/web.php`
- Test: `tests/Feature/RoadmapTest.php`

**Interfaces:**
- Consumes: `RoadmapService`.
- Produces: Halaman Matriks Rencana Aksi 10 Tahun sesuai format wajib Pasal 10 KAK dengan tab horizon waktu, filter penanggung jawab, progress bar capaian, dan fitur ekspor CSV / Cetak.

- [ ] **Step 1: Tulis feature test untuk modul Roadmap**
Verifikasi rute `/roadmap` mengembalikan data aksi dan filter jangka waktu berfungsi.
- [ ] **Step 2: Implementasikan `RoadmapController` & `resources/views/roadmap/index.blade.php`**
Buat antarmuka matriks aksi dengan kolom: Program/Aksi, Baseline, Target, Lokasi Prioritas, Tahap Waktu, Penanggung Jawab, Pendukung, Kebutuhan Indikatif, KPI, Output Program, Sumber Dana, dan Status Capaian.
- [ ] **Step 3: Tambahkan fitur ekspor CSV di `RoadmapController@exportCsv`**
Memungkinkan pengunduhan seluruh tabel rencana aksi untuk keperluan pelaporan instansi.
- [ ] **Step 4: Jalankan test untuk memastikan lolos**
Run: `php artisan test --filter=RoadmapTest`
Expected: PASS.
- [ ] **Step 5: Commit**
```bash
git add app/Http/Controllers/RoadmapController.php resources/views/roadmap routes/web.php tests/Feature/RoadmapTest.php
git commit -m "feat: implement 10-year roadmap action matrix module with filtering and CSV export"
```

---

### Task 8: Modul Manajemen Data Master (CRUD & Baseline Inventory)

**Files:**
- Create:
  - `app/Http/Controllers/FasyankesController.php`
  - `app/Http/Controllers/TreatmentFacilityController.php`
  - `app/Http/Controllers/TransferLocationController.php`
  - `resources/views/fasyankes/index.blade.php`
  - `resources/views/fasyankes/create.blade.php`
  - `resources/views/fasyankes/edit.blade.php`
  - `resources/views/treatment-facilities/index.blade.php`
  - `resources/views/transfer-locations/index.blade.php`
- Modify: `routes/web.php`
- Test: `tests/Feature/MasterDataCrudTest.php`

**Interfaces:**
- Consumes: Model `Fasyankes`, `TreatmentFacility`, `TransferLocation`.
- Produces: Antarmuka pengelolaan data master dengan validasi form, pencarian, dan pagination.

- [ ] **Step 1: Tulis feature test untuk CRUD data fasyankes dan fasilitas**
- [ ] **Step 2: Implementasikan controller dan view CRUD**
- [ ] **Step 3: Jalankan test untuk memastikan lolos**
Run: `php artisan test --filter=MasterDataCrudTest`
Expected: PASS.
- [ ] **Step 4: Commit**
```bash
git add app/Http/Controllers resources/views/fasyankes resources/views/treatment-facilities resources/views/transfer-locations routes/web.php tests/Feature/MasterDataCrudTest.php
git commit -m "feat: implement master data CRUD interfaces for fasyankes, treatment facilities, and transfer locations"
```

---

### Task 9: Verifikasi Sistem Menyeluruh, Optimasi UI & Dokumentasi Panduan

**Files:**
- Create: `README.md`
- Test: Seluruh unit dan feature tests (`php artisan test`)

**Interfaces:**
- Consumes: Seluruh modul yang telah selesai dibangun.
- Produces: Aplikasi terverifikasi 100% lulus uji, peta berjalan mulus, dokumentasi panduan instalasi dan penggunaan lengkap.

- [ ] **Step 1: Jalankan seluruh test suite otomatis**
Run: `php artisan test`
Expected: Seluruh test suite (Unit & Feature) berstatus PASS tanpa error.
- [ ] **Step 2: Jalankan web development server lokal dan uji navigasi**
Run: `php artisan serve --port=8000`
Verifikasi halaman WebGIS, Dashboard, Analisis Kesenjangan, dan Roadmap di browser.
- [ ] **Step 3: Tulis `README.md` komprehensif**
Dokumentasikan arsitektur sistem, langkah instalasi, panduan konfigurasi database, struktur data, dan petunjuk operasional.
- [ ] **Step 4: Final Commit**
```bash
git add .
git commit -m "docs: add comprehensive README with setup instructions and project documentation"
```
