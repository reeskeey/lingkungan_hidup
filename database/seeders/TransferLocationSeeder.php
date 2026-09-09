<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Province;
use App\Models\TransferLocation;

class TransferLocationSeeder extends Seeder
{
    public function run(): void
    {
        $provinces = Province::with('regencies')->get();

        // Titik-titik simpul Lokasi Pemindahan strategis (khususnya wilayah perbatasan/kepulauan)
        $locations = [
            ['prov' => '11', 'name' => 'Lokasi Pemindahan Banda Aceh & Aceh Besar', 'lat' => 5.5483, 'lng' => 95.3238, 'cap' => 5.0, 'cold' => true, 'target' => 35],
            ['prov' => '12', 'name' => 'Lokasi Pemindahan Danau Toba & Simalungun', 'lat' => 2.6500, 'lng' => 98.8500, 'cap' => 8.0, 'cold' => true, 'target' => 45],
            ['prov' => '12', 'name' => 'Lokasi Pemindahan Nias Gunung Sitoli', 'lat' => 1.2833, 'lng' => 97.6167, 'cap' => 4.0, 'cold' => true, 'target' => 20],
            ['prov' => '13', 'name' => 'Lokasi Pemindahan Bukittinggi & Agam', 'lat' => -0.3000, 'lng' => 100.3800, 'cap' => 6.0, 'cold' => true, 'target' => 30],
            ['prov' => '14', 'name' => 'Lokasi Pemindahan Dumai Pesisir', 'lat' => 1.6667, 'lng' => 101.4500, 'cap' => 7.0, 'cold' => true, 'target' => 25],
            ['prov' => '19', 'name' => 'Lokasi Pemindahan Pangkal Pinang Bangka', 'lat' => -2.1333, 'lng' => 106.1167, 'cap' => 5.0, 'cold' => true, 'target' => 30],
            ['prov' => '19', 'name' => 'Lokasi Pemindahan Tanjung Pandan Belitung', 'lat' => -2.7333, 'lng' => 107.6333, 'cap' => 3.5, 'cold' => true, 'target' => 18],
            ['prov' => '21', 'name' => 'Lokasi Pemindahan Tanjung Pinang Bintan', 'lat' => 0.9167, 'lng' => 104.4500, 'cap' => 6.0, 'cold' => true, 'target' => 32],
            ['prov' => '21', 'name' => 'Lokasi Pemindahan Natuna Ranai', 'lat' => 3.9333, 'lng' => 108.3833, 'cap' => 3.0, 'cold' => true, 'target' => 12],
            ['prov' => '32', 'name' => 'Lokasi Pemindahan Cirebon Pantura', 'lat' => -6.7167, 'lng' => 108.5500, 'cap' => 10.0, 'cold' => true, 'target' => 60],
            ['prov' => '32', 'name' => 'Lokasi Pemindahan Priangan Timur Tasikmalaya', 'lat' => -7.3333, 'lng' => 108.2167, 'cap' => 8.0, 'cold' => true, 'target' => 50],
            ['prov' => '33', 'name' => 'Lokasi Pemindahan Banyumas Raya Purwokerto', 'lat' => -7.4244, 'lng' => 109.2300, 'cap' => 9.0, 'cold' => true, 'target' => 55],
            ['prov' => '33', 'name' => 'Lokasi Pemindahan Karesidenan Pati Kudus', 'lat' => -6.8000, 'lng' => 110.8400, 'cap' => 7.5, 'cold' => false, 'target' => 40],
            ['prov' => '35', 'name' => 'Lokasi Pemindahan Madura Bangkalan', 'lat' => -7.0300, 'lng' => 112.7500, 'cap' => 6.0, 'cold' => true, 'target' => 35],
            ['prov' => '35', 'name' => 'Lokasi Pemindahan Banyuwangi Ketapang', 'lat' => -8.1500, 'lng' => 114.4000, 'cap' => 8.0, 'cold' => true, 'target' => 45],
            ['prov' => '51', 'name' => 'Lokasi Pemindahan Buleleng Singaraja', 'lat' => -8.1167, 'lng' => 115.0833, 'cap' => 5.0, 'cold' => true, 'target' => 28],
            ['prov' => '52', 'name' => 'Lokasi Pemindahan Sumbawa Besar', 'lat' => -8.5000, 'lng' => 117.4333, 'cap' => 4.5, 'cold' => true, 'target' => 22],
            ['prov' => '52', 'name' => 'Lokasi Pemindahan Bima Kota', 'lat' => -8.4667, 'lng' => 118.7167, 'cap' => 4.0, 'cold' => false, 'target' => 20],
            ['prov' => '53', 'name' => 'Lokasi Pemindahan Kupang Timor', 'lat' => -10.1772, 'lng' => 123.6070, 'cap' => 6.0, 'cold' => true, 'target' => 35],
            ['prov' => '53', 'name' => 'Lokasi Pemindahan Flores Labuan Bajo', 'lat' => -8.4964, 'lng' => 119.8877, 'cap' => 4.0, 'cold' => true, 'target' => 22],
            ['prov' => '61', 'name' => 'Lokasi Pemindahan Singkawang Sambas', 'lat' => 0.9000, 'lng' => 108.9833, 'cap' => 5.0, 'cold' => true, 'target' => 25],
            ['prov' => '64', 'name' => 'Lokasi Pemindahan Kawasan IKN Nusantara', 'lat' => -0.9700, 'lng' => 116.7000, 'cap' => 8.0, 'cold' => true, 'target' => 40],
            ['prov' => '72', 'name' => 'Lokasi Pemindahan Palu Teluk', 'lat' => -0.8917, 'lng' => 119.8707, 'cap' => 5.0, 'cold' => true, 'target' => 30],
            ['prov' => '74', 'name' => 'Lokasi Pemindahan Kendari Pesisir', 'lat' => -3.9722, 'lng' => 122.5833, 'cap' => 4.5, 'cold' => true, 'target' => 25],
            ['prov' => '81', 'name' => 'Lokasi Pemindahan Maluku Tenggara Tual', 'lat' => -5.6333, 'lng' => 132.7500, 'cap' => 3.5, 'cold' => true, 'target' => 16],
            ['prov' => '82', 'name' => 'Lokasi Pemindahan Ternate Halmahera', 'lat' => 0.7833, 'lng' => 127.3667, 'cap' => 4.0, 'cold' => true, 'target' => 24],
            ['prov' => '92', 'name' => 'Lokasi Pemindahan Manokwari Papua Barat', 'lat' => -0.8615, 'lng' => 134.0620, 'cap' => 4.5, 'cold' => true, 'target' => 20],
            ['prov' => '96', 'name' => 'Lokasi Pemindahan Sorong Papua Barat Daya', 'lat' => -0.8762, 'lng' => 131.2558, 'cap' => 6.0, 'cold' => true, 'target' => 30],
        ];

        foreach ($locations as $loc) {
            $prov = $provinces->firstWhere('code', $loc['prov']);
            if (!$prov) continue;
            $regency = $prov->regencies->first();

            TransferLocation::create([
                'name' => $loc['name'],
                'province_id' => $prov->id,
                'regency_id' => $regency ? $regency->id : 1,
                'address' => 'Sentra Logistik Limbah Medis, ' . $prov->name,
                'latitude' => $loc['lat'],
                'longitude' => $loc['lng'],
                'holding_capacity_ton' => $loc['cap'],
                'has_cold_storage' => $loc['cold'],
                'service_status' => 'Aktif Beroperasi',
                'target_served_fasyankes' => $loc['target'],
            ]);
        }
    }
}
