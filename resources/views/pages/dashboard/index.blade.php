@extends('layouts.admin')

@section('title', 'Dashboard | lanina')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

@section('content')
<h1 class="text-2xl font-bold text-gray-800 mb-6">Dashboard</h1>

<!-- TOP -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

    <!-- Pendapatan -->
  <div class="bg-[#BB9457] text-white rounded-2xl p-6 flex justify-between items-center shadow">
    <div>
      <p class="text-xl opacity-80">Total Pendapatan</p>
      <h2 class="text-3xl font-bold mt-2">Rp 0</h2>
    </div>
    <div class="bg-white p-3 rounded-xl" style="flex-shrink:0;width:48px;height:48px;display:flex;align-items:center;justify-content:center;">
    <svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px;min-width:24px;min-height:24px;display:block;" fill="none" viewBox="0 0 24 24" stroke="#BB9457" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
    </svg>
    </div>
  </div>

    <!-- Banner -->
  <div class="relative rounded-2xl overflow-hidden shadow">
    <img src="/images/banner.jpg" class="absolute inset-0 w-full h-full object-cover">
    <div class="absolute inset-0 bg-black/40"></div>

    <div class="relative px-8 py-6 text-white">
      <h3 class="text-2xl font-semibold">Selamat Datang, Admin!</h3>
      <p class="text-sm opacity-90">
        Pantau semua pesanan dan kelola toko LANINA dari sini.
      </p>
    </div>
  </div>

 </div>

  <!-- STATISTIK -->
  <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6 mb-8">

  <!-- Total Pesanan -->
  <div class="bg-white rounded-2xl p-5 shadow flex items-start justify-between">
    <div>
      <p class="text-sm text-gray-500">Total Pesanan</p>
      <h3 class="text-2xl font-bold text-gray-900 mt-2" data-stat="pesanan">0</h3>
    </div>
    <div class="bg-purple-100 p-3 rounded-xl">
      <svg xmlns="http://www.w3.org/2000/svg"
      class="w-5 h-5 text-purple-500"
      fill="none" viewBox="0 0 24 24" stroke="currentColor">

      <path stroke-width="2"
          d="M12 12a5 5 0 100-10 5 5 0 000 10zM4 20a8 8 0 0116 0"/>
    </svg>
    </div>
  </div>

  <!-- Total Selesai -->
  <div class="bg-white rounded-2xl p-5 shadow flex items-start justify-between">
    <div>
      <p class="text-sm text-gray-500">Total Selesai</p>
      <h3 class="text-2xl font-bold text-gray-900 mt-2" data-stat="selesai">0</h3>
    </div>
    <div class="bg-green-100 p-3 rounded-xl">
      <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
          d="M5 13l4 4L19 7"/>
      </svg>
    </div>
  </div>

  <!-- Dikirim -->
  <div class="bg-white rounded-2xl p-5 shadow flex items-start justify-between">
    <div>
      <p class="text-sm text-gray-500">Dikirim</p>
      <h3 class="text-2xl font-bold text-gray-900 mt-2" data-stat="dikirim">0</h3>
    </div>
    <div class="bg-blue-100 p-3 rounded-xl">
      <svg xmlns="http://www.w3.org/2000/svg"
      class="w-5 h-5 text-blue-500"
      fill="none" viewBox="0 0 24 24" stroke="currentColor">

      <path stroke-width="2"
          d="M3 7h10v8H3V7zm10 3h4l3 3v2h-7v-5z"/>
      <circle cx="7" cy="18" r="2"/>
      <circle cx="17" cy="18" r="2"/>
    </svg>
    </div>
  </div>

  <!-- Dikerjakan -->
  <div class="bg-white rounded-2xl p-5 shadow flex items-start justify-between">
    <div>
      <p class="text-sm text-gray-500">Dikerjakan</p>
      <h3 class="text-2xl font-bold text-gray-900 mt-2" data-stat="dikerjakan">0</h3>
    </div>
    <div class="bg-yellow-100 p-3 rounded-xl">
      <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
      </svg>
    </div>
  </div>

  <!-- Belum -->
  <div class="bg-white rounded-2xl p-5 shadow flex items-start justify-between">
    <div>
      <p class="text-sm text-gray-500">Belum</p>
      <h3 class="text-2xl font-bold text-gray-900 mt-2" data-stat="belum">0</h3>
    </div>
    <div class="bg-red-100 p-3 rounded-xl">
      <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
          d="M6 18L18 6M6 6l12 12"/>
      </svg>
    </div>
  </div>

