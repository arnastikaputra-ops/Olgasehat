@extends('FRONTEND.layout.frontend')

@section('content')

   <section class="bg-[url('assets/blue-banner.png')] bg-no-repeat text-white relative overflow-hidden h-[300px] flex items-center justify-center" style="background-size: 1910px 300px;">
  <div class="container mx-auto px-6 text-center w-full">
    <h1 class="text-3xl md:text-4xl font-bold tracking-wide mt-10">
      TEMUKAN CLUB MENARIK UNTUKMU
    </h1>
  </div>
  </section>

  <!-- Search Filters -->
  <section class="container mx-auto px-6 py-8">
    <form class="flex flex-wrap gap-4 justify-center md:justify-start items-center">
      <input
        type="text"
        placeholder="Cari nama klub"
        class="flex-grow min-w-[180px] border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-600"
      />
      <select
        class="border border-gray-300 rounded-md px-4 py-2 min-w-[140px] focus:outline-none focus:ring-2 focus:ring-blue-600"
      >
        <option disabled selected>Pilih Kota</option>
        <option>Denpasar</option>
        <option>Jakarta</option>
        <option>Surabaya</option>
        <option>Bandung</option>
      </select>
      <select
        class="border border-gray-300 rounded-md px-4 py-2 min-w-[180px] focus:outline-none focus:ring-2 focus:ring-blue-600"
      >
        <option disabled selected>Pilih Cabang Olahraga</option>
        <option>Futsal</option>
        <option>Basketball</option>
        <option>Mini Soccer</option>
        <option>Badminton</option>
      </select>
      <input
        type="date"
        class="border border-gray-300 rounded-md px-4 py-2 min-w-[160px] focus:outline-none focus:ring-2 focus:ring-blue-600"
      />
      <button
        type="submit"
        class="bg-blue-700 text-white px-6 py-2 rounded-md hover:bg-blue-800 transition min-w-[120px]"
      >
        Cari Venue
      </button>
    </form>
  </section>

  <!-- Club Cards Grid -->
  <section class="container mx-auto px-6 pb-12">
    <h2 class="font-semibold text-lg mb-4">
      Semua <span class="text-blue-700">Club</span>
    </h2>
    <div
      class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6"
      aria-label="Daftar club olahraga"
    >
      <!-- Club Card 1 -->
       <a href="/club-detail">
        <article class="border border-gray-200 rounded-lg shadow-sm p-4 hover:shadow-md transition">
        <div class="flex items-center space-x-4 mb-3">
          <img src="assets/foerda-icon.png" alt="BLESSCON Logo" class="w-12 h-12 rounded-full object-cover" />
          <div>
            <p class="text-xs text-gray-500">Mini Soccer</p>
            <h3 class="font-semibold text-lg">FOERDA 61</h3>
            <p class="text-xs text-gray-600 flex items-center space-x-1">
              <i class="fas fa-shield-alt text-gray-400"></i>
              <span>Level Putih I</span>
            </p>
          </div>
        </div>
        <div class="text-xs text-gray-500 space-y-1">
          <p class="flex items-center space-x-2">
            <i class="far fa-calendar-alt"></i>
            <span>12 Aug 2025 · 18:30</span>
          </p>
          <p class="flex items-center space-x-2">
            <i class="fas fa-map-marker-alt"></i>
            <span>Mini Soccer · Morley Soccer Arena</span>
          </p>
          <p class="flex items-center space-x-2">
            <i class="fas fa-wallet"></i>
            <span>Biaya · 245.000</span>
          </p>
          <p class="flex items-center space-x-2">
            <i class="fas fa-hand-holding-dollar"></i>
            <span>Dp · 0</span>
          </p>
        </div>
      </article>
       </a>

      <!-- Club Card 2 -->
      <article class="border border-gray-200 rounded-lg shadow-sm p-4 hover:shadow-md transition">
        <div class="flex items-center space-x-4 mb-3">
          <img src="assets/CREWCALL_FC.png" alt="CREWCALL FC Logo" class="w-12 h-12 rounded-full object-cover" />
          <div>
            <p class="text-xs text-gray-500">Mini Soccer</p>
            <h3 class="font-semibold text-lg">CREWCALL FC</h3>
            <p class="text-xs text-gray-600 flex items-center space-x-1">
              <i class="fas fa-shield-alt text-gray-400"></i>
              <span>Level Putih I · <i class="fas fa-star text-yellow-400"></i> 4.90</span>
            </p>
          </div>
        </div>
        <div class="text-xs text-gray-500 space-y-1">
          <p class="flex items-center space-x-2">
            <i class="far fa-calendar-alt"></i>
            <span>14 Aug 2025 · 19:30</span>
          </p>
          <p class="flex items-center space-x-2">
            <i class="fas fa-map-marker-alt"></i>
            <span>A, Fuerza Arena</span>
          </p>
          <p class="flex items-center space-x-2">
            <i class="fas fa-wallet"></i>
            <span>Biaya · 1.650.000</span>
          </p>
          <p class="flex items-center space-x-2">
            <i class="fas fa-hand-holding-dollar"></i>
            <span>Dp · 0</span>
          </p>
        </div>
      </article>

      <!-- Club Card 3 -->
      <article class="border border-gray-200 rounded-lg shadow-sm p-4 hover:shadow-md transition">
        <div class="flex items-center space-x-4 mb-3">
          <img src="assets/CIMKID_FC.png" alt="CIMKID FC Logo" class="w-12 h-12 rounded-full object-cover" />
          <div>
            <p class="text-xs text-gray-500">Mini Soccer</p>
            <h3 class="font-semibold text-lg">CiMKID FC</h3>
            <p class="text-xs text-gray-600 flex items-center space-x-1">
              <i class="fas fa-shield-alt text-gray-400"></i>
              <span>Level Putih I</span>
            </p>
          </div>
        </div>
        <div class="text-xs text-gray-500 space-y-1">
          <p class="flex items-center space-x-2">
            <i class="far fa-calendar-alt"></i>
            <span>15 Aug 2025 · 20:00</span>
          </p>
          <p class="flex items-center space-x-2">
            <i class="fas fa-map-marker-alt"></i>
            <span>ZSC mini soccer, ZSC Mini Soccer</span>
          </p>
          <p class="flex items-center space-x-2">
            <i class="fas fa-wallet"></i>
            <span>Biaya · 2.100.000</span>
          </p>
          <p class="flex items-center space-x-2">
            <i class="fas fa-hand-holding-dollar"></i>
            <span>Dp · 400.000</span>
          </p>
        </div>
      </article>

      <!-- Club Card 4 -->
      <article class="border border-gray-200 rounded-lg shadow-sm p-4 hover:shadow-md transition">
        <div class="flex items-center space-x-4 mb-3">
          <img src="assets/PB_SUKA_SUKA.png" alt="PB.Suka Suka Logo" class="w-12 h-12 rounded-full object-cover" />
          <div>
            <p class="text-xs text-gray-500">Badminton</p>
            <h3 class="font-semibold text-lg">PB.Suka Suka</h3>
            <p class="text-xs text-gray-600 flex items-center space-x-1">
              <i class="fas fa-shield-alt text-gray-400"></i>
              <span>Level Putih I</span>
            </p>
          </div>
        </div>
        <div class="text-xs text-gray-500 space-y-1">
          <p class="flex items-center space-x-2">
            <i class="far fa-calendar-alt"></i>
            <span>15 Aug 2025 · 21:00</span>
          </p>
          <p class="flex items-center space-x-2">
            <i class="fas fa-map-marker-alt"></i>
            <span>Lapangan A, GOR IBNU MANDIRI</span>
          </p>
          <p class="flex items-center space-x-2">
            <i class="fas fa-wallet"></i>
            <span>Biaya · 230.000</span>
          </p>
          <p class="flex items-center space-x-2">
            <i class="fas fa-hand-holding-dollar"></i>
            <span>Dp · 130.000</span>
          </p>
        </div>
      </article>

      <!-- Club Card 5 -->
      <article class="border border-gray-200 rounded-lg shadow-sm p-4 hover:shadow-md transition">
        <div class="flex items-center space-x-4 mb-3">
          <img src="assets/GARUDA_MUDA_FC.png" alt="GARUDA MUDA FC Logo" class="w-12 h-12 rounded-full object-cover" />
          <div>
            <p class="text-xs text-gray-500">Sepak Bola</p>
            <h3 class="font-semibold text-lg">GARUDA MUDA FC</h3>
            <p class="text-xs text-gray-600 flex items-center space-x-1">
              <i class="fas fa-shield-alt text-gray-400"></i>
              <span>Level Putih II · <i class="fas fa-star text-yellow-400"></i> 4.98</span>
            </p>
          </div>
        </div>
        <div class="text-xs text-gray-500 space-y-1">
          <p class="flex items-center space-x-2">
            <i class="far fa-calendar-alt"></i>
            <span>16 Aug 2025 · 15:30</span>
          </p>
          <p class="flex items-center space-x-2">
            <i class="fas fa-map-marker-alt"></i>
            <span>Stadion Viyata Jales Yudha, Stadion Militer Viyata Jales Yudha</span>
          </p>
          <p class="flex items-center space-x-2">
            <i class="fas fa-wallet"></i>
            <span>Biaya · 1.150.000</span>
          </p>
          <p class="flex items-center space-x-2">
            <i class="fas fa-hand-holding-dollar"></i>
            <span>Dp · 0</span>
          </p>
        </div>
      </article>

      <!-- Club Card 6 -->
      <article class="border border-gray-200 rounded-lg shadow-sm p-4 hover:shadow-md transition">
        <div class="flex items-center space-x-4 mb-3">
          <img src="assets/GOLLONZO_FOOTBALL_CLUB.png" alt="Gollonzo Football Club Logo" class="w-12 h-12 rounded-full object-cover" />
          <div>
            <p class="text-xs text-gray-500">Sepak Bola</p>
            <h3 class="font-semibold text-lg">Gollonzo Football Club</h3>
            <p class="text-xs text-gray-600 flex items-center space-x-1">
              <i class="fas fa-shield-alt text-gray-400"></i>
              <span>Level Putih I · <i class="fas fa-star text-yellow-400"></i> 5.00</span>
            </p>
          </div>
        </div>
        <div class="text-xs text-gray-500 space-y-1">
          <p class="flex items-center space-x-2">
            <i class="far fa-calendar-alt"></i>
            <span>16 Aug 2025 · 16:00</span>
          </p>
          <p class="flex items-center space-x-2">
            <i class="fas fa-map-marker-alt"></i>
            <span>Ex Arcici, ASIOP Stadium (ASTA)</span>
          </p>
          <p class="flex items-center space-x-2">
            <i class="fas fa-wallet"></i>
            <span>Biaya · 3.300.000</span>
          </p>
          <p class="flex items-center space-x-2">
            <i class="fas fa-hand-holding-dollar"></i>
            <span>Dp · 0</span>
          </p>
        </div>
      </article>

      <!-- Club Card 7 -->
      <article class="border border-gray-200 rounded-lg shadow-sm p-4 hover:shadow-md transition">
        <div class="flex items-center space-x-4 mb-3">
          <img src="assets/DRAKEN_MASTER.png" alt="Dranken Master Logo" class="w-12 h-12 rounded-full object-cover" />
          <div>
            <p class="text-xs text-gray-500">Mini Soccer</p>
            <h3 class="font-semibold text-lg">Dranken Master</h3>
            <p class="text-xs text-gray-600 flex items-center space-x-1">
              <i class="fas fa-shield-alt text-gray-400"></i>
              <span>Level Putih I · <i class="fas fa-star text-yellow-400"></i> 5.00</span>
            </p>
          </div>
        </div>
        <div class="text-xs text-gray-500 space-y-1">
          <p class="flex items-center space-x-2">
            <i class="far fa-calendar-alt"></i>
            <span>16 Aug 2025 · 16:00</span>
          </p>
          <p class="flex items-center space-x-2">
            <i class="fas fa-map-marker-alt"></i>
            <span>Lapangan A, Koci Soccer Field</span>
          </p>
          <p class="flex items-center space-x-2">
            <i class="fas fa-wallet"></i>
            <span>Biaya · 1.100.000</span>
          </p>
          <p class="flex items-center space-x-2">
            <span>Dp · 0</span>
          </p>
        </div>
      </article>

      <!-- Club Card 8 -->
      <article class="border border-gray-200 rounded-lg shadow-sm p-4 hover:shadow-md transition">
        <div class="flex items-center space-x-4 mb-3">
          <img src="assets/YMMI_FC.png" alt="YMMI FC Logo" class="w-12 h-12 rounded-full object-cover" />
          <div>
            <p class="text-xs text-gray-500">Mini Soccer</p>
            <h3 class="font-semibold text-lg">YMMI FC</h3>
            <p class="text-xs text-gray-600 flex items-center space-x-1">
              <i class="fas fa-shield-alt text-gray-400"></i>
              <span>Level Putih I</span>
            </p>
          </div>
        </div>
        <div class="text-xs text-gray-500 space-y-1">
          <p class="flex items-center space-x-2">
            <i class="far fa-calendar-alt"></i>
            <span>16 Aug 2025 · 18:00</span>
          </p>
          <p class="flex items-center space-x-2">
            <i class="fas fa-map-marker-alt"></i>
            <span>Lapangan 1, Goedang mini soccer</span>
          </p>
          <p class="flex items-center space-x-2">
            <i class="fas fa-wallet"></i>
            <span>Biaya · 950.000</span>
          </p>
          <p class="flex items-center space-x-2">
            <i class="fas fa-hand-holding-dollar"></i>
            <span>Dp · 300.000</span>
          </p>
        </div>
      </article>

      <!-- Club Card 9 -->
      <article class="border border-gray-200 rounded-lg shadow-sm p-4 hover:shadow-md transition">
        <div class="flex items-center space-x-4 mb-3">
          <img src="assets/EX_PLAYBOY.png" alt="Ex-Playboy Logo" class="w-12 h-12 rounded-full object-cover" />
          <div>
            <p class="text-xs text-gray-500">Futsal</p>
            <h3 class="font-semibold text-lg">Ex-Playboy</h3>
            <p class="text-xs text-gray-600 flex items-center space-x-1">
              <i class="fas fa-shield-alt text-gray-400"></i>
              <span>Level Putih I</span>
            </p>
          </div>
        </div>
        <div class="text-xs text-gray-500 space-y-1">
          <p class="flex items-center space-x-2">
            <i class="far fa-calendar-alt"></i>
            <span>16 Aug 2025 · 20:00</span>
          </p>
          <p class="flex items-center space-x-2">
            <i class="fas fa-map-marker-alt"></i>
            <span>CGV SPORTS HALL, CGV Cinema and Sport Hall</span>
          </p>
          <p class="flex items-center space-x-2">
            <i class="fas fa-wallet"></i>
            <span>Biaya · 550.000</span>
          </p>
          <p class="flex items-center space-x-2">
            <i class="fas fa-hand-holding-dollar"></i>
            <span>Dp · 350.000</span>
          </p>
        </div>
      </article>
    </div>
  </section>

  @endsection