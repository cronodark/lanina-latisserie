@extends('layouts.admin')

@section('title', 'Dashboard | lanina')

@section('content')
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Dashboard</h1>

    <!-- TOP -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

        <!-- Pendapatan -->
        <div class="bg-[#BB9457] text-white rounded-2xl p-6 flex justify-between items-center shadow">
            <div>
                <p class="text-xl opacity-80">Total Pendapatan</p>
                <h2 class="text-3xl font-bold mt-2">Rp {{ number_format($preOrders->sum('total'), 0, ',', '.') }}</h2>
            </div>
            <div class="bg-white p-3 rounded-xl"
                style="flex-shrink:0;width:48px;height:48px;display:flex;align-items:center;justify-content:center;">
                <svg xmlns="http://www.w3.org/2000/svg"
                    style="width:24px;height:24px;min-width:24px;min-height:24px;display:block;" fill="none"
                    viewBox="0 0 24 24" stroke="#BB9457" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
            </div>
        </div>

        <!-- Banner -->
        <div class="relative rounded-2xl overflow-hidden shadow">
            <img src="{{ asset('images/banner.png') }}" class="absolute inset-0 w-full h-full object-cover">
            <div class="absolute inset-0 bg-black/40"></div>
            <div class="relative px-8 py-6 text-white">
                <h3 class="text-2xl font-semibold">Selamat Datang, Admin!</h3>
                <p class="text-sm opacity-90">Pantau semua pesanan dan kelola toko LANINA dari sini.</p>
            </div>
        </div>

    </div>

    <!-- STATISTIK -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6 mb-8">

        <div class="bg-white rounded-2xl p-5 shadow flex items-start justify-between">
            <div>
                <p class="text-sm text-gray-500">Total Pesanan</p>
                <h3 class="text-2xl font-bold text-gray-900 mt-2">{{ $preOrders->count() }}</h3>
            </div>
            <div class="bg-purple-100 p-3 rounded-xl">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-width="2" d="M12 12a5 5 0 100-10 5 5 0 000 10zM4 20a8 8 0 0116 0" />
                </svg>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow flex items-start justify-between">
            <div>
                <p class="text-sm text-gray-500">Total Selesai</p>
                <h3 class="text-2xl font-bold text-gray-900 mt-2">{{ $preOrders->where('status', 'completed')->count() }}</h3>
            </div>
            <div class="bg-green-100 p-3 rounded-xl">
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow flex items-start justify-between">
            <div>
                <p class="text-sm text-gray-500">Dikirim</p>
                <h3 class="text-2xl font-bold text-gray-900 mt-2">{{ $preOrders->where('status', 'shipping')->count() }}</h3>
            </div>
            <div class="bg-blue-100 p-3 rounded-xl">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-width="2" d="M3 7h10v8H3V7zm10 3h4l3 3v2h-7v-5z" />
                    <circle cx="7" cy="18" r="2" /><circle cx="17" cy="18" r="2" />
                </svg>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow flex items-start justify-between">
            <div>
                <p class="text-sm text-gray-500">Dikerjakan</p>
                <h3 class="text-2xl font-bold text-gray-900 mt-2">{{ $preOrders->where('status', 'processing')->count() }}</h3>
            </div>
            <div class="bg-yellow-100 p-3 rounded-xl">
                <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow flex items-start justify-between">
            <div>
                <p class="text-sm text-gray-500">Belum Bayar</p>
                <h3 class="text-2xl font-bold text-gray-900 mt-2">{{ $preOrders->where('status', 'unpaid')->count() }}</h3>
            </div>
            <div class="bg-red-100 p-3 rounded-xl">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </div>
        </div>

    </div>

    <!-- Grafik -->
    <div class="bg-white rounded-2xl p-4 md:p-6 shadow mb-8">

        <!-- HEADER -->
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-3 mb-4">
            <div>
                <h3 class="text-lg font-semibold" id="chartTitle">{{ $grafikData['title'] }}</h3>
                <p class="text-xs text-gray-400 mt-0.5">Pilih bulan untuk melihat per hari, atau pilih tahun saja untuk melihat per bulan</p>
            </div>

            <!-- FILTER PERIODE: dropdown bulan + tahun -->
            <div class="flex items-center gap-2 flex-wrap">
                <!-- Dropdown Bulan -->
                <select id="filterBulan"
                    class="px-3 py-2 border border-gray-200 rounded-xl text-sm bg-gray-50 focus:ring-2 focus:ring-[#BB9457] outline-none cursor-pointer">
                    <option value="">-- Semua Bulan --</option>
                    <option value="1">Januari</option>
                    <option value="2">Februari</option>
                    <option value="3">Maret</option>
                    <option value="4">April</option>
                    <option value="5" selected>Mei</option>
                    <option value="6">Juni</option>
                    <option value="7">Juli</option>
                    <option value="8">Agustus</option>
                    <option value="9">September</option>
                    <option value="10">Oktober</option>
                    <option value="11">November</option>
                    <option value="12">Desember</option>
                </select>

                <!-- Dropdown Tahun -->
                <select id="filterTahun"
                    class="px-3 py-2 border border-gray-200 rounded-xl text-sm bg-gray-50 focus:ring-2 focus:ring-[#BB9457] outline-none cursor-pointer">
                    @for ($y = now()->year; $y >= now()->year - 4; $y--)
                        <option value="{{ $y }}" {{ $y == now()->year ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>

                <!-- Tombol Terapkan -->
                <button id="btnTerapkan"
                    class="px-4 py-2 bg-[#BB9457] text-white rounded-xl text-sm font-medium hover:bg-[#a07d45] transition-colors">
                    Terapkan
                </button>
            </div>
        </div>

        <!-- Loading indicator -->
        <div id="chartLoading" class="hidden items-center justify-center h-[300px]">
            <div class="flex flex-col items-center gap-2 text-gray-400">
                <svg class="animate-spin w-8 h-8 text-[#BB9457]" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                <span class="text-sm">Memuat data...</span>
            </div>
        </div>

        <!-- Empty state grafik -->
        <div id="chartEmpty" class="hidden items-center justify-center h-[300px]">
            <div class="flex flex-col items-center gap-3 text-gray-400">
                <svg class="w-14 h-14 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                <p class="text-sm font-medium" id="chartEmptyText">Tidak ada data pesanan pada periode ini.</p>
                <p class="text-xs text-gray-300">Coba pilih periode yang berbeda.</p>
            </div>
        </div>

        <!-- CHART -->
        <div class="w-full h-[300px] md:h-[350px]" id="chartWrapper">
            <canvas id="chartPendapatan"></canvas>
        </div>

    </div>

    <!-- Semua Pesanan Preorder -->
    <div class="bg-white rounded-2xl p-6 shadow">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-semibold text-gray-800">Semua Pesanan Preorder</h3>
            <select id="filterStatus"
                class="px-4 py-2 border border-gray-200 rounded-xl text-sm bg-gray-50 focus:ring-2 focus:ring-[#BB9457] outline-none cursor-pointer">
                <option value="">Semua Status</option>
                <option value="unpaid">Belum Bayar</option>
                <option value="processing">Dikerjakan</option>
                <option value="shipping">Dikirim</option>
                <option value="completed">Selesai</option>
                <option value="cancelled">Dibatalkan</option>
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
                        <th class="py-3 px-4 text-left">Total Harga</th>
                        <th class="py-3 px-4 text-left">Status</th>
                    </tr>
                </thead>
                <tbody id="tbodyDeadline" class="divide-y divide-gray-100"></tbody>
            </table>
        </div>

        <div id="emptyState" class="hidden text-center py-10 text-gray-400 text-sm">
            Tidak ada data preorder dengan status ini.
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {

            // ==============================
            // DATA BACKEND
            // ==============================
            window.pesananData = @json($pesanan);
            const pesananData  = window.pesananData || [];

            const initialGrafik = @json($grafikData);
            const currentYear   = {{ $currentYear }};
            const currentMonth  = {{ $currentMonth }};

            // ==============================
            // CHART SETUP
            // ==============================
            const ctx = document.getElementById('chartPendapatan').getContext('2d');

            window.chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: initialGrafik.labels,
                    datasets: [{
                        label: 'Pendapatan (Rp)',
                        data: initialGrafik.values,
                        backgroundColor: 'rgba(187, 148, 87, 0.2)',
                        borderColor: '#BB9457',
                        borderWidth: 2,
                        borderRadius: 6,
                        hoverBackgroundColor: 'rgba(187, 148, 87, 0.5)',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    const val = context.parsed.y || 0
                                    return ' Rp ' + val.toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function (value) {
                                    if (value >= 1000000) return 'Rp ' + (value / 1000000).toFixed(1) + 'jt';
                                    if (value >= 1000)    return 'Rp ' + (value / 1000).toFixed(0) + 'rb';
                                    return 'Rp ' + value;
                                }
                            },
                            grid: { color: 'rgba(0,0,0,0.05)' }
                        },
                        x: { grid: { display: false } }
                    }
                }
            });

            // ==============================
            // UPDATE CHART
            // ==============================
            function updateChart(labels, values, title) {
                chart.data.labels = labels;
                chart.data.datasets[0].data = values;
                chart.update('active');
                document.getElementById('chartTitle').textContent = title;
            }

            function setLoading(show) {
                const loading = document.getElementById('chartLoading');
                const wrapper = document.getElementById('chartWrapper');
                const empty   = document.getElementById('chartEmpty');
                if (show) {
                    loading.classList.remove('hidden');
                    loading.classList.add('flex');
                    wrapper.classList.add('hidden');
                    empty.classList.add('hidden');
                    empty.classList.remove('flex');
                } else {
                    loading.classList.add('hidden');
                    loading.classList.remove('flex');
                }
            }

            function setChartEmpty(isEmpty) {
                const wrapper = document.getElementById('chartWrapper');
                const empty   = document.getElementById('chartEmpty');
                if (isEmpty) {
                    wrapper.classList.add('hidden');
                    empty.classList.remove('hidden');
                    empty.classList.add('flex');
                    document.getElementById('chartEmptyText').textContent =
                        'Tidak ada data pesanan pada periode ini.';
                } else {
                    wrapper.classList.remove('hidden');
                    empty.classList.add('hidden');
                    empty.classList.remove('flex');
                }
            }

            async function fetchGrafik(params) {
                setLoading(true);
                try {
                    const url = new URL('{{ route("dashboard.grafik") }}', window.location.origin);
                    Object.entries(params).forEach(([k, v]) => { if (v) url.searchParams.set(k, v); });

                    const res  = await fetch(url.toString(), {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    const data = await res.json();

                    const hasData = data.values.some(v => v > 0);
                    setChartEmpty(!hasData, data.title);

                    if (hasData) {
                        updateChart(data.labels, data.values, data.title);
                    } else {
                        // Tetap update judul meski kosong
                        document.getElementById('chartTitle').textContent = data.title;
                    }
                } catch (e) {
                    console.error('Gagal memuat data grafik:', e);
                } finally {
                    setLoading(false);
                }
            }

            // ==============================
            // CEK DATA AWAL SAAT LOAD
            // ==============================
            const initialHasData = initialGrafik.values.some(v => v > 0);
            setChartEmpty(!initialHasData);
            if (!initialHasData) {
                document.getElementById('chartTitle').textContent = initialGrafik.title;
            }

            // ==============================
            // FILTER PERIODE
            // ==============================
            document.getElementById('btnTerapkan').addEventListener('click', function () {
                const bulan = document.getElementById('filterBulan').value;   // '' atau '1'-'12'
                const tahun = document.getElementById('filterTahun').value;   // '2024', '2025', dst

                const params = { year: tahun };
                if (bulan) params.month = bulan;

                fetchGrafik(params);
            });

            // ==============================
            // HELPER TABEL
            // ==============================
            function formatDate(dateStr) {
                if (!dateStr) return '-';
                return new Date(dateStr).toLocaleDateString('id-ID');
            }

            function formatStatus(status) {
                const statusLabel = {
                    unpaid: 'Belum Bayar',
                    processing: 'Diproses',
                    shipping: 'Dikirim',
                    completed: 'Selesai',
                    cancelled: 'Dibatalkan',
                };

                return statusLabel[status] || status.charAt(0).toUpperCase() + status.slice(1);
            }

            const statusColor = {
                unpaid:     'bg-red-500 text-white',
                processing: 'bg-yellow-500 text-white',
                shipping:   'bg-blue-500 text-white',
                completed:  'bg-green-500 text-white',
                cancelled:  'bg-gray-500 text-white',
                expired:    'bg-orange-500 text-white',
                failed:     'bg-rose-500 text-white'
            };

            function escapeHtml(value) {
                return String(value ?? '-')
                    .replaceAll('&', '&amp;')
                    .replaceAll('<', '&lt;')
                    .replaceAll('>', '&gt;')
                    .replaceAll('"', '&quot;')
                    .replaceAll("'", '&#039;');
            }

            function renderTabel(filter = '') {
                const tbody = document.getElementById('tbodyDeadline');
                const empty = document.getElementById('emptyState');

                let data = pesananData;
                if (filter) data = data.filter(p => p.status.toLowerCase() === filter.toLowerCase());

                if (!data.length) {
                    tbody.innerHTML = '';
                    empty.classList.remove('hidden');
                    return;
                }

                empty.classList.add('hidden');
                tbody.innerHTML = data.map(p => `
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-3 py-3">${escapeHtml(p.id)}</td>
                        <td class="px-3 py-3">${escapeHtml(p.nama_pelanggan)}</td>
                        <td class="px-3 py-3">${escapeHtml(p.nama_produk)}</td>
                        <td class="px-3 py-3">${formatDate(p.tanggal_pembelian)}</td>
                        <td class="px-3 py-3">Rp ${(p.total_harga || 0).toLocaleString('id-ID')}</td>
                        <td class="">
                            <span class="inline-flex items-center whitespace-nowrap px-2 py-0.5 text-[11px] font-semibold rounded-full ${statusColor[p.status] || 'bg-gray-200 text-gray-700'}">
                                ${formatStatus(p.status)}
                            </span>
                        </td>
                    </tr>
                `).join('');
            }

            document.getElementById('filterStatus').addEventListener('change', function () {
                renderTabel(this.value);
            });

            renderTabel();
        });
    </script>
@endpush
