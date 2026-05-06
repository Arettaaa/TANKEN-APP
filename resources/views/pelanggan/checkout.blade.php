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
    .step-label.active { color: #111; }
    .step-label.done   { color: #111; }

    .step-line {
        flex: 1;
        height: 2px;
        background: #e5e7eb;
        margin-top: 17px;
        min-width: 60px;
    }
    .step-line.done { background: #111; }

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
    .form-input:focus { border-color: #111; }
    .form-input::placeholder { color: #c5c5c5; }

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
    .saved-address-box:hover { border-color: #111; }
    .saved-address-box.active { border-color: #111; background: #fafafa; }

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
    .ship-option:hover { border-color: #111; }
    .ship-option.active {
        background: #111;
        border-color: #111;
    }
    .ship-option.active .ship-name   { color: #fff; }
    .ship-option.active .ship-days   { color: rgba(255,255,255,0.55); }
    .ship-option.active .ship-price  { color: #fff; }

    /* Summary card */
    .summary-card {
        border: 1.5px solid #e5e7eb;
        border-radius: 8px;
        padding: 22px;
        position: sticky;
        top: 80px;
    }
    .summary-divider { border: none; border-top: 1.5px solid #f3f4f6; margin: 14px 0; }

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
    .continue-btn:hover { background: #333; }
</style>
@endpush

@section('content')

@php
// Data dummy
$cartItems = $cartItems ?? collect([
    ['id'=>1,'name'=>'Sport Active Joggers','sku'=>'TKN-002','image'=>'men-home2.jpg','size'=>'XS','qty'=>1,'price'=>799000],
]);
$PPN_RATE      = 0.11;
$shippingCost  = 130000; // default SiCepat
$subtotal      = collect($cartItems)->sum(fn($i) => $i['price'] * $i['qty']);
$ppn           = round($subtotal * $PPN_RATE);
$total         = $subtotal + $ppn + $shippingCost;

$shippingOptions = [
    ['id'=>'jne',    'name'=>'JNE Regular',      'days'=>'2-3 hari', 'price'=>150000],
    ['id'=>'jnt',    'name'=>'J&T Express',       'days'=>'2-4 hari', 'price'=>120000],
    ['id'=>'sicepat','name'=>'SiCepat Reguler',   'days'=>'2-3 hari', 'price'=>130000, 'default'=>true],
    ['id'=>'anteraja','name'=>'AnterAja Standard','days'=>'3-4 hari', 'price'=>110000],
];
@endphp

<div class="bg-white min-h-screen">
    <div class="max-w-7xl mx-auto px-6 lg:px-10 py-10">

        {{-- Breadcrumb --}}
        <p class="text-xs font-bold tracking-widest uppercase text-gray-400 mb-2">Pembelian</p>
        <h1 class="text-3xl font-extrabold text-gray-900 mb-8">Checkout</h1>

        {{-- ===== STEP INDICATOR ===== --}}
        <div class="flex items-start mb-10">
            {{-- Step 1 --}}
            <div class="step-item">
                <div class="step-circle active">1</div>
                <span class="step-label active">Pengiriman</span>
            </div>
            <div class="step-line flex-1 mx-2"></div>
            {{-- Step 2 --}}
            <div class="step-item">
                <div class="step-circle">2</div>
                <span class="step-label">Pembayaran</span>
            </div>
            <div class="step-line flex-1 mx-2"></div>
            {{-- Step 3 --}}
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

                    {{-- Saved address --}}
                    @auth
                    <div class="saved-address-box active mb-6" id="savedAddressBox" onclick="toggleSavedAddress()">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#111" stroke-width="1.8" width="18" height="18" class="flex-shrink-0">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>
                        </svg>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">Gunakan alamat tersimpan</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ auth()->user()->address ?? '123 Main St, New York, NY 10001' }}</p>
                        </div>
                    </div>
                    @endauth

                    <form action="{{ route('pelanggan.checkout.proses') }}" method="POST" id="checkoutForm">
                        @csrf

                        {{-- Nama & Email --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="form-label">Nama Lengkap <span class="text-red-400">*</span></label>
                                <input type="text" name="full_name" class="form-input"
                                       value="{{ old('full_name', auth()->user()->name ?? '') }}"
                                       placeholder="Nama lengkap" required>
                            </div>
                            <div>
                                <label class="form-label">Email <span class="text-red-400">*</span></label>
                                <input type="email" name="email" class="form-input"
                                       value="{{ old('email', auth()->user()->email ?? '') }}"
                                       placeholder="email@example.com" required>
                            </div>
                        </div>

                        {{-- Telepon --}}
                        <div class="mb-4">
                            <label class="form-label">Nomor Telepon <span class="text-red-400">*</span></label>
                            <input type="tel" name="phone" class="form-input"
                                   value="{{ old('phone', auth()->user()->phone ?? '') }}"
                                   placeholder="+62 812 3456 7890" required>
                        </div>

                        {{-- Alamat --}}
                        <div class="mb-4">
                            <label class="form-label">Alamat <span class="text-red-400">*</span></label>
                            <input type="text" name="address" class="form-input"
                                   value="{{ old('address', auth()->user()->address ?? '') }}"
                                   placeholder="Nama jalan, nomor rumah, RT/RW" required>
                        </div>

                        {{-- Kota & Provinsi --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="form-label">Kota <span class="text-red-400">*</span></label>
                                <input type="text" name="city" class="form-input"
                                       value="{{ old('city', auth()->user()->city ?? '') }}"
                                       placeholder="Bogor" required>
                            </div>
                            <div>
                                <label class="form-label">Provinsi <span class="text-red-400">*</span></label>
                                <input type="text" name="state" class="form-input"
                                       value="{{ old('state', auth()->user()->state ?? '') }}"
                                       placeholder="Jawa Barat" required>
                            </div>
                        </div>

                        {{-- Kode Pos --}}
                        <div class="mb-7">
                            <label class="form-label">Kode Pos <span class="text-red-400">*</span></label>
                            <input type="text" name="zip_code" class="form-input"
                                   value="{{ old('zip_code', auth()->user()->zip_code ?? '') }}"
                                   placeholder="12720" required>
                        </div>

                        {{-- Metode Pengiriman --}}
                        <div>
                            <label class="form-label mb-3">Metode Pengiriman</label>
                            <div class="flex flex-col gap-2" id="shippingOptions">
                                @foreach($shippingOptions as $opt)
                                <div class="ship-option {{ isset($opt['default']) ? 'active' : '' }}"
                                     onclick="selectShipping(this, '{{ $opt['id'] }}', {{ $opt['price'] }})">
                                    <div>
                                        <p class="ship-name font-semibold text-sm {{ isset($opt['default']) ? 'text-white' : 'text-gray-900' }}">{{ $opt['name'] }}</p>
                                        <p class="ship-days text-xs mt-0.5 {{ isset($opt['default']) ? 'text-white/55' : 'text-gray-400' }}">{{ $opt['days'] }}</p>
                                    </div>
                                    <span class="ship-price font-bold text-sm {{ isset($opt['default']) ? 'text-white' : 'text-gray-900' }}">
                                        Rp {{ number_format($opt['price'], 0, ',', '.') }}
                                    </span>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <input type="hidden" name="shipping_method" id="shippingMethodInput" value="sicepat">
                        <input type="hidden" name="shipping_cost"   id="shippingCostInput"   value="{{ $shippingCost }}">

                        {{-- Continue button --}}
                        <button type="submit" class="continue-btn mt-8">
                            Lanjutkan
                        </button>
                    </form>

                </div>
            </div>

            {{-- ===== KANAN: SUMMARY ===== --}}
            <div class="w-full lg:w-72 flex-shrink-0">
                <div class="summary-card">
                    <p class="text-xs font-bold tracking-widest uppercase text-gray-400 mb-1">Ringkasan</p>
                    <h2 class="font-extrabold text-gray-900 text-base mb-5">Ringkasan Pesanan</h2>

                    {{-- Items --}}
                    <div class="flex flex-col gap-3 mb-4">
                        @foreach($cartItems as $item)
                        @php
                            $imgPath = isset($item['image']) ? asset('images/'.$item['image']) : null;
                        @endphp
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
                                Rp {{ number_format($shippingCost, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">PPN (11%)</span>
                            <span class="font-semibold text-gray-800">Rp {{ number_format($ppn, 0, ',', '.') }}</span>
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
    const subtotalBase  = {{ $subtotal }};
    const ppnBase       = {{ $ppn }};
    const BIAYA_LAYANAN = 0;

    function formatRp(val) {
        return 'Rp ' + Math.round(val).toLocaleString('id-ID');
    }

    function selectShipping(el, id, price) {
        // Reset semua
        document.querySelectorAll('.ship-option').forEach(o => {
            o.classList.remove('active');
            o.querySelector('.ship-name').classList.replace('text-white','text-gray-900');
            o.querySelector('.ship-days').classList.remove('text-white/55');
            o.querySelector('.ship-days').classList.add('text-gray-400');
            o.querySelector('.ship-price').classList.replace('text-white','text-gray-900');
        });

        // Aktifkan yang dipilih
        el.classList.add('active');
        el.querySelector('.ship-name').classList.replace('text-gray-900','text-white');
        el.querySelector('.ship-days').classList.add('text-white/55');
        el.querySelector('.ship-days').classList.remove('text-gray-400');
        el.querySelector('.ship-price').classList.replace('text-gray-900','text-white');

        // Update hidden inputs & summary
        document.getElementById('shippingMethodInput').value = id;
        document.getElementById('shippingCostInput').value   = price;
        document.getElementById('summaryShipping').textContent = formatRp(price);
        document.getElementById('summaryTotal').textContent    = formatRp(subtotalBase + ppnBase + price);
    }

    function toggleSavedAddress() {
        // Nanti disambungkan ke autofill dari data user
    }
</script>
@endpush