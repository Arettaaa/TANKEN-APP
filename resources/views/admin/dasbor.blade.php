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
        /* CSS RESET & VARIABLES (100% SAMA DENGAN PAGE STOK) */
        :root {
            --sidebar-bg: #141414;
            --main-bg: #F4F4F5;
            --card-bg: #FFFFFF;
            --border-color: #E4E4E7;
            --text-main: #111111;
            --text-muted: #71717A;
            --text-light: #A1A1AA;
            --font-family: 'Inter', sans-serif;
            --primary-black: #111111;

            /* Icon Colors */
            --icon-green-bg: #dcfce7; --icon-green-text: #16a34a;
            --icon-blue-bg: #dbeafe; --icon-blue-text: #2563eb;
            --icon-orange-bg: #ffedd5; --icon-orange-text: #ea580c;
            --icon-red-bg: #fee2e2; --icon-red-text: #dc2626;
            --icon-purple-bg: #f3e8ff; --icon-purple-text: #9333ea; /* Tambahan untuk Dasbor */
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: var(--font-family); background-color: var(--main-bg);
            color: var(--text-main); display: flex; height: 100vh; overflow: hidden;
        }

        a { text-decoration: none; color: inherit; }
        ul { list-style: none; }
        button { cursor: pointer; font-family: inherit; }

        /* =========================================
           SIDEBAR (SAMA PERSIS DENGAN STOK)
           ========================================= */
        .sidebar {
            width: 260px; background-color: var(--sidebar-bg); color: #FFFFFF;
            display: flex; flex-direction: column; flex-shrink: 0;
            border-right: 1px solid rgba(255,255,255,0.05);
        }

        .sidebar-header { padding: 24px 20px; margin-bottom: 8px; }
        .sidebar-brand { font-size: 24px; font-weight: 900; letter-spacing: -1px; display: flex; align-items: center; gap: 8px; }
        .sidebar-subtitle { font-size: 12px; color: var(--text-light); font-weight: 500; margin-top: 4px; letter-spacing: 0.5px; }

        .sidebar-nav {
            flex: 1; overflow-y: auto; padding: 0 12px 24px 12px;
            display: flex; flex-direction: column; gap: 6px;
        }
        .sidebar-nav::-webkit-scrollbar { display: none; }

        .nav-item {
            display: flex; align-items: center; gap: 12px; padding: 10px 12px;
            color: var(--text-light); border-radius: 8px; transition: all 0.2s ease;
            font-size: 14px; font-weight: 500;
        }
        .nav-item i { font-size: 20px; width: 24px; text-align: center; }
        .nav-item:hover { color: #FFFFFF; background-color: rgba(255, 255, 255, 0.05); }
        .nav-item.active { color: #FFFFFF; background-color: rgba(255, 255, 255, 0.1); font-weight: 600; }
        
        .nav-sub-item { padding-left: 48px; font-size: 13px; }

        /* =========================================
           MAIN LAYOUT & HEADER
           ========================================= */
        .main-wrapper { flex: 1; display: flex; flex-direction: column; overflow: hidden; }

        .top-header {
            height: 80px; background-color: var(--card-bg); border-bottom: 1px solid var(--border-color);
            display: flex; align-items: center; justify-content: space-between; padding: 0 32px; flex-shrink: 0;
        }
        .header-title h2 { font-size: 24px; font-weight: 800; letter-spacing: -0.5px; }
        .header-breadcrumb { font-size: 14px; color: var(--text-muted); margin-top: 4px; font-weight: 500; }
        .header-breadcrumb span { color: var(--text-main); font-weight: 600; }

        .header-actions { display: flex; align-items: center; gap: 24px; }
        .btn-notification { position: relative; background: none; border: none; color: var(--text-muted); }
        .btn-notification i { font-size: 24px; }
        .badge {
            position: absolute; top: -4px; right: -4px; width: 18px; height: 18px;
            background-color: #ef4444; color: white; font-size: 10px; font-weight: bold;
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            border: 2px solid var(--card-bg);
        }

        .user-profile { display: flex; align-items: center; gap: 12px; cursor: pointer; }
        .user-avatar { width: 40px; height: 40px; background-color: var(--sidebar-bg); color: white; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 20px; }
        .user-info p:first-child { font-size: 14px; font-weight: 700; }
        .user-info p:last-child { font-size: 12px; color: var(--text-muted); }

        /* =========================================
           CONTENT BODY & DASBOR COMPONENTS
           ========================================= */
        .content-body { flex: 1; overflow-y: auto; padding: 32px; }

        /* KPI Cards */
        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; margin-bottom: 24px; }
        .stat-card { background-color: var(--card-bg); padding: 24px; border-radius: 8px; border: 1px solid var(--border-color); box-shadow: 0 1px 2px rgba(0,0,0,0.02); }
        .stat-icon { width: 48px; height: 48px; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 24px; margin-bottom: 16px; }
        .stat-icon.blue { background-color: var(--icon-blue-bg); color: var(--icon-blue-text); }
        .stat-icon.green { background-color: var(--icon-green-bg); color: var(--icon-green-text); }
        .stat-icon.purple { background-color: var(--icon-purple-bg); color: var(--icon-purple-text); }
        .stat-icon.red { background-color: var(--icon-red-bg); color: var(--icon-red-text); }
        .stat-value { font-size: 30px; font-weight: 900; letter-spacing: -1px; margin-bottom: 4px; }
        .stat-label { font-size: 11px; font-weight: 700; color: var(--text-light); text-transform: uppercase; letter-spacing: 1px; }

        /* Charts Section */
        .charts-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; margin-bottom: 24px; }
        .chart-card { background-color: var(--card-bg); padding: 24px; border-radius: 8px; border: 1px solid var(--border-color); box-shadow: 0 1px 2px rgba(0,0,0,0.02); }
        .chart-card h3 { font-size: 18px; font-weight: 700; margin-bottom: 20px; }
        .chart-container { position: relative; height: 260px; width: 100%; }

        /* Activity List */
        .activity-card { background-color: var(--card-bg); border-radius: 8px; border: 1px solid var(--border-color); box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02); }
        .activity-header { padding: 20px 24px; border-bottom: 1px solid var(--border-color); }
        .activity-header h3 { font-size: 18px; font-weight: 700; }
        .activity-item { display: flex; justify-content: space-between; align-items: center; padding: 20px 24px; border-bottom: 1px solid var(--border-color); transition: background-color 0.2s; }
        .activity-item:last-child { border-bottom: none; }
        .activity-item:hover { background-color: #FAFAFA; }
        .activity-title { font-size: 15px; font-weight: 600; color: var(--text-main); }
        .activity-desc { font-size: 14px; color: var(--text-muted); margin-top: 4px; }
        .activity-desc.warning { color: var(--icon-red-text); font-weight: 500; }
        .activity-time { font-size: 13px; color: var(--text-light); font-weight: 500; }
    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-brand">
                <i class="ph-fill ph-triangle"></i> TANKEN
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
                    <div class="user-avatar"><i class="ph ph-user"></i></div>
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
                    <div class="stat-value" id="val-penjualan">Rp 0</div>
                    <div class="stat-label">Total Penjualan</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon blue"><i class="ph ph-shopping-cart"></i></div>
                    <div class="stat-value" id="val-pesanan">0</div>
                    <div class="stat-label">Total Pesanan</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon purple"><i class="ph ph-users"></i></div>
                    <div class="stat-value" id="val-pengguna">0</div>
                    <div class="stat-label">Total Pengguna</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon red"><i class="ph ph-warning-circle"></i></div>
                    <div class="stat-value" id="val-stok">0</div>
                    <div class="stat-label">Stok Menipis</div>
                </div>
            </div>

            <div class="charts-grid">
                <div class="chart-card">
                    <h3>Tren Penjualan (Bulan Ini)</h3>
                    <div class="chart-container">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>
                <div class="chart-card">
                    <h3>Penjualan per Produk</h3>
                    <div class="chart-container">
                        <canvas id="productsChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="activity-card">
                <div class="activity-header">
                    <h3>Aktivitas Terbaru</h3>
                </div>
                <div>
                    <div class="activity-item">
                        <div>
                            <div class="activity-title">Pesanan baru diterima</div>
                            <div class="activity-desc">Yama Cargo Pants - Rp 120.000</div>
                        </div>
                        <div class="activity-time">5 menit yang lalu</div>
                    </div>
                    <div class="activity-item">
                        <div>
                            <div class="activity-title">Peringatan Stok Menipis</div>
                            <div class="activity-desc warning">Gama Jogger Pants sisa 1 unit</div>
                        </div>
                        <div class="activity-time">1 jam yang lalu</div>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            // 1. DATA CENTER
            const dbData = {
                produkNama: ['Yama Cargo', 'Gama Jogger', 'Sora Track', 'Meru Short'],
                produkTerjual: [110, 90, 80, 50], 
                bulanLabel: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                penjualanBulan: [4500000, 5000000, 6200000, 5800000, 6500000, 7000000],
                totalPesanan: 330, 
                totalPengguna: 142,
                stokMenipis: 1
            };

            const totalPendapatan = dbData.penjualanBulan.reduce((a, b) => a + b, 0);

            // 2. INJEKSI KPI
            const formatRupiah = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 });
            
            document.getElementById('val-penjualan').innerText = formatRupiah.format(totalPendapatan);
            document.getElementById('val-pesanan').innerText = dbData.totalPesanan;
            document.getElementById('val-pengguna').innerText = dbData.totalPengguna;
            document.getElementById('val-stok').innerText = dbData.stokMenipis;

            // 3. RENDER GRAFIK
            Chart.defaults.font.family = "'Inter', sans-serif";
            Chart.defaults.color = '#A1A1AA';

            new Chart(document.getElementById('salesChart').getContext('2d'), {
                type: 'line',
                data: {
                    labels: dbData.bulanLabel,
                    datasets: [{
                        label: 'Pendapatan',
                        data: dbData.penjualanBulan,
                        borderColor: '#111111',
                        backgroundColor: '#111111',
                        borderWidth: 3,
                        pointBackgroundColor: '#FFFFFF',
                        pointBorderColor: '#111111',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        tension: 0.4 
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { 
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) { return formatRupiah.format(context.raw); }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 8000000,
                            ticks: { 
                                stepSize: 2000000,
                                callback: function(value) { return 'Rp ' + (value/1000000) + ' Jt'; }
                            },
                            grid: { borderDash: [5, 5], color: '#F4F4F5', drawBorder: false }
                        },
                        x: { grid: { display: false, drawBorder: false } }
                    }
                }
            });

            new Chart(document.getElementById('productsChart').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: dbData.produkNama,
                    datasets: [{
                        label: 'Unit Terjual',
                        data: dbData.produkTerjual,
                        backgroundColor: '#111111',
                        borderRadius: 4,
                        barPercentage: 0.6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 150,
                            ticks: { stepSize: 30 },
                            grid: { borderDash: [5, 5], color: '#F4F4F5', drawBorder: false }
                        },
                        x: { grid: { display: false, drawBorder: false } }
                    }
                }
            });
        });
    </script>
</body>
</html>