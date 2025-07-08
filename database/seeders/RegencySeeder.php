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
        $provinceJateng = Province::where('code', '33')->first();

        if ($provinceJateng) {
            $regencies = [
                // Data untuk Provinsi Jawa Tengah
                ['province_id' => $provinceJateng->id, 'code' => '3301', 'name' => 'KABUPATEN CILACAP'],
                ['province_id' => $provinceJateng->id, 'code' => '3302', 'name' => 'KABUPATEN BANYUMAS'],
                ['province_id' => $provinceJateng->id, 'code' => '3303', 'name' => 'KABUPATEN PURBALINGGA'],
                ['province_id' => $provinceJateng->id, 'code' => '3304', 'name' => 'KABUPATEN BANJARNEGARA'],
                ['province_id' => $provinceJateng->id, 'code' => '3305', 'name' => 'KABUPATEN KEBUMEN'],
                ['province_id' => $provinceJateng->id, 'code' => '3306', 'name' => 'KABUPATEN PURWOREJO'],
                ['province_id' => $provinceJateng->id, 'code' => '3307', 'name' => 'KABUPATEN WONOSOBO'],
                ['province_id' => $provinceJateng->id, 'code' => '3308', 'name' => 'KABUPATEN MAGELANG'],
                ['province_id' => $provinceJateng->id, 'code' => '3309', 'name' => 'KABUPATEN BOYOLALI'],
                ['province_id' => $provinceJateng->id, 'code' => '3310', 'name' => 'KABUPATEN KLATEN'],
                ['province_id' => $provinceJateng->id, 'code' => '3311', 'name' => 'KABUPATEN SUKOHARJO'],
                ['province_id' => $provinceJateng->id, 'code' => '3312', 'name' => 'KABUPATEN WONOGIRI'],
                ['province_id' => $provinceJateng->id, 'code' => '3313', 'name' => 'KABUPATEN KARANGANYAR'],
                ['province_id' => $provinceJateng->id, 'code' => '3314', 'name' => 'KABUPATEN SRAGEN'],
                ['province_id' => $provinceJateng->id, 'code' => '3315', 'name' => 'KABUPATEN GROBOGAN'],
                ['province_id' => $provinceJateng->id, 'code' => '3316', 'name' => 'KABUPATEN BLORA'],
                ['province_id' => $provinceJateng->id, 'code' => '3317', 'name' => 'KABUPATEN REMBANG'],
                ['province_id' => $provinceJateng->id, 'code' => '3318', 'name' => 'KABUPATEN PATI'],
                ['province_id' => $provinceJateng->id, 'code' => '3319', 'name' => 'KABUPATEN KUDUS'],
                ['province_id' => $provinceJateng->id, 'code' => '3320', 'name' => 'KABUPATEN JEPARA'],
                ['province_id' => $provinceJateng->id, 'code' => '3321', 'name' => 'KABUPATEN DEMAK'],
                ['province_id' => $provinceJateng->id, 'code' => '3322', 'name' => 'KABUPATEN SEMARANG'],
                ['province_id' => $provinceJateng->id, 'code' => '3323', 'name' => 'KABUPATEN TEMANGGUNG'],
                ['province_id' => $provinceJateng->id, 'code' => '3324', 'name' => 'KABUPATEN KENDAL'],
                ['province_id' => $provinceJateng->id, 'code' => '3325', 'name' => 'KABUPATEN BATANG'],
                ['province_id' => $provinceJateng->id, 'code' => '3326', 'name' => 'KABUPATEN PEKALONGAN'],
                ['province_id' => $provinceJateng->id, 'code' => '3327', 'name' => 'KABUPATEN PEMALANG'],
                ['province_id' => $provinceJateng->id, 'code' => '3328', 'name' => 'KABUPATEN TEGAL'],
                ['province_id' => $provinceJateng->id, 'code' => '3329', 'name' => 'KABUPATEN BREBES'],
                ['province_id' => $provinceJateng->id, 'code' => '3371', 'name' => 'KOTA MAGELANG'],
                ['province_id' => $provinceJateng->id, 'code' => '3372', 'name' => 'KOTA SURAKARTA'],
                ['province_id' => $provinceJateng->id, 'code' => '3373', 'name' => 'KOTA SALATIGA'],
                ['province_id' => $provinceJateng->id, 'code' => '3374', 'name' => 'KOTA SEMARANG'],
                ['province_id' => $provinceJateng->id, 'code' => '3375', 'name' => 'KOTA PEKALONGAN'],
                ['province_id' => $provinceJateng->id, 'code' => '3376', 'name' => 'KOTA TEGAL'],
            ];

            DB::table('regencies')->insert($regencies);
        } else {
            $this->command->info('Province seeder for Jawa Tengah not found. Please run ProvinceSeeder first.');
        }
    }
}
