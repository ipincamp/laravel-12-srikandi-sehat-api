<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();
        $provinces = [
            [
                'code' => '33',
                'name' => 'JAWA TENGAH',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('provinces')->insert($provinces);
    }
}
