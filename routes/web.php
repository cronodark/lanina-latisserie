<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductTestController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\PromoDetailController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth'], function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::group(['middleware' => 'role:admin'], function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    });

    Route::group(['middleware' => 'role:customer'], function () {
        // Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    });
});

Route::group(['middleware' => 'guest'], function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('login.submit');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
});

Route::get('/', [LandingPageController::class, 'index'])->name('beranda');
Route::get('/produk', [LandingPageController::class, 'product'])->name('produk');
Route::get('/detailproduk', [LandingPageController::class, 'detail'])->name('detailproduk');
Route::get('/keranjang', [LandingPageController::class, 'cart'])->name('keranjang');
Route::get('/checkout', [LandingPageController::class, 'checkout'])->name('checkout');

Route::prefix('product')->name('product.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/create', [ProductController::class, 'create'])->name('create');
    Route::post('/', [ProductController::class, 'store'])->name('store');
    Route::get('/{product}', [ProductController::class, 'show'])->name('show');
    Route::get('/{product}/update', [ProductController::class, 'edit'])->name('edit');
    Route::put('/{product}', [ProductController::class, 'update'])->name('update');
});

Route::prefix('admin/product')->name('product-admin.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/create', [ProductController::class, 'create'])->name('create');
    Route::post('/', [ProductController::class, 'store'])->name('store');
    Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('edit');
    Route::put('/{product}', [ProductController::class, 'update'])->name('update');
    Route::delete('/{product}', [ProductController::class, 'destroy'])->name('destroy');
});

Route::prefix('admin/promo')->name('promo-admin.')->group(function () {
    Route::get('/rekomendasi', [PromoController::class, 'rekomendasi'])->name('rekomendasi');
    Route::get('/produk-dalam-promosi', [PromoController::class, 'produkDalamPromosi'])->name('produkDalamPromosi');
    Route::get('/status/{tab}', [PromoController::class, 'status'])->name('status');
    Route::get('/create', [PromoController::class, 'create'])->name('create');
    Route::post('/', [PromoController::class, 'store'])->name('store');
    Route::get('/{promo}/edit', [PromoController::class, 'edit'])->name('edit');
    Route::put('/{promo}', [PromoController::class, 'update'])->name('update');
    Route::delete('/{promo}', [PromoController::class, 'destroy'])->name('destroy');
    Route::delete('/product/{product}', [PromoController::class, 'destroyProduct'])->name('destroyProduct');
    Route::post('/toggle/{product}', [PromoController::class, 'toggleSelect'])->name('toggleSelect');
});