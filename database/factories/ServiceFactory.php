<?php

namespace Database\Factories;

use App\Models\Club;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->title,
            'is_group' => fake()->boolean,
            'duration' => fake()->numberBetween(1, 3),
            'description'=> fake()->text,
            'image_url' => fake()->imageUrl,
            'price' => fake()->numberBetween(990, 4990),
            'club_id' => fake()->numberBetween(1, Club::all()->count()),
        ];
    }
}
