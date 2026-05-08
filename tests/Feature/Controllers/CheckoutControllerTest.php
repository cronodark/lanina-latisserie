<?php

namespace Tests\Feature\Controllers;

use App\Models\Address;
use App\Models\PreOrder;
use App\Models\PreOrderDetail;
use App\Models\Product;
use App\Models\Promo;
use App\Models\TanggalTersedia;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutControllerTest extends TestCase
{
    use RefreshDatabase;

    private function createAuthenticatedUser(array $attributes = []): User
    {
        $user = User::factory()->create($attributes);
        $this->actingAs($user);
        return $user;
    }

    private function addToCart(User $user, array $items): void
    {
        session()->put('cart_user_' . $user->id, $items);
    }

    public function test_checkout_index_requires_authentication(): void
    {
        $response = $this->get(route('checkout.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_checkout_index_redirects_when_no_items_checked(): void
    {
        $user = $this->createAuthenticatedUser();

        $response = $this->get(route('checkout.index'));

        $response->assertRedirect(route('cart.index'));
        $response->assertSessionHas('error', 'Pilih minimal 1 produk untuk checkout.');
    }

    public function test_checkout_index_shows_cart_items(): void
    {
        $user = $this->createAuthenticatedUser();
        $product = Product::factory()->create(['price' => 50000]);

        $this->addToCart($user, [
            'product:' . $product->id => [
                'type' => 'product',
                'item_id' => $product->id,
                'qty' => 2,
                'checked' => true,
            ],
        ]);

        $response = $this->get(route('checkout.index'));

        $response->assertStatus(200);
        $response->assertViewHas('items');
        $response->assertViewHas('grandTotal', 100000);
    }

    public function test_checkout_store_requires_authentication(): void
    {
        $response = $this->post(route('checkout.store'), []);

        $response->assertRedirect(route('login'));
    }

    public function test_checkout_store_validates_actual_periode_required(): void
    {
        $user = $this->createAuthenticatedUser();
        $product = Product::factory()->create();

        $this->addToCart($user, [
            'product:' . $product->id => [
                'type' => 'product',
                'item_id' => $product->id,
                'qty' => 1,
                'checked' => true,
            ],
        ]);

        $response = $this->post(route('checkout.store'), [
            'send_type' => 'pickUp',
        ]);

        $response->assertSessionHasErrors('actual_periode');
    }

    public function test_checkout_store_validates_send_type_required(): void
    {
        $user = $this->createAuthenticatedUser();
        $product = Product::factory()->create();
        $slot = TanggalTersedia::factory()->create();

        $this->addToCart($user, [
            'product:' . $product->id => [
                'type' => 'product',
                'item_id' => $product->id,
                'qty' => 1,
                'checked' => true,
            ],
        ]);

        $response = $this->post(route('checkout.store'), [
            'actual_periode' => $slot->tanggal->format('Y-m-d'),
        ]);

        $response->assertSessionHasErrors('send_type');
    }

    public function test_checkout_store_rejects_nonexistent_date(): void
    {
        $user = $this->createAuthenticatedUser();
        $product = Product::factory()->create();

        $this->addToCart($user, [
            'product:' . $product->id => [
                'type' => 'product',
                'item_id' => $product->id,
                'qty' => 1,
                'checked' => true,
            ],
        ]);

        $response = $this->post(route('checkout.store'), [
            'send_type' => 'pickUp',
            'actual_periode' => '2026-12-25', // Date not in tanggal_tersedia
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Tanggal yang dipilih tidak tersedia untuk pre-order.');
    }

    public function test_checkout_store_rejects_inactive_slot(): void
    {
        $user = $this->createAuthenticatedUser();
        $product = Product::factory()->create();
        $slot = TanggalTersedia::factory()->inactive()->create([
            'tanggal' => '2026-06-20',
        ]);

        $this->addToCart($user, [
            'product:' . $product->id => [
                'type' => 'product',
                'item_id' => $product->id,
                'qty' => 1,
                'checked' => true,
            ],
        ]);

        $response = $this->post(route('checkout.store'), [
            'send_type' => 'pickUp',
            'actual_periode' => '2026-06-20',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Tanggal yang dipilih sedang tidak aktif.');
    }

    public function test_checkout_store_rejects_full_slot(): void
    {
        $user = $this->createAuthenticatedUser();
        $product = Product::factory()->create();
        $slot = TanggalTersedia::factory()->create([
            'tanggal' => '2026-06-25',
            'kuota' => 2,
        ]);

        // Fill the slot
        PreOrder::factory()->count(2)->create([
            'actual_periode' => '2026-06-25',
            'status' => 'unpaid',
            'user_id' => $user->id,
        ]);

        $this->addToCart($user, [
            'product:' . $product->id => [
                'type' => 'product',
                'item_id' => $product->id,
                'qty' => 1,
                'checked' => true,
            ],
        ]);

        $response = $this->post(route('checkout.store'), [
            'send_type' => 'pickUp',
            'actual_periode' => '2026-06-25',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Slot untuk tanggal ini sudah penuh. Silakan pilih tanggal lain.');
    }

    public function test_checkout_store_creates_order_with_unpaid_status(): void
    {
        $user = $this->createAuthenticatedUser();
        $product = Product::factory()->create(['price' => 100000]);
        $slot = TanggalTersedia::factory()->create([
            'tanggal' => '2026-07-01',
            'kuota' => 10,
        ]);

        $this->addToCart($user, [
            'product:' . $product->id => [
                'type' => 'product',
                'item_id' => $product->id,
                'qty' => 1,
                'checked' => true,
            ],
        ]);

        // Mock Midtrans to avoid actual API call
        $this->mock(\Midtrans\Snap::class, function ($mock) {
            $mock->shouldReceive('createTransaction')
                ->andReturn((object) ['redirect_url' => 'https://payment.test/abc']);
        });

        $response = $this->post(route('checkout.store'), [
            'send_type' => 'pickUp',
            'actual_periode' => '2026-07-01',
        ]);

        $this->assertDatabaseHas('pre_orders', [
            'user_id' => $user->id,
            'actual_periode' => '2026-07-01',
            'status' => 'unpaid',
            'total' => 100000,
        ]);
    }

    public function test_checkout_store_locks_slot_immediately(): void
    {
        $user = $this->createAuthenticatedUser();
        $product = Product::factory()->create(['price' => 50000]);
        $slot = TanggalTersedia::factory()->create([
            'tanggal' => '2026-07-05',
            'kuota' => 5,
        ]);

        $this->addToCart($user, [
            'product:' . $product->id => [
                'type' => 'product',
                'item_id' => $product->id,
                'qty' => 1,
                'checked' => true,
            ],
        ]);

        // Mock Midtrans
        $this->mock(\Midtrans\Snap::class, function ($mock) {
            $mock->shouldReceive('createTransaction')
                ->andReturn((object) ['redirect_url' => 'https://payment.test/abc']);
        });

        $this->post(route('checkout.store'), [
            'send_type' => 'pickUp',
            'actual_periode' => '2026-07-05',
        ]);

        $slot = $slot->fresh();
        $this->assertSame(1, $slot->terisi);
        $this->assertSame(4, $slot->sisa_kuota);
    }

    public function test_checkout_store_creates_order_details(): void
    {
        $user = $this->createAuthenticatedUser();
        $product1 = Product::factory()->create(['price' => 30000]);
        $product2 = Product::factory()->create(['price' => 40000]);
        $promo = Promo::factory()->create(['price' => 50000]);
        $slot = TanggalTersedia::factory()->create([
            'tanggal' => '2026-07-10',
        ]);

        $this->addToCart($user, [
            'product:' . $product1->id => [
                'type' => 'product',
                'item_id' => $product1->id,
                'qty' => 2,
                'checked' => true,
            ],
            'product:' . $product2->id => [
                'type' => 'product',
                'item_id' => $product2->id,
                'qty' => 1,
                'checked' => true,
            ],
            'promo:' . $promo->id => [
                'type' => 'promo',
                'item_id' => $promo->id,
                'qty' => 1,
                'checked' => true,
            ],
        ]);

        // Mock Midtrans
        $this->mock(\Midtrans\Snap::class, function ($mock) {
            $mock->shouldReceive('createTransaction')
                ->andReturn((object) ['redirect_url' => 'https://payment.test/abc']);
        });

        $this->post(route('checkout.store'), [
            'send_type' => 'pickUp',
            'actual_periode' => '2026-07-10',
        ]);

        $order = PreOrder::where('user_id', $user->id)->first();

        $this->assertDatabaseCount('pre_order_details', 3);
        $this->assertDatabaseHas('pre_order_details', [
            'pre_order_id' => $order->id,
            'type' => 'product',
            'product_id' => $product1->id,
            'quantity' => 2,
        ]);
        $this->assertDatabaseHas('pre_order_details', [
            'pre_order_id' => $order->id,
            'type' => 'product',
            'product_id' => $product2->id,
            'quantity' => 1,
        ]);
        $this->assertDatabaseHas('pre_order_details', [
            'pre_order_id' => $order->id,
            'type' => 'promo',
            'promo_id' => $promo->id,
            'quantity' => 1,
        ]);
    }

    public function test_checkout_store_validates_address_belongs_to_user(): void
    {
        $user = $this->createAuthenticatedUser();
        $otherUser = User::factory()->create();
        $otherAddress = Address::factory()->create(['user_id' => $otherUser->id]);
        $product = Product::factory()->create();
        $slot = TanggalTersedia::factory()->create();

        $this->addToCart($user, [
            'product:' . $product->id => [
                'type' => 'product',
                'item_id' => $product->id,
                'qty' => 1,
                'checked' => true,
            ],
        ]);

        $response = $this->post(route('checkout.store'), [
            'send_type' => 'pickUp',
            'actual_periode' => $slot->tanggal->format('Y-m-d'),
            'address_id' => $otherAddress->id,
        ]);

        $response->assertSessionHasErrors('address_id');
    }

    public function test_checkout_store_clears_checked_items_from_cart(): void
    {
        $user = $this->createAuthenticatedUser();
        $product1 = Product::factory()->create();
        $product2 = Product::factory()->create();
        $slot = TanggalTersedia::factory()->create();

        $this->addToCart($user, [
            'product:' . $product1->id => [
                'type' => 'product',
                'item_id' => $product1->id,
                'qty' => 1,
                'checked' => true,
            ],
            'product:' . $product2->id => [
                'type' => 'product',
                'item_id' => $product2->id,
                'qty' => 1,
                'checked' => false, // Not checked
            ],
        ]);

        // Mock Midtrans
        $this->mock(\Midtrans\Snap::class, function ($mock) {
            $mock->shouldReceive('createTransaction')
                ->andReturn((object) ['redirect_url' => 'https://payment.test/abc']);
        });

        $this->post(route('checkout.store'), [
            'send_type' => 'pickUp',
            'actual_periode' => $slot->tanggal->format('Y-m-d'),
        ]);

        $cart = session('cart_user_' . $user->id);
        
        // Checked item should be removed
        $this->assertArrayNotHasKey('product:' . $product1->id, $cart);
        
        // Unchecked item should remain
        $this->assertArrayHasKey('product:' . $product2->id, $cart);
    }
}
