@extends('layouts.admin')

@section('title', 'Pesanan | lanina')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<meta name="csrf-token" content="{{ csrf_token() }}">

<h1 class="text-2xl font-bold text-gray-800 mb-6">Manajemen Pesanan</h1>

<div class="bg-white rounded-2xl p-6 shadow-sm">

    <div class="flex items-center justify-between border-b pb-4 mb-4">
        <h2 class="text-lg font-semibold text-gray-800">Daftar Pesanan</h2>
        <select id="statusFilter" onchange="filterTable()"
            class="px-3 py-1.5 border border-gray-200 rounded-lg text-sm bg-gray-50 focus:ring-2 focus:ring-[#BB9457] outline-none">
            <option value="">All</option>
            <option value="Selesai">Selesai</option>
            <option value="Dikirim">Dikirim</option>
            <option value="Dikerjakan">Dikerjakan</option>
            <option value="Belum">Belum</option>
        </select>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-gray-500 tracking-wider">
                    <th class="py-3 px-4 text-left">Id Pesanan</th>
                    <th class="py-3 px-4 text-left">Nama Pelanggan</th>
                    <th class="py-3 px-4 text-left">Nama Produk</th>
                    <th class="py-3 px-4 text-left">Tanggal Pembelian</th>
                    <th class="py-3 px-4 text-left">Tanggal Pengantaran</th>
                    <th class="py-3 px-4 text-left">Total Harga</th>
                    <th class="py-3 px-4 text-left">Status</th>
                    <th class="py-3 px-4 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($pesanan as $item)
                {{-- ✅ data-* lengkap di tr untuk dibaca JS --}}
                <tr
                    data-id="{{ $item->id }}"
                    data-status="{{ $item->status }}"
                    data-id-pesanan="{{ $item->id_pesanan }}"
                    data-nama-pelanggan="{{ $item->nama_pelanggan }}"
                    data-nama-produk="{{ $item->nama_produk }}"
                    data-tanggal-pembelian="{{ \Carbon\Carbon::parse($item->tanggal_pembelian)->format('d/m/Y') }}"
                    data-tanggal-pengantaran="{{ \Carbon\Carbon::parse($item->tanggal_pengantaran)->format('d/m/Y') }}"
                    data-total-harga="Rp {{ number_format($item->total_harga, 0, ',', '.') }}"
                    data-nomor-telepon="{{ $item->nomor_telepon ?? '-' }}"
                    data-email="{{ $item->email ?? '-' }}"
                    data-metode-pengiriman="{{ $item->metode_pengiriman ?? '-' }}"
                    data-alamat="{{ $item->alamat ?? '-' }}"
                    data-catatan-alamat="{{ $item->catatan_alamat ?? '-' }}"
                    data-metode-pembayaran="{{ $item->metode_pembayaran ?? '-' }}"
                    data-status-pembayaran="{{ $item->status_pembayaran ?? '-' }}"
                    class="hover:bg-gray-50 transition">

                    <td class="px-4 py-3">{{ $item->id_pesanan }}</td>
                    <td class="px-4 py-3">{{ $item->nama_pelanggan }}</td>
                    <td class="px-4 py-3">{{ $item->nama_produk }}</td>
                    <td class="px-4 py-3">{{ \Carbon\Carbon::parse($item->tanggal_pembelian)->format('d/m/y') }}</td>
                    <td class="px-4 py-3">{{ \Carbon\Carbon::parse($item->tanggal_pengantaran)->format('d/m/y') }}</td>
                    <td class="px-4 py-3">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>

                    <td class="px-4 py-3">
                        <button onclick="openStatusModal(this, {{ $item->id }})"
                            data-id="{{ $item->id }}"
                            class="px-3 py-1 text-xs font-semibold rounded-full text-white
                            {{ $item->status == 'Selesai' ? 'bg-green-500' :
                               ($item->status == 'Dikirim' ? 'bg-blue-500' :
                               ($item->status == 'Dikerjakan' ? 'bg-yellow-500' : 'bg-red-500')) }}">
                            {{ $item->status }}
                        </button>
                    </td>

                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <form action="{{ route('pesanan.destroy', $item->id) }}" method="POST"
                                  onsubmit="return confirm('Yakin hapus?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:scale-110 transition">
                                    <i class="fas fa-trash-can"></i>
                                </button>
                            </form>

                            <a href="{{ route('pesanan.edit', $item->id) }}"
                                class="text-yellow-500 hover:scale-110 transition">
                                <i class="fas fa-pen-to-square"></i>
                            </a>

                            <button onclick="openViewModal({{ $item->id }})"
                                class="text-cyan-500 hover:scale-110 transition">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-10 text-gray-400">
                        Belum ada data pesanan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ✅ MODAL STATUS — berdiri sendiri di luar card --}}
