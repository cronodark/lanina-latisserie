@props(['active' => []])

{{-- OVERLAY --}}
<div id="sidebarOverlay"
    class="fixed inset-0 bg-black/30 z-30 hidden lg:hidden"></div>

{{-- SIDEBAR --}}
<aside id="sidebar"
    class="fixed top-0 left-0 z-40 w-[230px] min-h-screen bg-[#A9BC7A]
           flex flex-col px-6 py-8 font-glacial
           transform -translate-x-full lg:translate-x-0
           transition-transform duration-300">

    {{-- Logo --}}
    <div class="mb-10">
        <img src="{{ asset('images/logo.png') }}" alt="La Nina" class="w-[140px] object-contain">
    </div>

    <nav class="flex flex-col gap-7 text-md ml-2">

        {{-- Dashboard --}}
        <a href="{{ route('profil') }}"
            class="font-semibold transition
            {{ $active === 'dashboard' ? 'text-white' : 'text-white/70 hover:text-white' }}">
            Dashboard
        </a>

        {{-- Alamat --}}
        <div>
            <p class="text-white font-semibold mb-2">Alamat Saya</p>
            <div class="flex flex-col gap-2 ml-2">
                <a href="{{ route('alamat') }}"
                    class="transition
                    {{ $active === 'daftar-alamat' ? 'text-white font-medium' : 'text-white/70 hover:text-white' }}">
                    Daftar Alamat
                </a>
                <a href="{{ route('tambah-alamat') }}"
                    class="transition
                    {{ $active === 'tambah-alamat' ? 'text-white font-medium' : 'text-white/70 hover:text-white' }}">
                    Tambah Alamat
                </a>
            </div>
        </div>

        <div>
            <p class="text-white font-semibold mb-2">Pesanan Saya</p>
            <div class="flex flex-col gap-2 ml-2">
                <a href="{{ route('alamat') }}"
                    class="transition
                    {{ $active === 'daftar-alamat' ? 'text-white font-medium' : 'text-white/70 hover:text-white' }}">
                    Belum Bayar
                </a>
                <a href="{{ route('diproses') }}"
                    class="transition
                    {{ $active === 'diproses' ? 'text-white font-medium' : 'text-white/70 hover:text-white' }}">
                    Diproses
                </a>
                <a href="{{ route('tambah-alamat') }}"
                    class="transition
                    {{ $active === 'tambah-alamat' ? 'text-white font-medium' : 'text-white/70 hover:text-white' }}">
                    Diantar
                </a>
                <a href="{{ route('tambah-alamat') }}"
                    class="transition
                    {{ $active === 'tambah-alamat' ? 'text-white font-medium' : 'text-white/70 hover:text-white' }}">
                    Selesai
                </a>
            </div>
        </div>
    </nav>
</aside>
