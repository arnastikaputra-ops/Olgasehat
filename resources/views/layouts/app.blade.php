<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>@yield('title', 'Olga Sehat - Platform Gaya Hidup Sehat')</title>
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
              <a href="/edit-profile-user" class="dropdown-item">
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
                <a href="/pemiliklapangan/komunitas" class="dropdown-item">
                  <span data-translate>Komunitas</span>
                  <i class="fas fa-users"></i>
                </a>
                <a href="/pemiliklapangan/membership" class="dropdown-item">
                  <span data-translate>Membership</span>
                  <i class="fas fa-crown"></i>
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
                <a href="/pengelolakesehatan/komunitas" class="dropdown-item">
                  <span data-translate>Komunitas</span>
                  <i class="fas fa-users"></i>
                </a>
                <a href="/pengelolakesehatan/membership" class="dropdown-item">
                  <span data-translate>Membership</span>
                  <i class="fas fa-crown"></i>
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
                <a href="/admin/komunitas" class="dropdown-item">
                  <span data-translate>Kelola Komunitas</span>
                  <i class="fas fa-users"></i>
                </a>
                <a href="/admin/membership" class="dropdown-item">
                  <span data-translate>Kelola Membership</span>
                  <i class="fas fa-crown"></i>
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
          <button id="registerBtn" class="text-gray-700 hover:text-blue-700 font-semibold focus:outline-none transition px-3 py-2" data-translate>Register</button>
          <div id="registerDropdown"
            class="hidden absolute right-0 mt-2 w-60 bg-white border border-gray-100 rounded-xl shadow-xl z-50 py-1
                   transform scale-95 opacity-0 transition-all duration-200 ease-out">
            <a href="/daftaruser" class="block px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition" data-translate>Register User</a>
            <a href="/regispengelola" class="block px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition" data-translate>Register Pengelola Venue</a>
            <a href="/regispengelolakesehatan" class="block px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition" data-translate>Register Pengelola Kesehatan</a>
          </div>
        </div>

        <!-- Login Dropdown -->
        <div class="relative">
          <button id="loginBtn"
            class="bg-blue-700 text-white px-5 py-2 rounded-lg hover:bg-blue-800 transition focus:outline-none font-semibold shadow-sm" data-translate>
            Login
          </button>
          <div id="loginDropdown"
            class="hidden absolute right-0 mt-2 w-56 bg-white border border-gray-100 rounded-xl shadow-xl z-50 py-1
                   transform scale-95 opacity-0 transition-all duration-200 ease-out">
            <a href="/loginuser" class="block px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition" data-translate>Login User</a>
            <a href="/loginpengelolavenue" class="block px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition" data-translate>Login Pengelola Venue</a>
            <a href="/loginpengelolakesehatan" class="block px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition" data-translate>Login Pengelola Kesehatan</a>
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
              <a href="/edit-profile-user" class="dropdown-item" style="font-size: 0.875rem;">
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
        <!-- Login and Register Buttons -->
        <div class="border-t pt-3 sm:pt-4 px-4 sm:px-6 pb-4">
          <div class="mb-3">
            <p class="text-sm font-semibold text-gray-600 mb-2" data-translate>Login Sebagai:</p>
            <a href="/loginuser" class="block w-full px-4 sm:px-6 py-3 sm:py-4 text-center text-sm sm:text-base text-blue-700 font-medium border border-blue-700 rounded-md hover:bg-blue-50 mb-2 menu-item opacity-0 translate-y-1 transition-all duration-200" data-translate>Login User</a>
            <a href="/loginpengelolavenue" class="block w-full px-4 sm:px-6 py-3 sm:py-4 text-center text-sm sm:text-base text-blue-700 font-medium border border-blue-700 rounded-md hover:bg-blue-50 mb-2 menu-item opacity-0 translate-y-1 transition-all duration-200" data-translate>Login Pengelola Venue</a>
            <a href="/loginpengelolakesehatan" class="block w-full px-4 sm:px-6 py-3 sm:py-4 text-center text-sm sm:text-base text-blue-700 font-medium border border-blue-700 rounded-md hover:bg-blue-50 mb-2 menu-item opacity-0 translate-y-1 transition-all duration-200" data-translate>Login Pengelola Kesehatan</a>
          </div>
          <div>
            <p class="text-sm font-semibold text-gray-600 mb-2" data-translate>Register Sebagai:</p>
            <a href="/daftaruser" class="block w-full px-4 sm:px-6 py-3 sm:py-4 text-center text-sm sm:text-base bg-blue-700 text-white font-medium rounded-md hover:bg-blue-800 mb-2 menu-item opacity-0 translate-y-1 transition-all duration-200" data-translate>Register User</a>
            <a href="/regispengelola" class="block w-full px-4 sm:px-6 py-3 sm:py-4 text-center text-sm sm:text-base bg-blue-700 text-white font-medium rounded-md hover:bg-blue-800 mb-2 menu-item opacity-0 translate-y-1 transition-all duration-200" data-translate>Register Pengelola Venue</a>
            <a href="/regispengelolakesehatan" class="block w-full px-4 sm:px-6 py-3 sm:py-4 text-center text-sm sm:text-base bg-blue-700 text-white font-medium rounded-md hover:bg-blue-800 menu-item opacity-0 translate-y-1 transition-all duration-200" data-translate>Register Pengelola Kesehatan</a>
          </div>
        </div>
      @endif
    </nav>
