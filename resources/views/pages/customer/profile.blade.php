@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="min-h-screen bg-[#F5F0E8]">

    {{-- ===== SIDEBAR ===== --}}
    <x-sidebar active="dashboard" />

    {{-- ===== MAIN CONTENT ===== --}}
    <div class="lg:ml-[230px] flex flex-col gap-5 px-4 sm:px-6 lg:px-8 py-6 lg:py-8">

        {{-- Topbar --}}
        <div class="flex items-center gap-4 mb-2">
            <button id="sidebarToggle" class="lg:hidden text-[#3D2B1F]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <div class="flex-1 max-w-[300px]">
                <div class="flex items-center gap-2 bg-white rounded-full px-4 py-2 shadow-[0_2px_8px_rgba(0,0,0,0.06)]">
                    <svg class="w-4 h-4 text-[#9A8878]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
                    </svg>
                    <input type="text" placeholder="Search" class="font-glacial text-sm text-[#3D2B1F] placeholder-[#C4B8AE] outline-none bg-transparent w-full">
                </div>
            </div>
        </div>

        {{-- Profile Banner --}}
        <div class="bg-[#BB9457] rounded-[20px] px-4 sm:px-6 lg:px-8 py-4 sm:py-6 flex flex-col sm:flex-row items-center gap-4 sm:gap-6 shadow-[0_4px_20px_rgba(0,0,0,0.1)]">
            <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-white/20 overflow-hidden shrink-0 ring-4 ring-white/30">
                <img src="https://api.dicebear.com/7.x/adventurer/svg?seed=maimunah" alt="Avatar" class="w-full h-full object-cover">
            </div>
            <div class="flex-1 text-center sm:text-left">
                <h2 class="font-['Playfair_Display'] font-bold text-white text-xl sm:text-2xl">Maimunah Pasutri Gaje</h2>
                <p class="font-glacial text-white/80 text-sm mt-0.5">Admin</p>
            </div>
            <div class="text-center sm:text-right mt-2 sm:mt-0">
                <p class="font-glacial text-white/90 text-sm">maimunah@gmail.com</p>
                <p class="font-glacial text-white/90 text-sm mt-1">+62 877 4563 4859</p>
            </div>
        </div>

        {{-- Pesanan Saya --}}
        <div class="bg-white rounded-[20px] px-4 sm:px-6 lg:px-8 py-6 lg:py-7 shadow-[0_3px_16px_rgba(0,0,0,0.06)]">
            <h2 class="font-['Playfair_Display'] font-bold text-[#3D2B1F] text-xl sm:text-2xl mb-4 sm:mb-6">Pesanan Saya</h2>
            <div class="flex flex-wrap justify-center sm:justify-around">

                @php
                    $orders = [
                        ['label' => 'Belum Bayar', 'icon' => 'wallet'],
                        ['label' => 'Dikemas',     'icon' => 'box'],
                        ['label' => 'Diantar',     'icon' => 'truck'],
                        ['label' => 'Selesai',     'icon' => 'check'],
                    ];
                @endphp

                @foreach ($orders as $order)
                    <a href="#" class="flex flex-col items-center gap-3 group">
                        <div class="w-16 h-16 rounded-[16px]  flex items-center justify-center group-hover:bg-[#E8E0D4] transition-colors">
                            @if ($order['icon'] === 'wallet')
                                <svg class="w-7 h-7 text-[#7A8C5C]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h.01M11 15h2M3 6h18a1 1 0 011 1v10a1 1 0 01-1 1H3a1 1 0 01-1-1V7a1 1 0 011-1z"/>
                                </svg>
                            @elseif ($order['icon'] === 'box')
                                <svg class="w-7 h-7 text-[#7A8C5C]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0v10l-8 4m0-14v14m-8-4l8 4"/>
                                </svg>
                            @elseif ($order['icon'] === 'truck')
                                <svg class="w-7 h-7 text-[#7A8C5C]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M1 3h15v13H1zM16 8h4l3 3v5h-7V8zM5.5 19a1.5 1.5 0 100-3 1.5 1.5 0 000 3zM18.5 19a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                                </svg>
                            @else
                                <svg class="w-7 h-7 text-[#7A8C5C]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            @endif
                        </div>
                        <span class="font-glacial font-bold text-[#3D2B1F] text-xs">{{ $order['label'] }}</span>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Alamat Saya --}}
        <div class="bg-white rounded-[20px] px-4 sm:px-6 lg:px-8 py-6 lg:py-7 shadow-[0_3px_16px_rgba(0,0,0,0.06)]">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 sm:gap-0 mb-4 sm:mb-5">
                <h2 class="font-['Playfair_Display'] font-bold text-[#3D2B1F] text-xl sm:text-2xl">Alamat Saya</h2>
                <button class="bg-[#7A8C5C] hover:bg-[#5C6B44] text-white font-glacial text-xs font-bold px-4 sm:px-5 py-2 sm:py-2.5 rounded-full transition-colors self-start sm:self-auto">
                    + Tambah Alamat
                </button>
            </div>

            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-red-500 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5.05 8.05a5 5 0 119.9 0c0 3.535-4.95 8.95-4.95 8.95S5.05 11.585 5.05 8.05zM10 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                </svg>
                <div class="flex-1">
                    <div class="flex items-center gap-1">
                        <p class="font-glacial font-bold text-[#3D2B1F] text-sm">Maimunah Pasutri Gaje</p>
                        <span class="font-glacial text-[#6B4C3B] text-sm">(+62 083 7439 2934)</span>
                        <svg class="w-4 h-4 text-[#3D2B1F] ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                    <p class="font-glacial text-[#6B4C3B] text-sm mt-1 leading-relaxed">
                        Jl. Raya Persawahan No. 12 RT 03/RW 07, Dusun Sumber Rejeki, Desa Mekar Jaya, Kec. Cibeureum, Kab. Tasikmalaya, Jawa Barat 46196
                    </p>
                </div>
            </div>
        </div>

        {{-- Info Personal --}}
        <div class="bg-white rounded-[20px] px-4 sm:px-6 lg:px-8 py-6 lg:py-7 shadow-[0_3px_16px_rgba(0,0,0,0.06)]">

            <h2 class="font-['Playfair_Display'] font-bold text-[#3D2B1F] text-lg sm:text-xl mb-1">Info Personal</h2>
            <hr class="border-[#E8E0D4] mb-4 sm:mb-6">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6 sm:mb-8">
                <div>
                    <label class="font-glacial text-[#9A8878] text-xs mb-1.5 block">Nama lengkap</label>
                    <input type="text" value="Maimunah Pasutri Gaje"
                        class="w-full font-glacial text-sm text-[#3D2B1F] bg-[#F9F6F2] border border-[#E8E0D4] rounded-[10px] px-4 py-3 outline-none focus:border-[#7A8C5C] transition-colors">
                </div>
                <div>
                    <label class="font-glacial text-[#9A8878] text-xs mb-1.5 block">Nomor Telephone</label>
                    <input type="text" value="+62 877 4563 4859"
                        class="w-full font-glacial text-sm text-[#3D2B1F] bg-[#F9F6F2] border border-[#E8E0D4] rounded-[10px] px-4 py-3 outline-none focus:border-[#7A8C5C] transition-colors">
                </div>
            </div>

            <h2 class="font-['Playfair_Display'] font-bold text-[#3D2B1F] text-lg sm:text-xl mb-1">Autentifikasi</h2>
            <hr class="border-[#E8E0D4] mb-4 sm:mb-6">

            <div class="mb-4">
                <label class="font-glacial text-[#9A8878] text-xs mb-1.5 block">Email</label>
                <input type="email" value="maimunah@gmail.com"
                    class="w-full font-glacial text-sm text-[#3D2B1F] bg-[#F9F6F2] border border-[#E8E0D4] rounded-[10px] px-4 py-3 outline-none focus:border-[#7A8C5C] transition-colors">
            </div>

            {{-- Password hint --}}
            <div class="bg-[#EBF4FB] border border-[#B8D9F0] rounded-[10px] px-4 sm:px-5 py-3 sm:py-3.5 mb-4">
                <p class="font-glacial text-[#3A7EBD] text-sm">Kosongkan jika tidak ingin mengubah password</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6 sm:mb-8">
                <div>
                    <label class="font-glacial text-[#9A8878] text-xs mb-1.5 block">Password</label>
                    <input type="password" placeholder="••••••"
                        class="w-full font-glacial text-sm text-[#3D2B1F] bg-[#F9F6F2] border border-[#E8E0D4] rounded-[10px] px-4 py-3 outline-none focus:border-[#7A8C5C] transition-colors">
                </div>
                <div>
                    <label class="font-glacial text-[#9A8878] text-xs mb-1.5 block">Konfirmasi Password</label>
                    <input type="password" placeholder="••••••"
                        class="w-full font-glacial text-sm text-[#3D2B1F] bg-[#F9F6F2] border border-[#E8E0D4] rounded-[10px] px-4 py-3 outline-none focus:border-[#7A8C5C] transition-colors">
                </div>
            </div>

            <hr class="border-[#E8E0D4] mb-4 sm:mb-6">

            <h2 class="font-['Playfair_Display'] font-bold text-[#3D2B1F] text-lg sm:text-xl mb-3 sm:mb-4">Foto Profile</h2>

            <div class="flex flex-col sm:flex-row items-center gap-4 sm:gap-5">
                <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full overflow-hidden shrink-0 ring-2 ring-[#E8E0D4]">
                    <img src="https://api.dicebear.com/7.x/adventurer/svg?seed=maimunah" alt="Avatar" class="w-full h-full object-cover">
                </div>
                <div class="flex-1 bg-[#F9F6F2] border border-[#E8E0D4] rounded-[12px] px-4 sm:px-5 py-3 sm:py-4 w-full">
                    <div class="flex items-center gap-3 mb-1">
                        <label class="bg-white border border-[#E8E0D4] font-glacial text-sm text-[#3D2B1F] px-3 sm:px-4 py-1 sm:py-1.5 rounded-[8px] cursor-pointer hover:bg-[#F0EDE6] transition-colors">
                            Choose File
                            <input type="file" class="hidden" accept=".jpg,.jpeg,.png">
                        </label>
                        <span class="font-glacial text-[#9A8878] text-sm">No file Chosen</span>
                    </div>
                    <p class="font-glacial text-[#9A8878] text-xs">Format: JPG, JPEG, PNG. Max: 2MB</p>
                </div>
            </div>

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
