<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>OLGA SEHAT - Login Pengelola Kesehatan</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap');
    body {
      font-family: 'Poppins', sans-serif;
      background: #ffffff;
    }
  </style>
</head>
<body class="flex flex-col items-center justify-center min-h-screen p-4">

  <!-- Logo -->
  <div class="mb-6 text-center">
    <img src="assets/olgasehat-icon.png" 
         alt="Logo" 
         class="mx-auto max-h-20 w-auto" />
  </div>

  <!-- Card -->
  <div class="max-w-sm w-full bg-white border-2 rounded-md p-6">
  <!-- Header -->
  <h2 class="text-lg font-bold text-gray-800 text-center">Login Pengelola Kesehatan</h2>
  <p class="text-sm text-gray-600 text-center mt-1">
    Sudah Punya Akun?
    <a href="/regispengelolakesehatan" class="text-[#1a3a7f] font-semibold hover:underline">Register di sini</a>
  </p>

  <!-- Form -->
  <form class="mt-6 space-y-4" action="{{ route('loginproses') }}" method="POST">
    @csrf
    <!-- Email -->
    <div>
      <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
      <input type="email"
             name="email"
             placeholder="Email"
             class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#1a3a7f] outline-none"
             value="{{ old('email') }}" />
      @error('email')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
      @enderror
    </div>

    <!-- Password -->
    <div>
      <label class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
      <input type="password"
             name="password"
             placeholder="Password"
             class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#1a3a7f] outline-none" />
      @error('password')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
      @enderror
    </div>

    <!-- Button -->
    <button type="submit"
            class="w-full bg-[#1a3a7f] hover:bg-[#142b5c] text-white font-semibold py-2 rounded-md transition">
      Login
    </button>
  </form>

  @if(session('success'))
    <div class="mt-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded text-sm text-center">
      {{ session('success') }}
    </div>
  @endif

  @if(session('error'))
    <div class="mt-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded text-sm text-center">
      {{ session('error') }}
    </div>
  @endif

  <!-- Footer text -->
  <p class="text-xs text-gray-600 text-center mt-4">
    Ingin daftar sebagai user di web utama OlgaSehat?
    <a href="/daftaruser" class="text-[#1a3a7f] hover:underline">Klik di sini</a>
  </p>
  </div>

</body>
</html>

