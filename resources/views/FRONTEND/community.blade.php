@extends('layouts.app')

@section('content')

  <section 
    class="bg-[url('assets/blue-banner.png')] bg-no-repeat text-white relative overflow-hidden flex items-center justify-center 
           min-h-[350px] sm:h-[300px] mt-16" 
    style="background-size: 1910px 400px;"
>
    <div class="container mx-auto px-6 text-center w-full flex flex-col items-center justify-center py-8">
        
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold mb-3">
            Komunitas & Aktivitas Olga Sehat
        </h1>
        
        <p class="text-base sm:text-lg mb-6 max-w-2xl mx-auto opacity-90">
            Temukan klub olahraga, kelas terbuka (Open Class), atau lawan sparring. Mulai perjalanan #HidupLebihAktif Anda hari ini—semua GRATIS diakses!
        </p>
        
        <div class="flex flex-col sm:flex-row justify-center space-y-3 sm:space-y-0 sm:space-x-4 w-full sm:w-auto">
            <a href="#daftar-komunitas" class="bg-white text-blue-700 py-3 px-8 rounded-full font-semibold text-lg shadow-lg hover:bg-gray-100 transition">
                Eksplor Aktivitas
            </a>
            @auth
                <a href="{{ route('activities.create') }}" class="bg-transparent border-2 border-white text-white py-3 px-8 rounded-full font-semibold text-lg shadow-lg hover:bg-white hover:text-blue-700 transition">
                    Buat Aktivitas Baru
                </a>
            @else
                <a href="/loginuser" class="bg-transparent border-2 border-white text-white py-3 px-8 rounded-full font-semibold text-lg shadow-lg hover:bg-white hover:text-blue-700 transition">
                    Buat Aktivitas Baru
                </a>
            @endauth
        </div>
    </div>
