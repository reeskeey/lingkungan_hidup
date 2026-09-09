<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Province;
use App\Models\Regency;

class ProvinceAndRegencySeeder extends Seeder
{
    public function run(): void
    {
        // 38 Provinsi di Indonesia
        $provinces = [
            ['code' => '11', 'name' => 'ACEH', 'latitude' => 4.695135, 'longitude' => 96.749399, 'zoom' => 8],
            ['code' => '12', 'name' => 'SUMATERA UTARA', 'latitude' => 2.115354, 'longitude' => 99.545097, 'zoom' => 8],
            ['code' => '13', 'name' => 'SUMATERA BARAT', 'latitude' => -0.739940, 'longitude' => 100.800005, 'zoom' => 8],
            ['code' => '14', 'name' => 'RIAU', 'latitude' => 0.293347, 'longitude' => 101.706825, 'zoom' => 8],
            ['code' => '15', 'name' => 'JAMBI', 'latitude' => -1.485183, 'longitude' => 102.438058, 'zoom' => 8],
            ['code' => '16', 'name' => 'SUMATERA SELATAN', 'latitude' => -3.319437, 'longitude' => 104.914444, 'zoom' => 8],
            ['code' => '17', 'name' => 'BENGKULU', 'latitude' => -3.577847, 'longitude' => 102.346388, 'zoom' => 8],
            ['code' => '18', 'name' => 'LAMPUNG', 'latitude' => -4.558585, 'longitude' => 105.406808, 'zoom' => 8],
            ['code' => '19', 'name' => 'KEPULAUAN BANGKA BELITUNG', 'latitude' => -2.741051, 'longitude' => 106.440587, 'zoom' => 8],
            ['code' => '21', 'name' => 'KEPULAUAN RIAU', 'latitude' => 3.945651, 'longitude' => 108.142867, 'zoom' => 8],
            ['code' => '31', 'name' => 'DKI JAKARTA', 'latitude' => -6.208763, 'longitude' => 106.845599, 'zoom' => 11],
            ['code' => '32', 'name' => 'JAWA BARAT', 'latitude' => -6.917464, 'longitude' => 107.619123, 'zoom' => 8],
            ['code' => '33', 'name' => 'JAWA TENGAH', 'latitude' => -7.150975, 'longitude' => 110.140259, 'zoom' => 8],
            ['code' => '34', 'name' => 'DAERAH ISTIMEWA YOGYAKARTA', 'latitude' => -7.795580, 'longitude' => 110.369490, 'zoom' => 10],
            ['code' => '35', 'name' => 'JAWA TIMUR', 'latitude' => -7.536064, 'longitude' => 112.238402, 'zoom' => 8],
            ['code' => '36', 'name' => 'BANTEN', 'latitude' => -6.405817, 'longitude' => 106.064018, 'zoom' => 9],
            ['code' => '51', 'name' => 'BALI', 'latitude' => -8.409518, 'longitude' => 115.188916, 'zoom' => 9],
            ['code' => '52', 'name' => 'NUSA TENGGARA BARAT', 'latitude' => -8.652933, 'longitude' => 117.361648, 'zoom' => 8],
            ['code' => '53', 'name' => 'NUSA TENGGARA TIMUR', 'latitude' => -8.657382, 'longitude' => 121.079371, 'zoom' => 8],
            ['code' => '61', 'name' => 'KALIMANTAN BARAT', 'latitude' => -0.278781, 'longitude' => 111.475285, 'zoom' => 7],
            ['code' => '62', 'name' => 'KALIMANTAN TENGAH', 'latitude' => -1.681488, 'longitude' => 113.382355, 'zoom' => 7],
            ['code' => '63', 'name' => 'KALIMANTAN SELATAN', 'latitude' => -3.092642, 'longitude' => 115.283759, 'zoom' => 8],
            ['code' => '64', 'name' => 'KALIMANTAN TIMUR', 'latitude' => 0.538659, 'longitude' => 116.419389, 'zoom' => 7],
            ['code' => '65', 'name' => 'KALIMANTAN UTARA', 'latitude' => 3.073093, 'longitude' => 116.041390, 'zoom' => 7],
            ['code' => '71', 'name' => 'SULAWESI UTARA', 'latitude' => 0.624693, 'longitude' => 123.975002, 'zoom' => 8],
            ['code' => '72', 'name' => 'SULAWESI TENGAH', 'latitude' => -1.430025, 'longitude' => 121.445618, 'zoom' => 7],
            ['code' => '73', 'name' => 'SULAWESI SELATAN', 'latitude' => -3.668799, 'longitude' => 119.974053, 'zoom' => 8],
            ['code' => '74', 'name' => 'SULAWESI TENGGARA', 'latitude' => -4.144910, 'longitude' => 122.174605, 'zoom' => 8],
            ['code' => '75', 'name' => 'GORONTALO', 'latitude' => 0.699937, 'longitude' => 122.446724, 'zoom' => 8],
            ['code' => '76', 'name' => 'SULAWESI BARAT', 'latitude' => -2.844137, 'longitude' => 119.232078, 'zoom' => 8],
            ['code' => '81', 'name' => 'MALUKU', 'latitude' => -3.238462, 'longitude' => 130.145273, 'zoom' => 7],
            ['code' => '82', 'name' => 'MALUKU UTARA', 'latitude' => 1.570999, 'longitude' => 127.808769, 'zoom' => 7],
            ['code' => '91', 'name' => 'PAPUA', 'latitude' => -4.269928, 'longitude' => 138.080353, 'zoom' => 7],
            ['code' => '92', 'name' => 'PAPUA BARAT', 'latitude' => -1.336115, 'longitude' => 133.174716, 'zoom' => 7],
            ['code' => '93', 'name' => 'PAPUA SELATAN', 'latitude' => -7.000000, 'longitude' => 139.500000, 'zoom' => 7],
            ['code' => '94', 'name' => 'PAPUA TENGAH', 'latitude' => -3.500000, 'longitude' => 136.000000, 'zoom' => 7],
            ['code' => '95', 'name' => 'PAPUA PEGUNUNGAN', 'latitude' => -4.100000, 'longitude' => 139.000000, 'zoom' => 7],
            ['code' => '96', 'name' => 'PAPUA BARAT DAYA', 'latitude' => -0.900000, 'longitude' => 131.300000, 'zoom' => 7],
        ];

        foreach ($provinces as $prov) {
            $createdProv = Province::create([
                'code' => $prov['code'],
                'name' => $prov['name'],
                'latitude' => $prov['latitude'],
                'longitude' => $prov['longitude'],
                'zoom_level' => $prov['zoom'],
            ]);

            // Tambahkan ibukota / kabupaten utama untuk setiap provinsi
            Regency::create([
                'province_id' => $createdProv->id,
                'code' => $prov['code'] . '71',
                'name' => 'KOTA PUSAT / IBUKOTA ' . $prov['name'],
                'latitude' => $prov['latitude'],
                'longitude' => $prov['longitude'],
            ]);

            Regency::create([
                'province_id' => $createdProv->id,
                'code' => $prov['code'] . '01',
                'name' => 'KABUPATEN DAERAH ' . $prov['name'],
                'latitude' => $prov['latitude'] + 0.15,
                'longitude' => $prov['longitude'] + 0.15,
            ]);
        }
    }
}
