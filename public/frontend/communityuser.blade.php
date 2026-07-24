@extends('user.layout.frontenduser')

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
            <a href="/buat-aktivitas" class="bg-transparent border-2 border-white text-white py-3 px-8 rounded-full font-semibold text-lg shadow-lg hover:bg-white hover:text-blue-700 transition">
                Buat Aktivitas Baru
            </a>
        </div>
    </div>
</section>

  <main>
    <section class="bg-gray-50 py-10" id="filter-cepat">
        <div class="container mx-auto px-6">
            <h2 class="text-2xl font-bold text-center mb-6 text-gray-800">Cari Berdasarkan Tipe Aktivitas</h2>
            
            <div class="flex flex-wrap justify-center gap-4">
                @foreach($activityTypes as $type)
                <a href="/communityuser?type={{ $type->name }}" class="flex flex-col items-center p-4 w-32 rounded-lg bg-white shadow-md hover:shadow-lg transition">
                    <i class="{{ $type->icon }} text-3xl text-blue-600 mb-2"></i>
                    <span class="text-sm font-medium text-center">{{ $type->title }}</span>
                </a>
                @endforeach
            </div>
            
            <form action="/communityuser" method="GET" class="max-w-xl mx-auto mt-8 flex space-x-2">
                <input 
                    type="text" 
                    name="search"
                    placeholder="Cari Kota atau Olahraga..." 
                    value="{{ request('search') }}"
                    class="flex-grow p-3 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                />
                <button type="submit" class="bg-blue-700 text-white p-3 rounded-md font-semibold hover:bg-blue-800 transition">
                    Cari
                </button>
            </form>
        </div>
    </section>

    <section class="container mx-auto px-6 py-12" id="daftar-komunitas">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">
            Nikmati Komunitas & Aktivitas
        </h2>
        
        @if(isset($activities) && $activities->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($activities as $activity)
            <a href="/communityuser_detail/{{ $activity->id }}" class="block group">
                <article class="bg-white rounded-lg shadow-lg overflow-hidden border-2 hover:shadow-xl transition relative">
                    @if($activity->banner)
                        <img src="{{ asset('fotoaktivitas/'.$activity->banner) }}" alt="{{ $activity->nama }}" class="w-full h-40 object-cover group-hover:scale-105 transition-transform" />
                    @else
                        <img src="{{ asset('assets/komunitas.png') }}" alt="{{ $activity->nama }}" class="w-full h-40 object-cover group-hover:scale-105 transition-transform" />
                    @endif
                    <div class="p-4">
                        @if($activity->activityType)
                            @if($activity->activityType->name == 'open-class')
                                <span class="inline-block bg-green-500 text-white text-xs font-bold px-2 py-0.5 rounded-full mb-2">{{ $activity->activityType->title }}</span>
                            @elseif($activity->activityType->name == 'klub')
                                <span class="inline-block bg-yellow-600 text-white text-xs font-bold px-2 py-0.5 rounded-full mb-2">{{ $activity->activityType->title }}</span>
                            @elseif($activity->activityType->name == 'event')
                                <span class="inline-block bg-blue-600 text-white text-xs font-bold px-2 py-0.5 rounded-full mb-2">{{ $activity->activityType->title }}</span>
                            @endif
                        @else
                            <span class="inline-block bg-gray-500 text-white text-xs font-bold px-2 py-0.5 rounded-full mb-2">{{ ucfirst($activity->jenis) }}</span>
                        @endif
                        <h3 class="font-bold text-md mb-1 text-gray-900">{{ $activity->nama }}</h3>
                        <p class="text-xs text-gray-500 mb-2">
                            @if($activity->user)
                                {{ $activity->user->name }}
                            @elseif($activity->pemilik)
                                {{ $activity->pemilik->name }}
                            @endif
                        </p>
                        <p class="text-xs text-gray-700 font-semibold flex items-center">
                            <i class="fas fa-wallet mr-2"></i> 
                            @if($activity->biaya_bergabung == 'gratis')
                                Bergabung Gratis
                            @else
                                Berbayar
                            @endif
                        </p>
                        @if($activity->lokasi)
                            <p class="text-xs text-gray-500 mt-1 flex items-center">
                                <i class="fas fa-map-marker-alt mr-2"></i> {{ $activity->lokasi }}
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
        <div class="text-center py-12">
            <i class="fas fa-inbox text-gray-300 text-6xl mb-4"></i>
            <p class="text-gray-500 text-lg">Belum ada aktivitas yang tersedia.</p>
            <p class="text-gray-400 text-sm mt-2">Aktivitas akan muncul setelah disetujui oleh admin.</p>
        </div>
        @endif
    </section>
</main>

  @endsection
