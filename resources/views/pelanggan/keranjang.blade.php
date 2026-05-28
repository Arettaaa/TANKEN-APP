@extends('layouts.main')

@section('title', 'Keranjang Belanja — TANKEN')

@push('styles')
<style>
    /* Hero banner */
    .cart-hero {
        position: relative;
        height: 300px;
        overflow: hidden;
        background-image: url('{{ asset("images/men-home.jpg") }}');
        background-size: cover;
        background-position: center 30%;
    }

    .cart-hero-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to bottom, rgba(0, 0, 0, 0.52) 0%, rgba(0, 0, 0, 0.62) 100%);
    }

    .cart-hero-content {
        position: relative;
        z-index: 1;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding: 0 0 36px 0;
    }

    .cart-hero-title {
        font-family: 'Inter', sans-serif;
        font-weight: 800;
        font-size: clamp(2.6rem, 6vw, 4rem);
        line-height: 1;
        letter-spacing: -0.03em;
        color: #fff;
    }

    /* Cart item card */
    .cart-item-card {
        border: 1.5px solid #e5e7eb;
        border-radius: 10px;
        padding: 18px;
        transition: border-color 0.2s, background 0.15s;
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .cart-item-card.is-checked {
        border-color: #111;
        background: #fafafa;
    }

    /* Product image */
    .item-img {
        width: 90px;
        height: 110px;
        border-radius: 8px;
        object-fit: cover;
        background: #f3f4f6;
        flex-shrink: 0;
    }

    .item-img-placeholder {
        width: 90px;
        height: 110px;
        border-radius: 8px;
        background: #f3f4f6;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Qty stepper */
    .qty-btn {
        width: 32px;
        height: 32px;
        border: 1.5px solid #e5e7eb;
        border-radius: 6px;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 1rem;
        font-weight: 600;
        color: #374151;
        transition: border-color 0.15s, background 0.15s;
        flex-shrink: 0;
    }

    .qty-btn:hover { border-color: #111; background: #f9f9f9; }
    .qty-btn:disabled { opacity: 0.35; cursor: not-allowed; }

    .qty-input {
        width: 40px;
        text-align: center;
        border: 1.5px solid #e5e7eb;
        border-radius: 6px;
        padding: 6px 4px;
        font-family: 'Inter', sans-serif;
        font-size: 0.875rem;
        font-weight: 600;
        outline: none;
        transition: border-color 0.2s;
        -moz-appearance: textfield; 
    }
    .qty-input:focus { border-color: #111; }
    
    .qty-input::-webkit-outer-spin-button,
    .qty-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* Delete btn */
    .delete-btn {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        border: none;
        background: transparent;
        color: #9ca3af;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.15s, color 0.15s;
        flex-shrink: 0;
    }

    .delete-btn:hover { background: #fee2e2; color: #dc2626; }

    /* Order summary card - FIXED LAYOUT */
    .summary-card {
        border: 1.5px solid #e5e7eb;
        border-radius: 10px;
        padding: 24px;
        position: sticky;
        top: 80px;
        max-height: calc(100vh - 110px); /* Mencegah kepanjangan */
        overflow-y: auto; /* Bisa di-scroll */
    }

    /* Scrollbar khusus untuk ringkasan biar cantik */
    .summary-card::-webkit-scrollbar { width: 4px; }
    .summary-card::-webkit-scrollbar-track { background: transparent; }
    .summary-card::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }

    /* Checkbox custom */
    .custom-check {
        width: 18px;
        height: 18px;
        border-radius: 4px;
        border: 1.5px solid #d1d5db;
        appearance: none;
        cursor: pointer;
        flex-shrink: 0;
        transition: background 0.15s, border-color 0.15s;
        display: grid;
        place-items: center;
    }

    .custom-check:checked {
        background: #111;
        border-color: #111;
    }

    .custom-check:checked::after {
        content: '';
        width: 10px;
        height: 10px;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='white' stroke-width='3'%3E%3Cpath d='M20 6L9 17l-5-5'/%3E%3C/svg%3E");
        background-size: contain;
        background-repeat: no-repeat;
        background-position: center;
    }

    /* Divider */
    .summary-divider {
        border: none;
        border-top: 1.5px solid #f3f4f6;
        margin: 14px 0;
    }

    /* ====== INLINE VOUCHER STYLES ====== */
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
        width: 18px;
        height: 18px;
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

    /* Delete Modal */
    .modal-overlay {
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease, visibility 0.3s ease;
    }
    .modal-overlay.active { opacity: 1; visibility: visible; }
    .modal-content {
        transform: scale(0.95) translateY(10px);
        opacity: 0;
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.3s ease;
    }
    .modal-overlay.active .modal-content { transform: scale(1) translateY(0); opacity: 1; }
</style>
@endpush

@section('content')

@php
$cartItems = $cartItems ?? collect([]);

// Hitung total item terpilih
$selectedItems = collect($cartItems)->where('checked', true);
$totalQty = $selectedItems->sum('qty');

// Subtotal murni (harga × qty)
$subtotalBefore = $selectedItems->sum(fn($i) => $i['price'] * $i['qty']);
$total = $subtotalBefore;

// Total absolute qty (seluruh item)
$absoluteTotalQty = collect($cartItems)->sum('qty');
@endphp

{{-- ===== HERO BANNER ===== --}}
<section class="cart-hero">
    <div class="cart-hero-overlay"></div>
    <div class="cart-hero-content max-w-7xl mx-auto px-6 lg:px-10 w-full">
        <p class="text-xs font-bold tracking-widest uppercase text-white/50 mb-2">Pilihan Kamu</p>
        <h1 class="cart-hero-title">Keranjang Belanja</h1>
        <p class="text-sm text-white/60 mt-2 font-medium" id="heroCartCount">
            {{ $absoluteTotalQty }} item di keranjangmu
        </p>
    </div>
</section>

{{-- ===== CONTENT ===== --}}
<section class="bg-gray-50 min-h-screen py-10">
    <div class="max-w-7xl mx-auto px-6 lg:px-10">

        @if(count($cartItems) > 0)
        <div class="flex flex-col lg:flex-row gap-6 items-start">

            {{-- ===== KIRI: ITEM LIST ===== --}}
            <div class="flex-1 min-w-0">

                <div class="bg-white border border-gray-200 rounded-lg px-4 py-3 mb-4 flex items-center gap-3">
                    <input type="checkbox" class="custom-check" id="selectAll" onchange="toggleSelectAll(this)">
                    <label for="selectAll" class="text-sm font-semibold text-gray-700 cursor-pointer select-none">
                        Pilih Semua
                        <span id="selectCount" class="text-gray-400 font-normal">({{ $totalQty }} terpilih)</span>
                    </label>
                    <button class="ml-auto text-xs text-red-400 font-semibold hover:text-red-600 transition-colors"
                        onclick="openDeleteModal('multiple')">
                        Hapus Terpilih
                    </button>
                </div>

                <div class="flex flex-col gap-3" id="cartList">

                    @foreach($cartItems as $idx => $item)
                    <div class="cart-item-card bg-white" id="item-{{ $item['id'] }}" data-id="{{ $item['id'] }}"
                        data-price="{{ $item['price'] }}">

                        <input type="checkbox" class="custom-check item-check" {{ $item['checked'] ? 'checked' : '' }}
                            onchange="updateSummary()">

                        @php $imgPath = $item['image'] ?? null; @endphp
                        @if($imgPath)
                        <img src="{{ $imgPath }}" 
                             onerror="this.onerror=null;this.src='{{ asset('images/men-home.jpg') }}';" 
                             alt="{{ $item['name'] }}" class="item-img">
                        @else
                        <div class="item-img-placeholder">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#d1d5db"
                                stroke-width="1.2" width="28" height="28">
                                <rect x="3" y="3" width="18" height="18" rx="2" />
                                <circle cx="8.5" cy="8.5" r="1.5" />
                                <polyline points="21 15 16 10 5 21" />
                            </svg>
                        </div>
                        @endif

                        <div class="flex-1 min-w-0">
                            <h3 class="font-bold text-gray-900 text-sm leading-snug mb-2">{{ $item['name'] }}</h3>

                            <div class="flex flex-wrap items-center gap-3 text-xs text-gray-500 mb-3">
                                <span><span class="font-semibold text-gray-700">Ukuran:</span> {{ $item['size'] }}</span>
                            </div>

                            <div class="mb-3">
                                <span class="font-bold text-gray-900 text-base">
                                    Rp {{ number_format($item['price'], 0, ',', '.') }}
                                </span>
                            </div>

                            <div class="flex items-center gap-2">
                                <button class="qty-btn" onclick="changeQty({{ $item['id'] }}, -1)" {{ $item['qty'] <=1
                                    ? 'disabled' : '' }}>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2.5" width="13" height="13">
                                        <line x1="5" y1="12" x2="19" y2="12" />
                                    </svg>
                                </button>
                                <input type="number" class="qty-input" id="qty-{{ $item['id'] }}"
                                    value="{{ $item['qty'] }}" min="1" max="99"
                                    onchange="onQtyInput({{ $item['id'] }}, this)">
                                <button class="qty-btn" onclick="changeQty({{ $item['id'] }}, 1)">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2.5" width="13" height="13">
                                        <line x1="12" y1="5" x2="12" y2="19" />
                                        <line x1="5" y1="12" x2="19" y2="12" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <button class="delete-btn" onclick="openDeleteModal('single', {{ $item['id'] }})" title="Hapus">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" width="16" height="16">
                                <polyline points="3 6 5 6 21 6" />
                                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
                                <path d="M10 11v6" /><path d="M14 11v6" /><path d="M9 6V4h6v2" />
                            </svg>
                        </button>
                    </div>
                    @endforeach

                </div>
            </div>

            {{-- ===== KANAN: ORDER SUMMARY ===== --}}
            <div class="w-full lg:w-80 flex-shrink-0">
                <div class="summary-card bg-white">
                    <h2 class="font-extrabold text-gray-900 text-lg mb-5">Ringkasan Pesanan</h2>

                    <div class="flex flex-col gap-3 text-sm">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Subtotal (<span id="summaryQty">{{ $totalQty }}</span> item)</span>
                            <span class="font-semibold text-gray-900" id="summarySubtotal">
                                Rp {{ number_format($subtotalBefore, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Ongkos Kirim</span>
                            <span class="font-semibold text-gray-400 text-xs italic">Dihitung saat checkout</span>
                        </div>
                        <div id="voucherRow" class="hidden flex justify-between items-center text-green-600">
                            <span class="flex items-center gap-1">Diskon Voucher</span>
                            <span class="font-bold" id="voucherDiscount">– Rp 0</span>
                        </div>
                    </div>

                    <hr class="summary-divider">

                    {{-- VOUCHER INLINE --}}
                    <div class="mb-6">
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
                            <div class="flex">
                                <input type="text" id="manualVoucherInput" class="w-full px-3 py-2.5 border border-gray-200 rounded-l-md text-xs font-semibold uppercase outline-none focus:border-black transition-colors" placeholder="KODE VOUCHER">
                                <button type="button" onclick="applyManualVoucher()" class="px-4 bg-black text-white text-[11px] font-bold tracking-widest uppercase rounded-r-md hover:bg-gray-800 transition-colors whitespace-nowrap">Terapkan</button>
                            </div>
                            <p id="inlineVoucherMsg" class="text-[11px] font-medium hidden mt-2"></p>

                            <hr class="border-gray-200 my-4">
                            <div class="flex items-center justify-between mb-3">
                                <p class="text-[10px] font-bold tracking-widest uppercase text-gray-500">Voucher Tersedia</p>
                                <button onclick="clearVoucher()" class="text-[10px] font-bold text-red-500 hover:text-red-700 hidden" id="clearVoucherBtn">HAPUS</button>
                            </div>
                            
                            <div class="flex flex-col gap-3 max-h-56 overflow-y-auto pr-1 custom-scrollbar">
                                @forelse($userVouchers as $uv)
                                @php
                                    $v = $uv->voucher;
                                    $valStr = $v->type === 'fixed' || $v->type === 'nominal'
                                        ? 'Potongan Rp ' . number_format($v->value, 0, ',', '.')
                                        : 'Diskon ' . $v->value . '%';
                                @endphp
                                <label class="voucher-radio-wrap" onclick="selectVoucherRadio(this)">
                                    <input type="radio" name="voucher_selection" class="custom-radio"
                                        value="{{ $v->code }}"
                                        data-label="{{ $valStr }}"
                                        data-type="{{ $v->type }}"
                                        data-value="{{ $v->value }}">
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

                    <div class="flex justify-between items-center mb-6">
                        <span class="font-extrabold text-gray-900 text-base">Total</span>
                        <span class="font-extrabold text-gray-900 text-xl" id="summaryTotal">
                            Rp {{ number_format($total, 0, ',', '.') }}
                        </span>
                    </div>

                    <form method="POST" action="{{ route('pelanggan.checkout.simpanItem') }}" id="checkoutForm">
                        @csrf
                        <div id="hiddenSelectedIds"></div>
                        <button type="submit" id="checkoutBtn"
                            class="block w-full text-center bg-gray-900 text-white text-xs font-bold tracking-widest uppercase py-4 rounded-lg hover:bg-gray-700 transition-colors mb-3">
                            Checkout (<span id="checkoutQty">{{ $totalQty }}</span> Item)
                        </button>
                    </form>
                    
                    <a href="{{ Route::has('pelanggan.katalog') ? route('pelanggan.katalog') : url('/katalog') }}"
                        class="block w-full text-center border border-gray-300 text-gray-700 text-xs font-bold tracking-widest uppercase py-3.5 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-colors">
                        Lanjut Belanja
                    </a>

                    <div class="flex items-center justify-center gap-1.5 mt-4 text-xs text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6" width="13" height="13">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                        </svg>
                        Transaksi aman & terenkripsi
                    </div>

                </div>
            </div>
        </div>

        @else
        <div class="bg-white min-h-[400px] flex flex-col items-center justify-center py-20 text-center">
            <div class="w-24 h-24 border border-gray-200 rounded-lg flex items-center justify-center mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#d1d5db"
                    stroke-width="1.3" width="38" height="38">
                    <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" />
                    <line x1="3" y1="6" x2="21" y2="6" />
                    <path d="M16 10a4 4 0 0 1-8 0" />
                </svg>
            </div>
            <h2 class="font-extrabold text-gray-900 text-xl mb-2">Keranjang kamu kosong</h2>
            <p class="text-sm text-gray-400 mb-7">Tambahkan produk untuk memulai belanja</p>
            <a href="{{ Route::has('pelanggan.katalog') ? route('pelanggan.katalog') : url('/katalog') }}"
                class="inline-flex items-center gap-2 bg-gray-900 text-white text-xs font-bold tracking-widest uppercase px-8 py-3.5 rounded-md hover:bg-gray-700 transition-colors">
                Lanjut Belanja
            </a>
        </div>
        @endif

    </div>
</section>

{{-- ===== MODAL KONFIRMASI HAPUS ===== --}}
<div id="deleteModal"
    class="modal-overlay fixed inset-0 z-[150] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
    <div class="modal-content w-full max-w-[340px] bg-white rounded-2xl shadow-2xl p-6 text-center">
        <div class="w-12 h-12 rounded-full bg-red-50 flex items-center justify-center mx-auto mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="w-6 h-6 text-red-500">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
        </div>
        <h3 class="text-lg font-bold text-gray-900 mb-2">Hapus Produk?</h3>
        <p class="text-sm text-gray-500 mb-6" id="deleteModalText">Yakin ingin menghapus produk ini dari keranjang?</p>

        <div class="flex gap-3">
            <button onclick="closeDeleteModal()" class="flex-1 py-2.5 border border-gray-300 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors">Batal</button>
            <button onclick="executeDelete()" class="flex-1 py-2.5 bg-red-600 rounded-lg text-sm font-semibold text-white hover:bg-red-700 transition-colors">Ya, Hapus</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    let activeVoucherType = '';
    let activeVoucherValue = 0;
    let currentVoucherDiscount = 0; 
    let activeVoucherCode = '';
    let activeVoucherLabel = '';

    const itemPrices = {
        @foreach($cartItems as $item)
        {{ $item['id'] }}: {{ $item['price'] }},
        @endforeach
    };

    function formatRp(val) {
        return 'Rp ' + Math.round(val).toLocaleString('id-ID');
    }

    document.getElementById('checkoutForm')?.addEventListener('submit', function(e) {
    const checked = document.querySelectorAll('.item-check:checked');
    if (checked.length === 0) {
        e.preventDefault();
        alert('Pilih minimal 1 produk untuk checkout.');
        return;
    }
    const container = document.getElementById('hiddenSelectedIds');
    container.innerHTML = '';
    checked.forEach(chk => {
        const id = chk.closest('.cart-item-card').dataset.id;
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'selected_ids[]';
        input.value = id;
        container.appendChild(input);
    });

    const voucherInput = document.createElement('input');
    voucherInput.type  = 'hidden';
    voucherInput.name  = 'voucher_code';
    voucherInput.value = activeVoucherCode;
    container.appendChild(voucherInput);
});

    // ========== UPDATE SUMMARY ==========
    function updateSummary() {
        const checks = document.querySelectorAll('.item-check');
        let subtotalBefore = 0;
        let totalQty = 0;
        let absoluteTotalQty = 0;

        checks.forEach(chk => {
            const card = chk.closest('.cart-item-card');
            card.classList.toggle('is-checked', chk.checked);

            const id    = parseInt(card.dataset.id);
            const qty   = parseInt(document.getElementById('qty-' + id)?.value || 1);
            
            absoluteTotalQty += qty; 

            if (!chk.checked) return;
            const price = itemPrices[id] || 0;
            subtotalBefore += price * qty;
            totalQty += qty;
        });

        // Hitung ulang diskon voucher (Support persen & nominal)
        if (activeVoucherType === 'percent' || activeVoucherType === 'persen' || activeVoucherType === 'percentage') {
            currentVoucherDiscount = subtotalBefore * (activeVoucherValue / 100);
        } else if (activeVoucherType === 'fixed' || activeVoucherType === 'nominal') {
            currentVoucherDiscount = activeVoucherValue;
        } else {
            currentVoucherDiscount = 0;
        }

        if (currentVoucherDiscount > subtotalBefore) {
            currentVoucherDiscount = subtotalBefore;
        }

        let total = subtotalBefore - currentVoucherDiscount;
        if (total < 0) total = 0;

        document.getElementById('summaryQty').textContent      = totalQty;
        document.getElementById('summarySubtotal').textContent = formatRp(subtotalBefore);
        document.getElementById('summaryTotal').textContent    = formatRp(total);
        document.getElementById('checkoutQty').textContent     = totalQty;

        if (activeVoucherType !== '') {
            document.getElementById('voucherDiscount').textContent = '– ' + formatRp(currentVoucherDiscount);
        }

        const checkedCount = Array.from(checks).filter(c => c.checked).length;
        const allCount     = checks.length; 
        
        if (document.getElementById('selectCount')) {
            document.getElementById('selectCount').textContent = '(' + totalQty + ' terpilih)';
        }
        
        if (document.getElementById('heroCartCount')) {
            document.getElementById('heroCartCount').textContent = absoluteTotalQty + ' item di keranjangmu';
        }

        const checkoutBtn = document.getElementById('checkoutBtn');
        if (totalQty === 0) {
            checkoutBtn.classList.add('opacity-40', 'pointer-events-none');
        } else {
            checkoutBtn.classList.remove('opacity-40', 'pointer-events-none');
        }

        const masterChk = document.getElementById('selectAll');
        if (masterChk) {
            masterChk.checked       = checkedCount === allCount && allCount > 0;
            masterChk.indeterminate = checkedCount > 0 && checkedCount < allCount;
        }

        const navbarBadge = document.getElementById('cart-badge');
        if (navbarBadge) {
            if (allCount > 0) {
                navbarBadge.textContent = allCount > 99 ? '99+' : allCount;
                navbarBadge.classList.remove('hidden');
                navbarBadge.style.display = '';
            } else {
                navbarBadge.classList.add('hidden');
                navbarBadge.style.display = 'none';
            }
        }
    }

    function changeQty(id, delta) {
        const input = document.getElementById('qty-' + id);
        let val = parseInt(input.value) + delta;
        if (val < 1) val = 1;
        if (val > 99) val = 99;
        input.value = val;

        const card = document.getElementById('item-' + id);
        const minusBtn = card.querySelectorAll('.qty-btn')[0];
        minusBtn.disabled = val <= 1;

        updateSummary();
        syncQtyToServer(id, val);
    }

    function onQtyInput(id, input) {
        let val = parseInt(input.value);
        if (isNaN(val) || val < 1) val = 1;
        if (val > 99) val = 99;
        input.value = val;
        updateSummary();
        syncQtyToServer(id, val);
    }

    function syncQtyToServer(id, qty) {
        fetch('/keranjang/' + id, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            },
            body: JSON.stringify({ quantity: qty }),
        }).catch(() => {});
    }

    let deleteMode = ''; 
    let deleteId = null;

    function openDeleteModal(mode, id = null) {
        deleteMode = mode;
        deleteId = id;
        
        const modal = document.getElementById('deleteModal');
        const text = document.getElementById('deleteModalText');
        
        if (mode === 'multiple') {
            const count = document.querySelectorAll('.item-check:checked').length;
            if(count === 0) return;
            text.textContent = `Yakin ingin menghapus ${count} produk terpilih dari keranjang?`;
        } else {
            text.textContent = `Yakin ingin menghapus produk ini dari keranjang?`;
        }
        modal.classList.add('active');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.remove('active');
    }

    function executeDelete() {
        closeDeleteModal();
        if (deleteMode === 'single' && deleteId) performSingleDelete(deleteId);
        else if (deleteMode === 'multiple') performMultipleDelete();
    }

    function performSingleDelete(id) {
        const card = document.getElementById('item-' + id);
        card.style.transition = 'opacity 0.3s, transform 0.3s, max-height 0.35s';
        card.style.opacity = '0';
        card.style.transform = 'translateX(10px)';
        setTimeout(() => { 
            card.remove(); 
            updateSummary(); 
            checkEmptyState(); 
        }, 300);
        
        fetch('/keranjang/' + id, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '' },
        }).catch(() => {});
    }

    function performMultipleDelete() {
        const checked = document.querySelectorAll('.item-check:checked');
        checked.forEach(chk => {
            const card = chk.closest('.cart-item-card');
            const id   = parseInt(card.dataset.id);
            card.style.transition = 'opacity 0.25s';
            card.style.opacity = '0';
            setTimeout(() => { 
                card.remove(); 
                updateSummary(); 
                checkEmptyState(); 
            }, 250);
            
            fetch('/keranjang/' + id, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '' },
            }).catch(() => {});
        });
    }

    function toggleSelectAll(masterChk) {
        document.querySelectorAll('.item-check').forEach(c => c.checked = masterChk.checked);
        updateSummary();
    }

    function checkEmptyState() {
        const remaining = document.querySelectorAll('.cart-item-card').length;
        if (remaining === 0) window.location.reload();
    }

    function toggleVoucherSection() {
        const coll = document.getElementById('voucherCollapsible');
        const chev = document.getElementById('voucherChevron');
        
        if (coll.classList.contains('hidden')) {
            coll.classList.remove('hidden');
            chev.style.transform = 'rotate(180deg)';
        } else {
            coll.classList.add('hidden');
            chev.style.transform = 'rotate(0deg)';
        }
    }

    function selectVoucherRadio(labelEl) {
        document.querySelectorAll('.voucher-radio-wrap').forEach(w => w.classList.remove('selected'));
        labelEl.classList.add('selected');
        document.getElementById('manualVoucherInput').value = '';
        document.getElementById('inlineVoucherMsg').classList.add('hidden');
        confirmVoucherSelection(labelEl.querySelector('input'));
    }

    function applyManualVoucher() {
    const input = document.getElementById('manualVoucherInput');
    const code  = input.value.toUpperCase().trim();
    const msg   = document.getElementById('inlineVoucherMsg');

    document.querySelectorAll('input[name="voucher_selection"]').forEach(r => r.checked = false);
    document.querySelectorAll('.voucher-radio-wrap').forEach(w => w.classList.remove('selected'));

    if (!code) {
        msg.textContent = 'Masukkan kode voucher terlebih dahulu.';
        msg.className   = 'mt-2 text-[11px] text-red-500 font-medium block';
        return;
    }

    fetch('/akun/voucher/info?code=' + code)
        .then(r => r.json())
        .then(v => {
            if (v.discount > 0 || v.value > 0) {
                msg.textContent = '✓ Voucher diterapkan!';
                msg.className   = 'mt-2 text-[11px] text-green-600 font-bold block';
                confirmVoucherSelection(null, { code: code, type: v.type || 'fixed', value: v.value || v.discount, label: code });
            } else {
                msg.textContent = 'Kode voucher tidak valid atau kedaluwarsa.';
                msg.className   = 'mt-2 text-[11px] text-red-500 font-medium block';
            }
        });
}

    function confirmVoucherSelection(radioElement = null, manualData = null) {
        let label = '';
        let code = '';

        if (manualData) {
            activeVoucherType = manualData.type || 'fixed';
            activeVoucherValue = parseFloat(manualData.value || manualData.discount);
            label = manualData.label;
            code = manualData.code;
        } else if (radioElement) {
            activeVoucherType = radioElement.dataset.type;
            activeVoucherValue = parseFloat(radioElement.dataset.value);
            label = radioElement.dataset.label;
            code = radioElement.value;
        } else {
            return;
        }

        activeVoucherCode = code;
        activeVoucherLabel = label;

        document.getElementById('activeVoucherTitle').textContent = code;
        document.getElementById('activeVoucherDesc').textContent = 'Diskon berhasil dipakai';
        document.getElementById('activeVoucherDesc').classList.replace('text-gray-900', 'text-green-600');
        
        document.getElementById('voucherRow').classList.remove('hidden');
        document.getElementById('clearVoucherBtn').classList.remove('hidden');

        updateSummary();
    }

    function clearVoucher() {
        activeVoucherType = '';
        activeVoucherValue = 0;
        currentVoucherDiscount = 0;
        activeVoucherCode = '';
        activeVoucherLabel = '';

        document.getElementById('activeVoucherTitle').textContent = 'Promo & Voucher';
        document.getElementById('activeVoucherDesc').textContent = 'Makin Hemat';
        document.getElementById('activeVoucherDesc').classList.replace('text-green-600', 'text-gray-900');
        
        document.getElementById('voucherRow').classList.add('hidden');
        document.getElementById('clearVoucherBtn').classList.add('hidden');
        
        document.getElementById('manualVoucherInput').value = '';
        document.getElementById('inlineVoucherMsg').classList.add('hidden');
        document.querySelectorAll('input[name="voucher_selection"]').forEach(r => r.checked = false);
        document.querySelectorAll('.voucher-radio-wrap').forEach(w => w.classList.remove('selected'));

        updateSummary();
    }

    if (document.getElementById('cartList')) {
        updateSummary();
    }
</script>
@endpush