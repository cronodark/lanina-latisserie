<?php

namespace Tests\Feature\Livewire;

use App\Livewire\Cart;
use App\Models\Product;
use App\Models\Promo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    private function createUser(): User
    {
        /** @var User $user */
        $user = User::factory()->create();

        return $user;
    }

    public function test_mount_normalizes_legacy_numeric_session_cart_keys(): void
    {
        $user = $this->createUser();
        $product = Product::factory()->create();

        $this->actingAs($user);

        session()->put('cart_user_'.$user->id, [
            (string) $product->id => [
                'qty' => 2,
                'checked' => true,
            ],
        ]);

        $component = Livewire::test(Cart::class);

        $this->assertArrayHasKey('product:'.$product->id, $component->instance()->cart);
        $this->assertSame(['product:'.$product->id], $component->instance()->selected);
    }

    public function test_add_item_increments_quantity_when_same_product_added_twice(): void
    {
        $user = $this->createUser();
        $product = Product::factory()->create();

        $this->actingAs($user);

        $component = Livewire::test(Cart::class)
            ->call('addItem', $product->id)
            ->call('addItem', $product->id);

        $this->assertSame(2, $component->instance()->cart['product:'.$product->id]['qty']);
        $this->assertSame(2, session('cart_user_'.$user->id)['product:'.$product->id]['qty']);
    }

    public function test_toggle_item_affects_total_and_checked_status(): void
    {
        $user = $this->createUser();
        $product = Product::factory()->create(['price' => 20000]);

        $this->actingAs($user);

        $component = Livewire::test(Cart::class)
            ->call('addItem', $product->id)
            ->call('addItem', $product->id)
            ->call('toggleItem', 'product:'.$product->id);

        $this->assertTrue($component->instance()->cart['product:'.$product->id]['checked']);
        $this->assertSame(40000, $component->instance()->total);
        $this->assertTrue($component->instance()->allChecked);
    }

    public function test_decrement_removes_item_when_quantity_reaches_zero(): void
    {
        $user = $this->createUser();
        $product = Product::factory()->create();

        $this->actingAs($user);

        $component = Livewire::test(Cart::class)
            ->call('addItem', $product->id)
            ->call('toggleItem', 'product:'.$product->id)
            ->call('decrement', 'product:'.$product->id);

        $this->assertArrayNotHasKey('product:'.$product->id, $component->instance()->cart);
        $this->assertSame([], $component->instance()->selected);
    }

    public function test_checkout_selected_redirects_to_checkout_route_when_total_is_positive(): void
    {
        $user = $this->createUser();
        $product = Product::factory()->create(['price' => 15000]);
        $promo = Promo::factory()->create(['price' => 5000, 'actual_price' => 10000]);

        $this->actingAs($user);

        session()->put('cart_user_'.$user->id, [
            'product:'.$product->id => [
                'type' => 'product',
                'item_id' => $product->id,
                'qty' => 1,
                'checked' => true,
            ],
            'promo:'.$promo->id => [
                'type' => 'promo',
                'item_id' => $promo->id,
                'qty' => 1,
                'checked' => false,
            ],
        ]);

        Livewire::test(Cart::class)
            ->call('checkoutSelected')
            ->assertRedirect(route('checkout.index'));
    }
}
