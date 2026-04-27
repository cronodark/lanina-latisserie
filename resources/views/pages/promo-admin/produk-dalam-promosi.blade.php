@extends('layouts.admin')
@section('title', 'Produk Dalam Promosi')

@section('content')

    <h1 class="text-2xl font-bold text-gray-800 mb-6">Manajemen Promosi</h1>

    {{-- Tab Navigation --}}
    <div class="grid grid-cols-3 gap-0 mb-8 rounded-2xl overflow-hidden border border-gray-200 bg-white">
        <a href="{{ route('promo-admin.status', 'terjadwal') }}"
            class="flex items-center justify-center gap-3 px-6 py-4 text-sm font-semibold transition text-gray-500 hover:bg-gray-50">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Terjadwal
        </a>
        <a href="{{ route('promo-admin.status', 'aktif') }}"
            class="flex items-center justify-center gap-3 px-6 py-4 text-sm font-semibold transition border-l border-r border-gray-200 bg-[#EEF2E6] text-[#4A5E2F] border-b-2 border-b-[#8A9E5B]">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
            </svg>
            Aktif
        </a>
        <a href="{{ route('promo-admin.status', 'berakhir') }}"
            class="flex items-center justify-center gap-3 px-6 py-4 text-sm font-semibold transition text-gray-500 hover:bg-gray-50">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Berakhir
        </a>
    </div>

    {{-- Sub header --}}
    <div class="bg-[#EEF2E6] rounded-2xl px-8 py-5 mb-8 flex items-center justify-between">
        <p class="text-2xl font-bold text-[#8A9E5B]">{{ $promos->count() }} Produk</p>
        <a href="{{ route('promo-admin.create') }}"
            class="bg-[#4A5E2F] hover:bg-[#3a4c23] text-white font-semibold text-sm px-5 py-2.5 rounded-xl transition">
            + Tambah Promosi Produk
        </a>
    </div>

    @if($promos->isEmpty())
        <div class="flex flex-col items-center justify-center py-24 text-gray-400">
            <p class="text-sm">Tidak ada produk dalam promosi aktif.</p>
        </div>
    @else
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5">
            @foreach($promos as $promo)
                <div class="bg-white rounded-3xl overflow-hidden hover:shadow-lg transition-shadow duration-300 group">

                    <div class="mx-3 mt-3 relative">
                        <div class="h-44 rounded-2xl overflow-hidden">
                            @if($promo->image)
                                <img src="{{ $promo->image }}" alt="{{ $promo->name }}"
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
                        <div class="absolute top-2 left-2 bg-[#8A9E5B] text-white text-sm font-bold px-3 py-1.5 rounded-xl flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            {{ $promo->percentage }}%
                        </div>
                    </div>

                    <div class="px-4 pt-4 pb-4">
                        <h3 class="font-bold text-gray-800 text-lg leading-tight mb-1">{{ $promo->name }}</h3>
                        <p class="text-gray-400 text-sm leading-relaxed line-clamp-2 mb-2">{{ $promo->description }}</p>
                        <div class="flex items-center gap-2 mb-3">
                            <p class="text-[#8A9E5B] font-bold text-base">Rp {{ number_format($promo->price, 0, ',', '.') }}</p>
                            <p class="text-red-400 text-sm line-through">Rp {{ number_format($promo->actual_price, 0, ',', '.') }}</p>
                        </div>

                        <p class="text-gray-500 text-xs mb-2">Berakhir tanggal:</p>
                        <div class="inline-block bg-[#8A9E5B] text-white text-xs font-semibold px-4 py-2 rounded-xl mb-3">
                            {{ \Carbon\Carbon::parse($promo->date_until)->translatedFormat('d F Y') }}
                        </div>

                        <div class="flex items-center justify-end gap-3">
                            <form action="{{ route('promo-admin.destroy', $promo->id) }}" method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus promosi ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-500 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                            <a href="{{ route('promo-admin.edit', $promo->id) }}" class="text-[#8A9E5B] hover:text-[#4A5E2F] transition">
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