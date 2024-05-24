<?php

namespace Database\Factories;

use App\Models\Club;
use App\Models\Subscription;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
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
            'subscription_id' => Subscription::get()->random()->id,
            'name' => fake()->firstName,
            'surname' => fake()->lastName,
            'patronymic' => fake()->name,
            'phone_number' => fake()->numerify("###########"),
            'address' => fake()->address,
            'birth_date'=> fake()->date,
            'email' => fake()->email,
            'accounting_number'=> fake()->numerify("###############")
        ];
    }
}
