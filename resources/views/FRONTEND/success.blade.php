@extends('layouts.app')

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
            <div class="w-8 h-8 rounded-full bg-amber-500 border-4 border-amber-200 flex items-center justify-center text-white font-semibold text-sm shadow-xl">
                <i class="fas fa-clock"></i>
            </div>
            <span class="font-semibold text-amber-700 text-center text-xs sm:text-sm">Terkirim (Menunggu ACC)</span>
        </div>
        
    </div>
    
    <div class="h-1.5 bg-gray-200 rounded-full relative max-w-md mx-auto">
        <div class="h-1.5 bg-blue-700 rounded-full absolute top-0 left-0 w-full transition-all duration-700"></div>
    </div>
</section>

<main class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-4xl pb-20">
    <div class="bg-white rounded-xl shadow-2xl p-6 sm:p-10 text-center border-t-4 border-blue-700">
        
        <div class="w-20 h-20 mx-auto mb-6 bg-amber-500 rounded-full flex items-center justify-center text-white">
            <i class="fas fa-clock text-4xl"></i>
        </div>

        <h1 class="text-3xl font-semibold mb-3 text-gray-900">Pemesanan Berhasil Dikirim! ⏳</h1>
        <p class="text-lg text-gray-600 mb-8" id="successMessage">
            Pemesanan Anda telah berhasil dikirim dan <strong>menunggu verifikasi (ACC)</strong> dari pemilik venue / pengelola. 
            Detail pemesanan dan kode booking telah dicatat pada sistem kami.
        </p>

        <div class="bg-gray-50 rounded-lg p-5 mb-8 border border-gray-200 inline-block text-left max-w-md w-full shadow-sm">
            <div class="flex justify-between items-center border-b pb-3 mb-3">
                <div>
                    <p class="text-xs font-medium text-gray-500">Kode Booking</p>
                    <p class="text-xl font-bold text-blue-700" id="successBookingCode">#OLGSEHAT10624</p>
                </div>
                <span class="bg-amber-100 text-amber-800 text-xs px-3 py-1.5 rounded-full font-bold uppercase tracking-wider">
                    <i class="fas fa-clock mr-1"></i> MENUNGGU ACC
                </span>
            </div>

            <div class="mb-4 bg-white p-3 rounded-lg border border-blue-100 flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 font-medium mb-0.5">Nomor Virtual Account (VA)</p>
                    <p class="text-lg font-bold text-gray-900 tracking-wider font-mono" id="successVA">88008123456789</p>
                </div>
                <div id="successBankLogo" class="ml-2">
                    <span class="text-xs font-bold px-2 py-1 bg-blue-100 text-blue-800 rounded">BCA</span>
                </div>
            </div>
            
            <div class="space-y-3 text-gray-700">
                <div class="flex justify-between border-b pb-2 text-sm">
                    <span class="font-medium">Total Dibayar</span>
                    <span class="font-bold text-green-700 text-base" id="successTotalPaid">Rp 100.000</span>
                </div>
                <div class="space-y-1" id="successItemsContainer">
                    <!-- Dynamic Items -->
                </div>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-4">
            <a href="/riwayatpayment" 
               class="inline-flex items-center justify-center bg-blue-700 text-white py-3 px-6 rounded-xl font-semibold hover:bg-blue-800 transition shadow-lg transform hover:scale-[1.01]">
                <i class="fas fa-history mr-2"></i> Lihat Riwayat Pemesanan
            </a>
            
            <a href="/" 
               class="inline-flex items-center justify-center bg-white text-blue-700 py-3 px-6 rounded-xl font-semibold border border-blue-700 hover:bg-gray-50 transition shadow-md transform hover:scale-[1.01]">
                Kembali ke Beranda
            </a>
        </div>
        
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const data = JSON.parse(sessionStorage.getItem('last_booking_success'));

    if (data) {
        document.getElementById('successBookingCode').textContent = '#' + (data.bookingCode || 'OLGSEHAT');
        
        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(number);
        }

        document.getElementById('successTotalPaid').textContent = formatRupiah(data.totalPrice || 0);

        if (data.virtualAccount) {
            document.getElementById('successVA').textContent = data.virtualAccount;
        } else if (data.booking && data.booking.virtual_account) {
            document.getElementById('successVA').textContent = data.booking.virtual_account;
        }

        if (data.bankCode) {
            const bankLower = data.bankCode.toLowerCase();
            document.getElementById('successBankLogo').innerHTML = `
                <img src="/images/banks/${bankLower}.png" alt="${data.bankCode}" class="h-6 object-contain" onerror="this.src='/images/banks/${bankLower}.svg'; this.onerror=function(){ this.outerHTML='<span class=\\'text-xs font-bold px-2 py-1 bg-blue-100 text-blue-800 rounded\\'>${data.bankCode}</span>'; }" />
            `;
        }
        
        if (data.cart && data.cart.length > 0) {
            const firstItem = data.cart[0];
            document.getElementById('successMessage').innerHTML = `Pemesanan Anda untuk <strong>${firstItem.venue} - ${firstItem.field}</strong> telah berhasil dikirim & <strong>menunggu verifikasi (ACC) Pemilik Venue</strong>. Detail pemesanan dan kode booking telah dicatat pada sistem kami.`;

            const container = document.getElementById('successItemsContainer');
            container.innerHTML = data.cart.map(item => `
                <div class="text-xs bg-white rounded p-2.5 border border-gray-200 mb-1 flex justify-between items-center">
                    <div>
                        <p class="font-bold text-gray-800">${item.field}</p>
                        <p class="text-gray-500">${item.date} • ${item.time}</p>
                    </div>
                    <span class="font-bold text-blue-700">${formatRupiah(item.price)}</span>
                </div>
            `).join('');
        }
    }
});
</script>

@endsection