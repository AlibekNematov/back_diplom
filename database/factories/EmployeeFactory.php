<?php

namespace Database\Factories;

use App\Models\Club;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'club_id' => Club::get()->random()->id,
            'name' => fake()->firstName,
            'surname' => fake()->lastName,
            'patronymic' => fake()->name,
            'position'=> fake()->word,
            'phone_number' => fake()->numerify("###########"),
            'email' => fake()->email,
            'address' => fake()->address,
            'avatar' => fake()->imageUrl,
        ];
    }
}
