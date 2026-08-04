@extends('layouts.public')
@section('title', 'Beranda')
@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

    #beranda, #cara-kerja, #instansi { scroll-margin-top: 5rem; }

    /* Menyamakan semua jenis font menjadi Inter */
    body, 
    .sim-font-display, 
    .sim-font-body, 
    .sim-font-mono { 
        font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; 
    }

    a:focus-visible, button:focus-visible {
        outline: 2px solid #FEA619;
        outline-offset: 2px;
        border-radius: 4px;
    }

    @keyframes sim-fade-up { from { opacity: 0; transform: translateY(14px); } to { opacity: 1; transform: translateY(0); } }
    .sim-anim-1 { animation: sim-fade-up .7s ease both; animation-delay: .05s; }
    .sim-anim-2 { animation: sim-fade-up .7s ease both; animation-delay: .15s; }
    .sim-anim-3 { animation: sim-fade-up .7s ease both; animation-delay: .28s; }
    .sim-anim-4 { animation: sim-fade-up .7s ease both; animation-delay: .4s; }

    .sim-reveal { opacity: 0; transform: translateY(18px); transition: opacity .6s ease, transform .6s ease; }
    .sim-reveal.sim-in { opacity: 1; transform: translateY(0); }
    .sim-delay-1 { transition-delay: .05s; }
    .sim-delay-2 { transition-delay: .15s; }
    .sim-delay-3 { transition-delay: .25s; }
    .sim-delay-4 { transition-delay: .35s; }

    .sim-wave-draw path,
    .sim-wave-draw use { stroke-dasharray: 2000; stroke-dashoffset: 2000; transition: stroke-dashoffset 1.7s ease-out; }
    .sim-wave-draw.sim-in path,
    .sim-wave-draw.sim-in use { stroke-dashoffset: 0; }

    .sim-card-hover { transition: transform .35s ease, box-shadow .35s ease; }
    .sim-card-hover:hover { transform: translateY(-4px); box-shadow: 0 16px 32px -14px rgba(0,35,111,0.2); }

    @media (prefers-reduced-motion: reduce) {
        .sim-anim-1, .sim-anim-2, .sim-anim-3, .sim-anim-4 { animation: none; opacity: 1; transform: none; }
        .sim-reveal { opacity: 1; transform: none; transition: none; }
        .sim-wave-draw path, .sim-wave-draw use { stroke-dashoffset: 0; transition: none; }
        .sim-card-hover:hover { transform: none; }
    }
</style>

<!-- Motif garis sungai -->
<svg width="0" height="0" class="absolute" aria-hidden="true" focusable="false">
    <defs>
        <path id="river-line" d="M0,20 C60,2 120,38 180,20 C240,2 300,38 360,20 C420,2 480,38 540,20 C600,2 660,38 720,20" />
        <path id="river-line-sm" d="M0,8 C20,0 40,16 60,8 C80,0 100,16 120,8 C140,0 160,16 180,8" />
    </defs>
</svg>

