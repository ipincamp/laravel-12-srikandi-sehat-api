<?php

namespace Database\Seeders;

use App\Enums\RolesEnum;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::unguard();

        $admin = User::firstOrCreate(
            [
                'email' => config('seed.admin.email'),
            ],
            [
                'name' => config('seed.admin.name'),
                'password' => bcrypt(config('seed.admin.password')),
                'email_verified_at' => now(),
            ]
        );

        $admin->assignRole(RolesEnum::ADMIN->value);

        User::factory(100)->create()->each(function ($user) {
            $user->assignRole(RolesEnum::USER->value);
        });
    }
}
