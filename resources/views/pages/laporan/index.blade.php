@extends('layouts.admin')

@section('title', 'Laporan Penjualan | lanina')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


{{-- ══════════════ HALAMAN ══════════════ --}}
<h1 class="text-2xl font-bold text-gray-800 mb-6">Laporan Penjualan</h1>

{{-- ══ FILTER FORM (GET) ══ --}}
<form method="GET" action="{{ url()->current() }}" id="filterForm">

    {{-- ── Filter Bar ── --}}
    <div class="bg-[#6A7941] rounded-2xl px-6 py-4 mb-6
                flex items-center justify-between">
        <span class="text-white font-semibold text-xl">Filter Bulanan</span>

    <div class="relative inline-block group">

    <select name="bulan"
        class="px-5 py-1.5 pr-10 rounded-full text-sm
               bg-[#6A7941] text-white
               border border-gray/30 outline-none cursor-pointer
               focus:ring-2 focus:ring-white/50
               hover:bg-white hover:text-[#6A7941]
               transition duration-200
               appearance-none"

        onchange="document.getElementById('filterForm').submit()">

        @foreach ($bulanList as $num => $nama)
            <option value="{{ $num }}"
                class="bg-white text-gray-700"
                {{ $bulanTerpilihAngka === $num ? 'selected' : '' }}>
                {{ $nama }}
            </option>
        @endforeach

    </select>

    {{-- ICON PANAH --}}
    <div class="pointer-events-none absolute inset-y-0 right-3
                flex items-center text-white
                group-hover:text-[#6A7941]">

        <svg xmlns="http://www.w3.org/2000/svg"
            class="w-4 h-4"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            stroke-width="2.5">

            <path stroke-linecap="round"
                stroke-linejoin="round"
                d="M19 9l-7 7-7-7" />

        </svg>

    </div>

</div>

        {{-- Hidden: kirim tahun supaya tidak hilang saat filter bulan --}}
        <input type="hidden" name="tahun" value="{{ $tahunTerpilih }}">
        <input type="hidden" name="produk_filter" value="{{ $produkFilterDipilih }}">
    </div>

    {{-- ── ROW 1: Total Penjualan + Banner ── --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">

        {{-- Total Penjualan --}}
        <div class="bg-[#BB9457] rounded-2xl p-6 shadow-sm">
            <p class="text-sm text-white/70 mb-1">Total Penjualan</p>
            <p class="text-3xl font-bold text-white mt-2">
                Rp {{ number_format($totalPenjualan, 0, ',', '.') }}
            </p>
        </div>

        {{-- Banner --}}
        <div class="rounded-2xl overflow-hidden relative shadow-sm"
             style="background:url('{{ asset('images/about.png') }}') center/cover no-repeat;
                    min-height:130px;">
            <div class="absolute inset-0 bg-black/30"></div>
            <div class="relative z-10 p-6 h-full flex flex-col justify-center">
                <p class="text-white font-bold text-xl leading-snug">
                    Lihat Laporan<br>Penjualan mu!
                </p>
            </div>
        </div>

    </div>

    {{-- ── ROW 2: KPI ── --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">

        {{-- Total Pesanan --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0"
                 style="background:rgba(187,148,87,0.12);">
                <i class="fas fa-clipboard-list text-xl" style="color:#BB9457;"></i>
            </div>
            <div>
                <p class="text-sm mb-1" style="color:#BB9457; font-weight:500;">Total Pesanan</p>
                <p class="text-2xl font-bold text-gray-800">
                    {{ number_format($totalPesanan, 0, ',', '.') }} Pesanan
                </p>
            </div>
        </div>

        {{-- Produk Terjual --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0"
                 style="background:rgba(107,143,78,0.12);">
                <i class="fas fa-box text-xl" style="color:#6B8F4E;"></i>
            </div>
            <div>
                <p class="text-sm mb-1" style="color:#6B8F4E; font-weight:500;">Produk Terjual</p>
                <p class="text-2xl font-bold text-gray-800">
                    {{ number_format($totalProdukTerjual, 0, ',', '.') }} Produk
                </p>
            </div>
        </div>

    </div>

    {{-- ── ROW 3: Bar Charts ── --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">

        {{-- Bar: Perbandingan Pendapatan --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm">
            <p class="text-sm font-semibold text-gray-800 mb-0.5">Perbandingan Pendapatan Bulanan</p>
            <p class="text-xs text-gray-400 mb-5">
                {{ $bulanSebelumnya }} vs {{ $bulanTerpilih }}
            </p>

            <div class="flex items-end gap-6 px-2" style="height:140px;">

                <div class="flex flex-col items-center gap-1 flex-1 h-full">
                    <span class="text-[10px] font-semibold text-[#BB9457]">
                        Rp {{ number_format($pendapatanSebelumnya, 0, ',', '.') }}
                    </span>
                    <div class="w-full flex items-end flex-1">
                        <div class="w-full rounded-t-lg bg-[#BB9457] transition-all duration-500"
                             style="height:{{ $pctPendSebelum }}%"></div>
                    </div>
                    <span class="text-[11px] text-gray-400 text-center leading-tight">Bulan Sebelumnya</span>
                    <span class="text-[11px] font-bold text-gray-700 text-center">{{ $bulanSebelumnya }}</span>
                </div>

                <div class="flex flex-col items-center gap-1 flex-1 h-full">
                    <span class="text-[10px] font-semibold text-[#6B8F4E]">
                        Rp {{ number_format($pendapatanTerpilih, 0, ',', '.') }}
                    </span>
                    <div class="w-full flex items-end flex-1">
                        <div class="w-full rounded-t-lg bg-[#BB9457] transition-all duration-500"
                             style="height:{{ $pctPendTerpilih }}%"></div>
                    </div>
                    <span class="text-[11px] text-gray-400 text-center leading-tight">Bulan Terpilih</span>
                    <span class="text-[11px] font-bold text-gray-700 text-center">{{ $bulanTerpilih }}</span>
                </div>

            </div>
        </div>

        {{-- Bar: Perbandingan Jumlah Terjual + filter produk --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm">

            <div class="flex items-start justify-between gap-2 mb-0.5">
                <p class="text-sm font-semibold text-gray-800">Perbandingan Jumlah Terjual Bulanan</p>
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

            <p class="text-xs text-gray-400 mb-5">
                {{ $bulanSebelumnya }} vs {{ $bulanTerpilih }}
                @if($produkFilterDipilih) — {{ $produkFilterDipilih }} @endif
            </p>

            <div class="flex items-end gap-6 px-2" style="height:140px;">

                <div class="flex flex-col items-center gap-1 flex-1 h-full">
                    <span class="text-[10px] font-semibold text-[#BB9457]">
                        {{ number_format($jumlahSebelumnya) }} pcs
                    </span>
                    <div class="w-full flex items-end flex-1">
                        <div class="w-full rounded-t-lg bg-[#A8C17A] transition-all duration-500"
                             style="height:{{ $pctJmlSebelum }}%"></div>
                    </div>
                    <span class="text-[11px] text-gray-400 text-center leading-tight">Bulan Sebelumnya</span>
                    <span class="text-[11px] font-bold text-gray-700 text-center">{{ $bulanSebelumnya }}</span>
                </div>

                <div class="flex flex-col items-center gap-1 flex-1 h-full">
                    <span class="text-[10px] font-semibold text-[#A8C17A]">
                        {{ number_format($jumlahTerpilih) }} pcs
                    </span>
                    <div class="w-full flex items-end flex-1">
                        <div class="w-full rounded-t-lg bg-[#A8C17A] transition-all duration-500"
                             style="height:{{ $pctJmlTerpilih }}%"></div>
                    </div>
                    <span class="text-[11px] text-gray-400 text-center leading-tight">Bulan Terpilih</span>
                    <span class="text-[11px] font-bold text-gray-700 text-center">{{ $bulanTerpilih }}</span>
                </div>

            </div>
        </div>

    </div>

</form>{{-- END filterForm --}}


{{-- ═════════════ PRODUK TERLARIS ═════════════ --}}
<div class="mb-6">

    <h2 class="text-[23px] font-bold text-[#2B2B2B] mb-5">Produk Terlaris</h2>

    {{-- DONUT + LEGEND --}}
    <div class="bg-[#F9F9F7] rounded-[20px] shadow-sm px-10 py-8">
        <div class="flex flex-col lg:flex-row items-center justify-center gap-24">

            {{-- DONUT CHART --}}
            {{-- circumference = 2 * π * 38 ≈ 238.76 --}}
            <div class="relative w-[240px] h-[240px] shrink-0">

                <svg viewBox="0 0 120 120" class="w-full h-full -rotate-90">

                    {{-- NASTAR 45.8% → dash=109.35 --}}
                    <circle cx="60" cy="60" r="38" fill="none"
                        stroke="#996633" stroke-width="21"
                        stroke-dasharray="109.35 129.41"
                        stroke-dashoffset="0"/>

                    {{-- STICK KEJU 29.3% → dash=69.96 --}}
                    <circle cx="60" cy="60" r="38" fill="none"
                        stroke="#A7B86A" stroke-width="21"
                        stroke-dasharray="69.96 168.8"
                        stroke-dashoffset="-109.35"/>

                    {{-- LIDAH KUCING 8.3% → dash=19.82 --}}
                    <circle cx="60" cy="60" r="38" fill="none"
                        stroke="#E9E1CC" stroke-width="21"
                        stroke-dasharray="19.82 218.94"
                        stroke-dashoffset="-179.31"/>

                    {{-- COOKIES 8.3% → dash=19.82 --}}
                    <circle cx="60" cy="60" r="38" fill="none"
                        stroke="#D8DEB4" stroke-width="21"
                        stroke-dasharray="19.82 218.94"
                        stroke-dashoffset="-199.13"/>

                    {{-- PUTRI SALJU 8.3% → dash=19.82 --}}
                    <circle cx="60" cy="60" r="38" fill="none"
                        stroke="#C48C5A" stroke-width="21"
                        stroke-dasharray="19.82 218.94"
                        stroke-dashoffset="-218.95"/>

                </svg>

                {{-- LUBANG TENGAH --}}
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="w-[64px] h-[64px] rounded-full bg-[#F9F9F7]"></div>
                </div>

                {{-- LABEL PERSENTASE --}}
                {{-- Nastar — tengah segmen ~22.9% dari kanan bawah --}}
                <span class="absolute text-white font-bold"
                    style="font-size:10px;left:124px;top:122px;
                           transform:rotate(12deg);transform-origin:left center;white-space:nowrap;">
                </span>
                {{-- Stick Keju — segmen hijau atas kiri --}}
                <span class="absolute text-white font-bold"
                    style="font-size:9px;left:36px;top:48px;
                           transform:rotate(22deg);transform-origin:left center;white-space:nowrap;">
                </span>
                {{-- Lidah Kucing --}}
                <span class="absolute text-white font-bold"
                    style="font-size:8px;left:8px;top:96px;
                           transform:rotate(78deg);transform-origin:left center;white-space:nowrap;">
                </span>
                {{-- Cookies --}}
                <span class="absolute text-white font-bold"
                    style="font-size:8px;left:22px;top:150px;
                           transform:rotate(52deg);transform-origin:left center;white-space:nowrap;">
                </span>

            </div>

            {{-- LEGEND --}}
            <div class="flex flex-col gap-[14px]">
                <div class="flex items-center gap-5">
                    <div class="w-[72px] h-[18px] rounded-[2px]" style="background:#996633;"></div>
                    <span class="text-[15px] font-semibold text-[#332A24]">Nastar</span>
                </div>
                <div class="flex items-center gap-5">
                    <div class="w-[72px] h-[18px] rounded-[2px]" style="background:#C48C5A;"></div>
                    <span class="text-[15px] font-semibold text-[#332A24]">Putri Salju</span>
                </div>
                <div class="flex items-center gap-5">
                    <div class="w-[72px] h-[18px] rounded-[2px]" style="background:#E9E1CC;"></div>
                    <span class="text-[15px] font-semibold text-[#332A24]">Lidah Kucing</span>
                </div>
                <div class="flex items-center gap-5">
                    <div class="w-[72px] h-[18px] rounded-[2px]" style="background:#D8DEB4;"></div>
                    <span class="text-[15px] font-semibold text-[#332A24]">Cookies</span>
                </div>
                <div class="flex items-center gap-5">
                    <div class="w-[72px] h-[18px] rounded-[2px]" style="background:#A7B86A;"></div>
                    <span class="text-[15px] font-semibold text-[#332A24]">Stick Keju</span>
                </div>
            </div>

        </div>
    </div>

    {{-- ── SLIDER ── --}}
    <div class="relative mt-5">

        {{-- TOMBOL KIRI --}}
        <button
            type="button"
            onclick="slideLeft()"
            class="absolute left-[-22px] top-1/2 -translate-y-1/2 z-20
                   w-[44px] h-[44px] rounded-full
                   flex items-center justify-center transition"
            style="background:rgba(141,141,135,0.85);">
            <i class="fas fa-arrow-left text-white text-sm"></i>
        </button>

        {{-- TOMBOL KANAN --}}
        <button
            type="button"
            onclick="slideRight()"
            class="absolute right-[-22px] top-1/2 -translate-y-1/2 z-20
                   w-[44px] h-[44px] rounded-full
                   flex items-center justify-center transition"
            style="background:rgba(141,141,135,0.85);">
            <i class="fas fa-arrow-right text-white text-sm"></i>
        </button>

        {{-- OVERFLOW WRAPPER --}}
        <div class="overflow-hidden">

            {{-- INNER --}}
            <div id="sliderInner"
                 class="flex gap-[14px] transition-transform duration-300 ease-in-out">

                @foreach ($sliderProduk as $sp)
                <div class="min-w-[300px] bg-[#F9F9F7] rounded-[18px] shadow-sm
                             flex items-stretch shrink-0 overflow-hidden">

                    {{-- GAMBAR --}}
                    <div class="w-[140px] h-full shrink-0 overflow-hidden rounded-l-[18px]">
                        <img src="{{ asset($sp['gambar']) }}"
                            alt="{{ $sp['nama'] }}"
                            class="w-full h-full object-cover rounded-l-[18px]">
                    </div>

                    {{-- KONTEN --}}
                    <div class="flex-1 flex flex-col justify-center px-4 py-4 gap-3">

                        <h3 class="text-[16px] font-bold text-[#5A3C2B] leading-tight">
                            {{ $sp['nama'] }}
                        </h3>

                        <div class="h-[38px] rounded-[10px] bg-[#A06C3A]
                                    flex items-center justify-center">
                            <span class="text-white font-bold text-sm">
                                {{ $sp['persen'] }}%
                            </span>
                        </div>

                    </div>

                </div>
                @endforeach

            </div>

        </div>

    </div>

</div>


{{-- ═════════════ TABEL LAPORAN PENJUALAN ═════════════ --}}
<div class="mt-8">

    <h2 class="text-[23px] font-bold text-[#2B2B2B] mb-5">
        Tabel Laporan Penjualan
    </h2>

    <div class="bg-white rounded-[24px] shadow-sm overflow-hidden">

        <div class="bg-white rounded-2xl p-6 shadow-sm">

            {{-- HEADER --}}
            <div class="flex items-center justify-between border-b pb-4 mb-4">
                <h2 class="text-lg font-semibold text-gray-800">
                    Tabel Penjualan
                </h2>

                <select id="statusFilter"
                    onchange="filterTable()"
                    class="px-3 py-1.5 border border-gray-200 rounded-lg text-sm
                           bg-gray-50 focus:ring-2 focus:ring-[#BB9457] outline-none">

                    <option value="">All</option>
                    <option value="Selesai">Selesai</option>
                    <option value="Dikirim">Dikirim</option>
                    <option value="Dikerjakan">Dikerjakan</option>
                    <option value="Belum">Belum</option>

                </select>
            </div>

            {{-- TABLE --}}
            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    {{-- HEAD --}}
                    <thead>
                        <tr class="text-gray-500 tracking-wider border-b">
                            <th class="py-3 px-4 text-left">Id Pesanan</th>
                            <th class="py-3 px-4 text-left">Nama Pelanggan</th>
                            <th class="py-3 px-4 text-left">Nama Produk</th>
                            <th class="py-3 px-4 text-left">Tanggal Pembelian</th>
                            <th class="py-3 px-4 text-left">Tanggal Pengantaran</th>
                            <th class="py-3 px-4 text-left">Total Harga</th>
                            <th class="py-3 px-4 text-left">Status</th>
                            <th class="py-3 px-4 text-left">Aksi</th>
                        </tr>
                    </thead>

                    {{-- BODY --}}
                    <tbody class="divide-y">

                        @forelse ($tabelPenjualan as $item)

                        @php
                            $tglPembelian = \Carbon\Carbon::createFromFormat(
                                'd/m/y',
                                $item->tanggal_pembelian
                            );

                            $tglPengantaran = \Carbon\Carbon::createFromFormat(
                                'd/m/y',
                                $item->tanggal_pengantaran
                            );
                        @endphp

                        <tr
                            data-id="{{ $item->id }}"
                            data-status="{{ $item->status }}"
                            class="hover:bg-gray-50 transition">

                            {{-- ID --}}
                            <td class="px-4 py-3">
                                {{ $item->id_pesanan }}
                            </td>

                            {{-- PELANGGAN --}}
                            <td class="px-4 py-3">
                                {{ $item->nama_pelanggan }}
                            </td>

                            {{-- PRODUK --}}
                            <td class="px-4 py-3">
                                {{ $item->nama_produk }}
                            </td>

                            {{-- TGL PEMBELIAN --}}
                            <td class="px-4 py-3">
                                {{ $tglPembelian->format('d/m/y') }}
                            </td>

                            {{-- TGL PENGANTARAN --}}
                            <td class="px-4 py-3">
                                {{ $tglPengantaran->format('d/m/y') }}
                            </td>

                            {{-- TOTAL --}}
                            <td class="px-4 py-3">
                                Rp {{ number_format($item->total_harga, 0, ',', '.') }}
                            </td>

                            {{-- STATUS --}}
                            <td class="px-4 py-3">

                                <button
                                    class="px-3 py-1 text-xs font-semibold rounded-full text-white

                                    {{ $item->status == 'Selesai'
                                        ? 'bg-green-500'
                                        : ($item->status == 'Dikirim'
                                        ? 'bg-blue-500'
                                        : ($item->status == 'Dikerjakan'
                                        ? 'bg-yellow-500'
                                        : 'bg-red-500')) }}">

                                    {{ $item->status }}

                                </button>

                            </td>

                            {{-- AKSI --}}
                            <td class="px-4 py-3">

                                <div class="flex items-center gap-3">

                                    {{-- HAPUS --}}
                                    <form
                                        action="{{ route('pesanan.destroy', $item->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Yakin hapus?')">

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="text-red-500 hover:scale-110 transition">

                                            <i class="fas fa-trash-can"></i>

                                        </button>

                                    </form>

                                    {{-- VIEW --}}
                                    <button
                                        onclick="openViewModal(this)"
                                            data-id_pesanan="{{ $item->id_pesanan }}"
                                            data-tanggal_pembelian="{{ $item->tanggal_pembelian }}"
                                            data-tanggal_pengantaran="{{ $item->tanggal_pengantaran }}"
                                            data-status="{{ $item->status }}"
                                            data-total_harga="{{ number_format($item->total_harga,0,',','.') }}"
                                            data-nama_pelanggan="{{ $item->nama_pelanggan }}"
                                            data-produk="{{ $item->nama_produk }}"
                                        class="text-cyan-500 hover:scale-110 transition">

                                        <i class="fas fa-eye"></i>

                                    </button>

                                </div>

                            </td>

                        </tr>

                        @empty

                        <tr>
                            <td colspan="8"
                                class="text-center py-10 text-gray-400">

                                Belum ada data pesanan.

                            </td>
                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>
    </div>

</div>


{{-- ═════════════ MODAL VIEW ═════════════ --}}
<div id="viewModal"
    class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">

    <div class="bg-white w-[700px] max-w-[95vw]
                max-h-[90vh] overflow-y-auto
                rounded-2xl p-8 shadow-lg relative">

        {{-- CLOSE --}}
        <button onclick="closeViewModal()"
            class="absolute top-4 right-4 w-8 h-8
                   bg-gray-100 hover:bg-gray-200
                   rounded-full flex items-center justify-center transition">

            <span class="text-gray-600 font-bold text-sm">X</span>

        </button>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

            {{-- KIRI --}}
            <div class="space-y-8">

                <div>
                    <h3 class="text-lg font-bold text-[#432818] mb-4">
                        Informasi Pesanan
                    </h3>

                    <div class="space-y-2 text-sm text-gray-700">

                        <div class="flex gap-2">
                            <span class="w-44 text-gray-500">ID Pesanan</span>
                            <span>: <span id="view_id_pesanan">-</span></span>
                        </div>

                        <div class="flex gap-2">
                            <span class="w-44 text-gray-500">Tanggal Pembelian</span>
                            <span>: <span id="view_tanggal_pembelian">-</span></span>
                        </div>

                        <div class="flex gap-2">
                            <span class="w-44 text-gray-500">Tanggal Pengantaran</span>
                            <span>: <span id="view_tanggal_pengantaran">-</span></span>
                        </div>

                        <div class="flex gap-2">
                            <span class="w-44 text-gray-500">Status</span>
                            <span>: <span id="view_status">-</span></span>
                        </div>

                        <div class="flex gap-2">
                            <span class="w-44 text-gray-500">Total Harga</span>
                            <span>: Rp <span id="view_total_harga">-</span></span>
                        </div>

                    </div>
                </div>

            </div>

            {{-- KANAN --}}
            <div class="space-y-8">

                <div>
                    <h3 class="text-lg font-bold text-[#432818] mb-4">
                        Informasi Pelanggan
                    </h3>

                    <div class="space-y-2 text-sm text-gray-700">

                        <div class="flex gap-2">
                            <span class="w-44 text-gray-500">Nama Pelanggan</span>
                            <span>: <span id="view_nama_pelanggan">-</span></span>
                        </div>

                        <div class="flex gap-2">
                            <span class="w-44 text-gray-500">Produk</span>
                            <span>: <span id="view_produk">-</span></span>
                        </div>

                    </div>
                </div>

            </div>

        </div>

    </div>

</div>


{{-- ═════════════ SCRIPT ═════════════ --}}
<script>

    // ═════════ SLIDER ═════════
    let sliderPos = 0;
    const CARD_WIDTH = 314;

    function getMaxSlide() {
        const inner = document.getElementById('sliderInner');
        return inner.scrollWidth - inner.parentElement.clientWidth;
    }

    function slideRight() {
        sliderPos = Math.min(sliderPos + CARD_WIDTH, getMaxSlide());

        document.getElementById('sliderInner').style.transform =
            `translateX(-${sliderPos}px)`;
    }

    function slideLeft() {
        sliderPos = Math.max(sliderPos - CARD_WIDTH, 0);

        document.getElementById('sliderInner').style.transform =
            `translateX(-${sliderPos}px)`;
    }


    // ═════════ FILTER TABLE ═════════
    function filterTable() {

        let value = document
            .getElementById('statusFilter')
            .value
            .toLowerCase();

        let rows = document.querySelectorAll('tbody tr');

        rows.forEach(row => {

            let status = row.getAttribute('data-status');

            if (!status) return;

            status = status.toLowerCase();

            if (value === '' || status.includes(value)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }

        });
    }


    // ═════════ MODAL VIEW ═════════
    function openViewModal(button) {

    document.getElementById('view_id_pesanan').innerText =
        button.dataset.id_pesanan;

    document.getElementById('view_tanggal_pembelian').innerText =
        button.dataset.tanggal_pembelian;

    document.getElementById('view_tanggal_pengantaran').innerText =
        button.dataset.tanggal_pengantaran;

    document.getElementById('view_status').innerText =
        button.dataset.status;

    document.getElementById('view_total_harga').innerText =
        button.dataset.total_harga;

    document.getElementById('view_nama_pelanggan').innerText =
        button.dataset.nama_pelanggan;

    document.getElementById('view_produk').innerText =
        button.dataset.produk;

    document.getElementById('viewModal').classList.remove('hidden');
    document.getElementById('viewModal').classList.add('flex');
}
function closeViewModal() {

        document.getElementById('viewModal').classList.remove('flex');
        document.getElementById('viewModal').classList.add('hidden');

    }
</script>

@endsection