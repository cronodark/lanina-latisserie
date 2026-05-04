@extends('layouts.app')

@section('title', 'Lanina Patisserie')

@section('content')
    <x-navbar />
    <section class="relative z-10 min-h-screen pt-16 pb-32 overflow-visible"
        style="background-image: url('/images/hero.png'); background-size: cover; background-position: center;">

        <div class="max-w-6xl mx-auto px-6 pt-16 grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
            {{-- TEXT --}}
            <div>
                <h1 class="font-gloock font-normal text-5xl md:text-6xl font-bold text-[#432818] leading-tight mb-6">
                    Kue <span class="font-caramel font-normal text-[#6A7941] text-8xl">Artisanal</span><br>
                    dari Hati,<br>
                    untuk Meja Kamu
                </h1>

                <p class="text-brown-light text-sm leading-relaxed mb-8 max-w-sm font-glacial text-base text-justify">
                    Lanina Patisserie menghadirkan kue-kue buatan tangan dengan bahan pilihan premium.
                    Setiap gigitan adalah cerita rasa yang dibuat dengan cinta dan ketelitian.
                </p>

                <a href="#our-product"
                    class="inline-flex items-center gap-2 bg-[#6A7941] px-8 py-3.5 font-glacial rounded-full text-white font-medium text-sm hover:bg-[#556433] transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                    Pesan Sekarang!
                </a>
            </div>
        </div>

        {{-- FLOATING STATS (FIXED) --}}
        <div class="absolute bottom-[-50px] left-1/2 -translate-x-1/2 w-full max-w-4xl px-6 z-30">
            <div
                class="bg-[#A8B97A] rounded-3xl shadow-2xl px-6 py-10 flex flex-col md:flex-row items-center justify-between gap-6">

                {{-- ITEM 1 --}}
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-white flex items-center justify-center shadow">
                        <span class="text-xl">🧁</span>
                    </div>
                    <div>
                        <p class="font-gloock extralight text-white text-lg leading-none">
                            500+ Happy Customers
                        </p>
                        <p class="font-gloock text-white/80 text-xs mt-1">
                            Ribuan kue terkirim, belum pernah ada yang kecewa
                        </p>
                    </div>
                </div>

                {{-- DIVIDER --}}
                <div class="hidden md:block w-px h-10 bg-white/30"></div>

                {{-- ITEM 2 --}}
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-white flex items-center justify-center shadow">
                        <span class="text-xl">🧾</span>
                    </div>
                    <div>
                        <p class="font-gloock extralight text-white text-lg leading-none">
                            Pre-Order Terjamin
                        </p>
                        <p class="font-gloock text-white/80 text-xs mt-1">
                            Slot terbatas — kue selalu fresh dan datang tepat waktu
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ===================== ABOUT US ===================== --}}
    <section id="about" class="py-10 bg-cream" style="padding-top: 6rem;">
        <div class="max-w-6xl mx-auto px-6 grid grid-cols-1 md:grid-cols-2 gap-16 items-center">

            {{-- About Text --}}
            <div class="scroll-fade">
                <h2 class="font-gloock text-6xl text-brown mb-2">Tentang Kami</h2>
                <p class="font-glacial text-lg mb-6 font-bold">— Sebuah toko kue dengan hati yang besar</p>
                <div class="space-y-4 font-glacial text-brown-light leading-relaxed font-serif text-base">
                    <p>Lanina Patisserie lahir dari kecintaan terhadap seni membuat kue. Kami percaya setiap kue bukan
                        sekadar makanan – ia adalah ekspresi rasa, kenangan, dan momen bahagia yang dibagikan bersama
                        orang-orang tersayang.</p>
                    <p>Dibuat dengan bahan berkualitas tinggi dan teknik patisserie yang telah diasah bertahun-tahun. Tidak
                        ada produksi massal di sini — hanya kue buatan tangan, khusus untukmu.</p>
                    <p>Setiap pre-order kami memastikan kamu selalu mendapatkan kue yang benar-benar segar. Karena kamu
                        pantas mendapatkan yang terbaik.</p>
                </div>
            </div>

            {{-- About Image --}}
            <div class="scroll-fade relative" style="transition-delay:0.2s">
                <div class="rounded-3xl overflow-hidden shadow-xl h-[380px] relative">
                    <img src="/images/about.png" alt="Coffee and dessert" class="w-full h-full object-cover">
                </div>
            </div>

        </div>
    </section>

    {{-- ===================== WHY US ===================== --}}
    <section class="py-20 ">
        <div class="max-w-6xl mx-auto px-6">

            <!-- HEADER -->
            <div class="text-right mb-10">
                <h2 class="font-gloock text-5xl text-brown">Kenapa Memilih Kami?</h2>
                <p class="text-brown-light font-glacial mt-2">
                    Lebih dari sekadar kue — sebuah pengalaman yang akan selalu diingat
                </p>
            </div>

            <!-- CARDS -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch ">

                <!-- CARD 1 -->
                <div class="text-center flex flex-col">
                    <div class="bg-[#EDE4CF] rounded-3xl py-10 px-6 flex-1 flex flex-col justify-center">
                        <div class="text-5xl mb-4">⭐</div>
                        <h3 class="font-gloock text-lg text-brown leading-snug">
                            Dibuat dengan Bahan Premium
                        </h3>
                    </div>

                    <p class="font-glacial text-brown-light text-sm mt-4 max-w-xs mx-auto">
                        Hanya bahan-bahan terbaik yang masuk ke setiap kue kami.
                    </p>
                </div>

                <!-- CARD 2 -->
                <div class="text-center flex flex-col">
                    <div
                        class="bg-[#EDE4CF] rounded-3xl py-10 px-6 border-2 border-blue-400 shadow-md flex-1 flex flex-col justify-center">
                        <div class="text-5xl mb-4">❤️</div>
                        <h3 class="font-gloock text-lg text-brown">
                            Dibuat dengan Cinta
                        </h3>
                    </div>

                    <p class="font-glacial text-brown-light text-sm mt-4 max-w-xs mx-auto">
                        Diracik dan dipanggang manual, satu per satu, dengan sepenuh hati.
                    </p>
                </div>

                <!-- CARD 3 -->
                <div class="text-center flex flex-col">
                    <div class="bg-[#EDE4CF] rounded-3xl py-10 px-6 flex-1 flex flex-col justify-center">
                        <div class="text-5xl mb-4">🎁</div>
                        <h3 class="font-gloock text-lg text-brown leading-snug">
                            Kemasan Eksklusif dan Siap Hampers
                        </h3>
                    </div>

                    <p class="font-glacial text-brown-light text-sm mt-4 max-w-xs mx-auto">
                        Box premium yang cantik — cocok untuk hadiah atau self-reward spesial.
                    </p>
                </div>

            </div>
        </div>
    </section>

    {{-- ===================== BEST SELLER ===================== --}}
    <section id="bestseller" class="py-16 sm:py-20 bg-[#A8B97A] overflow-hidden">

        <div class="w-full px-6 sm:px-8 lg:px-12 xl:px-16">

            {{-- HEADER --}}
            <div class="text-center mb-10">
                <h2 class="font-gloock text-4xl sm:text-5xl md:text-6xl text-white">
                    Paling Laris
                </h2>
                <p class="font-glacial text-white/80 mt-3 font-serif text-sm sm:text-base max-w-xl mx-auto ">
                    Kue-kue favorit pelanggan kami yang selalu habis terjual. Rasakan kenikmatan yang membuat semua
                    orang ketagihan!
                </p>
            </div>

            <div class="relative carousel">

                {{-- BUTTON LEFT --}}
                <button
                    class="carousel-left hidden md:flex absolute left-2 top-1/2 -translate-y-1/2 z-10 w-11 h-11 bg-white/80 backdrop-blur rounded-full shadow-lg items-center justify-center hover:bg-white transition">
                    <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>

                {{-- TRACK --}}
                <div id="bestsellerTrack"
                    class="track flex {{ count($bestsellers) <= 2 ? 'justify-center md:justify-center' : 'justify-start' }} gap-4 sm:gap-5 overflow-x-auto scroll-smooth snap-x snap-mandatory no-scrollbar cursor-grab active:cursor-grabbing">

                    @foreach ($bestsellers as $item)
                        <div
                            class="carousel-card flex-shrink-0 basis-[85%] md:basis-[calc((100%-1rem)/2)] lg:basis-[calc((100%-2.5rem)/3)] xl:basis-[calc((100%-3.75rem)/4)] bg-[#F5EFE6] rounded-3xl shadow-xl snap-start flex flex-col">

                            {{-- IMAGE (INSIDE CARD) --}}
                            <div class="p-3">
                                <div class="h-40 sm:h-44 md:h-48 overflow-hidden rounded-2xl shadow-md">
                                    <img src="{{ $item->product->image }}" class="w-full h-full object-cover">
                                </div>
                            </div>

                            {{-- CONTENT --}}
                            <div class="px-4 pb-4 flex-1 flex flex-col">
                                <h3 class="font-gloock text-lg text-gray-800 mb-1">
                                    {{ $item->product->name }}
                                </h3>

                                <p class="font-glacial text-sm text-gray-500 mb-4">
                                    Terjual {{ number_format($item->total_bought, 0, ',', '.') }} item
                                </p>

                                <a href="{{ route('product.show', $item->product->id) }}">
                                    <div class="w-full mt-auto">
                                        <div
                                            class="flex items-center justify-between bg-[#6B7D4F] text-white px-6 py-3 rounded-full">

                                            <!-- PRICE -->
                                            <span class="text-sm font-glacial">
                                                Rp {{ number_format($item->product->price, 0, ',', '.') }}
                                            </span>

                                            <!-- BUTTON -->
                                            <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center">
                                                <svg class="w-4 h-4 text-[#6B7D4F]" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                        d="M9 5l7 7-7 7" />
                                                </svg>
                                            </div>

                                        </div>
                                    </div>
                                </a>
                            </div>

                        </div>
                    @endforeach

                </div>

                {{-- BUTTON RIGHT --}}
                <button
                    class="carousel-right hidden md:flex absolute right-2 top-1/2 -translate-y-1/2 z-10 w-11 h-11 bg-white/80 backdrop-blur rounded-full shadow-lg items-center justify-center hover:bg-white transition">
                    <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        </div>
    </section>

    {{-- ===================== OUR PRODUCT ===================== --}}
    <section id="our-product" class="py-16 sm:py-20 bg-[#F0EAD2] overflow-hidden">

        <div class="w-full px-6 sm:px-8 lg:px-12 xl:px-16">

            {{-- HEADER --}}
            <div class="text-center mb-6 scroll-fade">
                <h2 class="font-gloock text-5xl text-brown">Produk Kami</h2>
                <p class="text-brown-light mt-3 font-glacial text-base">Temukan berbagai produk lezat dan berkualitas tinggi
                    dari kami</p>
            </div>

            <div class="relative carousel">

                {{-- BUTTON LEFT --}}
                <button
                    class="carousel-left hidden md:flex absolute left-2 top-1/2 -translate-y-1/2 z-10 w-11 h-11 bg-white/80 backdrop-blur rounded-full shadow-lg items-center justify-center hover:bg-white transition">
                    <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>

                {{-- TRACK --}}
                <div id="ourProductTrack"
                    class="track flex justify-start gap-4 sm:gap-5 overflow-x-auto scroll-smooth snap-x snap-mandatory no-scrollbar cursor-grab active:cursor-grabbing">

                    @foreach ($products as $item)
                        <div
                            class="carousel-card flex-shrink-0 basis-[85%] md:basis-[calc((100%-1rem)/2)] lg:basis-[calc((100%-2.5rem)/3)] xl:basis-[calc((100%-3.75rem)/4)] bg-[#FFF9F2] rounded-3xl shadow-xl snap-start my-8 flex flex-col">

                            {{-- IMAGE (INSIDE CARD) --}}
                            <div class="p-3">
                                <div class="h-40 sm:h-44 md:h-48 overflow-hidden rounded-2xl shadow-md">
                                    <img src="{{ $item->image }}" class="w-full h-full object-cover">
                                </div>
                            </div>

                            {{-- CONTENT --}}
                            <div class="px-4 pb-4 flex-1 flex flex-col">
                                <h3 class="font-gloock text-lg text-gray-800 mb-1">
                                    {{ $item->name }}
                                </h3>

                                <p class="font-glacial text-sm text-gray-500 mb-4 min-h-[3rem] overflow-hidden text-ellipsis"
                                    style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                                    {{ $item->description }}
                                </p>

                                <div class="w-full mt-auto cursor-pointer">
                                    <a href="{{ route('product.show', $item->id) }}">
                                        <div
                                            class="flex items-center justify-between bg-[#6B7D4F] text-white px-6 py-3 rounded-full">

                                            <!-- PRICE -->
                                            <span class="text-sm font-glacial">
                                                Rp {{ number_format($item->price, 0, ',', '.') }}
                                            </span>

                                            <!-- BUTTON -->
                                            <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center">
                                                <svg class="w-4 h-4 text-[#6B7D4F]" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2.5" d="M9 5l7 7-7 7" />
                                                </svg>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>

                        </div>
                    @endforeach

                </div>

                {{-- BUTTON RIGHT --}}
                <button
                    class="carousel-right hidden md:flex absolute right-2 top-1/2 -translate-y-1/2 z-10 w-11 h-11 bg-white/80 backdrop-blur rounded-full shadow-lg items-center justify-center hover:bg-white transition">
                    <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>

            <div class="text-center mb-10 scroll-fade">
                <a href="{{ route('product.index') }}"
                    class="inline-flex items-center gap-2 bg-[#432818] text-white text-warm-white px-7 py-2.5 rounded-full text-sm font-glacial hover:bg-brown-light transition-colors">
                    Lihat Semua Produk!
                </a>
            </div>
        </div>
    </section>

    {{-- ===================== CTA BANNER ===================== --}}
    <section class="h-auto py-20 relative overflow-hidden bg-cover bg-center"
        style="background-image: url('/images/flirtimage.png');">

        <div class="relative max-w-6xl mx-auto px-6 grid grid-cols-1 md:grid-cols-2 gap-8 items-center">

            <div class="scroll-fade">
                <h2 class="font-gloock text-4xl md:text-5xl text-white leading-tight mb-8">
                    Dibuat dengan Hati,<br>
                    Dinikmati Sepenuh Jiwa
                </h2>

                <a href="#our-product"
                    class="inline-flex items-center gap-3 bg-[#432818] text-white px-8 py-3.5 rounded-full font-glacial text-sm hover:bg-[#6B4F3A] transition-all duration-300 shadow-lg hover:shadow-xl group">
                    Pre-Order Sekarang
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <x-footer />

