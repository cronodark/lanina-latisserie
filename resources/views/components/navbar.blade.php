{{-- @extends('layouts.app')

@section('title', 'Navbar')

@section('content') --}}
    <nav class="fixed top-0 left-0 right-0 z-50 bg-warm-white/90 backdrop-blur-sm border-b border-cream-dark">
        <div class="max-w-6xl mx-auto px-6 py-3 flex items-center justify-between gap-8">
            {{-- Logo --}}
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-full border-2 border-sage flex items-center justify-center">
                    <svg viewBox="0 0 24 24" class="w-4 h-4 text-sage" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M12 2C8 2 5 5 5 9c0 2 1 4 2.5 5.5L12 22l4.5-7.5C18 13 19 11 19 9c0-4-3-7-7-7z" />
                    </svg>
                </div>
                <div>
                    <p class="font-display font-bold text-brown text-lg leading-none tracking-wide">LA NNA</p>
                    <p class="text-[9px] text-brown-light tracking-[0.2em] uppercase leading-none">PATISSERIE</p>
                </div>
            </div>

            {{-- Nav Links --}}
            <div class="hidden md:flex  gap-8">
                <a href="#about" class="nav-link text-sm text-brown font-medium hover:text-sage transition-colors">About
                    Me</a>
                <a href="#bestseller" class="nav-link text-sm text-brown font-medium hover:text-sage transition-colors">Best
                    Seller</a>
                <a href="#product" class="nav-link text-sm text-brown font-medium hover:text-sage transition-colors">Our
                    Product</a>
            </div>

            {{-- Account Icon --}}
            <button
                class="w-9 h-9 rounded-full border border-sage/40 flex items-center justify-center hover:bg-sage/10 transition-colors">
                <svg class="w-4 h-4 text-brown" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </button>
        </div>
    </nav>
{{-- @endsection --}}
