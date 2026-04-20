<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TANKEN | Define Your Motion</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        tanken: {
                            black: '#111111',
                            dark: '#1C1C1C',
                            gray: '#F9F9F9',
                            border: '#EAEAEA',
                            text: '#666666'
                        }
                    }
                }
            }
        }
    </script>

    <style>
        /* Custom Voucher Styling */
        .voucher-card {
            position: relative;
            background: white;
            border: 1px solid #EAEAEA;
        }
        /* Left Cutout */
        .voucher-card::before {
            content: '';
            position: absolute;
            left: -13px;
            top: 55%;
            transform: translateY(-50%);
            width: 24px;
            height: 24px;
            background-color: white; /* Matches section bg */
            border-right: 1px solid #EAEAEA;
            border-radius: 50%;
            z-index: 10;
        }
        /* Right Cutout */
        .voucher-card::after {
            content: '';
            position: absolute;
            right: -13px;
            top: 55%;
            transform: translateY(-50%);
            width: 24px;
            height: 24px;
            background-color: white; /* Matches section bg */
            border-left: 1px solid #EAEAEA;
            border-radius: 50%;
            z-index: 10;
        }
        
        /* Marquee Animation */
        @keyframes marquee {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        .animate-marquee {
            display: inline-flex;
            white-space: nowrap;
            animation: marquee 40s linear infinite;
        }
    </style>
</head>
<body class="bg-white text-tanken-black antialiased">

    <nav class="sticky top-0 z-50 bg-white border-b border-tanken-border">
        <div class="max-w-[1440px] mx-auto px-6 lg:px-12 h-[72px] flex items-center justify-between">
            <a href="{{ route('pelanggan.beranda') }}" class="flex items-center gap-2">
                <i class="ph-fill ph-triangle text-3xl"></i>
                <span class="text-[22px] font-black tracking-tighter">TANKEN</span>
            </a>

            <div class="hidden md:flex items-center gap-10">
                <a href="{{ route('pelanggan.shop') }}" class="text-[13px] font-bold text-black hover:text-gray-500 transition-colors">Shop</a>
                <a href="{{ route('pelanggan.shop') }}" class="text-[13px] font-bold text-black hover:text-gray-500 transition-colors">Women</a>
                <a href="{{ route('pelanggan.shop') }}" class="text-[13px] font-bold text-black hover:text-gray-500 transition-colors">Men</a>
                <a href="#" class="text-[13px] font-bold text-black hover:text-gray-500 transition-colors">Help</a>
            </div>

            <div class="flex items-center gap-6">
                <button class="hover:opacity-60 transition-opacity"><i class="ph ph-magnifying-glass text-[22px]"></i></button>
                <a href="{{ route('pelanggan.wishlist') }}" class="hover:opacity-60 transition-opacity"><i class="ph ph-heart text-[22px]"></i></a>
                <a href="{{ route('pelanggan.keranjang') }}" class="hover:opacity-60 transition-opacity"><i class="ph ph-shopping-cart text-[22px]"></i></a>
                <a href="{{ route('pelanggan.profil') }}" class="hover:opacity-60 transition-opacity"><i class="ph ph-user text-[22px]"></i></a>
            </div>
        </div>
    </nav>

    <section class="relative h-[85vh] flex flex-col justify-center">
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1556821840-3a63f95609a7?q=80&w=2070');">
            <div class="absolute inset-0 bg-black/50"></div> </div>
        
        <div class="relative z-10 max-w-[1440px] mx-auto px-6 lg:px-12 w-full mt-[-40px]">
            <p class="text-white text-[10px] font-bold uppercase tracking-[0.2em] mb-4">SPRING COLLECTION 2026</p>
            <h1 class="text-white text-6xl md:text-[110px] font-black leading-[0.85] tracking-tighter uppercase mb-10">
                DEFINE<br>YOUR<br>MOTION.
            </h1>
            <div class="flex flex-wrap items-center gap-4">
                <a href="{{ route('pelanggan.shop') }}" class="bg-white text-black px-8 py-3.5 text-[11px] font-black uppercase tracking-widest flex items-center gap-3 hover:bg-gray-200 transition">
                    SHOP NOW <i class="ph ph-arrow-right text-sm"></i>
                </a>
                <a href="{{ route('pelanggan.shop') }}" class="border border-white/30 text-white px-8 py-3.5 text-[11px] font-bold uppercase tracking-widest flex items-center gap-3 hover:bg-white/10 transition">
                    WOMEN <i class="ph ph-arrow-up-right text-sm"></i>
                </a>
                <a href="{{ route('pelanggan.shop') }}" class="border border-white/30 text-white px-8 py-3.5 text-[11px] font-bold uppercase tracking-widest flex items-center gap-3 hover:bg-white/10 transition">
                    MEN <i class="ph ph-arrow-up-right text-sm"></i>
                </a>
            </div>
        </div>

        <div class="absolute bottom-0 w-full bg-black/80 text-white/70 py-3 overflow-hidden backdrop-blur-sm border-t border-white/10">
            <div class="animate-marquee text-[10px] font-bold uppercase tracking-[0.2em]">
                @for ($i = 0; $i < 4; $i++)
                    <span class="mx-8">FREE SHIPPING OVER $100</span> <span class="text-white/30">—</span>
                    <span class="mx-8">PREMIUM QUALITY</span> <span class="text-white/30">—</span>
                    <span class="mx-8">MOVE WITH STYLE</span> <span class="text-white/30">—</span>
                    <span class="mx-8">ENGINEERED COMFORT</span> <span class="text-white/30">—</span>
                    <span class="mx-8">NEW COLLECTION</span> <span class="text-white/30">—</span>
                    <span class="mx-8">SPRING 2026</span> <span class="text-white/30">—</span>
                @endfor
            </div>
        </div>
    </section>

    <section class="grid grid-cols-1 md:grid-cols-2">
        <div class="relative h-[600px] overflow-hidden group">
            <img src="https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?q=80&w=1920" class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105" alt="Women">
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent flex flex-col justify-end p-12">
                <p class="text-white/80 text-[10px] font-bold uppercase tracking-[0.2em] mb-2">EFFORTLESS. POWERFUL. YOU.</p>
                <h2 class="text-white text-[64px] font-black tracking-tighter leading-none mb-4">Women</h2>
                <a href="{{ route('pelanggan.shop') }}" class="text-white text-[11px] font-bold uppercase tracking-widest flex items-center gap-2 group-hover:gap-3 transition-all">
                    SHOP COLLECTION <i class="ph ph-arrow-right"></i>
                </a>
            </div>
        </div>
        <div class="relative h-[600px] overflow-hidden group">
            <img src="https://images.unsplash.com/photo-1542272454315-4c01d7abdf4a?q=80&w=2070" class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105" alt="Men">
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent flex flex-col justify-end p-12">
                <p class="text-white/80 text-[10px] font-bold uppercase tracking-[0.2em] mb-2">PRECISION. MOVEMENT. STYLE.</p>
                <h2 class="text-white text-[64px] font-black tracking-tighter leading-none mb-4">Men</h2>
                <a href="{{ route('pelanggan.shop') }}" class="text-white text-[11px] font-bold uppercase tracking-widest flex items-center gap-2 group-hover:gap-3 transition-all">
                    SHOP COLLECTION <i class="ph ph-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>

    <section class="max-w-[1440px] mx-auto px-6 lg:px-12 py-24">
        <div class="flex justify-between items-end mb-12">
            <div>
                <p class="text-[10px] font-bold text-tanken-text uppercase tracking-[0.2em] mb-2">NEW IN</p>
                <h2 class="text-[40px] font-black tracking-tighter leading-none">Featured Pieces</h2>
            </div>
            <a href="{{ route('pelanggan.shop') }}" class="text-[11px] font-bold uppercase tracking-widest flex items-center gap-2 border-b border-black pb-1 hover:text-gray-500 hover:border-gray-500 transition-colors">
                VIEW ALL <i class="ph ph-arrow-right"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <div class="group cursor-pointer">
                <div class="bg-[#F6F6F6] aspect-[3/4] mb-4 overflow-hidden rounded-sm relative">
                    <img src="https://images.unsplash.com/photo-1541099649105-f69ad21f3246?q=80&w=1974" class="w-full h-full object-cover mix-blend-multiply transition-transform duration-700 group-hover:scale-105" alt="Outdoor Nylon Taslan Olive">
                </div>
                <h3 class="font-bold text-[14px] text-black">Outdoor Nylon Taslan Olive</h3>
                <p class="text-[11px] text-tanken-text uppercase tracking-wider mb-3">OUTDOOR</p>
                <div class="flex justify-between items-center">
                    <span class="font-bold text-[15px]">Rp 159.000</span>
                    <div class="flex items-center gap-1 text-[12px] font-bold">
                        <i class="ph-fill ph-star text-[#F5B000]"></i> 4.8
                    </div>
                </div>
            </div>

            <div class="group cursor-pointer">
                <div class="bg-[#F6F6F6] aspect-[3/4] mb-4 overflow-hidden rounded-sm relative">
                    <img src="https://images.unsplash.com/photo-1594938298596-eb5fd3f510fd?q=80&w=1964" class="w-full h-full object-cover mix-blend-multiply transition-transform duration-700 group-hover:scale-105" alt="Nylon Crinkle Shortpants Sora">
                </div>
                <h3 class="font-bold text-[14px] text-black">Nylon Crinkle Shortpants Sora</h3>
                <p class="text-[11px] text-tanken-text uppercase tracking-wider mb-3">CASUAL</p>
                <div class="flex justify-between items-center">
                    <span class="font-bold text-[15px]">Rp 89.000</span>
                    <div class="flex items-center gap-1 text-[12px] font-bold">
                        <i class="ph-fill ph-star text-[#F5B000]"></i> 4.9
                    </div>
                </div>
            </div>

            <div class="group cursor-pointer">
                <div class="bg-[#F6F6F6] aspect-[3/4] mb-4 overflow-hidden rounded-sm relative">
                    <img src="https://images.unsplash.com/photo-1506629082955-511b1aa562c8?q=80&w=1974" class="w-full h-full object-cover mix-blend-multiply transition-transform duration-700 group-hover:scale-105" alt="Cargo Shortpants Meru Petrol">
                </div>
                <h3 class="font-bold text-[14px] text-black">Cargo Shortpants Meru Petrol</h3>
                <p class="text-[11px] text-tanken-text uppercase tracking-wider mb-3">CARGO</p>
                <div class="flex justify-between items-center">
                    <span class="font-bold text-[15px]">Rp 159.000</span>
                    <div class="flex items-center gap-1 text-[12px] font-bold">
                        <i class="ph-fill ph-star text-[#F5B000]"></i> 4.7
                    </div>
                </div>
            </div>

            <div class="group cursor-pointer">
                <div class="bg-[#F6F6F6] aspect-[3/4] mb-4 overflow-hidden rounded-sm relative">
                    <img src="https://images.unsplash.com/photo-1624378439575-d1ead6bb17f0?q=80&w=1974" class="w-full h-full object-cover mix-blend-multiply transition-transform duration-700 group-hover:scale-105" alt="Cargo Shortpants Yama black">
                </div>
                <h3 class="font-bold text-[14px] text-black">Cargo Shortpants Yama black</h3>
                <p class="text-[11px] text-tanken-text uppercase tracking-wider mb-3">CARGO</p>
                <div class="flex justify-between items-center">
                    <span class="font-bold text-[15px]">Rp 159.000</span>
                    <div class="flex items-center gap-1 text-[12px] font-bold">
                        <i class="ph-fill ph-star text-[#F5B000]"></i> 4.6
                    </div>
                </div>
            </div>

        </div>
    </section>

    <section class="border-y border-tanken-border py-16">
        <div class="max-w-[1440px] mx-auto px-6 lg:px-12 grid grid-cols-2 md:grid-cols-4 gap-8 text-center divide-x divide-tanken-border">
            <div>
                <h3 class="text-[40px] font-black tracking-tighter mb-1">50K+</h3>
                <p class="text-[10px] font-bold text-tanken-text uppercase tracking-widest">HAPPY CUSTOMERS</p>
            </div>
            <div>
                <h3 class="text-[40px] font-black tracking-tighter mb-1">4.9</h3>
                <p class="text-[10px] font-bold text-tanken-text uppercase tracking-widest">AVERAGE RATING</p>
            </div>
            <div>
                <h3 class="text-[40px] font-black tracking-tighter mb-1">100%</h3>
                <p class="text-[10px] font-bold text-tanken-text uppercase tracking-widest">PREMIUM QUALITY</p>
            </div>
            <div>
                <h3 class="text-[40px] font-black tracking-tighter mb-1">24/7</h3>
                <p class="text-[10px] font-bold text-tanken-text uppercase tracking-widest">CUSTOMER SUPPORT</p>
            </div>
        </div>
    </section>

    <section class="max-w-[1440px] mx-auto px-6 lg:px-12 py-24 bg-white">
        <div class="flex justify-between items-end mb-12">
            <div>
                <p class="text-[10px] font-bold text-tanken-text uppercase tracking-[0.2em] mb-2">LIMITED OFFERS</p>
                <h2 class="text-[40px] font-black tracking-tighter leading-none">Exclusive Vouchers</h2>
            </div>
            <p class="text-[12px] text-tanken-text">Apply at checkout for instant savings</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="voucher-card p-8 flex flex-col h-full">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <p class="text-[10px] font-bold text-tanken-text uppercase tracking-widest mb-1">WELCOME OFFER</p>
                        <h3 class="text-[40px] font-black tracking-tighter leading-none">20% OFF</h3>
                    </div>
                    <div class="w-10 h-10 bg-black rounded-full flex justify-center items-center text-white">
                        <i class="ph ph-arrow-down-right text-lg"></i>
                    </div>
                </div>
                <p class="text-[13px] text-tanken-text mb-8 w-5/6">For new customers on their first order above $50.</p>
                
                <div class="border-t border-dashed border-tanken-border w-full absolute left-0 top-[55%]"></div>
                
                <div class="mt-auto pt-6">
                    <p class="text-[9px] font-bold text-tanken-text uppercase tracking-widest mb-2">VOUCHER CODE</p>
                    <div class="bg-[#F9F9F9] border border-tanken-border rounded flex justify-between items-center p-3 mb-3">
                        <span class="font-bold text-[14px] tracking-[0.2em]">WELCOME20</span>
                        <button class="text-[11px] font-bold uppercase flex items-center gap-1 hover:text-gray-500"><i class="ph ph-copy"></i> COPY</button>
                    </div>
                    <p class="text-[10px] text-tanken-text">Valid for first-time purchases only. Min. order $50.</p>
                </div>
            </div>

            <div class="voucher-card p-8 flex flex-col h-full">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <p class="text-[10px] font-bold text-tanken-text uppercase tracking-widest mb-1">FREE DELIVERY</p>
                        <h3 class="text-[40px] font-black tracking-tighter leading-none">FREE SHIP</h3>
                    </div>
                    <div class="w-10 h-10 bg-black rounded-full flex justify-center items-center text-white">
                        <i class="ph ph-arrow-down-right text-lg"></i>
                    </div>
                </div>
                <p class="text-[13px] text-tanken-text mb-8 w-5/6">Free shipping on all orders over $100, nationwide.</p>
                
                <div class="border-t border-dashed border-tanken-border w-full absolute left-0 top-[55%]"></div>
                
                <div class="mt-auto pt-6">
                    <p class="text-[9px] font-bold text-tanken-text uppercase tracking-widest mb-2">VOUCHER CODE</p>
                    <div class="bg-[#F9F9F9] border border-tanken-border rounded flex justify-between items-center p-3 mb-3">
                        <span class="font-bold text-[14px] tracking-[0.2em]">FREESHIP100</span>
                        <button class="text-[11px] font-bold uppercase flex items-center gap-1 hover:text-gray-500"><i class="ph ph-copy"></i> COPY</button>
                    </div>
                    <p class="text-[10px] text-tanken-text">Valid on all delivery zones. No expiry date.</p>
                </div>
            </div>

            <div class="voucher-card p-8 flex flex-col h-full">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <p class="text-[10px] font-bold text-tanken-text uppercase tracking-widest mb-1">SPRING SALE</p>
                        <h3 class="text-[40px] font-black tracking-tighter leading-none">30% OFF</h3>
                    </div>
                    <div class="w-10 h-10 bg-black rounded-full flex justify-center items-center text-white">
                        <i class="ph ph-arrow-down-right text-lg"></i>
                    </div>
                </div>
                <p class="text-[13px] text-tanken-text mb-8 w-5/6">Valid on selected Spring Collection 2026 items.</p>
                
                <div class="border-t border-dashed border-tanken-border w-full absolute left-0 top-[55%]"></div>
                
                <div class="mt-auto pt-6">
                    <p class="text-[9px] font-bold text-tanken-text uppercase tracking-widest mb-2">VOUCHER CODE</p>
                    <div class="bg-[#F9F9F9] border border-tanken-border rounded flex justify-between items-center p-3 mb-3">
                        <span class="font-bold text-[14px] tracking-[0.2em]">SPRING30</span>
                        <button class="text-[11px] font-bold uppercase flex items-center gap-1 hover:text-gray-500"><i class="ph ph-copy"></i> COPY</button>
                    </div>
                    <p class="text-[10px] text-tanken-text">Applies to selected items only. Limited time offer.</p>
                </div>
            </div>

        </div>
    </section>

    <section class="relative h-[600px] flex items-center">
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1483985988355-763728e1935b?q=80&w=2070');">
            <div class="absolute inset-0 bg-[#111111]/80"></div>
        </div>
        <div class="relative z-10 max-w-[1440px] mx-auto px-6 lg:px-12 w-full">
            <p class="text-white/60 text-[10px] font-bold uppercase tracking-[0.2em] mb-4">LIMITED EDITION</p>
            <h2 class="text-white text-[64px] font-black tracking-tighter leading-[1] mb-6">
                Spring<br>Collection<br>2026
            </h2>
            <p class="text-white/70 text-[14px] max-w-sm mb-10 leading-relaxed">
                New styles, same premium quality. Up to 30% off select pieces for a limited time.
            </p>
            <a href="{{ route('pelanggan.shop') }}" class="bg-white text-black px-8 py-3.5 text-[11px] font-black uppercase tracking-widest inline-flex items-center gap-3 hover:bg-gray-200 transition">
                SHOP COLLECTION <i class="ph ph-arrow-right text-sm"></i>
            </a>
        </div>
    </section>

    <section class="max-w-[1440px] mx-auto px-6 lg:px-12 py-24">
        <div class="mb-16">
            <p class="text-[10px] font-bold text-tanken-text uppercase tracking-[0.2em] mb-2">OUR PROMISE</p>
            <h2 class="text-[40px] font-black tracking-tighter leading-none">Why TANKEN</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
            <div>
                <div class="w-12 h-12 bg-tanken-black text-white flex items-center justify-center rounded mb-6">
                    <i class="ph ph-sparkle text-[24px]"></i>
                </div>
                <h3 class="text-[16px] font-bold mb-3">Premium Materials</h3>
                <p class="text-[13px] text-tanken-text leading-relaxed">Carefully selected fabrics engineered for durability and comfort, crafted from the finest materials available.</p>
            </div>
            <div>
                <div class="w-12 h-12 bg-tanken-black text-white flex items-center justify-center rounded mb-6">
                    <i class="ph ph-lightning text-[24px]"></i>
                </div>
                <h3 class="text-[16px] font-bold mb-3">Engineered Comfort</h3>
                <p class="text-[13px] text-tanken-text leading-relaxed">Advanced construction techniques for maximum mobility. Move freely without restrictions, all day long.</p>
            </div>
            <div>
                <div class="w-12 h-12 bg-tanken-black text-white flex items-center justify-center rounded mb-6">
                    <i class="ph ph-shield-check text-[24px]"></i>
                </div>
                <h3 class="text-[16px] font-bold mb-3">Modern Fit Technology</h3>
                <p class="text-[13px] text-tanken-text leading-relaxed">Precision-tailored designs that adapt to your movement. Perfect fit that looks great and feels even better.</p>
            </div>
        </div>
    </section>

    <section class="bg-[#1A1A1A] py-24 border-t border-[#2A2A2A]">
        <div class="max-w-[1440px] mx-auto px-6 lg:px-12 grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            
            <div class="text-white">
                <p class="text-[10px] font-bold text-white/50 uppercase tracking-[0.2em] mb-4">GROW WITH US</p>
                <h2 class="text-[48px] font-black tracking-tighter leading-[1.1] mb-6">Join the<br>TANKEN<br>Community</h2>
                <p class="text-[14px] text-white/70 leading-relaxed mb-10 max-w-md">
                    We're always looking for passionate partners to grow together. Whether you're a retailer, influencer, distributor, or creative collaborator — let's build something amazing.
                </p>
                
                <div class="grid grid-cols-2 gap-y-6 gap-x-4 mb-10">
                    <div>
                        <h4 class="text-[13px] font-bold mb-1 flex items-center gap-2"><div class="w-1 h-1 bg-white rounded-full"></div> Premium Quality</h4>
                        <p class="text-[11px] text-white/50 pl-3">Industry-leading products</p>
                    </div>
                    <div>
                        <h4 class="text-[13px] font-bold mb-1 flex items-center gap-2"><div class="w-1 h-1 bg-white rounded-full"></div> Fast Growth</h4>
                        <p class="text-[11px] text-white/50 pl-3">Rapid market expansion</p>
                    </div>
                    <div>
                        <h4 class="text-[13px] font-bold mb-1 flex items-center gap-2"><div class="w-1 h-1 bg-white rounded-full"></div> Competitive Terms</h4>
                        <p class="text-[11px] text-white/50 pl-3">Attractive margins</p>
                    </div>
                    <div>
                        <h4 class="text-[13px] font-bold mb-1 flex items-center gap-2"><div class="w-1 h-1 bg-white rounded-full"></div> Full Support</h4>
                        <p class="text-[11px] text-white/50 pl-3">Dedicated team assistance</p>
                    </div>
                </div>

                <a href="{{ route('pelanggan.mitra.pengajuan') }}" class="bg-white text-black px-8 py-3.5 text-[11px] font-black uppercase tracking-widest inline-flex items-center gap-3 hover:bg-gray-200 transition">
                    APPLY FOR PARTNERSHIP <i class="ph ph-arrow-right text-sm"></i>
                </a>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="border border-white/10 p-10 flex flex-col justify-center">
                    <h3 class="text-[40px] font-black text-white tracking-tighter mb-2">100+</h3>
                    <p class="text-[10px] font-bold text-white/50 uppercase tracking-[0.2em]">ACTIVE PARTNERS</p>
                </div>
                <div class="border border-white/10 p-10 flex flex-col justify-center">
                    <h3 class="text-[40px] font-black text-white tracking-tighter mb-2">50+</h3>
                    <p class="text-[10px] font-bold text-white/50 uppercase tracking-[0.2em]">COUNTRIES WORLDWIDE</p>
                </div>
                <div class="border border-white/10 p-10 flex flex-col justify-center">
                    <h3 class="text-[40px] font-black text-white tracking-tighter mb-2">$5M+</h3>
                    <p class="text-[10px] font-bold text-white/50 uppercase tracking-[0.2em]">REVENUE GENERATED</p>
                </div>
                <div class="border border-white/10 p-10 flex flex-col justify-center">
                    <h3 class="text-[40px] font-black text-white tracking-tighter mb-2">98%</h3>
                    <p class="text-[10px] font-bold text-white/50 uppercase tracking-[0.2em]">PARTNER SATISFACTION</p>
                </div>
            </div>

        </div>
    </section>

    <footer class="bg-tanken-black text-white pt-24 pb-8">
        <div class="max-w-[1440px] mx-auto px-6 lg:px-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-20">
                
                <div class="md:col-span-1">
                    <a href="{{ route('pelanggan.beranda') }}" class="flex items-center gap-2 mb-6">
                        <i class="ph-fill ph-triangle text-[28px]"></i>
                        <span class="text-[24px] font-black tracking-tighter">TANKEN</span>
                    </a>
                    <p class="text-[13px] text-white/60 leading-relaxed mb-8 max-w-[280px]">
                        Move with style. Premium athletic and casual pants designed for the modern lifestyle. Quality you can feel, style you can see.
                    </p>
                    <div class="flex flex-col gap-3 text-[13px] text-white/60">
                        <div class="flex items-center gap-3"><i class="ph ph-map-pin text-lg"></i> 123 Fashion Street, New York, NY 10001</div>
                        <div class="flex items-center gap-3"><i class="ph ph-phone text-lg"></i> 1-800-TANKEN (826536)</div>
                        <div class="flex items-center gap-3"><i class="ph ph-envelope-simple text-lg"></i> support@tanken.com</div>
                    </div>
                </div>

                <div>
                    <h4 class="font-bold text-[14px] mb-6">Shop</h4>
                    <ul class="flex flex-col gap-4 text-[13px] text-white/60">
                        <li><a href="{{ route('pelanggan.shop') }}" class="hover:text-white transition-colors">All Products</a></li>
                        <li><a href="{{ route('pelanggan.shop') }}" class="hover:text-white transition-colors">Women</a></li>
                        <li><a href="{{ route('pelanggan.shop') }}" class="hover:text-white transition-colors">Men</a></li>
                        <li><a href="{{ route('pelanggan.shop') }}" class="hover:text-white transition-colors">Unisex</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold text-[14px] mb-6">Help</h4>
                    <ul class="flex flex-col gap-4 text-[13px] text-white/60">
                        <li><a href="#" class="hover:text-white transition-colors">My Orders</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Shipping Info</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Returns</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Size Guide</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">FAQ</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold text-[14px] mb-6">Company</h4>
                    <ul class="flex flex-col gap-4 text-[13px] text-white/60">
                        <li><a href="#" class="hover:text-white transition-colors">About Us</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Contact</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Careers</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Sustainability</a></li>
                    </ul>
                </div>

            </div>

            <div class="border-t border-white/10 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-[12px] text-white/40">
                <p>© 2026 TANKEN. All rights reserved.</p>
                <div class="flex gap-6">
                    <a href="#" class="hover:text-white transition-colors">Privacy Policy</a>
                    <a href="#" class="hover:text-white transition-colors">Terms of Service</a>
                    <a href="#" class="hover:text-white transition-colors">Cookie Policy</a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>