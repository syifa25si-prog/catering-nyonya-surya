<?php

namespace Database\Factories;

use App\Models\Menu;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    public function definition(): array
    {
        return ['order_id' => Order::factory(), 'menu_id' => Menu::factory(), 'menu_name' => 'Paket nasi', 'price' => 35000, 'quantity' => 1, 'subtotal' => 35000];
    }
}
