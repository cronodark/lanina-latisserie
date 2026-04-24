<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    public function show (Promo $promo)
    {
        return view('pages.promo.show', [
            'promo' => $promo,
        ]);
    }
}
