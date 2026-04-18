<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TANKEN | Manajemen Produk</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: #F3F4F6; /* Warna abu-abu muda sesuai desain baru */
            color: #111;
            display: flex;
            min-height: 100vh;
        }

        /* --- SIDEBAR (Tema Gelap Monokrom) --- */
        .sidebar {
            width: 250px;
            background-color: #111111;
            color: #9CA3AF;
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            left: 0;
            top: 0;
            overflow-y: auto;
            z-index: 100;
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
        .sidebar-menu li { margin-bottom: 5px; }
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
        .sidebar-menu a i { font-size: 20px; }
        .sidebar-menu a:hover { color: white; }
        .sidebar-menu a.active {
            background-color: #2D2D2D; /* Blok menu aktif */
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
        .breadcrumb span { color: #666; font-weight: 500; }
        
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
            top: -4px; right: -4px;
            background-color: #E7000B;
            color: white;
            font-size: 10px;
            font-weight: bold;
            width: 16px; height: 16px;
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
            width: 38px; height: 38px;
            background-color: #111;
            color: white;
            border-radius: 6px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 18px;
        }
        .user-info h4 { font-size: 13px; font-weight: 600; color: #111; }
        .user-info p { font-size: 11px; color: #666; }

        /* CONTENT AREA */
        .content-area {
            padding: 0 40px 40px;
        }

        /* HEADER ACTIONS */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }
        .product-count {
            font-size: 14px;
            color: #444;
        }
        .btn-primary {
            background-color: #111111; /* Tombol hitam */
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: opacity 0.2s;
        }
        .btn-primary:hover { opacity: 0.8; }

        /* FILTER BAR */
        .filter-bar {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
            align-items: center;
            background: white;
            padding: 15px;
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        .filter-select {
            background-color: white;
            border: 1px solid #E5E7EB;
            padding: 10px 35px 10px 15px;
            border-radius: 4px;
            font-size: 13px;
            color: #111;
            min-width: 180px;
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 16px;
            outline: none;
        }
        .filter-select:focus { border-color: #111; }
        
        .search-box {
            flex: 1;
            display: flex;
            align-items: center;
            background-color: white;
            border: 1px solid #E5E7EB;
            border-radius: 4px;
            padding: 0 15px;
        }
        .search-box input {
            border: none;
            outline: none;
            padding: 10px;
            width: 100%;
            font-size: 13px;
        }
        .search-box i { color: #888; font-size: 18px; }
        .btn-export {
            background-color: white;
            border: 1px solid #E5E7EB;
            padding: 10px 20px;
            border-radius: 4px;
            font-size: 13px;
            font-weight: 600;
            color: #111;
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-export:hover { background-color: #F9FAFB; }

        /* TABLE STYLES */
        .table-container {
            background-color: white;
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            overflow: hidden;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        thead {
            background-color: #111111; /* Header tabel hitam */
            color: white;
        }
        th {
            padding: 16px 20px;
            text-align: left;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        td {
            padding: 16px 20px;
            border-bottom: 1px solid #F3F4F6;
            font-size: 14px;
            vertical-align: middle;
            color: #111;
        }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover { background-color: #F9FAFB; }

        /* Table Specifics */
        .product-cell {
            display: flex;
            align-items: center;
            gap: 15px;
            font-weight: 600;
            color: #111;
        }
        .product-cell img {
            width: 45px; height: 45px;
            border-radius: 4px;
            object-fit: cover;
            background-color: #EEE;
        }
        .sku-text { font-family: monospace; font-size: 13px; color: #666; }
        .badge-category {
            background-color: #EFF6FF;
            color: #3B82F6;
            padding: 6px 12px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }
        .price-text { font-weight: 700; color: #111; }
        .stock-orange { color: #D97706; font-weight: 700; }
        .stock-green { color: #10B981; font-weight: 700; }
        
        .rating-badge {
            background-color: #FEF3C7;
            padding: 6px 10px;
            border-radius: 4px;
            font-weight: 600;
            font-size: 13px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            color: #111;
        }
        .rating-badge i { color: #F59E0B; }

        /* Custom Toggle Switch */
        .switch {
            position: relative;
            display: inline-block;
            width: 40px;
            height: 22px;
        }
        .switch input { opacity: 0; width: 0; height: 0; }
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0; left: 0; right: 0; bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 34px;
            border: 2px solid transparent;
        }
        .slider:before {
            position: absolute;
            content: "";
            height: 14px; width: 14px;
            left: 2px; bottom: 2px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }
        input:checked + .slider { background-color: white; border-color: #10B981; } 
        input:checked + .slider:before { transform: translateX(18px); background-color: #10B981; }

        /* Action Buttons */
        .action-btns {
            display: flex;
            gap: 15px;
            color: #666;
            font-size: 18px;
        }
        .action-btns i { cursor: pointer; transition: color 0.2s; }
        .action-btns i:hover { color: #111; }

        /* --- MODAL STYLES (Sesuai Gambar Add Product) --- */
        .modal-overlay {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background-color: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(2px);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        .modal-content {
            background-color: white;
            width: 600px;
            max-width: 90%;
            border-radius: 12px;
            padding: 35px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            transform: translateY(20px);
            transition: transform 0.3s ease;
        }
        .modal-overlay.active .modal-content {
            transform: translateY(0);
        }
        .modal-header {
            margin-bottom: 25px;
        }
        .modal-header h2 {
            font-size: 24px;
            font-weight: 800;
            color: #111;
            letter-spacing: -0.5px;
        }
        
        .form-group { margin-bottom: 18px; }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #111;
        }
        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #E5E7EB;
            border-radius: 6px;
            font-size: 14px;
            outline: none;
            font-family: 'Inter', sans-serif;
            transition: border-color 0.2s;
        }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
            border-color: #111;
        }
        .form-group textarea {
            resize: vertical;
            height: 100px;
        }
        .upload-area {
            border: 1px dashed #D1D5DB;
            border-radius: 6px;
            padding: 40px;
            text-align: center;
            cursor: pointer;
            color: #666;
            transition: background 0.2s;
        }
        .upload-area:hover { background-color: #F9FAFB; }
        .upload-area i { font-size: 28px; color: #888; margin-bottom: 10px; }
        .upload-area p { font-size: 13px; }
        
        .modal-actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }
        .btn-cancel {
            flex: 1;
            background: white;
            border: 1px solid #E5E7EB;
            color: #111;
            padding: 14px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-cancel:hover { background: #F9FAFB; }
        
        .btn-submit {
            flex: 1;
            background: #111111;
            color: white;
            border: none;
            padding: 14px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: opacity 0.2s;
        }
        .btn-submit:hover { opacity: 0.8; }

    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="sidebar-brand">
            <h2>TANKEN</h2>
            <p>Panel Admin</p>
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
                <h1>Manajemen Produk</h1>
                <div class="breadcrumb">Beranda / <span>Produk</span></div>
            </div>
            <div class="topbar-right">
                <div class="notification">
                    <i class="ph ph-bell"></i>
                    <span class="notif-badge">2</span>
                </div>
                <div class="user-profile">
                    <div class="user-avatar"><i class="ph ph-user"></i></div>
                    <div class="user-info">
                        <h4>Pengguna Admin</h4>
                        <p>Administrator</p>
                    </div>
                    <i class="ph ph-caret-down" style="color: #666;"></i>
                </div>
            </div>
        </header>

        <div class="content-area">
            
            <div class="page-header">
                <div class="product-count">8 dari 8 produk</div>
                <button class="btn-primary" onclick="openModal()"><i class="ph ph-plus"></i> Tambah Produk</button>
            </div>

            <div class="filter-bar">
                <select class="filter-select">
                    <option value="" disabled selected><i class="ph ph-funnel"></i> Semua Kategori</option>
                    <option value="celana_pendek_pria">Celana Pendek Pria</option>
                    <option value="celana_pendek_wanita">Celana Pendek Wanita</option>
                    <option value="celana_panjang_kargo">Celana Panjang Kargo</option>
                </select>

                <select class="filter-select">
                    <option value="" disabled selected>Urutkan Berdasarkan</option>
                    <option value="nama">Nama</option>
                    <option value="harga_tertinggi">Harga Tertinggi</option>
                    <option value="harga_terendah">Harga Terendah</option>
                    <option value="stok">Jumlah Stok</option>
                </select>

                <select class="filter-select" style="min-width: 140px;">
                    <option value="10">10 per halaman</option>
                    <option value="25">25 per halaman</option>
                    <option value="50">50 per halaman</option>
                </select>
                
                <div class="search-box">
                    <i class="ph ph-magnifying-glass"></i>
                    <input type="text" placeholder="Cari produk...">
                </div>
                
                <button class="btn-export"><i class="ph ph-download-simple"></i> Ekspor Excel</button>
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
                            <th>Penilaian</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="product-cell">
                                    <img src="https://images.unsplash.com/photo-1624378439575-d1ead6bb17f0?w=100" alt="Product">
                                    <span>Athletic Flow<br>Joggers</span>
                                </div>
                            </td>
                            <td class="sku-text">AFL-JOG-001</td>
                            <td><span class="badge-category">Jogger</span></td>
                            <td class="price-text">$89.99</td>
                            <td class="stock-orange">45</td>
                            <td><div class="rating-badge"><i class="ph-fill ph-star"></i> 4.8</div></td>
                            <td>
                                <label class="switch"><input type="checkbox"><span class="slider"></span></label>
                            </td>
                            <td>
                                <div class="action-btns">
                                    <i class="ph ph-eye"></i>
                                    <i class="ph ph-pencil-simple"></i>
                                    <i class="ph ph-trash"></i>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="product-cell">
                                    <img src="https://images.unsplash.com/photo-1542272454315-4c01d7abdf4a?w=100" alt="Product">
                                    <span>Classic Wide<br>Leg Pants</span>
                                </div>
                            </td>
                            <td class="sku-text">CLS-WID-008</td>
                            <td><span class="badge-category">Casual</span></td>
                            <td class="price-text">$129.99</td>
                            <td class="stock-orange">25</td>
                            <td><div class="rating-badge"><i class="ph-fill ph-star"></i> 4.5</div></td>
                            <td>
                                <label class="switch"><input type="checkbox" checked><span class="slider"></span></label>
                            </td>
                            <td>
                                <div class="action-btns">
                                    <i class="ph ph-eye"></i>
                                    <i class="ph ph-pencil-simple"></i>
                                    <i class="ph ph-trash"></i>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="product-cell">
                                    <img src="https://images.unsplash.com/photo-1604176354204-926873812d4e?w=100" alt="Product">
                                    <span>Minimalist<br>Cargo Pants</span>
                                </div>
                            </td>
                            <td class="sku-text">MIN-CAR-002</td>
                            <td><span class="badge-category">Cargo</span></td>
                            <td class="price-text">$119.99</td>
                            <td class="stock-orange">32</td>
                            <td><div class="rating-badge"><i class="ph-fill ph-star"></i> 4.9</div></td>
                            <td>
                                <label class="switch"><input type="checkbox" checked><span class="slider"></span></label>
                            </td>
                            <td>
                                <div class="action-btns">
                                    <i class="ph ph-eye"></i>
                                    <i class="ph ph-pencil-simple"></i>
                                    <i class="ph ph-trash"></i>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <div class="modal-overlay" id="productModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Tambah Produk Baru</h2>
            </div>
            
            <form action="#" method="POST">
                <div class="form-group">
                    <label>Nama Produk</label>
                    <input type="text" placeholder="Masukkan nama produk">
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>SKU</label>
                        <input type="text" placeholder="mis., AFL-JOG-001">
                    </div>
                    <div class="form-group">
                        <label>Kategori</label>
                        <select>
                            <option value="" disabled selected>Pilih kategori...</option>
                            <option value="celana_pendek_pria">Celana Pendek Pria</option>
                            <option value="celana_pendek_wanita">Celana Pendek Wanita</option>
                            <option value="celana_panjang_kargo">Celana Panjang Kargo</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Harga</label>
                        <input type="number" placeholder="0.00">
                    </div>
                    <div class="form-group">
                        <label>Stok</label>
                        <input type="number" placeholder="0">
                    </div>
                </div>

                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea placeholder="Masukkan deskripsi produk"></textarea>
                </div>

                <div class="form-group">
                    <label>Unggah Gambar</label>
                    <div class="upload-area">
                        <i class="ph ph-plus"></i>
                        <p>Klik untuk mengunggah gambar</p>
                    </div>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="closeModal()">Batal</button>
                    <button type="submit" class="btn-submit">Tambah Produk</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const modal = document.getElementById('productModal');

        // Fungsi Buka Modal
        function openModal() {
            modal.classList.add('active');
        }

        // Fungsi Tutup Modal
        function closeModal() {
            modal.classList.remove('active');
        }

        // Klik di area gelap untuk menutup
        modal.addEventListener('click', function(e) {
            if(e.target === modal) {
                closeModal();
            }
        });
    </script>
</body>
</html>