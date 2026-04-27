@extends('layouts.admin')
@section('title', 'Edit Promosi Produk')

@section('content')

    <h1 class="text-2xl font-bold text-gray-800 mb-6">Manajemen Promosi</h1>

    {{-- Header banner --}}
    <div class="bg-[#B8935A] rounded-2xl px-8 py-6 mb-8 flex items-center gap-5">
        <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-white">Edit Promosi Produk</h2>
    </div>

    {{-- Form card --}}
    <div class="bg-white rounded-2xl shadow-sm p-8">

        <h3 class="text-lg font-bold text-gray-800 mb-6">Tambah Promosi Produk</h3>
        <hr class="border-gray-200 mb-6">

        <form action="{{ route('promo-admin.update', $promo->id) }}" method="POST" enctype="multipart/form-data"
            x-data="{
                selectedProducts: {{ $promo->promoDetails->map(fn($d) => ['id' => $d->product->id, 'name' => $d->product->name, 'image' => $d->product->hasMedia(App\Models\Product::MEDIA_COLLECTION) ? $d->product->getFirstMediaUrl(App\Models\Product::MEDIA_COLLECTION) : ''])->toJson() }},
                allProducts: {{ $allProducts->map(fn($p) => ['id' => $p->id, 'name' => $p->name, 'image' => $p->hasMedia(App\Models\Product::MEDIA_COLLECTION) ? $p->getFirstMediaUrl(App\Models\Product::MEDIA_COLLECTION) : ''])->toJson() }},
                showDropdown: false,
                removeProduct(id) {
                    this.selectedProducts = this.selectedProducts.filter(p => p.id !== id);
                },
                addProduct(product) {
                    if (!this.selectedProducts.find(p => p.id === product.id)) {
                        this.selectedProducts.push(product);
                    }
                    this.showDropdown = false;
                }
            }">
            @csrf
            @method('PUT')

            {{-- Informasi Utama --}}
            <p class="text-base font-bold text-gray-800 mb-4">Informasi Utama</p>

            {{-- Nama Kue --}}
            <div class="mb-6">
                <label class="block text-sm text-gray-600 mb-1.5">Nama Kue</label>
                <div class="flex items-center gap-3 flex-wrap border border-gray-200 rounded-xl px-3 py-2 min-h-[56px]">
                    <template x-for="product in selectedProducts" :key="product.id">
                        <div class="flex items-center gap-2 bg-gray-100 rounded-xl px-3 py-2">
                            <img :src="product.image || ''" class="w-8 h-8 rounded-lg object-cover bg-gray-200">
                            <span class="text-sm font-medium text-gray-700" x-text="product.name"></span>
                            <input type="hidden" name="product_ids[]" :value="product.id">
                            <button type="button" @click="removeProduct(product.id)"
                                class="text-gray-400 hover:text-gray-600 ml-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </template>
                    <div class="relative ml-auto">
                        <button type="button" @click="showDropdown = !showDropdown"
                            class="bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-semibold px-4 py-2 rounded-xl transition">
                            + Tambah Produk
                        </button>
                        <div x-show="showDropdown" x-transition @click.outside="showDropdown = false"
                            class="absolute right-0 top-10 w-56 bg-white rounded-xl shadow-lg border border-gray-100 z-20 max-h-60 overflow-y-auto">
                            <template x-for="product in allProducts" :key="product.id">
                                <button type="button" @click="addProduct(product)"
                                    class="w-full flex items-center gap-3 px-4 py-2.5 hover:bg-gray-50 transition">
                                    <img :src="product.image || ''" class="w-8 h-8 rounded-lg object-cover bg-gray-200">
                                    <span class="text-sm text-gray-700" x-text="product.name"></span>
                                </button>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Harga Total Awal, Harga Promo, Persenan, Stok --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-4">
                <div>
                    <label class="block text-sm text-gray-600 mb-1.5">Harga Total Awal</label>
                    <input type="number" name="actual_price" placeholder="Masukan harga kue"
                        value="{{ old('actual_price', $promo->actual_price) }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 outline-none focus:border-[#8A9E5B] focus:ring-1 focus:ring-[#8A9E5B] transition placeholder:text-gray-300">
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1.5">Harga Promo</label>
                    <input type="number" name="price" placeholder="Masukan harga kue"
                        value="{{ old('price', $promo->price) }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 outline-none focus:border-[#8A9E5B] focus:ring-1 focus:ring-[#8A9E5B] transition placeholder:text-gray-300">
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1.5">Persenan</label>
                    <input type="text" value="{{ $promo->percentage }}%" disabled
                        class="w-full border border-gray-100 bg-gray-50 rounded-xl px-4 py-3 text-sm text-gray-500 cursor-not-allowed">
                    <p class="text-xs text-gray-400 mt-1">Dihitung otomatis dari harga</p>
                </div>
            </div>
            <div class="mb-8 w-full md:w-1/3">
                <label class="block text-sm text-gray-600 mb-1.5">Stok</label>
                <input type="number" name="stok" placeholder="Masukan stok"
                    value="{{ old('stok', $promo->stok ?? '') }}"
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 outline-none focus:border-[#8A9E5B] focus:ring-1 focus:ring-[#8A9E5B] transition placeholder:text-gray-300">
            </div>

            <hr class="border-gray-200 mb-6">

            {{-- Detail Tambahan --}}
            <p class="text-base font-bold text-gray-800 mb-2">Detail Tambahan</p>
            <p class="text-sm font-semibold text-gray-700 mb-4">Durasi Promosi</p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                {{-- Kolom kiri --}}
                <div class="space-y-5">
                    <div>
                        <label class="block text-sm text-gray-600 mb-1.5">Tanggal Mulai Promosi</label>
                        <input type="date" name="date_start"
                            value="{{ old('date_start', $promo->date_start ?? '') }}"
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 outline-none focus:border-[#8A9E5B] focus:ring-1 focus:ring-[#8A9E5B] transition">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1.5">Tanggal Berakhir Promosi</label>
                        <input type="date" name="date_until"
                            value="{{ old('date_until', $promo->date_until ? $promo->date_until->format('Y-m-d') : '') }}"
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 outline-none focus:border-[#8A9E5B] focus:ring-1 focus:ring-[#8A9E5B] transition">
                    </div>
                </div>

                {{-- Kolom tengah --}}
                <div>
                    <label class="block text-sm text-gray-600 mb-1.5">Deskripsi</label>
                    <textarea name="description" rows="5" placeholder="Tuliskan keterangan kue"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 outline-none focus:border-[#8A9E5B] focus:ring-1 focus:ring-[#8A9E5B] transition placeholder:text-gray-300 resize-none">{{ old('description', $promo->description) }}</textarea>
                </div>

                {{-- Kolom kanan — Thumbnail --}}
                <div>
                    <label class="block text-sm text-gray-600 mb-1.5">
                        Thumbnail Produk
                        <span class="text-red-400 text-xs ml-1">(khusus bundle)</span>
                    </label>
                    <div x-data="{ preview: '{{ $promo->image ?? '' }}' }"
                        class="border-2 border-dashed border-gray-200 rounded-xl h-[176px] flex flex-col items-center justify-center cursor-pointer hover:border-[#8A9E5B] transition relative overflow-hidden bg-gray-50"
                        @click="$refs.fileInput.click()">
                        <img x-show="preview" :src="preview"
                            class="absolute inset-0 w-full h-full object-cover rounded-xl">
                        <div x-show="!preview" class="flex flex-col items-center gap-2 text-gray-400">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-xs">Unggah Foto Produk</p>
                        </div>
                        <input type="file" name="image" accept="image/*" x-ref="fileInput" class="hidden"
                            @change="preview = URL.createObjectURL($event.target.files[0])">
                    </div>
                </div>

            </div>

            {{-- Submit --}}
            <div class="flex justify-end mt-8">
                <button type="submit"
                    class="bg-[#4A5E2F] hover:bg-[#3a4c23] text-white font-semibold px-10 py-3 rounded-xl transition text-sm">
                    Simpan Perubahan
                </button>
            </div>

        </form>
    </div>

@endsection