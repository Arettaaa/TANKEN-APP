<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // PROSES DAFTAR (Register)
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return back()->with('error', 'Gagal mendaftar. Pastikan email belum digunakan dan password minimal 8 karakter.');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer',
        ]);

        // HAPUS Auth::login($user); DI SINI

        // Lempar ke login dengan pesan sukses
        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login.');
    }

    // PROSES MASUK (Login)
    public function login(Request $request)
    {
        // 1. Ambil email dan password
        $credentials = $request->only('email', 'password');

        // 2. Coba cocokkan dengan database
        if (Auth::attempt($credentials)) {
            // Kalau cocok, buat sesi baru biar aman
            $request->session()->regenerate();

            // 3. Cek Jabatannya (Role)
            $role = Auth::user()->role;
            if ($role === 'super_admin' || $role === 'admin_gudang') {
                return redirect()->intended('/admin/products'); // Lempar ke admin
            }

            // Kalau customer, lempar ke beranda
            return redirect()->route('pelanggan.home');
        }

        // Kalau password/email salah, balikin ke halaman login bawa pesan error
        return back()->with('error', 'Email atau password salah!');
    }

    // PROSES KELUAR (Logout)
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
