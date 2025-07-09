<?php

namespace App\Http\Controllers\Api\Locations;

use App\Http\Controllers\Controller;
use App\Models\Regency;
use Illuminate\Support\Facades\Cache;

class DistrictController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Regency $regency)
    {
        $districts = Cache::remember("districts_for_regency_{$regency->id}", 86400, function () use ($regency) {
            return $regency->with('districts')->get(['id', 'code', 'name']);
        });

        return $this->json(
            message: 'Districts retrieved successfully',
            data: $districts
        );
    }
}
