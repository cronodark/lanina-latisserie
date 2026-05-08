@auth

    @if (auth()->user()->hasRole('admin'))
        {{-- ===================== SIDEBAR ADMIN ===================== --}}
        <aside id="sidebar" class="fixed top-0 left-0 h-screen w-[240px] bg-[#8A9E5B] flex flex-col z-40 overflow-hidden">

            {{-- Logo --}}
            <div class="px-6 py-5 border-b border-white/20">
                <a href="{{ route('beranda') }}" class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full border-2 border-white flex items-center justify-center">
                        <svg viewBox="0 0 24 24" class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M12 2C8 2 5 5 5 9c0 2 1 4 2.5 5.5L12 22l4.5-7.5C18 13 19 11 19 9c0-4-3-7-7-7z" />
                        </svg>
                    </div>
                    <div class="hide-text">
                        <p class="font-bold text-white text-sm leading-none tracking-wide">LANINA</p>
                        <p class="text-[8px] text-white/80 tracking-[0.2em] uppercase leading-none">PATISSERIE</p>
                    </div>
                </a>
            </div>

            {{-- Nav --}}
            <nav class="flex-1 px-4 py-6 overflow-y-auto space-y-1">

                <a href="/dashboard"
                    class="block px-3 py-2 rounded-lg text-white font-semibold text-sm hover:bg-white/10 transition
                        {{ request()->routeIs('dashboard') ? 'bg-white/10' : '' }}">
                    <span class="hide-text">Dashboard</span>
                </a>

                <a href="/pesanan"
                    class="block px-3 py-2 rounded-lg text-white font-semibold text-sm hover:bg-white/10 transition
                        {{ request()->routeIs('pesanan.*') ? 'bg-white/10' : '' }}">
                    <span class="hide-text">Manajemen Pesanan</span>
                </a>

                {{-- Manajemen Produk --}}
                <div x-data="{ open: {{ request()->routeIs('product-admin.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                        class="w-full text-left px-3 py-2 rounded-lg text-white font-semibold text-sm hover:bg-white/10 transition flex items-center justify-between">
                        <span class="hide-text">Manajemen Produk</span>
                        <svg class="w-3 h-3 transition-transform duration-200" :class="open ? 'rotate-180' : ''"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-transition class="ml-3 mt-1 space-y-1 border-l border-white/30 pl-3">
                        <a href="{{ route('product-admin.index') }}"
                            class="block py-1.5 text-xs hover:text-white transition
                                {{ request()->routeIs('product-admin.index') ? 'text-white font-semibold' : 'text-white/80' }}">
                            <span class="hide-text">Daftar Produk</span>
                        </a>
                        <a href="{{ route('product-admin.create') }}"
                            class="block py-1.5 text-xs hover:text-white transition
                                {{ request()->routeIs('product-admin.create') ? 'text-white font-semibold' : 'text-white/80' }}">
                            <span class="hide-text">Tambah Produk</span>
                        </a>
                    </div>
                </div>

                {{-- Laporan Penjualan --}}
                <a href="{{ route('laporan.index') }}"
                    class="block px-3 py-2 rounded-lg text-white font-semibold text-sm hover:bg-white/10 transition
                        {{ request()->routeIs('laporan.*') ? 'bg-white/10' : '' }}">
                    <span class="hide-text">Laporan Penjualan</span>
                </a>

                {{-- Manajemen Promosi --}}
                <div x-data="{ open: {{ request()->routeIs('promo-admin.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                        class="w-full text-left px-3 py-2 rounded-lg text-white font-semibold text-sm hover:bg-white/10 transition flex items-center justify-between">
                        <span class="hide-text">Manajemen Promosi</span>
                        <svg class="w-3 h-3 transition-transform duration-200" :class="open ? 'rotate-180' : ''"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-transition class="ml-3 mt-1 space-y-1 border-l border-white/30 pl-3">
                        <a href="{{ route('promo-admin.rekomendasi') }}"
                            class="block py-1.5 text-xs hover:text-white transition
                                {{ request()->routeIs('promo-admin.rekomendasi') ? 'text-white font-semibold' : 'text-white/80' }}">
                            <span class="hide-text">Rekomendasi Produk Promosi</span>
                        </a>
                        <a href="{{ route('promo-admin.create') }}"
                            class="block py-1.5 text-xs hover:text-white transition
                                {{ request()->routeIs('promo-admin.create') ? 'text-white font-semibold' : 'text-white/80' }}">
                            <span class="hide-text">Tambah Promosi Produk</span>
                        </a>
                        <a href="{{ route('promo-admin.status', 'aktif') }}"
                            class="block py-1.5 text-xs hover:text-white transition
                                {{ request()->routeIs('promo-admin.status') ? 'text-white font-semibold' : 'text-white/80' }}">
                            <span class="hide-text">Status Promosi</span>
                        </a>
                    </div>
                </div>

                {{-- Manajemen Jadwal --}}
                <div x-data="{ open: {{ request()->routeIs('jadwal-admin.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                        class="w-full text-left px-3 py-2 rounded-lg text-white font-semibold text-sm hover:bg-white/10 transition flex items-center justify-between">
                        <span class="hide-text">Manajemen Jadwal</span>
                        <svg class="w-3 h-3 transition-transform duration-200" :class="open ? 'rotate-180' : ''"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-transition class="ml-3 mt-1 space-y-1 border-l border-white/30 pl-3">
                        <a href="{{ route('jadwal-admin.kalender') }}"
                            class="block py-1.5 text-xs hover:text-white transition
                                {{ request()->routeIs('jadwal-admin.kalender') ? 'text-white font-semibold' : 'text-white/80' }}">
                            <span class="hide-text">Kalender Pesanan</span>
                        </a>
                        <a href="{{ route('jadwal-admin.slot') }}"
                            class="block py-1.5 text-xs hover:text-white transition
                                {{ request()->routeIs('jadwal-admin.slot') ? 'text-white font-semibold' : 'text-white/80' }}">
                            <span class="hide-text">Slot Preorder</span>
                        </a>
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
                    <span class="hide-text">Logout</span>
                </a>
            </div>

        </aside>

    @elseif(auth()->user()->hasRole('customer'))
        {{-- ===================== SIDEBAR CUSTOMER ===================== --}}
        {{-- Overlay mobile (logic tidak diubah) --}}
        <div id="sidebarOverlay" class="fixed inset-0 bg-black/30 z-30 hidden lg:hidden"></div>

        <aside id="sidebar"
            class="fixed top-0 left-0 h-screen w-[240px] bg-[#8A9E5B] flex flex-col z-40 overflow-hidden
                   transform -translate-x-full lg:translate-x-0 transition-transform duration-300">

            {{-- Logo — sama persis dengan admin --}}
            <div class="px-6 py-5 border-b border-white/20">
                <a href="{{ route('beranda') }}" class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full border-2 border-white flex items-center justify-center">
                        <svg viewBox="0 0 24 24" class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M12 2C8 2 5 5 5 9c0 2 1 4 2.5 5.5L12 22l4.5-7.5C18 13 19 11 19 9c0-4-3-7-7-7z" />
                        </svg>
                    </div>
                    <div class="hide-text">
                        <p class="font-bold text-white text-sm leading-none tracking-wide">LANINA</p>
                        <p class="text-[8px] text-white/80 tracking-[0.2em] uppercase leading-none">PATISSERIE</p>
                    </div>
                </a>
            </div>

            {{-- Nav --}}
            <nav class="flex-1 px-4 py-6 overflow-y-auto space-y-1">

                {{-- Profil Saya --}}
                <a href="{{ route('profile.index') }}"
                    class="block px-3 py-2 rounded-lg text-white font-semibold text-sm hover:bg-white/10 transition
                        {{ Route::currentRouteName() == 'profile.index' ? 'bg-white/10' : '' }}">
                    <span class="hide-text">Profil Saya</span>
                </a>

                {{-- Alamat Saya --}}
                <div x-data="{ open: {{ in_array(Route::currentRouteName(), ['profile.address.index', 'profile.address.create']) ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                        class="w-full text-left px-3 py-2 rounded-lg text-white font-semibold text-sm hover:bg-white/10 transition flex items-center justify-between">
                        <span class="hide-text">Alamat Saya</span>
                        <svg class="w-3 h-3 transition-transform duration-200" :class="open ? 'rotate-180' : ''"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-transition class="ml-3 mt-1 space-y-1 border-l border-white/30 pl-3">
                        <a href="{{ route('profile.address.index') }}"
                            class="block py-1.5 text-xs hover:text-white transition
                                {{ Route::currentRouteName() == 'profile.address.index' ? 'text-white font-semibold' : 'text-white/80' }}">
                            <span class="hide-text">Daftar Alamat</span>
                        </a>
                        <a href="{{ route('profile.address.create') }}"
                            class="block py-1.5 text-xs hover:text-white transition
                                {{ Route::currentRouteName() == 'profile.address.create' ? 'text-white font-semibold' : 'text-white/80' }}">
                            <span class="hide-text">Tambah Alamat</span>
                        </a>
                    </div>
                </div>

                {{-- Pesanan Saya --}}
                <div x-data="{ open: {{ in_array(($active ?? ''), ['belum-bayar','diproses','diantar','selesai']) ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                        class="w-full text-left px-3 py-2 rounded-lg text-white font-semibold text-sm hover:bg-white/10 transition flex items-center justify-between">
                        <span class="hide-text">Pesanan Saya</span>
                        <svg class="w-3 h-3 transition-transform duration-200" :class="open ? 'rotate-180' : ''"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-transition class="ml-3 mt-1 space-y-1 border-l border-white/30 pl-3">
                        <a href="{{ route('profile.preorder.index', ['tab' => 'belum-bayar']) }}"
                            class="block py-1.5 text-xs hover:text-white transition
                                {{ ($active ?? '') === 'belum-bayar' ? 'text-white font-semibold' : 'text-white/80' }}">
                            <span class="hide-text">Belum Bayar</span>
                        </a>
                        <a href="{{ route('profile.preorder.index', ['tab' => 'diproses']) }}"
                            class="block py-1.5 text-xs hover:text-white transition
                                {{ ($active ?? '') === 'diproses' ? 'text-white font-semibold' : 'text-white/80' }}">
                            <span class="hide-text">Diproses</span>
                        </a>
                        <a href="{{ route('profile.preorder.index', ['tab' => 'diantar']) }}"
                            class="block py-1.5 text-xs hover:text-white transition
                                {{ ($active ?? '') === 'diantar' ? 'text-white font-semibold' : 'text-white/80' }}">
                            <span class="hide-text">Diantar</span>
                        </a>
                        <a href="{{ route('profile.preorder.index', ['tab' => 'selesai']) }}"
                            class="block py-1.5 text-xs hover:text-white transition
                                {{ ($active ?? '') === 'selesai' ? 'text-white font-semibold' : 'text-white/80' }}">
                            <span class="hide-text">Selesai</span>
                        </a>
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
                    <span class="hide-text">Logout</span>
                </a>
            </div>

        </aside>
    @endif

@endauth
