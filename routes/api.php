<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;

// Authentication routes
Route::prefix('auth')->controller(AuthController::class)->group(function () {
    Route::post('login', 'login')->name('auth.login');
    Route::post('register', 'register')->name('auth.register');
    Route::middleware('auth:sanctum')->post('logout', 'logout')->name('auth.logout');
});

// User routes
Route::middleware('auth:sanctum')->prefix('me')->controller(UserController::class)->group(function () {
    Route::get('locations', 'locations')->name('users.locations');
    Route::get('/', 'profile')->name('users.profile');
    Route::put('profile', 'updateProfile')->name('users.updateProfile');
    Route::patch('password', 'changePassword')->name('users.changePassword');
});