<!-- Hero Section -->
<div id="beranda" class="relative bg-[#eef2f6] mx-4 mt-6 sm:mt-8 lg:mx-8 rounded-xl overflow-hidden shadow-sm min-h-[500px] sm:min-h-[460px] md:min-h-[520px] lg:min-h-[560px] flex flex-col justify-center">
    <!-- Background Image -->
    <div class="absolute inset-0 flex justify-end">
        <img src="{{ asset('images/balaikota.jpg') }}" alt="Balai Kota Banjarmasin" class="w-full h-full object-cover object-center">
    </div>

    <!-- Gradient Overlay -->
    <div class="absolute inset-0 bg-gradient-to-r from-[#DEE9FC] via-[#DEE9FC]/85 to-[#DEE9FC]/40 sm:via-[#DEE9FC]/70 sm:to-transparent"></div>

    <!-- Konten Teks & Tombol -->
    <div class="relative z-10 p-6 sm:p-12 md:p-16 lg:p-20 flex flex-col justify-center h-full max-w-2xl">

        <div class="sim-anim-1 inline-flex w-fit items-center gap-2 bg-white/80 sim-font-mono text-[11px] sm:text-xs font-semibold tracking-wide uppercase text-[#00236F] px-3.5 py-1.5 rounded-full border border-[#00236F]/15 shadow-sm mb-5">
            <span class="relative flex h-2 w-2">
                <span class="motion-safe:animate-ping absolute inline-flex h-full w-full rounded-full bg-[#FEA619] opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-[#FEA619]"></span>
            </span>
            Portal Resmi Pemerintah Kota Banjarmasin
        </div>

        <h1 class="sim-anim-2 sim-font-display text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-extrabold text-[#00236F] leading-tight mb-5">
            Sistem Informasi Magang Akurat<br class="hidden sm:block">
            Pemerintahan Kota<br class="hidden sm:block">
            <span class="relative inline-block text-[#FEA619]">
                Banjarmasin
            </span>
        </h1>

        <p class="sim-anim-3 sim-font-body text-[#1f2937]/80 text-sm md:text-base mb-8 max-w-md font-medium leading-relaxed">
            Temukan dan ajukan peluang magang di berbagai instansi Pemerintah Kota Banjarmasin.
        </p>

        <div class="sim-anim-4 flex flex-wrap items-center gap-3">
            @auth
                <a href="{{ route('skpd.index') }}" class="group inline-flex items-center gap-2 bg-[#00236F] text-white px-6 py-3.5 sm:py-3 rounded-md text-sm font-semibold hover:bg-[#001b57] active:scale-[0.98] transition shadow-sm">
                    Cari Instansi Magang
                    <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                </a>
            @else
                <a href="{{ route('login') }}" class="group inline-flex items-center gap-2 bg-[#00236F] text-white px-6 py-3.5 sm:py-3 rounded-md text-sm font-semibold hover:bg-[#001b57] active:scale-[0.98] transition shadow-sm">
                    Daftar Sekarang
                    <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                </a>
                <a href="{{ route('skpd.index') }}" class="inline-flex items-center gap-2 bg-white/70 hover:bg-white text-[#00236F] px-6 py-3.5 sm:py-3 rounded-md text-sm font-semibold border border-[#00236F]/20 transition">
                    Lihat Instansi
                </a>
            @endauth
        </div>
    </div>
</div>

<!-- Kenapa Melalui Portal Ini -->
<section class="px-4 sm:px-6 lg:px-8 pt-20 sm:pt-24">
    <div class="max-w-2xl mx-auto text-center mb-12 sim-reveal sim-observe">
        <p class="sim-font-mono text-xs font-semibold tracking-widest uppercase text-[#FEA619] mb-3">Kenapa Melalui Portal Ini</p>
        <h2 class="sim-font-display text-2xl sm:text-3xl font-bold text-[#00236F] mb-3">Dirancang Agar Proses Pengajuan Magang Anda Lebih Mudah</h2>
        <p class="text-sm text-[#1f2937]/70">Satu sistem untuk menemukan, mengajukan, dan memantau magang di lingkungan Pemerintah Kota Banjarmasin.</p>
    </div>

    <!-- Menyamakan tinggi grid items dengan h-full pada child items -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 items-stretch">
        <div class="sim-reveal sim-observe sim-delay-1 sim-card-hover bg-white rounded-xl border border-gray-100 shadow-sm p-6 h-full flex flex-col">
            <div class="w-11 h-11 rounded-lg bg-[#00236F]/10 text-[#00236F] flex items-center justify-center mb-4">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 3l7 3v6c0 4.5-3 7.5-7 9-4-1.5-7-4.5-7-9V6l7-3z" />
                    <path d="M9 12l2 2 4-4" />
                </svg>
            </div>
            <h3 class="sim-font-display font-bold text-[#00236F] mb-1.5">Resmi &amp; Terverifikasi</h3>
            <p class="text-sm text-[#1f2937]/70 leading-relaxed flex-grow">Setiap instansi yang tercantum merupakan Satuan Kerja Perangkat Daerah resmi di lingkungan Pemerintah Kota Banjarmasin.</p>
        </div>

        <div class="sim-reveal sim-observe sim-delay-2 sim-card-hover bg-white rounded-xl border border-gray-100 shadow-sm p-6 h-full flex flex-col">
            <div class="w-11 h-11 rounded-lg bg-[#00236F]/10 text-[#00236F] flex items-center justify-center mb-4">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="7" height="7" rx="1.5" />
                    <rect x="14" y="3" width="7" height="7" rx="1.5" />
                    <rect x="3" y="14" width="7" height="7" rx="1.5" />
                    <rect x="14" y="14" width="7" height="7" rx="1.5" />
                </svg>
            </div>
            <h3 class="sim-font-display font-bold text-[#00236F] mb-1.5">Beragam Bidang Studi</h3>
            <p class="text-sm text-[#1f2937]/70 leading-relaxed flex-grow">Dari teknologi informasi, kesehatan, hingga administrasi publik. Silakan pilih instansi yang sesuai jurusan Anda.</p>
        </div>

        <div class="sim-reveal sim-observe sim-delay-3 sim-card-hover bg-white rounded-xl border border-gray-100 shadow-sm p-6 h-full flex flex-col">
            <div class="w-11 h-11 rounded-lg bg-[#00236F]/10 text-[#00236F] flex items-center justify-center mb-4">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="5" y="4" width="14" height="17" rx="2" />
                    <path d="M9 3.5h6a1 1 0 011 1V6H8V4.5a1 1 0 011-1z" />
                    <path d="M9 13l2 2 4-4" />
                </svg>
            </div>
            <h3 class="sim-font-display font-bold text-[#00236F] mb-1.5">Proses Transparan</h3>
            <p class="text-sm text-[#1f2937]/70 leading-relaxed flex-grow">Pantau status pengajuan magang Anda secara langsung, tanpa perlu datang berulang kali.</p>
        </div>
    </div>
