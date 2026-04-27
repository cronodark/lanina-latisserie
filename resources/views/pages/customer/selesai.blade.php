@extends('layouts.app')

@section('title', 'Diantar')

@section('content')

    <div class="min-h-screen bg-[#FBFEF3]">

        <x-sidebar active="diantar" />

        {{-- MAIN CONTENT --}}
        <div class="lg:ml-[270px] flex flex-col gap-6 px-4 sm:px-6 lg:px-10 py-8 lg:py-10">

            {{-- Topbar - Search Button --}}
            <div class="flex items-center gap-4 mb-2">
                <button id="sidebarToggle" class="lg:hidden text-[#3D2B1F]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <div class="flex-1 max-w-[360px]">
                    <div class="flex items-center gap-2 bg-[#F5F6FA] border border-[#D5D5D5] rounded-full px-5 py-3 shadow-[0_2px_8px_rgba(0,0,0,0.06)]">
                        <svg class="w-5 h-5 text-[#9A8878]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z" />
                        </svg>
                        <input type="text" placeholder="Search"
                            class="font-nunito text-sm text-[#3D2B1F] placeholder-[#C4B8AE] outline-none bg-transparent w-full">
                    </div>
                </div>
            </div>

            <x-cusNavbar active="selesai"/>

            {{-- Header Banner --}}
            <div class="bg-[#BB9457] rounded-[24px] px-6 sm:px-8 lg:px-10 py-6 sm:py-8 flex items-center gap-5">
                <svg class="w-14 h-14 text-white shrink-0" viewBox="0 0 67 71" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M60.5162 44.8488C61.8142 43.5508 63.9182 43.5508 65.2162 44.8488C66.5135 46.1469 66.514 48.2511 65.2162 49.5489L53.2487 61.5132C51.9506 62.8109 49.8465 62.8111 48.5486 61.5132L44.5594 57.524C43.2631 56.2259 43.2622 54.1215 44.5594 52.824C45.8575 51.5259 47.9647 51.5259 49.2627 52.824L50.8986 54.4599L60.5162 44.8488ZM6.64756 49.2665L27.6322 61.3833V39.0809L6.64756 26.1688V49.2665ZM9.0203 19.8263L30.9073 33.2935L38.441 28.3987L17.0084 15.2107L9.0203 19.8263ZM23.5521 11.4357L44.6114 24.3933L52.2295 19.4433L30.956 7.16091L23.5521 11.4357ZM61.9152 35.2313C61.9152 37.0667 60.4267 38.5546 58.5914 38.5551C56.7557 38.5551 55.2676 37.067 55.2676 35.2313V25.3995L34.2797 39.0322V61.3833L35.2762 60.8088C36.8659 59.8912 38.8994 60.4364 39.8172 62.026C40.7347 63.6157 40.1896 65.6493 38.6 66.567L32.6178 70.0206C31.5897 70.6135 30.322 70.6141 29.2941 70.0206L1.66189 54.0639C0.634224 53.4703 0.000464205 52.3748 0 51.188V19.2778C0.000229939 18.0906 0.633696 16.9923 1.66189 16.3987L14.7817 8.81956C15.1246 8.51324 15.5248 8.28867 15.9503 8.14766L29.2941 0.445184L29.6901 0.250431C30.6353 -0.139387 31.718 -0.0738496 32.6178 0.445184L60.2533 16.3987C61.2814 16.9923 61.9149 18.0906 61.9152 19.2778V35.2313Z" fill="white" />
                </svg>
                <h1 class="font-glacial text-white text-3xl sm:text-4xl">
                    Selesai
                </h1>
            </div>

            {{-- Order List --}}
            @php
                $orders = [
                    [
                        'name'  => 'Lorem Ipsum',
                        'desc'  => 'Lorem Ipsum is simply dummy text of the printing',
                        'qty'   => '2x',
                        'total' => 'Rp 100.000',
                        'image' => 'https://images.unsplash.com/photo-1558961363-fa8fdf82db35?w=400&h=300&fit=crop',
                    ],
                    [
                        'name'  => 'Lorem Ipsum',
                        'desc'  => 'Lorem Ipsum is simply dummy text of the printing',
                        'qty'   => '2x',
                        'total' => 'Rp 100.000',
                        'image' => 'https://images.unsplash.com/photo-1464349095431-e9a21285b5f3?w=400&h=300&fit=crop',
                    ],
                    [
                        'name'  => 'Lorem Ipsum',
                        'desc'  => 'Lorem Ipsum is simply dummy text of the printing',
                        'qty'   => '2x',
                        'total' => 'Rp 100.000',
                        'image' => 'https://images.unsplash.com/photo-1499636136210-6f4ee915583e?w=400&h=300&fit=crop',
                    ],
                ];
            @endphp

            <div class="bg-white rounded-[24px] px-6 py-6 card-shadow flex flex-col gap-4">
                @foreach ($orders as $i => $order)
                    <div class="bg-[#FAF6EF] rounded-[20px] overflow-hidden flex flex-row">

                        {{-- Product Image --}}
                        <div class="w-[250px] shrink-0 aspect-[4/3] p-4">
                            <img src="{{ $order['image'] }}" alt="{{ $order['name'] }}"
                                class="w-full h-full object-cover rounded-[20px]">
                        </div>

                        {{-- Product Info --}}
                        <div class="flex-1 px-6 py-6 flex flex-col justify-between">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <h3 class="font-['Playfair_Display'] font-bold text-[#3D2B1F] text-2xl mb-2">
                                        {{ $order['name'] }}
                                    </h3>
                                    <p class="font-poppins text-[#6B4C3B] text-base leading-relaxed">
                                        {{ $order['desc'] }}
                                    </p>
                                </div>
                                <span class="font-poppins font-bold text-[#3D2B1F] text-xl shrink-0">
                                    {{ $order['qty'] }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between mt-5">
                                <p class="font-poppins font-bold text-[#3D2B1F] text-lg">
                                    Total: <span class="text-[#6A7941] text-xl ml-1">{{ $order['total'] }}</span>
                                </p>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>

        </div>
    </div>

    <script>
        {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.getElementById('sidebarToggle');
            const overlay = document.getElementById('sidebarOverlay');

            toggle.addEventListener('click', () => {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
            });

            overlay.addEventListener('click', () => {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            });
        }
    </script>

@endsection
