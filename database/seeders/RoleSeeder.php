<?php

namespace Database\Seeders;

use App\Enums\RolesEnum;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();
        $roles = [
            [
                'name' => RolesEnum::ADMIN->value,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => RolesEnum::USER->value,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('roles')->insert($roles);
    }
}
