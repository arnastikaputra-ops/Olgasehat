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
<div class="bg-gradient-to-br from-white to-blue-50 shadow-xl rounded-2xl p-6 border border-blue-100 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 flex flex-col justify-between">
  <div>
    <div class="flex items-center justify-center mb-6">
      <div class="bg-blue-100 p-4 rounded-full">
        <i class="fas fa-heartbeat text-blue-600 text-2xl"></i>
      </div>
    </div>
    <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Kalkulator Kolesterol</h2>

    <div class="space-y-4 mb-6">
      <div>
        <label class="block mb-2 font-semibold text-gray-700 flex items-center text-sm">
          <i class="fas fa-vial text-blue-500 mr-2"></i> Kadar LDL (mg/dL)
        </label>
        <input type="number" id="ldl" class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 outline-none" placeholder="contoh: 120">
      </div>

      <div>
        <label class="block mb-2 font-semibold text-gray-700 flex items-center text-sm">
          <i class="fas fa-vial text-blue-500 mr-2"></i> Kadar HDL (mg/dL)
        </label>
        <input type="number" id="hdl" class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 outline-none" placeholder="contoh: 45">
      </div>
    </div>

    <div class="flex gap-3 mb-6">
      <button onclick="hitungKolesterol()" class="flex-1 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold py-3 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-[1.02] transition-all duration-200 flex items-center justify-center space-x-2">
        <i class="fas fa-calculator"></i>
        <span>Hitung Kolesterol</span>
      </button>
      <button onclick="resetKolesterol()" class="px-4 bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-[1.02] transition-all duration-200 flex items-center justify-center" title="Reset">
        <i class="fas fa-redo"></i>
      </button>
    </div>
  </div>

  <div id="hasilKolesterol" class="p-5 rounded-xl border-2 text-center bg-gray-50 border-gray-200 text-gray-600 transition-all duration-300">
    <div class="flex items-center justify-center mb-3">
      <i id="kolesterolStatusIcon" class="fas fa-chart-line text-3xl text-gray-400"></i>
    </div>
    <p class="text-2xl font-extrabold mb-1" id="kolesterolNilai">–</p>
    <p class="font-bold text-base mb-4" id="kolesterolStatus">Belum ada hasil</p>

    <div class="w-full h-4 rounded-full mt-4 flex overflow-hidden bg-gray-200 shadow-inner" id="barKolesterol">
      <div class="w-1/4 h-4 bg-gray-300 transition-all duration-500"></div>
      <div class="w-1/4 h-4 bg-gray-300 transition-all duration-500"></div>
      <div class="w-1/4 h-4 bg-gray-300 transition-all duration-500"></div>
      <div class="w-1/4 h-4 bg-gray-300 transition-all duration-500"></div>
    </div>

    <div class="flex justify-between text-xs text-gray-500 mt-2 mb-4">
      <span class="font-medium">&lt;100</span>
      <span class="font-medium">100-129</span>
      <span class="font-medium">130-159</span>
      <span class="font-medium">&ge;160</span>
    </div>

    <div class="text-sm mt-4 text-left bg-white rounded-lg p-4 border border-gray-200" id="kolesterolSaran">
      <p class="text-center text-gray-500 italic">Isi data LDL & HDL lalu klik "Hitung Kolesterol".</p>
    </div>
  </div>
</div>


