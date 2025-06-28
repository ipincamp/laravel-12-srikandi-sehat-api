<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AuthRequest;
use App\Http\Requests\User\ChangePasswordRequest;
use App\Http\Requests\User\UpdateProfileRequest;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    // Handle locations retrieval
    public function locations()
    {
        // https://github.com/emsifa/api-wilayah-indonesia
        $districts = cache()->remember('districts_with_villages', 3600, function () {
            return \App\Models\District::with('villages:id,code,name,district_id')->get(['id', 'name']);
        });

        return $this->json(
            message: 'Districts retrieved successfully',
            data: $districts
        );
    }

    // Handle user profile retrieval
    public function profile(AuthRequest $request)
    {
        // Return the authenticated user's profile
        return $this->json(
            message: 'Profile retrieved successfully',
            data: new UserResource($request->user()->load('roles', 'profile'))
        );
    }

    // Handle user profile update
    public function updateProfile(UpdateProfileRequest $request)
    {
        // Validate request data
        $validated = $request->validated();

        // Update user profile
        $user = $request->user();

        if (isset($validated['name'])) {
            $user->name = $validated['name'];
        }
        if (isset($validated['email'])) {
            $user->email = $validated['email'];
        }
        if (isset($validated['address'])) {
            $user->profile->address = $validated['address'];
        }
        if (isset($validated['date_of_birth'])) {
            $user->profile->date_of_birth = $validated['date_of_birth'];
        }
        if (isset($validated['height_cm'])) {
            $user->profile->height_cm = $validated['height_cm'];
        }
        if (isset($validated['weight_kg'])) {
            $user->profile->weight_kg = $validated['weight_kg'];
        }

        $user->save();

        return $this->json(
            message: 'Profile updated successfully',
            data: $user->load('profile')
        );
    }

    // Handle password change
    public function changePassword(ChangePasswordRequest $request)
    {
        // Validate request data
        $validated = $request->validated();
        $user = $request->user();

        // Update password
        $user->password = bcrypt($validated['new_password']);
        $user->save();

        // Invalidate all tokens for the user
        $request->user()->tokens()->delete();

        return $this->json(
            message: 'Password changed successfully. Please log in again.'
        );
    }
}
