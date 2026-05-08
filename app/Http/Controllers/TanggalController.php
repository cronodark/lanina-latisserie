<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class TanggalController extends Controller
{
    public function kalender(Request $request)
    {
        $bulan = (int) $request->get('bulan', now()->month);
        $tahun = (int) $request->get('tahun', now()->year);

        $semuaPesanan = collect([
            [
                'tanggal'  => Carbon::createFromDate($tahun, $bulan, 3)->format('Y-m-d'),
                'customer' => 'Andi Saputra',
                'email'    => 'andi@email.com',
                'status'   => 'paid',
                'total'    => 350000,
                'items'    => [
                    ['produk' => 'Birthday Cake', 'quantity' => 1, 'type' => 'Vanilla'],
                    ['produk' => 'Macaron Box', 'quantity' => 2, 'type' => 'Assorted'],
                ],
            ],
            [
                'tanggal'  => Carbon::createFromDate($tahun, $bulan, 3)->format('Y-m-d'),
                'customer' => 'Rina Dewi',
                'email'    => 'rina@email.com',
                'status'   => 'paid',
                'total'    => 120000,
                'items'    => [
                    ['produk' => 'Croissant', 'quantity' => 6, 'type' => 'Original'],
                ],
            ],
            [
                'tanggal'  => Carbon::createFromDate($tahun, $bulan, 7)->format('Y-m-d'),
                'customer' => 'Budi Santoso',
                'email'    => 'budi@email.com',
                'status'   => 'completed',
                'total'    => 280000,
                'items'    => [
                    ['produk' => 'Tart Buah', 'quantity' => 1, 'type' => 'Medium'],
                    ['produk' => 'Éclair', 'quantity' => 4, 'type' => 'Chocolate'],
                ],
            ],

            // ====== 4 pesanan di tanggal 10 ======
            [
                'tanggal'  => Carbon::createFromDate($tahun, $bulan, 10)->format('Y-m-d'),
                'customer' => 'Citra Lestari',
                'email'    => 'citra@email.com',
                'status'   => 'shipping',
                'total'    => 450000,
                'items'    => [
                    ['produk' => 'Macaron Box', 'quantity' => 3, 'type' => 'Matcha'],
                    ['produk' => 'Croissant', 'quantity' => 2, 'type' => 'Butter'],
                ],
            ],
            [
                'tanggal'  => Carbon::createFromDate($tahun, $bulan, 10)->format('Y-m-d'),
                'customer' => 'Deni Firmansyah',
                'email'    => 'deni@email.com',
                'status'   => 'paid',
                'total'    => 320000,
                'items'    => [
                    ['produk' => 'Cheesecake', 'quantity' => 1, 'type' => 'Blueberry'],
                    ['produk' => 'Croissant', 'quantity' => 4, 'type' => 'Almond'],
                ],
            ],
            [
                'tanggal'  => Carbon::createFromDate($tahun, $bulan, 10)->format('Y-m-d'),
                'customer' => 'Eva Marlina',
                'email'    => 'eva@email.com',
                'status'   => 'pending',
                'total'    => 175000,
                'items'    => [
                    ['produk' => 'Éclair', 'quantity' => 5, 'type' => 'Vanilla'],
                ],
            ],
            [
                'tanggal'  => Carbon::createFromDate($tahun, $bulan, 10)->format('Y-m-d'),
                'customer' => 'Farhan Nugroho',
                'email'    => 'farhan@email.com',
                'status'   => 'completed',
                'total'    => 620000,
                'items'    => [
                    ['produk' => 'Wedding Cake', 'quantity' => 1, 'type' => '2 Tier'],
                    ['produk' => 'Macaron Box', 'quantity' => 2, 'type' => 'Rose'],
                    ['produk' => 'Tart Buah', 'quantity' => 1, 'type' => 'Medium'],
                ],
            ],
            // ======================================

            [
                'tanggal'  => Carbon::createFromDate($tahun, $bulan, 15)->format('Y-m-d'),
                'customer' => 'Sinta Rahayu',
                'email'    => 'sinta@email.com',
                'status'   => 'completed',
                'total'    => 1200000,
                'items'    => [
                    ['produk' => 'Wedding Cake', 'quantity' => 1, 'type' => '3 Tier'],
                ],
            ],
            [
                'tanggal'  => Carbon::createFromDate($tahun, $bulan, 15)->format('Y-m-d'),
                'customer' => 'Fahri Maulana',
                'email'    => 'fahri@email.com',
                'status'   => 'paid',
                'total'    => 380000,
                'items'    => [
                    ['produk' => 'Birthday Cake', 'quantity' => 1, 'type' => 'Chocolate'],
                    ['produk' => 'Macaron Box', 'quantity' => 1, 'type' => 'Mixed'],
                ],
            ],
            [
                'tanggal'  => Carbon::createFromDate($tahun, $bulan, now()->day + 1)->format('Y-m-d'),
                'customer' => 'Gita Permata',
                'email'    => 'gita@email.com',
                'status'   => 'paid',
                'total'    => 210000,
                'items'    => [
                    ['produk' => 'Tart Buah', 'quantity' => 1, 'type' => 'Small'],
                    ['produk' => 'Croissant', 'quantity' => 3, 'type' => 'Butter'],
                ],
            ],
            [
                'tanggal'  => Carbon::createFromDate($tahun, $bulan, now()->day + 2)->format('Y-m-d'),
                'customer' => 'Hani Kusuma',
                'email'    => 'hani@email.com',
                'status'   => 'pending',
                'total'    => 95000,
                'items'    => [
                    ['produk' => 'Croissant', 'quantity' => 4, 'type' => 'Original'],
                ],
            ],
            [
                'tanggal'  => Carbon::createFromDate($tahun, $bulan, now()->day)->format('Y-m-d'),
                'customer' => 'Irwan Hakim',
                'email'    => 'irwan@email.com',
                'status'   => 'paid',
                'total'    => 560000,
                'items'    => [
                    ['produk' => 'Birthday Cake', 'quantity' => 1, 'type' => 'Red Velvet'],
                    ['produk' => 'Macaron Box', 'quantity' => 2, 'type' => 'Rose'],
                    ['produk' => 'Éclair', 'quantity' => 6, 'type' => 'Mixed'],
                ],
            ],
        ]);

        $pesananPerTanggal = $semuaPesanan->groupBy('tanggal');

        $totalPesanan    = $semuaPesanan->count();
        $slotTersedia    = 12;
        $tanggalRamai    = $pesananPerTanggal->sortByDesc(fn($v) => $v->count())->keys()->first();
        $tanggalRamaiFmt = $tanggalRamai ? Carbon::parse($tanggalRamai)->format('d M') : '-';

        return view('pages.jadwal-admin.kalender', compact(
            'bulan', 'tahun', 'pesananPerTanggal', 'semuaPesanan',
            'totalPesanan', 'slotTersedia', 'tanggalRamaiFmt'
        ));
    }
}
