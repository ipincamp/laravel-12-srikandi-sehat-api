<?php

namespace App\Exports;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection, WithHeadings
{
    public function headings(): array
    {
        return [
            'User ID',
            'Nama User',
            'Email',
            'Telepon',
            'Umur (Tahun)',
            'Tinggi (cm)',
            'Berat (kg)',
            'Pendidikan Terakhir',
            'Pekerjaan Orang Tua',
            'Pendidikan Ortu',
            'Akses Internet',
            'Usia Haid Pertama (Tahun)',
            'Alamat Lengkap',
            'Siklus Ke',
            'Tanggal Mulai Siklus',
            'Tanggal Selesai Siklus',
            'Durasi Haid (Hari)',
            'Panjang Siklus (Hari)',
            'Gejala Tercatat (dipisah koma)',
            'Catatan Gejala (digabung)',
        ];
    }

    public function collection(): Collection
    {
        $users = User::whereDoesntHave('roles', fn($q) => $q->where('name', 'admin'))
            ->with([
                'profile.village.district.regency.province',
                'profile.village.classification',
                'menstrualCycles' => fn($q) => $q->whereNotNull('finish_date')->orderBy('start_date', 'asc'),
                'menstrualCycles.symptomEntries.symptoms:name',
            ])
            ->get();

        $exportData = collect();

        foreach ($users as $user) {
            $completedCycles = $user->menstrualCycles;

            foreach ($completedCycles as $index => $cycle) {
                $startDate = Carbon::parse($cycle->start_date);
                $endDate = Carbon::parse($cycle->finish_date);

                $periodLength = abs($endDate->diffInDays($startDate)) + 1;
                $cycleLength = null;
                if (isset($completedCycles[$index + 1])) {
                    $cycleLength = round(abs(Carbon::parse($completedCycles[$index + 1]->start_date)->diffInDays($startDate)));
                }

                $symptoms = $cycle->symptomEntries->flatMap->symptoms->pluck('name')->unique()->implode(', ');
                $notes = $cycle->symptomEntries->pluck('notes')->filter()->implode(' | ');

                $profile = optional($user->profile);
                $village = optional($profile->village);
                $classification = optional($village->classification);
                $address = $this->formatAddress($village, $classification);

                $exportData->push([
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'email' => $user->email,
                    'phone' => $profile->phone,
                    'age' => $profile->birthdate ? Carbon::parse($profile->birthdate)->age : null,
                    'height_cm' => $profile->height_cm,
                    'weight_kg' => $profile->weight_kg,
                    'last_education' => $profile->last_education,
                    'parent_job' => $profile->last_parent_job,
                    'last_parent_education' => $profile->last_parent_education,
                    'internet_access' => $profile->internet_access,
                    'age_at_first_menstruation' => $profile->first_menstruation,
                    'address' => $address,
                    'cycle_number' => $index + 1,
                    'cycle_start_date' => $startDate->toDateString(),
                    'cycle_finish_date' => $endDate->toDateString(),
                    'period_length_days' => $periodLength,
                    'cycle_length_days' => $cycleLength,
                    'symptoms_list' => $symptoms ?: '-',
                    'notes_list' => $notes ?: '-',
                ]);
            }
        }

        return $exportData;
    }

    private function formatAddress($village, $classification): ?string
    {
        if (!$village) return null;
        $classificationLabel = (strtolower(optional($classification)->name) === 'rural') ? 'DESA' : 'KOTA';
        $villageName = $village->name;
        $districtName = optional($village->district)->name;
        $regencyName = optional(optional($village->district)->regency)->name;
        $provinceName = optional(optional(optional($village->district)->regency)->province)->name;
        return "($classificationLabel) {$villageName}, KECAMATAN {$districtName}, KABUPATEN {$regencyName}, PROVINSI {$provinceName}";
    }
}
