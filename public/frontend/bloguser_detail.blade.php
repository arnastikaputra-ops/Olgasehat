@extends('user.layout.frontenduser')

@section('content')

@php
$categoryColors = [
    'olahraga' => 'blue',
    'kesehatan' => 'green',
    'edukasi' => 'yellow',
];
@endphp

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<section class="container mx-auto px-4 sm:px-6 py-24 grid grid-cols-1 lg:grid-cols-4 gap-12 max-w-7xl">
    <main class="lg:col-span-3 order-2 lg:order-1">
        
        <div class="mb-8 border-b border-gray-200 pb-4">
            <span class="inline-block bg-{{ $berita->category ? ($categoryColors[strtolower($berita->category->title)] ?? 'blue') : 'blue' }}-100 text-{{ $berita->category ? ($categoryColors[strtolower($berita->category->title)] ?? 'blue') : 'blue' }}-700 text-sm font-semibold px-3 py-1 rounded-full mb-3">{{ $berita->category ? $berita->category->title : 'OLAHRAGA' }}</span>
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 leading-tight mb-4">
                {{ $berita->title }}
            </h1>

            <div class="flex flex-col sm:flex-row sm:items-center text-gray-600 text-sm justify-between">
                <div class="flex items-center space-x-4 mb-3 sm:mb-0">
                    <div class="flex items-center space-x-1">
                        <i class="fas fa-user-circle text-base text-gray-500"></i>
                        <span>oleh {{ $berita->name }}</span>
                    </div>
                    <div class="flex items-center space-x-1">
                        <i class="fas fa-calendar-alt text-base text-gray-500"></i>
                        <span>{{ $berita->created_at->format('d M Y') }}</span>
                    </div>
                    {{-- Tambahan: Ikon dan Jumlah Dilihat --}}
                    <div class="flex items-center space-x-1">
                        <i class="fas fa-eye text-base text-gray-500"></i>
                        {{-- Ganti '1234' dengan data dinamis jika tersedia, misal: {{ $berita->hit }} --}}
                        <span>{{ $berita->hit ?? 0 }} Dilihat</span>
                    </div>
                    {{-- End Tambahan --}}
                </div>

                <div class="flex items-center space-x-3 text-sm">
                    <span class="font-medium text-gray-700">Bagikan:</span>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}" target="_blank" aria-label="Share on Facebook" class="text-blue-600 hover:text-blue-800 transition transform hover:scale-110">
                        <i class="fab fa-facebook-f fa-lg"></i>
                    </a>
                    <a href="https://twitter.com/intent/tweet?url={{ url()->current() }}&text={{ $berita->title }}" target="_blank" aria-label="Share on Twitter" class="text-blue-400 hover:text-blue-600 transition transform hover:scale-110">
                        <i class="fab fa-twitter fa-lg"></i>
                    </a>
                    <a href="https://wa.me/?text={{ urlencode($berita->title . ' ' . url()->current()) }}" target="_blank" aria-label="Share on WhatsApp" class="text-green-500 hover:text-green-700 transition transform hover:scale-110">
                        <i class="fab fa-whatsapp fa-lg"></i>
                    </a>
                </div>
            </div>
        </div>

        <img
            src="{{ $berita->foto ? asset('fotoberita/' . $berita->foto) : asset('assets/tenis.jpg') }}"
            alt="{{ $berita->title }}"
            class="w-full rounded-xl mb-10 object-cover max-h-[500px] shadow-2xl"
        />

        <article class="prose prose-base md:prose-lg max-w-none text-justify text-gray-800 leading-relaxed">
            {!! $berita->content !!}
        </article>
        
        <div class="mt-10 pt-6 border-t border-gray-200">
            <p class="text-gray-700 font-semibold text-sm">Kategori: <a href="{{ route('user.bloguser_news') }}?category={{ $berita->category ? $berita->category->title : 'OLAHRAGA' }}" class="text-blue-700 font-medium hover:underline">{{ $berita->category ? $berita->category->title : 'OLAHRAGA' }}</a></p>
        </div>

    </main>

    <aside class="lg:col-span-1 space-y-8 order-1 lg:order-2">
        
        <div class="bg-white rounded-xl shadow-xl p-6 border-t-4 border-blue-700">
            <h2 class="text-xl font-bold text-gray-900 mb-4 border-b-2 border-gray-200 pb-3">
                Artikel Terbaru
            </h2>
            <ol class="space-y-4 text-base">
                @forelse($latestBeritas as $latest)
                <li class="pb-3 border-b border-gray-100 last:border-b-0 last:pb-0">
                    <a href="{{ route('user.bloguser_detail', $latest->id) }}" class="font-semibold text-gray-800 hover:text-blue-700 transition block">
                        {{ $latest->title }}
                    </a>
                    <p class="text-xs text-gray-500 mt-1">
                        <span class="text-{{ $latest->category ? ($categoryColors[strtolower($latest->category->title)] ?? 'blue') : 'blue' }}-600 font-medium">{{ $latest->category ? $latest->category->title : 'OLAHRAGA' }}</span> - {{ $latest->created_at->format('d M Y') }}
                    </p>
                </li>
                @empty
                <li class="text-sm text-gray-500">Belum ada artikel terbaru.</li>
                @endforelse
            </ol>
        </div>
        
    </aside>
