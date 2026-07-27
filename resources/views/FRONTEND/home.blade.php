@extends('layouts.app')

@section('content')

<style>
    /* Animasi Banner Carousel */
    .banner-slide {
        transition: opacity 1s ease-in-out, transform 1s ease-in-out;
    }
    
    .banner-image {
        transition: transform 10s ease-in-out;
    }
    
    .banner-slide.active .banner-image {
        transform: scale(1.05);
    }
    
    .banner-content {
        transition: opacity 0.8s ease-in-out, transform 0.8s ease-in-out;
    }
    
    .banner-dot {
        transition: all 0.3s ease-in-out;
    }
    
    .banner-dot:hover {
        transform: scale(1.2);
    }
    
    /* Smooth fade effect */
    @keyframes fadeInZoom {
        from {
            opacity: 0;
            transform: scale(1.05);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }
    
    .banner-slide.active {
        animation: fadeInZoom 1s ease-in-out;
    }
</style>

@if(isset($homeBanners) && $homeBanners->count() > 0)
    <!-- Hero Section dengan Carousel Banner -->
    <section class="relative h-[500px] md:h-[600px] overflow-hidden" data-aos="fade-in">
        <div id="homeBannerCarousel" class="relative h-full">
            <div class="carousel-container h-full relative overflow-hidden">
                @foreach($homeBanners as $index => $banner)
                    <div class="banner-slide absolute inset-0 transition-all duration-1000 ease-in-out {{ $index === 0 ? 'opacity-100 z-10 scale-100 active' : 'opacity-0 z-0 scale-105' }}" data-index="{{ $index }}">
                        <div class="relative bg-cover bg-center h-full banner-image" style="background-image: url('{{ asset('fotogaleri/'.$banner->foto) }}');">
                            <div class="absolute inset-0 bg-black bg-opacity-50 flex flex-col justify-center px-6">
                                <div class="container mx-auto text-white text-left banner-content {{ $index === 0 ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4' }}" data-aos="fade-up-right" data-aos-delay="200">
                                    <h1 class="text-4xl md:text-5xl font-bold mb-4" data-translate>
                                        Kini <span class="font-extrabold">Olga Sehat</span> Hadir<br />
                                        Untuk Gaya Hidup Sehat
                                    </h1>
                                    <p class="text-base md:text-xl mb-4 max-w-3xl" data-translate>
                                        Selamat datang di OLGA SEHAT<br />
                                        Satu platform untuk booking lapangan, klinik, komunitas olahraga, dan cek kesehatan.
                                    </p>
                                    <p class="text-base md:text-xl font-semibold italic" data-translate>#HidupLebihAktif kini lebih mudah!</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @if($homeBanners->count() > 1)
                <button id="bannerPrev" class="absolute left-4 top-1/2 -translate-y-1/2 z-20 bg-white/30 hover:bg-white/50 backdrop-blur-sm text-white p-3 rounded-full transition-all duration-300 hover:scale-110" aria-label="Previous">
                    <i class="fas fa-chevron-left text-xl"></i>
                </button>
                <button id="bannerNext" class="absolute right-4 top-1/2 -translate-y-1/2 z-20 bg-white/30 hover:bg-white/50 backdrop-blur-sm text-white p-3 rounded-full transition-all duration-300 hover:scale-110" aria-label="Next">
                    <i class="fas fa-chevron-right text-xl"></i>
                </button>
                <div class="absolute bottom-4 left-1/2 -translate-x-1/2 z-20 flex space-x-2">
                    @foreach($homeBanners as $index => $banner)
                        <button type="button" class="banner-dot w-3 h-3 rounded-full transition-all duration-300 {{ $index === 0 ? 'bg-white w-8' : 'bg-white/50 hover:bg-white/75' }}" data-index="{{ $index }}" aria-label="Slide {{ $index + 1 }}"></button>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@else
    <!-- Fallback jika tidak ada banner -->
    <section class="relative bg-cover bg-center h-[500px] md:h-[600px]" style="background-image: url('{{ asset('assets/banten-indonesia-august-02-2022-600nw-2455954305.webp') }}');" data-aos="fade-in">
        <div class="absolute inset-0 bg-black bg-opacity-50 flex flex-col justify-center px-6">
            <div class="container mx-auto text-white text-left" data-aos="fade-up-right" data-aos-delay="200">
                <h1 class="text-4xl md:text-5xl font-bold mb-4" data-translate>
                    Kini <span class="font-extrabold">Olga Sehat</span> Hadir<br />
                    Untuk Gaya Hidup Sehat
                </h1>
                <p class="text-base md:text-xl mb-4 max-w-3xl" data-translate>
                    Selamat datang di OLGA SEHAT<br />
                    Satu platform untuk booking lapangan, klinik, komunitas olahraga, dan cek kesehatan.
                </p>
                <p class="text-base md:text-xl font-semibold italic" data-translate>#HidupLebihAktif kini lebih mudah!</p>
            </div>
        </div>
    </section>
@endif

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<section class="container mx-auto px-6 py-12 md:py-16 flex flex-col lg:flex-row items-center lg:items-center lg:space-x-10 max-w-6xl" data-aos="fade-up">

    <div class="lg:w-1/2 mb-8 lg:mb-0 order-2 lg:order-1" data-aos="fade-up-right" data-aos-delay="100">

        <div class="inline-flex space-x-2 mb-6 p-1 bg-gray-100 rounded-full shadow-inner" role="tablist" aria-label="Toggle Kelola Fasilitas">
            <button id="btnPemilikLapangan" role="tab" aria-selected="true" aria-controls="contentPemilikLapangan" tabindex="0"
                    class="bg-blue-700 text-white text-sm font-bold rounded-full px-4 py-1.5 transition-all duration-300 focus:outline-none focus:ring-4 focus:ring-blue-400 focus:ring-opacity-50">
                PEMILIK LAPANGAN
            </button>
            <button id="btnPenyewaLapangan" role="tab" aria-selected="false" aria-controls="contentPenyewaLapangan" tabindex="-1"
                    class="bg-transparent text-gray-700 text-sm font-semibold rounded-full px-4 py-1.5 transition-all duration-300 hover:bg-white hover:shadow-sm focus:outline-none focus:ring-4 focus:ring-gray-400 focus:ring-opacity-50">
                PENYEWA
            </button>
        </div>

        <div id="contentPemilikLapangan" role="tabpanel" aria-labelledby="btnPemilikLapangan" tabindex="0" class="space-y-4">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 leading-snug" data-translate>Kelola fasilitas lebih <span class="text-blue-700">praktis dan menguntungkan</span>.</h2>
            <p class="text-gray-700 max-w-lg text-base leading-relaxed" data-translate>
                Waktunya buat venue Anda lebih dari sekadar venue. Semuanya dimulai dengan pengelolaan yang simpel, fleksibel, dan profitable lewat OLGA SEHAT Venue Management.
            </p>
            <a href="{{ route('venue.management') }}" class="inline-flex items-center text-blue-700 font-bold hover:text-blue-900 text-base transition-colors group" data-translate>
                Lihat Selengkapnya
                <i class="fas fa-arrow-right ml-2 transition-transform group-hover:translate-x-1"></i>
            </a>
        </div>

        <div id="contentPenyewaLapangan" role="tabpanel" aria-labelledby="btnPenyewaLapangan" tabindex="0" class="hidden space-y-4">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 leading-snug" data-translate>Sewa lapangan dengan <span class="text-blue-700">mudah dan cepat</span>.</h2>
            <p class="text-gray-700 max-w-lg text-base leading-relaxed" data-translate>
                Ada rencana berolahraga minggu ini tapi belum tahu mau main di mana? Atau tidak sempat jauh-jauh datang ke venue hanya untuk booking lapangan? Kami punya solusinya!
            </p>
            <a href="{{ route('frontend.venue') }}" class="inline-flex items-center text-blue-700 font-bold hover:text-blue-900 text-base transition-colors group" data-translate>
                Lihat Selengkapnya
                <i class="fas fa-arrow-right ml-2 transition-transform group-hover:translate-x-1"></i>
            </a>
        </div>
    </div>

    <div class="lg:w-1/2 grid grid-cols-2 gap-3 lg:gap-4 order-1 lg:order-2" id="imageContainerPemilikLapangan" data-aos="fade-up-left" data-aos-delay="200">
        @if(isset($lapanganBanners) && $lapanganBanners->count() > 0)
            @foreach($lapanganBanners as $index => $banner)
                <img src="{{ asset('fotogaleri/'.$banner->foto) }}" alt="Lapangan Banner {{ $index + 1 }}"
                     class="rounded-xl shadow-xl object-cover h-36 md:h-48 w-full transform transition duration-500 hover:scale-[1.03] hover:shadow-2xl" />
            @endforeach
            @if($lapanganBanners->count() < 4)
                @for($i = $lapanganBanners->count(); $i < 4; $i++)
                    <div class="rounded-xl shadow-xl bg-gray-200 h-36 md:h-48 w-full flex items-center justify-center">
                        <span class="text-gray-400 text-sm">Gambar {{ $i + 1 }}</span>
                    </div>
                @endfor
            @endif
        @else
            {{-- Fallback jika tidak ada data --}}
            <img src="{{ asset('assets/MU Sport Center.jpeg') }}" alt="MU Sport Center"
                 class="rounded-xl shadow-xl object-cover h-36 md:h-48 w-full transform transition duration-500 hover:scale-[1.03] hover:shadow-2xl" />
            <img src="{{ asset('assets/Imbo Sport Center.webp') }}" alt="Imbo Sport Center"
                 class="rounded-xl shadow-xl object-cover h-36 md:h-48 w-full transform transition duration-500 hover:scale-[1.03] hover:shadow-2xl" />
            <img src="{{ asset('assets/DC Arena Bali.jpeg') }}" alt="DC Arena Bali"
                 class="rounded-xl shadow-xl object-cover h-36 md:h-48 w-full transform transition duration-500 hover:scale-[1.03] hover:shadow-2xl" />
            <img src="{{ asset('assets/Arena Sport.jpg') }}" alt="Arena Sport"
                 class="rounded-xl shadow-xl object-cover h-36 md:h-48 w-full transform transition duration-500 hover:scale-[1.03] hover:shadow-2xl" />
        @endif
    </div>

    <div class="lg:w-1/2 grid grid-cols-2 gap-3 lg:gap-4 hidden order-1 lg:order-2" id="imageContainerPenyewaLapangan">
        @if(isset($lapanganBanners) && $lapanganBanners->count() > 0)
            @foreach($lapanganBanners as $index => $banner)
                <img src="{{ asset('fotogaleri/'.$banner->foto) }}" alt="Lapangan Banner {{ $index + 1 }}"
                     class="rounded-xl shadow-xl object-cover h-36 md:h-48 w-full transform transition duration-500 hover:scale-[1.03] hover:shadow-2xl" />
            @endforeach
            @if($lapanganBanners->count() < 4)
                @for($i = $lapanganBanners->count(); $i < 4; $i++)
                    <div class="rounded-xl shadow-xl bg-gray-200 h-36 md:h-48 w-full flex items-center justify-center">
                        <span class="text-gray-400 text-sm">Gambar {{ $i + 1 }}</span>
                    </div>
                @endfor
            @endif
        @else
            {{-- Fallback jika tidak ada data --}}
            <img src="{{ asset('assets/futsal.jpg') }}" alt="Penyewa Lapangan Futsal"
                 class="rounded-xl shadow-xl object-cover h-36 md:h-48 w-full transform transition duration-500 hover:scale-[1.03] hover:shadow-2xl" />
            <img src="{{ asset('assets/minsok.jpg') }}" alt="Penyewa Lapangan Mini Soccer"
                 class="rounded-xl shadow-xl object-cover h-36 md:h-48 w-full transform transition duration-500 hover:scale-[1.03] hover:shadow-2xl" />
            <img src="{{ asset('assets/tenis.jpg') }}" alt="Penyewa Lapangan Tenis"
                 class="rounded-xl shadow-xl object-cover h-36 md:h-48 w-full transform transition duration-500 hover:scale-[1.03] hover:shadow-2xl" />
            <img src="{{ asset('assets/padel.webp') }}" alt="Penyewa Lapangan Padel"
                 class="rounded-xl shadow-xl object-cover h-36 md:h-48 w-full transform transition duration-500 hover:scale-[1.03] hover:shadow-2xl" />
        @endif
    </div>
</section>

<section class="container mx-auto px-6 py-12 md:py-16 flex flex-col lg:flex-row items-center lg:items-center lg:space-x-10 max-w-6xl" data-aos="fade-up">

    <div class="lg:w-1/2 grid grid-cols-2 gap-3 lg:gap-4 mb-8 lg:mb-0 order-1 lg:order-1" id="imageContainerPemilikKesehatan" data-aos="fade-up-left" data-aos-delay="100">
        @if(isset($kesehatanBanners) && $kesehatanBanners->count() > 0)
            @foreach($kesehatanBanners as $index => $banner)
                <img src="{{ asset('fotogaleri/'.$banner->foto) }}" alt="Kesehatan Banner {{ $index + 1 }}"
                     class="rounded-xl shadow-xl object-cover h-36 md:h-48 w-full transform transition duration-500 hover:scale-[1.03] hover:shadow-2xl" />
            @endforeach
            @if($kesehatanBanners->count() < 4)
                @for($i = $kesehatanBanners->count(); $i < 4; $i++)
                    <div class="rounded-xl shadow-xl bg-gray-200 h-36 md:h-48 w-full flex items-center justify-center">
                        <span class="text-gray-400 text-sm">Gambar {{ $i + 1 }}</span>
                    </div>
                @endfor
            @endif
        @else
            {{-- Fallback jika tidak ada data --}}
            <img src="{{ asset('assets/ksht.png') }}" alt="Layanan Kesehatan 1"
                 class="rounded-xl shadow-xl object-cover h-36 md:h-48 w-full transform transition duration-500 hover:scale-[1.03] hover:shadow-2xl" />
            <img src="{{ asset('assets/klnk.png') }}" alt="Klinik Kesehatan 2"
                 class="rounded-xl shadow-xl object-cover h-36 md:h-48 w-full transform transition duration-500 hover:scale-[1.03] hover:shadow-2xl" />
            <img src="{{ asset('assets/lks.png') }}" alt="Fasilitas Medis 3"
                 class="rounded-xl shadow-xl object-cover h-36 md:h-48 w-full transform transition duration-500 hover:scale-[1.03] hover:shadow-2xl" />
            <img src="{{ asset('assets/sports-tools.jpg') }}" alt="Checkup 4"
                 class="rounded-xl shadow-xl object-cover h-36 md:h-48 w-full transform transition duration-500 hover:scale-[1.03] hover:shadow-2xl" />
        @endif
    </div>

    <div class="lg:w-1/2 order-2 lg:order-2" data-aos="fade-up-right" data-aos-delay="200">

        <div class="inline-flex space-x-2 mb-6 p-1 bg-gray-100 rounded-full shadow-inner" role="tablist" aria-label="Toggle Kelola Fasilitas Kesehatan">
            <button id="btnPemilikKesehatan" role="tab" aria-selected="true" aria-controls="contentPemilikKesehatan" tabindex="0"
                    class="bg-blue-700 text-white text-sm font-bold rounded-full px-4 py-1.5 transition-all duration-300 focus:outline-none focus:ring-4 focus:ring-blue-400 focus:ring-opacity-50">
                PEMILIK KESEHATAN
            </button>
            <button id="btnPenyewaKesehatan" role="tab" aria-selected="false" aria-controls="contentPenyewaKesehatan" tabindex="-1"
                    class="bg-transparent text-gray-700 text-sm font-semibold rounded-full px-4 py-1.5 transition-all duration-300 hover:bg-white hover:shadow-sm focus:outline-none focus:ring-4 focus:ring-gray-400 focus:ring-opacity-50">
                PENYEWA
            </button>
        </div>

        <div id="contentPemilikKesehatan" role="tabpanel" aria-labelledby="btnPemilikKesehatan" tabindex="0" class="space-y-4">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 leading-snug" data-translate>Kelola layanan kesehatan lebih <span class="text-blue-700">mudah dan menguntungkan</span>.</h2>
            <p class="text-gray-700 max-w-lg text-base leading-relaxed" data-translate>
                Waktunya buat tempat sehat Anda jadi lebih dari sekadar fasilitas. Semuanya dimulai dengan pengelolaan yang simpel, fleksibel, dan profitable lewat OLGA SEHAT Health Management.
            </p>
            <a href="{{ route('health.management') }}" class="inline-flex items-center text-blue-700 font-bold hover:text-blue-900 text-base transition-colors group" data-translate>
                Lihat Selengkapnya
                <i class="fas fa-arrow-right ml-2 transition-transform group-hover:translate-x-1"></i>
            </a>
        </div>

        <div id="contentPenyewaKesehatan" role="tabpanel" aria-labelledby="btnPenyewaKesehatan" tabindex="0" class="hidden space-y-4">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 leading-snug" data-translate>Booking cek kesehatan dan klinik <span class="text-blue-700">lebih mudah</span>.</h2>
            <p class="text-gray-700 max-w-lg text-base leading-relaxed" data-translate>
                Cari fasilitas kesehatan terdekat, seperti klinik, fisioterapi, dan pusat kebugaran. Jadwalkan sesi Anda tanpa ribet. Kesehatan Anda kini di ujung jari.
            </p>
            <a href="{{ route('frontend.healthy') }}" class="inline-flex items-center text-blue-700 font-bold hover:text-blue-900 text-base transition-colors group" data-translate>
                Lihat Selengkapnya
                <i class="fas fa-arrow-right ml-2 transition-transform group-hover:translate-x-1"></i>
            </a>
        </div>
    </div>

    <div class="lg:w-1/2 grid grid-cols-2 gap-3 lg:gap-4 hidden order-1 lg:order-1" id="imageContainerPenyewaKesehatan">
        @if(isset($kesehatanBanners) && $kesehatanBanners->count() > 0)
            @foreach($kesehatanBanners as $index => $banner)
                <img src="{{ asset('fotogaleri/'.$banner->foto) }}" alt="Kesehatan Banner {{ $index + 1 }}"
                     class="rounded-xl shadow-xl object-cover h-36 md:h-48 w-full transform transition duration-500 hover:scale-[1.03] hover:shadow-2xl" />
            @endforeach
            @if($kesehatanBanners->count() < 4)
                @for($i = $kesehatanBanners->count(); $i < 4; $i++)
                    <div class="rounded-xl shadow-xl bg-gray-200 h-36 md:h-48 w-full flex items-center justify-center">
                        <span class="text-gray-400 text-sm">Gambar {{ $i + 1 }}</span>
                    </div>
                @endfor
            @endif
        @else
            {{-- Fallback jika tidak ada data --}}
            <img src="{{ asset('assets/ksht.png') }}" alt="Penyewa Kesehatan 1"
                 class="rounded-xl shadow-xl object-cover h-36 md:h-48 w-full transform transition duration-500 hover:scale-[1.03] hover:shadow-2xl" />
            <img src="{{ asset('assets/klnk.png') }}" alt="Penyewa Kesehatan 2"
                 class="rounded-xl shadow-xl object-cover h-36 md:h-48 w-full transform transition duration-500 hover:scale-[1.03] hover:shadow-2xl" />
            <img src="{{ asset('assets/lks.png') }}" alt="Penyewa Kesehatan 3"
                 class="rounded-xl shadow-xl object-cover h-36 md:h-48 w-full transform transition duration-500 hover:scale-[1.03] hover:shadow-2xl" />
            <img src="{{ asset('assets/sports-tools.jpg') }}" alt="Penyewa Kesehatan 4"
                 class="rounded-xl shadow-xl object-cover h-36 md:h-48 w-full transform transition duration-500 hover:scale-[1.03] hover:shadow-2xl" />
        @endif
    </div>
</section>

@if(Auth::check() && Auth::user()->role === 'user' && isset($activities))
<section class="bg-gray-50 py-16 md:py-20" data-aos="fade-up">
    <div class="container mx-auto px-6 text-center max-w-6xl">

        <h2 class="text-3xl md:text-4xl font-bold mb-4 text-gray-900 leading-tight" data-aos="fade-up" data-aos-delay="100">
            Temukan <span class="text-blue-700">Komunitas & Aktivitas</span> Favoritmu!
        </h2>
        <p class="text-gray-600 mb-10 max-w-3xl mx-auto text-lg leading-relaxed" data-aos="fade-up" data-aos-delay="200">
            Buat kegiatan seru bersama Olga Sehat! Komunitas, Membership, atau Event Seru dari berbagai kegiatan olahraga di sekitarmu. Saatnya jalin silaturahmi dan tambah semangat di lapangan!
        </p>

        <div class="flex flex-col sm:flex-row justify-center space-y-3 sm:space-y-0 sm:space-x-4 mb-14" data-aos="fade-up" data-aos-delay="300">

            <a href="/buat-aktivitas" class="block w-full sm:w-auto">
            <button class="bg-blue-700 text-white text-sm font-bold rounded-full px-6 py-3 shadow-lg hover:bg-blue-800 transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-4 focus:ring-blue-400 focus:ring-opacity-50">
                <i class="fas fa-plus-circle mr-2"></i> BUAT AKTIVITAS BARU
            </button>
            </a>

            <a href="/community" class="block w-full sm:w-auto">
            <button class="w-full bg-white text-gray-800 text-sm font-bold rounded-full px-6 py-3 border border-gray-300 shadow-md hover:bg-gray-100 transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-4 focus:ring-gray-300 focus:ring-opacity-50">
                <i class="fas fa-users mr-2"></i> GABUNG AKTIVITAS
            </button>
            </a>
        </div>

        <div class="flex overflow-x-auto gap-4 pb-4 max-w-5xl mx-auto lg:overflow-x-visible lg:grid lg:grid-cols-3 lg:gap-4 lg:pb-0" data-aos="fade-up" data-aos-delay="300">

            @if($activities->count() > 0)
                @foreach($activities as $index => $activity)
                    <div class="relative group overflow-hidden rounded-xl shadow-xl transform transition duration-500 hover:scale-[1.02] flex-shrink-0 w-72 sm:w-full" data-aos="fade-up" data-aos-delay="{{ 400 + ($index * 100) }}">
                        @if($activity->banner)
                            <img src="{{ asset('fotoaktivitas/'.$activity->banner) }}" alt="{{ $activity->nama }}"
                                class="object-cover h-56 md:h-64 w-full transition duration-500 group-hover:opacity-85" />
                        @else
                            <img src="{{ asset('assets/komunitas.png') }}" alt="{{ $activity->nama }}"
                                class="object-cover h-56 md:h-64 w-full transition duration-500 group-hover:opacity-85" />
                        @endif
                        <div class="absolute inset-0 bg-black bg-opacity-30 flex flex-col items-start justify-end p-4">
                            @if($activity->jenis == 'komunitas')
                                <span class="inline-block bg-green-600 text-white text-xs font-bold px-2 py-0.5 rounded-full mb-2">KOMUNITAS</span>
                            @elseif($activity->jenis == 'membership')
                                <span class="inline-block bg-yellow-600 text-white text-xs font-bold px-2 py-0.5 rounded-full mb-2">MEMBERSHIP</span>
                            @elseif($activity->jenis == 'event')
                                <span class="inline-block bg-blue-600 text-white text-xs font-bold px-2 py-0.5 rounded-full mb-2">EVENT OLAHRAGA</span>
                            @endif
                            <h3 class="text-white text-lg font-bold">{{ $activity->nama }}</h3>
                            <a href="/community-detail/{{ $activity->id }}" class="absolute inset-0"></a>
                        </div>
                    </div>
                @endforeach
                @if($activities->count() < 3)
                    @for($i = $activities->count(); $i < 3; $i++)
                        <div class="relative group overflow-hidden rounded-xl shadow-xl bg-gray-200 h-56 md:h-64 w-full flex items-center justify-center flex-shrink-0 w-72 sm:w-full">
                            <span class="text-gray-400 text-sm">Aktivitas {{ $i + 1 }}</span>
                        </div>
                    @endfor
                @endif
            @else
                {{-- Fallback jika tidak ada data --}}
                <div class="relative group overflow-hidden rounded-xl shadow-xl transform transition duration-500 hover:scale-[1.02] flex-shrink-0 w-72 sm:w-full" data-aos="fade-up" data-aos-delay="400">
                    <img src="{{ asset('assets/komunitas.png') }}" alt="Komunitas Futsal Jakarta"
                                class="object-cover h-56 md:h-64 w-full transition duration-500 group-hover:opacity-85" />
                    <div class="absolute inset-0 bg-black bg-opacity-30 flex flex-col items-start justify-end p-4">
                    <span class="inline-block bg-green-600 text-white text-xs font-bold px-2 py-0.5 rounded-full mb-2">KOMUNITAS</span>
                        <h3 class="text-white text-lg font-bold">Kumpulan Pemuda Futsal</h3>
                    </div>
                </div>
            @endif

        </div>

        <a href="/community" class="inline-flex items-center text-blue-700 font-bold mt-12 hover:text-blue-900 text-lg transition-colors group">
            Lihat Semua Aktivitas
            <i class="fas fa-arrow-right ml-3 transition-transform group-hover:translate-x-1"></i>
        </a>
    </div>
</section>
@endif

<section class="relative h-auto py-16 md:py-24 overflow-hidden"
    style="background-image: url('{{ asset('assets/banten-indonesia-august-02-2022-600nw-2455954305.webp') }}'); background-attachment: fixed; background-size: cover; background-position: center;" data-aos="fade-up">

    <div class="absolute inset-0 bg-black bg-opacity-80 backdrop-blur-sm"></div>

    <div class="relative container mx-auto px-6 flex flex-col justify-center items-center text-white max-w-6xl">

        <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-12 text-center max-w-4xl leading-snug" data-aos="fade-up" data-aos-delay="100" data-translate>
            Mengapa <span class="text-blue-400">Olga Sehat</span> Adalah Pilihan Terbaik?
        </h2>

        <div class="flex overflow-x-auto w-full pb-4 snap-x snap-mandatory lg:grid lg:grid-cols-4 lg:gap-5 max-w-full lg:max-w-none" data-aos="fade-up" data-aos-delay="200">

            @if(isset($programs) && $programs->count() > 0)
                @foreach($programs as $index => $program)
                    <div class="flex-shrink-0 w-72 md:min-w-72 lg:w-auto snap-center mr-4 lg:mr-0
                                bg-white/10 backdrop-blur-sm rounded-xl p-5 md:p-6 flex flex-col items-center space-y-4 border border-white/30 shadow-xl
                                transform transition-all duration-300 hover:bg-white/20 hover:scale-[1.03] hover:border-blue-400" data-aos="fade-up" data-aos-delay="{{ 300 + ($index * 100) }}">
                        <div class="w-14 h-14 bg-blue-600 rounded-full flex items-center justify-center text-white text-2xl shadow-lg">
                            @if($program->icon)
                                <i class="{{ $program->icon }}"></i>
                            @else
                                <i class="fas fa-star"></i>
                            @endif
                        </div>
                        <p class="text-center font-bold text-base md:text-lg leading-snug" data-translate>{{ $program->title }}</p>
                        @if($program->description)
                            <p class="text-xs text-white/80" data-translate>{{ $program->description }}</p>
                        @endif
                    </div>
                @endforeach
            @else
                {{-- Fallback jika tidak ada data --}}
                <div class="flex-shrink-0 w-72 md:min-w-72 lg:w-auto snap-center mr-4 lg:mr-0
                            bg-white/10 backdrop-blur-sm rounded-xl p-5 md:p-6 flex flex-col items-center space-y-4 border border-white/30 shadow-xl
                            transform transition-all duration-300 hover:bg-white/20 hover:scale-[1.03] hover:border-blue-400" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-14 h-14 bg-blue-600 rounded-full flex items-center justify-center text-white text-2xl shadow-lg">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <p class="text-center font-bold text-base md:text-lg leading-snug" data-translate>Layanan Fasilitas Olahraga & Kesehatan Terintegrasi</p>
                    <p class="text-xs text-white/80" data-translate>Temukan lapangan, studio, hingga layanan fisioterapi dalam satu platform.</p>
                </div>
            @endif

        </div>
        </div>
</section>

<section class="container mx-auto px-6 py-16 md:py-20" data-aos="fade-up">
    <div class="max-w-6xl mx-auto flex flex-col lg:flex-row rounded-2xl overflow-hidden shadow-2xl bg-white">

        <div class="lg:w-1/3 bg-blue-700 bg-cover bg-center flex items-center justify-center p-8 md:p-10 min-h-56 lg:min-h-full"
             style="background-image: url('{{ asset('assets/blue-banner.png') }}'); background-blend-mode: multiply; background-color: rgba(29, 78, 216, 0.85);" data-aos="fade-up" data-aos-delay="100">
            <h2 class="text-white text-3xl md:text-4xl font-extrabold text-center relative z-10" data-translate>
                Apa Kata Mereka?
            </h2>
        </div>

        <div class="lg:w-2/3 bg-white p-6 md:p-10 flex flex-col justify-between" data-aos="fade-up" data-aos-delay="200">
            <div id="testimonial-container" class="relative min-h-[250px] md:min-h-[180px]">

                @php
                    $reviews = \App\Models\Review::all();
                @endphp
                @if($reviews->count() > 0)
                    @foreach($reviews as $index => $review)
                        <div class="testimonial-item absolute inset-0 transition-opacity duration-700 ease-in-out {{ $index === 0 ? 'opacity-100' : 'opacity-0 pointer-events-none' }}">
                            <div class="flex items-start space-x-4 mb-4">
                                @if($review->foto)
                                    <img src="{{ asset('storage/' . $review->foto) }}" alt="{{ $review->nama }}" class="w-14 h-14 rounded-full object-cover border-3 border-gray-100" />
                                @else
                                    <img src="{{ asset('assets/Goes Natha bos .jpg') }}" alt="{{ $review->nama }}" class="w-14 h-14 rounded-full object-cover border-3 border-gray-100" />
                                @endif
                                <div>
                                    <p class="font-bold text-lg sm:text-xl text-gray-900 leading-snug" data-translate>{{ $review->nama }}</p>
                                    <p class="text-sm text-blue-700 font-semibold mt-0.5" data-translate>{{ $review->company ?? 'Pengguna Olga Sehat' }}</p>
                                    <div class="flex items-center mt-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $review->rate ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                        @endfor
                                        <span class="ml-2 text-sm text-gray-600 font-medium" data-translate>{{ $review->rate }}/5</span>
                                    </div>
                                </div>
                                <div class="text-blue-200 text-5xl font-extrabold select-none ml-auto hidden md:block">"</div>
                            </div>
                            <p class="text-gray-800 text-base md:text-lg leading-relaxed italic border-l-4 border-blue-500 pl-4 py-1" data-translate>
                                "{{ $review->ulasan }}"
                            </p>
                        </div>
                    @endforeach
                @else
                    <div class="testimonial-item absolute inset-0 transition-opacity duration-700 ease-in-out opacity-100">
                        <div class="flex items-center justify-center h-full">
                            <div class="text-center">
                                <i class="fas fa-comments text-gray-300 text-4xl mb-4"></i>
                                <p class="text-gray-500 text-lg" data-translate>Belum ada testimoni.</p>
                                <p class="text-gray-400 text-sm" data-translate>Testimoni akan muncul di sini setelah data ditambahkan.</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="flex items-center justify-between mt-6 pt-3 border-t border-gray-100">
                <span id="testimonial-counter" class="text-sm font-bold text-blue-700 tracking-wider">
                    @if($reviews->count() > 0)
                        01/{{ str_pad($reviews->count(), 2, '0', STR_PAD_LEFT) }}
                    @else
                        00/00
                    @endif
                </span>

                <div class="flex space-x-3">
                    <button id="prev-btn" aria-label="Previous"
                            class="text-blue-700 hover:text-white w-8 h-8 flex items-center justify-center rounded-full bg-white border border-blue-700/50
                                   hover:bg-blue-700 transition-all duration-200 shadow-md {{ $reviews->count() <= 1 ? 'opacity-50 cursor-not-allowed' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <button id="next-btn" aria-label="Next"
                            class="text-blue-700 hover:text-white w-8 h-8 flex items-center justify-center rounded-full bg-white border border-blue-700/50
                                   hover:bg-blue-700 transition-all duration-200 shadow-md {{ $reviews->count() <= 1 ? 'opacity-50 cursor-not-allowed' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init({
    duration: 1000,
    once: true,
  });

  // Home Banner Carousel dengan Animasi Smooth
  document.addEventListener('DOMContentLoaded', function() {
    const bannerSlides = document.querySelectorAll('.banner-slide');
    const bannerDots = document.querySelectorAll('.banner-dot');
    const bannerPrev = document.getElementById('bannerPrev');
    const bannerNext = document.getElementById('bannerNext');
    let bannerCurrentIndex = 0;
    let bannerInterval;
    let isTransitioning = false;

    if (bannerSlides.length > 1) {
      function updateBannerCarousel() {
        if (isTransitioning) return;
        isTransitioning = true;

        bannerSlides.forEach((slide, index) => {
          const content = slide.querySelector('.banner-content');
          
          if (index === bannerCurrentIndex) {
            // Slide aktif
            slide.classList.remove('opacity-0', 'z-0', 'scale-105');
            slide.classList.add('opacity-100', 'z-10', 'scale-100', 'active');
            
            // Animasi konten masuk
            if (content) {
              setTimeout(() => {
                content.classList.remove('opacity-0', 'translate-y-4');
                content.classList.add('opacity-100', 'translate-y-0');
              }, 200);
            }
          } else {
            // Slide tidak aktif
            slide.classList.remove('opacity-100', 'z-10', 'scale-100', 'active');
            slide.classList.add('opacity-0', 'z-0', 'scale-105');
            
            // Reset konten
            if (content) {
              content.classList.remove('opacity-100', 'translate-y-0');
              content.classList.add('opacity-0', 'translate-y-4');
            }
          }
        });

        // Update dots dengan animasi
        bannerDots.forEach((dot, index) => {
          if (index === bannerCurrentIndex) {
            dot.classList.remove('bg-white/50', 'w-3');
            dot.classList.add('bg-white', 'w-8');
          } else {
            dot.classList.remove('bg-white', 'w-8');
            dot.classList.add('bg-white/50', 'w-3');
          }
        });

        setTimeout(() => {
          isTransitioning = false;
        }, 1000);
      }

      function nextBanner() {
        if (isTransitioning) return;
        bannerCurrentIndex = (bannerCurrentIndex + 1) % bannerSlides.length;
        updateBannerCarousel();
      }

      function prevBanner() {
        if (isTransitioning) return;
        bannerCurrentIndex = (bannerCurrentIndex - 1 + bannerSlides.length) % bannerSlides.length;
        updateBannerCarousel();
      }

      if (bannerNext) {
        bannerNext.addEventListener('click', () => {
          nextBanner();
          resetBannerInterval();
        });
      }

      if (bannerPrev) {
        bannerPrev.addEventListener('click', () => {
          prevBanner();
          resetBannerInterval();
        });
      }

      bannerDots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
          if (isTransitioning || index === bannerCurrentIndex) return;
          bannerCurrentIndex = index;
          updateBannerCarousel();
          resetBannerInterval();
        });
      });

      function resetBannerInterval() {
        clearInterval(bannerInterval);
        bannerInterval = setInterval(nextBanner, 5000); // 5 detik untuk melihat animasi lebih lama
      }

      bannerSlides.forEach((slide) => {
        slide.style.cursor = 'pointer';
        slide.addEventListener('click', (event) => {
          if (event.target.closest('.banner-dot') || event.target.closest('#bannerPrev') || event.target.closest('#bannerNext')) {
            return;
          }
          nextBanner();
          resetBannerInterval();
        });
      });

      // Auto-slide every 5 seconds
      bannerInterval = setInterval(nextBanner, 5000);
    }
  });

  // Testimonial navigation
  document.addEventListener('DOMContentLoaded', function() {
    const testimonialItems = document.querySelectorAll('.testimonial-item');
    const prevBtn = document.getElementById('prev-btn');
    const nextBtn = document.getElementById('next-btn');
    const counter = document.getElementById('testimonial-counter');
    let currentIndex = 0;

    function updateTestimonials() {
      testimonialItems.forEach((item, index) => {
        if (index === currentIndex) {
          item.classList.remove('opacity-0', 'pointer-events-none');
          item.classList.add('opacity-100');
        } else {
          item.classList.remove('opacity-100');
          item.classList.add('opacity-0', 'pointer-events-none');
        }
      });

      // Update counter
      const total = testimonialItems.length;
      const current = (currentIndex + 1).toString().padStart(2, '0');
      const totalStr = total.toString().padStart(2, '0');
      counter.textContent = `${current}/${totalStr}`;
    }

    if (prevBtn && nextBtn) {
      prevBtn.addEventListener('click', function() {
        if (testimonialItems.length > 1) {
          currentIndex = (currentIndex - 1 + testimonialItems.length) % testimonialItems.length;
          updateTestimonials();
        }
      });

      nextBtn.addEventListener('click', function() {
        if (testimonialItems.length > 1) {
          currentIndex = (currentIndex + 1) % testimonialItems.length;
          updateTestimonials();
        }
      });
    }

    // Auto-rotate testimonials every 5 seconds if more than 1
    if (testimonialItems.length > 1) {
      setInterval(function() {
        currentIndex = (currentIndex + 1) % testimonialItems.length;
        updateTestimonials();
      }, 5000);
    }
  });
</script>
@endpush