</div>


  <!-- Grafik -->
<div class="bg-white rounded-2xl p-4 md:p-6 shadow mb-8">

    <!-- HEADER -->
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-3 mb-4">

        <h3 class="text-lg font-semibold">
            Grafik Penjualan Per Hari
        </h3>

        <!-- FILTER TANGGAL -->
        <input type="text"
        id="filterTanggal"
        placeholder="Pilih Tanggal"
        class="px-4 py-2 border border-gray-200 rounded-xl text-sm bg-gray-50 focus:ring-2 focus:ring-[#BB9457] outline-none cursor-pointer w-full md:w-auto">
  </div>

    <!-- CHART -->
    <div class="w-full h-[300px] md:h-[350px]">
        <canvas id="chartPendapatan" width="400" height="300"></canvas>
    </div>

</div>

  <!-- Pesanan Mendekati Deadline -->
<div class="bg-white rounded-2xl p-6 shadow">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-xl font-semibold text-gray-800">Pesanan Mendekati Deadline</h3>

        <!-- FILTER STATUS -->
        <select id="filterStatus"
            class="px-4 py-2 border border-gray-200 rounded-xl text-sm bg-gray-50 focus:ring-2 focus:ring-[#BB9457] outline-none cursor-pointer">
            <option value="">Semua Status</option>
            <option value="selesai">Selesai</option>
            <option value="dikirim">Dikirim</option>
            <option value="dikerjakan">Dikerjakan</option>
            <option value="belum">Belum</option>
        </select>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-gray-500 text-m tracking-wider">
                    <th class="py-3 px-4 text-left">Id Pesanan</th>
                    <th class="py-3 px-4 text-left">Nama Pelanggan</th>
                    <th class="py-3 px-4 text-left">Nama Produk</th>
                    <th class="py-3 px-4 text-left">Tanggal Pembelian</th>
                    <th class="py-3 px-4 text-left">Tanggal Pengantaran</th>
                    <th class="py-3 px-4 text-left">Total Harga</th>
                    <th class="py-3 px-4 text-left">Status</th>
                </tr>
            </thead>
            <tbody id="tbodyDeadline" class="divide-y divide-gray-100">
                <!-- diisi dummy dulu -->
            </tbody>
        </table>
    </div>

    <!-- Empty state -->
    <div id="emptyState" class="hidden text-center py-10 text-gray-400 text-sm">
        Tidak ada pesanan dengan status ini.
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<style>
.flatpickr-calendar {
    border-radius: 16px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    border: none;
}

.flatpickr-months {
    background: #BB9457;
    border-radius: 16px 16px 0 0;
}

/* Dropdown bulan */
.flatpickr-monthDropdown-months {
    background: #BB9457 !important;
    color: white !important;
}

/* Item bulan */
.flatpickr-monthDropdown-month option{
    background: #BB9457 !important;
    color: white !important;
}

/* Hover bulan */
.flatpickr-monthDropdown-month:hover {
    background: #F3E9DC !important;
    color: #BB9457 !important;
}

/* Bulan aktif */
.flatpickr-monthDropdown-month.selected {
    background: #BB9457 !important;
    color: white !important;
}
.numInputWrapper input {
    color: white !important;
    font-weight: 500;
}

.flatpickr-day:hover {
    background: #F3E9DC;
    color: #BB9457;
}

