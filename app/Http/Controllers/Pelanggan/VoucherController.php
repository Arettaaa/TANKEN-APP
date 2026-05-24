<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
}