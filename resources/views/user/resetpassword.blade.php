<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>OlgaSehat regis email</title>
  <script src="https://cdn.tailwindcss.com"></script>
   <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
  />
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen p-6">
<!-- Header -->
<header class="bg-white shadow-md fixed top-0 left-0 w-full z-50">
  <div class="container mx-auto flex items-center justify-between py-3 px-6">
    
    <!-- Logo -->
    <a href="#" class="flex items-center space-x-2">
      <img src="assets/logo.png" alt="Olga Sehat Logo" class="h-10 w-auto" />
    </a>

    <!-- Navigation (desktop) -->
    <nav class="hidden md:flex space-x-8 text-gray-700 font-medium">
       <a href="/" class="hover:text-blue-700">Sewa Lapangan</a>
      <a href="/tempatsehat" class="hover:text-blue-700">Tempat Sehat</a>
      <a href="/community" class="hover:text-blue-700">Komunitas</a>
      <a href="/club" class="hover:text-blue-700">Klub</a>
      <a href="/blog-news" class="hover:text-blue-700">Blog & News</a>
    </nav>

    <!-- Actions (desktop) -->
    <div class="hidden md:flex items-center space-x-4">
      <!-- Cart -->
      <button aria-label="Cart" class="text-gray-700 hover:text-blue-700 relative">
        <i class="fas fa-shopping-cart fa-lg"></i>
        <span class="absolute -top-2 -right-2 bg-red-600 text-white text-xs rounded-full px-1.5">0</span>
      </button>
      <!-- Register -->
      <a href="/daftaruser" class="px-5 py-2 border border-blue-600 text-blue-600 rounded-lg hover:bg-blue-50 transition">
        Register
      </a>
      <!-- Login -->
      <a href="/loginuser" class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
        Login
      </a>
    </div>

    <!-- Mobile buttons -->
    <div class="flex md:hidden items-center space-x-4">
      <!-- Cart (mobile) -->
      <button aria-label="Cart" class="text-gray-700 hover:text-blue-700 relative">
        <i class="fas fa-shopping-cart fa-lg"></i>
        <span class="absolute -top-2 -right-2 bg-red-600 text-white text-xs rounded-full px-1.5">0</span>
      </button>
      <!-- Mobile menu button -->
      <button id="mobileMenuBtn" class="text-gray-700 hover:text-blue-700 focus:outline-none" aria-label="Open menu">
        <i class="fas fa-bars fa-lg"></i>
      </button>
    </div>
  </div>

  <!-- Mobile menu -->
  <nav id="mobileMenu" class="hidden md:hidden bg-white border-t border-gray-200 shadow-md">
    <a href="venue.html" class="block px-6 py-3 border-b hover:bg-blue-50 hover:text-blue-700">Sewa Lapangan</a>
    <a href="tempat_sehat.html" class="block px-6 py-3 border-b hover:bg-blue-50 hover:text-blue-700">Tempat Sehat</a>
    <a href="community.html" class="block px-6 py-3 border-b hover:bg-blue-50 hover:text-blue-700">Komunitas</a>
    <a href="club.html" class="block px-6 py-3 border-b hover:bg-blue-50 hover:text-blue-700">Klub</a>
    <a href="blog&news.html" class="block px-6 py-3 border-b hover:bg-blue-50 hover:text-blue-700">Blog & News</a>
    
    <!-- Register & Login di mobile -->
<div class="border-t border-gray-200 px-6 py-4 space-y-3">
  <!-- Register Dropdown -->
  <div class="relative">
    <button id="mobileRegisterBtn" class="w-full text-center border border-blue-600 text-blue-600 rounded-lg py-2 hover:bg-blue-50 flex justify-between items-center">
      Register
      <i class="fas fa-chevron-down ml-2 text-sm"></i>
    </button>
    <div id="mobileRegisterMenu" class="hidden mt-2 bg-white border border-gray-200 rounded-lg shadow-md overflow-hidden">
      <a href="/daftaruser" class="block px-4 py-2 text-sm hover:bg-blue-50 hover:text-blue-700">Register User</a>
      
    </div>
  </div>

 <!-- Register & Login di mobile -->
    <div class="border-t border-gray-200 px-6 py-4 flex space-x-3">
      <a href="/daftaruser" class="flex-1 text-center border border-blue-600 text-blue-600 rounded-lg py-2 hover:bg-blue-50">
        Register
      </a>
      <a href="/loginuser" class="flex-1 text-center bg-blue-600 text-white rounded-lg py-2 hover:bg-blue-700">
        Login
      </a>
    </div>
  </nav>