.flatpickr-day.selected {
    background: #BB9457 !important;
    color: white !important;
}
</style>
<script>
document.addEventListener("DOMContentLoaded", function () {

    // ==============================
    // DATA BACKEND
    // ==============================
    window.pesananData = @json($pesanan ?? []);
    const pesananData = window.pesananData || [];

    // ==============================
    // FLATPICKR
    // ==============================
    flatpickr("#filterTanggal", {
        dateFormat: "Y-m-d",
        onChange: function(selectedDates, dateStr) {
            updateChart(dateStr);
        }
    });

    // ==============================
    // CHART
    // ==============================
    const ctx = document.getElementById('chartPendapatan').getContext('2d');

    window.chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['1 Jul', '2 Jul', '3 Jul', '4 Jul', '5 Jul', '6 Jul', '7 Jul'],
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: [150000, 320000, 275000, 490000, 210000, 380000, 420000],
                borderColor: '#BB9457',
                backgroundColor: 'rgba(187, 148, 87, 0.1)',
                borderWidth: 2,
                pointBackgroundColor: '#BB9457',
                pointRadius: 4,
                tension: 0.4,
                fill: true,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    window.updateChart = function(tanggal) {
        const dummyData = Array.from({length: 7}, () => Math.floor(Math.random() * 500000));
        const dummyLabels = Array.from({length: 7}, (_, i) => {
            const d = new Date(tanggal);
            d.setDate(d.getDate() - (6 - i));
            return d.getDate() + ' ' + d.toLocaleString('id-ID', {month: 'short'});
        });

        chart.data.labels = dummyLabels;
        chart.data.datasets[0].data = dummyData;
        chart.update();
    }

    // ==============================
    // HELPER
    // ==============================
    function formatDate(dateStr) {
        if (!dateStr) return '-';
        return new Date(dateStr).toLocaleDateString('id-ID');
    }

    function formatStatus(status) {
        return status.charAt(0).toUpperCase() + status.slice(1);
    }

    const statusColor = {
        belum: 'bg-red-500 text-white',
        dikerjakan: 'bg-yellow-500 text-white',
        dikirim: 'bg-blue-500 text-white',
        selesai: 'bg-green-500 text-white'
    };

    function getDeadlineData() {
        const now = new Date();
        const limit = new Date();
        limit.setDate(now.getDate() + 3);

        return pesananData.filter(p => {
            if (!p.tanggal_pengantaran) return false;
            const tgl = new Date(p.tanggal_pengantaran);
            return tgl >= now && tgl <= limit;
        });
    }

    // ==============================
    // RENDER TABLE
    // ==============================
    function renderTabel(filter = '') {
        const tbody = document.getElementById('tbodyDeadline');
        const empty = document.getElementById('emptyState');

        let data = getDeadlineData();

        if (filter) {
            data = data.filter(p => p.status.toLowerCase() === filter.toLowerCase());
        }

        if (!data.length) {
            tbody.innerHTML = '';
            empty.classList.remove('hidden');
            return;
        }

        empty.classList.add('hidden');

        tbody.innerHTML = data.map(p => `
            <tr class="hover:bg-gray-50 transition">
                <td class="px-4 py-3">${p.id || '-'}</td>
                <td class="px-4 py-3">${p.nama_pelanggan || '-'}</td>
                <td class="px-4 py-3">${p.nama_produk || '-'}</td>
                <td class="px-4 py-3">${formatDate(p.tanggal_pembelian)}</td>
                <td class="px-4 py-3">${formatDate(p.tanggal_pengantaran)}</td>
                <td class="px-4 py-3">Rp ${(p.total_harga || 0).toLocaleString('id-ID')}</td>
                <td class="px-4 py-3">
                    <button class="px-3 py-1 text-xs font-semibold rounded-full ${statusColor[p.status]}">
                        ${formatStatus(p.status)}
                    </button>
                </td>
            </tr>
        `).join('');
    }

    document.getElementById('filterStatus')
        .addEventListener('change', function () {
            renderTabel(this.value);
        });

    renderTabel();
    // Hitung statistik dari pesananData
    const stats = {
        pesanan: pesananData.length,
        selesai: pesananData.filter(p => p.status === 'selesai').length,
        dikirim: pesananData.filter(p => p.status === 'dikirim').length,
        dikerjakan: pesananData.filter(p => p.status === 'dikerjakan').length,
        belum: pesananData.filter(p => p.status === 'belum').length,
    };

    Object.entries(stats).forEach(([key, val]) => {
        const el = document.querySelector(`[data-stat="${key}"]`);
        if (el) el.textContent = val;
    });

});
</script>