</section>

<!-- Cara Kerja -->
<section id="cara-kerja" class="px-4 sm:px-6 lg:px-8 pt-20 sm:pt-24">
    <div class="max-w-2xl mx-auto text-center mb-14 sim-reveal sim-observe">
        <p class="sim-font-mono text-xs font-semibold tracking-widest uppercase text-[#FEA619] mb-3">Alur Pendaftaran</p>
        <h2 class="sim-font-display text-2xl sm:text-3xl font-bold text-[#00236F]">Cara Kerja Sistem</h2>
    </div>

    <div class="relative max-w-5xl mx-auto grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-y-10 gap-x-6">
        <div class="relative sim-reveal sim-observe sim-delay-1 text-center">
            <div class="relative z-10 w-14 h-14 mx-auto rounded-full bg-[#00236F] text-white sim-font-mono text-sm font-semibold flex items-center justify-center shadow-md mb-3">01</div>
            <svg class="w-6 h-6 mx-auto text-[#00236F] mb-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="10" cy="8" r="3.5" />
                <path d="M3.5 20c0-3.6 2.9-6 6.5-6s6.5 2.4 6.5 6" />
                <path d="M18.5 8.5v5M16 11h5" />
            </svg>
            <h3 class="sim-font-display font-bold text-[#00236F] text-sm mb-1">Buat Akun</h3>
            <p class="text-xs text-[#1f2937]/65 leading-relaxed px-2">Daftar menggunakan data diri dan institusi pendidikan Anda.</p>
        </div>

        <div class="relative sim-reveal sim-observe sim-delay-2 text-center">
            <div class="relative z-10 w-14 h-14 mx-auto rounded-full bg-[#00236F] text-white sim-font-mono text-sm font-semibold flex items-center justify-center shadow-md mb-3">02</div>
            <svg class="w-6 h-6 mx-auto text-[#00236F] mb-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M5 21V5.5a1.5 1.5 0 011.5-1.5h5A1.5 1.5 0 0113 5.5V21" />
                <path d="M13 10.5h4.5A1.5 1.5 0 0119 12v9" />
                <path d="M5 21h14" />
                <path d="M8 8h2M8 12h2M8 16h2" />
            </svg>
            <h3 class="sim-font-display font-bold text-[#00236F] text-sm mb-1">Pilih Instansi</h3>
            <p class="text-xs text-[#1f2937]/65 leading-relaxed px-2">Jelajahi daftar SKPD dan temukan yang sesuai minat Anda.</p>
        </div>

        <div class="relative sim-reveal sim-observe sim-delay-3 text-center">
            <div class="relative z-10 w-14 h-14 mx-auto rounded-full bg-[#00236F] text-white sim-font-mono text-sm font-semibold flex items-center justify-center shadow-md mb-3">03</div>
            <svg class="w-6 h-6 mx-auto text-[#00236F] mb-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 3L3 10.5l7.5 3L14 21l7-18z" />
                <path d="M10.5 13.5L21 3" />
            </svg>
            <h3 class="sim-font-display font-bold text-[#00236F] text-sm mb-1">Ajukan Permohonan</h3>
            <p class="text-xs text-[#1f2937]/65 leading-relaxed px-2">Kirim berkas persyaratan langsung melalui sistem.</p>
        </div>

        <div class="relative sim-reveal sim-observe sim-delay-4 text-center">
            <div class="relative z-10 w-14 h-14 mx-auto rounded-full bg-[#00236F] text-white sim-font-mono text-sm font-semibold flex items-center justify-center shadow-md mb-3">04</div>
            <svg class="w-6 h-6 mx-auto text-[#00236F] mb-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M2 9l10-5 10 5-10 5-10-5z" />
                <path d="M6 11v5c0 1.7 2.7 3 6 3s6-1.3 6-3v-5" />
                <path d="M22 9v6" />
            </svg>
            <h3 class="sim-font-display font-bold text-[#00236F] text-sm mb-1">Mulai Magang</h3>
            <p class="text-xs text-[#1f2937]/65 leading-relaxed px-2">Setelah disetujui, mulai program magang di instansi pilihan.</p>
        </div>
    </div>
