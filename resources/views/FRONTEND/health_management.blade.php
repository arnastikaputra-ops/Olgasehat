@extends('layouts.app')

@section('title', 'Olga Sehat Health Management - Kelola Klinik Lebih Mudah')

@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<style>
    .cta-banner-pattern {
        background-color: #013D9D;
        position: relative;
    }
    .cta-banner-pattern::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-image: url('{{ asset("assets/Rectangle334.png") }}');
        background-size: 200px 200px;
        background-repeat: repeat;
        opacity: 1;
        pointer-events: none;
        z-index: 0;
    }
    .cta-banner-pattern > * {
        position: relative;
        z-index: 1;
    }
</style>


<!-- Hero Section - App Description -->
<header class="w-full h-80 bg-cover bg-center bg-no-repeat relative" 
    style="background-image: url('{{ asset("assets/klnk.png") }}');">
    <div class="absolute inset-0 bg-black bg-opacity-30"></div>

    <div class="container mx-auto h-full flex items-center px-6 relative z-10">
        <h1 class="text-white text-3xl md:text-4xl font-bold">
        </h1>
    </div>
</header>

<section class="container mx-auto px-6 pt-24 md:pt-28 pb-12 md:pb-16 max-w-7xl" data-aos="fade-up">
    <div class="flex flex-col lg:flex-row items-center lg:items-center lg:space-x-12">
        <!-- Left: Text Content -->
        <div class="lg:w-1/2 mb-8 lg:mb-0" data-aos="fade-up-right" data-aos-delay="100">
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-6 leading-tight" data-translate>
                Solusi cerdas untuk mengelola klinik Anda tersedia di Android & iOS.
            </h1>
            <p class="text-gray-700 text-base md:text-lg mb-6 leading-relaxed" data-translate>
                Solusi terpadu untuk pengelolaan klinik yang lebih efisien, fleksibel, dan berorientasi pada profit, terhubung dengan jaringan pasien di seluruh Indonesia.
            </p>
            <div class="flex items-center space-x-3 mb-6">
                <img src="{{ asset('assets/olgasehat-icon.png') }}" alt="Olga Sehat Logo" class="h-12 w-auto" />
                <span class="text-blue-700 font-bold text-lg" data-translate>#HidupLebihAktifLebihMudah</span>
            </div>
        </div>
        <!-- Right: App Image -->
        <div class="lg:w-1/2" data-aos="fade-up-left" data-aos-delay="200">
            <div class="relative">
                <div class="rounded-xl overflow-hidden">
                    <img src="{{ asset('assets/ksht.png') }}" alt="Olga Sehat Health App" class="w-full h-auto max-w-md mx-auto rounded-xl" />
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Hero Section - Health Management Description -->
<section class="bg-white-50 py-12 md:py-16" data-aos="fade-up">
    <div class="container mx-auto px-6 max-w-7xl">
        <div class="flex flex-col lg:flex-row items-center lg:items-center lg:space-x-12">
            <div class="lg:w-1/2 mb-3 lg:mb-0 flex justify-center" data-aos="fade-up-right" data-aos-delay="100">
    <img src="{{ asset('assets/lks.png') }}" alt="Health Management" class="w-[300px] h-auto" />
