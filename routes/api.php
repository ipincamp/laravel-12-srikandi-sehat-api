<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Locations\DistrictController;
use App\Http\Controllers\Api\Locations\ProvinceController;
use App\Http\Controllers\Api\Locations\RegencyController;
use App\Http\Controllers\Api\Locations\VillageController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Rute Publik (Authentication)
Route::prefix('auth')->controller(AuthController::class)->group(function () {
    Route::post('login', 'login')->name('auth.login');
    Route::post('register', 'register')->name('auth.register');
});

// Rute Lokasi (Umumnya Publik) - untuk dropdown alamat
Route::prefix('locations')->group(function () {
    Route::get('/provinces', [ProvinceController::class, '__invoke'])->name('locations.provinces');
    Route::get('/provinces/{province}/regencies', [RegencyController::class, '__invoke'])->name('locations.regencies');
    Route::get('/regencies/{regency}/districts', [DistrictController::class, '__invoke'])->name('locations.districts');
    Route::get('/districts/{districtCode}/villages', [VillageController::class, '__invoke'])->name('locations.villages');
});

// Rute yang Membutuhkan Autentikasi
Route::middleware('auth:sanctum')->group(function () {
    // User Profile
    Route::prefix('me')->controller(UserController::class)->name('users.')->group(function () {
        Route::get('/', 'profile')->name('profile');
        Route::post('profile', 'updateProfile')->name('updateProfile');
        Route::post('password', 'changePassword')->name('changePassword');
    });

    // Logout
    Route::post('auth/logout', [AuthController::class, 'logout'])->name('auth.logout');
});
