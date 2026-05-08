<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // ═══════════════════════════════════════
        // LIST BULAN
        // ═══════════════════════════════════════
        $bulanList = [
            1  => 'Januari',
            2  => 'Februari',
            3  => 'Maret',
            4  => 'April',
            5  => 'Mei',
            6  => 'Juni',
            7  => 'Juli',
            8  => 'Agustus',
            9  => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        // ═══════════════════════════════════════
        // FILTER
        // ═══════════════════════════════════════
        $bulanTerpilihAngka = (int) $request->bulan ?? 10;
        $tahunTerpilih = (int) $request->tahun ?? 2025;
        $produkFilterDipilih = $request->produk_filter ?? '';

        if (!array_key_exists($bulanTerpilihAngka, $bulanList)) {
            $bulanTerpilihAngka = 10;
        }

        $bulanTerpilih = $bulanList[$bulanTerpilihAngka];

        $bulanSebelumnya = $bulanTerpilihAngka == 1
            ? $bulanList[12]
            : $bulanList[$bulanTerpilihAngka - 1];

        // ═══════════════════════════════════════
        // DUMMY SUMMARY
        // ═══════════════════════════════════════
        $dummyData = [
            10 => [
                'penjualan' => 6000000,
                'pesanan' => 120,
                'produk' => 350,
                'pend_sblm' => 4500000,
                'jml_sblm' => 280,
            ],
            11 => [
                'penjualan' => 7200000,
                'pesanan' => 145,
                'produk' => 420,
                'pend_sblm' => 6000000,
                'jml_sblm' => 350,
            ],
        ];

        $d = $dummyData[$bulanTerpilihAngka] ?? $dummyData[10];

        // ═══════════════════════════════════════
        // KPI
        // ═══════════════════════════════════════
        $totalPenjualan = $d['penjualan'];
        $totalPesanan = $d['pesanan'];
        $totalProdukTerjual = $d['produk'];

        $pendapatanSebelumnya = $d['pend_sblm'];
        $pendapatanTerpilih = $d['penjualan'];

        $jumlahSebelumnya = $d['jml_sblm'];
        $jumlahTerpilih = $d['produk'];

        // ═══════════════════════════════════════
        // PRODUK TERLARIS
        // ═══════════════════════════════════════
        $produkTerlaris = [
            [
                'nama' => 'Nastar',
                'persen' => 45.8,
                'warna' => '#996633',
            ],
            [
                'nama' => 'Putri Salju',
                'persen' => 8.3,
                'warna' => '#C48C5A',
            ],
            [
                'nama' => 'Lidah Kucing',
                'persen' => 8.3,
                'warna' => '#E9E1CC',
            ],
            [
                'nama' => 'Cookies',
                'persen' => 8.3,
                'warna' => '#D8DEB4',
            ],
            [
                'nama' => 'Stick Keju',
                'persen' => 29.3,
                'warna' => '#A7B86A',
            ],
        ];

        // ═══════════════════════════════════════
        // SLIDER
        // ═══════════════════════════════════════
        $sliderProduk = [
            [
                'nama' => 'Nastar',
                'persen' => 45.8,
                'gambar' => 'images/1.png',
            ],
            [
                'nama' => 'Stick Keju',
                'gambar' => 'images/3.png',
                'persen' => 29,
            ],
            [
                'nama' => 'Cookies',
                'gambar' => 'images/2.png',
                'persen' => 8,
            ],
            [
                'nama' => 'Putri Salju',
                'persen' => 8,
                'gambar' => 'images/1.png',
            ],
            [
                'nama' => 'Lidah Kucing',
                'persen' => 8,
                'gambar' => 'images/3.png',
            ],
        ];

        // ═══════════════════════════════════════
        // TABEL
        // ═══════════════════════════════════════
        $tabelPenjualan = [
            (object)[
                'id' => 1,
                'id_pesanan' => 'ORD-001',
                'nama_pelanggan' => 'Kasyah',
                'nama_produk' => 'Nastar',
                'tanggal_pembelian' => '12/10/25',
                'tanggal_pengantaran' => '14/10/25',
                'total_harga' => 150000,
                'status' => 'Selesai',
            ],
            (object)[
                'id' => 2,
                'id_pesanan' => 'ORD-002',
                'nama_pelanggan' => 'Fatiana',
                'nama_produk' => 'Lidah Kucing',
                'tanggal_pembelian' => '12/10/25',
                'tanggal_pengantaran' => '14/10/25',
                'total_harga' => 100000,
                'status' => 'Dikirim',
            ],
        ];

        // ═══════════════════════════════════════
        // PERSENTASE BAR
        // ═══════════════════════════════════════
        $maxPendapatan = max($pendapatanSebelumnya, $pendapatanTerpilih);

        $pctPendSebelum = round(($pendapatanSebelumnya / $maxPendapatan) * 100);

        $pctPendTerpilih = round(($pendapatanTerpilih / $maxPendapatan) * 100);

        $maxJumlah = max($jumlahSebelumnya, $jumlahTerpilih);

        $pctJmlSebelum = round(($jumlahSebelumnya / $maxJumlah) * 100);

        $pctJmlTerpilih = round(($jumlahTerpilih / $maxJumlah) * 100);

        return view('pages.laporan.index', compact(
            'bulanList',
            'bulanTerpilihAngka',
            'bulanTerpilih',
            'bulanSebelumnya',
            'tahunTerpilih',
            'produkFilterDipilih',

            'totalPenjualan',
            'totalPesanan',
            'totalProdukTerjual',

            'pendapatanSebelumnya',
            'pendapatanTerpilih',

            'jumlahSebelumnya',
            'jumlahTerpilih',

            'pctPendSebelum',
            'pctPendTerpilih',

            'pctJmlSebelum',
            'pctJmlTerpilih',

            'produkTerlaris',
            'sliderProduk',
            'tabelPenjualan'
        ));
    }
}