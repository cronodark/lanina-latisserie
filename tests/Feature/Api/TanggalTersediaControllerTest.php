<?php

namespace Tests\Feature\Api;

use App\Models\PreOrder;
use App\Models\TanggalTersedia;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TanggalTersediaControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_available_dates(): void
    {
        $user = User::factory()->create();

        // Create active slots with available quota
        TanggalTersedia::factory()->count(3)->create([
            'is_aktif' => true,
            'kuota' => 10,
        ]);

        // Create inactive slot (should not be returned)
        TanggalTersedia::factory()->inactive()->create([
            'kuota' => 10,
        ]);

        // Create full slot (should not be returned)
        $fullSlot = TanggalTersedia::factory()->create([
            'tanggal' => now()->addDays(10),
            'kuota' => 2,
            'is_aktif' => true,
        ]);

        PreOrder::factory()->count(2)->create([
            'actual_periode' => $fullSlot->tanggal,
            'status' => 'unpaid',
            'user_id' => $user->id,
        ]);

        $response = $this->getJson('/api/tanggal-tersedia');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                '*' => [
                    'id',
                    'tanggal',
                    'tanggal_display',
                    'kuota',
                    'terisi',
                    'sisa',
                    'status',
                    'is_available',
                    'keterangan',
                ],
            ],
        ]);

        $data = $response->json('data');
        
        // Should only return 3 available slots (not inactive or full)
        $this->assertCount(3, $data);
        
        // All returned slots should be available
        foreach ($data as $slot) {
            $this->assertTrue($slot['is_available']);
            $this->assertGreaterThan(0, $slot['sisa']);
        }
    }

    public function test_index_response_format_is_correct(): void
    {
        $slot = TanggalTersedia::factory()->create([
            'tanggal' => '2026-06-15',
            'kuota' => 10,
            'keterangan' => 'Test slot',
            'is_aktif' => true,
        ]);

        $response = $this->getJson('/api/tanggal-tersedia');

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $data = $response->json('data.0');
        
        $this->assertSame($slot->id, $data['id']);
        $this->assertSame('2026-06-15', $data['tanggal']);
        $this->assertIsString($data['tanggal_display']);
        $this->assertSame(10, $data['kuota']);
        $this->assertSame(0, $data['terisi']);
        $this->assertSame(10, $data['sisa']);
        $this->assertSame('Aktif', $data['status']);
        $this->assertTrue($data['is_available']);
        $this->assertSame('Test slot', $data['keterangan']);
    }

    public function test_index_filters_by_date_range(): void
    {
        // Create slots in different months
        TanggalTersedia::factory()->create([
            'tanggal' => '2026-01-15',
            'is_aktif' => true,
        ]);

        TanggalTersedia::factory()->create([
            'tanggal' => '2026-02-15',
            'is_aktif' => true,
        ]);

        TanggalTersedia::factory()->create([
            'tanggal' => '2026-03-15',
            'is_aktif' => true,
        ]);

        $response = $this->getJson('/api/tanggal-tersedia?start_date=2026-02-01&end_date=2026-02-28');

        $response->assertStatus(200);
        
        $data = $response->json('data');
        
        // Should only return February slot
        $this->assertCount(1, $data);
        $this->assertSame('2026-02-15', $data[0]['tanggal']);
    }

    public function test_index_defaults_to_future_dates(): void
    {
        // Create past date slot
        TanggalTersedia::factory()->create([
            'tanggal' => now()->subDays(5),
            'is_aktif' => true,
        ]);

        // Create future date slot
        TanggalTersedia::factory()->create([
            'tanggal' => now()->addDays(5),
            'is_aktif' => true,
        ]);

        $response = $this->getJson('/api/tanggal-tersedia');

        $response->assertStatus(200);
        
        $data = $response->json('data');
        
        // Should only return future dates (default behavior)
        $this->assertGreaterThanOrEqual(1, count($data));
        
        foreach ($data as $slot) {
            $this->assertGreaterThanOrEqual(
                now()->addDay()->startOfDay()->format('Y-m-d'),
                $slot['tanggal']
            );
        }
    }

    public function test_check_returns_availability_for_specific_date(): void
    {
        $slot = TanggalTersedia::factory()->create([
            'tanggal' => '2026-07-15',
            'kuota' => 10,
            'is_aktif' => true,
        ]);

        $response = $this->getJson('/api/tanggal-tersedia/check?tanggal=2026-07-15');

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'available' => true,
            'message' => 'Tanggal tersedia untuk pre-order.',
        ]);

        $data = $response->json('data');
        
        $this->assertSame('2026-07-15', $data['tanggal']);
        $this->assertSame(10, $data['kuota']);
        $this->assertSame(0, $data['terisi']);
        $this->assertSame(10, $data['sisa']);
        $this->assertSame('Aktif', $data['status']);
    }

    public function test_check_returns_false_for_full_slot(): void
    {
        $user = User::factory()->create();
        
        $slot = TanggalTersedia::factory()->create([
            'tanggal' => '2026-07-20',
            'kuota' => 2,
            'is_aktif' => true,
        ]);

        // Fill the slot
        PreOrder::factory()->count(2)->create([
            'actual_periode' => '2026-07-20',
            'status' => 'unpaid',
            'user_id' => $user->id,
        ]);

        $response = $this->getJson('/api/tanggal-tersedia/check?tanggal=2026-07-20');

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'available' => false,
            'message' => 'Tanggal tidak tersedia (slot penuh atau nonaktif).',
        ]);
    }

    public function test_check_returns_false_for_inactive_slot(): void
    {
        TanggalTersedia::factory()->inactive()->create([
            'tanggal' => '2026-07-25',
            'kuota' => 10,
        ]);

        $response = $this->getJson('/api/tanggal-tersedia/check?tanggal=2026-07-25');

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'available' => false,
        ]);
    }

    public function test_check_validates_tanggal_parameter(): void
    {
        $response = $this->getJson('/api/tanggal-tersedia/check');

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'message' => 'Tanggal harus diisi.',
        ]);
    }

    public function test_check_returns_false_for_nonexistent_date(): void
    {
        $response = $this->getJson('/api/tanggal-tersedia/check?tanggal=2026-12-25');

        $response->assertStatus(200);
        $response->assertJson([
            'success' => false,
            'available' => false,
            'message' => 'Tanggal tidak tersedia untuk pre-order.',
        ]);
    }

    public function test_index_orders_by_date_ascending(): void
    {
        TanggalTersedia::factory()->create([
            'tanggal' => '2026-08-20',
            'is_aktif' => true,
        ]);

        TanggalTersedia::factory()->create([
            'tanggal' => '2026-08-10',
            'is_aktif' => true,
        ]);

        TanggalTersedia::factory()->create([
            'tanggal' => '2026-08-15',
            'is_aktif' => true,
        ]);

        $response = $this->getJson('/api/tanggal-tersedia?start_date=2026-08-01&end_date=2026-08-31');

        $response->assertStatus(200);
        
        $data = $response->json('data');
        
        $this->assertSame('2026-08-10', $data[0]['tanggal']);
        $this->assertSame('2026-08-15', $data[1]['tanggal']);
        $this->assertSame('2026-08-20', $data[2]['tanggal']);
    }
}
