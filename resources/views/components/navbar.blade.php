<nav class="fixed top-0 left-0 right-0 z-50 bg-black/30 backdrop-blur-sm">

    <div class="max-w-[1400px] 2xl:max-w-[1600px] mx-auto
                px-4 sm:px-6 md:px-10 lg:px-16 xl:px-24 2xl:px-32
                py-3 flex items-center justify-between gap-6 xl:gap-10">

        {{-- Logo --}}
        <div class="flex items-center gap-2 sm:gap-3">
            <div class="w-8 h-8 sm:w-9 sm:h-9 xl:w-10 xl:h-10 rounded-full border-2 border-sage flex items-center justify-center text-white">
                <svg viewBox="0 0 24 24" class="w-4 h-4 xl:w-5 xl:h-5 text-white" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M12 2C8 2 5 5 5 9c0 2 1 4 2.5 5.5L12 22l4.5-7.5C18 13 19 11 19 9c0-4-3-7-7-7z" />
                </svg>
            </div>
            <div>
                <p class="font-display font-bold text-white text-base sm:text-lg xl:text-xl leading-none tracking-wide">
                    LA NNA
                </p>
                <p class="text-[8px] sm:text-[9px] xl:text-[10px] text-white tracking-[0.2em] uppercase leading-none">
                    PATISSERIE
                </p>
            </div>
        </div>

        {{-- Nav Links (Desktop) --}}
        <div class="hidden md:flex items-center gap-6 lg:gap-8 xl:gap-10 text-white">
            <a href="#about" class="text-sm lg:text-base xl:text-lg text-brown font-medium hover:text-sage transition">
                About Us
            </a>
            <a href="#bestseller" class="text-sm lg:text-base xl:text-lg text-brown font-medium hover:text-sage transition">
                Best Seller
            </a>
            <a href="#product" class="text-sm lg:text-base xl:text-lg text-brown font-medium hover:text-sage transition">
                Our Product
            </a>
        </div>

        {{-- Right Section --}}
        <div class="flex items-center gap-3 sm:gap-4 text-white">

            {{-- Account Icon --}}
            <button
                class="w-9 h-9 sm:w-10 sm:h-10 xl:w-11 xl:h-11 rounded-full border border-sage/40 flex items-center justify-center hover:bg-sage/10 transition">
                <svg class="w-4 h-4 xl:w-5 xl:h-5 text-brown" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </button>

            {{-- Hamburger (Mobile) --}}
            <button id="menu-btn"
                class="md:hidden w-9 h-9 flex items-center justify-center rounded-lg border border-sage/30">
                <svg class="w-5 h-5 text-brown" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

        </div>
    </div>

    {{-- Mobile Menu --}}
    <div id="mobile-menu"
        class="hidden md:hidden px-6 pb-4 bg-warm-white border-t border-cream-dark">

        <div class="flex flex-col gap-4 text-sm">
            <a href="#about" class="text-brown hover:text-sage">About Us</a>
            <a href="#bestseller" class="text-brown hover:text-sage">Best Seller</a>
            <a href="#product" class="text-brown hover:text-sage">Our Product</a>
        </div>

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
                        class="block px-4 py-2 text-sm text-brown hover:bg-[#f0f0f0] transition-colors">Login</a>
                    <a href="{{ route('register') }}"
                        class="block px-4 py-2 text-sm text-brown hover:bg-[#f0f0f0] transition-colors">Register</a>
                @endguest

                @auth
                    <a href="#"
                        class="block px-4 py-2 text-sm text-brown hover:bg-[#f0f0f0] transition-colors">Profile</a>
                    <a href="{{ route('logout') }}"
                        class="block px-4 py-2 text-sm text-brown hover:bg-[#f0f0f0] transition-colors">Logout</a>
                @endauth
            </div>
        </details>
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
