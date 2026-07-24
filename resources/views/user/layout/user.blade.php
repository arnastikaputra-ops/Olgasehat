<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>@yield('title', 'Olga Sehat - Profile')</title>
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
  <style>
    /* Custom scroll for file drop box */
    ::-webkit-scrollbar {
      width: 4px;
      height: 4px;
    }
    ::-webkit-scrollbar-thumb {
      background-color: rgba(156, 163, 175, 0.5);
      border-radius: 6px;
    }
    .input-date {
      max-width: 4rem;
    }
    .img-sport {
      width: 48px;
      height: 48px;
      border-radius: 0.5rem;
      object-fit: cover;
      background: #f3f4f6; /* light gray fallback */
      box-shadow: 0 1px 3px rgb(0 0 0 / 0.1);
    }
    .btn-save {
      background-color: #f97316;
      color: white;
      font-weight: 700;
      transition: background-color 0.3s ease;
    }
    .btn-save:hover {
      background-color: #ea580c;
    }
    /* Owner Avatar Styles - seperti di pemilik lapangan */
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
</head>
<body class="bg-white text-gray-800 font-sans">
<!-- HEADER -->
<header id="mainHeader" class="fixed top-0 left-0 right-0 z-50 shadow-md bg-white transition-transform duration-300 ease-in-out">
  <div class="container mx-auto flex items-center justify-between py-4 px-6">

    <!-- Logo -->
    <a href="{{ url('/') }}" class="flex items-center space-x-2">
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
        <button id="languageBtnUser" class="text-gray-700 hover:text-blue-700 focus:outline-none flex items-center space-x-2">
          <i class="fas fa-globe fa-lg"></i>
          <span id="currentLanguageUser">ID</span>
          <i class="fas fa-chevron-down text-sm"></i>
        </button>
        <div id="languageDropdownUser" class="hidden absolute right-0 mt-2 w-48 bg-white border rounded-md shadow-lg z-50 max-h-60 overflow-y-auto">
          <!-- Daftar bahasa akan diisi oleh JavaScript -->
        </div>
      </div>

      <!-- Dropdown User -->
      <div class="relative owner-topbar">
        <button id="userMenuBtn" class="owner-avatar-toggle focus:outline-none" data-toggle="dropdown">
          <div class="owner-avatar-sm mr-2">
            @if(Auth::user()->image ?? false)
              <img src="{{ asset('storage/' . Auth::user()->image) }}" alt="{{ Auth::user()->name }}">
            @else
              <span>{{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}</span>
            @endif
          </div>
          <span class="owner-name hidden md:inline">{{ Auth::user()->name ?? 'User' }}</span>
          <i class="fas fa-chevron-down"></i>
        </button>
        <!-- Dropdown menu -->
        <div id="userMenu" class="hidden absolute right-0 mt-2 owner-dropdown bg-white shadow-lg border-0 z-50">
          <div class="owner-profile-card text-center mb-3">
            <div class="owner-avatar-lg mx-auto mb-2">
              @if(Auth::user()->image ?? false)
                <img src="{{ asset('storage/' . Auth::user()->image) }}" alt="{{ Auth::user()->name }}">
              @else
                <span>{{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}</span>
              @endif
            </div>
            <h6 class="mb-0">{{ Auth::user()->name ?? 'User' }}</h6>
            <span class="badge badge-role mt-1">User</span>
          </div>
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
          <div class="dropdown-divider my-2" style="border-top: 1px solid #e5e7eb;"></div>
          <form id="logout-form" action="{{ route('user.logout') }}" method="POST">
            @csrf
            <button type="submit" class="dropdown-item w-full text-left text-red-600 hover:bg-red-50" style="border: none; background: none; cursor: pointer;">
              <span data-translate>Logout</span>
              <i class="fas fa-sign-out-alt"></i>
            </button>
          </form>
        </div>
      </div>
    </div>

    <!-- Header Mobile -->
    <div class="flex md:hidden items-center space-x-4 ml-auto">
      <!-- Language Selector Mobile -->
      <div class="relative">
        <button id="languageBtnUserMobile" class="text-gray-700 hover:text-blue-700 focus:outline-none flex items-center space-x-2">
          <i class="fas fa-globe fa-lg"></i>
          <span id="currentLanguageUserMobile">ID</span>
          <i class="fas fa-chevron-down text-sm"></i>
        </button>
        <div id="languageDropdownUserMobile" class="hidden absolute right-0 mt-2 w-48 bg-white border rounded-md shadow-lg z-50 max-h-60 overflow-y-auto">
          <!-- Daftar bahasa akan diisi oleh JavaScript -->
        </div>
      </div>

      <!-- Dropdown User Mobile -->
      <div class="relative">
        <button id="mobileUserBtn" class="flex items-center space-x-2 focus:outline-none">
          @if(Auth::user()->image ?? false)
              <img src="{{ asset('storage/' . Auth::user()->image) }}" alt="User Avatar" class="w-8 h-8 rounded-full border object-cover" />
          @else
              <div class="w-8 h-8 rounded-full border bg-blue-100 flex items-center justify-center text-blue-700 font-semibold text-sm">
                {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
              </div>
          @endif
          <i class="fas fa-chevron-down text-gray-500 text-xs sm:text-sm"></i>
        </button>
        <!-- Dropdown menu -->
        <div id="mobileUserMenu" class="hidden absolute right-0 mt-2 owner-dropdown bg-white shadow-lg border-0 z-50" style="width: 200px; padding: 16px 16px 8px;">
          <div class="owner-profile-card text-center mb-3">
            <div class="owner-avatar-lg mx-auto mb-2" style="width: 56px; height: 56px; font-size: 1.1rem;">
              @if(Auth::user()->image ?? false)
                <img src="{{ asset('storage/' . Auth::user()->image) }}" alt="{{ Auth::user()->name }}">
              @else
                <span>{{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}</span>
              @endif
            </div>
            <h6 class="mb-0" style="font-size: 0.875rem;">{{ Auth::user()->name ?? 'User' }}</h6>
            <span class="badge badge-role mt-1">User</span>
          </div>
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
          <div class="dropdown-divider my-2" style="border-top: 1px solid #e5e7eb;"></div>
          <form id="mobile-logout-form" action="{{ route('user.logout') }}" method="POST">
            @csrf
            <button type="submit" class="dropdown-item w-full text-left text-red-600 hover:bg-red-50" style="border: none; background: none; cursor: pointer; font-size: 0.875rem;">
              <span data-translate>Logout</span>
              <i class="fas fa-sign-out-alt"></i>
            </button>
          </form>
        </div>
      </div>

      <!-- Tombol Hamburger -->
      <button id="mobileMenuBtn"
              class="text-gray-700 hover:text-blue-700 focus:outline-none"
              aria-label="Open menu">
        <i class="fas fa-bars fa-lg"></i>
      </button>
    </div>

    <!-- Menu Navigasi Mobile -->
    <nav id="mobileMenu"
         class="hidden flex-col md:hidden bg-white border-t border-gray-200 shadow-md
                transition-all duration-300 ease-in-out absolute top-full left-0 w-full z-[50]">

      <!-- Link Navigasi -->
      <a href="/venue" class="block px-6 py-4 border-b text-center font-medium text-gray-700 hover:bg-blue-50 hover:text-blue-700 menu-item opacity-0 translate-y-1 transition-all duration-200" data-translate>Fasilitas Olahraga</a>
      <a href="/healthy" class="block px-6 py-4 border-b text-center font-medium text-gray-700 hover:bg-blue-50 hover:text-blue-700 menu-item opacity-0 translate-y-1 transition-all duration-200" data-translate>Layanan Kesehatan</a>
      <a href="/community" class="block px-6 py-4 border-b text-center font-medium text-gray-700 hover:bg-blue-50 hover:text-blue-700 menu-item opacity-0 translate-y-1 transition-all duration-200" data-translate>Komunitas & Aktivitas</a>
      <a href="/blog-news" class="block px-6 py-4 border-b text-center font-medium text-gray-700 hover:bg-blue-50 hover:text-blue-700 menu-item opacity-0 translate-y-1 transition-all duration-200" data-translate>Info Sehat</a>

      <!-- User Links -->
      <div class="border-t pt-4 px-6 pb-4">
        <form action="{{ route('user.logout') }}" method="POST">
          @csrf
          <button type="submit" class="w-full px-4 py-3 text-center bg-red-600 text-white font-semibold rounded-md hover:bg-red-700 menu-item opacity-0 translate-y-1 transition-all duration-200" data-translate>Logout</button>
        </form>
      </div>
    </nav>
</header>
  <main class="pt-18">
    @yield('content')
  </main>



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

      // Desktop Dropdowns
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
        // Desktop
        if (!userBtn?.contains(e.target) && !userMenu?.contains(e.target)) {
          userMenu?.classList.add("hidden", "opacity-0", "scale-95");
        }
        // Mobile User
        if (!mobileUserBtn?.contains(e.target) && !mobileUserMenu?.contains(e.target)) {
          mobileUserMenu?.classList.add("hidden", "opacity-0", "scale-95");
        }
        // Mobile
        if (!mobileMenu.contains(e.target) && !mobileMenuBtn?.contains(e.target)) {
          mobileMenu.classList.add("hidden");
        }
      });



      // Language Dropdown Functionality
      const languageBtnUser = document.getElementById("languageBtnUser");
      const languageDropdownUser = document.getElementById("languageDropdownUser");
      const currentLanguageUser = document.getElementById("currentLanguageUser");
      const languageBtnUserMobile = document.getElementById("languageBtnUserMobile");
      const languageDropdownUserMobile = document.getElementById("languageDropdownUserMobile");
      const currentLanguageUserMobile = document.getElementById("currentLanguageUserMobile");

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

      function fetchLanguagesUser() {
        populateLanguageDropdownUser(languages);
        populateLanguageDropdownUserMobile(languages);
        
        // Load saved language from localStorage dan auto-translate
        const savedLang = localStorage.getItem('selectedLanguage') || 'id';
        const savedLangObj = languages.find(l => l.code === savedLang);
        if (savedLangObj) {
          currentLanguageUser.textContent = savedLangObj.code.toUpperCase();
          currentLanguageUserMobile.textContent = savedLangObj.code.toUpperCase();
          
          // Auto-translate saat page load jika bukan bahasa Indonesia
          if (savedLang !== 'id') {
            // Translate segera setelah DOM ready
            if (document.readyState === 'complete' || document.readyState === 'interactive') {
              translatePageUser(savedLang);
            } else {
              document.addEventListener('DOMContentLoaded', () => {
                translatePageUser(savedLang);
              });
            }
          }
        }
      }

      function populateLanguageDropdownUser(languages) {
        languageDropdownUser.innerHTML = '';
        languages.forEach(lang => {
          const li = document.createElement('li');
          li.className = 'px-4 py-2 hover:bg-gray-100 cursor-pointer flex items-center space-x-2';
          li.innerHTML = `<span>${lang.flag || '🌐'}</span><span>${lang.name} (${lang.code.toUpperCase()})</span>`;
          li.addEventListener('click', () => {
            currentLanguageUser.textContent = lang.code.toUpperCase();
            languageDropdownUser.classList.add('hidden');
            changeLanguageUser(lang.code);
          });
          languageDropdownUser.appendChild(li);
        });
      }

      function populateLanguageDropdownUserMobile(languages) {
        languageDropdownUserMobile.innerHTML = '';
        languages.forEach(lang => {
          const li = document.createElement('li');
          li.className = 'px-4 py-2 hover:bg-gray-100 cursor-pointer flex items-center space-x-2';
          li.innerHTML = `<span>${lang.flag || '🌐'}</span><span>${lang.name} (${lang.code.toUpperCase()})</span>`;
          li.addEventListener('click', () => {
            currentLanguageUserMobile.textContent = lang.code.toUpperCase();
            languageDropdownUserMobile.classList.add('hidden');
            changeLanguageUser(lang.code);
          });
          languageDropdownUserMobile.appendChild(li);
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

      async function changeLanguageUser(langCode) {
        localStorage.setItem('selectedLanguage', langCode);
        const langObj = languages.find(l => l.code === langCode);
        if (langObj) {
          currentLanguageUser.textContent = langObj.code.toUpperCase();
          currentLanguageUserMobile.textContent = langObj.code.toUpperCase();
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

      async function translatePageUser(langCode) {
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
      languageBtnUser?.addEventListener("click", (e) => {
        e.stopPropagation();
        languageDropdownUser.classList.toggle("hidden");
      });

      // Toggle mobile language dropdown
      languageBtnUserMobile?.addEventListener("click", (e) => {
        e.stopPropagation();
        languageDropdownUserMobile.classList.toggle("hidden");
      });

      // Close dropdowns on outside click
      window.addEventListener("click", (e) => {
        if (!languageBtnUser?.contains(e.target) && !languageDropdownUser?.contains(e.target)) {
          languageDropdownUser?.classList.add("hidden");
        }
        if (!languageBtnUserMobile?.contains(e.target) && !languageDropdownUserMobile?.contains(e.target)) {
          languageDropdownUserMobile?.classList.add("hidden");
        }
      });

      // Fetch languages on page load
      fetchLanguagesUser();

      // Header hide/show on scroll
      let lastScrollTop = 0;
      window.addEventListener('scroll', function() {
        let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        const header = document.getElementById('mainHeader');
        if (scrollTop > lastScrollTop && scrollTop > 100) {
          // Scroll down and past 100px
          header.style.transform = 'translateY(-100%)';
        } else {
          // Scroll up
          header.style.transform = 'translateY(0)';
        }
        lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
      });
    });

    function showContactModal() {
        Swal.fire({
            title: 'Hubungi Kami',
            text: 'Sedang memuat data kontak...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
                fetch('/api/contact-us')
                    .then(response => response.json())
                    .then(contacts => {
                        if (contacts.length === 0) {
                            Swal.fire({
                                icon: 'info',
                                title: 'Hubungi Kami',
                                text: 'Saluran kontak belum dikonfigurasi oleh admin.',
                                confirmButtonColor: '#013D9D'
                            });
                            return;
                        }
                        
                        let htmlContent = '<div class="space-y-3 text-left">';
                        contacts.forEach(contact => {
                            let icon = 'fa-comment';
                            let url = '#';
                            let badgeColor = 'bg-gray-100 text-gray-800';
                            
                            if (contact.type === 'whatsapp') {
                                icon = 'fa-whatsapp';
                                let phoneNum = contact.kontak.replace(/[^0-9]/g, '');
                                if (phoneNum.startsWith('0')) {
                                    phoneNum = '62' + phoneNum.slice(1);
                                }
                                url = `https://wa.me/${phoneNum}`;
                                badgeColor = 'bg-green-100 text-green-800';
                            } else if (contact.type === 'email') {
                                icon = 'fa-envelope';
                                url = `mailto:${contact.kontak}`;
                                badgeColor = 'bg-blue-100 text-blue-800';
                            } else if (contact.type === 'phone' || contact.type === 'telepon') {
                                icon = 'fa-phone';
                                url = `tel:${contact.kontak.replace(/[^0-9]/g, '')}`;
                                badgeColor = 'bg-indigo-100 text-indigo-800';
                            }
                            
                            htmlContent += `
                                <a href="${url}" target="_blank" class="flex items-center justify-between p-3 border border-gray-200 rounded-xl hover:bg-gray-50 transition duration-150 block mb-2 shadow-sm">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center ${badgeColor}">
                                            <i class="fab ${icon} fas ${icon} text-lg"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-gray-900 text-sm">${contact.title}</h4>
                                            <p class="text-xs text-gray-500">${contact.kontak}</p>
                                        </div>
                                    </div>
                                    <i class="fas fa-chevron-right text-gray-400 text-sm"></i>
                                </a>
                            `;
                        });
                        htmlContent += '</div>';
                        
                        Swal.fire({
                            title: 'Hubungi Kami',
                            html: htmlContent,
                            showConfirmButton: false,
                            showCloseButton: true,
                            customClass: {
                                popup: 'rounded-2xl'
                            }
                        });
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Terjadi kesalahan saat memuat data kontak.',
                            confirmButtonColor: '#013D9D'
                        });
                    });
            }
        });
    }

    // HOME JS //
    @if(session('success'))
      Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: '{{ session('success') }}',
        confirmButtonText: 'OK'
      });
    @endif
  </script>
</body>
</html>


