<?php

use Illuminate\Support\Facades\Route;

use App\Models\Product;

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
use App\Http\Controllers\Admin\PaymentController;

// Import Controller Pelanggan
use App\Http\Controllers\Pelanggan\CustomerProfileController;
use App\Http\Controllers\Pelanggan\AddressController;
use App\Http\Controllers\Pelanggan\WishlistController;
use App\Http\Controllers\Pelanggan\CartController;
use App\Http\Controllers\Pelanggan\CheckoutController;
use App\Http\Controllers\Pelanggan\PaymentController as PelangganPaymentController;
use App\Http\Controllers\Pelanggan\CustomerOrderController;
use App\Http\Controllers\Pelanggan\ReviewController as PelangganReviewController;


// ==========================================
// AUTENTIKASI (LOGIN & REGISTER)
// ==========================================
Route::get('/masuk', function () {
    return view('auth.login');
})->name('login');

Route::get('/daftar', function () {
    return view('auth.register');
})->name('register');

Route::post('/masuk', [AuthController::class, 'login']);
Route::post('/daftar', [AuthController::class, 'register']);
Route::post('/keluar', [AuthController::class, 'logout'])->name('logout');

Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'callback']);

/*
|--------------------------------------------------------------------------
| Web Routes — PELANGGAN
|--------------------------------------------------------------------------
*/
Route::redirect('/', '/beranda');

Route::name('pelanggan.')->group(function () {

    Route::get('/beranda', function () {
        return view('pelanggan.homepage');
    })->name('home');

    Route::get('/katalog', function () {
        $products = \App\Models\Product::all();
        return view('pelanggan.katalog', compact('products'));
    })->name('katalog');

    Route::get('/produk/{slug}', function ($slug) {
        $product = \App\Models\Product::with([
            'galleries',
            'stocks',
            'reviews' => fn($q) => $q->where('status', 'approved')->with('user'),
        ])->where('slug', $slug)->firstOrFail();

        return view('pelanggan.produk-detail', compact('product'));
    })->name('produk.detail');

    Route::prefix('akun')->group(function () {

        Route::get('/profil', function () {
            return view('pelanggan.profil-edit');
        })->name('profil-edit');
        Route::put('/profil/simpan', [CustomerProfileController::class, 'update'])->name('profil.simpan');

        Route::get('/password', function () {
            return view('pelanggan.profil-password');
        })->name('profil-password');
        Route::put('/password/simpan', [CustomerProfileController::class, 'updatePassword'])
            ->name('ganti-password.simpan');

        Route::get('/pesanan', [CustomerOrderController::class, 'index'])->name('profil-order');
        Route::post('/pesanan/{id}/beli-lagi', [CustomerOrderController::class, 'beliLagi'])->name('pesanan.beli-lagi');

        Route::get('/wishlist', [WishlistController::class, 'index'])->name('profil-wishlist');
        Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
        Route::delete('/wishlist/{productId}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');

        Route::get('/keranjang',        [CartController::class, 'index'])->name('keranjang.index');
        Route::post('/keranjang',       [CartController::class, 'store'])->name('keranjang.store');
        Route::patch('/keranjang/{id}', [CartController::class, 'update'])->name('keranjang.update');
        Route::delete('/keranjang/{id}', [CartController::class, 'destroy'])->name('keranjang.destroy');

        Route::post('/checkout/simpan-item', [CheckoutController::class, 'simpanItem'])->name('checkout.simpanItem');
        Route::get('/checkout',             [CheckoutController::class, 'index'])->name('checkout.index');
        Route::post('/checkout/proses', [CheckoutController::class, 'proses'])->name('checkout.proses');
        Route::get('/checkout/ongkir', [CheckoutController::class, 'getOngkir'])->name('checkout.ongkir');

        Route::get('/alamat', [AddressController::class, 'index'])->name('profil-alamat');
        Route::post('/alamat', [AddressController::class, 'store'])->name('alamat.store');
        Route::put('/alamat/{address}', [AddressController::class, 'update'])->name('alamat.update');
        Route::delete('/alamat/{address}', [AddressController::class, 'destroy'])->name('alamat.destroy');
        Route::post('/alamat/{address}/default', [AddressController::class, 'setDefault'])->name('alamat.default');

        // ==== PAYMENT & REVIEW ====
        Route::get('/checkout/payment',         [PelangganPaymentController::class, 'index'])->name('checkout.payment');
        Route::post('/checkout/payment/simpan', [PelangganPaymentController::class, 'simpan'])->name('checkout.payment.simpan');
        
        // Rute Review (Langkah 3)
        Route::get('/checkout/review', [PelangganPaymentController::class, 'review'])->name('checkout.review');
        
        // Rute Buat Pesanan
        Route::post('/checkout/place-order', [PelangganPaymentController::class, 'placeOrder'])->name('checkout.place-order');

        Route::get('/ulasan',  [PelangganReviewController::class, 'create'])->name('ulasan.create');
        Route::post('/ulasan', [PelangganReviewController::class, 'store'])->name('ulasan.store');
    });

    // =================================
    // VOUCHER - FE ONLY (MOCKUP API)
    // =================================
    Route::post('/voucher/check', function (\Illuminate\Http\Request $request) {
        $code = strtoupper(trim($request->code));
        if ($code === 'TANKEN50') {
            return response()->json(['valid' => true, 'label' => 'Promo Diskon 50 Ribu', 'discount' => 50000]);
        }
        return response()->json(['valid' => false, 'message' => 'Kode voucher tidak valid atau sudah kedaluwarsa.']);
    })->name('voucher.check');
});

