<?php

namespace App\Http\Controllers;

use App\Models\PreOrder;
use Illuminate\Http\Request;

class PesananController extends Controller
{
    public function index()
    {
        $query = PreOrder::query();

        // Filter berdasarkan status query parameter
        $filterStatus = request('status');
        if ($filterStatus) {
            // Normalisasi status dari query parameter
            $normalizedStatus = $this->normalizeStatus($filterStatus);
            if ($normalizedStatus) {
                $query->where('status', $normalizedStatus);
            }
        }

        $orders = $query
            ->with([
                'customer:id,name,email,telp',
                'address:id,street,district,city,state,zip_code,rt,rw,notes',
                'detailPreOrders.product:id,name',
                'detailPreOrders.promo:id,name',
            ])
            ->orderByDesc('id')
            ->paginate(10)
            ->through(function (PreOrder $order) {
                $productNames = $order->detailPreOrders
                    ->map(function ($detail) {
                        $item = $detail->type === 'promo' ? $detail->promo : $detail->product;

                        return $item?->name;
                    })
                    ->filter()
                    ->values();

                $detailProduk = $order->detailPreOrders
                    ->map(function ($detail) {
                        $item = $detail->type === 'promo' ? $detail->promo : $detail->product;

                        return [
                            'nama' => $item?->name ?? 'Item tidak tersedia',
                            'jumlah' => (int) $detail->quantity,
                            'tipe' => $detail->type,
                        ];
                    })
                    ->values()
                    ->all();

                $tanggalPembelian = $this->formatDateString($order->created_at);
                $tanggalPengantaran = $this->formatDateString($order->actual_periode)
                    ?? $this->formatDateString($order->end_periode);

                return (object) [
                    'id' => $order->id,
                    'id_pesanan' => 'PO-' . str_pad((string) $order->id, 5, '0', STR_PAD_LEFT),
                    'nama_pelanggan' => $order->customer?->name ?? '-',
                    'nama_produk' => $productNames->isNotEmpty() ? $productNames->implode(', ') : '-',
                    'tanggal_pembelian' => $tanggalPembelian,
                    'tanggal_pengantaran' => $tanggalPengantaran,
                    'total_harga' => (int) $order->total,
                    'status' => $order->status,
                    'status_filter' => $this->statusFilterValue($order->status),
                    'status_label' => $this->statusLabel($order->status),
                    'nomor_telepon' => $order->customer?->telp ?? '-',
                    'email' => $order->customer?->email ?? '-',
                    'metode_pengiriman' => $this->sendTypeLabel($order->send_type),
                    'send_type' => $order->send_type,
                    'alamat' => $this->formatAddress($order),
                    'catatan_alamat' => $order->address?->notes ?? '-',
                    'metode_pembayaran' => $this->paymentMethodLabel($order->payment_method),
                    'status_pembayaran' => $this->paymentStatusLabel($order->status),
                    'nomor_resi' => $order->tracking_number ?? '-',
                    'detail_produk' => $detailProduk,
                ];
            });

        return view('pages.pesanan-admin.index', [
            'title' => 'Pesanan',
            'orders' => $orders,
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => ['required', 'string'],
            'send_type' => ['required', 'string'],
            'nomor_resi' => ['nullable', 'string', 'max:50'],
        ]);

        $status = $this->normalizeStatus($validated['status']);
        $sendType = $this->normalizeSendType($validated['send_type']);

        if (! $status) {
            return response()->json([
                'success' => false,
                'message' => 'Status tidak valid.',
            ], 422);
        }

        if (! $sendType) {
            return response()->json([
                'success' => false,
                'message' => 'Tipe pengiriman tidak valid.',
            ], 422);
        }

        // Hanya bisa ubah dari processing ke shipping
        $order = PreOrder::findOrFail($id);
        if ($order->status !== 'processing' || $status !== 'shipping') {
            return response()->json([
                'success' => false,
                'message' => 'Status hanya bisa diubah dari "processing" ke "shipping".',
            ], 422);
        }

        // Update status, send_type dan nomor resi jika ada
        $updateData = [
            'status' => $status,
            'send_type' => $sendType,
        ];

        if (!empty($validated['nomor_resi'])) {
            $updateData['tracking_number'] = $validated['nomor_resi'];
        }

        $order->update($updateData);

        return response()->json([
            'success' => true,
            'status' => $status,
            'status_label' => $this->statusLabel($status),
            'send_type' => $sendType,
            'tracking_number' => $order->tracking_number,
        ]);
    }