@endsection

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const carousels = document.querySelectorAll(".carousel");

        carousels.forEach((carousel) => {
            const track = carousel.querySelector(".track");
            const leftBtn = carousel.querySelector(".carousel-left");
            const rightBtn = carousel.querySelector(".carousel-right");

            if (!track || !leftBtn || !rightBtn) {
                return;
            }

            const getScrollAmount = () => {
                const card = track.querySelector(".carousel-card");
                const trackStyles = window.getComputedStyle(track);
                const gap = parseFloat(trackStyles.gap || trackStyles.columnGap || "0");

                if (!card) {
                    return 300;
                }

                return card.getBoundingClientRect().width + gap;
            };

            rightBtn.addEventListener("click", () => {
                track.scrollBy({
                    left: getScrollAmount(),
                    behavior: "smooth"
                });
            });

            leftBtn.addEventListener("click", () => {
                track.scrollBy({
                    left: -getScrollAmount(),
                    behavior: "smooth"
                });
            });
        });
    });
</script>


<Style>
    .no-scrollbar::-webkit-scrollbar {
        display: none;
        /* Chrome, Safari */
    }

    .no-scrollbar {
        -ms-overflow-style: none;
        /* IE & Edge lama */
        scrollbar-width: none;
        /* Firefox */
    }
</Style>
