<?php

namespace App\Http\Resources\User;

use App\Http\Resources\Location\VillageResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'phone' => $this->phone,
            'birthdate' => $this->birthdate,
            'height_m' => $this->height_m,
            'weight_kg' => $this->weight_kg,
            'bmi' => $this->weight_kg && $this->height_m ? round($this->weight_kg / ($this->height_m ** 2), 2) : null,
            'last_education' => $this->last_education,
            'last_parent_education' => $this->last_parent_education,
            'internet_access' => $this->internet_access,
            'first_menstruation' => $this->first_menstruation,
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'address' => new VillageResource($this->whenLoaded('village')),
        ];
    }
}
