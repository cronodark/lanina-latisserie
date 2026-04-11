<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LandingPageController;
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