</header>

  <main class="pt-2 sm:pt-3 md:pt-4">
    @yield('content')
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
        <li><a href="javascript:void(0)" onclick="openPrivacyModal()" class="hover:text-blue-700" data-translate>Kebijakan &amp; Privasi</a></li>
        <li><a href="javascript:void(0)" onclick="openTermsModal()" class="hover:text-blue-700" data-translate>Syarat &amp; Ketentuan</a></li>
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
        <li><a href="javascript:void(0)" onclick="openFaqModal()" class="hover:text-blue-700" data-translate>FAQs</a></li>
        <li><a href="javascript:void(0)" onclick="openSupportModal()" class="hover:text-blue-700" data-translate>Support Center</a></li>
        <li><a href="javascript:void(0)" onclick="openContactUsModal()" class="hover:text-blue-700 font-semibold text-blue-700" data-translate>Contact Us</a></li>
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
            href="https://facebook.com"
            target="_blank"
            class="w-10 h-10 flex items-center justify-center bg-blue-700 text-white rounded-full hover:bg-blue-800 transition text-lg"
            ><i class="fab fa-facebook-f"></i
          ></a>
          <a
            href="https://youtube.com"
            target="_blank"
            class="w-10 h-10 flex items-center justify-center bg-blue-700 text-white rounded-full hover:bg-blue-800 transition text-lg"
            ><i class="fab fa-youtube"></i
          ></a>
          <a
            href="https://instagram.com"
            target="_blank"
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

<!-- MODAL CONTACT US -->
<div id="contactUsModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black bg-opacity-60 flex items-center justify-center p-4">
  <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden transform transition-all">
    <div class="bg-gradient-to-r from-blue-900 to-blue-700 text-white p-5 flex justify-between items-center">
      <div class="flex items-center space-x-3">
        <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center text-white text-lg"><i class="fas fa-headset"></i></div>
        <div>
          <h3 class="text-lg font-bold">Contact Us - OlgaSehat</h3>
          <p class="text-xs text-blue-100">Bantuan & Layanan Pelanggan</p>
        </div>
      </div>
      <button onclick="closeContactUsModal()" class="text-white hover:text-gray-200 text-2xl font-bold focus:outline-none">&times;</button>
    </div>
    <div class="p-6 space-y-4">
      <p class="text-gray-600 text-sm leading-relaxed">
        Memiliki pertanyaan, kendala pemesanan, atau ingin bermitra? Silakan hubungi tim kami melalui kontak resmi yang dikelola via Backoffice Admin:
      </p>
      @php
        $contacts = \App\Models\ContactUs::all();
      @endphp
      <div class="space-y-3">
        @forelse($contacts as $c)
          <div class="p-3.5 bg-gray-50 border border-gray-100 rounded-xl flex items-center justify-between hover:bg-blue-50 transition">
            <div class="flex items-center space-x-3">
              @if(str_contains(strtolower($c->type ?? ''), 'wa') || str_contains(strtolower($c->type ?? ''), 'phone') || str_contains(strtolower($c->title ?? ''), 'whatsapp'))
                <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-lg font-bold"><i class="fab fa-whatsapp"></i></div>
              @elseif(str_contains(strtolower($c->type ?? ''), 'email') || str_contains(strtolower($c->title ?? ''), 'email'))
                <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-lg font-bold"><i class="fas fa-envelope"></i></div>
              @else
                <div class="w-10 h-10 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center text-lg font-bold"><i class="fas fa-map-marker-alt"></i></div>
              @endif
              <div>
                <h4 class="font-bold text-gray-800 text-sm">{{ $c->title }}</h4>
                <p class="text-xs text-gray-600 font-medium">{{ $c->kontak }}</p>
              </div>
            </div>
            @if(str_contains(strtolower($c->type ?? ''), 'wa') || str_contains(strtolower($c->type ?? ''), 'phone') || str_contains(strtolower($c->title ?? ''), 'whatsapp'))
              <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $c->kontak) }}" target="_blank" class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg shadow-sm">Hubungi</a>
            @elseif(str_contains(strtolower($c->type ?? ''), 'email') || str_contains(strtolower($c->title ?? ''), 'email'))
              <a href="mailto:{{ $c->kontak }}" class="px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg shadow-sm">Email</a>
            @endif
          </div>
        @empty
          <div class="p-4 bg-gray-50 text-center text-gray-500 text-sm rounded-xl">
            <i class="fas fa-info-circle mr-1"></i> Informasi kontak belum ditambahkan dari Backoffice.
          </div>
        @endforelse
      </div>
    </div>
    <div class="bg-gray-50 px-6 py-3 border-t text-right">
      <button onclick="closeContactUsModal()" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium text-sm rounded-lg transition">Tutup</button>
    </div>
  </div>
