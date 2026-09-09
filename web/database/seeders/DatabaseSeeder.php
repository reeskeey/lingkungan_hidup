<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            ProvinceAndRegencySeeder::class,
            FasyankesSeeder::class,
            TreatmentFacilitySeeder::class,
            TransferLocationSeeder::class,
            RegionalGapSeeder::class,
            RoadmapActionSeeder::class,
            UserSeeder::class,
        ]);
    }
}
