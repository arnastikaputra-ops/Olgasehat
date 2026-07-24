@extends('layouts.app')

@section('title', 'Olga Sehat Venue Management - Kelola Venue Lebih Mudah')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Lily+Script+One&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<style>
    .step-number {
        font-family: 'Lily Script One', cursive;
    }
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
<section class="container mx-auto px-6 pt-24 md:pt-28 pb-12 md:pb-16 max-w-7xl" data-aos="fade-up">
    <div class="flex flex-col lg:flex-row items-center lg:items-center lg:space-x-12">
        <!-- Left: Text Content -->
        <div class="lg:w-1/2 mb-8 lg:mb-0" data-aos="fade-up-right" data-aos-delay="100">
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-6 leading-tight" data-translate>
                Aplikasi kelola lapangan olahraga untuk Android & iOS.
            </h1>
            <p class="text-gray-700 text-base md:text-lg mb-6 leading-relaxed" data-translate>
                Kelola venue dengan lebih simpel, fleksibel, dan profitable sekaligus terhubung dengan puluhan ribu pengguna di seluruh Indonesia.
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
                    <img src="{{ asset('assets/jogging.jpg') }}" alt="Olga Sehat App" class="w-full h-auto max-w-md mx-auto rounded-xl" />
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Hero Section - Venue Management Description -->
<section class="bg-white-50 py-12 md:py-16" data-aos="fade-up">
    <div class="container mx-auto px-6 max-w-7xl">
        <div class="flex flex-col lg:flex-row items-center lg:items-center lg:space-x-12">
            <!-- Left: Image -->
            <div class="lg:w-1/2 mb-8 lg:mb-0" data-aos="fade-up-right" data-aos-delay="100">
                <img src="{{ asset('assets/iphone.png') }}" alt="Venue Management"  />
            </div>
            <!-- Right: Text Content -->
            <div class="lg:w-1/2" data-aos="fade-up-left" data-aos-delay="200">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6 leading-tight" data-translate>
                    Apa itu OlgaSehat Venue Management?
                </h2>
                <p class="text-gray-700 text-base md:text-lg mb-6 leading-relaxed" data-translate>
                    Aplikasi manajemen venue olahraga tercanggih di Indonesia yang di-design untuk meningkatkan efisiensi dan penjualan venue Anda. Nikmati kemudahan akses statistik venue, kelola jadwal booking, atur ketersediaan lapangan, pantau transaksi keuangan, hingga kelola hak akses karyawan hanya dalam genggaman.
                </p>
                <a href="/regispengelola" class="inline-flex items-center text-blue-700 font-bold hover:text-blue-900 text-lg transition-colors group" data-translate>
                    Daftarkan Venue Sekarang
                    <i class="fas fa-arrow-right ml-2 transition-transform group-hover:translate-x-1"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="container mx-auto px-6 py-12 md:py-16 max-w-7xl" data-aos="fade-up">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12">
        <!-- Feature 1 -->
        <div class="flex flex-col md:flex-row items-start space-x-6" data-aos="fade-up" data-aos-delay="100">
            <div class="flex-shrink-0 mb-4 md:mb-0">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-calendar-alt text-blue-700 text-2xl"></i>
                </div>
            </div>
            <div>
                <h3 class="text-xl md:text-2xl font-bold text-gray-900 mb-3" data-translate>Atur Booking Tanpa Ribet</h3>
                <p class="text-gray-700 text-base leading-relaxed" data-translate>
                    Tidak perlu buang waktu anda buat input booking satu-satu. Pemilik venue tinggal mantau, tiba-tiba lapangan sudah penuh!
                </p>
            </div>
        </div>

        <!-- Feature 2 -->
        <div class="flex flex-col md:flex-row items-start space-x-6" data-aos="fade-up" data-aos-delay="200">
            <div class="flex-shrink-0 mb-4 md:mb-0">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-chart-bar text-blue-700 text-2xl"></i>
                </div>
            </div>
            <div>
                <h3 class="text-xl md:text-2xl font-bold text-gray-900 mb-3" data-translate>Pantau Performa Venue</h3>
                <p class="text-gray-700 text-base leading-relaxed" data-translate>
                    Pelajari berbagai statistik penting terkait penggunaan venue untuk pengembangan bisnis yang lebih baik.
                </p>
            </div>
        </div>

        <!-- Feature 3 -->
        <div class="flex flex-col md:flex-row items-start space-x-6" data-aos="fade-up" data-aos-delay="300">
            <div class="flex-shrink-0 mb-4 md:mb-0">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-wallet text-blue-700 text-2xl"></i>
                </div>
            </div>
            <div>
                <h3 class="text-xl md:text-2xl font-bold text-gray-900 mb-3" data-translate>Berbagai Pilihan Pembayaran</h3>
                <p class="text-gray-700 text-base leading-relaxed" data-translate>
                    Buat pelanggan lebih nyaman dengan berbagai pilihan pembayaran. Bisa langsung bayar penuh atau down payment terlebih dahulu.
                </p>
            </div>
        </div>

        <!-- Feature 4 -->
        <div class="flex flex-col md:flex-row items-start space-x-6" data-aos="fade-up" data-aos-delay="400">
            <div class="flex-shrink-0 mb-4 md:mb-0">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-percent text-blue-700 text-2xl"></i>
                </div>
            </div>
            <div>
                <h3 class="text-xl md:text-2xl font-bold text-gray-900 mb-3" data-translate>Promo untuk Pelanggan</h3>
                <p class="text-gray-700 text-base leading-relaxed" data-translate>
                    Buat pelanggan baru berdatangan dan pelanggan lama makin setia lewat diskon dan voucher promo menarik dari venue.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Onboarding/Process Section -->
