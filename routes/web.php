<?php

use Illuminate\Support\Facades\Route;


// ==========================================
// RUTE PELANGGAN 
// ==========================================

// Otomatis arahkan (redirect) dari tanken.com/ ke tanken.com/beranda
Route::redirect('/', '/beranda');

Route::get('/beranda', function () {
    return view('pelanggan.homepage'); 
})->name('pelanggan.beranda');


// ==========================================
// RUTE ADMIN (DASHBOARD & MANAJEMEN)
// ==========================================

// Mengelompokkan semua rute yang berawalan "/admin"
Route::prefix('admin')->name('admin.')->group(function () {

    // 1. Dasbor
    Route::get('/dasbor', function () {
        return view('admin.dasbor');
    })->name('dasbor');

    // 2. Produk
    Route::get('/produk', function () {
        return view('admin.kelola-produk');
    })->name('produk');

    // 3. Ulasan
    Route::get('/ulasan', function () {
        return view('admin.ulasan');
    })->name('ulasan');

    // 4. Stok
    Route::get('/stok', function () {
        return view('admin.stok');
    })->name('stok');

    // 5. Pesanan
    Route::get('/pesanan', function () {
        return view('admin.pesanan');
    })->name('pesanan');

    // 6. Pembayaran
    Route::get('/pembayaran', function () {
        return view('admin.pembayaran');
    })->name('pembayaran');

    // 7. Laporan
    Route::get('/laporan', function () {
        return view('admin.laporan');
    })->name('laporan');

    // 8. Pengguna
    Route::get('/pengguna', function () {
        return view('admin.pengguna');
    })->name('pengguna');

    // 9. Promo & Voucher
    Route::get('/voucher', function () {
        return view('admin.voucher');
    })->name('voucher');

    // 10. Kemitraan
    Route::get('/kemitraan', function () {
        return view('admin.kemitraan');
    })->name('kemitraan');

});