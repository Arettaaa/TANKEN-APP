<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TANKEN | Admin Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: #F3F4F6; /* Warna abu-abu sangat muda persis di gambar */
            color: #111;
            display: flex;
            min-height: 100vh;
        }

        /* --- SIDEBAR --- */
        .sidebar {
            width: 250px;
            background-color: #111111; /* Warna hitam/charcoal */
            color: #9CA3AF;
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            left: 0;
            top: 0;
            overflow-y: auto;
        }

        .sidebar-brand {
            padding: 30px 20px;
            color: white;
        }
        .sidebar-brand h2 {
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -0.5px;
            margin-bottom: 5px;
        }
        .sidebar-brand p {
            font-size: 12px;
            color: #6B7280;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0 10px;
        }
        .sidebar-menu li {
            margin-bottom: 5px;
        }
        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 12px 15px;
            text-decoration: none;
            color: #9CA3AF;
            font-size: 14px;
            font-weight: 500;
            border-radius: 6px;
            transition: all 0.2s ease;
        }
        .sidebar-menu a i {
            font-size: 20px;
        }
        .sidebar-menu a:hover {
            color: white;
        }
        .sidebar-menu a.active {
            background-color: #2D2D2D; /* Blok abu-abu untuk menu aktif */
            color: white;
            font-weight: 600;
        }
        .sub-menu {
            padding-left: 45px;
            margin-top: -5px;
            margin-bottom: 10px;
        }
        .sub-menu a {
            padding: 8px 10px;
            font-size: 13px;
        }

        /* --- MAIN CONTENT --- */
        .main-wrapper {
            margin-left: 250px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        /* TOPBAR */
        .topbar {
            background-color: #F3F4F6;
            padding: 25px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .topbar-title h1 {
            font-size: 24px;
            font-weight: 800;
            color: #111;
            margin-bottom: 4px;
            letter-spacing: -0.5px;
        }
        .breadcrumb {
            font-size: 13px;
            color: #111;
            font-weight: 600;
        }
        .breadcrumb span {
            color: #666;
            font-weight: 500;
        }
        .topbar-right {
            display: flex;
            align-items: center;
            gap: 25px;
        }
        .notification {
            position: relative;
            cursor: pointer;
            font-size: 22px;
            color: #111;
        }
        .notif-badge {
            position: absolute;
            top: -4px;
            right: -4px;
            background-color: #E7000B;
            color: white;
            font-size: 10px;
            font-weight: bold;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            border: 2px solid #F3F4F6;
        }
        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
        }
        .user-avatar {
            width: 38px;
            height: 38px;
            background-color: #111;
            color: white;
            border-radius: 6px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 18px;
        }
        .user-info h4 {
            font-size: 13px;
            font-weight: 600;
            color: #111;
        }
        .user-info p {
            font-size: 11px;
            color: #666;
        }

        /* CONTENT AREA */
        .content-area {
            padding: 0 40px 40px;
        }

        /* KPI CARDS */
        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 25px;
        }
        .kpi-card {
            background-color: white;
            padding: 25px;
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        .kpi-icon {
            width: 45px;
            height: 45px;
            border-radius: 6px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 22px;
            margin-bottom: 25px;
        }
        /* Icon Colors */
        .icon-green { background-color: #E6F8ED; color: #10B981; }
        .icon-blue { background-color: #EAF4FF; color: #3B82F6; }
        .icon-purple { background-color: #F3E8FF; color: #8B5CF6; }
        .icon-red { background-color: #FEE2E2; color: #EF4444; }
        
        .kpi-card h3 {
            font-size: 32px;
            font-weight: 800;
            margin-bottom: 5px;
            color: #111;
            letter-spacing: -0.5px;
        }
        .kpi-card p {
            font-size: 10px;
            font-weight: 700;
            color: #111;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* CHARTS GRID */
        .charts-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 25px;
        }
        .chart-card {
            background-color: white;
            padding: 25px;
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        .chart-card h3 {
            font-size: 18px;
            font-weight: 800;
            margin-bottom: 20px;
            letter-spacing: -0.5px;
        }

        /* RECENT ACTIVITY */
        .activity-card {
            background-color: white;
            padding: 25px;
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }
        .activity-card h3 {
            font-size: 18px;
            font-weight: 800;
            margin-bottom: 10px;
            letter-spacing: -0.5px;
        }
        .activity-list {
            list-style: none;
        }
        .activity-item {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 15px 0;
            border-bottom: 1px solid #F3F4F6;
        }
        .activity-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }
        .activity-detail h4 {
            font-size: 14px;
            font-weight: 500;
            color: #111;
            margin-bottom: 4px;
        }
        .activity-detail p {
            font-size: 13px;
            color: #666;
        }
        .activity-time {
            font-size: 12px;
            color: #111;
            font-weight: 500;
        }

    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="sidebar-brand">
            <h2>TANKEN</h2>
            <p>Admin Panel</p>
        </div>
<ul class="sidebar-menu">
    <li>
        <a href="{{ url('/admin/dasbor') }}" class="{{ request()->is('admin/dasbor') ? 'active' : '' }}">
            <i class="ph ph-squares-four"></i> Dasbor
        </a>
    </li>
    <li>
        <a href="{{ url('/admin/produk') }}" class="{{ request()->is('admin/produk*') ? 'active' : '' }}">
            <i class="ph ph-package"></i> Produk
        </a>
    </li>
    <div class="sub-menu">
        <a href="{{ url('/admin/ulasan') }}" class="{{ request()->is('admin/ulasan*') ? 'active' : '' }}">
            <i class="ph ph-chat-teardrop"></i> Ulasan
        </a>
    </div>
    <li>
        <a href="{{ url('/admin/stok') }}" class="{{ request()->is('admin/stok*') ? 'active' : '' }}">
            <i class="ph ph-archive-box"></i> Stok
        </a>
    </li>
    <li>
        <a href="{{ url('/admin/pesanan') }}" class="{{ request()->is('admin/pesanan*') ? 'active' : '' }}">
            <i class="ph ph-shopping-cart"></i> Pesanan
        </a>
    </li>
    <li>
        <a href="{{ url('/admin/pembayaran') }}" class="{{ request()->is('admin/pembayaran*') ? 'active' : '' }}">
            <i class="ph ph-credit-card"></i> Pembayaran
        </a>
    </li>
    <li>
        <a href="{{ url('/admin/laporan') }}" class="{{ request()->is('admin/laporan*') ? 'active' : '' }}">
            <i class="ph ph-chart-bar"></i> Laporan
        </a>
    </li>
    <li>
        <a href="{{ url('/admin/pengguna') }}" class="{{ request()->is('admin/pengguna*') ? 'active' : '' }}">
            <i class="ph ph-users"></i> Pengguna
        </a>
    </li>
    <li>
        <a href="{{ url('/admin/voucher') }}" class="{{ request()->is('admin/voucher*') ? 'active' : '' }}">
            <i class="ph ph-ticket"></i> Promo & Voucher
        </a>
    </li>
    <li>
        <a href="{{ url('/admin/kemitraan') }}" class="{{ request()->is('admin/kemitraan*') ? 'active' : '' }}">
            <i class="ph ph-handshake"></i> Kemitraan
        </a>
    </li>
</ul>
    </aside>

    <main class="main-wrapper">
        <header class="topbar">
            <div class="topbar-title">
                <h1>Dashboard Overview</h1>
                <div class="breadcrumb">Home / <span>Dashboard</span></div>
            </div>
            <div class="topbar-right">
                <div class="notification">
                    <i class="ph ph-bell"></i>
                    <span class="notif-badge">2</span>
                </div>
                <div class="user-profile">
                    <div class="user-avatar">
                        <i class="ph ph-user"></i>
                    </div>
                    <div class="user-info">
                        <h4>Admin User</h4>
                        <p>Administrator</p>
                    </div>
                    <i class="ph ph-caret-down" style="color: #666;"></i>
                </div>
            </div>
        </header>

        <div class="content-area">

            <div class="kpi-grid">
                <div class="kpi-card">
                    <div class="kpi-icon icon-green"><i class="ph ph-currency-dollar"></i></div>
                    <h3>$125,430</h3>
                    <p>TOTAL SALES</p>
                </div>
                <div class="kpi-card">
                    <div class="kpi-icon icon-blue"><i class="ph ph-shopping-cart"></i></div>
                    <h3>543</h3>
                    <p>TOTAL ORDERS</p>
                </div>
                <div class="kpi-card">
                    <div class="kpi-icon icon-purple"><i class="ph ph-users"></i></div>
                    <h3>1247</h3>
                    <p>TOTAL USERS</p>
                </div>
                <div class="kpi-card">
                    <div class="kpi-icon icon-red"><i class="ph ph-warning-circle"></i></div>
                    <h3>8</h3>
                    <p>LOW STOCK ITEMS</p>
                </div>
            </div>

            <div class="charts-grid">
                <div class="chart-card">
                    <h3>Sales Overview</h3>
                    <canvas id="salesChart" height="200"></canvas>
                </div>
                <div class="chart-card">
                    <h3>Best Selling Products</h3>
                    <canvas id="productsChart" height="200"></canvas>
                </div>
            </div>

            <div class="activity-card">
                <h3>Recent Activity</h3>
                <ul class="activity-list">
                    <li class="activity-item">
                        <div class="activity-detail">
                            <h4>New order received</h4>
                            <p>ORD-001 - $189.98</p>
                        </div>
                        <div class="activity-time">5 mins ago</div>
                    </li>
                    <li class="activity-item">
                        <div class="activity-detail">
                            <h4>Stock updated</h4>
                            <p>Athletic Flow Joggers - 45 units</p>
                        </div>
                        <div class="activity-time">1 hour ago</div>
                    </li>
                    <li class="activity-item">
                        <div class="activity-detail">
                            <h4>New user registered</h4>
                            <p>jane@example.com</p>
                        </div>
                        <div class="activity-time">2 hours ago</div>
                    </li>
                    <li class="activity-item">
                        <div class="activity-detail">
                            <h4>Payment confirmed</h4>
                            <p>ORD-002 - $149.99</p>
                        </div>
                        <div class="activity-time">3 hours ago</div>
                    </li>
                </ul>
            </div>
        </div>
    </main>

    <script>
        // Data & Config untuk Sales Overview (Line Chart)
        const ctxSales = document.getElementById('salesChart').getContext('2d');
        new Chart(ctxSales, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Sales',
                    data: [12000, 15000, 18000, 15500, 22000, 25000],
                    borderColor: '#000000', /* Warna garis hitam pekat */
                    backgroundColor: '#000000',
                    borderWidth: 2,
                    pointBackgroundColor: '#000000',
                    pointRadius: 4,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 6500, color: '#111', font: { weight: '600', size: 11 } },
                        grid: { borderDash: [4, 4], color: '#E5E7EB' }
                    },
                    x: {
                        ticks: { color: '#111', font: { weight: '600', size: 11 } },
                        grid: { display: false }
                    }
                }
            }
        });

        // Data & Config untuk Best Selling Products (Bar Chart)
        const ctxProducts = document.getElementById('productsChart').getContext('2d');
        new Chart(ctxProducts, {
            type: 'bar',
            data: {
                // Label diperbarui sesuai gambar
                labels: ['Athletic Flow', 'Sport Luxe', 'Cargo Pants', 'Joggers', 'Chinos'],
                datasets: [{
                    label: 'Sold',
                    data: [145, 130, 118, 98, 85],
                    backgroundColor: '#000000', /* Warna batang hitam pekat */
                    borderRadius: 0, /* Menghapus lengkungan agar kotaknya tajam */
                    barPercentage: 0.75
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 40, color: '#111', font: { weight: '600', size: 11 } },
                        grid: { borderDash: [4, 4], color: '#E5E7EB' }
                    },
                    x: {
                        ticks: { color: '#111', font: { weight: '600', size: 11 } },
                        grid: { display: false }
                    }
                }
            }
        });
    </script>
</body>
</html>