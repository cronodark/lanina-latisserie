<?php

namespace App\Http\Controllers;

use App\Models\PreOrder;
use App\Models\PreOrderDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    /**
     * Display sales report page with filtering by month and year.
     *
     * Features:
     * - Monthly sales summary (total revenue, orders, products sold)
     * - Comparison with previous month
     * - Top selling products with percentage
     * - Product sales chart (filterable by product)
     * - Detailed transaction table
     *
     * @param Request $request Query params: ?bulan=10&tahun=2025&produk_filter=Nastar
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Get filter parameters from query string
        $bulanTerpilih = (int) $request->input('bulan', now()->month);
        $tahunTerpilih = (int) $request->input('tahun', now()->year);
        $produkFilter = $request->input('produk_filter', '');

        // Validate month
        if ($bulanTerpilih < 1 || $bulanTerpilih > 12) {
            $bulanTerpilih = now()->month;
        }

        // Calculate previous month
        $bulanSebelumnya = $bulanTerpilih === 1 ? 12 : $bulanTerpilih - 1;
        $tahunSebelumnya = $bulanTerpilih === 1 ? $tahunTerpilih - 1 : $tahunTerpilih;

        // Get sales data for selected month
        $dataBulanIni = $this->getSalesDataForMonth($tahunTerpilih, $bulanTerpilih);

        // Get sales data for previous month
        $dataBulanLalu = $this->getSalesDataForMonth($tahunSebelumnya, $bulanSebelumnya);

        // Get top selling products for selected month
        $produkTerlaris = $this->getTopSellingProducts($tahunTerpilih, $bulanTerpilih);

        // Get product sales count for chart (with filter)
        $jumlahProdukSebelumnya = $this->getProductSalesCount($tahunSebelumnya, $bulanSebelumnya);
        $jumlahProdukTerpilih = $this->getProductSalesCount($tahunTerpilih, $bulanTerpilih);

        // Apply product filter if specified
        $jumlahSebelumnya = $dataBulanLalu['totalProdukTerjual'];
        $jumlahTerpilih = $dataBulanIni['totalProdukTerjual'];

        if ($produkFilter && isset($jumlahProdukSebelumnya[$produkFilter])) {
            $jumlahSebelumnya = $jumlahProdukSebelumnya[$produkFilter];
            $jumlahTerpilih = $jumlahProdukTerpilih[$produkFilter];
        }

        // Get detailed transaction table for selected month
        $tabelPenjualan = $this->getTransactionTable($tahunTerpilih, $bulanTerpilih);

        // Prepare slider products (same as top selling but with images)
        $sliderProduk = $produkTerlaris->map(function ($item) {
            return [
                'nama' => $item['nama'],
                'persen' => $item['persen'],
                'gambar' => $item['gambar'] ?? '/images/placeholder.jpg',
            ];
        })->toArray();

        return view('pages.laporan.index', [
            // Summary data
            'totalPenjualan' => $dataBulanIni['totalPenjualan'],
            'totalPesanan' => $dataBulanIni['totalPesanan'],
            'totalProdukTerjual' => $dataBulanIni['totalProdukTerjual'],

            // Previous month comparison
            'pendapatanSebelumnya' => $dataBulanLalu['totalPenjualan'],
            'pendapatanTerpilih' => $dataBulanIni['totalPenjualan'],

            // Product count for chart
            'jumlahSebelumnya' => $jumlahSebelumnya,
            'jumlahTerpilih' => $jumlahTerpilih,
            'jumlahProdukSebelumnya' => $jumlahProdukSebelumnya,
            'jumlahProdukTerpilih' => $jumlahProdukTerpilih,

            // Top products
            'produkTerlaris' => $produkTerlaris->toArray(),
            'sliderProduk' => $sliderProduk,

            // Transaction table
            'tabelPenjualan' => $tabelPenjualan,
        ]);
    }

    /**
     * Export sales report to PDF.
     *
     * @param Request $request Query params: ?bulan=5&tahun=2026
     * @return \Illuminate\Http\Response
     */
    public function exportPdf(Request $request)
    {
        // Get filter parameters
        $bulanTerpilih = (int) $request->input('bulan', now()->month);
        $tahunTerpilih = (int) $request->input('tahun', now()->year);

        // Validate month
        if ($bulanTerpilih < 1 || $bulanTerpilih > 12) {
            $bulanTerpilih = now()->month;
        }

        // Get sales data
        $dataBulanIni = $this->getSalesDataForMonth($tahunTerpilih, $bulanTerpilih);
        $produkTerlaris = $this->getTopSellingProducts($tahunTerpilih, $bulanTerpilih);
        $tabelPenjualan = $this->getTransactionTable($tahunTerpilih, $bulanTerpilih);

        // Month names
        $bulanNama = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        $data = [
            'bulan' => $bulanNama[$bulanTerpilih],
            'tahun' => $tahunTerpilih,
            'totalPenjualan' => $dataBulanIni['totalPenjualan'],
            'totalPesanan' => $dataBulanIni['totalPesanan'],
            'totalProdukTerjual' => $dataBulanIni['totalProdukTerjual'],
            'produkTerlaris' => $produkTerlaris->toArray(),
            'tabelPenjualan' => $tabelPenjualan,
        ];

        $pdf = Pdf::loadView('pages.laporan.pdf', $data);
        $pdf->setPaper('a4', 'portrait');

        $filename = 'Laporan-Penjualan-' . $bulanNama[$bulanTerpilih] . '-' . $tahunTerpilih . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Get top selling products for a specific month based on actual_periode.
     *
     * @param int $year
     * @param int $month
     * @return \Illuminate\Support\Collection
     */
    private function getTopSellingProducts(int $year, int $month)
    {
        $validStatuses = ['unpaid', 'processing', 'shipping', 'completed'];

        // Get order IDs for the month based on actual_periode
        $orderIds = PreOrder::whereYear('actual_periode', $year)
            ->whereMonth('actual_periode', $month)
            ->whereIn('status', $validStatuses)
            ->pluck('id');

        // Get product sales grouped by product/promo
        $productSales = PreOrderDetail::whereIn('pre_order_id', $orderIds)
            ->select('type', 'product_id', 'promo_id', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('type', 'product_id', 'promo_id')
            ->orderByDesc('total_quantity')
            ->limit(10)
            ->get();

        // Calculate total quantity for percentage
        $totalQuantity = $productSales->sum('total_quantity');

        // Define color palette for chart
        $colors = [
            '#996633', // Brown
            '#C48C5A', // Light Brown
            '#A7B86A', // Green
            '#E9E1CC', // Cream
            '#D8DEB4', // Light Green
            '#8B7355', // Dark Brown
            '#B8956A', // Tan
            '#9CAF88', // Sage
            '#F5E6D3', // Beige
            '#C9B896', // Sand
        ];

        return $productSales->map(function ($detail, $index) use ($totalQuantity, $colors) {
            $item = null;
            $name = 'Unknown';
            $image = null;

            if ($detail->type === 'promo' && $detail->promo_id) {
                $item = \App\Models\Promo::find($detail->promo_id);
                $name = $item ? $item->name : 'Promo Unknown';
                $image = $item && $item->getFirstMediaUrl('promo-image')
                    ? $item->getFirstMediaUrl('promo-image')
                    : null;
            } elseif ($detail->type === 'product' && $detail->product_id) {
                $item = \App\Models\Product::find($detail->product_id);
                $name = $item ? $item->name : 'Product Unknown';
                $image = $item && $item->getFirstMediaUrl('product-image')
                    ? $item->getFirstMediaUrl('product-image')
                    : null;
            }

            $percentage = $totalQuantity > 0
                ? ($detail->total_quantity / $totalQuantity) * 100
                : 0;

            return [
                'nama' => $name,
                'jumlah' => (int) $detail->total_quantity,
                'persen' => round($percentage, 1),
                'warna' => $colors[$index % count($colors)],
                'gambar' => $image,
            ];
        });
    }

    /**
     * Get sales data summary for a specific month based on actual_periode.
     *
     * @param int $year
     * @param int $month
     * @return array
     */
    private function getSalesDataForMonth(int $year, int $month)
    {
        $validStatuses = ['unpaid', 'processing', 'shipping', 'completed'];

        // Get orders for the month based on actual_periode (delivery date)
        $orders = PreOrder::whereYear('actual_periode', $year)
            ->whereMonth('actual_periode', $month)
            ->whereIn('status', $validStatuses)
            ->get();

        // Calculate totals
        $totalPenjualan = $orders->sum('total');
        $totalPesanan = $orders->count();

        // Calculate total products sold
        $totalProdukTerjual = PreOrderDetail::whereIn('pre_order_id', $orders->pluck('id'))
            ->sum('quantity');

        return [
            'totalPenjualan' => (int) $totalPenjualan,
            'totalPesanan' => $totalPesanan,
            'totalProdukTerjual' => (int) $totalProdukTerjual,
        ];
    }

    /**
     * Get product sales count for chart based on actual_periode.
     *
     * @param int $year
     * @param int $month
     * @return array
     */
    private function getProductSalesCount(int $year, int $month)
    {
        $validStatuses = ['unpaid', 'processing', 'shipping', 'completed'];

        // Get order IDs for the month based on actual_periode
        $orderIds = PreOrder::whereYear('actual_periode', $year)
            ->whereMonth('actual_periode', $month)
            ->whereIn('status', $validStatuses)
            ->pluck('id');

        // Get product sales grouped by name
        $productSales = PreOrderDetail::whereIn('pre_order_id', $orderIds)
            ->select('type', 'product_id', 'promo_id', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('type', 'product_id', 'promo_id')
            ->get();

        $result = [];

        foreach ($productSales as $detail) {
            $name = 'Unknown';

            if ($detail->type === 'promo' && $detail->promo_id) {
                $item = \App\Models\Promo::find($detail->promo_id);
                $name = $item ? $item->name : 'Promo Unknown';
            } elseif ($detail->type === 'product' && $detail->product_id) {
                $item = \App\Models\Product::find($detail->product_id);
                $name = $item ? $item->name : 'Product Unknown';
            }

            if (isset($result[$name])) {
                $result[$name] += (int) $detail->total_quantity;
            } else {
                $result[$name] = (int) $detail->total_quantity;
            }
        }

        return $result;
    }

    /**
     * Get detailed transaction table for a specific month based on actual_periode.
     *
     * @param int $year
     * @param int $month
     * @return \Illuminate\Support\Collection
     */
    private function getTransactionTable(int $year, int $month)
    {
        $validStatuses = ['unpaid', 'processing', 'shipping', 'completed'];

        $orders = PreOrder::with(['customer', 'detailPreOrders.product', 'detailPreOrders.promo'])
            ->whereYear('actual_periode', $year)
            ->whereMonth('actual_periode', $month)
            ->whereIn('status', $validStatuses)
            ->orderBy('actual_periode', 'desc')
            ->get();

        return $orders->map(function ($order) {
            // Get product names
            $productNames = $order->detailPreOrders->map(function ($detail) {
                if ($detail->type === 'promo' && $detail->promo) {
                    return $detail->promo->name;
                } elseif ($detail->type === 'product' && $detail->product) {
                    return $detail->product->name;
                }
                return 'Unknown';
            })->filter()->implode(', ');

            // Map status to Indonesian
            $statusMap = [
                'unpaid' => 'Belum Bayar',
                'processing' => 'Dikerjakan',
                'shipping' => 'Dikirim',
                'completed' => 'Selesai',
            ];

            return (object) [
                'id' => $order->id,
                'id_pesanan' => 'PO-' . str_pad($order->id, 5, '0', STR_PAD_LEFT),
                'nama_pelanggan' => $order->customer ? $order->customer->name : 'Unknown',
                'nama_produk' => $productNames ?: 'No products',
                'tanggal_pembelian' => $order->created_at->format('d/m/y'),
                'tanggal_pengantaran' => $order->actual_periode
                    ? $order->actual_periode->format('d/m/y')
                    : ($order->end_periode ? $order->end_periode->format('d/m/y') : '-'),
                'total_harga' => (int) $order->total,
                'status' => $statusMap[$order->status] ?? ucfirst($order->status),
            ];
        });
    }
}