</div>

            <!-- Right: Text Content -->
            <div class="lg:w-1/2" data-aos="fade-up-left" data-aos-delay="200">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6 leading-tight" data-translate>
                    Apa itu OlgaSehat Klinik Management?
                </h2>
                <p class="text-gray-700 text-base md:text-lg mb-6 leading-relaxed" data-translate>
                    Aplikasi manajemen klinik modern di Indonesia yang didesain untuk membantu Anda mengelola operasional dengan lebih efisien dan meningkatkan pendapatan. Dapatkan kemudahan dalam mengakses statistik operasional klinik, mengatur jadwal praktik dokter, memantau keuangan, serta mengelola peran dan hak akses staf melalui satu platform terintegrasi.
                </p>
                <a href="{{ url('/regispengelolakesehatan') }}" class="inline-flex items-center text-blue-700 font-bold hover:text-blue-900 text-lg transition-colors group" data-translate>
                    Daftarkan Klinik Sekarang
                    <i class="fas fa-arrow-right ml-2 transition-transform group-hover:translate-x-1"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="container mx-auto px-6 py-12 md:py-16 max-w-7xl" data-aos="fade-up">
    
    <div class="grid grid-cols-1 md:grid-cols-4 gap-10 text-center">

        <!-- Feature 1 -->
        <div data-aos="fade-up" data-aos-delay="100">
            <div class="w-20 h-20 mx-auto mb-4 bg-white rounded-full shadow-md flex items-center justify-center">
                <i class="fas fa-calendar-check text-blue-600 text-3xl"></i>
            </div>
            <h3 class="text-lg md:text-xl font-bold text-gray-900 mb-2">Atur Booking Tanpa Ribet</h3>
            <p class="text-gray-700 text-sm leading-relaxed">
                Tidak perlu buang waktu untuk mencatat jadwal satu per satu. Pemilik klinik cukup memantau jadwal dokter dan pasien tersusun otomatis!
            </p>
        </div>

        <!-- Feature 2 -->
        <div data-aos="fade-up" data-aos-delay="200">
            <div class="w-20 h-20 mx-auto mb-4 bg-white rounded-full shadow-md flex items-center justify-center">
                <i class="fas fa-chart-line text-blue-600 text-3xl"></i>
            </div>
            <h3 class="text-lg md:text-xl font-bold text-gray-900 mb-2">Pantau Performa Klinik</h3>
            <p class="text-gray-700 text-sm leading-relaxed">
                Pelajari berbagai statistik penting terkait operasional dan layanan untuk pengembangan klinik yang lebih efisien.
            </p>
        </div>

        <!-- Feature 3 -->
        <div data-aos="fade-up" data-aos-delay="300">
            <div class="w-20 h-20 mx-auto mb-4 bg-white rounded-full shadow-md flex items-center justify-center">
                <i class="fas fa-file-invoice-dollar text-blue-600 text-3xl"></i>
            </div>
            <h3 class="text-lg md:text-xl font-bold text-gray-900 mb-2">Berbagai Pilihan Pembayaran</h3>
            <p class="text-gray-700 text-sm leading-relaxed">
                Berikan kemudahan pasien dengan metode pembayaran fleksibel seperti tunai, asuransi, atau deposit.
            </p>
        </div>

        <!-- Feature 4 -->
        <div data-aos="fade-up" data-aos-delay="400">
            <div class="w-20 h-20 mx-auto mb-4 bg-white rounded-full shadow-md flex items-center justify-center">
                <i class="fas fa-percent text-blue-600 text-3xl"></i>
            </div>
            <h3 class="text-lg md:text-xl font-bold text-gray-900 mb-2">Promo untuk Pasien</h3>
            <p class="text-gray-700 text-sm leading-relaxed">
                Tarik pasien baru dan tingkatkan loyalitas dengan diskon, paket layanan, dan promo menarik klinik Anda.
            </p>
        </div>

    </div>
</section>

<section class="bg-white py-12 md:py-16" data-aos="fade-up">
    <div class="container mx-auto px-6 max-w-7xl">

        <!-- Title + Description -->
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-black mb-4" data-translate>
                Sudah siap membawa bisnis klinik Anda ke level berikutnya?
            </h2>

            <p class="text-gray-500 text-base md:text-lg max-w-3xl mx-auto leading-relaxed" data-translate>
                Daftarkan diri Anda di AYO Klinik Management sekarang! Prosesnya mudah, cepat, dan bisa dilakukan kapan saja.
                Ikuti langkah-langkah sederhana berikut ini:
            </p>
        </div>

        <!-- Steps (mirip desain: icon bulat + title + text) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 text-center">

            <!-- Step 1 -->
            <div data-aos="fade-up" data-aos-delay="100">
                <div class="w-20 h-20 mx-auto flex items-center justify-center rounded-full bg-blue-100 shadow-sm mb-3">
                    <span class="text-blue-700 font-bold text-3xl">1</span>
                </div>

                <h4 class="font-semibold text-black mb-1">Registrasi</h4>
                <p class="text-gray-500 text-sm leading-relaxed">
                    Lengkapi data identitas dan informasi klinik Anda.
                </p>
            </div>

            <!-- Step 2 -->
            <div data-aos="fade-up" data-aos-delay="200">
                <div class="w-20 h-20 mx-auto flex items-center justify-center rounded-full bg-blue-100 shadow-sm mb-3">
                    <span class="text-blue-700 font-bold text-3xl">2</span>
                </div>

                <h4 class="font-semibold text-black mb-1">Verifikasi</h4>
                <p class="text-gray-500 text-sm leading-relaxed">
                    Admin AYO Klinik akan melakukan verifikasi data Anda.
                </p>
            </div>

            <!-- Step 3 -->
            <div data-aos="fade-up" data-aos-delay="300">
                <div class="w-20 h-20 mx-auto flex items-center justify-center rounded-full bg-blue-100 shadow-sm mb-3">
                    <span class="text-blue-700 font-bold text-3xl">3</span>
                </div>

                <h4 class="font-semibold text-black mb-1">Aktivasi</h4>
                <p class="text-gray-500 text-sm leading-relaxed">
                    Finalisasi dan lengkapi data awal yang diperlukan untuk menjalankan klinik Anda di platform.
                </p>
            </div>

            <!-- Step 4 -->
            <div data-aos="fade-up" data-aos-delay="400">
                <div class="w-20 h-20 mx-auto flex items-center justify-center rounded-full bg-blue-100 shadow-sm mb-3">
                    <span class="text-blue-700 font-bold text-3xl">4</span>
                </div>

                <h4 class="font-semibold text-black mb-1">Mulai Kelola Klinik</h4>
                <p class="text-gray-500 text-sm leading-relaxed">
                    Mulai kelola klinik Anda dengan lebih efisien dan profitable.
                </p>
            </div>

        </div>
    </div>
