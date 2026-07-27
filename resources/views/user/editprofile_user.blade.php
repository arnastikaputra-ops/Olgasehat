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
    .form-input {
        transition: all 0.2s ease;
    }
    .form-input:focus {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
    }
    .profile-image-preview {
        transition: all 0.3s ease;
    }
    .profile-image-preview:hover {
        transform: scale(1.05);
    }
    .form-section {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
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
                <div class="flex items-center space-x-3 mb-2">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-user-edit text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl md:text-4xl font-bold text-gray-900">Edit Profil</h1>
                        <p class="text-gray-600">Perbarui informasi akun dan detail kontak Anda</p>
                    </div>
                </div>
            </div>

            {{-- Form Edit Profil --}}
            <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100 form-section">
                <form action="{{ route('user.updateprofile') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    {{-- Success/Error Messages --}}
                    @if(session('success'))
                        <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-lg">
                            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-lg">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="space-y-6">
                        
                        {{-- Section: Profile Image --}}
                        <div class="pb-6 border-b border-gray-200">
                            <label class="block text-sm font-semibold text-gray-700 mb-4">
                                <i class="fas fa-image mr-2 text-blue-600"></i>Foto Profil
                            </label>
                            <div class="flex flex-col md:flex-row items-start md:items-center gap-6">
                                {{-- Current Image Preview --}}
                                <div class="flex-shrink-0">
                                    @if(Auth::user()->image ?? null)
                                        <img src="{{ asset('storage/' . Auth::user()->image) }}" 
                                             alt="Current Profile Image" 
                                             id="currentImagePreview"
                                             class="w-32 h-32 rounded-full object-cover shadow-lg border-4 border-gray-200 profile-image-preview">
                                    @else
                                        @php
                                            $userName = Auth::user()->name ?? 'Rendra';
                                            $initial = strtoupper(substr($userName, 0, 1));
                                        @endphp
                                        <div id="currentImagePreview" class="w-32 h-32 rounded-full bg-blue-300 flex items-center justify-center text-blue-800 text-5xl font-bold shadow-lg border-4 border-gray-200">
                                            {{ $initial }}
                                        </div>
                                    @endif
                                </div>
                                
                                {{-- Upload Section --}}
                                <div class="flex-1">
                                    <input type="file" 
                                           id="image" 
                                           name="image"
                                           class="w-full p-3 border-2 border-dashed border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 form-input cursor-pointer"
                                           accept="image/*"
                                           onchange="previewImage(this)">
                                    <p class="text-sm text-gray-500 mt-2">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Upload gambar profil (max 2MB, format: JPG, PNG, GIF)
                                    </p>
                                    {{-- New Image Preview --}}
                                    <div id="newImagePreview" class="mt-4 hidden">
                                        <p class="text-sm font-semibold text-gray-700 mb-2">Preview Gambar Baru:</p>
                                        <img id="previewImg" src="" alt="Preview" class="w-24 h-24 rounded-full object-cover shadow-md border-2 border-blue-500">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Section: Informasi Dasar --}}
                        <div class="space-y-5">
                            <h3 class="text-lg font-bold text-gray-900 flex items-center mb-4">
                                <i class="fas fa-user-circle mr-2 text-blue-600"></i>
                                Informasi Dasar
                            </h3>

                            {{-- Field: Name --}}
                            <div>
                                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <i class="fas fa-user absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                    <input type="text" 
                                           id="name" 
                                           name="name"
                                           value="{{ old('name', Auth::user()->name ?? '') }}"
                                           class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 form-input"
                                           placeholder="Masukkan nama lengkap Anda"
                                           required>
                                </div>
                            </div>

                            {{-- Field: Email --}}
                            <div>
                                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <i class="fas fa-envelope absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                    <input type="email" 
                                           id="email" 
                                           name="email"
                                           value="{{ old('email', Auth::user()->email ?? '') }}"
                                           class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 form-input bg-gray-50"
                                           placeholder="Masukkan email Anda"
                                           readonly>
                                    <p class="text-xs text-gray-500 mt-1">
                                        <i class="fas fa-lock mr-1"></i>Email tidak dapat diubah
                                    </p>
                                </div>
                            </div>

                            {{-- Field: Current Password --}}
                            <div>
                                <label for="current_password" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Password Saat Ini
                                </label>
                                <div class="relative">
                                    <i class="fas fa-lock absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                    <input type="password" 
                                           id="current_password" 
                                           name="current_password"
                                           class="w-full pl-12 pr-12 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 form-input"
                                           placeholder="Masukkan password saat ini">
                                    <button type="button" 
                                            onclick="togglePassword('current_password', 'toggleCurrentPassword')"
                                            class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition">
                                        <i id="toggleCurrentPassword" class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">
                                    <i class="fas fa-info-circle mr-1"></i>Diperlukan untuk mengubah password
                                </p>
                            </div>

                            {{-- Field: New Password --}}
                            <div>
                                <label for="new_password" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Password Baru
                                </label>
                                <div class="relative">
                                    <i class="fas fa-key absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                    <input type="password" 
                                           id="new_password" 
                                           name="new_password"
                                           class="w-full pl-12 pr-12 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 form-input"
                                           placeholder="Masukkan password baru">
                                    <button type="button" 
                                            onclick="togglePassword('new_password', 'toggleNewPassword')"
                                            class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition">
                                        <i id="toggleNewPassword" class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            {{-- Field: Confirm New Password --}}
                            <div>
                                <label for="new_password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Konfirmasi Password Baru
                                </label>
                                <div class="relative">
                                    <i class="fas fa-key absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                    <input type="password" 
                                           id="new_password_confirmation" 
                                           name="new_password_confirmation"
                                           class="w-full pl-12 pr-12 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 form-input"
                                           placeholder="Konfirmasi password baru">
                                    <button type="button" 
                                            onclick="togglePassword('new_password_confirmation', 'toggleConfirmPassword')"
                                            class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition">
                                        <i id="toggleConfirmPassword" class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Section: Tanggal Lahir --}}
                        <div class="space-y-5 pt-6 border-t border-gray-200">
                            <h3 class="text-lg font-bold text-gray-900 flex items-center mb-4">
                                <i class="fas fa-birthday-cake mr-2 text-blue-600"></i>
                                Tanggal Lahir (Opsional)
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                {{-- Field: Birth Day --}}
                                <div>
                                    <label for="birth_day" class="block text-sm font-semibold text-gray-700 mb-2">Tanggal</label>
                                    <div class="relative">
                                        <i class="fas fa-calendar-day absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                        <input type="number" 
                                               id="birth_day" 
                                               name="birth_day"
                                               value="{{ old('birth_day', Auth::user()->birth_day ?? '') }}"
                                               min="1" 
                                               max="31"
                                               class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 form-input"
                                               placeholder="Tanggal">
                                    </div>
                                </div>

                                {{-- Field: Birth Month --}}
                                <div>
                                    <label for="birth_month" class="block text-sm font-semibold text-gray-700 mb-2">Bulan</label>
                                    <div class="relative">
                                        <i class="fas fa-calendar-alt absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                        <select id="birth_month" 
                                                name="birth_month"
                                                class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 form-input appearance-none cursor-pointer">
                                            <option value="">Pilih Bulan</option>
                                            <option value="Januari" {{ old('birth_month', Auth::user()->birth_month) == 'Januari' ? 'selected' : '' }}>Januari</option>
                                            <option value="Februari" {{ old('birth_month', Auth::user()->birth_month) == 'Februari' ? 'selected' : '' }}>Februari</option>
                                            <option value="Maret" {{ old('birth_month', Auth::user()->birth_month) == 'Maret' ? 'selected' : '' }}>Maret</option>
                                            <option value="April" {{ old('birth_month', Auth::user()->birth_month) == 'April' ? 'selected' : '' }}>April</option>
                                            <option value="Mei" {{ old('birth_month', Auth::user()->birth_month) == 'Mei' ? 'selected' : '' }}>Mei</option>
                                            <option value="Juni" {{ old('birth_month', Auth::user()->birth_month) == 'Juni' ? 'selected' : '' }}>Juni</option>
                                            <option value="Juli" {{ old('birth_month', Auth::user()->birth_month) == 'Juli' ? 'selected' : '' }}>Juli</option>
                                            <option value="Agustus" {{ old('birth_month', Auth::user()->birth_month) == 'Agustus' ? 'selected' : '' }}>Agustus</option>
                                            <option value="September" {{ old('birth_month', Auth::user()->birth_month) == 'September' ? 'selected' : '' }}>September</option>
                                            <option value="Oktober" {{ old('birth_month', Auth::user()->birth_month) == 'Oktober' ? 'selected' : '' }}>Oktober</option>
                                            <option value="November" {{ old('birth_month', Auth::user()->birth_month) == 'November' ? 'selected' : '' }}>November</option>
                                            <option value="Desember" {{ old('birth_month', Auth::user()->birth_month) == 'Desember' ? 'selected' : '' }}>Desember</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- Field: Birth Year --}}
                                <div>
                                    <label for="birth_year" class="block text-sm font-semibold text-gray-700 mb-2">Tahun</label>
                                    <div class="relative">
                                        <i class="fas fa-calendar absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                        <input type="number" 
                                               id="birth_year" 
                                               name="birth_year"
                                               value="{{ old('birth_year', Auth::user()->birth_year ?? '') }}"
                                               min="1950" 
                                               max="{{ date('Y') }}"
                                               class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 form-input"
                                               placeholder="Tahun">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-200">
                            <button type="submit" 
                                    class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg shadow-md hover:shadow-lg transition duration-200 flex items-center justify-center">
                                <i class="fas fa-save mr-2"></i>Simpan Perubahan
                            </button>
                            <a href="/dashboarduser" 
                               class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-6 py-3 rounded-lg shadow-md hover:shadow-lg transition duration-200 flex items-center justify-center">
                                <i class="fas fa-times mr-2"></i>Batal
                            </a>
                        </div>
                        
                    </div>
                </form>
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

                {{-- Banner Card: Beri Ulasan & Rating --}}
                <a href="javascript:void(0)" onclick="openReviewModal()" class="quick-action-item rounded-xl p-4 border border-amber-300 bg-amber-50/70 shadow-sm hover:shadow-md block relative overflow-hidden transition-all duration-200">
                    <div class="relative z-10 flex items-start space-x-3">
                        <div class="w-12 h-12 bg-amber-500 rounded-full flex items-center justify-center flex-shrink-0 shadow-md">
                            <i class="fas fa-star text-white text-lg"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900 mb-1">Beri Ulasan & Rating</h3>
                            <p class="text-xs text-gray-600">Bagikan kepuasan & pengalaman Anda bersama Olga Sehat!</p>
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
                        <a href="/venueuser" class="quick-action-item bg-white rounded-xl p-4 border border-gray-200 shadow-sm hover:shadow-md block">
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
                        <a href="/healthyuser" class="quick-action-item bg-white rounded-xl p-4 border border-gray-200 shadow-sm hover:shadow-md block">
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
                        <a href="/communityuser" class="quick-action-item bg-white rounded-xl p-4 border border-gray-200 shadow-sm hover:shadow-md block">
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
function previewImage(input) {
    const preview = document.getElementById('newImagePreview');
    const previewImg = document.getElementById('previewImg');
    const currentPreview = document.getElementById('currentImagePreview');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.classList.remove('hidden');
        }
        
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.classList.add('hidden');
    }
}

function togglePassword(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>

@endsection
