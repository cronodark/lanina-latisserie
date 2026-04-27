@extends('layouts.admin')
@section('title', 'Rekomendasi Produk Promosi')

@section('content')

    <h1 class="text-2xl font-bold text-gray-800 mb-6">Manajemen Promosi</h1>

    <div x-data="rekomendasiPage()">

        {{-- Header banner --}}
        <div class="bg-[#B8935A] rounded-2xl px-8 py-6 mb-8 flex items-center justify-between">
            <h2 class="text-2xl font-bold text-white">Rekomendasi Produk Promosi</h2>
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open"
                    class="flex items-center gap-2 border border-white text-white text-sm font-semibold px-4 py-2 rounded-xl hover:bg-white/10 transition">
                    Penjualan Terendah
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open" x-transition @click.outside="open = false"
                    class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 z-10 overflow-hidden">
                    <a href="?sort=penjualan_terendah" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50">Penjualan Terendah</a>
                    <a href="?sort=penjualan_tertinggi" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50">Penjualan Tertinggi</a>
                    <a href="?sort=stok_terendah" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50">Stok Terendah</a>
                </div>
            </div>
        </div>

        {{-- Sub header --}}
        <div class="bg-[#EEF2E6] rounded-2xl px-8 py-5 mb-8 flex items-center justify-between">
            <p class="text-2xl font-bold text-[#8A9E5B]">{{ $products->count() }} Produk</p>
            <button @click="goToCreate()"
                class="bg-[#4A5E2F] hover:bg-[#3a4c23] text-white font-semibold text-sm px-5 py-2.5 rounded-xl transition flex items-center gap-2">
                + Tambah Promosi Produk
                <span x-show="selected.length > 0"
                    class="bg-white text-[#4A5E2F] text-xs font-bold w-5 h-5 rounded-full flex items-center justify-center"
                    x-text="selected.length"></span>
            </button>
        </div>

        @if($products->isEmpty())
            <div class="flex flex-col items-center justify-center py-24 text-gray-400">
                <p class="text-sm">Belum ada produk.</p>
            </div>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5">
                @foreach($products as $product)
                    <div class="bg-white rounded-3xl overflow-hidden hover:shadow-lg transition-shadow duration-300 group">

                        <div class="mx-3 mt-3 h-44 rounded-2xl overflow-hidden">
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

                        <div class="px-4 pt-4 pb-4">
                            <h3 class="font-bold text-gray-800 text-lg leading-tight mb-1">{{ $product->name }}</h3>
                            <p class="text-gray-400 text-sm leading-relaxed line-clamp-2 mb-3">{{ $product->description }}</p>
                            <p class="text-[#8A9E5B] font-bold text-xl mb-4">Rp {{ number_format($product->harga, 0, ',', '.') }}</p>

                            <div class="flex items-center justify-end gap-3">
                                {{-- Hapus --}}
                                <form action="{{ route('product-admin.destroy', $product->id) }}" method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-500 transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>

                                {{-- Checklist --}}
                                <button type="button"
                                    @click="toggle(
                                        {{ $product->id }},
                                        '{{ addslashes($product->name) }}',
                                        '{{ $product->hasMedia(App\Models\Product::MEDIA_COLLECTION) ? $product->getFirstMediaUrl(App\Models\Product::MEDIA_COLLECTION) : '' }}'
                                    )"
                                    :class="isSelected({{ $product->id }})
                                        ? 'bg-[#8A9E5B] border-[#8A9E5B]'
                                        : 'border-gray-300 hover:border-[#8A9E5B]'"
                                    class="w-6 h-6 rounded-full border-2 flex items-center justify-center transition">
                                    <svg x-show="isSelected({{ $product->id }})"
                                        class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        @endif

        {{-- Floating button --}}
        <div class="fixed bottom-8 left-1/2 -translate-x-1/2 z-50">
            <button @click="goToCreate()"
                class="bg-[#4A5E2F] hover:bg-[#3a4c23] text-white font-semibold px-8 py-3.5 rounded-full shadow-lg transition flex items-center gap-2">
                + Tambah Promosi
                <span x-show="selected.length > 0"
                    class="bg-white text-[#4A5E2F] text-xs font-bold w-5 h-5 rounded-full flex items-center justify-center"
                    x-text="selected.length"></span>
            </button>
        </div>

    </div>

    <script>
        function rekomendasiPage() {
            return {
                selected: [],
                toggle(id, name, image) {
                    const idx = this.selected.findIndex(p => p.id === id);
                    if (idx >= 0) {
                        this.selected.splice(idx, 1);
                    } else {
                        this.selected.push({ id, name, image });
                    }
                },
                isSelected(id) {
                    return this.selected.some(p => p.id === id);
                },
                goToCreate() {
                    if (this.selected.length === 0) {
                        window.location.href = '{{ route('promo-admin.create') }}';
                        return;
                    }
                    const params = this.selected.map(p => `product_ids[]=${p.id}`).join('&');
                    window.location.href = '{{ route('promo-admin.create') }}?' + params;
                }
            }
        }
    </script>

@endsection