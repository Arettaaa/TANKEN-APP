<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with('order')->latest();

        // Fitur Search & Filter (Akan diproses dari custom dropdown UI)
        if ($search = $request->search) {
            $query->where('payment_id', 'like', "%{$search}%")
                  ->orWhere('transaction_id', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%");
        }
        if ($status = $request->status) {
            $query->where('status', $status);
        }
        if ($method = $request->method) {
            $query->where('method', $method);
        }

        $payments = $query->paginate(15)->withQueryString();

        // Data untuk Stat Cards
        $totalRevenue        = Payment::where('status', 'completed')->sum('amount');
        $completedCount      = Payment::where('status', 'completed')->count();
        $pendingCount        = Payment::where('status', 'pending')->count();
        $failedRefundedCount = Payment::whereIn('status', ['failed', 'refunded'])->count();

        // Dummy Data untuk Chart (6 Bulan Terakhir) - Nantinya bisa pakai query GROUP BY
        $chartLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'];
        $chartData   = [12500000, 15000000, 14200000, 18500000, 17000000, 21000000];

        return view('admin.payment', compact(
            'payments', 'totalRevenue', 'completedCount', 
            'pendingCount', 'failedRefundedCount', 'chartLabels', 'chartData'
        ));
    }

    public function export(Request $request)
    {
        $payments = Payment::latest()->get();
        $filename = "Tanken_Payments_" . date('Y-m-d_H-i') . ".csv";

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Payment ID', 'Order ID', 'Customer', 'Amount (Rp)', 'Method', 'Status', 'Date', 'Transaction ID'];

        $callback = function() use($payments, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($payments as $pay) {
                fputcsv($file, [
                    $pay->payment_id,
                    $pay->order->order_number ?? '-',
                    $pay->customer_name,
                    $pay->amount,
                    strtoupper($pay->method),
                    strtoupper($pay->status),
                    $pay->created_at->format('Y-m-d H:i'),
                    $pay->transaction_id ?? '-'
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}