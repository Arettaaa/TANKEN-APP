<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Voucher;
use App\Models\UserVoucher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
// Tambahan untuk fitur OTP
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return back()->with('error', 'Gagal mendaftar. Pastikan email belum digunakan dan password minimal 8 karakter.');
        }

        // Generate 6 Digit OTP
        $otpCode = rand(100000, 999999);

        // Buat user dengan status belum terverifikasi (menyimpan OTP)
        $user = User::create([
            'name'           => $request->name,
            'email'          => $request->email,
            'password'       => Hash::make($request->password),
            'role'           => 'customer',
            'otp_code'       => $otpCode,
            'otp_expires_at' => Carbon::now()->addMinutes(10), // Batas kedaluwarsa 10 menit
        ]);

        // Kirim email OTP menggunakan Mailable OtpMail
        Mail::to($user->email)->send(new OtpMail($otpCode));

        // Simpan email ke dalam session agar halaman verifikasi dapat melacak identitas pengguna
        session(['verify_email' => $user->email]);

        // Arahkan ke halaman input OTP
        return redirect()->route('otp.verify.page');
    }

    // Fungsi untuk menampilkan tampilan input OTP
    public function showOtpForm()
    {
        if (!session('verify_email')) {
            return redirect()->route('register');
        };
        
        return view('auth.verify-otp'); 
    }

    // Fungsi untuk memvalidasi dan memproses kode OTP
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric|digits:6'
        ]);

        // === TAMBAHKAN BARIS INI SEMENTARA ===
        // dd([
        //     'otp_yang_diketik' => $request->otp,
        //     'email_di_session' => session('verify_email')
        // ]);
        // =====================================

        $email = session('verify_email');
        $user = User::where('email', $email)
                    ->where('otp_code', $request->otp)
                    ->first();

        // Verifikasi kecocokan kode
        if (!$user) {
            return back()->with('error', 'Kode OTP salah. Silakan periksa kembali kotak masuk Anda.');
        }

        // Verifikasi batas waktu
        if (Carbon::now()->greaterThan($user->otp_expires_at)) {
            return back()->with('error', 'Kode OTP sudah kedaluwarsa. Silakan lakukan pendaftaran ulang.');
        }

        // Jika berhasil, perbarui data akun dan bersihkan kode OTP
        $user->update([
            'email_verified_at' => Carbon::now(),
            'otp_code' => null,
            'otp_expires_at' => null
        ]);

        // Berikan voucher sambutan eksklusif bagi pengguna yang berhasil terverifikasi
        $this->giveWelcomeVoucher($user);

        // Hapus session sementara
        session()->forget('verify_email');

        // Autentikasi dan login pengguna secara otomatis
        Auth::login($user);

        return redirect()->route('pelanggan.home')->with('success', 'Email berhasil diverifikasi! Selamat datang dan nikmati pengalaman berbelanja Anda.');
    }

    // Fungsi untuk mengirim ulang OTP
    public function resendOtp()
    {
        // 1. Pastikan session email masih ada
        if (!session('verify_email')) {
            return redirect()->route('register')->with('error', 'Sesi telah habis, silakan daftar ulang.');
        }

        // 2. Cari user berdasarkan email di session
        $user = User::where('email', session('verify_email'))->first();

        if (!$user) {
            return redirect()->route('register')->with('error', 'Akun tidak ditemukan.');
        }

        // 3. Generate angka OTP yang benar-benar baru
        $newOtpCode = rand(100000, 999999);

        // 4. Update data user di database dengan OTP baru dan reset waktu 10 menit
        $user->update([
            'otp_code' => $newOtpCode,
            'otp_expires_at' => Carbon::now()->addMinutes(10)
        ]);

        // 5. Kirim email OTP yang baru
        Mail::to($user->email)->send(new OtpMail($newOtpCode));

        // 6. Kembalikan user ke halaman form dengan pesan sukses
        return back()->with('success', 'Kode OTP baru telah dikirim ke email Anda! Waktu telah direset.');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $role = Auth::user()->role;
            if ($role === 'super_admin' || $role === 'admin_gudang') {
                return redirect()->intended('/admin/products');
            }

            return redirect()->route('pelanggan.home');
        }

        return back()->with('error', 'Email atau password salah!');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }
    
   public function checkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'Email tidak ditemukan. Pastikan email yang kamu masukkan sudah benar.');
        }

      if ($user->google_id) {
            return back()->with('error', 'Akun ini terdaftar via Google. Silakan masuk menggunakan tombol "Continue with Google".');
        }

        session(['reset_email' => $request->email]);
        return redirect()->route('password.reset.form');
    }
    
    public function showResetPassword()
    {
        if (!session('reset_email')) {
            return redirect()->route('password.forgot')
                            ->with('error', 'Silakan masukkan email terlebih dahulu.');
        }
    
        return view('auth.reset-password');
    }
    
    public function resetPassword(Request $request)
    {
        $email = session('reset_email');
    
        if (!$email) {
            return redirect()->route('password.forgot')
                            ->with('error', 'Sesi habis. Silakan ulangi dari awal.');
        }
    
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ], [
            'password.min'       => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);
    
        $user = User::where('email', $email)->first();
    
        if (!$user) {
            return redirect()->route('password.forgot')
                            ->with('error', 'Akun tidak ditemukan. Silakan ulangi.');
        }
    
        $user->update([
            'password' => Hash::make($request->password),
        ]);
    
        session()->forget('reset_email');
    
        return redirect()->route('login')
                        ->with('success', 'Password berhasil diubah! Silakan masuk dengan password baru.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function giveWelcomeVoucher(User $user): void
    {
        $voucher = Voucher::where('is_active', true)
            ->where('is_welcome', true)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            })
            ->get()
            ->first(function ($v) {
                // Cek sisa kuota
                if (is_null($v->quota)) return true; // unlimited
                return $v->userVouchers()->count() < $v->quota;
            });

        if (!$voucher) return;

        UserVoucher::firstOrCreate([
            'user_id'    => $user->id,
            'voucher_id' => $voucher->id,
        ]);
    }

    // Fungsi untuk membatalkan proses OTP dan kembali daftar ulang
    public function cancelOtp()
    {
        // Hapus session email agar sistem tahu pengguna memulai dari awal
        session()->forget('verify_email');
        
        // Arahkan kembali ke halaman register
        return redirect()->route('register')->with('error', 'Silakan daftar ulang dengan alamat email yang benar.');
    }
}