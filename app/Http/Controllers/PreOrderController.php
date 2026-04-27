<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PreOrderController extends Controller
{
    public function index()
    {
        return view('pages.preorder.index');
    }
}
