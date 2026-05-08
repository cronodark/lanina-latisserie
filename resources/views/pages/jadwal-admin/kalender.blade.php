@extends('layouts.admin')

@section('title', 'Kalender Pesanan | lanina')

@section('content')

@php
    $namaBulan = [
        1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',
        5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',
        9=>'September',10=>'Oktober',11=>'November',12=>'Desember'
    ];

    $prevBulan = $bulan == 1 ? 12 : $bulan - 1;
    $prevTahun = $bulan == 1 ? $tahun - 1 : $tahun;
    $nextBulan = $bulan == 12 ? 1 : $bulan + 1;
    $nextTahun = $bulan == 12 ? $tahun + 1 : $tahun;

    $hariDalamBulan = \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->daysInMonth;
    $hariPertama    = \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->dayOfWeek;
    $today          = now()->startOfDay();
@endphp

<div x-data="{
    modal: false,
    selectedDate: '',
    selectedOrders: [],
    openDay(date, orders) {
        if (!orders || !orders.length) return;
        this.selectedDate = date;
        this.selectedOrders = orders;
        this.modal = true;
    }
}">

{{-- Header --}}
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Kalender Pesanan</h1>
    <p class="text-sm text-gray-500 mt-1">Klik tanggal untuk melihat detail pesanan & kue</p>
</div>

