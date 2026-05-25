@extends('layouts.main')

@section('title', 'Checkout — TANKEN')

@push('styles')
<style>
    /* Step indicator */
    .step-wrap {
        display: flex;
        align-items: flex-start;
        gap: 0;
    }

    .step-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
        position: relative;
    }

    .step-circle {
        width: 34px;
        height: 34px;
        border-radius: 6px;
        border: 2px solid #e5e7eb;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        font-weight: 700;
        color: #9ca3af;
        transition: all 0.2s;
        flex-shrink: 0;
        z-index: 1;
    }

    .step-circle.active {
        background: #111;
        border-color: #111;
        color: #fff;
    }

    .step-circle.done {
        background: #111;
        border-color: #111;
        color: #fff;
    }

    .step-label {
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: #9ca3af;
    }

    .step-label.active {
        color: #111;
    }

    .step-label.done {
        color: #111;
    }

    .step-line {
        flex: 1;
        height: 2px;
        background: #e5e7eb;
        margin-top: 17px;
        min-width: 60px;
    }

    .step-line.done {
        background: #111;
    }

    /* Form */
    .form-label {
        display: block;
        font-size: 0.68rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: #9ca3af;
        margin-bottom: 6px;
    }

    .form-input {
        width: 100%;
        padding: 12px 14px;
        border: 1.5px solid #e5e7eb;
        border-radius: 6px;
        font-family: 'Inter', sans-serif;
        font-size: 0.875rem;
        color: #111;
        outline: none;
        transition: border-color 0.2s;
        background: #fff;
    }

    .form-input:focus {
        border-color: #111;
    }

    .form-input::placeholder {
        color: #c5c5c5;
    }

    /* Saved address pill */
    .saved-address-box {
        border: 1.5px solid #e5e7eb;
        border-radius: 6px;
        padding: 13px 16px;
        cursor: pointer;
        transition: border-color 0.2s;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .saved-address-box:hover {
        border-color: #111;
    }

    .saved-address-box.active {
        border-color: #111;
        background: #fafafa;
    }

    /* Shipping method */
    .ship-option {
        border: 1.5px solid #e5e7eb;
        border-radius: 6px;
        padding: 14px 16px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: border-color 0.2s, background 0.15s;
    }

    .ship-option:hover {
        border-color: #111;
    }

    .ship-option.active {
        background: #111;
        border-color: #111;
    }

    .ship-option.active .ship-name {
        color: #fff;
    }

    .ship-option.active .ship-days {
        color: rgba(255, 255, 255, 0.55);
    }

    .ship-option.active .ship-price {
        color: #fff;
    }

    /* Summary card */
    .summary-card {
        border: 1.5px solid #e5e7eb;
        border-radius: 8px;
        padding: 22px;
        position: sticky;
        top: 80px;
    }

    .summary-divider {
        border: none;
        border-top: 1.5px solid #f3f4f6;
        margin: 14px 0;
    }

    /* Continue btn */
    .continue-btn {
        width: 100%;
        padding: 15px;
        background: #111;
        color: #fff;
        border: none;
        border-radius: 6px;
        font-family: 'Inter', sans-serif;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        cursor: pointer;
        transition: background 0.2s;
    }

    .continue-btn:hover {
        background: #333;
    }

    .region-dropdown {
        display: none;
        position: absolute;
        top: calc(100% + 4px);
        left: 0;
        width: 100%;
        background: #fff;
        border: 1.5px solid #e5e7eb;
        border-radius: 8px;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        z-index: 50;
        overflow: hidden;
    }

    .region-dropdown.show {
        display: flex;
        flex-direction: column;
    }

    .region-tab {
        flex: 1;
        text-align: center;
        padding: 10px 4px;
        font-size: 0.7rem;
        font-weight: 700;
        color: #9ca3af;
        border-bottom: 2px solid transparent;
        cursor: pointer;
        transition: all 0.2s;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }

    .region-tab.active {
        color: #111;
        border-bottom-color: #111;
    }

    .region-tab:disabled {
        cursor: not-allowed;
        opacity: 0.4;
    }

    .region-list-item {
        padding: 10px 16px;
        font-size: 0.8125rem;
        font-weight: 500;
        color: #374151;
        cursor: pointer;
        transition: background 0.15s;
    }

    .region-list-item:hover {
        background: #f3f4f6;
        color: #111;
    }

    .region-search-wrapper {
        padding: 10px 16px;
        border-bottom: 1px solid #e5e7eb;
    }

    .region-search-input {
        width: 100%;
        padding: 8px 12px 8px 36px;
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        font-size: 0.8125rem;
        outline: none;
    }

    .region-search-input:focus {
        border-color: #111;
    }


    .voucher-trigger {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 16px;
    border: 1.5px solid #e5e7eb;
    border-radius: 8px;
    background: #fff;
    cursor: pointer;
    transition: all 0.2s;
}
.voucher-trigger:hover { border-color: #111; }

.custom-scrollbar::-webkit-scrollbar { width: 4px; }
.custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 4px; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }

.voucher-radio-wrap {
    border: 1.5px solid #e5e7eb;
    border-radius: 8px;
    padding: 14px;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    gap: 12px;
    align-items: flex-start;
    background: #fff;
}
.voucher-radio-wrap:hover { border-color: #d1d5db; background: #f9fafb; }
.voucher-radio-wrap.selected { border-color: #111; background: #fafafa; }

.custom-radio {
    width: 18px; height: 18px;
    border-radius: 50%;
    border: 1.5px solid #d1d5db;
    appearance: none;
    cursor: pointer;
    flex-shrink: 0;
    margin-top: 2px;
    position: relative;
}
.custom-radio:checked { border-color: #111; background: #111; }
.custom-radio:checked::after {
    content: '';
    position: absolute;
    top: 50%; left: 50%;
    transform: translate(-50%, -50%);
    width: 6px; height: 6px;
    border-radius: 50%;
    background: white;
}
</style>
@endpush

@section('content')

@php
// Data dummy (Jika data dari controller belum ada)
$cartItems = $cartItems ?? collect([
    ['id'=>1, 'name'=>'Sport Active Joggers', 'sku'=>'TKN-002', 'image'=>'men-home2.jpg', 'size'=>'XS', 'qty'=>1, 'price'=>799000],
]);

$shippingCost = 130000; // default SiCepat
$subtotal = collect($cartItems)->sum(fn($i) => $i['price'] * $i['qty']);

// Total hanya Subtotal + Ongkir
$total = $subtotal + $shippingCost;

$shippingOptions = $shippingOptions ?? [
    ['id'=>'jne', 'name'=>'JNE Regular', 'days'=>'2-3 hari', 'price'=>150000],
    ['id'=>'jnt', 'name'=>'J&T Express', 'days'=>'2-4 hari', 'price'=>120000],
    ['id'=>'sicepat','name'=>'SiCepat Reguler', 'days'=>'2-3 hari', 'price'=>130000, 'default'=>true],
    ['id'=>'anteraja','name'=>'AnterAja Standard','days'=>'3-4 hari', 'price'=>110000],
];
@endphp

<div class="bg-white min-h-screen">
    <div class="max-w-7xl mx-auto px-6 lg:px-10 py-10">

        <p class="text-xs font-bold tracking-widest uppercase text-gray-400 mb-2">Pembelian</p>
        <h1 class="text-3xl font-extrabold text-gray-900 mb-8">Checkout</h1>

        {{-- ===== STEP INDICATOR ===== --}}
        <div class="flex items-start mb-10">
            <div class="step-item">
                <div class="step-circle active">1</div>
                <span class="step-label active">Pengiriman</span>
            </div>
            <div class="step-line flex-1 mx-2"></div>
            <div class="step-item">
                <div class="step-circle">2</div>
                <span class="step-label">Pembayaran</span>
            </div>
            <div class="step-line flex-1 mx-2"></div>
            <div class="step-item">
                <div class="step-circle">3</div>
                <span class="step-label">Peninjauan</span>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-6 items-start">

            {{-- ===== KIRI: FORM ===== --}}
            <div class="flex-1 min-w-0">
                <div class="border border-gray-200 rounded-lg p-7">

                    <p class="text-xs font-bold tracking-widest uppercase text-gray-400 mb-1">Langkah 1</p>
                    <h2 class="text-lg font-extrabold text-gray-900 mb-6">Informasi Pengiriman</h2>

                    @if(isset($addresses) && $addresses->count() > 0)
                    <div class="mb-6">
                        <label class="form-label mb-2">Pilih Alamat Tersimpan</label>
                        <div class="flex flex-col gap-2" id="addressOptions">
                            @foreach($addresses as $addr)
                            <div class="saved-address-box {{ $addr->is_default ? 'active' : '' }}"
                                onclick="selectAddress(this, {{ $addr->id }}, '{{ $addr->city_id }}')">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#111"
                                    stroke-width="1.8" width="18" height="18" class="flex-shrink-0">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                                    <circle cx="12" cy="10" r="3" />
                                </svg>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-gray-900">
                                        {{ $addr->name }}
                                        @if($addr->is_default)
                                        <span
                                            class="text-[10px] font-bold tracking-widest uppercase bg-gray-100 text-gray-500 px-2 py-0.5 rounded ml-1">Utama</span>
                                        @endif
                                    </p>
                                    <p class="text-xs text-gray-400 mt-0.5">{{ $addr->street }}, {{ $addr->region }} {{
                                        $addr->postal }}</p>
                                    <p class="text-xs text-gray-400">{{ $addr->phone }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <button type="button" onclick="toggleNewAddressForm()"
                            class="mt-3 text-xs font-bold tracking-widest uppercase text-gray-500 hover:text-gray-900 transition-colors underline underline-offset-2">
                            + Gunakan alamat lain
                        </button>
                    </div>
                    @endif
                    <form action="{{ route('pelanggan.checkout.proses') }}" method="POST" id="checkoutForm">
                        @csrf
                        <input type="hidden" name="address_id" id="selectedAddressId" value="{{ $defaultAddress->id ?? '' }}">
                        <input type="hidden" name="shipping_method" id="shippingMethodInput" value="">
                        <input type="hidden" name="shipping_cost" id="shippingCostInput" value="">
                        <input type="hidden" name="city_id" id="checkoutCityId" value="{{ $defaultAddress->city_id ?? '' }}">
                        <input type="hidden" name="shipping_days" id="shippingDaysInput" value="">
                        <input type="hidden" name="voucher_discount" id="voucherDiscountInput" value="0">

                        <div id="newAddressForm" class="{{ (isset($addresses) && $addresses->count() > 0) ? 'hidden' : '' }}">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="form-label">Nama Lengkap</label>
                                    <input type="text" name="full_name" class="form-input bg-gray-50 cursor-not-allowed"
                                        value="{{ auth()->user()->name ?? '' }}" readonly>
                                </div>
                                <div>
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-input bg-gray-50 cursor-not-allowed"
                                        value="{{ auth()->user()->email ?? '' }}" readonly>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Nomor Telepon</label>
                                <input type="tel" name="phone" class="form-input bg-gray-50 cursor-not-allowed"
                                    value="{{ auth()->user()->phone ?? '' }}" readonly>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Alamat <span class="text-red-400">*</span></label>
                                <input type="text" name="address" class="form-input" value="{{ old('address') }}"
                                    placeholder="Nama jalan, nomor rumah, RT/RW">
                            </div>

                            <div class="mb-4 relative">
                                <label class="form-label">Wilayah <span class="text-red-400">*</span></label>
                                <div id="regionTrigger" class="form-input cursor-pointer flex justify-between items-center" onclick="toggleRegionDropdown()">
                                    <span id="regionDisplayText" class="text-gray-400 truncate pr-4">Pilih Provinsi, Kota, Kecamatan, Kelurahan</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="14" height="14" class="flex-shrink-0 text-gray-400">
                                        <path d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                                <input type="hidden" id="newAddrRegion" name="new_region" value="">
                                <input type="hidden" id="newAddrCityId" name="new_city_id" value="">

                                <div id="regionDropdown" class="region-dropdown">
                                    <div class="region-search-wrapper relative">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="13" height="13" class="absolute left-7 top-1/2 -translate-y-1/2 text-gray-400">
                                            <circle cx="11" cy="11" r="8" /><path d="m21 21-4.35-4.35" />
                                        </svg>
                                        <input type="text" id="regionSearchInput" class="region-search-input" placeholder="Cari wilayah..." oninput="handleSearch(this.value)">
                                    </div>
                                    <div class="flex border-b border-gray-100 bg-gray-50/50">
                                        <button type="button" class="region-tab active" id="tab-0" onclick="changeStep(0)">Provinsi</button>
                                        <button type="button" class="region-tab" id="tab-1" onclick="changeStep(1)" disabled>Kota</button>
                                        <button type="button" class="region-tab" id="tab-2" onclick="changeStep(2)" disabled>Kecamatan</button>
                                        <button type="button" class="region-tab" id="tab-3" onclick="changeStep(3)" disabled>Kelurahan</button>
                                    </div>
                                    <div class="p-1 max-h-48 overflow-y-auto" id="regionListContainer"></div>
                                </div>
                            </div>

                            <div class="mb-7">
                                <label class="form-label">Kode Pos <span class="text-red-400">*</span></label>
                                <input type="text" name="zip_code" id="zip_code_input" class="form-input"
                                    value="{{ old('zip_code') }}" placeholder="12720">
                            </div>
                        </div>

                        <div class="mt-4">
                            <label class="form-label mb-3">Metode Pengiriman</label>
                            <div id="shippingLoading" class="hidden text-xs text-gray-400 py-3 text-center">
                                <svg class="animate-spin inline w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                                </svg>
                                Menghitung ongkos kirim...
                            </div>
                            <div id="shippingPlaceholder" class="text-xs text-gray-400 border border-dashed border-gray-200 rounded-lg py-4 text-center">
                                Pilih alamat tujuan dulu untuk melihat opsi pengiriman
                            </div>
                            <div class="flex flex-col gap-2" id="shippingOptions"></div>
                        </div>

                        <button type="submit" class="continue-btn mt-8">Lanjutkan</button>
                    </form>
                </div>
            </div>

            {{-- ===== KANAN: SUMMARY ===== --}}
            <div class="w-full lg:w-72 flex-shrink-0">
                <div class="summary-card">
                    <p class="text-xs font-bold tracking-widest uppercase text-gray-400 mb-1">Ringkasan</p>
                    <h2 class="font-extrabold text-gray-900 text-base mb-5">Ringkasan Pesanan</h2>

                    <div class="flex flex-col gap-3 mb-4">
                        @foreach($cartItems as $item)
                        @php $imgPath = $item['image'] ?? null; @endphp
                        <div class="flex items-center gap-3">
                            @if($imgPath)
                            <img src="{{ $imgPath }}" alt="{{ $item['name'] }}"
                                class="w-12 h-14 object-cover rounded-md bg-gray-100 flex-shrink-0">
                            @else
                            <div class="w-12 h-14 rounded-md bg-gray-100 flex-shrink-0"></div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 leading-snug truncate">{{ $item['name'] }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $item['size'] }} × {{ $item['qty'] }}</p>
                            </div>
                            <span class="text-sm font-bold text-gray-900 flex-shrink-0">
                                Rp {{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}
                            </span>
                        </div>
                        @endforeach
                    </div>

                    <hr class="summary-divider">

                    <div class="flex flex-col gap-2.5 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Subtotal ({{ collect($cartItems)->sum('qty') }} item)</span>
                            <span class="font-semibold text-gray-800">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Ongkos Kirim</span>
                            <span class="font-semibold text-gray-800" id="summaryShipping">
                                {{ isset($defaultAddress) ? '...' : 'Pilih alamat dulu' }}
                            </span>
                        </div>

                        {{-- VOUCHER INLINE --}}
<div class="mb-4 mt-2">
    <button type="button" class="voucher-trigger" onclick="toggleVoucherSection()">
        <div class="flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#10b981" stroke-width="2" width="20" height="20">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z" />
            </svg>
            <div class="text-left">
                <p class="text-[10px] font-bold tracking-widest uppercase text-gray-500" id="activeVoucherTitle">Promo & Voucher</p>
                <p class="text-sm font-semibold text-gray-900 mt-0.5" id="activeVoucherDesc">Makin Hemat</p>
            </div>
        </div>
        <svg id="voucherChevron" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="16" height="16" class="text-gray-400 transition-transform duration-300">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <div id="voucherCollapsible" class="hidden mt-3 border border-gray-100 rounded-lg p-4 bg-gray-50/50">
        {{-- Manual input --}}
        <div class="flex">
            <input type="text" id="manualVoucherInput" 
                class="w-full px-3 py-2.5 border border-gray-200 rounded-l-md text-xs font-semibold uppercase outline-none focus:border-black transition-colors" 
                placeholder="KODE VOUCHER">
            <button type="button" onclick="applyManualVoucher()" 
                class="px-4 bg-black text-white text-[11px] font-bold tracking-widest uppercase rounded-r-md hover:bg-gray-800 transition-colors whitespace-nowrap">
                Terapkan
            </button>
        </div>
        <p class="text-[10px] text-gray-400 mt-1.5">Atau pilih voucher kamu di bawah</p>
        <p id="inlineVoucherMsg" class="text-[11px] font-medium hidden mt-2"></p>

        <hr class="border-gray-200 my-4">
        <div class="flex items-center justify-between mb-3">
            <p class="text-[10px] font-bold tracking-widest uppercase text-gray-500">Voucher Saya</p>
            <button onclick="clearVoucher()" class="text-[10px] font-bold text-red-500 hover:text-red-700 hidden" id="clearVoucherBtn">HAPUS</button>
        </div>

            <div class="flex flex-col gap-3 max-h-56 overflow-y-auto pr-1 custom-scrollbar">
                @forelse($userVouchers as $uv)
                @php
                    $v = $uv->voucher;
                    $valStr = $v->type === 'fixed'
                        ? 'Potongan Rp ' . number_format($v->value, 0, ',', '.')
                        : 'Diskon ' . $v->value . '%';
                    $discount = $v->type === 'fixed' ? $v->value : 0;
                @endphp
                <label class="voucher-radio-wrap" onclick="selectVoucherRadio(this)">
                    <input type="radio" name="voucher_selection" class="custom-radio" 
                        value="{{ $v->code }}" 
                        data-label="{{ $valStr }}" 
                        data-discount="{{ $discount }}">
                    <div class="flex-1">
                        <p class="text-xs font-bold text-gray-900 mb-1">{{ $valStr }}</p>
                        <p class="text-[11px] text-gray-500 leading-relaxed">
                            {{ $v->description ?: 'Berlaku untuk semua produk.' }}
                            @if($v->min_purchase > 0)
                                Min. Rp {{ number_format($v->min_purchase, 0, ',', '.') }}.
                            @endif
                        </p>
                        @if($v->expires_at)
                        <p class="text-[10px] text-gray-400 mt-1">Berlaku hingga {{ $v->expires_at->format('d M Y') }}</p>
                        @endif
                    </div>
                </label>
                @empty
                <p class="text-xs text-gray-400 text-center py-2">Tidak ada voucher tersedia.</p>
                @endforelse
            </div>
        </div>
    </div>
                        
                        {{-- Row Diskon Voucher dari Session --}}
                        <div class="flex justify-between items-center hidden" id="checkoutVoucherRow">
                            <span class="text-green-600 font-medium">Diskon Voucher</span>
                            <span class="font-bold text-green-600" id="checkoutVoucherDiscount">– Rp 0</span>
                        </div>
                    </div>

                    <hr class="summary-divider">

                    <div class="flex justify-between items-center">
                        <span class="font-extrabold text-gray-900">Total</span>
                        <span class="font-extrabold text-gray-900 text-lg" id="summaryTotal">
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
    const subtotalBase = {{ $subtotal }};
    let currentShippingPrice = 0;
    let voucherDiscount = 0;

    function formatRp(val) {
        return 'Rp ' + Math.round(val).toLocaleString('id-ID');
    }

    updateTotalSummary();
    // Auto-apply voucher dari keranjang
const savedVoucherCode     = '{{ $activeVoucherCode }}';
const savedVoucherDiscount = {{ $activeVoucherDiscount }};

if (savedVoucherCode && savedVoucherDiscount > 0) {
    voucherDiscount = savedVoucherDiscount;
    document.getElementById('voucherDiscountInput').value = savedVoucherDiscount;
    document.getElementById('activeVoucherTitle').textContent = savedVoucherCode;
    document.getElementById('activeVoucherDesc').textContent  = 'Diskon berhasil dipakai';
    document.getElementById('activeVoucherDesc').classList.replace('text-gray-900', 'text-green-600');
    document.getElementById('checkoutVoucherRow').classList.remove('hidden');
    document.getElementById('checkoutVoucherDiscount').textContent = '– ' + formatRp(savedVoucherDiscount);
    document.getElementById('clearVoucherBtn').classList.remove('hidden');

    // Highlight radio button yang sesuai
    document.querySelectorAll('input[name="voucher_selection"]').forEach(r => {
        if (r.value === savedVoucherCode) {
            r.checked = true;
            r.closest('.voucher-radio-wrap').classList.add('selected');
        }
    });

    updateTotalSummary();
}
    function updateTotalSummary() {
        let finalTotal = subtotalBase + currentShippingPrice - voucherDiscount;
        if (finalTotal < 0) finalTotal = 0;
        document.getElementById('summaryTotal').textContent = formatRp(finalTotal);
    }

    // ===== LOAD ONGKIR DARI API =====
    async function loadOngkir(cityId) {
        if (!cityId) return;

        document.getElementById('shippingLoading').classList.remove('hidden');
        document.getElementById('shippingPlaceholder').classList.add('hidden');
        document.getElementById('shippingOptions').innerHTML = '';
        document.getElementById('shippingMethodInput').value = '';
        document.getElementById('shippingCostInput').value   = '';
        
        currentShippingPrice = 0;
        document.getElementById('summaryShipping').textContent = '...';
        updateTotalSummary();

        try {
            const res  = await fetch(`/akun/checkout/ongkir?city_id=${cityId}`);
            const data = await res.json();

            document.getElementById('shippingLoading').classList.add('hidden');

            if (!data.length) {
                document.getElementById('shippingPlaceholder').classList.remove('hidden');
                document.getElementById('shippingPlaceholder').textContent = 'Ongkir tidak tersedia untuk kota ini.';
                return;
            }

            renderShippingOptions(data);

        } catch(e) {
            document.getElementById('shippingLoading').classList.add('hidden');
            document.getElementById('shippingPlaceholder').classList.remove('hidden');
            document.getElementById('shippingPlaceholder').textContent = 'Gagal memuat ongkir. Coba lagi.';
        }
    }

    function renderShippingOptions(options) {
        const container = document.getElementById('shippingOptions');
        container.innerHTML = '';

        options.forEach((opt, idx) => {
            const isFirst = idx === 0;
            const div = document.createElement('div');
            div.className = `ship-option ${isFirst ? 'active' : ''}`;
            div.innerHTML = `
                <div>
                    <p class="ship-name font-semibold text-sm ${isFirst ? 'text-white' : 'text-gray-900'}">
                        ${opt.courier} - ${opt.service}
                    </p>
                    <p class="ship-days text-xs mt-0.5 ${isFirst ? 'text-white/55' : 'text-gray-400'}">
                        ${opt.days}
                    </p>
                </div>
                <span class="ship-price font-bold text-sm ${isFirst ? 'text-white' : 'text-gray-900'}">
                    ${formatRp(opt.price)}
                </span>
            `;
            div.onclick = () => selectShipping(div, `${opt.courier}-${opt.service}`, opt.price, opt.days);
            container.appendChild(div);

            // Auto-pilih yang pertama
            if (isFirst) {
                document.getElementById('shippingMethodInput').value = `${opt.courier}-${opt.service}`;
                document.getElementById('shippingCostInput').value   = opt.price;
                document.getElementById('shippingDaysInput').value   = opt.days;
                
                currentShippingPrice = opt.price;
                document.getElementById('summaryShipping').textContent = formatRp(opt.price);
                updateTotalSummary();
            }
        });
    }

    function selectShipping(el, id, price, days) {
        document.querySelectorAll('.ship-option').forEach(o => {
            o.classList.remove('active');
            o.querySelector('.ship-name').classList.replace('text-white', 'text-gray-900');
            o.querySelector('.ship-days').classList.remove('text-white/55');
            o.querySelector('.ship-days').classList.add('text-gray-400');
            o.querySelector('.ship-price').classList.replace('text-white', 'text-gray-900');
        });

        el.classList.add('active');
        el.querySelector('.ship-name').classList.replace('text-gray-900', 'text-white');
        el.querySelector('.ship-days').classList.add('text-white/55');
        el.querySelector('.ship-days').classList.remove('text-gray-400');
        el.querySelector('.ship-price').classList.replace('text-gray-900', 'text-white');

        document.getElementById('shippingMethodInput').value   = id;
        document.getElementById('shippingCostInput').value     = price;
        document.getElementById('shippingDaysInput').value     = days;
        
        currentShippingPrice = price;
        document.getElementById('summaryShipping').textContent = formatRp(price);
        updateTotalSummary();
    }

    function selectAddress(el, id, cityId) {
        document.querySelectorAll('.saved-address-box').forEach(b => b.classList.remove('active'));
        el.classList.add('active');
        document.getElementById('selectedAddressId').value = id;
        document.getElementById('checkoutCityId').value    = cityId;
        loadOngkir(cityId);
    }

    let currentStep     = 0;
    let currentListData = [];
    let regionState     = {
        prov: {id:'', name:''},
        kota: {id:'', name:''},
        kec:  {id:'', name:''},
        kel:  {id:'', name:''}
    };

    function updateRegionDisplayText() {
        const parts = [];
        if (regionState.prov.id) parts.push(regionState.prov.name);
        if (regionState.kota.id) parts.push(regionState.kota.name);
        if (regionState.kec.id) parts.push(regionState.kec.name);
        if (regionState.kel.id) parts.push(regionState.kel.name);

        const displayText = document.getElementById('regionDisplayText');
        if (parts.length > 0) {
            displayText.innerText = parts.join(', ');
            displayText.classList.replace('text-gray-400', 'text-gray-900');
        } else {
            displayText.innerText = 'Pilih Provinsi, Kota, Kecamatan, Kelurahan';
            displayText.classList.replace('text-gray-900', 'text-gray-400');
        }
    }

    window.toggleRegionDropdown = function() {
        const dd = document.getElementById('regionDropdown');
        if (dd.classList.contains('show')) closeRegionDropdown();
        else openRegionDropdown();
    };

    function openRegionDropdown() {
        document.getElementById('regionDropdown').classList.add('show');
        document.getElementById('regionTrigger').classList.add('active-dropdown');
        if (currentListData.length === 0) loadStepData(0);
        setTimeout(() => document.getElementById('regionSearchInput').focus(), 100);
    }

    function closeRegionDropdown() {
        document.getElementById('regionDropdown').classList.remove('show');
        document.getElementById('regionTrigger').classList.remove('active-dropdown');
        document.getElementById('regionSearchInput').value = '';
    }

    async function loadStepData(step) {
        const container = document.getElementById('regionListContainer');
        container.innerHTML = '<div class="p-6 text-center text-xs text-gray-400">Loading...</div>';

        let type = '', id = '';
        if (step === 0) type = 'provinsi';
        if (step === 1) { type = 'kota';       id = regionState.prov.id; }
        if (step === 2) { type = 'kecamatan';  id = regionState.kota.id; }
        if (step === 3) { type = 'kelurahan';  id = regionState.kec.id;  }

        try {
            const res  = await fetch(`/wilayah?type=${type}&id=${id}`);
            const json = await res.json();
            currentListData = json.value.map(i => ({ id: i.id, name: i.name.toUpperCase() }));
            renderRegionList(currentListData);
        } catch {
            container.innerHTML = '<p class="p-3 text-red-500 text-center text-xs">Gagal load data</p>';
        }
    }

    function renderRegionList(data) {
        const container = document.getElementById('regionListContainer');
        container.innerHTML = '';
        if (!data.length) {
            container.innerHTML = '<p class="p-3 text-sm text-gray-400 text-center">Data tidak tersedia.</p>';
            return;
        }
        data.forEach(item => {
            const div = document.createElement('div');
            div.className = 'region-list-item';
            div.innerText = item.name;
            div.onclick   = () => handleSelectRegion(item);
            container.appendChild(div);
        });
    }

    window.handleSearch = function(keyword) {
        const filtered = currentListData.filter(i => i.name.toLowerCase().includes(keyword.toLowerCase()));
        renderRegionList(filtered);
    };

    function handleSelectRegion(item) {
        document.getElementById('regionSearchInput').value = '';
        
        if (currentStep === 0) {
            regionState.prov = item;
            regionState.kota = regionState.kec = regionState.kel = {id:'', name:''};
            updateRegionDisplayText();
            currentStep = 1;
        } else if (currentStep === 1) {
            regionState.kota = item;
            regionState.kec  = regionState.kel = {id:'', name:''};
            updateRegionDisplayText();
            currentStep = 2;
        } else if (currentStep === 2) {
            regionState.kec = item;
            regionState.kel = {id:'', name:''};
            updateRegionDisplayText();
            currentStep = 3;
        } else if (currentStep === 3) {
            regionState.kel = item;
            updateRegionDisplayText();
            finishRegionSelection();
            return;
        }
        
        updateTabsUI();
        loadStepData(currentStep);
    }

    window.changeStep = function(step) {
        if (step === 1 && !regionState.prov.id) return;
        if (step === 2 && !regionState.kota.id) return;
        if (step === 3 && !regionState.kec.id)  return;
        
        currentStep = step;
        document.getElementById('regionSearchInput').value = '';
        updateTabsUI();
        loadStepData(step);
    };

    function updateTabsUI() {
        document.getElementById('tab-1').disabled = !regionState.prov.id;
        document.getElementById('tab-2').disabled = !regionState.kota.id;
        document.getElementById('tab-3').disabled = !regionState.kec.id;
        for (let i = 0; i <= 3; i++) {
            document.getElementById('tab-' + i).classList.toggle('active', i === currentStep);
        }
    }

    function finishRegionSelection() {
        const full = `${regionState.prov.name}, ${regionState.kota.name}, ${regionState.kec.name}, ${regionState.kel.name}`;
        
        document.getElementById('newAddrRegion').value  = full;
        document.getElementById('newAddrCityId').value  = 'dist_' + regionState.kec.id;
        document.getElementById('checkoutCityId').value = 'dist_' + regionState.kec.id;

        fetchKodePos(regionState.kel.id);

        document.getElementById('selectedAddressId').value = '';
        document.querySelectorAll('.saved-address-box').forEach(b => b.classList.remove('active'));

        closeRegionDropdown();
        loadOngkir('dist_' + regionState.kec.id);
    }

    async function fetchKodePos(kelurahanId) {
        try {
            const res  = await fetch(`/wilayah?type=kelurahan_detail&id=${kelurahanId}`);
            const json = await res.json();
            if (json.value && json.value[0] && json.value[0].postal_code) {
                document.getElementById('zip_code_input').value = json.value[0].postal_code;
            }
        } catch(e) {}
    }

    function toggleNewAddressForm() {
        const form = document.getElementById('newAddressForm');
        form.classList.toggle('hidden');
        if (!form.classList.contains('hidden')) {
            document.getElementById('selectedAddressId').value = '';
            document.getElementById('checkoutCityId').value    = '';
            document.querySelectorAll('.saved-address-box').forEach(b => b.classList.remove('active'));
            document.getElementById('shippingOptions').innerHTML = '';
            document.getElementById('shippingPlaceholder').classList.remove('hidden');
            document.getElementById('shippingPlaceholder').textContent = 'Pilih alamat tujuan dulu untuk melihat opsi pengiriman';
            
            regionState = { prov: {id:'', name:''}, kota: {id:'', name:''}, kec: {id:'', name:''}, kel: {id:'', name:''} };
            currentStep = 0;
            updateTabsUI();
            updateRegionDisplayText();
        }
    }

    const defaultCityId = '{{ $defaultAddress->city_id ?? "" }}';
    if (defaultCityId) loadOngkir(defaultCityId);

    function applyCheckoutVoucher() {
    const code = document.getElementById('checkoutVoucherInput').value.toUpperCase().trim();
    const msg  = document.getElementById('checkoutVoucherMsg');

    if (!code) {
        msg.textContent = 'Masukkan kode voucher dulu.';
        msg.className   = 'text-[11px] font-medium mt-1.5 text-red-500 block';
        return;
    }

    fetch('{{ route("pelanggan.voucher.claim") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        },
        body: JSON.stringify({ code })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            // Ambil nilai diskon dari voucher
            fetch('/voucher/info?code=' + code)
                .then(r => r.json())
                .then(v => {
                    voucherDiscount = v.discount;
                    document.getElementById('voucherDiscountInput').value = voucherDiscount;
                    document.getElementById('checkoutVoucherRow').classList.remove('hidden');
                    document.getElementById('checkoutVoucherDiscount').textContent = '– ' + formatRp(voucherDiscount);
                    document.getElementById('clearCheckoutVoucherBtn').classList.remove('hidden');
                    msg.textContent = '✓ Voucher berhasil diterapkan!';
                    msg.className   = 'text-[11px] font-medium mt-1.5 text-green-600 block';
                    updateTotalSummary();
                });
        } else {
            msg.textContent = data.message;
            msg.className   = 'text-[11px] font-medium mt-1.5 text-red-500 block';
        }
    });
}

function clearCheckoutVoucher() {
    voucherDiscount = 0;
    document.getElementById('voucherDiscountInput').value = 0;
    document.getElementById('checkoutVoucherInput').value = '';
    document.getElementById('checkoutVoucherRow').classList.add('hidden');
    document.getElementById('clearCheckoutVoucherBtn').classList.add('hidden');
    document.getElementById('checkoutVoucherMsg').classList.add('hidden');
    updateTotalSummary();
}

function toggleVoucherSection() {
    const coll = document.getElementById('voucherCollapsible');
    const chev = document.getElementById('voucherChevron');
    coll.classList.contains('hidden')
        ? (coll.classList.remove('hidden'), chev.style.transform = 'rotate(180deg)')
        : (coll.classList.add('hidden'),    chev.style.transform = 'rotate(0deg)');
}

function selectVoucherRadio(labelEl) {
    document.querySelectorAll('.voucher-radio-wrap').forEach(w => w.classList.remove('selected'));
    labelEl.classList.add('selected');
    document.getElementById('manualVoucherInput').value = '';
    document.getElementById('inlineVoucherMsg').classList.add('hidden');
    confirmVoucherSelection(labelEl.querySelector('input'));
}

function applyManualVoucher() {
    const code = document.getElementById('manualVoucherInput').value.toUpperCase().trim();
    const msg  = document.getElementById('inlineVoucherMsg');

    document.querySelectorAll('input[name="voucher_selection"]').forEach(r => r.checked = false);
    document.querySelectorAll('.voucher-radio-wrap').forEach(w => w.classList.remove('selected'));

    if (!code) {
        msg.textContent = 'Masukkan kode voucher dulu.';
        msg.className   = 'mt-2 text-[11px] text-red-500 font-medium block';
        return;
    }

    fetch('/voucher/info?code=' + code)
        .then(r => r.json())
        .then(v => {
            if (v.discount > 0) {
                msg.textContent = '✓ Voucher diterapkan!';
                msg.className   = 'mt-2 text-[11px] text-green-600 font-bold block';
                confirmVoucherSelection(null, { code, discount: v.discount, label: code });
            } else {
                msg.textContent = 'Kode voucher tidak valid atau kedaluwarsa.';
                msg.className   = 'mt-2 text-[11px] text-red-500 font-medium block';
            }
        });
}

function confirmVoucherSelection(radioEl = null, manualData = null) {
    let discount = 0, label = '', code = '';

    if (manualData) {
        discount = manualData.discount;
        label    = manualData.label;
        code     = manualData.code;
    } else if (radioEl) {
        discount = parseInt(radioEl.dataset.discount);
        label    = radioEl.dataset.label;
        code     = radioEl.value;
    } else return;

    voucherDiscount = discount;
    document.getElementById('voucherDiscountInput').value = discount;
    document.getElementById('activeVoucherTitle').textContent = code;
    document.getElementById('activeVoucherDesc').textContent  = 'Diskon berhasil dipakai';
    document.getElementById('activeVoucherDesc').classList.replace('text-gray-900', 'text-green-600');
    document.getElementById('checkoutVoucherRow').classList.remove('hidden');
    document.getElementById('checkoutVoucherDiscount').textContent = '– ' + formatRp(discount);
    document.getElementById('clearVoucherBtn').classList.remove('hidden');

    updateTotalSummary();
}

function clearVoucher() {
    voucherDiscount = 0;
    document.getElementById('voucherDiscountInput').value = 0;
    document.getElementById('activeVoucherTitle').textContent = 'Promo & Voucher';
    document.getElementById('activeVoucherDesc').textContent  = 'Makin Hemat';
    document.getElementById('activeVoucherDesc').classList.replace('text-green-600', 'text-gray-900');
    document.getElementById('checkoutVoucherRow').classList.add('hidden');
    document.getElementById('clearVoucherBtn').classList.add('hidden');
    document.getElementById('manualVoucherInput').value = '';
    document.getElementById('inlineVoucherMsg').classList.add('hidden');
    document.querySelectorAll('input[name="voucher_selection"]').forEach(r => r.checked = false);
    document.querySelectorAll('.voucher-radio-wrap').forEach(w => w.classList.remove('selected'));
    updateTotalSummary();
}
</script>
@endpush