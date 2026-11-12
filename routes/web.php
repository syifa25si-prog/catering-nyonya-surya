<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->middleware('throttle:5,1')->name('login.store');
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'store'])->middleware('throttle:5,1')->name('register.store');
});
Route::middleware('auth')->group(function () {
    Route::get('/', [CatalogController::class, 'home'])->name('home');
    Route::get('/tentang', [CatalogController::class, 'about'])->name('about');
    Route::get('/katalog', [CatalogController::class, 'index'])->name('catalog');
    Route::get('/paket/{menu}', [CatalogController::class, 'show'])->name('menu.show');
    Route::get('/paket/{menu}/gambar', [CatalogController::class, 'image'])->name('menu.image');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
    Route::post('/keranjang/{menu}', [CartController::class, 'store'])->name('cart.store');
    Route::patch('/keranjang/{menu}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/keranjang/{menu}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::post('/checkout', [OrderController::class, 'store'])->block(10, 10)->name('checkout.store');
    Route::get('/pesanan', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/pesanan/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
        Route::get('/', Admin\DashboardController::class)->name('dashboard');
        Route::resource('categories', Admin\CategoryController::class)->except('show');
        Route::resource('menus', Admin\MenuController::class)->except('show');
        Route::get('/orders', [Admin\OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [Admin\OrderController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}', [Admin\OrderController::class, 'update'])->name('orders.update');
    });
});
