<?php

namespace Database\Seeders;

use App\Enums\RolesEnum;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if admin exists in a single query
        $adminEmail = config('seed.admin.email');
        if (!DB::table('users')->where('email', $adminEmail)->exists()) {
            // Get admin role ID in advance to avoid extra query
            $roleId = DB::table('roles')
                ->where('name', RolesEnum::ADMIN->value)
                ->value('id');

            // Generate UUID once
            $adminId = (string) \Illuminate\Support\Str::uuid();
            $now = now();

            // Insert admin user
            DB::table('users')->insert([
                'id' => $adminId,
                'name' => config('seed.admin.name'),
                'email' => $adminEmail,
                'password' => bcrypt(config('seed.admin.password')),
                'email_verified_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            // Assign role in a single query
            DB::table('model_has_roles')->insert([
                'role_id' => $roleId,
                'model_type' => User::class,
                'model_id' => $adminId,
            ]);
        }
    }
}
