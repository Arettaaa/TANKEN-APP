<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->from ?? now()->startOfMonth()->toDateString();
        $to   = $request->to   ?? now()->toDateString();

        $summary = Order::where('payment_status', 'paid')
            ->whereBetween('created_at', [$from, $to . ' 23:59:59'])
            ->selectRaw('COUNT(*) as total_orders, SUM(total) as total_revenue, AVG(total) as avg_order')
            ->first();

        $daily = Order::where('payment_status', 'paid')
            ->whereBetween('created_at', [$from, $to . ' 23:59:59'])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as revenue'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.reports.index', compact('summary', 'daily', 'from', 'to'));
    }
}