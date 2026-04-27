@extends('layouts.app')

@section('title', 'Belum Bayar')

@section('content')

    <div class="min-h-screen bg-[#FBFEF3]">

        <x-sidebar active="belum-bayar" />

        {{-- MAIN CONTENT --}}
        <div class="lg:ml-[270px] flex flex-col gap-6 px-4 sm:px-6 lg:px-10 py-8 lg:py-10">

            {{-- Topbar --}}
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

            {{-- Tab Navigation --}}
            <x-cusNavbar active="belum-bayar"/>

            {{-- Header Banner --}}
            <div class="bg-[#BB9457] rounded-[24px] px-6 sm:px-8 lg:px-10 py-6 sm:py-8 flex items-center gap-5">
                <svg class="w-14 h-14 text-white shrink-0" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M46.25 38.3333H46.2817M3.5 9.83333V54.1667C3.5 57.6645 6.33553 60.5 9.83333 60.5H54.1667C57.6645 60.5 60.5 57.6645 60.5 54.1667V22.5C60.5 19.0022 57.6645 16.1667 54.1667 16.1667L9.83333 16.1667C6.33553 16.1667 3.5 13.3311 3.5 9.83333ZM3.5 9.83333C3.5 6.33553 6.33553 3.5 9.83333 3.5H47.8333M47.8333 38.3333C47.8333 39.2078 47.1245 39.9167 46.25 39.9167C45.3755 39.9167 44.6667 39.2078 44.6667 38.3333C44.6667 37.4589 45.3755 36.75 46.25 36.75C47.1245 36.75 47.8333 37.4589 47.8333 38.3333Z" stroke="white" stroke-width="7" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <h1 class="font-glacial text-white text-3xl sm:text-4xl">
                    Pesanan Saya
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
                        'paid'  => false,
                    ],
                    [
                        'name'  => 'Lorem Ipsum',
                        'desc'  => 'Lorem Ipsum is simply dummy text of the printing',
                        'qty'   => '2x',
                        'total' => 'Rp 100.000',
                        'image' => 'https://images.unsplash.com/photo-1464349095431-e9a21285b5f3?w=400&h=300&fit=crop',
                        'paid'  => true,
                    ],
                    [
                        'name'  => 'Lorem Ipsum',
                        'desc'  => 'Lorem Ipsum is simply dummy text of the printing',
                        'qty'   => '2x',
                        'total' => 'Rp 100.000',
                        'image' => 'https://images.unsplash.com/photo-1499636136210-6f4ee915583e?w=400&h=300&fit=crop',
                        'paid'  => false,
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
                                <div class="flex-1">
                                    <div class="flex items-start justify-between gap-4">
                                        <h3 class="font-['Playfair_Display'] font-bold text-[#3D2B1F] text-2xl mb-2">
                                            {{ $order['name'] }}
                                        </h3>
                                        <span class="font-poppins font-bold text-[#3D2B1F] text-xl shrink-0">
                                            {{ $order['qty'] }}
                                        </span>
                                    </div>
                                    <div class="flex gap-6">
                                        <p class="font-poppins text-[#6B4C3B] text-base leading-relaxed flex-1">
                                            {{ $order['desc'] }}
                                        </p>
                                        <p class="font-poppins text-[#6B4C3B] text-base leading-relaxed flex-1">
                                            {{ $order['desc'] }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between mt-5">
                                <p class="font-poppins font-bold text-[#3D2B1F] text-lg">
                                    Total: <span class="text-[#6A7941] text-xl ml-1">{{ $order['total'] }}</span>
                                </p>
                                <div class="flex items-center gap-3">
                                    <button
                                        onclick="batalPesanan({{ $i }})"
                                        class="bg-red-400 hover:bg-red-500 text-white font-poppins font-semibold text-sm px-6 py-2.5 rounded-full transition-colors">
                                        Batal
                                    </button>
                                    <button
                                        onclick="bayarPesanan({{ $i }})"
                                        class="bg-[#7A8C5C] hover:bg-[#5C6B44] text-white font-poppins font-semibold text-sm px-6 py-2.5 rounded-full transition-colors">
                                        Bayar
                                    </button>
                                </div>
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

            function batalPesanan(index) {
                Swal.fire({
                    title: 'Batalkan Pesanan?',
                    text: 'Pesanan ini akan dibatalkan secara permanen.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Batalkan',
                    cancelButtonText: 'Kembali',
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#9A8878',
                    customClass: {
                        popup: 'rounded-[20px] font-poppins',
                        confirmButton: 'rounded-full px-6 py-2 text-sm font-medium',
                        cancelButton: 'rounded-full px-6 py-2 text-sm font-medium',
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Dibatalkan!',
                            text: 'Pesanan berhasil dibatalkan.',
                            icon: 'success',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#7A8C5C',
                            customClass: {
                                popup: 'rounded-[20px] font-poppins',
                                confirmButton: 'rounded-full px-6 py-2 text-sm font-medium',
                            }
                        });
                    }
                });
            }

            function bayarPesanan(index) {
                Swal.fire({
                    title: 'Lanjutkan Pembayaran?',
                    text: 'Anda akan diarahkan ke halaman pembayaran.',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Bayar',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#7A8C5C',
                    cancelButtonColor: '#9A8878',
                    customClass: {
                        popup: 'rounded-[20px] font-poppins',
                        confirmButton: 'rounded-full px-6 py-2 text-sm font-medium',
                        cancelButton: 'rounded-full px-6 py-2 text-sm font-medium',
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Arahkan ke halaman pembayaran
                        // window.location.href = '/pembayaran/' + index;
                    }
                });
            }

            window.batalPesanan = batalPesanan;
            window.bayarPesanan = bayarPesanan;
        }
    </script>

@endsection
