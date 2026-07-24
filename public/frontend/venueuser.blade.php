@extends('user.layout.frontenduser')

@section('content')

  <!-- Blue Banner - Tetap Tampil -->
  <section class="bg-[url('assets/blue-banner.png')] bg-no-repeat text-white relative overflow-hidden h-[300px] flex items-center justify-center" style="background-size: 1910px 300px;">
    <div class="container mx-auto px-6 text-center w-full">
      <h1 class="text-3xl md:text-4xl font-bold tracking-wide mt-10">
        BOOKING VENUE OLAHRAGA TERDEKAT 
      </h1>
    </div>
  </section>

  <section class="container mx-auto px-6 py-6">
    <form id="venueSearchForm" class="bg-white rounded-lg border border-gray-200 shadow-sm">
      <div class="flex flex-col lg:flex-row items-stretch gap-2 p-2">
        
        <!-- Search Input - Cari nama venue dan Kota (gabungan, memanjang) -->
        <div class="relative flex-[2] min-w-0">
          <div class="relative">
            <input
              type="text"
              id="unifiedSearch"
              name="q"
              placeholder="Cari nama venue atau kota"
              class="w-full border border-gray-300 rounded-lg px-4 pl-10 py-3 text-gray-700 placeholder-gray-400 focus:outline-none focus:border-gray-400 transition-all duration-150 bg-white"
              autocomplete="off"
            />
            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
          </div>
          <!-- Suggestions Dropdown -->
          <div id="suggestionsDropdown" class="absolute top-full left-0 w-full bg-white border border-gray-200 rounded-lg shadow-xl max-h-80 overflow-y-auto hidden z-50 mt-1">
          </div>
        </div>
        
        <!-- Sport Category Dropdown - Pilih Cabang Olahraga -->
        <div class="relative flex-1 min-w-0">
          <div class="relative">
            <select
              id="sportCategory"
              name="kategori"
              class="w-full border border-gray-300 rounded-lg px-4 pl-10 pr-10 py-3 text-gray-700 focus:outline-none focus:border-gray-400 transition-all duration-150 bg-white appearance-none cursor-pointer"
            >
              <option value="all" selected>Pilih Cabang Olahraga</option>
            </select>
            <i class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm pointer-events-none"></i>
            <i class="fas fa-football-ball absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
          </div>
        </div>

        <!-- Date Input - Lihat Tanggal -->
        <div class="relative flex-1 min-w-0">
          <div class="relative">
            <input
              type="date"
              id="bookingDate"
              name="tanggal"
              value="{{ request('tanggal') }}"
              class="w-full border border-gray-300 rounded-lg px-4 pl-10 pr-4 py-3 text-gray-700 focus:outline-none focus:border-gray-400 transition-all duration-150 bg-white cursor-pointer"
            />
            <i class="fas fa-calendar-alt absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm pointer-events-none"></i>
          </div>
        </div>
        
        <!-- Search Button - Cari venue -->
        <button
          type="submit"
          class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 whitespace-nowrap"
        >
          Cari venue
        </button>
        
      </div>
    </form>
  </section>

  <!-- Venue Cards Grid -->
  <section class="container mx-auto px-6 pb-12">
    <h2 class="font-bold text-xl mb-6 text-gray-800">
      Nikmati <span class="text-blue-700">{{ $venues->total() }} Venue</span> yang tersedia
    </h2>
    @if($venues->count() > 0)
    <div
      class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6"
      aria-label="Daftar venue olahraga"
    >
      @foreach($venues as $venue)
        <a href="{{ route('user.venue.detail', $venue->id) }}" class="block group">
            <article
                class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100 transform group-hover:-translate-y-1 group-hover:shadow-xl transition duration-300"
            >
                <img
                    src="{{ $venue->logo ? asset('storage/' . $venue->logo) : asset('assets/olgasehat-icon.png') }}"
                    alt="{{ $venue->namavenue }}"
                    class="w-full h-40 object-cover"
                />
                
                <div class="p-4">
                    @php
                      $kategoriList = is_array($venue->kategori) ? $venue->kategori : ($venue->kategori ? [$venue->kategori] : []);
                      $kategoriDisplay = !empty($kategoriList) ? implode(', ', $kategoriList) : 'Olahraga';
                    @endphp
                    <p class="text-xs text-gray-500 font-medium mb-0">Venue | {{ $kategoriDisplay }}</p>
                    <h3 class="font-bold text-lg text-gray-900 mb-1">{{ $venue->namavenue }}</h3>
                    <p class="text-sm text-gray-600 mb-3 flex items-center">
                        <i class="fas fa-map-marker-alt text-blue-500 text-xs mr-1"></i> {{ $venue->kota }}
                    </p>
                    
                    <p class="font-bold text-gray-900 mb-3">
                        @if($venue->min_price > 0)
                            Mulai <span class="text-xl">Rp{{ number_format($venue->min_price, 0, ',', '.') }}</span> /Sesi
                        @else
                            <span class="text-sm text-gray-500">Harga belum tersedia</span>
                        @endif
                    </p>
                    
                    <div class="pt-2 border-t border-gray-100">
                    <p class="text-xs text-gray-500 font-medium mb-2">Jadwal Tersedia</p>
                    <div class="flex flex-wrap gap-2">
                        @php
                            // Ambil beberapa slot available untuk preview
                            $availableSlots = $venue->lapangans->flatMap(function($lapangan) {
                                return $lapangan->slots->where('status', 'available')->take(4);
                            })->take(4);
                        @endphp
                        @if($availableSlots->count() > 0)
                            @foreach($availableSlots->take(4) as $slot)
                                <button class="bg-green-100 text-green-700 text-xs rounded-lg px-3 py-1 font-medium hover:bg-green-600 hover:text-white transition">
                                    {{ \Carbon\Carbon::parse($slot->jam_mulai)->format('H:i') }}
                                </button>
                            @endforeach
                        @else
                            <span class="text-xs text-gray-400">Belum ada jadwal tersedia</span>
                        @endif
                    </div>
                </div>
            </article>
        </a>
      @endforeach
    </div>
    @else
    <div class="text-center py-12">
        <p class="text-gray-600 text-lg">Belum ada venue yang tersedia saat ini.</p>
    </div>
    @endif

    @if($venues->hasPages())
    <section class="mt-8 mb-20 md:mt-12 md:mb-24">
        <div class="flex justify-center">
            {{ $venues->links() }}
        </div>
    </section>
    @endif

