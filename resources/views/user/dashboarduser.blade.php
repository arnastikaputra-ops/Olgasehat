@extends('user.layout.user')

@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<style>
    .tab-active {
        border-bottom: 3px solid #2563eb;
        color: #2563eb;
        font-weight: 700;
        font-size: 1rem;
        position: relative;
        transition: all 0.3s ease;
    }
    .tab-active::after {
        content: '';
        position: absolute;
        bottom: -3px;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #2563eb 0%, #3b82f6 100%);
        border-radius: 3px 3px 0 0;
    }
    .tab-inactive {
        border-bottom: 3px solid transparent;
        color: #6b7280;
        font-weight: 500;
        font-size: 1rem;
        transition: all 0.3s ease;
        position: relative;
    }
    .tab-inactive:hover {
        color: #2563eb;
        font-weight: 600;
        border-bottom-color: #3b82f6;
        transform: translateY(-2px);
    }
    .tab-inactive:hover::after {
        content: '';
        position: absolute;
        bottom: -3px;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #3b82f6 0%, #60a5fa 100%);
        border-radius: 3px 3px 0 0;
        animation: slideIn 0.3s ease;
    }
    @keyframes slideIn {
        from {
            transform: scaleX(0);
        }
        to {
            transform: scaleX(1);
        }
    }
    .stat-card {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        transition: all 0.3s ease;
        border-left: 4px solid;
    }
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
    .stat-card.blue { border-left-color: #3b82f6; }
    .stat-card.green { border-left-color: #10b981; }
    .stat-card.purple { border-left-color: #8b5cf6; }
    .stat-card.yellow { border-left-color: #f59e0b; }
    .booking-card {
        background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%);
        transition: all 0.3s ease;
    }
    .booking-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
    }
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
    .section-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 1rem;
    }
</style>
@endpush

@section('content')
<main class="pt-20 min-h-screen pb-8 px-4 md:px-8 lg:px-10 bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- Kolom Kiri (2/3) --}}
        <div class="lg:col-span-2 space-y-6">
            
            {{-- Header dengan Gradient --}}
            <div class="dashboard-header bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <h1 class="text-4xl md:text-5xl font-bold mb-3 text-gray-900">Dashboard</h1>
                <p class="text-lg text-gray-700 mb-2">Selamat Datang, <span class="font-semibold text-gray-900">{{ Auth::user()->name ?? 'Rendra' }}</span></p>
                <p class="text-base text-gray-600 leading-relaxed max-w-3xl">
                    Nikmati akses penuh ke semua fitur premium Olga Sehat: booking lapangan olahraga favorit, layanan kesehatan terdekat, 
                    komunitas olahraga aktif, dan membership eksklusif. Kelola semua aktivitas olahraga dan kesehatan Anda dalam satu platform terintegrasi.
                </p>
            </div>

            {{-- Progres Mingguan --}}
            <section class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="section-title flex items-center">
                        <i class="fas fa-chart-line text-blue-600 mr-3 text-xl"></i>
                        Progres Mingguan Anda
                    </h2>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <!-- Card: Sesi Pemesanan -->
                    <div class="stat-card blue bg-white rounded-xl p-5 border border-gray-200 shadow-md">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-calendar-check text-blue-600 text-xl"></i>
                            </div>
                        </div>
                        <p class="text-sm text-gray-500 mb-1 font-medium">Sesi Pemesanan</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $bookingCount }}</p>
                    </div>

                    <!-- Card: Riwayat Kontrol -->
                    <div class="stat-card green bg-white rounded-xl p-5 border border-gray-200 shadow-md">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-clipboard-list text-green-600 text-xl"></i>
                            </div>
                        </div>
                        <p class="text-sm text-gray-500 mb-1 font-medium">Riwayat Kontrol</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $healthBookingCount }}</p>
                    </div>

                    <!-- Card: Komunitas Aktif -->
                    <div class="stat-card purple bg-white rounded-xl p-5 border border-gray-200 shadow-md">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-users text-purple-600 text-xl"></i>
                            </div>
                        </div>
                        <p class="text-sm text-gray-500 mb-1 font-medium">Komunitas Aktif</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $communityCount }}</p>
                    </div>

                    <!-- Card: Membership -->
                    <div class="stat-card yellow bg-white rounded-xl p-5 border border-gray-200 shadow-md">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-crown text-yellow-600 text-xl"></i>
                            </div>
                        </div>
                        <p class="text-sm text-gray-500 mb-1 font-medium">Membership</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $membershipCount }}</p>
                    </div>
                </div>
            </section>

            {{-- Navigation Tabs & Content Area dalam 1 Card --}}
            <section class="bg-white rounded-2xl shadow-lg border border-gray-100">
                {{-- Navigation Tabs --}}
                <div class="p-4 border-b border-gray-200 bg-gray-50/50">
                    <div class="flex space-x-1">
                        <button id="tabPemesanan" class="tab-active pb-3 px-6 text-base transition-all duration-300 cursor-pointer">
                            <i class="fas fa-calendar-alt mr-2"></i>Pemesanan
                        </button>
                        <button id="tabCekKesehatan" class="tab-inactive pb-3 px-6 text-base transition-all duration-300 cursor-pointer">
                            <i class="fas fa-heartbeat mr-2"></i>Cek Kesehatan
                        </button>
                        <button id="tabSettings" class="tab-inactive pb-3 px-6 text-base transition-all duration-300 cursor-pointer">
                            <i class="fas fa-cog mr-2"></i>Settings
                        </button>
                    </div>
                </div>

                {{-- Content Area: Pemesanan (Default Active) --}}
                <div id="contentPemesanan" class="content-tab p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-calendar-alt text-blue-600 mr-2"></i>
                            Riwayat Pemesanan
                        </h3>
                    </div>
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-8 border border-blue-200 text-center">
                        <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-calendar-check text-blue-600 text-3xl"></i>
                        </div>
                        <h4 class="text-xl font-bold text-gray-900 mb-2">Kelola Riwayat Pemesanan Anda</h4>
                        <p class="text-gray-600 mb-6 max-w-md mx-auto">
                            Lihat semua riwayat pemesanan lapangan olahraga, booking venue, dan transaksi Anda dalam satu tempat.
                        </p>
                        <a href="/riwayatpayment" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition duration-200">
                            <i class="fas fa-arrow-right mr-2"></i>Lihat Riwayat Pemesanan
                        </a>
                    </div>
                </div>

                {{-- Content Area: Cek Kesehatan (Hidden by default) --}}
                <div id="contentCekKesehatan" class="content-tab hidden p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-clipboard-list text-green-600 mr-2"></i>
                            Riwayat Kontrol Kesehatan
                        </h3>
                    </div>
                    <div class="bg-gradient-to-br from-green-50 to-blue-50 rounded-xl p-8 border border-green-200 text-center">
                        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-heartbeat text-green-600 text-3xl"></i>
                        </div>
                        <h4 class="text-xl font-bold text-gray-900 mb-2">Kelola Riwayat Kontrol Anda</h4>
                        <p class="text-gray-600 mb-6 max-w-md mx-auto">
                            Lihat semua riwayat cek kesehatan, hasil pemeriksaan, dan rekomendasi dokter dalam satu tempat.
                        </p>
                        <a href="/riwayatkontrol" class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow-md transition duration-200">
                            <i class="fas fa-arrow-right mr-2"></i>Lihat Riwayat Kontrol
                        </a>
                    </div>
                </div>

                {{-- Content Area: Settings (Hidden by default) --}}
                <div id="contentSettings" class="content-tab hidden p-6">
                    <div class="text-center py-8">
                        <i class="fas fa-cog text-4xl text-gray-600 mb-4"></i>
                        <p class="text-gray-600 text-lg">Konten Settings akan ditampilkan di sini.</p>
                    </div>
                </div>
            </section>

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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab Navigation
    const tabs = {
        pemesanan: {
            button: document.getElementById('tabPemesanan'),
            content: document.getElementById('contentPemesanan')
        },
        cekKesehatan: {
            button: document.getElementById('tabCekKesehatan'),
            content: document.getElementById('contentCekKesehatan')
        },
        settings: {
            button: document.getElementById('tabSettings'),
            content: document.getElementById('contentSettings')
        }
    };

    function switchTab(activeTab) {
        // Reset all tabs
        Object.keys(tabs).forEach(key => {
            tabs[key].button.classList.remove('tab-active');
            tabs[key].button.classList.add('tab-inactive');
            tabs[key].content.classList.add('hidden');
        });

        // Activate selected tab
        tabs[activeTab].button.classList.remove('tab-inactive');
        tabs[activeTab].button.classList.add('tab-active');
        tabs[activeTab].content.classList.remove('hidden');
    }

    // Event listeners
    tabs.pemesanan.button.addEventListener('click', () => switchTab('pemesanan'));
    tabs.cekKesehatan.button.addEventListener('click', () => switchTab('cekKesehatan'));
    tabs.settings.button.addEventListener('click', () => switchTab('settings'));
});
</script>
@endsection
