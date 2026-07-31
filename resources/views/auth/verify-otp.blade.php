@extends('layouts.auth')

@section('title', 'Verifikasi OTP')
@section('left-title', 'Join TANKEN')
@section('left-desc', 'Create an account to unlock exclusive benefits, track orders, and save your favorites.')

@section('content')
    {{-- Menampilkan pesan error atau success (jika ada) --}}
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

    {{-- Form Verifikasi --}}
    <form action="{{ route('otp.verify.process') }}" method="POST" id="otpForm" class="flex flex-col gap-5">
        @csrf
        
        <!-- Container 6 Kotak Input OTP -->
        <div class="flex justify-between gap-2 mt-2 mb-2">
            @for ($i = 0; $i < 6; $i++)
                <input type="text" maxlength="1" class="otp-input w-12 h-14 text-center text-2xl font-extrabold text-gray-900 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-black focus:border-black outline-none transition-all shadow-sm" required>
            @endfor
        </div>
        
        <!-- Hidden input untuk menampung gabungan 6 angka saat di-submit ke Controller -->
        <input type="hidden" name="otp" id="hiddenOTP">

        <button type="submit" class="w-full bg-black text-white font-bold text-sm py-4 rounded-full flex items-center justify-center gap-2 hover:bg-gray-900 transition-colors mt-2">
            Verify Account
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" width="14" height="14"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </button>
    </form>

    <p class="text-center text-sm text-gray-500 mt-6">
        Belum menerima kode?&nbsp;
        <!-- Link ini nantinya diarahkan ke route resend OTP -->
        <a href="#" class="font-bold text-gray-900 hover:underline">Kirim Ulang</a>
    </p>

<!-- Script agar UX 6 kotak OTP berjalan mulus -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const inputs = document.querySelectorAll('.otp-input');
        const hiddenInput = document.getElementById('hiddenOTP');
        const form = document.getElementById('otpForm');

        inputs.forEach((input, index) => {
            // Pindah otomatis ke kotak selanjutnya saat mengetik
            input.addEventListener('input', (e) => {
                if (e.target.value.length === 1 && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
            });

            // Pindah ke kotak sebelumnya jika menekan Backspace
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && e.target.value === '' && index > 0) {
                    inputs[index - 1].focus();
                }
            });
            
            // Mencegah input selain angka (hanya bisa 0-9)
            input.addEventListener('keypress', (e) => {
                if (e.key < '0' || e.key > '9') {
                    e.preventDefault();
                }
            });
            
            // Support untuk fitur Paste OTP langsung 6 digit
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

        // Saat tombol Verify ditekan, gabungkan ke-6 angka dan masukkan ke hiddenInput
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