@if(isset($venueBanners) && $venueBanners->count() > 0)
<section class="mx-auto px-4 sm:px-6 lg:px-8 mt-12 relative">
    <div class="overflow-hidden rounded-lg">
        <div class="flex transition-transform duration-500 ease-in-out" id="carousel" style="transform: translateX(0%)">
            @foreach($venueBanners as $index => $banner)
                <img alt="Venue Banner {{ $index + 1 }}" class="w-full flex-shrink-0 object-cover rounded-lg" height="400" src="{{ asset('fotogaleri/'.$banner->foto) }}" style="max-height: 400px" width="900"/>
            @endforeach
        </div>
    </div>
    @if($venueBanners->count() > 1)
    <button aria-label="Previous slide" class="absolute top-1/2 left-6 -translate-y-1/2 bg-gray-500 bg-opacity-50 hover:bg-opacity-70 text-white p-2 rounded-full" id="prev" style="user-select:none">
        <i class="fas fa-chevron-left"></i>
    </button>
    <button aria-label="Next slide" class="absolute top-1/2 right-6 -translate-y-1/2 bg-gray-500 bg-opacity-50 hover:bg-opacity-70 text-white p-2 rounded-full" id="next" style="user-select:none">
        <i class="fas fa-chevron-right"></i>
    </button>
    <div class="flex justify-center space-x-2 mt-3 text-gray-600 text-xs select-none" id="dots">
        @foreach($venueBanners as $index => $banner)
            <button aria-label="Slide {{ $index + 1 }}" class="w-2 h-2 rounded-full {{ $index === 0 ? 'bg-gray-600' : 'bg-gray-300' }}" data-index="{{ $index }}"></button>
        @endforeach
    </div>
    @endif
</section>
@endif

