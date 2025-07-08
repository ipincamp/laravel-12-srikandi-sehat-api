<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Province;

class RegencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jateng = DB::table('provinces')->where('code', '33')->first();
        $now = now();

        if ($jateng) {
            $regencies = [
                [
                    'province_id' => $jateng->id,
                    'code' => '3302',
                    'name' => 'BANYUMAS',
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ];

            DB::table('regencies')->insert($regencies);
        } else {
            $this->command->info('Province seeder for Jawa Tengah not found. Please run ProvinceSeeder first.');
        }
    }
}