</section>

  <main>
    <section class="bg-gray-50 py-10" id="filter-cepat">
        <div class="container mx-auto px-6">
            <h2 class="text-2xl font-bold text-center mb-6 text-gray-800">Cari Berdasarkan Tipe Aktivitas</h2>
            
            <div class="flex flex-wrap justify-center items-center gap-4">
                @php
                    $currentType = request('type');
                @endphp

                <a href="/community" 
                   class="flex flex-col items-center p-4 w-32 rounded-xl transition-all duration-300 {{ !$currentType ? 'bg-blue-600 text-white shadow-lg ring-4 ring-blue-200 transform scale-105 font-bold' : 'bg-white text-gray-700 shadow-md hover:shadow-lg' }}">
                    <i class="fas fa-th-large text-3xl {{ !$currentType ? 'text-white' : 'text-blue-600' }} mb-2"></i>
                    <span class="text-sm text-center">Semua</span>
                </a>

                @foreach($activityTypes as $type)
                @php
                    $isActive = ($currentType === $type->name) || 
                                ($currentType === 'membership' && $type->name === 'klub') || 
                                ($currentType === 'komunitas' && $type->name === 'open-class');
                @endphp
                <a href="/community?type={{ $type->name }}" 
                   class="flex flex-col items-center p-4 w-32 rounded-xl transition-all duration-300 {{ $isActive ? 'bg-blue-600 text-white shadow-lg ring-4 ring-blue-200 transform scale-105 font-bold' : 'bg-white text-gray-700 shadow-md hover:shadow-lg' }}">
                    <i class="{{ $type->icon }} text-3xl {{ $isActive ? 'text-white' : 'text-blue-600' }} mb-2"></i>
                    <span class="text-sm text-center">{{ $type->title }}</span>
                </a>
                @endforeach
            </div>
            
            <form action="/community" method="GET" class="max-w-xl mx-auto mt-8 flex space-x-2">
                @if(request('type'))
                    <input type="hidden" name="type" value="{{ request('type') }}">
                @endif
                <input 
                    type="text" 
                    name="search"
                    placeholder="Cari Kota atau Olahraga..." 
                    value="{{ request('search') }}"
                    class="flex-grow p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                />
                <button type="submit" class="bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-800 transition shadow-md">
                    Cari
                </button>
            </form>
        </div>
    </section>

    <!-- Section Penjelasan Komunitas & Aktivitas -->
    <section class="bg-white py-8 border-b border-gray-100">
        <div class="container mx-auto px-6">
            <div class="bg-gradient-to-r from-blue-50 via-indigo-50 to-sky-50 rounded-2xl p-6 sm:p-8 border border-blue-100 shadow-sm">
                <div class="flex items-center space-x-3 mb-4">
                    <span class="bg-blue-600 text-white w-10 h-10 rounded-xl flex items-center justify-center font-bold text-lg shadow-md">
                        <i class="fas fa-users"></i>
                    </span>
                    <div>
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Apa Itu Komunitas & Fitur Mabar OlgaSehat?</h2>
                        <p class="text-xs sm:text-sm text-gray-600">Panduan mudah memahami alur dan manfaat fitur Komunitas bagi Pengguna & Pemilik Venue</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                    <!-- Kartu Untuk Pengguna / User -->
                    <div class="bg-white rounded-xl p-5 border border-blue-100 shadow-xs hover:shadow-md transition">
                        <div class="flex items-center space-x-2 text-blue-700 font-bold mb-2">
                            <i class="fas fa-user-friends text-lg"></i>
                            <h3 class="text-base">Untuk Pengguna (Olahragawan)</h3>
                        </div>
                        <p class="text-xs sm:text-sm text-gray-600 leading-relaxed mb-3">
                            Tempat mencari teman **Main Bareng (Mabar)**, sparing lawan tanding, atau bergabung ke kelas latihan olahraga terbuka. Kamu bisa langsung bergabung tanpa perlu memiliki tim sendiri!
                        </p>
                        <ul class="text-xs text-gray-500 space-y-1.5 list-disc list-inside">
                            <li><strong>Open Class (Mabar):</strong> Gabung latihan bersama per-sesi jam.</li>
                            <li><strong>VIP Membership:</strong> Langganan member venue untuk dapat potongan sewa.</li>
                            <li><strong>Event Olahraga:</strong> Ikut kompetisi & turnamen resmi.</li>
                        </ul>
                    </div>

                    <!-- Kartu Untuk Pemilik Venue (Mitra) -->
                    <div class="bg-white rounded-xl p-5 border border-indigo-100 shadow-xs hover:shadow-md transition">
                        <div class="flex items-center space-x-2 text-indigo-700 font-bold mb-2">
                            <i class="fas fa-store text-lg"></i>
                            <h3 class="text-base">Untuk Pemilik Venue (Mitra Lapangan)</h3>
                        </div>
                        <p class="text-xs sm:text-sm text-gray-600 leading-relaxed mb-3">
                            Wadah efektif untuk **meramaikan jadwal lapangan yang sepi (Off-Peak)** dengan membuat sesi Mabar terbuka atau menjual paket keanggotaan VIP bulanan.
                        </p>
                        <ul class="text-xs text-gray-500 space-y-1.5 list-disc list-inside">
                            <li><strong>Meningkatkan Okupansi:</strong> Mengisi jam lapangan kosong per-slot pemain.</li>
                            <li><strong>Pendapatan Otomatis:</strong> Peserta patungan sewa langsung via sistem.</li>
                            <li><strong>Member Loyal:</strong> Mengikat komunitas pemain rutin di venue Anda.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="container mx-auto px-6 py-12" id="daftar-komunitas">
        @if(request('type') === 'klub' || request('type') === 'membership')
        <!-- VIP Membership Spotlight Banner -->
        <div class="mb-10 bg-gradient-to-r from-slate-950 via-amber-950 to-slate-900 rounded-3xl p-6 sm:p-10 text-white shadow-2xl relative overflow-hidden border border-amber-500/30">
            <div class="absolute top-0 right-0 w-96 h-96 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="relative z-10 grid grid-cols-1 lg:grid-cols-3 gap-6 items-center">
                <div class="lg:col-span-2">
                    <span class="inline-flex items-center bg-amber-500/20 text-amber-300 border border-amber-500/40 text-xs font-black px-3.5 py-1.5 rounded-full mb-3 shadow-inner">
                        <i class="fas fa-crown mr-1.5 text-amber-400"></i> PROGRAM KEANGGOTAAN OLGASEHAT
                    </span>
                    <h2 class="text-2xl sm:text-4xl font-black text-white mb-3 tracking-tight">VIP Membership & Klub Olahraga</h2>
                    <p class="text-sm sm:text-base text-amber-100/90 leading-relaxed mb-6">
                        Bergabung ke dalam keanggotaan VIP untuk menikmati diskon otomatis setiap kali booking lapangan venue, akses jadwal latihan bareng, dan prioritas layanan kesehatan di platform.
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div class="flex items-center space-x-2 bg-white/10 backdrop-blur-sm p-3 rounded-xl border border-white/10">
                            <i class="fas fa-percent text-amber-400 text-lg"></i>
                            <span class="text-xs font-semibold text-white">Diskon Booking Lapangan 10%+</span>
                        </div>
                        <div class="flex items-center space-x-2 bg-white/10 backdrop-blur-sm p-3 rounded-xl border border-white/10">
                            <i class="fas fa-users text-amber-400 text-lg"></i>
                            <span class="text-xs font-semibold text-white">Sparing & Latihan Rutin</span>
                        </div>
                        <div class="flex items-center space-x-2 bg-white/10 backdrop-blur-sm p-3 rounded-xl border border-white/10">
                            <i class="fas fa-id-card text-amber-400 text-lg"></i>
                            <span class="text-xs font-semibold text-white">E-Card Status VIP Member</span>
                        </div>
                    </div>
                </div>
                <div class="hidden lg:flex justify-end">
                    <div class="w-48 h-48 rounded-2xl bg-gradient-to-br from-amber-400 to-amber-600 p-1 shadow-2xl transform rotate-3 hover:rotate-0 transition duration-300">
                        <div class="w-full h-full bg-slate-900 rounded-xl p-5 flex flex-col justify-between text-white border border-amber-400/50">
                            <div class="flex justify-between items-center">
                                <i class="fas fa-crown text-amber-400 text-2xl"></i>
                                <span class="text-[10px] font-black tracking-widest text-amber-300">VIP CARD</span>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 font-mono">OLGASEHAT MEMBER</p>
                                <p class="text-sm font-bold tracking-wider text-white">#VIP-ACCESS</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                @if(request('type') === 'klub' || request('type') === 'membership')
                    <i class="fas fa-crown text-amber-500 mr-3 text-2xl"></i> Paket Membership & Klub Olahraga
                @elseif(request('type') === 'open-class' || request('type') === 'komunitas')
                    <i class="fas fa-chalkboard-teacher text-blue-600 mr-3 text-2xl"></i> Komunitas & Open Class
                @elseif(request('type') === 'event')
                    <i class="fas fa-calendar-alt text-purple-600 mr-3 text-2xl"></i> Event & Turnamen Olahraga
                @else
                    <i class="fas fa-list-alt text-blue-600 mr-3 text-2xl"></i> Nikmati Komunitas & Aktivitas
                @endif
            </h2>

            @if(request('type') || request('search'))
                <a href="/community" class="inline-flex items-center text-sm font-semibold text-blue-600 hover:text-blue-800 bg-blue-50 px-3 py-1.5 rounded-lg border border-blue-200">
                    <i class="fas fa-times-circle mr-1.5"></i> Hapus Filter
                </a>
            @endif
        </div>
        
        @if(isset($activities) && $activities->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($activities as $activity)
            @php
                $isMembership = ($activity->jenis === 'membership') || ($activity->activityType && ($activity->activityType->name === 'klub' || $activity->activityType->name === 'membership'));
            @endphp
            <a href="/community-detail/{{ $activity->id }}" class="block group">
                <article class="bg-white rounded-2xl shadow-md overflow-hidden border {{ $isMembership ? 'border-amber-400 ring-2 ring-amber-200/60' : 'border-gray-200' }} hover:shadow-2xl transition duration-300 relative flex flex-col h-full">
                    <div class="relative">
                        @if($activity->banner)
                            <img src="{{ asset('fotoaktivitas/'.$activity->banner) }}" alt="{{ $activity->nama }}" class="w-full h-44 object-cover group-hover:scale-105 transition-transform duration-300" />
                        @else
                            <img src="{{ asset('assets/komunitas.png') }}" alt="{{ $activity->nama }}" class="w-full h-44 object-cover group-hover:scale-105 transition-transform duration-300" />
                        @endif

                        <div class="absolute top-3 left-3">
                            @if($isMembership)
                                <span class="inline-flex items-center bg-amber-500 text-slate-950 text-xs font-black px-3 py-1 rounded-full shadow-lg border border-amber-300"><i class="fas fa-crown mr-1"></i> VIP Membership</span>
                            @elseif($activity->activityType && $activity->activityType->name === 'open-class')
                                <span class="inline-flex items-center bg-green-500 text-white text-xs font-bold px-2.5 py-1 rounded-full shadow-md"><i class="fas fa-users mr-1"></i> Komunitas</span>
                            @elseif($activity->activityType && $activity->activityType->name === 'event')
                                <span class="inline-flex items-center bg-blue-600 text-white text-xs font-bold px-2.5 py-1 rounded-full shadow-md"><i class="fas fa-calendar-alt mr-1"></i> Event</span>
                            @else
                                <span class="inline-flex items-center bg-gray-600 text-white text-xs font-bold px-2.5 py-1 rounded-full shadow-md">{{ ucfirst($activity->jenis) }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="p-5 flex flex-col flex-grow justify-between">
                        <div>
                            <h3 class="font-bold text-base mb-1 text-gray-900 line-clamp-1 group-hover:text-blue-600 transition">{{ $activity->nama }}</h3>
                            <p class="text-xs text-gray-500 mb-3 flex items-center">
                                <i class="fas fa-user-circle mr-1.5 text-gray-400"></i>
                                @if($activity->user)
                                    {{ $activity->user->name }}
                                @elseif($activity->pemilik)
                                    {{ $activity->pemilik->name }}
                                @else
                                    Admin OlgaSehat
                                @endif
                            </p>
                        </div>
                        
                        <div>
                            <div class="pt-3 border-t border-gray-100 flex items-center justify-between">
                                <span class="text-xs font-bold {{ $activity->biaya_bergabung == 'gratis' ? 'text-green-600' : 'text-amber-600' }} flex items-center">
                                    <i class="fas fa-tag mr-1"></i> 
                                    @if($activity->biaya_bergabung == 'gratis')
                                        Gratis
                                    @else
                                        @if($activity->harga)
                                            Rp {{ number_format($activity->harga, 0, ',', '.') }}
                                        @else
                                            Berbayar
                                        @endif
                                    @endif
                                </span>

                                @if($isMembership)
                                    <span class="text-xs font-black text-amber-700 bg-amber-100 px-2.5 py-1 rounded-lg border border-amber-300 shadow-sm group-hover:bg-amber-500 group-hover:text-slate-950 transition">
                                        Beli Membership <i class="fas fa-arrow-right ml-0.5"></i>
                                    </span>
                                @else
                                    <span class="text-xs font-semibold text-blue-600 group-hover:underline">
                                        Lihat Detail <i class="fas fa-arrow-right ml-0.5"></i>
                                    </span>
                                @endif
                            </div>
                            @if($activity->lokasi)
                                <p class="text-xs text-gray-500 mt-2 flex items-center line-clamp-1">
                                    <i class="fas fa-map-marker-alt mr-1.5 text-red-400 flex-shrink-0"></i> {{ $activity->lokasi }}
                                </p>
                            @endif
                        </div>
                    </div>
                </article>
            </a>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $activities->links() }}
        </div>
        @else
        <div class="bg-white rounded-2xl p-12 text-center border-2 border-dashed border-gray-300">
            <i class="fas fa-crown text-gray-300 text-6xl mb-4"></i>
            <p class="text-gray-600 text-lg font-bold">Belum ada paket/aktivitas pada kategori ini.</p>
            <p class="text-gray-400 text-sm mt-1 mb-4">Temukan atau buat aktivitas baru untuk bergabung!</p>
            <a href="/community" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Semua Aktivitas
            </a>
        </div>
        @endif
    </section>
</main>

@endsection