@if(isset($venueBanners) && $venueBanners->count() > 1)
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const carousel = document.getElementById('carousel');
    const prev = document.getElementById('prev');
    const next = document.getElementById('next');
    const dots = document.querySelectorAll('#dots button');
    let currentIndex = 0;
    const totalSlides = {{ $venueBanners->count() }};

    if (!carousel || totalSlides <= 1) return;

    function updateCarousel() {
      carousel.style.transform = `translateX(-${currentIndex * 100}%)`;
      dots.forEach((dot, index) => {
        if (index === currentIndex) {
          dot.classList.add('bg-gray-600');
          dot.classList.remove('bg-gray-300');
        } else {
          dot.classList.add('bg-gray-300');
          dot.classList.remove('bg-gray-600');
        }
      });
    }

    if (prev) {
      prev.addEventListener('click', () => {
        if (currentIndex > 0) {
          currentIndex--;
        } else {
          currentIndex = totalSlides - 1;
        }
        updateCarousel();
      });
    }

    if (next) {
      next.addEventListener('click', () => {
        if (currentIndex < totalSlides - 1) {
          currentIndex++;
        } else {
          currentIndex = 0;
        }
        updateCarousel();
      });
    }

    dots.forEach((dot, index) => {
      dot.addEventListener('click', () => {
        currentIndex = index;
        updateCarousel();
      });
    });

    // Auto-slide every 5 seconds
    setInterval(() => {
      if (currentIndex < totalSlides - 1) {
        currentIndex++;
      } else {
        currentIndex = 0;
      }
      updateCarousel();
    }, 5000);
  });
</script>
@endif

