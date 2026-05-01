<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Promo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PromoController extends Controller
{
    public function show(Promo $promo): View
    {
        return view('pages.promo.show', [
            'title' => 'Detail Promosi',
            'promo' => $promo,
        ]);
    }

    public function rekomendasi(Request $request): View
    {
        $products = Product::all();

        return view('pages.promo-admin.rekomendasi', [
            'title'    => 'Rekomendasi Produk Promosi',
            'products' => $products,
        ]);
    }

    public function produkDalamPromosi(): View
    {
        $promos = Promo::where('status', 'active')->get();

        return view('pages.promo-admin.produk-dalam-promosi', [
            'title'  => 'Produk Dalam Promosi',
            'promos' => $promos,
        ]);
    }

    public function status(string $tab): View
    {
        $promos = match($tab) {
            'terjadwal' => Promo::where('status', 'scheduled')->get(),
            'berakhir'  => Promo::where('status', 'expired')->get(),
            default     => Promo::where('status', 'active')->get(),
        };

        return view('pages.promo-admin.status', [
            'title'  => 'Status Promosi',
            'promos' => $promos,
            'tab'    => $tab,
        ]);
    }

    public function create(): View
    {
        $products = Product::all();

        return view('pages.promo-admin.create', [
            'title'    => 'Tambah Promosi Produk',
            'products' => $products,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'         => ['nullable', 'string', 'max:255'],
            'actual_price' => ['required', 'integer', 'min:0'],
            'price'        => ['required', 'integer', 'min:0'],
            'date_start'   => ['nullable', 'date'],
            'date_until'   => ['required', 'date'],
            'stok'         => ['nullable', 'integer', 'min:0'],
            'description'  => ['nullable', 'string'],
            'image'        => ['nullable', 'image', 'max:2048'],
        ]);

        $promo = Promo::create([
            'name'         => $request->name ?? '-',
            'actual_price' => $request->actual_price,
            'price'        => $request->price,
            'date_start'   => $request->date_start,
            'date_until'   => $request->date_until,
            'stok'         => $request->stok,
            'description'  => $request->description,
            'status'       => 'active',
        ]);

        if ($request->hasFile('image')) {
            $promo->addMediaFromRequest('image')
                  ->toMediaCollection(Promo::MEDIA_COLLECTION);
        }

        return redirect()
            ->route('promo-admin.status', 'aktif')
            ->with('success', 'Promosi berhasil ditambahkan.');
    }

    public function edit(Promo $promo): View
    {
        $allProducts = Product::all();

        return view('pages.promo-admin.edit', [
            'title'       => 'Edit Promosi Produk',
            'promo'       => $promo,
            'allProducts' => $allProducts,
        ]);
    }

    public function update(Request $request, Promo $promo): RedirectResponse
    {
        $request->validate([
            'name'         => ['nullable', 'string', 'max:255'],
            'actual_price' => ['required', 'integer', 'min:0'],
            'price'        => ['required', 'integer', 'min:0'],
            'date_start'   => ['nullable', 'date'],
            'date_until'   => ['required', 'date'],
            'stok'         => ['nullable', 'integer', 'min:0'],
            'description'  => ['nullable', 'string'],
            'image'        => ['nullable', 'image', 'max:2048'],
        ]);

        $promo->update([
            'name'         => $request->name ?? $promo->name,
            'actual_price' => $request->actual_price,
            'price'        => $request->price,
            'date_start'   => $request->date_start,
            'date_until'   => $request->date_until,
            'stok'         => $request->stok,
            'description'  => $request->description,
        ]);

        if ($request->hasFile('image')) {
            $promo->addMediaFromRequest('image')
                  ->toMediaCollection(Promo::MEDIA_COLLECTION);
        }

        return redirect()
            ->route('promo-admin.status', 'aktif')
            ->with('success', 'Promosi berhasil diperbarui.');
    }

    public function destroy(Promo $promo): RedirectResponse
    {
        $promo->delete();

        return redirect()
            ->route('promo-admin.status', 'aktif')
            ->with('success', 'Promosi berhasil dihapus.');
    }

    public function destroyProduct(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()
            ->route('promo-admin.rekomendasi')
            ->with('success', 'Produk berhasil dihapus.');
    }

    public function toggleSelect(Product $product): RedirectResponse
    {
        $product->update([
            'selected_for_promo' => !$product->selected_for_promo,
        ]);

        return redirect()->back();
    }
}