<!-- ====================================== -->
<!-- === KALKULATOR GULA DARAH ============ -->
<!-- ====================================== -->
<div class="bg-gradient-to-br from-white to-green-50 shadow-xl rounded-2xl p-6 border border-green-100 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 flex flex-col justify-between">
  <div>
    <div class="flex items-center justify-center mb-6">
      <div class="bg-green-100 p-4 rounded-full">
        <i class="fas fa-tint text-green-600 text-2xl"></i>
      </div>
    </div>
    <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Kalkulator Gula Darah</h2>

    <div class="space-y-4 mb-6">
      <div>
        <label class="block mb-2 font-semibold text-gray-700 flex items-center text-sm">
          <i class="fas fa-vial text-green-500 mr-2"></i> Total Gula Darah (mg/dL)
        </label>
        <input type="number" id="gula" class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all duration-200 outline-none" placeholder="contoh: 110">
      </div>

      <div>
        <label class="block mb-2 font-semibold text-gray-700 flex items-center text-sm">
          <i class="fas fa-clock text-green-500 mr-2"></i> Waktu Pengukuran
        </label>
        <select id="waktuGula" class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all duration-200 outline-none">
          <option value="puasa">Puasa (Min. 8 jam)</option>
          <option value="2jam">2 Jam Setelah Makan / Sewaktu</option>
        </select>
      </div>
    </div>

    <div class="flex gap-3 mb-6">
      <button onclick="hitungGula()" class="flex-1 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-bold py-3 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-[1.02] transition-all duration-200 flex items-center justify-center space-x-2">
        <i class="fas fa-calculator"></i>
        <span>Hitung Gula Darah</span>
      </button>
      <button onclick="resetGula()" class="px-4 bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-[1.02] transition-all duration-200 flex items-center justify-center" title="Reset">
        <i class="fas fa-redo"></i>
      </button>
    </div>
  </div>

  <div id="hasilGula" class="p-5 rounded-xl border-2 text-center bg-gray-50 border-gray-200 text-gray-600 transition-all duration-300">
    <div class="flex items-center justify-center mb-3">
      <i id="gulaStatusIcon" class="fas fa-chart-line text-3xl text-gray-400"></i>
    </div>
    <p class="text-3xl font-extrabold mb-1" id="gulaNilai">–</p>
    <p class="font-bold text-base mb-4" id="gulaStatus">Belum ada hasil</p>

    <div class="w-full h-4 rounded-full mt-4 flex overflow-hidden bg-gray-200 shadow-inner" id="barGula">
      <div class="w-1/4 h-4 bg-gray-300 transition-all duration-500"></div>
      <div class="w-1/4 h-4 bg-gray-300 transition-all duration-500"></div>
      <div class="w-1/4 h-4 bg-gray-300 transition-all duration-500"></div>
      <div class="w-1/4 h-4 bg-gray-300 transition-all duration-500"></div>
    </div>

    <div class="flex justify-between text-xs text-gray-500 mt-2 mb-4">
      <span class="font-medium">Rendah</span>
      <span class="font-medium">Normal</span>
      <span class="font-medium">Pra-Diabetes</span>
      <span class="font-medium">Diabetes</span>
    </div>

    <div class="text-sm mt-4 text-left bg-white rounded-lg p-4 border border-gray-200" id="gulaSaran">
      <p class="text-center text-gray-500 italic">Isi data gula darah lalu klik "Hitung Gula Darah".</p>
    </div>
  </div>
</div>


<!-- ====================================== -->
<!-- ========== KALKULATOR BMI ============ -->
<!-- ====================================== -->
<div class="bg-gradient-to-br from-white to-purple-50 shadow-xl rounded-2xl p-6 border border-purple-100 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 flex flex-col justify-between">
  <div>
    <div class="flex items-center justify-center mb-6">
      <div class="bg-purple-100 p-4 rounded-full">
        <i class="fas fa-weight text-purple-600 text-2xl"></i>
      </div>
    </div>
    <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Kalkulator BMI</h2>

    <div class="space-y-4 mb-6">
      <div>
        <label class="block mb-2 font-semibold text-gray-700 flex items-center text-sm">
          <i class="fas fa-weight text-purple-500 mr-2"></i> Berat Badan (kg)
        </label>
        <input type="number" id="berat" class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all duration-200 outline-none" placeholder="contoh: 65">
      </div>

      <div>
        <label class="block mb-2 font-semibold text-gray-700 flex items-center text-sm">
          <i class="fas fa-ruler-vertical text-purple-500 mr-2"></i> Tinggi Badan (cm)
        </label>
        <input type="number" id="tinggi" class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all duration-200 outline-none" placeholder="contoh: 170">
      </div>
    </div>

    <div class="flex gap-3 mb-6">
      <button onclick="hitungBMI()" class="flex-1 bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white font-bold py-3 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-[1.02] transition-all duration-200 flex items-center justify-center space-x-2">
        <i class="fas fa-calculator"></i>
        <span>Hitung BMI</span>
      </button>
      <button onclick="resetBMI()" class="px-4 bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-[1.02] transition-all duration-200 flex items-center justify-center" title="Reset">
        <i class="fas fa-redo"></i>
      </button>
    </div>
  </div>

  <div id="hasilBMI" class="p-5 rounded-xl border-2 text-center bg-gray-50 border-gray-200 text-gray-600 transition-all duration-300">
    <div class="flex items-center justify-center mb-3">
      <i id="bmiStatusIcon" class="fas fa-chart-line text-3xl text-gray-400"></i>
    </div>
    <p class="text-3xl font-extrabold mb-1" id="bmiNilai">–</p>
    <p class="font-bold text-base mb-4" id="bmiStatus">Belum ada hasil</p>

    <div class="w-full h-4 rounded-full mt-4 flex overflow-hidden bg-gray-200 shadow-inner" id="barBMI">
      <div class="w-1/4 h-4 bg-gray-300 transition-all duration-500"></div>
      <div class="w-1/4 h-4 bg-gray-300 transition-all duration-500"></div>
      <div class="w-1/4 h-4 bg-gray-300 transition-all duration-500"></div>
      <div class="w-1/4 h-4 bg-gray-300 transition-all duration-500"></div>
    </div>

    <div class="flex justify-between text-xs text-gray-500 mt-2 mb-4">
      <span class="font-medium">&lt;18.5</span>
      <span class="font-medium">18.5-24.9</span>
      <span class="font-medium">25-29.9</span>
      <span class="font-medium">&ge;30</span>
    </div>

    <div class="text-sm mt-4 text-left bg-white rounded-lg p-4 border border-gray-200" id="bmiSaran">
      <p class="text-center text-gray-500 italic">Isi data berat & tinggi badan lalu klik "Hitung BMI".</p>
    </div>
  </div>
