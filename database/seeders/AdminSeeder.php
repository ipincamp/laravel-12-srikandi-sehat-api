<?php

namespace Database\Seeders;

use App\Enums\RolesEnum;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'name' => config('seed.admin.name'),
            'email' => config('seed.admin.email'),
            'password' => bcrypt(config('seed.admin.password')),
            'email_verified_at' => now(),
        ]);
        $admin->assignRole(RolesEnum::ADMIN);
    }
}
