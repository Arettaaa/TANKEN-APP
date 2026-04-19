<?php

use Illuminate\Support\Facades\Route;


// ==========================================
// RUTE PELANGGAN 
// ==========================================

// Otomatis arahkan (redirect) dari tanken.com/ ke tanken.com/beranda
Route::redirect('/', '/beranda');

Route::get('/beranda', function () {return view('pelanggan.homepage');})->name('pelanggan.beranda');
Route::get('/shop', function () { return view('pelanggan.shop'); })->name('pelanggan.shop');
Route::get('/wishlist', function () { return view('pelanggan.wishlist'); })->name('pelanggan.wishlist');
Route::get('/keranjang', function () { return view('pelanggan.keranjang'); })->name('pelanggan.keranjang');
Route::get('/profil', function () { return view('pelanggan.profil'); })->name('pelanggan.profil');
Route::get('/checkout', function () { return view('pelanggan.checkout'); })->name('pelanggan.checkout');

// Fitur Khusus Mitra Kolaborasi
Route::get('/mitra/kolaborasi', function () { return view('pelanggan.mitra-form'); })->name('pelanggan.mitra.pengajuan');

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
