<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Province;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $jabar = Province::where('code', '32')->first();
        $jatim = Province::where('code', '35')->first();

        // 1. Superadmin KLH / BPLH
        User::updateOrCreate(
            ['email' => 'admin@klh.go.id'],
            [
                'name' => 'Administrator KLH / BPLH',
                'password' => Hash::make('password'),
                'role' => 'superadmin',
                'province_id' => null,
            ]
        );

        // 2. Operator Daerah - Jawa Barat
        User::updateOrCreate(
            ['email' => 'operator.jabar@klh.go.id'],
            [
                'name' => 'Operator DLH Jawa Barat',
                'password' => Hash::make('password'),
                'role' => 'operator_daerah',
                'province_id' => $jabar ? $jabar->id : null,
            ]
        );

        // 3. Operator Daerah - Jawa Timur
        User::updateOrCreate(
            ['email' => 'operator.jatim@klh.go.id'],
            [
                'name' => 'Operator DLH Jawa Timur',
                'password' => Hash::make('password'),
                'role' => 'operator_daerah',
                'province_id' => $jatim ? $jatim->id : null,
            ]
        );
    }
}
