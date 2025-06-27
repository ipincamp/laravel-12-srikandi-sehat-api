<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Handle user login
    public function login(Request $request)
    {
        // Validate request data
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Attempt to log the user in
        if (Auth::attempt($request->only('email', 'password'))) {
            // Generate a new token for the user
            $token = $request->user()->createToken($request->token_name);

            return response()->json(['token' => $token->plainTextToken], 200);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
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

        // Generate a new token for the user
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['token' => $token], 201);
    }

    // Handle user logout
    public function logout(Request $request)
    {
        // Revoke the user's token
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }
}
