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
                    <input type="text" name="nama_pelanggan" placeholder="Nama pelanggan"
                        value="{{ old('nama_pelanggan', $pesanan->nama_pelanggan) }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 outline-none focus:border-[#BB9457] focus:ring-1 focus:ring-[#BB9457] transition placeholder:text-gray-300">
                </div>

                <div>
                    <label class="block text-sm text-gray-600 mb-1.5">Nomor Telephone</label>
                    <input type="text" name="nomor_telepon" placeholder="Nomor telephone"
                        value="{{ old('nomor_telepon', $pesanan->nomor_telepon ?? '') }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 outline-none focus:border-[#BB9457] focus:ring-1 focus:ring-[#BB9457] transition placeholder:text-gray-300">
                </div>

                <div>
                    <label class="block text-sm text-gray-600 mb-1.5">Email</label>
                    <input type="email" name="email" placeholder="Email"
                        value="{{ old('email', $pesanan->email ?? '') }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 outline-none focus:border-[#BB9457] focus:ring-1 focus:ring-[#BB9457] transition placeholder:text-gray-300">
                </div>
            </div>

            <hr class="border-gray-200 mb-6">

            {{-- ======= Informasi Pesanan ======= --}}
            <p class="text-base font-bold text-gray-800 mb-4">Informasi Pesanan</p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-4">
                <div>
                    <label class="block text-sm text-gray-600 mb-1.5">ID Pesanan</label>
                    <input type="text" name="id_pesanan" placeholder="ID Pesanan"
                        value="{{ old('id_pesanan', $pesanan->id_pesanan) }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 outline-none focus:border-[#BB9457] focus:ring-1 focus:ring-[#BB9457] transition placeholder:text-gray-300">
                </div>

                <div>
                    <label class="block text-sm text-gray-600 mb-1.5">Tanggal Pembelian</label>
                    <input type="date" name="tanggal_pembelian"
                        value="{{ old('tanggal_pembelian', $pesanan->tanggal_pembelian) }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 outline-none focus:border-[#BB9457] focus:ring-1 focus:ring-[#BB9457] transition">
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
                    <input type="number" name="total_harga" placeholder="Total harga"
                        value="{{ old('total_harga', $pesanan->total_harga) }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 outline-none focus:border-[#BB9457] focus:ring-1 focus:ring-[#BB9457] transition placeholder:text-gray-300">
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
                                <input type="text" name="produk[{{ $index }}][nama]"
                                    value="{{ $produk->nama }}"
                                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none focus:border-[#BB9457] focus:ring-1 focus:ring-[#BB9457] transition">
                            </td>
                            <td class="px-4 py-3">
                                <input type="number" name="produk[{{ $index }}][jumlah]"
                                    value="{{ $produk->jumlah }}"
                                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none focus:border-[#BB9457] focus:ring-1 focus:ring-[#BB9457] transition">
                            </td>
                            <td class="px-4 py-3">
                                <input type="number" name="produk[{{ $index }}][total]"
                                    value="{{ $produk->total }}"
                                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none focus:border-[#BB9457] focus:ring-1 focus:ring-[#BB9457] transition">
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
                    <input type="text" name="status_pembayaran" placeholder="Status Pembayaran"
                        value="{{ old('status_pembayaran', $pesanan->status_pembayaran ?? '') }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 outline-none focus:border-[#BB9457] focus:ring-1 focus:ring-[#BB9457] transition placeholder:text-gray-300">
                </div>
            </div>

            <hr class="border-gray-200 mb-6">

            {{-- ======= Informasi Pengiriman ======= --}}
            <p class="text-base font-bold text-gray-800 mb-4">Informasi Pengiriman</p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-4">
                <div>
                    <label class="block text-sm text-gray-600 mb-1.5">Metode</label>
                    <input type="text" name="metode_pengiriman" placeholder="Metode pengantaran"
                        value="{{ old('metode_pengiriman', $pesanan->metode_pengiriman ?? '') }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 outline-none focus:border-[#BB9457] focus:ring-1 focus:ring-[#BB9457] transition placeholder:text-gray-300">
                </div>

                <div>
                    <label class="block text-sm text-gray-600 mb-1.5">Alamat</label>
                    <input type="text" name="alamat" placeholder="Alamat pelanggan"
                        value="{{ old('alamat', $pesanan->alamat ?? '') }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 outline-none focus:border-[#BB9457] focus:ring-1 focus:ring-[#BB9457] transition placeholder:text-gray-300">
                </div>

                <div>
                    <label class="block text-sm text-gray-600 mb-1.5">Catatan Alamat</label>
                    <input type="text" name="catatan_alamat" placeholder="Catatan alamat customer"
                        value="{{ old('catatan_alamat', $pesanan->catatan_alamat ?? '') }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 outline-none focus:border-[#BB9457] focus:ring-1 focus:ring-[#BB9457] transition placeholder:text-gray-300">
                </div>
            </div>

            <div class="w-full md:w-1/3 mb-8">
                <label class="block text-sm text-gray-600 mb-1.5">
                    Nomor Resi
                    <span class="text-gray-400 font-normal text-xs ml-1">(Opsional, jika ada)</span>
                </label>
                <input type="text" name="nomor_resi" placeholder="Masukan nomor resi"
                    value="{{ old('nomor_resi', $pesanan->nomor_resi ?? '') }}"
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 outline-none focus:border-[#BB9457] focus:ring-1 focus:ring-[#BB9457] transition placeholder:text-gray-300">
            </div>

            <div class="flex justify-end">
                <button type="submit"
                    class="bg-[#6B8F4E] hover:bg-[#5a7a3f] text-white font-semibold px-10 py-3 rounded-xl transition text-sm">
                    Edit Pesanan
                </button>
            </div>

        </form>
    </div>

@endsection