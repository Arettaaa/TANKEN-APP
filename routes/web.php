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
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\ReviewController;


// ==========================================
// AUTENTIKASI (LOGIN & REGISTER)
// ==========================================
// 1. Rute Manual (Email & Password)
// Menampilkan halaman (GET)
Route::get('/masuk', function () {
    return view('auth.login');
})->name('login');

Route::get('/daftar', function () {
    return view('auth.register');
})->name('register');

// Memproses form (POST)
Route::post('/masuk', [AuthController::class, 'login']);
Route::post('/daftar', [AuthController::class, 'register']);
Route::post('/keluar', [AuthController::class, 'logout'])->name('logout');

// 2. Rute Google OAuth (Socialite)
Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'callback']);

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

    // ---- Review Management ----
    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::patch('/reviews/{id}/status', [ReviewController::class, 'updateStatus'])->name('reviews.updateStatus');
    Route::delete('/reviews/{id}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

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
