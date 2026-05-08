<?php

namespace App\Http\Controllers;

use App\Models\PreOrder;
use App\Models\TanggalTersedia;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TanggalController extends Controller
{
    /**
     * Display calendar view with orders grouped by date.
     */
    public function kalender(Request $request)
    {
        $bulan = (int) $request->get('bulan', now()->month);
        $tahun = (int) $request->get('tahun', now()->year);

        // Get all orders for the selected month
        $semuaPesanan = PreOrder::with(['customer', 'detailPreOrders.product', 'detailPreOrders.promo'])
            ->whereYear('actual_periode', $tahun)
            ->whereMonth('actual_periode', $bulan)
            ->whereIn('status', ['unpaid', 'paid', 'processing', 'shipping', 'completed'])
            ->get()
            ->map(function ($order) {
                // Get product names
                $items = $order->detailPreOrders->map(function ($detail) {
                    $item = $detail->type === 'promo' ? $detail->promo : $detail->product;
                    return [
                        'produk' => $item ? $item->name : 'Unknown',
                        'quantity' => $detail->quantity,
                        'type' => $detail->type,
                    ];
                })->toArray();

                return [
                    'id' => $order->id,
                    'tanggal' => $order->actual_periode->format('Y-m-d'),
                    'customer' => $order->customer ? $order->customer->name : 'Unknown',
                    'email' => $order->customer ? $order->customer->email : '-',
                    'telp' => $order->customer ? $order->customer->telp : '-',
                    'status' => $order->status,
                    'send_type' => $order->send_type,
                    'total' => (int) $order->total,
                    'items' => $items,
                ];
            });

        // Get slot data for the month
        $tanggalTersedia = TanggalTersedia::whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->get()
            ->keyBy(function ($item) {
                return $item->tanggal->format('Y-m-d');
            })
            ->map(function ($slot) {
                return [
                    'id' => $slot->id,
                    'kuota' => $slot->kuota,
                    'terisi' => $slot->terisi,
                    'sisa' => $slot->sisa_kuota,
                    'status' => $slot->status,
                    'is_aktif' => $slot->is_aktif,
                    'keterangan' => $slot->keterangan,
                ];
            });

        $pesananPerTanggal = $semuaPesanan->groupBy('tanggal');

        $totalPesanan = $semuaPesanan->count();
        $slotTersedia = $tanggalTersedia->where('is_aktif', true)->count();
        $slotPenuh = $tanggalTersedia->where('status', 'Penuh')->count();
        $tanggalRamai = $pesananPerTanggal->sortByDesc(fn($v) => $v->count())->keys()->first();
        $tanggalRamaiFmt = $tanggalRamai ? Carbon::parse($tanggalRamai)->format('d M') : '-';

        return view('pages.jadwal-admin.kalender', compact(
            'bulan', 'tahun', 'pesananPerTanggal', 'semuaPesanan', 'tanggalTersedia',
            'totalPesanan', 'slotTersedia', 'slotPenuh', 'tanggalRamaiFmt'
        ));
    }
}
