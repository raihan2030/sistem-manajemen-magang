@extends('layouts.public')
@section('title', 'Beranda')
@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    body { 
        font-family: 'Plus Jakarta Sans', sans-serif; 
        scroll-behavior: smooth;
    }

    .reveal { 
        opacity: 0; 
        transform: translateY(30px); 
        transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1); 
    }
    .reveal.active { 
        opacity: 1; 
        transform: translateY(0); 
    }
    
    .delay-100 { transition-delay: 100ms; }
    .delay-200 { transition-delay: 200ms; }
    .delay-300 { transition-delay: 300ms; }
    .delay-400 { transition-delay: 400ms; }

    .bg-pattern {
        background-image: radial-gradient(rgba(255, 255, 255, 0.15) 1px, transparent 1px);
        background-size: 24px 24px;
    }
</style>

<!-- Hero Section -->
<section class="relative pt-12 pb-20 lg:pt-20 lg:pb-28 overflow-hidden bg-white">
    <!-- Dekorasi background halus -->
    <div class="absolute inset-0 -z-10 bg-[#F8FAFC]">
        <div class="absolute top-0 right-0 -translate-y-12 translate-x-1/3 w-[800px] h-[800px] rounded-full bg-gradient-to-br from-[#DEE9FC]/60 to-transparent blur-3xl"></div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="grid lg:grid-cols-2 gap-12 lg:gap-8 items-center">
            
            <!-- Teks Hero -->
            <div class="max-w-2xl reveal active">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white border border-slate-200 shadow-sm mb-6">
                    <span class="flex h-2.5 w-2.5 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#FEA619] opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-[#FEA619]"></span>
                    </span>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-700">Portal Resmi Pemerintah Kota Banjarmasin</span>
                </div>

                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-[#00236F] leading-[1.15] mb-6 tracking-tight">
                    Sistem Informasi Magang <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#00236F] to-[#FEA619]">Akurat</span>
                </h1>
                
                <p class="text-lg text-slate-600 mb-8 leading-relaxed max-w-lg font-medium">
                    Jelajahi dan ajukan peluang magang di berbagai Satuan Kerja Perangkat Daerah (SKPD) secara mudah dan terintegrasi.
                </p>

                <div class="flex flex-wrap items-center gap-4">
                    @auth
                        <a href="{{ route('skpd.index') }}" class="inline-flex justify-center items-center gap-2 bg-[#00236F] hover:bg-[#001b57] text-white px-8 py-3.5 rounded-xl font-semibold transition-all shadow-lg shadow-blue-900/20 active:scale-95 w-full sm:w-auto">
                            Cari Instansi
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex justify-center items-center gap-2 bg-[#00236F] hover:bg-[#001b57] text-white px-8 py-3.5 rounded-xl font-semibold transition-all shadow-lg shadow-blue-900/20 active:scale-95 w-full sm:w-auto">
                            Daftar Sekarang
                        </a>
                        <a href="{{ route('skpd.index') }}" class="inline-flex justify-center items-center gap-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 px-8 py-3.5 rounded-xl font-semibold transition-all active:scale-95 w-full sm:w-auto">
                            Lihat Instansi
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Gambar Hero (Sekarang Muncul di Mobile) -->
            <div class="relative h-[320px] sm:h-[400px] lg:h-[550px] reveal active delay-100 mt-8 lg:mt-0">
                <!-- Background shadow/aksen di belakang gambar -->
                <div class="absolute inset-0 bg-gradient-to-tr from-[#00236F]/10 to-transparent rounded-[2rem] lg:rounded-[2.5rem] transform rotate-3 scale-105 transition-transform hover:rotate-6 duration-700"></div>
                
                <img src="{{ asset('images/balaikota.jpg') }}" alt="Balai Kota Banjarmasin" class="relative w-full h-full object-cover rounded-[2rem] lg:rounded-[2.5rem] shadow-2xl border-4 border-white">
                
                <!-- Floating Badge menyesuaikan ukuran layar -->
                <div class="absolute -bottom-6 -left-2 sm:-left-4 lg:-bottom-8 lg:-left-8 bg-white p-3 sm:p-4 lg:p-5 rounded-xl lg:rounded-2xl shadow-xl border border-slate-100 flex items-center gap-3 lg:gap-4 animate-bounce" style="animation-duration: 3s;">
                    <div class="w-10 h-10 lg:w-12 lg:h-12 bg-green-100 rounded-full flex items-center justify-center text-green-600 shrink-0">
                        <svg class="w-5 h-5 lg:w-6 lg:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs lg:text-sm font-bold text-slate-800">Terverifikasi</p>
                        <p class="text-[10px] lg:text-xs text-slate-500 font-medium">Program Resmi Pemerintah Kota Banjarmasin</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Keunggulan (Bento Grid Style) -->
<section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-16 reveal">
            <h2 class="text-sm font-bold tracking-widest text-[#FEA619] uppercase mb-3">Mengapa Menggunakan Portal Ini</h2>
            <h3 class="text-3xl md:text-4xl font-extrabold text-[#00236F]">Dirancang Untuk Kemudahan Anda</h3>
        </div>

        <div class="grid md:grid-cols-3 gap-6">
            <!-- Kotak 1 (Lebar 2 kolom di desktop) -->
            <div class="md:col-span-2 bg-slate-50 rounded-[2rem] p-8 lg:p-10 border border-slate-100 hover:border-[#00236F]/20 transition-colors reveal delay-100">
                <div class="w-14 h-14 bg-white rounded-2xl shadow-sm border border-slate-200 flex items-center justify-center text-[#00236F] mb-6">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>
                <h4 class="text-xl font-bold text-slate-900 mb-3">Resmi & Terverifikasi</h4>
                <p class="text-slate-600 leading-relaxed max-w-lg font-medium">Setiap instansi yang ada di platform ini adalah Satuan Kerja Perangkat Daerah (SKPD) resmi di lingkungan Pemerintah Kota Banjarmasin. Tidak ada perantara.</p>
            </div>

            <!-- Kotak 2 -->
            <div class="bg-slate-50 rounded-[2rem] p-8 lg:p-10 border border-slate-100 hover:border-[#00236F]/20 transition-colors reveal delay-200">
                <div class="w-14 h-14 bg-white rounded-2xl shadow-sm border border-slate-200 flex items-center justify-center text-[#00236F] mb-6">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <h4 class="text-xl font-bold text-slate-900 mb-3">Beragam Bidang</h4>
                <p class="text-slate-600 leading-relaxed font-medium">Dari teknologi, administrasi, hingga kesehatan. Temukan tempat yang paling sesuai dengan jurusanmu.</p>
            </div>

            <!-- Kotak 3 -->
            <div class="bg-slate-50 rounded-[2rem] p-8 lg:p-10 border border-slate-100 hover:border-[#00236F]/20 transition-colors reveal delay-300">
                <div class="w-14 h-14 bg-white rounded-2xl shadow-sm border border-slate-200 flex items-center justify-center text-[#00236F] mb-6">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"></path></svg>
                </div>
                <h4 class="text-xl font-bold text-slate-900 mb-3">Proses Transparan</h4>
                <p class="text-slate-600 leading-relaxed font-medium">Pantau langsung status pengajuan magangmu dari dashboard. Tidak perlu repot datang ke kantor untuk bertanya.</p>
            </div>
            
            <!-- Kotak 4 (Aksen warna gelap) -->
            <div class="md:col-span-2 bg-[#00236F] rounded-[2rem] p-8 lg:p-10 text-white relative overflow-hidden reveal delay-100">
                <div class="absolute right-0 bottom-0 opacity-10 pointer-events-none">
                    <svg width="250" height="250" viewBox="0 0 200 200"><path fill="currentColor" d="M45.7,-76.3C58.9,-69.3,68.9,-54.6,76.5,-40.1C84.1,-25.6,89.3,-12.8,87.9,-1.4C86.5,10,78.5,20,70.8,30.3C63.1,40.6,55.8,51.2,45.2,58.4C34.6,65.6,20.7,69.5,6.5,70.9C-7.7,72.3,-22.2,71.2,-34.5,65.1C-46.8,59,-56.9,47.9,-66.1,35.7C-75.3,23.5,-83.6,10.2,-85.1,-3.5C-86.6,-17.2,-81.3,-31.3,-71.8,-42.1C-62.3,-52.9,-48.6,-60.4,-35.1,-67.2C-21.6,-74,-8.3,-80.1,6.5,-82.9C21.3,-85.7,42.6,-85.2,45.7,-76.3Z" transform="translate(100 100)"/></svg>
                </div>
                <div class="relative z-10">
                    <h4 class="text-2xl font-bold mb-3">Kemudahan Alur Sistem</h4>
                    <p class="text-blue-100 leading-relaxed max-w-lg mb-6 font-medium">Cukup lengkapi data diri dan berkas sekali saja, dan kamu siap melamar ke berbagai instansi yang tersedia di sistem kami.</p>
                    <a href="#cara-kerja" class="inline-flex items-center gap-2 text-[#FEA619] font-bold hover:text-white transition-colors">
                        Lihat Alur Pendaftaran <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Cara Kerja (Timeline / Step) -->
<section id="cara-kerja" class="py-24 bg-[#F8FAFC]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-16 reveal">
            <h2 class="text-sm font-bold tracking-widest text-[#FEA619] uppercase mb-3">Panduan</h2>
            <h3 class="text-3xl md:text-4xl font-extrabold text-[#00236F]">Bagaimana Cara Kerjanya?</h3>
        </div>

        <div class="relative max-w-5xl mx-auto">
            <!-- Garis Penghubung (Hanya muncul di desktop) -->
            <div class="hidden md:block absolute top-[2.25rem] left-0 w-full h-0.5 bg-slate-200 z-0"></div>

            <div class="grid md:grid-cols-4 gap-12 md:gap-6 relative z-10">
                <!-- Step 1 -->
                <div class="relative text-center reveal delay-100">
                    <div class="w-16 h-16 mx-auto bg-white border-4 border-[#F8FAFC] rounded-2xl shadow-sm flex items-center justify-center text-xl font-extrabold text-[#00236F] mb-6 relative z-10 rotate-3 hover:rotate-0 transition-transform">
                        1
                    </div>
                    <h4 class="text-lg font-bold text-slate-900 mb-2">Buat Akun</h4>
                    <p class="text-sm text-slate-600 leading-relaxed font-medium px-2">Daftarkan dirimu dengan data pribadi dan institusi pendidikan.</p>
                </div>

                <!-- Step 2 -->
                <div class="relative text-center reveal delay-200">
                    <div class="w-16 h-16 mx-auto bg-white border-4 border-[#F8FAFC] rounded-2xl shadow-sm flex items-center justify-center text-xl font-extrabold text-[#00236F] mb-6 relative z-10 -rotate-3 hover:rotate-0 transition-transform">
                        2
                    </div>
                    <h4 class="text-lg font-bold text-slate-900 mb-2">Pilih Instansi</h4>
                    <p class="text-sm text-slate-600 leading-relaxed font-medium px-2">Cari dan pilih SKPD yang paling sesuai dengan bidangmu.</p>
                </div>

                <!-- Step 3 -->
                <div class="relative text-center reveal delay-300">
                    <div class="w-16 h-16 mx-auto bg-white border-4 border-[#F8FAFC] rounded-2xl shadow-sm flex items-center justify-center text-xl font-extrabold text-[#00236F] mb-6 relative z-10 rotate-3 hover:rotate-0 transition-transform">
                        3
                    </div>
                    <h4 class="text-lg font-bold text-slate-900 mb-2">Upload Berkas</h4>
                    <p class="text-sm text-slate-600 leading-relaxed font-medium px-2">Kirim dokumen persyaratan magang langsung lewat sistem.</p>
                </div>

                <!-- Step 4 -->
                <div class="relative text-center reveal delay-400">
                    <div class="w-16 h-16 mx-auto bg-[#00236F] border-4 border-[#F8FAFC] rounded-2xl shadow-md flex items-center justify-center text-white mb-6 relative z-10 -rotate-3 hover:rotate-0 transition-transform">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <h4 class="text-lg font-bold text-slate-900 mb-2">Mulai Magang</h4>
                    <p class="text-sm text-slate-600 leading-relaxed font-medium px-2">Pantau persetujuan admin dan bersiap mulai magang.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Daftar Instansi -->
<section id="instansi" class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12 reveal">
            <div class="max-w-2xl">
                <h2 class="text-3xl md:text-4xl font-extrabold text-[#00236F] mb-4">Daftar Instansi (SKPD)</h2>
                <p class="text-slate-600 text-lg font-medium">Temukan tempat magang yang tepat untuk mengembangkan potensimu di lingkungan Pemerintahan Kota.</p>
            </div>
            <div>
                <a href="{{ route('skpd.index') }}" class="inline-flex items-center gap-2 bg-[#FEA619] hover:bg-amber-500 text-slate-900 font-bold px-5 py-2.5 rounded-xl text-sm transition shadow-sm group">
                    Lihat Semua 
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8 reveal delay-100">
            @forelse($skpds as $skpd)
                <x-skpd-card :skpd="$skpd" />
            @empty
                <div class="col-span-full py-20 text-center bg-slate-50 rounded-3xl border border-slate-100">
                    <div class="w-16 h-16 bg-slate-200 rounded-full flex items-center justify-center text-slate-400 mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-700 mb-1">Belum Ada Data</h3>
                    <p class="text-slate-500 font-medium">Daftar instansi (SKPD) saat ini belum tersedia.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- CTA / Call to Action -->
<section class="py-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto mb-20 reveal">
    <div class="relative bg-[#00236F] bg-pattern rounded-[2.5rem] overflow-hidden px-8 py-16 md:px-16 md:py-20 text-center md:text-left flex flex-col md:flex-row items-center justify-between gap-10 shadow-2xl shadow-blue-900/20 border border-blue-800">
        <div class="max-w-2xl relative z-10">
            <h2 class="text-3xl md:text-4xl font-extrabold text-white mb-4 tracking-tight">Siap Memulai Perjalananmu?</h2>
            <p class="text-blue-100 text-lg mb-0 font-medium">Daftar sekarang. Gratis, resmi, dan didukung penuh oleh Pemerintah Kota Banjarmasin.</p>
        </div>
        <div class="shrink-0 relative z-10">
            @auth
                <a href="{{ route('skpd.index') }}" class="inline-flex justify-center items-center gap-2 bg-[#FEA619] hover:bg-amber-400 text-slate-900 px-8 py-4 rounded-xl font-bold transition-all shadow-lg active:scale-95 text-lg">
                    Cari Instansi Magang
                </a>
            @else
                <a href="{{ route('login') }}" class="inline-flex justify-center items-center gap-2 bg-[#FEA619] hover:bg-amber-400 text-slate-900 px-8 py-4 rounded-xl font-bold transition-all shadow-lg active:scale-95 text-lg">
                    Daftar Sekarang
                </a>
            @endauth
        </div>
    </div>
</section>

<script>
    // Script ringan untuk mendeteksi scroll dan menjalankan animasi CSS
    document.addEventListener("DOMContentLoaded", () => {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: "0px 0px -50px 0px"
        });

        document.querySelectorAll('.reveal').forEach((el) => {
            observer.observe(el);
        });
    });
</script>
@endsection