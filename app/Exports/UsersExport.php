<?php

namespace App\Exports;

use App\Enums\RolesEnum;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UsersExport implements FromQuery, WithHeadings, WithMapping
{
    /**
     * Menentukan judul untuk setiap kolom di CSV.
     */
    public function headings(): array
    {
        return [
            'ID',
            'Nama',
            'Email',
            'Role',
            'Telepon',
            'Tanggal Lahir',
            'Klasifikasi',
            'Desa/Kelurahan',
            'Kecamatan',
            'Kabupaten/Kota',
            'Provinsi',
            'Tinggi (m)',
            'Berat (kg)',
            'Pendidikan Terakhir',
            'Pendidikan Ortu',
            'Akses Internet',
            'Menstruasi Pertama',
        ];
    }

    /**
     * Mengambil data dari database secara efisien.
     */
    public function query()
    {
        // Eager load semua relasi yang dibutuhkan untuk performa
        return User::query()
            ->with([
                'roles:id,name',
                'profile.village.classification:id,name',
                'profile.village.district.regency.province',
                'activeCycle',
            ])
            ->whereDoesntHave('roles', function ($query) {
                $query->where('name', RolesEnum::ADMIN->value);
            });
    }

    /**
     * Memetakan setiap baris data ke format yang diinginkan.
     */
    public function map($user): array
    {
        // Gunakan optional() untuk mencegah error jika data profile atau relasi lainnya null
        $profile = optional($user->profile);
        $village = optional($profile->village);
        $district = optional($village->district);
        $regency = optional($district->regency);
        $province = optional($regency->province);
        $classification = optional($village->classification);

        return [
            $user->id,
            $user->name,
            $user->email,
            $user->roles->first()->name ?? 'N/A',
            $profile->phone,
            $profile->birthdate,
            $classification->name,
            $village->name,
            $district->name,
            $regency->name,
            $province->name,
            $profile->height_m,
            $profile->weight_kg,
            $profile->last_education,
            $profile->last_parent_education,
            $profile->internet_access,
            $profile->first_menstruation,
        ];
    }
}
