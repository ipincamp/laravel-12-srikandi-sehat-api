<?php

namespace App\Http\Resources\Cycle;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FinishedCycleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Konversi ke objek Carbon untuk perhitungan yang akurat
        $startDate = Carbon::parse($this->start_date);
        $finishDate = Carbon::parse($this->finish_date);

        // Hitung lama haid (selisih hari + 1)
        $periodLength = $finishDate->diffInDays($startDate) + 1;

        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'start_date' => $this->start_date,
            'finish_date' => $this->finish_date,
            'period_length_days' => round($periodLength),
        ];
    }
}
