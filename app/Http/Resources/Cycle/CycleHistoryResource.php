<?php

namespace App\Http\Resources\Cycle;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CycleHistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->cycle->id,
            // Ambil data cycle dari objek yang kita kirim dari controller
            'start_date' => Carbon::parse($this->cycle->start_date)->toDateString(),
            'finish_date' => Carbon::parse($this->cycle->finish_date)->toDateString(),

            // Ambil hasil perhitungan dari controller
            'period_length_days' => $this->period_length_days,
            'cycle_length_days' => $this->cycle_length_days,

            // Gunakan SymptomEntryResource untuk menampilkan detail setiap catatan gejala
            // 'symptom_entries' => SymptomEntryResource::collection($this->cycle->symptomEntries),
        ];
    }
}
