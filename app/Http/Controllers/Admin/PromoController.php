<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;
use App\Helpers\ExportHelper;

class PromoController extends Controller
{
    public function index(Request $request)
    {
        $query = Voucher::query();

        // SEARCH
        if ($request->filled('search')) {
            $search = strtolower($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                ->orWhere('type', 'like', "%{$search}%")
                ->orWhere('value', 'like', "%{$search}%")
                ->orWhere('min_purchase', 'like', "%{$search}%")
                ->orWhere('quota', 'like', "%{$search}%")
                ->orWhere('used_count', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
                ->orWhereDate('expires_at', $search)
                ->orWhere('expires_at', 'like', "%{$search}%");

                if ($search === 'active') {
                    $q->orWhere(function ($qq) {
                        $qq->where('is_active', true)
                        ->where(function ($x) {
                            $x->whereNull('expires_at')
                            ->orWhere('expires_at', '>', now());
                        });
                    });
                }

                if ($search === 'disabled') {
                    $q->orWhere('is_active', false);
                }

                if ($search === 'expired') {
                    $q->orWhere(function ($qq) {
                        $qq->where('is_active', true)
                        ->whereNotNull('expires_at')
                        ->where('expires_at', '<=', now());
                    });
                }
            });
        }

        // FILTER TYPE
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // FILTER STATUS
        if ($request->filled('status')) {
            $now = now();
            if ($request->status === 'active') {
                $query->where('is_active', true)
                    ->where(function ($q) use ($now) {
                        $q->whereNull('expires_at')
                        ->orWhere('expires_at', '>', $now);
                    });
            } elseif ($request->status === 'disabled') {
                $query->where('is_active', false);
            } elseif ($request->status === 'expired') {
                $query->where('is_active', true)
                    ->whereNotNull('expires_at')
                    ->where('expires_at', '<=', $now);
            }
        }

        $vouchers = $query
            ->withCount('userVouchers')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $now = now();

        $activePromos = Voucher::where('is_active', true)
            ->where(function ($q) use ($now) {
                $q->whereNull('expires_at')
                ->orWhere('expires_at', '>', $now);
            })->count();

        $expiredPromos = Voucher::where('is_active', true)
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', $now)
            ->count();

        $totalUsage = Voucher::sum('used_count');

        // PERUBAHAN DI SINI: Hanya kalikan value untuk voucher tipe 'fixed'
        $totalDiscount = Voucher::where('type', 'fixed')->sum(
            \DB::raw('used_count * value')
        );

        return view('admin.promo-voucher', compact(
            'vouchers',
            'activePromos',
            'expiredPromos',
            'totalUsage',
            'totalDiscount'
        ));
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

    public function exportExcel(Request $request)
    {
        $vouchers = \App\Models\Voucher::withCount('userVouchers')->get();
        $now = now();
        $columns = ['ID', 'Kode', 'Tipe', 'Nilai', 'Min. Pembelian', 'Kuota', 'Diklaim', 'Sisa', 'Kadaluarsa', 'Welcome', 'Status'];
    
        $rows = $vouchers->map(function ($v) use ($now) {
            $isExpired = $v->expires_at && $now->gt($v->expires_at);
            $claimed   = $v->user_vouchers_count;
            $sisa      = $v->quota ? ($v->quota - $claimed) : '∞';
    
            if (!$v->is_active) $status = 'Disabled';
            elseif ($isExpired)  $status = 'Expired';
            else                 $status = 'Active';
    
            return [
                $v->id,
                $v->code,
                ucfirst($v->type),
                $v->type === 'fixed' ? 'Rp ' . number_format($v->value, 0, ',', '.') : $v->value . '%',
                'Rp ' . number_format($v->min_purchase, 0, ',', '.'),
                $v->quota ?? '∞',
                $claimed,
                $sisa,
                $v->expires_at ? $v->expires_at->format('Y-m-d') : 'Tanpa Batas',
                $v->is_welcome ? 'Ya' : 'Tidak',
                $status,
            ];
        });
    
       return ExportHelper::excel('Tanken_Promo_Vouchers', 'Laporan Promo & Voucher', $columns, $rows);
    }
}