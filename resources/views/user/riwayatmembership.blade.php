@extends('user.layout.user')

@push('css')
{{-- Memastikan ikon Font Awesome tersedia untuk visual --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<style>
    /* Styling untuk tautan navigasi profil yang aktif */
    .profile-nav-link {
        @apply flex items-center p-3 rounded-lg font-semibold transition duration-150;
    }
    .profile-nav-link.active {
        /* Menggunakan warna orange untuk status aktif */
        @apply bg-orange-500 text-white shadow-md shadow-orange-200;
    }
    .profile-nav-link:not(.active) {
        @apply text-gray-700 hover:bg-gray-100 hover:text-orange-500;
    }
    /* Styling untuk ikon navigasi */
    .profile-nav-link i {
        @apply w-5 h-5 mr-3;
    }
    /* Styling khusus untuk Card Membership */
    .membership-card {
        /* Gradien biru yang menarik untuk card utama */
        @apply bg-gradient-to-br from-blue-600 to-blue-800 text-white p-6 rounded-xl shadow-2xl;
    }
    .membership-feature {
        @apply flex items-start text-sm mb-2;
    }
</style>
@endpush

@section('content')

<main class="pt-20 min-h-screen pb-8 px-4 md:px-8 lg:px-10 bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8">

        <div class="lg:col-span-2 space-y-6">
            
            {{-- Header Konten --}}
            <div class="bg-gradient-to-r from-slate-900 via-blue-900 to-indigo-950 rounded-2xl shadow-xl p-6 text-white flex flex-wrap justify-between items-center gap-4 border border-blue-500/30">
                <div>
                    <span class="inline-flex items-center bg-amber-500/20 text-amber-300 border border-amber-500/40 text-xs font-black px-3 py-1 rounded-full mb-2">
                        <i class="fas fa-crown mr-1.5 text-amber-400"></i> KEANGGOTAAN OLGASEHAT
                    </span>
                    <h2 class="font-black text-2xl md:text-3xl text-white">Membership Saya</h2>
                    <p class="text-blue-100/90 text-sm mt-1">Status keanggotaan aktif memberikan Anda potongan harga khusus saat booking venue & layanan kesehatan.</p>
                </div>
                {{-- Tombol CTA --}}
                <a href="/community?type=klub" class="bg-gradient-to-r from-amber-500 to-yellow-500 text-slate-950 font-black px-5 py-3 rounded-xl shadow-lg hover:from-amber-600 hover:to-yellow-600 transition flex items-center text-sm md:text-base whitespace-nowrap">
                    <i class="fas fa-plus-circle mr-2 text-base"></i> Beli Paket Membership
                </a>
            </div>

            <!-- Card Keuntungan VIP Membership -->
            <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-md">
                <h3 class="font-extrabold text-slate-900 text-lg md:text-xl mb-1 flex items-center">
                    <i class="fas fa-gift text-amber-500 mr-2 text-2xl"></i> Keuntungan Memiliki Membership Lapangan & Klinik
                </h3>
                <p class="text-xs md:text-sm text-gray-600 mb-5">Dapatkan penawaran istimewa dan proteksi hemat bertransaksi di platform OlgaSehat:</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="p-4 rounded-xl bg-amber-50/70 border border-amber-200/80 flex items-start space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-400 text-slate-950 flex items-center justify-center font-black text-lg flex-shrink-0 shadow-sm">
                            <i class="fas fa-percent"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-900 text-sm mb-0.5">Diskon VIP Otomatis (10%+ - 20%)</h4>
                            <p class="text-xs text-slate-600 leading-relaxed">Potongan harga langsung otomatis terpotong saat Anda melakukan booking lapangan & layanan klinik.</p>
                        </div>
                    </div>

                    <div class="p-4 rounded-xl bg-blue-50/70 border border-blue-200/80 flex items-start space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center font-black text-lg flex-shrink-0 shadow-sm">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-900 text-sm mb-0.5">Prioritas Booking & Sparing</h4>
                            <p class="text-xs text-slate-600 leading-relaxed">Akses memesan jam favorit lebih awal serta kesempatan mengikuti event sparing khusus member venue.</p>
                        </div>
                    </div>

                    <div class="p-4 rounded-xl bg-emerald-50/70 border border-emerald-200/80 flex items-start space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-500 text-white flex items-center justify-center font-black text-lg flex-shrink-0 shadow-sm">
                            <i class="fab fa-whatsapp"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-900 text-sm mb-0.5">Grup WA Eksklusif Venue / Klinik</h4>
                            <p class="text-xs text-slate-600 leading-relaxed">Terhubung langsung ke grup WhatsApp internal pengelola untuk koordinasi main, promo, dan info klinik.</p>
                        </div>
                    </div>

                    <div class="p-4 rounded-xl bg-purple-50/70 border border-purple-200/80 flex items-start space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-purple-600 text-white flex items-center justify-center font-black text-lg flex-shrink-0 shadow-sm">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-900 text-sm mb-0.5">Bebas Biaya Admin & Terintegrasi</h4>
                            <p class="text-xs text-slate-600 leading-relaxed">Penghematan biaya transaksi tanpa biaya tersembunyi dengan histori keanggotaan transparan di dashboard.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Guide Alert: Cara Menggunakan Diskon Member -->
            <div class="bg-amber-50 border-2 border-amber-300/80 rounded-2xl p-5 shadow-sm flex items-start space-x-4">
                <div class="w-10 h-10 bg-amber-400 text-slate-950 rounded-xl flex items-center justify-center font-black text-lg shadow-sm flex-shrink-0 mt-0.5">
                    <i class="fas fa-lightbulb"></i>
                </div>
                <div class="text-sm">
                    <h4 class="font-extrabold text-amber-900 text-base mb-1">Cara Menggunakan Diskon VIP Member:</h4>
                    <ol class="list-decimal list-inside text-amber-950 space-y-1 text-xs md:text-sm font-medium">
                        <li>Pastikan status membership Anda sudah <strong>Approved / Member Aktif</strong> (telah diverifikasi pemilik venue/admin).</li>
                        <li>Buka menu <a href="/venue" class="font-bold underline text-blue-700">Booking Venue / Lapangan</a> atau Klinik.</li>
                        <li>Pilih jadwal & venue yang Anda inginkan, sistem akan <strong>otomatis memotong harga total dengan Diskon Member</strong>!</li>
                    </ol>
                </div>
            </div>
            
            <!-- Membership Section -->
            <div class="grid grid-cols-1 gap-6">
                @forelse ($memberships as $membership)
                @php
                    $startDate = $membership->created_at;
                    $endDate = $membership->created_at->copy()->addDays(30);
                    $daysRemaining = now()->diffInDays($endDate, false);
                    $isApproved = $membership->status === 'approved';
                @endphp
                <div class="rounded-2xl shadow-xl bg-gradient-to-r from-slate-900 via-blue-900 to-indigo-950 text-white p-6 md:p-8 relative overflow-hidden border border-blue-400/30 transition hover:shadow-2xl">
                    {{-- Decorative Card Background Glow --}}
                    <div class="absolute -right-12 -bottom-12 w-64 h-64 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="absolute -left-12 -top-12 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
                    
                    <div class="relative z-10">
                        {{-- Top Header --}}
                        <div class="flex flex-wrap items-center justify-between gap-4 pb-4 border-b border-white/10">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 rounded-xl bg-amber-400/20 border border-amber-400/40 flex items-center justify-center text-amber-300 text-2xl shadow-inner">
                                    <i class="fas fa-crown"></i>
                                </div>
                                <div>
                                    <span class="text-xs font-black tracking-widest text-amber-300 uppercase">KARTU VIP MEMBER EKSKLUSIF</span>
                                    <h3 class="text-xl md:text-2xl font-black text-white">{{ $membership->activity->nama ?? 'Membership Venue' }}</h3>
                                </div>
                            </div>
                            <div>
                                @if($isApproved)
                                    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-black bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 shadow-lg">
                                        <i class="fas fa-check-circle mr-1.5 text-emerald-400"></i> MEMBER VIP AKTIF
                                    </span>
                                @elseif($membership->status === 'pending')
                                    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-black bg-amber-500/20 text-amber-300 border border-amber-500/40">
                                        <i class="fas fa-clock mr-1.5 text-amber-400"></i> MENUNGGU VERIFIKASI ADMIN
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-black bg-red-500/20 text-red-300 border border-red-500/40">
                                        <i class="fas fa-times-circle mr-1.5 text-red-400"></i> DITOLAK
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Card Body Details --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 my-6">
                            <div>
                                <p class="text-xs text-blue-200 font-medium uppercase tracking-wider mb-1">ID Anggota VIP</p>
                                <p class="text-lg font-mono font-bold text-white tracking-widest">#MEM-OLG-{{ sprintf('%04d', $membership->id) }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-blue-200 font-medium uppercase tracking-wider mb-1">Pemilik / Lokasi</p>
                                <p class="text-base font-semibold text-white flex items-center">
                                    <i class="fas fa-map-marker-alt text-amber-400 mr-2 text-xs"></i>
                                    {{ $membership->activity->lokasi ?? 'Denpasar, Bali' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-blue-200 font-medium uppercase tracking-wider mb-1">Masa Berlaku Keanggotaan</p>
                                <p class="text-base font-bold text-white">{{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}</p>
                                @if($isApproved)
                                    <p class="text-xs text-emerald-400 font-medium mt-0.5"><i class="fas fa-hourglass-half mr-1"></i>Sisa Masa Aktif: {{ max(0, $daysRemaining) }} Hari</p>
                                @endif
                            </div>
                        </div>

                        {{-- Benefit Highlight Box --}}
                        <div class="p-4 rounded-xl bg-white/5 border border-white/10 mb-6 backdrop-blur-sm">
                            <h4 class="text-xs font-black text-amber-300 uppercase tracking-wider mb-2 flex items-center">
                                <i class="fas fa-star mr-2 text-amber-400"></i> Benefit Keanggotaan VIP Anda:
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-xs md:text-sm text-blue-100 font-medium">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-check text-emerald-400 text-xs"></i>
                                    <span>Diskon Khusus Member 10%+ Otomatis Saat Booking</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-check text-emerald-400 text-xs"></i>
                                    <span>Prioritas Akses Jadwal Sparing & Latihan Lapangan</span>
                                </div>
                            </div>
                        </div>

                        {{-- Footer Action Buttons --}}
                        <div class="flex flex-wrap items-center justify-between gap-4 pt-4 border-t border-white/10">
                            <div>
                                <span class="text-xs text-gray-400 block">Biaya Langganan</span>
                                <span class="text-xl font-black text-amber-400">Rp {{ number_format($membership->activity->harga ?? 0, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex flex-wrap gap-3">
                                @if($isApproved)
                                <a href="/venue" class="inline-flex items-center px-5 py-2.5 rounded-xl font-black text-sm bg-gradient-to-r from-amber-500 to-yellow-500 hover:from-amber-600 hover:to-yellow-600 text-slate-950 shadow-lg hover:shadow-amber-500/25 transition">
                                    <i class="fas fa-futbol mr-2"></i> Booking Lapangan (Pakai Diskon)
                                </a>
                                <a href="/healthy" class="inline-flex items-center px-5 py-2.5 rounded-xl font-bold text-sm bg-white/10 hover:bg-white/20 text-white border border-white/20 transition">
                                    <i class="fas fa-hospital-user mr-2"></i> Booking Klinik
                                </a>
                                @else
                                <span class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-bold bg-amber-500/20 text-amber-300 border border-amber-400/30">
                                    <i class="fas fa-info-circle mr-1.5"></i> Bukti bayar Anda sedang diverifikasi admin
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full bg-white rounded-xl shadow-md p-12 text-center border-2 border-dashed border-gray-300">
                    <i class="fas fa-crown text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">Belum Ada Membership</h3>
                    <p class="text-gray-500 max-w-lg mx-auto mb-6">
                        Anda belum mendaftar membership di club/venue manapun. Temukan membership favorit Anda dan nikmati diskon member eksklusif!
                    </p>
                    <a href="/community?type=klub" class="inline-flex items-center px-6 py-3 bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-lg shadow-md transition">
                        <i class="fas fa-search mr-2"></i> Cari Membership Sekarang
                    </a>
                </div>
                @endforelse
            </div>

            {{-- Empty State (Gunakan jika tidak ada data) --}}
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

                {{-- Blue Banner Card: Nikmati Akses User / VIP Status --}}
                @php
                    $isUserMember = false;
                    if (Auth::check()) {
                        $isUserMember = \App\Models\ActivityParticipant::where('user_id', Auth::id())
                            ->where('status', 'approved')
                            ->whereHas('activity', function($q) {
                                $q->where('jenis', 'membership')
                                  ->orWhereHas('activityType', function($at) {
                                      $at->whereIn('name', ['klub', 'membership']);
                                  });
                            })->exists();
                    }
                @endphp
                @if($isUserMember)
                    <a href="{{ route('user.riwayatmembership') }}" class="rounded-xl p-4 border border-amber-300 bg-gradient-to-r from-amber-500 to-orange-600 shadow-md hover:shadow-lg block relative overflow-hidden text-white transition-all duration-300 hover:translate-x-1">
                        <div class="relative z-10 flex items-start space-x-3">
                            <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center flex-shrink-0 shadow-md">
                                <i class="fas fa-crown text-white text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <h3 class="font-bold text-white text-base mb-1">VIP Member Aktif</h3>
                                    <span class="bg-white text-amber-800 text-[10px] font-black px-2.5 py-0.5 rounded-full uppercase tracking-wider">AKTIF</span>
                                </div>
                                <p class="text-xs text-amber-100 leading-relaxed">Selamat! Anda menikmati diskon 10% untuk venue & kesehatan. Kelola membership Anda di sini.</p>
                            </div>
                        </div>
                    </a>
                @else
                    <a href="{{ route('community', ['type' => 'klub']) }}" class="rounded-xl p-4 border border-blue-200 bg-gradient-to-r from-blue-600 to-indigo-700 shadow-md hover:shadow-lg block relative overflow-hidden text-white transition-all duration-300 hover:translate-x-1">
                        <div class="relative z-10 flex items-start space-x-3">
                            <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center flex-shrink-0 shadow-md">
                                <i class="fas fa-crown text-yellow-300 text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <h3 class="font-bold text-white text-base mb-1">Nikmati Akses User / Premium</h3>
                                    <span class="bg-yellow-400 text-yellow-950 text-[10px] font-black px-2 py-0.5 rounded-full uppercase tracking-wider">UPGRADE</span>
                                </div>
                                <p class="text-xs text-blue-100 leading-relaxed">Akses penuh ke semua fitur premium, keanggotaan klub & diskon 10% di setiap pemesanan!</p>
                            </div>
                        </div>
                    </a>
                @endif

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

@endsection