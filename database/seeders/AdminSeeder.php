<?php

namespace Database\Seeders;

use App\Enums\RolesEnum;
use App\Models\MenstrualCycle;
use App\Models\Symptom;
use App\Models\SymptomEntry;
use App\Models\User;
use App\Models\UserProfile;
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

        // 200 user dummy dengan data lengkap
        User::factory(200)
            ->has(UserProfile::factory(), 'profile') // Setiap user punya 1 profil
            ->has(
                MenstrualCycle::factory()->count(rand(3, 6)) // Setiap user punya 3-6 siklus
                    ->has(
                        SymptomEntry::factory()
                            ->count(rand(2, 5)) // Setiap siklus punya 2-5 entri gejala
                            ->state(function (array $attributes, MenstrualCycle $cycle) {
                                // Teruskan user_id dari siklus (induk) ke entri gejala (anak)
                                return ['user_id' => $cycle->user_id];
                            })
                            ->afterCreating(function (SymptomEntry $entry) {
                                // Untuk setiap entri, lampirkan 1-3 gejala acak
                                $symptoms = Symptom::inRandomOrder()->limit(rand(1, 3))->get();
                                $entry->symptoms()->attach($symptoms);
                            })
                    )
            )
            ->create() // Jalankan pembuatan semua data di atas
            ->each(function ($user) {
                // Terakhir, berikan role 'user' untuk setiap user baru
                $user->assignRole(RolesEnum::USER->value);
            });
    }
}
