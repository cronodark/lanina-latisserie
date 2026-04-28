<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        return view('pages.product.index', [
            'title' => 'Daftar Produk',
        ]);
    }

    public function create(): View
    {
        return view('pages.product-admin.create', [
            'title' => 'Add Product',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'description'  => ['required', 'string'],
            'harga'        => ['required', 'integer', 'min:0'],
            'expired_day'  => ['required', 'integer', 'min:1'],
            'image'        => ['required', 'image', 'max:2048'],
        ]);

        $product = Product::query()->create([
            'name'        => $validated['name'],
            'description' => $validated['description'],
            'harga'       => $validated['harga'],
            'expired_day' => $validated['expired_day'],
        ]);

        $product
            ->addMediaFromRequest('image')
            ->toMediaCollection(Product::MEDIA_COLLECTION);

        return redirect()
            ->route('product-admin.index')
            ->with('success', 'Product added successfully.');
    }

    public function show(Product $product): View
    {
        return view('pages.product-admin.show', [
            'title'   => 'Product ' . $product->name,
            'product' => $product,
        ]);
    }

    public function edit(Product $product): View
    {
        return view('pages.product-admin.edit', [
            'title'   => 'Edit Product',
            'product' => $product,
        ]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'harga'       => ['required', 'integer', 'min:0'],
            'expired_day' => ['required', 'integer', 'min:1'],
            'image'       => ['nullable', 'image', 'max:2048'],
        ]);

        $product->update([
            'name'        => $validated['name'],
            'description' => $validated['description'],
            'harga'       => $validated['harga'],
            'expired_day' => $validated['expired_day'],
        ]);

        if ($request->hasFile('image')) {
            $product
                ->addMediaFromRequest('image')
                ->toMediaCollection(Product::MEDIA_COLLECTION);
        }

        return redirect()
            ->route('product-admin.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()
            ->route('product-admin.index')
            ->with('success', 'Product deleted successfully.');
    }
}
