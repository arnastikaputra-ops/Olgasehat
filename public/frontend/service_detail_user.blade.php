@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<main class="container mx-auto px-4 sm:px-6 pt-24 pb-32 max-w-7xl">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">

        <section class="lg:col-span-8 space-y-8">
            <!-- Gallery/Image Section -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 md:gap-4 overflow-hidden rounded-xl shadow-lg">
                <div class="col-span-1 sm:col-span-2 rounded-tl-xl rounded-bl-xl bg-gradient-to-br from-blue-50 to-indigo-100 h-64 sm:h-96 flex items-center justify-center">
                    <img src="https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                         alt="Klinik Sarana Medika Baru" 
                         class="w-full h-full object-cover">
                </div>
                <div class="hidden sm:grid grid-rows-3 gap-3 md:gap-4 h-96">
                    <div class="bg-gradient-to-br from-green-50 to-emerald-100 rounded-tr-xl flex items-center justify-center">
                        <img src="https://images.unsplash.com/photo-1586773860418-d37222d8fce3?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" 
                             alt="Fasilitas Klinik" 
                             class="w-full h-full object-cover">
                    </div>
                    <div class="bg-gradient-to-br from-purple-50 to-violet-100 flex items-center justify-center">
                        <img src="https://images.unsplash.com/photo-1551076805-e1869033e561?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" 
                             alt="Ruangan Klinik" 
                             class="w-full h-full object-cover">
                    </div>
                    <div class="bg-gradient-to-br from-pink-50 to-rose-100 rounded-br-xl flex items-center justify-center">
                        <img src="https://images.unsplash.com/photo-1532938911079-1b06ac7ceec7?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" 
                             alt="Dokter Klinik" 
                             class="w-full h-full object-cover">
                    </div>
                </div>
            </div>

            <!-- Main Content Card -->
            <div class="bg-white p-6 md:p-8 rounded-xl shadow-xl space-y-6">
                
                <!-- Header Section -->
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold mb-2 text-gray-900">Klinik Sarana Medika Baru</h1>
                    <p class="text-lg text-gray-600 mb-3 flex items-center space-x-2">
                        <i class="fas fa-map-marker-alt text-blue-600"></i>
                        <span>Kabupaten Tangerang, Banten</span>
                    </p>
                    <div class="flex flex-wrap gap-2">
                        <span class="inline-block bg-blue-100 text-blue-700 text-sm font-semibold px-4 py-1.5 rounded-full shadow-sm">
                            <i class="fas fa-hospital mr-2"></i> Klinik Umum
                        </span>
                        <span class="inline-block bg-green-100 text-green-700 text-sm font-semibold px-4 py-1.5 rounded-full shadow-sm">
                            <i class="fas fa-phone mr-2"></i> 08129941262
                        </span>
                    </div>
                </div>

                <hr class="border-gray-200" />

                <!-- Informasi Klinik -->
                <div>
                    <h3 class="font-bold text-xl mb-3 text-gray-800 flex items-center">
                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>Tentang Klinik
                    </h3>
                    <div class="text-gray-700 leading-relaxed">
                        <p class="mb-4">
                            Klinik Sarana Medika Baru merupakan fasilitas kesehatan terpadu yang menyediakan layanan medis komprehensif dengan standar pelayanan terbaik. Klinik ini dilengkapi dengan peralatan medis modern dan didukung oleh tenaga kesehatan profesional yang berpengalaman.
                        </p>
                        <p>
                            Kami berkomitmen untuk memberikan pelayanan kesehatan yang berkualitas dengan pendekatan holistik, mulai dari pencegahan, diagnosis, pengobatan, hingga rehabilitasi untuk seluruh anggota keluarga.
                        </p>
                    </div>
                </div>

                <hr class="border-gray-200" />

                <!-- Layanan Unggulan -->
                <div>
                    <h3 class="font-bold text-xl mb-4 text-gray-800 flex items-center">
                        <i class="fas fa-check-circle text-green-600 mr-2"></i>Layanan Unggulan
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-start space-x-3 p-4 bg-green-50 rounded-lg">
                            <i class="fas fa-stethoscope text-green-600 mt-1"></i>
                            <div>
                                <h4 class="font-semibold text-gray-800 mb-1">Konsultasi Umum</h4>
                                <p class="text-sm text-gray-600">Pelayanan konsultasi dokter umum untuk keluhan kesehatan</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3 p-4 bg-blue-50 rounded-lg">
                            <i class="fas fa-vial text-blue-600 mt-1"></i>
                            <div>
                                <h4 class="font-semibold text-gray-800 mb-1">Laboratorium</h4>
                                <p class="text-sm text-gray-600">Pemeriksaan darah dan urine dengan hasil akurat</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3 p-4 bg-purple-50 rounded-lg">
                            <i class="fas fa-pills text-purple-600 mt-1"></i>
                            <div>
                                <h4 class="font-semibold text-gray-800 mb-1">Apotek</h4>
                                <p class="text-sm text-gray-600">Ketersediaan obat lengkap dengan resep dokter</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3 p-4 bg-pink-50 rounded-lg">
                            <i class="fas fa-ambulance text-pink-600 mt-1"></i>
                            <div>
                                <h4 class="font-semibold text-gray-800 mb-1">IGD 24 Jam</h4>
                                <p class="text-sm text-gray-600">Pelayanan gawat darurat selama 24 jam</p>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="border-gray-200" />

                <!-- Informasi Dokter -->
                <div>
                    <h3 class="font-bold text-xl mb-4 text-gray-800 flex items-center">
                        <i class="fas fa-user-md text-blue-600 mr-2"></i>Dokter yang Praktik
                    </h3>
                    <div class="space-y-4">
                        <!-- Dokter 1 -->
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                            <div class="flex items-center space-x-4">
                                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user-md text-blue-600 text-2xl"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-800">dr. Swisniawati Hidayat, MARS</h4>
                                    <p class="text-sm text-gray-600">Dokter Umum</p>
                                    <p class="text-xs text-gray-500">10 tahun pengalaman</p>
                                </div>
                            </div>
                            <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                                Buat Janji
                            </button>
                        </div>

                        <!-- Dokter 2 -->
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                            <div class="flex items-center space-x-4">
                                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user-md text-green-600 text-2xl"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-800">Kartika Elkim</h4>
                                    <p class="text-sm text-gray-600">Dokter Umum</p>
                                    <p class="text-xs text-gray-500">8 tahun pengalaman</p>
                                </div>
                            </div>
                            <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                                Buat Janji
                            </button>
                        </div>

                        <!-- Dokter 3 -->
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                            <div class="flex items-center space-x-4">
                                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user-md text-purple-600 text-2xl"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-800">Teguh Kesuma Wijaya</h4>
                                    <p class="text-sm text-gray-600">Dokter Umum</p>
                                    <p class="text-xs text-gray-500">12 tahun pengalaman</p>
                                </div>
                            </div>
                            <button class="bg-gray-400 text-white px-4 py-2 rounded-lg cursor-not-allowed">
                                Tidak Tersedia
                            </button>
                        </div>

                        <!-- Dokter 4 -->
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                            <div class="flex items-center space-x-4">
                                <div class="w-16 h-16 bg-pink-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user-md text-pink-600 text-2xl"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-800">Christine</h4>
                                    <p class="text-sm text-gray-600">Dokter Umum</p>
                                    <p class="text-xs text-gray-500">6 tahun pengalaman</p>
                                </div>
                            </div>
                            <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                                Buat Janji
                            </button>
                        </div>
                    </div>
                </div>

                <hr class="border-gray-200" />

                <!-- Jadwal Operasional -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <h3 class="font-bold text-xl mb-3 text-gray-800 flex items-center">
                            <i class="fas fa-calendar-alt text-orange-600 mr-2"></i>Jadwal Operasional
                        </h3>
                        <div class="space-y-3 text-gray-700">
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="font-medium">Senin - Jumat</span>
                                <span class="text-green-600 font-semibold">07:00 - 21:00</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="font-medium">Sabtu</span>
                                <span class="text-green-600 font-semibold">07:00 - 18:00</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="font-medium">Minggu</span>
                                <span class="text-green-600 font-semibold">08:00 - 16:00</span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="font-medium">IGD</span>
                                <span class="text-blue-600 font-semibold">24 Jam</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="font-bold text-xl mb-3 text-gray-800 flex items-center">
                            <i class="fas fa-clipboard-check text-indigo-600 mr-2"></i>Fasilitas
                        </h3>
                        <ul class="space-y-3 text-gray-700">
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-green-600 mr-3 mt-1"></i>
                                <span>Ruang Tunggu Nyaman</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-green-600 mr-3 mt-1"></i>
                                <span>Parkir Luas</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-green-600 mr-3 mt-1"></i>
                                <span>AC di Semua Ruangan</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-green-600 mr-3 mt-1"></i>
                                <span>Toilet Bersih</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-green-600 mr-3 mt-1"></i>
                                <span>Menerima BPJS & Asuransi</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <hr class="border-gray-200" />

                <!-- Lokasi Klinik -->
                <div>
                    <h3 class="font-bold text-xl mb-3 text-gray-800 flex items-center">
                        <i class="fas fa-map-marker-alt text-red-600 mr-2"></i>Lokasi Klinik
                    </h3>
                    <div class="bg-gray-50 p-4 rounded-lg space-y-3">
                        <p class="text-gray-700 font-medium">
                            JL Raya Kutabumi No. 50, RT. 006/RW. 004<br>
                            Kp. Teriti, Karet, Kabupaten Tangerang<br>
                            Banten 15810
                        </p>
                        <div class="bg-white p-4 rounded-lg h-64 flex items-center justify-center border border-gray-200">
                            <div class="text-center text-gray-400">
                                <i class="fas fa-map-marked-alt text-4xl mb-2"></i>
                                <p class="text-sm">Peta Lokasi Klinik</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <a href="#" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium">
                                <i class="fas fa-external-link-alt mr-2"></i>Buka Peta (Google Maps)
                            </a>
                            <span class="text-gray-600 text-sm">
                                <i class="fas fa-phone mr-1"></i>08129941262
                            </span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- FAQ Section -->
            <div class="bg-white p-6 md:p-8 rounded-xl shadow-xl">
                <h3 class="font-bold text-xl mb-4 text-gray-800 flex items-center">
                    <i class="fas fa-question-circle text-purple-600 mr-2"></i>Pertanyaan yang Sering Diajukan
                </h3>
                <div class="space-y-4">
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h4 class="font-semibold text-gray-800 mb-2 flex items-center">
                            <i class="fas fa-question text-blue-600 mr-2"></i>Apakah klinik menerima BPJS?
                        </h4>
                        <p class="text-gray-600 text-sm">Ya, Klinik Sarana Medika Baru menerima pasien BPJS. Pastikan Anda membawa kartu BPJS yang masih aktif dan surat rujukan jika diperlukan.</p>
                    </div>
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h4 class="font-semibold text-gray-800 mb-2 flex items-center">
                            <i class="fas fa-question text-blue-600 mr-2"></i>Berapa biaya konsultasi umum?
                        </h4>
                        <p class="text-gray-600 text-sm">Biaya konsultasi umum mulai dari Rp 50.000. Biaya dapat bervariasi tergantung kompleksitas pemeriksaan dan waktu kunjungan.</p>
                    </div>
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h4 class="font-semibold text-gray-800 mb-2 flex items-center">
                            <i class="fas fa-question text-blue-600 mr-2"></i>Apakah perlu membuat janji terlebih dahulu?
                        </h4>
                        <p class="text-gray-600 text-sm">Tidak wajib, namun disarankan untuk membuat janji terlebih dahulu untuk menghindari antrian panjang, terutama pada jam sibuk.</p>
                    </div>
                </div>
            </div>

        </section>

        <!-- Sidebar -->
        <aside class="lg:col-span-4 space-y-6">
            <!-- Booking Card -->
            <div class="bg-gradient-to-br from-blue-600 to-indigo-700 text-white p-6 rounded-xl shadow-xl sticky top-24">
                <h3 class="text-xl font-bold mb-5">Buat Janji Konsultasi</h3>
                
                <div class="space-y-3 mb-5">
                    <div class="flex items-center justify-between pb-3 border-b border-blue-500/30">
                        <span class="text-blue-100 text-sm">Biaya Konsultasi Umum</span>
                        <span class="text-2xl font-bold">Rp 50.000</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-blue-100">Durasi Rata-rata</span>
                        <span class="font-semibold">15-30 Menit</span>
                    </div>
                </div>

                <div class="space-y-4 pt-2">
                    <div>
                        <label class="block text-sm font-semibold mb-2">Pilih Dokter</label>
                        <select class="w-full px-4 py-2.5 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-white">
                            <option>dr. Swisniawati Hidayat, MARS</option>
                            <option>Kartika Elkim</option>
                            <option>Christine</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-2">Pilih Tanggal</label>
                        <input type="date" class="w-full px-4 py-2.5 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-white" />
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold mb-2">Pilih Waktu</label>
                        <select class="w-full px-4 py-2.5 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-white">
                            <option>08:00 - 08:30</option>
                            <option>09:00 - 09:30</option>
                            <option>10:00 - 10:30</option>
                            <option>14:00 - 14:30</option>
                            <option>15:00 - 15:30</option>
                            <option>16:00 - 16:30</option>
                        </select>
                    </div>

                    <button class="w-full bg-white text-blue-600 font-bold py-3 rounded-lg hover:bg-blue-50 transition duration-200 mt-2 shadow-md">
                        <i class="fas fa-calendar-check mr-2"></i>Buat Janji Sekarang
                    </button>
                </div>

                <div class="mt-4 pt-4 border-t border-blue-500/30">
                    <div class="flex items-center text-blue-100 text-sm">
                        <i class="fas fa-info-circle mr-2"></i>
                        <span>Konfirmasi akan dikirim via WhatsApp</span>
                    </div>
                </div>
            </div>

            <!-- Contact Info Card -->
            <div class="bg-white p-6 rounded-xl shadow-lg">
                <h3 class="font-bold text-lg mb-4 text-gray-800">Kontak Darurat</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-ambulance text-red-600 mr-3"></i>
                            <span class="font-semibold text-gray-800">IGD 24 Jam</span>
                        </div>
                        <span class="text-red-600 font-bold">08129941262</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-phone text-green-600 mr-3"></i>
                            <span class="font-semibold text-gray-800">Informasi</span>
                        </div>
                        <span class="text-green-600 font-bold">08129941262</span>
                    </div>
                </div>
            </div>
        </aside>

    </div>
</main>

@endsection

