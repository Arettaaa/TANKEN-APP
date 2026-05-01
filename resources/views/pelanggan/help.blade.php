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
        transition: border-color 0.2s ease;
    }
    .contact-card:hover {
        border-color: #bbb;
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
<section class="bg-gray-50 py-16 md:py-20">
    <div class="max-w-7xl mx-auto px-6 lg:px-10">
        {{-- Diubah menjadi grid-cols-3 agar 3 kotak langsung berjajar sejajar --}}
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

{{-- ===== 3. STILL NEED HELP? ===== --}}
<section class="bg-white py-16 md:py-20 border-t border-gray-100">
    <div class="max-w-7xl mx-auto px-6 lg:px-10">

        <div class="mb-12 reveal">
            <p class="text-xs font-semibold tracking-widest uppercase text-gray-400 mb-2">Hubungi Kami</p>
            <h2 class="font-extrabold text-3xl md:text-4xl tracking-tight text-gray-900"
                style="font-family:'Inter',sans-serif;">
                Masih Butuh Bantuan?
            </h2>
            <p class="text-sm text-gray-500 mt-3 max-w-sm leading-relaxed">
                Tim Customer Service kami selalu siap untuk membantu berbagai kendala Anda.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

            {{-- Email --}}
            <div class="contact-card p-8 reveal">
                <div class="contact-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="1.6" width="18" height="18" class="text-gray-700">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                        <polyline points="22,6 12,13 2,6"/>
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-gray-900 mb-1">Email Kami</h3>
                <p class="text-sm font-medium text-gray-700 mb-0.5">support@tanken.com</p>
                <p class="text-xs text-gray-400">Dibalas maksimal dalam 24 jam</p>
            </div>

            {{-- Call --}}
            <div class="contact-card p-8 reveal">
                <div class="contact-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="1.6" width="18" height="18" class="text-gray-700">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.18 2 2 0 0 1 3.6 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.58a16 16 0 0 0 6 6l.95-1.95a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-gray-900 mb-1">Telepon Kami</h3>
                <p class="text-sm font-medium text-gray-700 mb-0.5">1-800-TANKEN</p>
                <p class="text-xs text-gray-400">Senin–Jumat, 09.00–18.00 WIB</p>
            </div>

            {{-- Live Chat --}}
            <div class="contact-card p-8 reveal">
                <div class="contact-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="1.6" width="18" height="18" class="text-gray-700">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-gray-900 mb-1">Live Chat</h3>
                <p class="text-sm font-medium text-gray-700 mb-0.5">Mulai Obrolan</p>
                <p class="text-xs text-gray-400">Tersedia 09.00–21.00 WIB</p>
            </div>

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