@extends('user.layout.frontenduser')

@section('content')

<section class="container mx-auto px-4 sm:px-6 lg:px-8 py-10 pt-[120px] max-w-5xl"> 
    <div class="flex items-start justify-between mb-8">
        
        <div class="flex flex-col items-center space-y-2 w-1/3">
            <div class="w-8 h-8 rounded-full bg-blue-700 flex items-center justify-center text-white font-bold text-sm shadow-md">
                <i class="fas fa-check"></i>
            </div>
            <span class="font-bold text-blue-700 text-center text-xs sm:text-sm">Validasi Item</span>
        </div>
        
        <div class="flex flex-col items-center space-y-2 w-1/3">
            <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-bold text-sm">
                2
            </div>
            <span class="font-semibold text-gray-500 text-center text-xs sm:text-sm">Data & Pembayaran</span>
        </div>
        
        <div class="flex flex-col items-center space-y-2 w-1/3 opacity-50">
            <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-bold text-sm">
                3
            </div>
            <span class="font-semibold text-gray-500 text-center text-xs sm:text-sm">Success (Selesai)</span>
        </div>
        
    </div>
    
    <div class="h-1.5 bg-gray-200 rounded-full relative max-w-md mx-auto">
    
    </div>
</section>

<main class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-4xl pb-24">
    <div class="bg-white rounded-xl shadow-2xl p-6 sm:p-10 border border-gray-100">
        <h1 class="text-3xl font-bold mb-2 text-center text-gray-900">Periksa Pemesanan Anda</h1>
        <p class="text-center text-gray-600 mb-8 text-lg">Pastikan detail pemesanan sudah sesuai dan benar sebelum melanjutkan ke pembayaran.</p>

        <div class="bg-blue-50 border border-blue-200 rounded-xl p-5 sm:p-6 flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
            <div class="space-y-1 sm:space-y-0">
                <h2 class="font-bold text-xl text-blue-800">MU Sport Center</h2>
                <p class="text-base text-gray-700">Lapangan Futsal A</p>
                <p class="text-base font-semibold mt-2 text-gray-800">
                    <i class="far fa-calendar-alt mr-1"></i> 03 Sep 2025 &bull; <i class="far fa-clock ml-2 mr-1"></i> 07:00 - 08:00
                </p>
            </div>
            <div class="text-left sm:text-right mt-4 sm:mt-0 w-full sm:w-auto">
                <button class="flex items-center text-sm text-red-600 hover:text-red-700 mt-2 transition">
                    <i class="fas fa-trash-alt mr-1"></i> Hapus
                </button>
            </div>
        </div>
        
        <div class="bg-gray-50 rounded-lg p-5 mb-8 border border-gray-200">
            <div class="flex justify-between items-center py-1">
                <span class="text-gray-700">Total Sesi</span>
                <span class="font-semibold text-gray-800">1 Sesi</span>
            </div>
            <div class="flex justify-between items-center py-1 border-t border-gray-200 mt-2 pt-3">
                <span class="text-xl font-bold text-gray-900">Total Pembayaran</span>
                <span class="text-xl font-bold text-blue-700">Rp100.000</span>
            </div>
        </div>

        <a href="/paymentuser">
            <button class="w-full bg-blue-700 text-white text-lg py-4 rounded-xl font-bold hover:bg-blue-800 transition transform hover:scale-[1.005] shadow-lg">
                <i class="fas fa-money-check-alt mr-2"></i> LANJUT KE PEMBAYARAN
            </button>
        </a>
    </div>
    
</main>

@endsection