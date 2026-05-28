<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Helpers\ExportHelper; 
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

        $diffInDays = $start->diffInDays($end);
        $prevStart = $start->copy()->subDays($diffInDays + 1)->startOfDay();
        $prevEnd = $start->copy()->subSecond();

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

        $totalOrders = (clone $query)->count();
        $totalRevenue = (clone $query)->sum('total');
        $newCustomers = (clone $query)->distinct('customer_email')->count();
        $avgOrderValue = $totalOrders > 0 ? ($totalRevenue / $totalOrders) : 0;

        $prevTotalOrders = (clone $prevQuery)->count();
        $prevTotalRevenue = (clone $prevQuery)->sum('total');
        $prevNewCustomers = (clone $prevQuery)->distinct('customer_email')->count();
        $prevAvgOrderValue = $prevTotalOrders > 0 ? ($prevTotalRevenue / $prevTotalOrders) : 0;

        $revenueGrowth = $prevTotalRevenue > 0 ? (($totalRevenue - $prevTotalRevenue) / $prevTotalRevenue) * 100 : ($totalRevenue > 0 ? 100 : 0);
        $ordersGrowth = $prevTotalOrders > 0 ? (($totalOrders - $prevTotalOrders) / $prevTotalOrders) * 100 : ($totalOrders > 0 ? 100 : 0);
        $customersGrowth = $prevNewCustomers > 0 ? (($newCustomers - $prevNewCustomers) / $prevNewCustomers) * 100 : ($newCustomers > 0 ? 100 : 0);
        $avgGrowth = $prevAvgOrderValue > 0 ? (($avgOrderValue - $prevAvgOrderValue) / $prevAvgOrderValue) * 100 : ($avgOrderValue > 0 ? 100 : 0);

        $dailySales = (clone $query)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as revenue'), DB::raw('COUNT(*) as orders'))
            ->groupBy('date')->orderBy('date')->get();

        $trendLabels = $dailySales->pluck('date')->map(fn($d) => Carbon::parse($d)->format('d M'))->toArray();
        $trendData   = $dailySales->pluck('revenue')->toArray();
        $orderData   = $dailySales->pluck('orders')->toArray();
        $orderLabels = $trendLabels;

        $categoryDistribution = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->where('orders.payment_status', 'paid')
            ->whereBetween('orders.created_at', [$start, $end])
            ->select('categories.name', DB::raw('SUM(order_items.subtotal) as total'))
            ->groupBy('categories.id', 'categories.name')->get();

        $categoryLabels = $categoryDistribution->pluck('name')->toArray();
        $categoryData   = $categoryDistribution->pluck('total')->toArray();

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
        return $pdf->download("Tanken_Report_".now()->format('Ymd').".pdf");
    }

   public function exportExcel(Request $request)
    {
        $data = $this->getReportData($request);

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

    
        $sheet1 = $spreadsheet->getActiveSheet();
        $sheet1->setTitle('Ringkasan');

        $sheet1->mergeCells('A1:B1');
        $sheet1->setCellValue('A1', 'LAPORAN PENJUALAN - ' . strtoupper($data['label']));
        $sheet1->getStyle('A1:B1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14, 'color' => ['argb' => 'FFFFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF1F2937']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet1->getRowDimension(1)->setRowHeight(32);

        $sheet1->mergeCells('A2:B2');
        $sheet1->setCellValue('A2', 'Periode: ' . $data['start']->format('d M Y') . ' s/d ' . $data['end']->format('d M Y'));
        $sheet1->getStyle('A2:B2')->applyFromArray([
            'font' => ['italic' => true, 'size' => 9, 'color' => ['argb' => 'FF6B7280']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFF9FAFB']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
        ]);

        $sheet1->getRowDimension(3)->setRowHeight(8);

        $sheet1->setCellValue('A4', 'Metrik');
        $sheet1->setCellValue('B4', 'Nilai');
        $sheet1->getStyle('A4:B4')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF111827']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['argb' => 'FFD1D5DB']]],
        ]);

        $kpis = [
            ['Total Revenue',    'Rp ' . number_format($data['totalRevenue'], 0, ',', '.')],
            ['Total Orders',     $data['totalOrders'] . ' orders'],
            ['Total Customers',  $data['newCustomers'] . ' customers'],
            ['Avg. Order Value', 'Rp ' . number_format($data['avgOrderValue'], 0, ',', '.')],
        ];

        foreach ($kpis as $i => $kpi) {
            $row = 5 + $i;
            $bg  = $i % 2 === 0 ? 'FFFFFFFF' : 'FFF3F4F6';
            $sheet1->setCellValue("A{$row}", $kpi[0]);
            $sheet1->setCellValue("B{$row}", $kpi[1]);
            $sheet1->getStyle("A{$row}:B{$row}")->applyFromArray([
                'fill'    => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => $bg]],
                'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['argb' => 'FFE5E7EB']]],
                'font'    => ['size' => 10],
            ]);
            $sheet1->getRowDimension($row)->setRowHeight(18);
        }

        $sheet1->getColumnDimension('A')->setWidth(25);
        $sheet1->getColumnDimension('B')->setWidth(25);

        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle('Top Produk');

        $sheet2->mergeCells('A1:E1');
        $sheet2->setCellValue('A1', 'TOP SELLING PRODUCTS - ' . strtoupper($data['label']));
        $sheet2->getStyle('A1:E1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14, 'color' => ['argb' => 'FFFFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF1F2937']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet2->getRowDimension(1)->setRowHeight(32);

        $sheet2->getRowDimension(2)->setRowHeight(8);

        $cols2 = ['Rank', 'Nama Produk', 'Units Sold', 'Revenue (Rp)', 'Avg Price (Rp)'];
        foreach ($cols2 as $i => $col) {
            $letter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i + 1);
            $sheet2->setCellValue("{$letter}3", $col);
        }
        $sheet2->getStyle('A3:E3')->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
            'fill'      => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF111827']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            'borders'   => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['argb' => 'FFD1D5DB']]],
        ]);
        $sheet2->getRowDimension(3)->setRowHeight(20);

        foreach ($data['topProducts'] as $i => $row) {
            $excelRow = 4 + $i;
            $bg = $i % 2 === 0 ? 'FFFFFFFF' : 'FFF3F4F6';
            $sheet2->setCellValue("A{$excelRow}", $i + 1);
            $sheet2->setCellValue("B{$excelRow}", $row->name);
            $sheet2->setCellValue("C{$excelRow}", $row->units_sold);
            $sheet2->setCellValue("D{$excelRow}", 'Rp ' . number_format($row->revenue, 0, ',', '.'));
            $sheet2->setCellValue("E{$excelRow}", 'Rp ' . number_format(round($row->avg_price), 0, ',', '.'));
            $sheet2->getStyle("A{$excelRow}:E{$excelRow}")->applyFromArray([
                'fill'    => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => $bg]],
                'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['argb' => 'FFE5E7EB']]],
                'font'    => ['size' => 9],
            ]);
            $sheet2->getRowDimension($excelRow)->setRowHeight(16);
        }

        foreach (['A' => 8, 'B' => 50, 'C' => 15, 'D' => 20, 'E' => 20] as $col => $width) {
            $sheet2->getColumnDimension($col)->setWidth($width);
        }
        $sheet2->freezePane('A4');

        $spreadsheet->setActiveSheetIndex(0);
        $writer   = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'Tanken_Reports_' . date('Y-m-d') . '.xlsx';

        return response()->stream(
            fn() => $writer->save('php://output'),
            200,
            [
                'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
                'Cache-Control'       => 'max-age=0',
                'Pragma'              => 'no-cache',
            ]
        );
    }
}