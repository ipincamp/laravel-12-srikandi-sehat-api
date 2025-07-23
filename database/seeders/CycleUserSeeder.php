<?php

namespace Database\Seeders;

use App\Enums\RolesEnum;
use App\Models\MenstrualCycle;
use App\Models\UserProfile;
use App\Models\Symptom;
use App\Models\User;
use App\Models\Village;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CycleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Buat user spesifik untuk data dummy
        $dummyUser = User::firstOrCreate(
            ['email' => config('seed.user.email')],
            [
                'name' => config('seed.user.name'),
                'password' => bcrypt(config('seed.user.password')),
                'email_verified_at' => now(),
            ]
        );
        $dummyUser->assignRole(RolesEnum::USER->value);

        // 2. Buat profil untuk user tersebut
        UserProfile::updateOrCreate(
            ['user_id' => $dummyUser->id],
            [
                'village_id' => Village::inRandomOrder()->first()?->id,
                'phone' => '081234567890',
                'birthdate' => '1998-01-15',
                'height_cm' => 160,
                'weight_kg' => 55,
                'last_education' => 'SMA',
                'last_parent_education' => 'SMA',
                'last_parent_job' => 'Petani',
                'internet_access' => 'wifi',
                'first_menstruation' => 15,
            ]
        );

        // 3. Ambil data master gejala untuk dilampirkan nanti
        $symptoms = Symptom::all();
        if ($symptoms->isEmpty()) {
            $this->command->warn('Symptoms table is empty, skipping symptom log creation for the dummy user.');
        }

        // 4. Logika untuk membuat 4 siklus berurutan
        $currentStartDate = Carbon::now()->subMonths(4); // Mulai dari 4 bulan yang lalu

        for ($i = 0; $i < 4; $i++) {
            $periodLength = rand(6, 7); // Durasi haid 6-7 hari
            $finishDate = $currentStartDate->copy()->addDays($periodLength - 1);

            // Buat data siklus di database
            $cycle = MenstrualCycle::create([
                'user_id' => $dummyUser->id,
                'start_date' => $currentStartDate,
                'finish_date' => $finishDate,
            ]);

            // Buat beberapa entri gejala acak di dalam durasi haid
            if ($symptoms->isNotEmpty()) {
                for ($j = 0; $j < rand(2, 3); $j++) {
                    $logDate = $currentStartDate->copy()->addDays(rand(0, $periodLength - 1));
                    $entry = $cycle->symptomEntries()->create([
                        'user_id' => $dummyUser->id,
                        'log_date' => $logDate,
                        'notes' => 'Ini adalah catatan dummy untuk tanggal ' . $logDate->toDateString(),
                    ]);
                    // Lampirkan 1-2 gejala acak ke entri ini
                    $entry->symptoms()->attach($symptoms->random(rand(1, 2))->pluck('id')->toArray());
                }
            }

            // Siapkan tanggal mulai untuk siklus berikutnya (14 hari dari start saat ini)
            $currentStartDate->addDays(14);
        }
    }
}
