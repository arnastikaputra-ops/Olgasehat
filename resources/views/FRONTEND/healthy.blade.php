@extends('layouts.app')

@section('content')

@php
    $featuredClinics = $featuredClinics ?? collect();
@endphp

<section class="bg-[url('assets/blue-banner.png')] bg-no-repeat text-white relative overflow-hidden h-[300px] flex items-center justify-center" style="background-size: 1910px 300px;">
    <div class="absolute inset-0 bg-blue-900 bg-opacity-30"></div>
    <div class="container mx-auto px-6 text-center w-full relative z-10">
        <h1 class="text-3xl md:text-5xl font-extrabold tracking-wide mt-10">
            LAYANAN KESEHATAN TERDEKAT
        </h1>
        <p class="text-lg mt-3 opacity-90 max-w-3xl mx-auto">
            Temukan Layanan Kesehatan, Fisioterapi, dan Cek Medis Terdekat yang teruji dan terpercaya.
        </p>
    </div>
</section>

<section class="container mx-auto px-6 py-6">
    <form id="healthySearchForm" class="bg-white rounded-lg border border-gray-200 shadow-sm" method="GET" action="{{ route('frontend.healthy') }}">
      <div class="flex flex-col lg:flex-row items-stretch gap-2 p-2">
        
        <!-- Search Input - Cari layanan kesehatan -->
        <div class="relative flex-[2] min-w-0">
          <div class="relative">
            <input
              type="text"
              id="unifiedSearch"
              name="q"
              value="{{ request('q') }}"
              placeholder="Cari layanan (e.g., Fisioterapi, Cek Kolesterol, Klinik Mata)"
              class="w-full border border-gray-300 rounded-lg px-4 pl-10 py-3 text-gray-700 placeholder-gray-400 focus:outline-none focus:border-gray-400 transition-all duration-150 bg-white"
              autocomplete="off"
            />
            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
          </div>
          <!-- Suggestions Dropdown -->
          <div id="suggestionsDropdown" class="absolute top-full left-0 w-full bg-white border border-gray-200 rounded-lg shadow-xl max-h-80 overflow-y-auto hidden z-50 mt-1">
          </div>
        </div>
        
        <!-- Category Dropdown - Jenis Layanan -->
        <div class="relative flex-1 min-w-0">
          <div class="relative">
            <select
              id="serviceCategory"
              name="jenis_layanan"
              class="w-full border border-gray-300 rounded-lg px-4 pl-10 pr-10 py-3 text-gray-700 focus:outline-none focus:border-gray-400 transition-all duration-150 bg-white appearance-none cursor-pointer"
            >
              <option value="all" {{ !request('jenis_layanan') || request('jenis_layanan') == 'all' ? 'selected' : '' }}>Jenis Layanan</option>
              @foreach($serviceCategories ?? [] as $category)
                <option value="{{ $category }}" {{ request('jenis_layanan') == $category ? 'selected' : '' }}>{{ $category }}</option>
              @endforeach
            </select>
            <i class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm pointer-events-none"></i>
            <i class="fas fa-heartbeat absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
          </div>
        </div>

        <!-- Date Input - Pilih Tanggal -->
        <div class="relative flex-1 min-w-0">
          <div class="relative">
            <input
              type="date"
              id="healthyBookingDate"
              name="tanggal"
              value="{{ request('tanggal') }}"
              class="w-full border border-gray-300 rounded-lg px-4 pl-10 pr-4 py-3 text-gray-700 focus:outline-none focus:border-gray-400 transition-all duration-150 bg-white cursor-pointer"
            />
            <i class="fas fa-calendar-alt absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm pointer-events-none"></i>
          </div>
        </div>

        <!-- Time Select - Pilih Jam -->
        <div class="relative flex-1 min-w-0">
          <div class="relative">
            <select
              id="healthyBookingTime"
              name="jam"
              class="w-full border border-gray-300 rounded-lg px-4 pl-10 pr-10 py-3 text-gray-700 focus:outline-none focus:border-gray-400 transition-all duration-150 bg-white appearance-none cursor-pointer"
            >
              <option value="all" {{ !request('jam') || request('jam') == 'all' ? 'selected' : '' }}>Pilih Jam / Sesi</option>
              @foreach(['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'] as $timeOpt)
                <option value="{{ $timeOpt }}" {{ request('jam') == $timeOpt ? 'selected' : '' }}>{{ $timeOpt }} WIB</option>
              @endforeach
            </select>
            <i class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm pointer-events-none"></i>
            <i class="fas fa-clock absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
          </div>
        </div>
        
        <!-- Search Button - Cari Layanan -->
        <button
          type="submit"
          class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 whitespace-nowrap"
        >
          Cari Layanan
        </button>
        
      </div>
    </form>
  </section>

<section class="container mx-auto px-6 py-10">
    <div class="text-center mb-12">
        <h1 class="text-4xl md:text-5xl font-extrabold mb-3 bg-gradient-to-r from-blue-600 to-green-600 bg-clip-text text-transparent">Kalkulator Kesehatan</h1>
        <p class="text-gray-600 text-lg">Pantau kesehatan Anda dengan mudah dan dapatkan rekomendasi personal</p>
    </div>

    <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8">


<!-- ====================================== -->
<!-- === KALKULATOR KOLESTEROL ============ -->
<!-- ====================================== -->

<div class="bg-gradient-to-br from-white to-blue-50 shadow-xl rounded-2xl p-6 border border-blue-100 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
  <div class="flex items-center justify-center mb-6">
    <div class="bg-blue-100 p-4 rounded-full">
      <i class="fas fa-heartbeat text-blue-600 text-2xl"></i>
    </div>
  </div>
  <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Kalkulator Kolesterol</h2>

  <div class="space-y-4 mb-6">
    <div>
      <label class="block mb-2 font-semibold text-gray-700 flex items-center">
        <i class="fas fa-vial text-blue-500 mr-2 text-sm"></i>
        Masukkan kadar LDL (mg/dL)
      </label>
      <input type="number" id="ldl" class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 outline-none" placeholder="contoh: 130">
    </div>

    <div>
      <label class="block mb-2 font-semibold text-gray-700 flex items-center">
        <i class="fas fa-vial text-blue-500 mr-2 text-sm"></i>
        Masukkan kadar HDL (mg/dL)
      </label>
      <input type="number" id="hdl" class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 outline-none" placeholder="contoh: 45">
    </div>
  </div>

  <div class="flex gap-3">
    <button onclick="hitungKolesterol()" class="flex-1 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold py-3 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-[1.02] transition-all duration-200 flex items-center justify-center space-x-2">
      <i class="fas fa-calculator"></i>
      <span>Hitung Kolesterol</span>
    </button>
    <button onclick="resetKolesterol()" class="px-4 bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-[1.02] transition-all duration-200 flex items-center justify-center" title="Reset">
      <i class="fas fa-redo"></i>
    </button>
  </div>

  <div id="hasilKolesterol" class="mt-6 p-5 rounded-xl border-2 text-center bg-gray-50 text-gray-600 transition-all duration-300">
    <div class="flex items-center justify-center mb-3">
      <i class="fas fa-chart-line text-3xl text-gray-400"></i>
    </div>
    <p class="text-4xl font-extrabold mb-2" id="kolesterolNilai">–</p>
    <p class="font-bold text-lg mb-4" id="kolesterolStatus">Belum ada hasil</p>

    <div class="w-full h-4 rounded-full mt-4 flex overflow-hidden bg-gray-200 shadow-inner" id="barKolesterol">
      <div class="w-1/4 h-4 bg-gray-300 transition-all duration-500"></div>
      <div class="w-1/4 h-4 bg-gray-300 transition-all duration-500"></div>
      <div class="w-1/4 h-4 bg-gray-300 transition-all duration-500"></div>
      <div class="w-1/4 h-4 bg-gray-300 transition-all duration-500"></div>
    </div>

    <div class="flex justify-between text-xs text-gray-500 mt-2 mb-4">
      <span class="font-medium">&lt;100</span><span class="font-medium">100-159</span><span class="font-medium">160-189</span><span class="font-medium">&gt;190</span>
    </div>

    <div class="text-sm mt-4 text-left bg-white rounded-lg p-4 border border-gray-200" id="kolesterolSaran">
      <p class="text-center text-gray-500 italic">Isi data dan klik "Hitung Kolesterol".</p>
    </div>
  </div>
