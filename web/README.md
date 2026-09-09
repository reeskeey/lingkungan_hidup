# WebGIS & Peta Jalan (Roadmap) Pengelolaan Limbah B3 Medis Fasyankes

Sistem Informasi Geospasial (WebGIS) dan Pemantauan Peta Jalan (Roadmap) 10 Tahun (2026–2036) Pengelolaan Limbah B3 Medis dari Fasilitas Pelayanan Kesehatan di Indonesia, dikembangkan sesuai spesifikasi **Kerangka Acuan Kerja (KAK) Kementerian Lingkungan Hidup / Badan Pengendalian Lingkungan Hidup (KLH / BPLH) Tahun Anggaran 2026**.

---

## 🌟 Fitur Utama

### 1. WebGIS Interaktif Nasional
* **Peta Navigasi Responsif**: Didukung oleh Leaflet.js dengan 3 pilihan peta dasar (*Carto Positron Light*, *OpenStreetMap*, dan *Esri World Imagery*).
* **Lapisan Tematik Multi-Layer**:
  * 🏥 **Titik Fasyankes**: Marker titik berkategori warna (RS Kelas A/B/C/D, Puskesmas, Klinik) dengan fitur *Marker Clustering*.
  * 🏭 **Fasilitas Pengolahan Berizin**: Pemetaan Insinerator dan Autoklaf komersial pihak ke-3 maupun mandiri fasyankes rujukan.
  * 📍 **Lokasi Pemindahan**: Depo transfer penampungan limbah medis strategis berfasilitas *cold storage*.
  * ⭕ **Radius Jangkauan Layanan (Buffer)**: Visualisasi radius buffer 50 km dan 100 km untuk analisis keterjangkauan dan area blank spot.
  * 🗺️ **Peta Kesenjangan Provinsi (Choropleth)**: Gradasi warna status neraca wilayah:
    * 🔴 **Defisit Kritis** (< 50% kapasitas)
    * 🟠 **Defisit Sedang** (50% - 99% kapasitas)
    * 🟢 **Surplus / Aman** (≥ 100% kapasitas)
* **Panel Kontrol & Filter Real-Time**: Saring data berdasarkan provinsi, jenis fasyankes, atau status defisit wilayah secara instan tanpa reload halaman.

### 2. Dashboard Eksekutif & Analitik (Chart.js)
* **Kartu KPI Utama**: Total timbulan limbah medis nasional (ton/hari & ton/tahun), kapasitas olah berizin aktif, rasio ketercakupan nasional (%), persentase kepatuhan izin TPS B3 fasyankes, dan jumlah provinsi defisit kritis.
* **Grafik Interaktif**:
  * *Donut Chart*: Komposisi jenis limbah (Infeksius, Benda Tajam, Patologis, Kimia/Farmasi).
  * *Bar Chart*: 10 Provinsi Defisit Pengolahan Terbesar sebagai prioritas intervensi penanganan.
  * *Line Chart*: Proyeksi Pertumbuhan Timbulan vs Target Kapasitas Pengolahan 10 Tahun (2026–2036).
* **Ringkasan Alokasi Horizon Waktu**: Kartu pemantauan anggaran indikatif dan progres capaian rencana aksi.

### 3. Analisis Kesenjangan Kapasitas (Capacity Gap Analysis)
* Tabel neraca kapasitas 38 provinsi di Indonesia menghitung selisih timbulan terhadap kapasitas izin.
* Penyaringan berdasarkan gugus kepulauan (Sumatera, Jawa, Bali-Nusa Tenggara, Kalimantan, Sulawesi, Maluku-Papua).
* Indikator progres bar ketercakupan dan rekomendasi arah kebijakan per wilayah.

