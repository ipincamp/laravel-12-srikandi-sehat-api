<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MenstrualCycle>
 */
class MenstrualCycleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = Carbon::instance($this->faker->dateTimeThisYear());
        $finishDate = $startDate->copy()->addDays(rand(4, 7));

        return [
            'start_date'  => $startDate,
            'finish_date' => $finishDate,
        ];
    }
}
