<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sales Report – TANKEN</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #333; font-size: 12px; margin: 0; padding: 40px; }
        .header { border-bottom: 2px solid #000; padding-bottom: 20px; margin-bottom: 30px; }
        .brand { font-size: 24px; font-weight: bold; letter-spacing: 2px; }
        .report-info { text-align: right; float: right; margin-top: -35px; }
        .meta-table { width: 100%; margin-bottom: 30px; border-collapse: collapse; }
        .meta-table td { padding: 8px 0; border-bottom: 1px solid #eee; }
        .section-title { font-size: 14px; font-weight: bold; text-transform: uppercase; margin: 30px 0 15px; border-left: 4px solid #000; padding-left: 10px; }
        .kpi-grid { width: 100%; margin-bottom: 20px; }
        .kpi-box { width: 23%; padding: 15px; background: #f9f9f9; border: 1px solid #eee; display: inline-block; text-align: center; }
        .kpi-val { font-size: 16px; font-weight: bold; margin-top: 5px; }
        .data-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .data-table th { background: #000; color: #fff; padding: 10px; text-align: left; font-size: 10px; }
        .data-table td { padding: 10px; border-bottom: 1px solid #eee; }
        .footer { position: fixed; bottom: 30px; left: 40px; right: 40px; border-top: 1px solid #eee; padding-top: 10px; font-size: 10px; color: #999; }
    </style>
</head>
<body>
    <div class="header">
        <div class="brand">TANKEN</div>
        <div class="report-info">
            <strong>SALES REPORT</strong><br>
            Periode: {{ $label }}<br>
            Dicetak: {{ now()->format('d M Y H:i') }}
        </div>
    </div>

    <table class="meta-table">
        <tr>
            <td width="20%"><strong>Dari Tanggal</strong></td><td width="30%">{{ $dateFrom }}</td>
            <td width="20%"><strong>Kategori</strong></td><td width="30%">{{ $categoryLabel ?? 'Semua' }}</td>
        </tr>
        <tr>
            <td><strong>Sampai Tanggal</strong></td><td>{{ $dateTo }}</td>
            <td><strong>Admin</strong></td><td>{{ auth()->user()->name ?? 'Administrator' }}</td>
        </tr>
    </table>

    <div class="section-title">Ringkasan Performa</div>
    <div class="kpi-grid">
        <div class="kpi-box">Revenue<div class="kpi-val">Rp{{ number_format($totalRevenue, 0, ',', '.') }}</div></div>
        <div class="kpi-box">Total Orders<div class="kpi-val">{{ number_format($totalOrders) }}</div></div>
        <div class="kpi-box">Customers<div class="kpi-val">{{ number_format($newCustomers) }}</div></div>
        <div class="kpi-box">Avg. Order<div class="kpi-val">Rp{{ number_format($avgOrderValue, 0, ',', '.') }}</div></div>
    </div>

    <div class="section-title">Top Selling Products</div>
    <table class="data-table">
        <thead>
            <tr><th>RANK</th><th>PRODUK</th><th>TERJUAL</th><th>TOTAL REVENUE</th></tr>
        </thead>
        <tbody>
            @foreach($topProducts as $i => $p)
            <tr><td>{{ $i+1 }}</td><td><strong>{{ $p->name }}</strong></td><td>{{ $p->units_sold }} unit</td><td>Rp{{ number_format($p->revenue, 0, ',', '.') }}</td></tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">Ringkasan Bulanan ({{ now()->year }})</div>
    <table class="data-table">
        <thead>
            <tr><th>BULAN</th><th>ORDERS</th><th>REVENUE</th><th>NEW CUSTOMERS</th></tr>
        </thead>
        <tbody>
            @foreach($monthlySummary as $m)
            <tr><td>{{ $m->month_label }}</td><td>{{ $m->total_orders }}</td><td>Rp{{ number_format($m->revenue, 0, ',', '.') }}</td><td>{{ $m->new_customers }}</td></tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">TANKEN Admin Panel • Laporan Resmi Sistem • Halaman 1 dari 1</div>
</body>
</html>