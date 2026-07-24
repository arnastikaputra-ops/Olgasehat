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
                    <h1 class="text-3xl md:text-4xl font-bold text-white drop-shadow-lg">{{ $activity->nama }}</h1>
                </div>
            </div>
            
            <!-- Info Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mb-6">
                <div class="info-card bg-white rounded-xl p-4 shadow-md border border-gray-100">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-trophy text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Kategori</p>
                            <p class="font-semibold text-gray-800">{{ $activity->kategori }}</p>
                        </div>
                    </div>
                </div>
                
                @if($activity->lokasi)
                <div class="info-card bg-white rounded-xl p-4 shadow-md border border-gray-100">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-map-marker-alt text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Lokasi</p>
                            <p class="font-semibold text-gray-800">{{ $activity->lokasi }}</p>
                        </div>
                    </div>
                </div>
                @endif
                
                <div class="info-card bg-white rounded-xl p-4 shadow-md border border-gray-100">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
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
                        <div class="inline-block bg-yellow-500 text-white px-6 py-3 rounded-full mb-3">
                            <p class="text-3xl font-extrabold mb-1">Berbayar</p>
                            <p class="text-sm opacity-90">
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
                        <div class="w-full bg-green-500 text-white py-4 rounded-xl font-bold text-lg mb-6 text-center">
                            <i class="fas fa-check-circle mr-2"></i> ANDA SUDAH TERDAFTAR
                        </div>
                        @if($isJoined)
                            <div class="bg-white rounded-lg p-4 mb-6 border border-gray-200">
                                <p class="text-sm text-gray-600 mb-2">Status Pendaftaran:</p>
                                @if($isJoined->status === 'approved')
                                    <span class="bg-green-100 text-green-700 text-xs font-semibold px-3 py-1 rounded-full">
                                        <i class="fas fa-check-circle"></i> Disetujui
                                    </span>
                                @elseif($isJoined->status === 'pending')
                                    <span class="bg-yellow-100 text-yellow-700 text-xs font-semibold px-3 py-1 rounded-full">
                                        <i class="fas fa-clock"></i> Menunggu Verifikasi
                                    </span>
                                @else
                                    <span class="bg-red-100 text-red-700 text-xs font-semibold px-3 py-1 rounded-full">
                                        <i class="fas fa-times-circle"></i> Ditolak
                                    </span>
                                @endif
                                <p class="text-sm text-gray-700 mt-3">Nama Peserta: <strong>{{ $isJoined->nama_peserta }}</strong></p>
                            </div>
                        @endif
                    @else
                        <button onclick="document.getElementById('joinModal').classList.remove('hidden')" class="w-full bg-gradient-to-r from-blue-700 to-indigo-700 text-white py-4 rounded-xl font-bold text-lg hover:from-blue-600 hover:to-indigo-600 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105 mb-6">
                            <i class="fas fa-user-plus mr-2"></i> BERGABUNG SEKARANG
                        </button>
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
                        @if($activity->lokasi)
                        <div class="flex items-start space-x-3 bg-white rounded-lg p-3 shadow-sm">
                            <i class="fas fa-map-marker-alt text-green-600 mt-1"></i>
                            <div>
                                <p class="text-xs text-gray-500">Lokasi Kegiatan</p>
                                <p class="font-semibold text-gray-800">{{ $activity->lokasi }}</p>
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

{{-- Modal Form Bergabung Aktivitas --}}
@auth
@php
    $isJoined = \App\Models\ActivityParticipant::where('activity_id', $activity->id)
        ->where('user_id', auth()->id())
        ->first();
@endphp
@if(!$isJoined)
<div id="joinModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 relative">
        <button onclick="document.getElementById('joinModal').classList.add('hidden')" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
            <i class="fas fa-times text-xl"></i>
        </button>

        <h3 class="text-2xl font-bold text-gray-900 mb-4">Bergabung dengan {{ $activity->nama }}</h3>

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('user.community.join', $activity->id) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Peserta <span class="text-red-500">*</span></label>
                <input type="text" name="nama_peserta" class="w-full rounded-lg border border-gray-300 p-3 focus:border-blue-500 focus:ring-blue-500" placeholder="Masukkan nama lengkap" required value="{{ old('nama_peserta') }}">
            </div>

            @if($activity->biaya_bergabung === 'berbayar')
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Upload Bukti Pembayaran <span class="text-red-500">*</span>
                </label>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center">
                    <input type="file" name="bukti_pembayaran" id="bukti_pembayaran" class="hidden" accept="image/*" required>
                    <label for="bukti_pembayaran" class="cursor-pointer">
                        <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                        <p class="text-sm text-gray-600">Klik untuk upload bukti pembayaran</p>
                        <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, GIF. Maksimal 2MB</p>
                    </label>
                </div>
                <div id="preview-container" class="mt-3 hidden">
                    <img id="preview-image" src="" alt="Preview" class="max-w-full h-32 object-cover rounded-lg">
                </div>
                @if($activity->harga)
                <p class="text-sm text-gray-600 mt-2">
                    <i class="fas fa-info-circle mr-1"></i>
                    Total Pembayaran: <strong>Rp {{ number_format($activity->harga, 0, ',', '.') }}</strong>
                </p>
                @endif
            </div>
            @endif

            <div class="flex gap-3">
                <button type="button" onclick="document.getElementById('joinModal').classList.add('hidden')" class="flex-1 border border-gray-300 text-gray-700 py-3 rounded-xl font-semibold hover:bg-gray-50 transition">
                    Batal
                </button>
                <button type="submit" class="flex-1 bg-blue-600 text-white py-3 rounded-xl font-semibold hover:bg-blue-700 transition">
                    <i class="fas fa-check mr-2"></i> Daftar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
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
</script>
@endif
@endauth

@endsection
