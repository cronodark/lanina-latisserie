@extends('layouts.admin')

@section('title', 'Pesanan | lanina')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <h1 class="text-2xl font-bold text-gray-800 mb-6">Manajemen Pesanan</h1>

    <div class="bg-white rounded-2xl p-6 shadow-sm">

        <div class="flex items-center justify-between border-b pb-4 mb-4">
            <h2 class="text-lg font-semibold text-gray-800">Daftar Pesanan</h2>
            <!-- Filter Status:
                - All: Semua pesanan
                - completed: Selesai
                - shipping: Dikirim
                - processing: Dikerjakan
                - unpaid: Belum (pembayaran belum diterima)
                - canceled: Dibatalkan (pesanan dibatalkan)
            -->
            <form id="filterForm" method="GET" class="flex items-center gap-2">
                <select id="statusFilter" name="status" onchange="document.getElementById('filterForm').submit()"
                    class="px-3 py-1.5 border border-gray-200 rounded-lg text-sm bg-gray-50 focus:ring-2 focus:ring-[#BB9457] outline-none">
                    <option value="">All</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                    <option value="shipping" {{ request('status') === 'shipping' ? 'selected' : '' }}>Dikirim</option>
                    <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Dikerjakan</option>
                    <option value="unpaid" {{ request('status') === 'unpaid' ? 'selected' : '' }}>Belum</option>
                    <option value="canceled" {{ request('status') === 'canceled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse">
                <thead>
                    <tr class="text-gray-500 tracking-wider bg-gray-50 border-b">
                        <th class="py-3 px-4 text-left w-20">Id Pesanan</th>
                        <th class="py-3 px-4 text-left w-32">Nama Pelanggan</th>
                        <th class="py-3 px-4 text-left w-32">Nama Produk</th>
                        <th class="py-3 px-4 text-left w-24">Tanggal Pembelian</th>
                        <th class="py-3 px-4 text-left w-24">Tanggal Pengantaran</th>
                        <th class="py-3 px-4 text-left w-20">Total Harga</th>
                        <th class="py-3 px-4 text-left w-20">Status</th>
                        <th class="py-3 px-4 text-center w-24">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse ($orders as $item)
                        @php
                            // Status color mapping untuk 5 status:
                            // - completed: Hijau (Selesai)
                            // - shipping: Biru (Dikirim)
                            // - processing: Kuning (Dikerjakan)
                            // - unpaid: Merah (Belum)
                            // - canceled: Abu-abu (Dibatalkan)
                            $statusColor = match ($item->status) {
                                'completed' => 'bg-green-500',
                                'shipping' => 'bg-blue-500',
                                'processing' => 'bg-yellow-500',
                                'canceled' => 'bg-gray-600',
                                default => 'bg-red-500',
                            };
                        @endphp
                        <tr data-id="{{ $item->id }}" data-status="{{ $item->status_filter }}"
                            data-id-pesanan="{{ $item->id_pesanan }}" data-nama-pelanggan="{{ $item->nama_pelanggan }}"
                            data-nama-produk="{{ $item->nama_produk }}"
                            data-detail-produk='@json($item->detail_produk)'
                            data-tanggal-pembelian="{{ $item->tanggal_pembelian ? \Carbon\Carbon::parse($item->tanggal_pembelian)->format('d/m/Y') : '-' }}"
                            data-tanggal-pengantaran="{{ $item->tanggal_pengantaran ?? '-' }}"
                            data-start-periode="{{ $item->start_periode ?? '' }}"
                            data-end-periode="{{ $item->end_periode ?? '' }}"
                            data-total-harga="Rp {{ number_format($item->total_harga, 0, ',', '.') }}"
                            data-nomor-telepon="{{ $item->nomor_telepon ?? '-' }}" data-email="{{ $item->email ?? '-' }}"
                            data-nomor-resi="{{ $item->nomor_resi ?? '' }}"
                            data-metode-pengiriman="{{ $item->metode_pengiriman ?? '-' }}"
                                data-send-type="{{ $item->send_type ?? '-' }}"
                                data-choosen-expedition="{{ $item->choosen_expedition ?? '' }}"
                            data-alamat="{{ $item->alamat ?? '-' }}"
                            data-catatan-alamat="{{ $item->catatan_alamat ?? '-' }}"
                            data-metode-pembayaran="{{ $item->metode_pembayaran ?? '-' }}"
                            data-status-pembayaran="{{ $item->status_pembayaran ?? '-' }}"
                            class="hover:bg-gray-50 transition">

                            <td class="px-4 py-3 w-20 text-xs font-semibold text-gray-800">{{ $item->id_pesanan }}</td>
                            <td class="px-4 py-3 w-32 truncate">{{ $item->nama_pelanggan }}</td>
                            <td class="px-4 py-3 w-32 truncate">{{ $item->nama_produk }}</td>
                            <td class="px-4 py-3 w-24 text-sm text-center">{{ $item->tanggal_pembelian ? \Carbon\Carbon::parse($item->tanggal_pembelian)->format('d/m/y') : '-' }}
                            </td>
                            <td class="px-4 py-3 w-24 text-sm text-center">{{ $item->tanggal_pengantaran ?? '-' }}
                            </td>
                            <td class="px-4 py-3 w-20 text-sm font-semibold text-right">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>

                            <td class="px-4 py-3 w-20">
                                <button onclick="openStatusModal(this, {{ $item->id }}, '{{ $item->status }}')"
                                    data-id="{{ $item->id }}" data-status-label="{{ $item->status_label }}"
                                    class="px-3 py-1 text-xs font-semibold rounded-full text-white {{ $statusColor }} hover:opacity-80 transition">
                                    {{ $item->status_label }}
                                </button>
                            </td>

                            <td class="px-4 py-3 w-24">
                                <div class="flex items-center justify-center gap-2">
                                    <form action="{{ route('pesanan.destroy', $item->id) }}" method="POST"
                                        onsubmit="return openDeleteConfirmModal(this, '{{ $item->id_pesanan }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 hover:scale-110 transition" title="Hapus">
                                            <i class="fas fa-trash-can text-sm"></i>
                                        </button>
                                    </form>

                                    <a href="{{ route('pesanan.edit', $item->id) }}"
                                        class="text-yellow-500 hover:text-yellow-700 hover:scale-110 transition" title="Edit">
                                        <i class="fas fa-pen-to-square text-sm"></i>
                                    </a>

                                    <button onclick="openViewModal({{ $item->id }})"
                                        class="text-cyan-500 hover:text-cyan-700 hover:scale-110 transition" title="Lihat">
                                        <i class="fas fa-eye text-sm"></i>
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

        @if ($orders instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="mt-5 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <p class="text-sm text-gray-500">
                    Menampilkan {{ $orders->firstItem() ?? 0 }}-{{ $orders->lastItem() ?? 0 }} dari
                    {{ $orders->total() }} pesanan
                </p>
                <div>
                    {{ $orders->appends(request()->query())->onEachSide(1)->links('pagination::admin') }}
                </div>
            </div>
        @endif
    </div>

    {{-- ✅ MODAL STATUS — berdiri sendiri di luar card --}}
    <!--
        Modal Update Status ke Shipping
        - Hanya tampilkan opsi "Dikirim" (processing → shipping)
        - Dropdown tipe pengiriman untuk memilih/ubah send_type
        - Input resi hanya tampil jika tipe = kurirEkspedisi
    -->
    <div id="statusModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-white w-[400px] rounded-2xl p-6 shadow-lg">
            <h3 class="text-center font-semibold text-gray-700 mb-4">Update Status ke Pengiriman</h3>

            <input type="hidden" id="modalPesananId">
            <input type="hidden" id="modalCurrentStatus">

            <div class="space-y-4">
                <!-- Status: Dikirim -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status Pesanan</label>
                    <div class="flex justify-between items-center border-b pb-2 px-3 py-2 bg-blue-50 rounded-lg">
                        <span class="px-3 py-1 bg-blue-500 text-white rounded-full text-sm">Dikirim</span>
                        <input type="radio" name="status" value="shipping" checked disabled>
                    </div>
                </div>

                <div class="space-y-2 bg-gray-50 p-3 rounded-lg">
                    <div class="flex gap-2 text-sm text-gray-700">
                        <span class="w-40 text-gray-500">Metode Kirim</span>
                        <span>: <span id="modalSendTypeLabel">-</span></span>
                    </div>
                    <div id="modalExpeditionDisplay" class="flex gap-2 text-sm text-gray-700 hidden">
                        <span class="w-40 text-gray-500">Ekspedisi</span>
                        <span>: <span id="modalExpeditionLabel">-</span></span>
                    </div>
                </div>

                <!-- Input Resi -->
                <div id="resiInputContainer" class="hidden space-y-2 bg-gray-50 p-3 rounded-lg">
                    <label class="block text-sm font-medium text-gray-700">
                        Nomor Resi <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="nomorResi" placeholder="Masukkan nomor resi pengiriman"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#BB9457] outline-none">
                    <p class="text-xs text-gray-500">Masukkan nomor resi dari kurir ekspedisi jika sudah tersedia.</p>
                </div>
            </div>

            <button onclick="applyStatus()" class="mt-6 w-full bg-green-500 text-white py-2 rounded-full font-semibold">
                Done
            </button>
        </div>
    </div>

    {{-- ✅ MODAL VIEW — berdiri sendiri, sejajar dengan statusModal --}}
    <!--
        Modal View Detail Pesanan + Edit Status Cepat
        - Menampilkan semua informasi pesanan
        - Section edit status dengan dropdown tipe kirim
        - Input resi muncul otomatis jika tipe kirim = kurirEkspedisi
    -->
    <div id="viewModal" class="fixed inset-0 bg-black/50 items-center justify-center z-50" style="display:none">
        <div class="bg-white w-[900px] max-w-[95vw] max-h-[90vh] overflow-y-auto rounded-2xl p-8 shadow-lg relative">

            <button onclick="closeViewModal()"
                class="absolute top-4 right-4 w-8 h-8 bg-gray-100 hover:bg-gray-200 rounded-full flex items-center justify-center transition">
                <span class="text-gray-600 font-bold text-sm">X</span>
            </button>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                {{-- KIRI: INFORMASI PESANAN & PELANGGAN --}}
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
                            <div id="viewExpeditionDisplay" class="flex gap-2 hidden">
                                <span class="w-44 text-gray-500">Ekspedisi</span>
                                <span>: <span id="view_choosen_expedition">-</span></span>
                            </div>
                            <div id="viewResiDisplay" class="flex gap-2 hidden">
                                <span class="w-44 text-gray-500">Nomor Resi</span>
                                <span>: <span id="view_nomor_resi">-</span></span>
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

                {{-- KANAN: PRODUK, PEMBAYARAN, EDIT STATUS CEPAT --}}
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

                    <div id="editStatusSection" class="hidden bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-lg border-2 border-blue-200">
                        <h3 class="text-lg font-bold text-[#432818] mb-4">Edit Status Cepat</h3>

                        <input type="hidden" id="viewModalPesananId">

                        <div class="space-y-3">
                            <div class="bg-white p-3 rounded-lg space-y-2">
                                <div class="flex gap-2 text-sm text-gray-700">
                                    <span class="w-40 text-gray-500">Metode Kirim</span>
                                    <span>: <span id="viewCurrentSendTypeLabel">-</span></span>
                                </div>
                                <div id="viewCurrentExpeditionDisplay" class="flex gap-2 text-sm text-gray-700 hidden">
                                    <span class="w-40 text-gray-500">Ekspedisi</span>
                                    <span>: <span id="viewCurrentExpeditionLabel">-</span></span>
                                </div>
                            </div>

                            <!-- Input Resi -->
                            <div id="viewResiInputContainer" class="hidden space-y-2 bg-white p-3 rounded-lg">
                                <label class="block text-sm font-medium text-gray-700">
                                    Nomor Resi <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="viewNomorResi" placeholder="Masukkan nomor resi"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#BB9457] outline-none">
                                <p class="text-xs text-gray-500">Masukkan nomor resi dari kurir ekspedisi jika sudah tersedia.</p>
                            </div>

                            <!-- Save Button -->
                            <button onclick="applyStatusFromView()" class="w-full mt-4 bg-green-500 hover:bg-green-600 text-white py-2 rounded-lg font-semibold text-sm transition">
                                Simpan
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- ✅ MODAL: Info ketika status tidak bisa diubah (pengganti alert) --}}
    <div id="cannotChangeModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-white w-[420px] rounded-2xl p-6 shadow-lg text-center">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Tidak Bisa Mengubah Status</h3>
            <p id="cannotChangeMessage" class="text-sm text-gray-600 mb-4">Status pesanan saat ini tidak bisa diubah.</p>
            <div class="flex justify-center gap-3">
                <button onclick="closeCannotChangeModal()" class="px-4 py-2 bg-blue-500 text-white rounded-lg font-medium">Tutup</button>
            </div>
        </div>
    </div>

    {{-- ✅ MODAL HAPUS --}}
    <div id="deleteModal" class="hidden fixed inset-0 bg-black/55 backdrop-blur-sm items-center justify-center z-50">
        <div class="w-[420px] max-w-[92vw] rounded-3xl bg-white shadow-2xl overflow-hidden">
            <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-5 text-white">
                <div class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-white/15">
                        <i class="fas fa-triangle-exclamation text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold leading-tight">Hapus Pesanan?</h3>
                        <p class="text-sm text-white/85">Tindakan ini tidak bisa dibatalkan.</p>
                    </div>
                </div>
            </div>

            <div class="px-6 py-5">
                <p class="text-sm text-gray-600 leading-6">
                    Pesanan <span id="deleteOrderNumber" class="font-semibold text-gray-800">-</span> akan dihapus permanen dari sistem.
                </p>

                <div class="mt-5 flex justify-end gap-3">
                    <button type="button" onclick="closeDeleteConfirmModal()"
                        class="rounded-xl border border-gray-200 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                        Batal
                    </button>
                    <button type="button" onclick="confirmDeleteOrder()"
                        class="rounded-xl bg-red-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-red-700 transition">
                        Ya, Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentButton = null;
        let deleteTargetForm = null;

        // Status label mapping untuk 5 status yang tersedia:
        // - unpaid: Belum (Pembayaran belum diterima)
        // - processing: Dikerjakan (Pesanan sedang dikerjakan)
        // - shipping: Dikirim (Pesanan dalam pengiriman)
        // - completed: Selesai (Pesanan selesai diterima)
        // - canceled: Dibatalkan (Pesanan dibatalkan)
        const statusLabelMap = {
            completed: 'Selesai',
            shipping: 'Dikirim',
            processing: 'Dikerjakan',
            unpaid: 'Belum',
            canceled: 'Dibatalkan'
        };

        const sendTypeLabelMap = {
            pickUp: 'Ambil Sendiri',
            kurirToko: 'Kurir Toko',
            kurirEkspedisi: 'Kurir Ekspedisi'
        };

        // FILTER — dihapus karena sekarang menggunakan backend filtering via query parameter
        // Filter sekarang dilakukan dengan mengirim ?status=xxx ke backend

        // OPEN MODAL STATUS
        function openStatusModal(btn, id, statusCode) {
            // ambil row dulu (row menyimpan status ter-normalize di data-status)
            const row = document.querySelector(`tr[data-id="${id}"]`);
            const rowStatus = (row?.dataset.status || '').toString().toLowerCase();
            const passedStatus = (statusCode || '').toString().toLowerCase();

            // Putuskan apakah kita boleh membuka modal edit: kalau salah satu sumber bilang 'processing'
            const isProcessing = (passedStatus === 'processing') || (rowStatus === 'processing');

            if (!isProcessing) {
                // Tampilkan modal info yang lebih rapi
                const label = statusLabelMap[passedStatus] || statusLabelMap[rowStatus] || (passedStatus || rowStatus || 'Tidak diketahui');
                openCannotChangeModal(`Status saat ini adalah "${label}".\nStatus hanya bisa diubah dari "Dikerjakan" menjadi "Dikirim".`);
                return;
            }

            currentButton = btn;
            const sendType = row?.dataset.sendType || '';
            const expedition = row?.dataset.choosenExpedition || '';
            const nomorResi = row?.dataset.nomorResi || '';

            document.getElementById('modalPesananId').value = id;
            document.getElementById('modalCurrentStatus').value = 'processing';

            document.getElementById('modalSendTypeLabel').textContent = sendTypeLabelMap[sendType] || '-';
            const modalExpeditionDisplay = document.getElementById('modalExpeditionDisplay');
            const modalExpeditionLabel = document.getElementById('modalExpeditionLabel');
            const resiInputContainer = document.getElementById('resiInputContainer');
            const nomorResiInput = document.getElementById('nomorResi');

            if (sendType === 'kurirEkspedisi') {
                modalExpeditionLabel.textContent = expedition || '-';
                modalExpeditionDisplay.classList.remove('hidden');
                resiInputContainer.classList.remove('hidden');
                nomorResiInput.value = nomorResi === '-' ? '' : nomorResi;
            } else {
                modalExpeditionDisplay.classList.add('hidden');
                resiInputContainer.classList.add('hidden');
                nomorResiInput.value = '';
            }

            document.getElementById('statusModal').style.display = 'flex';
        }

        // APPLY STATUS
        function applyStatus() {
            const status = 'shipping'; // Status selalu shipping
            const id = document.getElementById('modalPesananId').value;
            const nomorResi = document.getElementById('nomorResi').value.trim();
            const row = currentButton?.closest('tr') || document.querySelector(`tr[data-id="${id}"]`);
            const sendType = row?.dataset.sendType || '';

            // Resi hanya wajib jika memang ekspedisi
            if (sendType === 'kurirEkspedisi' && !nomorResi) {
                alert('Nomor resi wajib diisi untuk kurir ekspedisi!');
                document.getElementById('nomorResi').focus();
                return;
            }

            const payload = {
                status: status,
                nomor_resi: (sendType === 'kurirEkspedisi' && nomorResi) ? nomorResi : undefined
            };

            fetch(`/pesanan/${id}/status`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(payload)
                })
                .then(res => res.json())
                .then(data => {
                    if (!data.success) return alert(data.message || 'Gagal update status');

                    // Color mapping untuk status badge
                    const colorMap = {
                        completed: 'bg-green-500',
                        shipping: 'bg-blue-500',
                        processing: 'bg-yellow-500',
                        unpaid: 'bg-red-500',
                        canceled: 'bg-gray-600'
                    };

                    currentButton.innerText = data.status_label || statusLabelMap[status] || status;
                    currentButton.className =
                        `px-3 py-1 text-xs font-semibold rounded-full text-white ${colorMap[status] || 'bg-red-500'}`;
                    currentButton.closest('tr').dataset.status = data.status || status;
                    // update resi on table row when applied from status modal
                    const rowForModal = currentButton.closest('tr');
                    if (rowForModal) {
                        rowForModal.dataset.nomorResi = data.tracking_number || document.getElementById('nomorResi')?.value || '';
                    }

                    closeStatusModal();
                    alert('Status berhasil diupdate ke "Dikirim"' + (nomorResi ? ' dan resi tersimpan' : ''));
                })
                .catch(() => alert('Terjadi kesalahan'));
        }

        // CLOSE STATUS MODAL
        function closeStatusModal() {
            document.getElementById('statusModal').style.display = 'none';
            document.getElementById('nomorResi').value = '';
            document.getElementById('resiInputContainer').classList.add('hidden');
            document.getElementById('modalExpeditionDisplay').classList.add('hidden');
            document.getElementById('modalSendTypeLabel').textContent = '-';
            document.getElementById('modalExpeditionLabel').textContent = '-';
        }

        // OPEN 'CANNOT CHANGE' INFO MODAL
        function openCannotChangeModal(message) {
            const modal = document.getElementById('cannotChangeModal');
            const msgEl = document.getElementById('cannotChangeMessage');
            msgEl.textContent = message;
            modal.classList.remove('hidden');
            modal.style.display = 'flex';
        }

        function openDeleteConfirmModal(form, orderNumber) {
            deleteTargetForm = form;
            const modal = document.getElementById('deleteModal');
            const label = document.getElementById('deleteOrderNumber');
            if (label) label.textContent = orderNumber || '-';
            modal.classList.remove('hidden');
            modal.style.display = 'flex';
            return false;
        }

        function closeDeleteConfirmModal() {
            const modal = document.getElementById('deleteModal');
            modal.classList.add('hidden');
            modal.style.display = 'none';
            deleteTargetForm = null;
        }

        function confirmDeleteOrder() {
            if (!deleteTargetForm) return;
            deleteTargetForm.submit();
        }

        // CLOSE 'CANNOT CHANGE' INFO MODAL
        function closeCannotChangeModal() {
            const modal = document.getElementById('cannotChangeModal');
            modal.classList.add('hidden');
            modal.style.display = 'none';
        }

        // OPEN VIEW MODAL
        function openViewModal(id) {
            const row = document.querySelector(`tr[data-id="${id}"]`);
            if (!row) return;

            document.getElementById('view_id_pesanan').textContent = row.dataset.idPesanan || '-';
            document.getElementById('view_tanggal_pembelian').textContent = row.dataset.tanggalPembelian || '-';
            document.getElementById('view_tanggal_pengantaran').textContent = row.dataset.tanggalPengantaran || '-';
            document.getElementById('view_status').textContent = row.dataset.status || '-';
            document.getElementById('view_total_harga').textContent = row.dataset.totalHarga || '-';
            document.getElementById('view_nama_pelanggan').textContent = row.dataset.namaPelanggan || '-';
            document.getElementById('view_nomor_telepon').textContent = row.dataset.nomorTelepon || '-';
            document.getElementById('view_email').textContent = row.dataset.email || '-';
            document.getElementById('view_metode_pengiriman').textContent = row.dataset.metodePengiriman || '-';
            document.getElementById('view_alamat').textContent = row.dataset.alamat || '-';
            // show expedition and resi only if send_type = kurirEkspedisi
            const currentSendType = row.dataset.sendType || '';
            if (currentSendType === 'kurirEkspedisi') {
                const chosenExp = row.dataset.choosenExpedition || '';
                const nomorResi = row.dataset.nomorResi || '';
                document.getElementById('view_choosen_expedition').textContent = chosenExp || '-';
                document.getElementById('view_nomor_resi').textContent = nomorResi || '-';
                document.getElementById('viewExpeditionDisplay').classList.remove('hidden');
                document.getElementById('viewResiDisplay').classList.remove('hidden');
            } else {
                document.getElementById('viewExpeditionDisplay').classList.add('hidden');
                document.getElementById('viewResiDisplay').classList.add('hidden');
            }
            document.getElementById('view_catatan_alamat').textContent = row.dataset.catatanAlamat || '-';
            document.getElementById('view_metode_pembayaran').textContent = row.dataset.metodePembayaran || '-';
            document.getElementById('view_status_pembayaran').textContent = row.dataset.statusPembayaran || '-';
            document.getElementById('viewCurrentSendTypeLabel').textContent = sendTypeLabelMap[row.dataset.sendType || ''] || '-';

            let details = [];
            try {
                details = JSON.parse(row.dataset.detailProduk || '[]');
            } catch (e) {
                details = [];
            }

            const produkHtml = details.length ? details.map((produk, index) => `
                <div>
                    <p class="font-semibold text-gray-700 mb-1">Produk ${index + 1}</p>
                    <div class="space-y-1">
                        <div class="flex gap-2">
                            <span class="w-24 text-gray-500">Nama Produk</span>
                            <span>: ${escapeHtml(produk.nama || '-')}</span>
                        </div>
                        <div class="flex gap-2">
                            <span class="w-24 text-gray-500">Jumlah</span>
                            <span>: ${escapeHtml(String(produk.jumlah ?? 0))}</span>
                        </div>
                    </div>
                </div>
            `).join('') : `
                <div class="text-sm text-gray-500">Detail produk tidak tersedia.</div>
            `;

            document.getElementById('view_produk').innerHTML = produkHtml;

            // Setup edit status section - hanya tampil jika status = processing
            const editStatusSection = document.getElementById('editStatusSection');
            const currentStatus = row.dataset.status || '';
            const sendType = row.dataset.sendType || '';

            if (currentStatus === 'processing') {
                editStatusSection.classList.remove('hidden');
                document.getElementById('viewModalPesananId').value = id;
                if (sendType === 'kurirEkspedisi') {
                    document.getElementById('viewCurrentExpeditionDisplay').classList.remove('hidden');
                    document.getElementById('viewCurrentExpeditionLabel').textContent = row.dataset.choosenExpedition || '-';
                    document.getElementById('viewResiInputContainer').classList.remove('hidden');
                    document.getElementById('viewNomorResi').value = row.dataset.nomorResi || '';
                } else {
                    document.getElementById('viewCurrentExpeditionDisplay').classList.add('hidden');
                    document.getElementById('viewResiInputContainer').classList.add('hidden');
                    document.getElementById('viewNomorResi').value = '';
                }
            } else {
                editStatusSection.classList.add('hidden');
            }

            document.getElementById('viewModal').style.display = 'flex';
        }

        // Handle view send type change
        function handleViewSendTypeChange() {
            return;
        }

        // Apply status from view modal
        function applyStatusFromView() {
            const status = 'shipping'; // Status selalu shipping
            const id = document.getElementById('viewModalPesananId').value;
            const nomorResi = document.getElementById('viewNomorResi').value.trim();
            const row = document.querySelector(`tr[data-id="${id}"]`);
            const sendType = row?.dataset.sendType || '';

            if (sendType === 'kurirEkspedisi' && !nomorResi) {
                alert('Nomor resi wajib diisi untuk kurir ekspedisi!');
                document.getElementById('viewNomorResi').focus();
                return;
            }

            const payload = {
                status: status,
                nomor_resi: (sendType === 'kurirEkspedisi' && nomorResi) ? nomorResi : undefined
            };

            fetch(`/pesanan/${id}/status`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(payload)
                })
                .then(res => res.json())
                .then(data => {
                    if (!data.success) return alert(data.message || 'Gagal update status');

                    // Update row data di table
                    const row = document.querySelector(`tr[data-id="${id}"]`);
                    if (row) {
                            row.dataset.status = data.status || status;
                            // update resi value in row dataset when applied from view modal
                            row.dataset.nomorResi = data.tracking_number || document.getElementById('viewNomorResi')?.value || '';
                            // Update status badge
                        const statusBtn = row.querySelector('button[data-status-label]');
                        if (statusBtn) {
                            const colorMap = {
                                completed: 'bg-green-500',
                                shipping: 'bg-blue-500',
                                processing: 'bg-yellow-500',
                                unpaid: 'bg-red-500',
                                canceled: 'bg-gray-600'
                            };

                            statusBtn.innerText = data.status_label || 'Dikirim';
                            statusBtn.className = `px-3 py-1 text-xs font-semibold rounded-full text-white ${colorMap[status] || 'bg-blue-500'}`;
                        }
                    }

                    closeViewModal();
                    alert('Status berhasil diupdate ke "Dikirim"' + (nomorResi ? ' dan resi tersimpan' : ''));
                })
                .catch(() => alert('Terjadi kesalahan'));
        }

        function escapeHtml(text) {
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return text.replace(/[&<>"']/g, m => map[m]);
        }

        // CLOSE VIEW MODAL
        function closeViewModal() {
            document.getElementById('viewModal').style.display = 'none';
            document.getElementById('viewNomorResi').value = '';
            document.getElementById('viewResiInputContainer').classList.add('hidden');
            document.getElementById('editStatusSection').classList.add('hidden');
            document.getElementById('viewCurrentExpeditionDisplay').classList.add('hidden');
            document.getElementById('viewCurrentSendTypeLabel').textContent = '-';
            document.getElementById('viewCurrentExpeditionLabel').textContent = '-';
        }

        // Klik luar modal status
        document.getElementById('statusModal').addEventListener('click', function(e) {
            if (e.target === this) closeStatusModal();
        });

        // Klik luar modal view
        document.getElementById('viewModal').addEventListener('click', function(e) {
            if (e.target === this) closeViewModal();
        });

        // Klik luar modal cannotChange
        document.getElementById('cannotChangeModal').addEventListener('click', function(e) {
            if (e.target === this) closeCannotChangeModal();
        });

        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) closeDeleteConfirmModal();
        });
    </script>

@endsection
