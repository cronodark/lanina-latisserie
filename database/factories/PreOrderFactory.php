<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PreOrder>
 */
class PreOrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'actual_periode' => fake()->date(),
            'status' => fake()->randomElement(['pending', 'processing', 'cancelled', 'delivered','finished']),
            'start_periode' => fake()->date(),
            'end_periode' => fake()->date(),
            'send_type' => fake()->randomElement(['kurirEkspedisi', 'pickUp', 'kurirToko']),
            'tracking_number' => fake()->uuid(),
            'choosen_expedition' => fake()->randomElement(['jne', 'pos', 'tiki']),
            'user_id' => User::factory(),
            'address_id' => Address::factory(),
        ];
    }
}
