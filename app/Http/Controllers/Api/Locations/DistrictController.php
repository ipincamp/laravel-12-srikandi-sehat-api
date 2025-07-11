<?php

namespace App\Http\Controllers\Api\Locations;

use App\Http\Controllers\Controller;
use App\Http\Resources\Location\DistrictResource;
use App\Models\Regency;
use Illuminate\Support\Facades\Cache;

class DistrictController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(string $regencyCode)
    {
        $cacheTtl = 86400;
        $cacheKey = "districts_for_regency_code_{$regencyCode}";

        $districts = Cache::remember($cacheKey, $cacheTtl, function () use ($regencyCode) {
            return Regency::where('code', $regencyCode)
                ->with(['districts' => function ($query) {
                    $query->select('id', 'name', 'code', 'regency_id');
                }])
                ->first();
        });

        if (!$districts) {
            return $this->json(
                status: 404,
                message: 'Regency not found.',
                data: []
            );
        }

        return $this->json(
            message: 'Districts retrieved successfully',
            data: DistrictResource::collection($districts->districts)
        );
    }
}
