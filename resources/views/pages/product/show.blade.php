@extends('layouts.app')

@section('title', 'Detail Produk')

@section('content')

    {{-- Flatpickr CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <x-navbar />

    <main
        class="min-h-screen bg-[#F0EAD2]
    px-4 sm:px-6 md:px-10 lg:px-16 xl:px-24 2xl:px-32
    pt-16 sm:pt-20 md:pt-24 xl:pt-28
    pb-12">

        <div class="max-w-[1400px] 2xl:max-w-[1600px] mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12 xl:gap-16 2xl:gap-20 items-start">

                {{-- ===== LEFT: Product Image ===== --}}
                <div
                    class="rounded-2xl md:rounded-[24px] xl:rounded-[28px] overflow-hidden shadow-lg
                        aspect-[4/5] md:aspect-square">

                    <img src="{{ $product->image }}" class="w-full h-full object-cover">
                </div>

                {{-- ===== RIGHT: Product Info ===== --}}
                <div class="relative">

                    {{-- Cart Icon --}}
                    <div class="absolute top-0 right-0">
                        <button
                            class="w-10 h-10 sm:w-12 sm:h-12 xl:w-14 xl:h-14 bg-white rounded-full flex items-center justify-center shadow-md hover:scale-105 transition">
                            <svg class="w-5 h-5 xl:w-6 xl:h-6 text-[#7A8C5C]" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </button>
                    </div>

                    {{-- Product Name --}}
                    <h1
                        class="font-['Playfair_Display']
                    text-3xl sm:text-4xl md:text-5xl xl:text-6xl
                    font-bold text-[#3D2B1F]
                    leading-tight mb-4 md:mb-5 xl:mb-6
                    pr-0 md:pr-16">
                        {{ $product->name }}
                    </h1>

                    {{-- Description --}}
                    <p
                        class="font-glacial text-[#6B4C3B] text-sm sm:text-base md:text-lg xl:text-xl leading-relaxed mb-6 md:mb-8 xl:mb-10">
                        {{ $product->description }}
                    </p>

                    {{-- Controls --}}
                    <div class="flex flex-wrap items-center gap-2 sm:gap-3 xl:gap-4 mb-6 md:mb-8 xl:mb-10">

                        {{-- Stok --}}
                        <div
                            class="flex justify-center items-center gap-2 bg-white border border-[#D8CFC4] rounded-[10px]
                                px-4 py-2.5 pr-10
                                text-xs sm:text-sm xl:text-base
                                text-[#3D2B1F] cursor-pointer
                                focus:outline-none focus:border-[#7A8C5C]">
                            <span class="font-bold text-[#3D2B1F]">Stok:</span>
                            <span class="text-[#3D2B1F]">100</span>
                        </div>

                        {{-- Quantity --}}
                        <div class="flex items-center bg-white border border-[#D8CFC4] rounded-[10px] overflow-hidden">
                            <button id="qty-minus"
                                class="w-10 h-10 sm:w-10 sm:h-10 xl:w-12 xl:h-12 flex items-center justify-center bg-[#E1E1E1] hover:bg-[#BDBDBD] font-bold">
                                −
                            </button>
                            <span id="qty-value"
                                class="w-9 sm:w-10 xl:w-12 text-center font-bold text-[#3D2B1F]
                                   text-xs sm:text-sm xl:text-base mx-5 select-none">
                                2
                            </span>
                            <button id="qty-plus"
                                class="w-10 h-10 sm:w-10 sm:h-10 xl:w-12 xl:h-12 flex items-center justify-center bg-[#E1E1E1] hover:bg-[#BDBDBD] font-bold">
                                +
                            </button>
                        </div>
                    </div>

                    {{-- Price + Checkout --}}
                    <div
                        class="flex flex-col sm:flex-row items-stretch
                        bg-[#7A8C5C] rounded-[14px]
                        p-2 sm:p-2.5 gap-2 sm:gap-3">

                        {{-- Price --}}
                        <div
                            class="flex flex-1 items-center justify-center sm:justify-start
                            bg-white rounded-lg
                            px-4 sm:px-6 xl:px-8
                            py-3 sm:py-4 xl:py-5">
                            <span
                                class="font-['Playfair_Display'] font-bold
                                text-lg sm:text-xl xl:text-2xl 2xl:text-3xl
                                text-[#3D2B1F]">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </span>
                        </div>

                        {{-- Checkout Button --}}
                        <button
                            class="bg-[#ADC178] hover:bg-[#5C6B44]
                            text-white font-['Playfair_Display'] font-bold
                            text-lg sm:text-xl xl:text-2xl
                            px-6 sm:px-10 xl:px-12
                            py-3 sm:py-4 xl:py-5
                            rounded-lg transition
                            w-full sm:w-auto">
                            Checkout!
                        </button>

                    </div>
                </div>
            </div>
        </div>
    </main>

    <x-footer />

    {{-- Flatpickr JS --}}
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        // Quantity
        const minus = document.getElementById('qty-minus');
        const plus = document.getElementById('qty-plus');
        const val = document.getElementById('qty-value');
        let qty = 2;

        minus.addEventListener('click', () => {
            if (qty > 1) {
                qty--;
                val.textContent = qty;
            }
        });

        plus.addEventListener('click', () => {
            qty++;
            val.textContent = qty;
        });

        // Date Picker
        flatpickr("#date-picker", {
            dateFormat: "d/m/Y",
            minDate: "today"
        });
    </script>

    {{-- Custom Style Calendar --}}
    <style>
        .flatpickr-calendar {
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            font-family: sans-serif;
        }

        .flatpickr-day.selected {
            background: #7A8C5C;
            border-color: #7A8C5C;
            color: white;
        }

        .flatpickr-day:hover {
            background: #EDEAE3;
        }

        .flatpickr-months .flatpickr-month {
            color: #3D2B1F;
            font-weight: bold;
        }
    </style>

@endsection
