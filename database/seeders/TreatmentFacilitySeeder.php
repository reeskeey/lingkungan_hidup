<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Province;
use App\Models\TreatmentFacility;

class TreatmentFacilitySeeder extends Seeder
{
    public function run(): void
    {
        $provinces = Province::with('regencies')->get();

        // Fasilitas Pengolah Berizin Komersial Utama di Indonesia (Real reference)
        $facilities = [
            // Jawa Barat
            ['prov' => '32', 'name' => 'PT Prasadha Pamunah Limbah Industri (PPLI)', 'type' => 'Insinerator Berizin', 'category' => 'Jasa Komersial Pihak Ketiga', 'lat' => -6.4414, 'lng' => 106.9158, 'cap_h' => 2500, 'cap_day' => 48.0, 'permit' => 'SK.560/Menlhk/2023'],
            ['prov' => '32', 'name' => 'PT Tenang Jaya Sejahtera Karawang', 'type' => 'Insinerator Berizin', 'category' => 'Jasa Komersial Pihak Ketiga', 'lat' => -6.3245, 'lng' => 107.3123, 'cap_h' => 1500, 'cap_day' => 30.0, 'permit' => 'SK.412/Menlhk/2023'],
            ['prov' => '32', 'name' => 'PT Wastec International Cikarang', 'type' => 'Insinerator Berizin', 'category' => 'Jasa Komersial Pihak Ketiga', 'lat' => -6.3150, 'lng' => 107.1350, 'cap_h' => 1200, 'cap_day' => 24.0, 'permit' => 'SK.305/Menlhk/2022'],
            ['prov' => '32', 'name' => 'Insinerator Terpadu RSUP Hasan Sadikin', 'type' => 'Insinerator Berizin', 'category' => 'Mandiri Fasyankes', 'lat' => -6.8967, 'lng' => 107.5982, 'cap_h' => 300, 'cap_day' => 4.5, 'permit' => 'SK.180/Menlhk/2021'],
            
            // Banten
            ['prov' => '36', 'name' => 'PT Pengelola Limbah Medis Banten', 'type' => 'Autoklaf/Sterilisasi Berizin', 'category' => 'Jasa Komersial Pihak Ketiga', 'lat' => -6.0500, 'lng' => 106.1500, 'cap_h' => 800, 'cap_day' => 16.0, 'permit' => 'SK.221/Menlhk/2023'],
            ['prov' => '36', 'name' => 'PT Putra Restu Ibu Abadi (PRIA) Cilegon', 'type' => 'Insinerator Berizin', 'category' => 'Jasa Komersial Pihak Ketiga', 'lat' => -6.0123, 'lng' => 106.0456, 'cap_h' => 1000, 'cap_day' => 20.0, 'permit' => 'SK.431/Menlhk/2024'],
            
            // DKI Jakarta
            ['prov' => '31', 'name' => 'Fasilitas Pengolah Termal RSUP Fatmawati', 'type' => 'Autoklaf/Sterilisasi Berizin', 'category' => 'Mandiri Fasyankes', 'lat' => -6.2942, 'lng' => 106.7946, 'cap_h' => 250, 'cap_day' => 3.5, 'permit' => 'SK.112/Menlhk/2022'],

            // Jawa Tengah
            ['prov' => '33', 'name' => 'PT Arah Environmental Indonesia Sukoharjo', 'type' => 'Insinerator Berizin', 'category' => 'Jasa Komersial Pihak Ketiga', 'lat' => -7.6321, 'lng' => 110.8754, 'cap_h' => 1000, 'cap_day' => 20.0, 'permit' => 'SK.340/Menlhk/2023'],
            ['prov' => '33', 'name' => 'Insinerator Sentral RSUP Dr. Kariadi Semarang', 'type' => 'Insinerator Berizin', 'category' => 'Mandiri Fasyankes', 'lat' => -6.9934, 'lng' => 110.4079, 'cap_h' => 350, 'cap_day' => 5.0, 'permit' => 'SK.195/Menlhk/2023'],

            // Jawa Timur
            ['prov' => '35', 'name' => 'PT Putra Restu Ibu Abadi (PRIA) Mojokerto', 'type' => 'Insinerator Berizin', 'category' => 'Jasa Komersial Pihak Ketiga', 'lat' => -7.4856, 'lng' => 112.4356, 'cap_h' => 2000, 'cap_day' => 40.0, 'permit' => 'SK.602/Menlhk/2024'],
            ['prov' => '35', 'name' => 'PT Surya Sejahtera Medika Surabaya', 'type' => 'Autoklaf/Sterilisasi Berizin', 'category' => 'Jasa Komersial Pihak Ketiga', 'lat' => -7.2600, 'lng' => 112.7200, 'cap_h' => 600, 'cap_day' => 12.0, 'permit' => 'SK.115/Menlhk/2023'],

            // Sumatera Utara
            ['prov' => '12', 'name' => 'PT Sumatera Hijau Lestari Medan', 'type' => 'Insinerator Berizin', 'category' => 'Jasa Komersial Pihak Ketiga', 'lat' => 3.6500, 'lng' => 98.7000, 'cap_h' => 800, 'cap_day' => 15.0, 'permit' => 'SK.209/Menlhk/2022'],
            
            // Riau & Kepri
            ['prov' => '14', 'name' => 'PT Riau Prima Ekosistem Pekanbaru', 'type' => 'Insinerator Berizin', 'category' => 'Jasa Komersial Pihak Ketiga', 'lat' => 0.5333, 'lng' => 101.4500, 'cap_h' => 500, 'cap_day' => 10.0, 'permit' => 'SK.319/Menlhk/2023'],
            ['prov' => '21', 'name' => 'Fasilitas Pengolah B3 Kawasan Batam', 'type' => 'Insinerator Berizin', 'category' => 'Jasa Komersial Pihak Ketiga', 'lat' => 1.0833, 'lng' => 104.0333, 'cap_h' => 600, 'cap_day' => 12.0, 'permit' => 'SK.145/Menlhk/2023'],

            // Sumatera Selatan
            ['prov' => '16', 'name' => 'PT Sriwijaya Enviro Palembang', 'type' => 'Insinerator Berizin', 'category' => 'Jasa Komersial Pihak Ketiga', 'lat' => -2.9800, 'lng' => 104.7200, 'cap_h' => 600, 'cap_day' => 12.0, 'permit' => 'SK.278/Menlhk/2023'],

            // Bali
            ['prov' => '51', 'name' => 'Insinerator Percontohan Regional Jembrana Bali', 'type' => 'Insinerator Berizin', 'category' => 'Mandiri Fasyankes', 'lat' => -8.3200, 'lng' => 114.6500, 'cap_h' => 200, 'cap_day' => 3.5, 'permit' => 'SK.089/Menlhk/2024'],

            // NTB
            ['prov' => '52', 'name' => 'Fasilitas Insinerator Regional Lemer Lombok Barat', 'type' => 'Insinerator Berizin', 'category' => 'Mandiri Fasyankes', 'lat' => -8.7000, 'lng' => 116.0500, 'cap_h' => 300, 'cap_day' => 5.0, 'permit' => 'SK.190/Menlhk/2022'],

            // Kalimantan Timur & Selatan
            ['prov' => '64', 'name' => 'PT Borneo Limbah Sejahtera Balikpapan', 'type' => 'Insinerator Berizin', 'category' => 'Jasa Komersial Pihak Ketiga', 'lat' => -1.2654, 'lng' => 116.8312, 'cap_h' => 600, 'cap_day' => 12.0, 'permit' => 'SK.332/Menlhk/2023'],
            ['prov' => '63', 'name' => 'Fasilitas Insinerator Banjarbaru', 'type' => 'Insinerator Berizin', 'category' => 'Jasa Komersial Pihak Ketiga', 'lat' => -3.4500, 'lng' => 114.8300, 'cap_h' => 400, 'cap_day' => 8.0, 'permit' => 'SK.201/Menlhk/2024'],

            // Kalimantan Barat
            ['prov' => '61', 'name' => 'Fasilitas Pengolah Medis Khatulistiwa Pontianak', 'type' => 'Autoklaf/Sterilisasi Berizin', 'category' => 'Jasa Komersial Pihak Ketiga', 'lat' => -0.0500, 'lng' => 109.3500, 'cap_h' => 300, 'cap_day' => 5.0, 'permit' => 'SK.176/Menlhk/2023'],

            // Sulawesi Selatan
            ['prov' => '73', 'name' => 'PT Celebes Pengolah Medis Makassar', 'type' => 'Insinerator Berizin', 'category' => 'Jasa Komersial Pihak Ketiga', 'lat' => -5.1000, 'lng' => 119.5200, 'cap_h' => 700, 'cap_day' => 14.0, 'permit' => 'SK.401/Menlhk/2023'],

            // Sulawesi Utara
            ['prov' => '71', 'name' => 'Insinerator Terpadu RSUP Prof Kandou Manado', 'type' => 'Insinerator Berizin', 'category' => 'Mandiri Fasyankes', 'lat' => 1.4556, 'lng' => 124.8278, 'cap_h' => 200, 'cap_day' => 3.0, 'permit' => 'SK.111/Menlhk/2022'],

            // Maluku
            ['prov' => '81', 'name' => 'Insinerator Regional Ambon Baguala', 'type' => 'Insinerator Berizin', 'category' => 'Mandiri Fasyankes', 'lat' => -3.6300, 'lng' => 128.2500, 'cap_h' => 150, 'cap_day' => 2.0, 'permit' => 'SK.099/Menlhk/2023'],

            // Papua
            ['prov' => '91', 'name' => 'Fasilitas Pengolah Limbah Medis Dok II Jayapura', 'type' => 'Insinerator Berizin', 'category' => 'Mandiri Fasyankes', 'lat' => -2.5333, 'lng' => 140.7167, 'cap_h' => 200, 'cap_day' => 2.5, 'permit' => 'SK.087/Menlhk/2024'],
        ];

        foreach ($facilities as $fac) {
            $prov = $provinces->firstWhere('code', $fac['prov']);
            if (!$prov) continue;
            $regency = $prov->regencies->first();

            TreatmentFacility::create([
                'name' => $fac['name'],
                'facility_type' => $fac['type'],
                'operator_category' => $fac['category'],
                'province_id' => $prov->id,
                'regency_id' => $regency ? $regency->id : 1,
                'latitude' => $fac['lat'],
                'longitude' => $fac['lng'],
                'installed_capacity_kg_h' => $fac['cap_h'],
                'licensed_capacity_ton_day' => $fac['cap_day'],
                'permit_number' => $fac['permit'],
                'operational_status' => 'Aktif Beroperasi',
            ]);
        }
    }
}
