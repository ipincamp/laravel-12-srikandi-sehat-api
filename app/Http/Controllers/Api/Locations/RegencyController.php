<?php

namespace App\Http\Controllers\Api\Locations;

use App\Http\Controllers\Controller;
use App\Http\Resources\Location\RegencyResource;
use App\Models\Province;
use Illuminate\Support\Facades\Cache;

class RegencyController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(string $provinceCode)
    {
        $cacheTtl = 86400;
        $cacheKey = "regencies_for_province_code_{$provinceCode}";

        $province = Cache::remember($cacheKey, $cacheTtl, function () use ($provinceCode) {
            return Province::where('code', $provinceCode)
                ->with(['regencies' => function ($query) {
                    $query->select('id', 'name', 'code', 'province_id');
                }])
                ->first();
        });

        if (!$province) {
            return $this->json(
                status: 404,
                message: 'Province not found.',
                data: []
            );
        }

        return $this->json(
            message: 'Regencies retrieved successfully',
            data: RegencyResource::collection($province->regencies)
        );
    }
}
