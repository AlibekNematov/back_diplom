<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\ClientService;
use App\Models\Club;
use App\Models\Employee;
use App\Models\EmployeeService;
use App\Models\Service;
use App\Models\Subscription;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        Club::factory()->count(10)->create();
        Subscription::factory()->count(3)->create();
        Client::factory()->count(30)->create();
        Employee::factory()->count(10)->create();
        Service::factory()->count(40)->create();
        ClientService::factory()->count(100)->create();
        EmployeeService::factory()->count(200)->create();
    }
}
