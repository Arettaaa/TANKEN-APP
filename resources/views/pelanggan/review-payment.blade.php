@extends('layouts.main')

@section('title', 'Peninjauan Pesanan — TANKEN')

@push('styles')
<style>
    /* Step indicator - Sesuai Screenshot Asli */
    .step-item { display: flex; flex-direction: column; align-items: center; gap: 8px; width: 90px; flex-shrink: 0; }
    .step-box {
        width: 40px; height: 40px; border-radius: 8px; border: 2px solid #e5e7eb; background: #fff;
        display: flex; align-items: center; justify-content: center; font-size: 0.9rem; font-weight: 700;
        color: #9ca3af; transition: all 0.2s;
    }
    .step-box.active, .step-box.done { background: #111; border-color: #111; color: #fff; }
    .step-label { font-size: 0.65rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: #9ca3af; text-align: center; }
    .step-label.active, .step-label.done { color: #111; }
    
    .step-line { flex: 1; height: 2px; background: #e5e7eb; margin-top: 20px; min-width: 20px; }
    .step-line.done { background: #111; }

    /* Summary card */
    .summary-card { border: 1.5px solid #e5e7eb; border-radius: 8px; padding: 22px; position: sticky; top: 80px; }
    .summary-divider { border: none; border-top: 1.5px solid #f3f4f6; margin: 14px 0; }

    /* Review Boxes */
    .review-box {
        border: 1.5px solid #e5e7eb; border-radius: 8px; padding: 20px;
        background: #fff; margin-bottom: 24px;
    }
    .review-label {
        font-size: 0.65rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase;
        color: #9ca3af; margin-bottom: 12px; display: block;
    }

    /* Buttons */
    .continue-btn {
        width: 100%; padding: 14px; background: #111; color: #fff; border: 1.5px solid #111; border-radius: 6px;
        font-family: 'Inter', sans-serif; font-size: 0.75rem; font-weight: 700; letter-spacing: 0.12em;
        text-transform: uppercase; cursor: pointer; transition: background 0.2s;
    }
    .continue-btn:hover { background: #333; }
    .back-btn {
        width: 100%; padding: 14px; background: #fff; color: #111; border: 1.5px solid #e5e7eb; border-radius: 6px;
        font-family: 'Inter', sans-serif; font-size: 0.75rem; font-weight: 700; letter-spacing: 0.12em;
        text-transform: uppercase; cursor: pointer; transition: border-color 0.2s, background 0.2s; text-align: center;
    }
    .back-btn:hover { border-color: #111; background: #fafafa; }
</style>
@endpush

@section('content')

<div class="bg-white min-h-screen">
    <div class="max-w-7xl mx-auto px-5 sm:px-6 lg:px-10 py-6 sm:py-10">

        <p class="text-[10px] sm:text-xs font-bold tracking-widest uppercase text-gray-400 mb-1 sm:mb-2">Pembelian</p>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mb-6 sm:mb-8">Checkout</h1>

        {{-- ===== STEP INDICATOR ===== --}}
        <div class="flex items-start mb-10 w-full">
            <div class="step-item">
                <div class="step-box done"><i class="fa-solid fa-check"></i></div>
                <span class="step-label done">Pengiriman</span>
            </div>
            <div class="step-line done mx-2 sm:mx-4"></div>
            <div class="step-item">
                <div class="step-box done"><i class="fa-solid fa-check"></i></div>
                <span class="step-label done">Pembayaran</span>
            </div>
            <div class="step-line done mx-2 sm:mx-4"></div>
            <div class="step-item">
                <div class="step-box active">3</div>
                <span class="step-label active">Peninjauan</span>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-6 lg:gap-8 items-start">

            {{-- ===== KIRI: DETAIL REVIEW ===== --}}
            <div class="flex-1 w-full min-w-0">
                <div class="border border-gray-200 rounded-lg p-5 sm:p-7">

                    <p class="text-[10px] sm:text-xs font-bold tracking-widest uppercase text-gray-400 mb-1">Langkah 3</p>
                    <h2 class="text-base sm:text-lg font-extrabold text-gray-900 mb-5 sm:mb-6">Review Order</h2>

                    {{-- 1. Shipping Address --}}
                    <span class="review-label">Alamat Pengiriman</span>
                    <div class="review-box bg-gray-50/50 border-gray-100">
                        <p class="text-sm font-semibold text-gray-900 mb-1">{{ $shippingAddress['name'] }}</p>
                        <p class="text-sm text-gray-500 leading-relaxed">{{ $shippingAddress['address'] }}</p>
                        <p class="text-sm text-gray-500 leading-relaxed">{{ $shippingAddress['city_zip'] }}</p>
                        <p class="text-sm text-gray-500 leading-relaxed mt-1">{{ $shippingAddress['phone'] }}</p>
                    </div>

                    {{-- 2. Order Items --}}
                    <span class="review-label">Order Items</span>
                    <div class="review-box">
                        <div class="flex flex-col gap-4">
                            @foreach($cartItems as $item)
                            <div class="flex items-center gap-4">
                                @if(isset($item['image']) && $item['image'])
                                    <img src="{{ $item['image'] }}" onerror="this.onerror=null;this.src='{{ asset('images/men-home.jpg') }}';" alt="{{ $item['name'] }}" class="w-16 h-20 object-cover rounded-md bg-gray-100 flex-shrink-0">
                                @else
                                    <div class="w-16 h-20 rounded-md bg-gray-100 flex-shrink-0"></div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 leading-snug truncate">{{ $item['name'] }}</p>
                                    <p class="text-xs text-gray-400 mt-1">Ukuran: {{ $item['size'] }} × {{ $item['qty'] }}</p>
                                </div>
                                <span class="text-sm font-bold text-gray-900 flex-shrink-0">
                                    Rp {{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}
                                </span>
                            </div>
                            @if(!$loop->last) <hr class="border-gray-100 my-1"> @endif
                            @endforeach
                        </div>
                    </div>

                    {{-- BUTTONS ACTION --}}
                    <form action="{{ route('pelanggan.checkout.place-order') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-2 gap-3 sm:gap-4 mt-8">
                            <a href="{{ route('pelanggan.checkout.payment') }}" class="back-btn flex items-center justify-center gap-2">
                                <i class="fa-solid fa-arrow-left text-xs hidden sm:inline-block"></i> Kembali
                            </a>
                            <button type="submit" class="continue-btn">
                                Buat Pesanan
                            </button>
                        </div>
                    </form>

                </div>
            </div>

            {{-- ===== KANAN: SUMMARY ===== --}}
            <div class="w-full lg:w-80 flex-shrink-0 mt-2 lg:mt-0">
                <div class="summary-card">
                    <p class="text-[10px] sm:text-xs font-bold tracking-widest uppercase text-gray-400 mb-1">Ringkasan</p>
                    <h2 class="font-extrabold text-gray-900 text-sm sm:text-base mb-4 sm:mb-5">Order Summary</h2>

                    <div class="flex flex-col gap-3 mb-4">
                        @foreach($cartItems as $item)
                        <div class="flex items-center gap-3">
                            @if(isset($item['image']) && $item['image'])
                                <img src="{{ $item['image'] }}" onerror="this.onerror=null;this.src='{{ asset('images/men-home.jpg') }}';" alt="{{ $item['name'] }}" class="w-10 h-12 sm:w-12 sm:h-14 object-cover rounded-md bg-gray-100 flex-shrink-0">
                            @else
                                <div class="w-10 h-12 sm:w-12 sm:h-14 rounded-md bg-gray-100 flex-shrink-0"></div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <p class="text-xs sm:text-sm font-semibold text-gray-900 leading-snug truncate">{{ $item['name'] }}</p>
                                <p class="text-[10px] sm:text-xs text-gray-400 mt-0.5">{{ $item['size'] }} × {{ $item['qty'] }}</p>
                            </div>
                            <span class="text-xs sm:text-sm font-bold text-gray-900 flex-shrink-0">
                                Rp {{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}
                            </span>
                        </div>
                        @endforeach
                    </div>

                    <hr class="summary-divider">

                    <div class="flex flex-col gap-2.5 text-xs sm:text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Subtotal ({{ collect($cartItems)->sum('qty') }} item)</span>
                            <span class="font-semibold text-gray-800">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Ongkos Kirim</span>
                            <span class="font-semibold text-gray-800">Rp {{ number_format($shippingCost, 0, ',', '.') }}</span>
                        </div>
                        
                        {{-- Row Info Diskon Voucher --}}
                        @if($voucherDiscount > 0)
                        <div class="flex justify-between items-center text-green-600">
                            <span class="font-medium">Diskon Voucher</span>
                            <span class="font-bold">– Rp {{ number_format($voucherDiscount, 0, ',', '.') }}</span>
                        </div>
                        @endif
                    </div>

                    <hr class="summary-divider">

                    <div class="flex justify-between items-center">
                        <span class="font-extrabold text-gray-900 text-sm sm:text-base">Total</span>
                        <span class="font-extrabold text-gray-900 text-base sm:text-lg">
                            Rp {{ number_format($total, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Animasi tombol saat disubmit agar terlihat memproses:
    document.querySelector('form').addEventListener('submit', function() {
        const btn = this.querySelector('.continue-btn');
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Memproses...';
        btn.disabled = true;
        btn.classList.add('opacity-80', 'cursor-not-allowed');
    });
</script>
@endpush