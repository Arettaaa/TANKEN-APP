<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dasbor Admin | TANKEN</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        /* CSS RESET & VARIABLES */
        :root {
            --sidebar-bg: #141414;
            --main-bg: #F4F4F5;
            --card-bg: #FFFFFF;
            --border-color: #E4E4E7;
            --text-main: #111111;
            --text-muted: #71717A;
            --text-light: #A1A1AA;
            --font-family: 'Inter', sans-serif;

            /* Icon Colors */
            --icon-green-bg: #dcfce7;
            --icon-green-text: #16a34a;
            --icon-blue-bg: #dbeafe;
            --icon-blue-text: #2563eb;
            --icon-purple-bg: #f3e8ff;
            --icon-purple-text: #9333ea;
            --icon-red-bg: #fee2e2;
            --icon-red-text: #dc2626;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--font-family);
            background-color: var(--main-bg);
            color: var(--text-main);
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        ul {
            list-style: none;
        }

        /* --- SIDEBAR --- */
        .sidebar {
            width: 260px;
            background-color: var(--sidebar-bg);
            color: #FFFFFF;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            border-right: 1px solid rgba(255, 255, 255, 0.05);
        }

        .sidebar-header {
            padding: 24px 20px;
            margin-bottom: 8px;
        }

        .sidebar-brand {
            font-size: 24px;
            font-weight: 900;
            letter-spacing: -1px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .sidebar-subtitle {
            font-size: 12px;
            color: var(--text-light);
            font-weight: 500;
            margin-top: 4px;
            letter-spacing: 0.5px;
        }

        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            padding: 0 12px 24px 12px;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .sidebar-nav::-webkit-scrollbar {
            display: none;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            color: var(--text-light);
            border-radius: 8px;
            transition: all 0.2s ease;
            font-size: 14px;
            font-weight: 500;
        }

        .nav-item i {
            font-size: 20px;
            width: 24px;
            text-align: center;
        }

        .nav-item:hover {
            color: #FFFFFF;
            background-color: rgba(255, 255, 255, 0.05);
        }

        .nav-item.active {
            color: #FFFFFF;
            background-color: rgba(255, 255, 255, 0.1);
            font-weight: 600;
        }

        .nav-sub-item {
            padding-left: 48px;
            font-size: 13px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            color: var(--text-light);
            border-radius: 8px;
            transition: all 0.2s ease;
            font-size: 14px;
            font-weight: 500;
        }

        .nav-item i {
            font-size: 20px;
            width: 24px;
            text-align: center;
        }

        .nav-item:hover {
            color: #FFFFFF;
            background-color: rgba(255, 255, 255, 0.05);
        }

        .nav-item.active {
            color: #FFFFFF;
            background-color: rgba(255, 255, 255, 0.1);
            font-weight: 600;
        }

        .nav-sub-item {
            padding-left: 48px;
            font-size: 13px;
            margin-top: -4px;
        }

        .nav-sub-item i {
            font-size: 18px;
            /* Ikon sedikit lebih kecil */
        }

        /* Jarak antar grup menu tanpa menggunakan teks judul */
        .mt-group {
            margin-top: 24px;
        }

        /* --- MAIN LAYOUT --- */
        .main-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        /* --- HEADER --- */
        .top-header {
            height: 80px;
            background-color: var(--card-bg);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            flex-shrink: 0;
        }

        .header-title h2 {
            font-size: 24px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .header-breadcrumb {
            font-size: 14px;
            color: var(--text-muted);
            margin-top: 4px;
            font-weight: 500;
        }

        .header-breadcrumb span {
            color: var(--text-main);
            font-weight: 600;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 24px;
        }

        .btn-notification {
            position: relative;
            background: none;
            border: none;
            cursor: pointer;
            color: var(--text-muted);
            transition: color 0.2s;
        }

        .btn-notification i {
            font-size: 24px;
        }

        .btn-notification:hover {
            color: var(--text-main);
        }

        .badge {
            position: absolute;
            top: -4px;
            right: -4px;
            width: 18px;
            height: 18px;
            background-color: #ef4444;
            color: white;
            font-size: 10px;
            font-weight: bold;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid var(--card-bg);
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background-color: var(--sidebar-bg);
            color: white;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .user-info p:first-child {
            font-size: 14px;
            font-weight: 700;
        }

        .user-info p:last-child {
            font-size: 12px;
            color: var(--text-muted);
        }

        /* --- DASHBOARD CONTENT --- */
        .content-body {
            flex: 1;
            overflow-y: auto;
            padding: 32px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 24px;
            margin-bottom: 24px;
        }

        .stat-card {
            background-color: var(--card-bg);
            padding: 24px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 16px;
        }

        .stat-icon.green {
            background-color: var(--icon-green-bg);
            color: var(--icon-green-text);
        }

        .stat-icon.blue {
            background-color: var(--icon-blue-bg);
            color: var(--icon-blue-text);
        }

        .stat-icon.purple {
            background-color: var(--icon-purple-bg);
            color: var(--icon-purple-text);
        }

        .stat-icon.red {
            background-color: var(--icon-red-bg);
            color: var(--icon-red-text);
        }

        .stat-value {
            font-size: 30px;
            font-weight: 900;
            letter-spacing: -1px;
            margin-bottom: 4px;
        }

        .stat-label {
            font-size: 11px;
            font-weight: 700;
            color: var(--text-light);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .charts-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
            margin-bottom: 24px;
        }

        .chart-card {
            background-color: var(--card-bg);
            padding: 24px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
        }

        .chart-card h3 {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .chart-container {
            position: relative;
            height: 260px;
            width: 100%;
        }

        .activity-card {
            background-color: var(--card-bg);
            border-radius: 8px;
            border: 1px solid var(--border-color);
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
        }

        .activity-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--border-color);
        }

        .activity-header h3 {
            font-size: 18px;
            font-weight: 700;
        }

        .activity-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 24px;
            border-bottom: 1px solid var(--border-color);
            transition: background-color 0.2s;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-item:hover {
            background-color: #FAFAFA;
        }

        .activity-title {
            font-size: 15px;
            font-weight: 600;
            color: var(--text-main);
        }

        .activity-desc {
            font-size: 14px;
            color: var(--text-muted);
            margin-top: 4px;
        }

        .activity-time {
            font-size: 13px;
            color: var(--text-light);
            font-weight: 500;
        }

        @media (max-width: 1024px) {

            .stats-grid,
            .charts-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                display: none;
            }

            .stats-grid,
            .charts-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

    <aside class="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-brand">
                TANKEN
            </div>
            <div class="sidebar-subtitle">Panel Admin</div>
        </div>

        <nav class="sidebar-nav">
            <a href="{{ route('admin.dasbor') }}" class="nav-item active">
                <i class="ph ph-squares-four"></i> Dasbor
            </a>

            <a href="{{ route('admin.produk') }}" class="nav-item">
                <i class="ph ph-package"></i> Produk
            </a>

            <a href="{{ route('admin.ulasan') }}" class="nav-item nav-sub-item">
                <i class="ph ph-chat-teardrop"></i> Ulasan
            </a>

            <a href="{{ route('admin.stok') }}" class="nav-item">
                <i class="ph ph-archive-box"></i> Stok
            </a>

            <a href="{{ route('admin.pesanan') }}" class="nav-item">
                <i class="ph ph-shopping-cart-simple"></i> Pesanan
            </a>

            <a href="{{ route('admin.pembayaran') }}" class="nav-item">
                <i class="ph ph-credit-card"></i> Pembayaran
            </a>

            <a href="{{ route('admin.laporan') }}" class="nav-item">
                <i class="ph ph-chart-bar"></i> Laporan
            </a>

            <a href="{{ route('admin.pengguna') }}" class="nav-item">
                <i class="ph ph-users"></i> Pengguna
            </a>

            <a href="{{ route('admin.voucher') }}" class="nav-item">
                <i class="ph ph-tag"></i> Promo & Voucher
            </a>

            <a href="{{ route('admin.kemitraan') }}" class="nav-item">
                <i class="ph ph-handshake"></i> Kemitraan
            </a>
        </nav>
    </aside>

    <main class="main-wrapper">

        <header class="top-header">
            <div class="header-title">
                <h2>Ringkasan Dasbor</h2>
                <div class="header-breadcrumb">
                    <span>Beranda</span> / Dasbor
                </div>
            </div>

            <div class="header-actions">
                <button class="btn-notification">
                    <i class="ph ph-bell"></i>
                    <span class="badge">2</span>
                </button>
                <div class="user-profile">
                    <div class="user-avatar">
                        <i class="ph ph-user"></i>
                    </div>
                    <div class="user-info">
                        <p>Admin User</p>
                        <p>Administrator</p>
                    </div>
                    <i class="ph ph-caret-down" style="color: var(--text-light);"></i>
                </div>
            </div>
        </header>

        <div class="content-body">

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon green"><i class="ph ph-currency-dollar"></i></div>
                    <div class="stat-value">Rp 125.430.000</div>
                    <div class="stat-label">Total Penjualan</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon blue"><i class="ph ph-shopping-cart"></i></div>
                    <div class="stat-value">543</div>
                    <div class="stat-label">Total Pesanan</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon purple"><i class="ph ph-users"></i></div>
                    <div class="stat-value">1.247</div>
                    <div class="stat-label">Total Pengguna</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon red"><i class="ph ph-warning-circle"></i></div>
                    <div class="stat-value">8</div>
                    <div class="stat-label">Stok Menipis</div>
                </div>
            </div>

            <div class="charts-grid">
                <div class="chart-card">
                    <h3>Ringkasan Penjualan</h3>
                    <div class="chart-container">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>
                <div class="chart-card">
                    <h3>Produk Terlaris</h3>
                    <div class="chart-container">
                        <canvas id="productsChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="activity-card">
                <div class="activity-header">
                    <h3>Aktivitas Terbaru</h3>
                </div>
                <div class="activity-list">
                    <div class="activity-item">
                        <div>
                            <div class="activity-title">Pesanan baru diterima</div>
                            <div class="activity-desc">ORD-001 - Rp 2.849.000</div>
                        </div>
                        <div class="activity-time">5 menit yang lalu</div>
                    </div>
                    <div class="activity-item">
                        <div>
                            <div class="activity-title">Stok diperbarui</div>
                            <div class="activity-desc">Athletic Flow Joggers - 45 unit</div>
                        </div>
                        <div class="activity-time">1 jam yang lalu</div>
                    </div>
                    <div class="activity-item">
                        <div>
                            <div class="activity-title">Pengguna baru terdaftar</div>
                            <div class="activity-desc">jane@example.com</div>
                        </div>
                        <div class="activity-time">2 jam yang lalu</div>
                    </div>
                    <div class="activity-item">
                        <div>
                            <div class="activity-title">Pembayaran dikonfirmasi</div>
                            <div class="activity-desc">ORD-002 - Rp 2.249.000</div>
                        </div>
                        <div class="activity-time">3 jam yang lalu</div>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Setup Default Charts
            Chart.defaults.font.family = "'Inter', sans-serif";
            Chart.defaults.color = '#A1A1AA';
            const gridColor = '#F4F4F5';

            // 1. Sales Line Chart
            const ctxSales = document.getElementById('salesChart').getContext('2d');
            new Chart(ctxSales, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                    datasets: [{
                        label: 'Penjualan',
                        data: [12000, 15000, 18000, 16000, 22000, 25000],
                        borderColor: '#111111',
                        backgroundColor: '#111111',
                        borderWidth: 2,
                        pointBackgroundColor: '#111111',
                        pointBorderColor: '#111111',
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Rp ' + context.raw.toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 26000,
                            ticks: {
                                stepSize: 6500
                            },
                            grid: {
                                color: gridColor,
                                drawBorder: false,
                                borderDash: [5, 5]
                            }
                        },
                        x: {
                            grid: {
                                display: true,
                                color: gridColor,
                                drawBorder: false,
                                borderDash: [5, 5]
                            }
                        }
                    }
                }
            });

            // 2. Best Selling Bar Chart
            const ctxProducts = document.getElementById('productsChart').getContext('2d');
            new Chart(ctxProducts, {
                type: 'bar',
                data: {
                    labels: ['Athletic', 'Sport', 'Cargo', 'Jogger', 'Chino'],
                    datasets: [{
                        label: 'Terjual',
                        data: [150, 135, 118, 98, 87],
                        backgroundColor: '#111111',
                        borderRadius: 3,
                        barThickness: 40
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 160,
                            ticks: {
                                stepSize: 40
                            },
                            grid: {
                                color: gridColor,
                                drawBorder: false,
                                borderDash: [5, 5]
                            }
                        },
                        x: {
                            grid: {
                                display: false,
                                drawBorder: false
                            }
                        }
                    }
                }
            });
        });
    </script>
</body>

</html>