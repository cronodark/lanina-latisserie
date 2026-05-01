@extends('layouts.admin')

@section('title', 'Laporan Penjualan | lanina')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

@php
// ══════════════════════════════════════════════════════════════
// DUMMY DATA — Saat disambung backend, hapus semua blok ?? ini
// dan pass variabel dari controller via compact() atau with()
// ══════════════════════════════════════════════════════════════

$bulanList = [
    1=>'Januari', 2=>'Februari', 3=>'Maret',    4=>'April',
    5=>'Mei',     6=>'Juni',     7=>'Juli',      8=>'Agustus',
    9=>'September',10=>'Oktober',11=>'November', 12=>'Desember',
];

// Ambil filter dari query string (GET), default Oktober 2025
$bulanTerpilihAngka  = (int) request('bulan',  10);
$tahunTerpilih       = (int) request('tahun',   2025);
$produkFilterDipilih = request('produk_filter', '');

// Pastikan nilai bulan valid
if (!array_key_exists($bulanTerpilihAngka, $bulanList)) $bulanTerpilihAngka = 10;

$bulanTerpilih   = $bulanList[$bulanTerpilihAngka];
$bulanSebelumnya = $bulanTerpilihAngka === 1
    ? $bulanList[12]
    : $bulanList[$bulanTerpilihAngka - 1];

// ── Dummy ringkasan per bulan ──
$dummyData = [
    1  => ['penjualan'=>3200000, 'pesanan'=>75,  'produk'=>210, 'pend_sblm'=>2800000, 'jml_sblm'=>185],
    2  => ['penjualan'=>3800000, 'pesanan'=>88,  'produk'=>245, 'pend_sblm'=>3200000, 'jml_sblm'=>210],
    3  => ['penjualan'=>4100000, 'pesanan'=>95,  'produk'=>260, 'pend_sblm'=>3800000, 'jml_sblm'=>245],
    4  => ['penjualan'=>3900000, 'pesanan'=>90,  'produk'=>250, 'pend_sblm'=>4100000, 'jml_sblm'=>260],
    5  => ['penjualan'=>4600000, 'pesanan'=>105, 'produk'=>295, 'pend_sblm'=>3900000, 'jml_sblm'=>250],
    6  => ['penjualan'=>5100000, 'pesanan'=>115, 'produk'=>320, 'pend_sblm'=>4600000, 'jml_sblm'=>295],
    7  => ['penjualan'=>5400000, 'pesanan'=>118, 'produk'=>335, 'pend_sblm'=>5100000, 'jml_sblm'=>320],
    8  => ['penjualan'=>4800000, 'pesanan'=>108, 'produk'=>300, 'pend_sblm'=>5400000, 'jml_sblm'=>335],
    9  => ['penjualan'=>4500000, 'pesanan'=>95,  'produk'=>280, 'pend_sblm'=>4800000, 'jml_sblm'=>300],
    10 => ['penjualan'=>6000000, 'pesanan'=>120, 'produk'=>350, 'pend_sblm'=>4500000, 'jml_sblm'=>280],
    11 => ['penjualan'=>7200000, 'pesanan'=>145, 'produk'=>420, 'pend_sblm'=>6000000, 'jml_sblm'=>350],
    12 => ['penjualan'=>8500000, 'pesanan'=>180, 'produk'=>510, 'pend_sblm'=>7200000, 'jml_sblm'=>420],
];

$d = $dummyData[$bulanTerpilihAngka];

$totalPenjualan       = $totalPenjualan       ?? $d['penjualan'];
$totalPesanan         = $totalPesanan         ?? $d['pesanan'];
$totalProdukTerjual   = $totalProdukTerjual   ?? $d['produk'];
$pendapatanSebelumnya = $pendapatanSebelumnya ?? $d['pend_sblm'];
$pendapatanTerpilih   = $pendapatanTerpilih   ?? $d['penjualan'];
$jumlahSebelumnya     = $jumlahSebelumnya     ?? $d['jml_sblm'];
$jumlahTerpilih       = $jumlahTerpilih       ?? $d['produk'];