</div>



<!-- ====================================== -->
<!-- === KALKULATOR GULA DARAH ============ -->
<!-- ====================================== -->

<div class="bg-gradient-to-br from-white to-green-50 shadow-xl rounded-2xl p-6 border border-green-100 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
  <div class="flex items-center justify-center mb-6">
    <div class="bg-green-100 p-4 rounded-full">
      <i class="fas fa-tint text-green-600 text-2xl"></i>
    </div>
  </div>
  <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Kalkulator Gula Darah</h2>

  <div class="space-y-4 mb-6">
    <div>
      <label class="block mb-2 font-semibold text-gray-700 flex items-center">
        <i class="fas fa-vial text-green-500 mr-2 text-sm"></i>
        Total Gula Darah (mg/dL)
      </label>
      <input type="number" id="gula" class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all duration-200 outline-none" placeholder="contoh: 110">
    </div>

    <div>
      <label class="block mb-2 font-semibold text-gray-700 flex items-center">
        <i class="fas fa-clock text-green-500 mr-2 text-sm"></i>
        Waktu Pengukuran
      </label>
      <select id="waktuGula" class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all duration-200 outline-none">
        <option value="puasa">Puasa</option>
        <option value="2jam">2 Jam Setelah Makan</option>
      </select>
    </div>
  </div>

  <div class="flex gap-3">
    <button onclick="hitungGula()" class="flex-1 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-bold py-3 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-[1.02] transition-all duration-200 flex items-center justify-center space-x-2">
      <i class="fas fa-calculator"></i>
      <span>Hitung Gula Darah</span>
    </button>
    <button onclick="resetGula()" class="px-4 bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-[1.02] transition-all duration-200 flex items-center justify-center" title="Reset">
      <i class="fas fa-redo"></i>
    </button>
  </div>

  <div id="hasilGula" class="mt-6 p-5 rounded-xl border-2 text-center bg-gray-50 text-gray-600 transition-all duration-300">
    <div class="flex items-center justify-center mb-3">
      <i class="fas fa-chart-line text-3xl text-gray-400"></i>
    </div>
    <p class="text-4xl font-extrabold mb-2" id="gulaNilai">–</p>
    <p class="font-bold text-lg mb-4" id="gulaStatus">Belum ada hasil</p>

    <div class="w-full h-4 rounded-full mt-4 flex overflow-hidden bg-gray-200 shadow-inner" id="barGula">
      <div class="w-1/4 h-4 bg-gray-300 transition-all duration-500"></div>
      <div class="w-1/4 h-4 bg-gray-300 transition-all duration-500"></div>
      <div class="w-1/4 h-4 bg-gray-300 transition-all duration-500"></div>
      <div class="w-1/4 h-4 bg-gray-300 transition-all duration-500"></div>
    </div>

    <div class="flex justify-between text-xs text-gray-500 mt-2 mb-4">
      <span class="font-medium">Rendah</span><span class="font-medium">Normal</span><span class="font-medium">Pra-Diabetes</span><span class="font-medium">Tinggi</span>
    </div>

    <div class="text-sm mt-4 text-left bg-white rounded-lg p-4 border border-gray-200" id="gulaSaran">
      <p class="text-center text-gray-500 italic">Isi data dan klik "Hitung Gula Darah".</p>
    </div>
  </div>
</div>



<!-- ====================================== -->
<!-- ========== KALKULATOR BMI ============ -->
<!-- ====================================== -->

<div class="bg-gradient-to-br from-white to-purple-50 shadow-xl rounded-2xl p-6 border border-purple-100 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
  <div class="flex items-center justify-center mb-6">
    <div class="bg-purple-100 p-4 rounded-full">
      <i class="fas fa-weight text-purple-600 text-2xl"></i>
    </div>
  </div>
  <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Kalkulator BMI</h2>

  <div class="space-y-4 mb-6">
    <div>
      <label class="block mb-2 font-semibold text-gray-700 flex items-center">
        <i class="fas fa-weight text-purple-500 mr-2 text-sm"></i>
        Berat (kg)
      </label>
      <input type="number" id="berat" class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all duration-200 outline-none" placeholder="contoh: 70">
    </div>

    <div>
      <label class="block mb-2 font-semibold text-gray-700 flex items-center">
        <i class="fas fa-ruler-vertical text-purple-500 mr-2 text-sm"></i>
        Tinggi (cm)
      </label>
      <input type="number" id="tinggi" class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all duration-200 outline-none" placeholder="contoh: 170">
    </div>
  </div>

  <div class="flex gap-3">
    <button onclick="hitungBMI()" class="flex-1 bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white font-bold py-3 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-[1.02] transition-all duration-200 flex items-center justify-center space-x-2">
      <i class="fas fa-calculator"></i>
      <span>Hitung BMI</span>
    </button>
    <button onclick="resetBMI()" class="px-4 bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-[1.02] transition-all duration-200 flex items-center justify-center" title="Reset">
      <i class="fas fa-redo"></i>
    </button>
  </div>

  <div id="hasilBMI" class="mt-6 p-5 rounded-xl border-2 text-center bg-gray-50 text-gray-600 transition-all duration-300">
    <div class="flex items-center justify-center mb-3">
      <i class="fas fa-chart-line text-3xl text-gray-400"></i>
    </div>
    <p class="text-4xl font-extrabold mb-2" id="bmiNilai">–</p>
    <p class="font-bold text-lg mb-4" id="bmiStatus">Belum ada hasil</p>

    <div class="w-full h-4 rounded-full mt-4 flex overflow-hidden bg-gray-200 shadow-inner" id="barBMI">
      <div class="w-1/4 h-4 bg-gray-300 transition-all duration-500"></div>
      <div class="w-1/4 h-4 bg-gray-300 transition-all duration-500"></div>
      <div class="w-1/4 h-4 bg-gray-300 transition-all duration-500"></div>
      <div class="w-1/4 h-4 bg-gray-300 transition-all duration-500"></div>
    </div>

    <div class="flex justify-between text-xs text-gray-500 mt-2 mb-4">
      <span class="font-medium">&lt;18.5</span><span class="font-medium">18.5-25</span><span class="font-medium">25-30</span><span class="font-medium">&gt;30</span>
    </div>

    <div class="text-sm mt-4 text-left bg-white rounded-lg p-4 border border-gray-200" id="bmiSaran">
      <p class="text-center text-gray-500 italic">Isi data dan klik "Hitung BMI".</p>
    </div>
  </div>
