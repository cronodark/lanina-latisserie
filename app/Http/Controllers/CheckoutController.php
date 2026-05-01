<?php

namespace App\Http\Controllers;

use App\Models\PreOrder;
use App\Models\PreOrderDetail;
use App\Models\Product;
use App\Models\Promo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;
use Throwable;

class CheckoutController extends Controller
{
    public function index(): View|RedirectResponse
    {
        $checkedCart = $this->getCheckedCartItems();

        if ($checkedCart->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Pilih minimal 1 produk untuk checkout.');
        }

        [$items, $total] = $this->buildCheckoutItems($checkedCart);

        if (empty($items)) {
            return redirect()->route('cart.index')
                ->with('error', 'Produk yang dipilih tidak tersedia.');
        }

        $addresses = Auth::user()->address()->get();
        $pickupDate = now()->addDays(2)->toDateString();

        return view('pages.checkout.index', [
            'items' => $items,
            'addresses' => $addresses,
            'grandTotal' => $total,
            'pickupDate' => $pickupDate,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $checkedCart = $this->getCheckedCartItems();

        if ($checkedCart->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Pilih minimal 1 item untuk checkout.');
        }

        [$items, $total] = $this->buildCheckoutItems($checkedCart);

        if (empty($items)) {
            return redirect()->route('cart.index')
                ->with('error', 'Item checkout tidak valid. Silakan pilih ulang item keranjang.');
        }

        $validated = $request->validate([
            'address_id' => [
                'nullable',
                'integer',
                Rule::exists('addresses', 'id')->where(fn ($query) => $query->where('user_id', Auth::id())),
            ],
            'send_type' => 'required|in:pickUp,kurirEkspedisi,kurirToko',
            'actual_periode' => ['nullable', 'date'],
        ]);

        $preOrder = null;

        DB::transaction(function () use ($items, $total, $validated, &$preOrder) {
            $preOrder = PreOrder::create([
                'actual_periode' => $validated['actual_periode'] ?? now()->addDays(2)->toDateString(),
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'start_periode' => now()->toDateString(),
                'end_periode' => null,
                'total' => $total,
                'send_type' => $validated['send_type'] ?? 'pickUp',
                'tracking_number' => null,
                'choosen_expedition' => null,
                'address_id' => $validated['address_id'] ?? null,
                'user_id' => Auth::id(),
            ]);

            foreach ($items as $item) {
                PreOrderDetail::create([
                    'quantity' => $item['qty'],
                    'type' => $item['type'],
                    'product_id' => $item['type'] === 'product' ? $item['id'] : null,
                    'promo_id' => $item['type'] === 'promo' ? $item['id'] : null,
                    'pre_order_id' => $preOrder->id,
                ]);
            }
        });

        if (! $preOrder instanceof PreOrder) {
            return redirect()->route('checkout.index')
                ->with('error', 'Gagal memproses checkout. Silakan coba lagi.');
        }

        try {
            $paymentResponse = $this->createMidtransPayment($preOrder, $items);

            $preOrder->update([
                'payment_method' => 'midtrans',
                'midtrans_order_id' => $paymentResponse['order_id'],
                'midtrans_transaction_id' => $paymentResponse['transaction_id'] ?? null,
                'payment_redirect_url' => $paymentResponse['redirect_url'],
            ]);
        } catch (Throwable $throwable) {
            if ($preOrder) {
                $preOrder->delete();
            }

            report($throwable);

            return redirect()->route('checkout.index')
                ->with('error', 'Gagal membuat transaksi Midtrans. Silakan coba lagi.');
        }

        $sessionKey = $this->sessionKey();
        $cart = session()->get($sessionKey, []);

        foreach ($checkedCart->keys() as $cartKey) {
            unset($cart[$cartKey]);
        }

        session()->put($sessionKey, $cart);

        return redirect()->away($preOrder->payment_redirect_url)
            ->with('success', 'Checkout berhasil dibuat. Silakan selesaikan pembayaran di Midtrans.');
    }

    public function paymentFinish(PreOrder $preOrder): RedirectResponse
    {
        if ($preOrder->user_id !== Auth::id()) {
            return redirect()->route('cart.index')
                ->with('error', 'Pesanan tidak ditemukan.');
        }

        try {
            $this->syncPaymentStatusFromMidtrans($preOrder);
        } catch (Throwable $throwable) {
            report($throwable);
        }

        $preOrder->refresh();

        $message = $preOrder->payment_status === 'paid'
            ? 'Pembayaran terverifikasi. Pesanan Anda sedang diproses.'
            : 'Pesanan berhasil dibuat. Pembayaran belum terverifikasi, silakan selesaikan pembayaran dari halaman pesanan.';

        return redirect()->route('profile.preorder.index')
            ->with('success', $message);
    }

    private function sessionKey(): string
    {
        return 'cart_user_'.Auth::id();
    }

    private function getCheckedCartItems()
    {
        return collect(session()->get($this->sessionKey(), []))
            ->filter(fn (array $item) => (bool) ($item['checked'] ?? false));
    }

    private function buildCheckoutItems($checkedCart): array
    {
        $productIds = $checkedCart
            ->filter(fn (array $item) => ($item['type'] ?? 'product') === 'product')
            ->map(fn (array $item, $cartKey) => (int) ($item['item_id'] ?? (is_numeric($cartKey) ? $cartKey : 0)))
            ->filter()
            ->unique()
            ->values()
            ->all();

        $promoIds = $checkedCart
            ->filter(fn (array $item) => ($item['type'] ?? 'product') === 'promo')
            ->map(fn (array $item, $cartKey) => (int) ($item['item_id'] ?? (is_numeric($cartKey) ? $cartKey : 0)))
            ->filter()
            ->unique()
            ->values()
            ->all();

        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');
        $promos = Promo::whereIn('id', $promoIds)->get()->keyBy('id');

        $items = [];
        $total = 0;

        foreach ($checkedCart as $cartKey => $item) {
            $type = $item['type'] ?? 'product';
            $itemId = (int) ($item['item_id'] ?? (is_numeric($cartKey) ? $cartKey : 0));
            $qty = max(1, (int) ($item['qty'] ?? 1));

            if ($type === 'promo') {
                $model = $promos->get($itemId);
            } else {
                $type = 'product';
                $model = $products->get($itemId);
            }

            if (! $model) {
                continue;
            }

            $lineTotal = ((int) $model->price) * $qty;

            $items[] = [
                'key' => (string) $cartKey,
                'id' => $model->id,
                'type' => $type,
                'name' => $model->name,
                'desc' => $model->description,
                'qty' => $qty,
                'total' => $lineTotal,
            ];

            $total += $lineTotal;
        }

        return [$items, $total];
    }

    /**
     * @param  array<int, array<string, mixed>>  $items
     * @return array{order_id: string, redirect_url: string, transaction_id: string|null}
     */
    private function createMidtransPayment(PreOrder $preOrder, array $items): array
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = (bool) config('midtrans.is_production');
        Config::$isSanitized = (bool) config('midtrans.is_sanitized');
        Config::$is3ds = (bool) config('midtrans.is_3ds');

        $orderId = 'PO-'.$preOrder->id.'-'.now()->format('YmdHis');
        $grossAmount = 0;

        $itemDetails = [];

        foreach ($items as $item) {
            $unitPrice = intdiv((int) $item['total'], max(1, (int) $item['qty']));
            $grossAmount += (int) $item['total'];

            $itemDetails[] = [
                'id' => $item['type'].'-'.$item['id'],
                'price' => $unitPrice,
                'quantity' => (int) $item['qty'],
                'name' => Str::limit((string) $item['name'], 50, ''),
            ];
        }

        $payload = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $grossAmount,
            ],
            'item_details' => $itemDetails,
            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'phone' => Auth::user()->telp,
            ],
            'callbacks' => [
                'finish' => url('/checkout/payment/'.$preOrder->id.'/finish'),
                'error' => url('/checkout/payment/'.$preOrder->id.'/finish'),
                'pending' => url('/checkout/payment/'.$preOrder->id.'/finish'),
            ],
        ];

        $response = Snap::createTransaction($payload);

        return [
            'order_id' => $orderId,
            'redirect_url' => $response->redirect_url,
            'transaction_id' => $response->transaction_id ?? null,
        ];
    }

    private function syncPaymentStatusFromMidtrans(PreOrder $preOrder): void
    {
        if (! $preOrder->midtrans_order_id) {
            return;
        }

        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = (bool) config('midtrans.is_production');
        Config::$isSanitized = (bool) config('midtrans.is_sanitized');
        Config::$is3ds = (bool) config('midtrans.is_3ds');

        $status = Transaction::status($preOrder->midtrans_order_id);
        $statusData = is_array($status) ? $status : (array) $status;
        $mappedStatus = $this->mapMidtransStatus(
            (string) ($statusData['transaction_status'] ?? ''),
            (string) ($statusData['fraud_status'] ?? '')
        );

        $updates = [
            'payment_status' => $mappedStatus,
            'payment_method' => $statusData['payment_type'] ?? $preOrder->payment_method,
            'midtrans_transaction_id' => $statusData['transaction_id'] ?? $preOrder->midtrans_transaction_id,
        ];

        if ($mappedStatus === 'paid') {
            $updates['status'] = 'processing';
            $updates['paid_at'] = $preOrder->paid_at ?? now();
        } elseif (in_array($mappedStatus, ['expired', 'failed', 'cancelled', 'refunded'], true)) {
            $updates['status'] = 'cancelled';
        }

        $preOrder->update($updates);
    }

    private function mapMidtransStatus(string $transactionStatus, string $fraudStatus): string
    {
        return match ($transactionStatus) {
            'settlement' => 'paid',
            'capture' => $fraudStatus === 'accept' ? 'paid' : 'unpaid',
            'pending' => 'unpaid',
            'expire' => 'expired',
            'deny' => 'failed',
            'cancel' => 'cancelled',
            'refund', 'partial_refund', 'chargeback' => 'refunded',
            default => 'unpaid',
        };
    }
}
