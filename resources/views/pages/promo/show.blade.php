@extends('layouts.app')

@section('title', 'Detail Produk')

@section('content')

    @php
        $price = (int) $promo->price;
        $actualPrice = (int) ($promo->actual_price ?? 0);
        $hasDiscount = $actualPrice > $price;
        $formattedDateUntil = $promo->date_until ? $promo->date_until->format('d M Y') : null;
    @endphp

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

                    <img src="{{ $promo->image ?? asset('images/promo-placeholder.jpg') }}" alt="{{ $promo->name }}"
                        class="w-full h-full object-cover" loading="lazy">
                </div>

                {{-- ===== RIGHT: Product Info ===== --}}
                <div class="relative">
                    <div class="flex flex-wrap items-center gap-3 mb-4">
                        @if ($hasDiscount)
                            <span
                                class="inline-flex items-center rounded-full bg-[#B8402A] px-3 py-1 text-xs sm:text-sm font-semibold text-white">
                                Hemat {{ $promo->percentage }}%
                            </span>
                        @endif

                        @if ($formattedDateUntil)
                            <span
                                class="inline-flex items-center rounded-full bg-white/80 border border-[#D8CFC4] px-3 py-1 text-xs sm:text-sm font-semibold text-[#6B4C3B]">
                                Berlaku sampai {{ $formattedDateUntil }}
                            </span>
                        @endif
                    </div>

                    {{-- Product Name --}}
                    <h1
                        class="font-['Playfair_Display']
                    text-3xl sm:text-4xl md:text-5xl xl:text-6xl
                    font-bold text-[#3D2B1F]
                    leading-tight mb-4 md:mb-5 xl:mb-6
                    pr-0 md:pr-16">
                        {{ $promo->name }}
                    </h1>

                    {{-- Description --}}
                    <p
                        class="font-glacial text-[#6B4C3B] text-sm sm:text-base md:text-lg xl:text-xl leading-relaxed mb-6 md:mb-8 xl:mb-10">
                        {{ $promo->description }}
                    </p>

                    @if ($hasDiscount)
                        <div class="flex items-end gap-3 mb-6 md:mb-8 xl:mb-10">
                            <p class="text-[#8A6D5A] line-through text-sm sm:text-base md:text-lg">
                                Rp {{ number_format($actualPrice, 0, ',', '.') }}
                            </p>
                            <p class="font-bold text-[#3D2B1F] text-lg sm:text-xl md:text-2xl">
                                Rp {{ number_format($price, 0, ',', '.') }}
                            </p>
                        </div>
                    @endif

                    {{-- Controls --}}
                    <div class="flex flex-wrap items-center gap-2 sm:gap-3 xl:gap-4 mb-6 md:mb-8 xl:mb-10">
                        {{-- Quantity --}}
                        <div class="flex items-center bg-white border border-[#D8CFC4] rounded-[10px] overflow-hidden">
                            <button id="qty-minus" type="button"
                                class="w-10 h-10 sm:w-10 sm:h-10 xl:w-12 xl:h-12 flex items-center justify-center bg-[#E1E1E1] hover:bg-[#BDBDBD] font-bold">
                                −
                            </button>
                            <span id="qty-value"
                                class="w-9 sm:w-10 xl:w-12 text-center font-bold text-[#3D2B1F]
                                   text-xs sm:text-sm xl:text-base mx-5 select-none">
                                1
                            </span>
                            <button id="qty-plus" type="button"
                                class="w-10 h-10 sm:w-10 sm:h-10 xl:w-12 xl:h-12 flex items-center justify-center bg-[#E1E1E1] hover:bg-[#BDBDBD] font-bold">
                                +
                            </button>
                        </div>
                    </div>

                    {{-- Price + Checkout --}}
                    <form action="{{ route('cart.store', $promo) }}" method="POST"
                        class="flex flex-col sm:flex-row items-stretch
                        bg-[#7A8C5C] rounded-[14px]
                        p-2 sm:p-2.5 gap-2 sm:gap-3">
                        @csrf
                        <input type="hidden" name="qty" id="qty-submit" value="1">

                        {{-- Subtotal --}}
                        <div
                            class="flex flex-1 items-center justify-center sm:justify-start
                            bg-white rounded-lg
                            px-4 sm:px-6 xl:px-8
                            py-3 sm:py-4 xl:py-5">
                            <div class="flex flex-col leading-tight">
                                <span id="subtotal"
                                    class="font-['Playfair_Display'] font-bold
                                    text-lg sm:text-xl xl:text-2xl 2xl:text-3xl
                                    text-[#3D2B1F]">
                                    Rp {{ number_format($price, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        {{-- Add to Cart Button --}}
                        <button type="submit"
                            class="bg-[#ADC178] hover:bg-[#5C6B44]
                            text-white font-['Playfair_Display'] font-bold
                            text-lg sm:text-xl xl:text-2xl
                            px-6 sm:px-10 xl:px-12
                            py-3 sm:py-4 xl:py-5
                            rounded-lg transition
                            w-full sm:w-auto">
                            Tambah ke Keranjang
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <x-footer />

    <script>
        const minus = document.getElementById('qty-minus');
        const plus = document.getElementById('qty-plus');
        const qtyValue = document.getElementById('qty-value');
        const qtySubmit = document.getElementById('qty-submit');
        const subtotal = document.getElementById('subtotal');
        const unitPrice = {{ $price }};
        const minQty = 1;
        const maxQty = 99;
        let qty = 1;

        const toCurrency = (value) => `Rp ${value.toLocaleString('id-ID')}`;

        const setQty = (nextQty) => {
            const safeQty = Math.min(maxQty, Math.max(minQty, Number(nextQty) || minQty));
            qty = safeQty;
            qtyValue.textContent = safeQty;
            qtySubmit.value = safeQty;
            subtotal.textContent = toCurrency(safeQty * unitPrice);
        };

        minus.addEventListener('click', () => {
            setQty(qty - 1);
        });

        plus.addEventListener('click', () => {
            setQty(qty + 1);
        });

        setQty(qty);
    </script>

@endsection