// ── Dummy produk terlaris ──
$produkTertaris = $produkTertaris ?? [
    ['nama'=>'Nastar',       'persen'=>40.8, 'warna'=>'#BB9457'],
    ['nama'=>'Putri Salju',  'persen'=>25.4, 'warna'=>'#C4A882'],
    ['nama'=>'Lidah Kucing', 'persen'=>18.3, 'warna'=>'#6B8F4E'],
    ['nama'=>'Cookies',      'persen'=>10.2, 'warna'=>'#A8C17A'],
    ['nama'=>'Stick Keju',   'persen'=>5.3,  'warna'=>'#D4C5A9'],
];

// ── Dummy jumlah per produk (untuk filter chart jumlah terjual) ──
$jumlahProdukSebelumnya = $jumlahProdukSebelumnya ?? [
    'Nastar'      => round($d['jml_sblm'] * 0.41),
    'Putri Salju' => round($d['jml_sblm'] * 0.25),
    'Lidah Kucing'=> round($d['jml_sblm'] * 0.18),
    'Cookies'     => round($d['jml_sblm'] * 0.10),
    'Stick Keju'  => round($d['jml_sblm'] * 0.06),
];
$jumlahProdukTerpilih = $jumlahProdukTerpilih ?? [
    'Nastar'      => round($d['produk'] * 0.41),
    'Putri Salju' => round($d['produk'] * 0.25),
    'Lidah Kucing'=> round($d['produk'] * 0.18),
    'Cookies'     => round($d['produk'] * 0.10),
    'Stick Keju'  => round($d['produk'] * 0.06),
];

// Terapkan filter produk ke bar jumlah
if ($produkFilterDipilih && isset($jumlahProdukSebelumnya[$produkFilterDipilih])) {
    $jumlahSebelumnya = $jumlahProdukSebelumnya[$produkFilterDipilih];
    $jumlahTerpilih   = $jumlahProdukTerpilih[$produkFilterDipilih];
}

// ── Dummy slider produk ──
$sliderProduk = $sliderProduk ?? [
    ['nama'=>'Nastar',       'persen'=>40.8, 'gambar'=>'/images/nastar.jpg'],
    ['nama'=>'Putri Salju',  'persen'=>25.4, 'gambar'=>'/images/putri-salju.jpg'],
    ['nama'=>'Lidah Kucing', 'persen'=>18.3, 'gambar'=>'/images/lidah-kucing.jpg'],
    ['nama'=>'Cookies',      'persen'=>10.2, 'gambar'=>'/images/cookies.jpg'],
    ['nama'=>'Stick Keju',   'persen'=>5.3,  'gambar'=>'/images/stick-keju.jpg'],
];