</div>

    </div>
</section>

<!-- ====================================== -->
<!-- ============ SCRIPT ================== -->
<!-- ====================================== -->

<script>
// Helper for styling advice lists safely
function tampilkanSaran(id, list, warna = "blue") {
  const container = document.getElementById(id);
  if (!container) return;

  const styleConfigs = {
    green: { border: 'border-l-4 border-green-500', bg: 'bg-green-50', icon: 'text-green-500' },
    blue: { border: 'border-l-4 border-blue-500', bg: 'bg-blue-50', icon: 'text-blue-500' },
    yellow: { border: 'border-l-4 border-yellow-500', bg: 'bg-yellow-50', icon: 'text-yellow-500' },
    orange: { border: 'border-l-4 border-orange-500', bg: 'bg-orange-50', icon: 'text-orange-500' },
    red: { border: 'border-l-4 border-red-500', bg: 'bg-red-50', icon: 'text-red-500' },
    purple: { border: 'border-l-4 border-purple-500', bg: 'bg-purple-50', icon: 'text-purple-500' },
  };

  const conf = styleConfigs[warna] || styleConfigs['blue'];
  const icons = ['check-circle', 'lightbulb', 'heart', 'dumbbell', 'utensils', 'running', 'user-md', 'shield-alt'];

  let html = "<div class='space-y-2'>";
  list.forEach((itemText, idx) => {
    const icon = icons[idx % icons.length];
    html += `
      <div class='flex items-start space-x-3 p-3 rounded-lg ${conf.bg} ${conf.border} transition-all duration-200'>
        <i class='fas fa-${icon} ${conf.icon} mt-0.5 flex-shrink-0 text-base'></i>
        <span class='text-gray-800 leading-relaxed text-sm font-medium'>${itemText}</span>
      </div>`;
  });
  html += "</div>";
  container.innerHTML = html;
}

// Helper to color indicator bars
function warnaiBar(barId, activeIndex, warnaClass) {
  const barContainer = document.getElementById(barId);
  if (!barContainer) return;
  const segments = barContainer.children;
  for (let i = 0; i < segments.length; i++) {
    if (i === activeIndex) {
      segments[i].className = `w-1/4 h-4 transition-all duration-500 ${warnaClass} shadow-md`;
    } else {
      segments[i].className = "w-1/4 h-4 transition-all duration-500 bg-gray-200";
    }
  }
}

