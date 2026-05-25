<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;

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
        $payments = Order::latest()->get();
        $filename = "Tanken_Payments_" . date('Y-m-d_H-i') . ".csv";

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Payment ID', 'Order ID', 'Customer', 'Amount (Rp)', 'Method', 'Status', 'Date', 'Transaction ID'];

        $callback = function () use ($payments, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($payments as $pay) {
                fputcsv($file, [
                    $pay->id,
                    $pay->order_number ?? '-',
                    $pay->customer_name,
                    $pay->total,
                    strtoupper($pay->payment_method),
                    strtoupper($pay->payment_status),
                    $pay->created_at->format('Y-m-d H:i'),
                    $pay->transaction_id ?? '-'
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
