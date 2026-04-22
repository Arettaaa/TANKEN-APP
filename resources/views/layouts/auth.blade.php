<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Auth') — TANKEN</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f0f0f0; overflow: hidden; }

        .left-panel {
            position: relative;
            background-image: url('{{ asset("images/sign.jpg") }}');
            background-size: cover;
            background-position: center;
        }
        .left-overlay {
            position: absolute; inset: 0;
            background: linear-gradient(to bottom, rgba(0,0,0,0.45) 0%, rgba(0,0,0,0.55) 100%);
        }

        .input-wrap { position: relative; }
        .input-wrap input {
            width: 100%; padding: 14px 14px 14px 44px; border: 1.5px solid #e5e7eb; border-radius: 12px;
            font-family: 'Inter', sans-serif; font-size: 0.9rem; color: #111; background: #fff;
            outline: none; transition: border-color 0.2s ease;
        }
        .input-wrap input:focus { border-color: #111; }
        .input-wrap input::placeholder { color: #bbb; }
        .input-icon { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #bbb; pointer-events: none; }
        .eye-toggle {
            position: absolute; right: 14px; top: 50%; transform: translateY(-50%);
            cursor: pointer; color: #bbb; background: none; border: none; padding: 0;
            display: flex; align-items: center; transition: color 0.2s;
        }
        .eye-toggle:hover { color: #555; }

        .social-btn {
            display: flex; align-items: center; justify-content: center; gap: 8px;
            width: 100%; padding: 14px; border: 1.5px solid #e5e7eb; border-radius: 50px;
            font-family: 'Inter', sans-serif; font-size: 0.875rem; font-weight: 600;
            color: #111; background: #fff; cursor: pointer; text-decoration: none;
            transition: border-color 0.2s, background 0.2s;
        }
        .social-btn:hover { border-color: #aaa; background: #f9f9f9; }

        .benefit-item { display: flex; align-items: center; gap: 12px; color: rgba(255,255,255,0.9); font-size: 0.9rem; }
        .bullet-dot { width: 8px; height: 8px; border-radius: 50%; background: rgba(255,255,255,0.5); flex-shrink: 0; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-thumb { background: #ccc; border-radius: 4px; }
    </style>
</head>
<body class="flex h-screen w-full">

    {{-- ===== LEFT PANEL (STAY) ===== --}}
    <div class="left-panel hidden lg:flex lg:w-[50%] h-full flex-col justify-between p-10 relative flex-shrink-0">
        <div class="left-overlay"></div>
        <div class="relative z-10"><span class="text-white font-extrabold text-2xl tracking-widest uppercase">TANKEN</span></div>
        <div class="relative z-10 mb-8">
            <h1 class="text-white font-extrabold text-5xl leading-tight mb-4">@yield('left-title')</h1>
            <p class="text-white/70 text-base leading-relaxed mb-8 max-w-md">@yield('left-desc')</p>
            <div class="flex flex-col gap-3">
                <div class="benefit-item"><span class="bullet-dot"></span><span>Gratis ongkir untuk pesanan di atas Rp500.000</span></div>
                <div class="benefit-item"><span class="bullet-dot"></span><span>Penawaran eksklusif khusus member</span></div>
                <div class="benefit-item"><span class="bullet-dot"></span><span>Akses awal ke koleksi terbaru</span></div>
                <div class="benefit-item"><span class="bullet-dot"></span><span>Pengembalian mudah dalam 30 hari</span></div>
            </div>
        </div>
        <div class="relative z-10"><p class="text-white/40 text-xs">© 2026 TANKEN. All rights reserved.</p></div>
    </div>

    {{-- ===== RIGHT PANEL (SCROLLABLE) ===== --}}
    <div class="flex-1 h-full overflow-y-auto flex flex-col items-center py-10 px-6">
        
        <div class="w-full max-w-md bg-white rounded-2xl shadow-sm p-8 lg:p-9 my-auto flex-shrink-0">
            {{-- ISI FORM LOGIN/REGISTER MASUK KE SINI --}}
            @yield('content')
        </div>

        <div class="mt-6 flex-shrink-0">
            <a href="{{ route('pelanggan.home') }}" class="flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-800 transition-colors font-medium">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="14" height="14"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
                Back to Home
            </a>
        </div>
    </div>

<script>
    // Logika mata sudah dibenarkan
    function togglePassword(inputId, btn) {
        const input = document.getElementById(inputId);
        const eyeOpen   = document.getElementById(inputId + '-eye-open');
        const eyeClosed = document.getElementById(inputId + '-eye-closed');
        
        if (input.type === 'password') {
            input.type = 'text'; 
            eyeOpen.style.display = 'block'; // Tampilkan mata kebuka
            eyeClosed.style.display = 'none'; // Sembunyikan mata silang
        } else {
            input.type = 'password'; 
            eyeOpen.style.display = 'none'; // Sembunyikan mata kebuka
            eyeClosed.style.display = 'block'; // Tampilkan mata silang
        }
    }
</script>
</body>
</html>