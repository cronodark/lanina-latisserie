@extends('layouts.admin')
@section('title', 'Edit Pesanan')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <h1 class="text-2xl font-bold text-gray-800 mb-6">Manajemen Pesanan</h1>

    <div class="bg-[#BB9457] rounded-2xl px-8 py-6 mb-8 flex items-center gap-5">
        <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-white">Edit Pesanan</h2>
    </div>

    <div class="bg-white rounded-2xl shadow-sm p-8">

        <h3 class="text-lg font-bold text-gray-800 mb-6">Edit Data Pesanan</h3>
        <hr class="border-gray-200 mb-6">

        {{-- ✅ HANYA SATU FORM — form duplikat dihapus --}}
        <form action="{{ route('pesanan.update', $pesanan->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- ======= Informasi Pelanggan ======= --}}
            <p class="text-base font-bold text-gray-800 mb-4">Informasi Pelanggan</p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
                <div>
                    <label class="block text-sm text-gray-600 mb-1.5">Nama Pelanggan</label>
                    <input type="text" placeholder="Nama pelanggan" readonly
                        value="{{ old('nama_pelanggan', $pesanan->nama_pelanggan) }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 bg-gray-50 outline-none cursor-not-allowed placeholder:text-gray-300">
                </div>

                <div>
                    <label class="block text-sm text-gray-600 mb-1.5">Nomor Telephone</label>
                    <input type="text" placeholder="Nomor telephone" readonly
                        value="{{ old('nomor_telepon', $pesanan->nomor_telepon ?? '') }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 bg-gray-50 outline-none cursor-not-allowed placeholder:text-gray-300">
                </div>

                <div>
                    <label class="block text-sm text-gray-600 mb-1.5">Email</label>
                    <input type="email" placeholder="Email" readonly
                        value="{{ old('email', $pesanan->email ?? '') }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 bg-gray-50 outline-none cursor-not-allowed placeholder:text-gray-300">
                </div>
            </div>

            <hr class="border-gray-200 mb-6">

            {{-- ======= Informasi Pesanan ======= --}}
            <p class="text-base font-bold text-gray-800 mb-4">Informasi Pesanan</p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-4">
                <div>
                    <label class="block text-sm text-gray-600 mb-1.5">ID Pesanan</label>
                    <input type="text" placeholder="ID Pesanan" readonly
                        value="{{ old('id_pesanan', $pesanan->id_pesanan) }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 bg-gray-50 outline-none cursor-not-allowed placeholder:text-gray-300">
                </div>

                <div>
                    <label class="block text-sm text-gray-600 mb-1.5">Tanggal Pembelian</label>
                    <input type="date" readonly
                        value="{{ old('tanggal_pembelian', $pesanan->tanggal_pembelian) }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 bg-gray-50 outline-none cursor-not-allowed">
                </div>

                <div>
                    <label class="block text-sm text-gray-600 mb-1.5">Tanggal Pengantaran</label>
                    <input type="date" name="tanggal_pengantaran"
                        value="{{ old('tanggal_pengantaran', $pesanan->tanggal_pengantaran) }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 outline-none focus:border-[#BB9457] focus:ring-1 focus:ring-[#BB9457] transition">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                <div>
                    <label class="block text-sm text-gray-600 mb-1.5">Total Harga</label>
                    <input type="number" placeholder="Total harga" readonly
                        value="{{ old('total_harga', $pesanan->total_harga) }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 bg-gray-50 outline-none cursor-not-allowed placeholder:text-gray-300">
                </div>

                <div>
                    <label class="block text-sm text-gray-600 mb-1.5">Status Pesanan</label>
                    <select name="status"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 outline-none focus:border-[#BB9457] focus:ring-1 focus:ring-[#BB9457] transition bg-white">
                        @foreach (['Belum', 'Dikerjakan', 'Dikirim', 'Selesai'] as $s)
                            <option value="{{ $s }}" {{ old('status', $pesanan->status) == $s ? 'selected' : '' }}>
                                {{ $s }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <hr class="border-gray-200 mb-6">

            {{-- ======= Detail Produk ======= --}}
            <p class="text-base font-bold text-gray-800 mb-4">Detail Produk</p>

            <div class="rounded-xl overflow-hidden border border-gray-100 mb-6">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100 text-gray-600">
                        <tr>
                            <th class="py-3 px-4 text-left font-medium">Nama Produk</th>
                            <th class="py-3 px-4 text-left font-medium">Jumlah</th>
                            <th class="py-3 px-4 text-left font-medium">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @php $produkList = $pesanan->produk ?? []; @endphp

                        @forelse ($produkList as $index => $produk)
                        <tr>
                            <td class="px-4 py-3">
                                <input type="text" readonly
                                    value="{{ $produk->nama }}"
                                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm bg-gray-50 outline-none cursor-not-allowed">
                            </td>
                            <td class="px-4 py-3">
                                <input type="number" readonly
                                    value="{{ $produk->jumlah }}"
                                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm bg-gray-50 outline-none cursor-not-allowed">
                            </td>
                            <td class="px-4 py-3">
                                <input type="number" readonly
                                    value="{{ $produk->total }}"
                                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm bg-gray-50 outline-none cursor-not-allowed">
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-6 text-gray-400 text-sm">
                                Belum ada produk.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <hr class="border-gray-200 mb-6">

            {{-- ======= Informasi Pembayaran ======= --}}
            <p class="text-base font-bold text-gray-800 mb-4">Informasi Pembayaran</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                <div>
                    <label class="block text-sm text-gray-600 mb-1.5">Metode Pembayaran</label>
                    <input type="text" name="metode_pembayaran" placeholder="Metode pembayaran"
                        value="{{ old('metode_pembayaran', $pesanan->metode_pembayaran ?? '') }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 outline-none focus:border-[#BB9457] focus:ring-1 focus:ring-[#BB9457] transition placeholder:text-gray-300">
                </div>

                <div>
                    <label class="block text-sm text-gray-600 mb-1.5">Status Pembayaran</label>
                    <input type="text" placeholder="Status Pembayaran" readonly
                        value="{{ old('status_pembayaran', $pesanan->status_pembayaran ?? '') }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 bg-gray-50 outline-none cursor-not-allowed placeholder:text-gray-300">
                </div>
            </div>

            <hr class="border-gray-200 mb-6">

            {{-- ======= Informasi Pengiriman ======= --}}
            <p class="text-base font-bold text-gray-800 mb-4">Informasi Pengiriman</p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-4">
                <div>
                    <label class="block text-sm text-gray-600 mb-1.5">Metode Pengiriman</label>
                    <select name="send_type" id="sendTypeSelect"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 outline-none focus:border-[#BB9457] focus:ring-1 focus:ring-[#BB9457] transition bg-white">
                        <option value="">-- Pilih Metode Pengiriman --</option>
                        <option value="pickUp" {{ old('send_type', $pesanan->send_type ?? '') == 'pickUp' ? 'selected' : '' }}>Ambil Sendiri</option>
                        <option value="kurirToko" {{ old('send_type', $pesanan->send_type ?? '') == 'kurirToko' ? 'selected' : '' }}>Kurir Toko</option>
                        <option value="kurirEkspedisi" {{ old('send_type', $pesanan->send_type ?? '') == 'kurirEkspedisi' ? 'selected' : '' }}>Kurir Ekspedisi</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm text-gray-600 mb-1.5">Alamat</label>
                    <input type="text" placeholder="Alamat pelanggan" readonly
                        value="{{ old('alamat', $pesanan->alamat ?? '') }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 bg-gray-50 outline-none cursor-not-allowed placeholder:text-gray-300">
                </div>

                <div>
                    <label class="block text-sm text-gray-600 mb-1.5">Catatan Alamat</label>
                    <input type="text" placeholder="Catatan alamat customer" readonly
                        value="{{ old('catatan_alamat', $pesanan->catatan_alamat ?? '') }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 bg-gray-50 outline-none cursor-not-allowed placeholder:text-gray-300">
                </div>
            </div>

            <div id="pickupDateContainer" class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6 hidden">
                <div>
                    <label class="block text-sm text-gray-600 mb-1.5">Tanggal Start Ambil</label>
                    <input type="date" name="start_periode"
                        value="{{ old('start_periode', $pesanan->start_periode ?? '') }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 outline-none focus:border-[#BB9457] focus:ring-1 focus:ring-[#BB9457] transition">
                </div>

                <div>
                    <label class="block text-sm text-gray-600 mb-1.5">Tanggal End Ambil</label>
                    <input type="date" name="end_periode"
                        value="{{ old('end_periode', $pesanan->end_periode ?? '') }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 outline-none focus:border-[#BB9457] focus:ring-1 focus:ring-[#BB9457] transition">
                </div>
            </div>

            <div id="shippingMetaContainer" class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6 hidden">
                <div>
                    <label class="block text-sm text-gray-600 mb-1.5">
                        Ekspedisi
                        <span id="ekspedisiRequiredMark" class="text-red-500 hidden">*</span>
                    </label>
                    <select name="choosen_expedition" id="expeditionSelect" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 outline-none focus:border-[#BB9457] focus:ring-1 focus:ring-[#BB9457] transition bg-white">
                        <option value="">-- Pilih Ekspedisi --</option>
                        <option value="JNE" {{ old('choosen_expedition', $pesanan->choosen_expedition ?? '') == 'JNE' ? 'selected' : '' }}>JNE</option>
                        <option value="J&T" {{ old('choosen_expedition', $pesanan->choosen_expedition ?? '') == 'J&T' ? 'selected' : '' }}>J&T</option>
                        <option value="SiCepat" {{ old('choosen_expedition', $pesanan->choosen_expedition ?? '') == 'SiCepat' ? 'selected' : '' }}>SiCepat</option>
                        <option value="Pos Indonesia" {{ old('choosen_expedition', $pesanan->choosen_expedition ?? '') == 'Pos Indonesia' ? 'selected' : '' }}>Pos Indonesia</option>
                    </select>
                    @error('choosen_expedition')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm text-gray-600 mb-1.5">
                        Nomor Resi
                        <span id="nomorResiRequiredMark" class="text-red-500 hidden">*</span>
                    </label>
                    <input type="text" name="nomor_resi" id="nomorResiInput" placeholder="Masukan nomor resi"
                        maxlength="50"
                        value="{{ old('nomor_resi', $pesanan->nomor_resi ?? '') }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 outline-none focus:border-[#BB9457] focus:ring-1 focus:ring-[#BB9457] transition placeholder:text-gray-300">
                    <p id="nomorResiHint" class="text-xs text-gray-400 mt-1 hidden">Wajib diisi untuk kurir ekspedisi.</p>
                    @error('nomor_resi')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit"
                    class="bg-[#6B8F4E] hover:bg-[#5a7a3f] text-white font-semibold px-10 py-3 rounded-xl transition text-sm">
                    Edit Pesanan
                </button>
            </div>

        </form>
    </div>

    <script>
        function toggleShippingFields() {
            const sendType = document.getElementById('sendTypeSelect')?.value || '';
            const pickupDateContainer = document.getElementById('pickupDateContainer');
            const shippingMetaContainer = document.getElementById('shippingMetaContainer');
            const nomorResi = document.getElementById('nomorResiInput');
            const ekspedisi = document.getElementById('expeditionSelect');
            const nomorResiMark = document.getElementById('nomorResiRequiredMark');
            const nomorResiHint = document.getElementById('nomorResiHint');
            const ekspedisiMark = document.getElementById('ekspedisiRequiredMark');

            if (!pickupDateContainer || !shippingMetaContainer) return;

            const isEkspedisi = sendType === 'kurirEkspedisi';

            if (sendType === 'pickUp') {
                pickupDateContainer.classList.remove('hidden');
                shippingMetaContainer.classList.add('hidden');
            } else if (isEkspedisi) {
                pickupDateContainer.classList.add('hidden');
                shippingMetaContainer.classList.remove('hidden');
            } else {
                pickupDateContainer.classList.add('hidden');
                shippingMetaContainer.classList.add('hidden');
            }

            // Toggle required: hanya wajib jika kurir ekspedisi
            if (nomorResi) nomorResi.required = isEkspedisi;
            if (ekspedisi) ekspedisi.required = isEkspedisi;
            nomorResiMark?.classList.toggle('hidden', !isEkspedisi);
            ekspedisiMark?.classList.toggle('hidden', !isEkspedisi);
            nomorResiHint?.classList.toggle('hidden', !isEkspedisi);
        }

        document.addEventListener('DOMContentLoaded', function () {
            const sendTypeSelect = document.getElementById('sendTypeSelect');
            if (sendTypeSelect) {
                sendTypeSelect.addEventListener('change', toggleShippingFields);
                toggleShippingFields();
            }

            // Safety net: cegat submit kalau kurir ekspedisi tapi ekspedisi/resi belum diisi.
            const pesananForm = document.querySelector('form[action*="pesanan"]');
            if (pesananForm) {
                pesananForm.addEventListener('submit', function (e) {
                    const sendType = document.getElementById('sendTypeSelect')?.value;
                    const nomorResi = document.getElementById('nomorResiInput')?.value.trim();
                    const ekspedisi = document.getElementById('expeditionSelect')?.value;
                    if (sendType === 'kurirEkspedisi') {
                        if (!ekspedisi) {
                            e.preventDefault();
                            alert('Silakan pilih ekspedisi terlebih dahulu.');
                            return;
                        }
                        if (!nomorResi) {
                            e.preventDefault();
                            alert('Nomor resi wajib diisi untuk kurir ekspedisi.');
                            return;
                        }
                    }
                });
            }
        });
    </script>

@endsection