### 4. Matriks Rencana Aksi 10 Tahun (Roadmap Module)
* Mengikuti struktur wajib **Pasal 10 KAK**:
  * Kolom: *Program/Aksi | Baseline (2026) | Target Capaian | Lokasi/Prioritas | Tahap Waktu | Penanggung Jawab | Pendukung | Kebutuhan Indikatif (Rp) | KPI | Output Program | Sumber Pendanaan | Capaian (%)*
* Tab navigasi horizon waktu:
  * **Jangka Pendek (Tahun 1–2: 2026–2027)**: Baseline, quick wins, dan kepatuhan izin TPS.
  * **Jangka Menengah (Tahun 3–5: 2028–2030)**: Penutupan gap layanan, sistem regional, dan fasilitas baru.
  * **Jangka Panjang (Tahun 6–10: 2031–2036)**: Pemerataan layanan, nol defisit kritis (*zero deficit*), dan keberlanjutan.
* Fitur ekspor seluruh matriks aksi ke format **CSV** (kompatibel Microsoft Excel) dan tombol cetak laporan.

### 5. Manajemen Data Master (CRUD)
* Antarmuka pengelolaan inventarisasi data Fasyankes & timbulan limbah.
* Fasilitas Pengolahan Berizin KLH.
* Lokasi Pemindahan regional berfasilitas cold storage.

---

## 🛠️ Tumpukan Teknologi (Tech Stack)

* **Backend**: Laravel 12 (PHP 8.2+)
* **Database**: MySQL 8.x
* **Frontend**: Blade Templates + Bootstrap 5.3 (Tema Enterprise Klasik Pemerintahan) + Bootstrap Icons
* **Geospatial & Visualisasi**: Leaflet.js 1.9+, Leaflet.markercluster 1.5+, Chart.js 4.4+

---

## 🚀 Panduan Instalasi & Menjalankan Aplikasi

### 1. Kebutuhan Sistem
* PHP >= 8.2 (dengan ekstensi `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`)
* Composer >= 2.0
* Server Database MySQL (misalnya via XAMPP)

### 2. Pengaturan Basis Data
Pastikan MySQL aktif, lalu buat basis data (jika belum ada):
```sql
CREATE DATABASE gis_limbah_b3 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Konfigurasi koneksi di file `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gis_limbah_b3
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Migrasi & Seeding Data Nasional
Jalankan migrasi tabel dan pengisian seeder komprehensif 38 provinsi:
```bash
php artisan migrate:fresh --seed
```

### 4. Menjalankan Server Lokal
Jalankan development server Laravel:
```bash
php artisan serve
```
Buka peramban (browser) di alamat: **`http://localhost:8000`**

---

## 🧪 Pengujian Otomatis (Automated Testing)

Aplikasi dilengkapi test suite komprehensif (Unit & Feature Testing):

```bash
php artisan test
```

Daftar suite pengujian yang diuji:
* `DomainModelTest`: Validasi relasi model Eloquent (Provinsi, Fasyankes, Fasilitas, Lokasi Pemindahan, Gap, Roadmap).
* `SeederIntegrityTest`: Verifikasi kuantitas & validitas seeder data 38 provinsi di Indonesia.
* `ServiceLayerTest`: Pengujian output fitur geospasial GeoJSON, kalkulasi neraca gap, dan proyeksi 10 tahun.
* `WebGisRouteTest`: Pengujian rendering portal peta interaktif dan API endpoint `/webgis/data`.
* `DashboardAndGapTest`: Pengujian rendering grafik analitik dan tabel kesenjangan kapasitas.
* `RoadmapTest`: Pengujian tabel matriks rencana aksi Pasal 10 KAK dan endpoint unduh CSV.
* `MasterDataCrudTest`: Pengujian antarmuka CRUD data master fasilitas.

---

## 🏛️ Lisensi & Hak Cipta
Aplikasi ini dikembangkan untuk mendukung kebijakan **Kementerian Lingkungan Hidup / Badan Pengendalian Lingkungan Hidup (KLH / BPLH Republik Indonesia)** Tahun Anggaran 2026.
