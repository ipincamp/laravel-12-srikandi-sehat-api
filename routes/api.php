<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Locations\DistrictController;
use App\Http\Controllers\Api\Locations\ProvinceController;
use App\Http\Controllers\Api\Locations\RegencyController;
use App\Http\Controllers\Api\Locations\VillageController;
use App\Http\Controllers\Api\MenstrualCycleController;
use App\Http\Controllers\Api\SymptomController;
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
    Route::get('/provinces/{provinceCode}/regencies', [RegencyController::class, '__invoke'])->name('locations.regencies');
    Route::get('/regencies/{regencyCode}/districts', [DistrictController::class, '__invoke'])->name('locations.districts');
    Route::get('/districts/{districtCode}/villages', [VillageController::class, '__invoke'])->name('locations.villages');
});

// Rute yang Membutuhkan Autentikasi
Route::middleware('auth:sanctum')->group(function () {
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('users/{userID}', [UserController::class, 'show'])->name('users.show');
    Route::get('users/export-csv', [UserController::class, 'exportCsv'])->name('users.export.csv');

    // User Profile
    Route::prefix('me')->controller(UserController::class)->name('users.')->group(function () {
        Route::get('/', 'profile')->name('profile');
        Route::post('profile', 'updateProfile')->name('updateProfile');
        Route::post('password', 'changePassword')->name('changePassword');
    });

    // Daftar gejala (symptoms)
    Route::get('cycles/symptoms', [SymptomController::class, '__invoke'])->name('cycles.symptoms');
    // Menstrual Cycle
    Route::prefix('cycles')->controller(MenstrualCycleController::class)->group(function () {
        Route::get('symptoms/{entry}', 'showSymptomLog')->name('cycles.symptoms.show');
        Route::get('history', 'history')->name('cycles.history');
        Route::post('start', 'start')->name('cycles.start');
        Route::post('finish', 'finish')->name('cycles.finish');
        Route::post('symptoms', 'logSymptoms')->name('cycles.log_symptoms');
    });

    // Logout
    Route::post('auth/logout', [AuthController::class, 'logout'])->name('auth.logout');
});
