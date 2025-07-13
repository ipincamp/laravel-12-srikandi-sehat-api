<?php

namespace App\Http\Controllers\Api;

use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\ChangePasswordRequest;
use App\Http\Requests\User\UpdateProfileRequest;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use App\Models\Village;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
        $request->validate([
            'classification' => ['nullable', 'string', 'in:urban,rural'],
        ]);

        $stats = DB::table('classifications')
            ->select(
                'classifications.name',
                DB::raw('COUNT(user_profiles.user_id) as total_users')
            )
            ->leftJoin('villages', 'villages.classification_id', '=', 'classifications.id')
            ->leftJoin('user_profiles', 'user_profiles.village_id', '=', 'villages.id')
            ->groupBy('classifications.name')
            ->get()
            ->pluck('total_users', 'name');

        $query = User::query()->with(['profile.village.classification', 'roles']);

        if ($request->filled('classification')) {
            $query->whereHas('profile.village.classification', function ($q) use ($request) {
                $q->where('name', $request->classification);
            });
        }

        $users = $query->paginate(10)->withQueryString();

        return UserResource::collection($users)->additional([
            "status" => true,
            "message" => "Profile retrieved successfully",
            'meta' => [
                'stats' => [
                    'urban' => $stats->get('urban', 0),
                    'rural' => $stats->get('rural', 0),
                ]
            ],
        ]);
    }

    /**
     * Menangani permintaan ekspor data pengguna ke CSV.
     */
    public function exportCsv()
    {
        return Excel::download(new UsersExport, 'srikandi_sehat_users.csv');
    }
}
