<?php

namespace Tests\Feature\Controllers;

use App\Models\PreOrder;
use App\Models\TanggalTersedia;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TanggalControllerTest extends TestCase
{
    use RefreshDatabase;

    private function createAdmin(): User
    {
        $admin = User::factory()->create();
        $this->actingAs($admin);
        return $admin;
    }

    public function test_kalender_requires_authentication(): void
    {
        $response = $this->get(route('jadwal-admin.kalender'));

        $response->assertRedirect(route('login'));
    }

    public function test_kalender_shows_current_month_by_default(): void
    {
        $this->createAdmin();

        $response = $this->get(route('jadwal-admin.kalender'));

        $response->assertStatus(200);
        $response->assertViewHas('bulan', now()->month);
        $response->assertViewHas('tahun', now()->year);
    }

    public function test_kalender_accepts_month_year_parameters(): void
    {
        $this->createAdmin();

        $response = $this->get(route('jadwal-admin.kalender', [
            'bulan' => 3,
            'tahun' => 2026,
        ]));

        $response->assertStatus(200);
        $response->assertViewHas('bulan', 3);
        $response->assertViewHas('tahun', 2026);
    }

    public function test_kalender_groups_orders_by_date(): void
    {
        $admin = $this->createAdmin();
        $user = User::factory()->create();

        // Create orders for different dates
        PreOrder::factory()->count(3)->create([
            'actual_periode' => '2026-05-10',
            'status' => 'processing',
            'user_id' => $user->id,
        ]);

        PreOrder::factory()->count(2)->create([
            'actual_periode' => '2026-05-15',
            'status' => 'paid',
            'user_id' => $user->id,
        ]);

        $response = $this->get(route('jadwal-admin.kalender', [
            'bulan' => 5,
            'tahun' => 2026,
        ]));

        $response->assertStatus(200);
        
        $pesananPerTanggal = $response->viewData('pesananPerTanggal');
        
        $this->assertCount(3, $pesananPerTanggal['2026-05-10']);
        $this->assertCount(2, $pesananPerTanggal['2026-05-15']);
    }

    public function test_kalender_includes_slot_data(): void
    {
        $this->createAdmin();

        TanggalTersedia::factory()->create([
            'tanggal' => '2026-05-20',
            'kuota' => 10,
        ]);

        TanggalTersedia::factory()->create([
            'tanggal' => '2026-05-25',
            'kuota' => 15,
        ]);

        $response = $this->get(route('jadwal-admin.kalender', [
            'bulan' => 5,
            'tahun' => 2026,
        ]));

        $response->assertStatus(200);
        
        $tanggalTersedia = $response->viewData('tanggalTersedia');
        
        $this->assertCount(2, $tanggalTersedia);
        $this->assertArrayHasKey('2026-05-20', $tanggalTersedia);
        $this->assertArrayHasKey('2026-05-25', $tanggalTersedia);
        
        $this->assertSame(10, $tanggalTersedia['2026-05-20']['kuota']);
        $this->assertSame(15, $tanggalTersedia['2026-05-25']['kuota']);
    }

    public function test_kalender_calculates_statistics_correctly(): void
    {
        $admin = $this->createAdmin();
        $user = User::factory()->create();

        // Create 5 orders
        PreOrder::factory()->count(5)->create([
            'actual_periode' => '2026-06-10',
            'status' => 'processing',
            'user_id' => $user->id,
        ]);

        // Create 3 active slots
        TanggalTersedia::factory()->count(3)->create([
            'tanggal' => now()->setMonth(6)->setYear(2026)->addDays(1),
            'is_aktif' => true,
            'kuota' => 10,
        ]);

        // Create 1 full slot
        $fullSlot = TanggalTersedia::factory()->create([
            'tanggal' => '2026-06-15',
            'kuota' => 2,
            'is_aktif' => true,
        ]);

        PreOrder::factory()->count(2)->create([
            'actual_periode' => '2026-06-15',
            'status' => 'unpaid',
            'user_id' => $user->id,
        ]);

        $response = $this->get(route('jadwal-admin.kalender', [
            'bulan' => 6,
            'tahun' => 2026,
        ]));

        $response->assertStatus(200);
        $response->assertViewHas('totalPesanan', 7); // 5 + 2
        $response->assertViewHas('slotTersedia', 4); // 3 + 1 (all active)
        $response->assertViewHas('slotPenuh', 1); // The full slot
    }

    public function test_kalender_identifies_busiest_date(): void
    {
        $admin = $this->createAdmin();
        $user = User::factory()->create();

        // Create orders for different dates
        PreOrder::factory()->count(1)->create([
            'actual_periode' => '2026-07-05',
            'status' => 'processing',
            'user_id' => $user->id,
        ]);

        PreOrder::factory()->count(5)->create([
            'actual_periode' => '2026-07-10',
            'status' => 'processing',
            'user_id' => $user->id,
        ]);

        PreOrder::factory()->count(2)->create([
            'actual_periode' => '2026-07-15',
            'status' => 'processing',
            'user_id' => $user->id,
        ]);

        $response = $this->get(route('jadwal-admin.kalender', [
            'bulan' => 7,
            'tahun' => 2026,
        ]));

        $response->assertStatus(200);
        $response->assertViewHas('tanggalRamaiFmt', '10 Jul');
    }

    public function test_kalender_filters_orders_by_month(): void
    {
        $admin = $this->createAdmin();
        $user = User::factory()->create();

        // Create orders in different months
        PreOrder::factory()->create([
            'actual_periode' => '2026-05-15',
            'status' => 'processing',
            'user_id' => $user->id,
        ]);

        PreOrder::factory()->count(3)->create([
            'actual_periode' => '2026-06-15',
            'status' => 'processing',
            'user_id' => $user->id,
        ]);

        $response = $this->get(route('jadwal-admin.kalender', [
            'bulan' => 6,
            'tahun' => 2026,
        ]));

        $response->assertStatus(200);
        
        $semuaPesanan = $response->viewData('semuaPesanan');
        
        // Should only show June orders
        $this->assertCount(3, $semuaPesanan);
    }

    public function test_kalender_includes_all_order_statuses(): void
    {
        $admin = $this->createAdmin();
        $user = User::factory()->create();

        // Create orders with different statuses
        PreOrder::factory()->create([
            'actual_periode' => '2026-08-10',
            'status' => 'unpaid',
            'user_id' => $user->id,
        ]);

        PreOrder::factory()->create([
            'actual_periode' => '2026-08-10',
            'status' => 'paid',
            'user_id' => $user->id,
        ]);

        PreOrder::factory()->create([
            'actual_periode' => '2026-08-10',
            'status' => 'processing',
            'user_id' => $user->id,
        ]);

        PreOrder::factory()->create([
            'actual_periode' => '2026-08-10',
            'status' => 'shipping',
            'user_id' => $user->id,
        ]);

        PreOrder::factory()->create([
            'actual_periode' => '2026-08-10',
            'status' => 'completed',
            'user_id' => $user->id,
        ]);

        // Cancelled should not be included
        PreOrder::factory()->create([
            'actual_periode' => '2026-08-10',
            'status' => 'cancelled',
            'user_id' => $user->id,
        ]);

        $response = $this->get(route('jadwal-admin.kalender', [
            'bulan' => 8,
            'tahun' => 2026,
        ]));

        $response->assertStatus(200);
        
        $semuaPesanan = $response->viewData('semuaPesanan');
        
        // Should include all except cancelled
        $this->assertCount(5, $semuaPesanan);
    }

    public function test_kalender_slot_data_includes_all_required_fields(): void
    {
        $admin = $this->createAdmin();
        $user = User::factory()->create();

        $slot = TanggalTersedia::factory()->create([
            'tanggal' => '2026-09-15',
            'kuota' => 10,
            'keterangan' => 'Test slot',
            'is_aktif' => true,
        ]);

        // Create some orders to test terisi/sisa
        PreOrder::factory()->count(3)->create([
            'actual_periode' => '2026-09-15',
            'status' => 'unpaid',
            'user_id' => $user->id,
        ]);

        $response = $this->get(route('jadwal-admin.kalender', [
            'bulan' => 9,
            'tahun' => 2026,
        ]));

        $response->assertStatus(200);
        
        $tanggalTersedia = $response->viewData('tanggalTersedia');
        $slotData = $tanggalTersedia['2026-09-15'];
        
        $this->assertArrayHasKey('id', $slotData);
        $this->assertArrayHasKey('kuota', $slotData);
        $this->assertArrayHasKey('terisi', $slotData);
        $this->assertArrayHasKey('sisa', $slotData);
        $this->assertArrayHasKey('status', $slotData);
        $this->assertArrayHasKey('is_aktif', $slotData);
        $this->assertArrayHasKey('keterangan', $slotData);
        
        $this->assertSame(10, $slotData['kuota']);
        $this->assertSame(3, $slotData['terisi']);
        $this->assertSame(7, $slotData['sisa']);
        $this->assertSame('Aktif', $slotData['status']);
        $this->assertTrue($slotData['is_aktif']);
    }
}
