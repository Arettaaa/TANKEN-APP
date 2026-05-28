<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Voucher;
use App\Models\UserVoucher;
use Illuminate\Support\Facades\Auth;

class VoucherController extends Controller
{
    public function index()
    {
        $userVouchers = auth()->user()
            ->userVouchers()
            ->with('voucher')
            ->latest()
            ->get();

        return view('pelanggan.voucher-saya', compact('userVouchers'));
    }

    public function claim(Request $request)
    {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'Login dulu untuk klaim voucher.'], 401);
        }

        $voucher = Voucher::where('code', strtoupper($request->code))
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->first();

        if (!$voucher) {
            return response()->json(['success' => false, 'message' => 'Voucher tidak valid atau sudah kadaluarsa.']);
        }

        if ($voucher->quota && $voucher->userVouchers()->count() >= $voucher->quota) {
            return response()->json(['success' => false, 'message' => 'Kuota voucher sudah habis.']);
        }

        $already = UserVoucher::where('user_id', auth()->id())
            ->where('voucher_id', $voucher->id)
            ->exists();

        if ($already) {
            return response()->json(['success' => false, 'message' => 'Already claimed']);
        }

        UserVoucher::create([
            'user_id'    => auth()->id(),
            'voucher_id' => $voucher->id,
        ]);

        return response()->json(['success' => true, 'message' => 'Claimed!']);
    }
}