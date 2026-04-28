<?php

namespace App\Livewire;

use App\Models\PreOrder;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PreorderTabs extends Component
{
    public string $tab = 'belum-bayar';

    protected array $allowedTabs = ['belum-bayar', 'diproses', 'diantar', 'selesai'];

    protected $queryString = [
        'tab' => ['except' => 'belum-bayar'],
    ];

    public function mount(): void
    {
        $this->tab = $this->sanitizeTab($this->tab);
    }

    public function setTab(string $tab): void
    {
        $this->tab = $this->sanitizeTab($tab);
    }

    public function getOrdersProperty(): Collection
    {
        $userId = Auth::id();

        if (! $userId) {
            return collect();
        }

        return PreOrder::query()
            ->where('user_id', $userId)
            ->with([
                'detailPreOrders.product:id,name,description,price',
                'detailPreOrders.promo:id,name,description,price',
                'detailPreOrders.product.media',
                'detailPreOrders.promo.media',
            ])
            ->when($this->tab === 'belum-bayar', function ($query) {
                $query->where(function ($inner) {
                    $inner->where('payment_status', 'unpaid')
                        ->orWhereIn('status', ['pending', 'unpaid']);
                });
            })
            ->when($this->tab === 'diproses', function ($query) {
                $query->where('status', 'processing');
            })
            ->when($this->tab === 'diantar', function ($query) {
                $query->whereIn('status', ['shipping', 'diantar', 'delivering', 'siap_diambil', 'ready_pickup'])
                    ->orWhere(function ($inner) {
                        $inner->where('send_type', 'pickUp')
                            ->whereIn('status', ['siap_diambil', 'ready_pickup']);
                    });
            })
            ->when($this->tab === 'selesai', function ($query) {
                $query->whereIn('status', ['completed', 'selesai', 'done']);
            })
            ->latest()
            ->get();
    }

    public function pay(int $preOrderId)
    {
        $order = PreOrder::query()
            ->where('id', $preOrderId)
            ->where('user_id', Auth::id())
            ->first();

        if (! $order || ! $order->payment_redirect_url) {
            session()->flash('error', 'Link pembayaran tidak tersedia untuk pesanan ini.');

            return null;
        }

        return redirect()->away($order->payment_redirect_url);
    }

    public function cancel(int $preOrderId): void
    {
        $order = PreOrder::query()
            ->where('id', $preOrderId)
            ->where('user_id', Auth::id())
            ->where('payment_status', 'unpaid')
            ->first();

        if (! $order) {
            session()->flash('error', 'Pesanan tidak dapat dibatalkan.');

            return;
        }

        $order->update([
            'status' => 'cancelled',
            'payment_status' => 'cancelled',
        ]);

        session()->flash('success', 'Pesanan berhasil dibatalkan.');
    }

    public function confirmReceived(int $preOrderId): void
    {
        $order = PreOrder::query()
            ->where('id', $preOrderId)
            ->where('user_id', Auth::id())
            ->first();

        if (! $order) {
            session()->flash('error', 'Pesanan tidak ditemukan.');

            return;
        }

        $order->update([
            'status' => 'completed',
            'end_periode' => now()->toDateString(),
        ]);

        $this->tab = 'selesai';
        session()->flash('success', 'Pesanan berhasil dikonfirmasi.');
    }

    public function getSummaryProperty(): array
    {
        $counts = [
            'belum-bayar' => 0,
            'diproses' => 0,
            'diantar' => 0,
            'selesai' => 0,
        ];

        $orders = PreOrder::query()
            ->where('user_id', Auth::id())
            ->get(['status', 'payment_status', 'send_type']);

        foreach ($orders as $order) {
            if ($order->payment_status === 'unpaid' || in_array($order->status, ['pending', 'unpaid'], true)) {
                $counts['belum-bayar']++;
                continue;
            }

            if (in_array($order->status, ['processing', 'diproses', 'dikemas'], true)) {
                $counts['diproses']++;
                continue;
            }

            if (in_array($order->status, ['shipping', 'diantar', 'delivering', 'siap_diambil', 'ready_pickup'], true)) {
                $counts['diantar']++;
                continue;
            }

            if (in_array($order->status, ['completed', 'selesai', 'done'], true)) {
                $counts['selesai']++;
            }
        }

        return $counts;
    }

    public function orderTotal(PreOrder $order): int
    {
        return $order->detailPreOrders->sum(function ($detail) {
            $item = $detail->type === 'promo' ? $detail->promo : $detail->product;
            $price = (int) ($item->price ?? 0);

            return $price * (int) $detail->quantity;
        });
    }

    public function orderTitle(PreOrder $order): string
    {
        $firstItem = $order->detailPreOrders
            ->map(fn ($detail) => $this->resolveDetailItem($detail))
            ->filter()
            ->first();

        if ($firstItem && ! empty($firstItem->name)) {
            return $firstItem->name;
        }

        return 'Pre Order #'.$order->id;
    }

    public function orderDescription(PreOrder $order): string
    {
        $names = $order->detailPreOrders
            ->map(function ($detail) {
                $item = $detail->type === 'promo' ? $detail->promo : $detail->product;

                return $item->name ?? null;
            })
            ->filter()
            ->take(3)
            ->values();

        if ($names->isEmpty()) {
            return 'Detail produk tidak tersedia.';
        }

        $suffix = $order->detailPreOrders->count() > 3 ? ' dan lainnya' : '';

        return $names->implode(', ').$suffix;
    }

    public function orderImage(PreOrder $order): string
    {
        $firstDetail = $order->detailPreOrders->sortBy('id')->first();

        if ($firstDetail) {
            $firstItem = $this->resolveDetailItem($firstDetail);

            if ($firstItem && ! empty($firstItem->image)) {
                return (string) $firstItem->image;
            }
        }

        return 'https://images.unsplash.com/photo-1542838132-92c53300491e?w=400&h=300&fit=crop';
    }

    public function orderQty(PreOrder $order): int
    {
        return (int) $order->detailPreOrders->sum('quantity');
    }

    public function tabTitle(): string
    {
        return match ($this->tab) {
            'belum-bayar' => 'Belum Bayar',
            'diproses' => 'Diproses',
            'diantar' => 'Diantar (termasuk Siap Diambil)',
            'selesai' => 'Selesai',
            default => 'Pesanan Saya',
        };
    }

    private function sanitizeTab(string $tab): string
    {
        return in_array($tab, $this->allowedTabs, true) ? $tab : 'belum-bayar';
    }

    private function resolveDetailItem($detail)
    {
        return $detail->type === 'promo' ? $detail->promo : $detail->product;
    }

    public function render(): View
    {
        return view('livewire.preorder-tabs');
    }
}
