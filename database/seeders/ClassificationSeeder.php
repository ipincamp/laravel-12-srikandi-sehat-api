<?php

namespace Database\Seeders;

use App\Enums\ClassificationsEnum;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();
        $classifications = [
            [
                'name' => ClassificationsEnum::URBAN->value,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => ClassificationsEnum::RURAL->value,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('classifications')->insert($classifications);
    }
}
