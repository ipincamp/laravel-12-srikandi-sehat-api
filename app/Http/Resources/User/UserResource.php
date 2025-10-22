<?php

namespace App\Http\Resources\User;

use App\Http\Resources\Cycle\CycleHistoryResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->roles->pluck('name')->first(),
            'profile' => UserProfileResource::make($this->whenLoaded('profile')),
            'current_cycle_number' => $this->current_cycle_number,
            'cycle_history' => $this->whenLoaded('menstrualCycles', function () {
                $completedCycles = $this->menstrualCycles;

                $historyData = $completedCycles->map(function ($currentCycle, $index) use ($completedCycles) {
                    $startDate = Carbon::parse($currentCycle->start_date);
                    $endDate = Carbon::parse($currentCycle->finish_date);

                    $periodLength = $endDate->diffInDays($startDate) + 1;
                    $cycleLength = null;

                    if (isset($completedCycles[$index + 1])) {
                        $nextCycleStartDate = Carbon::parse($completedCycles[$index + 1]->start_date);
                        $cycleLength = $nextCycleStartDate->diffInDays($startDate);
                    }

                    return (object) [
                        'cycle' => $currentCycle,
                        'period_length_days' => $periodLength,
                        'cycle_length_days' => $cycleLength,
                    ];
                });

                return CycleHistoryResource::collection($historyData->reverse());
            }),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
