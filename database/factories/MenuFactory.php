<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class MenuFactory extends Factory
{
    public function definition(): array
    {
        return ['category_id' => Category::factory(), 'name' => fake()->words(3, true), 'description' => fake()->paragraph(), 'price' => 35000, 'is_available' => true];
    }
}
