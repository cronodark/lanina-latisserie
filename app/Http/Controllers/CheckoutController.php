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
use Illuminate\Validation\Rule;
use Illuminate\View\View;

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

        [$items] = $this->buildCheckoutItems($checkedCart);

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

        DB::transaction(function () use ($items, $validated) {
            $preOrder = PreOrder::create([
                'actual_periode' => $validated['actual_periode'] ?? now()->addDays(2)->toDateString(),
                'status' => 'pending',
                'start_periode' => now()->toDateString(),
                'end_periode' => null,
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

        $sessionKey = $this->sessionKey();
        $cart = session()->get($sessionKey, []);

        foreach ($checkedCart->keys() as $cartKey) {
            unset($cart[$cartKey]);
        }

        session()->put($sessionKey, $cart);

        return redirect()->route('cart.index')
            ->with('success', 'Checkout berhasil dibuat. Pesanan Anda sedang diproses.');
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
}
