<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Produk | TANKEN Admin</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <style>
        /* =========================================
           VARIABLES & RESET
           ========================================= */
        :root {
            --sidebar-bg: #111111;
            --main-bg: #F4F4F5;
            --card-bg: #FFFFFF;
            --border-color: #E4E4E7;
            --text-main: #111111;
            --text-muted: #71717A;
            --text-light: #A1A1AA;
            --font-family: 'Inter', sans-serif;
            --primary-black: #111111;
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

        button {
            cursor: pointer;
            font-family: inherit;
        }

        input,
        select,
        textarea {
            font-family: inherit;
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

        /* MAIN LAYOUT & HEADER */
        .main-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

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
            font-weight: 800;
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
            color: var(--text-muted);
        }

        .btn-notification i {
            font-size: 24px;
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

        /* =========================================
           CONTENT BODY
           ========================================= */
        .content-body {
            flex: 1;
            overflow-y: auto;
            padding: 32px;
        }

        .page-actions-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .item-count {
            font-size: 14px;
            color: var(--text-muted);
            font-weight: 500;
        }

        .btn-black {
            background-color: var(--primary-black);
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 14px;
            font-weight: 600;
            border-radius: 6px;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: background 0.2s;
        }

        .btn-black:hover {
            background-color: #333;
        }

        /* Filter Section */
        .filter-section {
            background-color: var(--card-bg);
            padding: 16px;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .filter-group-left {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .filter-group-right {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .custom-select {
            padding: 10px 32px 10px 12px;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            font-size: 14px;
            color: var(--text-main);
            background-color: var(--card-bg);
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%2371717A' viewBox='0 0 256 256'%3E%3Cpath d='M213.66,101.66l-80,80a8,8,0,0,1-11.32,0l-80-80A8,8,0,0,1,53.66,90.34L128,164.69l74.34-74.35a8,8,0,0,1,11.32,11.32Z'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            outline: none;
        }

        .filter-icon {
            padding: 10px;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
        }

        .search-box {
            position: relative;
        }

        .search-box i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
        }

        .search-box input {
            padding: 10px 12px 10px 36px;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            font-size: 14px;
            width: 250px;
            outline: none;
        }

        .btn-outline {
            background-color: var(--card-bg);
            color: var(--text-main);
            border: 1px solid var(--border-color);
            padding: 10px 16px;
            font-size: 14px;
            font-weight: 600;
            border-radius: 6px;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: background 0.2s;
        }

        .btn-outline:hover {
            background-color: #F9F9F9;
        }

        /* Table */
        .table-container {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 6px;
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background-color: var(--primary-black);
            color: #FFFFFF;
            text-align: left;
            padding: 16px 24px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        td {
            padding: 16px 24px;
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
        }

        tr:last-child td {
            border-bottom: none;
        }

        .td-product {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .product-img {
            width: 48px;
            height: 48px;
            background-color: #eee;
            border-radius: 6px;
            object-fit: cover;
        }

        .product-name {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-main);
            line-height: 1.4;
            max-width: 250px;
        }

        .td-sku {
            font-size: 14px;
            color: var(--text-muted);
        }

        .td-price {
            font-size: 14px;
            font-weight: 700;
        }

        .td-stock {
            font-size: 14px;
            font-weight: 700;
            color: #d97706;
        }

        /* Orange for emphasis */

        .badge-cat {
            background-color: #e0f2fe;
            color: #0284c7;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-cat.wanita {
            background-color: #fce7f3;
            color: #db2777;
        }

        .rating-box {
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 13px;
            font-weight: 600;
        }

        .rating-box i {
            color: #f59e0b;
        }

        /* Toggle Switch */
        .switch {
            position: relative;
            display: inline-block;
            width: 44px;
            height: 24px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 24px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked+.slider {
            background-color: #10b981;
        }

        input:checked+.slider:before {
            transform: translateX(20px);
        }

        .action-btns {
            display: flex;
            gap: 12px;
            color: var(--text-muted);
            font-size: 18px;
        }

        .action-btns i {
            cursor: pointer;
            transition: color 0.2s;
        }

        .action-btns i:hover {
            color: var(--text-main);
        }

        /* =========================================
           MODAL (ADD PRODUCT)
           ========================================= */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: none;
            /* Hidden by default */
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        .modal-overlay.active {
            display: flex;
        }

        .modal-box {
            background-color: var(--card-bg);
            width: 600px;
            border-radius: 8px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
        }

        .modal-header {
            padding: 24px;
            border-bottom: 1px solid var(--border-color);
        }

        .modal-header h3 {
            font-size: 20px;
            font-weight: 800;
        }

        .modal-body {
            padding: 24px;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            font-size: 14px;
            outline: none;
        }

        .form-group textarea {
            resize: vertical;
            height: 80px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .upload-area {
            border: 2px dashed var(--border-color);
            border-radius: 6px;
            padding: 40px;
            text-align: center;
            cursor: pointer;
            transition: border 0.2s;
        }

        .upload-area:hover {
            border-color: var(--text-light);
        }

        .upload-area i {
            font-size: 32px;
            color: var(--text-main);
            margin-bottom: 8px;
        }

        .upload-area p {
            font-size: 14px;
            color: var(--text-muted);
            font-weight: 500;
        }

        .modal-footer {
            padding: 24px;
            border-top: 1px solid var(--border-color);
            display: flex;
            justify-content: flex-end;
            gap: 12px;
        }

        .btn-cancel {
            background: white;
            border: 1px solid var(--border-color);
            color: var(--text-main);
            padding: 12px 24px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 14px;
        }

        .btn-cancel:hover {
            background-color: #F9F9F9;
        }

        .btn-submit {
            background: var(--primary-black);
            border: none;
            color: white;
            padding: 12px 24px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 14px;
        }

        .btn-submit:hover {
            background-color: #333;
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
            <a href="{{ route('admin.dasbor') }}" class="nav-item">
                <i class="ph ph-squares-four"></i> Dasbor
            </a>

            <a href="{{ route('admin.produk') }}" class="nav-item active">
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
                <h2>Manajemen Produk</h2>
                <div class="header-breadcrumb">
                    <span>Beranda</span> / Produk
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

            <div class="page-actions-top">
                <div class="item-count" id="jumlahProduk">Menghitung...</div>

                <button class="btn-black" id="openModalBtn">
                    <i class="ph ph-plus"></i> Tambah Produk
                </button>
            </div>

            <div class="filter-section">
                <div class="filter-group-left">
                    <div class="filter-icon"><i class="ph ph-funnel"></i></div>
                    <select class="custom-select" style="width: 150px;">
                        <option value="">Semua Kategori</option>
                        <option value="pria">Pria</option>
                        <option value="wanita">Wanita</option>
                    </select>
                    <select class="custom-select" style="width: 220px;">
                        <option value="">Urutkan Berdasarkan</option>
                        <option value="nama">Abjad / Nama</option>
                        <option value="harga_rendah">Harga Terendah</option>
                        <option value="harga_tinggi">Harga Tertinggi</option>
                        <option value="stok">Jumlah Stok</option>
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
                        <input type="text" placeholder="Cari produk...">
                    </div>
                    <button class="btn-outline" id="exportExcelBtn">
                        <i class="ph ph-download-simple"></i> Export Excel
                    </button>
                </div>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>SKU</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Rating</th>
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
                            <td class="td-price">Rp 89.000</td>
                            <td class="td-stock">44</td>
                            <td>
                                <div class="rating-box"><i class="ph-fill ph-star"></i> 5.0</div>
                            </td>
                            <td><label class="switch"><input type="checkbox" checked><span class="slider"></span></label></td>
                            <td class="action-btns"><i class="ph ph-eye"></i> <i class="ph ph-pencil-simple"></i> <i class="ph ph-trash"></i></td>
                        </tr>
                        <tr>
                            <td class="td-product">
                                <img src="https://images.unsplash.com/photo-1541099649105-f69ad21f3246?q=80&w=200" alt="Produk" class="product-img">
                                <span class="product-name">Outdoor Nylon Taslan Olive</span>
                            </td>
                            <td class="td-sku">TNK-PR-002</td>
                            <td><span class="badge-cat">Pria</span></td>
                            <td class="td-price">Rp 159.000</td>
                            <td class="td-stock">52</td>
                            <td>
                                <div class="rating-box"><i class="ph-fill ph-star"></i> 4.8</div>
                            </td>
                            <td><label class="switch"><input type="checkbox" checked><span class="slider"></span></label></td>
                            <td class="action-btns"><i class="ph ph-eye"></i> <i class="ph ph-pencil-simple"></i> <i class="ph ph-trash"></i></td>
                        </tr>
                        <tr>
                            <td class="td-product">
                                <img src="https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?q=80&w=200" alt="Produk" class="product-img">
                                <span class="product-name">Cargo Pants Wanita Nylon Taslan</span>
                            </td>
                            <td class="td-sku">TNK-WN-003</td>
                            <td><span class="badge-cat wanita">Wanita</span></td>
                            <td class="td-price">Rp 169.000</td>
                            <td class="td-stock">45</td>
                            <td>
                                <div class="rating-box"><i class="ph-fill ph-star"></i> 5.0</div>
                            </td>
                            <td><label class="switch"><input type="checkbox" checked><span class="slider"></span></label></td>
                            <td class="action-btns"><i class="ph ph-eye"></i> <i class="ph ph-pencil-simple"></i> <i class="ph ph-trash"></i></td>
                        </tr>
                        <tr>
                            <td class="td-product">
                                <img src="https://images.unsplash.com/photo-1624378439575-d1ead6bb17f0?q=80&w=200" alt="Produk" class="product-img">
                                <span class="product-name">Cargo Shortpants Gama Olive</span>
                            </td>
                            <td class="td-sku">TNK-PR-004</td>
                            <td><span class="badge-cat">Pria</span></td>
                            <td class="td-price">Rp 129.000</td>
                            <td class="td-stock">120</td>
                            <td>
                                <div class="rating-box"><i class="ph-fill ph-star"></i> 5.0</div>
                            </td>
                            <td><label class="switch"><input type="checkbox" checked><span class="slider"></span></label></td>
                            <td class="action-btns"><i class="ph ph-eye"></i> <i class="ph ph-pencil-simple"></i> <i class="ph ph-trash"></i></td>
                        </tr>
                        <tr>
                            <td class="td-product">
                                <img src="https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?q=80&w=200" alt="Produk" class="product-img">
                                <span class="product-name">Cargo Shortpants Gama Petrol</span>
                            </td>
                            <td class="td-sku">TNK-PR-005</td>
                            <td><span class="badge-cat">Pria</span></td>
                            <td class="td-price">Rp 159.000</td>
                            <td class="td-stock">52</td>
                            <td>
                                <div class="rating-box"><i class="ph-fill ph-star"></i> 4.8</div>
                            </td>
                            <td><label class="switch"><input type="checkbox" checked><span class="slider"></span></label></td>
                            <td class="action-btns"><i class="ph ph-eye"></i> <i class="ph ph-pencil-simple"></i> <i class="ph ph-trash"></i></td>
                        </tr>
                        <tr>
                            <td class="td-product">
                                <img src="https://unsplash.com/id/foto/seorang-pria-berdiri-di-samping-kolam-renang-ABHLcp7juXU" alt="Produk" class="product-img">
                                <span class="product-name">Cargo Shortpants Gama black</span>
                            </td>
                            <td class="td-sku">TNK-PR-006</td>
                            <td><span class="badge-cat">Pria</span></td>
                            <td class="td-price">Rp 159.000</td>
                            <td class="td-stock">52</td>
                            <td>
                                <div class="rating-box"><i class="ph-fill ph-star"></i> 4.8</div>
                            </td>
                            <td><label class="switch"><input type="checkbox" checked><span class="slider"></span></label></td>
                            <td class="action-btns"><i class="ph ph-eye"></i> <i class="ph ph-pencil-simple"></i> <i class="ph ph-trash"></i></td>
                        </tr>
                        <tr>
                            <td class="td-product">
                                <img src="https://images.unsplash.com/photo-1506629082955-511b1aa562c8?q=80&w=200" alt="Produk" class="product-img">
                                <span class="product-name">Cargo Shortpants Wanita Nylon Crinkle</span>
                            </td>
                            <td class="td-sku">TNK-WN-007</td>
                            <td><span class="badge-cat wanita">Wanita</span></td>
                            <td class="td-price">Rp 119.000</td>
                            <td class="td-stock" style="color: #ef4444;">8</td>
                            <td>
                                <div class="rating-box"><i class="ph-fill ph-star"></i> 4.5</div>
                            </td>
                            <td><label class="switch"><input type="checkbox" checked><span class="slider"></span></label></td>
                            <td class="action-btns"><i class="ph ph-eye"></i> <i class="ph ph-pencil-simple"></i> <i class="ph ph-trash"></i></td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </main>

    <div class="modal-overlay" id="addProductModal">
        <div class="modal-box">
            <div class="modal-header">
                <h3>Tambah Produk Baru</h3>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Nama Produk</label>
                    <input type="text" placeholder="Masukkan nama produk">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>SKU</label>
                        <input type="text" placeholder="cth. TNK-PR-001">
                    </div>
                    <div class="form-group">
                        <label>Kategori</label>
                        <select class="custom-select" style="width: 100%; background-position: right 12px center;">
                            <option value="">Pilih Kategori</option>
                            <option value="pria">Pria</option>
                            <option value="wanita">Wanita</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Harga (Rp)</label>
                        <input type="number" placeholder="0">
                    </div>
                    <div class="form-group">
                        <label>Stok</label>
                        <input type="number" placeholder="0">
                    </div>
                </div>
                <div class="form-group">
                    <label>Upload Gambar</label>
                    <div class="upload-area" id="uploadArea" style="cursor: pointer; position: relative; overflow: hidden; text-align: center; border: 2px dashed #ccc; padding: 20px; border-radius: 8px;">
                        <input type="file" id="inputGambar" name="gambar_produk" accept="image/*" style="display: none;">

                        <div id="uploadContent">
                            <i class="ph ph-plus" style="font-size: 24px;"></i>
                            <p>Klik untuk mengunggah gambar</p>
                        </div>

                        <img id="previewGambar" src="" alt="Preview" style="display: none; max-width: 100%; max-height: 200px; margin: 0 auto; border-radius: 4px;">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-cancel" id="closeModalBtn">Batal</button>
                <button class="btn-submit">Tambah Produk</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            function updateJumlahProduk() {
                const barisProduk = document.querySelectorAll('tbody tr');
                let jumlahTerlihat = 0;

                barisProduk.forEach(baris => {
                    if (window.getComputedStyle(baris).display !== 'none') {
                        jumlahTerlihat++;
                    }
                });

                const elemenJumlah = document.getElementById('jumlahProduk');
                if (elemenJumlah) {
                    elemenJumlah.textContent = jumlahTerlihat + ' produk ditemukan';
                }
            }
            updateJumlahProduk();


            const searchInput = document.querySelector('.search-box input');

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const keyword = this.value.toLowerCase();
                    const barisProduk = document.querySelectorAll('tbody tr');

                    barisProduk.forEach(baris => {
                        const namaProduk = baris.querySelector('.product-name').textContent.toLowerCase();
                        const skuProduk = baris.querySelector('.td-sku').textContent.toLowerCase();

                        if (namaProduk.includes(keyword) || skuProduk.includes(keyword)) {
                            baris.style.display = '';
                        } else {
                            baris.style.display = 'none';
                        }
                    });

                    updateJumlahProduk();
                });
            }


            const modal = document.getElementById('addProductModal');
            const btnOpen = document.getElementById('openModalBtn');
            const btnClose = document.getElementById('closeModalBtn');

            if (btnOpen && modal && btnClose) {
                btnOpen.addEventListener('click', function() {
                    modal.classList.add('active');
                });

                btnClose.addEventListener('click', function() {
                    modal.classList.remove('active');
                });

                window.addEventListener('click', function(event) {
                    if (event.target === modal) {
                        modal.classList.remove('active');
                    }
                });
            }

            const uploadArea = document.getElementById('uploadArea');
            const inputGambar = document.getElementById('inputGambar');
            const uploadContent = document.getElementById('uploadContent');
            const previewGambar = document.getElementById('previewGambar');

            if (uploadArea && inputGambar && uploadContent && previewGambar) {

                uploadArea.addEventListener('click', () => {
                    inputGambar.click();
                });

                inputGambar.addEventListener('change', function(event) {
                    const file = event.target.files[0];

                    if (file) {
                        const reader = new FileReader();

                        reader.onload = function(e) {
                            uploadContent.style.display = 'none';

                            previewGambar.src = e.target.result;
                            previewGambar.style.display = 'block';
                        };

                        reader.readAsDataURL(file);
                    }
                });
            }
            // FITUR EXPORT KE EXCEL (CSV)
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

                    const rows = document.querySelectorAll('tbody tr');

                    rows.forEach(row => {
                        if (window.getComputedStyle(row).display !== 'none') {
                            let rowData = [];
                            const cells = row.querySelectorAll('td');

                            for (let i = 0; i < cells.length - 1; i++) {
                                let cellText = "";

                                if (i === 0) {
                                    const namaProduk = cells[i].querySelector('.product-name');
                                    cellText = namaProduk ? namaProduk.innerText.trim() : cells[i].innerText.trim();
                                } else if (i === 6) {
                                    const checkbox = cells[i].querySelector('input[type="checkbox"]');
                                    cellText = (checkbox && checkbox.checked) ? "Aktif" : "Nonaktif";
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
                    const blob = new Blob(["\uFEFF" + csvString], {
                        type: 'text/csv;charset=utf-8;'
                    });
                    const url = URL.createObjectURL(blob);

                    const link = document.createElement("a");
                    link.setAttribute("href", url);
                    link.setAttribute("download", "Data_Produk_Tanken.csv");

                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                });
            }

        });
    </script>
</body>

</html>