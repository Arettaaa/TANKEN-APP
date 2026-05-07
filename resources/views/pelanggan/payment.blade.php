{{-- resources/views/pelanggan/payment.blade.php --}}
@extends('layouts.main')

@section('title', 'Pembayaran — TANKEN')

@push('styles')
<style>
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

    .form-label { display: block; font-size: 0.68rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: #9ca3af; margin-bottom: 6px; }
    .form-input { width: 100%; padding: 12px 14px; border: 1.5px solid #e5e7eb; border-radius: 6px; font-family: 'Inter', sans-serif; font-size: 0.875rem; color: #111; outline: none; transition: border-color 0.2s; background: #fff; }
    .form-input:focus { border-color: #111; }
    .form-input.is-invalid { border-color: #ef4444; background-color: #fef2f2; }

    .form-select {
        width: 100%; padding: 12px 40px 12px 14px; border: 1.5px solid #e5e7eb; border-radius: 6px;
        font-family: 'Inter', sans-serif; font-size: 0.875rem; color: #111; outline: none;
        background-color: #fff; appearance: none;
        background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg xmlns%3D'http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg' width%3D'24' height%3D'24' viewBox%3D'0 0 24 24' fill%3D'none' stroke%3D'%23111' stroke-width%3D'2'%3E%3Cpolyline points%3D'6 9 12 15 18 9'%3E%3C%2Fpolyline%3E%3C%2Fsvg%3E");
        background-repeat: no-repeat; background-position: right 12px center; background-size: 16px; cursor: pointer;
        transition: border-color 0.2s;
    }
    .form-select:focus { border-color: #111; }
    .form-select.is-invalid { border-color: #ef4444; background-color: #fef2f2; }

    /* Payment method tabs */
    .pay-tab { flex: 1; padding: 14px 10px; border: 1.5px solid #e5e7eb; border-radius: 8px; cursor: pointer; display: flex; flex-direction: column; align-items: center; gap: 8px; transition: all 0.2s; background: #fff; }
    .pay-tab:hover { border-color: #111; }
    .pay-tab.active { background: #111; border-color: #111; }
    .pay-tab .pay-icon { font-size: 1.3rem; color: #6b7280; transition: color 0.2s; }
    .pay-tab.active .pay-icon { color: #fff; }
    .pay-tab .pay-label { font-size: 0.72rem; font-weight: 700; color: #374151; text-align: center; }
    .pay-tab.active .pay-label { color: #fff; }

    /* Upload zone */
    .upload-zone { border: 2px dashed #d1d5db; border-radius: 8px; padding: 28px 20px; text-align: center; background-color: #f9fafb; transition: all 0.2s ease; position: relative; cursor: pointer; }
    .upload-zone:hover, .upload-zone.dragover { border-color: #111; background-color: #f3f4f6; }
    .upload-zone.has-file { border-style: solid; border-color: #10b981; background-color: #ecfdf5; }
    .upload-zone.is-invalid { border-color: #ef4444; background-color: #fef2f2; }
    .upload-input { position: absolute; inset: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; z-index: 10; }

    /* Summary card */
    .summary-card { border: 1.5px solid #e5e7eb; border-radius: 8px; padding: 22px; position: sticky; top: 80px; }
    .summary-divider { border: none; border-top: 1.5px solid #f3f4f6; margin: 14px 0; }

    .continue-btn { width: 100%; padding: 14px; background: #111; color: #fff; border: 1.5px solid #111; border-radius: 6px; font-family: 'Inter', sans-serif; font-size: 0.75rem; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; cursor: pointer; transition: background 0.2s; }
    .continue-btn:hover { background: #333; }
    .back-btn { width: 100%; padding: 14px; background: #fff; color: #111; border: 1.5px solid #e5e7eb; border-radius: 6px; font-family: 'Inter', sans-serif; font-size: 0.75rem; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; cursor: pointer; transition: border-color 0.2s; text-align: center; display: flex; align-items: center; justify-content: center; gap: 8px; }
    .back-btn:hover { border-color: #111; background: #fafafa; }

    /* QRIS placeholder */
    .qris-placeholder {
        width: 180px; height: 180px; border: 2px dashed #d1d5db; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        background: repeating-linear-gradient(45deg, #f3f4f6, #f3f4f6 5px, #e5e7eb 5px, #e5e7eb 10px);
        opacity: 0.5;
    }
</style>
@endpush

@section('content')

@php
    $PPN_RATE = 0.11;
    $subtotal     = $subtotal ?? 0;
    $ppn          = $ppn ?? 0;
    $shippingCost = $shippingCost ?? 0;
    $total        = $total ?? ($subtotal + $ppn + $shippingCost);
@endphp

<div class="bg-white min-h-screen">
    <div class="max-w-7xl mx-auto px-5 sm:px-6 lg:px-10 py-6 sm:py-10">

        <p class="text-[10px] sm:text-xs font-bold tracking-widest uppercase text-gray-400 mb-1">Pembelian</p>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mb-6 sm:mb-8">Checkout</h1>

        {{-- STEP INDICATOR --}}
        <div class="flex items-start mb-10 w-full">
            <div class="step-item">
                <div class="step-box done"><i class="fa-solid fa-check"></i></div>
                <span class="step-label done">Pengiriman</span>
            </div>
            <div class="step-line done mx-2 sm:mx-4"></div>
            <div class="step-item">
                <div class="step-box active">2</div>
                <span class="step-label active">Pembayaran</span>
            </div>
            <div class="step-line mx-2 sm:mx-4"></div>
            <div class="step-item">
                <div class="step-box">3</div>
                <span class="step-label">Peninjauan</span>
            </div>
        </div>

        @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
        @endif

        <div class="flex flex-col lg:flex-row gap-6 lg:gap-8 items-start">

            {{-- KIRI: FORM --}}
            <div class="flex-1 w-full min-w-0">
                <div class="border border-gray-200 rounded-lg p-5 sm:p-7">

                    <p class="text-xs font-bold tracking-widest uppercase text-gray-400 mb-1">Langkah 2</p>
                    <h2 class="text-lg font-extrabold text-gray-900 mb-6">Informasi Pembayaran</h2>

                    {{-- Ringkasan alamat pengiriman --}}
                    <div class="bg-gray-50 border border-gray-100 rounded-lg px-4 py-3 mb-6 flex gap-3 items-start">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#111" stroke-width="1.8" width="16" height="16" class="flex-shrink-0 mt-0.5">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>
                        </svg>
                        <div>
                            <p class="text-xs font-bold text-gray-900">{{ $address->name }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $address->street }}, {{ $address->region }} {{ $address->postal }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">Kurir: <span class="font-semibold text-gray-700">{{ $shipping }}</span> · Ongkir: <span class="font-semibold text-gray-700">Rp {{ number_format($shippingCost, 0, ',', '.') }}</span></p>
                        </div>
                    </div>

                    <form action="{{ route('pelanggan.checkout.payment.simpan') }}" method="POST" id="paymentForm" enctype="multipart/form-data" novalidate>
                        @csrf
                        <input type="hidden" name="payment_method" id="selectedPaymentMethod" value="bank_transfer">

                        {{-- PILIH METODE --}}
                        <label class="form-label mb-3">Metode Pembayaran</label>
                        <div class="flex gap-3 mb-6">
                            <div class="pay-tab active" onclick="selectPayment('bank_transfer', this)">
                                <i class="fa-solid fa-building-columns pay-icon"></i>
                                <span class="pay-label">Transfer Bank</span>
                            </div>
                            <div class="pay-tab" onclick="selectPayment('qris', this)">
                                <i class="fa-solid fa-qrcode pay-icon"></i>
                                <span class="pay-label">QRIS</span>
                            </div>
                        </div>

                        {{-- FORM: TRANSFER BANK --}}
                        <div id="form-bank_transfer" class="payment-block">
                            <div class="mb-4">
                                <label class="form-label">Pilih Bank Tujuan Transfer</label>
                                <select name="bank_provider" id="bankProvider" class="form-select">
                                    <option value="" disabled selected>-- Pilih Bank --</option>
                                    @foreach($banks as $bank)
                                    <option value="{{ $bank['value'] }}">
                                        {{ $bank['label'] }} — {{ $bank['number'] }} a.n {{ $bank['name'] }}
                                    </option>
                                    @endforeach
                                </select>
                                <p id="bankErrorMsg" class="hidden text-xs font-medium text-red-500 mt-1.5">
                                    <i class="fa-solid fa-circle-exclamation mr-1"></i> Pilih bank tujuan transfer dulu.
                                </p>
                            </div>

                            {{-- Detail rekening setelah pilih --}}
                            <div id="bankDetail" class="hidden bg-gray-50 border border-gray-100 rounded-lg p-4 mb-4">
                                <p class="text-xs text-gray-400 uppercase font-bold tracking-widest mb-2">Detail Rekening</p>
                                <p class="text-sm font-bold text-gray-900" id="bankDetailName">—</p>
                                <p class="text-lg font-extrabold text-gray-900 tracking-widest mt-1" id="bankDetailNumber">—</p>
                                <p class="text-xs text-gray-500 mt-0.5">a.n. <span id="bankDetailHolder">TANKEN ID</span></p>
                            </div>

                            <p class="text-xs text-gray-500 bg-gray-50 border border-gray-100 rounded-lg p-3">
                                <i class="fa-solid fa-circle-info mr-1 text-gray-400"></i>
                                Transfer sesuai total tagihan ke rekening di atas, lalu upload bukti transfer di bawah.
                            </p>
                        </div>

                        {{-- FORM: QRIS --}}
                        <div id="form-qris" class="payment-block hidden">
                            <div class="flex flex-col items-center py-4">
                                {{-- QR Placeholder transparan / dummy --}}
                                <div class="qris-placeholder mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#9ca3af" stroke-width="1.2" width="48" height="48">
                                        <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/>
                                        <path d="M14 14h.01M14 17h.01M17 14h.01M17 17h.01M20 14h.01M20 17h.01M20 20h.01M17 20h.01M14 20h.01"/>
                                    </svg>
                                </div>
                                <p class="text-xs font-bold text-gray-500 tracking-widest uppercase">TANKEN — QRIS</p>
                                <p class="text-[11px] text-gray-400 mt-1">Scan QR code ini dengan aplikasi dompet digital apapun</p>
                            </div>
                            <p class="text-xs text-gray-500 bg-gray-50 border border-gray-100 rounded-lg p-3 mt-2">
                                <i class="fa-solid fa-circle-info mr-1 text-gray-400"></i>
                                Setelah transfer via QRIS, upload screenshot bukti pembayaran di bawah.
                            </p>
                        </div>

                        {{-- UPLOAD BUKTI --}}
                        <div class="mt-7 pt-6 border-t border-gray-100">
                            <label class="form-label mb-3">Upload Bukti Pembayaran <span class="text-red-500">*</span></label>

                            <div class="upload-zone" id="uploadZone">
                                <input type="file" name="payment_proof" id="paymentProof" class="upload-input" accept=".jpg,.jpeg,.png">

                                <div id="uploadUiDefault" class="flex flex-col items-center">
                                    <div class="w-11 h-11 rounded-full bg-gray-100 flex items-center justify-center mb-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" class="w-5 h-5 text-gray-500">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                        </svg>
                                    </div>
                                    <p class="text-sm font-semibold text-gray-700">Klik atau seret file ke sini</p>
                                    <p class="text-xs text-gray-400 mt-1">PNG atau JPG · Maks. 3 MB</p>
                                </div>

                                <div id="uploadUiSuccess" class="hidden flex flex-col items-center">
                                    <div class="w-11 h-11 rounded-full bg-green-100 flex items-center justify-center mb-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="w-5 h-5 text-green-600">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                    <p class="text-sm font-semibold text-green-700 truncate max-w-[200px]" id="fileNameDisplay">—</p>
                                    <p class="text-xs text-gray-400 mt-1">Klik untuk mengganti</p>
                                </div>
                            </div>

                            <p id="uploadRequiredMsg" class="hidden text-xs font-medium text-red-500 mt-2">
                                <i class="fa-solid fa-circle-exclamation mr-1"></i> Bukti pembayaran wajib di-upload.
                            </p>
                            <p id="uploadSizeMsg" class="hidden text-xs font-medium text-red-500 mt-2">
                                <i class="fa-solid fa-circle-exclamation mr-1"></i> Ukuran file terlalu besar. Maksimal 3 MB.
                            </p>
                        </div>

                        {{-- TOMBOL --}}
                        <div class="grid grid-cols-2 gap-3 mt-8">
                            <a href="{{ route('pelanggan.checkout.index') }}" class="back-btn">
                                <i class="fa-solid fa-arrow-left text-xs"></i> Kembali
                            </a>
                            <button type="submit" class="continue-btn">Lanjutkan</button>
                        </div>
                    </form>

                </div>
            </div>

            {{-- KANAN: SUMMARY --}}
            <div class="w-full lg:w-80 flex-shrink-0">
                <div class="summary-card">
                    <p class="text-xs font-bold tracking-widest uppercase text-gray-400 mb-1">Ringkasan</p>
                    <h2 class="font-extrabold text-gray-900 text-base mb-5">Ringkasan Pesanan</h2>

                    <div class="flex flex-col gap-3 mb-4">
                        @foreach($cartItems as $item)
                        <div class="flex items-center gap-3">
                            @if($item['image'])
                                <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="w-12 h-14 object-cover rounded-md bg-gray-100 flex-shrink-0">
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
                            <span class="text-gray-500">Subtotal</span>
                            <span class="font-semibold text-gray-800">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Ongkos Kirim</span>
                            <span class="font-semibold text-gray-800">Rp {{ number_format($shippingCost, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">PPN (11%)</span>
                            <span class="font-semibold text-gray-800">Rp {{ number_format($ppn, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <hr class="summary-divider">

                    <div class="flex justify-between items-center">
                        <span class="font-extrabold text-gray-900">Total</span>
                        <span class="font-extrabold text-gray-900 text-lg">
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
    // Data bank dari controller
    const bankData = @json($banks);

    function selectPayment(method, el) {
        document.querySelectorAll('.pay-tab').forEach(t => t.classList.remove('active'));
        el.classList.add('active');
        document.getElementById('selectedPaymentMethod').value = method;

        document.querySelectorAll('.payment-block').forEach(b => b.classList.add('hidden'));
        document.getElementById('form-' + method).classList.remove('hidden');
    }

    // Tampilkan detail rekening saat bank dipilih
    document.getElementById('bankProvider').addEventListener('change', function () {
        const selected = bankData.find(b => b.value === this.value);
        if (selected) {
            document.getElementById('bankDetailName').textContent   = selected.label;
            document.getElementById('bankDetailNumber').textContent = selected.number;
            document.getElementById('bankDetailHolder').textContent = selected.name;
            document.getElementById('bankDetail').classList.remove('hidden');
            this.classList.remove('is-invalid');
            document.getElementById('bankErrorMsg').classList.add('hidden');
        }
    });

    // Upload logic
    const uploadInput = document.getElementById('paymentProof');
    const uploadZone  = document.getElementById('uploadZone');
    const uiDefault   = document.getElementById('uploadUiDefault');
    const uiSuccess   = document.getElementById('uploadUiSuccess');
    const MAX_SIZE    = 3 * 1024 * 1024;

    uploadInput.addEventListener('change', function () {
        const file = this.files[0];
        document.getElementById('uploadRequiredMsg').classList.add('hidden');
        document.getElementById('uploadSizeMsg').classList.add('hidden');
        uploadZone.classList.remove('is-invalid');

        if (!file) return;

        if (file.size > MAX_SIZE) {
            document.getElementById('uploadSizeMsg').classList.remove('hidden');
            uploadZone.classList.remove('has-file');
            uiDefault.classList.remove('hidden');
            uiSuccess.classList.add('hidden');
            this.value = '';
            return;
        }

        uploadZone.classList.add('has-file');
        uiDefault.classList.add('hidden');
        uiSuccess.classList.remove('hidden');
        document.getElementById('fileNameDisplay').textContent = file.name;
    });

    ['dragenter','dragover'].forEach(e => uploadZone.addEventListener(e, () => uploadZone.classList.add('dragover')));
    ['dragleave','drop'].forEach(e => uploadZone.addEventListener(e, () => uploadZone.classList.remove('dragover')));

    // Validasi submit
    document.getElementById('paymentForm').addEventListener('submit', function (e) {
        e.preventDefault();
        let valid = true;
        const method = document.getElementById('selectedPaymentMethod').value;

        // Reset errors
        document.getElementById('bankErrorMsg').classList.add('hidden');
        document.getElementById('bankProvider').classList.remove('is-invalid');
        document.getElementById('uploadRequiredMsg').classList.add('hidden');
        uploadZone.classList.remove('is-invalid');

        if (method === 'bank_transfer') {
            const bank = document.getElementById('bankProvider');
            if (!bank.value) {
                valid = false;
                bank.classList.add('is-invalid');
                document.getElementById('bankErrorMsg').classList.remove('hidden');
            }
        }

        if (uploadInput.files.length === 0) {
            valid = false;
            uploadZone.classList.add('is-invalid');
            document.getElementById('uploadRequiredMsg').classList.remove('hidden');
            uploadZone.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        if (valid) this.submit();
    });
</script>
@endpush