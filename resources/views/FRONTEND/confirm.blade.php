@extends('layouts.app')

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

        <!-- Dynamic Container for Cart Items -->
        <div id="cartItemsContainer" class="space-y-4 mb-6">
            <!-- Will be filled by JS -->
        </div>
        
        <div class="bg-gray-50 rounded-lg p-5 mb-8 border border-gray-200">
            <div class="flex justify-between items-center py-1">
                <span class="text-gray-700">Total Sesi</span>
                <span class="font-semibold text-gray-800" id="totalSessions">0 Sesi</span>
            </div>
            <div class="flex justify-between items-center py-1 border-t border-gray-200 mt-2 pt-3">
                <span class="text-xl font-bold text-gray-900">Total Pembayaran</span>
                <span class="text-xl font-bold text-blue-700" id="totalPrice">Rp0</span>
            </div>
        </div>

        <a href="/payment" id="btnContinue" class="block">
            <button class="w-full bg-blue-700 text-white text-lg py-4 rounded-xl font-bold hover:bg-blue-800 transition transform hover:scale-[1.005] shadow-lg">
                <i class="fas fa-money-check-alt mr-2"></i> LANJUT KE PEMBAYARAN
            </button>
        </a>
    </div>
    
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('cartItemsContainer');
    const totalSessionsEl = document.getElementById('totalSessions');
    const totalPriceEl = document.getElementById('totalPrice');
    const btnContinue = document.getElementById('btnContinue');

    // Load from localStorage
    let cart = JSON.parse(localStorage.getItem('booking_cart')) || [];

    function formatRupiah(number) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(number);
    }

    window.removeItem = function(index) {
        cart.splice(index, 1);
        localStorage.setItem('booking_cart', JSON.stringify(cart));
        renderCart();
    }

    function renderCart() {
        if (cart.length === 0) {
            container.innerHTML = `
                <div class="text-center py-12 border-2 border-dashed border-gray-300 rounded-xl bg-gray-50">
                    <i class="fas fa-calendar-times text-5xl text-gray-400 mb-3"></i>
                    <h3 class="text-xl font-bold text-gray-700">Keranjang Kosong</h3>
                    <p class="text-gray-500 mb-6">Anda belum memilih slot jadwal lapangan.</p>
                    <a href="/venue" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition inline-block">
                        Kembali Cari Lapangan
                    </a>
                </div>
            `;
            totalSessionsEl.textContent = '0 Sesi';
            totalPriceEl.textContent = 'Rp0';
            btnContinue.style.display = 'none';
            return;
        }

        btnContinue.style.display = 'block';
        let total = 0;
        
        container.innerHTML = cart.map((item, idx) => {
            const price = parseInt(item.price);
            total += price;
            return `
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-5 flex flex-col sm:flex-row justify-between items-start sm:items-center">
                    <div>
                        <h2 class="font-bold text-xl text-blue-800">${item.venue}</h2>
                        <p class="text-base text-gray-700">${item.field}</p>
                        <p class="text-base font-semibold mt-2 text-gray-800">
                            <i class="far fa-calendar-alt mr-1"></i> ${item.date} &bull; <i class="far fa-clock ml-2 mr-1"></i> ${item.time}
                        </p>
                        <p class="text-lg font-bold text-blue-700 mt-2">${formatRupiah(price)}</p>
                    </div>
                    <div class="text-left sm:text-right mt-4 sm:mt-0 w-full sm:w-auto">
                        <button onclick="removeItem(${idx})" class="flex items-center text-sm text-red-600 hover:text-red-700 mt-2 transition font-semibold">
                            <i class="fas fa-trash-alt mr-1"></i> Hapus
                        </button>
                    </div>
                </div>
            `;
        }).join('');

        totalSessionsEl.textContent = `${cart.length} Sesi`;
        totalPriceEl.textContent = formatRupiah(total);
    }

    renderCart();
});
</script>

@endsection