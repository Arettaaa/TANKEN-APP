@extends('layouts.auth')

@section('title', 'Verifikasi OTP')
@section('left-title', 'Join TANKEN')
@section('left-desc', 'Create an account to unlock exclusive benefits, track orders, and save your favorites.')

@section('content')
    @if(session('error'))
        <div class="mb-5 p-3 bg-red-50 text-red-600 text-sm font-semibold rounded-lg border border-red-100 text-center">
            {{ session('error') }}
        </div>
    @endif
    @if(session('success'))
        <div class="mb-5 p-3 bg-green-50 text-green-600 text-sm font-semibold rounded-lg border border-green-100 text-center">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-7">
        <h2 class="font-extrabold text-3xl text-gray-900 mb-1">Verify Email</h2>
        <p class="text-sm text-gray-400 font-medium">Masukkan 6 digit kode OTP yang telah dikirim ke email Anda.</p>
    </div>

    <form action="{{ route('otp.verify.process') }}" method="POST" id="otpForm" class="flex flex-col gap-3">
        @csrf
        
        <!-- Container 6 Kotak Input OTP -->
        <div class="flex justify-between gap-2 mt-2">
            @for ($i = 0; $i < 6; $i++)
                <input type="text" maxlength="1" class="otp-input w-12 h-14 text-center text-2xl font-extrabold text-gray-900 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-black focus:border-black outline-none transition-all shadow-sm" required>
            @endfor
        </div>
        
        <input type="hidden" name="otp" id="hiddenOTP">

        <!-- Elemen Countdown Timer -->
        <p id="countdown-text" class="text-center text-sm font-semibold text-gray-600 mt-2 mb-2">
            Waktu tersisa: <span id="timer" class="text-red-500 font-bold">10:00</span>
        </p>

        <button type="submit" id="verifyBtn" class="w-full bg-black text-white font-bold text-sm py-4 rounded-full flex items-center justify-center gap-2 hover:bg-gray-900 transition-colors">
            Verify Account
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" width="14" height="14"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </button>
    </form>

    <!-- Area Navigasi Bawah (Kirim Ulang & Daftar Ulang) -->
    <div class="text-center mt-6 flex flex-col gap-4">
        <!-- Fitur Kirim Ulang OTP -->
        <div class="flex items-center justify-center gap-1">
            <span class="text-sm text-gray-500">Belum menerima kode?</span>
            <form action="{{ route('otp.resend') }}" method="POST" class="inline m-0 p-0">
                @csrf
                <button type="submit" id="resendBtn" class="font-bold text-sm text-gray-900 hover:underline bg-transparent border-none cursor-pointer p-0">
                    Kirim Ulang
                </button>
            </form>
        </div>

        <!-- Fitur Batal & Daftar Ulang -->
        <a href="{{ route('otp.cancel') }}" class="text-sm text-red-500 font-semibold hover:underline transition-colors">
            Salah alamat email? Daftar Ulang
        </a>
    </div>

<!-- Script UX OTP & Countdown Timer -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const inputs = document.querySelectorAll('.otp-input');
        const hiddenInput = document.getElementById('hiddenOTP');
        const form = document.getElementById('otpForm');
        const verifyBtn = document.getElementById('verifyBtn');
        const timerDisplay = document.getElementById('timer');
        const countdownText = document.getElementById('countdown-text');
        
        // LOGIKA COUNTDOWN TIMER
        let timeLeft = 600; // 10 menit = 600 detik

        const countdownInterval = setInterval(() => {
            timeLeft--;
            
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            
            timerDisplay.innerText = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            
            if (timeLeft <= 0) {
                clearInterval(countdownInterval);
                countdownText.innerHTML = '<span class="text-red-600 font-bold">Waktu OTP habis. Silakan kirim ulang kode.</span>';
                
                inputs.forEach(input => {
                    input.disabled = true;
                    input.classList.add('bg-gray-100', 'cursor-not-allowed');
                });
                verifyBtn.disabled = true;
                verifyBtn.classList.add('opacity-50', 'cursor-not-allowed');
            }
        }, 1000); 

        // LOGIKA UX KOTAK OTP
        inputs.forEach((input, index) => {
            input.addEventListener('input', (e) => {
                if (e.target.value.length === 1 && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
            });

            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && e.target.value === '' && index > 0) {
                    inputs[index - 1].focus();
                }
            });
            
            input.addEventListener('keypress', (e) => {
                if (e.key < '0' || e.key > '9') {
                    e.preventDefault();
                }
            });
            
            input.addEventListener('paste', (e) => {
                e.preventDefault();
                const pastedData = e.clipboardData.getData('text').slice(0, 6).split('');
                if(pastedData.every(char => char >= '0' && char <= '9')){
                    inputs.forEach((inpt, i) => {
                        if(pastedData[i]) {
                            inpt.value = pastedData[i];
                            if(i < inputs.length - 1) inputs[i + 1].focus();
                        }
                    });
                }
            });
        });

        // Penggabungan nilai saat form dikirim
        form.addEventListener('submit', (e) => {
            let otpValue = '';
            inputs.forEach(input => {
                otpValue += input.value;
            });
            hiddenInput.value = otpValue;
        });
    });
</script>
@endsection