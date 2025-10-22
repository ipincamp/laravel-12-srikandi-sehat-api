<?php

namespace Database\Seeders;

use App\Models\District;
use App\Models\Regency;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banyumasRegency = DB::table('regencies')->where('code', '3302')->first();

        if (!$banyumasRegency) {
            $this->command->error('Regency with code 3302 (BANYUMAS) not found. Please run RegencySeeder first.');
            return;
        }

        $districts = [
            ['code' => '3302010', 'name' => 'LUMBIR'],
            ['code' => '3302020', 'name' => 'WANGON'],
            ['code' => '3302030', 'name' => 'JATILAWANG'],
            ['code' => '3302040', 'name' => 'RAWALO'],
            ['code' => '3302050', 'name' => 'KEBASEN'],
            ['code' => '3302060', 'name' => 'KEMRANJEN'],
            ['code' => '3302070', 'name' => 'SUMPIUH'],
            ['code' => '3302080', 'name' => 'TAMBAK'],
            ['code' => '3302090', 'name' => 'SOMAGEDE'],
            ['code' => '3302100', 'name' => 'KALIBAGOR'],
            ['code' => '3302110', 'name' => 'BANYUMAS'],
            ['code' => '3302120', 'name' => 'PATIKRAJA'],
            ['code' => '3302130', 'name' => 'PURWOJATI'],
            ['code' => '3302140', 'name' => 'AJIBARANG'],
            ['code' => '3302150', 'name' => 'GUMELAR'],
            ['code' => '3302160', 'name' => 'PEKUNCEN'],
            ['code' => '3302170', 'name' => 'CILONGOK'],
            ['code' => '3302180', 'name' => 'KARANGLEWAS'],
            ['code' => '3302190', 'name' => 'KEDUNG BANTENG'],
            ['code' => '3302200', 'name' => 'BATURRADEN'],
            ['code' => '3302210', 'name' => 'SUMBANG'],
            ['code' => '3302220', 'name' => 'KEMBARAN'],
            ['code' => '3302230', 'name' => 'SOKARAJA'],
            ['code' => '3302710', 'name' => 'PURWOKERTO SELATAN'],
            ['code' => '3302720', 'name' => 'PURWOKERTO BARAT'],
            ['code' => '3302730', 'name' => 'PURWOKERTO TIMUR'],
            ['code' => '3302740', 'name' => 'PURWOKERTO UTARA'],
        ];

        $now = now();
        foreach ($districts as &$district) {
            $district['regency_id'] = $banyumasRegency->id;
            $district['created_at'] = $now;
            $district['updated_at'] = $now;
        }

        DB::table('districts')->insert($districts);
    }
}
