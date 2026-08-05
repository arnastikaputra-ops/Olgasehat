@extends('layouts.app')

@section('content')

@php
    $user = Auth::user();
    $isMember = false;
    if ($user) {
        $isMember = \App\Models\ActivityParticipant::where('user_id', $user->id)
            ->where('status', 'approved')
            ->whereHas('activity', function($q) {
                $q->where('jenis', 'membership')
                  ->orWhereHas('activityType', function($at) {
                      $at->whereIn('name', ['klub', 'membership']);
                  });
            })
            ->exists();
    }
@endphp

<section class="container mx-auto px-4 sm:px-6 lg:px-8 py-10 pt-[120px] max-w-5xl"> 
    @if($isMember)
    <div class="mb-6 bg-gradient-to-r from-amber-500 to-amber-600 rounded-xl p-4 text-white shadow-lg flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center text-xl shadow-inner">
                <i class="fas fa-crown"></i>
            </div>
            <div>
                <h4 class="font-bold text-sm">Status VIP Member Aktif!</h4>
                <p class="text-xs text-amber-100">Diskon khusus member 10% akan otomatis diterapkan pada saat checkout.</p>
            </div>
        </div>
        <span class="bg-white text-amber-800 text-xs font-black px-3 py-1.5 rounded-full uppercase tracking-wider shadow">DISKON 10%</span>
    </div>
    @endif

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
                <form class="space-y-5" id="customerForm">
                    <div>
                        <label for="customerName" class="block text-sm font-medium text-gray-700">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" id="customerName" name="customerName" placeholder="Contoh: Bima Sakti" required
                                value="{{ $user->name ?? '' }}"
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
                            <input type="tel" id="customerPhone" name="customerPhone" placeholder="812345678" required
                                    class="flex-1 rounded-lg border border-gray-300 px-4 py-2.5 focus:ring-blue-500 focus:border-blue-500 text-base" />
                        </div>
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email <span class="text-red-500">*</span></label>
                        <input type="email" id="email" name="email" placeholder="contoh@gmail.com" required
                                value="{{ $user->email ?? '' }}"
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
                        <li>Pemesanan bersifat final setelah pembayaran diverifikasi oleh sistem.</li>
                        <li>Reschedule bisa dilakukan maksimal H-3 dari Tanggal Pemesanan yang sudah disewa.</li>
                        <li>Penggunaan Lapangan dan waktu :
                            <ul class="list-disc list-inside ml-5 mt-1 space-y-1">
                                <li>Gunakan Lapangan sesuai dengan jadwal yang telah dipesan.</li>
                                <li>Datanglah 10 menit sebelum jadwal bermain.</li>
                            </ul>
                        </li>
                        <li>Kebersihan :
                            <ul class="list-disc list-inside ml-5 mt-1 space-y-1">
                                <li>Jaga kebersihan lapangan dengan selalu membuang sampah pada tempatnya.</li>
                            </ul>
                        </li>
                    </ol>
                </div>
            </section>
        </div>

        <div class="lg:col-span-5 space-y-6 md:space-y-8">
            
            <section class="bg-blue-50 rounded-xl shadow-lg p-6 md:p-8 border-t-4 border-blue-700">
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Ringkasan Pembayaran</h3>
                <div class="space-y-3 text-gray-700" id="summaryContainer">
                    <!-- Dynamic Summary Content loaded via JS -->
                </div>
            </section>

            <section class="bg-white rounded-xl shadow-lg p-6 md:p-8 border border-gray-100">
                <h2 class="text-xl font-semibold text-gray-900 mb-6 border-b pb-3">Pilih Metode Pembayaran</h2>
                <p class="text-sm text-gray-600 mb-6">Semua transaksi yang dilakukan aman dan terenkripsi.</p>

                <form class="space-y-6" id="paymentMethodForm">
                    
                    @php
                        $dbPaymentSettings = isset($paymentSettings) && $paymentSettings->count() > 0 
                            ? $paymentSettings 
                            : \App\Models\PaymentSetting::where('is_active', true)->get();
                        $banks = $dbPaymentSettings->where('category', 'bank');
                        $ewallets = $dbPaymentSettings->where('category', 'ewallet');
                    @endphp

                    <div class="border border-gray-200 rounded-lg overflow-hidden focus-within:border-blue-500 transition duration-200">
                        <label class="flex items-center p-4 cursor-pointer bg-blue-50 hover:bg-blue-100 transition duration-200">
                            <input type="radio" name="paymentMethod" value="virtualAccount" class="form-radio text-blue-700 h-5 w-5 mr-3" checked />
                            <span class="font-semibold text-gray-800">Transfer Virtual Account (VA) / Bank</span>
                        </label>
                        <div class="p-4 pt-3 bg-white grid grid-cols-3 gap-3 border-t border-gray-100" id="vaBankContainer">
                            @foreach($banks as $bIdx => $bank)
                            <label class="border border-gray-200 rounded-lg p-2 text-center cursor-pointer hover:border-blue-500 hover:bg-blue-50 flex flex-col items-center transition">
                                <input type="radio" name="selectedBank" value="{{ $bank->bank_code }}" class="mb-1" {{ $bIdx == 0 ? 'checked' : '' }} />
                                <i class="fas fa-university text-2xl text-blue-600 my-1"></i>
                                <span class="text-xs font-semibold text-gray-700">{{ $bank->bank_code }}</span>
                                <span class="text-[10px] text-gray-500 truncate max-w-full font-mono">{{ $bank->account_number }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="border border-gray-200 rounded-lg overflow-hidden focus-within:border-blue-500 transition duration-200">
                        <label class="flex items-center p-4 cursor-pointer bg-white hover:bg-gray-50 transition duration-200">
                            <input type="radio" name="paymentMethod" value="eWallets" class="form-radio text-blue-700 h-5 w-5 mr-3" />
                            <span class="font-semibold text-gray-800">E-Wallet</span>
                        </label>
                        <div class="p-4 pt-3 bg-white grid grid-cols-2 gap-3 border-t border-gray-100 hidden" id="ewalletsLogoContainer">
                            @foreach($ewallets as $ewallet)
                            <label class="border border-gray-200 rounded-lg p-2 text-center cursor-pointer hover:border-blue-500 hover:bg-blue-50 flex flex-col items-center transition">
                                <input type="radio" name="selectedBank" value="{{ $ewallet->bank_code }}" class="mb-1" />
                                <i class="fas fa-wallet text-2xl text-green-600 my-1"></i>
                                <span class="text-xs font-semibold text-gray-700">{{ $ewallet->bank_code }}</span>
                                <span class="text-[10px] text-gray-500 truncate max-w-full font-mono">{{ $ewallet->account_number }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="border border-gray-200 rounded-lg p-4 bg-gray-50 space-y-2">
                        <label class="block font-semibold text-gray-800 text-sm">
                            <i class="fas fa-file-upload text-blue-600 mr-1"></i> Upload Bukti Transfer (Foto / Screenshot) <span class="text-red-500">* Wajib</span>
                        </label>
                        <input type="file" id="buktiPembayaran" name="bukti_pembayaran" accept="image/*" class="w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200 cursor-pointer" required />
                        <p class="text-xs text-gray-500">Upload foto struk / resi bukti transfer (Format: JPG, PNG, WEBP, Maks. 4MB).</p>
                        <div id="buktiPreviewContainer" class="hidden mt-2">
                            <img id="buktiPreview" class="h-32 object-contain rounded border border-gray-300 shadow-sm" alt="Preview Bukti Pembayaran" />
                        </div>
                    </div>
                    
                </form>
            </section>

            <div class="sticky bottom-0 bg-white p-4 shadow-top-lg lg:shadow-none lg:p-0">
                <button id="payButton" class="w-full bg-blue-700 text-white text-lg py-3 rounded-xl font-semibold hover:bg-blue-800 transition transform hover:scale-[1.005] shadow-lg">
                    <i class="fas fa-credit-card mr-2"></i> BAYAR SEKARANG
                </button>
            </div>
            
            <div id="loadingOverlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
                <div class="bg-white rounded-lg p-8 flex flex-col items-center space-y-4 shadow-2xl">
                    <div class="w-12 h-12 border-4 border-blue-200 border-t-blue-700 rounded-full animate-spin"></div>
                    <p class="text-gray-700 font-semibold">Memproses Pembayaran...</p>
                </div>
            </div>
            
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const payButton = document.getElementById('payButton');
    const loadingOverlay = document.getElementById('loadingOverlay');
    const summaryContainer = document.getElementById('summaryContainer');
    const customerForm = document.getElementById('customerForm');

    // Toggle e-wallets display
    const paymentMethods = document.getElementsByName('paymentMethod');
    const ewalletsContainer = document.getElementById('ewalletsLogoContainer');

    paymentMethods.forEach(method => {
        method.addEventListener('change', function() {
            if (this.value === 'eWallets') {
                ewalletsContainer.classList.remove('hidden');
            } else {
                ewalletsContainer.classList.add('hidden');
            }
        });
    });

    // Image Preview for Bukti Pembayaran
    const buktiPembayaranInput = document.getElementById('buktiPembayaran');
    const buktiPreviewContainer = document.getElementById('buktiPreviewContainer');
    const buktiPreview = document.getElementById('buktiPreview');

    if (buktiPembayaranInput) {
        buktiPembayaranInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(evt) {
                    buktiPreview.src = evt.target.result;
                    buktiPreviewContainer.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                buktiPreviewContainer.classList.add('hidden');
            }
        });
    }

    // Load from localStorage
    let cart = JSON.parse(localStorage.getItem('booking_cart')) || [];

    if (cart.length === 0) {
        alert('Keranjang belanja kosong. Silakan pilih lapangan terlebih dahulu.');
        window.location.href = '/venue';
        return;
    }

    function formatRupiah(number) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(number);
    }

    let totalVal = 0;
    cart.forEach(item => {
        totalVal += parseInt(item.price);
    });

    // Render summary container
    summaryContainer.innerHTML = cart.map(item => `
        <div class="flex justify-between py-1 text-sm border-b border-blue-100 pb-2">
            <div>
                <span class="font-semibold text-gray-800">${item.venue} - ${item.field}</span>
                <p class="text-xs text-gray-500">${item.date} • ${item.time}</p>
            </div>
            <span class="font-bold text-gray-800">${formatRupiah(item.price)}</span>
        </div>
    `).join('') + `
        <div class="pt-3 flex justify-between items-center font-bold text-gray-900">
            <span>Total Bayar</span>
            <span class="text-2xl text-blue-700">${formatRupiah(totalVal)}</span>
        </div>
        <p class="text-xs text-right text-blue-600 font-semibold mt-1">Pembayaran Penuh (Full Payment)</p>
    `;

    payButton.innerHTML = `<i class="fas fa-credit-card mr-2"></i> BAYAR SEKARANG (${formatRupiah(totalVal)})`;

    if (payButton && loadingOverlay) {
        payButton.addEventListener('click', function(e) {
            e.preventDefault();

            // Validate form
            if (!customerForm.checkValidity()) {
                customerForm.reportValidity();
                return;
            }

            // Check if proof of payment is selected
            if (!buktiPembayaranInput || !buktiPembayaranInput.files[0]) {
                alert('Silakan unggah foto bukti transfer (bukti pembayaran) terlebih dahulu untuk menyelesaikan pendaftaran.');
                if (buktiPembayaranInput) buktiPembayaranInput.focus();
                return;
            }

            @if(!Auth::check())
                alert('Silakan login terlebih dahulu untuk melakukan pemesanan.');
                window.location.href = '/loginuser';
                return;
            @endif

            // Show loading overlay
            loadingOverlay.classList.remove('hidden');

            const slotIds = cart.map(item => item.slotId);
            const selectedBankEl = document.querySelector('input[name="selectedBank"]:checked');
            const selectedBank = selectedBankEl ? selectedBankEl.value : 'BCA';
            const selectedMethodEl = document.querySelector('input[name="paymentMethod"]:checked');
            const paymentMethod = selectedMethodEl ? selectedMethodEl.value : 'virtualAccount';

            // Construct FormData for multipart submission
            const formData = new FormData();
            slotIds.forEach(id => formData.append('slot_ids[]', id));
            formData.append('customer_name', document.getElementById('customerName').value);
            formData.append('customer_phone', document.getElementById('customerPhone').value);
            formData.append('email', document.getElementById('email').value);
            formData.append('notes', document.getElementById('notes').value);
            formData.append('payment_method', paymentMethod);
            formData.append('bank_code', selectedBank);

            if (buktiPembayaranInput && buktiPembayaranInput.files[0]) {
                formData.append('bukti_pembayaran', buktiPembayaranInput.files[0]);
            }

            // Send dynamic slot booking to backend
            fetch('/book-slots', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw err; });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Save booking details to sessionStorage for success page
                    sessionStorage.setItem('last_booking_success', JSON.stringify({
                        cart: cart,
                        totalPrice: totalVal,
                        customerName: document.getElementById('customerName').value,
                        bookingCode: data.booking ? data.booking.kode_booking : ('OLG-' + Date.now().toString().slice(-6).toUpperCase()),
                        bankCode: selectedBank,
                        virtualAccount: data.booking ? data.booking.virtual_account : '88008123456789',
                        booking: data.booking
                    }));

                    // Clear cart
                    localStorage.removeItem('booking_cart');

                    // Redirect to success
                    window.location.href = '/success';
                } else {
                    throw new Error(data.message || 'Pemesanan gagal.');
                }
            })
            .catch(error => {
                console.error("Booking error:", error);
                loadingOverlay.classList.add('hidden');
                alert(error.message || 'Jadwal slot yang Anda pilih baru saja dipesan oleh pengguna lain. Silakan pilih jadwal lain.');
                window.location.href = '/venue';
            });
        });
    }
});
</script>
@endsection