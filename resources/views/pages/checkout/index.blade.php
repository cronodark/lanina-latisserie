@extends('layouts.app')

@section('title', 'Checkout')

@section('content')

    <x-navbar />

    <main class="min-h-screen bg-[#F5F0E8] mt-10 py-12 px-6 md:px-16">
        <div class="max-w-[1100px] mx-auto flex justify-center">

            <div class="w-full max-w-[520px] flex flex-col gap-3">
                @php
                    $selectedAddress = collect($addresses)->first();
                    $selectedAddressText = $selectedAddress
                        ? collect([
                            $selectedAddress->street,
                            'RT '.$selectedAddress->rt.'/RW '.$selectedAddress->rw,
                            $selectedAddress->district,
                            $selectedAddress->city,
                            $selectedAddress->state,
                            $selectedAddress->zip_code,
                        ])->filter()->implode(', ')
                        : 'Belum ada alamat tersimpan';
                @endphp

                {{-- ===== SECTION 1: Order Items ===== --}}
                <div class="bg-white rounded-[20px] px-8 py-8 shadow-[0_3px_16px_rgba(0,0,0,0.06)]">

                    <h1 class="font-['Playfair_Display'] text-4xl font-bold text-[#3D2B1F] text-center mb-8">
                        Checkout
                    </h1>

                    {{-- ===== ALAMAT ===== --}}
                    <div class="mb-6 pb-6 border-b border-[#E8E0D4]">
                        <div id="open-address-modal" class="bg-[#F2EFEA] rounded-[16px] px-5 py-4 flex flex-col gap-2 cursor-pointer">

                            <div class="flex items-center justify-between">

                                <div class="flex items-center gap-3">
                                    {{-- Icon lokasi --}}
                                    <div class="w-6 h-6 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M5.05 8.05a5 5 0 119.9 0c0 3.535-4.95 8.95-4.95 8.95S5.05 11.585 5.05 8.05zM10 9a1 1 0 100-2 1 1 0 000 2z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>

                                    <div>
                                        <p id="address-name" class="font-glacial font-bold text-[#3D2B1F] text-sm">
                                            {{ auth()->user()->name }}
                                            <span class="font-normal text-[#6B4C3B] ml-2">
                                                ({{ auth()->user()->telp }})
                                            </span>
                                        </p>
                                    </div>
                                </div>

                                {{-- panah kanan --}}
                                <svg class="w-4 h-4 text-[#3D2B1F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>

                            </div>

                            <p id="address-detail" class="font-glacial text-[#6B4C3B] text-sm leading-relaxed">
                                {{ $selectedAddressText }}
                            </p>

                        </div>
                    </div>

                    <div class="flex flex-col divide-y divide-[#E8E0D4]">
                        @foreach ($items as $item)
                            <div class="py-4 first:pt-0 last:pb-0">
                                <div class="flex items-start justify-between mb-1">
                                    <p class="font-['Playfair_Display'] font-bold text-[#3D2B1F] text-base">
                                        {{ $item['name'] }}
                                    </p>
                                    <span class="font-glacial text-[#3D2B1F] text-sm font-bold ml-4 shrink-0">
                                        {{ $item['qty'] }}x
                                    </span>
                                </div>
                                <div class="flex items-end justify-between">
                                    <p class="font-glacial text-[#6B4C3B] text-sm">{{ $item['desc'] }}</p>
                                    <span class="font-glacial font-bold text-[#7A8C5C] text-lg ml-4 shrink-0">
                                        Rp {{ number_format($item['total'], 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- ===== SECTION 2: Metode Kirim ===== --}}
                <div class="bg-white rounded-[20px] px-8 py-7 shadow-[0_3px_16px_rgba(0,0,0,0.06)]">
                    <h2 class="font-['Playfair_Display'] font-bold text-[#3D2B1F] text-xl mb-4">
                        Metode Pengiriman
                    </h2>
                    <div id="open-shipping-modal"
                        class="flex items-center justify-between pb-4 border-b border-[#E8E0D4] cursor-pointer hover:opacity-75 transition-opacity">
                        <span id="selected-shipping-label" class="font-glacial text-[#3D2B1F] text-sm">Ambil Sendiri</span>
                        <svg class="w-4 h-4 text-[#7A8C5C]" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                </div>

                {{-- ===== SECTION 3: Metode Pembayaran ===== --}}
                <div class="bg-white rounded-[20px] px-8 py-7 shadow-[0_3px_16px_rgba(0,0,0,0.06)]">

                    <h2 class="font-['Playfair_Display'] font-bold text-[#3D2B1F] text-xl mb-4">
                        Metode Pembayaran
                    </h2>

                    <div id="open-payment-modal"
                        class="flex items-center justify-between pb-4 border-b border-[#E8E0D4] cursor-pointer hover:opacity-75 transition-opacity">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-7 bg-[#005BAA] rounded-[6px] flex items-center justify-center shrink-0">
                                <span class="text-white font-bold text-[10px] tracking-wide">BCA</span>
                            </div>
                            <span id="selected-bank-label" class="font-glacial text-[#3D2B1F] text-sm font-bold">Bank
                                BCA</span>
                        </div>
                        <svg class="w-4 h-4 text-[#7A8C5C]" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>

                    <div class="pt-4">
                        <p class="font-glacial text-[#9A8878] text-xs mb-1">Virtual Account</p>
                        <p class="font-glacial font-bold text-[#3D2B1F] text-lg tracking-widest">
                            123 9876 0235 3435
                        </p>
                    </div>

                </div>

                {{-- ===== SECTION 4: Tanggal + Total + Bayar ===== --}}
                <div class="bg-white rounded-[20px] px-8 py-7 shadow-[0_3px_16px_rgba(0,0,0,0.06)]">

                    <div class="flex items-center justify-between mb-5">
                        <p class="font-['Playfair_Display'] font-bold text-[#3D2B1F] text-lg">Tanggal Pengambilan</p>
                        <span class="font-glacial font-bold text-[#3D2B1F] text-base">12 02 26</span>
                    </div>

                    <div class="flex items-center justify-between mb-7">
                        <p class="font-glacial font-bold text-[#3D2B1F] text-base tracking-widest uppercase">TOTAL</p>
                        <span class="font-glacial font-bold text-[#7A8C5C] text-2xl">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                    </div>

                    <div class="flex justify-end">
                        <button
                            class="bg-[#7A8C5C] hover:bg-[#5C6B44] text-white font-glacial font-bold text-sm tracking-widest uppercase px-10 py-3 rounded-full transition-colors duration-200">
                            BAYAR
                        </button>
                    </div>

                </div>

            </div>
        </div>
    </main>
    @include('modalAlamat')
    @include('modalpembayaran')
    @include('modalpengiriman')
@endsection
