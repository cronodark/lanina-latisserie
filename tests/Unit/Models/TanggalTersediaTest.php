<?php

namespace Tests\Unit\Models;

use App\Models\PreOrder;
use App\Models\TanggalTersedia;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TanggalTersediaTest extends TestCase
{
    use RefreshDatabase;

    public function test_terisi_counts_orders_with_all_relevant_statuses(): void
    {
        $slot = TanggalTersedia::factory()->create([
            'tanggal' => '2026-06-15',
            'kuota' => 10,
        ]);

        $user = User::factory()->create();

        // Create orders with different statuses
        PreOrder::factory()->create([
            'actual_periode' => '2026-06-15',
            'status' => 'unpaid',
            'user_id' => $user->id,
        ]);
        PreOrder::factory()->create([
            'actual_periode' => '2026-06-15',
            'status' => 'paid',
            'user_id' => $user->id,
        ]);
        PreOrder::factory()->create([
            'actual_periode' => '2026-06-15',
            'status' => 'processing',
            'user_id' => $user->id,
        ]);
        PreOrder::factory()->create([
            'actual_periode' => '2026-06-15',
            'status' => 'shipping',
            'user_id' => $user->id,
        ]);
        PreOrder::factory()->create([
            'actual_periode' => '2026-06-15',
            'status' => 'completed',
            'user_id' => $user->id,
        ]);

        // Create cancelled order (should not be counted)
        PreOrder::factory()->create([
            'actual_periode' => '2026-06-15',
            'status' => 'cancelled',
            'user_id' => $user->id,
        ]);

        $slot = $slot->fresh();

        $this->assertSame(5, $slot->terisi);
    }

    public function test_sisa_kuota_calculates_correctly(): void
    {
        $slot = TanggalTersedia::factory()->create([
            'tanggal' => '2026-06-20',
            'kuota' => 10,
        ]);

        $user = User::factory()->create();

        // Create 3 orders
        PreOrder::factory()->count(3)->create([
            'actual_periode' => '2026-06-20',
            'status' => 'unpaid',
            'user_id' => $user->id,
        ]);

        $slot = $slot->fresh();
        $this->assertSame(7, $slot->sisa_kuota);

        // Create 7 more orders
        PreOrder::factory()->count(7)->create([
            'actual_periode' => '2026-06-20',
            'status' => 'paid',
            'user_id' => $user->id,
        ]);

        $slot = $slot->fresh();
        $this->assertSame(0, $slot->sisa_kuota);
    }

    public function test_sisa_kuota_never_goes_negative(): void
    {
        $slot = TanggalTersedia::factory()->create([
            'tanggal' => '2026-06-25',
            'kuota' => 5,
        ]);

        $user = User::factory()->create();

        // Create 10 orders (more than kuota)
        PreOrder::factory()->count(10)->create([
            'actual_periode' => '2026-06-25',
            'status' => 'unpaid',
            'user_id' => $user->id,
        ]);

        $slot = $slot->fresh();
        $this->assertSame(0, $slot->sisa_kuota);
        $this->assertGreaterThanOrEqual(0, $slot->sisa_kuota);
    }

    public function test_status_returns_nonaktif_when_is_aktif_false(): void
    {
        $slot = TanggalTersedia::factory()->inactive()->create([
            'kuota' => 10,
        ]);

        $this->assertSame('Nonaktif', $slot->status);
    }

    public function test_status_returns_penuh_when_sisa_kuota_zero(): void
    {
        $slot = TanggalTersedia::factory()->create([
            'tanggal' => '2026-07-01',
            'kuota' => 5,
        ]);

        $user = User::factory()->create();

        // Fill all slots
        PreOrder::factory()->count(5)->create([
            'actual_periode' => '2026-07-01',
            'status' => 'unpaid',
            'user_id' => $user->id,
        ]);

        $slot = $slot->fresh();
        $this->assertSame('Penuh', $slot->status);
    }

    public function test_status_returns_aktif_when_available(): void
    {
        $slot = TanggalTersedia::factory()->create([
            'tanggal' => '2026-07-05',
            'kuota' => 10,
            'is_aktif' => true,
        ]);

        $user = User::factory()->create();

        // Create some orders but not full
        PreOrder::factory()->count(3)->create([
            'actual_periode' => '2026-07-05',
            'status' => 'unpaid',
            'user_id' => $user->id,
        ]);

        $slot = $slot->fresh();
        $this->assertSame('Aktif', $slot->status);
    }

    public function test_aktif_scope_filters_active_slots(): void
    {
        TanggalTersedia::factory()->count(2)->create(['is_aktif' => true]);
        TanggalTersedia::factory()->inactive()->create();

        $activeSlots = TanggalTersedia::aktif()->get();

        $this->assertCount(2, $activeSlots);
        $this->assertTrue($activeSlots->every(fn($slot) => $slot->is_aktif === true));
    }

    public function test_mendatang_scope_filters_future_dates(): void
    {
        $today = now()->startOfDay();

        // Past date
        TanggalTersedia::factory()->create([
            'tanggal' => $today->copy()->subDay(),
        ]);

        // Today
        TanggalTersedia::factory()->create([
            'tanggal' => $today->copy(),
        ]);

        // Future
        TanggalTersedia::factory()->create([
            'tanggal' => $today->copy()->addDay(),
        ]);

        $futureDates = TanggalTersedia::mendatang()->get();

        $this->assertCount(2, $futureDates); // today + future
    }

    public function test_pre_orders_relationship_works(): void
    {
        $slot = TanggalTersedia::factory()->create([
            'tanggal' => '2026-07-10',
        ]);

        $user = User::factory()->create();

        PreOrder::factory()->count(3)->create([
            'actual_periode' => '2026-07-10',
            'user_id' => $user->id,
        ]);

        // Create order for different date (should not be counted)
        PreOrder::factory()->create([
            'actual_periode' => '2026-07-11',
            'user_id' => $user->id,
        ]);

        $this->assertCount(3, $slot->preOrders);
    }

    public function test_tanggal_is_cast_to_date(): void
    {
        $slot = TanggalTersedia::factory()->create([
            'tanggal' => '2026-08-15',
        ]);

        $slot = $slot->fresh();

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $slot->tanggal);
        $this->assertSame('2026-08-15', $slot->tanggal->format('Y-m-d'));
    }

    public function test_is_aktif_is_cast_to_boolean(): void
    {
        $slot = TanggalTersedia::factory()->create([
            'is_aktif' => 1,
        ]);

        $slot = $slot->fresh();

        $this->assertIsBool($slot->is_aktif);
        $this->assertTrue($slot->is_aktif);
    }
}
