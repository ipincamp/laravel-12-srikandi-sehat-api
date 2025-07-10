<?php

namespace App\Http\Resources\Location;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VillageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'code' => $this->code,
            'classification' => $this->whenLoaded('classification', fn() => $this->classification->name),
            'district' => new DistrictResource($this->whenLoaded('district')),
        ];
    }
}