{{-- PERUBAHAN: Tambahkan mt-16 dan sesuaikan mx-auto dan px-6 agar sejalan dengan carousel --}}
<section class="container mx-auto px-4 sm:px-6 lg:px-8 mt-16 mb-16">
    <div class="flex flex-col md:flex-row md:items-start md:space-x-12">
        
        <div class="md:w-1/4 mb-8 md:mb-0">
            <h2 class="text-3xl font-bold text-gray-900 mb-2 border-l-4 border-blue-700 pl-3">
                Promo Venue
            </h2>
            <p class="text-gray-600 mb-4 text-lg">
                Jangan lewatkan kesempatan! Booking sekarang dan dapatkan penawaran terbaik dari berbagai venue pilihan.
            </p>
            <a 
                href="#" 
                class="inline-flex items-center text-blue-700 hover:text-blue-900 text-sm font-bold transition duration-300 group"
            >
                Lihat Semua Promo 
                <i class="fas fa-arrow-right ml-2 text-sm transition group-hover:translate-x-1"></i>
            </a>
        </div>
        
        <div 
            class="md:w-3/4 flex space-x-6 overflow-x-auto pb-4 
            scrollbar-thin scrollbar-thumb-blue-500 scrollbar-track-gray-100"
        >
            
            <a href="/venueuser_detail" class="flex-shrink-0 min-w-[280px] md:min-w-[300px] block">
                <article class="bg-white rounded-xl overflow-hidden shadow-xl hover:shadow-2xl transform hover:-translate-y-1 transition duration-300 border border-gray-100">
                    <div class="relative">
                        <img 
                            alt="Promo Special MU Sport Center" 
                            class="w-full h-40 object-cover" 
                            height="160" 
                            src="{{ asset('assets/MU Sport Center.jpeg') }}" 
                            width="300"
                        />
                        <span class="absolute top-3 right-3 bg-red-600 text-white text-xs font-bold rounded-full px-3 py-1 shadow-md">
                            🔥 PROMO SPESIAL
                        </span>
                    </div>
                    <div class="p-4">
                        <h4 class="font-bold text-base text-gray-900 mb-1 line-clamp-1">Promo Special MU Sport Center</h4>
                        <p class="text-sm text-blue-700 mb-2 font-medium">MU Sport Center</p>
                        <div class="flex items-center text-xs text-gray-500 pt-2 border-t border-gray-100">
                            <i class="far fa-calendar-alt mr-2"></i>
                            <p>Periode 01 Jul - 31 Aug</p>
                        </div>
                    </div>
                </article>
            </a>
            
            <a href="/venueuser_detail" class="flex-shrink-0 min-w-[280px] md:min-w-[300px] block">
                <article class="bg-white rounded-xl overflow-hidden shadow-xl hover:shadow-2xl transform hover:-translate-y-1 transition duration-300 border border-gray-100">
                    <div class="relative">
                        <img 
                            alt="PRICELIST 2025" 
                            class="w-full h-40 object-cover" 
                            height="160" 
                            src="{{ asset('assets/Imbo Sport Center.webp') }}" 
                            width="300"
                        />
                        <span class="absolute top-3 right-3 bg-red-600 text-white text-xs font-bold rounded-full px-3 py-1 shadow-md">
                            🔥 PROMO SPESIAL
                        </span>
                    </div>
                    <div class="p-4">
                        <h4 class="font-bold text-base text-gray-900 mb-1 line-clamp-1">PRICELIST 2025</h4>
                        <p class="text-sm text-blue-700 mb-2 font-medium">Arena Sport</p>
                        <div class="flex items-center text-xs text-gray-500 pt-2 border-t border-gray-100">
                            <i class="far fa-calendar-alt mr-2"></i>
                            <p>Periode 01 Apr - 31 Dec</p>
                        </div>
                    </div>
                </article>
            </a>
            
            <a href="/venueuser_detail" class="flex-shrink-0 min-w-[280px] md:min-w-[300px] block">
                <article class="bg-white rounded-xl overflow-hidden shadow-xl hover:shadow-2xl transform hover:-translate-y-1 transition duration-300 border border-gray-100">
                    <div class="relative">
                        <img 
                            alt="PRICELIST 20" 
                            class="w-full h-40 object-cover" 
                            height="160" 
                            src="{{ asset('assets/DC Arena Bali.jpeg') }}" 
                            width="300"
                        />
                        <span class="absolute top-3 right-3 bg-red-600 text-white text-xs font-bold rounded-full px-3 py-1 shadow-md">
                            🔥 PROMO SPESIAL
                        </span>
                    </div>
                    <div class="p-4">
                        <h4 class="font-bold text-base text-gray-900 mb-1 line-clamp-1">PRICELIST 20</h4>
                        <p class="text-sm text-blue-700 mb-2 font-medium">111 Stadion Arena</p>
                        <div class="flex items-center text-xs text-gray-500 pt-2 border-t border-gray-100">
                            <i class="far fa-calendar-alt mr-2"></i>
                            <p>Periode 01 Apr - 31</p>
                        </div>
                    </div>
                </article>
            </a>
            
            <a href="/venueuser_detail" class="flex-shrink-0 min-w-[280px] md:min-w-[300px] block">
                <article class="bg-white rounded-xl overflow-hidden shadow-xl hover:shadow-2xl transform hover:-translate-y-1 transition duration-300 border border-gray-100">
                    <div class="relative">
                        <img 
                            alt="Special Weekend Offer" 
                            class="w-full h-40 object-cover" 
                            height="160" 
                            src="{{ asset('assets/Arena Sport.jpg') }}" 
                            width="300"
                        />
                        <span class="absolute top-3 right-3 bg-red-600 text-white text-xs font-bold rounded-full px-3 py-1 shadow-md">
                            🔥 PROMO SPESIAL
                        </span>
                    </div>
                    <div class="p-4">
                        <h4 class="font-bold text-base text-gray-900 mb-1 line-clamp-1">Special Weekend Offer</h4>
                        <p class="text-sm text-blue-700 mb-2 font-medium">Weekend Sports Arena</p>
                        <div class="flex items-center text-xs text-gray-500 pt-2 border-t border-gray-100">
                            <i class="far fa-calendar-alt mr-2"></i>
                            <p>Periode 01 Sep - 30 Sep</p>
                        </div>
                    </div>
                </article>
            </a>
            
            <a href="/venueuser_detail" class="flex-shrink-0 min-w-[280px] md:min-w-[300px] block">
                <article class="bg-white rounded-xl overflow-hidden shadow-xl hover:shadow-2xl transform hover:-translate-y-1 transition duration-300 border border-gray-100">
                    <div class="relative">
                        <img 
                            alt="Early Bird Discount" 
                            class="w-full h-40 object-cover" 
                            height="160" 
                            src="https://storage.googleapis.com/a1aa/image/2dc016db-2391-4056-8156-041f6d284417.jpg" 
                            width="300"
                        />
                        <span class="absolute top-3 right-3 bg-red-600 text-white text-xs font-bold rounded-full px-3 py-1 shadow-md">
                            🔥 PROMO SPESIAL
                        </span>
                    </div>
                    <div class="p-4">
                        <h4 class="font-bold text-base text-gray-900 mb-1 line-clamp-1">Early Bird Discount</h4>
                        <p class="text-sm text-blue-700 mb-2 font-medium">Morning Sports Complex</p>
                        <div class="flex items-center text-xs text-gray-500 pt-2 border-t border-gray-100">
                            <i class="far fa-calendar-alt mr-2"></i>
                            <p>Periode 01 Oct - 31 Oct</p>
                        </div>
                    </div>
                </article>
            </a>
            
        </div>
    </div>
