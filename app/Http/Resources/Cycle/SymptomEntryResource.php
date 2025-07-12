<?php

namespace App\Http\Resources\Cycle;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SymptomEntryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'log_date' => Carbon::parse($this->log_date)->toDateString(),
            'notes' => $this->notes,
            // Gunakan SymptomResource untuk mengubah koleksi gejala
            'symptoms' => SymptomResource::collection($this->whenLoaded('symptoms')),
        ];
    }
}
