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
        $totalSales = Order::where('payment_status', 'paid')->sum('total');
        $totalOrders = Order::count();
        $totalUsers  = User::where('role', 'customer')->count();
        $lowStockItems = Product::with('stocks')
            ->get()
            ->filter(function($p) { 
                return $p->stocks->sum('quantity') <= 20; 
            })
            ->count();

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

        $bestSelling = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select('products.name as product_name', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        $recentActivity = ActivityLog::with('user')
            ->latest()
            ->limit(6)
            ->get();

        return view('admin.dashboard', compact(
            'totalSales',
            'totalOrders',
            'totalUsers',
            'lowStockItems',
            'salesChart',
            'bestSelling',
            'recentActivity'
        ));
    }
}
