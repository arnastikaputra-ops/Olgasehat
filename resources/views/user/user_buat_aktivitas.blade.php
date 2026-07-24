<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>OLGA SEHAT — Buat Aktivitas Baru</title>
  <link rel="icon" href="{{ asset('assets/olgasehat-icon.png') }}" type="image/png" />
  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            blue: {
              700: '#013D9D',
              800: '#002D7A',
              900: '#001F5C',
            }
          }
        }
      }
    }
  </script>
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- AOS (Animate On Scroll) -->
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  @if(Auth::check())
  <style>
    /* Owner Avatar Styles - untuk user yang sudah login */
    .owner-topbar .owner-avatar-sm,
    .owner-topbar .owner-avatar-lg {
      display: flex;
      align-items: center;
      justify-content: center;
      background: #f1f5ff;
      color: #1c2a56;
      font-weight: 700;
      border-radius: 50%;
      overflow: hidden;
    }
    .owner-topbar .owner-avatar-sm {
      width: 36px;
      height: 36px;
      font-size: 0.9rem;
    }
    .owner-topbar .owner-avatar-lg {
      width: 64px;
      height: 64px;
      font-size: 1.25rem;
    }
    .owner-topbar .owner-avatar-sm img,
    .owner-topbar .owner-avatar-lg img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
    .owner-avatar-toggle {
      color: #1c2a56;
      font-weight: 600;
      display: flex;
      align-items: center;
    }
    .owner-avatar-toggle i {
      font-size: 0.7rem;
      margin-left: 0.5rem;
    }
    .owner-dropdown {
      width: 240px;
      border-radius: 18px;
      padding: 20px 20px 12px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    .owner-profile-card h6 {
      font-weight: 700;
      color: #1c2a56;
    }
    .badge-role {
      background: rgba(40, 200, 120, 0.15);
      color: #1f9d67;
      border-radius: 999px;
      font-weight: 600;
      font-size: 0.75rem;
      padding: 0.25rem 0.75rem;
    }
    .owner-dropdown .dropdown-item {
      border-radius: 12px;
      padding: 0.55rem 0.75rem;
      font-weight: 600;
      color: #1c2a56;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .owner-dropdown .dropdown-item:hover {
      background: #f1f5ff;
      color: #1c2a56;
    }
    .owner-dropdown .dropdown-item i {
      color: #9ca3af;
      font-size: 0.875rem;
    }
  </style>
  @endif
    <style>
        /* Small custom */
        .glass { background: rgba(255,255,255,0.9); backdrop-filter: blur(8px); }
        .select-card {
            /* Styling dasar */
            border: 2px solid transparent;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
        .select-card.selected {
            border-color: #0ea5e9; /* sky-500 */
            box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.2);
            position: relative;
        }
        .select-card.selected::after {
            content: "\f058"; /* check-circle icon */
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            top: 1rem;
            right: 1rem;
            color: #16a34a; /* green-600 */
            font-size: 1.25rem;
        }
        .select-card:not(.selected):hover {
            border-color: #bae6fd; /* sky-200 */
        }
        /* Style untuk status tidak berizin */
        .card-disabled {
            filter: grayscale(100%);
            opacity: 0.6;
            cursor: not-allowed;
        }
        .card-disabled:hover {
            border-color: transparent !important;
        }
    </style>
</head>
<body class="bg-white text-gray-800 font-sans">
@php
    $authUser = Auth::user();
    $role = $authUser->role ?? null;
    $roleLabels = [
        'user' => 'User',
        'pemiliklapangan' => 'Pemilik Lapangan',
        'pengelolakesehatan' => 'Pengelola Kesehatan',
        'superadmin' => 'Super Admin',
    ];
    $roleLabel = $role ? ($roleLabels[$role] ?? ucfirst($role)) : null;
    $dashboardLinks = [
        'user' => route('user.editprofile'),
        'pemiliklapangan' => url('/pemiliklapangan/dashboard'),
        'pengelolakesehatan' => url('/pengelolakesehatan/dashboard'),
        'superadmin' => route('admin'),
    ];
    $dashboardLink = $role ? ($dashboardLinks[$role] ?? null) : null;
    $logoutRoute = $role === 'user' ? route('user.logout') : route('logout');

    // Check if user is venue owner
    $is_venue_owner = $role === 'pemiliklapangan';
    $venue_owner_message = 'Fitur Membership hanya dapat dibuat oleh Pemilik Lapangan. Silakan daftar sebagai Pemilik Lapangan untuk mengakses fitur ini.';
@endphp

<!-- HEADER -->
<header id="mainHeader" class="fixed top-0 left-0 right-0 z-50 shadow-md bg-white transition-transform duration-300 ease-in-out">
  <div class="container mx-auto flex items-center justify-between py-4 px-6">

    <!-- Logo -->
    <a href="/" class="flex items-center space-x-2">
      <img src="{{ asset('assets/olgasehat-icon.png') }}" alt="Olga Sehat Logo" class="h-10 w-auto" />
    </a>

    <!-- Menu Desktop -->
    <nav class="hidden md:flex space-x-6 text-gray-700 font-medium">
      <a href="/venue" class="hover:text-blue-700 border-b-2 border-transparent hover:border-blue-700 pb-1 transition-colors duration-200" data-translate>Fasilitas Olahraga</a>
      <a href="/healthy" class="hover:text-blue-700 border-b-2 border-transparent hover:border-blue-700 pb-1 transition-colors duration-200" data-translate>Layanan Kesehatan</a>
      <a href="/community" class="hover:text-blue-700 border-b-2 border-transparent hover:border-blue-700 pb-1 transition-colors duration-200" data-translate>Komunitas & Aktivitas</a>
      <a href="/blog-news" class="hover:text-blue-700 border-b-2 border-transparent hover:border-blue-700 pb-1 transition-colors duration-200" data-translate>Info Sehat</a>
    </nav>

    <!-- Aksi Desktop -->
    <div class="hidden md:flex items-center space-x-4 relative">
      <!-- Language Selector -->
      <div class="relative">
        <button id="languageBtn" class="text-gray-700 hover:text-blue-700 focus:outline-none flex items-center space-x-2">
          <i class="fas fa-globe fa-lg"></i>
          <span id="currentLanguage">ID</span>
          <i class="fas fa-chevron-down text-sm"></i>
        </button>
        <div id="languageDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white border rounded-md shadow-lg z-50 max-h-60 overflow-y-auto">
          <!-- Daftar bahasa akan diisi oleh JavaScript -->
        </div>
      </div>

      @if($authUser)
        <!-- Dropdown Authenticated -->
        <div class="relative owner-topbar">
          <button id="userMenuBtn" class="owner-avatar-toggle focus:outline-none" data-toggle="dropdown">
            <div class="owner-avatar-sm mr-2">
              @if($authUser->image ?? false)
                <img src="{{ asset('storage/' . $authUser->image) }}" alt="{{ $authUser->name }}">
              @else
                <span>{{ strtoupper(substr($authUser->name ?? 'U', 0, 1)) }}</span>
              @endif
            </div>
            <span class="owner-name hidden md:inline">{{ $authUser->name ?? 'Pengguna' }}</span>
            <i class="fas fa-chevron-down"></i>
          </button>
          <!-- Dropdown menu -->
          <div id="userMenu" class="hidden absolute right-0 mt-2 owner-dropdown bg-white shadow-lg border-0 z-50">
            <div class="owner-profile-card text-center mb-3">
              <div class="owner-avatar-lg mx-auto mb-2">
                @if($authUser->image ?? false)
                  <img src="{{ asset('storage/' . $authUser->image) }}" alt="{{ $authUser->name }}">
                @else
                  <span>{{ strtoupper(substr($authUser->name ?? 'U', 0, 1)) }}</span>
                @endif
              </div>
              <h6 class="mb-0">{{ $authUser->name ?? 'Pengguna' }}</h6>
              @if($roleLabel)
                <span class="badge badge-role mt-1">{{ $roleLabel }}</span>
              @endif
            </div>

            @if($role === 'user')
              <a href="/dashboarduser" class="dropdown-item">
                <span data-translate>Profil</span>
                <i class="fas fa-user"></i>
              </a>
              <a href="/riwayatpayment" class="dropdown-item" style="background-color: #dbeafe; color: #1e40af;">
                <span data-translate>Riwayat Pemesanan</span>
                <i class="fas fa-history"></i>
              </a>
              <div class="dropdown-divider my-2" style="border-top: 1px solid #e5e7eb;"></div>
              <a href="/riwayatkontrol" class="dropdown-item" style="background-color: #d1fae5; color: #065f46;">
                <span data-translate>Riwayat Kontrol</span>
                <i class="fas fa-clipboard-list"></i>
              </a>
              <a href="/riwayat-komunitas" class="dropdown-item">
                <span data-translate>Komunitas</span>
                <i class="fas fa-users"></i>
              </a>
              <a href="/riwayatmembership" class="dropdown-item">
                <span data-translate>Membership</span>
                <i class="fas fa-crown"></i>
              </a>
              <a href="/settings" class="dropdown-item">
                <span data-translate>Pengaturan</span>
                <i class="fas fa-cog"></i>
              </a>
            @else
              @if($dashboardLink)
                <a href="{{ $dashboardLink }}" class="dropdown-item">
                  <span data-translate>Ke Dashboard</span>
                  <i class="fas fa-columns"></i>
                </a>
              @endif
              @if($role === 'pemiliklapangan')
                <a href="{{ route('fasilitas') }}" class="dropdown-item">
                  <span data-translate>Kelola Fasilitas</span>
                  <i class="fas fa-home"></i>
                </a>
                <a href="/pemiliklapangan/pengaturan" class="dropdown-item">
                  <span data-translate>Pengaturan Pemilik</span>
                  <i class="fas fa-cog"></i>
                </a>
              @elseif($role === 'pengelolakesehatan')
                <a href="/pengelolakesehatan/layanan" class="dropdown-item">
                  <span data-translate>Kelola Layanan</span>
                  <i class="fas fa-stethoscope"></i>
                </a>
                <a href="/pengelolakesehatan/pengaturan" class="dropdown-item">
                  <span data-translate>Pengaturan Klinik</span>
                  <i class="fas fa-cog"></i>
                </a>
              @elseif($role === 'superadmin')
                <a href="{{ route('admin') }}" class="dropdown-item">
                  <span data-translate>Backoffice Admin</span>
                  <i class="fas fa-lock"></i>
                </a>
                <a href="{{ route('admin.users.list') }}" class="dropdown-item">
                  <span data-translate>Kelola Pengguna</span>
                  <i class="fas fa-users-cog"></i>
                </a>
              @endif
            @endif

            <div class="dropdown-divider my-2" style="border-top: 1px solid #e5e7eb;"></div>
            <form id="logout-form" action="{{ $logoutRoute }}" method="POST">
              @csrf
              <button type="submit" class="dropdown-item w-full text-left text-red-600 hover:bg-red-50" style="border: none; background: none; cursor: pointer;">
                <span data-translate>Logout</span>
                <i class="fas fa-sign-out-alt"></i>
              </button>
            </form>
          </div>
        </div>
      @else
        <!-- Register Dropdown -->
        <div class="relative">
          <button id="registerBtn" class="text-gray-700 hover:text-blue-700 focus:outline-none" data-translate>Daftar</button>
          <div id="registerDropdown"
            class="hidden absolute right-0 mt-2 w-60 bg-white border rounded-md shadow-lg z-50
                   transform scale-95 opacity-0 transition-all duration-200 ease-out">
            <a href="/daftaruser" class="block px-4 py-2 text-gray-700 hover:bg-gray-100" data-translate>Akun User</a>
            <a href="/regispengelola" class="block px-4 py-2 text-gray-700 hover:bg-gray-100" data-translate>Akun Pengelola Venue</a>
            <a href="/regispengelolakesehatan" class="block px-4 py-2 text-gray-700 hover:bg-gray-100" data-translate>Akun Pengelola Kesehatan</a>
          </div>
        </div>

        <!-- Login Dropdown -->
        <div class="relative">
          <button id="loginBtn"
            class="bg-blue-700 text-white px-4 py-2 rounded-md hover:bg-blue-800 transition focus:outline-none" data-translate>
            Masuk
          </button>
          <div id="loginDropdown"
            class="hidden absolute right-0 mt-2 w-56 bg-white border rounded-md shadow-lg z-50
                   transform scale-95 opacity-0 transition-all duration-200 ease-out">
            <a href="/loginuser" class="block px-4 py-2 text-gray-700 hover:bg-gray-100" data-translate>Masuk User</a>
            <a href="/loginpengelolavenue" class="block px-4 py-2 text-gray-700 hover:bg-gray-100" data-translate>Masuk Pengelola Venue</a>
            <a href="/loginpengelolakesehatan" class="block px-4 py-2 text-gray-700 hover:bg-gray-100" data-translate>Masuk Pengelola Kesehatan</a>
          </div>
        </div>
      @endif
    </div>

    <!-- Header Mobile & Tablet -->
    <div class="flex lg:hidden items-center space-x-2 sm:space-x-3 md:space-x-4 ml-auto">
      <!-- Language Selector Mobile -->
      <div class="relative">
        <button id="languageBtnMobile" class="text-gray-700 hover:text-blue-700 focus:outline-none flex items-center space-x-1 sm:space-x-2">
          <i class="fas fa-globe text-base sm:text-lg"></i>
          <span id="currentLanguageMobile" class="text-xs sm:text-sm font-medium">ID</span>
          <i class="fas fa-chevron-down text-xs"></i>
        </button>
        <div id="languageDropdownMobile" class="hidden absolute right-0 mt-2 w-40 sm:w-48 bg-white border rounded-md shadow-lg z-50 max-h-60 overflow-y-auto">
          <!-- Daftar bahasa akan diisi oleh JavaScript -->
        </div>
      </div>

      @if($authUser)
        <!-- Dropdown User Mobile -->
        <div class="relative owner-topbar">
          <button id="mobileUserBtn" class="owner-avatar-toggle focus:outline-none">
            <div class="owner-avatar-sm mr-1 sm:mr-2" style="width: 28px; height: 28px; font-size: 0.75rem;">
              @if($authUser->image ?? false)
                <img src="{{ asset('storage/' . $authUser->image) }}" alt="{{ $authUser->name }}">
              @else
                <span>{{ strtoupper(substr($authUser->name ?? 'U', 0, 1)) }}</span>
              @endif
            </div>
            <i class="fas fa-chevron-down text-xs sm:text-sm"></i>
          </button>
          <!-- Dropdown menu -->
          <div id="mobileUserMenu" class="hidden absolute right-0 mt-2 owner-dropdown bg-white shadow-lg border-0 z-50" style="width: 200px; padding: 16px 16px 8px;">
            <div class="owner-profile-card text-center mb-3">
              <div class="owner-avatar-lg mx-auto mb-2" style="width: 56px; height: 56px; font-size: 1.1rem;">
                @if($authUser->image ?? false)
                  <img src="{{ asset('storage/' . $authUser->image) }}" alt="{{ $authUser->name }}">
                @else
                  <span>{{ strtoupper(substr($authUser->name ?? 'U', 0, 1)) }}</span>
                @endif
              </div>
              <h6 class="mb-0" style="font-size: 0.875rem;">{{ $authUser->name ?? 'Pengguna' }}</h6>
              @if($roleLabel)
                <span class="badge badge-role mt-1">{{ $roleLabel }}</span>
              @endif
            </div>
            @if($role === 'user')
              <a href="/dashboarduser" class="dropdown-item" style="font-size: 0.875rem;">
                <span data-translate>Profil</span>
                <i class="fas fa-user"></i>
              </a>
              <a href="/riwayatpayment" class="dropdown-item" style="font-size: 0.875rem; background-color: #dbeafe !important; color: #1e40af !important; border-radius: 8px; font-weight: 500;">
                <span data-translate>Riwayat Pemesanan</span>
                <i class="fas fa-history"></i>
              </a>
              <div class="dropdown-divider my-2" style="border-top: 1px solid #e5e7eb;"></div>
              <a href="/riwayatkontrol" class="dropdown-item" style="font-size: 0.875rem; background-color: #d1fae5 !important; color: #065f46 !important; border-radius: 8px; font-weight: 500;">
                <span data-translate>Riwayat Kontrol</span>
                <i class="fas fa-clipboard-list"></i>
              </a>
              <a href="/riwayat-komunitas" class="dropdown-item" style="font-size: 0.875rem;">
                <span data-translate>Komunitas</span>
                <i class="fas fa-users"></i>
              </a>
              <a href="/riwayatmembership" class="dropdown-item" style="font-size: 0.875rem;">
                <span data-translate>Membership</span>
                <i class="fas fa-crown"></i>
              </a>
              <a href="/settings" class="dropdown-item" style="font-size: 0.875rem;">
                <span data-translate>Pengaturan</span>
                <i class="fas fa-cog"></i>
              </a>
            @else
              @if($dashboardLink)
                <a href="{{ $dashboardLink }}" class="dropdown-item" style="font-size: 0.875rem;">
                  <span data-translate>Ke Dashboard</span>
                  <i class="fas fa-columns"></i>
                </a>
              @endif
              @if($role === 'pemiliklapangan')
                <a href="{{ route('fasilitas') }}" class="dropdown-item" style="font-size: 0.875rem;">
                  <span data-translate>Kelola Fasilitas</span>
                  <i class="fas fa-home"></i>
                </a>
                <a href="/pemiliklapangan/pengaturan" class="dropdown-item" style="font-size: 0.875rem;">
                  <span data-translate>Pengaturan Pemilik</span>
                  <i class="fas fa-cog"></i>
                </a>
              @elseif($role === 'pengelolakesehatan')
                <a href="/pengelolakesehatan/layanan" class="dropdown-item" style="font-size: 0.875rem;">
                  <span data-translate>Kelola Layanan</span>
                  <i class="fas fa-stethoscope"></i>
                </a>
                <a href="/pengelolakesehatan/pengaturan" class="dropdown-item" style="font-size: 0.875rem;">
                  <span data-translate>Pengaturan Klinik</span>
                  <i class="fas fa-cog"></i>
                </a>
              @elseif($role === 'superadmin')
                <a href="{{ route('admin') }}" class="dropdown-item" style="font-size: 0.875rem;">
                  <span data-translate>Backoffice Admin</span>
                  <i class="fas fa-lock"></i>
                </a>
                <a href="{{ route('admin.users.list') }}" class="dropdown-item" style="font-size: 0.875rem;">
                  <span data-translate>Kelola Pengguna</span>
                  <i class="fas fa-users-cog"></i>
                </a>
              @endif
            @endif
            <div class="dropdown-divider my-2" style="border-top: 1px solid #e5e7eb;"></div>
            <form id="mobile-logout-form" action="{{ $logoutRoute }}" method="POST">
              @csrf
              <button type="submit" class="dropdown-item w-full text-left text-red-600 hover:bg-red-50" style="border: none; background: none; cursor: pointer; font-size: 0.875rem;">
                <span data-translate>Logout</span>
                <i class="fas fa-sign-out-alt"></i>
              </button>
            </form>
          </div>
        </div>
      @endif

      <!-- Tombol Hamburger -->
      <button id="mobileMenuBtn"
              class="text-gray-700 hover:text-blue-700 focus:outline-none p-1 sm:p-2"
              aria-label="Open menu">
        <i class="fas fa-bars text-lg sm:text-xl"></i>
      </button>
    </div>

    <!-- Menu Navigasi Mobile -->
    <nav id="mobileMenu"
         class="hidden flex-col lg:hidden bg-white border-t border-gray-200 shadow-md 
                transition-all duration-300 ease-in-out absolute top-full left-0 w-full z-[50] max-h-[calc(100vh-4rem)] overflow-y-auto">

      <!-- Link Navigasi -->
      <a href="/venue" class="block px-4 sm:px-6 py-3 sm:py-4 border-b text-center font-medium text-sm sm:text-base text-gray-700 hover:bg-blue-50 hover:text-blue-700 menu-item opacity-0 translate-y-1 transition-all duration-200" data-translate>Fasilitas Olahraga</a>
      <a href="/healthy" class="block px-4 sm:px-6 py-3 sm:py-4 border-b text-center font-medium text-sm sm:text-base text-gray-700 hover:bg-blue-50 hover:text-blue-700 menu-item opacity-0 translate-y-1 transition-all duration-200" data-translate>Layanan Kesehatan</a>
      <a href="/community" class="block px-4 sm:px-6 py-3 sm:py-4 border-b text-center font-medium text-sm sm:text-base text-gray-700 hover:bg-blue-50 hover:text-blue-700 menu-item opacity-0 translate-y-1 transition-all duration-200" data-translate>Komunitas & Aktivitas</a>
      <a href="/blog-news" class="block px-4 sm:px-6 py-3 sm:py-4 border-b text-center font-medium text-sm sm:text-base text-gray-700 hover:bg-blue-50 hover:text-blue-700 menu-item opacity-0 translate-y-1 transition-all duration-200" data-translate>Info Sehat</a>

      @if($authUser)
        <div class="border-t pt-3 sm:pt-4 px-4 sm:px-6 pb-4 space-y-3">
          @if($dashboardLink)
            <a href="{{ $dashboardLink }}" class="block w-full px-4 sm:px-6 py-3 sm:py-4 text-center text-sm sm:text-base bg-blue-700 text-white font-semibold rounded-md hover:bg-blue-800 menu-item opacity-0 translate-y-1 transition-all duration-200" data-translate>Ke Dashboard</a>
          @endif
          <form action="{{ $logoutRoute }}" method="POST">
            @csrf
            <button type="submit" class="w-full px-4 sm:px-6 py-3 sm:py-4 text-center text-sm sm:text-base bg-red-600 text-white font-semibold rounded-md hover:bg-red-700 menu-item opacity-0 translate-y-1 transition-all duration-200" data-translate>Logout</button>
          </form>
        </div>
      @else
        <!-- Masuk and Daftar Buttons -->
        <div class="border-t pt-3 sm:pt-4 px-4 sm:px-6 pb-4">
          <div class="mb-3">
            <p class="text-sm font-semibold text-gray-600 mb-2" data-translate>Masuk Sebagai:</p>
            <a href="/loginuser" class="block w-full px-4 sm:px-6 py-3 sm:py-4 text-center text-sm sm:text-base text-blue-700 font-medium border border-blue-700 rounded-md hover:bg-blue-50 mb-2 menu-item opacity-0 translate-y-1 transition-all duration-200" data-translate>Masuk User</a>
            <a href="/loginpengelolavenue" class="block w-full px-4 sm:px-6 py-3 sm:py-4 text-center text-sm sm:text-base text-blue-700 font-medium border border-blue-700 rounded-md hover:bg-blue-50 mb-2 menu-item opacity-0 translate-y-1 transition-all duration-200" data-translate>Masuk Pengelola Venue</a>
            <a href="/loginpengelolakesehatan" class="block w-full px-4 sm:px-6 py-3 sm:py-4 text-center text-sm sm:text-base text-blue-700 font-medium border border-blue-700 rounded-md hover:bg-blue-50 mb-2 menu-item opacity-0 translate-y-1 transition-all duration-200" data-translate>Masuk Pengelola Kesehatan</a>
          </div>
          <div>
            <p class="text-sm font-semibold text-gray-600 mb-2" data-translate>Daftar Sebagai:</p>
            <a href="/daftaruser" class="block w-full px-4 sm:px-6 py-3 sm:py-4 text-center text-sm sm:text-base bg-blue-700 text-white font-medium rounded-md hover:bg-blue-800 mb-2 menu-item opacity-0 translate-y-1 transition-all duration-200" data-translate>Daftar User</a>
            <a href="/regispengelola" class="block w-full px-4 sm:px-6 py-3 sm:py-4 text-center text-sm sm:text-base bg-blue-700 text-white font-medium rounded-md hover:bg-blue-800 mb-2 menu-item opacity-0 translate-y-1 transition-all duration-200" data-translate>Daftar Pengelola Venue</a>
            <a href="/regispengelolakesehatan" class="block w-full px-4 sm:px-6 py-3 sm:py-4 text-center text-sm sm:text-base bg-blue-700 text-white font-medium rounded-md hover:bg-blue-800 menu-item opacity-0 translate-y-1 transition-all duration-200" data-translate>Daftar Pengelola Kesehatan</a>
          </div>
        </div>
      @endif
    </nav>
</header>

    <main class="max-w-5xl mx-auto px-6 pt-24 pb-24">

        <h2 class="text-3xl md:text-4xl font-bold text-slate-800">Buat Aktivitas Baru</h2>
        <p class="text-lg text-slate-600 mt-2 border-b pb-4">Pilih jenis aktivitas yang ingin kamu publikasikan. Aktifkan fitur Olahraga Anda sekarang!</p>

        <section class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-8">
            
            {{-- Kartu 1: Komunitas --}}
            <button data-type="komunitas" class="select-card group rounded-2xl p-6 text-left bg-white transition focus:outline-none">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="text-2xl mb-2 text-green-600"><i class="fas fa-users"></i></div>
                        <h3 class="text-xl font-semibold text-slate-800">Komunitas</h3>
                        <p class="text-sm text-slate-500 mt-1">Buat grup olahraga rutin (Futsal, Basket, Lari, dll.).</p>
                        <p class="text-xs text-green-600 font-medium mt-3">GRATIS UNTUK SEMUA USER</p>
                    </div>
                </div>
            </button>

            {{-- Kartu 2: Membership (Khusus Pemilik Lapangan) --}}
            <button 
                data-type="membership" 
                @if(!$is_venue_owner) disabled @endif
                class="select-card group rounded-2xl p-6 text-left bg-white transition focus:outline-none @if(!$is_venue_owner) card-disabled @endif"
            >
                <div class="flex items-start justify-between">
                    <div>
                        <div class="text-2xl mb-2 text-amber-600"><i class="fas fa-credit-card"></i></div>
                        <h3 class="text-xl font-semibold text-slate-800">Membership</h3>
                        <p class="text-sm text-slate-500 mt-1">Buat paket keanggotaan/langganan untuk Lapangan Anda.</p>
                        <p class="text-xs text-amber-600 font-medium mt-3">KHUSUS PEMILIK LAPANGAN</p>
                    </div>
                    @if(!$is_venue_owner)
                        <div class="text-red-500 font-semibold absolute top-4 right-4 text-xs">Akses Ditolak</div>
                    @endif
                </div>
            </button>

            {{-- Kartu 3: Event Olahraga --}}
            <button data-type="event" class="select-card group rounded-2xl p-6 text-left bg-white transition focus:outline-none">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="text-2xl mb-2 text-red-600"><i class="fas fa-calendar-alt"></i></div>
                        <h3 class="text-xl font-semibold text-slate-800">Event Olahraga</h3>
                        <p class="text-sm text-slate-500 mt-1">Buat turnamen, fun run, atau latihan bersama berbayar/gratis.</p>
                        <p class="text-xs text-red-600 font-medium mt-3">TERBUKA UNTUK SEMUA USER</p>
                    </div>
                </div>
            </button>
        </section>

        @if(!$is_venue_owner)
            <div class="mt-8 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-md shadow-inner" role="alert">
                <p class="font-bold">Perhatian!</p>
                <p>{{ $venue_owner_message }}</p>
            </div>
        @endif

        <section id="forms" class="mt-12">

            <form id="form-komunitas" class="hidden bg-white p-8 rounded-2xl shadow-xl glass border border-slate-100" action="{{ route('activities.store.user') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="jenis" value="komunitas">
                <h2 class="text-2xl font-bold text-sky-800 border-b pb-3 mb-4">Detail Komunitas Baru</h2>
                
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                {{-- Form fields: Nama, Kategori, Lokasi, Biaya --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Komunitas <span class="text-red-500">*</span></label>
                        <input name="nama" class="w-full rounded-lg border border-slate-300 p-3 focus:border-sky-500 focus:ring-sky-500" placeholder="Contoh: Kumpulan Pemuda Futsal" required value="{{ old('nama') }}">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Kategori Olahraga <span class="text-red-500">*</span></label>
                        <select name="kategori" class="w-full rounded-lg border border-slate-300 p-3 focus:border-sky-500 focus:ring-sky-500" required>
                            <option value="">Pilih Kategori</option>
                            <option value="Futsal" {{ old('kategori') == 'Futsal' ? 'selected' : '' }}>Futsal</option>
                            <option value="Basket" {{ old('kategori') == 'Basket' ? 'selected' : '' }}>Basket</option>
                            <option value="Yoga" {{ old('kategori') == 'Yoga' ? 'selected' : '' }}>Yoga</option>
                            <option value="Gym" {{ old('kategori') == 'Gym' ? 'selected' : '' }}>Gym</option>
                            <option value="Lainnya" {{ old('kategori') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Lokasi Kegiatan</label>
                        <input name="lokasi" class="w-full rounded-lg border border-slate-300 p-3 focus:border-sky-500 focus:ring-sky-500" placeholder="Kota Denpasar, Bali" value="{{ old('lokasi') }}">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Biaya Bergabung</label>
                        <div class="flex items-center gap-4 mt-2 h-[48px]">
                            <label class="flex items-center gap-2">
                                <input type="radio" name="biaya" value="gratis" class="form-radio text-sky-600 biaya-radio" {{ old('biaya', 'gratis') == 'gratis' ? 'checked' : '' }}>
                                <span class="text-sm text-slate-600">Gratis</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="radio" name="biaya" value="berbayar" class="form-radio text-sky-600 biaya-radio" {{ old('biaya') == 'berbayar' ? 'checked' : '' }}>
                                <span class="text-sm text-slate-600">Berbayar (misal: iuran)</span>
                            </label>
                        </div>
                    </div>
                    <div id="harga-komunitas-container" class="{{ old('biaya') == 'berbayar' ? '' : 'hidden' }}">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Harga (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" name="harga" class="w-full rounded-lg border border-slate-300 p-3 focus:border-sky-500 focus:ring-sky-500" placeholder="Contoh: 50000" min="0" value="{{ old('harga') }}" {{ old('biaya') == 'berbayar' ? 'required' : '' }}>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Deskripsi Lengkap <span class="text-red-500">*</span></label>
                        <textarea name="deskripsi" rows="4" class="w-full rounded-lg border border-slate-300 p-3 focus:border-sky-500 focus:ring-sky-500" placeholder="Tulis ringkasan komunitas, jadwal, dan manfaatnya" required>{{ old('deskripsi') }}</textarea>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">
                            <i class="fas fa-link mr-2 text-sky-600"></i>Link Grup WhatsApp (Untuk Bergabung)
                        </label>
                        <input name="link" class="w-full rounded-lg border border-slate-300 p-3 focus:border-sky-500 focus:ring-sky-500" placeholder="https://chat.whatsapp.com/..." value="{{ old('link') }}">
                        <p class="text-xs text-gray-500 mt-1">
                            <i class="fas fa-info-circle mr-1"></i>Link ini akan digunakan untuk tombol "Bergabung"
                        </p>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">
                            <i class="fas fa-phone-alt mr-2 text-sky-600"></i>Kontak & Informasi Lainnya (Opsional)
                        </label>
                        <input name="link_kontak_2" class="w-full rounded-lg border border-slate-300 p-3 focus:border-sky-500 focus:ring-sky-500" placeholder="https://wa.me/.. atau @akuninstagram atau link lainnya" value="{{ old('link_kontak_2') }}">
                        <p class="text-xs text-gray-500 mt-1">
                            <i class="fas fa-info-circle mr-1"></i>Link kontak tambahan (Instagram, WhatsApp personal, dll.)
                        </p>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Upload Banner Komunitas (Max 2MB)</label>
                        <input type="file" name="banner" id="komunitas-image" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100" accept="image/*">
                        <img id="komunitas-image-preview" class="mt-4 rounded-lg max-h-40 object-cover hidden shadow-md" alt="Preview Banner">
                    </div>

                    <div class="md:col-span-2 flex items-center gap-4 pt-4">
                        <button type="submit" class="bg-sky-600 hover:bg-sky-700 text-white font-bold px-6 py-3 rounded-xl transition duration-200">
                            <i class="fas fa-check-circle mr-2"></i> Simpan Komunitas
                        </button>
                        <button type="button" class="border border-slate-300 text-slate-600 hover:bg-slate-100 px-6 py-3 rounded-xl transition duration-200" onclick="resetForms()">Batal</button>
                    </div>
                </div>
            </form>

            <form id="form-membership" class="hidden bg-white p-8 rounded-2xl shadow-xl glass border border-slate-100" action="{{ route('activities.store.user') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="jenis" value="membership">
                <h2 class="text-2xl font-bold text-sky-800 border-b pb-3 mb-4">Detail Paket Membership</h2>
                
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                {{-- Form fields: Nama, Lokasi, Harga, Durasi --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Paket Membership <span class="text-red-500">*</span></label>
                        <input name="nama" class="w-full rounded-lg border border-slate-300 p-3 focus:border-sky-500 focus:ring-sky-500" placeholder="Contoh: GOLD PASS Bulanan" required value="{{ old('nama') }}">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Kategori Olahraga <span class="text-red-500">*</span></label>
                        <select name="kategori" class="w-full rounded-lg border border-slate-300 p-3 focus:border-sky-500 focus:ring-sky-500" required>
                            <option value="">Pilih Kategori</option>
                            <option value="Futsal" {{ old('kategori') == 'Futsal' ? 'selected' : '' }}>Futsal</option>
                            <option value="Basket" {{ old('kategori') == 'Basket' ? 'selected' : '' }}>Basket</option>
                            <option value="Yoga" {{ old('kategori') == 'Yoga' ? 'selected' : '' }}>Yoga</option>
                            <option value="Gym" {{ old('kategori') == 'Gym' ? 'selected' : '' }}>Gym</option>
                            <option value="Lainnya" {{ old('kategori') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Lokasi <span class="text-red-500">*</span></label>
                        <input name="lokasi" class="w-full rounded-lg border border-slate-300 p-3 focus:border-sky-500 focus:ring-sky-500" placeholder="Lapangan A - Denpasar" required value="{{ old('lokasi') }}">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Biaya Bergabung</label>
                        <div class="flex items-center gap-4 mt-2 h-[48px]">
                            <label class="flex items-center gap-2">
                                <input type="radio" name="biaya" value="gratis" class="form-radio text-sky-600" {{ old('biaya', 'berbayar') == 'gratis' ? 'checked' : '' }}>
                                <span class="text-sm text-slate-600">Gratis</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="radio" name="biaya" value="berbayar" class="form-radio text-sky-600" {{ old('biaya', 'berbayar') == 'berbayar' ? 'checked' : '' }}>
                                <span class="text-sm text-slate-600">Berbayar</span>
                            </label>
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Deskripsi Lengkap <span class="text-red-500">*</span></label>
                        <textarea name="deskripsi" rows="4" class="w-full rounded-lg border border-slate-300 p-3 focus:border-sky-500 focus:ring-sky-500" placeholder="Tuliskan semua keuntungan yang didapat member, cth: akses malam, diskon sewa lapangan, free sparing" required>{{ old('deskripsi') }}</textarea>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">
                            <i class="fas fa-link mr-2 text-sky-600"></i>Link Grup WhatsApp (Untuk Bergabung)
                        </label>
                        <input name="link" class="w-full rounded-lg border border-slate-300 p-3 focus:border-sky-500 focus:ring-sky-500" placeholder="https://chat.whatsapp.com/..." value="{{ old('link') }}">
                        <p class="text-xs text-gray-500 mt-1">
                            <i class="fas fa-info-circle mr-1"></i>Link ini akan digunakan untuk tombol "Bergabung"
                        </p>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">
                            <i class="fas fa-phone-alt mr-2 text-sky-600"></i>Kontak & Informasi Lainnya (Opsional)
                        </label>
                        <input name="link_kontak_2" class="w-full rounded-lg border border-slate-300 p-3 focus:border-sky-500 focus:ring-sky-500" placeholder="https://wa.me/.. atau @akuninstagram atau link lainnya" value="{{ old('link_kontak_2') }}">
                        <p class="text-xs text-gray-500 mt-1">
                            <i class="fas fa-info-circle mr-1"></i>Link kontak tambahan (Instagram, WhatsApp personal, dll.)
                        </p>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Upload Banner Kartu (Max 2MB)</label>
                        <input type="file" name="banner" id="membership-image" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100" accept="image/*">
                        <img id="membership-image-preview" class="mt-4 rounded-lg max-h-40 object-cover hidden shadow-md" alt="Preview Banner">
                    </div>
                    
                    <div class="md:col-span-2 flex items-center gap-4 pt-4">
                        <button type="submit" class="bg-sky-600 hover:bg-sky-700 text-white font-bold px-6 py-3 rounded-xl transition duration-200">
                            <i class="fas fa-check-circle mr-2"></i> Simpan Membership
                        </button>
                        <button type="button" class="border border-slate-300 text-slate-600 hover:bg-slate-100 px-6 py-3 rounded-xl transition duration-200" onclick="resetForms()">Batal</button>
                    </div>
                </div>
            </form>

            <form id="form-event" class="hidden bg-white p-8 rounded-2xl shadow-xl glass border border-slate-100" action="{{ route('activities.store.user') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="jenis" value="event">
                <h2 class="text-2xl font-bold text-sky-800 border-b pb-3 mb-4">Detail Event Olahraga</h2>
                
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                {{-- Form fields: Nama, Jenis, Waktu, Lokasi, Kapasitas, Biaya --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Event <span class="text-red-500">*</span></label>
                        <input name="nama" class="w-full rounded-lg border border-slate-300 p-3 focus:border-sky-500 focus:ring-sky-500" placeholder="Contoh: Turnamen Futsal Bali 2025" required value="{{ old('nama') }}">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Kategori Olahraga <span class="text-red-500">*</span></label>
                        <select name="kategori" class="w-full rounded-lg border border-slate-300 p-3 focus:border-sky-500 focus:ring-sky-500" required>
                            <option value="">Pilih Kategori</option>
                            <option value="Futsal" {{ old('kategori') == 'Futsal' ? 'selected' : '' }}>Futsal</option>
                            <option value="Basket" {{ old('kategori') == 'Basket' ? 'selected' : '' }}>Basket</option>
                            <option value="Run / Fun Run" {{ old('kategori') == 'Run / Fun Run' ? 'selected' : '' }}>Run / Fun Run</option>
                            <option value="Gym Challenge" {{ old('kategori') == 'Gym Challenge' ? 'selected' : '' }}>Gym Challenge</option>
                            <option value="Lainnya" {{ old('kategori') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Lokasi Event <span class="text-red-500">*</span></label>
                        <input name="lokasi" class="w-full rounded-lg border border-slate-300 p-3 focus:border-sky-500 focus:ring-sky-500" placeholder="Lapangan GOR Ngurah Rai" required value="{{ old('lokasi') }}">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Biaya Bergabung</label>
                        <div class="flex items-center gap-4 mt-2 h-[48px]">
                            <label class="flex items-center gap-2">
                                <input type="radio" name="biaya" value="gratis" class="form-radio text-sky-600 biaya-radio-event" {{ old('biaya', 'gratis') == 'gratis' ? 'checked' : '' }}>
                                <span class="text-sm text-slate-600">Gratis</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="radio" name="biaya" value="berbayar" class="form-radio text-sky-600 biaya-radio-event" {{ old('biaya') == 'berbayar' ? 'checked' : '' }}>
                                <span class="text-sm text-slate-600">Berbayar</span>
                            </label>
                        </div>
                    </div>
                    <div id="harga-event-container" class="{{ old('biaya') == 'berbayar' ? '' : 'hidden' }}">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Harga (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" name="harga" class="w-full rounded-lg border border-slate-300 p-3 focus:border-sky-500 focus:ring-sky-500" placeholder="Contoh: 100000" min="0" value="{{ old('harga') }}" {{ old('biaya') == 'berbayar' ? 'required' : '' }}>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Deskripsi & Aturan Lengkap <span class="text-red-500">*</span></label>
                        <textarea name="deskripsi" rows="4" class="w-full rounded-lg border border-slate-300 p-3 focus:border-sky-500 focus:ring-sky-500" placeholder="Detail event, hadiah, aturan pendaftaran" required>{{ old('deskripsi') }}</textarea>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">
                            <i class="fas fa-link mr-2 text-sky-600"></i>Link Grup WhatsApp (Untuk Bergabung)
                        </label>
                        <input name="link" class="w-full rounded-lg border border-slate-300 p-3 focus:border-sky-500 focus:ring-sky-500" placeholder="https://chat.whatsapp.com/..." value="{{ old('link') }}">
                        <p class="text-xs text-gray-500 mt-1">
                            <i class="fas fa-info-circle mr-1"></i>Link ini akan digunakan untuk tombol "Bergabung"
                        </p>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">
                            <i class="fas fa-phone-alt mr-2 text-sky-600"></i>Kontak & Informasi Lainnya (Opsional)
                        </label>
                        <input name="link_kontak_2" class="w-full rounded-lg border border-slate-300 p-3 focus:border-sky-500 focus:ring-sky-500" placeholder="https://wa.me/.. atau @akuninstagram atau link lainnya" value="{{ old('link_kontak_2') }}">
                        <p class="text-xs text-gray-500 mt-1">
                            <i class="fas fa-info-circle mr-1"></i>Link kontak tambahan (Instagram, WhatsApp personal, dll.)
                        </p>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Upload Poster Event (Max 2MB)</label>
                        <input type="file" name="banner" id="event-image" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100" accept="image/*">
                        <img id="event-image-preview" class="mt-4 rounded-lg max-h-40 object-cover hidden shadow-md" alt="Preview Poster">
                    </div>

                    <div class="md:col-span-2 flex items-center gap-4 pt-4">
                        <button type="submit" class="bg-sky-600 hover:bg-sky-700 text-white font-bold px-6 py-3 rounded-xl transition duration-200">
                            <i class="fas fa-check-circle mr-2"></i> Simpan Event
                        </button>
                        <button type="button" class="border border-slate-300 text-slate-600 hover:bg-slate-100 px-6 py-3 rounded-xl transition duration-200" onclick="resetForms()">Batal</button>
                    </div>
                </div>
            </form>

        </section>

    </div>
  </main>

  <footer class="bg-white text-gray-700 py-16">
  <div class="container mx-auto px-6 grid grid-cols-1 md:grid-cols-4 gap-12">
    <div>
      {{-- Ganti teks H3 ini dengan gambar logo --}}
      <img src="{{ asset('assets/olgasehat-icon.png') }}" alt="Olga Sehat Logo" class="h-10 w-auto mb-4" />

      <p class="text-base text-gray-600 max-w-xs">
        Making sports easy to book, fun to play, and safe for everyone.
      </p>
    </div>
    <div>
      <h3 class="font-semibold text-lg mb-4 text-gray-800" data-translate>Perusahaan</h3>
      <ul class="space-y-3 text-base">
        <li><a href="{{ route('tentang') }}" class="hover:text-blue-700" data-translate>Tentang</a></li>
        <li><a href="#" class="hover:text-blue-700" data-translate>Kebijakan &amp; Privasi</a></li>
        <li><a href="#" class="hover:text-blue-700" data-translate>Syarat &amp; Ketentuan</a></li>
      </ul>
    </div>
    <div>
      <h3 class="font-semibold text-lg mb-4 text-gray-800" data-translate>Ekosistem</h3>
      <ul class="space-y-3 text-base">
        <li><a href="/venue" class="hover:text-blue-700" data-translate>Fasilitas Olahraga</a></li>
        <li><a href="/healthy" class="hover:text-blue-700" data-translate>Layanan Kesehatan</a></li>
        <li><a href="/community" class="hover:text-blue-700" data-translate>Komunitas & Aktivitas</a></li>
        <li><a href="/blog-news" class="hover:text-blue-700" data-translate>Info Sehat</a></li>
      </ul>
    </div>
    <div>
      <h3 class="font-semibold text-lg mb-4 text-gray-800" data-translate>Support</h3>
      <ul class="space-y-3 text-base">
        <li><a href="#" class="hover:text-blue-700" data-translate>FAQs</a></li>
        <li><a href="#" class="hover:text-blue-700" data-translate>Support Center</a></li>
        <li><a href="#" class="hover:text-blue-700" data-translate>Contact Us</a></li>
      </ul>
      <div class="flex space-x-4 mt-6">
        @php
          $socialMedia = \App\Models\SocialMedia::orderBy('created_at', 'asc')->get();
        @endphp
        @forelse($socialMedia as $media)
          <a
            href="{{ $media->url }}"
            target="_blank"
            rel="noopener noreferrer"
            class="w-10 h-10 flex items-center justify-center bg-blue-700 text-white rounded-full hover:bg-blue-800 transition text-lg"
            aria-label="{{ $media->title ?? 'Social Media' }}"
            ><i class="{{ $media->icon }}"></i
          ></a>
        @empty
          <a
            href="#"
            class="w-10 h-10 flex items-center justify-center bg-blue-700 text-white rounded-full hover:bg-blue-800 transition text-lg"
            ><i class="fab fa-facebook-f"></i
          ></a>
          <a
            href="#"
            class="w-10 h-10 flex items-center justify-center bg-blue-700 text-white rounded-full hover:bg-blue-800 transition text-lg"
            ><i class="fab fa-youtube"></i
          ></a>
          <a
            href="#"
            class="w-10 h-10 flex items-center justify-center bg-blue-700 text-white rounded-full hover:bg-blue-800 transition text-lg"
            ><i class="fab fa-instagram"></i
          ></a>
        @endforelse
      </div>
    </div>
  </div>
  <div class="container mx-auto px-6 text-center mt-10 pt-6 border-t border-gray-300 text-base text-gray-500" data-translate>
    &copy; 2024 Olga Sehat. All rights reserved.
  </div>
</footer>

  <!-- Overlay for Cart -->
  <div id="cartOverlay" class="fixed inset-0 bg-black bg-opacity-50 hidden z-40"></div>

  <!-- Cart Sidebar -->
  <div id="cartSidebar"
       class="fixed top-0 right-0 w-80 max-w-full h-full bg-white shadow-lg transform translate-x-full transition-transform duration-300 z-50 flex flex-col">
    <!-- Header -->
    <div class="flex justify-between items-center px-4 py-3 border-b flex-shrink-0">
      <h2 class="font-semibold text-lg">JADWAL DIPILIH</h2>
      <button id="closeCart" class="text-gray-500 hover:text-gray-700">
        <i class="fas fa-times"></i>
      </button>
    </div>

    <!-- Isi Cart -->
    <div class="cart-content flex-grow overflow-y-auto p-4 space-y-4">
      Belum ada jadwal di keranjang.
    </div>
  </div>

  <script src="{{ asset('assets/olgasehat.js') }}"></script>
  <script>
    // Header Layout //
    document.addEventListener("DOMContentLoaded", function () {
      // Dropdown helper - updated to ensure only one dropdown shows at a time
      function toggleDropdown(dropdownToToggle, dropdownToClose) {
        // Close the other dropdown if open
        if (dropdownToClose && !dropdownToClose.classList.contains("hidden")) {
          dropdownToClose.classList.remove("opacity-100", "scale-100");
          dropdownToClose.classList.add("opacity-0", "scale-95");
          setTimeout(() => dropdownToClose.classList.add("hidden"), 200);
        }

        // Toggle the target dropdown
        if (dropdownToToggle.classList.contains("hidden")) {
          dropdownToToggle.classList.remove("hidden");
          setTimeout(() => {
            dropdownToToggle.classList.remove("opacity-0", "scale-95");
            dropdownToToggle.classList.add("opacity-100", "scale-100");
          }, 10);
        } else {
          dropdownToToggle.classList.remove("opacity-100", "scale-100");
          dropdownToToggle.classList.add("opacity-0", "scale-95");
          setTimeout(() => dropdownToToggle.classList.add("hidden"), 200);
        }
      }

      @if($authUser)
        // Desktop User Dropdown
        const userBtn = document.getElementById("userMenuBtn");
        const userMenu = document.getElementById("userMenu");

        userBtn?.addEventListener("click", (e) => {
          e.stopPropagation();
          toggleDropdown(userMenu, null);
        });

        // Mobile User Dropdown
        const mobileUserBtn = document.getElementById("mobileUserBtn");
        const mobileUserMenu = document.getElementById("mobileUserMenu");

        mobileUserBtn?.addEventListener("click", (e) => {
          e.stopPropagation();
          toggleDropdown(mobileUserMenu, userMenu);
        });
      @else
        // Desktop Dropdowns
        const registerBtn = document.getElementById("registerBtn");
        const registerDropdown = document.getElementById("registerDropdown");
        const loginBtn = document.getElementById("loginBtn");
        const loginDropdown = document.getElementById("loginDropdown");

        registerBtn?.addEventListener("click", (e) => {
          e.stopPropagation();
          toggleDropdown(registerDropdown, loginDropdown);
        });

        loginBtn?.addEventListener("click", (e) => {
          e.stopPropagation();
          toggleDropdown(loginDropdown, registerDropdown);
        });
      @endif

      // Mobile Menu Toggle with Animation
      const mobileMenuBtn = document.getElementById("mobileMenuBtn");
      const mobileMenu = document.getElementById("mobileMenu");
      mobileMenuBtn?.addEventListener("click", (e) => {
        e.stopPropagation();
        if (mobileMenu.classList.contains("hidden")) {
          // Open menu with stagger animation
          mobileMenu.classList.remove("hidden");
          const menuItems = mobileMenu.querySelectorAll(".menu-item");
          menuItems.forEach((item, index) => {
            item.style.opacity = "0";
            item.style.transform = "translateY(4px)";
            setTimeout(() => {
              item.style.transition = "all 0.2s ease-out";
              item.style.opacity = "1";
              item.style.transform = "translateY(0)";
            }, index * 50);
          });
        } else {
          // Close menu with fade out
          const menuItems = mobileMenu.querySelectorAll(".menu-item");
          menuItems.forEach((item) => {
            item.style.opacity = "0";
            item.style.transform = "translateY(-4px)";
          });
          setTimeout(() => {
            mobileMenu.classList.add("hidden");
            // Reset styles after close
            menuItems.forEach((item) => {
              item.style.transition = "";
              item.style.opacity = "";
              item.style.transform = "";
            });
          }, 200);
        }
      });

      // Close mobile menu on outside click
      window.addEventListener("click", (e) => {
        @if($authUser)
          // Desktop
          if (!userBtn?.contains(e.target) && !userMenu?.contains(e.target)) {
            userMenu?.classList.add("hidden", "opacity-0", "scale-95");
          }
          // Mobile User
          if (!mobileUserBtn?.contains(e.target) && !mobileUserMenu?.contains(e.target)) {
            mobileUserMenu?.classList.add("hidden", "opacity-0", "scale-95");
          }
        @else
          // Desktop
          if (!registerBtn?.contains(e.target) && !registerDropdown?.contains(e.target)) {
            registerDropdown?.classList.add("hidden", "opacity-0", "scale-95");
          }
          if (!loginBtn?.contains(e.target) && !loginDropdown?.contains(e.target)) {
            loginDropdown?.classList.add("hidden", "opacity-0", "scale-95");
          }
        @endif
        // Mobile
        if (!mobileMenu.contains(e.target) && !mobileMenuBtn?.contains(e.target)) {
          mobileMenu.classList.add("hidden");
        }
      });

      // Language Dropdown Functionality
      const languageBtn = document.getElementById("languageBtn");
      const languageDropdown = document.getElementById("languageDropdown");
      const currentLanguage = document.getElementById("currentLanguage");
      const languageBtnMobile = document.getElementById("languageBtnMobile");
      const languageDropdownMobile = document.getElementById("languageDropdownMobile");
      const currentLanguageMobile = document.getElementById("currentLanguageMobile");

      // Language list dengan bahasa Bali, Jawa, dan internasional
      const languages = [
        {code: 'id', name: 'Bahasa Indonesia', flag: '🇮🇩'},
        {code: 'ban', name: 'Basa Bali', flag: '🏝️'},
        {code: 'jw', name: 'Basa Jawa', flag: '☕'}, // Google Translate pakai 'jw' bukan 'jv'
        {code: 'en', name: 'English', flag: '🇬🇧'},
        {code: 'es', name: 'Español', flag: '🇪🇸'},
        {code: 'fr', name: 'Français', flag: '🇫🇷'},
        {code: 'de', name: 'Deutsch', flag: '🇩🇪'},
        {code: 'it', name: 'Italiano', flag: '🇮🇹'},
        {code: 'pt', name: 'Português', flag: '🇵🇹'},
        {code: 'ru', name: 'Русский', flag: '🇷🇺'},
        {code: 'ja', name: '日本語', flag: '🇯🇵'},
        {code: 'ko', name: '한국어', flag: '🇰🇷'},
        {code: 'zh', name: '中文', flag: '🇨🇳'},
        {code: 'ar', name: 'العربية', flag: '🇸🇦'},
        {code: 'hi', name: 'हिन्दी', flag: '🇮🇳'},
        {code: 'th', name: 'ไทย', flag: '🇹🇭'},
        {code: 'vi', name: 'Tiếng Việt', flag: '🇻🇳'},
        {code: 'nl', name: 'Nederlands', flag: '🇳🇱'},
        {code: 'pl', name: 'Polski', flag: '🇵🇱'},
        {code: 'tr', name: 'Türkçe', flag: '🇹🇷'}
      ];

      // Parallel translate untuk lebih cepat (5 requests sekaligus)
      async function translateBatch(texts, targetLang) {
        const promises = texts.map(text => translateText(text, targetLang));
        return Promise.all(promises);
      }

      // Batch translate multiple texts sekaligus dengan parallel processing (LEBIH CEPAT)
      async function batchTranslateTexts(texts, targetLang) {
        const uniqueTexts = [...new Set(texts.filter(t => t && t.trim().length >= 2))];
        const translatedMap = new Map();

        // Translate dalam batch parallel (5 teks sekaligus untuk lebih cepat)
        const batchSize = 5;
        for (let i = 0; i < uniqueTexts.length; i += batchSize) {
          const batch = uniqueTexts.slice(i, i + batchSize);
          const translatedBatch = await translateBatch(batch, targetLang);

          batch.forEach((text, index) => {
            translatedMap.set(text, translatedBatch[index]);
          });

          // Delay minimal hanya di akhir setiap batch
          if (i + batchSize < uniqueTexts.length) {
            await new Promise(resolve => setTimeout(resolve, 10)); // Delay minimal
          }
        }

        return translatedMap;
      }

      function fetchLanguages() {
        populateLanguageDropdown(languages);
        populateLanguageDropdownMobile(languages);

        // Load saved language from localStorage dan auto-translate
        const savedLang = localStorage.getItem('selectedLanguage') || 'id';
        const savedLangObj = languages.find(l => l.code === savedLang);
        if (savedLangObj) {
          currentLanguage.textContent = savedLangObj.code.toUpperCase();
          currentLanguageMobile.textContent = savedLangObj.code.toUpperCase();

          // Auto-translate saat page load jika bukan bahasa Indonesia
          if (savedLang !== 'id') {
            // Translate segera setelah DOM ready
            if (document.readyState === 'complete' || document.readyState === 'interactive') {
              translatePage(savedLang);
            } else {
              document.addEventListener('DOMContentLoaded', () => {
                translatePage(savedLang);
              });
            }
          }
        }
      }

      function populateLanguageDropdown(languages) {
        languageDropdown.innerHTML = '';
        languages.forEach(lang => {
          const li = document.createElement('li');
          li.className = 'px-4 py-2 hover:bg-gray-100 cursor-pointer flex items-center space-x-2';
          li.innerHTML = `<span>${lang.flag || '🌐'}</span><span>${lang.name} (${lang.code.toUpperCase()})</span>`;
          li.addEventListener('click', () => {
            currentLanguage.textContent = lang.code.toUpperCase();
            languageDropdown.classList.add('hidden');
            changeLanguage(lang.code);
          });
          languageDropdown.appendChild(li);
        });
      }

      function populateLanguageDropdownMobile(languages) {
        languageDropdownMobile.innerHTML = '';
        languages.forEach(lang => {
          const li = document.createElement('li');
          li.className = 'px-4 py-2 hover:bg-gray-100 cursor-pointer flex items-center space-x-2';
          li.innerHTML = `<span>${lang.flag || '🌐'}</span><span>${lang.name} (${lang.code.toUpperCase()})</span>`;
          li.addEventListener('click', () => {
            currentLanguageMobile.textContent = lang.code.toUpperCase();
            languageDropdownMobile.classList.add('hidden');
            changeLanguage(lang.code);
          });
          languageDropdownMobile.appendChild(li);
        });
      }

      // Translation cache untuk menghindari translate ulang
      const translationCache = new Map();

      // Translate text menggunakan multiple API dengan cache
      async function translateText(text, targetLang) {
        if (targetLang === 'id') return text;
        if (!text || text.trim().length === 0) return text;

        const cacheKey = `${text}|${targetLang}`;
        if (translationCache.has(cacheKey)) {
          return translationCache.get(cacheKey);
        }

        if (/^[\d\s\W]+$/.test(text) || text.trim().length < 2) {
          return text;
        }

        try {
          const response = await fetch(`https://translate.googleapis.com/translate_a/single?client=gtx&sl=id&tl=${targetLang}&dt=t&q=${encodeURIComponent(text)}`);
          const data = await response.json();
          if (data && data[0] && data[0][0] && data[0][0][0]) {
            const translated = data[0][0][0];
            translationCache.set(cacheKey, translated);
            return translated;
          }
        } catch (error) {
          console.log('Google Translate failed, trying MyMemory API');
        }
        try {
          const response = await fetch(`https://api.mymemory.translated.net/get?q=${encodeURIComponent(text)}&langpair=id|${targetLang}`);
          const data = await response.json();
          if (data.responseData && data.responseData.translatedText) {
            const translated = data.responseData.translatedText;
            translationCache.set(cacheKey, translated);
            return translated;
          }
        } catch (error) {
          console.error('Translation API error:', error);
        }
        return text;
      }

      // Get all text nodes di halaman (otomatis, tanpa perlu data-translate)
      function getAllTextNodes(element = document.body) {
        const textNodes = [];
        const walker = document.createTreeWalker(
          element,
          NodeFilter.SHOW_TEXT,
          {
            acceptNode: function(node) {
              const parent = node.parentElement;
              if (!parent) return NodeFilter.FILTER_REJECT;

              const tagName = parent.tagName.toLowerCase();
              if (['script', 'style', 'noscript', 'meta', 'link'].includes(tagName)) {
                return NodeFilter.FILTER_REJECT;
              }

              const style = window.getComputedStyle(parent);
              if (style.display === 'none' || style.visibility === 'hidden') {
                return NodeFilter.FILTER_REJECT;
              }

              if (parent.classList.contains('no-translate') || parent.hasAttribute('data-no-translate')) {
                return NodeFilter.FILTER_REJECT;
              }

              const text = node.textContent.trim();
              if (!text || text.length < 2 || /^[\d\s\W]+$/.test(text)) {
                return NodeFilter.FILTER_REJECT;
              }

              if (/^[\d.]+$/.test(text) || /^(https?|www\.)/i.test(text)) {
                return NodeFilter.FILTER_REJECT;
              }

              return NodeFilter.FILTER_ACCEPT;
            }
          }
        );

        let node;
        while (node = walker.nextNode()) {
          const text = node.textContent.trim();
          if (text && text.length >= 2) {
            textNodes.push({
              node: node,
              text: text,
              original: text
            });
          }
        }

        return textNodes;
      }

      async function changeLanguage(langCode) {
        localStorage.setItem('selectedLanguage', langCode);
        const langObj = languages.find(l => l.code === langCode);
        if (langObj) {
          currentLanguage.textContent = langObj.code.toUpperCase();
          currentLanguageMobile.textContent = langObj.code.toUpperCase();
        }
        if (langCode === 'id') {
          document.querySelectorAll('[data-translated]').forEach(el => {
            el.removeAttribute('data-translated');
          });
          location.reload();
          return;
        }
        Swal.fire({
          title: 'Mengubah Bahasa...',
          text: 'Mohon tunggu sebentar',
          allowOutsideClick: false,
          showConfirmButton: false,
          willOpen: () => { Swal.showLoading(); }
        });
        try {
          // Get all text nodes automatically
          const textNodes = getAllTextNodes();
          if (textNodes.length === 0) {
            Swal.close();
            return;
          }

          textNodes.forEach(item => {
            if (!item.node.parentElement.hasAttribute('data-translated')) {
              item.node.parentElement.setAttribute('data-original-text', item.text);
              item.node.parentElement.setAttribute('data-translated', 'true');
            }
          });

          const uniqueTexts = [...new Set(textNodes.map(item => item.text))];
          let translatedCount = 0;
          const totalTexts = uniqueTexts.length;

          const updateProgress = () => {
            const progress = Math.round((translatedCount / totalTexts) * 100);
            Swal.update({ text: `Menerjemahkan ${translatedCount}/${totalTexts} teks... ${progress}%` });
          };

          // Batch translate dengan parallel processing (lebih cepat)
          const uniqueTextsList = uniqueTexts.filter(t => t && t.trim().length >= 2);
          const translatedMap = await batchTranslateTexts(uniqueTextsList, langCode);

          // Update progress setelah selesai
          translatedCount = uniqueTextsList.length;
          updateProgress();

          textNodes.forEach(item => {
            const translated = translatedMap.get(item.text);
            if (translated && translated !== item.text) {
              item.node.textContent = item.node.textContent.replace(item.text, translated);
            }
          });

          Swal.close();
          Swal.fire({
            icon: 'success',
            title: 'Bahasa Berhasil Diubah',
            text: `Semua halaman telah diterjemahkan ke ${langObj ? langObj.name : langCode.toUpperCase()}`,
            confirmButtonText: 'OK',
            timer: 2000,
            timerProgressBar: true
          });
        } catch (error) {
          console.error('Language change error:', error);
          Swal.close();
          Swal.fire({
            icon: 'error',
            title: 'Terjadi Kesalahan',
            text: 'Gagal mengubah bahasa. Silakan coba lagi.',
            confirmButtonText: 'OK'
          });
        }
      }

      async function translatePage(langCode) {
        if (langCode === 'id') return;
        try {
          // Tunggu DOM siap
          if (document.readyState === 'loading') {
            await new Promise(resolve => {
              if (document.readyState === 'complete') {
                resolve();
              } else {
                document.addEventListener('DOMContentLoaded', resolve);
              }
            });
          }

          // Tunggu sebentar untuk memastikan konten dinamis sudah ter-load
          await new Promise(resolve => setTimeout(resolve, 300));

          const textNodes = getAllTextNodes();
          if (textNodes.length === 0) return;

          textNodes.forEach(item => {
            if (!item.node.parentElement.hasAttribute('data-translated')) {
              item.node.parentElement.setAttribute('data-original-text', item.text);
              item.node.parentElement.setAttribute('data-translated', 'true');
            }
          });

          const uniqueTexts = [...new Set(textNodes.map(item => item.text))];
          const uniqueTextsList = uniqueTexts.filter(t => t && t.trim().length >= 2);

          // Batch translate dengan parallel processing (lebih cepat)
          const translatedMap = await batchTranslateTexts(uniqueTextsList, langCode);

          textNodes.forEach(item => {
            const translated = translatedMap.get(item.text);
            if (translated && translated !== item.text) {
              item.node.textContent = item.node.textContent.replace(item.text, translated);
            }
          });

          console.log(`Auto-translated ${uniqueTextsList.length} unique texts to ${langCode}`);
        } catch (error) {
          console.error('Auto translate error:', error);
        }
      }

      // Toggle desktop language dropdown
      languageBtn?.addEventListener("click", (e) => {
        e.stopPropagation();
        languageDropdown.classList.toggle("hidden");
      });

      // Toggle mobile language dropdown
      languageBtnMobile?.addEventListener("click", (e) => {
        e.stopPropagation();
        languageDropdownMobile.classList.toggle("hidden");
      });

      // Close dropdowns on outside click
      window.addEventListener("click", (e) => {
        if (!languageBtn?.contains(e.target) && !languageDropdown?.contains(e.target)) {
          languageDropdown?.classList.add("hidden");
        }
        if (!languageBtnMobile?.contains(e.target) && !languageDropdownMobile?.contains(e.target)) {
          languageDropdownMobile?.classList.add("hidden");
        }
      });

      // Fetch languages on page load
      fetchLanguages();

      // Header hide/show on scroll
      let lastScrollTop = 0;
      window.addEventListener('scroll', function() {
        let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        const header = document.getElementById('mainHeader');
        if (scrollTop > lastScrollTop && scrollTop > 100) {
          header.style.transform = 'translateY(-100%)';
        } else {
          header.style.transform = 'translateY(0)';
        }
        lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
      });
    });

    // HOME JS //
    @if(session('success'))
      Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: '{{ session('success') }}',
        confirmButtonText: 'OK'
      });
    @endif

    // Initialize AOS
    AOS.init({
      duration: 800,
      easing: 'ease-in-out',
      once: true,
      offset: 100
    });

    // VENUE JS //
    if (document.getElementById('unifiedSearch')) {
      document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('unifiedSearch');
        const dropdown = document.getElementById('suggestionsDropdown');
        const dummyVenues = [
          {name: "MU Sport Center", address: "Jl. Sunset Road No. 123, Kuta, Denpasar, Bali", distance: 1.2},
          {name: "Imbo Sport Center", address: "Jl. Bypass Ngurah Rai No. 45, Jimbaran, Denpasar Selatan", distance: 3.5},
          {name: "DC Arena Bali", address: "Jl. Raya Kuta No. 78, Kuta, Badung, Bali", distance: 5.1},
          {name: "Arena Sport", address: "Jl. Teuku Umar Barat No. 200, Denpasar Utara", distance: 7.8},
          {name: "Bali Futsal Center", address: "Jl. Gunung Agung No. 15, Renon, Denpasar Timur", distance: 10.2},
          {name: "Sport Hub Denpasar", address: "Jl. Diponegoro No. 300, Denpasar Pusat", distance: 15.4}
        ];

        function showSuggestions(query) {
          if (!query.trim()) {
            dropdown.innerHTML = '';
            dropdown.classList.add('hidden');
            return;
          }

          const filtered = dummyVenues
            .filter(venue => venue.name.toLowerCase().includes(query.toLowerCase()) || venue.address.toLowerCase().includes(query.toLowerCase()))
            .sort((a, b) => a.distance - b.distance)
            .slice(0, 5);

          if (filtered.length === 0) {
            dropdown.innerHTML = '<li class="px-4 py-2 text-gray-500">Tidak ada hasil</li>';
          } else {
            dropdown.innerHTML = filtered.map(venue => `
              <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer border-b last:border-b-0" onclick="selectVenue('${venue.name} - ${venue.address} (${venue.distance} km)')">
                <div class="font-semibold">${venue.name}</div>
                <div class="text-sm text-gray-600">${venue.address}</div>
                <div class="text-xs text-gray-400">${venue.distance} km</div>
              </li>
            `).join('');
          }

          dropdown.classList.remove('hidden');
        }

        window.selectVenue = function(value) {
          searchInput.value = value;
          dropdown.classList.add('hidden');
        };

        searchInput.addEventListener('input', (e) => {
          showSuggestions(e.target.value);
        });

        searchInput.addEventListener('focus', () => {
          if (searchInput.value.trim()) {
            showSuggestions(searchInput.value);
          }
        });

        document.addEventListener('click', (e) => {
          if (!searchInput.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.add('hidden');
          }
        });

        const form = searchInput.closest('form');
        form.addEventListener('submit', (e) => {
          e.preventDefault();
          const searchTerm = searchInput.value;
          if (searchTerm) {
            alert(`Searching for: ${searchTerm}`);
          }
        });
      });
    }
  </script>
  @stack('scripts')

    <script>
        // Simple JS to toggle forms and preview
        const cards = document.querySelectorAll('.select-card');
        const formKom = document.getElementById('form-komunitas');
        const formMem = document.getElementById('form-membership');
        const formEvt = document.getElementById('form-event');
        const formsSection = document.getElementById('forms');

        function hideAll() {
            formKom.classList.add('hidden');
            formMem.classList.add('hidden');
            formEvt.classList.add('hidden');
        }

        cards.forEach(c => c.addEventListener('click', () => {
            // Cek jika disabled (khusus Membership)
            if (c.disabled) {
                alert("Anda tidak memiliki izin Pemilik Lapangan untuk membuat Membership.");
                return;
            }

            // Atur status selected
            cards.forEach(x => x.classList.remove('selected'));
            c.classList.add('selected');
            
            const t = c.getAttribute('data-type');
            hideAll();
            
            // Tampilkan form yang sesuai
            if (t === 'komunitas') {
                formKom.classList.remove('hidden');
                // Initialize harga toggle setelah form ditampilkan
                setTimeout(() => {
                    initializeHargaToggle('komunitas');
                }, 100);
            }
            if (t === 'membership') formMem.classList.remove('hidden');
            if (t === 'event') {
                formEvt.classList.remove('hidden');
                // Initialize harga toggle setelah form ditampilkan
                setTimeout(() => {
                    initializeHargaToggle('event');
                }, 100);
            }
            
            // Scroll ke form
            if (!formsSection.classList.contains('hidden')) {
                window.scrollTo({ top: formsSection.offsetTop - 40, behavior: 'smooth' });
            }
        }));

        // --- Membership Preview Bindings ---
        const mNama = document.querySelector('#form-membership [name="m_nama"]');
        const mLokasi = document.querySelector('#form-membership [name="m_lokasi"]');
        const mHarga = document.querySelector('#form-membership [name="m_harga"]');
        const mDurasi = document.querySelector('#form-membership [name="m_durasi"]');
        const mFasilitas = document.querySelector('#form-membership [name="m_fasilitas"]');

        function updatePreview() {
            // Nilai default dan formatting
            const nama = mNama.value || 'GOLD PASS Bulanan';
            const lokasi = mLokasi.value || 'Lapangan A - Denpasar';
            const harga = mHarga.value ? Number(mHarga.value).toLocaleString('id-ID') : '0';
            const durasi = mDurasi.value || 'Bulan';
            
            // Format Fasilitas
            let fasilitasText = mFasilitas.value.split('\n').join(' · ').replace(/[^\w\s\·]/g, '');
            if(fasilitasText.length > 50) fasilitasText = fasilitasText.substring(0, 50) + '...';
            fasilitasText = fasilitasText || 'akses malam · diskon sewa · free sparing';


            document.getElementById('preview-title').textContent = nama;
            document.getElementById('preview-lokasi').textContent = lokasi;
            document.getElementById('preview-harga').textContent = `Rp ${harga} / ${durasi.replace('1 ', '')}`;
            document.getElementById('preview-fasilitas').textContent = 'Fasilitas: ' + fasilitasText;
        }

        [mNama, mLokasi, mHarga, mDurasi, mFasilitas].forEach(i => i && i.addEventListener('input', updatePreview));
        [mNama, mLokasi, mHarga, mDurasi, mFasilitas].forEach(i => i && i.addEventListener('change', updatePreview)); // Untuk select

        // --- Image Preview Helpers ---
        function previewImage(input, targetSelector) {
            const file = input.files && input.files[0];
            const target = document.querySelector(targetSelector);
            
            if (!target) return;

            if (!file) {
                target.classList.add('hidden');
                return;
            }

            const reader = new FileReader();
            reader.onload = e => {
                target.src = e.target.result;
                target.classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        }

        document.getElementById('komunitas-image').addEventListener('change', e => previewImage(e.target, '#komunitas-image-preview'));
        document.getElementById('membership-image').addEventListener('change', e => previewImage(e.target, '#membership-image-preview'));
        document.getElementById('event-image').addEventListener('change', e => previewImage(e.target, '#event-image-preview'));

        // --- Fake Submit Handlers ---
        function submitKomunitas() {
            alert('Komunitas berhasil dibuat! (Demo). Redirecting...');
            resetForms();
        }
        function submitMembership() {
            alert('Membership disimpan! (Demo). Redirecting...');
            resetForms();
        }
        function submitEvent() {
            alert('Event tersimpan! (Demo). Redirecting...');
            resetForms();
        }

        // --- Reset Function ---
        function resetForms() {
            hideAll();
            cards.forEach(x => x.classList.remove('selected'));
            document.querySelectorAll('form').forEach(f => f.reset());
            document.querySelectorAll('img[id$="-preview"]').forEach(img => img.classList.add('hidden'));
            updatePreview();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // --- Toggle Harga Input Based on Biaya Selection ---
        function initializeHargaToggle(formType) {
            if (formType === 'komunitas') {
                const hargaKomunitasContainer = document.getElementById('harga-komunitas-container');
                const hargaKomunitasInput = hargaKomunitasContainer?.querySelector('input[name="harga"]');
                
                // Add listeners to all radio buttons
                document.querySelectorAll('#form-komunitas .biaya-radio').forEach(radio => {
                    radio.addEventListener('change', function() {
                        if (this.value === 'berbayar') {
                            hargaKomunitasContainer?.classList.remove('hidden');
                            if (hargaKomunitasInput) {
                                hargaKomunitasInput.required = true;
                                hargaKomunitasInput.setAttribute('required', 'required');
                            }
                        } else {
                            hargaKomunitasContainer?.classList.add('hidden');
                            if (hargaKomunitasInput) {
                                hargaKomunitasInput.required = false;
                                hargaKomunitasInput.removeAttribute('required');
                                hargaKomunitasInput.value = '';
                            }
                        }
                    });
                    
                    // Check initial state immediately
                    if (radio.checked && radio.value === 'berbayar') {
                        hargaKomunitasContainer?.classList.remove('hidden');
                        if (hargaKomunitasInput) {
                            hargaKomunitasInput.required = true;
                            hargaKomunitasInput.setAttribute('required', 'required');
                        }
                    }
                });
            } else if (formType === 'event') {
                const hargaEventContainer = document.getElementById('harga-event-container');
                const hargaEventInput = hargaEventContainer?.querySelector('input[name="harga"]');
                
                // Add listeners to all radio buttons
                document.querySelectorAll('#form-event .biaya-radio-event').forEach(radio => {
                    radio.addEventListener('change', function() {
                        if (this.value === 'berbayar') {
                            hargaEventContainer?.classList.remove('hidden');
                            if (hargaEventInput) {
                                hargaEventInput.required = true;
                                hargaEventInput.setAttribute('required', 'required');
                            }
                        } else {
                            hargaEventContainer?.classList.add('hidden');
                            if (hargaEventInput) {
                                hargaEventInput.required = false;
                                hargaEventInput.removeAttribute('required');
                                hargaEventInput.value = '';
                            }
                        }
                    });
                    
                    // Check initial state immediately
                    if (radio.checked && radio.value === 'berbayar') {
                        hargaEventContainer?.classList.remove('hidden');
                        if (hargaEventInput) {
                            hargaEventInput.required = true;
                            hargaEventInput.setAttribute('required', 'required');
                        }
                    }
                });
            }
        }

        function toggleHargaInput() {
            // Initialize for all forms on page load
            initializeHargaToggle('komunitas');
            initializeHargaToggle('event');
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            updatePreview();
            toggleHargaInput();
            
            // Also check if form is already visible (e.g., after validation error)
            if (!formKom.classList.contains('hidden')) {
                initializeHargaToggle('komunitas');
            }
            if (!formEvt.classList.contains('hidden')) {
                initializeHargaToggle('event');
            }
        });
        
        // Also initialize immediately (for cases where DOMContentLoaded already fired)
        updatePreview();
        toggleHargaInput();
    </script>
</body>
</html>