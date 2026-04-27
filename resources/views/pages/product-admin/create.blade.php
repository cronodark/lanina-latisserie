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

            {{-- Grid 3 kolom: Nama Kue | Harga | Stok --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">

                {{-- Input: Nama Kue — menggunakan old() untuk mempertahankan value saat validasi gagal --}}
                <div>
                    <label class="block text-sm text-gray-600 mb-1.5">Nama Kue</label>
                    <input type="text" name="name" placeholder="Masukan nama kue"
                        value="{{ old('name') }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 outline-none focus:border-[#8A9E5B] focus:ring-1 focus:ring-[#8A9E5B] transition placeholder:text-gray-300">
                    {{-- Menampilkan pesan error validasi untuk field 'name' --}}
                    @error('name')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Input: Harga — prefix "Rp" di kiri input menggunakan flex layout --}}
                <div>
                    <label class="block text-sm text-gray-600 mb-1.5">Harga</label>
                    <div class="flex items-center border border-gray-200 rounded-xl overflow-hidden focus-within:border-[#8A9E5B] focus-within:ring-1 focus-within:ring-[#8A9E5B] transition">
                        {{-- Label prefix satuan mata uang --}}
                        <span class="px-3 py-3 bg-gray-100 text-sm text-gray-500 border-r border-gray-200 font-medium">Rp</span>
                        <input type="number" name="harga" placeholder="Masukan harga kue"
                            value="{{ old('harga') }}"
                            class="flex-1 px-4 py-3 text-sm text-gray-700 outline-none placeholder:text-gray-300">
                    </div>
                    @error('harga')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Input: Stok — tipe number untuk memastikan hanya angka --}}
                <div>
                    <label class="block text-sm text-gray-600 mb-1.5">Stok</label>
                    <input type="number" name="stok" placeholder="Masukan stok"
                        value="{{ old('stok') }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 outline-none focus:border-[#8A9E5B] focus:ring-1 focus:ring-[#8A9E5B] transition placeholder:text-gray-300">
                    @error('stok')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            {{-- ======= SECTION: Detail Tambahan ======= --}}
            <p class="text-base font-bold text-gray-800 mb-4">Detail Tambahan</p>

            {{-- Grid 3 kolom: Kolom Kiri | Kolom Tengah | Kolom Kanan (Upload) --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                {{-- === KOLOM KIRI: Berat, Tanggal Produksi, Tanggal Expired === --}}
                <div class="space-y-5">

                    {{-- Input: Berat — suffix "gram" di kanan input --}}
                    <div>
                        <label class="block text-sm text-gray-600 mb-1.5">Berat</label>
                        <div class="flex items-center border border-gray-200 rounded-xl overflow-hidden focus-within:border-[#8A9E5B] focus-within:ring-1 focus-within:ring-[#8A9E5B] transition">
                            <input type="text" name="berat" placeholder="Berat kue"
                                value="{{ old('berat') }}"
                                class="flex-1 px-4 py-3 text-sm text-gray-700 outline-none placeholder:text-gray-300">
                            {{-- Label suffix satuan berat --}}
                            <span class="px-3 py-3 bg-gray-100 text-sm text-gray-500 border-l border-gray-200 font-medium">gram</span>
                        </div>
                    </div>

                    {{-- Input: Tanggal Produksi — tipe date memunculkan date picker browser --}}
                    <div>
                        <label class="block text-sm text-gray-600 mb-1.5">Tanggal Produksi</label>
                        <input type="date" name="tanggal_produksi"
                            value="{{ old('tanggal_produksi') }}"
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 outline-none focus:border-[#8A9E5B] focus:ring-1 focus:ring-[#8A9E5B] transition placeholder:text-gray-300">
                    </div>

                    {{-- Input: Tanggal Expired — sama seperti tanggal produksi --}}
                    <div>
                        <label class="block text-sm text-gray-600 mb-1.5">Tanggal Expired</label>
                        <input type="date" name="expired_day"
                            value="{{ old('expired_day') }}"
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 outline-none focus:border-[#8A9E5B] focus:ring-1 focus:ring-[#8A9E5B] transition placeholder:text-gray-300">
                    </div>
                </div>

                {{-- === KOLOM TENGAH: Deskripsi & Status Produk === --}}
                <div class="space-y-5">

                    {{-- Textarea: Deskripsi — resize-none mencegah user memperbesar textarea secara manual --}}
                    <div>
                        <label class="block text-sm text-gray-600 mb-1.5">Deskripsi</label>
                        <textarea name="description" rows="5" placeholder="Tuliskan keterangan kue"
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 outline-none focus:border-[#8A9E5B] focus:ring-1 focus:ring-[#8A9E5B] transition placeholder:text-gray-300 resize-none">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Input: Status Produk (misal: tersedia, habis, dll) --}}
                    <div>
                        <label class="block text-sm text-gray-600 mb-1.5">Status Produk</label>
                        <input type="text" name="status" placeholder="Status kue"
                            value="{{ old('status') }}"
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 outline-none focus:border-[#8A9E5B] focus:ring-1 focus:ring-[#8A9E5B] transition placeholder:text-gray-300">
                        @error('status')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- === KOLOM KANAN: Upload Foto Produk === --}}
                <div>
                    <label class="block text-sm text-gray-600 mb-1.5">
                        Bukti / Lampiran <span class="text-red-500">*</span> {{-- Tanda * = field wajib --}}
                    </label>

                    {{-- 
                        Alpine.js x-data: state lokal 'preview' untuk menyimpan URL sementara gambar.
                        Klik area ini akan memicu input file tersembunyi via $refs.
                    --}}
                    <div x-data="{ preview: null }"
                        class="border-2 border-dashed border-gray-200 rounded-xl h-[220px] flex flex-col items-center justify-center cursor-pointer hover:border-[#8A9E5B] transition relative overflow-hidden"
                        @click="$refs.fileInput.click()">

                        {{-- Preview gambar: tampil jika 'preview' tidak null, overlay menutupi area upload --}}
                        <img x-show="preview" :src="preview"
                            class="absolute inset-0 w-full h-full object-cover rounded-xl">

                        {{-- Placeholder ikon + teks: hanya tampil jika belum ada preview --}}
                        <div x-show="!preview" class="flex flex-col items-center gap-2 text-gray-400">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="text-xs">Unggah Foto Produk</p>
                        </div>

                        {{-- 
                            Input file tersembunyi (hidden), hanya menerima file gambar (accept="image/*").
                            Event @change: membuat object URL dari file yang dipilih dan disimpan ke 'preview'.
                        --}}
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