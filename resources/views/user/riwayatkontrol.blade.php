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
    .health-record-card {
        transition: all 0.3s ease;
        border-left: 4px solid;
    }
    .health-record-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
    }
    .health-record-card.completed {
        border-left-color: #10b981;
    }
    .health-record-card.pending {
        border-left-color: #f59e0b;
    }
    .health-record-card.cancelled {
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
    .result-item {
        background: linear-gradient(135deg, #f9fafb 0%, #ffffff 100%);
        border: 1px solid #e5e7eb;
        transition: all 0.2s ease;
    }
    .result-item:hover {
        background: linear-gradient(135deg, #f3f4f6 0%, #f9fafb 100%);
        border-color: #d1d5db;
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
                        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">Riwayat Kontrol</h1>
                        <p class="text-gray-600">Lihat semua riwayat cek kesehatan dan pemeriksaan Anda</p>
                    </div>
                    <div class="hidden md:flex items-center space-x-2">
                        <div class="bg-green-100 text-green-700 px-4 py-2 rounded-lg font-semibold">
                            <i class="fas fa-clipboard-check mr-2"></i>Total: {{ $healthBookings->count() }} Kontrol
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filter Section --}}
            <form method="GET" action="{{ route('user.riwayatkontrol') }}" id="filterKontrolForm" class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 space-y-4">
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-search mr-2 text-gray-500"></i>Cari Riwayat
                        </label>
                        <input 
                            type="text" 
                            name="q"
                            id="filterSearchInput"
                            value="{{ request('q') }}"
                            placeholder="Cari layanan, dokter, atau klinik..."
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-gray-700 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition"
                        >
                    </div>
                    <div class="md:w-44">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-calendar-alt mr-2 text-gray-500"></i>Tanggal
                        </label>
                        <input 
                            type="date" 
                            name="tanggal"
                            id="filterDateInput"
                            value="{{ request('tanggal') }}"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-gray-700 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition"
                        >
                    </div>
                    <div class="md:w-44">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-tags mr-2 text-gray-500"></i>Kategori
                        </label>
                        <select name="kategori" id="filterCategorySelect" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-gray-700 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition">
                            <option value="">Semua Kategori</option>
                            <option value="medical-checkup" {{ request('kategori') == 'medical-checkup' ? 'selected' : '' }}>Medical Check-Up</option>
                            <option value="fisioterapi" {{ request('kategori') == 'fisioterapi' ? 'selected' : '' }}>Fisioterapi & Cedera</option>
                            <option value="dokter-spesialis" {{ request('kategori') == 'dokter-spesialis' ? 'selected' : '' }}>Dokter Spesialis</option>
                            <option value="nutrisi" {{ request('kategori') == 'nutrisi' ? 'selected' : '' }}>Nutrisi & Gizi</option>
                        </select>
                    </div>
                    <div class="md:w-44">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-info-circle mr-2 text-gray-500"></i>Status
                        </label>
                        <select name="status" id="filterStatusSelect" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-gray-700 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition">
                            <option value="">Semua Status</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Dikonfirmasi</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>
                </div>
                <div class="flex items-center justify-end space-x-2 pt-2 border-t border-gray-100">
                    <button type="button" id="resetFilterBtn" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold rounded-lg transition flex items-center">
                        <i class="fas fa-undo mr-1.5"></i> Reset Filter
                    </button>
                    <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-xs font-bold rounded-lg transition shadow-sm flex items-center">
                        <i class="fas fa-search mr-1.5"></i> Terapkan Filter
                    </button>
                </div>
            </form>

            {{-- Riwayat Kontrol Cards Container --}}
            <div class="space-y-4" id="bookingCardsListContainer">
                <div id="noFilteredResultsMsg" class="bg-white rounded-xl shadow-md p-10 text-center border-2 border-dashed border-gray-300 hidden">
                    <i class="fas fa-search text-gray-400 text-4xl mb-3"></i>
                    <h4 class="font-bold text-gray-800 text-lg">Tidak Ada Riwayat Kontrol Cocok</h4>
                    <p class="text-sm text-gray-500 mt-1">Coba ubah tanggal, status, atau pencarian Anda.</p>
                </div>

                @forelse ($healthBookings as $booking)
                <div class="booking-card bg-white rounded-lg shadow-sm border border-gray-200 p-5 relative overflow-hidden"
                     data-search="{{ strtolower(($booking->clinic->nama ?? '') . ' ' . ($booking->doctor->nama_lengkap ?? $booking->doctor->nama ?? '') . ' ' . ($booking->service->nama ?? '') . ' ' . $booking->kode_booking . ' ' . $booking->nama_pasien) }}"
                     data-date="{{ $booking->tanggal ? $booking->tanggal->format('Y-m-d') : '' }}"
                     data-category="{{ strtolower($booking->service->kategori ?? '') }}"
                     data-status="{{ strtolower($booking->status) }}">
                    <div class="flex items-start justify-between mb-4 pb-3 border-b border-gray-200">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center flex-shrink-0 relative">
                                <i class="fas fa-hospital text-white text-sm"></i>
                            </div>
                            <h3 class="text-base font-bold text-gray-900">{{ $booking->clinic->nama ?? 'Klinik Kesehatan' }}</h3>
                        </div>
                        <div class="flex items-center space-x-2 flex-wrap gap-1">
                            <button type="button" onclick="openReviewModal('klinik', {{ $booking->clinic_id ?? 0 }}, '{{ addslashes($booking->clinic->nama ?? 'Klinik') }}')" class="px-3 py-1.5 text-sm bg-amber-500 text-white rounded-lg hover:bg-amber-600 transition-colors font-medium">
                                <i class="fas fa-star mr-1"></i> Beri Rating
                            </button>
                            @if($booking->status === 'pending' || $booking->status === 'confirmed')
                            <button onclick="openRescheduleModal({{ $booking->id }}, '{{ $booking->tanggal->format('Y-m-d') }}', '{{ substr($booking->jam, 0, 5) }}')" class="px-3 py-1.5 text-sm border border-green-600 text-green-600 rounded-lg hover:bg-green-50 transition-colors font-medium">
                                Jadwalkan Ulang
                            </button>
                            @endif
                            <a href="{{ $booking->service_id ? route('frontend.service.detail', $booking->service_id) : '/healthy' }}" class="px-3 py-1.5 text-sm bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium">
                                Booking Ulang
                            </a>
                        </div>
                    </div>

                    <div class="space-y-2.5 mb-4">
                        <div class="flex items-start">
                            <span class="text-sm text-gray-500 w-36">Waktu Pemesanan</span>
                            <span class="text-sm text-gray-900 font-medium">{{ $booking->created_at->format('l, d F Y') }}</span>
                        </div>
                        <div class="flex items-start">
                            <span class="text-sm text-gray-500 w-36">Kode Booking</span>
                            <span class="text-sm text-gray-900 font-medium">{{ $booking->kode_booking }}</span>
                        </div>
                        <div class="flex items-start">
                            <span class="text-sm text-gray-500 w-36">Nama Dokter</span>
                            <span class="text-sm text-gray-900 font-medium">{{ $booking->doctor->nama_lengkap ?? $booking->doctor->nama ?? 'Tim Dokter' }}</span>
                        </div>
                        <div class="flex items-start">
                            <span class="text-sm text-gray-500 w-36">Pelayanan</span>
                            <span class="text-sm text-gray-900 font-medium">{{ $booking->service->nama ?? 'Pemeriksaan Kesehatan' }}</span>
                        </div>
                        <div class="flex items-start">
                            <span class="text-sm text-gray-500 w-36">Jadwal Booking</span>
                            <span class="text-sm text-gray-900 font-medium">{{ $booking->tanggal->format('d F Y') }} • {{ substr($booking->jam, 0, 5) }} WIB</span>
                        </div>
                        <div class="flex items-start">
                            <span class="text-sm text-gray-500 w-36">Biaya Cek</span>
                            <span class="text-sm text-gray-900 font-medium">Rp {{ number_format($booking->total_harga, 0, ',', '.') }} ({{ $booking->status_pembayaran }})</span>
                        </div>
                        <div class="flex items-start">
                            <span class="text-sm text-gray-500 w-36">Status</span>
                            @php
                                $badgeClasses = [
                                    'pending' => 'bg-yellow-100 text-yellow-700',
                                    'confirmed' => 'bg-blue-100 text-blue-700',
                                    'completed' => 'bg-green-100 text-green-700',
                                    'cancelled' => 'bg-red-100 text-red-700',
                                    'no_show' => 'bg-gray-100 text-gray-700',
                                ];
                                $badgeClass = $badgeClasses[$booking->status] ?? 'bg-gray-100 text-gray-700';
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $badgeClass }}">
                                {{ $booking->status_text }}
                            </span>
                        </div>
                    </div>

                    @if($booking->catatan_dokter)
                    <div class="pt-3 border-t border-gray-200">
                        <div class="bg-green-50 rounded-lg p-3 border border-green-100">
                            <p class="text-xs text-green-700 font-semibold mb-1"><i class="fas fa-stethoscope mr-1"></i> Catatan Dokter / Resep:</p>
                            <p class="text-xs text-green-800">{{ $booking->catatan_dokter }}</p>
                        </div>
                    </div>
                    @endif
                </div>
                @empty
                <div class="bg-white rounded-xl shadow-md p-12 text-center border-2 border-dashed border-gray-300">
                    <i class="fas fa-clipboard-list text-6xl text-gray-400 mb-4"></i>
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">Belum Ada Riwayat Kontrol</h3>
                    <p class="text-gray-500 max-w-lg mx-auto mb-6">
                        Anda belum mendaftar untuk pemeriksaan kesehatan apapun. Jadwalkan pemeriksaan Anda sekarang!
                    </p>
                    <a href="/healthy" class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow-md transition">
                        <i class="fas fa-search mr-2"></i> Cari Layanan Kesehatan
                    </a>
                </div>
                @endforelse
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
                    <a href="{{ route('user.riwayatmembership') }}" class="quick-action-item rounded-xl p-4 border border-amber-300 bg-gradient-to-r from-amber-500 to-orange-600 shadow-md hover:shadow-lg block relative overflow-hidden text-white transition-all transform hover:-translate-y-0.5">
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
                    <a href="{{ route('community', ['type' => 'klub']) }}" class="quick-action-item rounded-xl p-4 border border-blue-200 bg-gradient-to-r from-blue-600 to-indigo-700 shadow-md hover:shadow-lg block relative overflow-hidden text-white transition-all transform hover:-translate-y-0.5">
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
function openRescheduleModal(bookingId, currentTanggal, currentJam) {
    Swal.fire({
        title: 'Jadwalkan Ulang Kontrol',
        html: `
            <div class="text-left space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Pilih Tanggal Baru</label>
                    <input type="date" id="reschedule-date" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-700 focus:ring-2 focus:ring-green-500 focus:border-green-500" value="${currentTanggal}" min="${new Date().toISOString().split('T')[0]}">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Pilih Jam Baru</label>
                    <input type="time" id="reschedule-time" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-700 focus:ring-2 focus:ring-green-500 focus:border-green-500" value="${currentJam}">
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Simpan Jadwal',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#16a34a',
        preConfirm: () => {
            const date = document.getElementById('reschedule-date').value;
            const time = document.getElementById('reschedule-time').value;
            if (!date || !time) {
                Swal.showValidationMessage('Tanggal dan jam harus diisi!');
                return false;
            }
            return { date: date, time: time };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Memproses...',
                text: 'Mohon tunggu sebentar.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch(`/riwayatkontrol/${bookingId}/reschedule`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    tanggal: result.value.date,
                    jam: result.value.time
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: data.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: data.message
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Terjadi kesalahan sistem.'
                });
            });
        }
    });
}

// Real-time Instant Filter Handler
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('filterSearchInput');
    const dateInput = document.getElementById('filterDateInput');
    const categorySelect = document.getElementById('filterCategorySelect');
    const statusSelect = document.getElementById('filterStatusSelect');
    const resetBtn = document.getElementById('resetFilterBtn');
    const cards = document.querySelectorAll('.booking-card');
    const noResultsMsg = document.getElementById('noFilteredResultsMsg');

    function applyFilters() {
        const query = searchInput ? searchInput.value.toLowerCase().trim() : '';
        const selectedDate = dateInput ? dateInput.value.trim() : '';
        const selectedCategory = categorySelect ? categorySelect.value.toLowerCase().trim() : '';
        const selectedStatus = statusSelect ? statusSelect.value.toLowerCase().trim() : '';

        let visibleCount = 0;

        cards.forEach(card => {
            const cardSearch = (card.getAttribute('data-search') || '').toLowerCase();
            const cardDate = (card.getAttribute('data-date') || '').trim();
            const cardCategory = (card.getAttribute('data-category') || '').toLowerCase();
            const cardStatus = (card.getAttribute('data-status') || '').toLowerCase();

            let matchQuery = !query || cardSearch.includes(query);
            let matchDate = !selectedDate || cardDate === selectedDate;
            let matchCategory = !selectedCategory || cardCategory.includes(selectedCategory);
            let matchStatus = !selectedStatus || cardStatus === selectedStatus;

            if (matchQuery && matchDate && matchCategory && matchStatus) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        if (noResultsMsg) {
            if (visibleCount === 0 && cards.length > 0) {
                noResultsMsg.classList.remove('hidden');
            } else {
                noResultsMsg.classList.add('hidden');
            }
        }
    }

    [searchInput, dateInput, categorySelect, statusSelect].forEach(el => {
        if (el) {
            el.addEventListener('input', applyFilters);
            el.addEventListener('change', applyFilters);
        }
    });

    if (resetBtn) {
        resetBtn.addEventListener('click', function(e) {
            if (e) e.preventDefault();
            if (searchInput) searchInput.value = '';
            if (dateInput) dateInput.value = '';
            if (categorySelect) categorySelect.value = '';
            if (statusSelect) statusSelect.value = '';
            applyFilters();
            window.history.replaceState({}, document.title, window.location.pathname);
        });
    }

    applyFilters();
});
</script>

@endsection

