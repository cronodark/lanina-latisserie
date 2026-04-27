@extends('layouts.admin')
@section('title', 'Daftar Produk')

@section('content')

    <h1 class="text-2xl font-bold text-gray-800 mb-6">Manajemen Produk</h1>

    {{-- Success Alert --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 text-sm px-5 py-3 rounded-xl mb-6 flex items-center gap-2">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Header bar --}}
    <div class="bg-[#EEF2E6] rounded-2xl px-8 py-5 mb-8 flex items-center justify-between">
        <p class="text-2xl font-bold text-[#8A9E5B]">{{ $products->count() }} Produk</p>
        <a href="{{ route('product-admin.create') }}"
            class="bg-[#4A5E2F] hover:bg-[#3a4c23] text-white font-semibold text-sm px-5 py-2.5 rounded-xl transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Produk
        </a>
    </div>

    {{-- Product grid --}}
    @if($products->isEmpty())
        <div class="flex flex-col items-center justify-center py-24 text-gray-400">
            <svg class="w-16 h-16 mb-4 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
            <p class="text-sm">Belum ada produk yang terdaftar.</p>
            <a href="{{ route('product-admin.create') }}" class="mt-3 text-[#8A9E5B] text-sm font-semibold hover:underline">
                + Tambah Produk
            </a>
        </div>
    @else
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5">
            @foreach($products as $product)
                <div class="bg-white rounded-3xl overflow-hidden hover:shadow-lg transition-shadow duration-300 group">

                    {{-- Foto --}}
                    <div class="mx-3 mt-3 h-48 rounded-2xl overflow-hidden">
                        @if($product->hasMedia(App\Models\Product::MEDIA_COLLECTION))
                            <img src="{{ $product->getFirstMediaUrl(App\Models\Product::MEDIA_COLLECTION) }}"
                                alt="{{ $product->name }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        @else
                            <div class="w-full h-full bg-gray-100 flex items-center justify-center">
                                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif
                    </div>

                    {{-- Info --}}
                    <div class="px-4 pt-4 pb-4">
                        <h3 class="font-bold text-gray-800 text-lg leading-tight mb-1">{{ $product->name }}</h3>
                        <p class="text-gray-400 text-sm leading-relaxed line-clamp-2 mb-3">{{ $product->description }}</p>
                        <p class="text-[#8A9E5B] font-bold text-xl">Rp {{ number_format($product->harga, 0, ',', '.') }}</p>

                        {{-- Actions --}}
                        <div class="flex items-center justify-end gap-3 mt-3">

                            {{-- Hapus --}}
                            <form action="{{ route('product-admin.destroy', $product->id) }}" method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="flex items-center justify-center text-red-400 hover:text-red-500 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>

                            {{-- Edit --}}
                            <a href="{{ route('product-admin.edit', $product->id) }}"
                                class="flex items-center justify-center text-[#8A9E5B] hover:text-[#4A5E2F] transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>

                        </div>
                    </div>

                </div>
            @endforeach
        </div>
    @endif

@endsection