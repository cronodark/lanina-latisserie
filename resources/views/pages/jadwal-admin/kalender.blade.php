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
    slotInfo: null,
    openDay(date, orders, slot) {
        this.selectedDate = date;
        this.selectedOrders = orders || [];
        this.slotInfo = slot || null;
        this.modal = true;
    }
}">

{{-- Header --}}
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Kalender Pesanan</h1>
    <p class="text-sm text-gray-500 mt-1">Klik tanggal untuk melihat detail pesanan & slot</p>
</div>

{{-- Stats --}}
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Total Pesanan</p>
        <h3 class="text-3xl font-bold mt-2 text-gray-800">{{ $totalPesanan }}</h3>
        <p class="text-xs text-[#BB9457] mt-1 font-medium">{{ $namaBulan[$bulan] }} {{ $tahun }}</p>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Tanggal Paling Ramai</p>
        <h3 class="text-2xl font-bold mt-2 text-gray-800">{{ $tanggalRamaiFmt }}</h3>
        <p class="text-xs text-gray-400 mt-1">Terbanyak pesanan</p>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Slot Aktif</p>
        <h3 class="text-3xl font-bold mt-2 text-green-600">{{ $slotTersedia }}</h3>
        <p class="text-xs text-green-500 mt-1 font-medium">Tersedia bulan ini</p>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Slot Penuh</p>
        <h3 class="text-3xl font-bold mt-2 text-red-600">{{ $slotPenuh }}</h3>
        <p class="text-xs text-red-500 mt-1 font-medium">Sudah terisi</p>
    </div>
</div>

