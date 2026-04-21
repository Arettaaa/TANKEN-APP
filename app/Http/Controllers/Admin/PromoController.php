<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::latest()->paginate(15);
        return view('admin.promo.index', compact('vouchers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code'  => 'required|string|unique:vouchers,code',
            'type'  => 'required|in:percentage,fixed',
            'value' => 'required|integer|min:1',
        ]);

        Voucher::create($validated);
        return back()->with('success', 'Voucher berhasil dibuat.');
    }

    public function destroy(Voucher $voucher)
    {
        $voucher->delete();
        return back()->with('success', 'Voucher berhasil dihapus.');
    }
}