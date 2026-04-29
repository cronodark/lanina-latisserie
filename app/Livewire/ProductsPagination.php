<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ProductsPagination extends Component
{
    use WithPagination;

    protected $paginationTheme = 'livewire';

    public function render()
    {
        $products = Product::paginate(9);

        return view('livewire.products-pagination', [
            'products' => $products,
        ]);
    }
}