// ================= KOLESTEROL =================
function hitungKolesterol() {
  const ldl = parseFloat(document.getElementById("ldl").value);
  const hdl = parseFloat(document.getElementById("hdl").value);
  const nilaiEl = document.getElementById("kolesterolNilai");
  const statusEl = document.getElementById("kolesterolStatus");
  const hasilBox = document.getElementById("hasilKolesterol");
  const iconEl = document.getElementById("kolesterolStatusIcon");

  if (isNaN(ldl) || isNaN(hdl) || ldl <= 0 || hdl <= 0) {
    nilaiEl.textContent = "–";
    statusEl.textContent = "Data tidak valid";
    if (iconEl) iconEl.className = "fas fa-exclamation-circle text-3xl text-red-500";
    hasilBox.className = "p-5 rounded-xl border-2 text-center bg-red-50 border-red-300 text-red-700 transition-all duration-300";
    tampilkanSaran("kolesterolSaran", ["Masukkan nilai LDL dan HDL yang valid (lebih dari 0)."], "red");
    warnaiBar("barKolesterol", -1, "");
    return;
  }

  const rasio = (ldl / hdl).toFixed(1);
  let kategori, warnaBox, warnaBar, warnaSaran, index, iconClass, saranList;

  if (ldl < 100) {
    kategori = "Optimal / Normal";
    warnaBox = "bg-green-50 border-green-400 text-green-800";
    warnaBar = "bg-green-500";
    warnaSaran = "green";
    index = 0;
    iconClass = "fas fa-check-circle text-3xl text-green-500";
    saranList = [
      "Pertahankan pola makan seimbang dan rendah lemak jenuh",
      "Konsumsi makanan kaya serat seperti oatmeal, kacang-kacangan, dan buah",
      "Rutin berolahraga aerobik minimal 150 menit per minggu",
      "Jaga rasio LDL/HDL tetap ideal (< 3.5)",
      "Lakukan pemeriksaan kadar lipid darah berkala secara rutin"
    ];
  } else if (ldl <= 129) {
    kategori = "Mendekati Optimal";
    warnaBox = "bg-blue-50 border-blue-400 text-blue-800";
    warnaBar = "bg-blue-500";
    warnaSaran = "blue";
    index = 1;
    iconClass = "fas fa-info-circle text-3xl text-blue-500";
    saranList = [
      "Batasi konsumsi gorengan, santan, dan minyak kelapa sawit",
      "Tingkatkan asupan lemak tak jenuh dari alpukat, minyak zaitun, dan ikan",
      "Jaga aktivitas fisik tetap konsisten setiap hari",
      "Kontrol berat badan agar tetap dalam batas ideal"
    ];
  } else if (ldl <= 159) {
    kategori = "Batas Tinggi (Perlu Waspada)";
    warnaBox = "bg-yellow-50 border-yellow-400 text-yellow-800";
    warnaBar = "bg-yellow-500";
    warnaSaran = "yellow";
    index = 2;
    iconClass = "fas fa-exclamation-triangle text-3xl text-yellow-500";
    saranList = [
      "Kurangi konsumsi makanan tinggi kolesterol dan produk susu olahan tinggi lemak",
      "Tingkatkan olahraga kardio (jalan cepat, bersepeda, berenang) 30-45 menit per hari",
      "Konsumsi makanan penurun kolesterol seperti tempe, tahu, dan buah kaya pektin",
      "Konsultasikan ke dokter untuk evaluasi gaya hidup"
    ];
  } else {
    kategori = "Tinggi / Sangat Tinggi";
    warnaBox = "bg-red-50 border-red-400 text-red-800";
    warnaBar = "bg-red-500";
    warnaSaran = "red";
    index = 3;
    iconClass = "fas fa-exclamation-circle text-3xl text-red-500";
    saranList = [
      "Segera konsultasi ke dokter spesialis untuk evaluasi lebih lanjut",
      "Hindari makanan berlemak tinggi, cepat saji, dan jeroan sepenuhnya",
      "Jalankan diet rendah kolesterol dengan pengawasan tenaga medis",
      "Lakukan tes kesehatan pembuluh darah dan jantung secara menyeluruh"
    ];
  }

  nilaiEl.textContent = `LDL: ${ldl} mg/dL (Rasio: ${rasio})`;
  statusEl.textContent = kategori;
  if (iconEl) iconEl.className = iconClass;
  hasilBox.className = `p-5 rounded-xl border-2 text-center ${warnaBox} transition-all duration-300`;
  tampilkanSaran("kolesterolSaran", saranList, warnaSaran);
  warnaiBar("barKolesterol", index, warnaBar);
}