    public function edit($id)
    {
        $order = PreOrder::query()
            ->with([
                'customer:id,name,email,telp',
                'address:id,street,district,city,state,zip_code,rt,rw,notes',
                'detailPreOrders.product:id,name,price',
                'detailPreOrders.promo:id,name,price',
            ])
            ->findOrFail($id);

        $pesanan = (object) [
            'id' => $order->id,
            'id_pesanan' => 'PO-' . str_pad((string) $order->id, 5, '0', STR_PAD_LEFT),
            'nama_pelanggan' => $order->customer?->name ?? '-',
            'nomor_telepon' => $order->customer?->telp ?? '',
            'email' => $order->customer?->email ?? '',
            'tanggal_pembelian' => $this->formatDateString($order->created_at),
            'tanggal_pengantaran' => $this->formatDateString($order->actual_periode) ?? $this->formatDateString($order->end_periode),
            'total_harga' => (int) $order->total,
            'status' => $this->statusLabel($order->status),
            'metode_pembayaran' => $this->paymentMethodLabel($order->payment_method),
            'status_pembayaran' => $this->paymentStatusLabel($order->status),
            'metode_pengiriman' => $this->sendTypeLabel($order->send_type),
            'alamat' => $this->formatAddress($order),
            'catatan_alamat' => $order->address?->notes ?? '',
            'nomor_resi' => $order->tracking_number ?? '',
            'produk' => $order->detailPreOrders->map(function ($detail) {
                $item = $detail->type === 'promo' ? $detail->promo : $detail->product;

                return (object) [
                    'nama' => $item?->name ?? 'Item tidak tersedia',
                    'jumlah' => (int) $detail->quantity,
                    'total' => (int) ($item?->price ?? 0) * (int) $detail->quantity,
                ];
            })->all(),
        ];

        return view('pages.pesanan-admin.edit', compact('pesanan'));
    }

    public function show($id)
    {
        return redirect()->route('pesanan.edit', $id);
    }

