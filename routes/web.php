<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\MidtransWebhookController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PromoController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth'], function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    // Admin Routes
    Route::group(['middleware' => 'role:admin'], function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::prefix('product')->name('product.')->group(function () {
            Route::get('/', [ProductController::class, 'indexAdmin'])->name('index');
            Route::get('/create', [ProductController::class, 'create'])->name('create');
            Route::post('/', [ProductController::class, 'store'])->name('store');
            Route::get('/{product}', [ProductController::class, 'show'])->name('show');
            Route::get('/{product}/update', [ProductController::class, 'edit'])->name('edit');
            Route::put('/{product}', [ProductController::class, 'update'])->name('update');
        });
    });

    // Customer Routes
    Route::group(['middleware' => 'role:customer'], function () {
        Route::prefix('product')->name('product.')->group(function () {
            Route::get('/', [ProductController::class, 'index'])->name('index');
        });

        Route::post('/cart/{product}', [CartController::class, 'storeProduct'])->name('cart.store');
        Route::post('/cart/promo/{promo}', [CartController::class, 'storePromo'])->name('cart.store.promo');
        Route::get('/cart', function () {
            return view('pages.cart.index');
        })->name('cart.index');
        Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
        Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
        Route::get('/checkout/payment/{preOrder}/finish', [CheckoutController::class, 'paymentFinish'])->name('checkout.payment.finish');
    });
});

// Guest Routes
Route::group(['middleware' => 'guest'], function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('login.submit');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
});

// Landing Page Routes
Route::get('/', [LandingPageController::class, 'index'])->name('beranda');
Route::get('/produk', [LandingPageController::class, 'product'])->name('produk');
Route::get('/detailproduk', [LandingPageController::class, 'detail'])->name('detailproduk');
Route::post('/midtrans/notification', [MidtransWebhookController::class, 'handle'])->name('midtrans.notification');

// Profile page
Route::get('/profil', [LandingPageController::class, 'profil'])->name('profil');
Route::get('/profil/alamat', [LandingPageController::class, 'alamat'])->name('alamat');
Route::get('/profil/tambah-alamat', [LandingPageController::class, 'addAlamat'])->name('tambah-alamat');
Route::get('/profil/edit-alamat', [LandingPageController::class, 'editAlamat'])->name('edit-alamat');
Route::get('/profil/belum-bayar', [LandingPageController::class, 'belumByr'])->name('belum-bayar');
Route::get('/profil/diproses', [LandingPageController::class, 'diproses'])->name('diproses');
Route::get('/profil/diantar', [LandingPageController::class, 'diantar'])->name('diantar');
Route::get('/profil/selesai', [LandingPageController::class, 'selesai'])->name('selesai');

Route::prefix('product')->name('product.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/create', [ProductController::class, 'create'])->name('create');
    Route::post('/', [ProductController::class, 'store'])->name('store');
    Route::get('/{product}', [ProductController::class, 'show'])->name('show');
    Route::get('/{product}/update', [ProductController::class, 'edit'])->name('edit');
    Route::put('/{product}', [ProductController::class, 'update'])->name('update');
});

Route::get('/promo/{promo}', [PromoController::class, 'show'])->name('promo.show');

// dashboard 
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

