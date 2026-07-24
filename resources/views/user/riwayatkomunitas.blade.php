@extends('user.layout.user')

@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<style>
    .community-card {
        @apply bg-white p-5 rounded-xl shadow-lg border border-gray-100 transition duration-300 hover:shadow-xl hover:-translate-y-1;
    }
</style>
@endpush

@section('content')

<main class="pt-20 min-h-screen pb-8 px-4 md:px-8 lg:px-10 bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8">

        <div class="lg:col-span-2 space-y-6">
            
            {{-- Header Konten --}}
            <div class="bg-white rounded-xl shadow-xl p-6 border-l-4 border-orange-500 flex justify-between items-center">
                <div>
                    <h2 class="font-extrabold text-2xl text-gray-900">Riwayat Aktivitas Saya</h2>
                    <p class="text-gray-500 mt-1">Aktivitas yang telah kamu buat, kelola, dan ikuti.</p>
                </div>
                {{-- Tombol Tambah Aktivitas di Header --}}
                <a href="/buat-aktivitas" class="bg-blue-600 text-white font-semibold px-4 py-2 rounded-lg shadow-md hover:bg-blue-700 transition duration-150 flex items-center text-sm md:text-base whitespace-nowrap">
                    <i class="fas fa-plus mr-2"></i> Tambah Aktivitas
                </a>
            </div>

            {{-- Notifikasi --}}
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Filter dan Search --}}
            <div class="bg-white rounded-xl shadow-md p-4">
                <form method="GET" action="{{ route('user.riwayat-komunitas') }}" class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <input type="text" 
                               name="search" 
                               placeholder="Cari aktivitas..." 
                               value="{{ request('search') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <select name="status" class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-search mr-2"></i> Cari
                    </button>
                </form>
            </div>
            
            {{-- Daftar Aktivitas yang Dibuat User --}}
            @if(isset($activities) && $activities->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($activities as $activity)
                <div class="community-card bg-white p-6 rounded-lg shadow-md">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center flex-1">
                            {{-- Ikon berdasarkan jenis --}}
                            <div class="w-12 h-12 rounded-full flex items-center justify-center mr-4 flex-shrink-0
                                @if($activity->jenis == 'komunitas') bg-blue-100 text-blue-600
                                @elseif($activity->jenis == 'membership') bg-amber-100 text-amber-600
                                @else bg-red-100 text-red-600
                                @endif">
                                @if($activity->jenis == 'komunitas')
                                    <i class="fas fa-users text-xl"></i>
                                @elseif($activity->jenis == 'membership')
                                    <i class="fas fa-credit-card text-xl"></i>
                                @else
                                    <i class="fas fa-calendar-alt text-xl"></i>
                                @endif
                            </div>
                            {{-- Info Dasar --}}
                            <div class="flex-1">
                                <p class="text-xl font-bold text-gray-900">{{ $activity->nama }}</p>
                                <p class="text-sm text-gray-500">{{ $activity->kategori }} | {{ $activity->lokasi ?? '-' }}</p>
                            </div>
                        </div>
                        {{-- Status Badge --}}
                        <div class="ml-2">
                            @if($activity->status == 'approved')
                                <span class="bg-green-100 text-green-700 text-xs font-semibold px-2 py-1 rounded-full">
                                    <i class="fas fa-check-circle"></i> Approved
                                </span>
                            @elseif($activity->status == 'pending')
                                <span class="bg-yellow-100 text-yellow-700 text-xs font-semibold px-2 py-1 rounded-full">
                                    <i class="fas fa-clock"></i> Pending
                                </span>
                            @else
                                <span class="bg-red-100 text-red-700 text-xs font-semibold px-2 py-1 rounded-full">
                                    <i class="fas fa-times-circle"></i> Rejected
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Banner Preview --}}
                    @if($activity->banner)
                    <div class="mb-4">
                        <img src="{{ asset('fotoaktivitas/'.$activity->banner) }}" 
                             alt="{{ $activity->nama }}" 
                             class="w-full h-32 object-cover rounded-lg">
                    </div>
                    @endif
                    
                    {{-- Deskripsi --}}
                    <p class="text-sm text-gray-600 mt-4 pt-3 border-t border-gray-100 line-clamp-2">
                        {{ Str::limit($activity->deskripsi, 100) }}
                    </p>

                    {{-- Info Tambahan --}}
                    <div class="mt-3 pt-3 border-t border-gray-100">
                        <div class="flex items-center justify-between text-xs text-gray-500 mb-2">
                            <span>
                                <i class="fas fa-money-bill-wave mr-1"></i>
                                {{ $activity->biaya_bergabung == 'gratis' ? 'Gratis' : 'Rp ' . number_format($activity->harga, 0, ',', '.') }}
                            </span>
                            <span>
                                <i class="fas fa-calendar mr-1"></i>
                                {{ $activity->created_at->format('d M Y') }}
                            </span>
                        </div>
                        @if($activity->status == 'rejected' && $activity->alasan_reject)
                        <div class="mt-2 p-2 bg-red-50 border border-red-200 rounded text-xs text-red-700">
                            <strong>Alasan ditolak:</strong> {{ $activity->alasan_reject }}
                        </div>
                        @endif
                    </div>
                    
                    {{-- Daftar Anggota yang Bergabung (hanya untuk aktivitas yang sudah disetujui) --}}
                    @if($activity->status === 'approved')
                    <div class="mt-4 pt-3 border-t border-gray-200">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                            <i class="fas fa-users mr-2"></i>
                            Anggota yang Bergabung 
                            @if($activity->participants && $activity->participants->count() > 0)
                                ({{ $activity->participants->count() }})
                            @endif
                        </h4>
                        @if($activity->participants && $activity->participants->count() > 0)
                        <div class="space-y-2 max-h-64 overflow-y-auto">
                            @foreach($activity->participants as $participant)
                            <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-gray-900">{{ $participant->nama_peserta }}</p>
                                        @if($participant->user)
                                        <p class="text-xs text-gray-500 mt-1">
                                            <i class="fas fa-user mr-1"></i>
                                            {{ $participant->user->name }} ({{ $participant->user->email }})
                                        </p>
                                        @endif
                                        <p class="text-xs text-gray-500 mt-1">
                                            <i class="fas fa-calendar mr-1"></i>
                                            Daftar: {{ $participant->created_at->format('d M Y H:i') }}
                                        </p>
                                        @if($participant->bukti_pembayaran)
                                        <a href="{{ asset('bukti_pembayaran/'.$participant->bukti_pembayaran) }}" 
                                           target="_blank" 
                                           class="text-xs text-blue-600 hover:text-blue-800 mt-1 inline-flex items-center">
                                            <i class="fas fa-file-image mr-1"></i> Lihat Bukti Pembayaran
                                        </a>
                                        @endif
                                    </div>
                                    <div class="ml-3 flex flex-col items-end gap-2">
                                        @if($participant->status === 'approved')
                                            <span class="bg-green-100 text-green-700 text-xs font-semibold px-2 py-1 rounded-full">
                                                <i class="fas fa-check-circle"></i> Disetujui
                                            </span>
                                        @elseif($participant->status === 'pending')
                                            <span class="bg-yellow-100 text-yellow-700 text-xs font-semibold px-2 py-1 rounded-full">
                                                <i class="fas fa-clock"></i> Menunggu
                                            </span>
                                        @else
                                            <span class="bg-red-100 text-red-700 text-xs font-semibold px-2 py-1 rounded-full">
                                                <i class="fas fa-times-circle"></i> Ditolak
                                            </span>
                                        @endif
                                        
                                        @if($participant->status === 'pending')
                                        <div class="flex gap-2">
                                            <form action="{{ route('user.participant.approve', $participant->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="bg-green-600 text-white text-xs font-semibold px-3 py-1 rounded-lg hover:bg-green-700 transition duration-150"
                                                        onclick="return confirm('Apakah Anda yakin ingin menyetujui peserta ini?')">
                                                    <i class="fas fa-check mr-1"></i> Setujui
                                                </button>
                                            </form>
                                            <button type="button" 
                                                    class="bg-red-600 text-white text-xs font-semibold px-3 py-1 rounded-lg hover:bg-red-700 transition duration-150"
                                                    onclick="showRejectModal({{ $participant->id }}, '{{ addslashes($participant->nama_peserta) }}')">
                                                <i class="fas fa-times mr-1"></i> Tolak
                                            </button>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <p class="text-sm text-gray-500 text-center py-2">
                            <i class="fas fa-users mr-1"></i>
                            Belum ada anggota yang bergabung
                        </p>
                        @endif
                    </div>
                    @endif

                    {{-- Tombol Aksi --}}
                    <div class="flex justify-between items-center mt-4 pt-3 border-t border-gray-100">
                        <a href="{{ route('community.detail', $activity->id) }}" 
                           class="text-sm font-semibold text-orange-500 hover:text-orange-700 transition duration-150 ease-in-out">
                            Lihat Detail 
                            <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                        <a href="{{ route('user.aktivitas.edit', $activity->id) }}" 
                           class="bg-blue-600 text-white text-sm font-semibold px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-150">
                            <i class="fas fa-edit mr-1"></i> Edit
                        </a>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-6">
                {{ $activities->links() }}
            </div>
            @else
            {{-- Empty State --}}
            <div class="bg-white rounded-xl shadow-xl p-10 flex flex-col items-center justify-center text-center border-2 border-dashed border-gray-300">
                <i class="fas fa-clipboard-list text-6xl text-gray-400 mb-4"></i>
                <h3 class="text-2xl font-bold text-gray-800 mb-2">Belum Ada Aktivitas</h3>
                <p class="text-gray-500 max-w-lg mb-6">
                    Kamu belum membuat aktivitas apapun. Yuk, buat aktivitas pertama kamu sekarang!
                </p>
                <a href="/buat-aktivitas" class="bg-blue-600 text-white font-semibold px-6 py-3 rounded-lg shadow-md hover:bg-blue-700 transition duration-150">
                    <i class="fas fa-plus mr-2"></i> Buat Aktivitas Sekarang
                </a>
            </div>
            @endif

            {{-- Section: Aktivitas yang Diikuti (Bergabung) --}}
            @if(isset($activitiesJoined) && $activitiesJoined->count() > 0)
            <div class="mt-8">
                <div class="bg-white rounded-xl shadow-xl p-6 border-l-4 border-blue-500 mb-6">
                    <h2 class="font-extrabold text-2xl text-gray-900">Event yang Saya Ikuti</h2>
                    <p class="text-gray-500 mt-1">Daftar event yang telah kamu daftarkan sebagai peserta.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($activitiesJoined as $participant)
                    @php
                        $activity = $participant->activity;
                    @endphp
                    @if($activity)
                    <div class="community-card bg-white p-6 rounded-lg shadow-md">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center flex-1">
                                <div class="w-12 h-12 rounded-full flex items-center justify-center mr-4 flex-shrink-0 bg-red-100 text-red-600">
                                    <i class="fas fa-calendar-alt text-xl"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xl font-bold text-gray-900">{{ $activity->nama }}</p>
                                    <p class="text-sm text-gray-500">{{ $activity->kategori }} | {{ $activity->lokasi ?? '-' }}</p>
                                </div>
                            </div>
                            {{-- Status Badge --}}
                            <div class="ml-2">
                                @if($participant->status === 'approved')
                                    <span class="bg-green-100 text-green-700 text-xs font-semibold px-2 py-1 rounded-full">
                                        <i class="fas fa-check-circle"></i> Disetujui
                                    </span>
                                @elseif($participant->status === 'pending')
                                    <span class="bg-yellow-100 text-yellow-700 text-xs font-semibold px-2 py-1 rounded-full">
                                        <i class="fas fa-clock"></i> Menunggu Verifikasi
                                    </span>
                                @else
                                    <span class="bg-red-100 text-red-700 text-xs font-semibold px-2 py-1 rounded-full">
                                        <i class="fas fa-times-circle"></i> Ditolak
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Banner Preview --}}
                        @if($activity->banner)
                        <div class="mb-4">
                            <img src="{{ asset('fotoaktivitas/'.$activity->banner) }}" 
                                 alt="{{ $activity->nama }}" 
                                 class="w-full h-32 object-cover rounded-lg">
                        </div>
                        @endif
                        
                        {{-- Info Peserta --}}
                        <div class="mt-3 pt-3 border-t border-gray-100">
                            <p class="text-sm text-gray-600 mb-2">
                                <i class="fas fa-user mr-1"></i>
                                <strong>Nama Peserta:</strong> {{ $participant->nama_peserta }}
                            </p>
                            <div class="flex items-center justify-between text-xs text-gray-500 mb-2">
                                <span>
                                    <i class="fas fa-money-bill-wave mr-1"></i>
                                    {{ $activity->biaya_bergabung == 'gratis' ? 'Gratis' : 'Rp ' . number_format($activity->harga, 0, ',', '.') }}
                                </span>
                                <span>
                                    <i class="fas fa-calendar mr-1"></i>
                                    Daftar: {{ $participant->created_at->format('d M Y') }}
                                </span>
                            </div>
                            @if($participant->bukti_pembayaran)
                            <div class="mt-2">
                                <a href="{{ asset('bukti_pembayaran/'.$participant->bukti_pembayaran) }}" target="_blank" class="text-xs text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-file-image mr-1"></i> Lihat Bukti Pembayaran
                                </a>
                            </div>
                            @endif
                        </div>
                        
                        {{-- Tombol Aksi --}}
                        <div class="flex justify-between items-center mt-4 pt-3 border-t border-gray-100">
                            <a href="{{ route('community.detail', $activity->id) }}"
                               class="text-sm font-semibold text-orange-500 hover:text-orange-700 transition duration-150 ease-in-out">
                                Lihat Detail
                                <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                            @if($activity->link_kontak)
                            <a href="{{ $activity->link_kontak }}" target="_blank"
                               class="bg-green-600 text-white text-sm font-semibold px-4 py-2 rounded-lg hover:bg-green-700 transition duration-150">
                                <i class="fab fa-whatsapp mr-1"></i> Lihat Grup WhatsApp
                            </a>
                            @endif
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
            @endif
            
        </div>
        
        {{-- Kolom Kanan (1/3) - Sidebar Unified --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl p-6 space-y-6 border border-gray-200 shadow-lg">
                
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
                <a href="#" class="rounded-xl p-4 border border-gray-200 shadow-sm hover:shadow-md block relative overflow-hidden transition-all duration-300 hover:translate-x-1">
                    <div class="absolute inset-0 opacity-20" style="background-image: url('{{ asset('assets/blue-banner.png') }}'); background-size: cover; background-position: center;"></div>
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
                        <a href="/venue" class="bg-white rounded-xl p-4 border border-gray-200 shadow-sm hover:shadow-md block transition-all duration-300 hover:translate-x-1">
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
                        <a href="/healthy" class="bg-white rounded-xl p-4 border border-gray-200 shadow-sm hover:shadow-md block transition-all duration-300 hover:translate-x-1">
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
                        <a href="/community" class="bg-white rounded-xl p-4 border border-gray-200 shadow-sm hover:shadow-md block transition-all duration-300 hover:translate-x-1">
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
</main>

{{-- Modal Reject Participant --}}
<div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-2xl p-6 max-w-md w-full mx-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-gray-900">Tolak Peserta</h3>
            <button onclick="closeRejectModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="rejectForm" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Alasan Penolakan (Opsional)
                </label>
                <textarea name="alasan_reject" 
                          rows="3" 
                          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-red-500"
                          placeholder="Masukkan alasan penolakan (opsional)"></textarea>
            </div>
            <div class="flex gap-3">
                <button type="button" 
                        onclick="closeRejectModal()" 
                        class="flex-1 bg-gray-200 text-gray-700 font-semibold px-4 py-2 rounded-lg hover:bg-gray-300 transition duration-150">
                    Batal
                </button>
                <button type="submit" 
                        class="flex-1 bg-red-600 text-white font-semibold px-4 py-2 rounded-lg hover:bg-red-700 transition duration-150">
                    <i class="fas fa-times mr-1"></i> Tolak Peserta
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function showRejectModal(participantId, participantName) {
    const modal = document.getElementById('rejectModal');
    const form = document.getElementById('rejectForm');
    form.action = '{{ url("/riwayat-komunitas/participant") }}/' + participantId + '/reject';
    modal.classList.remove('hidden');
}

function closeRejectModal() {
    const modal = document.getElementById('rejectModal');
    modal.classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('rejectModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeRejectModal();
    }
});
</script>
@endpush

@endsection