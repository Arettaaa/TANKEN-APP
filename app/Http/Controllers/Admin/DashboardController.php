<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik Utama
        $totalSales = Order::where('payment_status', 'paid')->sum('total');
        $totalOrders = Order::count();
        $totalUsers  = User::where('role', 'customer')->count();
        $lowStockItems = Product::with('stocks')
            ->get()
            ->filter(fn($p) => $p->total_stock <= 10)
            ->count();

        // Grafik Penjualan (6 bulan terakhir)
        $salesChart = Order::where('payment_status', 'paid')
            ->where('created_at', '>=', now()->subMonths(6))
            ->select(
                DB::raw("DATE_FORMAT(created_at, '%b') as month"),
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month_key"),
                DB::raw('SUM(total) as total')
            )
            ->groupBy('month_key', 'month')
            ->orderBy('month_key')
            ->get();

        // Produk Terlaris
        $bestSelling = DB::table('order_items')
            ->select('product_name', DB::raw('SUM(quantity) as total_sold'))
            ->groupBy('product_name')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        // Aktivitas Terbaru
        $recentActivity = ActivityLog::with('user')
            ->latest()
            ->limit(6)
            ->get();

        // Mengarahkan ke resources/views/admin/dashboard.blade.php
        return view('admin.dashboard', compact(
            'totalSales', 'totalOrders', 'totalUsers', 'lowStockItems',
            'salesChart', 'bestSelling', 'recentActivity'
        ));
    }
}