<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TanggalTersedia;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TanggalTersediaController extends Controller
{
    /**
     * Get available dates for pre-order.
     * Returns dates that are active and have available slots.
     */
    public function index(Request $request): JsonResponse
    {
        $startDate = $request->get('start_date', now()->addDay()->toDateString());
        $endDate = $request->get('end_date', now()->addMonths(2)->toDateString());

        $tanggalTersedia = TanggalTersedia::whereBetween('tanggal', [$startDate, $endDate])
            ->aktif()
            ->mendatang()
            ->orderBy('tanggal')
            ->get()
            ->map(function ($slot) {
                return [
                    'id' => $slot->id,
                    'tanggal' => $slot->tanggal->format('Y-m-d'),
                    'tanggal_display' => $slot->tanggal->translatedFormat('d F Y'),
                    'kuota' => $slot->kuota,
                    'terisi' => $slot->terisi,
                    'sisa' => $slot->sisa_kuota,
                    'status' => $slot->status,
                    'is_available' => $slot->sisa_kuota > 0 && $slot->is_aktif,
                    'keterangan' => $slot->keterangan,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $tanggalTersedia,
        ]);
    }

    /**
     * Check if a specific date is available.
     */
    public function check(Request $request): JsonResponse
    {
        $tanggal = $request->get('tanggal');

        if (!$tanggal) {
            return response()->json([
                'success' => false,
                'message' => 'Tanggal harus diisi.',
            ], 422);
        }

        $slot = TanggalTersedia::where('tanggal', $tanggal)->first();

        if (!$slot) {
            return response()->json([
                'success' => false,
                'available' => false,
                'message' => 'Tanggal tidak tersedia untuk pre-order.',
            ]);
        }

        $isAvailable = $slot->is_aktif && $slot->sisa_kuota > 0;

        return response()->json([
            'success' => true,
            'available' => $isAvailable,
            'data' => [
                'tanggal' => $slot->tanggal->format('Y-m-d'),
                'tanggal_display' => $slot->tanggal->translatedFormat('d F Y'),
                'kuota' => $slot->kuota,
                'terisi' => $slot->terisi,
                'sisa' => $slot->sisa_kuota,
                'status' => $slot->status,
                'keterangan' => $slot->keterangan,
            ],
            'message' => $isAvailable 
                ? 'Tanggal tersedia untuk pre-order.' 
                : 'Tanggal tidak tersedia (slot penuh atau nonaktif).',
        ]);
    }
}
