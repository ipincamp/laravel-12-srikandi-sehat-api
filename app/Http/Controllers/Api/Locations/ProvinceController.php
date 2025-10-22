<?php

namespace App\Http\Controllers\Api\Locations;

use App\Http\Controllers\Controller;
use App\Http\Resources\Location\ProvinceResource;
use App\Models\Province;
use Illuminate\Support\Facades\Cache;

class ProvinceController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        $provinces = Cache::remember('provinces_list', 86400, function () {
            return Province::get(['code', 'name']);
        });

        return $this->json(
            message: 'Provinces retrieved successfully',
            data: ProvinceResource::collection($provinces)
        );
    }
}
