@extends('layouts.main')

@section('title', 'Bantuan — TANKEN')

@push('styles')
<style>
    /* Hero */
    .help-hero {
        background-color: #0a0a0a;
        position: relative;
        overflow: hidden;
    }
    .help-hero::after {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(ellipse at 80% 50%, rgba(255,255,255,0.03) 0%, transparent 60%);
        pointer-events: none;
    }

    /* Topic cards */
    .topic-card {
        border: 1px solid #e8e8e8;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
        cursor: pointer;
    }
    .topic-card:hover {
        border-color: #111;
        box-shadow: 0 4px 24px rgba(0,0,0,0.07);
    }
    .topic-icon {
        width: 44px;
        height: 44px;
        background: #111;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .learn-more-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: #111;
        text-decoration: none;
        transition: gap 0.2s ease;
    }
    .learn-more-link:hover { gap: 10px; }

    /* Contact cards */
    .contact-card {
        border: 1px solid #e8e8e8;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
        display: block; /* Bikin seluruh kotak bisa diklik */
        text-align: left;
    }
    .contact-card:hover {
        border-color: #111;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    }
    .contact-icon {
        width: 40px;
        height: 40px;
        border: 1.5px solid #e0e0e0;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 14px;
    }

    /* CTA Banner */
    .cta-banner {
        background: #0a0a0a;
        position: relative;
        overflow: hidden;
    }
    .cta-banner::before {
        content: '';
        position: absolute;
        top: -40px;
        right: -40px;
        width: 280px;
        height: 280px;
        border-radius: 50%;
        border: 1px solid rgba(255,255,255,0.04);
    }
    .cta-banner::after {
        content: '';
        position: absolute;
        bottom: -60px;
        right: 60px;
        width: 180px;
        height: 180px;
        border-radius: 50%;
        border: 1px solid rgba(255,255,255,0.04);
    }
</style>
@endpush

@section('content')

{{-- ===== 1. HERO ===== --}}
<section class="help-hero py-16 md:py-24 px-6 lg:px-10">
    <div class="max-w-7xl mx-auto relative z-10">
        <p class="text-xs font-semibold tracking-widest uppercase text-white/40 mb-4">Pusat Bantuan</p>
        <h1 class="font-extrabold text-white leading-none tracking-tight mb-4"
            style="font-size: clamp(3rem, 8vw, 5.5rem); font-family:'Inter',sans-serif;">
            Ada yang Bisa<br>Kami Bantu?
        </h1>
        <p class="text-sm text-white/50 max-w-sm leading-relaxed">
            Temukan jawaban untuk pertanyaan Anda atau hubungi tim Customer Service kami.
        </p>
    </div>
</section>

{{-- ===== 2. TOPIC CARDS ===== --}}
<section class="bg-gray-50 py-10 md:py-16">
    <div class="max-w-7xl mx-auto px-6 lg:px-10">
        
        {{-- TOMBOL BACK KE HOME --}}
        <div class="mb-8">
            <a href="{{ route('pelanggan.home') }}" class="inline-flex items-center gap-1.5 text-gray-500 hover:text-black transition-colors group">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5 group-hover:-translate-x-0.5 transition-transform">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
                <span class="text-xs font-bold uppercase tracking-widest">Back to Home</span>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

            {{-- Shipping Information --}}
            <div class="topic-card bg-white p-8 reveal">
                <div class="topic-icon mb-5">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke="white" stroke-width="1.6" width="20" height="20">
                        <rect x="1" y="3" width="15" height="13"/>
                        <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/>
                        <circle cx="5.5" cy="18.5" r="2.5"/>
                        <circle cx="18.5" cy="18.5" r="2.5"/>
                    </svg>
                </div>
                <h2 class="text-base font-bold text-gray-900 mb-1.5" style="font-family:'Inter',sans-serif;">
                    Informasi Pengiriman
                </h2>
                <p class="text-sm text-gray-500 leading-relaxed mb-5">
                    Cek estimasi waktu, tarif ongkir, dan cara melacak pesanan Anda.
                </p>
                <a href="{{ route('help.shipping') }}" class="learn-more-link">
                    Selengkapnya
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="2.5" width="11" height="11">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            {{-- Returns & Exchanges --}}
            <div class="topic-card bg-white p-8 reveal">
                <div class="topic-icon mb-5">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke="white" stroke-width="1.6" width="20" height="20">
                        <polyline points="1 4 1 10 7 10"/>
                        <polyline points="23 20 23 14 17 14"/>
                        <path d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10m22 4-4.64 4.36A9 9 0 0 1 3.51 15"/>
                    </svg>
                </div>
                <h2 class="text-base font-bold text-gray-900 mb-1.5" style="font-family:'Inter',sans-serif;">
                    Pengembalian & Penukaran
                </h2>
                <p class="text-sm text-gray-500 leading-relaxed mb-5">
                    Panduan mengajukan retur dalam 7 hari dan tukar barang.
                </p>
                <a href="{{ route('help.returns') }}" class="learn-more-link">
                    Selengkapnya
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="2.5" width="11" height="11">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            {{-- FAQ --}}
            <div class="topic-card bg-white p-8 reveal">
                <div class="topic-icon mb-5">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke="white" stroke-width="1.6" width="20" height="20">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
                        <line x1="12" y1="17" x2="12.01" y2="17"/>
                    </svg>
                </div>
                <h2 class="text-base font-bold text-gray-900 mb-1.5" style="font-family:'Inter',sans-serif;">
                    Tanya Jawab (FAQ)
                </h2>
                <p class="text-sm text-gray-500 leading-relaxed mb-5">
                    Jawaban lengkap untuk pertanyaan yang paling sering diajukan.
                </p>
                <a href="{{ route('help.faq') }}" class="learn-more-link">
                    Selengkapnya
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="2.5" width="11" height="11">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

        </div>
    </div>
</section>

{{-- ===== 3. STILL NEED HELP? (Kotak Centered: Email & WhatsApp) ===== --}}
<section class="bg-white py-16 md:py-20 border-t border-gray-100">
    <div class="max-w-7xl mx-auto px-6 lg:px-10">

        {{-- Judul dibuat Center --}}
        <div class="mb-12 reveal text-center flex flex-col items-center">
            <h2 class="font-extrabold text-3xl md:text-4xl tracking-tight text-gray-900"
                style="font-family:'Inter',sans-serif;">
                Masih Butuh Bantuan?
            </h2>
             <p class="text-sm text-gray-500 mt-3 max-w-md leading-relaxed">
                Admin kami siap membantu menjawab semua pertanyaan kamu.
        </div>

        {{-- Grid diubah menjadi 2 kolom dan di-center (max-w-2xl mx-auto) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-2xl mx-auto">

            {{-- Kotak Email --}}
            <a href="mailto:explore.tanken@gmail.com" class="contact-card p-8 reveal bg-white rounded-none">
                <div class="contact-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="1.6" width="18" height="18" class="text-gray-700">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                        <polyline points="22,6 12,13 2,6"/>
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-gray-900 mb-1">Email TANKEN</h3>
                <p class="text-sm font-medium text-gray-700 mb-0.5">explore.tanken@gmail.com</p>
                <p class="text-xs text-gray-400">Dibalas maksimal dalam 24 jam</p>
            </a>

            {{-- Kotak WhatsApp --}}
            <a href="https://wa.me/6285121235200?text=Halo%20admin%20tanken%2C%20saya%20ingin%20bertanya%20terkait..." target="_blank" rel="noopener noreferrer" class="contact-card p-8 reveal bg-white rounded-none">
                <div class="contact-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="text-gray-700" viewBox="0 0 16 16">
                      <path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232"/>
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-gray-900 mb-1">WhatsApp TANKEN</h3>
                <p class="text-sm font-medium text-gray-700 mb-0.5">+62 851-2123-5200</p>
                <p class="text-xs text-gray-400">Senin–Jumat, 09.00–18.00 WIB</p>
            </a>

        </div>
    </div>
</section>

{{-- ===== 4. CTA BANNER ===== --}}
<section class="cta-banner py-14 md:py-16 px-6 lg:px-10">
    <div class="max-w-7xl mx-auto relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-8">
        <div>
            <p class="text-xs font-semibold tracking-widest uppercase text-white/40 mb-3">Jelajahi Semua Topik</p>
            <h2 class="font-extrabold text-white leading-tight tracking-tight"
                style="font-size: clamp(1.8rem, 4vw, 2.8rem); font-family:'Inter',sans-serif;">
                Tidak Menemukan<br>Jawaban Anda?
            </h2>
            <p class="text-sm text-white/50 mt-3 max-w-xs leading-relaxed">
                Jelajahi halaman FAQ kami untuk menemukan jawaban lengkap dari semua pertanyaan Anda.
            </p>
        </div>
        <div class="flex-shrink-0">
            <a href="{{ route('help.faq') }}"
               class="inline-flex items-center gap-2 border border-white text-white text-xs font-bold tracking-widest uppercase px-7 py-3.5 hover:bg-white hover:text-black transition-colors">
                Lihat Semua FAQ
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2.5" width="11" height="11">
                    <path d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    // Scroll reveal
    const revealEls = document.querySelectorAll('.reveal');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, i) => {
            if (entry.isIntersecting) {
                setTimeout(() => entry.target.classList.add('visible'), i * 70);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    revealEls.forEach(el => observer.observe(el));
</script>
@endpush