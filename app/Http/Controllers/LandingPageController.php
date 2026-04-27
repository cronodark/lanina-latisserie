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
    public function product()
    {
        return view('pages.main.produk', [
            'title' => 'Our Product'
        ]);
    }
    public function detail()
    {
        return view('pages.main.detailproduk', [
            'title' => 'Detail Produk'
        ]);
    }
    public function cart()
    {
        return view('pages.main.keranjang', [
            'title' => 'Keranjang'
        ]);
    }
    public function checkout()
    {
        return view('pages.main.checkout', [
            'title' => 'Checkout'
        ]);
    }
    public function profil()
    {
        return view('pages.customer.profil', [
            'title' => 'Profil Saya'
        ]);
    }
    public function alamat()
    {
        return view('pages.customer.alamat', [
            'title' => 'Alamat Saya'
        ]);
    }
    public function addAlamat()
    {
        return view('pages.customer.tambahAlamat', [
            'title' => 'Tambah Alamat'
        ]);
    }
    public function editAlamat()
    {
        return view('pages.customer.editAlamat', [
            'title' => 'Edit Alamat'
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
