@extends('layouts.main')

@section('title', 'Pembayaran Gagal — TANKEN')

@section('content')

<div class="bg-white min-h-screen flex flex-col items-center justify-center py-12 px-5 sm:px-6">
    <div class="w-full max-w-2xl flex flex-col items-center">

        {{-- Ikon Silang (Merah) --}}
        <div class="w-20 h-20 bg-red-50 rounded-full flex items-center justify-center mb-6 shadow-sm border border-red-100">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="w-10 h-10 text-red-500">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>

        {{-- Judul & Deskripsi --}}
        <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-4 text-center tracking-tight">Pembayaran Gagal</h1>
        <p class="text-sm sm:text-base text-gray-500 text-center max-w-md leading-relaxed mb-8">
            Sayangnya, pembayaran Anda tidak dapat diproses. Silakan coba lagi atau gunakan metode pembayaran lain.
        </p>

        {{-- Kotak Kemungkinan Penyebab --}}
        <div class="w-full border border-red-200 bg-red-50/40 rounded-lg p-6 sm:p-8 mb-8">
            <h3 class="font-bold text-gray-900 text-sm sm:text-base mb-3">Kemungkinan Penyebab:</h3>
            <ul class="list-disc pl-5 text-sm text-gray-600 space-y-2.5 marker:text-red-400">
                <li>Saldo di rekening Anda tidak mencukupi</li>
                <li>Detail kartu salah atau kartu telah kedaluwarsa</li>
                <li>Waktu tunggu sistem habis atau terjadi masalah jaringan</li>
                <li>Transaksi ditolak oleh bank Anda</li>
            </ul>
        </div>

        {{-- Tombol Aksi (Dibuat rounded-md / agak kotak) --}}
        <div class="flex flex-col sm:flex-row items-center gap-4 w-full mb-10">
            <a href="{{ Route::has('checkout.payment') ? route('checkout.payment') : url('/checkout/payment') }}" 
               class="w-full sm:flex-1 flex items-center justify-center gap-2 bg-[#111] text-white border border-[#111] text-xs font-bold tracking-[0.12em] uppercase py-4 px-6 rounded-md hover:bg-[#333] transition-all">
                <i class="fa-solid fa-rotate-right text-sm"></i> Coba Lagi
            </a>
            
            <a href="{{ Route::has('keranjang.index') ? route('keranjang.index') : url('/keranjang') }}" 
               class="w-full sm:flex-1 flex items-center justify-center gap-2 bg-white text-[#111] border border-gray-300 text-xs font-bold tracking-[0.12em] uppercase py-4 px-6 rounded-md hover:bg-gray-50 transition-all">
                <i class="fa-solid fa-arrow-left text-sm"></i> Kembali ke Keranjang
            </a>
        </div>

        {{-- Kotak Metode Pembayaran Lain --}}
        <div class="w-full bg-gray-50 rounded-lg p-6 sm:p-8 mb-8 border border-gray-100">
            <p class="font-bold text-xs sm:text-sm text-gray-900 mb-5 flex items-center gap-2 uppercase tracking-wide">
                <i class="fa-regular fa-credit-card"></i> Coba Metode Pembayaran Lain:
            </p>
            
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <a href="{{ Route::has('checkout.payment') ? route('checkout.payment') : url('/checkout/payment') }}" class="bg-white border border-gray-200 rounded-md p-4 flex flex-col items-center justify-center text-center gap-2.5 hover:border-gray-400 hover:shadow-sm transition-all group">
                    <i class="fa-regular fa-credit-card text-gray-400 text-xl group-hover:text-gray-800 transition-colors"></i>
                    <span class="text-[10px] sm:text-xs font-semibold text-gray-600 group-hover:text-gray-900">Kartu Kredit</span>
                </a>
                
                <a href="{{ Route::has('checkout.payment') ? route('checkout.payment') : url('/checkout/payment') }}" class="bg-white border border-gray-200 rounded-md p-4 flex flex-col items-center justify-center text-center gap-2.5 hover:border-gray-400 hover:shadow-sm transition-all group">
                    <i class="fa-solid fa-wallet text-gray-400 text-xl group-hover:text-gray-800 transition-colors"></i>
                    <span class="text-[10px] sm:text-xs font-semibold text-gray-600 group-hover:text-gray-900">E-Wallet</span>
                </a>

                <a href="{{ Route::has('checkout.payment') ? route('checkout.payment') : url('/checkout/payment') }}" class="bg-white border border-gray-200 rounded-md p-4 flex flex-col items-center justify-center text-center gap-2.5 hover:border-gray-400 hover:shadow-sm transition-all group">
                    <i class="fa-solid fa-laptop-code text-gray-400 text-xl group-hover:text-gray-800 transition-colors"></i>
                    <span class="text-[10px] sm:text-xs font-semibold text-gray-600 group-hover:text-gray-900">Virtual Account</span>
                </a>

                <a href="{{ Route::has('checkout.payment') ? route('checkout.payment') : url('/checkout/payment') }}" class="bg-white border border-gray-200 rounded-md p-4 flex flex-col items-center justify-center text-center gap-2.5 hover:border-gray-400 hover:shadow-sm transition-all group">
                    <i class="fa-solid fa-building-columns text-gray-400 text-xl group-hover:text-gray-800 transition-colors"></i>
                    <span class="text-[10px] sm:text-xs font-semibold text-gray-600 group-hover:text-gray-900">Transfer Bank</span>
                </a>
            </div>
        </div>

        {{-- Bantuan & Lanjut Belanja --}}
        <div class="flex flex-col items-center justify-center gap-3">
            <a href="#" class="text-xs sm:text-sm text-gray-500 flex items-center gap-2 hover:text-gray-900 transition-colors">
                <i class="fa-regular fa-circle-question"></i> Butuh bantuan? Hubungi tim dukungan kami
            </a>
            
            <a href="{{ Route::has('pelanggan.katalog') ? route('pelanggan.katalog') : url('/katalog') }}" class="text-[11px] sm:text-xs text-gray-400 hover:text-gray-900 flex items-center gap-1.5 transition-colors uppercase tracking-widest mt-2">
                atau lanjut belanja <i class="fa-solid fa-arrow-right-long"></i>
            </a>
        </div>

    </div>
</div>

@endsection