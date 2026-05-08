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
            'status' => fake()->randomElement(['unpaid', 'processing', 'cancelled', 'shipping', 'completed']),
            'payment_method' => fake()->randomElement(['midtrans', 'transfer', 'cash']),
            'midtrans_order_id' => fake()->optional()->bothify('PO-####-####'),
            'midtrans_transaction_id' => fake()->optional()->uuid(),
            'payment_redirect_url' => fake()->optional()->url(),
            'paid_at' => fake()->optional()->dateTimeBetween('-7 days', 'now'),
            'start_periode' => fake()->date(),
            'end_periode' => fake()->date(),
            'send_type' => fake()->randomElement(['kurirEkspedisi', 'pickUp', 'kurirToko']),
            'tracking_number' => fake()->uuid(),
            'choosen_expedition' => fake()->randomElement(['jne', 'pos', 'tiki']),
            'user_id' => User::factory(),
            'address_id' => Address::factory(),
            'total' => fake()->numberBetween(10000, 1000000)
        ];
    }
}
