@extends('layouts.main')

@section('title', 'Pembayaran — TANKEN')

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

    /* Form Styles */
    .form-label {
        display: block; font-size: 0.68rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase;
        color: #9ca3af; margin-bottom: 6px;
    }
    .form-input {
        width: 100%; padding: 12px 14px; border: 1.5px solid #e5e7eb; border-radius: 6px;
        font-family: 'Inter', sans-serif; font-size: 0.875rem; color: #111; outline: none; transition: border-color 0.2s; background: #fff;
    }
    .form-input.is-invalid { border-color: #ef4444; background-color: #fef2f2; }
    .form-input:focus { border-color: #111; }
    .form-input::placeholder { color: #c5c5c5; }

    /* Custom Beautiful Dropdown */
    .form-select {
        width: 100%; padding: 12px 40px 12px 14px; border: 1.5px solid #e5e7eb; border-radius: 6px;
        font-family: 'Inter', sans-serif; font-size: 0.875rem; color: #111; outline: none; transition: border-color 0.2s; 
        background-color: #fff; appearance: none;
        background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2224%22%20height%3D%2224%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%23111%22%20stroke-width%3D%222%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpolyline%20points%3D%226%209%2012%2015%2018%209%22%3E%3C%2Fpolyline%3E%3C%2Fsvg%3E");
        background-repeat: no-repeat; background-position: right 12px center; background-size: 16px; cursor: pointer;
    }
    .form-select.is-invalid { border-color: #ef4444; background-color: #fef2f2; }
    .form-select:focus { border-color: #111; box-shadow: 0 0 0 3px rgba(17,17,17,0.05); }

    /* Upload Zone Styles */
    .upload-zone {
        border: 2px dashed #d1d5db; border-radius: 8px; padding: 28px 20px; text-align: center;
        background-color: #f9fafb; transition: all 0.2s ease; position: relative; cursor: pointer;
    }
    .upload-zone.is-invalid { border-color: #ef4444; background-color: #fef2f2; }
    .upload-zone:hover, .upload-zone.dragover { border-color: #111; background-color: #f3f4f6; }
    .upload-zone.has-file { border-style: solid; border-color: #10b981; background-color: #ecfdf5; }
    .upload-input { position: absolute; inset: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; z-index: 10; }

    /* Payment Options Grid */
    .payment-box {
        border: 1.5px solid #e5e7eb; border-radius: 6px; padding: 16px; cursor: pointer;
        display: flex; flex-direction: column; align-items: flex-start; gap: 12px; transition: all 0.2s;
        color: #111; background: #fff; height: 100%;
    }
    .payment-box:hover { border-color: #111; }
    .payment-box.active { background: #111; border-color: #111; color: #fff; }
    .payment-box i { font-size: 1.3rem; color: #6b7280; transition: color 0.2s; }
    .payment-box.active i { color: #fff; }
    .payment-box span { font-size: 0.8rem; font-weight: 600; }

    /* Summary card */
    .summary-card { border: 1.5px solid #e5e7eb; border-radius: 8px; padding: 22px; position: sticky; top: 80px; }
    .summary-divider { border: none; border-top: 1.5px solid #f3f4f6; margin: 14px 0; }

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

@php
// Data dummy
$cartItems = $cartItems ?? collect([
    ['id'=>1,'name'=>'TANKEN | Celana Pendek Wanita Nylon Crinkle','sku'=>'TKN-190','image'=>'men-home2.jpg','size'=>'M','qty'=>1,'price'=>129000],
]);
$PPN_RATE      = 0.11;
$shippingCost  = 130000; 
$biayaLayanan  = 2000;   

$subtotal      = collect($cartItems)->sum(fn($i) => $i['price'] * $i['qty']);
$ppn           = round($subtotal * $PPN_RATE);
$total         = $subtotal + $ppn + $shippingCost + $biayaLayanan;
@endphp

<div class="bg-white min-h-screen">
    <div class="max-w-7xl mx-auto px-5 sm:px-6 lg:px-10 py-6 sm:py-10">

        {{-- Breadcrumb --}}
        <p class="text-[10px] sm:text-xs font-bold tracking-widest uppercase text-gray-400 mb-1 sm:mb-2">Pembelian</p>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mb-6 sm:mb-8">Checkout</h1>

        {{-- ===== STEP INDICATOR (SESUAI SCREENSHOT) ===== --}}
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

        <div class="flex flex-col lg:flex-row gap-6 lg:gap-8 items-start">

            {{-- ===== KIRI: FORM PAYMENT ===== --}}
            <div class="flex-1 w-full min-w-0">
                <div class="border border-gray-200 rounded-lg p-5 sm:p-7">

                    <form action="{{ route('checkout.review') ?? '#' }}" method="POST" id="paymentForm" enctype="multipart/form-data" novalidate>
                        @csrf
                        
                        <input type="hidden" name="payment_method" id="selectedPaymentMethod" value="bank_transfer">

                        {{-- Payment Options Grid --}}
                        <div class="grid grid-cols-2 gap-3 mb-6 sm:mb-8">
                            <div class="payment-box active" onclick="selectPayment('bank_transfer', this)">
                                <i class="fa-solid fa-building-columns"></i>
                                <span>Transfer Bank Manual</span>
                            </div>
                            <div class="payment-box" onclick="selectPayment('ewallet', this)">
                                <i class="fa-solid fa-wallet"></i>
                                <span>E-Wallet</span>
                            </div>
                            <div class="payment-box" onclick="selectPayment('virtual_account', this)">
                                <i class="fa-solid fa-laptop-code"></i>
                                <span>Virtual Account (VA)</span>
                            </div>
                            <div class="payment-box" onclick="selectPayment('credit_card', this)">
                                <i class="fa-regular fa-credit-card"></i>
                                <span>Kartu Kredit / Debit</span>
                            </div>
                        </div>

                        {{-- ============================================== --}}
                        {{-- DYNAMIC FORM SECTIONS --}}
                        {{-- ============================================== --}}

                        {{-- FORM: Bank Transfer (Default Aktif) --}}
                        <div id="form-bank_transfer" class="payment-details-block block">
                            <label class="form-label">Pilih Bank Tujuan</label>
                            <select name="bank_provider" id="bankProvider" class="form-select mb-1">
                                <option value="" disabled selected>-- Pilih Bank Tujuan Transfer --</option>
                                <option value="bca">BCA - 1234567890 a.n TANKEN ID</option>
                                <option value="mandiri">Mandiri - 0987654321 a.n TANKEN ID</option>
                                <option value="bri">BRI - 1122334455 a.n TANKEN ID</option>
                            </select>
                            {{-- Pesan Error Custom --}}
                            <p id="bankErrorMsg" class="hidden text-xs font-medium text-red-500 mb-3"><i class="fa-solid fa-circle-exclamation mr-1"></i> Silakan pilih bank tujuan.</p>
                            
                            <p class="text-xs text-gray-500 leading-relaxed bg-gray-50 p-3 rounded-md border border-gray-100 mt-2">
                                <i class="fa-solid fa-circle-info mr-1 text-gray-400"></i>
                                Silakan transfer sesuai total tagihan ke rekening di atas, lalu <strong>upload bukti transfer</strong> pada kolom yang disediakan di bawah agar admin dapat memverifikasi pesananmu.
                            </p>
                        </div>

                        {{-- FORM: E-Wallet --}}
                        <div id="form-ewallet" class="payment-details-block hidden">
                            <label class="form-label">Pilih Penyedia Layanan</label>
                            <select name="ewallet_provider" id="ewalletProvider" class="form-select mb-1">
                                <option value="" disabled selected>-- Pilih E-Wallet --</option>
                                <option value="gopay">GoPay - 081234567890</option>
                                <option value="ovo">OVO - 081234567890</option>
                                <option value="shopeepay">ShopeePay - 081234567890</option>
                                <option value="dana">DANA - 081234567890</option>
                            </select>
                            <p id="ewalletErrorMsg" class="hidden text-xs font-medium text-red-500 mb-3"><i class="fa-solid fa-circle-exclamation mr-1"></i> Silakan pilih layanan E-Wallet.</p>

                            <p class="text-xs text-gray-500 leading-relaxed bg-gray-50 p-3 rounded-md border border-gray-100 mt-2">
                                <i class="fa-solid fa-circle-info mr-1 text-gray-400"></i>
                                Transfer ke nomor E-Wallet yang tertera, pastikan nama tujuan adalah <strong>TANKEN ID</strong>. Jangan lupa upload bukti transfermu di bawah.
                            </p>
                        </div>

                        {{-- FORM: Virtual Account --}}
                        <div id="form-virtual_account" class="payment-details-block hidden">
                            <label class="form-label">Pilih Bank Virtual Account</label>
                            <select name="va_provider" id="vaProvider" class="form-select mb-1">
                                <option value="" disabled selected>-- Pilih Bank VA --</option>
                                <option value="bca_va">BCA Virtual Account</option>
                                <option value="mandiri_va">Mandiri Virtual Account</option>
                                <option value="bni_va">BNI Virtual Account</option>
                                <option value="permata_va">Permata Virtual Account</option>
                            </select>
                            <p id="vaErrorMsg" class="hidden text-xs font-medium text-red-500 mb-3"><i class="fa-solid fa-circle-exclamation mr-1"></i> Silakan pilih bank Virtual Account.</p>

                            <p class="text-xs text-gray-500 leading-relaxed bg-gray-50 p-3 rounded-md border border-gray-100 mt-2">
                                <i class="fa-solid fa-circle-info mr-1 text-gray-400"></i>
                                Nomor Virtual Account (VA) akan dibuat otomatis setelah kamu menekan tombol Lanjutkan. Silakan bayar melalui aplikasi m-banking milikmu.
                            </p>
                        </div>

                        {{-- FORM: Credit / Debit Card --}}
                        <div id="form-credit_card" class="payment-details-block hidden">
                            <div class="mb-4">
                                <label class="form-label">Nomor Kartu</label>
                                <input type="text" name="cc_number" id="ccNumber" class="form-input mb-1" placeholder="1234 5678 9012 3456" maxlength="19">
                                <p id="ccNumErrorMsg" class="hidden text-xs font-medium text-red-500"><i class="fa-solid fa-circle-exclamation mr-1"></i> Nomor kartu wajib diisi.</p>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="form-label">Masa Berlaku</label>
                                    <input type="text" name="cc_expiry" id="ccExpiry" class="form-input mb-1" placeholder="MM/YY" maxlength="5">
                                    <p id="ccExpErrorMsg" class="hidden text-xs font-medium text-red-500"><i class="fa-solid fa-circle-exclamation mr-1"></i> Wajib diisi.</p>
                                </div>
                                <div>
                                    <label class="form-label">CVV</label>
                                    <input type="password" name="cc_cvv" id="ccCvv" class="form-input mb-1" placeholder="123" maxlength="3">
                                    <p id="ccCvvErrorMsg" class="hidden text-xs font-medium text-red-500"><i class="fa-solid fa-circle-exclamation mr-1"></i> Wajib diisi.</p>
                                </div>
                            </div>
                        </div>

                        {{-- ============================================== --}}
                        {{-- UPLOAD BUKTI PEMBAYARAN (VERIFIKASI ADMIN) --}}
                        {{-- ============================================== --}}
                        <div id="uploadSection" class="mt-8 pt-6 border-t border-gray-100 block">
                            <label class="form-label mb-3 text-gray-900">Upload Bukti Pembayaran <span class="text-red-500">*</span></label>
                            
                            <div class="upload-zone" id="uploadZone">
                                <input type="file" name="payment_proof" id="paymentProof" class="upload-input" accept=".jpg, .jpeg, .png">
                                
                                <div id="uploadUiDefault" class="flex flex-col items-center justify-center">
                                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-gray-100 flex items-center justify-center mb-2 sm:mb-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" class="w-5 h-5 sm:w-6 sm:h-6 text-gray-500">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                        </svg>
                                    </div>
                                    <p class="text-xs sm:text-sm font-semibold text-gray-700">Klik atau seret file ke sini</p>
                                    <p class="text-[10px] sm:text-xs text-gray-400 mt-1">Hanya menerima PNG atau JPG. Maksimal 3 MB.</p>
                                </div>

                                <div id="uploadUiSuccess" class="hidden flex flex-col items-center justify-center">
                                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-green-100 flex items-center justify-center mb-2 sm:mb-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="w-5 h-5 sm:w-6 sm:h-6 text-green-600">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    <p class="text-xs sm:text-sm font-semibold text-green-700 truncate max-w-[200px]" id="fileNameDisplay">file_bukti.jpg</p>
                                    <p class="text-[10px] sm:text-xs text-gray-500 mt-1">Klik untuk mengganti foto</p>
                                </div>
                            </div>

                            {{-- Pesan Error Custom (Bawah Kotak Upload) --}}
                            <p id="uploadRequiredMsg" class="hidden text-xs font-medium text-red-500 mt-2 bg-red-50 p-2 rounded-md border border-red-100">
                                <i class="fa-solid fa-circle-exclamation mr-1"></i> Bukti pembayaran wajib di-upload.
                            </p>
                            <p id="uploadErrorMsg" class="hidden text-xs font-medium text-red-500 mt-2 bg-red-50 p-2 rounded-md border border-red-100">
                                <i class="fa-solid fa-circle-exclamation mr-1"></i> Ukuran file terlalu besar. Maksimal 3 MB!
                            </p>
                        </div>

                        {{-- BUTTONS --}}
                        <div class="grid grid-cols-2 gap-3 sm:gap-4 mt-8">
                            <a href="{{ route('checkout.index') ?? '#' }}" class="back-btn flex items-center justify-center gap-2">
                                <i class="fa-solid fa-arrow-left text-xs hidden sm:inline-block"></i> Kembali
                            </a>
                            <button type="submit" class="continue-btn" id="submitBtn">
                                Lanjutkan
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
                        @php $imgPath = isset($item['image']) ? asset('images/'.$item['image']) : null; @endphp
                        <div class="flex items-center gap-3">
                            @if($imgPath)
                                <img src="{{ $imgPath }}" alt="{{ $item['name'] }}" class="w-10 h-12 sm:w-12 sm:h-14 object-cover rounded-md bg-gray-100 flex-shrink-0">
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
                        <div class="flex justify-between">
                            <span class="text-gray-500">Biaya Layanan</span>
                            <span class="font-semibold text-gray-800">Rp {{ number_format($biayaLayanan, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">PPN (11%)</span>
                            <span class="font-semibold text-gray-800">Rp {{ number_format($ppn, 0, ',', '.') }}</span>
                        </div>
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
    // 1. FUNGSI GANTI METODE PEMBAYARAN
    function selectPayment(methodId, element) {
        document.querySelectorAll('.payment-box').forEach(box => box.classList.remove('active'));
        element.classList.add('active');
        document.getElementById('selectedPaymentMethod').value = methodId;

        document.querySelectorAll('.payment-details-block').forEach(form => {
            form.classList.remove('block');
            form.classList.add('hidden');
        });

        const selectedForm = document.getElementById('form-' + methodId);
        if (selectedForm) {
            selectedForm.classList.remove('hidden');
            selectedForm.classList.add('block');
        }

        // Tampilkan upload file HANYA untuk transfer bank & e-wallet
        const uploadSec = document.getElementById('uploadSection');
        if(methodId === 'bank_transfer' || methodId === 'ewallet') {
            uploadSec.classList.remove('hidden');
            uploadSec.classList.add('block');
        } else {
            uploadSec.classList.remove('block');
            uploadSec.classList.add('hidden');
        }
    }

    // 2. FUNGSI PREVIEW FILE UPLOAD (MAX 3MB, PNG/JPG)
    const uploadInput = document.getElementById('paymentProof');
    const uploadZone = document.getElementById('uploadZone');
    const uiDefault = document.getElementById('uploadUiDefault');
    const uiSuccess = document.getElementById('uploadUiSuccess');
    const fileNameDisplay = document.getElementById('fileNameDisplay');
    const sizeErrorMsg = document.getElementById('uploadErrorMsg');
    const requiredErrorMsg = document.getElementById('uploadRequiredMsg');

    const MAX_SIZE = 3 * 1024 * 1024;

    uploadInput.addEventListener('change', function(e) {
        const file = this.files[0];
        // Hilangkan pesan error "required" jika user mulai memilih file
        requiredErrorMsg.classList.add('hidden');
        uploadZone.classList.remove('is-invalid');
        
        if (file) {
            if (file.size > MAX_SIZE) {
                sizeErrorMsg.classList.remove('hidden');
                uploadZone.classList.remove('has-file');
                uiDefault.classList.remove('hidden');
                uiSuccess.classList.add('hidden');
                this.value = ''; // Kosongkan file
            } else {
                sizeErrorMsg.classList.add('hidden');
                uploadZone.classList.add('has-file');
                uiDefault.classList.add('hidden');
                uiSuccess.classList.remove('hidden');
                fileNameDisplay.textContent = file.name;
            }
        }
    });

    uploadInput.addEventListener('dragenter', () => uploadZone.classList.add('dragover'));
    uploadInput.addEventListener('dragleave', () => uploadZone.classList.remove('dragover'));
    uploadInput.addEventListener('drop', () => uploadZone.classList.remove('dragover'));

    // 3. JS CUSTOM VALIDATION (Pengganti Popup Bawaan Browser)
    document.getElementById('paymentForm').addEventListener('submit', function(e) {
        e.preventDefault(); // Tahan pengiriman form sementara
        
        let isValid = true;
        const method = document.getElementById('selectedPaymentMethod').value;
        
        // --- Reset Semua Pesan Error Terlebih Dahulu ---
        document.querySelectorAll('.text-red-500.hidden').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

        // --- Cek Berdasarkan Metode Pembayaran ---
        if (method === 'bank_transfer') {
            const select = document.getElementById('bankProvider');
            if (!select.value) {
                isValid = false;
                select.classList.add('is-invalid');
                document.getElementById('bankErrorMsg').classList.remove('hidden');
            }
        } else if (method === 'ewallet') {
            const select = document.getElementById('ewalletProvider');
            if (!select.value) {
                isValid = false;
                select.classList.add('is-invalid');
                document.getElementById('ewalletErrorMsg').classList.remove('hidden');
            }
        } else if (method === 'virtual_account') {
            const select = document.getElementById('vaProvider');
            if (!select.value) {
                isValid = false;
                select.classList.add('is-invalid');
                document.getElementById('vaErrorMsg').classList.remove('hidden');
            }
        } else if (method === 'credit_card') {
            const ccNum = document.getElementById('ccNumber');
            const ccExp = document.getElementById('ccExpiry');
            const ccCvv = document.getElementById('ccCvv');
            
            if (!ccNum.value.trim()) { isValid = false; ccNum.classList.add('is-invalid'); document.getElementById('ccNumErrorMsg').classList.remove('hidden'); }
            if (!ccExp.value.trim()) { isValid = false; ccExp.classList.add('is-invalid'); document.getElementById('ccExpErrorMsg').classList.remove('hidden'); }
            if (!ccCvv.value.trim()) { isValid = false; ccCvv.classList.add('is-invalid'); document.getElementById('ccCvvErrorMsg').classList.remove('hidden'); }
        }

        // --- Cek File Upload (Khusus Transfer & E-Wallet) ---
        if (method === 'bank_transfer' || method === 'ewallet') {
            if (uploadInput.files.length === 0) {
                isValid = false;
                uploadZone.classList.add('is-invalid');
                requiredErrorMsg.classList.remove('hidden');
                
                // Scroll layar otomatis ke arah kotak upload
                uploadZone.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }

        // Jika semua validasi lulus, kirim formnya!
        if (isValid) {
            this.submit();
        }
    });

    // Hapus pesan error saat dropdown diubah
    document.querySelectorAll('.form-select').forEach(select => {
        select.addEventListener('change', function() {
            this.classList.remove('is-invalid');
            const errorMsg = this.nextElementSibling;
            if(errorMsg && errorMsg.tagName === 'P') errorMsg.classList.add('hidden');
        });
    });

    // Hapus pesan error saat input text diketik
    document.querySelectorAll('.form-input').forEach(input => {
        input.addEventListener('input', function() {
            this.classList.remove('is-invalid');
            const errorMsg = this.nextElementSibling;
            if(errorMsg && errorMsg.tagName === 'P') errorMsg.classList.add('hidden');
        });
    });
</script>
@endpush