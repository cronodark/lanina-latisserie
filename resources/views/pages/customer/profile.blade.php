@extends('layouts.app')

@section('title', 'Dashboard')

<x-sidebar active="dashboard" />

@section('content')

<div class="lg:ml-[230px] min-h-screen bg-[##FCFEF4]">

    {{-- TOPBAR --}}
    <div class="px-6 py-4 mb-6">
        <div class="flex items-center bg-white border border-[#E5E0D8] rounded-full px-4 py-2 max-w-sm shadow-sm">
            <svg class="w-4 h-4 text-[#6B7A52] mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
            </svg>
            <input type="text" placeholder="Search"
                class="bg-transparent text-sm text-[#3D2B1F] outline-none w-full">
        </div>
    </div>

    <div class="px-6 pb-8 flex flex-col gap-6">

        {{-- PROFILE --}}
        <div class="bg-[#B9925A] rounded-2xl px-6 py-5 flex flex-col sm:flex-row items-center justify-between text-white">

            <div class="flex items-center gap-4">
                <img src="{{ auth()->user()->avatar ?? '/images/1.png' }}"
                    class="w-16 h-16 rounded-full object-cover">

                <div>
                    <h2 class="text-lg sm:text-xl font-semibold">
                        {{ auth()->user()->name ?? 'User Name' }}
                    </h2>
                    <p class="text-white/80 text-sm">
                        {{ auth()->user()->role ?? 'User' }}
                    </p>
                </div>
            </div>

            <div class="hidden sm:block h-10 w-px bg-white/40 mx-6"></div>

            <div class="text-sm text-right">
                <p>{{ auth()->user()->email ?? 'email@gmail.com' }}</p>
                <p class="text-white/80">{{ auth()->user()->phone ?? '+62xxxx' }}</p>
            </div>

        </div>

        {{-- PESANAN --}}
        <div class="bg-[#F0EFEA] rounded-2xl p-6">
            <h3 class="font-bold text-[#3D2B1F] text-xl mb-6">
                Pesanan Saya
            </h3>

            <div class="grid grid-cols-2 sm:grid-cols-4 text-center gap-6">

                {{-- Belum Bayar --}}
                <div class="flex flex-col items-center gap-2">
                    <svg class="w-8 h-8 text-[#6B7A52]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <rect x="3" y="7" width="18" height="14" rx="2"/>
                        <path d="M3 10h18"/>
                    </svg>
                    <span class="text-sm font-semibold text-[#3D2B1F]">Belum Bayar</span>
                </div>

                {{-- Dikemas --}}
                <div class="flex flex-col items-center gap-2">
                    <svg class="w-8 h-8 text-[#6B7A52]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                        <path d="M3.3 7l8.7 5 8.7-5"/>
                        <path d="M12 22V12"/>
                    </svg>
                    <span class="text-sm font-semibold text-[#3D2B1F]">Dikemas</span>
                </div>

                {{-- Diantar --}}
                <div class="flex flex-col items-center gap-2">
                    <svg class="w-8 h-8 text-[#6B7A52]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <rect x="1" y="3" width="15" height="13"/>
                        <path d="M16 8h4l3 3v5h-7z"/>
                        <circle cx="5.5" cy="18.5" r="2.5"/>
                        <circle cx="18.5" cy="18.5" r="2.5"/>
                    </svg>
                    <span class="text-sm font-semibold text-[#3D2B1F]">Diantar</span>
                </div>

                {{-- Selesai --}}
                <div class="flex flex-col items-center gap-2">
                    <svg class="w-8 h-8 text-[#6B7A52]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M9 12l2 2 4-4"/>
                    </svg>
                    <span class="text-sm font-semibold text-[#3D2B1F]">Selesai</span>
                </div>

            </div>
        </div>

        {{-- ALAMAT --}}
        <div class="bg-[#F0EFEA] rounded-2xl p-6">

            <div class="flex justify-between items-center mb-4">
                <h3 class="font-glacial font-bold text-xl text-[#3D2B1F]">Alamat Saya</h3>

                <button class="bg-[#6B7A52]  text-white text-sm px-5 py-2 rounded-full">
                    + Tambah Alamat
                </button>
            </div>
            <div class="flex gap-3">
                <svg class="w-5 h-5 text-red-500 mt-1" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2C8 2 5 5 5 9c0 5 7 13 7 13s7-8 7-13c0-4-3-7-7-7z"/>
                </svg>

                <div>
                    <p class="font-semibold text-[#3D2B1F]">
                        Maimunah Pasutri Gaje
                        <span class="text-sm text-gray-500">( +62 083 7439 2934 )</span>
                    </p>

                    <p class="text-sm text-gray-600 mt-1">
                        Jl. Raya Persawahan No. 12 RT 03/RW 07, Dusun Sumber Rejeki, Desa Mekar Jaya, Kec. Cibeureum, Kab. Tasikmalaya, Jawa Barat 46196
                    </p>
                </div>
            </div>
        </div>

        {{-- FORM --}}
        <form class="bg-[#F0EFEA] rounded-2xl p-6 flex flex-col gap-6">

            {{-- Info --}}
            <div>
                <h3 class="font-bold text-lg mb-4 text-[#3D2B1F]">Info Personal</h3>

                <div class="grid md:grid-cols-3 gap-4">
                    <input class="input" placeholder="Nama depan">
                    <input class="input" placeholder="Nama belakang">
                    <input class="input" placeholder="Nomor Telepon">
                </div>
            </div>

            {{-- Auth --}}
            <div>
                <h3 class="font-bold text-lg mb-4 text-[#3D2B1F]">Autentifikasi</h3>

                <div class="grid md:grid-cols-2 gap-4 mb-4">
                    <input class="input" placeholder="Username">
                    <input class="input" placeholder="Email">
                </div>

                <div class="bg-blue-100 text-blue-600 text-sm px-4 py-3 rounded-lg mb-4">
                    Kosongkan jika tidak ingin mengubah password
                </div>

                <div class="grid md:grid-cols-2 gap-4">
                    <input type="password" class="input" placeholder="Password">
                    <input type="password" class="input" placeholder="Konfirmasi Password">
                </div>
            </div>

            {{-- Avatar --}}
            <div class="flex items-center gap-4">
                <img id="avatar-preview" src="/images/avatar-default.png"
                    class="w-16 h-16 rounded-full object-cover">

                <input type="file" id="avatar-input" class="text-sm">
            </div>

            {{-- Submit --}}
            <button class="bg-[#6B7A52] text-white px-6 py-2 rounded-full self-end">
                Simpan
            </button>

        </form>

    </div>
</div>

<h1 class=" text-3xl">
    TEST FONT
</h1>

@endsection