function resetKolesterol() {
  document.getElementById("ldl").value = "";
  document.getElementById("hdl").value = "";
  document.getElementById("kolesterolNilai").textContent = "–";
  document.getElementById("kolesterolStatus").textContent = "Belum ada hasil";
  document.getElementById("kolesterolStatusIcon").className = "fas fa-chart-line text-3xl text-gray-400";
  document.getElementById("hasilKolesterol").className = "p-5 rounded-xl border-2 text-center bg-gray-50 border-gray-200 text-gray-600 transition-all duration-300";
  document.getElementById("kolesterolSaran").innerHTML = '<p class="text-center text-gray-500 italic">Isi data LDL & HDL lalu klik "Hitung Kolesterol".</p>';
  warnaiBar("barKolesterol", -1, "");
}

// ================= GULA DARAH =================
function hitungGula() {
  const gula = parseFloat(document.getElementById("gula").value);
  const waktu = document.getElementById("waktuGula").value;
  const nilaiEl = document.getElementById("gulaNilai");
  const statusEl = document.getElementById("gulaStatus");
  const hasilBox = document.getElementById("hasilGula");
  const iconEl = document.getElementById("gulaStatusIcon");

  if (isNaN(gula) || gula <= 0) {
    nilaiEl.textContent = "–";
    statusEl.textContent = "Data tidak valid";
    if (iconEl) iconEl.className = "fas fa-exclamation-circle text-3xl text-red-500";
    hasilBox.className = "p-5 rounded-xl border-2 text-center bg-red-50 border-red-300 text-red-700 transition-all duration-300";
    tampilkanSaran("gulaSaran", ["Masukkan angka kadar gula darah yang valid (lebih dari 0)."], "red");
    warnaiBar("barGula", -1, "");
    return;
  }

  let kategori, warnaBox, warnaBar, warnaSaran, index, iconClass, saranList;

  if (waktu === "puasa") {
    if (gula < 70) {
      kategori = "Rendah (Hipoglikemia)";
      warnaBox = "bg-blue-50 border-blue-400 text-blue-800";
      warnaBar = "bg-blue-500";
      warnaSaran = "blue";
      index = 0;
      iconClass = "fas fa-arrow-down text-3xl text-blue-500";
      saranList = [
        "Segera konsumsi 15-20 gram karbohidrat cepat serap (segelas jus buah / madu)",
        "Istirahat sejenak dan hindari aktivitas fisik berat",
        "Periksa kembali gula darah 15 menit setelah makan/minum manis",
        "Jaga jadwal makan tetap teratur untuk mencegah penurunan gula drastis"
      ];
    } else if (gula <= 99) {
      kategori = "Normal (Puasa)";
      warnaBox = "bg-green-50 border-green-400 text-green-800";
      warnaBar = "bg-green-500";
      warnaSaran = "green";
      index = 1;
      iconClass = "fas fa-check-circle text-3xl text-green-500";
      saranList = [
        "Sangat baik! Pertahankan pola makan seimbang",
        "Pilih karbohidrat kompleks dengan indeks glikemik rendah (beras merah, oat, ubi)",
        "Tetap aktif berolahraga secara teratur",
        "Batasi konsumsi minuman dan makanan manis buatan"
      ];
    } else if (gula <= 125) {
      kategori = "Pra-Diabetes (Puasa)";
      warnaBox = "bg-yellow-50 border-yellow-400 text-yellow-800";
      warnaBar = "bg-yellow-500";
      warnaSaran = "yellow";
      index = 2;
      iconClass = "fas fa-exclamation-triangle text-3xl text-yellow-500";
      saranList = [
        "Perlu waspada! Kurangi makanan/minuman tinggi gula dan karbohidrat sederhana",
        "Tingkatkan konsumsi serat dari sayuran hijau dan buah segar",
        "Lakukan olahraga minimal 30 menit per hari",
        "Turunkan berat badan jika tergolong kelebihan berat badan"
      ];
    } else {
      kategori = "Diabetes (Tinggi)";
      warnaBox = "bg-red-50 border-red-400 text-red-800";
      warnaBar = "bg-red-500";
      warnaSaran = "red";
      index = 3;
      iconClass = "fas fa-exclamation-circle text-3xl text-red-500";
      saranList = [
        "Kadar gula darah di atas batas normal. Segera konsultasikan ke dokter!",
        "Ikuti instruksi diet khusus pra-diabetes / diabetes dari tenaga kesehatan",
        "Pantau gula darah secara berkala dan catat perkembangannya",
        "Hindari gula manis buatan, tepung olahan, dan minuman bersoda"
      ];
    }
  } else {
    // 2 jam setelah makan / sewaktu
    if (gula < 70) {
      kategori = "Rendah (Hipoglikemia)";
      warnaBox = "bg-blue-50 border-blue-400 text-blue-800";
      warnaBar = "bg-blue-500";
      warnaSaran = "blue";
      index = 0;
      iconClass = "fas fa-arrow-down text-3xl text-blue-500";
      saranList = [
        "Konsumsi karbohidrat sederhana untuk meningkatkan gula darah",
        "Konsultasikan ke dokter jika gula darah sering turun setelah makan"
      ];
    } else if (gula <= 139) {
      kategori = "Normal (Setelah Makan)";
      warnaBox = "bg-green-50 border-green-400 text-green-800";
      warnaBar = "bg-green-500";
      warnaSaran = "green";
      index = 1;
      iconClass = "fas fa-check-circle text-3xl text-green-500";
      saranList = [
        "Kadar gula darah dalam batas normal setelah makan!",
        "Pertahankan kebiasaan pola makan sehat dan olahraga rutin"
      ];
    } else if (gula <= 199) {
      kategori = "Pra-Diabetes";
      warnaBox = "bg-yellow-50 border-yellow-400 text-yellow-800";
      warnaBar = "bg-yellow-500";
      warnaSaran = "yellow";
      index = 2;
      iconClass = "fas fa-exclamation-triangle text-3xl text-yellow-500";
      saranList = [
        "Kadar gula setelah makan di atas normal. Kurangi camilan manis dan nasi berlebih",
        "Olahraga teratur untuk meningkatkan sensitivitas insulin tubuh"
      ];
    } else {
      kategori = "Diabetes (Tinggi)";
      warnaBox = "bg-red-50 border-red-400 text-red-800";
      warnaBar = "bg-red-500";
      warnaSaran = "red";
      index = 3;
      iconClass = "fas fa-exclamation-circle text-3xl text-red-500";
      saranList = [
        "Kadar gula tinggi (>= 200 mg/dL). Segera periksakan diri ke dokter atau spesialis penyakit dalam",
        "Ikuti penanganan medis dan pola makan sehat sesuai petunjuk medis"
      ];
    }
  }

  nilaiEl.textContent = `${gula} mg/dL`;
  statusEl.textContent = kategori;
  if (iconEl) iconEl.className = iconClass;
  hasilBox.className = `p-5 rounded-xl border-2 text-center ${warnaBox} transition-all duration-300`;
  tampilkanSaran("gulaSaran", saranList, warnaSaran);
  warnaiBar("barGula", index, warnaBar);
}

