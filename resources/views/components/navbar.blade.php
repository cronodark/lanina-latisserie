<nav class="fixed top-0 left-0 right-0 z-50 bg-black/30 backdrop-blur-sm">

    <div
        class="max-w-[1400px] 2xl:max-w-[1600px] mx-auto
                px-4 sm:px-6 md:px-10 lg:px-16 xl:px-24 2xl:px-32
                py-3 flex items-center justify-between gap-6 xl:gap-10">

        {{-- Logo --}}
        <a href="{{ route('beranda') }}">
            <div class="flex items-center gap-2 sm:gap-3">
                <div
                    class="w-8 h-8 sm:w-9 sm:h-9 xl:w-10 xl:h-10 rounded-full border-2 border-sage flex items-center justify-center text-white">
                    <svg viewBox="0 0 24 24" class="w-4 h-4 xl:w-5 xl:h-5 text-white" fill="none" stroke="currentColor"
                        stroke-width="1.5">
                        <path d="M12 2C8 2 5 5 5 9c0 2 1 4 2.5 5.5L12 22l4.5-7.5C18 13 19 11 19 9c0-4-3-7-7-7z" />
                    </svg>
                </div>
                <div>
                    <p
                        class="font-display font-bold text-white text-base sm:text-lg xl:text-xl leading-none tracking-wide">
                        LA NNA
                    </p>
                    <p
                        class="text-[8px] sm:text-[9px] xl:text-[10px] text-white tracking-[0.2em] uppercase leading-none">
                        PATISSERIE
                    </p>
                </div>
            </div>
        </a>

        {{-- Nav Links (Desktop) --}}
        <div class="hidden md:flex items-center gap-6 lg:gap-8 xl:gap-10 text-white">
            <a href="{{ route('beranda') }}#about" class="text-sm lg:text-base xl:text-lg text-brown font-medium hover:text-sage transition">
                Tentang Kami
            </a>
            <a href="{{ route('beranda') }}#bestseller"
                class="text-sm lg:text-base xl:text-lg text-brown font-medium hover:text-sage transition">
                Paling Laris
            </a>
            <a href="{{ route('beranda') }}#our-product"
                class="text-sm lg:text-base xl:text-lg text-brown font-medium hover:text-sage transition">
                Produk Kami
            </a>
        </div>

        {{-- Right Section --}}
        <div class="flex items-center gap-3 sm:gap-4 text-white">

            @auth
                @if (auth()->user()->hasRole('customer'))
                    {{-- Cart Icon --}}
                    <a href="{{ route('cart.index') }}"
                        class="relative w-9 h-9 rounded-full border border-sage/40 flex items-center justify-center hover:bg-sage/10 transition-colors">
                        <svg class="w-4 h-4 text-brown" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </a>
                @endif
            @endauth

            {{-- Account Menu --}}
            <details class="relative group">
                <summary
                    class="list-none cursor-pointer w-9 h-9 rounded-full border border-sage/40 flex items-center justify-center hover:bg-sage/10 transition-colors">
                    <svg class="w-4 h-4 text-brown" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </summary>

                <div
                    class="absolute right-0 mt-2 w-44 rounded-xl border border-cream-dark bg-warm-white shadow-lg py-2 z-50 bg-white">
                    @guest
                        <a href="{{ route('login') }}"
                            class="block px-4 py-2 text-sm text-black hover:bg-[#f0f0f0] transition-colors">Login</a>
                        <a href="{{ route('register') }}"
                            class="block px-4 py-2 text-sm text-black hover:bg-[#f0f0f0] transition-colors">Register</a>
                    @endguest

                    @auth
                        <a href="#"
                            class="block px-4 py-2 text-sm text-black hover:bg-[#f0f0f0] transition-colors">Profile</a>
                        <a href="{{ route('logout') }}"
                            class="block px-4 py-2 text-sm text-black hover:bg-[#f0f0f0] transition-colors">Logout</a>
                    @endauth
                </div>
            </details>

            {{-- Hamburger (Mobile) --}}
            <button id="menu-btn"
                class="md:hidden w-9 h-9 flex items-center justify-center rounded-lg border border-sage/30">
                <svg class="w-5 h-5 text-brown" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

        </div>
    </div>

    {{-- Mobile Menu --}}
    <div id="mobile-menu" class="hidden md:hidden px-6 pb-4 bg-warm-white border-t border-cream-dark">

        <div class="flex flex-col gap-4 text-sm">
            <a href="{{ route('beranda') }}#about" class="text-brown hover:text-sage">Tentang Kami</a>
            <a href="{{ route('beranda') }}#bestseller" class="text-brown hover:text-sage">Terlaris</a>
            <a href="{{ route('beranda') }}#our-product" class="text-brown hover:text-sage">Produk Kami</a>
        </div>
    </div>
</nav>

{{-- SCRIPT --}}
<script>
    const btn = document.getElementById('menu-btn');
    const menu = document.getElementById('mobile-menu');

    btn.addEventListener('click', () => {
        menu.classList.toggle('hidden');
    });
</script>
