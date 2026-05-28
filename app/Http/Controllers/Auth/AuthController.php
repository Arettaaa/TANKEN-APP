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

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'customer',
        ]);

        $this->giveWelcomeVoucher($user);

        return redirect()->route('login')->with('success', 'Registrasi berhasil! Cek voucher sambutan di halaman Voucher Saya.');
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
}