</section>

  
  <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-16 mb-16">
    <h2 class="text-center font-bold text-3xl mb-10 text-gray-900">
        Pertanyaan Umum Seputar Layanan Olahraga
    </h2>
    <div class="space-y-6 max-w-4xl mx-auto">
        
        <details class="border-b border-gray-200 pb-4 group bg-white shadow-sm rounded-lg p-4">
            <summary class="cursor-pointer font-semibold text-lg flex justify-between items-center py-2 group-open:text-blue-700">
                Apa kelebihan sewa lapangan yang tersedia di Olga Sehat?
                <i class="fas fa-plus text-gray-700 group-open:text-blue-700 group-open:rotate-45 transition-transform"></i>
            </summary>
            <p class="text-base text-gray-600 mt-3 pl-4 border-l-4 border-blue-200">
                Olga Sehat menawarkan berbagai kelebihan, termasuk <span class="font-medium">sistem booking yang mudah dan real-time</span>, harga transparan, fasilitas terjamin kualitasnya, dan banyak pilihan venue olahraga (Futsal, Basket, Mini Soccer, dll.) terdekat dari lokasi Anda.
            </p>
        </details>
        
        <details class="border-b border-gray-200 pb-4 group bg-white shadow-sm rounded-lg p-4">
            <summary class="cursor-pointer font-semibold text-lg flex justify-between items-center py-2 group-open:text-blue-700">
                Bagaimana cara memesan lapangan di Olga Sehat?
                <i class="fas fa-plus text-gray-700 group-open:text-blue-700 group-open:rotate-45 transition-transform"></i>
            </summary>
            <p class="text-base text-gray-600 mt-3 pl-4 border-l-4 border-blue-200">
                Sangat mudah! Cukup gunakan kolom pencarian, pilih venue, jenis olahraga, tanggal, dan jam yang diinginkan. Lakukan pembayaran melalui opsi yang tersedia dan Anda akan mendapatkan e-tiket konfirmasi.
            </p>
        </details>
        
        <details class="border-b border-gray-200 pb-4 group bg-white shadow-sm rounded-lg p-4">
            <summary class="cursor-pointer font-semibold text-lg flex justify-between items-center py-2 group-open:text-blue-700">
                Berapa biaya sewa lapangan yang tersedia di Olga Sehat?
                <i class="fas fa-plus text-gray-700 group-open:text-blue-700 group-open:rotate-45 transition-transform"></i>
            </summary>
            <p class="text-base text-gray-600 mt-3 pl-4 border-l-4 border-blue-200">
                Biaya sewa bervariasi tergantung venue, jenis olahraga, dan jam booking (biasanya jam malam lebih mahal). Harga mulai dari sekitar Rp180.000 per sesi. Detail harga terperinci ada di halaman detail setiap venue.
            </p>
        </details>
        
        <details class="border-b border-gray-200 pb-4 group bg-white shadow-sm rounded-lg p-4">
            <summary class="cursor-pointer font-semibold text-lg flex justify-between items-center py-2 group-open:text-blue-700">
                Apakah ada diskon atau promo khusus untuk penyewaan lapangan di Olga Sehat?
                <i class="fas fa-plus text-gray-700 group-open:text-blue-700 group-open:rotate-45 transition-transform"></i>
            </summary>
            <p class="text-base text-gray-600 mt-3 pl-4 border-l-4 border-blue-200">
                Ya, kami secara rutin menawarkan berbagai promo, diskon *early bird*, atau potongan harga khusus di jam tertentu. Selalu cek bagian "Promo Venue" di halaman utama untuk penawaran terbaru!
            </p>
        </details>

        <details class="border-b border-gray-200 pb-4 group bg-white shadow-sm rounded-lg p-4">
            <summary class="cursor-pointer font-semibold text-lg flex justify-between items-center py-2 group-open:text-blue-700">
                Bagaimana kebijakan pembatalan dan pengembalian dana?
                <i class="fas fa-plus text-gray-700 group-open:text-blue-700 group-open:rotate-45 transition-transform"></i>
            </summary>
            <p class="text-base text-gray-600 mt-3 pl-4 border-l-4 border-blue-200">
                Kebijakan pembatalan bervariasi antar venue. Umumnya, pembatalan harus dilakukan minimal 24 jam sebelum jadwal. Silakan baca ketentuan pembatalan di halaman detail venue sebelum melakukan pembayaran.
            </p>
        </details>
    </div>
