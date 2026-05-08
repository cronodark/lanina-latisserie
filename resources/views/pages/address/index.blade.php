@extends('layouts.app')

@section('title', 'Daftar Alamat')

@section('content')

    <div class="min-h-screen bg-[#FBFEF3]">

        <x-sidebar active="daftar-alamat" />

        {{-- MAIN CONTENT --}}
        <div class="lg:ml-[230px] flex flex-col gap-5 px-4 sm:px-6 lg:px-8 py-6 lg:py-8">

            {{-- Topbar --}}
            <div class="flex items-center gap-4 mb-2">
                <button id="sidebarToggle" class="lg:hidden text-[#3D2B1F]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

            {{-- Header Banner --}}
            <div class="bg-[#BB9457] rounded-[20px] px-6 sm:px-8 py-6 sm:py-8 flex items-center gap-5">
                <svg class="w-12 h-12 sm:w-14 sm:h-14 text-white shrink-0" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                </svg>
                <h1 class="font-glacial font-bold text-white text-2xl sm:text-3xl lg:text-4xl">
                    Daftar Alamat
                </h1>
            </div>

            {{-- Tambah Alamat Button --}}
            <div class="flex justify-end">

                <a href="{{ route('profile.address.create') }}"
                    class="bg-[#7A8C5C] hover:bg-[#5C6B44] text-white font-glacial text-base font-bold px-8 py-3.5 rounded-full transition-colors">
                    Tambah Alamat
                </a>
            </div>

            <div class="flex flex-col gap-4">
                @foreach ($addresses as $address)
                    <div class="bg-white rounded-[20px] px-6 sm:px-8 py-8 sm:py-10 card-shadow">
                        <div class="flex items-center gap-3 mb-3">
                            <svg width="19" height="24" viewBox="0 0 25 32" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_105_6351)">
                                    <path
                                        d="M20.708 3.25552C18.1961 1.07115 14.827 -0.14669 11.536 0.014157C10.1355 0.0816555 8.75045 0.399042 7.45053 0.997911C5.78898 1.76337 4.31454 2.92664 3.11794 4.33262C0.706605 7.16612 -0.390854 10.5008 0.125762 14.2807C0.458071 16.7092 1.7147 18.7141 3.25896 20.4978C4.88142 22.3705 6.63512 24.1197 8.30923 25.945C9.60635 27.3582 10.8686 28.7857 11.0403 30.8854C11.099 31.5905 11.8362 32.0443 12.5399 31.9969C13.326 31.9438 13.8007 31.3866 13.9892 30.601C14.1833 29.7838 14.4472 28.9523 14.8507 28.2285C15.2486 27.5147 15.8225 26.89 16.3824 26.2883C18.164 24.3696 20.0266 22.5299 21.7594 20.5667C23.4544 18.6437 24.7516 16.4436 24.961 13.7824C25.2752 9.78276 23.6876 5.84631 20.708 3.25552ZM12.5008 16.4177C10.2696 16.4177 8.46003 14.5565 8.46003 12.2615C8.46003 9.96659 10.2696 8.10679 12.5008 8.10679C14.732 8.10679 16.5402 9.96659 16.5402 12.2615C16.5402 14.5565 14.732 16.4177 12.5008 16.4177Z"
                                        fill="#FF2222" />
                                </g>
                                <defs>
                                    <clipPath id="clip0_105_6351">
                                        <rect width="25" height="32" fill="white" />
                                    </clipPath>
                                </defs>
                            </svg>

                            <div class="flex items-center gap-2 flex-1">
                                <p class="font-poppins font-bold text-[#3D2B1F] text-base">{{ auth()->user()->name }}</p>
                                <span class="font-poppins text-[#3D2B1F] text-base">{{ auth()->user()->telp }}</span>
                                <svg class="w-5 h-5 text-[#3D2B1F] ml-1" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            </div>
                        </div>
                        <p class="font-poppins text-[#3D2B1F] text-base leading-relaxed pl-9 mb-6">
                            {{ $address->street }}, {{ $address->district }}, {{ $address->city }}, {{ $address->state }} {{ $address->zip_code }}, RT {{ $address->rt }}/RW {{ $address->rw }}@if($address->notes). {{ $address->notes }}@endif
                        </p>
                        <div class="flex items-center gap-3 pl-9">
                            <a href="{{ route('profile.address.edit', $address->id) }}"
                                class="flex items-center gap-1.5 bg-[#7A8C5C] hover:bg-[#5C6B44] text-white font-poppins text-sm font-medium px-4 py-2 rounded-full transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 012.828 0l.172.172a2 2 0 010 2.828L12 16H9v-3z" />
                                </svg>
                                Edit
                            </a>
                            <button type="button" data-delete-form-id="delete-address-form-{{ $address->id }}"
                                class="flex items-center gap-1.5 bg-red-50 hover:bg-red-100 text-red-500 font-poppins text-sm font-medium px-4 py-2 rounded-full transition-colors border border-red-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0h8m-9 0V5a1 1 0 011-1h4a1 1 0 011 1v2" />
                                </svg>
                                Hapus
                            </button>
                            <form id="delete-address-form-{{ $address->id }}" action="{{ route('profile.address.destroy', $address->id) }}"
                                method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </div>
                @endforeach
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

        {
            const deleteButtons = document.querySelectorAll('[data-delete-form-id]');

            const konfirmasiHapus = (formId) => {
                Swal.fire({
                    title: 'Hapus Alamat?',
                    text: 'Alamat ini akan dihapus secara permanen.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#e53e3e',
                    cancelButtonColor: '#7A8C5C',
                    borderRadius: '16px',
                    customClass: {
                        popup: 'rounded-[20px] font-poppins',
                        title: 'text-[#3D2B1F] font-bold',
                        htmlContainer: 'text-[#6B4C3B]',
                        confirmButton: 'rounded-full px-6 py-2 text-sm font-medium',
                        cancelButton: 'rounded-full px-6 py-2 text-sm font-medium',
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.getElementById(formId);
                        if (form) {
                            form.submit();
                        }
                    }
                });
            };

            deleteButtons.forEach((button) => {
                button.addEventListener('click', () => {
                    konfirmasiHapus(button.dataset.deleteFormId);
                });
            });
        }
    </script>
@endsection
