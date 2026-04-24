<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function store(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'qty' => ['nullable', 'integer', 'min:1'],
        ]);

        $qty = (int) ($validated['qty'] ?? 1);
        $sessionKey = 'cart_user_'.Auth::id();
        $cart = session()->get($sessionKey, []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['qty'] += $qty;
        } else {
            $cart[$product->id] = [
                'qty' => $qty,
                'checked' => false,
            ];
        }

        session()->put($sessionKey, $cart);

        return redirect()->back()
            ->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }
}
