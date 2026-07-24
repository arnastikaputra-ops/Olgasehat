@extends('user.layout.frontenduser')

@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<main class="container mx-auto px-4 sm:px-6 pt-24 pb-32 max-w-7xl">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">

        <section class="lg:col-span-8 space-y-8">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 md:gap-4 overflow-hidden rounded-xl shadow-lg">
                @if($venue->galleries && $venue->galleries->count() > 0)
                    <img
                        src="{{ asset('storage/' . $venue->galleries->first()->foto) }}"
                        alt="{{ $venue->namavenue }} Main"
                        class="col-span-1 sm:col-span-2 rounded-tl-xl rounded-bl-xl object-cover h-64 sm:h-96 w-full transition duration-300 hover:scale-105 cursor-pointer"
                        onclick="openGalleryModal()"
                    />
                    <div class="hidden sm:grid grid-rows-3 gap-3 md:gap-4 h-96">
                        @foreach($venue->galleries->skip(1)->take(2) as $gallery)
                            <img
                                src="{{ asset('storage/' . $gallery->foto) }}"
                                alt="{{ $venue->namavenue }} {{ $loop->iteration + 1 }}"
                                class="object-cover w-full h-full transition duration-300 hover:scale-105 cursor-pointer"
                                onclick="openGalleryModal()"
                            />
                        @endforeach
                        @if($venue->galleries->count() > 3)
                            <div
                                class="relative rounded-br-xl overflow-hidden cursor-pointer group"
                                aria-label="Lihat semua foto"
                                onclick="openGalleryModal()"
                            >
                                <img
                                    src="{{ asset('storage/' . $venue->galleries->skip(3)->first()->foto) }}"
                                    alt="{{ $venue->namavenue }} Gallery"
                                    class="object-cover w-full h-full brightness-50 transition duration-300 group-hover:brightness-75 group-hover:scale-105"
                                />
                                <div
                                    class="absolute inset-0 flex flex-col items-center justify-center text-white font-bold text-lg"
                                >
                                    <i class="fas fa-camera text-2xl mb-1"></i>
                                    Lihat Semua
                                </div>
                            </div>
                        @else
                            <div class="bg-gray-200 rounded-br-xl"></div>
                        @endif
                    </div>
                @else
                    <img
                        src="{{ $venue->logo ? asset('storage/' . $venue->logo) : asset('assets/olgasehat-icon.png') }}"
                        alt="{{ $venue->namavenue }}"
                        class="col-span-1 sm:col-span-2 rounded-tl-xl rounded-bl-xl object-cover h-64 sm:h-96 w-full transition duration-300 hover:scale-105 cursor-pointer"
                    />
                    <div class="hidden sm:grid grid-rows-3 gap-3 md:gap-4 h-96">
                        <div class="bg-gray-200 rounded-tr-xl"></div>
                        <div class="bg-gray-200"></div>
                        <div class="bg-gray-200 rounded-br-xl"></div>
                    </div>
                @endif
            </div>

            <div class="bg-white p-6 md:p-8 rounded-xl shadow-xl space-y-6">
                
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold mb-1 text-gray-900">{{ $venue->namavenue }}</h1>
                    <p class="text-lg text-gray-600 mb-3 flex items-center space-x-2">
                        <i class="fas fa-map-marker-alt text-blue-600"></i>
                        <span>{{ $venue->kota }}</span>
                    </p>
                    @php
                      $kategoriList = is_array($venue->kategori) ? $venue->kategori : ($venue->kategori ? [$venue->kategori] : []);
                      $kategoriDisplay = !empty($kategoriList) ? implode(', ', $kategoriList) : 'Olahraga';
                    @endphp
                    <span
                        class="inline-block bg-blue-100 text-blue-700 text-sm font-semibold px-4 py-1.5 rounded-full shadow-sm"
                    >
                        <i class="fas fa-futbol mr-2"></i> {{ $kategoriDisplay }}
                    </span>
                </div>

                <hr class="border-gray-200" />

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <h3 class="font-bold text-xl mb-3 text-gray-800">Detail Lapangan</h3>
                        @if($venue->detail)
                            <div class="text-gray-700 leading-relaxed">
                                {!! $venue->detail !!}
                            </div>
                        @else
                            <p class="text-gray-500 italic">Detail lapangan belum tersedia.</p>
                        @endif
                    </div>

                    <div>
                        <h3 class="font-bold text-xl mb-3 text-gray-800">Aturan Venue</h3>
                        @if($venue->aturan)
                            <div id="rulesText" class="text-sm text-gray-700 max-h-24 overflow-hidden transition-all duration-500 ease-in-out">
                                <p class="font-semibold mb-2">Peraturan Lapangan {{ $venue->namavenue }}:</p>
                                <div>
                                    {!! $venue->aturan !!}
                                </div>
                            </div>
                            <button
                                id="toggleRulesBtn"
                                class="text-blue-700 text-sm font-semibold mt-3 hover:text-blue-900 transition flex items-center"
                            >
                                Baca Selengkapnya <i class="fas fa-chevron-down ml-2 text-xs"></i>
                            </button>
                        @else
                            <p class="text-gray-500 italic">Aturan venue belum tersedia.</p>
                        @endif
                    </div>
                </div>
                
                <hr class="border-gray-200" />

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    
                    <div>
                        <h3 class="font-bold text-xl mb-3 text-gray-800">Lokasi Venue</h3>
                        <div class="bg-gray-50 p-4 rounded-lg flex flex-col space-y-3">
                            <p class="text-gray-600 leading-relaxed">
                                {{ $venue->kota ?? '' }}{{ $venue->kota && $venue->provinsi ? ', ' : '' }}{{ $venue->provinsi ?? '' }}
                                @if(!$venue->kota && !$venue->provinsi)
                                    Alamat belum tersedia
                                @endif
                            </p>
                            @if($venue->lokasi)
                                @php
                                    // Fungsi untuk mendapatkan embed URL dari Google Maps link
                                    $mapUrl = $venue->lokasi;
                                    $embedUrl = null;
                                    
                                    // Deteksi tipe URL Google Maps
                                    if (strpos($mapUrl, 'maps.app.goo.gl') !== false || strpos($mapUrl, 'goo.gl/maps') !== false) {
                                        // Short URL - tidak bisa di-embed langsung, hanya link
                                        $embedUrl = null;
                                    } elseif (strpos($mapUrl, 'google.com/maps') !== false) {
                                        // Full URL Google Maps - coba extract untuk embed
                                        // Format 1: /maps/@lat,lng,zoom
                                        if (preg_match('/@(-?\d+\.?\d*),(-?\d+\.?\d*),?(\d+\.?\d*)?z?/', $mapUrl, $matches)) {
                                            $lat = $matches[1];
                                            $lng = $matches[2];
                                            $zoom = isset($matches[3]) ? $matches[3] : '15';
                                            // Gunakan format embed dengan koordinat
                                            $embedUrl = 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d0!2d' . $lng . '!3d' . $lat . '!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2z' . $lat . '!' . $lng . '!5e0!3m2!1sen!2sid!4v' . time() . '!5m2!1sen!2sid';
                                        }
                                        // Format 2: /maps/place/PlaceName
                                        elseif (preg_match('/place\/([^\/\?]+)/', $mapUrl, $matches)) {
                                            $placeName = urlencode($matches[1]);
                                            // Gunakan format search untuk embed (tidak perlu API key)
                                            $embedUrl = 'https://www.google.com/maps?q=' . $placeName . '&output=embed';
                                        }
                                    }
                                @endphp
                                
                                @if($embedUrl)
                                    <!-- Tampilkan peta embedded -->
                                    <div class="mt-3 rounded-lg overflow-hidden shadow-md">
                                        <iframe
                                            src="{{ $embedUrl }}"
                                            width="100%"
                                            height="250"
                                            style="border:0;"
                                            allowfullscreen=""
                                            loading="lazy"
                                            referrerpolicy="no-referrer-when-downgrade"
                                            class="w-full"
                                        ></iframe>
                                    </div>
                                @endif
                                
                                <a
                                    href="{{ $venue->lokasi }}"
                                    class="text-blue-700 font-semibold hover:text-blue-900 transition flex items-center space-x-2 text-base mt-2"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                >
                                    <i class="fas fa-map-marked-alt"></i>
                                    <span>Buka Peta (Google Maps)</span>
                                    <i class="fas fa-external-link-alt text-xs"></i>
                                </a>
                            @endif
                        </div>
                    </div>

                    <div>
                        <h3 class="font-bold text-xl mb-3 text-gray-800">Video Preview</h3>
                        @if($venue->video_review)
                        <div class="relative bg-black rounded-lg overflow-hidden group shadow-md">
                            <img src="{{ asset('assets/Arena Sport.jpg') }}" alt="Video Preview" class="w-full h-32 object-cover opacity-70 group-hover:opacity-100 transition duration-300 cursor-pointer" />
                            <a href="{{ $venue->video_review }}" target="_blank" class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-30 group-hover:bg-opacity-10 transition">
                                <i class="fab fa-youtube text-white text-5xl opacity-80 group-hover:opacity-100 transition duration-300"></i>
                            </a>
                            <p class="absolute bottom-2 left-3 text-white text-xs font-medium bg-black/50 px-2 py-0.5 rounded">Lihat Video Lapangan</p>
                        </div>
                        @else
                        <p class="text-gray-500 italic">Video preview belum tersedia.</p>
                        @endif
                    </div>
                </div>

                <hr class="border-gray-200" />
                
                <div>
                    <h3 class="font-bold text-xl mb-4 text-gray-800">Fasilitas Tersedia</h3>
                    @if(count($fasilitas) > 0)
                    <ul class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 text-gray-700">
                        @foreach($fasilitas as $fasilitasItem)
                        <li class="flex items-center space-x-3 bg-gray-50 p-3 rounded-lg border border-gray-100">
                            <i class="fas {{ $iconMap[$fasilitasItem] ?? 'fa-check-circle' }} text-blue-600 text-xl"></i>
                            <span>{{ $fasilitasItem }}</span>
                        </li>
                        @endforeach
                    </ul>
                    @else
                    <p class="text-gray-500 italic py-4">Belum ada fasilitas yang tersedia untuk venue ini.</p>
                    @endif
                </div>
            </div>

            <div class="mt-8 bg-white p-6 md:p-8 rounded-xl shadow-xl">
                <h3 class="font-bold text-2xl mb-5 text-gray-900">Jadwal & Booking Lapangan</h3>
                
                @if($venue->lapangans && $venue->lapangans->count() > 0)
                <div class="mb-6 space-y-4">
                    {{-- Date Navigation --}}
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-3 flex-wrap">
                            <label class="text-sm font-semibold text-gray-700">Tanggal:</label>
                            <button 
                                id="prevDate" 
                                class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded-lg hover:bg-gray-100 transition"
                                aria-label="Tanggal sebelumnya"
                            >
                                <i class="fas fa-chevron-left text-gray-600"></i>
                            </button>
                            <button 
                                id="btnToday" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700 transition"
                            >
                                Hari Ini
                            </button>
                            <button 
                                id="btnTomorrow" 
                                class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-200 transition"
                            >
                                Besok
                            </button>
                            <input
                                type="date"
                                id="bookingDate"
                                class="border border-gray-300 rounded-lg px-4 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                                value="{{ $defaultDate->format('Y-m-d') }}"
                            />
                            <button 
                                id="nextDate" 
                                class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded-lg hover:bg-gray-100 transition"
                                aria-label="Tanggal selanjutnya"
                            >
                                <i class="fas fa-chevron-right text-gray-600"></i>
                            </button>
                        </div>
                        <select
                            id="bookingLapangan"
                            class="border border-gray-300 rounded-lg px-4 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                        >
                            @foreach($venue->lapangans as $lapangan)
                                <option value="{{ $lapangan->id }}" {{ $defaultLapangan && $defaultLapangan->id == $lapangan->id ? 'selected' : '' }}>
                                    {{ $venue->namavenue }} - {{ $lapangan->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 overflow-x-auto pb-4" id="timeSlotsContainer">
                    @if($timeslots && $timeslots->count() > 0)
                        @foreach($timeslots as $slot)
                            @php
                                $jamMulai = \Carbon\Carbon::parse($slot->jam_mulai)->format('H:i');
                                $jamSelesai = \Carbon\Carbon::parse($slot->jam_selesai)->format('H:i');
                                $timeRange = $jamMulai . ' - ' . $jamSelesai;
                                $isPromo = $slot->is_promo && $slot->harga_awal && $slot->harga_awal > $slot->harga;
                            @endphp
                            
                            @if($slot->status === 'booked')
                                <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 shadow-sm min-w-[140px]">
                                    <h6 class="text-sm font-semibold text-gray-700 mb-2">{{ $timeRange }}</h6>
                                    <p class="text-xs text-gray-500 line-through mb-1">Rp {{ number_format($slot->harga, 0, ',', '.') }}</p>
                                    <span class="text-xs font-bold text-red-600">Booked</span>
                                </div>
                            @elseif($slot->status === 'blocked')
                                <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 shadow-sm min-w-[140px]">
                                    <h6 class="text-sm font-semibold text-gray-700 mb-2">{{ $timeRange }}</h6>
                                    <p class="text-xs text-gray-600 mb-1">Rp {{ number_format($slot->harga, 0, ',', '.') }}</p>
                                    <span class="text-xs font-bold text-red-600">Blokir</span>
                                </div>
                            @else
                                {{-- Available Slot --}}
                                <button 
                                    class="bg-white {{ $isPromo ? 'border-2 border-green-500' : 'border border-gray-200' }} rounded-xl p-4 shadow-sm min-w-[140px] selectable relative overflow-hidden text-left transform transition-all duration-300 hover:-translate-y-2 hover:shadow-lg" 
                                    data-time="{{ $timeRange }}" 
                                    data-price="{{ $slot->harga }}" 
                                    data-slot-id="{{ $slot->id }}"
                                    data-promo="{{ $isPromo ? 'Promosi' : '' }}"
                                >
                                    @if($isPromo)
                                        <span class="absolute top-2 right-2 bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-md shadow-sm z-10">PROMO</span>
                                    @endif
                                    <h6 class="text-sm font-semibold text-gray-800 mb-3">{{ $timeRange }}</h6>
                                    <div class="mb-3">
                                        @if($isPromo && $slot->harga_awal)
                                            <p class="text-xs text-gray-400 line-through mb-1">Rp {{ number_format($slot->harga_awal, 0, ',', '.') }}</p>
                                            <p class="text-base font-bold text-blue-600">Rp {{ number_format($slot->harga, 0, ',', '.') }}</p>
                                        @else
                                            <p class="text-sm font-semibold text-gray-700">Rp {{ number_format($slot->harga, 0, ',', '.') }}</p>
                                        @endif
                                    </div>
                                    <span class="text-xs font-semibold text-green-600 block mb-2">Tersedia</span>
                                    @if($slot->catatan)
                                        <p class="text-xs text-gray-500 mt-2 mb-0">
                                            <i class="fas fa-info-circle mr-1"></i>{{ $slot->catatan }}
                                        </p>
                                    @endif
                                </button>
                                
                                {{-- Selected State (Hidden by default) --}}
                                <button 
                                    class="bg-blue-700 rounded-xl p-4 text-white min-w-[140px] hidden selectable-cancel relative shadow-xl text-left" 
                                    data-time="{{ $timeRange }}" 
                                    data-price="{{ $slot->harga }}"
                                    data-slot-id="{{ $slot->id }}"
                                >
                                    <span class="absolute top-2 right-2 bg-blue-900 text-white text-xs font-bold px-2 py-1 rounded-md">PILIH</span>
                                    <h6 class="text-sm font-semibold mb-2">{{ $timeRange }}</h6>
                                    <p class="text-sm font-semibold mb-2">Rp {{ number_format($slot->harga, 0, ',', '.') }}</p>
                                    <span class="text-xs font-bold">Batal Pilih</span>
                                </button>
                            @endif
                        @endforeach
                    @else
                        <div class="col-span-full text-center py-8">
                            <p class="text-gray-500">Belum ada jadwal tersedia untuk tanggal dan lapangan yang dipilih.</p>
                        </div>
                    @endif
                </div>
                @else
                <div class="text-center py-8">
                    <p class="text-gray-500">Belum ada lapangan yang tersedia untuk venue ini.</p>
                </div>
                @endif
            </div>
            
        </section>

        <aside class="lg:col-span-4 space-y-6 sticky top-24 self-start">
            <div class="bg-white p-6 rounded-xl shadow-xl space-y-4 border-t-4 border-blue-700">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Detail Pemesanan Anda</h3>
                
                <div id="cartContent" class="space-y-4 min-h-[50px]">
                    </div>

                <div id="initialPriceCard" class="text-center py-4">
                    <p class="text-sm text-gray-600">Mulai harga per jam</p>
                    <p class="text-3xl font-extrabold text-blue-700">
                        @if($venue->min_price > 0)
                            Rp {{ number_format($venue->min_price, 0, ',', '.') }} <span class="text-base font-normal text-gray-500">/ jam</span>
                        @else
                            <span class="text-base font-normal text-gray-500">Harga belum tersedia</span>
                        @endif
                    </p>
                    <p class="text-sm text-gray-500 mt-2">Pilih jadwal di sebelah kiri untuk memulai.</p>
                </div>
                
                <div id="bookingSummary" class="hidden pt-4 border-t">
                    <div class="flex justify-between items-center text-gray-700 font-medium text-sm">
                        <span>Total Sesi:</span>
                        <span id="total-sessions" class="font-bold text-gray-900">0 Jam</span>
                    </div>
                    <div class="flex justify-between items-center text-lg font-bold mt-2">
                        <span>Total Pembayaran:</span>
                        <span id="total-price" class="text-blue-700">Rp 0</span>
                    </div>
                </div>

                <a href="/confirmuser" id="bookLink" class="block">
                    <button id="bookButton" class="w-full bg-blue-700 text-white py-3 rounded-lg font-semibold hover:bg-blue-800 transition shadow-lg mt-4" disabled>
                        PILIH JADWAL DI ATAS
                    </button>
                </a>
            </div>
            
        </aside>
    </div>
</main>

<div id="bottomButton" class="fixed bottom-0 left-0 w-full bg-white border-t border-gray-200 p-4 shadow-2xl hidden lg:hidden z-50">
    <div class="flex justify-between items-center mb-2">
        <p class="text-sm text-gray-600"><span id="mobile-session-count">0 Sesi</span></p>
        <p class="text-xl font-bold text-blue-700" id="mobile-total-price">Rp 0</p>
    </div>
    <a href="/confirmuser" class="block w-full">
      <button class="w-full bg-blue-700 text-white py-3 rounded-lg font-semibold hover:bg-blue-800 transition">
        LANJUT PEMBAYARAN
      </button>
    </a>
</div>

{{-- Gallery Modal --}}
<div id="galleryModal" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden overflow-y-auto">
    <div class="min-h-screen px-4 py-8">
        <div class="max-w-7xl mx-auto">
            {{-- Header --}}
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl md:text-3xl font-bold text-white">{{ $venue->namavenue }} - Galeri Foto</h2>
                <button 
                    onclick="closeGalleryModal()" 
                    class="text-white hover:text-gray-300 transition p-2"
                    aria-label="Tutup galeri"
                >
                    <i class="fas fa-times text-3xl"></i>
                </button>
            </div>
            
            {{-- Gallery Grid --}}
            @if($venue->galleries && $venue->galleries->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
                @foreach($venue->galleries as $gallery)
                <div class="group relative overflow-hidden rounded-lg cursor-pointer transform transition-all duration-300 hover:scale-105">
                    <img
                        src="{{ asset('storage/' . $gallery->foto) }}"
                        alt="{{ $venue->namavenue }} Gallery {{ $loop->iteration }}"
                        class="w-full h-48 md:h-64 object-cover"
                        onclick="openImageModal('{{ asset('storage/' . $gallery->foto) }}', {{ $loop->iteration }}, {{ $venue->galleries->count() }})"
                    />
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition duration-300 flex items-center justify-center">
                        <i class="fas fa-search-plus text-white text-2xl opacity-0 group-hover:opacity-100 transition duration-300"></i>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-12">
                <p class="text-white text-lg">Belum ada foto galeri untuk venue ini.</p>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Image Lightbox Modal --}}
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-95 z-[60] hidden flex items-center justify-center">
    <button 
        onclick="closeImageModal()" 
        class="absolute top-4 right-4 text-white hover:text-gray-300 transition p-2"
        aria-label="Tutup gambar"
    >
        <i class="fas fa-times text-3xl"></i>
    </button>
    <button 
        onclick="previousImage()" 
        class="absolute left-4 text-white hover:text-gray-300 transition p-3 bg-black bg-opacity-50 rounded-full"
        aria-label="Gambar sebelumnya"
        id="prevImageBtn"
    >
        <i class="fas fa-chevron-left text-2xl"></i>
    </button>
    <button 
        onclick="nextImage()" 
        class="absolute right-4 text-white hover:text-gray-300 transition p-3 bg-black bg-opacity-50 rounded-full"
        aria-label="Gambar selanjutnya"
        id="nextImageBtn"
    >
        <i class="fas fa-chevron-right text-2xl"></i>
    </button>
    <div class="max-w-7xl mx-auto px-4">
        <img 
            id="modalImage" 
            src="" 
            alt="Gallery Image" 
            class="max-h-[90vh] max-w-full mx-auto object-contain"
        />
        <p class="text-white text-center mt-4" id="imageCounter"></p>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        // --- Global State & Element Selection ---
        let cart = [];
        const venueName = '{{ $venue->namavenue }}';
        const venueId = {{ $venue->id }};
        const fieldSelect = document.getElementById('bookingLapangan');
        const dateInput = document.getElementById('bookingDate');
        const timeSlotsContainer = document.getElementById('timeSlotsContainer');
        const cartContent = document.getElementById('cartContent'); // Kontainer utama cart
        const bookingSummary = document.getElementById('bookingSummary');
        const initialPriceCard = document.getElementById('initialPriceCard');
        const bookButton = document.getElementById('bookButton');
        const bottomButton = document.getElementById('bottomButton');
        const toggleBtn = document.getElementById('toggleRulesBtn');
        const rulesText = document.getElementById('rulesText');


        // --- Helper Functions ---

        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(number);
        }
        
        function calculateTotal() {
            let total = 0;
            let totalSessions = 0;
            cart.forEach(item => {
                // Harga disimpan sebagai integer di data-price, jadi bisa langsung diparse
                const price = parseInt(item.price); 
                total += price;
                totalSessions += 1;
            });
            return { total, totalSessions };
        }

        function addToCart(timeSlot, price) {
            const fieldName = fieldSelect.options[fieldSelect.selectedIndex].text;
            const currentDate = dateInput ? dateInput.value : '2025-09-04';
            
            const item = {
                venue: venueName,
                field: fieldName,
                date: currentDate,
                time: timeSlot,
                price: price 
            };
            cart.push(item);
            renderCart();
            updateSummary();
        }

        // Dihapus berdasarkan waktu (timeSlot)
        window.removeFromCart = function(timeSlot) {
            const initialLength = cart.length;
            cart = cart.filter(item => item.time !== timeSlot);
            
            // Mengembalikan styling tombol di Time Slots Container
            if (initialLength > cart.length) {
                const normalBtn = timeSlotsContainer.querySelector(`button.selectable[data-time="${timeSlot}"]`);
                const cancelBtn = timeSlotsContainer.querySelector(`button.selectable-cancel[data-time="${timeSlot}"]`);
                if (normalBtn && cancelBtn) {
                    cancelBtn.classList.add('hidden');
                    normalBtn.classList.remove('hidden');
                }
            }
            
            renderCart();
            updateSummary();
        }

        function renderCart() {
            const { totalSessions } = calculateTotal();

            if (totalSessions === 0) {
                cartContent.innerHTML = ''; // Kosongkan
                initialPriceCard.classList.remove('hidden');
                bookingSummary.classList.add('hidden');
                bookButton.disabled = true;
                bookButton.textContent = 'PILIH JADWAL DI ATAS';
                return;
            }

            // Tampilkan konten cart
            initialPriceCard.classList.add('hidden');
            bookingSummary.classList.remove('hidden');
            bookButton.disabled = false;
            bookButton.textContent = `BAYAR SEKARANG (${totalSessions} SESI)`;

            cartContent.innerHTML = cart.map(item => {
                const formattedPrice = formatRupiah(parseInt(item.price));
                return `
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 shadow-sm relative transition duration-300">
                        <button onclick="removeFromCart('${item.time}')" aria-label="Hapus item" class="absolute top-2 right-2 text-red-500 hover:text-red-700 focus:outline-none transition">
                            <i class="fas fa-times-circle text-lg"></i>
                        </button>
                        <h4 class="font-bold text-sm uppercase text-gray-800 mb-1">${item.field}</h4>
                        <p class="text-xs text-gray-600">${item.date} • <span class="font-semibold">${item.time}</span></p>
                        <p class="text-xl font-extrabold text-blue-700 mt-2">${formattedPrice}</p>
                    </div>
                `;
            }).join('');
        }

        function updateSummary() {
            const { total, totalSessions } = calculateTotal();
            
            // Sidebar summary update
            document.getElementById('total-sessions').textContent = `${totalSessions} Sesi`;
            document.getElementById('total-price').textContent = formatRupiah(total);
            
            // Mobile summary update
            document.getElementById('mobile-session-count').textContent = `${totalSessions} Sesi`;
            document.getElementById('mobile-total-price').textContent = formatRupiah(total);

            toggleBottomButton(totalSessions);
        }

        function toggleBottomButton(totalSessions) {
            if (totalSessions > 0 && window.innerWidth < 1024) { // Tampilkan hanya di mobile/tablet
                bottomButton.classList.remove('hidden');
            } else {
                bottomButton.classList.add('hidden');
            }
        }
        
        // --- Event Listeners and Initial Setup ---

        // Toggle rules "Read more" (Sudah diimplementasikan)
        if (toggleBtn && rulesText) {
            rulesText.style.maxHeight = '6rem'; 

            toggleBtn.addEventListener('click', () => {
                const icon = toggleBtn.querySelector('i');
                if (rulesText.classList.contains('overflow-hidden')) {
                    rulesText.classList.remove('overflow-hidden', 'max-h-24');
                    rulesText.style.maxHeight = rulesText.scrollHeight + 'px';
                    toggleBtn.innerHTML = 'Sembunyikan <i class="fas fa-chevron-up ml-2 text-xs"></i>';
                } else {
                    rulesText.style.maxHeight = '6rem';
                    rulesText.classList.add('overflow-hidden', 'max-h-24');
                    toggleBtn.innerHTML = 'Baca Selengkapnya <i class="fas fa-chevron-down ml-2 text-xs"></i>';
                }
            });
        }

        // Time slot selection - akan di-attach ulang setelah load slots
        // Event listener akan di-attach di function attachSlotListeners()
        
        // Trigger mobile button check on resize
        window.addEventListener('resize', () => updateSummary());

        // Initial render
        renderCart();
        updateSummary();

        // --- Load Slots Function ---
        function loadSlots() {
            if (!dateInput || !fieldSelect || !timeSlotsContainer) return;
            
            const date = dateInput.value;
            const lapanganId = fieldSelect.value;
            
            // Show loading state
            timeSlotsContainer.innerHTML = '<div class="col-span-full text-center py-8"><p class="text-gray-500">Memuat jadwal...</p></div>';
            
            // Fetch slots via AJAX
            fetch(`/venue-detail/${venueId}/slots?date=${date}&lapangan_id=${lapanganId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.timeslots.length > 0) {
                        renderSlots(data.timeslots);
                    } else {
                        timeSlotsContainer.innerHTML = '<div class="col-span-full text-center py-8"><p class="text-gray-500">Belum ada jadwal tersedia untuk tanggal dan lapangan yang dipilih.</p></div>';
                    }
                })
                .catch(error => {
                    console.error('Error loading slots:', error);
                    timeSlotsContainer.innerHTML = '<div class="col-span-full text-center py-8"><p class="text-red-500">Terjadi kesalahan saat memuat jadwal.</p></div>';
                });
        }

        // --- Render Slots Function ---
        function renderSlots(slots) {
            timeSlotsContainer.innerHTML = slots.map(slot => {
                const timeRange = `${slot.jam_mulai} - ${slot.jam_selesai}`;
                const isPromo = slot.is_promo && slot.harga_awal && slot.harga_awal > slot.harga;
                const formattedPrice = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(slot.harga);
                const formattedPriceAwal = slot.harga_awal ? new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(slot.harga_awal) : '';

                if (slot.status === 'booked') {
                    return `
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 shadow-sm min-w-[140px]">
                            <h6 class="text-sm font-semibold text-gray-700 mb-2">${timeRange}</h6>
                            <p class="text-xs text-gray-500 line-through mb-1">${formattedPrice}</p>
                            <span class="text-xs font-bold text-red-600">Booked</span>
                        </div>
                    `;
                } else if (slot.status === 'blocked') {
                    return `
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 shadow-sm min-w-[140px]">
                            <h6 class="text-sm font-semibold text-gray-700 mb-2">${timeRange}</h6>
                            <p class="text-xs text-gray-600 mb-1">${formattedPrice}</p>
                            <span class="text-xs font-bold text-red-600">Blokir</span>
                        </div>
                    `;
                } else {
                    return `
                        <button 
                            class="bg-white ${isPromo ? 'border-2 border-green-500' : 'border border-gray-200'} rounded-xl p-4 shadow-sm min-w-[140px] selectable relative overflow-hidden text-left transform transition-all duration-300 hover:-translate-y-2 hover:shadow-lg" 
                            data-time="${timeRange}" 
                            data-price="${slot.harga}" 
                            data-slot-id="${slot.id}"
                            data-promo="${isPromo ? 'Promosi' : ''}"
                        >
                            ${isPromo ? '<span class="absolute top-2 right-2 bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-md">PROMO</span>' : ''}
                            <h6 class="text-sm font-semibold text-gray-800 mb-2">${timeRange}</h6>
                            <div class="mb-2">
                                ${isPromo && formattedPriceAwal ? `
                                    <p class="text-xs text-gray-400 line-through mb-1">${formattedPriceAwal}</p>
                                    <p class="text-base font-bold text-blue-600">${formattedPrice}</p>
                                ` : `
                                    <p class="text-sm font-semibold text-gray-700">${formattedPrice}</p>
                                `}
                            </div>
                            <span class="text-xs font-semibold text-green-600 block mb-2">Tersedia</span>
                            ${slot.catatan ? `
                                <p class="text-xs text-gray-500 mt-2 mb-0">
                                    <i class="fas fa-info-circle mr-1"></i>${slot.catatan}
                                </p>
                            ` : ''}
                        </button>
                        <button 
                            class="bg-blue-700 rounded-xl p-4 text-white min-w-[140px] hidden selectable-cancel relative shadow-xl text-left" 
                            data-time="${timeRange}" 
                            data-price="${slot.harga}"
                            data-slot-id="${slot.id}"
                        >
                            <span class="absolute top-2 right-2 bg-blue-900 text-white text-xs font-bold px-2 py-1 rounded-md">PILIH</span>
                            <h6 class="text-sm font-semibold mb-2">${timeRange}</h6>
                            <p class="text-sm font-semibold mb-2">${formattedPrice}</p>
                            <span class="text-xs font-bold">Batal Pilih</span>
                        </button>
                    `;
                }
            }).join('');
            
            // Event listeners sudah menggunakan event delegation, tidak perlu attach ulang
        }

        // --- Attach Slot Listeners (Event Delegation) ---
        // Using event delegation so it works with dynamically loaded content
        function attachSlotListeners() {
            // Event listener sudah di-attach di level document atau container
            // Tidak perlu attach ulang karena menggunakan event delegation
        }
        
        // Global event listener untuk slot selection (event delegation)
        if (timeSlotsContainer) {
            timeSlotsContainer.addEventListener('click', (e) => {
                const target = e.target.closest('button.selectable, button.selectable-cancel');
                if (!target || target.disabled) return;

                const time = target.dataset.time;
                const price = target.dataset.price;
                
                let normalBtn, cancelBtn;
                if (target.classList.contains('selectable')) {
                    normalBtn = target;
                    cancelBtn = target.nextElementSibling;
                } else if (target.classList.contains('selectable-cancel')) {
                    cancelBtn = target;
                    normalBtn = target.previousElementSibling;
                }

                if (!normalBtn || !cancelBtn) return;

                // Select slot
                if (target.classList.contains('selectable') && target.classList.contains('hidden') === false) {
                    addToCart(time, price);
                    if (cancelBtn) cancelBtn.classList.remove('hidden');
                    if (normalBtn) normalBtn.classList.add('hidden');
                } 
                // Deselect slot
                else if (target.classList.contains('selectable-cancel') && target.classList.contains('hidden') === false) {
                    removeFromCart(time);
                    if (cancelBtn) cancelBtn.classList.add('hidden');
                    if (normalBtn) normalBtn.classList.remove('hidden');
                }
            });
        }

        // --- Date Navigation Functions ---
        function setDate(dateString) {
            if (dateInput) {
                dateInput.value = dateString;
                loadSlots();
            }
        }

        function getToday() {
            const today = new Date();
            return today.toISOString().split('T')[0];
        }

        function getTomorrow() {
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            return tomorrow.toISOString().split('T')[0];
        }

        function changeDate(days) {
            if (!dateInput) return;
            const currentDate = new Date(dateInput.value);
            currentDate.setDate(currentDate.getDate() + days);
            setDate(currentDate.toISOString().split('T')[0]);
        }

        // --- Event Listeners for Date Navigation ---
        const btnToday = document.getElementById('btnToday');
        const btnTomorrow = document.getElementById('btnTomorrow');
        const prevDateBtn = document.getElementById('prevDate');
        const nextDateBtn = document.getElementById('nextDate');

        if (btnToday) {
            btnToday.addEventListener('click', () => {
                setDate(getToday());
                // Update button styles
                btnToday.classList.remove('bg-gray-100', 'text-gray-700');
                btnToday.classList.add('bg-blue-600', 'text-white');
                btnTomorrow.classList.remove('bg-blue-600', 'text-white');
                btnTomorrow.classList.add('bg-gray-100', 'text-gray-700');
            });
        }

        if (btnTomorrow) {
            btnTomorrow.addEventListener('click', () => {
                setDate(getTomorrow());
                // Update button styles
                btnTomorrow.classList.remove('bg-gray-100', 'text-gray-700');
                btnTomorrow.classList.add('bg-blue-600', 'text-white');
                btnToday.classList.remove('bg-blue-600', 'text-white');
                btnToday.classList.add('bg-gray-100', 'text-gray-700');
            });
        }

        if (prevDateBtn) {
            prevDateBtn.addEventListener('click', () => changeDate(-1));
        }

        if (nextDateBtn) {
            nextDateBtn.addEventListener('click', () => changeDate(1));
        }

        // Update button styles based on current date
        function updateDateButtonStyles() {
            if (!dateInput || !btnToday || !btnTomorrow) return;
            const currentDate = dateInput.value;
            const today = getToday();
            const tomorrow = getTomorrow();

            if (currentDate === today) {
                btnToday.classList.remove('bg-gray-100', 'text-gray-700');
                btnToday.classList.add('bg-blue-600', 'text-white');
                btnTomorrow.classList.remove('bg-blue-600', 'text-white');
                btnTomorrow.classList.add('bg-gray-100', 'text-gray-700');
            } else if (currentDate === tomorrow) {
                btnTomorrow.classList.remove('bg-gray-100', 'text-gray-700');
                btnTomorrow.classList.add('bg-blue-600', 'text-white');
                btnToday.classList.remove('bg-blue-600', 'text-white');
                btnToday.classList.add('bg-gray-100', 'text-gray-700');
            } else {
                btnToday.classList.remove('bg-blue-600', 'text-white');
                btnToday.classList.add('bg-gray-100', 'text-gray-700');
                btnTomorrow.classList.remove('bg-blue-600', 'text-white');
                btnTomorrow.classList.add('bg-gray-100', 'text-gray-700');
            }
        }

        // --- Event Listeners for Date and Lapangan Change ---
        if (dateInput) {
            dateInput.addEventListener('change', () => {
                updateDateButtonStyles();
                loadSlots();
            });
        }
        if (fieldSelect) {
            fieldSelect.addEventListener('change', loadSlots);
        }

        // Initialize button styles
        updateDateButtonStyles();
    });

    // Gallery Modal Functions
    function openGalleryModal() {
        const modal = document.getElementById('galleryModal');
        if (modal) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeGalleryModal() {
        const modal = document.getElementById('galleryModal');
        if (modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
        closeImageModal();
    }

    // Image Lightbox Functions
    let currentImageIndex = 0;
    let totalImages = 0;
    let imageSources = [];

    function openImageModal(src, index, total) {
        currentImageIndex = index - 1;
        totalImages = total;
        
        // Collect all image sources
        imageSources = [];
        @if($venue->galleries && $venue->galleries->count() > 0)
            @foreach($venue->galleries as $gallery)
                imageSources.push('{{ asset('storage/' . $gallery->foto) }}');
            @endforeach
        @endif
        
        const imageModal = document.getElementById('imageModal');
        const modalImage = document.getElementById('modalImage');
        const imageCounter = document.getElementById('imageCounter');
        
        if (imageModal && modalImage) {
            modalImage.src = src;
            imageCounter.textContent = `${index} / ${total}`;
            imageModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeImageModal() {
        const imageModal = document.getElementById('imageModal');
        if (imageModal) {
            imageModal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    }

    function previousImage() {
        if (currentImageIndex > 0) {
            currentImageIndex--;
        } else {
            currentImageIndex = totalImages - 1;
        }
        updateImageModal();
    }

    function nextImage() {
        if (currentImageIndex < totalImages - 1) {
            currentImageIndex++;
        } else {
            currentImageIndex = 0;
        }
        updateImageModal();
    }

    function updateImageModal() {
        const modalImage = document.getElementById('modalImage');
        const imageCounter = document.getElementById('imageCounter');
        
        if (modalImage && imageSources.length > 0) {
            modalImage.src = imageSources[currentImageIndex];
            imageCounter.textContent = `${currentImageIndex + 1} / ${totalImages}`;
        }
    }

    // Close modals on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeImageModal();
            closeGalleryModal();
        }
        if (e.key === 'ArrowLeft') {
            const imageModal = document.getElementById('imageModal');
            if (imageModal && !imageModal.classList.contains('hidden')) {
                previousImage();
            }
        }
        if (e.key === 'ArrowRight') {
            const imageModal = document.getElementById('imageModal');
            if (imageModal && !imageModal.classList.contains('hidden')) {
                nextImage();
            }
        }
    });

    // Close modal when clicking outside
    document.getElementById('galleryModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeGalleryModal();
        }
    });

    document.getElementById('imageModal')?.addEventListener('click', function(e) {
        if (e.target === this || e.target.id === 'modalImage') {
            closeImageModal();
        }
    });
</script>
@endsection