{{-- Kalender --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-lg font-bold text-gray-800">{{ $namaBulan[$bulan] }} {{ $tahun }}</h2>
            <p class="text-xs text-gray-400 mt-0.5">Klik tanggal untuk detail pesanan & slot</p>
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

    {{-- Legend --}}
    <div class="flex items-center gap-4 mb-4 pb-4 border-b border-gray-100">
        <div class="flex items-center gap-2">
            <div class="w-3 h-3 rounded-full bg-green-500"></div>
            <span class="text-xs text-gray-600">Slot Aktif</span>
        </div>
        <div class="flex items-center gap-2">
            <div class="w-3 h-3 rounded-full bg-red-500"></div>
            <span class="text-xs text-gray-600">Slot Penuh</span>
        </div>
        <div class="flex items-center gap-2">
            <div class="w-3 h-3 rounded-full bg-gray-400"></div>
            <span class="text-xs text-gray-600">Nonaktif</span>
        </div>
        <div class="flex items-center gap-2">
            <div class="w-3 h-3 rounded-full border-2 border-[#BB9457]"></div>
            <span class="text-xs text-gray-600">Hari Ini</span>
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
                $slot     = $tanggalTersedia[$dateKey] ?? null;
                $hasOrder = $orders->isNotEmpty();
                $hasSlot  = $slot !== null;
                $isToday  = $dateKey === $today->format('Y-m-d');

                // Determine cell styling
                $bgClass = 'bg-gray-50/70';
                $borderClass = '';
                $cursorClass = 'cursor-default';
                $indicatorClass = '';

                if ($hasSlot) {
                    if ($slot['status'] === 'Aktif') {
                        $bgClass = 'bg-green-50 hover:bg-green-100';
                        $indicatorClass = 'bg-green-500';
                        $cursorClass = 'cursor-pointer';
                    } elseif ($slot['status'] === 'Penuh') {
                        $bgClass = 'bg-red-50 hover:bg-red-100';
                        $indicatorClass = 'bg-red-500';
                        $cursorClass = 'cursor-pointer';
                    } else {
                        $bgClass = 'bg-gray-100';
                        $indicatorClass = 'bg-gray-400';
                    }
                } elseif ($hasOrder) {
                    $bgClass = 'bg-blue-50 hover:bg-blue-100';
                    $cursorClass = 'cursor-pointer';
                }

                if ($isToday) {
                    $borderClass = 'ring-2 ring-[#BB9457] ring-offset-2';
                }

                $ordersData = $orders->map(function($o) {
                    return [
                        'id'      => $o['id'],
                        'nama'    => $o['customer'],
                        'email'   => $o['email'],
                        'telp'    => $o['telp'],
                        'status'  => $o['status'],
                        'send_type' => $o['send_type'],
                        'total'   => 'Rp ' . number_format($o['total'], 0, ',', '.'),
                        'details' => collect($o['items'])->map(fn($d) => [
                            'produk'   => $d['produk'],
                            'quantity' => $d['quantity'],
                            'type'     => $d['type'] ?? '',
                        ])->toArray(),
                    ];
                })->toArray();

                $slotData = $slot ? json_encode($slot) : 'null';
                $ordersJson = json_encode($ordersData);
                $dateFormatted = \Carbon\Carbon::parse($dateKey)->translatedFormat('d F Y');
            @endphp

            <div @if($hasSlot || $hasOrder)
                    @click="openDay('{{ $dateFormatted }}', {{ $ordersJson }}, {{ $slotData }})"
                 @endif
                class="aspect-square rounded-xl {{ $bgClass }} {{ $borderClass }} {{ $cursorClass }} p-2 flex flex-col items-center justify-between transition-all duration-200 relative group">
                
                {{-- Slot Indicator --}}
                @if($hasSlot)
                    <div class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full {{ $indicatorClass }}"></div>
                @endif

                {{-- Day Number --}}
                <div class="text-sm font-semibold {{ $isToday ? 'text-[#BB9457]' : 'text-gray-700' }}">
                    {{ $day }}
                </div>

                {{-- Order Count Badge --}}
                @if($hasOrder)
                    <div class="flex flex-col items-center gap-0.5 mt-auto">
                        <div class="px-2 py-0.5 rounded-full bg-[#BB9457] text-white text-[10px] font-bold">
                            {{ $orders->count() }}
                        </div>
                        <span class="text-[9px] text-gray-500 font-medium">pesanan</span>
                    </div>
                @endif

                {{-- Slot Info --}}
                @if($hasSlot && !$hasOrder)
                    <div class="flex flex-col items-center gap-0.5 mt-auto">
                        <span class="text-[10px] font-semibold {{ $slot['status'] === 'Aktif' ? 'text-green-600' : ($slot['status'] === 'Penuh' ? 'text-red-600' : 'text-gray-500') }}">
                            {{ $slot['terisi'] }}/{{ $slot['kuota'] }}
                        </span>
                        <span class="text-[9px] text-gray-500">slot</span>
                    </div>
                @endif

                {{-- Hover Tooltip --}}
                @if($hasSlot || $hasOrder)
                    <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-gray-900 text-white text-[10px] rounded opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none whitespace-nowrap z-10">
                        @if($hasSlot)
                            Slot: {{ $slot['terisi'] }}/{{ $slot['kuota'] }} • {{ $slot['status'] }}
                        @endif
                        @if($hasOrder)
                            <br>{{ $orders->count() }} pesanan
                        @endif
                    </div>
                @endif
            </div>
        @endfor

    </div>
</div>

{{-- Modal Detail --}}
<div x-show="modal"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 overflow-y-auto"
    style="display: none;">
    
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div @click="modal = false" class="fixed inset-0 transition-opacity bg-gray-900/50 backdrop-blur-sm"></div>

        <div x-show="modal"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="relative inline-block w-full max-w-2xl overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-2xl">
            
            {{-- Header --}}
            <div class="bg-gradient-to-r from-[#BB9457] to-[#8B6F47] px-6 py-5">
                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-white" x-text="selectedDate"></h3>
                        <p class="text-sm text-white/80 mt-1">Detail pesanan & slot</p>
                    </div>
                    <button @click="modal = false" 
                        class="w-8 h-8 rounded-xl bg-white/20 flex items-center justify-center hover:bg-white/30 transition text-white">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Body --}}
            <div class="px-6 py-5 max-h-[70vh] overflow-y-auto">
                
                {{-- Slot Info --}}
                <div x-show="slotInfo" class="mb-6 p-4 rounded-xl border-2" 
                    :class="{
                        'bg-green-50 border-green-200': slotInfo && slotInfo.status === 'Aktif',
                        'bg-red-50 border-red-200': slotInfo && slotInfo.status === 'Penuh',
                        'bg-gray-50 border-gray-200': slotInfo && slotInfo.status === 'Nonaktif'
                    }">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-sm font-bold text-gray-800">Informasi Slot</h4>
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold"
                            :class="{
                                'bg-green-100 text-green-700': slotInfo && slotInfo.status === 'Aktif',
                                'bg-red-100 text-red-700': slotInfo && slotInfo.status === 'Penuh',
                                'bg-gray-100 text-gray-700': slotInfo && slotInfo.status === 'Nonaktif'
                            }"
                            x-text="slotInfo ? slotInfo.status : ''"></span>
                    </div>
                    <div class="grid grid-cols-3 gap-3">
                        <div class="text-center p-3 bg-white rounded-lg">
                            <p class="text-xs text-gray-500 mb-1">Kuota</p>
                            <p class="text-2xl font-bold text-gray-800" x-text="slotInfo ? slotInfo.kuota : 0"></p>
                        </div>
                        <div class="text-center p-3 bg-white rounded-lg">
                            <p class="text-xs text-gray-500 mb-1">Terisi</p>
                            <p class="text-2xl font-bold text-[#BB9457]" x-text="slotInfo ? slotInfo.terisi : 0"></p>
                        </div>
                        <div class="text-center p-3 bg-white rounded-lg">
                            <p class="text-xs text-gray-500 mb-1">Sisa</p>
                            <p class="text-2xl font-bold" 
                                :class="slotInfo && slotInfo.sisa > 0 ? 'text-green-600' : 'text-red-600'"
                                x-text="slotInfo ? slotInfo.sisa : 0"></p>
                        </div>
                    </div>
                    <p x-show="slotInfo && slotInfo.keterangan" 
                        class="text-xs text-gray-600 mt-3 italic" 
                        x-text="slotInfo ? slotInfo.keterangan : ''"></p>
                </div>

                {{-- Orders List --}}
                <div x-show="selectedOrders.length > 0">
                    <h4 class="text-sm font-bold text-gray-800 mb-3">
                        Daftar Pesanan (<span x-text="selectedOrders.length"></span>)
                    </h4>
                    
                    <template x-for="(order, i) in selectedOrders" :key="i">
                        <div x-data="{ open: false }" class="mb-3 border border-gray-200 rounded-xl overflow-hidden hover:shadow-md transition-shadow">
                            <button @click="open = !open" 
                                class="w-full px-4 py-3 flex items-center justify-between hover:bg-gray-50 transition">
                                <div class="flex items-center gap-3 flex-1 text-left">
                                    <div class="w-10 h-10 rounded-xl bg-[#BB9457]/10 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-[#BB9457]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-bold text-gray-800 truncate" x-text="order.nama"></p>
                                        <div class="flex items-center gap-2 mt-0.5">
                                            <span class="text-xs px-2 py-0.5 rounded-full font-medium"
                                                :class="{
                                                    'bg-yellow-100 text-yellow-700': order.status === 'unpaid',
                                                    'bg-blue-100 text-blue-700': order.status === 'paid',
                                                    'bg-purple-100 text-purple-700': order.status === 'processing',
                                                    'bg-orange-100 text-orange-700': order.status === 'shipping',
                                                    'bg-green-100 text-green-700': order.status === 'completed'
                                                }"
                                                x-text="order.status"></span>
                                            <span class="text-xs text-gray-500" x-text="order.total"></span>
                                        </div>
                                    </div>
                                    <svg class="w-4 h-4 text-gray-400 transition-transform duration-200 flex-shrink-0"
                                        :class="open ? 'rotate-180' : ''"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </button>

                            <div x-show="open"
                                x-transition:enter="transition ease-out duration-150"
                                x-transition:enter-start="opacity-0 -translate-y-1"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                class="px-4 py-3 space-y-3 border-t border-gray-100 bg-gray-50/50">
                                
                                {{-- Customer Info --}}
                                <div class="grid grid-cols-2 gap-3 text-xs">
                                    <div>
                                        <p class="text-gray-500 mb-0.5">Email</p>
                                        <p class="text-gray-800 font-medium" x-text="order.email"></p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500 mb-0.5">Telepon</p>
                                        <p class="text-gray-800 font-medium" x-text="order.telp"></p>
                                    </div>
                                </div>

                                {{-- Items --}}
                                <div>
                                    <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-2">Pesanan Kue</p>
                                    <template x-for="(detail, j) in order.details" :key="j">
                                        <div class="flex items-center justify-between py-2 border-b border-gray-200 last:border-0">
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
                        </div>
                    </template>
                </div>

                {{-- Empty State --}}
                <div x-show="selectedOrders.length === 0 && !slotInfo" class="text-center py-8">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-sm text-gray-500">Belum ada pesanan atau slot untuk tanggal ini</p>
                </div>
            </div>

            {{-- Footer --}}
            <div class="px-6 py-4 border-t border-gray-100 flex justify-end">
                <button @click="modal = false"
                    class="px-5 py-2.5 rounded-xl bg-gray-100 text-gray-600 text-sm font-medium hover:bg-gray-200 transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

</div>
@endsection