</section>


<!-- Call to Action Banner -->
<section class="container mx-auto px-6 py-12 md:py-16 max-w-7xl" data-aos="fade-up">
    <div class="cta-banner-pattern rounded-2xl p-8 md:p-12 flex flex-col md:flex-row items-center justify-between shadow-2xl relative overflow-hidden">
        <div class="mb-6 md:mb-0 md:mr-8 relative z-10">
            <h2 class="text-2xl md:text-3xl font-bold text-white mb-2" data-translate>
                Kelola Klinik Anda dengan lebih efisien dan profitable.
            </h2>
        </div>
        <a href="{{ url('/regispengelolakesehatan') }}" class="bg-white text-blue-700 font-bold px-8 py-4 rounded-lg hover:bg-gray-100 transition-all duration-300 transform hover:scale-105 shadow-lg whitespace-nowrap relative z-10" data-translate>
    Daftar Sekarang
</a>
    </div>
</section>

<!-- Help/Contact Section -->
<section class="bg-gray-50 py-12 md:py-16" data-aos="fade-up">
    <div class="container mx-auto px-6 max-w-7xl">
        <div class="flex flex-col lg:flex-row items-center lg:items-center lg:space-x-12">
            <!-- Left: Image -->
            <div class="lg:w-1/2 mb-8 lg:mb-0" data-aos="fade-up-right" data-aos-delay="100">
                <img src="{{ asset('assets/hubungi.jpg') }}" alt="Support Team" class="rounded-xl shadow-xl w-full h-auto object-cover" />
            </div>
            <!-- Right: Text Content -->
            <div class="lg:w-1/2" data-aos="fade-up-left" data-aos-delay="200">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6 leading-tight" data-translate>
                    Ada yang bisa dibantu?
                </h2>
                <p class="text-gray-700 text-base md:text-lg mb-6 leading-relaxed" data-translate>
                    Kami siap menjawab setiap pertanyaan yang kamu ajukan mengenai kolaborasi bersama OlgaSehat. Jangan ragu untuk menghubungi kami!
                </p>
                @php
                    $waContact = \App\Models\ContactUs::where('type', 'whatsapp')
                        ->orWhere('title', 'like', '%whatsapp%')
                        ->orWhere('title', 'like', '%wa%')
                        ->first();
                    $rawWa = $waContact->kontak ?? '0812-3456-7890';
                    $cleanWa = preg_replace('/[^0-9]/', '', $rawWa);
                    if (\Illuminate\Support\Str::startsWith($cleanWa, '0')) {
                        $cleanWa = '62' . substr($cleanWa, 1);
                    }
                    $waLink = "https://wa.me/{$cleanWa}?text=" . urlencode("Halo, saya ingin bertanya mengenai layanan Anda");
                @endphp
                <a href="{{ $waLink }}" target="_blank" class="inline-flex items-center text-blue-700 font-bold hover:text-blue-900 text-lg transition-colors group" data-translate>
                    Hubungi Kami Sekarang!
                    <i class="fas fa-arrow-right ml-2 transition-transform group-hover:translate-x-1"></i>
                </a>
            </div>
        </div>
    </div>
</section>

@endsection

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init({
    duration: 1000,
    once: true,
  });
</script>