</section>

<!-- Section Daftar Instansi -->
<div id="instansi" class="w-full px-4 sm:px-6 lg:px-8 pt-20 sm:pt-24 pb-20">
    <!-- Grid Card Instansi -->
    <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-24">
    
    <!-- Header Section -->
    <div class="mb-10 flex flex-col sm:flex-row sm:justify-between sm:items-end gap-4">
        <div>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-[#00236F] mb-2">Daftar Instansi (SKPD)</h2>
            <p class="text-sm text-slate-600">Pilih instansi yang sesuai dengan minat dan bidang studi Anda.</p>
        </div>
        
        <!-- Tombol Lihat Semua -->
        <div>
            <a href="{{ route('skpd.index') }}" class="inline-flex items-center gap-2 bg-[#FEA619] hover:bg-amber-500 text-slate-900 font-bold px-5 py-2.5 rounded-xl text-sm transition shadow-sm group">
                Lihat Semua 
                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>
    </div>

    <!-- Grid Card Instansi -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($skpds as $skpd)
            <x-skpd-card :skpd="$skpd" />
        @empty
            <div class="col-span-1 md:col-span-3 text-center py-16 bg-white rounded-2xl border border-slate-200 shadow-xs">
                <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                <p class="text-sm text-slate-500 font-medium">Belum ada data instansi (SKPD) yang tersedia saat ini.</p>
            </div>
        @endforelse
    </div>
</div>

<!-- CTA Penutup -->
<section class="relative overflow-hidden mx-4 lg:mx-8 mb-20 rounded-2xl bg-gradient-to-br from-[#00236F] to-[#062B5C] sim-reveal sim-observe">
    <svg class="absolute inset-x-0 bottom-0 w-full h-24 sim-wave-draw sim-observe" viewBox="0 0 720 40" preserveAspectRatio="none" aria-hidden="true">
        <use href="#river-line" stroke="#FEA619" stroke-width="3" fill="none" stroke-opacity="0.18" />
    </svg>
    <div class="relative z-10 px-6 sm:px-12 py-14 sm:py-16 text-center max-w-2xl mx-auto">
        <h2 class="sim-font-display text-2xl sm:text-3xl font-bold text-white mb-3">Siap Memulai Perjalanan Magang Anda?</h2>
        <p class="text-sm sm:text-base text-white/75 mb-8">Gratis, resmi, dan didukung penuh oleh Pemerintah Kota Banjarmasin.</p>
        @auth
            <a href="{{ route('skpd.index') }}" class="inline-flex items-center gap-2 bg-[#FEA619] text-[#1f2937] px-7 py-3.5 rounded-md text-sm font-semibold hover:bg-opacity-90 active:scale-[0.98] transition shadow-sm">
                Cari Instansi Magang
            </a>
        @else
            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 bg-[#FEA619] text-[#1f2937] px-7 py-3.5 rounded-md text-sm font-semibold hover:bg-opacity-90 active:scale-[0.98] transition shadow-sm">
                Daftar Sekarang
            </a>
        @endauth
    </div>
</section>

<script>
    (function () {
        var prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        var targets = document.querySelectorAll('.sim-observe');

        if (prefersReduced || !('IntersectionObserver' in window)) {
            targets.forEach(function (el) { el.classList.add('sim-in'); });
            return;
        }

        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('sim-in');
                    io.unobserve(entry.target);
                }
            });
        }, { threshold: 0.15, rootMargin: '0px 0px -60px 0px' });

        targets.forEach(function (el) { io.observe(el); });
    })();
</script>
@endsection