<section class="bg-white py-12 md:py-16" data-aos="fade-up">
    <div class="container mx-auto px-6 max-w-7xl">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-black mb-4" data-translate>
                Sudah siap membawa bisnis venue Anda ke level berikutnya?
            </h2>
            <p class="text-gray-500 text-base md:text-lg max-w-3xl mx-auto leading-relaxed" data-translate>
                Daftarkan diri Anda di OLGA SEHAT Venue Management sekarang! Prosesnya mudah, cepat, dan bisa dilakukan kapan saja. Ikuti langkah-langkah sederhana berikut ini:
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 md:gap-12">
            <!-- Step 1 -->
            <div class="text-center" data-aos="fade-up" data-aos-delay="100">
                <div class="step-number text-7xl md:text-8xl font-bold text-gray-500 mb-4">
                    1
                </div>
                <p class="text-gray-500 text-base md:text-lg leading-relaxed" data-translate>
                    Lengkapi data identitas & data usaha Anda.
                </p>
            </div>

            <!-- Step 2 -->
            <div class="text-center" data-aos="fade-up" data-aos-delay="200">
                <div class="step-number text-7xl md:text-8xl font-bold text-gray-500 mb-4">
                    2
                </div>
                <p class="text-gray-500 text-base md:text-lg leading-relaxed" data-translate>
                    Admin OlgaSehat akan melakukan verifikasi data.
                </p>
            </div>

            <!-- Step 3 -->
            <div class="text-center" data-aos="fade-up" data-aos-delay="300">
                <div class="step-number text-7xl md:text-8xl font-bold text-gray-500 mb-4">
                    3
                </div>
                <p class="text-gray-500 text-base md:text-lg leading-relaxed" data-translate>
                    Finalisasi dan melengkapi data awal yang diperlukan.
                </p>
            </div>

            <!-- Step 4 -->
            <div class="text-center" data-aos="fade-up" data-aos-delay="400">
                <div class="step-number text-7xl md:text-8xl font-bold text-gray-500 mb-4">
                    4
                </div>
                <p class="text-gray-500 text-base md:text-lg leading-relaxed" data-translate>
                    Selamat! venue Anda sudah terdaftar
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
                Kelola venue Anda dengan lebih efisien dan profitable.
            </h2>
        </div>
        <a href="/regispengelola" class="bg-white text-blue-700 font-bold px-8 py-4 rounded-lg hover:bg-gray-100 transition-all duration-300 transform hover:scale-105 shadow-lg whitespace-nowrap relative z-10" data-translate>
            Daftar Sekarang
        </a>
    </div>
</section>

<!-- Help/Contact Section -->
a<section class="bg-gray-50 py-12 md:py-16" data-aos="fade-up">
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
                <a href="https://wa.me/6287861834425?text=Halo%2C%20saya%20ingin%20bertanya%20mengenai%20layanan%20Anda" target="_blank"class="inline-flex items-center text-blue-700 font-bold hover:text-blue-900 text-lg transition-colors group"data-translate>
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

