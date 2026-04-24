<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LandingPageController;
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

// Profile page
Route::get('/my-profile', [LandingPageController::class, 'myProfile'])->name('myProfile');

Route::prefix('product')->name('product.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/create', [ProductController::class, 'create'])->name('create');
    Route::post('/', [ProductController::class, 'store'])->name('store');
    Route::get('/{product}', [ProductController::class, 'show'])->name('show');
    Route::get('/{product}/update', [ProductController::class, 'edit'])->name('edit');
    Route::put('/{product}', [ProductController::class, 'update'])->name('update');
});

Route::get('/promo/{promo}', [PromoController::class, 'show'])->name('promo.show');
