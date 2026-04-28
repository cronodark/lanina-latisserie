<?php

namespace Tests\Feature\Livewire;

use App\Livewire\PreorderTabs;
use App\Models\Address;
use App\Models\PreOrder;
use App\Models\PreOrderDetail;
use App\Models\Product;
use App\Models\Promo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PreorderTabsTest extends TestCase
{
    use RefreshDatabase;

    private function createUser(): User
    {
        /** @var User $user */
        $user = User::factory()->create();

        return $user;
    }

    public function test_invalid_tab_is_sanitized_to_default(): void
    {
        $user = $this->createUser();
        $this->actingAs($user);

        $component = Livewire::test(PreorderTabs::class)
            ->call('setTab', 'tab-tidak-valid');

        $this->assertSame('belum-bayar', $component->instance()->tab);
    }

    public function test_orders_property_filters_processing_orders_for_current_user(): void
    {
        $user = $this->createUser();
        $otherUser = $this->createUser();

        $this->createOrder($user, ['status' => 'processing', 'payment_status' => 'paid']);
        $this->createOrder($user, ['status' => 'pending', 'payment_status' => 'unpaid']);
        $this->createOrder($otherUser, ['status' => 'processing', 'payment_status' => 'paid']);

        $this->actingAs($user);

        $component = Livewire::test(PreorderTabs::class)
            ->call('setTab', 'diproses');

        $orders = $component->instance()->orders;

        $this->assertCount(1, $orders);
        $this->assertSame('processing', $orders->first()->status);
        $this->assertSame($user->id, $orders->first()->user_id);
    }

    public function test_summary_counts_are_grouped_by_business_status_rules(): void
    {
        $user = $this->createUser();

        $this->createOrder($user, ['status' => 'pending', 'payment_status' => 'unpaid']);
        $this->createOrder($user, ['status' => 'processing', 'payment_status' => 'paid']);
        $this->createOrder($user, ['status' => 'ready_pickup', 'payment_status' => 'paid']);
        $this->createOrder($user, ['status' => 'completed', 'payment_status' => 'paid']);

        $this->actingAs($user);

        $summary = Livewire::test(PreorderTabs::class)->instance()->summary;

        $this->assertSame([
            'belum-bayar' => 1,
            'diproses' => 1,
            'diantar' => 1,
            'selesai' => 1,
        ], $summary);
    }

    public function test_cancel_only_updates_unpaid_order(): void
    {
        $user = $this->createUser();
        $unpaidOrder = $this->createOrder($user, ['status' => 'pending', 'payment_status' => 'unpaid']);
        $paidOrder = $this->createOrder($user, ['status' => 'processing', 'payment_status' => 'paid']);

        $this->actingAs($user);

        Livewire::test(PreorderTabs::class)->call('cancel', $unpaidOrder->id);
        Livewire::test(PreorderTabs::class)->call('cancel', $paidOrder->id);

        $this->assertDatabaseHas('pre_orders', [
            'id' => $unpaidOrder->id,
            'status' => 'cancelled',
            'payment_status' => 'cancelled',
        ]);

        $this->assertDatabaseHas('pre_orders', [
            'id' => $paidOrder->id,
            'status' => 'processing',
            'payment_status' => 'paid',
        ]);
    }

    public function test_confirm_received_marks_order_completed_and_moves_tab(): void
    {
        $user = $this->createUser();
        $order = $this->createOrder($user, ['status' => 'shipping', 'payment_status' => 'paid']);

        $this->actingAs($user);

        $component = Livewire::test(PreorderTabs::class)
            ->call('confirmReceived', $order->id);

        $this->assertDatabaseHas('pre_orders', [
            'id' => $order->id,
            'status' => 'completed',
        ]);
        $this->assertSame('selesai', $component->instance()->tab);
    }

    public function test_order_helper_methods_generate_expected_title_description_total_and_qty(): void
    {
        $user = $this->createUser();
        $order = $this->createOrder($user, ['status' => 'processing', 'payment_status' => 'paid']);

        $products = Product::factory()->count(3)->create();
        $promo = Promo::factory()->create(['price' => 30000, 'actual_price' => 40000]);

        PreOrderDetail::factory()->create([
            'pre_order_id' => $order->id,
            'type' => 'product',
            'product_id' => $products[0]->id,
            'promo_id' => null,
            'quantity' => 2,
        ]);
        PreOrderDetail::factory()->create([
            'pre_order_id' => $order->id,
            'type' => 'promo',
            'promo_id' => $promo->id,
            'product_id' => null,
            'quantity' => 1,
        ]);
        PreOrderDetail::factory()->create([
            'pre_order_id' => $order->id,
            'type' => 'product',
            'product_id' => $products[1]->id,
            'promo_id' => null,
            'quantity' => 1,
        ]);
        PreOrderDetail::factory()->create([
            'pre_order_id' => $order->id,
            'type' => 'product',
            'product_id' => $products[2]->id,
            'promo_id' => null,
            'quantity' => 1,
        ]);

        $order->load('detailPreOrders.product', 'detailPreOrders.promo');

        $this->actingAs($user);

        $component = Livewire::test(PreorderTabs::class);

        $this->assertSame($products[0]->name, $component->instance()->orderTitle($order));
        $this->assertStringContainsString('dan lainnya', $component->instance()->orderDescription($order));
        $this->assertSame(5, $component->instance()->orderQty($order));

        $expectedTotal = ($products[0]->price * 2) + $promo->price + $products[1]->price + $products[2]->price;
        $this->assertSame($expectedTotal, $component->instance()->orderTotal($order));
        $this->assertStringContainsString('images.unsplash.com', $component->instance()->orderImage($order));
    }

    public function test_pay_redirects_when_payment_url_exists_and_returns_null_otherwise(): void
    {
        $user = $this->createUser();
        $orderWithUrl = $this->createOrder($user, [
            'payment_redirect_url' => 'https://payment.example/abc',
        ]);
        $orderWithoutUrl = $this->createOrder($user, [
            'payment_redirect_url' => null,
        ]);

        $this->actingAs($user);

        Livewire::test(PreorderTabs::class)
            ->call('pay', $orderWithUrl->id)
            ->assertRedirect('https://payment.example/abc');

        $result = Livewire::test(PreorderTabs::class)->instance()->pay($orderWithoutUrl->id);
        $this->assertNull($result);
    }

    private function createOrder(User $user, array $overrides = []): PreOrder
    {
        $address = Address::factory()->create(['user_id' => $user->id]);

        return PreOrder::factory()->create(array_merge([
            'user_id' => $user->id,
            'address_id' => $address->id,
        ], $overrides));
    }
}
