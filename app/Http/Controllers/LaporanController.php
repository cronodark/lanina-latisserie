<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Models\Pesanan; // uncomment saat model sudah ada

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // Dummy data — hapus semua ini saat model sudah ada
        $tabelPenjualan = [
            (object)['id_pesanan'=>'ORD-001','nama_pelanggan'=>'Kasyah', 'nama_produk'=>'Nastar',       'tanggal_pembelian'=>'12/05/25','tanggal_pengantaran'=>'14/05/25','total_harga'=>10000,'status'=>'Selesai'],
            (object)['id_pesanan'=>'ORD-002','nama_pelanggan'=>'Fatima', 'nama_produk'=>'Lidah Kucing', 'tanggal_pembelian'=>'13/05/25','tanggal_pengantaran'=>'15/05/25','total_harga'=>10000,'status'=>'Belum'],
            (object)['id_pesanan'=>'ORD-003','nama_pelanggan'=>'Puci',   'nama_produk'=>'Nastar',       'tanggal_pembelian'=>'13/05/25','tanggal_pengantaran'=>'13/08/25','total_harga'=>10000,'status'=>'Selesai'],
            (object)['id_pesanan'=>'ORD-004','nama_pelanggan'=>'Saniyah','nama_produk'=>'Putri Salju',  'tanggal_pembelian'=>'13/05/25','tanggal_pengantaran'=>'14/09/25','total_harga'=>10000,'status'=>'Selesai'],
            (object)['id_pesanan'=>'ORD-005','nama_pelanggan'=>'Fatam',  'nama_produk'=>'Stick Keju',   'tanggal_pembelian'=>'13/05/25','tanggal_pengantaran'=>'14/09/25','total_harga'=>10000,'status'=>'Selesai'],
            (object)['id_pesanan'=>'ORD-006','nama_pelanggan'=>'Putri',  'nama_produk'=>'Nastar',       'tanggal_pembelian'=>'13/05/25','tanggal_pengantaran'=>'14/09/25','total_harga'=>10000,'status'=>'Selesai'],
        ];

        return view('pages.laporan.index', [
            'tabelPenjualan' => $tabelPenjualan,
        ]);
    }
}