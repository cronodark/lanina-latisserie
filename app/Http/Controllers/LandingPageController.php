<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function index()
    {
        return view('pages.main.landing', [
            'title' => 'Lanina Patisserie'
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
}
