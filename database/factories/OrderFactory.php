<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        return ['user_id' => User::factory(), 'number' => 'NS-'.fake()->unique()->uuid(), 'customer_name' => fake()->name(), 'phone' => '081234567890', 'address' => fake()->address(), 'event_date' => now()->addDays(3)->toDateString(), 'total' => 35000, 'status' => 'Menunggu'];
    }
}
