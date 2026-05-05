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
        $products = \App\Models\Product::all();
        return view('pelanggan.katalog', compact('products'));
    })->name('katalog');

    // Nama rute jadi: pelanggan.produk.detail
    Route::get('/produk/{slug}', function ($slug) {
        $product = \App\Models\Product::where('slug', $slug)->firstOrFail();
        return view('pelanggan.produk-detail', compact('product'));
    })->name('produk.detail');


    // ==========================================
    // ROUTE AKUN PELANGGAN (DIBUNGKUS PREFIX 'akun')
    // ==========================================
    Route::prefix('akun')->group(function () {
        
        // 1. Edit Profil (URL: /akun/profil | Nama Rute: pelanggan.profil-edit)
        Route::get('/profil', function () {
            return view('pelanggan.profil-edit');
        })->name('profil-edit');
        
        // 👇 TAMBAHKAN RUTE INI UNTUK MENANGANI FORM SUBMIT PROFIL 👇
        Route::post('/profil/simpan', function (\Illuminate\Http\Request $request) {
            // Nanti di sini tempat menaruh logika update ke database
            // Untuk sekarang, kita return kembali ke halaman sebelumnya (dummy sukses)
            return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
        })->name('profil.simpan');

        // 2. Ganti Password (URL: /akun/password | Nama Rute: pelanggan.profil-password)
        Route::get('/password', function () {
            return view('pelanggan.profil-password');
        })->name('profil-password');

        // 👇 TAMBAHKAN RUTE POST INI UNTUK MENYIMPAN PASSWORD 👇
        Route::post('/password/simpan', function (\Illuminate\Http\Request $request) {
            // Nanti logika validasi password lama & update password baru ditaruh di sini
            return redirect()->back()->with('success', 'Password berhasil diubah!');
        })->name('ganti-password.simpan');

        // 3. Riwayat Pesanan (URL: /akun/pesanan | Nama Rute: pelanggan.profil-order)
        Route::get('/pesanan', function () {
            return view('pelanggan.profil-order');
        })->name('profil-order');

        // 4. Wishlist (URL: /akun/wishlist | Nama Rute: pelanggan.profil-wishlist)
        Route::get('/wishlist', function () {
            return view('pelanggan.profil-wishlist');
        })->name('profil-wishlist');

        // 5. Alamat Saya (URL: /akun/alamat | Nama Rute: pelanggan.profil-alamat)
        Route::get('/alamat', function () {
            return view('pelanggan.profil-alamat');
        })->name('profil-alamat');

    }); // <-- Penutup dari Route::prefix('akun')

}); // <-- Penutup dari Route::name('pelanggan.')

// Women collection
Route::get('/women', function () {
    return view('pelanggan.homepage'); // ganti dengan view women nanti
})->name('women');

// Men collection
Route::get('/men', function () {
    return view('pelanggan.homepage'); // ganti dengan view men nanti
})->name('men');

// =================================
// Help & Support
// =================================
// help
Route::get('/help', function () {
    return view('pelanggan.help'); // ← ganti dari homepage
})->name('help');

// shipping
Route::get('/help/shipping', function () {
    return view('pelanggan.shipping-information');
})->name('help.shipping');

// returns & exchanges
Route::get('/help/returns', function () {
    return view('pelanggan.returns-exchanges');
})->name('help.returns');

// size guide
Route::get('/help/size-guide', function () {
    return view('pelanggan.size-guide'); 
})->name('help.size-guide');

// faq
Route::get('/help/faq', function () {
    return view('pelanggan.faq'); 
})->name('help.faq');


// ==========================================
// RUTE ADMIN
// ==========================================
Route::prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Products
      Route::get('products/export', [ProductController::class, 'exportExcel'])
        ->name('products.export');
    Route::resource('products', ProductController::class);
    Route::post('products/{product}/stock', [ProductController::class, 'updateStock'])
        ->name('products.updateStock');
   

    // ---- Review Management ----
    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::patch('/reviews/{id}/status', [ReviewController::class, 'updateStatus'])->name('reviews.updateStatus');
    Route::delete('/reviews/{id}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    // ---- Stock Management ----
    Route::get('/stock', [StockController::class, 'index'])->name('stock.index');
    Route::patch('/stock/{id}', [StockController::class, 'update'])->name('stock.update');

    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/export', [OrderController::class, 'export'])->name('orders.export');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');

    // Stock
    Route::get('stock', [StockController::class, 'index'])->name('stock.index');
    Route::patch('stock/{stock}', [StockController::class, 'update'])->name('stock.update');

    // ---- Payment Management ----
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/export', [PaymentController::class, 'export'])->name('payments.export');

    // ---- Reports & Analytics ----
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export/excel', [ReportController::class, 'exportExcel'])->name('reports.export.excel');
    Route::get('/reports/export/pdf', [ReportController::class, 'exportPdf'])->name('reports.export.pdf');

    // Users
    // Route::resource('users', UserController::class)->only(['index', 'show']);
    Route::resource('users', UserController::class)->except(['create', 'show', 'edit']);
    Route::post('users/{user}/toggle-status', [UserController::class, 'toggleStatus']);

    // Promo & Voucher
    Route::resource('promos', PromoController::class)->except(['create', 'show', 'edit']);
    Route::post('promos/{promo}/toggle-status', [PromoController::class, 'toggleStatus']);

    // Profile
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('profile/password', [ProfileController::class, 'updatePassword'])
        ->name('profile.password');
});
