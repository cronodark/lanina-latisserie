<?php

namespace App\Http\Controllers;

use App\Models\PreOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Hanya transaksi yang sudah dibayar
        $preOrders = PreOrder::where('payment_status', 'paid')->get();

        $currentYear  = now()->year;
        $currentMonth = now()->month;

        // Data grafik default: per hari dalam bulan berjalan (hanya yang sudah dibayar)
        $grafikData = $this->getGrafikPerHari($currentYear, $currentMonth);

        return view('pages.dashboard.index', compact('preOrders', 'grafikData', 'currentYear', 'currentMonth'));
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
     * Data pendapatan per hari dalam satu bulan
     */
    private function getGrafikPerHari(int $year, int $month): array
    {
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        $rows = PreOrder::selectRaw('DAY(created_at) as hari, SUM(total) as total')
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->whereNotNull('created_at')
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
     * Data pendapatan per bulan dalam satu tahun
     */
    private function getGrafikPerBulan(int $year): array
    {
        $rows = PreOrder::selectRaw('MONTH(created_at) as bulan, SUM(total) as total')
            ->whereYear('created_at', $year)
            ->whereNotNull('created_at')
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
