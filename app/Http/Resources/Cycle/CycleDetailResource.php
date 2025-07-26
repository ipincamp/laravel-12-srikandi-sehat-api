<?php

namespace App\Http\Resources\Cycle;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CycleDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $periodLength = null;
        if ($this->finish_date) {
            $startDate = Carbon::parse($this->start_date);
            $finishDate = Carbon::parse($this->finish_date);
            $periodLength = $finishDate->diffInDays($startDate) + 1;
        }

        return [
            'id' => $this->id,
            'start_date' => Carbon::parse($this->start_date)->format('Y-m-d'),
            'finish_date' => $this->finish_date ? Carbon::parse($this->finish_date)->format('Y-m-d') : null,
            'period_length_days' => $periodLength,
            'symptom_entries' => SymptomEntryResource::collection($this->whenLoaded('symptomEntries')),
        ];
    }
}
