@extends('layouts.app')

@section('title', 'Produk')

@section('content')

    <x-navbar />

    {{-- ===================== HERO SECTION ===================== --}}
    <section class="relative z-10 min-h-screen pt-16 pb-32 overflow-visible"
        style="background-image: url('/images/produk.png'); background-size: cover; background-position: center;">

        <div class="absolute inset-0 flex flex-col items-center justify-center text-center px-10 mt-10">
            {{-- <h1 class="font-['Playfair_Display'] text-[clamp(2.5rem,5vw,3.8rem)] font-bold text-[#3D2B1F] leading-tight mb-3.5"
                style="text-shadow: 0 2px 20px rgba(255,255,255,0.3);">
                Our Product
            </h1> --}}
            <p class="font-['Cormorant_Garamond'] text-base text-[rgba(61,43,31,0.75)] max-w-[360px] leading-[1.7] mb-6">
                Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the
                industry's standard dummy
            </p>
            <a href="#all-products"
                class="inline-block bg-[#6B3A2A] text-white py-3 px-9 rounded-full font-['Cormorant_Garamond'] text-[15px] font-semibold tracking-wide no-underline transition-all duration-300 hover:bg-[#8B4A38] hover:-translate-y-0.5"
                style="box-shadow: 0 4px 16px rgba(107,58,42,0.35);">
                See More!
            </a>
        </div>
    </section>

    {{-- ===================== PROMO SECTION ===================== --}}
    <section class="relative overflow-hidden  py-14 px-10 bg-[#F9C93C] rounded-[32px] mx-8 my-6">

        {{-- Decorative blobs --}}
        <div class="absolute top-[-60px] left-[-60px] w-[260px] h-[260px] rounded-full bg-[#FFFFFF]/60"></div>
        <div class="absolute bottom-[-80px] right-[-50px] w-[300px] h-[300px] rounded-full bg-[#ffffff]/60"></div>
        <div class="absolute top-[30px] right-[180px] w-[120px] h-[120px] rounded-full bg-[#ffffff]/40"></div>

        <div class="max-w-[1100px] mx-auto relative">
            <h2 class="font-['Playfair_Display'] text-8xl font-bold text-white text-center mb-10"
                style="text-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                Promo!
            </h2>

            {{-- Slider wrapper --}}
            <div class="relative flex items-center gap-4">

                {{-- Prev Button --}}
                <button id="promo-prev"
                    class="shrink-0 w-12 h-12 rounded-full bg-[#7A7A5A] hover:bg-[#5C5C44] text-white flex items-center justify-center transition-colors duration-200 shadow-md z-10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>

                {{-- Cards Container --}}
                <div class="overflow-hidden flex-1">
                    <div id="promo-track" class="flex gap-5 transition-transform duration-500 ease-in-out">

                        @php
                            $promos = [
                                [
                                    'image' => '/images/1.png',
                                    'name' => 'Lorem Ipsum',
                                    'desc' => 'Lorem Ipsum is simply dummy text of the printing',
                                    'price' => '50.000',
                                    'orig' => '200.000',
                                    'active' => false,
                                ],
                                [
                                    'image' => '/images/2.png',
                                    'name' => 'Lorem Ipsum',
                                    'desc' => 'Lorem Ipsum is simply dummy text of the printing',
                                    'price' => '50.000',
                                    'orig' => '200.000',
                                    'active' => false,
                                ],
                                [
                                    'image' => '/images/3.png',
                                    'name' => 'Lorem Ipsum',
                                    'desc' => 'Lorem Ipsum is simply dummy text of the printing',
                                    'price' => '50.000',
                                    'orig' => '200.000',
                                    'active' => true,
                                ],
                                [
                                    'image' => '/images/4.png',
                                    'name' => 'Lorem Ipsum',
                                    'desc' => 'Lorem Ipsum is simply dummy text of the printing',
                                    'price' => '50.000',
                                    'orig' => '200.000',
                                    'active' => false,
                                ],
                                [
                                    'image' => '/images/5.png',
                                    'name' => 'Lorem Ipsum',
                                    'desc' => 'Lorem Ipsum is simply dummy text of the printing',
                                    'price' => '50.000',
                                    'orig' => '200.000',
                                    'active' => false,
                                ],
                            ];
                        @endphp

                        @foreach ($promos as $promo)
                            <div
                                class="promo-card shrink-0 w-[calc((100%-40px)/3)] bg-white rounded-[20px] overflow-visible shadow-[0_4px_24px_rgba(0,0,0,0.10)] transition-all duration-300 relative">

                                {{-- Badge --}}
                                <div
                                    class="absolute z-10 flex items-center gap-1.5 bg-[#7A8C5C] text-white text-2xl font-bold py-1.5 px-5 rounded-tl-[20px] rounded-br-[20px] font-['DM_Sans'] shadow-sm">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M7 7h.01M3 3h8l10 10a2 2 0 010 2.83l-5.17 5.17a2 2 0 01-2.83 0L3 11V3z" />
                                    </svg>
                                    30%
                                </div>

                                {{-- Product Image --}}
                                <div class="p-3">
                                    <div class="h-40 sm:h-44 md:h-48 overflow-hidden rounded-2xl shadow-md">
                                        <img src="{{ $promo['image'] }}" alt="{{ $promo['name'] }}"
                                            class="w-full h-full object-cover">
                                    </div>
                                </div>
                                {{-- Card Body --}}
                                <div class="p-4 pt-3">
                                    <p
                                        class="font-['Playfair_Display'] font-bold text-[#3D2B1F] text-[17px] mb-1 underline underline-offset-2">
                                        {{ $promo['name'] }}
                                    </p>
                                    <p class="font-['Cormorant_Garamond'] text-[14px] text-[#6B4C3B] leading-relaxed mb-4">
                                        {{ $promo['desc'] }}
                                    </p>

                                    {{-- Price Row --}}
                                    <div class="flex items-center bg-[#7A8C5C] rounded-full p-1.5 pr-2.5 gap-2.5">
                                        {{-- Price box --}}
                                        <div
                                            class="flex-1 bg-[#FAF6F0] border-2 border-[#7A8C5C] rounded-full px-4 py-2 text-center">
                                            <p class="text-[#7A8C5C] font-bold text-[15px] leading-tight">
                                                Rp {{ $promo['price'] }}</p>
                                            <p class="text-red-400 text-[11px] line-through leading-tight">
                                                Rp {{ $promo['orig'] }}</p>
                                        </div>
                                        {{-- Arrow button --}}
                                        <button
                                            class="w-10 h-10 bg-white rounded-full flex items-center justify-center shrink-0 hover:scale-110 transition-transform duration-200 shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="#7A8C5C" viewBox="0 0 24 24"
                                                stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>

                {{-- Next Button --}}
                <button id="promo-next"
                    class="shrink-0 w-12 h-12 rounded-full bg-[#7A7A5A] hover:bg-[#5C5C44] text-white flex items-center justify-center transition-colors duration-200 shadow-md z-10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

            </div>
        </div>
    </section>

    {{--  --}}
    <section class="relative overflow-hidden  py-14 px-10 bg-[#ADC178]">
        <div class="max-w-[1100px] mx-auto relative">
            <h2 class="font-['Playfair_Display'] text-7xl font-bold text-white text-center mb-10"
                style="text-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                Recently Added
            </h2>

            {{-- Slider wrapper --}}
            <div class="relative flex items-center gap-4">
                {{-- Cards Container --}}
                <div class="overflow-hidden flex-1">
                    <div id="promo-track" class="flex gap-5 transition-transform duration-500 ease-in-out">

                        @php
                            $promos = [
                                [
                                    'image' => '/images/1.png',
                                    'name' => 'Lorem Ipsum',
                                    'desc' => 'Lorem Ipsum is simply dummy text of the printing',
                                    'price' => '50.000',
                                    'orig' => '200.000',
                                    'active' => false,
                                ],
                                [
                                    'image' => '/images/2.png',
                                    'name' => 'Lorem Ipsum',
                                    'desc' => 'Lorem Ipsum is simply dummy text of the printing',
                                    'price' => '50.000',
                                    'orig' => '200.000',
                                    'active' => false,
                                ],
                                [
                                    'image' => '/images/3.png',
                                    'name' => 'Lorem Ipsum',
                                    'desc' => 'Lorem Ipsum is simply dummy text of the printing',
                                    'price' => '50.000',
                                    'orig' => '200.000',
                                    'active' => true,
                                ],
                                [
                                    'image' => '/images/4.png',
                                    'name' => 'Lorem Ipsum',
                                    'desc' => 'Lorem Ipsum is simply dummy text of the printing',
                                    'price' => '50.000',
                                    'orig' => '200.000',
                                    'active' => false,
                                ],
                                [
                                    'image' => '/images/5.png',
                                    'name' => 'Lorem Ipsum',
                                    'desc' => 'Lorem Ipsum is simply dummy text of the printing',
                                    'price' => '50.000',
                                    'orig' => '200.000',
                                    'active' => false,
                                ],
                            ];
                        @endphp

                        @foreach ($promos as $promo)
                            <div
                                class="promo-card shrink-0 w-[calc((100%-40px)/3)] max-w- bg-[#FFF9F2] rounded-[20px] overflow-visible shadow-[0_4px_24px_rgba(0,0,0,0.10)] transition-all duration-300 relative">
                                {{-- Product Image --}}
                                <div class="p-3">
                                    <div class="h-40 sm:h-44 md:h-48 overflow-hidden rounded-2xl shadow-md">
                                        <img src="{{ $promo['image'] }}" alt="{{ $promo['name'] }}"
                                            class="w-full h-full object-cover">
                                    </div>
                                </div>
                                {{-- Card Body --}}
                                <div class="p-4 pt-3">
                                    <p
                                        class="font-['Playfair_Display'] font-bold text-[#3D2B1F] text-[17px] mb-1 underline underline-offset-2">
                                        {{ $promo['name'] }}
                                    </p>
                                    <p class="font-['Cormorant_Garamond'] text-[14px] text-[#6B4C3B] leading-relaxed mb-4">
                                        {{ $promo['desc'] }}
                                    </p>

                                    {{-- Price Row --}}
                                    <div class="flex justify-between items-center bg-[#7A8C5C] rounded-full p-2 pl-6 pr-2.5">
                                        {{-- Price box --}}
                                            <p class="text-white font-bold text-[15px] leading-tight">
                                                Rp {{ $promo['price'] }}</p>

                                        {{-- Arrow button --}}
                                        <button
                                            class="w-10 h-10 bg-[#FFF9F2] rounded-full flex items-center justify-center shrink-0 hover:scale-110 transition-transform duration-200 shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="#7A8C5C" viewBox="0 0 24 24"
                                                stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== ALL PRODUCTS ===================== --}}
    <section id="all-products" class="bg-[#FAF6F0] py-16 px-10">
        <div class="max-w-[1100px] mx-auto">
            <h2 class="font-['Playfair_Display'] text-5xl font-bold text-[#3D2B1F] text-center mb-12">
                All Products
            </h2>

            <div class="grid grid-cols-3 gap-[22px]">
                @php
                    $allProducts = [
                        [
                            'emoji' => '🍪',
                            'name' => 'Lorem Ipsum',
                            'desc' => 'Lorem Ipsum is simply dummy text of the printing',
                            'bg' => 'linear-gradient(135deg,#C8A882,#A07850)',
                        ],
                        [
                            'emoji' => '🥧',
                            'name' => 'Lorem Ipsum',
                            'desc' => 'Lorem Ipsum is simply dummy text of the printing',
                            'bg' => 'linear-gradient(135deg,#D4A840,#B08820)',
                        ],
                        [
                            'emoji' => '🫙',
                            'name' => 'Lorem Ipsum',
                            'desc' => 'Lorem Ipsum is simply dummy text of the printing',
                            'bg' => 'linear-gradient(135deg,#8B6040,#6B4828)',
                        ],
                        [
                            'emoji' => '🥐',
                            'name' => 'Lorem Ipsum',
                            'desc' => 'Lorem Ipsum is simply dummy text of the printing',
                            'bg' => 'linear-gradient(135deg,#D4B896,#B09060)',
                        ],
                        [
                            'emoji' => '🎂',
                            'name' => 'Lorem Ipsum',
                            'desc' => 'Lorem Ipsum is simply dummy text of the printing',
                            'bg' => 'linear-gradient(135deg,#E8C860,#C0A030)',
                        ],
                        [
                            'emoji' => '🍩',
                            'name' => 'Lorem Ipsum',
                            'desc' => 'Lorem Ipsum is simply dummy text of the printing',
                            'bg' => 'linear-gradient(135deg,#A07850,#7A5830)',
                        ],
                        [
                            'emoji' => '🍮',
                            'name' => 'Lorem Ipsum',
                            'desc' => 'Lorem Ipsum is simply dummy text of the printing',
                            'bg' => 'linear-gradient(135deg,#7A8C5C,#5C6B44)',
                        ],
                        [
                            'emoji' => '🍰',
                            'name' => 'Lorem Ipsum',
                            'desc' => 'Lorem Ipsum is simply dummy text of the printing',
                            'bg' => 'linear-gradient(135deg,#C8A882,#A07850)',
                        ],
                        [
                            'emoji' => '🫐',
                            'name' => 'Lorem Ipsum',
                            'desc' => 'Lorem Ipsum is simply dummy text of the printing',
                            'bg' => 'linear-gradient(135deg,#8B7A9C,#6B5A7C)',
                        ],
                    ];
                @endphp

                @foreach ($allProducts as $i => $product)
                    <div class="product-card bg-white rounded-[20px] overflow-hidden shadow-[0_3px_16px_rgba(0,0,0,0.07)] opacity-0 translate-y-5"
                        data-index="{{ $i }}">
                        <div class="h-[165px] flex items-center justify-center text-[5rem]"
                            style="background: {{ $product['bg'] }};">
                            {{ $product['emoji'] }}
                        </div>
                        <div class="p-4">
                            <p class="font-['Playfair_Display'] font-semibold text-[#3D2B1F] text-[15px] mb-1.5">
                                {{ $product['name'] }}
                            </p>
                            <p class="font-['Cormorant_Garamond'] text-[13px] text-[#6B4C3B] leading-relaxed mb-3">
                                {{ $product['desc'] }}
                            </p>
                            <div class="flex items-center justify-between">
                                <span class="text-[#7A8C5C] font-bold text-sm font-['DM_Sans']">Rp 50.000</span>
                                <button
                                    class="w-[34px] h-[34px] bg-[#7A8C5C] border-none rounded-full cursor-pointer flex items-center justify-center transition-colors duration-200 hover:bg-[#5C6B44]">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="white" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===================== CTA BANNER ===================== --}}
    <section class="max-h-screen py-20 relative overflow-hidden bg-cover bg-center"
        style="background-image: url('/images/flirtimage.png');">

        <div class="relative max-w-6xl mx-auto px-6 grid grid-cols-1 md:grid-cols-2 gap-8 items-center">

            <div class="scroll-fade">
                <h2 class="font-display text-4xl md:text-5xl font-bold text-white leading-tight mb-8">
                    Dibuat dengan Hati,<br>
                    Dinikmati Sepenuh Jiwa
                </h2>

                <a href="#"
                    class="inline-flex items-center gap-3 bg-[#432818] text-white px-8 py-3.5 rounded-full font-medium text-sm hover:bg-[#6B4F3A] transition-all duration-300 shadow-lg hover:shadow-xl group">
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

    <style>
        @keyframes float {

            0%,
            100% {
                transform: translateY(0)
            }

            50% {
                transform: translateY(-10px)
            }
        }

        .product-card:hover {
            transform: translateY(-5px) !important;
            box-shadow: 0 14px 35px rgba(0, 0, 0, 0.13) !important;
        }
    </style>

    <script>
        const cards = document.querySelectorAll('.product-card');
        const obs = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const idx = parseInt(entry.target.dataset.index);
                    setTimeout(() => {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                        entry.target.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                    }, idx * 80);
                    obs.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.1
        });
        cards.forEach(c => obs.observe(c));

        (function() {
            const track = document.getElementById('promo-track');
            const cards = track.querySelectorAll('.promo-card');
            const total = cards.length;
            const visible = 3;
            let current = 0;

            function getCardWidth() {
                if (!cards[0]) return 0;
                return cards[0].offsetWidth + 20; // gap-5 = 20px
            }

            function update() {
                track.style.transform = `translateX(-${current * getCardWidth()}px)`;
            }

            document.getElementById('promo-prev').addEventListener('click', () => {
                if (current > 0) {
                    current--;
                    update();
                }
            });

            document.getElementById('promo-next').addEventListener('click', () => {
                if (current < total - visible) {
                    current++;
                    update();
                }
            });
        })();
    </script>

@endsection
