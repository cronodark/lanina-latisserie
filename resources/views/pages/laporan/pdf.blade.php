<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan - {{ $bulan }} {{ $tahun }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: #333;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #BB9457;
            padding-bottom: 15px;
        }

        .header h1 {
            font-size: 24px;
            color: #BB9457;
            margin-bottom: 5px;
        }

        .header h2 {
            font-size: 16px;
            color: #666;
            font-weight: normal;
        }

        .header .periode {
            font-size: 14px;
            color: #333;
            margin-top: 10px;
            font-weight: bold;
        }

        .meta-info {
            text-align: right;
            font-size: 9px;
            color: #999;
            margin-bottom: 20px;
        }

        .summary {
            display: table;
            width: 100%;
            margin-bottom: 25px;
        }

        .summary-item {
            display: table-cell;
            width: 33.33%;
            padding: 15px;
            background: #f8f9fa;
            border: 1px solid #e0e0e0;
            text-align: center;
        }

        .summary-item .label {
            font-size: 9px;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .summary-item .value {
            font-size: 16px;
            font-weight: bold;
            color: #BB9457;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #333;
            margin: 25px 0 15px 0;
            padding-bottom: 5px;
            border-bottom: 2px solid #BB9457;
        }

        .top-products {
            margin-bottom: 25px;
        }

        .top-products table {
            width: 100%;
            border-collapse: collapse;
        }

        .top-products th {
            background: #BB9457;
            color: white;
            padding: 8px;
            text-align: left;
            font-size: 10px;
        }

        .top-products td {
            padding: 8px;
            border-bottom: 1px solid #e0e0e0;
        }

        .top-products tr:nth-child(even) {
            background: #f8f9fa;
        }

        .percentage-bar {
            display: inline-block;
            height: 12px;
            background: #6B8F4E;
            border-radius: 3px;
            margin-right: 5px;
        }

        .transactions table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
        }

        .transactions th {
            background: #BB9457;
            color: white;
            padding: 6px 4px;
            text-align: left;
            font-size: 9px;
        }

        .transactions td {
            padding: 6px 4px;
            border-bottom: 1px solid #e0e0e0;
        }

        .transactions tr:nth-child(even) {
            background: #f8f9fa;
        }

        .status-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
        }

        .status-selesai {
            background: #d4edda;
            color: #155724;
        }

        .status-dikirim {
            background: #d1ecf1;
            color: #0c5460;
        }

        .status-dikerjakan {
            background: #fff3cd;
            color: #856404;
        }

        .status-belum {
            background: #f8d7da;
            color: #721c24;
        }

        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #e0e0e0;
            text-align: center;
            font-size: 9px;
            color: #999;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #999;
        }

        .empty-state p {
            font-size: 12px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    {{-- Header --}}
    <div class="header">
        <h1>LANINA PATISSERIE</h1>
        <h2>Laporan Penjualan</h2>
        <div class="periode">{{ $bulan }} {{ $tahun }}</div>
    </div>

    {{-- Meta Info --}}
    <div class="meta-info">
        Dicetak pada: {{ $tanggalCetak }}
    </div>

    {{-- Summary Section --}}
    <div class="summary">
        <div class="summary-item">
            <div class="label">Total Penjualan</div>
            <div class="value">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</div>
        </div>
        <div class="summary-item">
            <div class="label">Total Pesanan</div>
            <div class="value">{{ number_format($totalPesanan, 0, ',', '.') }}</div>
        </div>
        <div class="summary-item">
            <div class="label">Produk Terjual</div>
            <div class="value">{{ number_format($totalProdukTerjual, 0, ',', '.') }}</div>
        </div>
    </div>

    {{-- Top Products Section --}}
    <div class="section-title">Produk Terlaris</div>
    <div class="top-products">
        @if(empty($produkTerlaris) || count($produkTerlaris) === 0)
            <div class="empty-state">
                <p>Belum ada data produk terlaris untuk periode ini.</p>
            </div>
        @else
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 45%;">Nama Produk</th>
                        <th style="width: 35%;">Persentase</th>
                        <th style="width: 15%;" class="text-right">%</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($produkTerlaris as $index => $produk)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $produk['nama'] }}</td>
                        <td>
                            <span class="percentage-bar" style="width: {{ $produk['persen'] }}%;"></span>
                        </td>
                        <td class="text-right">{{ number_format($produk['persen'], 1) }}%</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    {{-- Page Break --}}
    <div class="page-break"></div>

    {{-- Transactions Section --}}
    <div class="section-title">Detail Transaksi</div>
    <div class="transactions">
        @if($tabelPenjualan->isEmpty())
            <div class="empty-state">
                <p>Belum ada transaksi untuk periode ini.</p>
            </div>
        @else
            <table>
                <thead>
                    <tr>
                        <th style="width: 10%;">ID</th>
                        <th style="width: 15%;">Pelanggan</th>
                        <th style="width: 20%;">Produk</th>
                        <th style="width: 12%;">Tgl Beli</th>
                        <th style="width: 12%;">Tgl Kirim</th>
                        <th style="width: 16%;" class="text-right">Total</th>
                        <th style="width: 15%;" class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tabelPenjualan as $item)
                    <tr>
                        <td>{{ $item->id_pesanan }}</td>
                        <td>{{ $item->nama_pelanggan }}</td>
                        <td>{{ Str::limit($item->nama_produk, 30) }}</td>
                        <td>{{ $item->tanggal_pembelian }}</td>
                        <td>{{ $item->tanggal_pengantaran }}</td>
                        <td class="text-right">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                        <td class="text-center">
                            <span class="status-badge status-{{ strtolower(str_replace(' ', '', $item->status)) }}">
                                {{ $item->status }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Summary Total --}}
            <div style="margin-top: 15px; text-align: right; font-weight: bold; font-size: 11px;">
                <span style="margin-right: 20px;">Total Transaksi: {{ $tabelPenjualan->count() }}</span>
                <span>Grand Total: Rp {{ number_format($tabelPenjualan->sum('total_harga'), 0, ',', '.') }}</span>
            </div>
        @endif
    </div>

    {{-- Footer --}}
    <div class="footer">
        <p>Dokumen ini digenerate secara otomatis oleh sistem Lanina Patisserie</p>
        <p>© {{ date('Y') }} Lanina Patisserie. All rights reserved.</p>
    </div>
</body>
</html>
