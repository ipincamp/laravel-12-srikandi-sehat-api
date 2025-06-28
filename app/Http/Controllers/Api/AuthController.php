<?php

namespace App\Http\Controllers\Api;

use App\Enums\RolesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\AuthRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Handle user login
    public function login(LoginRequest $request)
    {
        // Validate request data
        $validated = $request->safe()->only(['email', 'password']);

        // Attempt to log the user in
        if (Auth::attempt($validated)) {
            $user = $request->user()->load('roles');

            // Generate a new token for the user
            $token = $user->createToken('auth_token');

            return $this->json(
                message: 'Login successful',
                data: [
                    'user' => new UserResource($user),
                    'token' => $token->plainTextToken
                ]
            );
        }

        return $this->json(
            status: 401,
            message: 'The provided credentials are incorrect.'
        );
    }

    // Handle user registration
    public function register(RegisterRequest $request)
    {
        // Validate request data
        $validated = $request->safe()->only(['name', 'email', 'password']);

        // Create a new user
        $user = \App\Models\User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        // Assign the default role to the user
        $user->assignRole(RolesEnum::USER);

        // Generate a new token for the user
        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->json(
            status: 201,
            message: 'Registration successful',
            data: [
                'user' => new UserResource($user->load('roles')),
                'token' => $token
            ]
        );
    }

    // Handle user logout
    public function logout(AuthRequest $request)
    {
        // Revoke the user's token
        $request->user()->currentAccessToken()->delete();

        return $this->json(
            message: 'Logout successful',
        );
    }
}
