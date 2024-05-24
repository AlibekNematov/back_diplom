<?php

namespace Database\Factories;

use App\Models\Club;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Club>
 */
class ClubFactory extends Factory
{
    protected $model = Club::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->name,
            'address' => fake()->sentence,
            'description' => fake()->text,
            'image_url' => fake()->imageUrl,
            'working_hours' => 'Пн-вс, 06:00-23:00',
            'start_working_timeslot' => fake()->numberBetween(6, 9),
            'end_working_timeslot' => fake()->numberBetween(0, 3),
        ];
    }
}
