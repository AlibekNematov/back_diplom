<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'date' => fake()->dateTimeBetween('now', '+7 days')->format('Y-m-d'),
            'timeslot' => fake()->numberBetween(8, 23),
            'employee_id' => Employee::get()->random()->id,
            'service_id' => Service::get()->random()->id,
        ];
    }
}