    public function destroy($id)
    {
        $order = PreOrder::findOrFail($id);
        $order->delete();

        return redirect()->route('pesanan.index')->with('success', 'Pesanan berhasil dihapus');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => ['required', 'string'],
            'tanggal_pengantaran' => ['nullable', 'date'],
            'metode_pembayaran' => ['nullable', 'string', 'max:30'],
            'metode_pengiriman' => ['nullable', 'string', 'max:50'],
            'nomor_resi' => ['nullable', 'string', 'max:50'],
        ]);

        $status = $this->normalizeStatus($validated['status']);

        if (! $status) {
            return back()->withErrors(['status' => 'Status tidak valid.'])->withInput();
        }

        $order = PreOrder::findOrFail($id);
        $order->update([
            'status' => $status,
            'actual_periode' => $validated['tanggal_pengantaran'] ?? $order->actual_periode,
            'payment_method' => $validated['metode_pembayaran'] ?? $order->payment_method,
            'send_type' => $this->normalizeSendType($validated['metode_pengiriman'] ?? null) ?? $order->send_type,
            'tracking_number' => $validated['nomor_resi'] ?? $order->tracking_number,
        ]);

        return redirect()->route('pesanan.index')->with('success', 'Pesanan berhasil diupdate');
    }

    /**
     * Normalizes status input to one of 5 valid statuses:
     * - unpaid: Belum (pembayaran belum diterima)
     * - processing: Dikerjakan (sedang dikerjakan)
     * - shipping: Dikirim (dalam pengiriman)
     * - completed: Selesai (pesanan selesai)
     * - canceled: Dibatalkan (pesanan dibatalkan)
     */
    private function normalizeStatus(?string $status): ?string
    {
        if (! $status) {
            return null;
        }

        $normalized = strtolower(trim($status));

        return match ($normalized) {
            'belum', 'unpaid' => 'unpaid',
            'dikerjakan', 'processing' => 'processing',
            'dikirim', 'shipping' => 'shipping',
            'selesai', 'completed' => 'completed',
            'dibatalkan', 'cancelled', 'canceled' => 'canceled',
            default => null,
        };
    }

    /**
     * Maps status code to Indonesian label.
     * Status codes:
     * - unpaid: Belum (pembayaran belum diterima)
     * - processing: Dikerjakan (sedang dikerjakan)
     * - shipping: Dikirim (dalam pengiriman)
     * - completed: Selesai (pesanan selesai)
     * - canceled: Dibatalkan (pesanan dibatalkan)
     */
    private function statusLabel(?string $status): string
    {
        return match ($status) {
            'processing' => 'Dikerjakan',
            'shipping' => 'Dikirim',
            'completed' => 'Selesai',
            'canceled' => 'Dibatalkan',
            default => 'Belum',
        };
    }

    /**
     * Determines payment status label based on order status.
     * Status lunas (Paid): processing, shipping, completed
     * Status belum lunas (Unpaid): unpaid, canceled
     */
    private function paymentStatusLabel(?string $status): string
    {
        return in_array($status, ['processing', 'shipping', 'completed'], true)
            ? 'Lunas'
            : 'Belum Lunas';
    }

    /**
     * Maps status for filtering. Returns normalized filter value:
     * - processing: Untuk filter
     * - shipping: Untuk filter
     * - completed: Untuk filter
     * - canceled: Untuk filter dibatalkan
     * - default (unpaid): Untuk status belum
     */
    private function statusFilterValue(?string $status): string
    {
        return match ($status) {
            'processing' => 'processing',
            'shipping' => 'shipping',
            'completed' => 'completed',
            'canceled' => 'canceled',
            default => 'unpaid',
        };
    }

    private function sendTypeLabel(?string $sendType): string
    {
        return match ($sendType) {
            'pickUp' => 'Ambil Sendiri',
            'kurirEkspedisi' => 'Kurir Ekspedisi',
            'kurirToko' => 'Kurir Toko',
            default => '-',
        };
    }

    private function paymentMethodLabel(?string $paymentMethod): string
    {
        if (! $paymentMethod) {
            return '-';
        }

        $normalized = str_replace(['_', '-'], ' ', strtolower(trim($paymentMethod)));

        return ucwords(preg_replace('/\s+/', ' ', $normalized));
    }

    private function formatDateString($date): ?string
    {
        if (! $date) {
            return null;
        }

        return \Carbon\Carbon::parse($date)->toDateString();
    }

    private function normalizeSendType(?string $sendType): ?string
    {
        if (! $sendType) {
            return null;
        }

        $normalized = strtolower(trim($sendType));

        return match ($normalized) {
            'pickup', 'pick up', 'ambil sendiri' => 'pickUp',
            'kurir ekspedisi', 'ekspedisi', 'kurirekspedisi' => 'kurirEkspedisi',
            'kurir toko', 'kurirtoko' => 'kurirToko',
            default => null,
        };
    }

    private function formatAddress(PreOrder $order): string
    {
        if (! $order->address) {
            return '-';
        }

        $parts = array_filter([
            $order->address->street,
            $order->address->district,
            $order->address->city,
            $order->address->state,
            $order->address->zip_code,
        ]);

        $address = implode(', ', $parts);

        if ($order->address->rt && $order->address->rw) {
            $address .= ' (RT ' . $order->address->rt . '/RW ' . $order->address->rw . ')';
        }

        return $address ?: '-';
    }
}
