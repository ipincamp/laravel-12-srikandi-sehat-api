<?php

namespace Database\Seeders;

use App\Models\District;
use Illuminate\Database\Seeder;

class DistrictSeeder extends Seeder
{
    public function run(): void
    {
        $districts = [
            ['id' => 10, 'name' => 'KECAMATAN LUMBIR'],
            ['id' => 20, 'name' => 'KECAMATAN WANGON'],
            ['id' => 30, 'name' => 'KECAMATAN JATILAWANG'],
            ['id' => 40, 'name' => 'KECAMATAN RAWALO'],
            ['id' => 50, 'name' => 'KECAMATAN KEBASEN'],
            ['id' => 60, 'name' => 'KECAMATAN KEMRANJEN'],
            ['id' => 70, 'name' => 'KECAMATAN SUMPIUH'],
            ['id' => 80, 'name' => 'KECAMATAN TAMBAK'],
            ['id' => 90, 'name' => 'KECAMATAN SOMAGEDE'],
            ['id' => 100, 'name' => 'KECAMATAN KALIBAGOR'],
            ['id' => 110, 'name' => 'KECAMATAN BANYUMAS'],
            ['id' => 120, 'name' => 'KECAMATAN PATIKRAJA'],
            ['id' => 130, 'name' => 'KECAMATAN PURWOJATI'],
            ['id' => 140, 'name' => 'KECAMATAN AJIBARANG'],
            ['id' => 150, 'name' => 'KECAMATAN GUMELAR'],
            ['id' => 160, 'name' => 'KECAMATAN PEKUNCEN'],
            ['id' => 170, 'name' => 'KECAMATAN CILONGOK'],
            ['id' => 180, 'name' => 'KECAMATAN KARANGLEWAS'],
            ['id' => 190, 'name' => 'KECAMATAN KEDUNG BANTENG'],
            ['id' => 200, 'name' => 'KECAMATAN BATURRADEN'],
            ['id' => 210, 'name' => 'KECAMATAN SUMBANG'],
            ['id' => 220, 'name' => 'KECAMATAN KEMBARAN'],
            ['id' => 230, 'name' => 'KECAMATAN SOKARAJA'],
            ['id' => 710, 'name' => 'KECAMATAN PURWOKERTO SELATAN'],
            ['id' => 720, 'name' => 'KECAMATAN PURWOKERTO BARAT'],
            ['id' => 730, 'name' => 'KECAMATAN PURWOKERTO TIMUR'],
            ['id' => 740, 'name' => 'KECAMATAN PURWOKERTO UTARA'],
        ];

        $now = now();
        $districts = array_map(function ($district) use ($now) {
            return array_merge($district, [
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }, $districts);

        District::insert($districts);
    }
}