</section>

  <section class="max-w-9xl mx-auto px-4 sm:px-6 lg:px-8 mt-16">
  <div class="bg-gray-900 text-white rounded-xl p-8 mx-auto space-y-4 w-full"> 
    <p class="text-xs font-normal opacity-70">Khusus Pemilik Bisnis</p>
    <h2 class="text-3xl font-bold leading-tight">
      Solusi Kelola<br/>
      Fasilitas Olahraga<br/>
      Anda
    </h2>
    <p class="text-sm font-normal max-w-md leading-relaxed">
      Tingkatkan Potensi Pendapatan Lapangan & Nikmati
      <span class="font-semibold">#BisnisMakinMudah</span>
      dalam mengelola venue olahraga
    </p>
    <a class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm px-6 py-3 rounded-md mt-4" href="/loginpengelolavenue">Daftar Sekarang</a>
  </div>
</section>

  <script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('unifiedSearch');
    const suggestionsDropdown = document.getElementById('suggestionsDropdown');
    const sportCategory = document.getElementById('sportCategory');
    const searchForm = document.getElementById('venueSearchForm');
    let searchTimeout;
    let selectedIndex = -1;
    
    // Load sport categories from API
    function loadSportCategories() {
        fetch('{{ route("frontend.venue.categories") }}')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.categories && data.categories.length > 0) {
                    // Clear existing options except "Pilih Cabang Olahraga"
                    while (sportCategory.options.length > 1) {
                        sportCategory.remove(1);
                    }
                    
                    // Add categories from API
                    data.categories.forEach(category => {
                        const option = document.createElement('option');
                        option.value = category;
                        option.textContent = category;
                        sportCategory.appendChild(option);
                    });
                }
            })
            .catch(error => {
                console.error('Error loading categories:', error);
            });
    }
    
    // Load categories on page load
    if (sportCategory) {
        loadSportCategories();
    }
    
    if (!searchInput || !suggestionsDropdown) return;
    
    // Search function untuk suggestions
    function performSearch(query) {
        if (query.length < 2) {
            suggestionsDropdown.classList.add('hidden');
            return;
        }
        
        fetch(`{{ route('frontend.venue.search') }}?q=${encodeURIComponent(query)}&limit=8`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.results.length > 0) {
                    displaySuggestions(data.results, query);
                } else {
                    suggestionsDropdown.innerHTML = '<div class="p-4 text-center text-gray-500 text-sm">Tidak ada hasil ditemukan</div>';
                    suggestionsDropdown.classList.remove('hidden');
                }
            })
            .catch(error => {
                console.error('Search error:', error);
                suggestionsDropdown.classList.add('hidden');
            });
    }
    
    // Display suggestions dengan format yang rapi
    function displaySuggestions(results, query) {
        suggestionsDropdown.innerHTML = results.map((venue, index) => {
            // Highlight matching text
            const highlightText = (text, query) => {
                if (!text || !query) return text;
                const regex = new RegExp(`(${query})`, 'gi');
                return text.replace(regex, '<mark class="bg-yellow-200 px-1 rounded">$1</mark>');
            };
            
            const namaHighlighted = highlightText(venue.nama, query);
            const kotaHighlighted = highlightText(venue.kota, query);
            
            // Format lapangan list
            let lapanganHtml = '';
            if (venue.lapangan && venue.lapangan.length > 0) {
                const lapanganList = venue.lapangan.slice(0, 3).map(l => highlightText(l, query)).join(', ');
                const moreCount = venue.lapangan.length > 3 ? ` +${venue.lapangan.length - 3} lainnya` : '';
                lapanganHtml = `
                    <div class="mt-1 flex items-start">
                        <i class="fas fa-futbol text-gray-400 text-xs mt-0.5 mr-2"></i>
                        <span class="text-xs text-gray-600">${lapanganList}${moreCount}</span>
                    </div>
                `;
            }
            
            return `
                <div class="suggestion-item p-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-b-0 transition-colors duration-150" 
                     data-venue-id="${venue.id}" 
                     data-index="${index}">
                    <div class="flex items-start justify-between">
                        <div class="flex-1 min-w-0">
                            <h4 class="font-semibold text-gray-900 text-sm mb-1">${namaHighlighted}</h4>
                            <div class="flex items-center text-xs text-gray-600 mb-1">
                                <i class="fas fa-map-marker-alt mr-1.5 text-gray-400"></i>
                                <span>${kotaHighlighted}${venue.provinsi ? ', ' + venue.provinsi : ''}</span>
                            </div>
                            ${lapanganHtml}
                        </div>
                        <div class="ml-3 flex-shrink-0">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                ${Array.isArray(venue.kategori) ? venue.kategori.join(', ') : (venue.kategori || 'Olahraga')}
                            </span>
                        </div>
                    </div>
                </div>
            `;
        }).join('');
        
        suggestionsDropdown.classList.remove('hidden');
        selectedIndex = -1;
        
        // Add click handlers
        document.querySelectorAll('.suggestion-item').forEach(item => {
            item.addEventListener('click', function() {
                const venueId = this.getAttribute('data-venue-id');
                window.location.href = `{{ route('user.venue.detail', '') }}/${venueId}`;
            });
        });
    }
    
    // Input event with debounce
    searchInput.addEventListener('input', function(e) {
        clearTimeout(searchTimeout);
        const query = e.target.value.trim();
        
        searchTimeout = setTimeout(() => {
            performSearch(query);
        }, 300);
    });
    
    // Keyboard navigation
    searchInput.addEventListener('keydown', function(e) {
        const items = document.querySelectorAll('.suggestion-item');
        
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            selectedIndex = Math.min(selectedIndex + 1, items.length - 1);
            updateSelection(items);
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            selectedIndex = Math.max(selectedIndex - 1, -1);
            updateSelection(items);
        } else if (e.key === 'Enter' && selectedIndex >= 0) {
            e.preventDefault();
            if (items[selectedIndex]) {
                items[selectedIndex].click();
            }
        } else if (e.key === 'Escape') {
            suggestionsDropdown.classList.add('hidden');
            selectedIndex = -1;
        }
    });
    
    function updateSelection(items) {
        items.forEach((item, index) => {
            if (index === selectedIndex) {
                item.classList.add('bg-blue-50');
                item.classList.remove('hover:bg-gray-50');
            } else {
                item.classList.remove('bg-blue-50');
                item.classList.add('hover:bg-gray-50');
            }
        });
    }
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !suggestionsDropdown.contains(e.target)) {
            suggestionsDropdown.classList.add('hidden');
            selectedIndex = -1;
        }
    });
    
    // Form submission with filter
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(searchForm);
            const params = new URLSearchParams();
            
            // Query search now includes both venue name and city
            if (formData.get('q')) {
                params.append('q', formData.get('q'));
            }
            if (formData.get('kategori') && formData.get('kategori') !== 'all') {
                params.append('kategori', formData.get('kategori'));
            }
            // Tambahkan parameter tanggal jika ada
            if (formData.get('tanggal')) {
                params.append('tanggal', formData.get('tanggal'));
            }
            
            // Redirect to filter route with parameters (use user route)
            const filterUrl = '/venueuser?' + params.toString();
            window.location.href = filterUrl;
        });
    }
});

</script>
  @endsection
