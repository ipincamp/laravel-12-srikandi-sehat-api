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
            'height_cm' => $this->height_cm,
            'weight_kg' => $this->weight_kg,
            'bmi' => $this->weight_kg && $this->height_cm ? round($this->weight_kg / (($this->height_cm / 100) ** 2), 2) : null,
            'last_education' => $this->last_education,
            'last_parent_education' => $this->last_parent_education,
            'last_parent_job' => $this->last_parent_job,
            'internet_access' => $this->internet_access,
            'first_menstruation' => $this->first_menstruation,
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'address' => $this->whenLoaded('village', function () {
                $village = $this->village;
                $classification = strtoupper($village->classification) === 'PERDESAAN' ? 'DESA' : 'KOTA';
                return "($classification) {$village->name}, KECAMATAN {$village->district->name}, KABUPATEN {$village->district->regency->name}, PROVINSI {$village->district->regency->province->name}";
            }),
        ];
    }
}