</div>

<!-- MODAL PRIVACY POLICY -->
<div id="privacyModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black bg-opacity-60 flex items-center justify-center p-4">
  <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full overflow-hidden transform transition-all">
    <div class="bg-blue-800 text-white p-5 flex justify-between items-center">
      <h3 class="text-lg font-bold"><i class="fas fa-user-shield mr-2"></i>Kebijakan & Privasi</h3>
      <button onclick="closePrivacyModal()" class="text-white hover:text-gray-200 text-2xl font-bold focus:outline-none">&times;</button>
    </div>
    <div class="p-6 max-h-96 overflow-y-auto space-y-3 text-sm text-gray-600 leading-relaxed">
      <p class="font-semibold text-gray-800">Privasi Anda Sangat Penting Bagi Kami</p>
      <p>OlgaSehat menghargai privasi pengguna dan berkomitmen melindungi informasi pribadi yang Anda bagikan saat mengakses platform kami.</p>
      <ul class="list-disc pl-5 space-y-1">
        <li>Data transaksi dan kontak disimpan dengan enkripsi aman.</li>
        <li>Informasi hanya digunakan untuk keperluan verifikasi reservasi dan pemesanan layanan kesehatan/olahraga.</li>
        <li>OlgaSehat tidak menjual atau membagikan data pengguna ke pihak ketiga tanpa persetujuan Anda.</li>
      </ul>
    </div>
    <div class="bg-gray-50 px-6 py-3 border-t text-right">
      <button onclick="closePrivacyModal()" class="px-4 py-2 bg-blue-700 hover:bg-blue-800 text-white font-medium text-sm rounded-lg transition">Mengerti</button>
    </div>
  </div>
</div>

<!-- MODAL TERMS & CONDITIONS -->
<div id="termsModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black bg-opacity-60 flex items-center justify-center p-4">
  <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full overflow-hidden transform transition-all">
    <div class="bg-blue-800 text-white p-5 flex justify-between items-center">
      <h3 class="text-lg font-bold"><i class="fas fa-file-contract mr-2"></i>Syarat & Ketentuan</h3>
      <button onclick="closeTermsModal()" class="text-white hover:text-gray-200 text-2xl font-bold focus:outline-none">&times;</button>
    </div>
    <div class="p-6 max-h-96 overflow-y-auto space-y-3 text-sm text-gray-600 leading-relaxed">
      <p class="font-semibold text-gray-800">Syarat Penggunaan Platform OlgaSehat</p>
      <ul class="list-disc pl-5 space-y-1.5">
        <li>Pengguna wajib memberikan informasi yang akurat saat pendaftaran dan reservasi.</li>
        <li>Setiap reservasi lapangan atau janji temu klinik tunduk pada kebijakan pembatalan dan komitmen mitra.</li>
        <li>Segala aktivitas ilegal, kecurangan, atau perusakan fasilitas venue dilarang keras.</li>
      </ul>
    </div>
    <div class="bg-gray-50 px-6 py-3 border-t text-right">
      <button onclick="closeTermsModal()" class="px-4 py-2 bg-blue-700 hover:bg-blue-800 text-white font-medium text-sm rounded-lg transition">Saya Setuju</button>
    </div>
  </div>
</div>

