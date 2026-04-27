@extends('layouts.app')

@section('title', 'Keranjang')

@section('content')

    <x-navbar />

    <main
        class="min-h-screen bg-[#F0EAD2]
    px-4 sm:px-6 md:px-10 lg:px-16 xl:px-24 2xl:px-32
    pt-16 sm:pt-20 md:pt-24 xl:pt-28
    pb-12">
        <div class="max-w-[1100px] mx-auto">

            <h1 class="font-['Playfair_Display'] text-5xl font-bold text-[#3D2B1F] mb-10">
                Keranjang Anda
            </h1>

            @livewire('cart')

        </div>
    </main>

    <x-footer />

@endsection