</div>

    </div>
</section>




<!-- ====================================== -->
<!-- ============ SCRIPT ================== -->
<!-- ====================================== -->

<script>

// === Fungsi membuat list saran ===
function tampilkanSaran(id, list, warna = "blue") {
  let html = "<div class='space-y-2'>";
  const icons = ['check-circle', 'lightbulb', 'heart', 'dumbbell', 'utensils', 'running', 'user-md', 'calendar-check'];
  const colorMap = {
    'blue': 'text-blue-500',
    'green': 'text-green-500',
    'yellow': 'text-yellow-500',
    'orange': 'text-orange-500',
    'red': 'text-red-500',
    'purple': 'text-purple-500'
  };
  const iconColor = colorMap[warna] || 'text-blue-500';
  
  list.forEach((s, index) => {
    const icon = icons[index % icons.length];
    const borderColorClass = `border-l-4 border-${warna}-300`;
    const bgColorClass = `bg-${warna}-50`;
    html += `<div class='flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 transition-all duration-200 ${borderColorClass} ${bgColorClass} opacity-70 hover:opacity-100'>
      <i class='fas fa-${icon} ${iconColor} mt-0.5 flex-shrink-0 text-base'></i>
      <span class='text-gray-700 leading-relaxed text-sm font-medium'>${s}</span>
    </div>`;
  });
  html += "</div>";
  document.getElementById(id).innerHTML = html;
}

// === Fungsi warnai bar indikator ===
function warnaiBar(barId, index, warna) {
  const bar = document.getElementById(barId).children;
  for (let i = 0; i < bar.length; i++) {
    bar[i].className = "w-1/4 h-4 transition-all duration-500 " + (i === index ? warna + " shadow-lg" : "bg-gray-300");
  }
}


/* ============================================================
   ===================  KOLESTEROL  ============================
   ============================================================ */

function hitungKolesterol() {
  const ldl = parseFloat(document.getElementById("ldl").value);
  const hdl = parseFloat(document.getElementById("hdl").value);
  const nilai = document.getElementById("kolesterolNilai");
  const status = document.getElementById("kolesterolStatus");
  const hasil = document.getElementById("hasilKolesterol");

  if (isNaN(ldl) || isNaN(hdl)) {
    nilai.textContent = "–";
    status.textContent = "Data tidak valid";
    tampilkanSaran("kolesterolSaran", ["Masukkan angka LDL dan HDL yang benar."], "red");
    hasil.className = "mt-6 p-5 rounded-xl border-2 text-center bg-red-50 border-red-300 text-red-700 transition-all duration-300";
    hasil.innerHTML = '<div class="flex items-center justify-center mb-3"><i class="fas fa-exclamation-circle text-red-500 text-3xl"></i></div><p class="text-4xl font-extrabold mb-2" id="kolesterolNilai">–</p><p class="font-bold text-lg mb-4" id="kolesterolStatus">Data tidak valid</p><div class="w-full h-4 rounded-full mt-4 flex overflow-hidden bg-gray-200 shadow-inner" id="barKolesterol"><div class="w-1/4 h-4 bg-gray-300 transition-all duration-500"></div><div class="w-1/4 h-4 bg-gray-300 transition-all duration-500"></div><div class="w-1/4 h-4 bg-gray-300 transition-all duration-500"></div><div class="w-1/4 h-4 bg-gray-300 transition-all duration-500"></div></div><div class="flex justify-between text-xs text-gray-500 mt-2 mb-4"><span class="font-medium">&lt;100</span><span class="font-medium">100-159</span><span class="font-medium">160-189</span><span class="font-medium">&gt;190</span></div><div class="text-sm mt-4 text-left bg-white rounded-lg p-4 border border-gray-200" id="kolesterolSaran"></div>';
    warnaiBar("barKolesterol", -1);
    return;
  }

  let kategori, warnaKotak, warnaBar, index, saranList;

  if (ldl < 100) {
    kategori = "Normal";
    warnaKotak = "bg-green-50 border-green-400 text-green-700";
    warnaBar = "bg-green-500"; 
    warnaSaran = "green";
    index = 1;
    saranList = [
      "Pertahankan pola makan sehat dengan porsi seimbang",
      "Konsumsi makanan tinggi serat seperti oatmeal, kacang-kacangan, dan buah-buahan",
      "Olahraga teratur 3–4 kali per minggu (aerobik, jogging, atau bersepeda)",
      "Batasi konsumsi daging merah, ganti dengan ikan atau ayam tanpa kulit",
      "Konsumsi lemak baik dari alpukat, kacang almond, dan minyak zaitun",
      "Jaga berat badan ideal dan hindari stres berlebihan",
      "Periksa kolesterol rutin setiap 6-12 bulan"
    ];
  } else if (ldl <= 159) {
    kategori = "Batas Atas Normal";
    warnaKotak = "bg-yellow-50 border-yellow-400 text-yellow-700";
    warnaBar = "bg-yellow-500"; 
    warnaSaran = "yellow";
    index = 2;
    saranList = [
      "Kurangi konsumsi makanan berminyak dan gorengan secara signifikan",
      "Tingkatkan konsumsi sayuran hijau (brokoli, bayam) minimal 2 porsi per hari",
      "Konsumsi buah-buahan yang kaya antioksidan seperti apel, berry, dan jeruk",
      "Lakukan olahraga kardio 30-45 menit setiap hari (jalan cepat, berenang, atau bersepeda)",
      "Batasi konsumsi produk susu tinggi lemak, pilih susu rendah lemak",
      "Hindari makanan cepat saji dan makanan olahan",
      "Konsumsi omega-3 dari ikan salmon, tuna, atau suplemen",
      "Konsultasi dengan ahli gizi untuk rencana diet yang tepat"
    ];
  } else if (ldl <= 189) {
    kategori = "Tinggi";
    warnaKotak = "bg-orange-50 border-orange-400 text-orange-700";
    warnaBar = "bg-orange-500"; 
    warnaSaran = "orange";
    index = 2;
    saranList = [
      "Segera kurangi makanan berlemak jenuh dan trans fat",
      "Tingkatkan aktivitas fisik menjadi 45-60 menit per hari",
      "Konsumsi makanan penurun kolesterol: oat, barley, kacang almond, kedelai",
      "Batasi konsumsi telur (maksimal 2-3 per minggu)",
      "Hindari jeroan, daging berlemak, dan seafood tinggi kolesterol",
      "Konsumsi minuman penurun kolesterol seperti teh hijau",
      "Kelola stres dengan meditasi, yoga, atau teknik relaksasi",
      "Konsultasi dokter untuk evaluasi lebih lanjut dan kemungkinan obat"
  ];
  } else {
    kategori = "Sangat Tinggi";
    warnaKotak = "bg-red-50 border-red-400 text-red-700";
    warnaBar = "bg-red-600"; 
    warnaSaran = "red";
    index = 3;
    saranList = [
      "Segera konsultasi dengan dokter spesialis jantung untuk penanganan medis",
      "Hentikan konsumsi makanan berlemak tinggi dan makanan cepat saji",
      "Ikuti program diet ketat dengan bantuan ahli gizi",
      "Lakukan olahraga intensitas sedang 60 menit per hari dengan pengawasan",
      "Pantau kolesterol secara rutin setiap 1-3 bulan",
      "Konsumsi obat penurun kolesterol sesuai resep dokter",
      "Kelola faktor risiko lain: tekanan darah, diabetes, merokok",
      "Perbaiki pola tidur (7-8 jam per hari) dan kelola stres"
    ];
  }

  hasil.className = `mt-6 p-5 rounded-xl border-2 text-center ${warnaKotak} transition-all duration-300 animate-fadeIn`;
  nilai.textContent = `${ldl} mg/dL`;
  status.textContent = kategori;
  
  // Tambahkan icon sesuai kategori
  let iconClass = '';
  if (index === 1) iconClass = 'fa-check-circle text-green-500';
  else if (index === 2) iconClass = 'fa-exclamation-triangle text-yellow-500';
  else if (index === 3) iconClass = 'fa-exclamation-circle text-red-500';
  else iconClass = 'fa-info-circle text-blue-500';
  
  const iconHtml = `<div class="flex items-center justify-center mb-3"><i class="fas ${iconClass} text-3xl"></i></div>`;
  const currentHTML = hasil.innerHTML;
  const nilaiStart = currentHTML.indexOf('<p class="text-4xl');
  const saranContainer = currentHTML.indexOf('<div class="text-sm mt-4');
  hasil.innerHTML = iconHtml + currentHTML.substring(nilaiStart, saranContainer) + currentHTML.substring(saranContainer);
  
  tampilkanSaran("kolesterolSaran", saranList, warnaSaran || "green");
  warnaiBar("barKolesterol", index, warnaBar);
}



