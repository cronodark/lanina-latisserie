<?php

namespace App\Http\Controllers;

use App\Models\PreOrderDetail;
use App\Models\Product;

class LandingPageController extends Controller
{
    public function index()
    {
        $products = Product::latest()->take(5)->get();
        $bestsellers = PreOrderDetail::query()
            ->selectRaw('product_id, SUM(quantity) as total_bought')
            ->where('type', 'product')
            ->whereNotNull('product_id')
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('total_bought')
            ->take(5)
            ->get()
            ->filter(fn (PreOrderDetail $detail) => $detail->product !== null)
            ->values();

        return view('pages.main.landing', [
            'title' => 'Lanina Patisserie',
            'products' => $products,
            'bestsellers' => $bestsellers,
        ]);
    }
    public function diproses()
    {
        return view('pages.customer.diproses', [
            'title' => 'Diproses'
        ]);
    }
    public function belumByr()
    {
        return view('pages.customer.belumBayar', [
            'title' => 'Belum Bayar'
        ]);
    }
    public function diantar()
    {
        return view('pages.customer.diantar', [
            'title' => 'Diantar'
        ]);
    }
    public function selesai()
    {
        return view('pages.customer.selesai', [
            'title' => 'selesai'
        ]);
    }
}
