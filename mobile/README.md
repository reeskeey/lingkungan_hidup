# Aplikasi Mobile GIS Limbah B3 Medis Fasyankes & Pelaporan Lapangan

Aplikasi mobile berbasis **Flutter** ini dirancang untuk **petugas lapangan KLH / DLH daerah** dalam melakukan inspeksi, verifikasi perizinan TPS B3, dan pencatatan timbulan medis langsung di lokasi fasyankes, dilengkapi visualisasi **peta spasial interaktif (Mobile GIS)** dan pemantauan **Peta Jalan 10 Tahun (Roadmap 2026–2036)**.

---

## 📱 Fitur Utama Aplikasi Mobile

1. **Inspeksi & Input Data Lapangan**:
   - Pencarian dan filter katalog fasilitas kesehatan seluruh Indonesia.
   - Formulir inspeksi lapangan dengan **deteksi otomatis titik koordinat GPS lokasi perangkat** (`geolocator`).
   - Perhitungan otomatis estimasi timbulan medis berdasarkan kapasitas tempat tidur (`bed_capacity * 0.8 kg/hari`).
   - Verifikasi status izin TPS limbah B3 dan metode penyimpanan berpendingin (*cold storage*).
2. **Peta Spasial Mobile (Mobile GIS)**:
   - Visualisasi peta interaktif dengan `flutter_map` (OpenStreetMap tile server).
   - Penanda (*markers*) warna terstandarisasi:
     - 🔴 **Fasyankes**: RS Rujukan, Puskesmas, dan Klinik.
     - 🟠 **Pengolah Berizin**: Insinerator & Autoklaf komersial pihak ke-3.
     - 🔵 **Lokasi Pemindahan**: Depo penampungan regional berfasilitas cold storage.
   - *Bottom Sheet* detail fasilitas saat marker disentuh.
3. **Peta Jalan 10 Tahun (Roadmap Tracker)**:
   - Pemantauan 15 butir program aksi nasional per tab horizon waktu:
     - Jangka Pendek (Tahun 1–2)
     - Jangka Menengah (Tahun 3–5)
     - Jangka Panjang (Tahun 6–10)
   - Dialog pembaruan persentase progres langsung dari aplikasi mobile bagi petugas terautentikasi.
4. **Dashboard Eksekutif & Akun Petugas**:
   - Kartu metrik KPI agregat nasional (Timbulan ton/hari, kapasitas olah aktif, % rasio ketercakupan layanan, kepatuhan izin TPS).
   - Daftar 5 provinsi dengan defisit pengolahan terbesar.
   - Manajemen sesi login dan peran wewenang (Superadmin KLH vs Operator Daerah).

---

## 📂 Struktur Arsitektur Kode (`mobile/lib`)

```text
mobile/
├── pubspec.yaml
├── android/                   <-- Konfigurasi Android & izin GPS/Internet
├── ios/                       <-- Konfigurasi iOS & izin GPS
└── lib/
    ├── main.dart              <-- MultiProvider & entry point aplikasi
    ├── core/
    │   ├── constants/
    │   │   ├── app_colors.dart    <-- Palet warna tema KLH/BPLH
    │   │   └── api_constants.dart <-- Konfigurasi endpoint REST API
    │   └── theme/
    │       └── app_theme.dart     <-- Tema Material 3 + Google Fonts
    ├── models/
    │   ├── user_model.dart
    │   ├── fasyankes_model.dart
    │   ├── treatment_facility_model.dart
    │   ├── transfer_location_model.dart
    │   ├── roadmap_action_model.dart
    │   └── province_model.dart
    ├── services/
    │   └── api_service.dart       <-- HTTP client dengan Bearer token
    ├── providers/
    │   ├── auth_provider.dart
    │   ├── fasyankes_provider.dart
    │   ├── map_provider.dart
    │   ├── roadmap_provider.dart
    │   └── dashboard_provider.dart
    ├── widgets/
    │   ├── kpi_card.dart
    │   ├── status_badge.dart
    │   └── facility_detail_sheet.dart
    └── views/
        ├── auth/
        │   └── login_screen.dart
        ├── home/
        │   └── main_navigation_screen.dart
        ├── inspection/
        │   ├── fasyankes_list_screen.dart
        │   └── fasyankes_form_screen.dart
        ├── map/
        │   └── mobile_gis_screen.dart
        ├── roadmap/
        │   └── roadmap_screen.dart
        └── dashboard/
            └── dashboard_screen.dart
```

---

## 🚀 Panduan Menjalankan Aplikasi Mobile

### 1. Jalankan Backend Laravel (`web`)
Pastikan backend API Laravel berjalan:
```bash
cd web
php artisan serve --host=0.0.0.0 --port=8000
```

### 2. Konfigurasi Alamat Host API (`ApiConstants.baseUrl`)
- **Android Emulator**: Menggunakan `http://10.0.2.2:8000/api/v1` (sudah otomatis diset).
- **iOS Simulator**: Menggunakan `http://127.0.0.1:8000/api/v1` (sudah otomatis diset).
- **Perangkat Fisik (HP Asli via Wi-Fi/LAN)**:
  Buka file `lib/core/constants/api_constants.dart` dan ubah IP menjadi IP lokal komputer Anda (contoh: `http://192.168.1.50:8000/api/v1`).

### 3. Jalankan Aplikasi Flutter
Masuk ke folder `mobile`:
```bash
cd mobile
flutter pub get
flutter run
```

---

## 🔑 Kredensial Akun Demo Petugas
- **Superadmin KLH**: `admin@klh.go.id` / password: `password`
- **Operator Daerah**: `operator.jabar@klh.go.id` / password: `password`