</section>

<section class="container mx-auto px-4 sm:px-6 pb-16 max-w-7xl">
    <h2 class="text-2xl font-bold text-gray-900 mb-6 border-l-4 border-blue-700 pl-3">Artikel Terkait</h2>
    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        
        <a href="#" class="block">
            <article class="bg-white rounded-xl shadow-lg overflow-hidden transform hover:scale-[1.02] hover:shadow-xl transition duration-300 group">
                <div class="relative overflow-hidden">
                    <img
                        src="{{ asset('assets/basketball-player.jpg') }}"
                        alt="Basketball Player"
                        class="w-full h-48 object-cover group-hover:scale-110 transition duration-500"
                    />
                    <p class="absolute top-3 left-3 bg-green-600 text-white text-xs font-bold px-3 py-1 rounded-full shadow-md">KESEHATAN</p>
                </div>
                <div class="p-5">
                    <h3 class="font-bold text-lg text-gray-900 mb-3 group-hover:text-blue-700 transition leading-snug line-clamp-2">
                        3 Hal Penting agar Main Bola Tetap Lancar di Bulan Puasa
                    </h3>
                    <p class="text-gray-600 text-sm mb-5 line-clamp-3">
                        Bagaimana cara tetap lancar main bola di bulan puasa? Yuk simak tips menjaga hidrasi dan energi berikut ini!
                    </p>
                    <div class="flex items-center justify-between text-xs text-gray-500 border-t pt-3">
                        <span class="font-semibold text-gray-700">oleh Yasin</span>
                        <time datetime="2022-04-14">14 April 2022</time>
                    </div>
                </div>
            </article>
        </a>

        <a href="#" class="block">
            <article class="bg-white rounded-xl shadow-lg overflow-hidden transform hover:scale-[1.02] hover:shadow-xl transition duration-300 group">
                <div class="relative overflow-hidden">
                    <img
                        src="{{ asset('assets/tennis-player.jpg') }}"
                        alt="Tennis Player"
                        class="w-full h-48 object-cover group-hover:scale-110 transition duration-500"
                    />
                    <p class="absolute top-3 left-3 bg-blue-700 text-white text-xs font-bold px-3 py-1 rounded-full shadow-md">OLAHRAGA</p>
                </div>
                <div class="p-5">
                    <h3 class="font-bold text-lg text-gray-900 mb-3 group-hover:text-blue-700 transition leading-snug line-clamp-2">
                        Cara Mencari Lawan Bermain Tennis untuk Mengasah Skill
                    </h3>
                    <p class="text-gray-600 text-sm mb-5 line-clamp-3">
                        Bukan hanya soal menang atau kalah, cari tahu cara terbaik menemukan lawan sparring yang sesuai level Anda.
                    </p>
                    <div class="flex items-center justify-between text-xs text-gray-500 border-t pt-3">
                        <span class="font-semibold text-gray-700">oleh Eren</span>
                        <time datetime="2025-03-28">28 March 2025</time>
                    </div>
                </div>
            </article>
        </a>

        <a href="#" class="block">
            <article class="bg-white rounded-xl shadow-lg overflow-hidden transform hover:scale-[1.02] hover:shadow-xl transition duration-300 group">
                <div class="relative overflow-hidden">
                    <img
                        src="{{ asset('assets/mini-soccer.jpg') }}"
                        alt="Mini Soccer Field"
                        class="w-full h-48 object-cover group-hover:scale-110 transition duration-500"
                    />
                    <p class="absolute top-3 left-3 bg-yellow-600 text-white text-xs font-bold px-3 py-1 rounded-full shadow-md">EDUKASI</p>
                </div>
                <div class="p-5">
                    <h3 class="font-bold text-lg text-gray-900 mb-3 group-hover:text-blue-700 transition leading-snug line-clamp-2">
                        Inilah Ukuran Standar Lapangan Mini Soccer yang Harus Diketahui
                    </h3>
                    <p class="text-gray-600 text-sm mb-5 line-clamp-3">
                        Memahami ukuran lapangan mini soccer yang wajib diketahui oleh para pemain dan pelatih.
                    </p>
                    <div class="flex items-center justify-between text-xs text-gray-500 border-t pt-3">
                        <span class="font-semibold text-gray-700">oleh Dite</span>
                        <time datetime="2024-07-29">29 July 2024</time>
                    </div>
                </div>
            </article>
        </a>
        
    </div>
</section>

@endsection