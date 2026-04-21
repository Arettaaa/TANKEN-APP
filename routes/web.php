<?php

use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes — PELANGGAN
|--------------------------------------------------------------------------
*/

// ---- Homepage ----
Route::get('/beranda', function () {
    return view('pelanggan.homepage');
})->name('home');

// Shop / Catalog
Route::get('/katalog', function () {
    return view('pelanggan.katalog');
})->name('katalog');

Route::get('/produk/{slug}', function ($slug) {
    return view('pelanggan.produk-detail');
})->name('produk.detail');

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
// RUTE ADMIN (DASHBOARD & MANAJEMEN)
// ==========================================

// Mengelompokkan semua rute yang berawalan "/admin"
Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/dasbor', function () {return view('admin.dasbor');})->name('dasbor');
    Route::get('/produk', function () {return view('admin.kelola-produk'); })->name('produk');
    Route::get('/ulasan', function () {return view('admin.ulasan');})->name('ulasan');
    Route::get('/stok', function () {return view('admin.stok');})->name('stok');
    Route::get('/pesanan', function () {return view('admin.pesanan'); })->name('pesanan');
    Route::get('/pembayaran', function () {return view('admin.pembayaran'); })->name('pembayaran');
    Route::get('/laporan', function () {return view('admin.laporan');})->name('laporan');
    Route::get('/pengguna', function () {return view('admin.pengguna'); })->name('pengguna');
    Route::get('/voucher', function () {return view('admin.voucher');})->name('voucher');
    Route::get('/kemitraan', function () {return view('admin.kemitraan'); })->name('kemitraan');
});
