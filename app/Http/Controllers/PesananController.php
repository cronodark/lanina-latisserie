<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Models\Pesanan; // uncomment saat sudah ada model

class PesananController extends Controller
{
    public function index()
    {
        $pesanan = [
            (object)[
                'id' => 1,
                'id_pesanan' => '#001',
                'nama_pelanggan' => 'Rina',
                'nama_produk' => 'Cake Coklat',
                'tanggal_pembelian' => '2025-05-01',
                'tanggal_pengantaran' => '2025-05-03',
                'total_harga' => 250000,
                'status' => 'Belum'
            ],
            (object)[
                'id' => 2,
                'id_pesanan' => '#002',
                'nama_pelanggan' => 'Budi',
                'nama_produk' => 'Cake Vanilla',
                'tanggal_pembelian' => '2025-05-02',
                'tanggal_pengantaran' => '2025-05-04',
                'total_harga' => 300000,
                'status' => 'Dikerjakan'
            ],
            (object)[
                'id' => 3,
                'id_pesanan' => '#003',
                'nama_pelanggan' => 'Sari',
                'nama_produk' => 'Cupcake',
                'tanggal_pembelian' => '2025-05-01',
                'tanggal_pengantaran' => '2025-05-05',
                'total_harga' => 180000,
                'status' => 'Dikirim'
            ],
        ];

        return view('pages.pesanan-admin.index', [
            'title' => 'Pesanan',
            'pesanan' => $pesanan,
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        // Nanti uncomment saat model sudah ada:
        // $pesanan = Pesanan::findOrFail($id);
        // $pesanan->status = $request->status;
        // $pesanan->save();

        return response()->json(['success' => true]);
    }

    public function edit($id)
{
    // Cari dari array dummy yang sama dengan index()
    $dummyPesanan = [
        1 => (object)[
            'id' => 1,
            'id_pesanan' => '#001',
            'nama_pelanggan' => 'Rina',
            'nama_produk' => 'Cake Coklat',
            'tanggal_pembelian' => '2025-05-01',
            'tanggal_pengantaran' => '2025-05-03',
            'total_harga' => 250000,
            'status' => 'Belum',
            'nomor_telepon' => '08123456789',
            'email' => 'rina@email.com',
            'metode_pembayaran' => 'Transfer',
            'status_pembayaran' => 'Lunas',
            'metode_pengiriman' => 'Antar',
            'alamat' => 'Jl. Contoh No. 1',
            'catatan_alamat' => '',
            'nomor_resi' => '',
            
        ],
        2 => (object)[
            'id' => 2,
            'id_pesanan' => '#002',
            'nama_pelanggan' => 'Budi',
            'nama_produk' => 'Cake Vanilla',
            'tanggal_pembelian' => '2025-05-02',
            'tanggal_pengantaran' => '2025-05-04',
            'total_harga' => 300000,
            'status' => 'Dikerjakan',
            'nomor_telepon' => '08234567890',
            'email' => 'budi@email.com',
            'metode_pembayaran' => 'COD',
            'status_pembayaran' => 'Belum Lunas',
            'metode_pengiriman' => 'Delivery',
            'alamat' => 'Jl. Mawar No. 5',
            'catatan_alamat' => 'Pagar hijau',
            'nomor_resi' => '',
            'produk' => [],
        ],
        3 => (object)[
            'id' => 3,
            'id_pesanan' => '#003',
            'nama_pelanggan' => 'Sari',
            'nama_produk' => 'Cupcake',
            'tanggal_pembelian' => '2025-05-01',
            'tanggal_pengantaran' => '2025-05-05',
            'total_harga' => 180000,
            'status' => 'Dikirim',
            'nomor_telepon' => '08345678901',
            'email' => 'sari@email.com',
            'metode_pembayaran' => 'Transfer',
            'status_pembayaran' => 'Lunas',
            'metode_pengiriman' => 'JNE',
            'alamat' => 'Jl. Melati No. 10',
            'catatan_alamat' => 'Rumah pagar hitam',
            'nomor_resi' => 'JNE123456',
            'produk' => [],
        ],
    ];

    $pesanan = $dummyPesanan[$id] ?? abort(404);

    return view('pages.pesanan-admin.edit', compact('pesanan'));
}

    public function show($id)
    {
        $pesanan = (object)[
            'id' => $id,
            'id_pesanan' => '#00' . $id,
            'nama_pelanggan' => 'Rina',
            'nama_produk' => 'Cake Coklat',
            'tanggal_pembelian' => '2025-05-01',
            'tanggal_pengantaran' => '2025-05-03',
            'total_harga' => 250000,
            'status' => 'Belum'
        ];

        return view('pages.pesanan-admin.show', compact('pesanan'));
    }

    public function destroy($id)
    {
        // Nanti uncomment saat model sudah ada:
        // Pesanan::findOrFail($id)->delete();

        return redirect()->route('pesanan.index')->with('success', 'Pesanan berhasil dihapus');
    }

    public function update(Request $request, $id)
    {
        // Nanti: Pesanan::findOrFail($id)->update($request->validated());
        return redirect()->route('pesanan.index')->with('success', 'Pesanan berhasil diupdate');
    }
}