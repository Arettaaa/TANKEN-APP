<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Helpers\ExportHelper;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::latest();

       if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_email', 'like', "%{$search}%")
                    ->orWhere('total', 'like', "%{$search}%")
                    ->orWhere('total_payment', 'like', "%{$search}%")
                    ->orWhere('payment_method', 'like', "%{$search}%")
                    ->orWhere('payment_status', 'like', "%{$search}%")
                    ->orWhereDate('created_at', $search)
                    ->orWhere('created_at', 'like', "%{$search}%");
            });
        }
        if ($status = $request->status) {
            $query->where('payment_status', $status);
        }
        if ($method = $request->method) {
            $query->where('payment_method', $method);
        }

        $payments = $query->paginate(15)->withQueryString();

        $totalRevenue        = Order::where('payment_status', 'paid')->sum('total');
        $completedCount      = Order::where('payment_status', 'paid')->count();
        $pendingCount        = Order::whereIn('payment_status', ['unpaid', 'waiting_confirmation'])->count();
        $failedRefundedCount = Order::whereIn('payment_status', ['failed', 'refunded'])->count();

        $revenueChart = Order::where('payment_status', 'paid')
            ->where('created_at', '>=', now()->subMonths(6))
            ->select(
                \DB::raw("DATE_FORMAT(created_at, '%b') as month"),
                \DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month_key"),
                \DB::raw('SUM(total) as total')
            )
            ->groupBy('month_key', 'month')
            ->orderBy('month_key')
            ->get();

        $chartLabels = $revenueChart->isNotEmpty()
            ? $revenueChart->pluck('month')->toArray()
            : ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'];

        $chartData = $revenueChart->isNotEmpty()
            ? $revenueChart->pluck('total')->map(function ($v) {
                return (int)$v;
            })->toArray()
            : [0, 0, 0, 0, 0, 0];

        return view('admin.payment', compact(
            'payments',
            'totalRevenue',
            'completedCount',
            'pendingCount',
            'failedRefundedCount',
            'chartLabels',
            'chartData'
        ));
    }

    public function export(Request $request)
    {
        $payments = \App\Models\Order::whereNotNull('payment_method')->get();
    
        $columns = ['No. Order', 'Customer', 'Email', 'Metode', 'Bank/Via', 'Total Transfer', 'Status', 'Tanggal Bayar'];
    
        $rows = $payments->map(fn($p) => [
            $p->order_number,
            $p->customer_name,
            $p->customer_email,
            str_replace('_', ' ', $p->payment_method),
            strtoupper($p->payment_reference ?? '-'),
            $p->total_payment > 0 ? $p->total_payment : $p->total,
            $p->payment_status,
            $p->paid_at ? \Carbon\Carbon::parse($p->paid_at)->format('Y-m-d H:i') : '-',
        ]);
    
    return ExportHelper::excel('Tanken_Payments', 'Laporan Pembayaran', $columns, $rows);
    }
}