function resetGula() {
  document.getElementById("gula").value = "";
  document.getElementById("waktuGula").value = "puasa";
  document.getElementById("gulaNilai").textContent = "–";
  document.getElementById("gulaStatus").textContent = "Belum ada hasil";
  document.getElementById("gulaStatusIcon").className = "fas fa-chart-line text-3xl text-gray-400";
  document.getElementById("hasilGula").className = "p-5 rounded-xl border-2 text-center bg-gray-50 border-gray-200 text-gray-600 transition-all duration-300";
  document.getElementById("gulaSaran").innerHTML = '<p class="text-center text-gray-500 italic">Isi data gula darah lalu klik "Hitung Gula Darah".</p>';
  warnaiBar("barGula", -1, "");
}

// ================= BMI =================
function hitungBMI() {
  const berat = parseFloat(document.getElementById("berat").value);
  const tinggi = parseFloat(document.getElementById("tinggi").value);
  const nilaiEl = document.getElementById("bmiNilai");
  const statusEl = document.getElementById("bmiStatus");
  const hasilBox = document.getElementById("hasilBMI");
  const iconEl = document.getElementById("bmiStatusIcon");

  if (isNaN(berat) || isNaN(tinggi) || berat <= 0 || tinggi <= 0) {
    nilaiEl.textContent = "–";
    statusEl.textContent = "Data tidak valid";
    if (iconEl) iconEl.className = "fas fa-exclamation-circle text-3xl text-red-500";
    hasilBox.className = "p-5 rounded-xl border-2 text-center bg-red-50 border-red-300 text-red-700 transition-all duration-300";
    tampilkanSaran("bmiSaran", ["Masukkan nilai berat dan tinggi badan yang valid (lebih dari 0)."], "red");
    warnaiBar("barBMI", -1, "");
    return;
  }

  const tinggiM = tinggi / 100;
  const bmi = berat / (tinggiM * tinggiM);
  const bmiFixed = bmi.toFixed(1);

  let kategori, warnaBox, warnaBar, warnaSaran, index, iconClass, saranList;

  if (bmi < 18.5) {
    kategori = "Kurus (Underweight)";
    warnaBox = "bg-blue-50 border-blue-400 text-blue-800";
    warnaBar = "bg-blue-500";
    warnaSaran = "blue";
    index = 0;
    iconClass = "fas fa-arrow-down text-3xl text-blue-500";
    saranList = [
      "Tingkatkan asupan kalori seimbang dengan porsi makanan padat nutrisi",
      "Konsumsi makanan kaya protein (daging, telur, ikan, kacang-kacangan)",
      "Lakukan latihan kekuatan/beban untuk meningkatkan massa otot",
      "Makan dengan frekuensi lebih sering (3 porsi utama + 2 porsi camilan sehat)"
    ];
  } else if (bmi <= 24.9) {
    kategori = "Normal (Ideal)";
    warnaBox = "bg-green-50 border-green-400 text-green-800";
    warnaBar = "bg-green-500";
    warnaSaran = "green";
    index = 1;
    iconClass = "fas fa-check-circle text-3xl text-green-500";
    saranList = [
      "Selamat! Berat badan Anda berada dalam kategori ideal",
      "Pertahankan pola makan bergizi seimbang dan hidrasi yang cukup",
      "Lakukan aktivitas fisik/olahraga teratur minimal 150 menit per minggu",
      "Jaga kualitas tidur dan kelola stres dengan baik"
    ];
  } else if (bmi <= 29.9) {
    kategori = "Kelebihan Berat Badan (Overweight)";
    warnaBox = "bg-orange-50 border-orange-400 text-orange-800";
    warnaBar = "bg-orange-500";
    warnaSaran = "orange";
    index = 2;
    iconClass = "fas fa-exclamation-triangle text-3xl text-orange-500";
    saranList = [
      "Kurangi asupan makanan berkalori tinggi, berlemak jenuh, dan manis",
      "Tingkatkan frekuensi dan durasi olahraga kardio (30-45 menit per hari)",
      "Perbanyak konsumsi serat dari sayur dan buah segar",
      "Kendalikan porsi makan sehari-hari"
    ];
  } else {
    kategori = "Obesitas";
    warnaBox = "bg-red-50 border-red-400 text-red-800";
    warnaBar = "bg-red-500";
    warnaSaran = "red";
    index = 3;
    iconClass = "fas fa-exclamation-circle text-3xl text-red-500";
    saranList = [
      "Indeks massa tubuh tergolong tinggi. Disarankan konsultasi dengan ahli gizi/dokter",
      "Jalankan program penurunan berat badan secara bertahap dan teratur",
      "Mulailah dengan olahraga intensitas ringan-sedang secara konsisten",
      "Hindari makanan cepat saji, gorengan, dan minuman manis bersoda"
    ];
  }

  nilaiEl.textContent = `BMI: ${bmiFixed}`;
  statusEl.textContent = kategori;
  if (iconEl) iconEl.className = iconClass;
  hasilBox.className = `p-5 rounded-xl border-2 text-center ${warnaBox} transition-all duration-300`;
  tampilkanSaran("bmiSaran", saranList, warnaSaran);
  warnaiBar("barBMI", index, warnaBar);
}

function resetBMI() {
  document.getElementById("berat").value = "";
  document.getElementById("tinggi").value = "";
  document.getElementById("bmiNilai").textContent = "–";
  document.getElementById("bmiStatus").textContent = "Belum ada hasil";
  document.getElementById("bmiStatusIcon").className = "fas fa-chart-line text-3xl text-gray-400";
  document.getElementById("hasilBMI").className = "p-5 rounded-xl border-2 text-center bg-gray-50 border-gray-200 text-gray-600 transition-all duration-300";
  document.getElementById("bmiSaran").innerHTML = '<p class="text-center text-gray-500 italic">Isi data berat & tinggi badan lalu klik "Hitung BMI".</p>';
  warnaiBar("barBMI", -1, "");
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