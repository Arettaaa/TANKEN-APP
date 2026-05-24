<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    public function index(Request $request)
    {
        $query = Voucher::query();

        if ($search = $request->search) {
            $query->where('code', 'like', "%{$search}%");
        }

        if ($type = $request->type) {
            $query->where('type', $type);
        }

        if ($status = $request->status) {
            $now = now();
            if ($status === 'active') {
                $query->where('is_active', true)->where(function ($q) use ($now) {
                    $q->whereNull('expires_at')->orWhere('expires_at', '>', $now);
                });
            } elseif ($status === 'disabled') {
                $query->where('is_active', false);
            } elseif ($status === 'expired') {
                $query->where('is_active', true)->whereNotNull('expires_at')->where('expires_at', '<=', $now);
            }
        }

        $vouchers = $query->withCount('userVouchers')->latest()->paginate(15)->withQueryString();

        $now           = now();
        $activePromos  = Voucher::where('is_active', true)->where(function ($q) use ($now) {
            $q->whereNull('expires_at')->orWhere('expires_at', '>', $now);
        })->count();
        $expiredPromos = Voucher::where('is_active', true)->whereNotNull('expires_at')->where('expires_at', '<=', $now)->count();
        $totalUsage    = Voucher::sum('used_count');
        $totalDiscount = Voucher::where('type', 'fixed')->get()->sum(fn($v) => $v->used_count * $v->value);

        return view('admin.promo-voucher', compact('vouchers', 'activePromos', 'expiredPromos', 'totalUsage', 'totalDiscount'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'value'        => preg_replace('/\D/', '', $request->value),
            'min_purchase' => preg_replace('/\D/', '', $request->min_purchase ?? '0'),
        ]);

        $validated = $request->validate([
            'code'         => 'required|string|unique:vouchers,code',
            'type'         => 'required|in:percentage,fixed',
            'value'        => 'required|numeric|min:1',
            'min_purchase' => 'nullable|numeric|min:0',
            'quota'        => 'nullable|integer|min:1',
            'expires_at'   => 'nullable|date',
            'description'  => 'nullable|string',
            'is_active'    => 'required|boolean',
            'is_welcome'   => 'nullable|boolean',
        ]);

        $validated['min_purchase'] = $validated['min_purchase'] ?? 0;
        $validated['is_welcome']   = $request->boolean('is_welcome');

        Voucher::create($validated);
        return response()->json(['success' => true]);
    }

    public function update(Request $request, Voucher $promo)
    {
        $request->merge([
            'value'        => preg_replace('/\D/', '', $request->value),
            'min_purchase' => preg_replace('/\D/', '', $request->min_purchase ?? '0'),
        ]);

        $validated = $request->validate([
            'code'         => 'required|string|unique:vouchers,code,' . $promo->id,
            'type'         => 'required|in:percentage,fixed',
            'value'        => 'required|numeric|min:1',
            'min_purchase' => 'nullable|numeric|min:0',
            'quota'        => 'nullable|integer|min:1',
            'expires_at'   => 'nullable|date',
            'description'  => 'nullable|string',
            'is_active'    => 'required|boolean',
            'is_welcome'   => 'nullable|boolean',
        ]);

        $validated['min_purchase'] = $validated['min_purchase'] ?? 0;
        $validated['is_welcome']   = $request->boolean('is_welcome');

        $promo->update($validated);
        return response()->json(['success' => true]);
    }

    public function destroy(Voucher $promo)
    {
        $promo->delete();
        return response()->json(['success' => true]);
    }

    public function toggleStatus(Voucher $promo)
    {
        $promo->update(['is_active' => !$promo->is_active]);
        return response()->json(['success' => true, 'is_active' => $promo->is_active]);
    }
}