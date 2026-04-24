<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Promo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class PromoDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $products = Product::pluck('id')->toArray();
        $promos = Promo::pluck('id')->toArray();
        return [
            'promo_id' => fake()->randomElement($promos),
            'product_id' => fake()->randomElement($products),
        ];
    }
}
