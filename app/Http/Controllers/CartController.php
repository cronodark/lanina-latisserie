<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Promo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function storeProduct(Request $request, Product $product): RedirectResponse
    {
        return $this->addToCart($request, 'product', $product->id);
    }

    public function storePromo(Request $request, Promo $promo): RedirectResponse
    {
        return $this->addToCart($request, 'promo', $promo->id);
    }

    private function addToCart(Request $request, string $type, int $itemId): RedirectResponse
    {
        $validated = $request->validate([
            'qty' => ['nullable', 'integer', 'min:1', 'max:99'],
        ]);

        $qty = (int) ($validated['qty'] ?? 1);
        $sessionKey = 'cart_user_'.Auth::id();
        $cart = session()->get($sessionKey, []);
        $cartKey = $type.':'.$itemId;

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['qty'] += $qty;
        } else {
            $cart[$cartKey] = [
                'type' => $type,
                'item_id' => $itemId,
                'qty' => $qty,
                'checked' => false,
            ];
        }

        session()->put($sessionKey, $cart);

        return redirect()->back()
            ->with('success', 'Item berhasil ditambahkan ke keranjang.');
    }
}
