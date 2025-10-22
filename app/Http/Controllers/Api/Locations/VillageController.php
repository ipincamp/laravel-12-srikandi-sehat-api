<?php

namespace App\Http\Controllers\Api\Locations;

use App\Http\Controllers\Controller;
use App\Http\Resources\Location\VillageResource;
use App\Models\Village;
use Illuminate\Support\Facades\Cache;

class VillageController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(string $districtCode)
    {
        $cacheTtl = 86400;
        $cacheKey = "villages_for_district_code_{$districtCode}";

        $villages = Cache::remember($cacheKey, $cacheTtl, function () use ($districtCode) {
            return Village::whereHas('district', function ($query) use ($districtCode) {
                $query->where('code', $districtCode);
            })
                ->with('classification:id,name')
                ->get(['id', 'name', 'code', 'classification_id']);
        });

        if ($villages->isEmpty()) {
            return $this->json(
                status: 404,
                message: 'No villages found for the specified district.',
                data: []
            );
        }

        return $this->json(
            message: 'Villages retrieved successfully',
            data: VillageResource::collection($villages)
        );
    }
}