// Sukses & Gagal Checkout
Route::get('/checkout/success', function () {
    return view('pelanggan.payment-confirm');
})->name('pelanggan.checkout.success'); // Namanya disesuaikan biar rapi dipanggil controller

Route::get('/checkout/failed', function () {
    return view('pelanggan.payment-failed');
})->name('checkout.failed');


// =================================
// Help & Support
// =================================
Route::get('/help', function () { return view('pelanggan.help'); })->name('help');
Route::get('/help/shipping', function () { return view('pelanggan.shipping-information'); })->name('help.shipping');
Route::get('/help/returns', function () { return view('pelanggan.returns-exchanges'); })->name('help.returns');
Route::get('/help/size-guide', function () { return view('pelanggan.size-guide'); })->name('help.size-guide');
Route::get('/help/faq', function () { return view('pelanggan.faq'); })->name('help.faq');

// ==========================================
// RUTE ADMIN
// ==========================================
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('products/export', [ProductController::class, 'exportExcel'])->name('products.export');
    Route::resource('products', ProductController::class);
    Route::post('products/{product}/stock', [ProductController::class, 'updateStock'])->name('products.updateStock');

    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::patch('/reviews/{id}/status', [ReviewController::class, 'updateStatus'])->name('reviews.updateStatus');
    Route::delete('/reviews/{id}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    Route::get('/stock', [StockController::class, 'index'])->name('stock.index');
    Route::patch('/stock/{id}', [StockController::class, 'update'])->name('stock.update');

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/export', [OrderController::class, 'export'])->name('orders.export');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::patch('/orders/{order}/konfirmasi', [OrderController::class, 'konfirmasi'])->name('orders.konfirmasi');
    Route::patch('/orders/{order}/tolak',      [OrderController::class, 'tolak'])->name('orders.tolak');

    Route::get('stock', [StockController::class, 'index'])->name('stock.index');
    Route::patch('stock/{stock}', [StockController::class, 'update'])->name('stock.update');

    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/export', [PaymentController::class, 'export'])->name('payments.export');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export/excel', [ReportController::class, 'exportExcel'])->name('reports.export.excel');
    Route::get('/reports/export/pdf', [ReportController::class, 'exportPdf'])->name('reports.export.pdf');

    Route::resource('users', UserController::class)->except(['create', 'show', 'edit']);
    Route::post('users/{user}/toggle-status', [UserController::class, 'toggleStatus']);

    Route::resource('promos', PromoController::class)->except(['create', 'show', 'edit']);
    Route::post('promos/{promo}/toggle-status', [PromoController::class, 'toggleStatus']);

    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});

Route::get('/wilayah', [AddressController::class, 'getWilayah']);