<!-- MODAL FAQ & SUPPORT -->
<div id="faqModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black bg-opacity-60 flex items-center justify-center p-4">
  <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full overflow-hidden transform transition-all">
    <div class="bg-blue-800 text-white p-5 flex justify-between items-center">
      <h3 class="text-lg font-bold"><i class="fas fa-question-circle mr-2"></i>Pertanyaan Umum (FAQs)</h3>
      <button onclick="closeFaqModal()" class="text-white hover:text-gray-200 text-2xl font-bold focus:outline-none">&times;</button>
    </div>
    <div class="p-6 max-h-96 overflow-y-auto space-y-3 text-sm text-gray-600 leading-relaxed">
      <div class="border-b pb-2">
        <h4 class="font-bold text-gray-800">Bagaimana cara booking lapangan atau layanan klinik?</h4>
        <p class="text-xs text-gray-500 mt-1">Pilih menu Fasilitas Olahraga atau Layanan Kesehatan, tentukan tanggal & jam, lalu selesaikan pembayaran online.</p>
      </div>
      <div class="border-b pb-2">
        <h4 class="font-bold text-gray-800">Bagaimana jika ingin mendaftar sebagai Pengelola Venue / Kesehatan?</h4>
        <p class="text-xs text-gray-500 mt-1">Klik tombol Register di bagian kanan atas lalu pilih Register Pengelola Venue atau Pengelola Kesehatan.</p>
      </div>
    </div>
    <div class="bg-gray-50 px-6 py-3 border-t text-right">
      <button onclick="closeFaqModal()" class="px-4 py-2 bg-blue-700 hover:bg-blue-800 text-white font-medium text-sm rounded-lg transition">Tutup</button>
    </div>
  </div>
</div>

<script>
function openContactUsModal() { document.getElementById('contactUsModal').classList.remove('hidden'); }
function closeContactUsModal() { document.getElementById('contactUsModal').classList.add('hidden'); }

function openPrivacyModal() { document.getElementById('privacyModal').classList.remove('hidden'); }
function closePrivacyModal() { document.getElementById('privacyModal').classList.add('hidden'); }

function openTermsModal() { document.getElementById('termsModal').classList.remove('hidden'); }
function closeTermsModal() { document.getElementById('termsModal').classList.add('hidden'); }

