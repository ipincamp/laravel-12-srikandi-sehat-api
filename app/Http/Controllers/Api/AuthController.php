<?php

namespace App\Http\Controllers\Api;

use App\Enums\RolesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /**
     * Handle user login.
     *
     * @param  LoginRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $credentials = $request->safe()->only(['email', 'password']);

            if (!Auth::attempt($credentials)) {
                return $this->json(
                    status: 401,
                    message: 'Invalid credentials. Please try again.'
                );
            }

            $user = User::where('email', $credentials['email'])->first();


            $user->tokens()->where('name', 'auth_token')->delete();

            $token = $user->createToken('auth_token')->plainTextToken;

            return $this->json(
                message: 'Login successful',
                data: [
                    'user'  => new UserResource(
                        $user->load(['roles', 'profile'])
                    ),
                    'token' => $token
                ]
            );
        } catch (\Throwable $th) {
            Log::error('Registration error: ' . $th->getMessage());
            return $this->json(
                status: 500,
                message: 'An error occurred on the server. Please try again later.'
            );
        }
    }

    /**
     * Handle user registration.
     *
     * @param  RegisterRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $validated = $request->safe()->only(['name', 'email', 'password']);

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => bcrypt($validated['password']),
            ]);

            $user->assignRole(RolesEnum::USER);

            $token = $user->createToken('auth_token')->plainTextToken;

            return $this->json(
                status: 201,
                message: 'Registration successful',
                data: [
                    'user' => new UserResource(
                        $user->load(['roles', 'profile'])
                    ),
                    'token' => $token
                ]
            );
        } catch (\Throwable $th) {
            Log::error('Registration error: ' . $th->getMessage());
            return $this->json(
                status: 500,
                message: 'An error occurred on the server. Please try again later.'
            );
        }
    }

    /**
     * Handle user logout.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return $this->json(
                message: 'Logout successful'
            );
        } catch (\Throwable $th) {
            Log::error('Registration error: ' . $th->getMessage());
            return $this->json(
                status: 500,
                message: 'An error occurred on the server. Please try again later.'
            );
        }
    }
}
