<?php

namespace App\Http\Controllers;

use App\Models\PreOrder;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $preOrders = PreOrder::query()
            ->with([
                'customer:id,name',
                'detailPreOrders.product:id,name,price',
                'detailPreOrders.promo:id,name,price',
            ])
            ->latest()
            ->get();

        $pesanan = $preOrders->map(function (PreOrder $preOrder) {
            return [
                'id' => $preOrder->id,
                'nama_pelanggan' => $preOrder->customer?->name,
                'nama_produk' => $preOrder->detailPreOrders
                    ->map(function ($detail) {
                        $item = $detail->type === 'promo' ? $detail->promo : $detail->product;

                        return $item?->name;
                    })
                    ->filter()
                    ->values()
                    ->implode(', '),
                'tanggal_pembelian' => $preOrder->created_at?->toDateString(),
                'tanggal_pengantaran' => $preOrder->actual_periode?->toDateString()
                    ?? $preOrder->end_periode?->toDateString(),
                'total_harga' => (int) $preOrder->total,
                'status' => $preOrder->status,
            ];
        })
            ->sortByDesc('tanggal_pembelian')
            ->take(5)
            ->values();

        $currentYear  = now()->year;
        $currentMonth = now()->month;

        $grafikData = $this->getGrafikPerHari($currentYear, $currentMonth);

        return view('pages.dashboard.index', compact('preOrders', 'pesanan', 'grafikData', 'currentYear', 'currentMonth'));
    }

    /**
     * API endpoint untuk data grafik berdasarkan filter bulan/tahun
     */
    public function grafikData(Request $request)
    {
        $year  = $request->input('year', now()->year);
        $month = $request->input('month'); // null = tampilkan per bulan dalam tahun

        if ($month) {
            $data = $this->getGrafikPerHari($year, $month);
        } else {
            $data = $this->getGrafikPerBulan($year);
        }

        return response()->json($data);
    }

    /**
     * Data pendapatan per hari dalam satu bulan berdasarkan actual_periode (tanggal slot).
     * 
     * Menggunakan actual_periode karena ini adalah tanggal pengantaran yang dipilih customer,
     * yang merepresentasikan kapan slot digunakan dan produk dikirim/diambil.
     */
    private function getGrafikPerHari(int $year, int $month): array
    {
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        // Query berdasarkan actual_periode (tanggal slot)
        $rows = PreOrder::selectRaw('DAY(actual_periode) as hari, SUM(total) as total')
            ->whereYear('actual_periode', $year)
            ->whereMonth('actual_periode', $month)
            ->whereNotNull('actual_periode')
            ->whereIn('status', ['unpaid', 'processing', 'shipping', 'completed']) // Exclude canceled
            ->groupBy('hari')
            ->orderBy('hari')
            ->pluck('total', 'hari');

        $labels = [];
        $values = [];
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $labels[] = $d;
            $values[] = (int) ($rows[$d] ?? 0);
        }

        return [
            'labels' => $labels,
            'values' => $values,
            'mode'   => 'harian',
            'title'  => 'Grafik Penjualan Per Hari — ' . $this->namaBulan($month) . ' ' . $year,
        ];
    }

    /**
     * Data pendapatan per bulan dalam satu tahun berdasarkan actual_periode (tanggal slot).
     * 
     * Menggunakan actual_periode karena ini adalah tanggal pengantaran yang dipilih customer,
     * yang merepresentasikan kapan slot digunakan dan produk dikirim/diambil.
     */
    private function getGrafikPerBulan(int $year): array
    {
        // Query berdasarkan actual_periode (tanggal slot)
        $rows = PreOrder::selectRaw('MONTH(actual_periode) as bulan, SUM(total) as total')
            ->whereYear('actual_periode', $year)
            ->whereNotNull('actual_periode')
            ->whereIn('status', ['unpaid', 'processing', 'shipping', 'completed']) // Exclude canceled
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('total', 'bulan');

        $namaBulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $labels = $namaBulan;
        $values = [];
        for ($m = 1; $m <= 12; $m++) {
            $values[] = (int) ($rows[$m] ?? 0);
        }

        return [
            'labels' => $labels,
            'values' => $values,
            'mode'   => 'bulanan',
            'title'  => 'Grafik Penjualan Per Bulan — ' . $year,
        ];
    }

    private function namaBulan(int $month): string
    {
        $list = [
            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        ];
        return $list[$month - 1] ?? '';
    }

    public function updateStatus(Request $request, $id)
    {
        return response()->json(['success' => true]);
    }
}