function openFaqModal() { document.getElementById('faqModal').classList.remove('hidden'); }
function closeFaqModal() { document.getElementById('faqModal').classList.add('hidden'); }
function openSupportModal() { openContactUsModal(); }
</script>

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
  <!-- MODAL BERI ULASAN & RATING GLOBAL -->
  <div id="reviewModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center p-4">
      <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full p-6 relative overflow-hidden max-h-[90vh] overflow-y-auto">
          <button onclick="closeReviewModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 text-2xl font-bold focus:outline-none">
              &times;
          </button>

          <div class="text-center mb-6">
              <div class="w-14 h-14 bg-amber-100 text-amber-500 rounded-full flex items-center justify-center mx-auto mb-3 text-2xl shadow-md">
                  <i class="fas fa-star"></i>
              </div>
              <h3 class="text-2xl font-bold text-gray-900" id="reviewModalTitle">Beri Ulasan & Rating</h3>
              <p class="text-sm text-gray-600 mt-1" id="reviewModalSubtitle">Bagikan pengalaman Anda bersama Olga Sehat!</p>
          </div>

          <form action="{{ route('user.review.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
              @csrf

              <!-- Hidden Inputs -->
              <input type="hidden" name="target_id" id="reviewTargetId" value="">

              <!-- Category Selector (Tipe Target) -->
              <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-1">Kategori Layanan Yang Dinilai</label>
                  <select name="tipe_target" id="reviewTipeTarget" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 text-sm font-medium">
                      <option value="platform">⭐ Platform Olga Sehat</option>
                      <option value="venue">⚽ Venue Olahraga / Lapangan</option>
                      <option value="klinik">🏥 Klinik / Layanan Kesehatan</option>
                      <option value="komunitas">👥 Komunitas Olahraga</option>
                      <option value="event">🏆 Event Olahraga</option>
                  </select>
              </div>

              <!-- Interactive Star Rating -->
              <div>
                  <label class="block text-sm font-semibold text-gray-700 text-center mb-2">Pilih Rating Bintang</label>
                  <div class="flex justify-center items-center space-x-2" id="starContainer">
                      <i class="fas fa-star text-3xl cursor-pointer text-amber-400 hover:text-amber-500 transition-colors user-star-icon" data-value="1" onclick="setRating(1)"></i>
                      <i class="fas fa-star text-3xl cursor-pointer text-amber-400 hover:text-amber-500 transition-colors user-star-icon" data-value="2" onclick="setRating(2)"></i>
                      <i class="fas fa-star text-3xl cursor-pointer text-amber-400 hover:text-amber-500 transition-colors user-star-icon" data-value="3" onclick="setRating(3)"></i>
                      <i class="fas fa-star text-3xl cursor-pointer text-amber-400 hover:text-amber-500 transition-colors user-star-icon" data-value="4" onclick="setRating(4)"></i>
                      <i class="fas fa-star text-3xl cursor-pointer text-amber-400 hover:text-amber-500 transition-colors user-star-icon" data-value="5" onclick="setRating(5)"></i>
                  </div>
                  <input type="hidden" name="rate" id="selectedRate" value="5" required>
              </div>

              <!-- Company / Profesi / Subtitle -->
              <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-1">Pekerjaan / Catatan Tambahan (Opsional)</label>
                  <input type="text" name="company" id="reviewCompanyInput" placeholder="Contoh: Pasien Klinik / Member Futsal" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 text-sm">
              </div>

              <!-- Ulasan Textarea -->
              <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-1">Tulis Ulasan Anda <span class="text-red-500">*</span></label>
                  <textarea name="ulasan" rows="3" required placeholder="Tuliskan pengalaman Anda..." class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 text-sm"></textarea>
              </div>

              <!-- Foto (Opsional) -->
              <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-1">Unggah Foto (Opsional)</label>
                  <input type="file" name="foto" accept="image/*" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100">
              </div>

              <div class="flex space-x-3 pt-2">
                  <button type="button" onclick="closeReviewModal()" class="flex-1 py-2.5 px-4 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg text-sm transition">Batal</button>
                  <button type="submit" class="flex-1 py-2.5 px-4 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-lg text-sm shadow-md hover:shadow-lg transition">Kirim Ulasan</button>
              </div>
          </form>
      </div>
  </div>

  <script>
  @if(session('success'))
      Swal.fire({
          icon: 'success',
          title: 'Berhasil!',
          text: '{{ session('success') }}',
          confirmButtonText: 'OK',
          confirmButtonColor: '#1d4ed8'
      });
  @endif

  @if(session('warning'))
      Swal.fire({
          icon: 'warning',
          title: 'Peringatan',
          text: '{{ session('warning') }}',
          confirmButtonText: 'OK',
          confirmButtonColor: '#1d4ed8'
      });
  @endif

  function openReviewModal(targetType = 'platform', targetId = null, targetName = null) {
      @if(!Auth::check())
          Swal.fire({
              icon: 'warning',
              title: 'Login Diperlukan',
              text: 'Silakan login terlebih dahulu untuk memberikan ulasan & rating.',
              confirmButtonText: 'Login Sekarang',
              showCancelButton: true,
              cancelButtonText: 'Batal',
              confirmButtonColor: '#1d4ed8'
          }).then((result) => {
              if (result.isConfirmed) {
                  window.location.href = '{{ route("login") }}';
              }
          });
          return;
      @endif

      const modal = document.getElementById('reviewModal');
      if (!modal) return;
      modal.classList.remove('hidden');
      document.getElementById('reviewTipeTarget').value = targetType;
      document.getElementById('reviewTargetId').value = targetId || '';
      
      const titleEl = document.getElementById('reviewModalTitle');
      const subTitleEl = document.getElementById('reviewModalSubtitle');
      const companyInput = document.getElementById('reviewCompanyInput');

      if (targetName) {
          titleEl.textContent = `Beri Rating & Ulasan: ${targetName}`;
          subTitleEl.textContent = `Bagikan pengalaman Anda di ${targetName}`;
          if (targetType === 'venue') companyInput.value = `Pengunjung Venue ${targetName}`;
          else if (targetType === 'klinik' || targetType === 'clinic') companyInput.value = `Pasien Klinik ${targetName}`;
          else if (targetType === 'komunitas') companyInput.value = `Anggota Komunitas ${targetName}`;
          else if (targetType === 'event') companyInput.value = `Peserta Event ${targetName}`;
      } else {
          titleEl.textContent = 'Beri Ulasan & Rating';
          subTitleEl.textContent = 'Bagikan pengalaman Anda mengolah tubuh & hidup sehat bersama Olga Sehat!';
          companyInput.value = '';
      }

      setRating(5);
  }

  function closeReviewModal() {
      const modal = document.getElementById('reviewModal');
      if (modal) modal.classList.add('hidden');
  }

  function setRating(val) {
      const selectedRate = document.getElementById('selectedRate');
      if (selectedRate) selectedRate.value = val;
      const stars = document.querySelectorAll('.user-star-icon');
      stars.forEach((star, index) => {
          if (index < val) {
              star.classList.remove('text-gray-300');
              star.classList.add('text-amber-400');
          } else {
              star.classList.remove('text-amber-400');
              star.classList.add('text-gray-300');
          }
      });
  }
  </script>

  @stack('scripts')
</body>
</html>