// ── Dummy tabel per bulan ──
$dummyTabel = [
    9 => [
        (object)['id_pesanan'=>'ORD-091','nama_pelanggan'=>'Budi',   'nama_produk'=>'Nastar',       'tanggal_pembelian'=>'02/09/25','tanggal_pengantaran'=>'04/09/25','total_harga'=>150000,'status'=>'Selesai'],
        (object)['id_pesanan'=>'ORD-092','nama_pelanggan'=>'Sari',   'nama_produk'=>'Cookies',      'tanggal_pembelian'=>'05/09/25','tanggal_pengantaran'=>'07/09/25','total_harga'=>60000, 'status'=>'Selesai'],
        (object)['id_pesanan'=>'ORD-093','nama_pelanggan'=>'Andi',   'nama_produk'=>'Lidah Kucing', 'tanggal_pembelian'=>'10/09/25','tanggal_pengantaran'=>'12/09/25','total_harga'=>95000, 'status'=>'Belum'],
    ],
    10 => [
        (object)['id_pesanan'=>'ORD-001','nama_pelanggan'=>'Kasyah', 'nama_produk'=>'Nastar',       'tanggal_pembelian'=>'12/10/25','tanggal_pengantaran'=>'14/10/25','total_harga'=>150000,'status'=>'Selesai'],
        (object)['id_pesanan'=>'ORD-002','nama_pelanggan'=>'Fatima', 'nama_produk'=>'Lidah Kucing', 'tanggal_pembelian'=>'13/10/25','tanggal_pengantaran'=>'15/10/25','total_harga'=>95000, 'status'=>'Belum'],
        (object)['id_pesanan'=>'ORD-003','nama_pelanggan'=>'Puci',   'nama_produk'=>'Nastar',       'tanggal_pembelian'=>'13/10/25','tanggal_pengantaran'=>'13/10/25','total_harga'=>120000,'status'=>'Selesai'],
        (object)['id_pesanan'=>'ORD-004','nama_pelanggan'=>'Saniyah','nama_produk'=>'Putri Salju',  'tanggal_pembelian'=>'13/10/25','tanggal_pengantaran'=>'14/10/25','total_harga'=>80000, 'status'=>'Selesai'],
        (object)['id_pesanan'=>'ORD-005','nama_pelanggan'=>'Fatam',  'nama_produk'=>'Stick Keju',   'tanggal_pembelian'=>'13/10/25','tanggal_pengantaran'=>'14/10/25','total_harga'=>75000, 'status'=>'Dikerjakan'],
        (object)['id_pesanan'=>'ORD-006','nama_pelanggan'=>'Putri',  'nama_produk'=>'Nastar',       'tanggal_pembelian'=>'13/10/25','tanggal_pengantaran'=>'14/10/25','total_harga'=>150000,'status'=>'Dikirim'],
    ],
    11 => [
        (object)['id_pesanan'=>'ORD-111','nama_pelanggan'=>'Dewi',   'nama_produk'=>'Putri Salju',  'tanggal_pembelian'=>'01/11/25','tanggal_pengantaran'=>'03/11/25','total_harga'=>120000,'status'=>'Selesai'],
        (object)['id_pesanan'=>'ORD-112','nama_pelanggan'=>'Hasan',  'nama_produk'=>'Nastar',       'tanggal_pembelian'=>'02/11/25','tanggal_pengantaran'=>'05/11/25','total_harga'=>150000,'status'=>'Dikirim'],
        (object)['id_pesanan'=>'ORD-113','nama_pelanggan'=>'Rizki',  'nama_produk'=>'Stick Keju',   'tanggal_pembelian'=>'03/11/25','tanggal_pengantaran'=>'06/11/25','total_harga'=>75000, 'status'=>'Dikerjakan'],
        (object)['id_pesanan'=>'ORD-114','nama_pelanggan'=>'Nilam',  'nama_produk'=>'Cookies',      'tanggal_pembelian'=>'05/11/25','tanggal_pengantaran'=>'08/11/25','total_harga'=>60000, 'status'=>'Selesai'],
    ],
];

$tabelPenjualan = $tabelPenjualan
    ?? ($dummyTabel[$bulanTerpilihAngka] ?? $dummyTabel[10]);

// ── Kalkulasi bar chart (%) ──
$maxPendapatan   = max($pendapatanSebelumnya, $pendapatanTerpilih, 1);
$maxJumlah       = max($jumlahSebelumnya, $jumlahTerpilih, 1);
$pctPendSebelum  = max(5, round(($pendapatanSebelumnya / $maxPendapatan) * 100));
$pctPendTerpilih = max(5, round(($pendapatanTerpilih   / $maxPendapatan) * 100));
$pctJmlSebelum   = max(5, round(($jumlahSebelumnya     / $maxJumlah)     * 100));
$pctJmlTerpilih  = max(5, round(($jumlahTerpilih       / $maxJumlah)     * 100));

// ── Kalkulasi donut SVG ──
$r             = 54;
$circumference = round(2 * M_PI * $r, 4);
$totalPersen   = array_sum(array_column($produkTertaris, 'persen'));
$offsetAcc     = 0;
$segments      = [];
foreach ($produkTertaris as $p) {
    $pct        = $totalPersen > 0 ? ($p['persen'] / $totalPersen) * 100 : 0;
    $dash       = round(($pct / 100) * $circumference, 4);
    $gap        = round($circumference - $dash, 4);
    $segments[] = [
        'dash'   => $dash,
        'gap'    => $gap,
        'offset' => round($circumference - $offsetAcc, 4),
        'warna'  => $p['warna'],
        'nama'   => $p['nama'],
        'persen' => round($p['persen'], 1),
    ];
    $offsetAcc += $dash;
}
@endphp

