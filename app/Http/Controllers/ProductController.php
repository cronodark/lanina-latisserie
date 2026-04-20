<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Promo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function create(): View
    {
        return view('pages.product.create', [
            'title' => 'Test CRUD Product - Create',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'integer', 'min:0'],
            'image' => ['required', 'image', 'max:2048'],
        ]);

        $product = Product::query()->create($validated);

        $product
            ->addMediaFromRequest('image')
            ->toMediaCollection(Product::MEDIA_COLLECTION);

        return redirect()
            ->route('product.show', $product)
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function index(): View
    {
        $products = Product::all();
        $recentProducts = Product::latest()->take(3)->get();
        $promos = Promo::orderBy('created_at', 'desc')->where('status', '=', 'active')->get();

        return view('pages.product.index', [
            'title' => 'Product List',
            'products' => $products,
            'recentProducts' => $recentProducts,
            'promos' => $promos,
        ]);
    }

    public function show(Product $product): View
    {
        return view('pages.product.show', [
            'title' => 'Product ' . $product->name,
            'product' => $product,
        ]);
    }

    public function edit(Product $product): View
    {
        return view('pages.product.update', [
            'title' => 'Test CRUD Product - Update',
            'product' => $product,
        ]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        $product->update($validated);

        if ($request->hasFile('image')) {
            $product
                ->addMediaFromRequest('image')
                ->toMediaCollection(Product::MEDIA_COLLECTION);
        }

        return redirect()
            ->route('product.show', $product)
            ->with('success', 'Produk berhasil diperbarui.');
    }
}
