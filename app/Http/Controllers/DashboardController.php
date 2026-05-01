<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
{
    $pesanan = [
        [
            "id" => "#001",
            "nama_pelanggan" => "Rina",
            "nama_produk" => "Cake Coklat",
            "tanggal_pembelian" => "2026-05-01",
            "tanggal_pengantaran" => "2026-05-03",
            "total_harga" => 250000,
            "status" => "belum"
        ],
        [
            "id" => "#002",
            "nama_pelanggan" => "Budi",
            "nama_produk" => "Cake Vanilla",
            "tanggal_pembelian" => "2026-05-02",
            "tanggal_pengantaran" => "2026-05-04",
            "total_harga" => 300000,
            "status" => "dikerjakan"
        ],
        [
            "id" => "#003",
            "nama_pelanggan" => "Sari",
            "nama_produk" => "Cupcake",
            "tanggal_pembelian" => "2026-05-01",
            "tanggal_pengantaran" => "2026-05-02",
            "total_harga" => 180000,
            "status" => "dikirim"
        ],
    ];

    return view('pages.dashboard.index', compact('pesanan'));

}   

    public function updateStatus(Request $request, $id)
    {
    return response()->json(['success' => true]);
    }
}