{{-- ══════════════ HALAMAN ══════════════ --}}
<h1 class="text-2xl font-bold text-gray-800 mb-6">Laporan Penjualan</h1>

{{-- ══ FILTER FORM (GET) ══ --}}
<form method="GET" action="{{ url()->current() }}" id="filterForm">

    {{-- Filter Bar --}}
    <div class="bg-[#BB9457] rounded-2xl px-6 py-4 mb-6
                flex flex-wrap items-center justify-between gap-3">
        <span class="text-white font-semibold text-sm">Filter Laporan</span>
        <div class="flex items-center gap-3">

            <select name="bulan"
                class="px-4 py-1.5 rounded-lg text-sm bg-white text-gray-700
                       border-0 outline-none cursor-pointer focus:ring-2 focus:ring-white/50"
                onchange="document.getElementById('filterForm').submit()">
                @foreach ($bulanList as $num => $nama)
                    <option value="{{ $num }}" {{ $bulanTerpilihAngka === $num ? 'selected' : '' }}>
                        {{ $nama }}
                    </option>
                @endforeach
            </select>

            <select name="tahun"
                class="px-4 py-1.5 rounded-lg text-sm bg-white text-gray-700
                       border-0 outline-none cursor-pointer focus:ring-2 focus:ring-white/50"
                onchange="document.getElementById('filterForm').submit()">
                @for ($y = (int)date('Y'); $y >= (int)date('Y') - 4; $y--)
                    <option value="{{ $y }}" {{ $tahunTerpilih === $y ? 'selected' : '' }}>
                        {{ $y }}
                    </option>
                @endfor
            </select>

        </div>
    </div>

    {{-- ROW 1: Total Penjualan + Banner --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">

        <div class="bg-white rounded-2xl p-6 shadow-sm">
            <p class="text-xs text-gray-400 mb-1">Total Penjualan</p>
            <p class="text-2xl font-bold text-gray-800">
                Rp {{ number_format($totalPenjualan, 0, ',', '.') }}
            </p>
            <p class="text-xs text-gray-400 mt-1">{{ $bulanTerpilih }} {{ $tahunTerpilih }}</p>
        </div>

        <div class="bg-[#6B8F4E] rounded-2xl p-6 shadow-sm
                    flex items-center justify-between overflow-hidden relative">
            <div class="z-10">
                <p class="text-white font-bold text-lg leading-tight">
                    Lihat Laporan<br>
                    Penjualan <span class="opacity-75">{{ $bulanTerpilih }}</span>
                </p>
                <p class="text-white/60 text-xs mt-1">{{ $tahunTerpilih }}</p>
            </div>
            <div class="absolute right-0 top-0 h-full w-40 opacity-25"
                 style="background:url('{{ asset('images/banner-cake.jpg') }}') center/cover no-repeat">
            </div>
        </div>

    </div>

    {{-- ROW 2: KPI --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">

        <div class="bg-white rounded-2xl p-6 shadow-sm flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-[#BB9457]/10 flex items-center justify-center shrink-0">
                <i class="fas fa-clipboard-list text-[#BB9457] text-lg"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400 mb-1">Total Pesanan — {{ $bulanTerpilih }}</p>
                <p class="text-xl font-bold text-gray-800">
                    {{ number_format($totalPesanan, 0, ',', '.') }} Pesanan
                </p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-[#6B8F4E]/10 flex items-center justify-center shrink-0">
                <i class="fas fa-box text-[#6B8F4E] text-lg"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400 mb-1">Produk Terjual — {{ $bulanTerpilih }}</p>
                <p class="text-xl font-bold text-gray-800">
                    {{ number_format($totalProdukTerjual, 0, ',', '.') }} Produk
                </p>
            </div>
        </div>

    </div>

    {{-- ROW 3: Bar Charts --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">

        {{-- Bar: Pendapatan --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm">
            <p class="text-sm font-semibold text-gray-700">Perbandingan Pendapatan Bulanan</p>
            <p class="text-xs text-gray-400 mb-4">{{ $bulanSebelumnya }} vs {{ $bulanTerpilih }}</p>

            <div class="flex items-end gap-8 px-4" style="height:130px">

                <div class="flex flex-col items-center gap-1 flex-1 h-full">
                    <span class="text-[10px] font-semibold text-[#BB9457]">
                        Rp {{ number_format($pendapatanSebelumnya, 0, ',', '.') }}
                    </span>
                    <div class="w-full flex items-end flex-1">
                        <div class="w-full rounded-t-lg bg-[#BB9457] transition-all duration-500"
                             style="height:{{ $pctPendSebelum }}%"></div>
                    </div>
                    <span class="text-xs text-gray-400 text-center leading-tight">
                        {{ $bulanSebelumnya }}
                    </span>
                </div>

                <div class="flex flex-col items-center gap-1 flex-1 h-full">
                    <span class="text-[10px] font-semibold text-[#6B8F4E]">
                        Rp {{ number_format($pendapatanTerpilih, 0, ',', '.') }}
                    </span>
                    <div class="w-full flex items-end flex-1">
                        <div class="w-full rounded-t-lg bg-[#6B8F4E] transition-all duration-500"
                             style="height:{{ $pctPendTerpilih }}%"></div>
                    </div>
                    <span class="text-xs text-gray-400 text-center leading-tight">
                        {{ $bulanTerpilih }}
                    </span>
                </div>

            </div>
        </div>

        {{-- Bar: Jumlah Terjual + filter produk --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm">

            <div class="flex items-start justify-between gap-2 mb-1">
                <p class="text-sm font-semibold text-gray-700">Perbandingan Jumlah Terjual</p>
                <select name="produk_filter"
                    class="px-3 py-1 border border-gray-200 rounded-lg text-xs
                           text-gray-600 bg-gray-50 outline-none shrink-0"
                    onchange="document.getElementById('filterForm').submit()">
                    <option value="">Semua Produk</option>
                    @foreach ($produkTertaris as $p)
                        <option value="{{ $p['nama'] }}"
                            {{ $produkFilterDipilih === $p['nama'] ? 'selected' : '' }}>
                            {{ $p['nama'] }}
                        </option>
                    @endforeach
                </select>
            </div>

            <p class="text-xs text-gray-400 mb-4">
                {{ $bulanSebelumnya }} vs {{ $bulanTerpilih }}
                @if($produkFilterDipilih) — {{ $produkFilterDipilih }} @endif
            </p>

            <div class="flex items-end gap-8 px-4" style="height:130px">

                <div class="flex flex-col items-center gap-1 flex-1 h-full">
                    <span class="text-[10px] font-semibold text-[#BB9457]">
                        {{ number_format($jumlahSebelumnya) }} pcs
                    </span>
                    <div class="w-full flex items-end flex-1">
                        <div class="w-full rounded-t-lg bg-[#BB9457] transition-all duration-500"
                             style="height:{{ $pctJmlSebelum }}%"></div>
                    </div>
                    <span class="text-xs text-gray-400 text-center leading-tight">
                        {{ $bulanSebelumnya }}
                    </span>
                </div>

                <div class="flex flex-col items-center gap-1 flex-1 h-full">
                    <span class="text-[10px] font-semibold text-[#A8C17A]">
                        {{ number_format($jumlahTerpilih) }} pcs
                    </span>
                    <div class="w-full flex items-end flex-1">
                        <div class="w-full rounded-t-lg bg-[#A8C17A] transition-all duration-500"
                             style="height:{{ $pctJmlTerpilih }}%"></div>
                    </div>
                    <span class="text-xs text-gray-400 text-center leading-tight">
                        {{ $bulanTerpilih }}
                    </span>
                </div>

            </div>
        </div>

    </div>

</form>{{-- END filterForm --}}


{{-- ══ PRODUK TERLARIS ══ --}}
<div class="bg-white rounded-2xl p-6 shadow-sm mb-5">
    <p class="text-base font-bold text-gray-800 mb-1">Produk Terlaris</p>
    <p class="text-xs text-gray-400 mb-5">{{ $bulanTerpilih }} {{ $tahunTerpilih }}</p>

    <div class="flex flex-col md:flex-row items-center gap-8">

        {{-- Donut SVG --}}
        <div class="relative shrink-0" style="width:140px;height:140px">
            <svg viewBox="0 0 120 120" class="w-full h-full" style="transform:rotate(-90deg)">
                @foreach ($segments as $seg)
                <circle
                    cx="60" cy="60" r="{{ $r }}"
                    fill="none"
                    stroke="{{ $seg['warna'] }}"
                    stroke-width="12"
                    stroke-dasharray="{{ $seg['dash'] }} {{ $seg['gap'] }}"
                    stroke-dashoffset="{{ $seg['offset'] }}"
                    stroke-linecap="butt"
                />
                @endforeach
            </svg>
            <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                <span class="text-sm font-bold text-gray-700">{{ $segments[0]['persen'] }}%</span>
                <span class="text-[9px] text-gray-400 text-center leading-tight px-2">
                    {{ $segments[0]['nama'] }}
                </span>
            </div>
        </div>

        {{-- Legenda --}}
        <div class="flex flex-col gap-2.5 flex-1">
            @foreach ($produkTertaris as $p)
            <div class="flex items-center justify-between text-sm">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-sm shrink-0" style="background:{{ $p['warna'] }}"></span>
                    <span class="text-gray-700">{{ $p['nama'] }}</span>
                </div>
                <span class="text-xs font-semibold text-gray-500">{{ $p['persen'] }}%</span>
            </div>
            @endforeach
        </div>

    </div>

    {{-- Slider --}}
    <div class="mt-6">
        <div class="flex items-center gap-3">

            <button type="button" onclick="slideLeft()"
                class="shrink-0 w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200
                       flex items-center justify-center transition cursor-pointer">
                <i class="fas fa-chevron-left text-gray-500 text-xs"></i>
            </button>

            <div class="flex-1 overflow-hidden">
                <div class="flex gap-3 transition-transform duration-300 ease-in-out"
                     id="sliderInner">
                    @foreach ($sliderProduk as $sp)
                    <div class="shrink-0 relative rounded-xl overflow-hidden bg-gray-100"
                         style="width:140px;height:90px">
                        <img src="{{ asset($sp['gambar']) }}"
                             alt="{{ $sp['nama'] }}"
                             class="w-full h-full object-cover"
                             onerror="this.style.display='none'">
                        <div class="absolute inset-0
                                    bg-gradient-to-t from-black/60 to-black/10
                                    flex flex-col items-center justify-end pb-3">
                            <p class="text-white text-xs font-semibold">{{ $sp['nama'] }}</p>
                            <p class="text-white text-sm font-bold">{{ $sp['persen'] }}%</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <button type="button" onclick="slideRight()"
                class="shrink-0 w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200
                       flex items-center justify-center transition cursor-pointer">
                <i class="fas fa-chevron-right text-gray-500 text-xs"></i>
            </button>

        </div>
    </div>
</div>


{{-- ══ TABEL PENJUALAN ══ --}}
<div class="bg-white rounded-2xl p-6 shadow-sm">

    <div class="flex flex-wrap items-center justify-between border-b pb-4 mb-4 gap-3">
        <div>
            <h2 class="text-base font-semibold text-gray-800">Tabel Penjualan</h2>
            <p class="text-xs text-gray-400 mt-0.5">{{ $bulanTerpilih }} {{ $tahunTerpilih }}</p>
        </div>
        {{--
            Sambungkan href ke route ekspor backend:
            route('admin.laporan.ekspor', ['bulan'=>$bulanTerpilihAngka,'tahun'=>$tahunTerpilih])
        --}}
        <a href="#"
           class="flex items-center gap-2 px-4 py-2 bg-[#BB9457] text-white text-xs
                  font-semibold rounded-lg hover:bg-[#a07d45] transition">
            <i class="fas fa-file-arrow-down"></i> Ekspor
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-gray-400 text-xs tracking-wide">
                    <th class="py-3 px-3 text-left font-medium">Id Pesanan</th>
                    <th class="py-3 px-3 text-left font-medium">Nama Pelanggan</th>
                    <th class="py-3 px-3 text-left font-medium">Nama Produk</th>
                    <th class="py-3 px-3 text-left font-medium">Tgl Pembelian</th>
                    <th class="py-3 px-3 text-left font-medium">Tgl Pengantaran</th>
                    <th class="py-3 px-3 text-left font-medium">Total Harga</th>
                    <th class="py-3 px-3 text-left font-medium">Status</th>
                    <th class="py-3 px-3 text-left font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse ($tabelPenjualan as $item)
                <tr class="hover:bg-gray-50/70 transition">
                    <td class="px-3 py-3 text-gray-600 text-xs font-mono">{{ $item->id_pesanan }}</td>
                    <td class="px-3 py-3 text-gray-700">{{ $item->nama_pelanggan }}</td>
                    <td class="px-3 py-3 text-gray-700">{{ $item->nama_produk }}</td>
                    <td class="px-3 py-3 text-gray-400 text-xs">{{ $item->tanggal_pembelian }}</td>
                    <td class="px-3 py-3 text-gray-400 text-xs">{{ $item->tanggal_pengantaran }}</td>
                    <td class="px-3 py-3 text-gray-700 font-medium">
                        Rp {{ number_format($item->total_harga, 0, ',', '.') }}
                    </td>
                    <td class="px-3 py-3">
                        @php
                            $badgeClass = match($item->status) {
                                'Selesai'    => 'bg-green-100 text-green-700',
                                'Dikirim'    => 'bg-blue-100 text-blue-700',
                                'Dikerjakan' => 'bg-yellow-100 text-yellow-700',
                                'Dibatalkan' => 'bg-red-100 text-red-700',
                                default      => 'bg-gray-100 text-gray-600',
                            };
                        @endphp
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $badgeClass }}">
                            {{ $item->status }}
                        </span>
                    </td>
                    <td class="px-3 py-3">
                        <div class="flex items-center gap-2">
                            {{-- Sambungkan ke route DELETE backend --}}
                            <button type="button"
                                onclick="confirmHapus('{{ $item->id_pesanan }}')"
                                class="w-7 h-7 flex items-center justify-center rounded-lg
                                       bg-red-50 text-red-400 hover:bg-red-100 hover:text-red-600
                                       hover:scale-110 transition" title="Hapus">
                                <i class="fas fa-trash-can text-xs"></i>
                            </button>
                            {{-- Sambungkan ke route edit backend --}}
                            <a href="#"
                               class="w-7 h-7 flex items-center justify-center rounded-lg
                                      bg-yellow-50 text-yellow-400 hover:bg-yellow-100 hover:text-yellow-600
                                      hover:scale-110 transition" title="Edit">
                                <i class="fas fa-pen-to-square text-xs"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-12 text-gray-400 text-sm">
                        <i class="fas fa-inbox text-3xl mb-3 block opacity-30"></i>
                        Belum ada data laporan untuk bulan ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{--
        Aktifkan pagination saat $tabelPenjualan sudah pakai Laravel Paginator:
        {{ $tabelPenjualan->appends(request()->query())->links() }}
    --}}

</div>


{{-- Form DELETE tersembunyi — sambungkan action ke route backend --}}
<form id="formHapus" method="POST" action="#" style="display:none">
    @csrf
    @method('DELETE')
</form>


<script>
// ── Slider ──
let sliderPos = 0;
const CARD_W  = 140 + 12; // card width + gap

function getSliderMax() {
    const inner   = document.getElementById('sliderInner');
    const wrapper = inner.parentElement;
    const total   = inner.children.length;
    const visible = Math.floor(wrapper.offsetWidth / CARD_W);
    return Math.max(0, (total - visible) * CARD_W);
}

function slideLeft() {
    sliderPos = Math.max(0, sliderPos - CARD_W);
    document.getElementById('sliderInner').style.transform = `translateX(-${sliderPos}px)`;
}

function slideRight() {
    sliderPos = Math.min(getSliderMax(), sliderPos + CARD_W);
    document.getElementById('sliderInner').style.transform = `translateX(-${sliderPos}px)`;
}

// ── Konfirmasi hapus & kirim DELETE ke backend ──
function confirmHapus(id) {
    if (!confirm('Yakin ingin menghapus pesanan ' + id + '?')) return;
    const form   = document.getElementById('formHapus');
    // Ganti path sesuai route backend:
    // form.action = '/admin/laporan/' + id;
    // form.submit();
    alert('Sambungkan form action ke route DELETE: ' + id);
}
</script>

@endsection