<div id="statusModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white w-[350px] rounded-2xl p-6 shadow-lg">
        <h3 class="text-center font-semibold text-gray-700 mb-4">Update Status Pesanan</h3>

        <input type="hidden" id="modalPesananId">

        <div class="space-y-4">
            <div class="flex justify-between items-center border-b pb-2">
                <span class="px-3 py-1 bg-green-500 text-white rounded-full text-sm">Selesai</span>
                <input type="radio" name="status" value="Selesai">
            </div>
            <div class="flex justify-between items-center border-b pb-2">
                <span class="px-3 py-1 bg-blue-500 text-white rounded-full text-sm">Dikirim</span>
                <input type="radio" name="status" value="Dikirim">
            </div>
            <div class="flex justify-between items-center border-b pb-2">
                <span class="px-3 py-1 bg-yellow-500 text-white rounded-full text-sm">Dikerjakan</span>
                <input type="radio" name="status" value="Dikerjakan">
            </div>
            <div class="flex justify-between items-center">
                <span class="px-3 py-1 bg-red-500 text-white rounded-full text-sm">Belum</span>
                <input type="radio" name="status" value="Belum">
            </div>
        </div>

        <button onclick="applyStatus()"
            class="mt-6 w-full bg-green-500 text-white py-2 rounded-full font-semibold">
            Done
        </button>
    </div>
</div>

{{-- ✅ MODAL VIEW — berdiri sendiri, sejajar dengan statusModal --}}
<div id="viewModal" class="fixed inset-0 bg-black/50 items-center justify-center z-50" style="display:none">
    <div class="bg-white w-[700px] max-w-[95vw] max-h-[90vh] overflow-y-auto rounded-2xl p-8 shadow-lg relative">

        <button onclick="closeViewModal()"
            class="absolute top-4 right-4 w-8 h-8 bg-gray-100 hover:bg-gray-200 rounded-full flex items-center justify-center transition">
            <span class="text-gray-600 font-bold text-sm">X</span>
        </button>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

            {{-- KIRI --}}
            <div class="space-y-8">

                <div>
                    <h3 class="text-lg font-bold text-[#432818] mb-4">Informasi Pesanan</h3>
                    <div class="space-y-2 text-sm text-gray-700">
                        <div class="flex gap-2">
                            <span class="w-44 text-gray-500">ID Pesanan</span>
                            <span>: <span id="view_id_pesanan">-</span></span>
                        </div>
                        <div class="flex gap-2">
                            <span class="w-44 text-gray-500">Tanggal Pembelian</span>
                            <span>: <span id="view_tanggal_pembelian">-</span></span>
                        </div>
                        <div class="flex gap-2">
                            <span class="w-44 text-gray-500">Tanggal Pengantaran</span>
                            <span>: <span id="view_tanggal_pengantaran">-</span></span>
                        </div>
                        <div class="flex gap-2">
                            <span class="w-44 text-gray-500">Status Pesanan</span>
                            <span>: <span id="view_status">-</span></span>
                        </div>
                        <div class="flex gap-2">
                            <span class="w-44 text-gray-500">Total Harga</span>
                            <span>: <span id="view_total_harga">-</span></span>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-bold text-[#432818] mb-4">Informasi Pelanggan</h3>
                    <div class="space-y-2 text-sm text-gray-700">
                        <div class="flex gap-2">
                            <span class="w-44 text-gray-500">Nama Pelanggan</span>
                            <span>: <span id="view_nama_pelanggan">-</span></span>
                        </div>
                        <div class="flex gap-2">
                            <span class="w-44 text-gray-500">Nomor HP</span>
                            <span>: <span id="view_nomor_telepon">-</span></span>
                        </div>
                        <div class="flex gap-2">
                            <span class="w-44 text-gray-500">Email</span>
                            <span>: <span id="view_email">-</span></span>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-bold text-[#432818] mb-4">Informasi Pengiriman</h3>
                    <div class="space-y-2 text-sm text-gray-700">
                        <div class="flex gap-2">
                            <span class="w-44 text-gray-500">Metode</span>
                            <span>: <span id="view_metode_pengiriman">-</span></span>
                        </div>
                        <div class="flex gap-2">
                            <span class="w-44 text-gray-500">Alamat</span>
                            <span>: <span id="view_alamat">-</span></span>
                        </div>
                        <div class="flex gap-2">
                            <span class="w-44 text-gray-500">Catatan Alamat</span>
                            <span>: <span id="view_catatan_alamat">-</span></span>
                        </div>
                    </div>
                </div>

            </div>

            {{-- KANAN --}}
            <div class="space-y-8">

                <div>
                    <h3 class="text-lg font-bold text-[#432818] mb-4">Detail Produk</h3>
                    <div id="view_produk" class="space-y-4 text-sm text-gray-700">
                        {{-- diisi JS --}}
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-bold text-[#432818] mb-4">Informasi Pembayaran</h3>
                    <div class="space-y-2 text-sm text-gray-700">
                        <div class="flex gap-2">
                            <span class="w-44 text-gray-500">Metode Pembayaran</span>
                            <span>: <span id="view_metode_pembayaran">-</span></span>
                        </div>
                        <div class="flex gap-2">
                            <span class="w-44 text-gray-500">Status Pembayaran</span>
                            <span>: <span id="view_status_pembayaran">-</span></span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
let currentButton = null;

// FILTER
function filterTable() {
    const filter = document.getElementById('statusFilter').value.toLowerCase();
    document.querySelectorAll('tbody tr[data-status]').forEach(row => {
        const status = row.dataset.status.toLowerCase();
        row.style.display = (!filter || status === filter) ? '' : 'none';
    });
}

// OPEN MODAL STATUS
function openStatusModal(btn, id) {
    currentButton = btn;
    document.getElementById('modalPesananId').value = id;

    const currentStatus = btn.innerText.trim();
    document.querySelectorAll('input[name="status"]').forEach(r => {
        r.checked = r.value === currentStatus;
    });

    document.getElementById('statusModal').style.display = 'flex';
}

// APPLY STATUS
function applyStatus() {
    const selected = document.querySelector('input[name="status"]:checked');
    if (!selected) return;

    const status = selected.value;
    const id = document.getElementById('modalPesananId').value;

    fetch(`/pesanan/${id}/status`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ status })
    })
    .then(res => res.json())
    .then(data => {
        if (!data.success) return alert('Gagal update status');

        const colorMap = {
            Selesai: 'bg-green-500',
            Dikirim: 'bg-blue-500',
            Dikerjakan: 'bg-yellow-500',
            Belum: 'bg-red-500'
        };

        currentButton.innerText = status;
        currentButton.className = `px-3 py-1 text-xs font-semibold rounded-full text-white ${colorMap[status]}`;
        currentButton.closest('tr').dataset.status = status;

        closeStatusModal();
    })
    .catch(() => alert('Terjadi kesalahan'));
}

