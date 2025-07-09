<?php

namespace App\Http\Controllers\Api\Locations;

use App\Http\Controllers\Controller;
use App\Models\Province;
use Illuminate\Support\Facades\Cache;

class RegencyController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Province $province)
    {
        $regencies = Cache::remember("regencies_for_province_{$province->id}", 86400, function () use ($province) {
            return $province->with('regencies')->get(['id', 'code', 'name']);
        });

        return $this->json(
            message: 'Regencies retrieved successfully',
            data: $regencies
        );
    }
}
