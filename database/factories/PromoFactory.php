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
        $price = fake()->numberBetween(10000, 100000);
        $discountedPrice = fake()->numberBetween(5000, $price - 1);

        return [
            'name' => fake()->unique()->sentence(1),
            'description' => fake()->paragraph(),
            'price' => $discountedPrice,
            'date_until' => fake()->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            'status' => fake()->randomElement(['active', 'inactive']),
            'actual_price' => $price,
        ];
    }
}
