<?php

namespace Database\Seeders;

use App\Enums\ClassificationsEnum;
use App\Models\Classification;
use Illuminate\Database\Seeder;

class ClassificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Classification::create(['name' => ClassificationsEnum::URBAN->value]);
        Classification::create(['name' => ClassificationsEnum::RURAL->value]);
    }
}
