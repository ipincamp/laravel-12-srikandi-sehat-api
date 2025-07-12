<?php

namespace App\Http\Resources\Cycle;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SymptomResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'category' => $this->category,
        ];
    }
}
