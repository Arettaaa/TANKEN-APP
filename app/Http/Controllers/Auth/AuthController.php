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

        // Kirim voucher welcome otomatis
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

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    // ─── PRIVATE ──────────────────────────────────────
    /**
     * Cari voucher dengan flag is_welcome = true yang masih aktif
     * dan masih ada sisa kuota, lalu assign ke user baru.
     */
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

        // Hindari duplikat jika user entah bagaimana sudah punya
        UserVoucher::firstOrCreate([
            'user_id'    => $user->id,
            'voucher_id' => $voucher->id,
        ]);
    }
}