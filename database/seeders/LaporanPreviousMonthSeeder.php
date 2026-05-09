<?php

namespace Database\Seeders;

use App\Models\PreOrder;
use App\Models\PreOrderDetail;
use App\Models\Product;
use App\Models\User;
use App\Models\Address;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class LaporanPreviousMonthSeeder extends Seeder
{
    /**
     * Seed data untuk bulan sebelumnya (April 2026) untuk perbandingan laporan.
     * 
     * Data ini akan membuat:
     * - 15 pesanan dengan actual_periode di April 2026
     * - Status: completed (untuk simulasi pesanan yang sudah selesai)
     * - Variasi produk untuk menampilkan produk terlaris
     */
    public function run(): void
    {
        // Get existing data
        $user = User::where('email', 'customer@example.com')->first();
        if (!$user) {
            $this->command->error('User customer@example.com tidak ditemukan. Jalankan DatabaseSeeder terlebih dahulu.');
            return;
        }

        $address = Address::where('user_id', $user->id)->first();
        if (!$address) {
            $this->command->error('Address untuk user tidak ditemukan.');
            return;
        }

        $products = Product::all();
        if ($products->isEmpty()) {
            $this->command->error('Tidak ada produk. Jalankan DatabaseSeeder terlebih dahulu.');
            return;
        }

        $this->command->info('Membuat data pesanan untuk April 2026...');

        // Data pesanan untuk April 2026
        $ordersData = [
            // Week 1 - Early April
            [
                'actual_periode' => '2026-04-05',
                'created_at' => '2026-03-28',
                'paid_at' => '2026-03-28',
                'status' => 'completed',
                'total' => 450000,
                'products' => [
                    ['product_id' => 1, 'quantity' => 5],
                    ['product_id' => 2, 'quantity' => 3],
                ]
            ],
            [
                'actual_periode' => '2026-04-06',
                'created_at' => '2026-03-29',
                'paid_at' => '2026-03-29',
                'status' => 'completed',
                'total' => 320000,
                'products' => [
                    ['product_id' => 3, 'quantity' => 4],
                ]
            ],
            [
                'actual_periode' => '2026-04-07',
                'created_at' => '2026-03-30',
                'paid_at' => '2026-03-30',
                'status' => 'completed',
                'total' => 580000,
                'products' => [
                    ['product_id' => 1, 'quantity' => 8],
                    ['product_id' => 4, 'quantity' => 2],
                ]
            ],
            
            // Week 2 - Mid April
            [
                'actual_periode' => '2026-04-12',
                'created_at' => '2026-04-05',
                'paid_at' => '2026-04-05',
                'status' => 'completed',
                'total' => 275000,
                'products' => [
                    ['product_id' => 2, 'quantity' => 3],
                    ['product_id' => 5, 'quantity' => 2],
                ]
            ],
            [
                'actual_periode' => '2026-04-13',
                'created_at' => '2026-04-06',
                'paid_at' => '2026-04-06',
                'status' => 'completed',
                'total' => 420000,
                'products' => [
                    ['product_id' => 1, 'quantity' => 6],
                ]
            ],
            [
                'actual_periode' => '2026-04-14',
                'created_at' => '2026-04-07',
                'paid_at' => '2026-04-07',
                'status' => 'completed',
                'total' => 390000,
                'products' => [
                    ['product_id' => 3, 'quantity' => 5],
                    ['product_id' => 6, 'quantity' => 1],
                ]
            ],
            
            // Week 3 - Late April
            [
                'actual_periode' => '2026-04-19',
                'created_at' => '2026-04-12',
                'paid_at' => '2026-04-12',
                'status' => 'completed',
                'total' => 510000,
                'products' => [
                    ['product_id' => 1, 'quantity' => 7],
                    ['product_id' => 2, 'quantity' => 2],
                ]
            ],
            [
                'actual_periode' => '2026-04-20',
                'created_at' => '2026-04-13',
                'paid_at' => '2026-04-13',
                'status' => 'completed',
                'total' => 340000,
                'products' => [
                    ['product_id' => 4, 'quantity' => 4],
                ]
            ],
            [
                'actual_periode' => '2026-04-21',
                'created_at' => '2026-04-14',
                'paid_at' => '2026-04-14',
                'status' => 'completed',
                'total' => 465000,
                'products' => [
                    ['product_id' => 1, 'quantity' => 5],
                    ['product_id' => 5, 'quantity' => 3],
                ]
            ],
            
            // Week 4 - End of April
            [
                'actual_periode' => '2026-04-26',
                'created_at' => '2026-04-19',
                'paid_at' => '2026-04-19',
                'status' => 'completed',
                'total' => 380000,
                'products' => [
                    ['product_id' => 2, 'quantity' => 4],
                    ['product_id' => 3, 'quantity' => 2],
                ]
            ],
            [
                'actual_periode' => '2026-04-27',
                'created_at' => '2026-04-20',
                'paid_at' => '2026-04-20',
                'status' => 'completed',
                'total' => 520000,
                'products' => [
                    ['product_id' => 1, 'quantity' => 9],
                ]
            ],
            [
                'actual_periode' => '2026-04-28',
                'created_at' => '2026-04-21',
                'paid_at' => '2026-04-21',
                'status' => 'completed',
                'total' => 295000,
                'products' => [
                    ['product_id' => 6, 'quantity' => 3],
                    ['product_id' => 7, 'quantity' => 1],
                ]
            ],
            [
                'actual_periode' => '2026-04-29',
                'created_at' => '2026-04-22',
                'paid_at' => '2026-04-22',
                'status' => 'completed',
                'total' => 435000,
                'products' => [
                    ['product_id' => 1, 'quantity' => 6],
                    ['product_id' => 4, 'quantity' => 2],
                ]
            ],
            [
                'actual_periode' => '2026-04-30',
                'created_at' => '2026-04-23',
                'paid_at' => '2026-04-23',
                'status' => 'completed',
                'total' => 360000,
                'products' => [
                    ['product_id' => 2, 'quantity' => 5],
                ]
            ],
            [
                'actual_periode' => '2026-04-30',
                'created_at' => '2026-04-23',
                'paid_at' => '2026-04-23',
                'status' => 'completed',
                'total' => 490000,
                'products' => [
                    ['product_id' => 1, 'quantity' => 7],
                    ['product_id' => 3, 'quantity' => 2],
                ]
            ],
        ];

        $createdCount = 0;

        foreach ($ordersData as $orderData) {
            // Create PreOrder
            $order = PreOrder::create([
                'user_id' => $user->id,
                'address_id' => $address->id,
                'actual_periode' => Carbon::parse($orderData['actual_periode']),
                'start_periode' => Carbon::parse($orderData['actual_periode'])->subDays(7),
                'end_periode' => null,
                'status' => $orderData['status'],
                'payment_method' => 'bank_transfer',
                'midtrans_order_id' => 'PO-APRIL-' . uniqid(),
                'midtrans_transaction_id' => 'TRX-APRIL-' . uniqid(),
                'payment_redirect_url' => null,
                'paid_at' => Carbon::parse($orderData['paid_at']),
                'send_type' => collect(['pickUp', 'kurirToko', 'kurirEkspedisi'])->random(),
                'tracking_number' => $orderData['status'] === 'completed' ? 'RESI-' . strtoupper(substr(md5(uniqid()), 0, 10)) : null,
                'choosen_expedition' => null,
                'total' => $orderData['total'],
                'created_at' => Carbon::parse($orderData['created_at']),
                'updated_at' => Carbon::parse($orderData['created_at']),
            ]);

            // Create PreOrderDetails
            foreach ($orderData['products'] as $productData) {
                $product = $products->find($productData['product_id']);
                if ($product) {
                    PreOrderDetail::create([
                        'pre_order_id' => $order->id,
                        'type' => 'product',
                        'product_id' => $product->id,
                        'promo_id' => null,
                        'quantity' => $productData['quantity'],
                        'created_at' => $order->created_at,
                        'updated_at' => $order->created_at,
                    ]);
                }
            }

            $createdCount++;
        }

        $this->command->info("✓ Berhasil membuat {$createdCount} pesanan untuk April 2026");
        
        // Summary
        $totalRevenue = collect($ordersData)->sum('total');
        $totalProducts = collect($ordersData)->flatMap(fn($o) => $o['products'])->sum('quantity');
        
        $this->command->info("  Total Pendapatan: Rp " . number_format($totalRevenue, 0, ',', '.'));
        $this->command->info("  Total Produk Terjual: {$totalProducts} pcs");
        $this->command->info("  Periode: 5 April - 30 April 2026");
    }
}