/* ============================================================
   ===================  GULA DARAH  ============================
   ============================================================ */

function hitungGula() {
  const gula = parseFloat(document.getElementById("gula").value);
  const waktu = document.getElementById("waktuGula").value;
  const nilai = document.getElementById("gulaNilai");
  const status = document.getElementById("gulaStatus");
  const hasil = document.getElementById("hasilGula");

  if (isNaN(gula)) {
    nilai.textContent = "–";
    status.textContent = "Data tidak valid";
    tampilkanSaran("gulaSaran", ["Masukkan kadar gula darah yang benar."], "red");
    hasil.className = "mt-6 p-5 rounded-xl border-2 text-center bg-red-50 border-red-300 text-red-700 transition-all duration-300";
    hasil.innerHTML = '<div class="flex items-center justify-center mb-3"><i class="fas fa-exclamation-circle text-red-500 text-3xl"></i></div><p class="text-4xl font-extrabold mb-2" id="gulaNilai">–</p><p class="font-bold text-lg mb-4" id="gulaStatus">Data tidak valid</p><div class="w-full h-4 rounded-full mt-4 flex overflow-hidden bg-gray-200 shadow-inner" id="barGula"><div class="w-1/4 h-4 bg-gray-300 transition-all duration-500"></div><div class="w-1/4 h-4 bg-gray-300 transition-all duration-500"></div><div class="w-1/4 h-4 bg-gray-300 transition-all duration-500"></div><div class="w-1/4 h-4 bg-gray-300 transition-all duration-500"></div></div><div class="flex justify-between text-xs text-gray-500 mt-2 mb-4"><span class="font-medium">Rendah</span><span class="font-medium">Normal</span><span class="font-medium">Pra-Diabetes</span><span class="font-medium">Tinggi</span></div><div class="text-sm mt-4 text-left bg-white rounded-lg p-4 border border-gray-200" id="gulaSaran"></div>';
    warnaiBar("barGula", -1);
    return;
  }

  let kategori, warnaKotak, warnaBar, warnaSaran, index, saranList;

  if (waktu === "puasa") {
    if (gula < 70) {
      kategori = "Rendah (Hipoglikemia)";
      warnaKotak = "bg-blue-50 border-blue-400 text-blue-700";
      warnaBar = "bg-blue-500"; 
      warnaSaran = "blue";
      index = 0;
      saranList = [
        "Segera konsumsi makanan manis: jus buah, madu, atau permen",
        "Jika gejala berat, konsumsi 15-20g glukosa atau tablet glukosa",
        "Periksa kembali gula darah setelah 15 menit untuk memastikan kembali normal",
        "Makan makanan lengkap setelah gula darah stabil",
        "Jaga pola makan teratur (3x makan utama + 2x snack)",
        "Konsultasi dokter jika sering mengalami hipoglikemia",
        "Monitor gejala: pusing, keringat dingin, gemetar, jantung berdebar",
        "Bawa selalu camilan manis untuk keadaan darurat"
      ];
    }
    else if (gula <= 99) {
      kategori = "Normal";
      warnaKotak = "bg-green-50 border-green-400 text-green-700";
      warnaBar = "bg-green-500"; 
      warnaSaran = "green";
      index = 1;
      saranList = [
        "Pertahankan pola makan sehat dengan karbohidrat kompleks",
        "Konsumsi makanan dengan indeks glikemik rendah: oatmeal, beras merah, ubi",
        "Olahraga rutin minimal 150 menit per minggu",
        "Kurangi konsumsi gula tambahan dan makanan manis",
        "Tingkatkan konsumsi serat dari sayuran dan buah-buahan",
        "Jaga berat badan ideal dan kontrol porsi makan",
        "Hindari minuman manis dan soda",
        "Periksa gula darah rutin setiap 6 bulan"
      ];
    }
    else if (gula <= 125) {
      kategori = "Pra-Diabetes (Puasa)";
      warnaKotak = "bg-yellow-50 border-yellow-400 text-yellow-700";
      warnaBar = "bg-yellow-500"; 
      warnaSaran = "yellow";
      index = 2;
      saranList = [
        "Segera ubah pola makan: kurangi karbohidrat sederhana dan gula",
        "Tingkatkan konsumsi serat: sayuran hijau, kacang-kacangan, biji-bijian",
        "Lakukan olahraga 30-45 menit per hari (jalan cepat, renang, atau bersepeda)",
        "Kurangi berat badan 5-10% jika kelebihan berat badan",
        "Hindari makanan olahan dan minuman manis sepenuhnya",
        "Pilih sumber karbohidrat kompleks: quinoa, oatmeal, beras merah",
        "Konsultasi dengan ahli gizi untuk rencana makan yang tepat",
        "Monitor gula darah secara rutin dan cek ke dokter setiap 3 bulan"
      ];
    }
    else {
      kategori = "Diabetes";
      warnaKotak = "bg-red-50 border-red-400 text-red-700";
      warnaBar = "bg-red-500"; index = 3;
      saranList = [
        "Segera konsultasi dengan dokter spesialis endokrinologi",
        "Ikuti program manajemen diabetes dengan ketat",
        "Konsumsi obat sesuai resep dokter secara teratur",
        "Diet ketat: kurangi karbohidrat, hindari gula sepenuhnya",
        "Olahraga teratur dengan monitoring gula darah",
        "Monitor gula darah 2-4x sehari dan catat hasilnya",
        "Konsultasi ahli gizi untuk meal planning yang tepat",
        "Edukasi diri tentang diabetes dan komplikasinya"
      ];
    }
  }

  else {
    if (gula < 140) {
      kategori = "Normal";
      warnaKotak = "bg-green-50 border-green-400 text-green-700";
      warnaBar = "bg-green-500"; 
      warnaSaran = "green";
      index = 1;
      saranList = [
        "Pertahankan pola makan sehat dengan porsi seimbang",
        "Tetap aktif bergerak: jalan kaki, naik tangga, atau aktivitas ringan",
        "Batasi konsumsi gula dan makanan manis",
        "Pilih makanan dengan indeks glikemik rendah",
        "Konsumsi protein dan serat bersama karbohidrat untuk memperlambat penyerapan",
        "Hindari makan berlebihan dalam sekali waktu",
        "Jaga berat badan ideal dan pola tidur yang cukup",
        "Lakukan pemeriksaan rutin untuk memantau kesehatan"
      ];
    }
    else if (gula <= 199) {
      kategori = "Pra-Diabetes";
      warnaKotak = "bg-yellow-50 border-yellow-400 text-yellow-700";
      warnaBar = "bg-yellow-500"; 
      warnaSaran = "yellow";
      index = 2;
      saranList = [
        "Kurangi makanan manis dan karbohidrat olahan secara drastis",
        "Tingkatkan konsumsi serat: sayuran hijau, buah-buahan rendah gula, kacang-kacangan",
        "Rutin berolahraga 30-45 menit per hari untuk meningkatkan sensitivitas insulin",
        "Kurangi porsi makan dan makan dalam porsi kecil tapi sering",
        "Hindari minuman manis, ganti dengan air putih atau teh tanpa gula",
        "Pilih karbohidrat kompleks: beras merah, quinoa, ubi jalar",
        "Konsultasi dengan ahli gizi untuk diet yang tepat",
        "Monitor gula darah dan kunjungi dokter untuk evaluasi lanjutan"
      ];
    }
    else {
      kategori = "Diabetes";
      warnaKotak = "bg-red-50 border-red-400 text-red-700";
      warnaBar = "bg-red-500"; index = 3;
      saranList = [
        "Segera konsultasi dokter untuk penanganan medis yang tepat",
        "Kurangi makanan tinggi gula dan karbohidrat sederhana sepenuhnya",
        "Ikuti diet diabetes dengan ketat sesuai rekomendasi ahli gizi",
        "Lakukan olahraga teratur dengan pengawasan (30-60 menit per hari)",
        "Konsumsi obat atau insulin sesuai resep dokter",
        "Monitor gula darah secara teratur dan catat semua hasil",
        "Konsultasi rutin dengan tim medis untuk manajemen diabetes",
        "Edukasi diri tentang diabetes, komplikasi, dan cara mencegahnya"
      ];
    }
  }

  hasil.className = `mt-6 p-5 rounded-xl border-2 text-center ${warnaKotak} transition-all duration-300 animate-fadeIn`;
  nilai.textContent = `${gula} mg/dL`;
  status.textContent = kategori;
  
  // Tambahkan icon sesuai kategori
  let iconClass = '';
  if (index === 0) iconClass = 'fa-exclamation-triangle text-blue-500';
  else if (index === 1) iconClass = 'fa-check-circle text-green-500';
  else if (index === 2) iconClass = 'fa-exclamation-triangle text-yellow-500';
  else iconClass = 'fa-times-circle text-red-500';
  
  const iconHtml = `<div class="flex items-center justify-center mb-3"><i class="fas ${iconClass} text-3xl"></i></div>`;
  const currentHTML = hasil.innerHTML;
  const nilaiStart = currentHTML.indexOf('<p class="text-4xl');
  const saranContainer = currentHTML.indexOf('<div class="text-sm mt-4');
  hasil.innerHTML = iconHtml + currentHTML.substring(nilaiStart, saranContainer) + currentHTML.substring(saranContainer);
  
  tampilkanSaran("gulaSaran", saranList, warnaSaran || "green");
  warnaiBar("barGula", index, warnaBar);
}



