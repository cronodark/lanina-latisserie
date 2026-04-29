{{-- Mewarisi layout utama admin --}}
@extends('layouts.admin')
@section('title', 'Tambah Produk')

@section('content')

    {{-- Judul halaman --}}
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Manajemen Produk</h1>

    {{-- Header banner dengan ikon dan judul section --}}
    <div class="bg-[#B8935A] rounded-2xl px-8 py-6 mb-8 flex items-center gap-5">
        {{-- Ikon kotak/produk --}}
        <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-white">Tambah Produk</h2>
    </div>

    {{-- Card utama berisi form --}}
    <div class="bg-white rounded-2xl shadow-sm p-8">

        <h3 class="text-lg font-bold text-gray-800 mb-6">Tambah Produk</h3>
        <hr class="border-gray-200 mb-6">

        {{--
            Form submit ke route 'product-admin.store' dengan method POST.
            enctype="multipart/form-data" diperlukan karena ada upload file/gambar.
        --}}
        <form action="{{ route('product-admin.store') }}" method="POST" enctype="multipart/form-data">
            @csrf {{-- Token CSRF untuk keamanan form --}}

            {{-- ======= SECTION: Informasi Utama ======= --}}
            <p class="text-base font-bold text-gray-800 mb-4">Informasi Utama</p>

            {{-- Grid 3 kolom: Nama Kue | Harga | Estimasi Pengerjaan --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">

                {{-- Input: Nama Kue --}}
                <div>
                    <label class="block text-sm text-gray-600 mb-1.5">Nama Kue</label>
                    <input type="text" name="name" placeholder="Masukan nama kue"
                        value="{{ old('name') }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 outline-none focus:border-[#8A9E5B] focus:ring-1 focus:ring-[#8A9E5B] transition placeholder:text-gray-300">
                    @error('name')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Input: Harga --}}
                <div>
                    <label class="block text-sm text-gray-600 mb-1.5">Harga</label>
                    <div class="flex items-center border border-gray-200 rounded-xl overflow-hidden focus-within:border-[#8A9E5B] focus-within:ring-1 focus-within:ring-[#8A9E5B] transition">
                        <span class="px-3 py-3 bg-gray-100 text-sm text-gray-500 border-r border-gray-200 font-medium">Rp</span>
                        <input type="number" name="price" placeholder="Masukan harga kue"
                            value="{{ old('price') }}"
                            class="flex-1 px-4 py-3 text-sm text-gray-700 outline-none placeholder:text-gray-300">
                    </div>
                    @error('price')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Input: Estimasi Pengerjaan --}}
                <div>
                    <label class="block text-sm text-gray-600 mb-1.5">Estimasi Pengerjaan (Hari)</label>
                    <input type="number" name="production_estimate" placeholder="Masukan estimasi pengerjaan"
                        value="{{ old('production_estimate') }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 outline-none focus:border-[#8A9E5B] focus:ring-1 focus:ring-[#8A9E5B] transition placeholder:text-gray-300">
                    @error('production_estimate')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            {{-- ======= SECTION: Detail Tambahan ======= --}}
            <p class="text-base font-bold text-gray-800 mb-4">Detail Tambahan</p>

            {{-- Grid 2 kolom: Deskripsi | Upload Foto --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- Textarea: Deskripsi --}}
                <div>
                    <label class="block text-sm text-gray-600 mb-1.5">Deskripsi</label>
                    <textarea name="description" rows="10" placeholder="Tuliskan keterangan kue"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 outline-none focus:border-[#8A9E5B] focus:ring-1 focus:ring-[#8A9E5B] transition placeholder:text-gray-300 resize-y">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Upload Foto Produk --}}
                <div>
                    <label class="block text-sm text-gray-600 mb-1.5">
                        Foto produk <span class="text-red-500">*</span>
                    </label>

                    <div x-data="{ preview: null }"
                        class="border-2 border-dashed border-gray-200 rounded-xl h-[220px] flex flex-col items-center justify-center cursor-pointer hover:border-[#8A9E5B] transition relative overflow-hidden"
                        @click="$refs.fileInput.click()">

                        <img x-show="preview" :src="preview"
                            class="absolute inset-0 w-full h-full object-cover rounded-xl">

                        <div x-show="!preview" class="flex flex-col items-center gap-2 text-gray-400">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="text-xs">Unggah Foto Produk</p>
                        </div>

                        <input type="file" name="image" accept="image/*" x-ref="fileInput" class="hidden"
                            @change="preview = URL.createObjectURL($event.target.files[0])">
                    </div>

                    @error('image')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            {{-- Tombol submit rata kanan --}}
            <div class="flex justify-end mt-8">
                <button type="submit"
                    class="bg-[#4A5E2F] hover:bg-[#3a4c23] text-white font-semibold px-10 py-3 rounded-xl transition text-sm">
                    Tambah Produk
                </button>
            </div>

        </form>
    </div>

@endsection
