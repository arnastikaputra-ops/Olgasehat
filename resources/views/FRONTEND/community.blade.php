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

    <section class="container mx-auto px-6 py-12" id="daftar-komunitas">
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
                <article class="bg-white rounded-xl shadow-md overflow-hidden border {{ $isMembership ? 'border-amber-300 ring-1 ring-amber-200' : 'border-gray-200' }} hover:shadow-2xl transition duration-300 relative">
                    <div class="relative">
                        @if($activity->banner)
                            <img src="{{ asset('fotoaktivitas/'.$activity->banner) }}" alt="{{ $activity->nama }}" class="w-full h-44 object-cover group-hover:scale-105 transition-transform duration-300" />
                        @else
                            <img src="{{ asset('assets/komunitas.png') }}" alt="{{ $activity->nama }}" class="w-full h-44 object-cover group-hover:scale-105 transition-transform duration-300" />
                        @endif

                        <div class="absolute top-3 left-3">
                            @if($isMembership)
                                <span class="inline-flex items-center bg-amber-500 text-slate-900 text-xs font-black px-2.5 py-1 rounded-full shadow-md"><i class="fas fa-crown mr-1"></i> Membership</span>
                            @elseif($activity->activityType && $activity->activityType->name === 'open-class')
                                <span class="inline-flex items-center bg-green-500 text-white text-xs font-bold px-2.5 py-1 rounded-full shadow-md"><i class="fas fa-users mr-1"></i> Komunitas</span>
                            @elseif($activity->activityType && $activity->activityType->name === 'event')
                                <span class="inline-flex items-center bg-blue-600 text-white text-xs font-bold px-2.5 py-1 rounded-full shadow-md"><i class="fas fa-calendar-alt mr-1"></i> Event</span>
                            @else
                                <span class="inline-flex items-center bg-gray-600 text-white text-xs font-bold px-2.5 py-1 rounded-full shadow-md">{{ ucfirst($activity->jenis) }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="p-5">
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
                        
                        <div class="pt-2 border-t border-gray-100 flex items-center justify-between">
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
                                <span class="text-xs font-bold text-amber-600 bg-amber-50 px-2 py-1 rounded-md border border-amber-200">
                                    Beli Membership <i class="fas fa-arrow-right ml-0.5"></i>
                                </span>
                            @else
                                <span class="text-xs font-semibold text-blue-600 group-hover:underline">
                                    Lihat Detail <i class="fas fa-arrow-right ml-0.5"></i>
                                </span>
                            @endif
                        </div>
                        @if($activity->lokasi)
                            <p class="text-xs text-gray-500 mt-2 flex items-center">
                                <i class="fas fa-map-marker-alt mr-1.5 text-red-400"></i> {{ $activity->lokasi }}
                            </p>
                        @endif
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