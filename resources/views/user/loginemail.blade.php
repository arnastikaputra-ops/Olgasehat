@extends('layouts.app')

@section('title', 'Olga Sehat - Login dengan Email')

@section('content')
<main class="min-h-screen flex items-start justify-center bg-gray-50 px-4 pt-20 pb-12">
  <div class="w-full max-w-6xl grid grid-cols-1 md:grid-cols-2 gap-8 bg-white rounded-lg shadow-sm">
    <!-- Left: Illustration -->
    <div class="hidden md:flex items-center justify-center bg-gradient-to-br from-blue-50 to-green-50 p-8 rounded-l-lg">
      <div class="text-center">
        <div class="mb-6">
          <i class="fas fa-user-md text-6xl text-blue-600 mb-4"></i>
        </div>
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Selamat datang</h2>
        <p class="text-gray-600">Silakan masuk untuk mengakses fitur-fitur unggulan di Olga Sehat</p>
      </div>
    </div>

    <!-- Right: Login Form -->
    <div class="p-8 md:p-12 flex flex-col justify-center">
      <!-- Logo/Brand -->
      <div class="mb-8 text-center md:text-left">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">
          Time to Move!
        </h1>
        <p class="text-gray-600 text-sm">Silakan masuk untuk mengakses fitur-fitur unggulan di Olga Sehat</p>
      </div>

      @if ($errors->any())
        <div class="mb-4 p-3 bg-red-50 border-l-4 border-red-500 text-red-700 rounded text-sm">
          <p class="font-semibold mb-1">Login gagal:</p>
          <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('loginproses') }}" method="POST" class="space-y-4">
        @csrf
        
        <!-- Email/No. HP -->
        <div>
          <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
            Email/No. HP
          </label>
          <input
            type="text"
            id="email"
            name="email"
            value="{{ old('email') }}"
            placeholder="masukkan email"
            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            required
          />
        </div>

        <!-- Password -->
        <div>
          <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
            Password
          </label>
          <div class="relative">
            <input
              type="password"
              id="password"
              name="password"
              placeholder="Masukkan password"
              class="w-full border border-gray-300 rounded-lg px-4 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              required
            />
            <button 
              type="button" 
              onclick="togglePassword('password')"
              class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700"
            >
              <i class="fas fa-eye" id="togglePassword"></i>
            </button>
          </div>
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
          <input 
            type="checkbox" 
            id="remember" 
            name="remember" 
            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
          />
          <label for="remember" class="ml-2 block text-sm text-gray-700">
            Ingat Saya
          </label>
        </div>

        <!-- Divider -->
        <div class="border-t border-gray-200 my-4"></div>

        <!-- Register Link -->
        <p class="text-center text-sm text-gray-600 mb-4">
          Belum punya akun? 
          <a href="/daftaruser" class="text-blue-600 font-medium hover:underline">
            Daftar Sekarang!
          </a>
        </p>

        <!-- Login Button -->
        <button
          type="submit"
          class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 rounded-lg transition-colors"
        >
          Masuk
        </button>
      </form>
    </div>
  </div>
</main>

<script>
function togglePassword(fieldId) {
  const field = document.getElementById(fieldId);
  const toggleIcon = document.getElementById('togglePassword');
  
  if (field.type === 'password') {
    field.type = 'text';
    toggleIcon.classList.remove('fa-eye');
    toggleIcon.classList.add('fa-eye-slash');
  } else {
    field.type = 'password';
    toggleIcon.classList.remove('fa-eye-slash');
    toggleIcon.classList.add('fa-eye');
  }
}
</script>
@endsection
