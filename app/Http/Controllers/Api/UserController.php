<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ChangePasswordRequest;
use App\Http\Requests\User\UpdateProfileRequest;
use App\Http\Resources\User\UserResource;
use App\Models\Village;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

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
                    'height_m' => $profileData['tb_m'],
                    'weight_kg' => $profileData['bb_kg'],
                    'last_education' => $profileData['edu_now'],
                    'last_parent_education' => $profileData['edu_parent'],
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
}
