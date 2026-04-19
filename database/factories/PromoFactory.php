<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Promo>
 */
class PromoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->sentence(1),
            'description' => fake()->paragraph(),
            'price' => fake()->numberBetween(10000, 100000),
            'date_until' => fake()->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            'status' => fake()->randomElement(['active', 'inactive']),
        ];
    }
}
