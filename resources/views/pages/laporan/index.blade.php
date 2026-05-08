@extends('layouts.admin')

@section('title', 'Laporan Penjualan | lanina')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

@php
// ══════════════════════════════════════════════════════════════
// Data dari LaporanController - Real database data
// ══════════════════════════════════════════════════════════════

$bulanList = [
    1=>'Januari', 2=>'Februari', 3=>'Maret',    4=>'April',
    5=>'Mei',     6=>'Juni',     7=>'Juli',      8=>'Agustus',
    9=>'September',10=>'Oktober',11=>'November', 12=>'Desember',
];

// Ambil filter dari query string (GET), default bulan dan tahun sekarang
$bulanTerpilihAngka  = (int) request('bulan',  now()->month);
$tahunTerpilih       = (int) request('tahun',   now()->year);
$produkFilterDipilih = request('produk_filter', '');

// Pastikan nilai bulan valid
if (!array_key_exists($bulanTerpilihAngka, $bulanList)) {
    $bulanTerpilihAngka = now()->month;
}

$bulanTerpilih   = $bulanList[$bulanTerpilihAngka];
$bulanSebelumnya = $bulanTerpilihAngka === 1
    ? $bulanList[12]
    : $bulanList[$bulanTerpilihAngka - 1];

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
$totalPersen   = array_sum(array_column($produkTerlaris, 'persen'));
$offsetAcc     = 0;
$segments      = [];
foreach ($produkTerlaris as $p) {
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
                    @foreach ($produkTerlaris as $p)
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

    @if(empty($produkTerlaris) || count($produkTerlaris) === 0)
        {{-- Empty State --}}
        <div class="flex flex-col items-center justify-center py-12 text-gray-400">
            <svg class="w-16 h-16 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
            </svg>
            <p class="text-sm font-medium">Belum ada data penjualan</p>
            <p class="text-xs mt-1">Pilih bulan lain atau tunggu transaksi masuk</p>
        </div>
    @else
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
                @foreach ($produkTerlaris as $p)
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
    @endif
</div>


{{-- ══ TABEL PENJUALAN ══ --}}
<div class="bg-white rounded-2xl p-6 shadow-sm">

    <div class="flex flex-wrap items-center justify-between border-b pb-4 mb-4 gap-3">
        <div>
            <h2 class="text-base font-semibold text-gray-800">Tabel Penjualan</h2>
            <p class="text-xs text-gray-400 mt-0.5">{{ $bulanTerpilih }} {{ $tahunTerpilih }}</p>
        </div>
        {{-- Export PDF Button - Connected to LaporanController@exportPdf --}}
        <a href="{{ route('laporan.export-pdf', ['bulan' => $bulanTerpilihAngka, 'tahun' => $tahunTerpilih]) }}"
           class="flex items-center gap-2 px-4 py-2 bg-[#BB9457] text-white text-xs
                  font-semibold rounded-lg hover:bg-[#a07d45] transition">
            <i class="fas fa-file-arrow-down"></i> Ekspor PDF
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
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-12 text-gray-400 text-sm">
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


<script>
// ── Slider ──
let sliderPos = 0;
const CARD_W  = 140 + 12; // card width + gap

function getSliderMax() {
    const inner   = document.getElementById('sliderInner');
    if (!inner) return 0;
    const wrapper = inner.parentElement;
    const total   = inner.children.length;
    const visible = Math.floor(wrapper.offsetWidth / CARD_W);
    return Math.max(0, (total - visible) * CARD_W);
}

function slideLeft() {
    sliderPos = Math.max(0, sliderPos - CARD_W);
    const inner = document.getElementById('sliderInner');
    if (inner) {
        inner.style.transform = `translateX(-${sliderPos}px)`;
    }
}

function slideRight() {
    sliderPos = Math.min(getSliderMax(), sliderPos + CARD_W);
    const inner = document.getElementById('sliderInner');
    if (inner) {
        inner.style.transform = `translateX(-${sliderPos}px)`;
    }
}
</script>

@endsection