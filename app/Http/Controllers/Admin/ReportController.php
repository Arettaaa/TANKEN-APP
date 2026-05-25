<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Pdf;

class ReportController extends Controller
{
    private function getReportData(Request $request)
    {
        $period = $request->period ?? 'this_month';
        $now = now();

        switch ($period) {
            case 'today':      $start = $now->copy()->startOfDay(); $end = $now->copy()->endOfDay(); $label = 'Hari Ini'; break;
            case 'this_week':  $start = $now->copy()->startOfWeek(); $end = $now->copy()->endOfWeek(); $label = 'Minggu Ini'; break;
            case 'last_month': $start = $now->copy()->subMonth()->startOfMonth(); $end = $now->copy()->subMonth()->endOfMonth(); $label = 'Bulan Lalu'; break;
            case 'this_year':  $start = $now->copy()->startOfYear(); $end = $now->copy()->endOfYear(); $label = 'Tahun Ini'; break;
            case 'custom':     $start = Carbon::parse($request->date_from)->startOfDay(); $end = Carbon::parse($request->date_to)->endOfDay(); $label = 'Custom Range'; break;
            default:           $start = $now->copy()->startOfMonth(); $end = $now->copy()->endOfMonth(); $label = 'Bulan Ini'; break;
        }

        // Tentukan Range Tanggal (Previous Period) untuk hitung Growth/Pertumbuhan
        $diffInDays = $start->diffInDays($end);
        $prevStart = $start->copy()->subDays($diffInDays + 1)->startOfDay();
        $prevEnd = $start->copy()->subSecond();

        // 2. Base Query (Hanya yang sudah dibayar/Paid)
        $query = Order::where('payment_status', 'paid')->whereBetween('created_at', [$start, $end]);
        $prevQuery = Order::where('payment_status', 'paid')->whereBetween('created_at', [$prevStart, $prevEnd]);

        if ($request->category) {
            $query->whereHas('items.product', function($q) use ($request) {
                $q->where('category_id', $request->category);
            });
            $prevQuery->whereHas('items.product', function($q) use ($request) {
                $q->where('category_id', $request->category);
            });
        }

        // 3. KPI Stats Current
        $totalOrders = (clone $query)->count();
        $totalRevenue = (clone $query)->sum('total');
        $newCustomers = (clone $query)->distinct('customer_email')->count();
        $avgOrderValue = $totalOrders > 0 ? ($totalRevenue / $totalOrders) : 0;

        // 4. KPI Stats Previous (Untuk Perbandingan)
        $prevTotalOrders = (clone $prevQuery)->count();
        $prevTotalRevenue = (clone $prevQuery)->sum('total');
        $prevNewCustomers = (clone $prevQuery)->distinct('customer_email')->count();
        $prevAvgOrderValue = $prevTotalOrders > 0 ? ($prevTotalRevenue / $prevTotalOrders) : 0;

        // 5. Hitung Growth (%) Asli dari Database
        $revenueGrowth = $prevTotalRevenue > 0 ? (($totalRevenue - $prevTotalRevenue) / $prevTotalRevenue) * 100 : ($totalRevenue > 0 ? 100 : 0);
        $ordersGrowth = $prevTotalOrders > 0 ? (($totalOrders - $prevTotalOrders) / $prevTotalOrders) * 100 : ($totalOrders > 0 ? 100 : 0);
        $customersGrowth = $prevNewCustomers > 0 ? (($newCustomers - $prevNewCustomers) / $prevNewCustomers) * 100 : ($newCustomers > 0 ? 100 : 0);
        $avgGrowth = $prevAvgOrderValue > 0 ? (($avgOrderValue - $prevAvgOrderValue) / $prevAvgOrderValue) * 100 : ($avgOrderValue > 0 ? 100 : 0);

        // 6. Data Chart: Sales Trend (Harian)
        $dailySales = (clone $query)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as revenue'), DB::raw('COUNT(*) as orders'))
            ->groupBy('date')->orderBy('date')->get();

        $trendLabels = $dailySales->pluck('date')->map(fn($d) => Carbon::parse($d)->format('d M'))->toArray();
        $trendData   = $dailySales->pluck('revenue')->toArray();
        $orderData   = $dailySales->pluck('orders')->toArray();
        $orderLabels = $trendLabels;

        // 7. Data Chart: Category Distribution
        $categoryDistribution = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->where('orders.payment_status', 'paid')
            ->whereBetween('orders.created_at', [$start, $end])
            ->select('categories.name', DB::raw('SUM(order_items.subtotal) as total'))
            ->groupBy('categories.id', 'categories.name')->get();

        $categoryLabels = $categoryDistribution->pluck('name')->toArray();
        $categoryData   = $categoryDistribution->pluck('total')->toArray();

        // 8. Top Products
        $topProducts = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.payment_status', 'paid')
            ->whereBetween('orders.created_at', [$start, $end])
            ->select(
                'order_items.product_name as name', 
                DB::raw('SUM(order_items.quantity) as units_sold'), 
                DB::raw('SUM(order_items.subtotal) as revenue')
            )
            ->groupBy('order_items.product_id', 'order_items.product_name')
            ->orderByDesc('units_sold')->take(5)->get()
            ->map(function($p) { 
                $p->avg_price = $p->units_sold > 0 ? ($p->revenue / $p->units_sold) : 0; 
                return $p; 
            });

        // 9. Ringkasan Bulanan (Untuk Tabel di PDF)
        $monthlySummary = Order::where('payment_status', 'paid')
            ->whereYear('created_at', $now->year)
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as total_orders'), DB::raw('SUM(total) as revenue'))
            ->groupBy('month')->orderBy('month')->get()
            ->map(function($m) { 
                $m->month_label = Carbon::create()->month($m->month)->translatedFormat('F'); 
                $m->new_customers = Order::where('payment_status', 'paid')->whereMonth('created_at', $m->month)->distinct('customer_email')->count();
                $m->avg_order_value = $m->total_orders > 0 ? ($m->revenue / $m->total_orders) : 0;
                return $m;
            });

        // MASUKKAN SEMUA VARIABEL KE COMPACT
        return compact(
            'totalOrders', 'totalRevenue', 'newCustomers', 'avgOrderValue',
            'revenueGrowth', 'ordersGrowth', 'customersGrowth', 'avgGrowth', // <--- Ini yang hilang tadi!
            'trendLabels', 'trendData', 'orderLabels', 'orderData', 'categoryLabels', 'categoryData',
            'topProducts', 'monthlySummary', 'label', 'start', 'end'
        );
    }

    public function index(Request $request)
    {
        $data = $this->getReportData($request);
        $data['categories'] = Category::all();
        return view('admin.reports', $data);
    }

    public function exportPdf(Request $request)
    {
        $data = $this->getReportData($request);
        $data['dateFrom'] = $data['start']->format('d M Y');
        $data['dateTo'] = $data['end']->format('d M Y');
        $pdf = Pdf::loadView('admin.report-pdf', $data);
        return $pdf->stream("Tanken_Report_".now()->format('Ymd').".pdf");
    }

    public function exportExcel(Request $request)
    {
        $data = $this->getReportData($request);
        $filename = "Tanken_Top_Products_" . date('Y-m-d') . ".csv";
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['Rank', 'Nama Produk', 'Units Sold', 'Revenue (Rp)', 'Avg Price (Rp)'];
        $callback = function() use($data, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($data['topProducts'] as $i => $row) {
                fputcsv($file, [
                    $i + 1, $row->name, $row->units_sold, $row->revenue, round($row->avg_price)
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}