/* ============================================================
   ========================= BMI ==============================
   ============================================================ */

function hitungBMI() {
  const berat = parseFloat(document.getElementById("berat").value);
  const tinggi = parseFloat(document.getElementById("tinggi").value) / 100;
  const nilai = document.getElementById("bmiNilai");
  const status = document.getElementById("bmiStatus");
  const hasil = document.getElementById("hasilBMI");

  if (isNaN(berat) || isNaN(tinggi) || tinggi === 0) {
    nilai.textContent = "–";
    status.textContent = "Data tidak valid";
    tampilkanSaran("bmiSaran", ["Masukkan berat dan tinggi yang benar."], "red");
    hasil.className = "mt-6 p-5 rounded-xl border-2 text-center bg-red-50 border-red-300 text-red-700 transition-all duration-300";
    hasil.innerHTML = '<div class="flex items-center justify-center mb-3"><i class="fas fa-exclamation-circle text-red-500 text-3xl"></i></div><p class="text-4xl font-extrabold mb-2" id="bmiNilai">–</p><p class="font-bold text-lg mb-4" id="bmiStatus">Data tidak valid</p><div class="w-full h-4 rounded-full mt-4 flex overflow-hidden bg-gray-200 shadow-inner" id="barBMI"><div class="w-1/4 h-4 bg-gray-300 transition-all duration-500"></div><div class="w-1/4 h-4 bg-gray-300 transition-all duration-500"></div><div class="w-1/4 h-4 bg-gray-300 transition-all duration-500"></div><div class="w-1/4 h-4 bg-gray-300 transition-all duration-500"></div></div><div class="flex justify-between text-xs text-gray-500 mt-2 mb-4"><span class="font-medium">&lt;18.5</span><span class="font-medium">18.5-25</span><span class="font-medium">25-30</span><span class="font-medium">&gt;30</span></div><div class="text-sm mt-4 text-left bg-white rounded-lg p-4 border border-gray-200" id="bmiSaran"></div>';
    warnaiBar("barBMI", -1);
    return;
  }

  const bmi = berat / (tinggi * tinggi);
  let kategori, warnaKotak, warnaBar, warnaSaran, index, saranList;

  if (bmi < 18.5) {
    kategori = "Kurus (Underweight)";
    warnaKotak = "bg-blue-50 border-blue-400 text-blue-700";
    warnaBar = "bg-blue-500"; 
    warnaSaran = "blue";
    index = 0;
    saranList = [
      "Tambah asupan kalori harian secara bertahap (300-500 kalori per hari)",
      "Konsumsi makanan padat nutrisi dan berprotein tinggi: daging, ikan, telur, kacang-kacangan",
      "Tingkatkan frekuensi makan menjadi 5-6 kali sehari dengan porsi kecil",
      "Lakukan latihan beban 2-3x per minggu untuk membangun massa otot",
      "Konsumsi smoothie tinggi kalori dengan protein powder",
      "Konsultasi dokter atau ahli gizi untuk evaluasi penyebab kurus",
      "Cek kesehatan tiroid dan metabolisme jika penurunan berat badan tidak wajar",
      "Tidur cukup 7-9 jam per hari untuk pemulihan dan pertumbuhan otot"
    ];
  }
  else if (bmi < 25) {
    kategori = "Normal (Ideal)";
    warnaKotak = "bg-green-50 border-green-400 text-green-700";
    warnaBar = "bg-green-500"; 
    warnaSaran = "green";
    index = 1;
    saranList = [
      "Pertahankan pola makan seimbang dengan porsi yang tepat",
      "Aktif bergerak minimal 30 menit per hari atau 150 menit per minggu",
      "Konsumsi berbagai macam makanan: sayuran, buah, protein, karbohidrat kompleks",
      "Hindari makanan tinggi gula dan lemak jenuh",
      "Tingkatkan konsumsi serat dari sayuran dan biji-bijian",
      "Kontrol porsi makan dan hindari makan berlebihan",
      "Lakukan olahraga kombinasi: kardio dan strength training",
      "Monitor berat badan secara rutin untuk menjaga BMI ideal"
    ];
  }
  else if (bmi < 30) {
    kategori = "Berlebih (Overweight)";
    warnaKotak = "bg-orange-50 border-orange-400 text-orange-700";
    warnaBar = "bg-orange-500"; 
    warnaSaran = "orange";
    index = 2;
    saranList = [
      "Kurangi asupan kalori harian 500-750 kalori untuk penurunan berat badan sehat",
      "Kurangi makanan manis, berminyak, dan makanan olahan",
      "Tingkatkan aktivitas fisik menjadi 45-60 menit per hari",
      "Perbanyak minum air putih minimal 2 liter per hari",
      "Konsumsi makanan tinggi serat untuk rasa kenyang lebih lama",
      "Kontrol porsi makan dengan metode piring sehat",
      "Lakukan olahraga kombinasi: kardio 4-5x per minggu dan strength training 2-3x per minggu",
      "Konsultasi dengan ahli gizi untuk program diet yang tepat dan berkelanjutan"
    ];
  }
  else {
    kategori = "Obesitas";
    warnaKotak = "bg-red-50 border-red-400 text-red-700";
    warnaBar = "bg-red-500"; 
    warnaSaran = "red";
    index = 3;
    saranList = [
      "Segera konsultasi dengan dokter atau ahli gizi untuk program penurunan berat badan",
      "Kurangi porsi makan secara bertahap dan ikuti rencana diet yang terstruktur",
      "Olahraga teratur 4–5x seminggu dengan durasi 45-60 menit (mulai perlahan dan tingkatkan intensitas)",
      "Hindari makanan cepat saji, minuman manis, dan makanan tinggi kalori",
      "Tingkatkan konsumsi sayuran hijau dan protein tanpa lemak",
      "Atur pola makan: makan teratur, jangan skip makan, hindari makan malam terlalu larut",
      "Dapatkan dukungan dari keluarga atau bergabung dengan program penurunan berat badan",
      "Pantau kemajuan dan konsultasi rutin untuk evaluasi program dan penyesuaian"
    ];
  }

  hasil.className = `mt-6 p-5 rounded-xl border-2 text-center ${warnaKotak} transition-all duration-300 animate-fadeIn`;
  nilai.textContent = bmi.toFixed(1);
  status.textContent = kategori;
  
  // Tambahkan icon sesuai kategori
  let iconClass = '';
  if (index === 0) iconClass = 'fa-arrow-down text-blue-500';
  else if (index === 1) iconClass = 'fa-check-circle text-green-500';
  else if (index === 2) iconClass = 'fa-exclamation-triangle text-orange-500';
  else iconClass = 'fa-exclamation-circle text-red-500';
  
  const iconHtml = `<div class="flex items-center justify-center mb-3"><i class="fas ${iconClass} text-3xl"></i></div>`;
  const currentHTML = hasil.innerHTML;
  const nilaiStart = currentHTML.indexOf('<p class="text-4xl');
  const saranContainer = currentHTML.indexOf('<div class="text-sm mt-4');
  hasil.innerHTML = iconHtml + currentHTML.substring(nilaiStart, saranContainer) + currentHTML.substring(saranContainer);
  
  tampilkanSaran("bmiSaran", saranList, warnaSaran || "purple");
  warnaiBar("barBMI", index, warnaBar);
}

