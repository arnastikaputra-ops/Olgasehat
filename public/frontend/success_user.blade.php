@extends('user.layout.frontenduser')

@section('content')

<section class="container mx-auto px-4 sm:px-6 lg:px-8 py-10 pt-[120px] max-w-5xl"> 
    <div class="flex items-start justify-between mb-8">
        
        <div class="flex flex-col items-center space-y-2 w-1/3">
            <div class="w-8 h-8 rounded-full bg-blue-700 flex items-center justify-center text-white font-semibold text-sm shadow-md">
                <i class="fas fa-check"></i>
            </div>
            <span class="font-semibold text-blue-700 text-center text-xs sm:text-sm">Validasi Item</span>
        </div>
        
        <div class="flex flex-col items-center space-y-2 w-1/3">
            <div class="w-8 h-8 rounded-full bg-blue-700 flex items-center justify-center text-white font-semibold text-sm shadow-md">
                <i class="fas fa-check"></i>
            </div>
            <span class="font-semibold text-blue-700 text-center text-xs sm:text-sm">Data & Pembayaran</span>
        </div>
        
        <div class="flex flex-col items-center space-y-2 w-1/3">
            <div class="w-8 h-8 rounded-full bg-blue-700 border-4 border-blue-300 flex items-center justify-center text-white font-semibold text-sm shadow-xl">
                <i class="fas fa-award"></i>
            </div>
            <span class="font-semibold text-green-700 text-center text-xs sm:text-sm">Success (Selesai)</span>
        </div>
        
    </div>
    
    <div class="h-1.5 bg-gray-200 rounded-full relative max-w-md mx-auto">
        <div class="h-1.5 bg-blue-700 rounded-full absolute top-0 left-0 w-full transition-all duration-700"></div>
    </div>
</section>

<main class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-4xl pb-20">
    <div class="bg-white rounded-xl shadow-2xl p-6 sm:p-10 text-center border-t-4 border-blue-700">
        
        <div class="w-20 h-20 mx-auto mb-6 bg-green-500 rounded-full flex items-center justify-center text-white">
            <i class="fas fa-check-circle text-4xl"></i>
        </div>

        <h1 class="text-3xl font-semibold mb-3 text-gray-900">Pembayaran Berhasil! 🎉</h1>
        <p class="text-lg text-gray-600 mb-8">
            Pemesanan Anda untuk **MU Sport Center - Lapangan Futsal A** telah dikonfirmasi. 
            Detail pemesanan dan kode *booking* telah dikirimkan ke email Anda.
        </p>

        <div class="bg-gray-50 rounded-lg p-5 mb-8 border border-gray-200 inline-block text-left max-w-sm w-full">
            <p class="text-sm font-medium text-gray-500 mb-1">Kode Booking</p>
            <p class="text-2xl font-semibold text-blue-700 mb-4">#OLGSEHAT10624</p>
            
            <div class="space-y-2 text-gray-700">
                <div class="flex justify-between">
                    <span class="font-medium">Total Dibayar</span>
                    <span class="font-semibold text-green-700">Rp 100.000</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-medium">Jadwal</span>
                    <span class="font-semibold">03 Sep 2025 (07:00 - 08:00)</span>
                </div>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-4">
            
            <a href="/download-receipt/OLGSEHAT10624" target="_blank" 
               class="inline-flex items-center justify-center bg-blue-700 text-white py-3 px-6 rounded-xl font-semibold hover:bg-blue-800 transition shadow-lg transform hover:scale-[1.01]">
                <i class="fas fa-download mr-2"></i> Unduh Bukti Pemesanan
            </a>
            
            <a href="/homeuser" 
               class="inline-flex items-center justify-center bg-white text-blue-700 py-3 px-6 rounded-xl font-semibold border border-blue-700 hover:bg-gray-50 transition shadow-md transform hover:scale-[1.01]">
                Kembali ke Beranda
            </a>
        </div>
        
    </div>
</main>

@endsection