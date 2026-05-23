@extends('layouts.admin')

@section('title', 'Edit Akun Admin')

@section('content')
<div class="max-w-4xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    {{-- Header Halaman Admin --}}
    <div class="mb-8 pb-4 border-b border-gray-200">
        <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Pengaturan Akun Admin</h1>
        <p class="text-sm text-gray-500 mt-1">Perbarui informasi profil dan kredensial keamanan hak akses administratif Anda.</p>
    </div>

    {{-- Form Card --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <form action="{{ route('admin.profile.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="p-6 sm:p-8 space-y-6">
                {{-- Bagian 1: Informasi Profil --}}
                <div>
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                        <i class="fa-regular fa-user text-gray-400"></i> Informasi Profil
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ auth()->user()->name ?? 'Administrator' }}" 
                                class="w-full border border-gray-200 rounded-lg px-4 py-3 text-sm text-gray-800 bg-gray-50/50 focus:bg-white focus:outline-none focus:border-black focus:ring-4 focus:ring-black/5 transition-all" required>
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">Alamat Email</label>
                            <input type="email" name="email" value="{{ auth()->user()->email ?? 'admin@tanken.id' }}" 
                                class="w-full border border-gray-200 rounded-lg px-4 py-3 text-sm text-gray-800 bg-gray-50/50 focus:bg-white focus:outline-none focus:border-black focus:ring-4 focus:ring-black/5 transition-all" required>
                        </div>
                    </div>
                </div>

                <hr class="border-gray-100">

                {{-- Bagian 2: Kredensial Keamanan (Tanpa Password Saat Ini) --}}
                <div>
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-2 flex items-center gap-2">
                        <i class="fa-solid fa-shield-halved text-gray-400"></i> Kredensial Keamanan
                    </h3>
                    <p class="text-xs text-gray-400 mb-4">Kosongkan kolom di bawah jika Anda tidak ingin memperbarui password saat ini.</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Password Baru --}}
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">Password Baru</label>
                            <input type="password" id="pwNew" name="password" placeholder="Masukkan password baru" 
                                class="w-full border border-gray-200 rounded-lg px-4 py-3 text-sm text-gray-800 bg-gray-50/50 focus:bg-white focus:outline-none focus:border-black focus:ring-4 focus:ring-black/5 transition-all" 
                                oninput="checkStrength(this.value); checkMatch();">
                            
                            {{-- Strength Bars (Diadaptasi dari profil-password) --}}
                            <div class="flex gap-1.5 mt-2">
                                <div id="bar1" class="h-1 flex-1 rounded-full bg-gray-200 transition-colors duration-300"></div>
                                <div id="bar2" class="h-1 flex-1 rounded-full bg-gray-200 transition-colors duration-300"></div>
                                <div id="bar3" class="h-1 flex-1 rounded-full bg-gray-200 transition-colors duration-300"></div>
                                <div id="bar4" class="h-1 flex-1 rounded-full bg-gray-200 transition-colors duration-300"></div>
                            </div>
                            <p id="strength-label" class="text-xs font-semibold mt-1 transition-colors duration-300"></p>
                            @error('password')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Konfirmasi Password Baru --}}
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">Konfirmasi Password Baru</label>
                            <input type="password" id="pwConfirm" name="password_confirmation" placeholder="Ulangi password baru" 
                                class="w-full border border-gray-200 rounded-lg px-4 py-3 text-sm text-gray-800 bg-gray-50/50 focus:bg-white focus:outline-none focus:border-black focus:ring-4 focus:ring-black/5 transition-all" 
                                oninput="checkMatch()">
                            <p id="match-label" class="text-xs font-semibold mt-1 transition-colors duration-200"></p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Footer Form / Aksi Tombol --}}
            <div class="px-6 sm:px-8 py-4 bg-gray-50 border-t border-gray-50 flex items-center justify-end gap-3">
                <a href="{{ route('admin.dashboard') }}" class="text-sm font-bold text-gray-500 hover:text-black px-4 py-2 transition-colors">
                    Batal
                </a>
                <button type="submit" class="bg-black text-white px-6 py-3 text-xs font-bold tracking-widest uppercase rounded-lg hover:bg-gray-800 transition-colors shadow-sm">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Logika Detektor Kekuatan Password murni dari sistem profil-password kamu
    function checkStrength(val) {
        let score = 0;
        if (val.length >= 6) score++;
        if (/[A-Z]/.test(val) && /[a-z]/.test(val)) score++;
        if (/[0-9]/.test(val)) score++;
        if (/[^A-Za-z0-9]/.test(val)) score++;

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
        label.className = 'text-xs mt-1 font-semibold ' + (val.length === 0 ? 'text-gray-400' : textColors[score]);
    }

    // Logika Validasi Kecocokan Konfirmasi Pasword
    function checkMatch() {
        const pw = document.getElementById('pwNew').value;
        const confirm = document.getElementById('pwConfirm').value;
        const label = document.getElementById('match-label');

        if (confirm.length === 0) {
            label.textContent = '';
            return;
        }

        if (pw === confirm) {
            label.textContent = '✓ Password cocok';
            label.className = 'text-xs font-semibold mt-1 text-green-600';
        } else {
            label.textContent = '✗ Password tidak cocok';
            label.className = 'text-xs font-semibold mt-1 text-red-500';
        }
    }
</script>
@endpush