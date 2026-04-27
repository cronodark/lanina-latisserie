<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->sentence(1),
            'description' => fake()->paragraph(),
            'price' => fake()->numberBetween(10000, 100000),
            'production_estimate' => fake()->numberBetween(1, 5),
        ];
    }
}