// === Fungsi Reset Kalkulator ===

function resetKolesterol() {
  document.getElementById("ldl").value = "";
  document.getElementById("hdl").value = "";
  document.getElementById("kolesterolNilai").textContent = "–";
  document.getElementById("kolesterolStatus").textContent = "Belum ada hasil";
  document.getElementById("hasilKolesterol").className = "mt-6 p-5 rounded-xl border-2 text-center bg-gray-50 text-gray-600 transition-all duration-300";
  document.getElementById("hasilKolesterol").innerHTML = `
    <div class="flex items-center justify-center mb-3">
      <i class="fas fa-chart-line text-3xl text-gray-400"></i>
    </div>
    <p class="text-4xl font-extrabold mb-2" id="kolesterolNilai">–</p>
    <p class="font-bold text-lg mb-4" id="kolesterolStatus">Belum ada hasil</p>
    <div class="w-full h-4 rounded-full mt-4 flex overflow-hidden bg-gray-200 shadow-inner" id="barKolesterol">
      <div class="w-1/4 h-4 bg-gray-300 transition-all duration-500"></div>
      <div class="w-1/4 h-4 bg-gray-300 transition-all duration-500"></div>
      <div class="w-1/4 h-4 bg-gray-300 transition-all duration-500"></div>
      <div class="w-1/4 h-4 bg-gray-300 transition-all duration-500"></div>
    </div>
    <div class="flex justify-between text-xs text-gray-500 mt-2 mb-4">
      <span class="font-medium">&lt;100</span><span class="font-medium">100-159</span><span class="font-medium">160-189</span><span class="font-medium">&gt;190</span>
    </div>
    <div class="text-sm mt-4 text-left bg-white rounded-lg p-4 border border-gray-200" id="kolesterolSaran">
      <p class="text-center text-gray-500 italic">Isi data dan klik "Hitung Kolesterol".</p>
    </div>
  `;
}

