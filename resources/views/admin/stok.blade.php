<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Stok | TANKEN Admin</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <style>
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
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: var(--font-family); background-color: var(--main-bg);
            color: var(--text-main); display: flex; height: 100vh; overflow: hidden;
        }

        a { text-decoration: none; color: inherit; }
        ul { list-style: none; }
        button { cursor: pointer; font-family: inherit; }
        input, select { font-family: inherit; outline: none; }

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

        .content-body { flex: 1; overflow-y: auto; padding: 32px; }

        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; margin-bottom: 24px; }
        .stat-card { background-color: var(--card-bg); padding: 24px; border-radius: 8px; border: 1px solid var(--border-color); box-shadow: 0 1px 2px rgba(0,0,0,0.02); }
        .stat-icon { width: 48px; height: 48px; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 24px; margin-bottom: 16px; }
        .stat-icon.blue { background-color: var(--icon-blue-bg); color: var(--icon-blue-text); }
        .stat-icon.green { background-color: var(--icon-green-bg); color: var(--icon-green-text); }
        .stat-icon.orange { background-color: var(--icon-orange-bg); color: var(--icon-orange-text); }
        .stat-icon.red { background-color: var(--icon-red-bg); color: var(--icon-red-text); }
        .stat-value { font-size: 30px; font-weight: 900; letter-spacing: -1px; margin-bottom: 4px; }
        .stat-label { font-size: 11px; font-weight: 700; color: var(--text-light); text-transform: uppercase; letter-spacing: 1px; }

        .page-actions-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
        .item-count { font-size: 14px; color: var(--text-muted); font-weight: 500; }

        .filter-section {
            background-color: var(--card-bg); padding: 16px; border: 1px solid var(--border-color);
            border-radius: 6px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;
        }
        .filter-group-left { display: flex; gap: 12px; align-items: center; }
        .filter-group-right { display: flex; gap: 12px; align-items: center; }

        .custom-select {
            padding: 10px 32px 10px 12px; border: 1px solid var(--border-color); border-radius: 6px;
            font-size: 14px; color: var(--text-main); background-color: var(--card-bg); appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%2371717A' viewBox='0 0 256 256'%3E%3Cpath d='M213.66,101.66l-80,80a8,8,0,0,1-11.32,0l-80-80A8,8,0,0,1,53.66,90.34L128,164.69l74.34-74.35a8,8,0,0,1,11.32,11.32Z'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat; background-position: right 12px center; cursor: pointer;
        }
        .filter-icon { padding: 10px; border: 1px solid var(--border-color); border-radius: 6px; display: flex; align-items: center; justify-content: center; color: var(--text-muted); }

        .search-box { position: relative; }
        .search-box i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 16px;}
        .search-box input { padding: 10px 12px 10px 36px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 14px; width: 250px; }

        .btn-outline {
            background-color: var(--card-bg); color: var(--text-main); border: 1px solid var(--border-color);
            padding: 10px 16px; font-size: 14px; font-weight: 600; border-radius: 6px; display: flex; align-items: center; gap: 8px; transition: background 0.2s;
        }
        .btn-outline:hover { background-color: #F9F9F9; }

        .table-container { background-color: var(--card-bg); border: 1px solid var(--border-color); border-radius: 6px; overflow: hidden; }
        table { width: 100%; border-collapse: collapse; }
        th { background-color: var(--primary-black); color: #FFFFFF; text-align: left; padding: 16px 24px; font-size: 11px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; }
        td { padding: 16px 24px; border-bottom: 1px solid var(--border-color); vertical-align: middle; }
        tr:last-child td { border-bottom: none; }

        .td-product { display: flex; align-items: center; gap: 16px; }
        .product-img { width: 48px; height: 48px; background-color: #eee; border-radius: 6px; object-fit: cover; }
        .product-name { font-size: 14px; font-weight: 600; color: var(--text-main); line-height: 1.4; max-width: 250px;}

        .td-sku { font-size: 14px; color: var(--text-muted); font-family: monospace; }
        .td-stock { font-size: 16px; font-weight: 800; }
        .td-min-stock { font-size: 14px; color: var(--text-muted); font-weight: 600; }
        
        .badge-cat { padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; background-color: #e0f2fe; color: #0284c7; }
        .badge-cat.wanita { background-color: #fce7f3; color: #db2777; }

        .status-badge { padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 700; display: inline-block;}
        .status-aman { background-color: var(--icon-green-bg); color: var(--icon-green-text); }
        .status-menipis { background-color: var(--icon-orange-bg); color: var(--icon-orange-text); }

        .action-btns { display: flex; gap: 8px; }
        .btn-action-small {
            padding: 6px 10px; border: 1px solid var(--border-color); border-radius: 4px;
            background: white; font-size: 12px; font-weight: 600; cursor: pointer;
            display: flex; align-items: center; gap: 4px; transition: 0.2s;
        }
        .btn-action-small:hover { background: #F4F4F5; }
        .btn-action-small i { font-size: 14px; }
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
            <a href="{{ route('admin.dasbor') }}" class="nav-item">
                <i class="ph ph-squares-four"></i> Dasbor
            </a>
            
            <a href="{{ route('admin.produk') }}" class="nav-item">
                <i class="ph ph-package"></i> Produk
            </a>
            <a href="{{ route('admin.ulasan') }}" class="nav-item nav-sub-item">
                <i class="ph ph-chat-teardrop"></i> Ulasan
            </a>

            <a href="{{ route('admin.stok') }}" class="nav-item active">
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
                <h2>Manajemen Stok</h2>
                <div class="header-breadcrumb">
                    <span>Beranda</span> / Stok
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
                    <div class="stat-icon blue"><i class="ph ph-package"></i></div>
                    <div class="stat-value">156</div>
                    <div class="stat-label">Total Varian Produk</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon green"><i class="ph ph-stack"></i></div>
                    <div class="stat-value">2.450</div>
                    <div class="stat-label">Total Unit Stok</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon orange"><i class="ph ph-warning-circle"></i></div>
                    <div class="stat-value">12</div>
                    <div class="stat-label">Stok Menipis</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon red"><i class="ph ph-x-circle"></i></div>
                    <div class="stat-value">3</div>
                    <div class="stat-label">Stok Habis</div>
                </div>
            </div>

            <div class="page-actions-top">
                <div class="item-count" id="jumlahProduk">Menghitung...</div>
            </div>

            <div class="filter-section">
                <div class="filter-group-left">
                    <div class="filter-icon"><i class="ph ph-funnel"></i></div>
                    <select class="custom-select" id="filterKategori" style="width: 150px;">
                        <option value="">Semua Kategori</option>
                        <option value="pria">Pria</option>
                        <option value="wanita">Wanita</option>
                    </select>
                    <select class="custom-select" id="filterUrutkan" style="width: 200px;">
                        <option value="">Urutkan Berdasarkan</option>
                        <option value="stok_terendah">Stok Terendah</option>
                        <option value="stok_tertinggi">Stok Tertinggi</option>
                        <option value="abjad">Abjad / Nama</option>
                    </select>
                    <select class="custom-select" style="width: 150px;">
                        <option value="10">10 per halaman</option>
                        <option value="25">25 per halaman</option>
                        <option value="50">50 per halaman</option>
                    </select>
                </div>
                <div class="filter-group-right">
                    <div class="search-box">
                        <i class="ph ph-magnifying-glass"></i>
                        <input type="text" id="searchInput" placeholder="Cari nama atau SKU...">
                    </div>
                    <button class="btn-outline" id="exportExcelBtn">
                        <i class="ph ph-download-simple"></i> Export Excel
                    </button>
                </div>
            </div>

            <div class="table-container">
                <table id="stokTable">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>SKU</th>
                            <th>Kategori</th>
                            <th>Stok Saat Ini</th>
                            <th>Min. Stok</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="td-product">
                                <img src="https://images.unsplash.com/photo-1594938298596-eb5fd3f510fd?q=80&w=200" alt="Produk" class="product-img">
                                <span class="product-name">Nylon Crinkle Shortpants sora</span>
                            </td>
                            <td class="td-sku">TNK-WN-001</td>
                            <td><span class="badge-cat wanita">Wanita</span></td>
                            <td class="td-stock">44</td>
                            <td class="td-min-stock">10</td>
                            <td><span class="status-badge status-aman">Aman</span></td>
                            <td class="action-btns">
                                <button class="btn-action-small"><i class="ph ph-plus"></i> In</button>
                                <button class="btn-action-small"><i class="ph ph-minus"></i> Out</button>
                            </td>
                        </tr>
                        <tr>
                            <td class="td-product">
                                <img src="https://images.unsplash.com/photo-1541099649105-f69ad21f3246?q=80&w=200" alt="Produk" class="product-img">
                                <span class="product-name">Outdoor Nylon Taslan Olive</span>
                            </td>
                            <td class="td-sku">TNK-PR-002</td>
                            <td><span class="badge-cat">Pria</span></td>
                            <td class="td-stock">52</td>
                            <td class="td-min-stock">15</td>
                            <td><span class="status-badge status-aman">Aman</span></td>
                            <td class="action-btns">
                                <button class="btn-action-small"><i class="ph ph-plus"></i> In</button>
                                <button class="btn-action-small"><i class="ph ph-minus"></i> Out</button>
                            </td>
                        </tr>
                        <tr>
                            <td class="td-product">
                                <img src="https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?q=80&w=200" alt="Produk" class="product-img">
                                <span class="product-name">Cargo Pants Wanita Nylon Taslan</span>
                            </td>
                            <td class="td-sku">TNK-WN-003</td>
                            <td><span class="badge-cat wanita">Wanita</span></td>
                            <td class="td-stock">45</td>
                            <td class="td-min-stock">10</td>
                            <td><span class="status-badge status-aman">Aman</span></td>
                            <td class="action-btns">
                                <button class="btn-action-small"><i class="ph ph-plus"></i> In</button>
                                <button class="btn-action-small"><i class="ph ph-minus"></i> Out</button>
                            </td>
                        </tr>
                        <tr>
                            <td class="td-product">
                                <img src="https://images.unsplash.com/photo-1624378439575-d1ead6bb17f0?q=80&w=200" alt="Produk" class="product-img">
                                <span class="product-name">Cargo Shortpants Gama Olive</span>
                            </td>
                            <td class="td-sku">TNK-PR-004</td>
                            <td><span class="badge-cat">Pria</span></td>
                            <td class="td-stock">120</td>
                            <td class="td-min-stock">20</td>
                            <td><span class="status-badge status-aman">Aman</span></td>
                            <td class="action-btns">
                                <button class="btn-action-small"><i class="ph ph-plus"></i> In</button>
                                <button class="btn-action-small"><i class="ph ph-minus"></i> Out</button>
                            </td>
                        </tr>
                        <tr>
                            <td class="td-product">
                                <img src="https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?q=80&w=200" alt="Produk" class="product-img">
                                <span class="product-name">Cargo Shortpants Gama Petrol</span>
                            </td>
                            <td class="td-sku">TNK-PR-005</td>
                            <td><span class="badge-cat">Pria</span></td>
                            <td class="td-stock">52</td>
                            <td class="td-min-stock">15</td>
                            <td><span class="status-badge status-aman">Aman</span></td>
                            <td class="action-btns">
                                <button class="btn-action-small"><i class="ph ph-plus"></i> In</button>
                                <button class="btn-action-small"><i class="ph ph-minus"></i> Out</button>
                            </td>
                        </tr>
                        <tr>
                            <td class="td-product">
                                <img src="https://images.unsplash.com/photo-1506629082955-511b1aa562c8?q=80&w=200" alt="Produk" class="product-img">
                                <span class="product-name">Cargo Shortpants Gama black</span>
                            </td>
                            <td class="td-sku">TNK-PR-006</td>
                            <td><span class="badge-cat">Pria</span></td>
                            <td class="td-stock">52</td>
                            <td class="td-min-stock">15</td>
                            <td><span class="status-badge status-aman">Aman</span></td>
                            <td class="action-btns">
                                <button class="btn-action-small"><i class="ph ph-plus"></i> In</button>
                                <button class="btn-action-small"><i class="ph ph-minus"></i> Out</button>
                            </td>
                        </tr>
                        <tr>
                            <td class="td-product">
                                <img src="https://images.unsplash.com/photo-1506629082955-511b1aa562c8?q=80&w=200" alt="Produk" class="product-img">
                                <span class="product-name">Cargo Shortpants Wanita Nylon Crinkle</span>
                            </td>
                            <td class="td-sku">TNK-WN-007</td>
                            <td><span class="badge-cat wanita">Wanita</span></td>
                            <td class="td-stock" style="color: #ea580c;">8</td>
                            <td class="td-min-stock">10</td>
                            <td><span class="status-badge status-menipis">Menipis</span></td>
                            <td class="action-btns">
                                <button class="btn-action-small"><i class="ph ph-plus"></i> In</button>
                                <button class="btn-action-small"><i class="ph ph-minus"></i> Out</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            const searchInput = document.getElementById('searchInput');
            const filterKategori = document.getElementById('filterKategori');
            const filterUrutkan = document.getElementById('filterUrutkan');
            const tableBody = document.querySelector('tbody');
            const rows = Array.from(tableBody.querySelectorAll('tr'));
            
            function updateJumlahProduk() {
                let count = 0;
                rows.forEach(row => {
                    if (window.getComputedStyle(row).display !== 'none') count++;
                });
                document.getElementById('jumlahProduk').textContent = count + ' produk ditampilkan';
            }

            function jalankanFilter() {
                const keyword = searchInput.value.toLowerCase();
                const kategori = filterKategori.value.toLowerCase();
                const urutan = filterUrutkan.value;

                rows.forEach(row => {
                    const name = row.querySelector('.product-name').textContent.toLowerCase();
                    const sku = row.querySelector('.td-sku').textContent.toLowerCase();
                    const catText = row.querySelector('.badge-cat').textContent.toLowerCase();
                    
                    const cocokSearch = name.includes(keyword) || sku.includes(keyword);
                    const cocokKategori = kategori === "" || catText === kategori;

                    if (cocokSearch && cocokKategori) {
                        row.style.display = ''; 
                    } else {
                        row.style.display = 'none'; 
                    }
                });

                if (urutan !== "") {
                    const barisTerlihat = rows.filter(row => row.style.display !== 'none');
                    
                    barisTerlihat.sort((a, b) => {
                        const stokA = parseInt(a.querySelector('.td-stock').textContent);
                        const stokB = parseInt(b.querySelector('.td-stock').textContent);
                        const namaA = a.querySelector('.product-name').textContent.toLowerCase();
                        const namaB = b.querySelector('.product-name').textContent.toLowerCase();

                        if (urutan === 'stok_terendah') return stokA - stokB;
                        if (urutan === 'stok_tertinggi') return stokB - stokA;
                        if (urutan === 'abjad') return namaA.localeCompare(namaB);
                        return 0;
                    });

                    barisTerlihat.forEach(row => tableBody.appendChild(row));
                }
                
                updateJumlahProduk();
            }

            searchInput.addEventListener('input', jalankanFilter);
            filterKategori.addEventListener('change', jalankanFilter);
            filterUrutkan.addEventListener('change', jalankanFilter);

            jalankanFilter();


            const btnExport = document.getElementById('exportExcelBtn');
            if (btnExport) {
                btnExport.addEventListener('click', function() {
                    let csvData = [];
                    
                    let headers = [];
                    const headerCells = document.querySelectorAll('thead th');
                    for (let i = 0; i < headerCells.length - 1; i++) {
                        headers.push('"' + headerCells[i].innerText.trim() + '"');
                    }
                    csvData.push(headers.join(','));

                    rows.forEach(row => {
                        if (window.getComputedStyle(row).display !== 'none') {
                            let rowData = [];
                            const cells = row.querySelectorAll('td');
                            
                            for (let i = 0; i < cells.length - 1; i++) {
                                let cellText = "";
                                if (i === 0) { // Nama Produk
                                    const namaProduk = cells[i].querySelector('.product-name');
                                    cellText = namaProduk ? namaProduk.innerText.trim() : cells[i].innerText.trim();
                                } else {
                                    cellText = cells[i].innerText.trim();
                                }
                                cellText = cellText.replace(/\r?\n|\r/g, " "); 
                                rowData.push('"' + cellText + '"');
                            }
                            csvData.push(rowData.join(','));
                        }
                    });

                    const csvString = csvData.join('\n');
                    const blob = new Blob(["\uFEFF" + csvString], { type: 'text/csv;charset=utf-8;' });
                    const url = URL.createObjectURL(blob);
                    
                    const link = document.createElement("a");
                    link.setAttribute("href", url);
                    link.setAttribute("download", "Laporan_Stok_Tanken.csv");
                    
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                });
            }
        });
    </script>
</body>
</html>