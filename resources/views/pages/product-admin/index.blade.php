{{-- Mewarisi layout utama admin --}}
@extends('layouts.admin')
@section('title', 'Daftar Produk')

@section('content')

    {{-- Judul halaman --}}
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Manajemen Produk</h1>

    {{--
        Alert sukses: hanya tampil jika ada session 'success'.
        Biasanya di-set oleh controller setelah berhasil tambah/edit/hapus produk.
    --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 text-sm px-5 py-3 rounded-xl mb-6 flex items-center gap-2">
            {{-- Ikon centang --}}
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Header bar: menampilkan jumlah produk dan tombol tambah --}}
    <div class="bg-[#EEF2E6] rounded-2xl px-8 py-5 mb-8 flex items-center justify-between">
        {{-- $products->count() menghitung total produk yang ada --}}
        <p class="text-2xl font-bold text-[#8A9E5B]">{{ $products->count() }} Produk</p>
        {{-- Tombol navigasi ke halaman form tambah produk --}}
        <a href="{{ route('product-admin.create') }}"
            class="bg-[#4A5E2F] hover:bg-[#3a4c23] text-white font-semibold text-sm px-5 py-2.5 rounded-xl transition flex items-center gap-2">
            {{-- Ikon plus --}}
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Produk
        </a>
    </div>

    {{--
        Kondisi: jika tidak ada produk sama sekali tampilkan empty state,
        jika ada tampilkan grid kartu produk.
    --}}
    @if($products->isEmpty())

        {{-- Empty state: tampil saat belum ada produk terdaftar --}}
        <div class="flex flex-col items-center justify-center py-24 text-gray-400">
            {{-- Ikon produk samar sebagai ilustrasi kosong --}}
            <svg class="w-16 h-16 mb-4 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
            <p class="text-sm">Belum ada produk yang terdaftar.</p>
            {{-- Shortcut link langsung ke form tambah produk --}}
            <a href="{{ route('product-admin.create') }}" class="mt-3 text-[#8A9E5B] text-sm font-semibold hover:underline">
                + Tambah Produk
            </a>
        </div>

    @else

        {{-- Grid produk: responsive 2 kolom di mobile, 3 di tablet, 4 di desktop --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5">

            {{-- Iterasi setiap produk dari koleksi $products --}}
            @foreach($products as $product)
                <div x-data="{ showDetail: false }" class="bg-white rounded-3xl overflow-hidden hover:shadow-lg transition-shadow duration-300 group h-full flex flex-col">

                    {{-- SECTION FOTO PRODUK --}}
                    <div class="mx-3 mt-3 h-48 rounded-2xl overflow-hidden">
                        {{--
                            Cek apakah produk punya media di MEDIA_COLLECTION.
                            Jika ada: tampilkan foto dengan efek zoom saat hover (group-hover:scale-105).
                            Jika tidak: tampilkan placeholder ikon gambar abu-abu.
                        --}}
                        @if($product->hasMedia(App\Models\Product::MEDIA_COLLECTION))
                            <img src="{{ $product->getFirstMediaUrl(App\Models\Product::MEDIA_COLLECTION) }}"
                                alt="{{ $product->name }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        @else
                            {{-- Placeholder jika produk belum punya foto --}}
                            <div class="w-full h-full bg-gray-100 flex items-center justify-center">
                                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif
                    </div>

                    {{-- SECTION INFO & AKSI PRODUK --}}
                    <div class="px-4 pt-4 pb-4 flex-1 flex flex-col">
                        {{-- Nama produk --}}
                        <h3 class="font-bold text-gray-800 text-lg leading-tight mb-1">{{ $product->name }}</h3>
                        {{-- Deskripsi: dibatasi 2 baris dengan line-clamp-2 agar kartu seragam --}}
                        <p class="text-gray-400 text-sm leading-relaxed line-clamp-2 mb-3">{{ $product->description }}</p>
                        {{-- Harga: diformat dengan pemisah ribuan (titik) --}}
                        <p class="text-[#8A9E5B] font-bold text-xl">Rp {{ number_format($product->price, 0, ',', '.') }}</p>

                        {{-- SECTION TOMBOL AKSI (Edit & Hapus) --}}
                        <div class="flex items-center justify-between gap-3 mt-auto pt-3">

                            <button type="button"
                                @click="showDetail = true"
                                class="flex items-center gap-2 text-sm font-semibold text-[#4A5E2F] hover:text-[#3a4c23] transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Detail
                            </button>

                            <div class="flex items-center gap-3">

                            {{--
                                Tombol Hapus: menggunakan form POST + @method('DELETE') karena
                                HTML tidak mendukung method DELETE secara langsung.
                                onsubmit: memunculkan konfirmasi browser sebelum menghapus.
                            --}}
                            <form action="{{ route('product-admin.destroy', $product->id) }}" method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                @csrf
                                @method('DELETE') {{-- Method spoofing untuk request DELETE --}}
                                <button type="submit"
                                    class="flex items-center justify-center text-red-400 hover:text-red-500 transition">
                                    {{-- Ikon tempat sampah --}}
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>

                            {{-- Tombol Edit: navigasi ke halaman form edit dengan ID produk --}}
                            <a href="{{ route('product-admin.edit', $product->id) }}"
                                class="flex items-center justify-center text-[#8A9E5B] hover:text-[#4A5E2F] transition">
                                {{-- Ikon pensil --}}
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>

                            </div>

                        </div>

                        {{-- Modal detail produk --}}
                        <div x-show="showDetail" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center px-4 py-6"
                            @keydown.escape.window="showDetail = false">
                            <div class="absolute inset-0 bg-black/50" @click="showDetail = false"></div>

                            <div class="relative z-10 w-full max-w-2xl rounded-3xl bg-white shadow-2xl overflow-hidden">
                                <div class="flex items-start justify-between gap-4 px-6 py-5 border-b border-gray-100">
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#8A9E5B]">Detail Produk</p>
                                        <h3 class="mt-1 text-2xl font-bold text-gray-800">{{ $product->name }}</h3>
                                    </div>
                                    <button type="button" @click="showDetail = false"
                                        class="text-gray-400 hover:text-gray-700 transition">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>

                                <div class="grid gap-0 md:grid-cols-[1.1fr_0.9fr]">
                                    <div class="bg-gray-50 p-5">
                                        @if($product->hasMedia(App\Models\Product::MEDIA_COLLECTION))
                                            <img src="{{ $product->getFirstMediaUrl(App\Models\Product::MEDIA_COLLECTION) }}"
                                                alt="{{ $product->name }}"
                                                class="h-64 w-full rounded-2xl object-cover">
                                        @else
                                            <div class="h-64 w-full rounded-2xl bg-gray-100 flex items-center justify-center">
                                                <svg class="w-14 h-14 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="p-6 space-y-5">
                                        <div>
                                            <p class="text-sm text-gray-400">Harga</p>
                                            <p class="text-2xl font-bold text-[#8A9E5B]">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                        </div>

                                        <div>
                                            <p class="text-sm text-gray-400 mb-1">Deskripsi</p>
                                            <p class="text-sm leading-6 text-gray-700">
                                                {{ $product->description }}
                                            </p>
                                        </div>

                                        @if(!empty($product->production_estimate))
                                            <div>
                                                <p class="text-sm text-gray-400">Estimasi Pengerjaan</p>
                                                <p class="text-sm font-semibold text-gray-800">{{ $product->production_estimate }} hari</p>
                                            </div>
                                        @endif

                                        <div class="flex justify-end pt-2">
                                            <button type="button" @click="showDetail = false"
                                                class="rounded-xl bg-[#4A5E2F] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[#3a4c23] transition">
                                                Tutup
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>

    @endif

@endsection
