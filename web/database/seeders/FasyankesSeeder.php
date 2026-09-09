<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Province;
use App\Models\Fasyankes;
use App\Models\WasteGeneration;

class FasyankesSeeder extends Seeder
{
    public function run(): void
    {
        $provinces = Province::with('regencies')->get();

        $hospitalTypes = [
            ['type' => 'RS Kelas A', 'beds' => [700, 1200], 'waste_per_bed' => [0.8, 1.4]],
            ['type' => 'RS Kelas B', 'beds' => [300, 600],  'waste_per_bed' => [0.6, 1.1]],
            ['type' => 'RS Kelas C', 'beds' => [100, 250],  'waste_per_bed' => [0.4, 0.8]],
            ['type' => 'RS Kelas D', 'beds' => [50, 90],    'waste_per_bed' => [0.3, 0.6]],
            ['type' => 'Puskesmas',  'beds' => [10, 30],    'waste_per_bed' => [0.2, 0.4]],
            ['type' => 'Klinik Pratama', 'beds' => [5, 15], 'waste_per_bed' => [0.15, 0.3]],
        ];

        $storageMethods = [
            'Ruang Berpendingin/Cold Storage',
            'TPS B3 Standar',
            'Penyimpanan Sederhana',
        ];

        $permitStatuses = [
            'Memiliki Izin',
            'Dalam Proses Perpanjangan',
            'Belum Memiliki Izin',
        ];

        // Daftar Fasyankes Ternama Nasional untuk realism
        $notableHospitals = [
            ['prov' => '31', 'name' => 'RSUP Nasional Dr. Cipto Mangunkusumo (RSCM)', 'type' => 'RS Kelas A', 'lat' => -6.1983, 'lng' => 106.8480, 'beds' => 1000],
            ['prov' => '31', 'name' => 'RSUP Fatmawati', 'type' => 'RS Kelas A', 'lat' => -6.2942, 'lng' => 106.7946, 'beds' => 850],
            ['prov' => '31', 'name' => 'RS Kanker Dharmais', 'type' => 'RS Kelas A', 'lat' => -6.1884, 'lng' => 106.7972, 'beds' => 600],
            ['prov' => '32', 'name' => 'RSUP Dr. Hasan Sadikin Bandung', 'type' => 'RS Kelas A', 'lat' => -6.8967, 'lng' => 107.5982, 'beds' => 950],
            ['prov' => '32', 'name' => 'RSUD Al-Ihsan Provinsi Jawa Barat', 'type' => 'RS Kelas B', 'lat' => -7.0256, 'lng' => 107.6189, 'beds' => 450],
            ['prov' => '33', 'name' => 'RSUP Dr. Kariadi Semarang', 'type' => 'RS Kelas A', 'lat' => -6.9934, 'lng' => 110.4079, 'beds' => 900],
            ['prov' => '33', 'name' => 'RSUD Dr. Moewardi Surakarta', 'type' => 'RS Kelas A', 'lat' => -7.5574, 'lng' => 110.8447, 'beds' => 800],
            ['prov' => '34', 'name' => 'RSUP Dr. Sardjito Yogyakarta', 'type' => 'RS Kelas A', 'lat' => -7.7687, 'lng' => 110.3734, 'beds' => 850],
            ['prov' => '35', 'name' => 'RSUD Dr. Soetomo Surabaya', 'type' => 'RS Kelas A', 'lat' => -7.2683, 'lng' => 112.7583, 'beds' => 1400],
            ['prov' => '35', 'name' => 'RSUD Dr. Saiful Anwar Malang', 'type' => 'RS Kelas A', 'lat' => -7.9734, 'lng' => 112.6319, 'beds' => 850],
            ['prov' => '36', 'name' => 'RSUP Dr. Sitanala Tangerang', 'type' => 'RS Kelas B', 'lat' => -6.1667, 'lng' => 106.6333, 'beds' => 350],
            ['prov' => '12', 'name' => 'RSUP H. Adam Malik Medan', 'type' => 'RS Kelas A', 'lat' => 3.5186, 'lng' => 98.6078, 'beds' => 800],
            ['prov' => '13', 'name' => 'RSUP Dr. M. Djamil Padang', 'type' => 'RS Kelas A', 'lat' => -0.9416, 'lng' => 100.3667, 'beds' => 750],
            ['prov' => '16', 'name' => 'RSUP Dr. Mohammad Hoesin Palembang', 'type' => 'RS Kelas A', 'lat' => -2.9644, 'lng' => 104.7506, 'beds' => 800],
            ['prov' => '51', 'name' => 'RSUP Prof. Dr. I.G.N.G. Ngoerah (Sanglah) Denpasar', 'type' => 'RS Kelas A', 'lat' => -8.6739, 'lng' => 115.2131, 'beds' => 750],
            ['prov' => '73', 'name' => 'RSUP Dr. Wahidin Sudirohusodo Makassar', 'type' => 'RS Kelas A', 'lat' => -5.1354, 'lng' => 119.4939, 'beds' => 900],
            ['prov' => '64', 'name' => 'RSUD A.W. Sjahranie Samarinda', 'type' => 'RS Kelas A', 'lat' => -0.4819, 'lng' => 117.1408, 'beds' => 700],
            ['prov' => '71', 'name' => 'RSUP Prof. Dr. R.D. Kandou Manado', 'type' => 'RS Kelas A', 'lat' => 1.4556, 'lng' => 124.8278, 'beds' => 750],
            ['prov' => '81', 'name' => 'RSUP Dr. J. Leimena Ambon', 'type' => 'RS Kelas B', 'lat' => -3.6703, 'lng' => 128.2189, 'beds' => 350],
            ['prov' => '91', 'name' => 'RSUD Jayapura Dok II', 'type' => 'RS Kelas B', 'lat' => -2.5333, 'lng' => 140.7167, 'beds' => 400],
        ];

        // Seed fasyankes terkenal terlebih dahulu
        foreach ($notableHospitals as $notable) {
            $prov = $provinces->firstWhere('code', $notable['prov']);
            if (!$prov) continue;
            $regency = $prov->regencies->first();

            $fasyankes = Fasyankes::create([
                'name' => $notable['name'],
                'type' => $notable['type'],
                'province_id' => $prov->id,
                'regency_id' => $regency ? $regency->id : 1,
                'address' => 'Jl. Protokol Kesehatan No. 1, ' . $prov->name,
                'latitude' => $notable['lat'],
                'longitude' => $notable['lng'],
                'bed_capacity' => $notable['beds'],
                'tps_permit_status' => 'Memiliki Izin',
                'storage_method' => 'Ruang Berpendingin/Cold Storage',
            ]);

            $daily = round($notable['beds'] * 1.15, 2);
            WasteGeneration::create([
                'fasyankes_id' => $fasyankes->id,
                'year' => 2026,
                'daily_generation_kg' => $daily,
                'annual_generation_ton' => round(($daily * 365) / 1000, 2),
                'infectious_kg' => round($daily * 0.65, 2),
                'sharps_kg' => round($daily * 0.12, 2),
                'pathological_kg' => round($daily * 0.08, 2),
                'chemical_pharmaceutical_kg' => round($daily * 0.15, 2),
                'management_method' => 'Kerjasama Pengolah Berizin Pihak ke-3',
            ]);
        }

        // Sekarang seed 3 - 5 Fasyankes untuk setiap dari 38 Provinsi agar sebaran nasional merata
        foreach ($provinces as $province) {
            $regencies = $province->regencies;
            $countPerProv = rand(3, 5);

            for ($i = 1; $i <= $countPerProv; $i++) {
                $reg = $regencies->random();
                $config = $hospitalTypes[array_rand($hospitalTypes)];
                $beds = rand($config['beds'][0], $config['beds'][1]);
                $wasteRate = rand($config['waste_per_bed'][0] * 100, $config['waste_per_bed'][1] * 100) / 100;
                $dailyWaste = round($beds * $wasteRate, 2);

                // Variasi koordinat di sekitar ibukota provinsi (+/- 0.05 s.d. 0.25 derajat)
                $latOffset = (rand(-250, 250) / 1000);
                $lngOffset = (rand(-250, 250) / 1000);

                $permit = $permitStatuses[array_rand($permitStatuses)];
                $storage = $storageMethods[array_rand($storageMethods)];

                $fasyankes = Fasyankes::create([
                    'name' => $config['type'] . ' ' . $reg->name . ' Unit ' . $i,
                    'type' => $config['type'],
                    'province_id' => $province->id,
                    'regency_id' => $reg->id,
                    'address' => 'Jl. Kesehatan Wilayah ' . $province->name,
                    'latitude' => $province->latitude + $latOffset,
                    'longitude' => $province->longitude + $lngOffset,
                    'bed_capacity' => $beds,
                    'tps_permit_status' => $permit,
                    'storage_method' => $storage,
                ]);

                WasteGeneration::create([
                    'fasyankes_id' => $fasyankes->id,
                    'year' => 2026,
                    'daily_generation_kg' => $dailyWaste,
                    'annual_generation_ton' => round(($dailyWaste * 365) / 1000, 2),
                    'infectious_kg' => round($dailyWaste * 0.65, 2),
                    'sharps_kg' => round($dailyWaste * 0.12, 2),
                    'pathological_kg' => round($dailyWaste * 0.08, 2),
                    'chemical_pharmaceutical_kg' => round($dailyWaste * 0.15, 2),
                    'management_method' => ($permit === 'Memiliki Izin') ? 'Kerjasama Pengolah Berizin Pihak ke-3' : 'Belum Terkelola Optimal',
                ]);
            }
        }
    }
}