function resetGula() {
  document.getElementById("gula").value = "";
  document.getElementById("waktuGula").value = "puasa";
  document.getElementById("gulaNilai").textContent = "–";
  document.getElementById("gulaStatus").textContent = "Belum ada hasil";
  document.getElementById("hasilGula").className = "mt-6 p-5 rounded-xl border-2 text-center bg-gray-50 text-gray-600 transition-all duration-300";
  document.getElementById("hasilGula").innerHTML = `
    <div class="flex items-center justify-center mb-3">
      <i class="fas fa-chart-line text-3xl text-gray-400"></i>
    </div>
    <p class="text-4xl font-extrabold mb-2" id="gulaNilai">–</p>
    <p class="font-bold text-lg mb-4" id="gulaStatus">Belum ada hasil</p>
    <div class="w-full h-4 rounded-full mt-4 flex overflow-hidden bg-gray-200 shadow-inner" id="barGula">
      <div class="w-1/4 h-4 bg-gray-300 transition-all duration-500"></div>
      <div class="w-1/4 h-4 bg-gray-300 transition-all duration-500"></div>
      <div class="w-1/4 h-4 bg-gray-300 transition-all duration-500"></div>
      <div class="w-1/4 h-4 bg-gray-300 transition-all duration-500"></div>
    </div>
    <div class="flex justify-between text-xs text-gray-500 mt-2 mb-4">
      <span class="font-medium">Normal</span><span class="font-medium">Pra-Diabetes</span><span class="font-medium">Diabetes</span><span class="font-medium">Tinggi</span>
    </div>
    <div class="text-sm mt-4 text-left bg-white rounded-lg p-4 border border-gray-200" id="gulaSaran">
      <p class="text-center text-gray-500 italic">Isi data dan klik "Hitung Gula Darah".</p>
    </div>
  `;
}

function resetBMI() {
  document.getElementById("berat").value = "";
  document.getElementById("tinggi").value = "";
  document.getElementById("bmiNilai").textContent = "–";
  document.getElementById("bmiStatus").textContent = "Belum ada hasil";
  document.getElementById("hasilBMI").className = "mt-6 p-5 rounded-xl border-2 text-center bg-gray-50 text-gray-600 transition-all duration-300";
  document.getElementById("hasilBMI").innerHTML = `
    <div class="flex items-center justify-center mb-3">
      <i class="fas fa-chart-line text-3xl text-gray-400"></i>
    </div>
    <p class="text-4xl font-extrabold mb-2" id="bmiNilai">–</p>
    <p class="font-bold text-lg mb-4" id="bmiStatus">Belum ada hasil</p>
    <div class="w-full h-4 rounded-full mt-4 flex overflow-hidden bg-gray-200 shadow-inner" id="barBMI">
      <div class="w-1/4 h-4 bg-gray-300 transition-all duration-500"></div>
      <div class="w-1/4 h-4 bg-gray-300 transition-all duration-500"></div>
      <div class="w-1/4 h-4 bg-gray-300 transition-all duration-500"></div>
      <div class="w-1/4 h-4 bg-gray-300 transition-all duration-500"></div>
    </div>
    <div class="flex justify-between text-xs text-gray-500 mt-2 mb-4">
      <span class="font-medium">&lt;18.5</span><span class="font-medium">18.5-25</span><span class="font-medium">25-30</span><span class="font-medium">&gt;30</span>
    </div>
    <div class="text-sm mt-4 text-left bg-white rounded-lg p-4 border border-gray-200" id="bmiSaran">
      <p class="text-center text-gray-500 italic">Isi data dan klik "Hitung BMI".</p>
    </div>
  `;
}

</script>

<section id="mitra" class="container mx-auto px-6 py-16">
    <div class="text-center mb-12">
        <p class="text-sm font-semibold tracking-[0.4em] text-green-500 mb-3">Klinik Mitra</p>
        <h1 class="font-bold text-3xl md:text-4xl text-gray-900 mb-4">
            Klinik Mitra Pilihan
        </h1>
        <p class="text-lg text-gray-600">
            {{ $featuredClinics->isEmpty() ? 'Klinik mitra akan segera hadir.' : 'Pilihan klinik terpercaya dari pengelola kesehatan Olga Sehat.' }}
        </p>
    </div>

    <div class="w-24 h-1 bg-gradient-to-r from-blue-500 to-green-500 mx-auto mb-12 rounded-full"></div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 max-w-6xl mx-auto">
        @forelse($featuredClinics as $clinic)
            @php
                $primaryService = $clinic->services->first();
                $clinicImage = $clinic->logo
                    ? asset('fotoklinik/' . $clinic->logo)
                    : asset('assets/klnk.png');
                $priceLabel = $primaryService
                    ? ($primaryService->tipe_harga === 'gratis'
                        ? 'Gratis'
                        : 'Rp ' . number_format($primaryService->harga, 0, ',', '.'))
                    : 'Hubungi Klinik';
            @endphp
            <div class="group">
                @if($primaryService)
                    <a href="{{ route('frontend.service.detail', $primaryService->id) }}" class="block">
                        <article class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 cursor-pointer">
                            <div class="relative">
                                <img
                                    src="{{ $clinicImage }}"
                                    alt="{{ $clinic->nama }}"
                                    class="w-full h-48 object-cover"
                                />
                            </div>

                            <div class="p-5 space-y-3">
                                <div>
                                    <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide mb-1">{{ ucfirst($clinic->tipe) }}</p>
                                    <h3 class="font-bold text-lg text-gray-900 leading-tight line-clamp-2 min-h-[48px]">
                                        {{ $clinic->nama }}
                                    </h3>
                                </div>
                                <p class="text-sm text-gray-600 flex items-center">
                                    <i class="fas fa-map-marker-alt text-blue-500 text-xs mr-2"></i>
                                    {{ $clinic->kota ?? 'Lokasi belum diisi' }}
                                </p>
                                <div class="flex items-center justify-between pt-3 border-t border-gray-100 text-xs sm:text-sm">
                                    <span class="text-green-600 font-semibold flex items-center">
                                        <i class="fas fa-money-bill-wave mr-2 text-xs"></i>
                                        {{ $priceLabel }}
                                    </span>
                                    <span class="text-blue-600 font-semibold flex items-center whitespace-nowrap">
                                        Lihat Detail
                                        <i class="fas fa-chevron-right ml-1 text-xs"></i>
                                    </span>
                                </div>
                            </div>
                        </article>
                    </a>
                @else
                    <article class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <div class="relative">
                            <img
                                src="{{ $clinicImage }}"
                                alt="{{ $clinic->nama }}"
                                class="w-full h-48 object-cover"
                            />
                        </div>

                        <div class="p-5 space-y-3">
                            <div>
                                <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide mb-1">{{ ucfirst($clinic->tipe) }}</p>
                                <h3 class="font-bold text-lg text-gray-900 leading-tight line-clamp-2 min-h-[48px]">
                                    {{ $clinic->nama }}
                                </h3>
                            </div>
                            <p class="text-sm text-gray-600 flex items-center">
                                <i class="fas fa-map-marker-alt text-blue-500 text-xs mr-2"></i>
                                {{ $clinic->kota ?? 'Lokasi belum diisi' }}
                            </p>
                            <div class="flex items-center justify-between pt-3 border-t border-gray-100 text-xs sm:text-sm">
                                <span class="text-green-600 font-semibold flex items-center">
                                    <i class="fas fa-money-bill-wave mr-2 text-xs"></i>
                                    {{ $priceLabel }}
                                </span>
                                <span class="text-gray-400 flex items-center whitespace-nowrap">
                                    Jadwal belum ada
                                </span>
                            </div>
                        </div>
                    </article>
                @endif
            </div>
        @empty
            <div class="col-span-1 sm:col-span-2 lg:col-span-4 text-center text-gray-500">
                Belum ada klinik yang ditampilkan.
            </div>
        @endforelse
    </div>

    <div class="text-center mt-12">
        <a href="{{ route('frontend.healthy') }}#mitra" class="inline-flex items-center justify-center bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-8 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
            Lihat Semua Klinik Lainnya
            <i class="fas fa-arrow-right ml-2"></i>
        </a>
    </div>