{{-- Stats --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Total Pesanan Bulan Ini</p>
        <h3 class="text-3xl font-bold mt-2 text-gray-800">{{ $totalPesanan }}</h3>
        <p class="text-xs text-[#BB9457] mt-1 font-medium">{{ $namaBulan[$bulan] }} {{ $tahun }}</p>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Tanggal Paling Ramai</p>
        <h3 class="text-2xl font-bold mt-2 text-gray-800">{{ $tanggalRamaiFmt }}</h3>
        <p class="text-xs text-gray-400 mt-1">Terbanyak pesanan</p>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Slot Tersedia</p>
        <h3 class="text-3xl font-bold mt-2 text-gray-800">{{ $slotTersedia }}</h3>
        <p class="text-xs text-green-500 mt-1 font-medium">Aktif bulan ini</p>
    </div>
</div>

{{-- Kalender --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-lg font-bold text-gray-800">{{ $namaBulan[$bulan] }} {{ $tahun }}</h2>
            <p class="text-xs text-gray-400 mt-0.5">Klik tanggal berwarna untuk detail pesanan</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('jadwal-admin.kalender', ['bulan' => $prevBulan, 'tahun' => $prevTahun]) }}"
                class="w-9 h-9 rounded-xl border border-gray-200 flex items-center justify-center hover:bg-gray-50 transition">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <a href="{{ route('jadwal-admin.kalender', ['bulan' => $nextBulan, 'tahun' => $nextTahun]) }}"
                class="w-9 h-9 rounded-xl border border-gray-200 flex items-center justify-center hover:bg-gray-50 transition">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>

    {{-- Nama Hari --}}
    <div class="grid grid-cols-7 mb-2">
        @foreach(['Min','Sen','Sel','Rab','Kam','Jum','Sab'] as $h)
            <div class="py-2 text-center text-xs font-semibold text-gray-400 uppercase tracking-wider">{{ $h }}</div>
        @endforeach
    </div>

    {{-- Grid --}}
    <div class="grid grid-cols-7 gap-2">

        @for($i = 0; $i < $hariPertama; $i++)
            <div class="aspect-square rounded-xl bg-gray-50/70"></div>
        @endfor

        @for($day = 1; $day <= $hariDalamBulan; $day++)
            @php
                $dateKey  = sprintf('%04d-%02d-%02d', $tahun, $bulan, $day);
                $orders   = $pesananPerTanggal[$dateKey] ?? collect();
                $hasOrder = $orders->isNotEmpty();
                $isToday  = $dateKey === $today->format('Y-m-d');

                $ordersData = $orders->map(function($o) {
                    return [
                        'nama'    => $o['customer'],
                        'email'   => $o['email'],
                        'status'  => $o['status'],
                        'total'   => 'Rp ' . number_format($o['total'], 0, ',', '.'),
                        'details' => collect($o['items'])->map(fn($d) => [
                            'produk'   => $d['produk'],
                            'quantity' => $d['quantity'],
                            'type'     => $d['type'] ?? '',
                        ])->toArray(),
                    ];
                })->values()->toJson();
            @endphp

            <div
                @if($hasOrder)
                    @click="openDay('{{ \Carbon\Carbon::createFromDate($tahun, $bulan, $day)->translatedFormat('d F Y') }}', {{ $ordersData }})"
                @endif
                class="aspect-square rounded-xl flex flex-col items-center justify-center relative select-none
                    {{ $hasOrder ? 'cursor-pointer' : 'cursor-default' }}
                    {{ $isToday && $hasOrder  ? 'bg-[#BB9457] text-white shadow-lg shadow-[#BB9457]/25 hover:bg-[#a8834c]' : '' }}
                    {{ $isToday && !$hasOrder ? 'border-2 border-[#BB9457] text-[#BB9457] font-bold' : '' }}
                    {{ !$isToday && $hasOrder ? 'bg-[#BB9457]/10 border border-[#BB9457]/25 text-[#BB9457] hover:bg-[#BB9457]/20 hover:scale-105' : '' }}
                    {{ !$isToday && !$hasOrder ? 'bg-gray-50/80 text-gray-400' : '' }}
                    transition-all duration-150">

                <span class="text-sm font-bold leading-none">{{ $day }}</span>

                @if($hasOrder)
                    <span class="text-[9px] mt-1 font-semibold {{ $isToday ? 'text-white/80' : 'text-[#BB9457]/70' }}">
                        {{ $orders->count() }}×
                    </span>
                @endif

                @if($isToday)
                    <div class="absolute top-1 right-1 w-1.5 h-1.5 rounded-full {{ $hasOrder ? 'bg-white' : 'bg-[#BB9457]' }}"></div>
                @endif
            </div>
        @endfor

        @php $sisa = (7 - ($hariPertama + $hariDalamBulan) % 7) % 7; @endphp
        @for($i = 0; $i < $sisa; $i++)
            <div class="aspect-square rounded-xl bg-gray-50/70"></div>
        @endfor
    </div>

    <div class="flex flex-wrap items-center gap-5 mt-5 pt-4 border-t border-gray-100">
        <div class="flex items-center gap-2 text-xs text-gray-500">
            <div class="w-3 h-3 rounded-md bg-[#BB9457]"></div>Hari ini + pesanan
        </div>
        <div class="flex items-center gap-2 text-xs text-gray-500">
            <div class="w-3 h-3 rounded-md bg-[#BB9457]/15 border border-[#BB9457]/25"></div>Ada pesanan
        </div>
        <div class="flex items-center gap-2 text-xs text-gray-500">
            <div class="w-3 h-3 rounded-md bg-gray-100"></div>Kosong
        </div>
    </div>
</div>

{{-- MODAL --}}
<div
    x-show="modal"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 flex items-center justify-center p-4"
    style="display:none">

    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="modal = false"></div>

    <div
        x-show="modal"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95 translate-y-3"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg z-10 overflow-hidden max-h-[85vh] flex flex-col">

        {{-- Modal Header --}}
        <div class="bg-gradient-to-r from-[#BB9457] to-[#d4a96a] px-6 py-5 flex-shrink-0">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-white/70 text-xs font-medium uppercase tracking-wider">Pesanan Tanggal</p>
                    <h3 class="text-white text-xl font-bold mt-0.5" x-text="selectedDate"></h3>
                    <p class="text-white/70 text-xs mt-1.5">
                        <span x-text="selectedOrders.length"></span> pesanan masuk
                    </p>
                </div>
                <button @click="modal = false"
                    class="w-8 h-8 rounded-xl bg-white/20 flex items-center justify-center hover:bg-white/30 transition text-white flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Order List --}}
        <div class="overflow-y-auto flex-1 px-6 py-4 space-y-2">
            <template x-for="(order, i) in selectedOrders" :key="i">
                <div class="rounded-xl border border-gray-100 overflow-hidden" x-data="{ open: i === 0 }">

                    {{-- Accordion Header — always visible, click to toggle --}}
                    <button type="button"
                        @click="open = !open"
                        class="w-full flex items-center justify-between px-4 py-3 bg-gray-50 hover:bg-gray-100/70 transition text-left">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-[#BB9457]/15 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-[#BB9457]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-800" x-text="order.nama"></p>
                                <p class="text-xs text-gray-400" x-text="order.details.length + ' item · ' + order.total"></p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0 ml-2">
                            <span class="text-xs font-medium px-2.5 py-1 rounded-lg"
                                :class="{
                                    'bg-red-50 text-red-500 border border-red-200':         order.status === 'pending',
                                    'bg-yellow-50 text-yellow-600 border border-yellow-200': order.status === 'paid',
                                    'bg-blue-50 text-blue-600 border border-blue-200':       order.status === 'shipping',
                                    'bg-green-50 text-green-600 border border-green-200':    order.status === 'completed',
                                    'bg-gray-100 text-gray-400 border border-gray-200':      order.status === 'cancelled',
                                }">
                                <span x-text="{pending:'Belum Bayar',paid:'Diproses',shipping:'Diantar',completed:'Selesai',cancelled:'Dibatalkan'}[order.status] || order.status"></span>
                            </span>
                            {{-- Chevron --}}
                            <svg class="w-4 h-4 text-gray-400 transition-transform duration-200"
                                :class="open ? 'rotate-180' : ''"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </button>

                    {{-- Accordion Body --}}
                    <div x-show="open"
                        x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 -translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="px-4 py-3 space-y-2.5 border-t border-gray-100">
                        <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Pesanan Kue</p>
                        <template x-for="(detail, j) in order.details" :key="j">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-md bg-[#BB9457]/10 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-3.5 h-3.5 text-[#BB9457]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.701 2.701 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.701 2.701 0 00-1.5-.454M9 6l3-3 3 3M12 3v12"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs font-medium text-gray-700" x-text="detail.produk"></p>
                                        <p class="text-[10px] text-gray-400" x-text="detail.type" x-show="detail.type"></p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1">
                                    <span class="text-sm font-bold text-[#BB9457]" x-text="detail.quantity"></span>
                                    <span class="text-[10px] text-gray-400">pcs</span>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </template>
        </div>

        {{-- Footer --}}
        <div class="px-6 py-4 border-t border-gray-100 flex-shrink-0 flex justify-end">
            <button @click="modal = false"
                class="px-5 py-2.5 rounded-xl bg-gray-100 text-gray-600 text-sm font-medium hover:bg-gray-200 transition">
                Tutup
            </button>
        </div>
    </div>
</div>

</div>
@endsection