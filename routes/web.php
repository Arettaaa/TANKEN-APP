<?php

use Illuminate\Support\Facades\Route;

// Import Controller Admin
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Admin\PromoController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ProfileController;


/*
|--------------------------------------------------------------------------
| Web Routes — PELANGGAN
|--------------------------------------------------------------------------
*/
Route::redirect('/', '/beranda');

Route::name('pelanggan.')->group(function () {
    // Nama rute jadi: pelanggan.home
    Route::get('/beranda', function () {
        return view('pelanggan.homepage');
    })->name('home');

    // Nama rute jadi: pelanggan.katalog
    Route::get('/katalog', function () {
        return view('pelanggan.katalog');
    })->name('katalog');

    Route::get('/produk/{slug}', function ($slug) {
        return view('pelanggan.produk-detail');
    })->name('produk.detail');
});

// Women collection
Route::get('/women', function () {
    return view('pelanggan.homepage'); // ganti dengan view women nanti
})->name('women');

// Men collection
Route::get('/men', function () {
    return view('pelanggan.homepage'); // ganti dengan view men nanti
})->name('men');

// Help
Route::get('/help', function () {
    return view('pelanggan.homepage'); // ganti dengan view help nanti
})->name('help');



// ==========================================
// RUTE ADMIN
// ==========================================
Route::prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Products
    Route::resource('products', ProductController::class);
    Route::post('products/{product}/stock', [ProductController::class, 'updateStock'])
         ->name('products.updateStock');

    // Orders
    Route::resource('orders', OrderController::class)->only(['index', 'show']);
    Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])
         ->name('orders.updateStatus');

    // Stock
    Route::get('stock', [StockController::class, 'index'])->name('stock.index');
    Route::patch('stock/{stock}', [StockController::class, 'update'])->name('stock.update');

    // Users
    Route::resource('users', UserController::class)->only(['index', 'show']);

    // Promo & Voucher
    Route::get('promo', [PromoController::class, 'index'])->name('promo.index');
    Route::post('promo', [PromoController::class, 'store'])->name('promo.store');
    Route::delete('promo/{voucher}', [PromoController::class, 'destroy'])->name('promo.destroy');

    // Reports
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');

    // Profile
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('profile/password', [ProfileController::class, 'updatePassword'])
         ->name('profile.password');
});