// CLOSE STATUS MODAL
function closeStatusModal() {
    document.getElementById('statusModal').style.display = 'none';
    document.querySelectorAll('input[name="status"]').forEach(r => r.checked = false);
}

// OPEN VIEW MODAL
function openViewModal(id) {
    const row = document.querySelector(`tr[data-id="${id}"]`);
    if (!row) return;

    document.getElementById('view_id_pesanan').textContent         = row.dataset.idPesanan || '-';
    document.getElementById('view_tanggal_pembelian').textContent  = row.dataset.tanggalPembelian || '-';
    document.getElementById('view_tanggal_pengantaran').textContent = row.dataset.tanggalPengantaran || '-';
    document.getElementById('view_status').textContent             = row.dataset.status || '-';
    document.getElementById('view_total_harga').textContent        = row.dataset.totalHarga || '-';
    document.getElementById('view_nama_pelanggan').textContent     = row.dataset.namaPelanggan || '-';
    document.getElementById('view_nomor_telepon').textContent      = row.dataset.nomorTelepon || '-';
    document.getElementById('view_email').textContent              = row.dataset.email || '-';
    document.getElementById('view_metode_pengiriman').textContent  = row.dataset.metodePengiriman || '-';
    document.getElementById('view_alamat').textContent             = row.dataset.alamat || '-';
    document.getElementById('view_catatan_alamat').textContent     = row.dataset.catatanAlamat || '-';
    document.getElementById('view_metode_pembayaran').textContent  = row.dataset.metodePembayaran || '-';
    document.getElementById('view_status_pembayaran').textContent  = row.dataset.statusPembayaran || '-';

    // Detail produk — nanti diganti fetch dari backend
    document.getElementById('view_produk').innerHTML = `
        <div>
            <p class="font-semibold text-gray-700 mb-1">Produk 1</p>
            <div class="space-y-1">
                <div class="flex gap-2">
                    <span class="w-24 text-gray-500">Nama Produk</span>
                    <span>: ${row.dataset.namaProduk || '-'}</span>
                </div>
            </div>
        </div>
    `;

    document.getElementById('viewModal').style.display = 'flex';
}

// CLOSE VIEW MODAL
function closeViewModal() {
    document.getElementById('viewModal').style.display = 'none';
}

// Klik luar modal status
document.getElementById('statusModal').addEventListener('click', function(e) {
    if (e.target === this) closeStatusModal();
});

// Klik luar modal view
document.getElementById('viewModal').addEventListener('click', function(e) {
    if (e.target === this) closeViewModal();
});
</script>

@endsection