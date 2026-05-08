@extends('layouts.admin')

@section('title', 'Slot Preorder | lanina')

@section('content')

{{--
    $slots  : Collection<TanggalTersedia> dari SlotController@index  (real DB)
    $totalSlot, $slotAktif, $slotPenuh dihitung dari accessor model
--}}
@php
    // Fallback: jika controller belum pass $slots (misal direct view), pakai kosong
    $slots     = $slots     ?? collect();
    $totalSlot = $totalSlot ?? $slots->count();
    $slotAktif = $slotAktif ?? $slots->filter(fn($s) => $s->status === 'Aktif')->count();
    $slotPenuh = $slotPenuh ?? $slots->filter(fn($s) => $s->status === 'Penuh')->count();
@endphp

<div x-data="{
    editModal: false,
    editSlot: {},
    openEdit(slot) {
        this.editSlot = { ...slot };
        this.editModal = true;
    }
}">

{{-- Header --}}
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Slot Preorder</h1>
    <p class="text-sm text-gray-500 mt-1">Atur tanggal yang bisa dipilih customer untuk preorder</p>
</div>

{{-- Stats --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Total Slot</p>
        <h3 class="text-3xl font-bold mt-2 text-gray-800">{{ $totalSlot }}</h3>
        <p class="text-xs text-gray-400 mt-1">Slot terdaftar</p>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Slot Aktif</p>
        <h3 class="text-3xl font-bold mt-2 text-green-600">{{ $slotAktif }}</h3>
        <p class="text-xs text-green-500 mt-1 font-medium">Tersedia untuk booking</p>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Slot Penuh</p>
        <h3 class="text-3xl font-bold mt-2 text-red-500">{{ $slotPenuh }}</h3>
        <p class="text-xs text-red-400 mt-1 font-medium">Kuota habis</p>
    </div>
</div>

{{-- Form Tambah Slot --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
    <h2 class="text-base font-bold text-gray-800 mb-5">Tambah Slot Baru</h2>

    <form action="{{ route('jadwal-admin.slot.store') }}" method="POST"
        class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
        @csrf

        <div>
            <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">Tanggal</label>
            <input type="date" name="tanggal" required
                min="{{ now()->addDay()->format('Y-m-d') }}"
                class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#BB9457]/30 focus:border-[#BB9457] transition">
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">Kuota</label>
            <input type="number" name="kuota" min="1" placeholder="Contoh: 10" required
                class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#BB9457]/30 focus:border-[#BB9457] transition">
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">
                Keterangan <span class="text-gray-300 font-normal normal-case">(opsional)</span>
            </label>
            <input type="text" name="keterangan" placeholder="Hari spesial, dsb..."
                class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#BB9457]/30 focus:border-[#BB9457] transition">
        </div>

        <div>
            <button type="submit"
                class="w-full bg-[#BB9457] text-white rounded-xl py-3 text-sm font-semibold hover:bg-[#a8834c] active:scale-95 transition-all flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Slot
            </button>
        </div>
    </form>
</div>

{{-- Tabel Slot --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
    <div class="flex items-center justify-between mb-5">
        <h2 class="text-base font-bold text-gray-800">Daftar Slot Tersedia</h2>
        <span class="text-xs bg-gray-100 text-gray-500 px-3 py-1.5 rounded-lg font-medium">
            {{ $totalSlot }} Slot
        </span>
    </div>

    @if($slots->isEmpty())
        <div class="py-12 flex flex-col items-center justify-center text-gray-400">
            <svg class="w-10 h-10 mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <p class="text-sm">Belum ada slot tersedia.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm table-fixed">
                <thead>
                    <tr class="text-xs text-gray-400 uppercase tracking-wider border-b border-gray-100">
                        <th class="pb-3 text-left font-semibold w-36">Tanggal</th>
                        <th class="pb-3 text-left font-semibold w-20">Kuota</th>
                        <th class="pb-3 text-left font-semibold w-20">Terisi</th>
                        <th class="pb-3 text-left font-semibold w-20">Sisa</th>
                        <th class="pb-3 text-left font-semibold w-24">Status</th>
                        <th class="pb-3 text-left font-semibold w-52">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($slots as $slot)
                        @php
                            /*
                             * $slot adalah instance TanggalTersedia.
                             * Accessor: $slot->terisi, $slot->sisa_kuota, $slot->status
                             * Untuk Alpine openEdit() kita kirim array plain agar bisa di-JSON-encode.
                             */
                            $slotArr = [
                                'id'      => $slot->id,
                                'tanggal' => $slot->tanggal->translatedFormat('d F Y'),
                                'kuota'   => $slot->kuota,
                                'terisi'  => $slot->terisi,
                                'status'  => $slot->status,
                            ];
                        @endphp
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="py-4 font-medium text-gray-700">
                                {{ $slot->tanggal->translatedFormat('d F Y') }}
                                @if($slot->keterangan)
                                    <p class="text-[10px] text-gray-400 font-normal mt-0.5">{{ $slot->keterangan }}</p>
                                @endif
                            </td>
                            <td class="py-4 text-gray-600">{{ $slot->kuota }}</td>
                            <td class="py-4 text-gray-600">{{ $slot->terisi }}</td>
                            <td class="py-4">
                                <span class="font-semibold {{ $slot->sisa_kuota > 0 ? 'text-green-600' : 'text-red-500' }}">
                                    {{ $slot->sisa_kuota }}
                                </span>
                            </td>
                            <td class="py-4">
                                @if($slot->status === 'Aktif')
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-medium bg-green-50 text-green-600 border border-green-200">Aktif</span>
                                @elseif($slot->status === 'Penuh')
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-medium bg-red-50 text-red-500 border border-red-200">Penuh</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-medium bg-gray-100 text-gray-500 border border-gray-200">Nonaktif</span>
                                @endif
                            </td>
                            <td class="py-4">
                                <div class="flex items-center gap-2">
                                    {{-- Edit: buka modal, kirim array plain --}}
                                    <button @click="openEdit({{ json_encode($slotArr) }})"
                                        class="px-3 py-1.5 rounded-lg bg-[#BB9457]/10 text-[#BB9457] text-xs font-medium hover:bg-[#BB9457]/20 transition">
                                        Edit
                                    </button>

                                    {{-- Toggle aktif / nonaktif --}}
                                    <form action="{{ route('jadwal-admin.slot.toggle', $slot->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                            class="px-3 py-1.5 rounded-lg text-xs font-medium transition
                                                {{ $slot->is_aktif ? 'bg-red-50 text-red-500 hover:bg-red-100' : 'bg-green-50 text-green-600 hover:bg-green-100' }}">
                                            {{ $slot->is_aktif ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>

                                    {{-- Hapus --}}
                                    <form action="{{ route('jadwal-admin.slot.destroy', $slot->id) }}" method="POST"
                                        onsubmit="return confirm('Hapus slot ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="w-7 h-7 rounded-lg bg-gray-100 text-gray-400 flex items-center justify-center hover:bg-red-50 hover:text-red-500 transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

{{-- Edit Modal --}}
<div
    x-show="editModal"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 flex items-center justify-center p-4"
    style="display:none">

    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="editModal = false"></div>

    <div
        x-show="editModal"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95 translate-y-3"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm z-10 overflow-hidden">

        <div class="bg-gradient-to-r from-[#BB9457] to-[#d4a96a] px-6 py-5 flex items-center justify-between">
            <div>
                <p class="text-white/70 text-xs font-medium uppercase tracking-wider">Edit Slot</p>
                <h3 class="text-white text-lg font-bold mt-0.5" x-text="editSlot.tanggal"></h3>
                <p class="text-white/60 text-xs mt-1">
                    Terisi: <span x-text="editSlot.terisi"></span> / <span x-text="editSlot.kuota"></span>
                </p>
            </div>
            <button @click="editModal = false"
                class="w-8 h-8 rounded-xl bg-white/20 flex items-center justify-center hover:bg-white/30 transition text-white">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form :action="`{{ url('admin/jadwal/slot') }}/${editSlot.id}`" method="POST" class="px-6 py-5 space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">Kuota</label>
                <input type="number" name="kuota" min="1" x-model="editSlot.kuota" required
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#BB9457]/30 focus:border-[#BB9457] transition">
                {{-- Peringatan jika kuota baru lebih kecil dari terisi --}}
                <p class="text-xs text-red-400 mt-1.5"
                    x-show="Number(editSlot.kuota) < Number(editSlot.terisi)">
                    ⚠️ Kuota lebih kecil dari jumlah yang sudah terisi.
                </p>
            </div>
            <div class="flex gap-3 pt-1">
                <button type="button" @click="editModal = false"
                    class="flex-1 py-2.5 rounded-xl bg-gray-100 text-gray-600 text-sm font-medium hover:bg-gray-200 transition">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 py-2.5 rounded-xl bg-[#BB9457] text-white text-sm font-semibold hover:bg-[#a8834c] transition">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

</div>
@endsection