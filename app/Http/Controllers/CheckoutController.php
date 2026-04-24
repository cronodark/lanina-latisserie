<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function index(): View|RedirectResponse
    {
        $sessionKey = 'cart_user_'.Auth::id();
        $cart = session()->get($sessionKey, []);

        $addresses = User::find(Auth::id())->address()->get();

        $checkedCart = collect($cart)
            ->filter(fn (array $item) => (bool) ($item['checked'] ?? false));

        if ($checkedCart->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Pilih minimal 1 produk untuk checkout.');
        }

        $products = Product::whereIn('id', $checkedCart->keys()->all())
            ->get()
            ->keyBy('id');

        $items = [];
        $total = 0;

        foreach ($checkedCart as $productId => $item) {
            if (! $products->has((int) $productId)) {
                continue;
            }

            $product = $products[(int) $productId];
            $qty = (int) ($item['qty'] ?? 0);
            $lineTotal = $product->price * $qty;

            $items[] = [
                'id' => $product->id,
                'name' => $product->name,
                'desc' => $product->description,
                'qty' => $qty,
                'total' => $lineTotal,
            ];

            $total += $lineTotal;
        }

        if (empty($items)) {
            return redirect()->route('cart.index')
                ->with('error', 'Produk yang dipilih tidak tersedia.');
        }

        return view('pages.checkout.index', [
            'items' => $items,
            'addresses' => $addresses,
            'grandTotal' => $total,
        ]);
    }
}
