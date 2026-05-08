{{-- Mewarisi layout utama admin --}}
@extends('layouts.admin')
@section('title', 'Status Promosi')

@section('content')

    {{-- Judul halaman --}}
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Manajemen Promosi</h1>

    {{-- 
        Tab Navigation: 3 tab status promosi (Terjadwal | Aktif | Berakhir).
        Setiap tab adalah link ke route yang sama dengan parameter $tab berbeda.
        Styling aktif/non-aktif ditentukan oleh kondisi $tab === '...' dari controller.
    --}}
    <div class="grid grid-cols-3 gap-4 mb-8">

        {{-- Tab Terjadwal --}}
        <a href="{{ route('promo-admin.status', 'terjadwal') }}"
            class="flex items-center justify-center gap-2 px-6 py-4 text-sm font-semibold transition rounded-2xl border-2
                   {{ $tab === 'terjadwal' ? 'bg-[#E0F4FC] text-[#4AABCF] border-[#4AABCF]' : 'bg-white text-gray-500 border-gray-200 hover:border-[#4AABCF] hover:text-[#4AABCF]' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Terjadwal
        </a>

        {{-- Tab Aktif --}}
        <a href="{{ route('promo-admin.status', 'aktif') }}"
            class="flex items-center justify-center gap-2 px-6 py-4 text-sm font-semibold transition rounded-2xl border-2
                   {{ $tab === 'aktif' ? 'bg-[#EEF2E6] text-[#4A5E2F] border-[#8A9E5B]' : 'bg-white text-gray-500 border-gray-200 hover:border-[#8A9E5B] hover:text-[#4A5E2F]' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
            </svg>
            Aktif
        </a>

        {{-- Tab Berakhir --}}
        <a href="{{ route('promo-admin.status', 'berakhir') }}"
            class="flex items-center justify-center gap-2 px-6 py-4 text-sm font-semibold transition rounded-2xl border-2
                   {{ $tab === 'berakhir' ? 'bg-gray-100 text-[#6B7E4A] border-[#6B7E4A]' : 'bg-white text-gray-500 border-gray-200 hover:border-gray-400 hover:text-gray-700' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Berakhir
        </a>
    </div>

    {{-- Sub header: jumlah promosi + tombol tambah --}}
    <div class="bg-[#EEF2E6] rounded-2xl px-8 py-5 mb-8 flex items-center justify-between">
        {{-- 
            Fallback ke angka 3 jika $promos kosong,
            agar sub header tetap menampilkan angka saat data dummy ditampilkan.
        --}}
        <p class="text-2xl font-bold text-[#8A9E5B]">{{ $promos->count() ?: 3 }} Promosi</p>
        <a href="{{ route('promo-admin.create') }}"
            class="bg-[#4A5E2F] hover:bg-[#3a4c23] text-white font-semibold text-sm px-5 py-2.5 rounded-xl transition">
            + Tambah Promosi Produk
        </a>
    </div>

    @php
        /*
         * Data dummy: digunakan sebagai placeholder tampilan saat belum ada data real.
         * Dikelompokkan per tab (terjadwal, aktif, berakhir) agar sesuai konteks tab aktif.
         * Menggunakan foto dari Unsplash sebagai gambar contoh.
         */
        $dummies = [
            'terjadwal' => [
                ['name' => 'Nastar Box Premium', 'description' => 'Nastar lumer isi nanas pilihan, cocok untuk hampers.', 'price' => 85000, 'actual_price' => 120000, 'percentage' => 30, 'date' => '15 Mei 2026', 'image' => 'https://images.unsplash.com/photo-1558961363-fa8fdf82db35?w=400&q=80'],
                ['name' => 'Choco Chip Cookies', 'description' => 'Cookies renyah dengan taburan choco chip melimpah.', 'price' => 65000, 'actual_price' => 95000, 'percentage' => 32, 'date' => '20 Mei 2026', 'image' => 'https://images.unsplash.com/photo-1499636136210-6f4ee915583e?w=400&q=80'],
                ['name' => 'Putri Salju', 'description' => 'Kue kering lembut bertabur gula halus yang manis.', 'price' => 55000, 'actual_price' => 80000, 'percentage' => 31, 'date' => '25 Mei 2026', 'image' => 'https://images.unsplash.com/photo-1548365328-8c6db3220e4c?w=400&q=80'],
            ],
            'aktif' => [
                ['name' => 'Kastengel Keju', 'description' => 'Kastengel renyah dengan keju gouda pilihan berkualitas.', 'price' => 75000, 'actual_price' => 110000, 'percentage' => 32, 'date' => '11 Mei 2026', 'image' => 'https://images.unsplash.com/photo-1621939514649-280e2ee25f60?w=400&q=80'],
                ['name' => 'Pineapple Tart', 'description' => 'Tart nanas dengan selai nanas asli yang manis segar.', 'price' => 70000, 'actual_price' => 95000, 'percentage' => 26, 'date' => '18 Mei 2026', 'image' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=400&q=80'],
                ['name' => 'Almond Crispy', 'description' => 'Kue kering renyah dengan topping almond panggang.', 'price' => 60000, 'actual_price' => 90000, 'percentage' => 33, 'date' => '22 Mei 2026', 'image' => 'https://images.unsplash.com/photo-1486427944299-d1955d23e34d?w=400&q=80'],
            ],
            'berakhir' => [
                ['name' => 'Lidah Kucing', 'description' => 'Kue lidah kucing tipis renyah dengan rasa vanilla lembut.', 'price' => 45000, 'actual_price' => 65000, 'percentage' => 31, 'date' => '01 Apr 2026', 'image' => 'https://images.unsplash.com/photo-1551024506-0bccd828d307?w=400&q=80'],
                ['name' => 'Kue Semprit', 'description' => 'Semprit butter klasik dengan tekstur lembut di mulut.', 'price' => 50000, 'actual_price' => 75000, 'percentage' => 33, 'date' => '10 Apr 2026', 'image' => 'https://images.unsplash.com/photo-1464349095431-e9a21285b5f3?w=400&q=80'],
                ['name' => 'Stick Keju', 'description' => 'Stick keju gurih dan renyah, cocok untuk camilan.', 'price' => 40000, 'actual_price' => 60000, 'percentage' => 33, 'date' => '15 Apr 2026', 'image' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=400&q=80'],
            ],
        ];

        // Warna badge diskon di pojok foto, berbeda tiap tab
        $badgeColor = [
            'terjadwal' => 'bg-[#4AABCF]',
            'aktif'     => 'bg-[#8A9E5B]',
            'berakhir'  => 'bg-gray-400',
        ];

        // Label teks di atas chip tanggal, berbeda antara tab terjadwal dan lainnya
        $dateLabel = [
            'terjadwal' => 'Mulai tanggal:',
            'aktif'     => 'Berakhir tanggal:',
            'berakhir'  => 'Berakhir tanggal:',
        ];

        // Warna background chip tanggal, berbeda tiap tab
        $dateBg = [
            'terjadwal' => 'bg-[#4AABCF] text-white',
            'aktif'     => 'bg-[#8A9E5B] text-white',
            'berakhir'  => 'bg-gray-200 text-gray-600',
        ];

        /*
         * Logika tampilan data:
         * Jika ada data real dari database ($promos tidak kosong) → petakan ke format array seragam,
         * tandai 'real' => true agar tombol aksi (edit/hapus) aktif.
         * Jika tidak ada data → gunakan data dummy sesuai tab aktif,
         * tandai 'real' => false agar tombol aksi dinonaktifkan (hanya dekorasi).
         */
        if ($promos->isNotEmpty()) {
            $displayPromos = $promos->map(function($p) use ($tab) {
                return [
                    'name'         => $p->name,
                    'description'  => $p->description,
                    'price'        => $p->price,
                    'actual_price' => $p->actual_price,
                    'percentage'   => $p->percentage,
                    // Tab terjadwal: tampilkan tanggal mulai; tab lain: tanggal berakhir
                    // translatedFormat() menghasilkan nama bulan dalam Bahasa Indonesia
                    'date'         => \Carbon\Carbon::parse($tab === 'terjadwal' ? ($p->date_start ?? $p->date_until) : $p->date_until)->translatedFormat('d F Y'),
                    'image'        => $p->image ?? null,
                    'id'           => $p->id,
                    'real'         => true, // Data asli dari database
                ];
            })->toArray();
        } else {
            // Tambahkan 'id' => null dan 'real' => false ke setiap item dummy
            $displayPromos = array_map(function($d) {
                return array_merge($d, ['id' => null, 'real' => false]);
            }, $dummies[$tab] ?? []);
        }
    @endphp

    {{-- Empty state: tampil jika $displayPromos kosong --}}
    @if(empty($displayPromos))
        <div class="flex flex-col items-center justify-center py-24 text-gray-400">
            <p class="text-sm">Tidak ada promosi {{ $tab }}.</p>
        </div>
    @else

        {{-- Grid kartu promosi: responsive 2 / 3 / 4 kolom --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5">
            @foreach($displayPromos as $item)
                <div class="bg-white rounded-3xl shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden group"
                     x-data="{ showDetail: false }">

                    {{-- SECTION FOTO + BADGE DISKON --}}
                    <div class="mx-3 mt-3 h-44 rounded-2xl overflow-hidden relative">

                        {{-- Tampilkan foto jika ada, placeholder coklat jika tidak --}}
                        @if(!empty($item['image']))
                            <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        @else
                            {{-- Placeholder dengan background coklat khas brand --}}
                            <div class="w-full h-full bg-[#C4A882] flex items-center justify-center">
                                <svg class="w-14 h-14 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif

                        {{-- 
                            Badge persentase diskon: posisi absolute pojok kiri atas foto.
                            Warna badge menyesuaikan tab aktif via $badgeColor.
                        --}}
                        <div class="absolute top-2 left-2 {{ $badgeColor[$tab] }} text-white text-xs font-bold px-2.5 py-1.5 rounded-xl flex items-center gap-1 shadow">
                            <svg class="w-3 h-3 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            {{ $item['percentage'] }}%
                        </div>
                    </div>

                    {{-- SECTION INFO PRODUK --}}
                    <div class="px-4 pt-3 pb-4">
                        {{-- Nama produk --}}
                        <h3 class="font-bold text-gray-800 text-base leading-tight mb-1">{{ $item['name'] }}</h3>
                        {{-- Deskripsi dibatasi 2 baris --}}
                        <p class="text-gray-400 text-xs leading-relaxed line-clamp-2 mb-2">{{ $item['description'] }}</p>

                        {{-- Harga promo (hijau) dan harga asli dicoret (merah) --}}
                        <div class="flex items-center gap-2 mb-3">
                            <p class="text-[#8A9E5B] font-bold text-sm">Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                            <p class="text-red-400 text-xs line-through">Rp {{ number_format($item['actual_price'], 0, ',', '.') }}</p>
                        </div>

                        {{-- Chip tanggal --}}
                        <p class="text-gray-400 text-xs mb-1">{{ $dateLabel[$tab] }}</p>
                        <div class="{{ $dateBg[$tab] }} text-xs font-semibold px-3 py-2 rounded-xl mb-3 text-center">
                            {{ $item['date'] }}
                        </div>

                        {{-- Detail: selalu bisa diklik (real maupun dummy) --}}
                        <div class="flex items-center justify-between gap-2 mb-0">
                            <button type="button" @click="showDetail = true"
                                class="flex items-center gap-1.5 text-gray-500 hover:text-[#4A5E2F] transition text-xs font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Detail
                            </button>

                            @if($item['real'])
                                {{-- Hapus + Edit kanan --}}
                                <div class="flex items-center gap-3">
                                    <form action="{{ route('promo-admin.destroy', $item['id']) }}" method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus promosi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-500 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                    <a href="{{ route('promo-admin.edit', $item['id']) }}" class="text-[#B8935A] hover:text-[#8A6A3A] transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                </div>
                            @else
                                <div class="flex items-center gap-3 opacity-30">
                                    <button type="button" disabled class="text-red-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                    <button type="button" disabled class="text-[#B8935A]">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                </div>
                            @endif
                        </div>

                        @if($item['real'] && $tab === 'berakhir')
                            <a href="{{ route('promo-admin.edit', $item['id']) }}?reuse=1"
                                class="mt-2 w-full flex items-center justify-center gap-1.5 bg-[#EEF2E6] hover:bg-[#dde8cc] text-[#4A5E2F] text-xs font-semibold px-3 py-2 rounded-xl transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                Gunakan Kembali
                            </a>
                        @endif
                    </div>

                    {{-- ===== MODAL DETAIL PROMOSI ===== --}}
                    <div x-show="showDetail" x-transition
                        class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4"
                        @click.self="showDetail = false"
                        style="display:none">
                        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl overflow-hidden" @click.stop>

                            {{-- Header --}}
                            <div class="px-8 pt-7 pb-5">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <p class="text-xs font-bold tracking-widest uppercase mb-1
                                            {{ $tab === 'terjadwal' ? 'text-[#4AABCF]' : ($tab === 'aktif' ? 'text-[#8A9E5B]' : 'text-gray-400') }}">
                                            Detail Promosi · {{ ucfirst($tab) }}
                                        </p>
                                        <h3 class="text-2xl font-bold text-gray-800">{{ $item['name'] }}</h3>
                                    </div>
                                    <button @click="showDetail = false" class="text-gray-400 hover:text-gray-600 transition mt-1">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            {{-- Body --}}
                            <div class="flex gap-6 px-8 pb-8">
                                {{-- Foto --}}
                                <div class="flex-shrink-0 w-56 h-44 rounded-2xl overflow-hidden bg-[#C4A882]">
                                    @if(!empty($item['image']))
                                        <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <svg class="w-12 h-12 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                {{-- Info --}}
                                <div class="flex-1 flex flex-col justify-between">
                                    <div class="space-y-3">
                                        {{-- Harga --}}
                                        <div>
                                            <p class="text-xs text-gray-400 mb-0.5">Harga Promo</p>
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <p class="text-[#8A9E5B] font-bold text-2xl">Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                                                <p class="text-red-400 text-sm line-through">Rp {{ number_format($item['actual_price'], 0, ',', '.') }}</p>
                                                <span class="{{ $badgeColor[$tab] }} text-white text-xs font-bold px-2 py-0.5 rounded-lg">-{{ $item['percentage'] }}%</span>
                                            </div>
                                        </div>
                                        {{-- Deskripsi --}}
                                        <div>
                                            <p class="text-xs text-gray-400 mb-0.5">Deskripsi</p>
                                            <p class="text-gray-600 text-sm leading-relaxed">{{ $item['description'] }}</p>
                                        </div>
                                        {{-- Tanggal --}}
                                        <div>
                                            <p class="text-xs text-gray-400 mb-0.5">{{ $dateLabel[$tab] }}</p>
                                            <span class="{{ $dateBg[$tab] }} text-xs font-semibold px-3 py-1.5 rounded-xl inline-block">{{ $item['date'] }}</span>
                                        </div>
                                        {{-- Status --}}
                                        <div>
                                            <p class="text-xs text-gray-400 mb-0.5">Status</p>
                                            <span class="text-xs font-semibold px-3 py-1.5 rounded-full inline-block
                                                {{ $tab === 'terjadwal' ? 'bg-[#E0F4FC] text-[#4AABCF]' : ($tab === 'aktif' ? 'bg-[#EEF2E6] text-[#4A5E2F]' : 'bg-gray-100 text-gray-500') }}">
                                                {{ ucfirst($tab) }}
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Tombol bawah --}}
                                    <div class="flex items-center justify-end gap-3 mt-5">
                                        @if($item['real'] && $tab === 'berakhir')
                                            <a href="{{ route('promo-admin.edit', $item['id']) }}?reuse=1"
                                                class="bg-[#EEF2E6] hover:bg-[#dde8cc] text-[#4A5E2F] font-semibold text-sm px-5 py-2.5 rounded-xl transition flex items-center gap-1.5">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                                </svg>
                                                Gunakan Kembali
                                            </a>
                                        @endif
                                        <button type="button" @click="showDetail = false"
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

@endsection