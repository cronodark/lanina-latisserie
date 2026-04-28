<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Promo;
use Livewire\Component;
use Livewire\WithPagination;

class ProductIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'livewire';

    public function render()
    {
        return view('livewire.product-index', [
            'products' => Product::paginate(9),
            'recentProducts' => Product::latest()->take(3)->get(),
            'promos' => Promo::orderBy('created_at', 'desc')->where('status', '=', 'active')->get(),
        ]);
    }
}
