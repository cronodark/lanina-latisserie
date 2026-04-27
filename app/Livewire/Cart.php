<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Promo;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Cart extends Component
{
    public array $cart = [];         // ['type:id' => ['type' => string, 'item_id' => int, 'qty' => int, 'checked' => bool]]

    public array $selected = [];     // product_ids that are checked

    public string $selectedDate = '';

    public function mount(): void
    {
        $sessionCart = session()->get($this->sessionKey(), []);
        $this->cart = $this->normalizeCart($sessionCart);
        $this->selected = array_keys(array_filter($this->cart, fn ($i) => $i['checked'] ?? false));
        $this->persist();
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
        $cartKey = 'product:'.$productId;

        if (isset($this->cart[$cartKey])) {
            $this->cart[$cartKey]['qty']++;
        } else {
            $this->cart[$cartKey] = [
                'type' => 'product',
                'item_id' => $productId,
                'qty' => 1,
                'checked' => false,
            ];
        }
        $this->persist();
    }

    public function increment(string $cartKey): void
    {
        if (isset($this->cart[$cartKey])) {
            $this->cart[$cartKey]['qty']++;
            $this->persist();
        }
    }

    public function decrement(string $cartKey): void
    {
        if (isset($this->cart[$cartKey])) {
            if ($this->cart[$cartKey]['qty'] > 1) {
                $this->cart[$cartKey]['qty']--;
            } else {
                unset($this->cart[$cartKey]);
                $this->selected = array_values(array_filter($this->selected, fn ($id) => $id !== $cartKey));
            }
            $this->persist();
        }
    }

    public function removeItem(string $cartKey): void
    {
        unset($this->cart[$cartKey]);
        $this->selected = array_values(array_filter($this->selected, fn ($id) => $id !== $cartKey));
        $this->persist();
    }

    public function toggleItem(string $cartKey): void
    {
        if (isset($this->cart[$cartKey])) {
            $this->cart[$cartKey]['checked'] = ! ($this->cart[$cartKey]['checked'] ?? false);
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

        $productIds = collect($this->cart)
            ->filter(fn (array $item) => ($item['type'] ?? 'product') === 'product')
            ->pluck('item_id')
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        $promoIds = collect($this->cart)
            ->filter(fn (array $item) => ($item['type'] ?? 'product') === 'promo')
            ->pluck('item_id')
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');
        $promos = Promo::whereIn('id', $promoIds)->get()->keyBy('id');
        $items = [];

        foreach ($this->cart as $cartKey => $data) {
            $type = $data['type'] ?? 'product';
            $itemId = (int) ($data['item_id'] ?? 0);

            if ($type === 'promo') {
                $model = $promos->get($itemId);
            } else {
                $type = 'product';
                $model = $products->get($itemId);
            }

            if ($model) {
                $items[] = [
                    'key' => (string) $cartKey,
                    'id' => $model->id,
                    'type' => $type,
                    'name' => $model->name,
                    'desc' => $model->description,
                    'price' => $model->price,
                    'image' => $model->image,
                    'qty' => (int) ($data['qty'] ?? 1),
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

    private function normalizeCart(array $cart): array
    {
        $normalized = [];

        foreach ($cart as $key => $item) {
            $type = $item['type'] ?? (is_numeric($key) ? 'product' : null);
            $itemId = (int) ($item['item_id'] ?? (is_numeric($key) ? $key : 0));

            if (! in_array($type, ['product', 'promo'], true) || $itemId < 1) {
                continue;
            }

            $normalizedKey = $type.':'.$itemId;
            $normalized[$normalizedKey] = [
                'type' => $type,
                'item_id' => $itemId,
                'qty' => max(1, (int) ($item['qty'] ?? 1)),
                'checked' => (bool) ($item['checked'] ?? false),
            ];
        }

        return $normalized;
    }
}
