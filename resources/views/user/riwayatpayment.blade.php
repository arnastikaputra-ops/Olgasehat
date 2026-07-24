@extends('user.layout.user')

@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<style>
    .sidebar-card {
        background: #ffffff !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    .blue-banner-card {
        background-image: url('{{ asset('assets/blue-banner.png') }}');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }
    .quick-action-item {
        transition: all 0.3s ease;
    }
    .quick-action-item:hover {
        transform: translateX(4px);
        background-color: #f9fafb;
    }
    .booking-card {
        transition: all 0.3s ease;
        border-left: 4px solid;
    }
    .booking-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }
    .booking-card.completed {
        border-left-color: #10b981;
    }
    .booking-card.pending {
        border-left-color: #f59e0b;
    }
    .booking-card.cancelled {
        border-left-color: #ef4444;
    }
    .status-badge {
        padding: 0.375rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .status-completed {
        background-color: #d1fae5;
        color: #065f46;
    }
    .status-pending {
        background-color: #fef3c7;
        color: #92400e;
    }
    .status-cancelled {
        background-color: #fee2e2;
        color: #991b1b;
    }
</style>
@endpush

@section('content')

<main class="pt-20 min-h-screen pb-8 px-4 md:px-8 lg:px-10 bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- Kolom Kiri (2/3) --}}
        <div class="lg:col-span-2 space-y-6">
            
            {{-- Header --}}
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">Riwayat Pemesanan</h1>
                        <p class="text-gray-600">Lihat semua booking venue olahraga yang telah Anda lakukan</p>
                    </div>
                    <div class="hidden md:flex items-center space-x-2">
                        <div class="bg-blue-100 text-blue-700 px-4 py-2 rounded-lg font-semibold">
                            <i class="fas fa-calendar-check mr-2"></i>Total: {{ (($venueBookings ?? collect())->count() + ($healthBookings ?? collect())->count()) }} Booking
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filter Section --}}
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-filter mr-2 text-gray-500"></i>Cari Booking
                        </label>
                        <input 
                            type="text" 
                            placeholder="Cari berdasarkan venue, lapangan, atau ID booking..."
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                        >
                    </div>
                    <div class="md:w-48">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-calendar-alt mr-2 text-gray-500"></i>Tanggal
                        </label>
                        <input 
                            type="date" 
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                        >
                    </div>
                    <div class="md:w-48">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-info-circle mr-2 text-gray-500"></i>Status
                        </label>
                        <select class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            <option value="">Semua Status</option>
                            <option value="completed">Selesai</option>
                            <option value="pending">Pending</option>
                            <option value="cancelled">Dibatalkan</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Booking Cards --}}
            <div class="space-y-4">
                {{-- Venue Bookings --}}
                @forelse ($venueBookings ?? [] as $booking)
                <div class="booking-card completed bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
                    <div class="flex flex-col md:flex-row">
                        <div class="md:w-48 flex-shrink-0 bg-blue-50 flex items-center justify-center p-4">
                            @if($booking->bank_code)
                                <img src="/images/banks/{{ strtolower($booking->bank_code) }}.png" alt="{{ $booking->bank_code }}" class="h-12 object-contain" onerror="this.src='/images/banks/{{ strtolower($booking->bank_code) }}.svg'; this.onerror=function(){ this.outerHTML='<i class=\\'fas fa-credit-card text-4xl text-blue-600\\'></i>'; }" />
                            @else
                                <i class="fas fa-futbol text-4xl text-blue-600"></i>
                            @endif
                        </div>
                        
                        <div class="flex-1 p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-2 mb-2">
                                        <span class="text-xs text-gray-500 font-medium">Booking Lapangan Venue</span>
                                        @if(($booking->status_pembayaran ?? '') == 'paid')
                                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800 border border-green-200">
                                                <i class="fas fa-check-circle mr-1"></i>LUNAS (DISETUJUI)
                                            </span>
                                        @elseif(($booking->status_pembayaran ?? '') == 'pending_acc' || ($booking->status_pembayaran ?? '') == 'pending')
                                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                                <i class="fas fa-clock mr-1"></i>MENUNGGU ACC PEMILIK VENUE
                                            </span>
                                        @elseif(($booking->status_pembayaran ?? '') == 'rejected')
                                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800 border border-red-200">
                                                <i class="fas fa-times-circle mr-1"></i>DITOLAK
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800">
                                                <i class="fas fa-check-circle mr-1"></i>{{ strtoupper($booking->status_pembayaran ?? 'LUNAS') }}
                                            </span>
                                        @endif
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $booking->venue->namavenue ?? 'Venue Olahraga' }}</h3>
                                    <p class="text-xs text-gray-500 mb-2">
                                        Virtual Account: <span class="font-mono font-bold text-gray-800">{{ $booking->virtual_account }}</span> ({{ $booking->bank_code ?? 'VA' }})
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-2xl font-bold text-green-600 mb-1">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</p>
                                    <p class="text-xs text-gray-500">Total Pembayaran</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 p-4 bg-gray-50 rounded-lg">
                                <div class="flex items-center text-sm text-gray-700">
                                    <i class="fas fa-hashtag mr-3 text-blue-600 w-5"></i>
                                    <div>
                                        <p class="text-xs text-gray-500">Kode Booking</p>
                                        <p class="font-semibold text-blue-700">{{ $booking->kode_booking }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center text-sm text-gray-700">
                                    <i class="fas fa-user mr-3 text-blue-600 w-5"></i>
                                    <div>
                                        <p class="text-xs text-gray-500">Nama Pemesan</p>
                                        <p class="font-semibold">{{ $booking->nama_pemesan }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center text-sm text-gray-700">
                                    <i class="fas fa-calendar-alt mr-3 text-blue-600 w-5"></i>
                                    <div>
                                        <p class="text-xs text-gray-500">Tanggal Booking</p>
                                        <p class="font-semibold">{{ $booking->created_at->format('d F Y, H:i') }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center text-sm text-gray-700">
                                    <i class="fas fa-credit-card mr-3 text-blue-600 w-5"></i>
                                    <div>
                                        <p class="text-xs text-gray-500">Metode Pembayaran</p>
                                        <p class="font-semibold">{{ $booking->bank_code ?? 'Virtual Account' }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-wrap gap-2 pt-2 border-t border-gray-200">
                                <a href="/venue" class="flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition text-sm">
                                    <i class="fas fa-eye mr-2"></i>Lihat Venue
                                </a>
                                @if($booking->bukti_pembayaran)
                                    <a href="{{ asset('bukti_pembayaran/' . $booking->bukti_pembayaran) }}" target="_blank" class="flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-semibold transition text-sm">
                                        <i class="fas fa-file-image mr-2"></i>Bukti Transfer
                                    </a>
                                @endif
                                <button class="flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-semibold transition text-sm" onclick="window.print()">
                                    <i class="fas fa-print mr-2"></i>Cetak Invoice
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                @endforelse

                {{-- Clinic Bookings --}}
                @forelse ($healthBookings ?? [] as $hb)
                <div class="booking-card completed bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
                    <div class="flex flex-col md:flex-row">
                        <div class="md:w-48 flex-shrink-0 bg-green-50 flex items-center justify-center p-4">
                            <i class="fas fa-hospital-user text-4xl text-green-600"></i>
                        </div>
                        
                        <div class="flex-1 p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-2 mb-2">
                                        <span class="text-xs text-gray-500 font-medium">Layanan Klinik Kesehatan</span>
                                        @if(($hb->status_pembayaran ?? '') == 'paid')
                                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800 border border-green-200">
                                                <i class="fas fa-check-circle mr-1"></i>LUNAS (DISETUJUI)
                                            </span>
                                        @elseif(($hb->status_pembayaran ?? '') == 'pending_acc' || ($hb->status_pembayaran ?? '') == 'pending')
                                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                                <i class="fas fa-clock mr-1"></i>MENUNGGU ACC PENGELOLA KLINIK
                                            </span>
                                        @elseif(($hb->status_pembayaran ?? '') == 'rejected')
                                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800 border border-red-200">
                                                <i class="fas fa-times-circle mr-1"></i>DITOLAK
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800">
                                                <i class="fas fa-check-circle mr-1"></i>{{ strtoupper($hb->status_pembayaran ?? 'PENDING') }}
                                            </span>
                                        @endif
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $hb->clinic->nama ?? 'Klinik Kesehatan' }}</h3>
                                    <p class="text-xs text-gray-500 mb-2">
                                        Virtual Account: <span class="font-mono font-bold text-gray-800">{{ $hb->virtual_account ?? '-' }}</span> ({{ $hb->bank_code ?? 'VA' }})
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-2xl font-bold text-green-600 mb-1">Rp {{ number_format($hb->total_harga, 0, ',', '.') }}</p>
                                    <p class="text-xs text-gray-500">Total Biaya</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 p-4 bg-gray-50 rounded-lg">
                                <div class="flex items-center text-sm text-gray-700">
                                    <i class="fas fa-user-md mr-3 text-green-600 w-5"></i>
                                    <div>
                                        <p class="text-xs text-gray-500">Dokter / Spesialis</p>
                                        <p class="font-semibold">{{ $hb->doctor->nama_lengkap ?? $hb->doctor->nama ?? 'Tim Dokter' }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center text-sm text-gray-700">
                                    <i class="fas fa-stethoscope mr-3 text-green-600 w-5"></i>
                                    <div>
                                        <p class="text-xs text-gray-500">Layanan</p>
                                        <p class="font-semibold">{{ $hb->service->nama ?? 'Pemeriksaan Kesehatan' }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center text-sm text-gray-700">
                                    <i class="fas fa-calendar-alt mr-3 text-green-600 w-5"></i>
                                    <div>
                                        <p class="text-xs text-gray-500">Jadwal Pertemuan</p>
                                        <p class="font-semibold">{{ \Carbon\Carbon::parse($hb->tanggal)->format('d F Y') }} ({{ $hb->jam }} WIB)</p>
                                    </div>
                                </div>
                                <div class="flex items-center text-sm text-gray-700">
                                    <i class="fas fa-hashtag mr-3 text-green-600 w-5"></i>
                                    <div>
                                        <p class="text-xs text-gray-500">Kode Booking</p>
                                        <p class="font-semibold text-green-700">{{ $hb->kode_booking }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-wrap gap-2 pt-2 border-t border-gray-200">
                                @if($hb->bukti_pembayaran)
                                    <a href="{{ asset('bukti_pembayaran/' . $hb->bukti_pembayaran) }}" target="_blank" class="flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-semibold transition text-sm">
                                        <i class="fas fa-file-image mr-2"></i>Bukti Transfer
                                    </a>
                                @endif
                                <button class="flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-semibold transition text-sm" onclick="window.print()">
                                    <i class="fas fa-print mr-2"></i>Cetak Invoice
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                @endforelse

                @if(($venueBookings->isEmpty() ?? true) && ($healthBookings->isEmpty() ?? true))
                <div class="bg-white rounded-xl shadow-lg p-12 text-center border-2 border-dashed border-gray-300">
                    <i class="fas fa-calendar-times text-6xl text-gray-400 mb-4"></i>
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">Belum Ada Booking</h3>
                    <p class="text-gray-500 max-w-lg mx-auto mb-6">
                        Anda belum melakukan booking venue atau layanan klinik apapun. Mulai dengan mencari layanan favorit Anda sekarang!
                    </p>
                    <div class="flex justify-center space-x-4">
                        <a href="/venue" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition">
                            <i class="fas fa-futbol mr-2"></i> Cari Venue
                        </a>
                        <a href="/healthy" class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow-md transition">
                            <i class="fas fa-heartbeat mr-2"></i> Cari Klinik
                        </a>
                    </div>
                </div>
                @endif
            </div>

        </div>

        {{-- Kolom Kanan (1/3) - Sidebar Unified --}}
        <div class="lg:col-span-1">
            <div class="sidebar-card bg-white rounded-2xl p-6 space-y-6 border border-gray-200">
                
                {{-- Greeting Section --}}
                <div class="flex items-start justify-between pb-4 border-b border-gray-200">
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Hello. {{ Auth::user()->name ?? 'Rendra' }}!</h2>
                        <p class="text-sm text-gray-600 leading-relaxed">Siap bergerak aktif hari ini? Yuk, cek progresmu!</p>
                    </div>
                    <div class="ml-4 flex-shrink-0">
                        @if(Auth::user()->image ?? null)
                            <img src="{{ asset('storage/' . Auth::user()->image) }}"
                                 alt="Profile Picture"
                                 class="w-20 h-20 rounded-full object-cover shadow-lg border-2 border-gray-200">
                        @else
                            @php
                                $userName = Auth::user()->name ?? 'Rendra';
                                $initial = strtolower(substr($userName, 0, 1));
                            @endphp
                            <div class="w-20 h-20 rounded-full bg-blue-300 flex items-center justify-center text-blue-800 text-3xl font-bold shadow-lg">
                                {{ $initial }}
                            </div>
                        @endif
                    </div>
                </div>
                
                {{-- Action Buttons --}}
                <div class="flex gap-2 pb-4 border-b border-gray-200">
                    <a href="javascript:void(0)" onclick="showContactModal()" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center py-2.5 px-4 rounded-lg font-semibold transition duration-200 text-sm shadow-md hover:shadow-lg">
                        <i class="fas fa-phone mr-1"></i>Hubungi Kami
                    </a>
                    <a href="/edit-profile-user" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 text-center py-2.5 px-4 rounded-lg font-semibold transition duration-200 text-sm shadow-md hover:shadow-lg">
                        <i class="fas fa-user-edit mr-1"></i>Edit Profile
                    </a>
                </div>

                {{-- Blue Banner Card: Nikmati Akses User --}}
                <a href="#" class="quick-action-item rounded-xl p-4 border border-gray-200 shadow-sm hover:shadow-md block relative overflow-hidden">
                    <div class="absolute inset-0 blue-banner-card opacity-20"></div>
                    <div class="relative z-10 flex items-start space-x-3">
                        <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center flex-shrink-0 shadow-md">
                            <i class="fas fa-star text-white text-lg"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900 mb-1">Nikmati Akses User</h3>
                            <p class="text-xs text-gray-500">Akses penuh ke semua fitur premium dan layanan eksklusif untuk pengalaman terbaik Anda</p>
                        </div>
                    </div>
                </a>

                {{-- Quick Actions Section --}}
                <div>
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-bolt text-yellow-500 mr-2"></i>
                        Aksi Cepat
                    </h2>
                    <div class="space-y-3">
                        <!-- Fasilitas Olahraga -->
                        <a href="/venue" class="quick-action-item bg-white rounded-xl p-4 border border-gray-200 shadow-sm hover:shadow-md block">
                            <div class="flex items-start space-x-3">
                                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-futbol text-blue-600 text-lg"></i>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900 mb-1">Fasilitas Olahraga</h3>
                                    <p class="text-xs text-gray-500">Booking lapangan olahraga favorit Anda dengan mudah</p>
                                </div>
                            </div>
                        </a>

                        <!-- Layanan Kesehatan -->
                        <a href="/healthy" class="quick-action-item bg-white rounded-xl p-4 border border-gray-200 shadow-sm hover:shadow-md block">
                            <div class="flex items-start space-x-3">
                                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-heartbeat text-green-600 text-lg"></i>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900 mb-1">Layanan Kesehatan</h3>
                                    <p class="text-xs text-gray-500">Cek kesehatan dan layanan medis terdekat</p>
                                </div>
                            </div>
                        </a>

                        <!-- Buat & Temukan Komunitas -->
                        <a href="/community" class="quick-action-item bg-white rounded-xl p-4 border border-gray-200 shadow-sm hover:shadow-md block">
                            <div class="flex items-start space-x-3">
                                <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-users text-orange-600 text-lg"></i>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900 mb-1">Buat & Temukan Komunitas</h3>
                                    <p class="text-xs text-gray-500">Bergabung atau buat komunitas olahraga baru</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</main>

@endsection
