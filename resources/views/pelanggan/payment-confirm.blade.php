@extends('layouts.main')

@section('title', 'Pesanan Dikonfirmasi — TANKEN')

@section('content')
@php
    $orderNumber  = session('order_number', '-');
    $shippingDays = session('shipping_days', '2-3 hari');

    \Carbon\Carbon::setLocale('id');
    preg_match('/(\d+)(?!.*\d)/', $shippingDays, $matches);
    $daysMax = isset($matches[1]) ? (int)$matches[1] : 3;
    $estimatedDelivery = \Carbon\Carbon::now()->addDays($daysMax)->translatedFormat('d F Y');
@endphp

<div class="bg-white min-h-[75vh] flex flex-col items-center justify-center py-16 px-5 sm:px-6">
    <div class="w-full max-w-3xl flex flex-col items-center">

        {{-- Ikon Ceklis (Kotak Hitam) --}}
        <div class="w-16 h-16 sm:w-20 sm:h-20 bg-[#111] flex items-center justify-center rounded-md mb-6 sm:mb-8 shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="1.5" class="w-8 h-8 sm:w-10 sm:h-10">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>

        {{-- Judul & Deskripsi --}}
        <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-4 text-center tracking-tight">Pesanan Dikonfirmasi!</h1>
        <p class="text-sm sm:text-base text-gray-600 text-center max-w-lg leading-relaxed mb-8 sm:mb-10">
            Terima kasih atas pembelian Anda. Pesanan Anda telah diterima dan sedang diproses oleh tim kami.
        </p>

        {{-- Kotak Info Nomor Pesanan & Tanggal --}}
        <div class="w-full bg-gray-50/50 border border-gray-200 rounded-md p-6 sm:p-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6 sm:gap-4 mb-8">
            <div>
                <p class="text-xs font-semibold text-gray-500 mb-1.5">Nomor Pesanan</p>
                <p class="font-extrabold text-gray-900 text-lg sm:text-xl tracking-wide">{{ $orderNumber }}</p>
            </div>
            <div class="sm:text-right">
                <p class="text-xs font-semibold text-gray-500 mb-1.5">Estimasi Pengiriman</p>
                <p class="font-extrabold text-gray-900 text-lg sm:text-xl tracking-wide">{{ $estimatedDelivery }}</p>
            </div>
        </div>

        {{-- Tombol Aksi (Lacak & Lanjut Belanja) --}}
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 w-full mb-8">
            <a href="{{ Route::has('pelanggan.profil-order') ? route('pelanggan.profil-order') : url('/akun/pesanan') }}" 
               class="w-full sm:w-auto sm:min-w-[200px] flex items-center justify-center gap-2.5 bg-[#111] text-white border border-[#111] text-xs font-bold tracking-[0.12em] uppercase py-4 px-6 rounded-md hover:bg-[#333] transition-all">
                <i class="fa-solid fa-box-open text-sm"></i> Lacak Pesanan
            </a>
            
            <a href="{{ Route::has('pelanggan.katalog') ? route('pelanggan.katalog') : url('/katalog') }}" 
               class="w-full sm:w-auto sm:min-w-[200px] flex items-center justify-center gap-2.5 bg-white text-[#111] border border-gray-200 text-xs font-bold tracking-[0.12em] uppercase py-4 px-6 rounded-md hover:bg-gray-50 hover:border-gray-300 transition-all">
                Lanjut Belanja <i class="fa-solid fa-arrow-right text-sm"></i>
            </a>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        sessionStorage.removeItem('tanken_cart');
        
        window.dispatchEvent(new Event('cartUpdated'));
    });
</script>
@endpush