<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // User and Role Seeders
            RoleSeeder::class,

            // Classification and Location Seeders
            ClassificationSeeder::class,
            ProvinceSeeder::class,
            RegencySeeder::class,
            DistrictSeeder::class,
            VillageSeeder::class,

            // Symptom Seeder
            SymptomSeeder::class,
            AdminSeeder::class,

            // Cycle User Seeder
            CycleUserSeeder::class,
        ]);
    }
}
