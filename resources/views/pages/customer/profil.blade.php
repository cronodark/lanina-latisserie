@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    <div class="min-h-screen bg-[#FBFEF3]">

        <x-sidebar active="dashboard" />

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
                    <div
                        class="flex items-center gap-2 bg-[#F5F6FA] border border-[#D5D5D5] rounded-full px-5 py-3 shadow-[0_2px_8px_rgba(0,0,0,0.06)]">
                        <svg class="w-5 h-5 text-[#9A8878]" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z" />
                        </svg>
                        <input type="text" placeholder="Search"
                            class="font-nunito text-sm text-[#3D2B1F] placeholder-[#C4B8AE] outline-none bg-transparent w-full">
                    </div>
                </div>
            </div>

            {{-- Profile Banner --}}
            <div
                class="bg-[#BB9457] rounded-[24px] font-nunito px-6 sm:px-8 lg:px-10 py-6 sm:py-8 flex flex-col sm:flex-row items-center gap-5 sm:gap-8 shadow-[0_4px_20px_rgba(0,0,0,0.1)]">
                <div
                    class="w-20 h-20 sm:w-24 sm:h-24 rounded-full bg-white/20 overflow-hidden shrink-0 ring-4 ring-white/30">
                    <img src="https://api.dicebear.com/7.x/adventurer/svg?seed=maimunah" alt="Avatar"
                        class="w-full h-full object-cover">
                </div>
                <div class="flex-1 text-center sm:text-left">
                    <h2 class="font-bold text-white text-2xl sm:text-3xl">Maimunah Pasutri Gaje</h2>
                    <p class="font-semibold text-white/80 text-base mt-1">Admin</p>
                </div>
                <div class="text-center sm:text-right mt-2 sm:mt-0">
                    <p class="font-bold text-white/90 text-base">maimunah@gmail.com</p>
                    <p class="font-bold text-white/90 text-base mt-1.5">+62 877 4563 4859</p>
                </div>
            </div>

            {{-- Pesanan Saya --}}
            <div class="bg-[#FCFFF4]/40 rounded-[24px] font-glacial px-6 sm:px-8 lg:px-10 py-8 lg:py-10 card-shadow">
                <h2 class="font-bold text-[#3D2B1F] text-2xl sm:text-3xl mb-6 sm:mb-8">Pesanan Saya</h2>
                <div class="flex flex-wrap justify-center sm:justify-around gap-8">

                    {{-- Belum Bayar --}}
                    <a href="#" class="flex flex-col items-center gap-3 group">
                        <div
                            class="w-20 h-20 rounded-[20px] flex items-center justify-center transition-all duration-300 group-hover:scale-105">
                            <svg class="w-12 h-12" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M46.25 38.3333H46.2817M3.5 9.83333V54.1667C3.5 57.6645 6.33553 60.5 9.83333 60.5H54.1667C57.6645 60.5 60.5 57.6645 60.5 54.1667V22.5C60.5 19.0022 57.6645 16.1667 54.1667 16.1667L9.83333 16.1667C6.33553 16.1667 3.5 13.3311 3.5 9.83333ZM3.5 9.83333C3.5 6.33553 6.33553 3.5 9.83333 3.5H47.8333M47.8333 38.3333C47.8333 39.2078 47.1245 39.9167 46.25 39.9167C45.3755 39.9167 44.6667 39.2078 44.6667 38.3333C44.6667 37.4589 45.3755 36.75 46.25 36.75C47.1245 36.75 47.8333 37.4589 47.8333 38.3333Z"
                                    stroke="#6A7941" stroke-width="7" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                        <span class="font-bold text-[#3D2B1F] text-sm">Belum Bayar</span>
                    </a>

                    {{-- Dikemas --}}
                    <a href="#" class="flex flex-col items-center gap-3 group">
                        <div
                            class="w-20 h-20 rounded-[20px] flex items-center justify-center transition-all duration-300 group-hover:scale-105">
                            <svg class="w-12 h-12" viewBox="0 0 67 71" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M60.5162 44.8488C61.8142 43.5508 63.9182 43.5508 65.2162 44.8488C66.5135 46.1469 66.514 48.2511 65.2162 49.5489L53.2487 61.5132C51.9506 62.8109 49.8465 62.8111 48.5486 61.5132L44.5594 57.524C43.2631 56.2259 43.2622 54.1215 44.5594 52.824C45.8575 51.5259 47.9647 51.5259 49.2627 52.824L50.8986 54.4599L60.5162 44.8488ZM6.64756 49.2665L27.6322 61.3833V39.0809L6.64756 26.1688V49.2665ZM9.0203 19.8263L30.9073 33.2935L38.441 28.3987L17.0084 15.2107L9.0203 19.8263ZM23.5521 11.4357L44.6114 24.3933L52.2295 19.4433L30.956 7.16091L23.5521 11.4357ZM61.9152 35.2313C61.9152 37.0667 60.4267 38.5546 58.5914 38.5551C56.7557 38.5551 55.2676 37.067 55.2676 35.2313V25.3995L34.2797 39.0322V61.3833L35.2762 60.8088C36.8659 59.8912 38.8994 60.4364 39.8172 62.026C40.7347 63.6157 40.1896 65.6493 38.6 66.567L32.6178 70.0206C31.5897 70.6135 30.322 70.6141 29.2941 70.0206L1.66189 54.0639C0.634224 53.4703 0.000464205 52.3748 0 51.188V19.2778C0.000229939 18.0906 0.633696 16.9923 1.66189 16.3987L14.7817 8.81956C15.1246 8.51324 15.5248 8.28867 15.9503 8.14766L29.2941 0.445184L29.6901 0.250431C30.6353 -0.139387 31.718 -0.0738496 32.6178 0.445184L60.2533 16.3987C61.2814 16.9923 61.9149 18.0906 61.9152 19.2778V35.2313Z"
                                    fill="#6A7941" />
                            </svg>
                        </div>
                        <span class="font-bold text-[#3D2B1F] text-sm">Dikemas</span>
                    </a>

                    {{-- Diantar --}}
                    <a href="#" class="flex flex-col items-center gap-3 group">
                        <div
                            class="w-20 h-20 rounded-[20px] flex items-center justify-center transition-all duration-300 group-hover:scale-105">
                            <svg class="w-12 h-12" viewBox="0 0 71 53" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M23.075 37.0355C20.7155 37.0363 18.8034 38.9507 18.8034 41.3103C18.8036 43.6697 20.7157 45.581 23.075 45.5819C25.435 45.5819 27.3495 43.6703 27.3498 41.3103C27.3498 38.9502 25.4351 37.0355 23.075 37.0355ZM54.9852 37.0355C52.6251 37.0355 50.7104 38.9502 50.7104 41.3103C50.7106 43.6703 52.6252 45.5819 54.9852 45.5819C57.345 45.5817 59.2565 43.6701 59.2568 41.3103C59.2568 38.9503 57.3452 37.0357 54.9852 37.0355ZM46.1532 34.89C48.1388 32.1631 51.3539 30.3879 54.9852 30.3879C58.6166 30.3881 61.8317 32.163 63.8172 34.89V27.2816L53.385 14.2462H46.1532V34.89ZM70.4648 39.7912C70.4644 43.0706 67.9819 45.7656 64.7943 46.111C63.0173 49.7348 59.2935 52.2293 54.9852 52.2295C50.6763 52.2295 46.9498 49.7355 45.1729 46.111C43.894 45.9719 42.7306 45.4555 41.794 44.673C40.3358 45.6032 38.6108 46.1529 36.7531 46.1532H33.7117C33.4361 46.1531 33.1693 46.1158 32.9132 46.0525C31.1476 49.7079 27.4064 52.2295 23.075 52.2295C18.7437 52.2288 15.0019 49.7081 13.2367 46.0525C12.9816 46.1154 12.7159 46.1531 12.4415 46.1532H9.40007C4.20894 46.1524 0.000854112 41.9442 0 36.7531V9.40007C0.000801149 4.20891 4.20891 0.00080113 9.40007 0H36.7531C41.3277 0.000753714 45.1334 3.26983 45.9746 7.59861H53.5246C55.2153 7.59868 56.8253 8.27011 58.0104 9.44876L58.494 9.98433L69.0691 23.208C69.9713 24.336 70.4647 25.7365 70.4648 27.181V39.7912ZM6.64756 36.7531C6.64842 38.2729 7.88029 39.5048 9.40007 39.5056H12.3084C13.1686 34.3335 17.659 30.3887 23.075 30.3879C28.4916 30.3879 32.9845 34.333 33.8448 39.5056H36.7531C38.1786 39.5048 39.3521 38.4209 39.4926 37.0322L39.5056 36.7531V9.40007C39.5048 7.88029 38.2729 6.64842 36.7531 6.64756H9.40007C7.88026 6.64836 6.64836 7.88026 6.64756 9.40007V36.7531Z"
                                    fill="#6A7941" />
                            </svg>
                        </div>
                        <span class="font-bold text-[#3D2B1F] text-sm">Diantar</span>
                    </a>

                    {{-- Selesai --}}
                    <a href="#" class="flex flex-col items-center gap-3 group">
                        <div
                            class="w-20 h-20 rounded-[20px] flex items-center justify-center transition-all duration-300 group-hover:scale-105">
                            <svg class="w-12 h-12" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M0 29.914C0 13.393 13.393 0 29.914 0C34.6024 0 39.0462 1.08043 43.0029 3.00855C44.3531 3.66648 44.9143 5.29448 44.2564 6.64461C43.5985 7.99473 41.9705 8.55599 40.6204 7.89811C37.3896 6.32374 33.759 5.43892 29.914 5.43892C16.3968 5.43892 5.43892 16.3968 5.43892 29.914C5.43892 43.4313 16.3968 54.3892 29.914 54.3892C43.4313 54.3892 54.3892 43.4313 54.3892 29.914C54.3892 28.4121 55.6067 27.1946 57.1086 27.1946C58.6105 27.1946 59.8281 28.4121 59.8281 29.914C59.8281 46.4351 46.4351 59.8281 29.914 59.8281C13.393 59.8281 0 46.4351 0 29.914ZM50.0869 10.9947C51.1489 9.93267 52.8704 9.93267 53.9324 10.9947C54.9944 12.0567 54.9944 13.7782 53.9324 14.8402L30.1371 38.6354C29.0751 39.6974 27.3536 39.6974 26.2916 38.6354L19.493 31.8368C18.431 30.7748 18.431 29.0533 19.493 27.9913C20.555 26.9293 22.2765 26.9293 23.3385 27.9913L28.2144 32.8672L50.0869 10.9947Z"
                                    fill="#6A7941" />
                            </svg>
                        </div>
                        <span class="font-bold text-[#3D2B1F] text-sm">Selesai</span>
                    </a>

                </div>
            </div>

            {{-- Alamat Saya --}}
            <div class="bg-white rounded-[24px] px-6 sm:px-8 lg:px-10 py-8 lg:py-10 card-shadow">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 sm:gap-0 mb-6 sm:mb-7">
                    <h2 class="font-glacial font-bold text-[#3D2B1F] text-2xl sm:text-3xl">Alamat Saya</h2>
                    <button
                        class="bg-[#7A8C5C] hover:bg-[#5C6B44] text-white font-glacial text-sm font-bold px-6 py-3 rounded-full transition-colors self-start sm:self-auto">
                        + Tambah Alamat
                    </button>
                </div>
                <div class="flex items-start gap-4">
                    <svg class="w-6 h-6 text-red-500 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M5.05 8.05a5 5 0 119.9 0c0 3.535-4.95 8.95-4.95 8.95S5.05 11.585 5.05 8.05zM10 9a1 1 0 100-2 1 1 0 000 2z"
                            clip-rule="evenodd" />
                    </svg>
                    <div class="flex-1 font-poppins">
                        <div class="flex items-center gap-2 flex-wrap">
                            <p class="font-bold text-[#3D2B1F] text-base">Maimunah Pasutri Gaje</p>
                            <span class="text-[#6B4C3B] font-medium text-base">(+62 083 7439 2934)</span>
                            <svg class="w-5 h-5 text-[#3D2B1F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                        <p class="text-[#6B4C3B] text-base mt-2 leading-relaxed">
                            Jl. Raya Persawahan No. 12 RT 03/RW 07, Dusun Sumber Rejeki, Desa Mekar Jaya, Kec. Cibeureum,
                            Kab. Tasikmalaya, Jawa Barat 46196
                        </p>
                    </div>
                </div>
            </div>

            {{-- Info Personal --}}
            <div class="bg-[#E2E3E4]/40 font-nunito rounded-[24px] px-6 sm:px-8 lg:px-10 py-8 lg:py-10 card-shadow">

                <h2 class="font-bold text-[#3D2B1F] text-xl sm:text-2xl mb-2">Info Personal</h2>
                <hr class="border-[#979797] mb-6 sm:mb-8">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-8 sm:mb-10">
                    <div>
                        <label class="text-base font-medium mb-2 block">Nama lengkap</label>
                        <input type="text" placeholder="Masukan Nama Anda"
                            class="w-full text-base text-[#3D2B1F] bg-[#F5F6F7] border border-[#979797]/20 rounded-[12px] px-5 py-3.5 outline-none focus:border-[#7A8C5C] transition-colors">
                    </div>
                    <div>
                        <label class="text-base font-medium mb-2 block">Nomor Telepon</label>
                        <input type="text" placeholder="Masukan Nomor Telepon Anda"
                            class="w-full text-base text-[#3D2B1F] bg-[#F5F6F7] border border-[#979797]/20 rounded-[12px] px-5 py-3.5 outline-none focus:border-[#7A8C5C] transition-colors">
                    </div>
                </div>

                <h2 class="font-bold text-[#3D2B1F] text-xl sm:text-2xl mb-2">Autentifikasi</h2>
                <hr class="border-[#979797] mb-6 sm:mb-8">

                <div class="mb-5">
                    <label class="text-base font-medium mb-2 block">Email</label>
                    <input type="email" placeholder="Masukan Email anda"
                        class="w-full text-base text-[#3D2B1F] bg-[#F5F6F7] border border-[#979797]/20 rounded-[12px] px-5 py-3.5 outline-none focus:border-[#7A8C5C] transition-colors">
                </div>

                {{-- Password hint --}}
                <div class="bg-[#48B3FF]/20 border border-[#48B3FF] rounded-[12px] px-5 py-4 mb-5">
                    <p class="text-[#0D80D2] text-base">Kosongkan jika tidak ingin mengubah password</p>
                </div>

                <div class="grid grid-cols-2 gap-5 mb-10">
                    <div>
                        <label class="text-base font-medium mb-2 block">Password</label>
                        <input type="password" placeholder="••••••"
                            class="w-full text-base text-[#3D2B1F] bg-[#F5F6F7] border border-[#979797]/20 rounded-[12px] px-5 py-3.5 outline-none focus:border-[#7A8C5C] transition-colors">
                    </div>
                    <div>
                        <label class="text-base font-medium mb-2 block">Konfirmasi Password</label>
                        <input type="password" placeholder="••••••"
                            class="w-full text-base text-[#3D2B1F] bg-[#F5F6F7] border border-[#979797]/20 rounded-[12px] px-5 py-3.5 outline-none focus:border-[#7A8C5C] transition-colors">
                    </div>
                </div>

                <hr class="border-[#979797] mb-8">

                {{-- Foto Profile --}}
                <h2 class="font-bold text-[#3D2B1F] text-xl sm:text-2xl mb-5">Foto Profile</h2>
                <div class="flex items-center gap-5">
                    {{-- Avatar --}}
                    <div class="w-24 h-24 rounded-full overflow-hidden shrink-0 ring-2 ring-[#E8E0D4]">
                        <img src="https://api.dicebear.com/7.x/adventurer/svg?seed=maimunah" alt="Avatar"
                            class="w-full h-full object-cover">
                    </div>

                    {{-- Choose File --}}
                    <div class="flex-1 bg-[#F5F6F7] border border-[#979797]/20 rounded-[14px] px-6 py-5">
                        <div class="flex items-center gap-3 mb-2">
                            <label
                                class="bg-[#48B3FF]/20 text-base text-[#0D80D2] px-5 py-2 rounded-[10px] cursor-pointer hover:bg-[#48B3FF] hover:text-white transition-colors">
                                Choose File
                                <input type="file" class="hidden" accept=".jpg,.jpeg,.png">
                            </label>
                            <span class="text-base text-[#6B6B6B]">No file Chosen</span>
                        </div>
                        <p class="font-bold text-black/70 text-sm mt-1">Format: JPG, JPEG, PNG. Max: 2MB</p>
                    </div>

                    {{-- Simpan --}}
                    <button id="btn-simpan"
                        class="bg-[#68C0FF] hover:bg-[#48B3FF] text-white font-bold text-base px-8 py-4 rounded-[12px] transition-colors shrink-0">
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

            document.getElementById('btn-simpan').addEventListener('click', () => {
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Perubahan berhasil disimpan.',
                    icon: 'success',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#7A8C5C',
                    customClass: {
                        popup: 'rounded-[20px] font-poppins',
                        confirmButton: 'rounded-full px-6 py-2 text-sm font-medium',
                    }
                });
            });
        }
    </script>

@endsection
