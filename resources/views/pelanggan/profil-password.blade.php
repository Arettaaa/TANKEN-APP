@extends('layouts.akun-pelanggan')

@section('title', 'Ganti Password — TANKEN')

@section('akun-content')
<div>
    <p class="text-[10px] sm:text-xs font-bold tracking-widest uppercase text-gray-400 mb-1">Keamanan</p>
    <h2 class="text-lg sm:text-xl font-extrabold text-gray-900 mb-6">Ganti Password</h2>

    {{-- Cek apakah user login via Google --}}
    @if(auth()->user()->google_id)
    <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 flex items-start gap-3">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7"
            width="20" height="20" class="text-amber-500 flex-shrink-0 mt-0.5">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
        </svg>
        <div>
            <p class="text-sm font-semibold text-amber-800">Akun Google</p>
            <p class="text-xs text-amber-600 mt-0.5">Kamu login menggunakan Google, sehingga tidak memiliki password.
                Kelola keamanan akunmu melalui pengaturan akun Google.</p>
        </div>
    </div>

    @else

    <form action="{{ route('pelanggan.ganti-password.simpan') }}" method="POST" class="flex flex-col gap-4 sm:gap-5">
        @csrf
        @method('PUT')

        <div>
            <label class="form-label">Password Saat Ini</label>
            <div class="relative w-full">
                <input type="password" name="current_password" id="pwCurrent" class="form-input w-full pr-11" placeholder="••••••••">
                
                <button type="button" 
                        class="absolute inset-y-0 right-0 flex items-center pr-3 pl-3 text-gray-400 hover:text-gray-600 focus:outline-none transition-colors" 
                        onclick="togglePw('pwCurrent','eyeCurrent')">
                    <svg id="eyeCurrentOpen" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                        <circle cx="12" cy="12" r="3" />
                    </svg>
                    <svg id="eyeCurrentClosed" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7" class="w-4 h-4 hidden">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24" />
                        <line x1="1" y1="1" x2="23" y2="23" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
            </div>
        </div>

        <div>
            <label class="form-label">Password Baru</label>
            <div class="relative w-full">
                <input type="password" name="password" id="pwNew" class="form-input w-full pr-11" placeholder="Min. 8 karakter"
                    oninput="checkStrength(this.value); checkMatch()">
                
                <button type="button" 
                        class="absolute inset-y-0 right-0 flex items-center pr-3 pl-3 text-gray-400 hover:text-gray-600 focus:outline-none transition-colors" 
                        onclick="togglePw('pwNew','eyeNew')">
                    <svg id="eyeNewOpen" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                        <circle cx="12" cy="12" r="3" />
                    </svg>
                    <svg id="eyeNewClosed" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7" class="w-4 h-4 hidden">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24" />
                        <line x1="1" y1="1" x2="23" y2="23" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
            </div>

            <div class="mt-2">
                <div class="flex gap-1 mb-1">
                    <div id="bar1" class="h-1 flex-1 rounded-full bg-gray-200 transition-colors duration-300"></div>
                    <div id="bar2" class="h-1 flex-1 rounded-full bg-gray-200 transition-colors duration-300"></div>
                    <div id="bar3" class="h-1 flex-1 rounded-full bg-gray-200 transition-colors duration-300"></div>
                    <div id="bar4" class="h-1 flex-1 rounded-full bg-gray-200 transition-colors duration-300"></div>
                </div>
                <p id="strength-label" class="text-xs text-gray-400"></p>
            </div>
        </div>

        <div>
            <label class="form-label">Konfirmasi Password Baru</label>
            <div class="relative w-full">
                <input type="password" name="password_confirmation" id="pwConfirm" class="form-input w-full pr-11"
                    placeholder="Ulangi password baru" oninput="checkMatch()">
                
                <button type="button" 
                        class="absolute inset-y-0 right-0 flex items-center pr-3 pl-3 text-gray-400 hover:text-gray-600 focus:outline-none transition-colors" 
                        onclick="togglePw('pwConfirm','eyeConfirm')">
                    <svg id="eyeConfirmOpen" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                        <circle cx="12" cy="12" r="3" />
                    </svg>
                    <svg id="eyeConfirmClosed" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7" class="w-4 h-4 hidden">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24" />
                        <line x1="1" y1="1" x2="23" y2="23" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
            </div>
            <p id="match-label" class="text-xs mt-1.5 min-h-[16px]"></p>
        </div>

        <div class="pt-2">
            <button type="submit"
                class="w-full sm:w-auto bg-gray-900 text-white text-[10px] sm:text-xs font-bold tracking-widest uppercase px-7 py-3.5 rounded-md hover:bg-gray-700 transition-colors shadow-sm">
                Perbarui Password
            </button>
        </div>
    </form>
    @endif
</div>
@endsection

@push('akun-scripts')
<script>
    function togglePw(inputId, eyePrefix) {
        const input = document.getElementById(inputId);
        const isPass = input.type === 'password';
        
        input.type = isPass ? 'text' : 'password';
        
        // Ganti style.display dengan classList bawaan Tailwind agar lebih rapi
        document.getElementById(eyePrefix + 'Open').classList.toggle('hidden', !isPass);
        document.getElementById(eyePrefix + 'Closed').classList.toggle('hidden', isPass);
    }

    function checkStrength(val) {
        let score = 0;
        if (val.length >= 8)               score++;
        if (/[A-Z]/.test(val))             score++;
        if (/[0-9]/.test(val))             score++;
        if (/[^A-Za-z0-9]/.test(val))      score++;

        const bars = [
            document.getElementById('bar1'),
            document.getElementById('bar2'),
            document.getElementById('bar3'),
            document.getElementById('bar4'),
        ];
        const label = document.getElementById('strength-label');

        const colors = ['', 'bg-red-400', 'bg-orange-400', 'bg-yellow-400', 'bg-green-500'];
        const labels = ['', 'Sangat lemah', 'Lemah', 'Cukup kuat', 'Kuat'];
        const textColors = ['', 'text-red-400', 'text-orange-400', 'text-yellow-400', 'text-green-500'];

        bars.forEach((bar, i) => {
            bar.className = 'h-1 flex-1 rounded-full transition-colors duration-300 ' +
                (i < score ? colors[score] : 'bg-gray-200');
        });

        label.textContent = val.length === 0 ? '' : labels[score];
        label.className = 'text-xs mt-1 ' + (val.length === 0 ? 'text-gray-400' : textColors[score]);
    }

    function checkMatch() {
        const pw      = document.getElementById('pwNew').value;
        const confirm = document.getElementById('pwConfirm').value;
        const label   = document.getElementById('match-label');

        if (confirm.length === 0) {
            label.textContent = '';
            return;
        }

        if (pw === confirm) {
            label.textContent = '✓ Password cocok';
            label.className   = 'text-xs mt-1.5 text-green-500 font-medium';
        } else {
            label.textContent = '✗ Password tidak cocok';
            label.className   = 'text-xs mt-1.5 text-red-500 font-medium';
        }
    }
</script>
@endpush