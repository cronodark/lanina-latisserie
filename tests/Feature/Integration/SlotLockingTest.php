<?php

namespace Tests\Feature\Integration;

use App\Models\PreOrder;
use App\Models\Product;
use App\Models\TanggalTersedia;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SlotLockingTest extends TestCase
{
    use RefreshDatabase;

    private function createAuthenticatedUser(): User
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        return $user;
    }

    private function addToCart(User $user, Product $product, int $qty = 1): void
    {
        session()->put('cart_user_' . $user->id, [
            'product:' . $product->id => [
                'type' => 'product',
                'item_id' => $product->id,
                'qty' => $qty,
                'checked' => true,
            ],
        ]);
    }

    public function test_unpaid_orders_lock_slots(): void
    {
        $user = User::factory()->create();
        
        $slot = TanggalTersedia::factory()->create([
            'tanggal' => '2026-08-10',
            'kuota' => 5,
        ]);

        // Create 2 unpaid orders
        PreOrder::factory()->count(2)->create([
            'actual_periode' => '2026-08-10',
            'status' => 'unpaid',
            'user_id' => $user->id,
        ]);

        $slot = $slot->fresh();

        $this->assertSame(2, $slot->terisi);
        $this->assertSame(3, $slot->sisa_kuota);
        $this->assertSame('Aktif', $slot->status);
    }

    public function test_cancelled_orders_do_not_count_toward_terisi(): void
    {
        $user = User::factory()->create();
        
        $slot = TanggalTersedia::factory()->create([
            'tanggal' => '2026-08-15',
            'kuota' => 5,
        ]);

        // Create 2 orders
        $order1 = PreOrder::factory()->create([
            'actual_periode' => '2026-08-15',
            'status' => 'unpaid',
            'user_id' => $user->id,
        ]);

        $order2 = PreOrder::factory()->create([
            'actual_periode' => '2026-08-15',
            'status' => 'paid',
            'user_id' => $user->id,
        ]);

        $slot = $slot->fresh();
        $this->assertSame(2, $slot->terisi);

        // Cancel one order
        $order1->update(['status' => 'cancelled']);

        $slot = $slot->fresh();
        $this->assertSame(1, $slot->terisi);
        $this->assertSame(4, $slot->sisa_kuota);
    }

    public function test_slot_status_updates_dynamically(): void
    {
        $user = User::factory()->create();
        
        $slot = TanggalTersedia::factory()->create([
            'tanggal' => '2026-08-20',
            'kuota' => 2,
            'is_aktif' => true,
        ]);

        // Initially should be Aktif
        $this->assertSame('Aktif', $slot->status);

        // Create 2 orders to fill the slot
        PreOrder::factory()->count(2)->create([
            'actual_periode' => '2026-08-20',
            'status' => 'unpaid',
            'user_id' => $user->id,
        ]);

        $slot = $slot->fresh();
        
        // Should now be Penuh
        $this->assertSame('Penuh', $slot->status);
        $this->assertSame(0, $slot->sisa_kuota);
    }

    public function test_slot_locking_prevents_overbooking(): void
    {
        $user = $this->createAuthenticatedUser();
        $product = Product::factory()->create(['price' => 50000]);
        
        $slot = TanggalTersedia::factory()->create([
            'tanggal' => '2026-08-25',
            'kuota' => 1,
        ]);

        $this->addToCart($user, $product);

        // Mock Midtrans
        $this->mock(\Midtrans\Snap::class, function ($mock) {
            $mock->shouldReceive('createTransaction')
                ->andReturn((object) ['redirect_url' => 'https://payment.test/abc']);
        });

        // First checkout should succeed
        $response1 = $this->post(route('checkout.store'), [
            'send_type' => 'pickUp',
            'actual_periode' => '2026-08-25',
        ]);

        $response1->assertRedirect();
        $this->assertDatabaseCount('pre_orders', 1);

        // Second checkout should fail (slot full)
        $user2 = User::factory()->create();
        $this->actingAs($user2);
        $this->addToCart($user2, $product);

        $response2 = $this->post(route('checkout.store'), [
            'send_type' => 'pickUp',
            'actual_periode' => '2026-08-25',
        ]);

        $response2->assertRedirect();
        $response2->assertSessionHas('error', 'Slot untuk tanggal ini sudah penuh. Silakan pilih tanggal lain.');
        
        // Should still only have 1 order
        $this->assertDatabaseCount('pre_orders', 1);
    }

    public function test_transaction_rollback_on_slot_full(): void
    {
        $user = $this->createAuthenticatedUser();
        $product = Product::factory()->create(['price' => 50000]);
        
        $slot = TanggalTersedia::factory()->create([
            'tanggal' => '2026-09-01',
            'kuota' => 1,
        ]);

        // Fill the slot
        PreOrder::factory()->create([
            'actual_periode' => '2026-09-01',
            'status' => 'unpaid',
            'user_id' => $user->id,
        ]);

        $this->addToCart($user, $product);

        // Try to checkout (should fail)
        $response = $this->post(route('checkout.store'), [
            'send_type' => 'pickUp',
            'actual_periode' => '2026-09-01',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');

        // Should still only have 1 order (transaction rolled back)
        $this->assertDatabaseCount('pre_orders', 1);
        
        // Slot should still show 1 terisi
        $slot = $slot->fresh();
        $this->assertSame(1, $slot->terisi);
    }

    public function test_all_order_statuses_count_toward_terisi(): void
    {
        $user = User::factory()->create();
        
        $slot = TanggalTersedia::factory()->create([
            'tanggal' => '2026-09-05',
            'kuota' => 10,
        ]);

        // Create orders with different statuses
        PreOrder::factory()->create([
            'actual_periode' => '2026-09-05',
            'status' => 'unpaid',
            'user_id' => $user->id,
        ]);

        PreOrder::factory()->create([
            'actual_periode' => '2026-09-05',
            'status' => 'paid',
            'user_id' => $user->id,
        ]);

        PreOrder::factory()->create([
            'actual_periode' => '2026-09-05',
            'status' => 'processing',
            'user_id' => $user->id,
        ]);

        PreOrder::factory()->create([
            'actual_periode' => '2026-09-05',
            'status' => 'shipping',
            'user_id' => $user->id,
        ]);

        PreOrder::factory()->create([
            'actual_periode' => '2026-09-05',
            'status' => 'completed',
            'user_id' => $user->id,
        ]);

        // Cancelled should not count
        PreOrder::factory()->create([
            'actual_periode' => '2026-09-05',
            'status' => 'cancelled',
            'user_id' => $user->id,
        ]);

        $slot = $slot->fresh();

        $this->assertSame(5, $slot->terisi);
        $this->assertSame(5, $slot->sisa_kuota);
    }

    public function test_lock_for_update_prevents_race_condition(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $product = Product::factory()->create(['price' => 50000]);
        
        $slot = TanggalTersedia::factory()->create([
            'tanggal' => '2026-09-10',
            'kuota' => 1,
        ]);

        // Simulate concurrent access by using database transactions
        $success1 = false;
        $success2 = false;

        try {
            DB::transaction(function () use ($slot, $user1, $product, &$success1) {
                $tanggalTersedia = TanggalTersedia::where('tanggal', $slot->tanggal)
                    ->lockForUpdate()
                    ->first();

                if ($tanggalTersedia->sisa_kuota > 0) {
                    PreOrder::create([
                        'actual_periode' => $slot->tanggal,
                        'status' => 'unpaid',
                        'user_id' => $user1->id,
                        'total' => 50000,
                        'send_type' => 'pickUp',
                        'start_periode' => now(),
                    ]);
                    $success1 = true;
                }
            });
        } catch (\Exception $e) {
            // Transaction failed
        }

        try {
            DB::transaction(function () use ($slot, $user2, $product, &$success2) {
                $tanggalTersedia = TanggalTersedia::where('tanggal', $slot->tanggal)
                    ->lockForUpdate()
                    ->first();

                if ($tanggalTersedia->sisa_kuota > 0) {
                    PreOrder::create([
                        'actual_periode' => $slot->tanggal,
                        'status' => 'unpaid',
                        'user_id' => $user2->id,
                        'total' => 50000,
                        'send_type' => 'pickUp',
                        'start_periode' => now(),
                    ]);
                    $success2 = true;
                }
            });
        } catch (\Exception $e) {
            // Transaction failed
        }

        // Only one should succeed
        $this->assertTrue($success1 XOR $success2);
        $this->assertDatabaseCount('pre_orders', 1);
        
        $slot = $slot->fresh();
        $this->assertSame(1, $slot->terisi);
        $this->assertSame(0, $slot->sisa_kuota);
    }

    public function test_inactive_slot_cannot_be_booked(): void
    {
        $user = $this->createAuthenticatedUser();
        $product = Product::factory()->create();
        
        $slot = TanggalTersedia::factory()->inactive()->create([
            'tanggal' => '2026-09-15',
            'kuota' => 10,
        ]);

        $this->addToCart($user, $product);

        $response = $this->post(route('checkout.store'), [
            'send_type' => 'pickUp',
            'actual_periode' => '2026-09-15',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Tanggal yang dipilih sedang tidak aktif.');
        
        $this->assertDatabaseCount('pre_orders', 0);
    }
}
