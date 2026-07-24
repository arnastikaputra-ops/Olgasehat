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
            <div class="w-8 h-8 rounded-full bg-blue-700 border-4 border-blue-300 flex items-center justify-center text-white font-semibold text-sm shadow-xl">
                2
            </div>
            <span class="font-semibold text-blue-800 text-center text-xs sm:text-sm">Data & Pembayaran</span>
        </div>
        
        <div class="flex flex-col items-center space-y-2 w-1/3 opacity-50">
            <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-semibold text-sm">
                3
            </div>
            <span class="font-semibold text-gray-500 text-center text-xs sm:text-sm">Success (Selesai)</span>
        </div>
        
    </div>
    
    <div class="h-1.5 bg-gray-200 rounded-full relative max-w-md mx-auto">
        <div class="h-1.5 bg-blue-700 rounded-full absolute top-0 left-0 w-1/2 transition-all duration-500"></div>
    </div>
</section>

<main class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl pb-20">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 md:gap-8">
        
        <div class="lg:col-span-7 space-y-6 md:space-y-8">
            
            <section class="bg-white rounded-xl shadow-lg p-6 md:p-8 border border-gray-100">
                <h2 class="text-xl font-semibold text-gray-900 mb-6 border-b pb-3">Detail Pelanggan</h2>
                <form class="space-y-5">
                    <div>
                        <label for="customerName" class="block text-sm font-medium text-gray-700">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" id="customerName" name="customerName" placeholder="Contoh: Bima Sakti" 
                                class="mt-1 block w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:ring-blue-500 focus:border-blue-500 text-base" />
                    </div>
                    <div>
                        <label for="customerPhone" class="block text-sm font-medium text-gray-700">Nomor Telepon <span class="text-red-500">*</span></label>
                        <div class="flex space-x-3 mt-1">
                            <select id="countryCode" name="countryCode" 
                                    class="rounded-lg border border-gray-300 px-3 py-2.5 focus:ring-blue-500 focus:border-blue-500 text-base">
                                <option value="+62" selected>🇮🇩 +62</option>
                                <option value="+1">🇺🇸 +1</option>
                                <option value="+44">🇬🇧 +44</option>
                            </select>
                            <input type="tel" id="customerPhone" name="customerPhone" placeholder="812345678" 
                                    class="flex-1 rounded-lg border border-gray-300 px-4 py-2.5 focus:ring-blue-500 focus:border-blue-500 text-base" />
                        </div>
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email <span class="text-red-500">*</span></label>
                        <input type="email" id="email" name="email" placeholder="contoh@gmail.com" 
                                class="mt-1 block w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:ring-blue-500 focus:border-blue-500 text-base" />
                    </div>
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700">Catatan Tambahan (Opsional)</label>
                        <textarea id="notes" name="notes" rows="3" placeholder="Contoh: Mohon disiapkan air mineral dingin" 
                                    class="mt-1 block w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:ring-blue-500 focus:border-blue-500 text-base"></textarea>
                    </div>
                </form>
            </section>
            
            <section class="bg-white rounded-xl shadow-lg p-6 md:p-8 border border-gray-100">
                <h3 class="text-xl font-semibold text-gray-900 mb-4 border-b pb-3">Syarat & Ketentuan Venue</h3>
                <div class="max-h-64 overflow-y-auto pr-2">
                    <ol class="list-decimal list-inside space-y-3 text-sm text-gray-700">
                        <li>Non Refund & Jika ada Refund hanya berlaku apabila ada pemakaian yang dilakukan oleh Pihak PLN BEC Bandung</li>
                        <li>Reschedule bisa dilakukan jika jadwal main masih H-3 dari Tanggal Pemesanan yang sudah dilakukan</li>
                        <li>Penggunaan Lapangan dan waktu :
                            <ul class="list-disc list-inside ml-5 mt-1 space-y-1">
                                <li>Gunakan Lapangan sesuai dengan jadwal yang telah dipesan</li>
                                <li>Datanglah 10 menit sebelum jadwal</li>
                                <li>Jika ingin memperpanjang waktu bermain silakan konfirmasi terlebih dahulu dengan pengelola</li>
                            </ul>
                        </li>
                        <li>Kebersihan :
                            <ul class="list-disc list-inside ml-5 mt-1 space-y-1">
                                <li>Jaga kebersihan lapangan dengan selalu membuang sampah pada tempatnya</li>
                            </ul>
                        </li>
                        <li>Keamanan :
                            <ul class="list-disc list-inside ml-5 mt-1 space-y-1">
                                <li>Selalau waspada terhadap barang bawaan anda, kehilangan barang diluar tanggung jawab pihak BEC</li>
                                <li>Ketika tidak sedang bermain, jaga jarak aman dari area aktif untuk menghindari resiko cedera</li>
                            </ul>
                        </li>
                        <li>Keterangan dan Etika bermain: (Teks lanjutan di sini...)</li>
                    </ol>
                </div>
            </section>
        </div>

        <div class="lg:col-span-5 space-y-6 md:space-y-8">
            
            <section class="bg-blue-50 rounded-xl shadow-lg p-6 md:p-8 border-t-4 border-blue-700">
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Ringkasan Pembayaran</h3>
                <div class="space-y-2 text-gray-700">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Harga Lapangan</span>
                        <span class="font-semibold text-gray-800">Rp 100.000</span>
                    </div>

                    <div class="pt-4 border-t border-gray-200 flex justify-between items-center">
                        <div class="text-xl font-semibold text-gray-900">Total Bayar</div>
                        <div class="text-2xl font-semibold text-blue-700">Rp 100.000</div>
                    </div>
                    <p class="text-sm text-right text-blue-600 font-semibold mt-1">Pembayaran Penuh (Full Payment)</p>
                </div>
            </section>

            <section class="bg-white rounded-xl shadow-lg p-6 md:p-8 border border-gray-100">
                <h2 class="text-xl font-semibold text-gray-900 mb-6 border-b pb-3">Pilih Metode Pembayaran</h2>
                <p class="text-sm text-gray-600 mb-6">Semua transaksi yang dilakukan aman dan terenkripsi.</p>

                <form class="space-y-6">
                    
                    <div class="border border-gray-200 rounded-lg overflow-hidden focus-within:border-blue-500 transition duration-200">
                        <label class="flex items-center p-4 cursor-pointer bg-blue-50 hover:bg-blue-100 transition duration-200">
                            <input type="radio" name="paymentMethod" value="virtualAccount" class="form-radio text-blue-700 h-5 w-5 mr-3" checked />
                            <span class="font-semibold text-gray-800">Transfer Virtual Account</span>
                        </label>
                        <div class="p-4 pt-3 bg-white grid grid-cols-4 gap-4 border-t border-gray-100">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5c/Bank_Central_Asia.svg/320px-Bank_Central_Asia.svg.png" alt="BCA" class="h-8 sm:h-10 object-contain mx-auto" />
                            <img src="https://upload.wikimedia.org/wikipedia/id/thumb/5/55/BNI_logo.svg/320px-BNI_logo.svg.png" alt="BNI" class="h-8 sm:h-10 object-contain mx-auto" />
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/ad/Bank_Mandiri_logo_2016.svg/320px-Bank_Mandiri_logo_2016.svg.png" alt="Mandiri" class="h-8 sm:h-10 object-contain mx-auto" />
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/0/09/Logo_Permata_Bank.svg/320px-Logo_Permata_Bank.svg.png" alt="Permata Bank" class="h-8 sm:h-10 object-contain mx-auto" />
                            </div>
                    </div>

                    <div class="border border-gray-200 rounded-lg overflow-hidden focus-within:border-blue-500 transition duration-200">
                        <label class="flex items-center p-4 cursor-pointer bg-white hover:bg-gray-50 transition duration-200">
                            <input type="radio" name="paymentMethod" value="eWallets" class="form-radio text-blue-700 h-5 w-5 mr-3" />
                            <span class="font-semibold text-gray-800">e-Wallets</span>
                        </label>
                        <div class="p-4 pt-3 bg-white grid grid-cols-4 gap-4 border-t border-gray-100 hidden">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/eb/Logo_ovo_sweat.svg/320px-Logo_ovo_sweat.svg.png" alt="OVO" class="h-8 sm:h-10 object-contain mx-auto" />
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/7/72/Logo_dana_blue.svg/320px-Logo_dana_blue.svg.png" alt="Dana" class="h-8 sm:h-10 object-contain mx-auto" />
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/86/Gopay_logo.svg/320px-Gopay_logo.svg.png" alt="GoPay" class="h-8 sm:h-10 object-contain mx-auto" />
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/85/LinkAja.svg/320px-LinkAja.svg.png" alt="LinkAja" class="h-8 sm:h-10 object-contain mx-auto" />
                        </div>
                    </div>
                    
                </form>
            </section>

            {{-- Menghapus tag <a> yang mengelilingi tombol --}}
            <div class="sticky bottom-0 bg-white p-4 shadow-top-lg lg:shadow-none lg:p-0">
                <button id="payButton" class="w-full bg-blue-700 text-white text-lg py-3 rounded-xl font-semibold hover:bg-blue-800 transition transform hover:scale-[1.005] shadow-lg">
                    <i class="fas fa-credit-card mr-2"></i> BAYAR SEKARANG (Rp 100.000)
                </button>
            </div>
            
            {{-- Loading Overlay Diletakkan di Luar Main Content untuk memastikan fixed position bekerja --}}
            <div id="loadingOverlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
                <div class="bg-white rounded-lg p-8 flex flex-col items-center space-y-4 shadow-2xl">
                    <div class="w-12 h-12 border-4 border-blue-200 border-t-blue-700 rounded-full animate-spin"></div>
                    <p class="text-gray-700 font-semibold">Memproses Pembayaran...</p>
                </div>
            </div>
            
        </div>
    </div>
</main>

{{-- SCRIPT DITARUH DI SINI DI DALAM SECTION CONTENT --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const payButton = document.getElementById('payButton');
    const loadingOverlay = document.getElementById('loadingOverlay');

    if (payButton && loadingOverlay) {
        payButton.addEventListener('click', function(e) {
            // e.preventDefault(); // Tidak perlu karena sudah bukan tag <a>

            // 1. Tampilkan loading overlay
            loadingOverlay.classList.remove('hidden');

            // 2. Simulasi proses pembayaran
            setTimeout(function() {
                try {
                    // Logic pembayaran yang sebenarnya akan ada di sini
                    
                    // 3. Redirect ke halaman success
                    window.location.href = '/success';

                } catch (error) {
                    console.error("Payment error:", error);
                    // Jika terjadi error, sembunyikan loading dan berikan feedback
                    loadingOverlay.classList.add('hidden');
                    alert('Terjadi kesalahan saat memproses pembayaran. Silakan coba lagi.');
                }
            }, 3000); // 3 detik simulasi delay
        });
    }
});
</script>

@endsection