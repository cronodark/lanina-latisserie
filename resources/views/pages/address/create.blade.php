@extends('layouts.app')

@section('title', 'Tambah Alamat')

@section('content')

    <div class="min-h-screen bg-[#FBFEF3] font-nunito">

        <x-sidebar active="tambah-alamat" />

        {{-- MAIN CONTENT --}}
        <div class="lg:ml-[270px] flex flex-col gap-6 px-4 sm:px-6 lg:px-10 py-8 lg:py-10">

            {{-- Topbar --}}
            <div class="flex items-center gap-4 mb-2">
                <button id="sidebarToggle" class="lg:hidden text-[#3D2B1F]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

            {{-- Header Banner --}}
            <div class="bg-[#BB9457] rounded-[24px] px-6 sm:px-8 lg:px-10 py-6 sm:py-8 flex items-center gap-5">
                <svg class="w-14 h-14 sm:w-16 sm:h-16 text-white shrink-0" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                </svg>
                <h1 class="font-glacial font-bold text-white text-3xl sm:text-4xl">
                    Tambah Alamat
                </h1>
            </div>

            {{-- Form Card --}}
            <div class="bg-white rounded-[24px] px-6 sm:px-8 lg:px-10 py-8 lg:py-10 card-shadow">
                <form action="{{ route('profile.address.store') }}" method="POST">
                    @csrf
                    <h2 class=" font-bold text-[#1A1A1A] text-2xl mb-2">Tambah Alamat</h2>
                    <hr class="border-[#E0E0E0] mb-8">

                    <h3 class=" font-bold text-[#1A1A1A] text-lg mb-5">Informasi Utama</h3>

                    {{-- Row 1: Alamat Jalan | Wilayah Administratif | Kode Pos --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-6">

                        {{-- Alamat Jalan --}}
                        <div>
                            <label class=" text-base font-semibold text-[#1A1A1A] mb-2 block">Alamat Jalan:</label>
                            <input type="text" name="street" value="{{ old('street') }}"
                                placeholder="Masukan alamat jalan"
                                class="w-full text-base text-[#3D2B1F] bg-[#F0F0F0] border-0 rounded-[12px] px-5 py-3.5 outline-none focus:ring-2 focus:ring-[#7A8C5C] transition-all placeholder-[#ABABAB] @error('street') ring-2 ring-red-400 @enderror">
                            @error('street')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Wilayah Administratif (RT/RW) --}}
                        <div>
                            <label class=" text-base font-semibold text-[#1A1A1A] mb-2 block">Wilayah Administratif</label>
                            <div class="flex gap-3">
                                <div class="flex items-center gap-2 flex-1 bg-[#F0F0F0] rounded-[12px] px-4 py-3.5">
                                    <span class=" font-bold text-base text-[#6B6B6B] shrink-0">RT</span>
                                    <input type="text" name="rt" value="{{ old('rt') }}" placeholder=""
                                        class="w-full  text-base text-[#3D2B1F] bg-transparent outline-none">
                                </div>
                                <div class="flex items-center gap-2 flex-1 bg-[#F0F0F0] rounded-[12px] px-4 py-3.5">
                                    <span class=" font-bold text-base text-[#6B6B6B] shrink-0">RW</span>
                                    <input type="text" name="rw" value="{{ old('rw') }}" placeholder=""
                                        class="w-full  text-base text-[#3D2B1F] bg-transparent outline-none">
                                </div>
                            </div>
                            @error('rt')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            @error('rw')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Kode Pos --}}
                        <div>
                            <label class=" text-base font-semibold text-[#1A1A1A] mb-2 block">Kode Pos</label>
                            <input type="text" name="zip_code" value="{{ old('zip_code') }}"
                                placeholder="Masukan informasi kode pos"
                                class="w-full text-base text-[#3D2B1F] bg-[#F0F0F0] border-0 rounded-[12px] px-5 py-3.5 outline-none focus:ring-2 focus:ring-[#7A8C5C] transition-all placeholder-[#ABABAB] @error('zip_code') ring-2 ring-red-400 @enderror">
                            @error('zip_code')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    {{-- Row 2: Kecamatan | Kabupaten | Provinsi | Patokan --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-12">

                        {{-- Kecamatan --}}
                        <div>
                            <label class=" text-base font-semibold text-[#1A1A1A] mb-2 block">Kecamatan</label>
                            <input type="text" name="district" value="{{ old('district') }}"
                                placeholder="Masukan nama kecamatan"
                                class="w-full text-base text-[#3D2B1F] bg-[#F0F0F0] border-0 rounded-[12px] px-5 py-3.5 outline-none focus:ring-2 focus:ring-[#7A8C5C] transition-all placeholder-[#ABABAB] @error('district') ring-2 ring-red-400 @enderror">
                            @error('district')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Kabupaten --}}
                        <div>
                            <label class=" text-base font-semibold text-[#1A1A1A] mb-2 block">Kabupaten</label>
                            <input type="text" name="city" value="{{ old('city') }}" placeholder="Masukan nama kabupaten"
                                class="w-full text-base text-[#3D2B1F] bg-[#F0F0F0] border-0 rounded-[12px] px-5 py-3.5 outline-none focus:ring-2 focus:ring-[#7A8C5C] transition-all placeholder-[#ABABAB] @error('city') ring-2 ring-red-400 @enderror">
                            @error('city')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Provinsi --}}
                        <div>
                            <label class=" text-base font-semibold text-[#1A1A1A] mb-2 block">Provinsi</label>
                            <input type="text" name="state" value="{{ old('state') }}" placeholder="Masukan nama provinsi"
                                class="w-full text-base text-[#3D2B1F] bg-[#F0F0F0] border-0 rounded-[12px] px-5 py-3.5 outline-none focus:ring-2 focus:ring-[#7A8C5C] transition-all placeholder-[#ABABAB] @error('state') ring-2 ring-red-400 @enderror">
                            @error('state')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Patokan --}}
                        <div>
                            <label class=" text-base font-semibold text-[#1A1A1A] mb-2 block">Patokan/Keterangan</label>
                            <input type="text" name="notes" value="{{ old('notes') }}"
                                placeholder="Masukan patokan atau keterangan"
                                class="w-full text-base text-[#3D2B1F] bg-[#F0F0F0] border-0 rounded-[12px] px-5 py-3.5 outline-none focus:ring-2 focus:ring-[#7A8C5C] transition-all placeholder-[#ABABAB] @error('notes') ring-2 ring-red-400 @enderror">
                            @error('notes')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    {{-- Submit Button --}}
                    <div class="flex justify-end gap-3">
                        <a href="{{ route('profile.address.index') }}"
                            class="bg-[#FD5454] hover:bg-[#FF0000] text-white  font-bold text-base px-10 py-4 rounded-[14px] transition-colors">
                            Batal
                        </a>
                        <button id="btn-tambah-alamat" type="submit"
                            class="bg-[#7A8C5C] hover:bg-[#5C6B44] text-white  font-bold text-base px-10 py-4 rounded-[14px] transition-colors">
                            Simpan
                        </button>
                    </div>
                </form>
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
