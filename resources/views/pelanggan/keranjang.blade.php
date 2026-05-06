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
        background: linear-gradient(to bottom, rgba(0,0,0,0.52) 0%, rgba(0,0,0,0.62) 100%);
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
    }
    .qty-input:focus { border-color: #111; }

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

    /* Order summary card */
    .summary-card {
        border: 1.5px solid #e5e7eb;
        border-radius: 10px;
        padding: 24px;
        position: sticky;
        top: 80px;
    }

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

    /* Voucher input */
    .voucher-input {
        flex: 1;
        padding: 10px 12px;
        border: 1.5px solid #e5e7eb;
        border-radius: 8px 0 0 8px;
        font-family: 'Inter', sans-serif;
        font-size: 0.82rem;
        outline: none;
        transition: border-color 0.2s;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .voucher-input:focus { border-color: #111; }
    .voucher-apply-btn {
        padding: 10px 16px;
        background: #111;
        color: #fff;
        border: none;
        border-radius: 0 8px 8px 0;
        font-family: 'Inter', sans-serif;
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        cursor: pointer;
        transition: background 0.2s;
        white-space: nowrap;
    }
    .voucher-apply-btn:hover { background: #333; }

    /* Divider */
    .summary-divider { border: none; border-top: 1.5px solid #f3f4f6; margin: 14px 0; }

    /* Color dot */
    .color-dot {
        width: 14px;
        height: 14px;
        border-radius: 50%;
        border: 1.5px solid rgba(0,0,0,0.15);
        flex-shrink: 0;
    }

    /* PPN info tooltip */
    .ppn-info {
        display: inline-flex;
        align-items: center;
        gap: 3px;
        font-size: 0.7rem;
        color: #9ca3af;
    }
</style>
@endpush

@section('content')

@php
// ============================================================
// DATA DUMMY — ganti dengan data dari controller/session nanti
// ============================================================
$cartItems = $cartItems ?? collect([
    [
        'id'       => 1,
        'name'     => 'Sport Active Joggers',
        'sku'      => 'TKN-002-XS-BLK',
        'image'    => 'men-home2.jpg',
        'size'     => 'XS',
        'color'    => 'Black',
        'color_hex'=> '#111111',
        'price'    => 799000,   // harga sebelum PPN
        'qty'      => 1,
        'checked'  => true,
    ],
]);

// Konstanta
$PPN_RATE     = 0.11;
$BIAYA_LAYANAN = 50000;
$FREE_SHIP_MIN = 500000;

// Hitung total item terpilih
$selectedItems  = collect($cartItems)->where('checked', true);
$totalQty       = $selectedItems->sum('qty');

// Subtotal (harga produk × qty, BELUM termasuk PPN)
$subtotalBefore = $selectedItems->sum(fn($i) => $i['price'] * $i['qty']);

// PPN 11% dari subtotal
$ppn            = round($subtotalBefore * $PPN_RATE);

// Subtotal setelah PPN
$subtotal       = $subtotalBefore + $ppn;

// Biaya layanan (flat)
$total          = $subtotal + $BIAYA_LAYANAN;

// Sisa agar gratis ongkir
$sisaFreeShip   = max(0, $FREE_SHIP_MIN - $subtotalBefore);

$totalCartCount = collect($cartItems)->sum('qty');
@endphp

{{-- ===== HERO BANNER ===== --}}
<section class="cart-hero">
    <div class="cart-hero-overlay"></div>
    <div class="cart-hero-content max-w-7xl mx-auto px-6 lg:px-10 w-full">
        <p class="text-xs font-bold tracking-widest uppercase text-white/50 mb-2">Pilihan Kamu</p>
        <h1 class="cart-hero-title">Keranjang Belanja</h1>
        <p class="text-sm text-white/60 mt-2 font-medium">
            {{ $totalCartCount }} {{ $totalCartCount == 1 ? 'item' : 'item' }} di keranjangmu
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

                {{-- Select All bar --}}
                <div class="bg-white border border-gray-200 rounded-lg px-4 py-3 mb-4 flex items-center gap-3">
                    <input type="checkbox" class="custom-check" id="selectAll" onchange="toggleSelectAll(this)">
                    <label for="selectAll" class="text-sm font-semibold text-gray-700 cursor-pointer select-none">
                        Pilih Semua
                        <span id="selectCount" class="text-gray-400 font-normal">({{ $totalQty }} dari {{ $totalCartCount }} terpilih)</span>
                    </label>
                    <button class="ml-auto text-xs text-red-400 font-semibold hover:text-red-600 transition-colors"
                            onclick="deleteSelected()">
                        Hapus Terpilih
                    </button>
                </div>

                {{-- Item cards --}}
                <div class="flex flex-col gap-3" id="cartList">

                    @foreach($cartItems as $idx => $item)
                    @php
                        $itemPpn   = round($item['price'] * $PPN_RATE);
                        $itemTotal = ($item['price'] + $itemPpn) * $item['qty'];
                    @endphp

                    <div class="cart-item-card bg-white" id="item-{{ $item['id'] }}" data-id="{{ $item['id'] }}" data-price="{{ $item['price'] }}">

                        {{-- Checkbox kiri --}}
                        <input type="checkbox"
                               class="custom-check item-check"
                               {{ $item['checked'] ? 'checked' : '' }}
                               onchange="updateSummary()">

                        {{-- Gambar --}}
                        @if(isset($item['image']) && $item['image'])
                            <img src="{{ asset('images/' . $item['image']) }}"
                                 alt="{{ $item['name'] }}"
                                 class="item-img">
                        @else
                            <div class="item-img-placeholder">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#d1d5db" stroke-width="1.2" width="28" height="28">
                                    <rect x="3" y="3" width="18" height="18" rx="2"/>
                                    <circle cx="8.5" cy="8.5" r="1.5"/>
                                    <polyline points="21 15 16 10 5 21"/>
                                </svg>
                            </div>
                        @endif

                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            <h3 class="font-bold text-gray-900 text-sm leading-snug mb-1">{{ $item['name'] }}</h3>
                            <p class="text-xs text-gray-400 mb-2 font-mono">SKU: {{ $item['sku'] }}</p>

                            <div class="flex flex-wrap items-center gap-3 text-xs text-gray-500 mb-3">
                                <span>
                                    <span class="font-semibold text-gray-700">Ukuran:</span> {{ $item['size'] }}
                                </span>
                                <span class="flex items-center gap-1.5">
                                    <span class="font-semibold text-gray-700">Warna:</span>
                                    <span class="color-dot" style="background:{{ $item['color_hex'] ?? '#111' }};"></span>
                                    {{ $item['color'] }}
                                </span>
                            </div>

                            {{-- Harga + PPN info --}}
                            <div class="mb-3">
                                <span class="font-bold text-gray-900 text-base">
                                    Rp {{ number_format($item['price'] + $itemPpn, 0, ',', '.') }}
                                </span>
                                <span class="ppn-info ml-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6" width="11" height="11">
                                        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                                    </svg>
                                    sudah termasuk PPN 11%
                                </span>
                            </div>

                            {{-- Qty stepper --}}
                            <div class="flex items-center gap-2">
                                <button class="qty-btn"
                                        onclick="changeQty({{ $item['id'] }}, -1)"
                                        {{ $item['qty'] <= 1 ? 'disabled' : '' }}>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" width="13" height="13">
                                        <line x1="5" y1="12" x2="19" y2="12"/>
                                    </svg>
                                </button>
                                <input type="number"
                                       class="qty-input"
                                       id="qty-{{ $item['id'] }}"
                                       value="{{ $item['qty'] }}"
                                       min="1" max="99"
                                       onchange="onQtyInput({{ $item['id'] }}, this)">
                                <button class="qty-btn" onclick="changeQty({{ $item['id'] }}, 1)">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" width="13" height="13">
                                        <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {{-- Delete --}}
                        <button class="delete-btn" onclick="removeItem({{ $item['id'] }})" title="Hapus">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" width="16" height="16">
                                <polyline points="3 6 5 6 21 6"/>
                                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                                <path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/>
                            </svg>
                        </button>

                    </div>
                    @endforeach

                </div>

                {{-- Voucher --}}
                <div class="bg-white border border-gray-200 rounded-lg p-4 mt-4">
                    <p class="text-xs font-bold tracking-widest uppercase text-gray-500 mb-3">Kode Voucher</p>
                    <div class="flex">
                        <input type="text" class="voucher-input" id="voucherInput" placeholder="Masukkan kode promo">
                        <button class="voucher-apply-btn" onclick="applyVoucher()">Pakai</button>
                    </div>
                    <div id="voucherMsg" class="mt-2 text-xs hidden"></div>
                </div>

            </div>

            {{-- ===== KANAN: ORDER SUMMARY ===== --}}
            <div class="w-full lg:w-80 flex-shrink-0">
                <div class="summary-card bg-white">

                    <h2 class="font-extrabold text-gray-900 text-lg mb-5">Ringkasan Pesanan</h2>

                    {{-- Rows --}}
                    <div class="flex flex-col gap-3 text-sm">

                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Subtotal (<span id="summaryQty">{{ $totalQty }}</span> item)</span>
                            <span class="font-semibold text-gray-900" id="summarySubtotal">
                                Rp {{ number_format($subtotalBefore, 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-gray-500 flex items-center gap-1">
                                PPN 11%
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#9ca3af" stroke-width="1.6" width="12" height="12">
                                    <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                                </svg>
                            </span>
                            <span class="font-semibold text-gray-900" id="summaryPpn">
                                Rp {{ number_format($ppn, 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Biaya Layanan</span>
                            <span class="font-semibold text-gray-900">Rp {{ number_format($BIAYA_LAYANAN, 0, ',', '.') }}</span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Ongkos Kirim</span>
                            <span class="font-semibold text-gray-400 text-xs italic">Dihitung saat checkout</span>
                        </div>

                        {{-- Free ship progress --}}
                        @if($sisaFreeShip > 0)
                        <div class="bg-gray-50 rounded-lg p-3 text-xs text-gray-500 leading-relaxed">
                            Tambah <span class="font-bold text-gray-800">Rp {{ number_format($sisaFreeShip, 0, ',', '.') }}</span>
                            lagi untuk gratis ongkir 🎉
                        </div>
                        @else
                        <div class="bg-green-50 rounded-lg p-3 text-xs text-green-700 font-semibold">
                            ✓ Kamu memenuhi syarat gratis ongkir!
                        </div>
                        @endif

                        {{-- Voucher row (hidden by default) --}}
                        <div id="voucherRow" class="hidden flex justify-between items-center text-green-600">
                            <span class="flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="13" height="13">
                                    <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/>
                                    <line x1="7" y1="7" x2="7.01" y2="7"/>
                                </svg>
                                Diskon Voucher
                            </span>
                            <span class="font-bold" id="voucherDiscount">– Rp 0</span>
                        </div>

                    </div>

                    <hr class="summary-divider">

                    {{-- Total --}}
                    <div class="flex justify-between items-center mb-6">
                        <span class="font-extrabold text-gray-900 text-base">Total</span>
                        <span class="font-extrabold text-gray-900 text-xl" id="summaryTotal">
                            Rp {{ number_format($total, 0, ',', '.') }}
                        </span>
                    </div>

                    {{-- CTA Checkout --}}
                    <a href="#"
                       id="checkoutBtn"
                       class="block w-full text-center bg-gray-900 text-white text-xs font-bold tracking-widest uppercase py-4 rounded-lg hover:bg-gray-700 transition-colors mb-3">
                        Checkout (<span id="checkoutQty">{{ $totalQty }}</span> Item)
                    </a>

                    {{-- Lanjut belanja --}}
                    <a href="{{ route('pelanggan.katalog') }}"
                       class="block w-full text-center border border-gray-300 text-gray-700 text-xs font-bold tracking-widest uppercase py-3.5 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-colors">
                        Lanjut Belanja
                    </a>

                    {{-- Info aman --}}
                    <div class="flex items-center justify-center gap-1.5 mt-4 text-xs text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6" width="13" height="13">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        </svg>
                        Transaksi aman & terenkripsi
                    </div>

                </div>
            </div>

        </div>

        @else
            {{-- ===== EMPTY CART ===== --}}
            <div class="bg-white min-h-[400px] flex flex-col items-center justify-center py-20 text-center">
                <div class="w-24 h-24 border border-gray-200 rounded-lg flex items-center justify-center mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#d1d5db" stroke-width="1.3" width="38" height="38">
                        <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                        <line x1="3" y1="6" x2="21" y2="6"/>
                        <path d="M16 10a4 4 0 0 1-8 0"/>
                    </svg>
                </div>
                <h2 class="font-extrabold text-gray-900 text-xl mb-2">Keranjang kamu kosong</h2>
                <p class="text-sm text-gray-400 mb-7">Tambahkan produk untuk memulai belanja</p>
                <a href="{{ route('pelanggan.katalog') }}"
                   class="inline-flex items-center gap-2 bg-gray-900 text-white text-xs font-bold tracking-widest uppercase px-8 py-3.5 rounded-md hover:bg-gray-700 transition-colors">
                    Lanjut Belanja
                </a>
            </div>
        @endif

    </div>
</section>

@endsection

@push('scripts')
<script>
    // ========== KONSTANTA ==========
    const PPN_RATE      = 0.11;
    const BIAYA_LAYANAN = 50000;
    const FREE_SHIP_MIN = 500000;

    // Harga base per item (dari PHP ke JS)
    const itemPrices = {
        @foreach($cartItems as $item)
        {{ $item['id'] }}: {{ $item['price'] }},
        @endforeach
    };

    // ========== FORMAT RUPIAH ==========
    function formatRp(val) {
        return 'Rp ' + Math.round(val).toLocaleString('id-ID');
    }

    // ========== UPDATE SUMMARY ==========
    function updateSummary() {
        const checks = document.querySelectorAll('.item-check');
        let subtotalBefore = 0;
        let totalQty = 0;

        checks.forEach(chk => {
            const card = chk.closest('.cart-item-card');
            // Toggle visual highlight
            card.classList.toggle('is-checked', chk.checked);

            if (!chk.checked) return;
            const id    = parseInt(card.dataset.id);
            const qty   = parseInt(document.getElementById('qty-' + id)?.value || 1);
            const price = itemPrices[id] || 0;
            subtotalBefore += price * qty;
            totalQty += qty;
        });

        const ppn    = Math.round(subtotalBefore * PPN_RATE);
        const total  = subtotalBefore + ppn + BIAYA_LAYANAN;

        document.getElementById('summaryQty').textContent     = totalQty;
        document.getElementById('summarySubtotal').textContent = formatRp(subtotalBefore);
        document.getElementById('summaryPpn').textContent      = formatRp(ppn);
        document.getElementById('summaryTotal').textContent    = formatRp(total);
        document.getElementById('checkoutQty').textContent     = totalQty;
        document.getElementById('selectCount').textContent     =
            '(' + totalQty + ' dari ' + getTotalAllQty() + ' terpilih)';

        // Checkout button state
        const checkoutBtn = document.getElementById('checkoutBtn');
        if (totalQty === 0) {
            checkoutBtn.classList.add('opacity-40', 'pointer-events-none');
        } else {
            checkoutBtn.classList.remove('opacity-40', 'pointer-events-none');
        }

        // Select All state
        const allCount     = checks.length;
        const checkedCount = Array.from(checks).filter(c => c.checked).length;
        const masterChk    = document.getElementById('selectAll');
        masterChk.checked       = checkedCount === allCount && allCount > 0;
        masterChk.indeterminate  = checkedCount > 0 && checkedCount < allCount;
    }

    function getTotalAllQty() {
        let t = 0;
        document.querySelectorAll('.cart-item-card').forEach(card => {
            const id = parseInt(card.dataset.id);
            t += parseInt(document.getElementById('qty-' + id)?.value || 1);
        });
        return t;
    }

    // ========== QTY ==========
    function changeQty(id, delta) {
        const input = document.getElementById('qty-' + id);
        let val = parseInt(input.value) + delta;
        if (val < 1) val = 1;
        if (val > 99) val = 99;
        input.value = val;

        // Disable minus btn jika qty = 1
        const card    = document.getElementById('item-' + id);
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

    // ========== REMOVE ITEM ==========
    function removeItem(id) {
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

    // ========== SELECT ALL ==========
    function toggleSelectAll(masterChk) {
        document.querySelectorAll('.item-check').forEach(c => c.checked = masterChk.checked);
        updateSummary();
    }

    // ========== DELETE SELECTED ==========
    function deleteSelected() {
        const checked = document.querySelectorAll('.item-check:checked');
        if (!checked.length) return;
        if (!confirm('Hapus ' + checked.length + ' item terpilih?')) return;
        checked.forEach(chk => {
            const card = chk.closest('.cart-item-card');
            const id   = parseInt(card.dataset.id);
            card.style.transition = 'opacity 0.25s';
            card.style.opacity = '0';
            setTimeout(() => { card.remove(); updateSummary(); checkEmptyState(); }, 250);
            fetch('/keranjang/' + id, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '' },
            }).catch(() => {});
        });
    }

    // ========== VOUCHER ==========
    function applyVoucher() {
        const code = document.getElementById('voucherInput').value.toUpperCase().trim();
        const msg  = document.getElementById('voucherMsg');
        if (!code) { msg.textContent = 'Masukkan kode voucher terlebih dahulu.'; msg.className = 'mt-2 text-xs text-red-500'; msg.classList.remove('hidden'); return; }

        fetch('/voucher/check', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            },
            body: JSON.stringify({ code }),
        })
        .then(r => r.json())
        .then(data => {
            if (data.valid) {
                msg.textContent = '✓ Voucher berhasil dipakai: ' + data.label;
                msg.className   = 'mt-2 text-xs text-green-600 font-semibold';
                document.getElementById('voucherRow').classList.remove('hidden');
                document.getElementById('voucherDiscount').textContent = '– ' + formatRp(data.discount);
            } else {
                msg.textContent = data.message || 'Kode voucher tidak valid.';
                msg.className   = 'mt-2 text-xs text-red-500';
            }
            msg.classList.remove('hidden');
        })
        .catch(() => {
            msg.textContent = 'Gagal memvalidasi voucher.';
            msg.className   = 'mt-2 text-xs text-red-500';
            msg.classList.remove('hidden');
        });
    }

    // ========== EMPTY CHECK ==========
    function checkEmptyState() {
        const remaining = document.querySelectorAll('.cart-item-card').length;
        if (remaining === 0) window.location.reload();
    }

    // Init
    updateSummary();
</script>
@endpush