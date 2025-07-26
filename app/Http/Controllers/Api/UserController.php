<?php

namespace App\Http\Controllers\Api;

use App\Enums\ClassificationsEnum;
use App\Enums\RolesEnum;
use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\ChangePasswordRequest;
use App\Http\Requests\User\UpdateProfileRequest;
use App\Http\Resources\User\AllUserResource;
use App\Http\Resources\User\UserResource;
use App\Models\Classification;
use App\Models\User;
use App\Models\Village;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    /**
     * Mengambil profil user yang sedang login.
     */
    public function profile(Request $request)
    {
        $user = $request->user();
        $user->load([
            'roles',
            'profile.village.district.regency.province',
            'profile.village.classification',
            'activeCycle',
        ]);

        return $this->json(
            message: 'Profile retrieved successfully',
            data: new UserResource($user)
        );
    }

    /**
     * Update profil user.
     */
    public function updateProfile(UpdateProfileRequest $request)
    {
        $validated = $request->validated();
        $user = $request->user();

        $userFields = ['name', 'email'];
        $userData = Arr::only($validated, $userFields);
        $profileData = Arr::except($validated, $userFields);

        if (!empty($userData)) {
            $user->update($userData);
        }

        if (!empty($profileData)) {
            $address = Village::where('code', $profileData['address'])->first();
            $user->profile()->updateOrCreate(
                [
                    'user_id' => $user->id
                ],
                [
                    // 'photo_path' => $profileData['photo_path'] ?? null,
                    'phone' => $profileData['phone'],
                    'village_id' => $address->id,
                    'birthdate' => $profileData['birthdate'],
                    'height_cm' => $profileData['tb_cm'],
                    'weight_kg' => $profileData['bb_kg'],
                    'last_education' => $profileData['edu_now'],
                    'last_parent_education' => $profileData['edu_parent'],
                    'last_parent_job' => $profileData['job_parent'],
                    'internet_access' => $profileData['inet_access'],
                    'first_menstruation' => $profileData['first_haid'],
                ],
            );
        }

        $user->load([
            'roles',
            'profile.village.district.regency.province',
            'profile.village.classification',
        ]);

        return $this->json(
            message: 'Profile updated successfully',
            data: new UserResource($user)
        );
    }

    /**
     * Ganti password user.
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        $user = $request->user();

        $user->password = Hash::make($request->validated('new_password'));
        $user->save();

        $user->tokens()->delete();

        return $this->json(
            message: 'Password changed successfully. Please log in again.'
        );
    }

    /**
     * Menampilkan daftar semua user dengan filter dan paginasi.
     */
    public function index(Request $request)
    {
        // 1. Validasi (tetap sama)
        $validScopeIds = Cache::remember('classification_ids', 86400, function () {
            return Classification::pluck('id')->all();
        });

        $request->validate([
            'scope' => ['nullable', 'integer', Rule::in($validScopeIds)],
        ]);

        // Kueri ini untuk diagram lingkaran dan akan selalu menampilkan total keseluruhan.
        $stats = DB::table('classifications')
            ->select(
                'classifications.name',
                DB::raw('COUNT(DISTINCT user_profiles.user_id) as total_users')
            )
            ->leftJoin('villages', 'villages.classification_id', '=', 'classifications.id')
            ->leftJoin('user_profiles', 'user_profiles.village_id', '=', 'villages.id')
            ->whereIn('user_profiles.user_id', function ($query) {
                // Hanya hitung user yang bukan admin
                $query->select('model_id')->from('model_has_roles')->where('role_id', '!=', 1); // Asumsi ID admin = 1
            })
            ->groupBy('classifications.name')
            ->get()
            ->pluck('total_users', 'name');

        // 3. Query utama untuk daftar user (tetap sama)
        $query = User::query()->with(['profile.village.classification', 'roles']);

        $query->whereDoesntHave('roles', function ($q) {
            $q->where('name', 'admin');
        });

        if ($request->filled('scope')) {
            $classificationId = $request->scope;

            $query->whereHas('profile.village', function ($q) use ($classificationId) {
                $q->where('classification_id', $classificationId);
            });
        }

        // 4. Paginasi (tetap sama)
        // Objek $users ini sudah berisi total data yang terfilter
        $users = $query->select('id', 'name', 'created_at')->paginate(10)->withQueryString();

        // 5. Susun Ulang Respons JSON
        // Kita keluarkan 'stats' dari 'meta' agar tidak menimpa meta bawaan paginasi
        return AllUserResource::collection($users)->additional([
            "status" => true,
            "message" => "Profile retrieved successfully",
            'meta' => [
                'stats' => [
                    'all_user' => $stats->sum(),
                    'urban_user' => $stats->get(ClassificationsEnum::URBAN->value, 0),
                    'rural_user' => $stats->get(ClassificationsEnum::RURAL->value, 0),
                ]
            ],
        ]);
    }

    /**
     * Menampilkan detail user berdasarkan ID.
     */
    public function show(string $userID)
    {
        $user = User::findOrFail($userID);

        if (!$user->hasRole(RolesEnum::USER->value)) {
            return $this->json(
                status: 404,
                message: 'User not found or does not have the required role.',
            );
        }

        // Pastikan user yang diminta bukan admin
        if ($user->hasRole(RolesEnum::ADMIN->value)) {
            return $this->json(
                status: 403,
                message: 'Access denied. Cannot view admin profile.',
            );
        }

        // Load relasi yang diperlukan
        $user->load([
            'roles',
            'profile.village.district.regency.province',
            'profile.village.classification',
            'activeCycle',
            'menstrualCycles' => function ($query) {
                $query->whereNotNull('finish_date')->orderBy('start_date', 'asc');
            },
            'menstrualCycles.symptomEntries.symptoms',
        ]);

        // Kembalikan data user dalam format yang sudah diatur
        return $this->json(
            message: 'User profile retrieved successfully',
            data: new UserResource($user)
        );
    }

    /**
     * Menangani permintaan ekspor data pengguna ke CSV.
     */
    public function exportCsv()
    {
        return Excel::download(new UsersExport, 'srikandi_sehat_all_data.csv');
    }
}
