{{-- Mewarisi layout utama admin --}}
@extends('layouts.admin')
@section('title', 'Rekomendasi Produk Promosi')

@section('content')

    {{-- Judul halaman --}}
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Manajemen Promosi</h1>

    {{--
        x-data="rekomendasiPage()": menghubungkan elemen ini dengan Alpine.js component
        yang didefinisikan di <script> bagian bawah halaman.
        Semua state dan method (selected, toggle, isSelected, goToCreate) tersedia di sini.
    --}}
    <div x-data="rekomendasiPage()" class="pb-28 sm:pb-32">

        {{-- Header banner: judul + dropdown filter/sort --}}
        <div class="bg-[#B8935A] rounded-2xl px-8 py-6 mb-8 flex items-center justify-between">
            <h2 class="text-2xl font-bold text-white">Rekomendasi Produk Promosi</h2>

            {{--
                Dropdown filter sort: Alpine.js lokal x-data terpisah dari parent.
                State 'open' hanya mengontrol buka/tutup dropdown ini saja.
            --}}
            <div x-data="{ open: false }" class="relative">
                {{-- Tombol toggle dropdown sort --}}
                <button @click="open = !open"
                    class="flex items-center gap-2 border border-white text-white text-sm font-semibold px-4 py-2 rounded-xl hover:bg-white/10 transition">
                    Penjualan Terendah
                    {{-- Ikon chevron bawah --}}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                {{--
                    Dropdown opsi sort: tampil saat 'open' true.
                    Setiap opsi mengirim query string ?sort=... untuk mengubah urutan produk.
                    @click.outside: tutup dropdown jika klik di luar area.
                --}}
                <div x-show="open" x-transition @click.outside="open = false"
                    class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 z-10 overflow-hidden">
                    <a href="?sort=penjualan_terendah" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50">Penjualan Terendah</a>
                    <a href="?sort=penjualan_tertinggi" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50">Penjualan Tertinggi</a>
                </div>
            </div>
        </div>

        {{-- Sub header: jumlah produk + tombol tambah promosi dengan badge counter --}}
        <div class="bg-[#EEF2E6] rounded-2xl px-8 py-5 mb-8 flex items-center justify-between">
            {{-- Jumlah total produk dari server --}}
            <p class="text-2xl font-bold text-[#8A9E5B]">{{ $products->count() }} Produk</p>

            {{--
                Tombol navigasi ke form tambah promosi.
                Badge bulat putih: hanya tampil jika ada produk terpilih (selected.length > 0),
                menampilkan jumlah produk yang sudah dicentang.
            --}}
            <button @click="goToCreate()"
                class="bg-[#4A5E2F] hover:bg-[#3a4c23] text-white font-semibold text-sm px-5 py-2.5 rounded-xl transition flex items-center gap-2">
                + Tambah Promosi Produk
                {{-- Badge counter produk terpilih --}}
                <span x-show="selected.length > 0"
                    class="bg-white text-[#4A5E2F] text-xs font-bold w-5 h-5 rounded-full flex items-center justify-center"
                    x-text="selected.length"></span>
            </button>
        </div>

        {{-- 
            Ranking kombinasi promo hasil Association Rules Mining
            Menampilkan top 10 pasangan produk yang sering dibeli bersama
            dengan metrik: Support, Confidence, Lift
            
            Algoritma: 2-Itemset Association Rules (bukan Apriori)
            Data source: Transaksi PreOrder dengan status valid (processing/shipping/completed)
            
            Dokumentasi lengkap: RECOMMENDATION_SYSTEM.md
        --}}
        <div x-data="{ expanded: false }" class="bg-white rounded-2xl border border-gray-200 px-6 py-5 mb-8">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <h3 class="text-lg font-bold text-gray-800">Ranking Kombinasi Promo</h3>
                    @if(!($recommendedCombinations ?? collect())->isEmpty())
                        <span class="bg-[#B8935A]/10 text-[#B8935A] text-xs font-semibold px-2.5 py-1 rounded-full">
                            {{ $recommendedCombinations->count() }} kombinasi
                        </span>
                    @endif
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-xs text-gray-500 hidden sm:inline">Support • Confidence • Lift</span>
                    @if(!($recommendedCombinations ?? collect())->isEmpty())
                        <button @click="expanded = !expanded" 
                            class="text-[#4A5E2F] hover:text-[#3a4c23] text-sm font-semibold flex items-center gap-1 transition">
                            <span x-text="expanded ? 'Sembunyikan' : 'Lihat Semua'"></span>
                            <svg class="w-4 h-4 transition-transform" :class="expanded ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                    @endif
                </div>
            </div>

            @if(($recommendedCombinations ?? collect())->isEmpty())
                <p class="text-sm text-gray-500">Belum ada data transaksi yang cukup untuk menghitung kombinasi promo.</p>
            @else
                <div class="space-y-3">
                    {{-- Show top 3 by default --}}
                    @foreach($recommendedCombinations->take(3) as $index => $combo)
                        <div class="border border-gray-100 rounded-xl px-4 py-3 hover:border-[#B8935A]/30 transition">
                            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                                <div class="flex-1 space-y-2">
                                    <div class="flex items-center gap-2">
                                        <span class="flex-shrink-0 w-6 h-6 rounded-full bg-[#B8935A] text-white text-xs font-bold flex items-center justify-center">
                                            {{ $index + 1 }}
                                        </span>
                                        <p class="text-sm font-semibold text-gray-800">
                                            {{ implode(' + ', $combo['products']) }}
                                        </p>
                                    </div>
                                    <div class="flex flex-wrap gap-2 text-[11px] font-semibold text-gray-600">
                                        <span class="bg-green-50 text-green-700 px-2.5 py-1 rounded-full">
                                            Support {{ number_format($combo['support'] * 100, 1) }}%
                                        </span>
                                        <span class="bg-blue-50 text-blue-700 px-2.5 py-1 rounded-full">
                                            Confidence {{ number_format(max($combo['confidence_a_to_b'], $combo['confidence_b_to_a']) * 100, 1) }}%
                                        </span>
                                        <span class="bg-purple-50 text-purple-700 px-2.5 py-1 rounded-full">
                                            Lift {{ number_format($combo['lift'], 2) }}x
                                        </span>
                                    </div>
                                </div>
                                <a href="{{ route('promo-admin.create') }}?{{ collect($combo['product_ids'])->map(fn ($productId) => 'product_ids[]=' . urlencode($productId))->implode('&') }}"
                                    class="inline-flex items-center justify-center bg-[#4A5E2F] hover:bg-[#3a4c23] text-white text-xs font-semibold px-4 py-2 rounded-lg transition whitespace-nowrap">
                                    <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Gunakan
                                </a>
                            </div>
                        </div>
                    @endforeach

                    {{-- Show remaining items when expanded --}}
                    @if($recommendedCombinations->count() > 3)
                        <div x-show="expanded" x-collapse class="space-y-3">
                            @foreach($recommendedCombinations->skip(3) as $index => $combo)
                                <div class="border border-gray-100 rounded-xl px-4 py-3 hover:border-[#B8935A]/30 transition">
                                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                                        <div class="flex-1 space-y-2">
                                            <div class="flex items-center gap-2">
                                                <span class="flex-shrink-0 w-6 h-6 rounded-full bg-gray-400 text-white text-xs font-bold flex items-center justify-center">
                                                    {{ $index + 4 }}
                                                </span>
                                                <p class="text-sm font-semibold text-gray-800">
                                                    {{ implode(' + ', $combo['products']) }}
                                                </p>
                                            </div>
                                            <div class="flex flex-wrap gap-2 text-[11px] font-semibold text-gray-600">
                                                <span class="bg-green-50 text-green-700 px-2.5 py-1 rounded-full">
                                                    Support {{ number_format($combo['support'] * 100, 1) }}%
                                                </span>
                                                <span class="bg-blue-50 text-blue-700 px-2.5 py-1 rounded-full">
                                                    Confidence {{ number_format(max($combo['confidence_a_to_b'], $combo['confidence_b_to_a']) * 100, 1) }}%
                                                </span>
                                                <span class="bg-purple-50 text-purple-700 px-2.5 py-1 rounded-full">
                                                    Lift {{ number_format($combo['lift'], 2) }}x
                                                </span>
                                            </div>
                                        </div>
                                        <a href="{{ route('promo-admin.create') }}?{{ collect($combo['product_ids'])->map(fn ($productId) => 'product_ids[]=' . urlencode($productId))->implode('&') }}"
                                            class="inline-flex items-center justify-center bg-[#4A5E2F] hover:bg-[#3a4c23] text-white text-xs font-semibold px-4 py-2 rounded-lg transition whitespace-nowrap">
                                            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                            </svg>
                                            Gunakan
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif
        </div>

        {{-- Kondisi: empty state atau grid produk --}}
        @if($products->isEmpty())
            {{-- Empty state: tampil jika belum ada produk --}}
            <div class="flex flex-col items-center justify-center py-24 text-gray-400">
                <p class="text-sm">Belum ada produk.</p>
            </div>
        @else
            {{-- Grid produk: responsive 2 / 3 / 4 kolom --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5">
                @foreach($products as $product)
                    @php
                        $salesCount = (int) ($salesCounts[$product->id] ?? 0);
                        $salesLabel = match (true) {
                            $salesCount === 0 => 'Belum ada penjualan',
                            $salesCount <= 3 => 'Mulai dilirik',
                            $salesCount <= 7 => 'Cukup laris',
                            default => 'Terlaris',
                        };
                    @endphp
                    <div @click="toggle(
                            {{ $product->id }},
                            '{{ addslashes($product->name) }}',
                            '{{ $product->hasMedia(App\Models\Product::MEDIA_COLLECTION) ? $product->getFirstMediaUrl(App\Models\Product::MEDIA_COLLECTION) : '' }}'
                        )"
                        :class="isSelected({{ $product->id }}) ? 'ring-2 ring-[#8A9E5B]' : ''"
                        class="bg-white rounded-3xl overflow-hidden hover:shadow-lg transition-shadow duration-300 group cursor-pointer"
                        x-data="{
                            showDetail: false,
                            openDetail(e) {
                                e.stopPropagation();
                                this.showDetail = true;
                            }
                        }">

                        {{-- SECTION FOTO PRODUK --}}
                        <div class="mx-3 mt-3 h-44 rounded-2xl overflow-hidden">
                            {{-- Tampilkan foto jika ada, placeholder jika tidak --}}
                            @if($product->hasMedia(App\Models\Product::MEDIA_COLLECTION))
                                <img src="{{ $product->getFirstMediaUrl(App\Models\Product::MEDIA_COLLECTION) }}"
                                    alt="{{ $product->name }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            @else
                                {{-- Placeholder ikon gambar jika produk belum punya foto --}}
                                <div class="w-full h-full bg-gray-100 flex items-center justify-center">
                                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        {{-- SECTION INFO & AKSI PRODUK --}}
                        <div class="px-4 pt-4 pb-4">
                            {{-- Nama produk --}}
                            <h3 class="font-bold text-gray-800 text-lg leading-tight mb-1">{{ $product->name }}</h3>
                            <div class="flex items-center gap-2 mb-2">
                                <span class="inline-flex items-center rounded-full bg-[#EEF2E6] px-2.5 py-1 text-[11px] font-semibold text-[#5F6F43]">
                                    {{ $salesLabel }}
                                </span>
                                <span class="text-xs text-gray-500">
                                    {{ $salesCount }}x terjual
                                </span>
                            </div>
                            {{-- Deskripsi dibatasi 2 baris --}}
                            <p class="text-gray-400 text-sm leading-relaxed line-clamp-2 mb-3">{{ $product->description }}</p>
                            {{-- Harga diformat dengan pemisah ribuan --}}
                            <p class="text-[#8A9E5B] font-bold text-xl mb-4">Rp {{ number_format($product->price, 0, ',', '.') }}</p>

                            {{-- SECTION TOMBOL AKSI --}}
                            <div class="flex items-center justify-between gap-3">

                                {{-- Tombol Detail: buka popup modal detail produk --}}
                                <button type="button"
                                    @click="openDetail($event)"
                                    class="flex items-center gap-1.5 text-gray-500 hover:text-[#4A5E2F] transition text-xs font-medium">
                                    {{-- Ikon mata (eye), mirip referensi --}}
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Detail
                                </button>

                                {{--
                                    Tombol Checklist: toggle pilih/batal pilih produk untuk promosi.
                                --}}
                                <button type="button"
                                    :class="isSelected({{ $product->id }})
                                        ? 'bg-[#8A9E5B] border-[#8A9E5B]'
                                        : 'border-gray-300 hover:border-[#8A9E5B]'"
                                    class="w-6 h-6 rounded-full border-2 flex items-center justify-center transition pointer-events-none"
                                    tabindex="-1"
                                    aria-hidden="true">
                                    {{-- Ikon centang: hanya muncul saat produk terpilih --}}
                                    <svg x-show="isSelected({{ $product->id }})"
                                        class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </button>

                            </div>
                        </div>

                        {{-- ===== MODAL DETAIL PRODUK ===== --}}
                        <div x-show="showDetail" x-transition
                            class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4"
                            @click.self="showDetail = false"
                            style="display:none">
                            <div class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl overflow-hidden" @click.stop>

                                {{-- ===== HEADER: label + nama + tombol X ===== --}}
                                <div class="px-8 pt-7 pb-5">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            {{-- Label kecil "DETAIL PRODUK" --}}
                                            <p class="text-xs font-bold tracking-widest text-[#8A9E5B] uppercase mb-1">Detail Produk</p>
                                            {{-- Nama produk besar --}}
                                            <h3 class="text-2xl font-bold text-gray-800">{{ $product->name }}</h3>
                                        </div>
                                        {{-- Tombol tutup X --}}
                                        <button @click="showDetail = false"
                                            class="text-gray-400 hover:text-gray-600 transition mt-1 flex-shrink-0">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                {{-- ===== BODY: foto kiri + info kanan ===== --}}
                                <div class="flex gap-6 px-8 pb-6">

                                    {{-- Foto produk: kiri, ukuran kotak --}}
                                    <div class="flex-shrink-0 w-56 h-44 rounded-2xl overflow-hidden bg-[#C4A882]">
                                        @if($product->hasMedia(App\Models\Product::MEDIA_COLLECTION))
                                            <img src="{{ $product->getFirstMediaUrl(App\Models\Product::MEDIA_COLLECTION) }}"
                                                alt="{{ $product->name }}"
                                                class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <svg class="w-12 h-12 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Info kanan --}}
                                    <div class="flex-1 flex flex-col justify-between">
                                        <div>
                                            {{-- Harga --}}
                                            <p class="text-xs text-gray-400 mb-0.5">Harga</p>
                                            <p class="text-[#8A9E5B] font-bold text-2xl mb-4">Rp {{ number_format($product->price, 0, ',', '.') }}</p>

                                            {{-- Deskripsi --}}
                                            <p class="text-xs text-gray-400 mb-1">Deskripsi</p>
                                            <p class="text-gray-600 text-sm leading-relaxed">{{ $product->description ?: 'Tidak ada deskripsi.' }}</p>

                                            {{-- Info tambahan: penjualan, stok --}}
                                            @php
                                                $salesCount = (int) ($salesCounts[$product->id] ?? 0);
                                            @endphp
                                            <div class="flex items-center gap-3 mt-3">
                                                <span class="bg-[#EEF2E6] text-[#5F6F43] text-xs font-semibold px-2.5 py-1 rounded-full">
                                                    {{ $salesCount }}x terjual
                                                </span>
                                                @if(isset($product->stok))
                                                <span class="bg-gray-100 text-gray-500 text-xs font-semibold px-2.5 py-1 rounded-full">
                                                    Stok: {{ $product->stok }}
                                                </span>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- Tombol bawah: Tutup + Pilih --}}
                                        <div class="flex items-center justify-end gap-3 mt-5">
                                            <button type="button"
                                                @click="showDetail = false"
                                                class="bg-[#4A5E2F] hover:bg-[#3a4c23] text-white font-semibold text-sm px-6 py-2.5 rounded-xl transition">
                                                Tutup
                                            </button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        {{-- ===== END MODAL ===== --}}

                    </div>
                @endforeach
            </div>
        @endif

        {{--
            Floating Action Button (FAB): tombol mengambang di tengah bawah layar.
            Selalu terlihat saat scroll, memudahkan akses ke form tambah promosi.
            Badge counter juga tampil di sini sama seperti tombol di sub header.
        --}}
        <div class="fixed bottom-8 left-1/2 -translate-x-1/2 z-50">
            <button @click="goToCreate()"
                class="bg-[#4A5E2F] hover:bg-[#3a4c23] text-white font-semibold px-8 py-3.5 rounded-full shadow-lg transition flex items-center gap-2">
                + Tambah Promosi
                {{-- Badge counter produk terpilih --}}
                <span x-show="selected.length > 0"
                    class="bg-white text-[#4A5E2F] text-xs font-bold w-5 h-5 rounded-full flex items-center justify-center"
                    x-text="selected.length"></span>
            </button>
        </div>

    </div>

    <script>
        // Alpine.js component function untuk halaman rekomendasi
        function rekomendasiPage() {
            return {
                // Array produk yang sedang dipilih untuk dijadikan promosi
                selected: [],
                
                // State untuk show/hide rekomendasi kombinasi
                showAllCombinations: false,

                // Snapshot data produk untuk dipakai saat memilih kombinasi otomatis.
                allProducts: @js($products->map(fn($product) => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'image' => $product->hasMedia(App\Models\Product::MEDIA_COLLECTION)
                        ? $product->getFirstMediaUrl(App\Models\Product::MEDIA_COLLECTION)
                        : '',
                ])->values()),

                // Toggle pilih/batal pilih produk berdasarkan ID
                // Jika sudah ada di selected → hapus; jika belum → tambahkan
                toggle(id, name, image) {
                    const idx = this.selected.findIndex(p => p.id === id);
                    if (idx >= 0) {
                        this.selected.splice(idx, 1); // Hapus dari array
                    } else {
                        this.selected.push({ id, name, image }); // Tambah ke array
                    }
                },

                // Cek apakah produk dengan ID tertentu sudah ada di selected
                isSelected(id) {
                    return this.selected.some(p => p.id === id);
                },

                // Navigasi ke halaman form tambah promosi
                // Jika tidak ada produk terpilih → buka form kosong
                // Jika ada produk terpilih → kirim ID sebagai query string (?product_ids[]=1&product_ids[]=2)
                // sehingga form tambah promosi bisa langsung pra-mengisi produk yang dipilih
                goToCreate(productIds = null) {
                    const ids = Array.isArray(productIds) ? productIds : this.selected.map(p => p.id);

                    if (ids.length === 0) {
                        window.location.href = '{{ route('promo-admin.create') }}';
                        return;
                    }
                    const params = ids.map((id) => `product_ids[]=${encodeURIComponent(id)}`).join('&');
                    window.location.href = '{{ route('promo-admin.create') }}?' + params;
                }
            }
        }
    </script>

@endsection