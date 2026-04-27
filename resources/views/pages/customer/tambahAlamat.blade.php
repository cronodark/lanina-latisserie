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
                <div class="flex-1 max-w-[360px]">
                    <div class="flex items-center gap-2 bg-[#F5F6FA] border border-[#D5D5D5] rounded-full px-5 py-3 shadow-[0_2px_8px_rgba(0,0,0,0.06)]">
                        <svg class="w-5 h-5 text-[#9A8878]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z" />
                        </svg>
                        <input type="text" placeholder="Search"
                            class="font-nunito text-sm text-[#3D2B1F] placeholder-[#C4B8AE] outline-none bg-transparent w-full">
                    </div>
                </div>
            </div>

            {{-- Header Banner --}}
            <div class="bg-[#BB9457] rounded-[24px] px-6 sm:px-8 lg:px-10 py-6 sm:py-8 flex items-center gap-5">
                <svg class="w-14 h-14 sm:w-16 sm:h-16 text-white shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                </svg>
                <h1 class="font-glacial font-bold text-white text-3xl sm:text-4xl">
                    Tambah Alamat
                </h1>
            </div>

            {{-- Form Card --}}
            <div class="bg-white rounded-[24px] px-6 sm:px-8 lg:px-10 py-8 lg:py-10 card-shadow">

                <h2 class=" font-bold text-[#1A1A1A] text-2xl mb-2">Tambah Alamat</h2>
                <hr class="border-[#E0E0E0] mb-8">

                <h3 class=" font-bold text-[#1A1A1A] text-lg mb-5">Informasi Utama</h3>

                {{-- Row 1: Alamat Jalan | Wilayah Administratif | Kode Pos --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-6">

                    {{-- Alamat Jalan --}}
                    <div>
                        <label class=" text-base font-semibold text-[#1A1A1A] mb-2 block">Alamat Jalan:</label>
                        <input type="text" placeholder="Masukan alamat jalan"
                            class="w-full  text-base text-[#3D2B1F] bg-[#F0F0F0] border-0 rounded-[12px] px-5 py-3.5 outline-none focus:ring-2 focus:ring-[#7A8C5C] transition-all placeholder-[#ABABAB]">
                    </div>

                    {{-- Wilayah Administratif (RT/RW) --}}
                    <div>
                        <label class=" text-base font-semibold text-[#1A1A1A] mb-2 block">Wilayah Administratif</label>
                        <div class="flex gap-3">
                            <div class="flex items-center gap-2 flex-1 bg-[#F0F0F0] rounded-[12px] px-4 py-3.5">
                                <span class=" font-bold text-base text-[#6B6B6B] shrink-0">RT</span>
                                <input type="text" placeholder=""
                                    class="w-full  text-base text-[#3D2B1F] bg-transparent outline-none">
                            </div>
                            <div class="flex items-center gap-2 flex-1 bg-[#F0F0F0] rounded-[12px] px-4 py-3.5">
                                <span class=" font-bold text-base text-[#6B6B6B] shrink-0">RW</span>
                                <input type="text" placeholder=""
                                    class="w-full  text-base text-[#3D2B1F] bg-transparent outline-none">
                            </div>
                        </div>
                    </div>

                    {{-- Kode Pos --}}
                    <div>
                        <label class=" text-base font-semibold text-[#1A1A1A] mb-2 block">Kode Pos</label>
                        <input type="text" placeholder="Masukan informasi kode pos"
                            class="w-full  text-base text-[#3D2B1F] bg-[#F0F0F0] border-0 rounded-[12px] px-5 py-3.5 outline-none focus:ring-2 focus:ring-[#7A8C5C] transition-all placeholder-[#ABABAB]">
                    </div>

                </div>

                {{-- Row 2: Kecamatan | Kabupaten | Patokan --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-12">

                    {{-- Kecamatan --}}
                    <div>
                        <label class=" text-base font-semibold text-[#1A1A1A] mb-2 block">Kecamatan</label>
                        <input type="text" placeholder="Masukan nama kecamatan"
                            class="w-full  text-base text-[#3D2B1F] bg-[#F0F0F0] border-0 rounded-[12px] px-5 py-3.5 outline-none focus:ring-2 focus:ring-[#7A8C5C] transition-all placeholder-[#ABABAB]">
                    </div>

                    {{-- Kabupaten --}}
                    <div>
                        <label class=" text-base font-semibold text-[#1A1A1A] mb-2 block">Kabupaten</label>
                        <input type="text" placeholder="Masukan nama kabupaten"
                            class="w-full  text-base text-[#3D2B1F] bg-[#F0F0F0] border-0 rounded-[12px] px-5 py-3.5 outline-none focus:ring-2 focus:ring-[#7A8C5C] transition-all placeholder-[#ABABAB]">
                    </div>

                    {{-- Patokan --}}
                    <div>
                        <label class=" text-base font-semibold text-[#1A1A1A] mb-2 block">Patokan/Keterangan</label>
                        <input type="text" placeholder="Masukan patokan atau keterangan"
                            class="w-full  text-base text-[#3D2B1F] bg-[#F0F0F0] border-0 rounded-[12px] px-5 py-3.5 outline-none focus:ring-2 focus:ring-[#7A8C5C] transition-all placeholder-[#ABABAB]">
                    </div>

                </div>

                {{-- Submit Button --}}
                  <div class="flex justify-end gap-3">
                    <button
                        class="bg-[#FD5454] hover:bg-[#FF0000] text-white  font-bold text-base px-10 py-4 rounded-[14px] transition-colors">
                        Batal
                    </button>
                    <button id="btn-tambah-alamat"
                        class="bg-[#7A8C5C] hover:bg-[#5C6B44] text-white  font-bold text-base px-10 py-4 rounded-[14px] transition-colors">
                        Simpan
                    </button>
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

            document.getElementById('btn-tambah-alamat').addEventListener('click', () => {
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Alamat berhasil ditambahkan.',
                    icon: 'success',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#7A8C5C',
                    customClass: {
                        popup: 'rounded-[20px] ',
                        confirmButton: 'rounded-full px-6 py-2 text-sm font-medium',
                    }
                });
            });
        }
    </script>

@endsection
