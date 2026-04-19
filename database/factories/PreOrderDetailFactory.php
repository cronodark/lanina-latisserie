<?php

namespace Database\Factories;

use App\Models\PreOrder;
use App\Models\Product;
use App\Models\Promo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PreOrderDetail>
 */
class PreOrderDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $isPromoItem = fake()->boolean();
        $productId = Product::query()->inRandomOrder()->value('id');
        $promoId = Promo::query()->inRandomOrder()->value('id');

        return [
            'quantity' => fake()->numberBetween(1, 10),
            'type' => $isPromoItem ? 'promo' : 'product',
            'product_id' => $isPromoItem ? null : $productId,
            'promo_id' => $isPromoItem ? ($promoId ?? Promo::factory()) : null,
            'pre_order_id' => PreOrder::factory(),
        ];
    }
}
