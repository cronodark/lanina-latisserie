<?php

namespace App\Livewire;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Cart extends Component
{
    public array $cart = [];         // ['product_id' => ['qty' => int, 'checked' => bool]]

    public array $selected = [];     // product_ids that are checked

    public string $selectedDate = '';

    public function mount(): void
    {
        $sessionCart = session()->get($this->sessionKey(), []);
        $this->cart = $sessionCart;
        $this->selected = array_keys(array_filter($sessionCart, fn ($i) => $i['checked'] ?? false));
    }

    private function sessionKey(): string
    {
        return 'cart_user_'.Auth::id();
    }

    private function persist(): void
    {
        session()->put($this->sessionKey(), $this->cart);
    }

    public function addItem(int $productId): void
    {
        if (isset($this->cart[$productId])) {
            $this->cart[$productId]['qty']++;
        } else {
            $this->cart[$productId] = ['qty' => 1, 'checked' => false];
        }
        $this->persist();
    }

    public function increment(int $productId): void
    {
        if (isset($this->cart[$productId])) {
            $this->cart[$productId]['qty']++;
            $this->persist();
        }
    }

    public function decrement(int $productId): void
    {
        if (isset($this->cart[$productId])) {
            if ($this->cart[$productId]['qty'] > 1) {
                $this->cart[$productId]['qty']--;
            } else {
                unset($this->cart[$productId]);
                $this->selected = array_values(array_filter($this->selected, fn ($id) => $id != $productId));
            }
            $this->persist();
        }
    }

    public function removeItem(int $productId): void
    {
        unset($this->cart[$productId]);
        $this->selected = array_values(array_filter($this->selected, fn ($id) => $id != $productId));
        $this->persist();
    }

    public function toggleItem(int $productId): void
    {
        if (isset($this->cart[$productId])) {
            $this->cart[$productId]['checked'] = ! ($this->cart[$productId]['checked'] ?? false);
            $this->persist();
        }
        $this->selected = array_keys(array_filter($this->cart, fn ($i) => $i['checked'] ?? false));
    }

    public function toggleAll(bool $value): void
    {
        foreach ($this->cart as $id => &$item) {
            $item['checked'] = $value;
        }
        unset($item);
        $this->selected = $value ? array_keys($this->cart) : [];
        $this->persist();
    }

    public function getCartItemsProperty(): array
    {
        if (empty($this->cart)) {
            return [];
        }

        $products = Product::whereIn('id', array_keys($this->cart))->get()->keyBy('id');
        $items = [];

        foreach ($this->cart as $productId => $data) {
            if ($products->has($productId)) {
                $product = $products[$productId];
                $items[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'desc' => $product->description,
                    'price' => $product->price,
                    'image' => $product->image,
                    'qty' => $data['qty'],
                    'checked' => $data['checked'] ?? false,
                ];
            }
        }

        return $items;
    }

    public function getTotalProperty(): int
    {
        return array_reduce($this->cartItems, function ($carry, $item) {
            return $carry + ($item['checked'] ? $item['price'] * $item['qty'] : 0);
        }, 0);
    }

    public function getAllCheckedProperty(): bool
    {
        if (empty($this->cart)) {
            return false;
        }

        return collect($this->cart)->every(fn ($i) => $i['checked'] ?? false);
    }

    public function checkoutSelected()
    {
        if ($this->total <= 0) {
            session()->flash('error', 'Pilih minimal 1 produk untuk checkout.');

            return;
        }

        return redirect()->route('checkout.index');
    }

    public function render()
    {
        return view('livewire.cart');
    }
}
