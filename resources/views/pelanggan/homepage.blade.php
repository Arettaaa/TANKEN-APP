<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TANKEN | Define Your Motion</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: #FFFFFF;
            color: #111111;
            overflow-x: hidden;
        }

        a { text-decoration: none; color: inherit; }
        ul { list-style: none; }

        /* --- NAVBAR --- */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 5%;
            background-color: #FFFFFF;
            border-bottom: 1px solid #F0F0F0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .nav-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }
        .nav-brand i { font-size: 26px; }
        .nav-links {
            display: flex;
            gap: 35px;
        }
        .nav-links a {
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: color 0.3s;
        }
        .nav-links a:hover { color: #666; }
        .nav-icons {
            display: flex;
            gap: 20px;
            font-size: 20px;
        }
        .nav-icons i { cursor: pointer; transition: color 0.3s; }
        .nav-icons i:hover { color: #666; }

        /* --- HERO SECTION --- */
        .hero {
            position: relative;
            height: 85vh;
            background-image: url('https://images.unsplash.com/photo-1556821840-3a63f95609a7?q=80&w=2070');
            background-size: cover;
            background-position: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 0 5%;
        }
        .hero::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(to right, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.3) 100%);
        }
        .hero-content {
            position: relative;
            z-index: 1;
            max-width: 800px;
            margin-top: -50px;
        }
        .hero-content h1 {
            font-size: 90px;
            font-weight: 900;
            color: white;
            line-height: 0.95;
            margin-bottom: 30px;
            text-transform: uppercase;
            letter-spacing: -2px;
        }
        .hero-buttons {
            display: flex;
            gap: 15px;
        }
        .btn-solid-white {
            background: white;
            color: #111;
            padding: 14px 30px;
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-radius: 4px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: background 0.3s;
        }
        .btn-solid-white:hover { background: #F0F0F0; }
        
        .btn-outline-white {
            background: transparent;
            color: white;
            border: 1px solid white;
            padding: 14px 30px;
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-radius: 4px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: background 0.3s, color 0.3s;
        }
        .btn-outline-white:hover {
            background: white;
            color: #111;
        }

        /* --- MARQUEE --- */
        .marquee-container {
            position: absolute;
            bottom: 0; left: 0; width: 100%;
            background: rgba(17, 17, 17, 0.9);
            color: white;
            padding: 12px 0;
            overflow: hidden;
            display: flex;
            white-space: nowrap;
            z-index: 2;
            border-top: 1px solid rgba(255,255,255,0.1);
        }
        .marquee-content {
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            display: inline-flex;
            gap: 50px;
            animation: marquee 20s linear infinite;
        }
        @keyframes marquee {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }

        /* --- CATEGORIES SPLIT (WOMEN / MEN) --- */
        .category-split {
            display: grid;
            grid-template-columns: 1fr 1fr;
            height: 400px;
        }
        .cat-box {
            position: relative;
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: flex-end;
            padding: 40px 5%;
            overflow: hidden;
        }
        .cat-box::before {
            content: '';
            position: absolute;
            bottom: 0; left: 0; width: 100%; height: 60%;
            background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
            transition: height 0.3s;
        }
        .cat-box:hover::before { height: 80%; }
        .cat-box-content {
            position: relative;
            z-index: 1;
            color: white;
        }
        .cat-box-content h2 {
            font-size: 48px;
            font-weight: 800;
            margin-bottom: 5px;
            letter-spacing: -1px;
        }
        .cat-box-content a {
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: gap 0.3s;
        }
        .cat-box-content a:hover { gap: 10px; }

        /* --- GLOBAL SECTION STYLES --- */
        .section-container {
            padding: 80px 5%;
        }
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 40px;
        }
        .section-title h4 {
            font-size: 12px;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 5px;
        }
        .section-title h2 {
            font-size: 32px;
            font-weight: 800;
            letter-spacing: -1px;
        }
        .view-all-link {
            font-size: 13px;
            font-weight: 600;
            color: #666;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: color 0.3s;
        }
        .view-all-link:hover { color: #111; }

        /* --- FEATURED PIECES --- */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }
        .product-card {
            cursor: pointer;
        }
        .product-img {
            width: 100%;
            height: 300px;
            background: #F5F5F5;
            border-radius: 4px;
            overflow: hidden;
            margin-bottom: 15px;
        }
        .product-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s;
        }
        .product-card:hover .product-img img { transform: scale(1.05); }
        
        .product-title {
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 4px;
            text-transform: uppercase;
        }
        .product-cat {
            font-size: 12px;
            color: #888;
            margin-bottom: 10px;
        }
        .product-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .product-price {
            font-size: 16px;
            font-weight: 700;
        }
        .product-rating {
            font-size: 13px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .product-rating i { color: #F0B100; }

        /* --- STATS SECTION --- */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            text-align: center;
            padding: 60px 5%;
            border-top: 1px solid #F0F0F0;
            border-bottom: 1px solid #F0F0F0;
        }
        .stat-item {
            border-right: 1px solid #F0F0F0;
        }
        .stat-item:last-child { border-right: none; }
        .stat-item h3 {
            font-size: 42px;
            font-weight: 800;
            margin-bottom: 5px;
            letter-spacing: -1px;
        }
        .stat-item p {
            font-size: 12px;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
        }

        /* --- EXCLUSIVE VOUCHERS --- */
        .vouchers-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
        }
        .voucher-card {
            border: 1px solid #EAEAEA;
            border-radius: 8px;
            padding: 25px;
            background: #FFFFFF;
            transition: box-shadow 0.3s;
        }
        .voucher-card:hover {
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }
        .voucher-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        .voucher-title h3 {
            font-size: 32px;
            font-weight: 800;
            letter-spacing: -1px;
            margin-bottom: 5px;
        }
        .voucher-title p {
            font-size: 12px;
            color: #666;
            line-height: 1.5;
        }
        .voucher-icon {
            width: 40px; height: 40px;
            background: #111;
            color: white;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 20px;
        }
        .voucher-divider {
            border-top: 2px dashed #EAEAEA;
            margin: 20px 0;
        }
        .voucher-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #F9F9F9;
            padding: 12px 15px;
            border-radius: 4px;
        }
        .voucher-code {
            font-family: monospace;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: 1px;
        }
        .btn-copy {
            font-size: 12px;
            font-weight: 600;
            color: #111;
            cursor: pointer;
            border: none;
            background: none;
            text-transform: uppercase;
        }

        /* --- SPRING COLLECTION BANNER --- */
        .spring-banner {
            position: relative;
            height: 60vh;
            background-image: url('https://images.unsplash.com/photo-1483985988355-763728e1935b?q=80&w=2070');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            display: flex;
            align-items: center;
            padding: 0 5%;
        }
        .spring-banner::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(17, 17, 17, 0.7);
        }
        .spring-content {
            position: relative;
            z-index: 1;
            color: white;
            max-width: 600px;
        }
        .spring-content h2 {
            font-size: 56px;
            font-weight: 800;
            line-height: 1;
            margin-bottom: 20px;
            letter-spacing: -1.5px;
        }
        .spring-content p {
            font-size: 16px;
            color: rgba(255,255,255,0.8);
            margin-bottom: 30px;
            line-height: 1.6;
        }

        /* --- WHY TANKEN --- */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 40px;
        }
        .feature-card {
            padding: 20px 0;
        }
        .feature-icon {
            width: 45px; height: 45px;
            background: #F5F5F5;
            color: #111;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 22px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .feature-card h3 {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 10px;
        }
        .feature-card p {
            color: #666;
            font-size: 14px;
            line-height: 1.6;
        }

        /* --- JOIN COMMUNITY --- */
        .community-section {
            background-color: #111111;
            color: white;
            padding: 80px 5%;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
        }
        .comm-left h2 {
            font-size: 48px;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 20px;
            letter-spacing: -1px;
        }
        .comm-left p {
            font-size: 15px;
            color: rgba(255,255,255,0.7);
            margin-bottom: 30px;
            line-height: 1.6;
        }
        .comm-list {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 40px;
        }
        .comm-list li {
            font-size: 14px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .comm-list li i { color: #10B981; font-size: 18px; }
        
        .comm-right {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1px;
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        .comm-stat {
            background: #111;
            padding: 40px 30px;
        }
        .comm-stat h3 {
            font-size: 36px;
            font-weight: 800;
            margin-bottom: 5px;
        }
        .comm-stat p {
            font-size: 12px;
            color: rgba(255,255,255,0.5);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* --- FOOTER --- */
        footer {
            background-color: #0A0A0A;
            color: white;
            padding: 80px 5% 30px;
        }
        .footer-top {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 40px;
            margin-bottom: 60px;
        }
        .footer-brand h2 {
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 20px;
            letter-spacing: -0.5px;
        }
        .footer-brand p {
            color: rgba(255,255,255,0.5);
            font-size: 13px;
            line-height: 1.6;
            margin-bottom: 25px;
            max-width: 300px;
        }
        .footer-contact {
            color: rgba(255,255,255,0.6);
            font-size: 13px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .footer-contact div {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .footer-links h4 {
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .footer-links ul {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .footer-links a {
            color: rgba(255,255,255,0.5);
            font-size: 13px;
            transition: color 0.3s;
        }
        .footer-links a:hover { color: white; }
        
        .footer-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid rgba(255,255,255,0.1);
            padding-top: 30px;
            font-size: 12px;
            color: rgba(255,255,255,0.4);
        }
        .footer-legal {
            display: flex;
            gap: 20px;
        }
        .footer-legal a:hover { color: white; }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .hero-content h1 { font-size: 64px; }
            .category-split, .community-section { grid-template-columns: 1fr; height: auto; }
            .cat-box { height: 300px; }
            .products-grid, .stats-grid, .vouchers-grid, .features-grid { grid-template-columns: repeat(2, 1fr); }
            .footer-top { grid-template-columns: 1fr 1fr; }
        }
        @media (max-width: 768px) {
            .nav-links { display: none; }
            .hero-content h1 { font-size: 48px; }
            .products-grid, .stats-grid, .vouchers-grid, .features-grid, .footer-top { grid-template-columns: 1fr; }
            .footer-bottom { flex-direction: column; gap: 15px; text-align: center; }
        }
    </style>
</head>
<body>

    <nav class="navbar">
        <div class="nav-brand">
            <i class="ph-fill ph-triangle"></i>
            TANKEN
        </div>
        <div class="nav-links">
            <a href="#">Shop</a>
            <a href="#">Women</a>
            <a href="#">Men</a>
            <a href="#">Help</a>
        </div>
        <div class="nav-icons">
            <i class="ph ph-magnifying-glass"></i>
            <i class="ph ph-heart"></i> <i class="ph ph-shopping-cart"></i>
            <i class="ph ph-user"></i>
        </div>
    </nav>

    <section class="hero">
        <div class="hero-content">
            <h1>Define<br>Your<br>Motion.</h1>
            <div class="hero-buttons">
                <a href="#" class="btn-solid-white">Shop Collection</a>
                <a href="#" class="btn-outline-white">Explore Categories</a>
            </div>
        </div>
        <div class="marquee-container">
            <div class="marquee-content">
                <span>Premium Quality</span>
                <span>•</span>
                <span>Engineered Comfort</span>
                <span>•</span>
                <span>Modern Fit</span>
                <span>•</span>
                <span>Durability</span>
                <span>•</span>
                <span>Premium Quality</span>
                <span>•</span>
                <span>Engineered Comfort</span>
                <span>•</span>
                <span>Modern Fit</span>
                <span>•</span>
                <span>Durability</span>
            </div>
        </div>
    </section>

    <section class="category-split">
        <div class="cat-box" style="background-image: url('https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?q=80&w=1920');">
            <div class="cat-box-content">
                <h2>Women</h2>
                <a href="#">Shop Collection <i class="ph ph-arrow-right"></i></a>
            </div>
        </div>
        <div class="cat-box" style="background-image: url('https://images.unsplash.com/photo-1542272454315-4c01d7abdf4a?q=80&w=2070');">
            <div class="cat-box-content">
                <h2>Men</h2>
                <a href="#">Shop Collection <i class="ph ph-arrow-right"></i></a>
            </div>
        </div>
    </section>

    <section class="section-container">
        <div class="section-header">
            <div class="section-title">
                <h4>Shop</h4>
                <h2>Featured Pieces</h2>
            </div>
            <a href="#" class="view-all-link">View All <i class="ph ph-caret-right"></i></a>
        </div>
        
        <div class="products-grid">
            <div class="product-card">
                <div class="product-img">
                    <img src="https://images.unsplash.com/photo-1624378439575-d1ead6bb17f0?q=80&w=1974" alt="Essential Cargo Shorts">
                </div>
                <h3 class="product-title">Essential Cargo Shorts</h3>
                <p class="product-cat">Casual</p>
                <div class="product-bottom">
                    <span class="product-price">$69.99</span>
                    <span class="product-rating"><i class="ph-fill ph-star"></i> 4.8</span>
                </div>
            </div>
            <div class="product-card">
                <div class="product-img">
                    <img src="https://images.unsplash.com/photo-1541099649105-f69ad21f3246?q=80&w=1974" alt="Active Flow Shorts">
                </div>
                <h3 class="product-title">Active Flow Shorts</h3>
                <p class="product-cat">Sport</p>
                <div class="product-bottom">
                    <span class="product-price">$54.99</span>
                    <span class="product-rating"><i class="ph-fill ph-star"></i> 4.9</span>
                </div>
            </div>
            <div class="product-card">
                <div class="product-img">
                    <img src="https://images.unsplash.com/photo-1594938298596-eb5fd3f510fd?q=80&w=1964" alt="Lounge Sweatshorts">
                </div>
                <h3 class="product-title">Lounge Sweatshorts</h3>
                <p class="product-cat">Casual</p>
                <div class="product-bottom">
                    <span class="product-price">$49.99</span>
                    <span class="product-rating"><i class="ph-fill ph-star"></i> 4.7</span>
                </div>
            </div>
            <div class="product-card">
                <div class="product-img">
                    <img src="https://images.unsplash.com/photo-1506629082955-511b1aa562c8?q=80&w=1974" alt="Tailored Chino Shorts">
                </div>
                <h3 class="product-title">Tailored Chino Shorts</h3>
                <p class="product-cat">Formal</p>
                <div class="product-bottom">
                    <span class="product-price">$79.99</span>
                    <span class="product-rating"><i class="ph-fill ph-star"></i> 4.6</span>
                </div>
            </div>
        </div>
    </section>

    <section class="stats-grid">
        <div class="stat-item">
            <h3>50K+</h3>
            <p>Happy Customers</p>
        </div>
        <div class="stat-item">
            <h3>4.9</h3>
            <p>Average Rating</p>
        </div>
        <div class="stat-item">
            <h3>100%</h3>
            <p>Premium Quality</p>
        </div>
        <div class="stat-item">
            <h3>24/7</h3>
            <p>Customer Support</p>
        </div>
    </section>

    <section class="section-container">
        <div class="section-header">
            <div class="section-title">
                <h4>Promotions</h4>
                <h2>Exclusive Vouchers</h2>
            </div>
            <a href="#" class="view-all-link" style="color: #888;">Log in to claim <i class="ph ph-caret-right"></i></a>
        </div>

        <div class="vouchers-grid">
            <div class="voucher-card">
                <div class="voucher-top">
                    <div class="voucher-title">
                        <h3>20% OFF</h3>
                        <p>For all casual items. Min spend $100. Valid until Dec 31.</p>
                    </div>
                    <div class="voucher-icon"><i class="ph ph-arrow-down-right"></i></div>
                </div>
                <div class="voucher-divider"></div>
                <div class="voucher-bottom">
                    <span class="voucher-code">CASUAL20</span>
                    <button class="btn-copy">Copy Code</button>
                </div>
            </div>
            <div class="voucher-card">
                <div class="voucher-top">
                    <div class="voucher-title">
                        <h3>FREE SHIP</h3>
                        <p>Free shipping on all orders over $150. Nationwide.</p>
                    </div>
                    <div class="voucher-icon"><i class="ph ph-arrow-down-right"></i></div>
                </div>
                <div class="voucher-divider"></div>
                <div class="voucher-bottom">
                    <span class="voucher-code">FREESHIP150</span>
                    <button class="btn-copy">Copy Code</button>
                </div>
            </div>
            <div class="voucher-card">
                <div class="voucher-top">
                    <div class="voucher-title">
                        <h3>30% OFF</h3>
                        <p>First time user only. No minimum spend required.</p>
                    </div>
                    <div class="voucher-icon"><i class="ph ph-arrow-down-right"></i></div>
                </div>
                <div class="voucher-divider"></div>
                <div class="voucher-bottom">
                    <span class="voucher-code">WELCOME30</span>
                    <button class="btn-copy">Copy Code</button>
                </div>
            </div>
        </div>
    </section>

    <section class="spring-banner">
        <div class="spring-content">
            <h2>Spring<br>Collection<br>2026</h2>
            <p>New styles, same premium quality. Discover what's new for the season ahead.</p>
            <a href="#" class="btn-solid-white">Shop Collection <i class="ph ph-arrow-right"></i></a>
        </div>
    </section>

    <section class="section-container">
        <div class="section-header">
            <div class="section-title">
                <h4>Features</h4>
                <h2>Why TANKEN</h2>
            </div>
        </div>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon"><i class="ph ph-medal"></i></div>
                <h3>Premium Materials</h3>
                <p>Carefully selected fabrics engineered for durability and comfort. Every piece is crafted from the finest materials available.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="ph ph-scissors"></i></div>
                <h3>Engineered Comfort</h3>
                <p>Advanced construction techniques for maximum mobility. Move freely without restrictions, all day long.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="ph ph-shield-check"></i></div>
                <h3>Modern Fit Technology</h3>
                <p>Precision-tailored designs that adapt to your movement. Perfect fit that looks great and feels even better.</p>
            </div>
        </div>
    </section>

    <section class="community-section">
        <div class="comm-left">
            <h2>Join the<br>TANKEN<br>Community</h2>
            <p>Become part of a global movement of individuals who define their own motion. Get exclusive access to new drops, special discounts, and community events.</p>
            
            <ul class="comm-list">
                <li><i class="ph-fill ph-check-circle"></i> Premium Quality</li>
                <li><i class="ph-fill ph-check-circle"></i> Early Access</li>
                <li><i class="ph-fill ph-check-circle"></i> Special Events</li>
                <li><i class="ph-fill ph-check-circle"></i> VIP Support</li>
            </ul>

            <a href="#" class="btn-solid-white">Join For Free <i class="ph ph-arrow-right"></i></a>
        </div>
        <div class="comm-right">
            <div class="comm-stat">
                <h3>100+</h3>
                <p>Retail Partners</p>
            </div>
            <div class="comm-stat">
                <h3>50+</h3>
                <p>Countries Served</p>
            </div>
            <div class="comm-stat">
                <h3>$5M+</h3>
                <p>Community Value</p>
            </div>
            <div class="comm-stat">
                <h3>98%</h3>
                <p>Satisfaction Rate</p>
            </div>
        </div>
    </section>

    <footer>
        <div class="footer-top">
            <div class="footer-brand">
                <h2><i class="ph-fill ph-triangle"></i> TANKEN</h2>
                <p>Move with style. Premium athletic and casual wear designed for the modern lifestyle. Quality you can feel, style you can see.</p>
                <div class="footer-contact">
                    <div><i class="ph ph-map-pin"></i> 123 Fashion Street, New York, NY 10001</div>
                    <div><i class="ph ph-phone"></i> 1-800-TANKEN (826536)</div>
                    <div><i class="ph ph-envelope-simple"></i> support@tanken.com</div>
                </div>
            </div>
            <div class="footer-links">
                <h4>Shop</h4>
                <ul>
                    <li><a href="#">All Products</a></li>
                    <li><a href="#">Women</a></li>
                    <li><a href="#">Men</a></li>
                    <li><a href="#">Unisex</a></li>
                </ul>
            </div>
            <div class="footer-links">
                <h4>Help</h4>
                <ul>
                    <li><a href="#">My Orders</a></li>
                    <li><a href="#">Shipping Info</a></li>
                    <li><a href="#">Returns</a></li>
                    <li><a href="#">Size Guide</a></li>
                    <li><a href="#">FAQ</a></li>
                </ul>
            </div>
            <div class="footer-links">
                <h4>Company</h4>
                <ul>
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Contact</a></li>
                    <li><a href="#">Careers</a></li>
                    <li><a href="#">Sustainability</a></li>
                </ul>
            </div>
        </div>
        
        <div class="footer-bottom">
            <div>© 2026 TANKEN. All rights reserved.</div>
            <div class="footer-legal">
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
                <a href="#">Cookie Policy</a>
            </div>
        </div>
    </footer>

</body>
</html>