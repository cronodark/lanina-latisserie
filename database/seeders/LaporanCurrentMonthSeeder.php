<?php

namespace Database\Seeders;

use App\Models\PreOrder;
use App\Models\PreOrderDetail;
use App\Models\Product;
use App\Models\User;
use App\Models\Address;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class LaporanCurrentMonthSeeder extends Seeder
{
    /**
     * Seed data untuk bulan ini (Mei 2026) untuk laporan.
     * 
     * Data ini akan membuat:
     * - 20 pesanan dengan actual_periode di Mei 2026
     * - Status: completed dan processing
     * - Variasi produk untuk menampilkan produk terlaris
     * - Total pendapatan lebih tinggi dari bulan sebelumnya (growth)
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

        $this->command->info('Membuat data pesanan untuk Mei 2026...');

        // Data pesanan untuk Mei 2026
        $ordersData = [
            // Week 1 - Early May
            [
                'actual_periode' => '2026-05-03',
                'created_at' => '2026-04-26',
                'paid_at' => '2026-04-26',
                'status' => 'completed',
                'total' => 520000,
                'products' => [
                    ['product_id' => 1, 'quantity' => 7],
                    ['product_id' => 2, 'quantity' => 3],
                ]
            ],
            [
                'actual_periode' => '2026-05-04',
                'created_at' => '2026-04-27',
                'paid_at' => '2026-04-27',
                'status' => 'completed',
                'total' => 380000,
                'products' => [
                    ['product_id' => 3, 'quantity' => 5],
                ]
            ],
            [
                'actual_periode' => '2026-05-05',
                'created_at' => '2026-04-28',
                'paid_at' => '2026-04-28',
                'status' => 'completed',
                'total' => 640000,
                'products' => [
                    ['product_id' => 1, 'quantity' => 10],
                    ['product_id' => 4, 'quantity' => 2],
                ]
            ],
            [
                'actual_periode' => '2026-05-06',
                'created_at' => '2026-04-29',
                'paid_at' => '2026-04-29',
                'status' => 'completed',
                'total' => 295000,
                'products' => [
                    ['product_id' => 5, 'quantity' => 3],
                ]
            ],
            
            // Week 2 - Mid May
            [
                'actual_periode' => '2026-05-10',
                'created_at' => '2026-05-03',
                'paid_at' => '2026-05-03',
                'status' => 'completed',
                'total' => 475000,
                'products' => [
                    ['product_id' => 1, 'quantity' => 6],
                    ['product_id' => 2, 'quantity' => 2],
                ]
            ],
            [
                'actual_periode' => '2026-05-11',
                'created_at' => '2026-05-04',
                'paid_at' => '2026-05-04',
                'status' => 'completed',
                'total' => 560000,
                'products' => [
                    ['product_id' => 1, 'quantity' => 8],
                ]
            ],
            [
                'actual_periode' => '2026-05-12',
                'created_at' => '2026-05-05',
                'paid_at' => '2026-05-05',
                'status' => 'completed',
                'total' => 420000,
                'products' => [
                    ['product_id' => 3, 'quantity' => 6],
                    ['product_id' => 6, 'quantity' => 1],
                ]
            ],
            [
                'actual_periode' => '2026-05-13',
                'created_at' => '2026-05-06',
                'paid_at' => '2026-05-06',
                'status' => 'completed',
                'total' => 385000,
                'products' => [
                    ['product_id' => 2, 'quantity' => 4],
                    ['product_id' => 4, 'quantity' => 2],
                ]
            ],
            
            // Week 3 - Late May
            [
                'actual_periode' => '2026-05-17',
                'created_at' => '2026-05-10',
                'paid_at' => '2026-05-10',
                'status' => 'completed',
                'total' => 590000,
                'products' => [
                    ['product_id' => 1, 'quantity' => 9],
                    ['product_id' => 2, 'quantity' => 2],
                ]
            ],
            [
                'actual_periode' => '2026-05-18',
                'created_at' => '2026-05-11',
                'paid_at' => '2026-05-11',
                'status' => 'completed',
                'total' => 410000,
                'products' => [
                    ['product_id' => 3, 'quantity' => 5],
                    ['product_id' => 5, 'quantity' => 2],
                ]
            ],
            [
                'actual_periode' => '2026-05-19',
                'created_at' => '2026-05-12',
                'paid_at' => '2026-05-12',
                'status' => 'completed',
                'total' => 505000,
                'products' => [
                    ['product_id' => 1, 'quantity' => 7],
                    ['product_id' => 4, 'quantity' => 3],
                ]
            ],
            [
                'actual_periode' => '2026-05-20',
                'created_at' => '2026-05-13',
                'paid_at' => '2026-05-13',
                'status' => 'completed',
                'total' => 340000,
                'products' => [
                    ['product_id' => 6, 'quantity' => 4],
                ]
            ],
            
            // Week 4 - End of May
            [
                'actual_periode' => '2026-05-24',
                'created_at' => '2026-05-17',
                'paid_at' => '2026-05-17',
                'status' => 'completed',
                'total' => 625000,
                'products' => [
                    ['product_id' => 1, 'quantity' => 10],
                    ['product_id' => 2, 'quantity' => 3],
                ]
            ],
            [
                'actual_periode' => '2026-05-25',
                'created_at' => '2026-05-18',
                'paid_at' => '2026-05-18',
                'status' => 'completed',
                'total' => 455000,
                'products' => [
                    ['product_id' => 3, 'quantity' => 6],
                ]
            ],
            [
                'actual_periode' => '2026-05-26',
                'created_at' => '2026-05-19',
                'paid_at' => '2026-05-19',
                'status' => 'completed',
                'total' => 530000,
                'products' => [
                    ['product_id' => 1, 'quantity' => 8],
                    ['product_id' => 5, 'quantity' => 2],
                ]
            ],
            [
                'actual_periode' => '2026-05-27',
                'created_at' => '2026-05-20',
                'paid_at' => '2026-05-20',
                'status' => 'completed',
                'total' => 395000,
                'products' => [
                    ['product_id' => 2, 'quantity' => 5],
                    ['product_id' => 4, 'quantity' => 1],
                ]
            ],
            
            // Future orders (processing)
            [
                'actual_periode' => '2026-05-30',
                'created_at' => '2026-05-23',
                'paid_at' => '2026-05-23',
                'status' => 'processing',
                'total' => 480000,
                'products' => [
                    ['product_id' => 1, 'quantity' => 7],
                ]
            ],
            [
                'actual_periode' => '2026-05-31',
                'created_at' => '2026-05-24',
                'paid_at' => '2026-05-24',
                'status' => 'processing',
                'total' => 365000,
                'products' => [
                    ['product_id' => 3, 'quantity' => 4],
                    ['product_id' => 6, 'quantity' => 2],
                ]
            ],
            [
                'actual_periode' => '2026-05-31',
                'created_at' => '2026-05-24',
                'paid_at' => '2026-05-24',
                'status' => 'processing',
                'total' => 550000,
                'products' => [
                    ['product_id' => 1, 'quantity' => 9],
                    ['product_id' => 2, 'quantity' => 2],
                ]
            ],
            [
                'actual_periode' => '2026-05-31',
                'created_at' => '2026-05-25',
                'paid_at' => '2026-05-25',
                'status' => 'processing',
                'total' => 420000,
                'products' => [
                    ['product_id' => 4, 'quantity' => 5],
                    ['product_id' => 5, 'quantity' => 2],
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
                'midtrans_order_id' => 'PO-MAY-' . uniqid(),
                'midtrans_transaction_id' => 'TRX-MAY-' . uniqid(),
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

        $this->command->info("✓ Berhasil membuat {$createdCount} pesanan untuk Mei 2026");
        
        // Summary
        $totalRevenue = collect($ordersData)->sum('total');
        $totalProducts = collect($ordersData)->flatMap(fn($o) => $o['products'])->sum('quantity');
        $completedOrders = collect($ordersData)->where('status', 'completed')->count();
        $processingOrders = collect($ordersData)->where('status', 'processing')->count();
        
        $this->command->info("  Total Pendapatan: Rp " . number_format($totalRevenue, 0, ',', '.'));
        $this->command->info("  Total Produk Terjual: {$totalProducts} pcs");
        $this->command->info("  Pesanan Selesai: {$completedOrders}");
        $this->command->info("  Pesanan Dikerjakan: {$processingOrders}");
        $this->command->info("  Periode: 3 Mei - 31 Mei 2026");
    }
}