</header>



  <main class="bg-white rounded-lg shadow-md max-w-4xl w-full grid grid-cols-1 md:grid-cols-2">
    <!-- Left image -->
    <div class="hidden md:block">
      <img 
        src="assets/sports-tools.jpg" 
        alt="Peralatan olahraga di atas rumput" 
        class="object-cover w-full h-full rounded-l-lg"
        onerror="this.onerror=null;this.src='https://placehold.co/400x600?text=Image+Unavailable';"
      />
    </div>

<!-- Right content -->
<div class="p-10 flex flex-col justify-center max-w-md mx-auto">
  <!-- Judul -->
  <h1 class="text-4xl font-bold mb-4 text-gray-900 text-left">Lupa Password ?</h1>
  
  <!-- Subjudul -->
  <p class="text-gray-600 mb-2 text-left leading-relaxed">
    Masukkan alamat email kamu yang sudah terdaftar di 
    <span class="font-bold text-indigo-900">Olgasehat</span>.
  </p>

  <!-- Link kembali -->
  <p class="text-left text-gray-600 mb-6">
    Kembali ke halaman 
    <a href="/loginuser" class="text-blue-600 hover:underline font-medium">Login page</a>
  </p>

  <!-- Input Email -->
  <input 
    type="email" 
    placeholder="Alamat Email" 
    class="w-full mb-4 bg-gray-100 border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
  />

  <!-- Tombol Reset -->
  <button
    class="w-full mb-4 bg-indigo-900 hover:bg-indigo-800 text-white font-semibold py-3 rounded-lg shadow-md transition"
    aria-label="Reset Password"
  >
    RESET PASSWORD
  </button>

  <!-- Info OTP -->
  <p class="text-xs text-gray-500 text-left leading-tight">
    Kami akan mengirimkan kode OTP ke emailmu yang terdaftar
  </p>
</div>





  <script>
  // Dropdown user
  const userBtn = document.getElementById("userMenuBtn");
  const userMenu = document.getElementById("userMenu"); 
  if (userBtn) {
    userBtn.addEventListener("click", () => {
      userMenu.classList.toggle("hidden");
    });
  }
</script>
  <!-- Cart Sidebar -->
  <aside id="cartSidebar" class="fixed top-0 right-0 h-full w-80 bg-white shadow-lg transform translate-x-full transition-transform duration-300 ease-in-out z-50 overflow-y-auto">
    <div class="flex justify-between items-center p-4 border-b border-gray-200">
      <h2 class="font-bold text-lg">JADWAL DIPILIH</h2>
      <button id="closeCartSidebar" aria-label="Close sidebar" class="text-gray-700 hover:text-gray-900 focus:outline-none">
        <i class="fas fa-times fa-lg"></i>
      </button>
    </div>
    <div class="p-4 text-gray-600">
      Belum ada jadwal di keranjang.
    </div>
  </aside>

    <!-- Swiper JS for slider -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>

      // Mobile menu toggle
      const mobileMenuBtn = document.getElementById("mobileMenuBtn");
      const mobileMenu = document.getElementById("mobileMenu");
      mobileMenuBtn.addEventListener("click", () => {
        mobileMenu.classList.toggle("hidden");
      });

      // Cart sidebar toggle
      const cartBtns = document.querySelectorAll('button[aria-label="Cart"]');
      const cartSidebar = document.getElementById('cartSidebar');
      const closeCartSidebarBtn = document.getElementById('closeCartSidebar');

      cartBtns.forEach(cartBtn => {
        cartBtn.addEventListener('click', () => {
          cartSidebar.classList.toggle('translate-x-full');
        });
      });

      closeCartSidebarBtn.addEventListener('click', () => {
        cartSidebar.classList.add('translate-x-full');
      });
    </script>

</script>
</body>
</html>
