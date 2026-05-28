<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('email', $googleUser->email)->first();

            if ($user) {
                if (!$user->google_id) {
                    $user->update(['google_id' => $googleUser->id]);
                }
            } else {
                $user = User::create([
                    'name'      => $googleUser->name,
                    'email'     => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'password'  => bcrypt(Str::random(24)),
                    'role'      => 'customer',
                ]);
            }

            Auth::login($user, true); 

            request()->session()->regenerate(); 

            if ($user->role === 'super_admin' || $user->role === 'admin_gudang') {
                return redirect('/admin/products');
            }

            return redirect()->route('pelanggan.home');
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Gagal login: ' . $e->getMessage());
        }
    }
}
