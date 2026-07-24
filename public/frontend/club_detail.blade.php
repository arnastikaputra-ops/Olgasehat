@extends('FRONTEND.layout.frontend')

@section('content')

    <main class="container mx-auto px-6 py-10">
    <div class="flex flex-col md:flex-row md:space-x-12">
        
        <div class="md:flex-1">
            
            <img 
                src="assets/venue-image.jpg" 
                alt="Gambar Venue Komunitas" 
                class="w-full h-64 object-cover rounded-xl shadow-lg mb-8" 
            />
            
            <div class="flex items-start space-x-5 mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Open Class Coach Bagus</h1>
                    
                    <div class="flex flex-wrap items-center text-gray-600 text-sm mt-2"> 
                        <div class="flex items-center space-x-1 pr-4 border-r border-gray-300">
                            <i class="fas fa-trophy text-blue-600"></i>
                            <span>Mini Soccer</span>
                        </div>
                        <div class="flex items-center space-x-1 px-4 border-r border-gray-300">
                            <i class="fas fa-city text-blue-600"></i>
                            <span>Kota Denpasar Utara</span>
                        </div>
                        <div class="flex items-center space-x-1 pl-4">
                            <i class="fas fa-shield-alt text-blue-600"></i>
                            <span>Level Putih I</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-8">
                <h2 class="text-xl font-semibold mb-3 border-b pb-1">Deskripsi Komunitas</h2>
                <p class="text-gray-700 leading-relaxed">
                    have fun : varian sparing 1. miring Rp. 150.000 / Rp. 95.000 2. Lapangan Rp. 245.000 3. Lapangan + Air Rp. 300.000
                </p>
            </div>
            
            <div class="mb-12">
                <h2 class="text-xl font-semibold mb-3 border-b pb-1">Lokasi Venue</h2>
                <p class="text-gray-700 leading-relaxed">
                    Jl. Nuansa Indah Selatan Utara I No.1, Pemecutan Kaja, Kec. Denpasar Utara, Kota Denpasar, Bali 80118
                </p>
            </div>
            
        </div>

        <div class="md:w-80 mt-8 md:mt-0">
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 shadow-lg">
                <p class="text-3xl font-extrabold text-blue-800 mb-1">Rp. 245,000</p>
                <p class="text-sm text-gray-600 mb-4">Wajib DP · Rp. 150,000</p>
                
                <button class="w-full bg-red-600 text-white py-3 rounded-xl font-bold text-lg hover:bg-red-700 transition mb-6 shadow-md">
                    JOIN SEKARANG
                </button>
                
                <div class="space-y-4 text-gray-800 text-base">
                    <div class="flex items-center space-x-3">
                        <i class="far fa-calendar-alt text-blue-600 w-5"></i>
                        <span>Thursday, 28 Aug 2025 · 21:00 - 22:00</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-map-marker-alt text-blue-600 w-5"></i>
                        <span>Jl. Nuansa Indah Selatan Utara I No.1, Pemecutan Kaja, Kec. Denpasar Utara, Kota Denpasar, Bali 80118</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="mt-12">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Komunitas Terkait Lainnya</h2>
        </section>
</main>

   @endsection