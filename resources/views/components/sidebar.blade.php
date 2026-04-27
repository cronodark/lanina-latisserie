{{-- ===================== SIDEBAR (Admin) ===================== --}}
<aside id="sidebar"
    class="fixed top-0 left-0 h-screen w-[240px] bg-[#8A9E5B] flex flex-col z-40">

    {{-- Logo --}}
    <div class="px-6 py-5 border-b border-white/20">
        <a href="{{ route('beranda') }}" class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-full border-2 border-white flex items-center justify-center">
                <svg viewBox="0 0 24 24" class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M12 2C8 2 5 5 5 9c0 2 1 4 2.5 5.5L12 22l4.5-7.5C18 13 19 11 19 9c0-4-3-7-7-7z" />
                </svg>
            </div>
            <div>
                <p class="font-bold text-white text-sm leading-none tracking-wide">LA NINA</p>
                <p class="text-[8px] text-white/80 tracking-[0.2em] uppercase leading-none">PATISSERIE</p>
            </div>
        </a>
    </div>

    {{-- Nav --}}
    <nav class="flex-1 px-4 py-6 overflow-y-auto space-y-1">

        <a href="{{ route('dashboard') }}"
            class="block px-3 py-2 rounded-lg text-white font-semibold text-sm hover:bg-white/10 transition
                   {{ request()->routeIs('dashboard') ? 'bg-white/10' : '' }}">
            Dashboard
        </a>

        <a href="#"
            class="block px-3 py-2 rounded-lg text-white font-semibold text-sm hover:bg-white/10 transition">
            Manajemen Pesanan
        </a>

        {{-- Manajemen Produk --}}
        <div x-data="{ open: {{ request()->routeIs('product-admin.*') ? 'true' : 'false' }} }">
            <button @click="open = !open"
                class="w-full text-left px-3 py-2 rounded-lg text-white font-semibold text-sm hover:bg-white/10 transition flex items-center justify-between">
                Manajemen Produk
                <svg class="w-3 h-3 transition-transform duration-200" :class="open ? 'rotate-180' : ''"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div x-show="open" x-transition class="ml-3 mt-1 space-y-1 border-l border-white/30 pl-3">
                <a href="{{ route('product-admin.index') }}"
                    class="block py-1.5 text-xs hover:text-white transition
                           {{ request()->routeIs('product-admin.index') ? 'text-white font-semibold' : 'text-white/80' }}">
                    Daftar Produk
                </a>
                <a href="{{ route('product-admin.create') }}"
                    class="block py-1.5 text-xs hover:text-white transition
                           {{ request()->routeIs('product-admin.create') ? 'text-white font-semibold' : 'text-white/80' }}">
                    Tambah Produk
                </a>
            </div>
        </div>

        <a href="#"
            class="block px-3 py-2 rounded-lg text-white font-semibold text-sm hover:bg-white/10 transition">
            Laporan Penjualan
        </a>

        {{-- Manajemen Promosi --}}
        <div x-data="{ open: false }">
            <button @click="open = !open"
                class="w-full text-left px-3 py-2 rounded-lg text-white font-semibold text-sm hover:bg-white/10 transition flex items-center justify-between">
                Manajemen Promosi
                <svg class="w-3 h-3 transition-transform duration-200" :class="open ? 'rotate-180' : ''"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div x-show="open" x-transition class="ml-3 mt-1 space-y-1 border-l border-white/30 pl-3">
                <a href="#" class="block py-1.5 text-white/80 text-xs hover:text-white transition">Rekomendasi Produk Promosi</a>
                <a href="#" class="block py-1.5 text-white/80 text-xs hover:text-white transition">Produk Dalam Promosi</a>
                <a href="#" class="block py-1.5 text-white/80 text-xs hover:text-white transition">Tambah Promosi Produk</a>
                <a href="#" class="block py-1.5 text-white/80 text-xs hover:text-white transition">Status Promosi</a>
            </div>
        </div>

    </nav>

    {{-- Logout --}}
    <div class="px-4 py-4 border-t border-white/20">
        <a href="{{ route('logout') }}"
            class="flex items-center gap-2 px-3 py-2 rounded-lg text-white/80 text-sm hover:bg-white/10 hover:text-white transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
            Logout
        </a>
    </div>

</aside>