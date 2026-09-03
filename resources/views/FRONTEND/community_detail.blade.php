@extends('layouts.app')

@section('content')

<style>
    .detail-hero-image {
        height: 400px;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    
    @media (max-width: 768px) {
        .detail-hero-image {
            height: 250px;
        }
    }
    
    .info-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    
    .info-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    
    .badge-custom {
        padding: 0.375rem 0.75rem;
        font-size: 0.75rem;
        font-weight: 700;
        border-radius: 9999px;
        display: inline-block;
    }
    
    .description-text {
        line-height: 1.8;
        font-size: 1rem;
        word-wrap: break-word;
        overflow-wrap: break-word;
        max-width: 100%;
    }
    
    .contact-box {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .contact-box a {
        color: white;
    }
</style>

@php
    $rawLokasi = $activity->lokasi ?? '';
    $isMapsUrl = filter_var($rawLokasi, FILTER_VALIDATE_URL) || Str::contains($rawLokasi, ['http://', 'https://', 'maps.app.goo.gl', 'google.com/maps', 'goo.gl']);
    
    $embedUrl = null;
    $cleanLocationText = $rawLokasi;

    if ($rawLokasi) {
        if (preg_match('/src=["\']([^"\']+)["\']/', $rawLokasi, $matches)) {
            $embedUrl = $matches[1];
            $cleanLocationText = 'Google Maps Embedded';
        } elseif (Str::contains($rawLokasi, ['google.com/maps', 'maps.app.goo.gl', 'goo.gl/maps'])) {
            if (preg_match('/@(-?\d+\.?\d*),(-?\d+\.?\d*),?(\d+\.?\d*)?z?/', $rawLokasi, $matches)) {
                $lat = $matches[1];
                $lng = $matches[2];
                $embedUrl = 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d0!2d' . $lng . '!3d' . $lat . '!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2z' . $lat . '!' . $lng . '!5e0!3m2!1sen!2sid!4v' . time() . '!5m2!1sen!2sid';
            } elseif (preg_match('/place\/([^\/\?]+)/', $rawLokasi, $matches)) {
                $placeName = urldecode($matches[1]);
                $cleanLocationText = str_replace('+', ' ', $placeName);
                $embedUrl = 'https://www.google.com/maps?q=' . urlencode($cleanLocationText) . '&output=embed';
            } else {
                $cleanLocationText = 'Lihat Peta Google Maps';
            }
        } elseif ($isMapsUrl) {
            $cleanLocationText = 'Lihat Peta Google Maps';
        } else {
            $embedUrl = 'https://www.google.com/maps?q=' . urlencode($rawLokasi) . '&output=embed';
        }
    }
@endphp

<main class="container mx-auto px-4 sm:px-6 py-8 sm:py-12 md:py-16">
    <!-- Breadcrumb -->
    <nav class="mb-6 text-sm text-gray-600">
        <a href="/" class="hover:text-blue-700">Home</a>
        <span class="mx-2">/</span>
        <a href="/community" class="hover:text-blue-700">Komunitas & Aktivitas</a>
        <span class="mx-2">/</span>
        <span class="text-gray-800">{{ $activity->nama }}</span>
    </nav>

    <div class="flex flex-col lg:flex-row lg:space-x-8">
        
        <!-- Main Content -->
        <div class="lg:w-2/3 mb-8 lg:mb-0">
            
            <!-- Hero Image -->
            <div class="relative rounded-2xl overflow-hidden shadow-2xl mb-6">
                @if($activity->banner)
                    <img 
                        src="{{ asset('fotoaktivitas/'.$activity->banner) }}" 
                        alt="{{ $activity->nama }}" 
                        class="detail-hero-image w-full" 
                    />
                @else
                    <img 
                        src="{{ asset('assets/komunitas.png') }}" 
                        alt="{{ $activity->nama }}" 
                        class="detail-hero-image w-full" 
                    />
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                <div class="absolute bottom-0 left-0 right-0 p-6">
                    @if($activity->activityType)
                        @if($activity->activityType->name == 'open-class')
                            <span class="badge-custom bg-green-500 text-white mb-3">{{ $activity->activityType->title }}</span>
                        @elseif($activity->activityType->name == 'klub')
                            <span class="badge-custom bg-yellow-600 text-white mb-3">{{ $activity->activityType->title }}</span>
                        @elseif($activity->activityType->name == 'event')
                            <span class="badge-custom bg-blue-600 text-white mb-3">{{ $activity->activityType->title }}</span>
                        @endif
                    @else
                        <span class="badge-custom bg-gray-500 text-white mb-3">{{ ucfirst($activity->jenis) }}</span>
                    @endif
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <h1 class="text-3xl md:text-4xl font-bold text-white drop-shadow-lg">{{ $activity->nama }}</h1>
                        <button type="button" onclick="openReviewModal('{{ $activity->jenis == 'event' ? 'event' : 'komunitas' }}', {{ $activity->id }}, '{{ addslashes($activity->nama) }}')" class="inline-flex items-center bg-amber-500 hover:bg-amber-600 text-white text-xs sm:text-sm font-bold px-4 py-2 rounded-full shadow-lg transition">
                            <i class="fas fa-star mr-1.5"></i> Beri Rating {{ $activity->jenis == 'event' ? 'Event' : 'Komunitas' }}
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Info Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mb-6">
                <div class="info-card bg-white rounded-xl p-4 shadow-md border border-gray-100">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-trophy text-blue-600 text-xl"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs text-gray-500">Kategori</p>
                            <p class="font-semibold text-gray-800 truncate" title="{{ $activity->kategori }}">{{ $activity->kategori }}</p>
                        </div>
                    </div>
                </div>
                
                @if($rawLokasi)
                <div class="info-card bg-white rounded-xl p-4 shadow-md border border-gray-100">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-map-marker-alt text-green-600 text-xl"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs text-gray-500">Lokasi</p>
                            @if($isMapsUrl)
                                <a href="#section-peta-lokasi" class="font-semibold text-blue-600 hover:underline truncate block text-sm" title="{{ $cleanLocationText }}">
                                    <i class="fas fa-map-pin text-red-500 mr-1"></i>{{ $cleanLocationText }}
                                </a>
                            @else
                                <p class="font-semibold text-gray-800 truncate text-sm" title="{{ $rawLokasi }}">{{ $rawLokasi }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
                
                <div class="info-card bg-white rounded-xl p-4 shadow-md border border-gray-100">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-wallet text-purple-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Biaya</p>
                            <p class="font-semibold text-gray-800">
                                @if($activity->biaya_bergabung == 'gratis')
                                    Gratis
                                @else
                                    Berbayar
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description Section -->
            <div class="bg-white rounded-2xl shadow-lg p-6 md:p-8 mb-6 overflow-hidden">
                <div class="flex items-center space-x-3 mb-4 pb-4 border-b border-gray-200">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-600"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 break-words">Tentang {{ $activity->activityType ? $activity->activityType->title : ucfirst($activity->jenis) }}</h2>
                </div>
                <div class="description-text text-gray-700 whitespace-pre-line break-words">
                    {{ $activity->deskripsi }}
                </div>
            </div>

            <!-- Map & Location Section -->
            @if($rawLokasi)
            <div id="section-peta-lokasi" class="bg-white rounded-2xl shadow-lg p-6 md:p-8 mb-6 border border-gray-100 overflow-hidden">
                <div class="flex items-center justify-between mb-4 pb-4 border-b border-gray-200 flex-wrap gap-3">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-map-marked-alt text-green-600 text-lg"></i>
                        </div>
                        <div>
                            <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Lokasi & Peta Kegiatan</h2>
                            @if(!$isMapsUrl)
                                <p class="text-xs sm:text-sm text-gray-600 flex items-center mt-0.5">
                                    <i class="fas fa-location-dot text-red-500 mr-1.5"></i>
                                    <span>{{ $rawLokasi }}</span>
                                </p>
                            @endif
                        </div>
                    </div>
                    @if($isMapsUrl)
                        <a href="{{ $rawLokasi }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center space-x-2 bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-semibold px-4 py-2 rounded-xl shadow transition">
                            <i class="fab fa-google"></i>
                            <span>Buka di Google Maps</span>
                            <i class="fas fa-external-link-alt text-xs ml-1"></i>
                        </a>
                    @else
                        <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($rawLokasi) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center space-x-2 bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-semibold px-4 py-2 rounded-xl shadow transition">
                            <i class="fab fa-google"></i>
                            <span>Buka di Google Maps</span>
                            <i class="fas fa-external-link-alt text-xs ml-1"></i>
                        </a>
                    @endif
                </div>

                @if($embedUrl)
                    <div class="relative w-full h-72 sm:h-80 md:h-96 rounded-xl overflow-hidden shadow-md border border-gray-200">
                        <iframe 
                            src="{{ $embedUrl }}" 
                            class="w-full h-full border-0" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                @elseif($isMapsUrl)
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-6 text-center">
                        <div class="w-12 h-12 bg-blue-500/10 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-map-location-dot text-2xl"></i>
                        </div>
                        <p class="text-sm font-medium text-gray-700 mb-3">Klik tombol di bawah untuk melihat rute dan lokasi persis kegiatan di Google Maps:</p>
                        <a href="{{ $rawLokasi }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center space-x-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold px-6 py-2.5 rounded-xl shadow-lg transition">
                            <i class="fas fa-map-pin mr-1"></i>
                            <span>Petunjuk Arah Google Maps</span>
                            <i class="fas fa-external-link-alt text-xs ml-1"></i>
                        </a>
                    </div>
                @endif
            </div>
            @endif

            <!-- Benefit Box for Membership -->
            @php
                $isMembershipAct = ($activity->jenis === 'membership') || ($activity->activityType && ($activity->activityType->name === 'klub' || $activity->activityType->name === 'membership'));
            @endphp
            @if($isMembershipAct)
            <div class="bg-gradient-to-r from-amber-500/10 via-amber-400/5 to-yellow-500/10 border-2 border-amber-400/40 rounded-2xl p-6 mb-6 shadow-md">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-10 h-10 bg-amber-500 text-slate-950 rounded-xl flex items-center justify-center font-black text-xl shadow-md">
                        <i class="fas fa-crown"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-extrabold text-gray-900">Keuntungan VIP Member OlgaSehat</h3>
                        <p class="text-xs text-gray-600">Benefit eksklusif saat keanggotaan Anda aktif</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                    <div class="flex items-start space-x-2 bg-white/80 p-3 rounded-xl border border-amber-200 shadow-sm">
                        <i class="fas fa-check-circle text-amber-500 text-base mt-0.5 flex-shrink-0"></i>
                        <div>
                            <p class="font-bold text-gray-900 text-xs">Diskon Booking Venue 10%+</p>
                            <p class="text-[11px] text-gray-500">Otomatis terpotong saat sewa lapangan</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-2 bg-white/80 p-3 rounded-xl border border-amber-200 shadow-sm">
                        <i class="fas fa-check-circle text-amber-500 text-base mt-0.5 flex-shrink-0"></i>
                        <div>
                            <p class="font-bold text-gray-900 text-xs">Akses Komunitas & Sparing</p>
                            <p class="text-[11px] text-gray-500">Ikuti jadwal mabar & sparring rutin</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-2 bg-white/80 p-3 rounded-xl border border-amber-200 shadow-sm">
                        <i class="fas fa-check-circle text-amber-500 text-base mt-0.5 flex-shrink-0"></i>
                        <div>
                            <p class="font-bold text-gray-900 text-xs">Kartu E-Card VIP Digital</p>
                            <p class="text-[11px] text-gray-500">Tercatat resmi di akun OlgaSehat</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-2 bg-white/80 p-3 rounded-xl border border-amber-200 shadow-sm">
                        <i class="fas fa-check-circle text-amber-500 text-base mt-0.5 flex-shrink-0"></i>
                        <div>
                            <p class="font-bold text-gray-900 text-xs">Diskon Klinik & Kesehatan</p>
                            <p class="text-[11px] text-gray-500">Potongan biaya booking klinik mitra</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Contact Section -->
            @if($activity->link_kontak_2 ?? null)
            <div class="contact-box rounded-2xl shadow-xl p-6 md:p-8 mb-6">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-phone-alt text-white text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white">Kontak & Informasi</h3>
                </div>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-white/80 mb-1">Kontak & Informasi Lainnya:</p>
                        <a href="{{ $activity->link_kontak_2 }}" target="_blank" class="inline-flex items-center space-x-2 text-white hover:text-blue-100 transition font-medium text-lg break-all max-w-full">
                            <i class="fas fa-external-link-alt flex-shrink-0"></i>
                            <span class="break-all">{{ $activity->link_kontak_2 }}</span>
                        </a>
                    </div>
                </div>
            </div>
            @endif

            <!-- Creator Info -->
            @if($activity->user || $activity->pemilik)
            <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-blue-600 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Dibuat oleh</p>
                        <p class="font-semibold text-gray-800 text-lg">
                            @if($activity->user)
                                {{ $activity->user->name }}
                            @elseif($activity->pemilik)
                                {{ $activity->pemilik->name }}
                            @endif
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            <i class="fas fa-calendar mr-1"></i>{{ $activity->created_at->format('d M Y') }}
                        </p>
                    </div>
                </div>
            </div>
            @endif
            
        </div>

        <!-- Sidebar -->
        <div class="lg:w-1/3 lg:max-w-sm">
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-2xl p-6 shadow-xl sticky top-24">
                <div class="text-center mb-6">
                @if($activity->biaya_bergabung == 'gratis')
                        <div class="inline-block bg-green-500 text-white px-6 py-3 rounded-full mb-3">
                            <p class="text-4xl font-extrabold mb-1">Gratis</p>
                            <p class="text-sm opacity-90">Bergabung tanpa biaya</p>
                        </div>
                    @else
                        <div class="inline-block bg-gradient-to-r from-amber-500 to-yellow-500 text-slate-950 px-6 py-3 rounded-full mb-3 shadow-lg">
                            <p class="text-3xl font-black mb-1">Berbayar</p>
                            <p class="text-sm font-bold opacity-90">
                                @if($activity->harga)
                                    Rp {{ number_format($activity->harga, 0, ',', '.') }}
                                @else
                                    Hubungi untuk info biaya
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
                
                @auth
                    @php
                        $isJoined = \App\Models\ActivityParticipant::where('activity_id', $activity->id)
                            ->where('user_id', auth()->id())
                            ->first();
                    @endphp
                    @if($isJoined)
                        <div class="w-full bg-green-500 text-white py-4 rounded-xl font-bold text-lg mb-6 text-center shadow-lg">
                            <i class="fas fa-check-circle mr-2"></i> ANDA SUDAH TERDAFTAR
                        </div>
                        <div class="bg-white rounded-xl p-4 mb-6 border border-gray-200 shadow-sm">
                            <p class="text-xs text-gray-500 mb-2 uppercase font-bold tracking-wider">Status Pendaftaran:</p>
                            @if($isJoined->status === 'approved')
                                <span class="bg-emerald-100 text-emerald-800 text-xs font-black px-3 py-1.5 rounded-full border border-emerald-300 inline-flex items-center">
                                    <i class="fas fa-check-circle mr-1"></i> Membership Disetujui (Aktif)
                                </span>
                            @elseif($isJoined->status === 'pending')
                                <span class="bg-amber-100 text-amber-800 text-xs font-black px-3 py-1.5 rounded-full border border-amber-300 inline-flex items-center">
                                    <i class="fas fa-clock mr-1"></i> Menunggu Verifikasi Admin
                                </span>
                            @else
                                <span class="bg-red-100 text-red-800 text-xs font-black px-3 py-1.5 rounded-full border border-red-300 inline-flex items-center">
                                    <i class="fas fa-times-circle mr-1"></i> Pendaftaran Ditolak
                                </span>
                            @endif
                            <p class="text-sm text-gray-800 mt-3 font-medium">Nama Peserta: <strong>{{ $isJoined->nama_peserta }}</strong></p>
                        </div>
                    @else
                        @if($isMembershipAct)
                            <button onclick="openJoinModal()" class="w-full bg-gradient-to-r from-amber-500 via-yellow-500 to-amber-600 hover:from-amber-600 hover:to-yellow-600 text-slate-950 py-4 rounded-xl font-black text-lg shadow-xl hover:shadow-2xl transition transform hover:scale-105 mb-6 flex items-center justify-center">
                                <i class="fas fa-crown text-slate-900 mr-2 text-xl"></i> BELI MEMBERSHIP SEKARANG
                            </button>
                        @else
                            <button onclick="openJoinModal()" class="w-full bg-gradient-to-r from-blue-700 to-indigo-700 text-white py-4 rounded-xl font-bold text-lg hover:from-blue-600 hover:to-indigo-600 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105 mb-6">
                                <i class="fas fa-user-plus mr-2"></i> BERGABUNG SEKARANG
                            </button>
                        @endif
                    @endif
                @else
                    <a href="/loginuser" class="w-full bg-gradient-to-r from-blue-700 to-indigo-700 text-white py-4 rounded-xl font-bold text-lg hover:from-blue-600 hover:to-indigo-600 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105 mb-6 block text-center">
                        <i class="fas fa-sign-in-alt mr-2"></i> LOGIN UNTUK BERGABUNG
                    </a>
                @endauth

                <div class="border-t border-blue-300 pt-6 mt-6">
                    <h4 class="font-bold text-gray-800 mb-4 text-lg flex items-center">
                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                        Informasi Detail
                    </h4>
                    <div class="space-y-3">
                        <div class="flex items-start space-x-3 bg-white rounded-lg p-3 shadow-sm">
                            <i class="fas fa-tag text-blue-600 mt-1"></i>
                            <div>
                                <p class="text-xs text-gray-500">Kategori Olahraga</p>
                                <p class="font-semibold text-gray-800">{{ $activity->kategori }}</p>
                            </div>
                        </div>
                        @if($rawLokasi)
                        <div class="flex items-start space-x-3 bg-white rounded-lg p-3 shadow-sm">
                            <i class="fas fa-map-marker-alt text-green-600 mt-1 flex-shrink-0"></i>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs text-gray-500">Lokasi Kegiatan</p>
                                @if($isMapsUrl)
                                    <a href="#section-peta-lokasi" class="font-semibold text-blue-600 hover:underline inline-flex items-center text-xs sm:text-sm mt-0.5" title="{{ $cleanLocationText }}">
                                        <i class="fas fa-map-pin text-red-500 mr-1 flex-shrink-0"></i>
                                        <span class="truncate max-w-[200px]">{{ $cleanLocationText }}</span>
                                    </a>
                                @else
                                    <p class="font-semibold text-gray-800 text-sm mt-0.5 truncate">{{ $rawLokasi }}</p>
                                @endif
                            </div>
                        </div>
                        @endif
                        <div class="flex items-start space-x-3 bg-white rounded-lg p-3 shadow-sm">
                            <i class="fas fa-calendar text-purple-600 mt-1"></i>
                            <div>
                                <p class="text-xs text-gray-500">Tanggal Dibuat</p>
                                <p class="font-semibold text-gray-800">{{ $activity->created_at->format('d M Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Activities -->
    @if(isset($relatedActivities) && $relatedActivities->count() > 0)
    <section class="mt-16">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-3xl font-bold text-gray-900">Aktivitas Terkait Lainnya</h2>
            <a href="/community" class="text-blue-700 hover:text-blue-800 font-medium flex items-center space-x-2">
                <span>Lihat Semua</span>
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($relatedActivities as $related)
            <a href="/community-detail/{{ $related->id }}" class="block group">
                <article class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 relative">
                    <div class="relative overflow-hidden">
                        @if($related->banner)
                            <img src="{{ asset('fotoaktivitas/'.$related->banner) }}" alt="{{ $related->nama }}" class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-500" />
                        @else
                            <img src="{{ asset('assets/komunitas.png') }}" alt="{{ $related->nama }}" class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-500" />
                        @endif
                        <div class="absolute top-3 left-3">
                            @if($related->activityType)
                                @if($related->activityType->name == 'open-class')
                                    <span class="badge-custom bg-green-500 text-white">{{ $related->activityType->title }}</span>
                                @elseif($related->activityType->name == 'klub')
                                    <span class="badge-custom bg-yellow-600 text-white">{{ $related->activityType->title }}</span>
                                @elseif($related->activityType->name == 'event')
                                    <span class="badge-custom bg-blue-600 text-white">{{ $related->activityType->title }}</span>
                                @endif
                            @endif
                        </div>
                    </div>
                    <div class="p-5">
                        <h3 class="font-bold text-lg mb-2 text-gray-900 line-clamp-2 group-hover:text-blue-700 transition">{{ $related->nama }}</h3>
                        @if($related->lokasi)
                            <p class="text-sm text-gray-500 mb-2 flex items-center">
                                <i class="fas fa-map-marker-alt mr-2 text-gray-400"></i>
                                <span class="line-clamp-1">{{ $related->lokasi }}</span>
                            </p>
                        @endif
                        <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-100">
                            <span class="text-xs text-gray-500">
                                @if($related->biaya_bergabung == 'gratis')
                                    <i class="fas fa-check-circle text-green-500 mr-1"></i>Gratis
                                @else
                                    <i class="fas fa-dollar-sign text-yellow-500 mr-1"></i>Berbayar
                                @endif
                            </span>
                            <span class="text-blue-600 text-sm font-medium group-hover:text-blue-700">
                                Lihat Detail <i class="fas fa-arrow-right ml-1"></i>
                            </span>
                        </div>
                    </div>
                </article>
            </a>
            @endforeach
        </div>
    </section>
    @endif
</main>

@auth
@php
    $isMembershipActModal = ($activity->jenis === 'membership') || ($activity->activityType && ($activity->activityType->name === 'klub' || $activity->activityType->name === 'membership'));
@endphp
<!-- Modal Bergabung / Beli Membership -->
<div id="joinModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="relative bg-white rounded-2xl shadow-2xl max-w-lg w-full p-6 sm:p-8 overflow-hidden border border-gray-100">
        
        <div class="flex items-center justify-between border-b pb-4 mb-4">
            <div class="flex items-center space-x-3">
                @if($isMembershipActModal)
                    <div class="w-10 h-10 bg-amber-500 text-slate-950 rounded-xl flex items-center justify-center text-lg font-black shadow">
                        <i class="fas fa-crown"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-gray-900">Beli VIP Membership</h3>
                        <p class="text-xs text-amber-700 font-semibold">{{ $activity->nama }}</p>
                    </div>
                @else
                    <div class="w-10 h-10 bg-blue-600 text-white rounded-xl flex items-center justify-center text-lg font-bold shadow">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Bergabung Aktivitas</h3>
                        <p class="text-xs text-gray-500 font-medium">{{ $activity->nama }}</p>
                    </div>
                @endif
            </div>
            <button onclick="closeJoinModal()" class="text-gray-400 hover:text-gray-600 p-2 rounded-lg hover:bg-gray-100 transition">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        <!-- Visual Step Wizard -->
        @if($activity->biaya_bergabung === 'berbayar')
        <div class="grid grid-cols-3 gap-2 text-center text-[11px] font-bold mb-5 bg-gray-50 p-2 rounded-xl border border-gray-200">
            <div class="text-blue-600 flex items-center justify-center">
                <span class="w-5 h-5 bg-blue-600 text-white rounded-full inline-flex items-center justify-center text-[10px] mr-1">1</span> Data
            </div>
            <div class="text-blue-600 flex items-center justify-center">
                <span class="w-5 h-5 bg-blue-600 text-white rounded-full inline-flex items-center justify-center text-[10px] mr-1">2</span> Bank
            </div>
            <div class="text-blue-600 flex items-center justify-center">
                <span class="w-5 h-5 bg-blue-600 text-white rounded-full inline-flex items-center justify-center text-[10px] mr-1">3</span> Bukti Bayar
            </div>
        </div>
        @endif

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg text-sm">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('user.community.join', $activity->id) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Peserta Membership <span class="text-red-500">*</span></label>
                <input type="text" name="nama_peserta" class="w-full rounded-xl border border-gray-300 p-3 text-sm focus:border-blue-500 focus:ring-blue-500 font-semibold" placeholder="Masukkan nama lengkap" required value="{{ old('nama_peserta', Auth::user()->name ?? '') }}">
            </div>

            @if($activity->biaya_bergabung === 'berbayar')
            <!-- Total Pembayaran -->
            @if($activity->harga)
            <div class="mb-4 p-4 bg-gradient-to-r from-amber-500 to-yellow-500 rounded-xl text-slate-950 flex items-center justify-between shadow-md">
                <div>
                    <p class="text-[11px] font-black uppercase tracking-wider opacity-80">Total Tagihan Membership</p>
                    <p class="text-2xl font-black">Rp {{ number_format($activity->harga, 0, ',', '.') }}</p>
                </div>
                <i class="fas fa-crown text-4xl opacity-30"></i>
            </div>
            @endif

            <!-- Pilih Metode Pembayaran (Bank / E-Wallet) -->
            @php
                $activeSettings = isset($paymentSettings) && $paymentSettings->count() > 0 
                    ? $paymentSettings 
                    : \App\Models\PaymentSetting::where('is_active', true)->get();
                $firstSetting = $activeSettings->first();

                $ownerBank = null;
                if (isset($activity->pendaftaran) && $activity->pendaftaran && $activity->pendaftaran->nomor_rekening) {
                    $ownerBank = [
                        'bank_name' => $activity->pendaftaran->nama_bank ?? 'Bank Pemilik Venue',
                        'account_number' => $activity->pendaftaran->nomor_rekening,
                        'account_holder' => $activity->pendaftaran->nama_pemilik_rekening ?? $activity->pendaftaran->nama_venue,
                    ];
                } elseif (isset($activity->clinic) && $activity->clinic && $activity->clinic->nomor_rekening) {
                    $ownerBank = [
                        'bank_name' => $activity->clinic->nama_bank ?? 'Bank Klinik',
                        'account_number' => $activity->clinic->nomor_rekening,
                        'account_holder' => $activity->clinic->nama_pemilik_rekening ?? $activity->clinic->nama,
                    ];
                }
            @endphp

            @if($ownerBank)
            <!-- Opsi Transfer Langsung Rekening Pemilik Venue/Klinik -->
            <div class="mb-4 p-3 bg-amber-500/10 border border-amber-500/30 rounded-xl space-y-2">
                <div class="flex items-center justify-between text-xs font-bold text-amber-400">
                    <span><i class="fas fa-university mr-1"></i> Rekening Langsung Pemilik Venue / Klinik:</span>
                    <span class="bg-amber-400 text-slate-950 text-[10px] px-2 py-0.5 rounded font-black uppercase">REKENING MITRA</span>
                </div>
                <div class="bg-slate-900 p-3 rounded-lg border border-slate-700">
                    <div class="flex items-center justify-between text-xs text-slate-400 mb-1">
                        <span>Bank: <strong class="text-amber-400">{{ $ownerBank['bank_name'] }}</strong></span>
                        <span>Atas Nama: <strong class="text-slate-200">{{ $ownerBank['account_holder'] }}</strong></span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="font-mono font-bold text-lg text-amber-300 tracking-wider">{{ $ownerBank['account_number'] }}</span>
                        <button type="button" onclick="navigator.clipboard.writeText('{{ $ownerBank['account_number'] }}'); alert('Nomor rekening pemilik disalin: {{ $ownerBank['account_number'] }}');" class="text-xs bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold px-3 py-1 rounded-lg transition">
                            <i class="fas fa-copy mr-1"></i>Salin
                        </button>
                    </div>
                </div>
                <p class="text-[11px] text-gray-500">Transfer langsung ke rekening pemilik venue di atas atau pilih Virtual Account Platform di bawah:</p>
            </div>
            @endif

            <div class="mb-4 space-y-2.5">
                <label class="block text-sm font-semibold text-gray-700">
                    Pilih Bank Virtual Account Transfer <span class="text-red-500">*</span>
                </label>
                
                <div class="grid grid-cols-3 gap-2" id="memberBankOptions">
                    @foreach($activeSettings as $idx => $ps)
                    <label class="member-bank-card border-2 {{ $idx == 0 ? 'border-amber-500 bg-amber-50/50' : 'border-gray-200' }} rounded-xl p-2 text-center cursor-pointer flex flex-col items-center justify-center transition hover:border-amber-500">
                        <input type="radio" name="bank_code" value="{{ $ps->bank_code }}" class="hidden" {{ $idx == 0 ? 'checked' : '' }} onchange="updateMemberVA('{{ addslashes($ps->bank_name) }}', '{{ addslashes($ps->account_number) }}', '{{ addslashes($ps->account_holder) }}')" />
                        <i class="{{ $ps->category == 'bank' ? 'fas fa-university text-amber-600' : 'fas fa-wallet text-green-500' }} text-lg mb-1"></i>
                        <span class="text-xs font-bold text-gray-800">{{ $ps->bank_code }}</span>
                    </label>
                    @endforeach
                </div>

                <!-- Info No. Virtual Account Transfer -->
                <div class="bg-slate-900 text-white rounded-xl p-4 border border-slate-700 space-y-1.5 shadow-inner">
                    <div class="flex items-center justify-between text-xs text-slate-400">
                        <span>Nomor Rekening / Virtual Account Platform:</span>
                        <span id="memberBankLabel" class="font-bold text-amber-400">{{ $firstSetting ? $firstSetting->bank_name : 'BCA' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span id="memberVaDisplay" class="font-mono font-bold text-lg text-amber-300 tracking-wider">{{ $firstSetting ? $firstSetting->account_number : '88008819203847' }}</span>
                        <button type="button" onclick="copyMemberVA()" class="text-xs bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold px-3 py-1 rounded-lg transition">
                            <i class="fas fa-copy mr-1"></i>Salin
                        </button>
                    </div>
                    <p class="text-[11px] text-slate-400 pt-1 border-t border-slate-800">Atas Nama: <strong id="memberHolderDisplay" class="text-slate-200">{{ $firstSetting ? $firstSetting->account_holder : 'PT OlgaSehat Indonesia' }}</strong></p>
                </div>
            </div>

            <!-- Upload Bukti Pembayaran -->
            <div class="mb-5">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Upload Bukti Transfer Pembayaran <span class="text-red-500">*</span>
                </label>
                <div class="border-2 border-dashed border-amber-300 rounded-xl p-4 text-center bg-amber-50/40 hover:bg-amber-50 transition">
                    <input type="file" name="bukti_pembayaran" id="bukti_pembayaran" class="hidden" accept="image/*" required>
                    <label for="bukti_pembayaran" class="cursor-pointer block">
                        <i class="fas fa-cloud-upload-alt text-3xl text-amber-500 mb-1"></i>
                        <p class="text-xs font-bold text-gray-800">Klik di sini untuk upload bukti transfer</p>
                        <p class="text-[11px] text-gray-500 mt-0.5">Format: JPG, PNG, GIF (Maksimal 2MB)</p>
                    </label>
                </div>
                <div id="preview-container" class="mt-3 hidden text-center">
                    <p class="text-xs text-gray-500 font-semibold mb-1">Preview Bukti Transfer:</p>
                    <img id="preview-image" src="" alt="Preview" class="max-w-full h-32 object-contain rounded-xl border border-gray-300 mx-auto shadow-md">
                </div>
            </div>
            @endif

            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeJoinModal()" class="flex-1 border border-gray-300 text-gray-700 py-3 rounded-xl font-bold hover:bg-gray-50 transition text-sm">
                    Batal
                </button>
                @if($isMembershipActModal)
                    <button type="submit" class="flex-1 bg-gradient-to-r from-amber-500 to-yellow-500 hover:from-amber-600 hover:to-yellow-600 text-slate-950 font-black py-3 rounded-xl shadow-lg transition text-sm flex items-center justify-center">
                        <i class="fas fa-crown mr-1.5 text-base"></i> Beli Membership
                    </button>
                @else
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl shadow-lg transition text-sm flex items-center justify-center">
                        <i class="fas fa-check mr-1.5"></i> Konfirmasi Daftar
                    </button>
                @endif
            </div>
        </form>
    </div>
</div>

<script>
    function openJoinModal() {
        const modal = document.getElementById('joinModal');
        if (modal) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeJoinModal() {
        const modal = document.getElementById('joinModal');
        if (modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }
    }

    // Preview image
    document.getElementById('bukti_pembayaran')?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview-image').src = e.target.result;
                document.getElementById('preview-container').classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    });

    function updateMemberVA(bankName, accountNumber, accountHolder) {
        const cards = document.querySelectorAll('.member-bank-card');
        cards.forEach(card => {
            card.classList.remove('border-blue-600', 'bg-blue-50/50');
            card.classList.add('border-gray-200');
        });

        const targetRadio = document.querySelector(`input[name="bank_code"][value="${bankName}"]`) || (event ? event.target : null);
        if (targetRadio) {
            targetRadio.checked = true;
            const parentCard = targetRadio.closest('.member-bank-card');
            if (parentCard) {
                parentCard.classList.remove('border-gray-200');
                parentCard.classList.add('border-blue-600', 'bg-blue-50/50');
            }
        }

        const label = document.getElementById('memberBankLabel');
        if (label) label.textContent = bankName;
        
        const vaDisplay = document.getElementById('memberVaDisplay');
        if (vaDisplay) vaDisplay.textContent = accountNumber;

        const holderDisplay = document.getElementById('memberHolderDisplay');
        if (holderDisplay) holderDisplay.textContent = accountHolder;
    }

    function copyMemberVA() {
        const vaText = document.getElementById('memberVaDisplay')?.textContent;
        if (vaText) {
            navigator.clipboard.writeText(vaText).then(() => {
                alert('Nomor Virtual Account/Rekening berhasil disalin: ' + vaText);
            });
        }
    }
</script>
@endauth

@endsection
