<?php

namespace Database\Factories;

use App\Models\Village;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserProfile>
 */
class UserProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'village_id'    => Village::inRandomOrder()->first()?->id,
            'phone'         => $this->faker->unique()->e164PhoneNumber(),
            'birthdate'     => $this->faker->dateTimeBetween('-40 years', '-15 years')->format('Y-m-d'),
            'height_cm'     => $this->faker->numberBetween(140, 200),
            'weight_kg'     => $this->faker->randomFloat(2, 40.0, 90.0),
            'last_education'=> $this->faker->randomElement(['SMP', 'SMA', 'S1', 'S2']),
            'last_parent_education' => $this->faker->randomElement(['SD', 'SMP', 'SMA', 'S1']),
            'last_parent_job' => $this->faker->randomElement(['Petani', 'Pedagang', 'PNS', 'Swasta']),
            'internet_access' => $this->faker->randomElement(['wifi', 'seluler']),
            'first_menstruation' => $this->faker->dateTimeBetween('-25 years', '-10 years')->format('Y-m-d'),
        ];
    }
}
