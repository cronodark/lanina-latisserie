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

        // Month names
        $bulanList = [
            1=>'Januari', 2=>'Februari', 3=>'Maret', 4=>'April',
            5=>'Mei', 6=>'Juni', 7=>'Juli', 8=>'Agustus',
            9=>'September', 10=>'Oktober', 11=>'November', 12=>'Desember',
        ];

        $bulanNama = $bulanList[$bulanTerpilih];

        // Get data
        $dataBulanIni = $this->getSalesDataForMonth($tahunTerpilih, $bulanTerpilih);
        $produkTerlaris = $this->getTopSellingProducts($tahunTerpilih, $bulanTerpilih);
        $tabelPenjualan = $this->getTransactionTable($tahunTerpilih, $bulanTerpilih);

        // Prepare data for PDF
        $data = [
            'bulan' => $bulanNama,
            'tahun' => $tahunTerpilih,
            'totalPenjualan' => $dataBulanIni['totalPenjualan'],
            'totalPesanan' => $dataBulanIni['totalPesanan'],
            'totalProdukTerjual' => $dataBulanIni['totalProdukTerjual'],
            'produkTerlaris' => $produkTerlaris->toArray(),
            'tabelPenjualan' => $tabelPenjualan,
            'tanggalCetak' => now()->format('d/m/Y H:i'),
        ];

        // Generate PDF
        $pdf = Pdf::loadView('pages.laporan.pdf', $data);

        // Set paper size and orientation
        $pdf->setPaper('a4', 'portrait');

        // Download PDF
        $filename = 'Laporan_Penjualan_' . $bulanNama . '_' . $tahunTerpilih . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Get sales summary data for a specific month.
     *
     * @param int $year
     * @param int $month
     * @return array
     */
    private function getSalesDataForMonth(int $year, int $month): array
    {
        $validStatuses = ['processing', 'shipping', 'completed'];

        // Get orders for the month
        $orders = PreOrder::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->whereIn('status', $validStatuses)
            ->get();

        // Calculate total revenue
        $totalPenjualan = $orders->sum('total');

        // Count total orders
        $totalPesanan = $orders->count();

        // Count total products sold
        $totalProdukTerjual = PreOrderDetail::whereIn('pre_order_id', $orders->pluck('id'))
            ->sum('quantity');

        return [
            'totalPenjualan' => (int) $totalPenjualan,
            'totalPesanan' => $totalPesanan,
            'totalProdukTerjual' => (int) $totalProdukTerjual,
        ];
    }

    /**
     * Get top selling products for a specific month with percentage.
     *
     * @param int $year
     * @param int $month
     * @return \Illuminate\Support\Collection
     */
    private function getTopSellingProducts(int $year, int $month)
    {
        $validStatuses = ['processing', 'shipping', 'completed'];

        // Get order IDs for the month
        $orderIds = PreOrder::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->whereIn('status', $validStatuses)
            ->pluck('id');

        if ($orderIds->isEmpty()) {
            return collect();
        }

        // Get product sales count
        $productSales = PreOrderDetail::whereIn('pre_order_id', $orderIds)
            ->whereNotNull('product_id')
            ->select('product_id', DB::raw('SUM(quantity) as total_qty'))
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        $totalQty = $productSales->sum('total_qty');

        if ($totalQty === 0) {
            return collect();
        }

        // Get product details and calculate percentage
        $products = Product::whereIn('id', $productSales->pluck('product_id'))->get()->keyBy('id');

        $colors = ['#BB9457', '#C4A882', '#6B8F4E', '#A8C17A', '#D4C5A9'];

        return $productSales->map(function ($item, $index) use ($products, $totalQty, $colors) {
            $product = $products->get($item->product_id);
            $percentage = ($item->total_qty / $totalQty) * 100;

            return [
                'nama' => $product ? $product->name : 'Unknown',
                'persen' => round($percentage, 1),
                'warna' => $colors[$index] ?? '#CCCCCC',
                'gambar' => $product ? $product->image : null,
            ];
        });
    }

    /**
     * Get product sales count for chart.
     *
     * @param int $year
     * @param int $month
     * @return array
     */
    private function getProductSalesCount(int $year, int $month): array
    {
        $validStatuses = ['processing', 'shipping', 'completed'];

        // Get order IDs for the month
        $orderIds = PreOrder::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->whereIn('status', $validStatuses)
            ->pluck('id');

        if ($orderIds->isEmpty()) {
            return [];
        }

        // Get product sales count
        $productSales = PreOrderDetail::whereIn('pre_order_id', $orderIds)
            ->whereNotNull('product_id')
            ->select('product_id', DB::raw('SUM(quantity) as total_qty'))
            ->groupBy('product_id')
            ->get();

        // Get product names
        $products = Product::whereIn('id', $productSales->pluck('product_id'))->get()->keyBy('id');

        $result = [];
        foreach ($productSales as $item) {
            $product = $products->get($item->product_id);
            if ($product) {
                $result[$product->name] = (int) $item->total_qty;
            }
        }

        return $result;
    }

    /**
     * Get detailed transaction table for a specific month.
     *
     * @param int $year
     * @param int $month
     * @return \Illuminate\Support\Collection
     */
    private function getTransactionTable(int $year, int $month)
    {
        $validStatuses = ['unpaid', 'processing', 'shipping', 'completed'];

        $orders = PreOrder::with(['customer', 'detailPreOrders.product', 'detailPreOrders.promo'])
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->whereIn('status', $validStatuses)
            ->orderBy('created_at', 'desc')
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
                'id_pesanan' => 'ORD-' . str_pad($order->id, 3, '0', STR_PAD_LEFT),
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