</section>

<section class="max-w-7xl mx-auto px-6 py-16 bg-gray-50 rounded-xl shadow-inner mb-16">
    <div class="text-center mb-10">
        <h2 class="text-3xl font-bold text-gray-900">Mengapa Memilih Layanan Kesehatan di OLGA SEHAT?</h2>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
        
        <div class="p-4">
            <i class="fas fa-user-md text-6xl text-green-600 mb-4"></i>
            <h3 class="font-semibold text-xl mb-2 text-gray-900">Spesialis Olahraga</h3>
            <p class="text-gray-600">Tim medis profesional yang berpengalaman dalam penanganan cedera dan kesehatan atlet.</p>
        </div>
        
        <div class="p-4">
            <i class="fas fa-hospital-alt text-6xl text-green-600 mb-4"></i>
            <h3 class="font-semibold text-xl mb-2 text-gray-900">Mitra Terpercaya</h3>
            <p class="text-gray-600">Bermitra dengan klinik, lab, dan rumah sakit yang teruji kualitas dan akreditasinya.</p>
        </div>
        
        <div class="p-4">
            <i class="fas fa-calendar-check text-6xl text-green-600 mb-4"></i>
            <h3 class="font-semibold text-xl mb-2 text-gray-900">Booking Fleksibel</h3>
            <p class="text-gray-600">Pesan jadwal cek kesehatan atau fisioterapi dengan mudah, kapan saja dan di mana saja.</p>
        </div>
    </div>
</section>

<section class="container mx-auto px-6 pb-24 md:pb-32">
    <nav aria-label="Pagination" class="flex justify-center space-x-2">
        <button aria-label="Previous page" class="w-10 h-10 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-200 flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed" disabled>
            <i class="fas fa-arrow-left"></i>
        </button>
        <button class="w-10 h-10 rounded-lg bg-green-600 text-white font-semibold">1</button>
        <button class="w-10 h-10 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-200 hidden sm:flex items-center justify-center">2</button>
        <button class="w-10 h-10 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-200 hidden sm:flex items-center justify-center">3</button>
        <span class="inline-flex items-center px-2 text-gray-700 select-none">...</span>
        <button class="w-10 h-10 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-200">63</button>
        <button aria-label="Next page" class="w-10 h-10 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-200 flex items-center justify-center">
            <i class="fas fa-arrow-right"></i>
        </button>
    </nav>
</section>

<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-16 mb-16">
    <h2 class="text-center font-bold text-3xl mb-10 text-gray-900">
        Pertanyaan Umum Seputar Layanan Kesehatan
    </h2>
    <div class="space-y-6 max-w-4xl mx-auto">
        
        <details class="border-b border-gray-200 pb-4 group bg-white shadow-sm rounded-lg p-4">
            <summary class="cursor-pointer font-semibold text-lg flex justify-between items-center py-2 group-open:text-green-700">
                Apa perbedaan Layanan Kesehatan di Olga Sehat dengan klinik biasa?
                <i class="fas fa-plus text-gray-700 group-open:text-green-700 group-open:rotate-45 transition-transform"></i>
            </summary>
            <p class="text-base text-gray-600 mt-3 pl-4 border-l-4 border-green-200">
                Layanan di Olga Sehat memiliki fokus kuat pada <span class="font-medium">kedokteran olahraga, kebugaran, dan pemulihan cedera</span>. Kami bermitra dengan spesialis yang memahami kebutuhan unik gaya hidup aktif Anda.
            </p>
        </details>
        
        <details class="border-b border-gray-200 pb-4 group bg-white shadow-sm rounded-lg p-4">
            <summary class="cursor-pointer font-semibold text-lg flex justify-between items-center py-2 group-open:text-green-700">
                Bisakah saya menggunakan asuransi untuk layanan medis di sini?
                <i class="fas fa-plus text-gray-700 group-open:text-green-700 group-open:rotate-45 transition-transform"></i>
            </summary>
            <p class="text-base text-gray-600 mt-3 pl-4 border-l-4 border-green-200">
                Beberapa mitra klinik kami menerima pembayaran asuransi. Detail penerimaan asuransi dan BPJS dapat Anda cek pada halaman detail masing-masing layanan.
            </p>
        </details>
        
        <details class="border-b border-gray-200 pb-4 group bg-white shadow-sm rounded-lg p-4">
            <summary class="cursor-pointer font-semibold text-lg flex justify-between items-center py-2 group-open:text-green-700">
                Bagaimana cara booking sesi Fisioterapi atau Medical Check-Up?
                <i class="fas fa-plus text-gray-700 group-open:text-green-700 group-open:rotate-45 transition-transform"></i>
            </summary>
            <p class="text-base text-gray-600 mt-3 pl-4 border-l-4 border-green-200">
                Gunakan fitur pencarian di atas, pilih layanan dan lokasi yang Anda inginkan, lalu pilih jadwal yang tersedia. Anda akan menerima konfirmasi melalui email atau aplikasi kami.
            </p>
        </details>

        <details class="border-b border-gray-200 pb-4 group bg-white shadow-sm rounded-lg p-4">
            <summary class="cursor-pointer font-semibold text-lg flex justify-between items-center py-2 group-open:text-green-700">
                Apakah ada layanan Home Visit (Panggilan ke Rumah)?
                <i class="fas fa-plus text-gray-700 group-open:text-green-700 group-open:rotate-45 transition-transform"></i>
            </summary>
            <p class="text-base text-gray-600 mt-3 pl-4 border-l-4 border-green-200">
                Ya, beberapa mitra Fisioterapi dan Lab kami menawarkan layanan <span class="font-medium">Home Visit</span>. Anda dapat mencari dan memfilter layanan yang memiliki label "Home Visit" di daftar layanan.
            </p>
        </details>

    </div>
</section>

<section class="container mx-auto px-6 mt-12 mb-16">
    <div class="bg-gray-900 text-white rounded-xl p-8 md:p-12 mx-auto space-y-5 max-w-7xl"> 
        <p class="text-sm font-normal opacity-70">Khusus Klinik, Fisioterapi, & Lab</p>
        <h2 class="text-3xl md:text-4xl font-bold leading-tight">
            Tingkatkan Jangkauan Layanan Kesehatan Anda
        </h2>
        <p class="text-base font-normal max-w-xl leading-relaxed opacity-90">
            Bergabunglah dengan jaringan mitra kami. Kelola jadwal, ketersediaan, dan janji temu pasien secara digital dan efisien di platform Olga Sehat.
        </p>
        <a 
            class="inline-block bg-green-600 hover:bg-green-700 text-white font-semibold text-base px-8 py-3 rounded-lg mt-4 transition duration-300 shadow-lg" 
            href="/daftar-mitra-kesehatan"
        >
            Daftar Mitra Kesehatan Sekarang
        </a>
    </div>
</section>

 @endsection