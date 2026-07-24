@extends('user.layout.frontenduser')

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
    .hero-banner-pattern {
        background-color: #013D9D;
        position: relative;
    }
    .hero-banner-pattern::before {
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
    .hero-banner-pattern > * {
        position: relative;
        z-index: 1;
    }
</style>

<!-- Hero Section -->
<section class="hero-banner-pattern pt-24 md:pt-28 pb-20 md:pb-24" data-aos="fade-up">
    <div class="container mx-auto px-6 max-w-7xl">
        <div class="text-center py-8 md:py-12">
            <h1 class="text-3xl md:text-4xl lg:text-5xl xl:text-6xl font-bold text-white leading-tight tracking-tight" data-translate>
                LEBIH DEKAT DENGAN OLGASEHAT
            </h1>
        </div>
    </div>
</section>

<!-- About Us Section -->
<section class="bg-white py-8 md:py-10" data-aos="fade-up">
    <div class="container mx-auto px-6 max-w-4xl">
        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6 leading-tight" data-translate>
            Tentang Kami
        </h2>
        <p class="text-gray-700 text-base md:text-lg leading-relaxed mb-6" data-translate>
            OlgaSehat adalah perusahaan teknologi yang memiliki misi meningkatkan tingkat partisipasi olahraga di seluruh Indonesia, yang dimana misi tersebut dipercaya dapat memberikan dampak yang positif terhadap olahraga Indonesia.
        </p>
    </div>
<!-- OlgaSehat Story Section -->
    <div class="container mx-auto px-6 max-w-4xl">
        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6 leading-tight" data-translate>
            Cerita OlgaSehat
        </h2>
        <p class="text-gray-700 text-base md:text-lg leading-relaxed mb-4" data-translate>
            OlgaSehat berawal dari platform sederhana untuk membantu orang menemukan teman berolahraga, kemudian berkembang menjadi ekosistem digital untuk gaya hidup sehat. Kami menawarkan berbagai fitur seperti menemukan teman berolahraga, rekomendasi aktivitas sehat, dan tantangan komunitas.
        </p>
        <p class="text-gray-700 text-base md:text-lg leading-relaxed mb-4" data-translate>
            Sejak 2025, OlgaSehat berkomitmen untuk terus berinovasi guna memberikan layanan yang lebih lengkap, praktis, dan menyenangkan. Kami menghubungkan pengguna dengan berbagai aktivitas sehat, mulai dari olahraga harian hingga komunitas kebugaran dan acara kesehatan.
        </p>
        <p class="text-gray-700 text-base md:text-lg leading-relaxed" data-translate>
            OlgaSehat bertujuan menjadi teman yang dekat, membantu, dan menginspirasi untuk hidup yang lebih sehat dan bahagia.
        </p>
    </div>
</section>

<!-- Video Section -->
<section class="bg-white py-8 md:py-10" data-aos="fade-up">
    <div class="container mx-auto px-6 max-w-4xl">
        
       
    <div class="max-w-md">
    <a href="https://youtu.be/EpXJbOcrp5A" target="_blank">
        <div class="relative aspect-video rounded-xl shadow-xl overflow-hidden">
            <iframe 
                class="absolute top-0 left-0 w-full h-full" 
                src="https://www.youtube.com/embed/EpXJbOcrp5A" 
                title="Olga Sehat Video" 
                frameborder="0" 
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                allowfullscreen>
            </iframe>
        </div>
    </a>
</div>


    </div>
</section>


<!-- Help/Contact Section -->
<section class="bg-white py-12 md:py-16" data-aos="fade-up">
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

