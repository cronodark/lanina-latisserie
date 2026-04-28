<?php

namespace Tests\Feature\Controllers;

use App\Models\Address;
use App\Models\PreOrder;
use App\Models\PreOrderDetail;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LandingPageControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_landing_page_returns_expected_view_with_title_and_limited_products(): void
    {
        Product::factory()->count(7)->create();

        $response = $this->get(route('beranda'));

        $response->assertOk();
        $response->assertViewIs('pages.main.landing');
        $response->assertViewHas('title', 'Lanina Patisserie');
        $response->assertViewHas('products', function ($products) {
            return $products->count() === 5;
        });
    }

    public function test_landing_page_bestsellers_are_sorted_by_total_bought_and_skip_missing_product(): void
    {
        $products = Product::factory()->count(2)->create();
        $preOrder = $this->createOrder();

        PreOrderDetail::factory()->create([
            'pre_order_id' => $preOrder->id,
            'type' => 'product',
            'product_id' => $products[0]->id,
            'promo_id' => null,
            'quantity' => 2,
        ]);
        PreOrderDetail::factory()->create([
            'pre_order_id' => $preOrder->id,
            'type' => 'product',
            'product_id' => $products[0]->id,
            'promo_id' => null,
            'quantity' => 3,
        ]);
        PreOrderDetail::factory()->create([
            'pre_order_id' => $preOrder->id,
            'type' => 'product',
            'product_id' => $products[1]->id,
            'promo_id' => null,
            'quantity' => 1,
        ]);
        PreOrderDetail::factory()->create([
            'pre_order_id' => $preOrder->id,
            'type' => 'product',
            'product_id' => null,
            'promo_id' => null,
            'quantity' => 100,
        ]);

        $response = $this->get(route('beranda'));

        $response->assertOk();
        $response->assertViewHas('bestsellers', function ($bestsellers) use ($products) {
            if ($bestsellers->count() !== 2) {
                return false;
            }

            $first = $bestsellers->first();
            $second = $bestsellers->get(1);

            return (int) $first->product_id === $products[0]->id
                && (int) $first->total_bought === 5
                && (int) $second->product_id === $products[1]->id
                && (int) $second->total_bought === 1;
        });
    }

    private function createOrder(): PreOrder
    {
        $user = User::factory()->create();
        $address = Address::factory()->create(['user_id' => $user->id]);

        return PreOrder::factory()->create([
            'user_id' => $user->id,
            'address_id' => $address->id,
        ]);
    }
}
