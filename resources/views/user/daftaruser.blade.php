@extends('layouts.app')

@section('title', 'Olga Sehat - Daftar User')

@section('content')
<main class="min-h-screen flex items-start justify-center bg-gray-50 px-4 pt-20 pb-12">
  <div class="w-full max-w-6xl grid grid-cols-1 md:grid-cols-2 gap-8 bg-white rounded-lg shadow-sm">
    <!-- Left: Illustration -->
    <div class="hidden md:flex items-center justify-center bg-gradient-to-br from-blue-50 to-green-50 p-8 rounded-l-lg">
      <div class="text-center">
        <div class="mb-6">
          <i class="fas fa-dumbbell text-6xl text-green-600 mb-4"></i>
        </div>
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Daftar Sekarang</h2>
        <p class="text-gray-600">Silakan buat akun untuk mengakses fitur-fitur unggulan di Olga Sehat</p>
      </div>
    </div>

    <!-- Right: Register Form -->
    <div class="p-8 md:p-12 flex flex-col justify-center">
      <!-- Logo/Brand -->
      <div class="mb-4 text-center md:text-left">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">
          Time to Move!
        </h1>
        <p class="text-gray-600 text-sm">Silakan buat akun untuk mengakses fitur-fitur unggulan di Olga Sehat</p>
      </div>

      <!-- Google Sign Up Button -->
      <a 
        href="{{ route('auth.google') }}"
        class="w-full mb-4 bg-white hover:bg-gray-50 text-gray-700 font-medium py-3 rounded-lg flex items-center justify-center space-x-3 border border-gray-300 transition-colors shadow-sm cursor-pointer"
      >
        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M21.805 10.023h-9.784v3.954h5.611a4.786 4.786 0 01-2.068 3.141v2.594h3.34c1.953-1.8 3.077-4.442 3.077-7.69 0-.676-.065-1.324-.176-1.999z" fill="#4285F4"/>
          <path d="M12.021 21c2.646 0 4.867-.875 6.489-2.38l-3.34-2.593c-.925.62-2.11.99-3.15.99a5.202 5.202 0 01-4.898-3.6H4.527v2.264A9 9 0 0012.02 21z" fill="#34A853"/>
          <path d="M7.123 13.417a5.155 5.155 0 010-3.252V7.9H4.527a9 9 0 000 8.198l2.596-2.68z" fill="#FBBC05"/>
          <path d="M12.02 6.375c1.44 0 2.73.495 3.75 1.467l2.81-2.814A8.932 8.932 0 0012.02 3a9 9 0 00-7.493 4.9l2.596 2.68a5.202 5.202 0 014.898-3.204z" fill="#EA4335"/>
        </svg>
        <span>Daftar dengan Google</span>
      </a>

      <!-- Divider -->
      <div class="flex items-center my-4">
        <div class="flex-1 border-t border-gray-300"></div>
        <span class="px-4 text-sm text-gray-500">atau dengan email</span>
        <div class="flex-1 border-t border-gray-300"></div>
      </div>

      @if ($errors->any())
        <div class="mb-4 p-3 bg-red-50 border-l-4 border-red-500 text-red-700 rounded text-sm">
          <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('user.register.submit') }}" method="POST" class="space-y-4">
        @csrf
        
        <!-- Nama Lengkap -->
        <div>
          <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
            Nama Lengkap <span class="text-red-500">*</span>
          </label>
          <input 
            type="text" 
            id="name" 
            name="name" 
            value="{{ old('name') }}" 
            required 
            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            placeholder="Masukkan nama lengkap"
          />
        </div>

        <!-- Email -->
        <div>
          <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
            Email <span class="text-red-500">*</span>
          </label>
          <input 
            type="email" 
            id="email" 
            name="email" 
            value="{{ old('email') }}" 
            required 
            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            placeholder="contoh@email.com"
          />
        </div>

        <!-- Password -->
        <div>
          <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
            Password <span class="text-red-500">*</span>
          </label>
          <div class="relative">
            <input 
              type="password" 
              id="password" 
              name="password" 
              required 
              class="w-full border border-gray-300 rounded-lg px-4 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              placeholder="Minimal 8 karakter"
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

        <!-- Confirm Password -->
        <div>
          <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
            Konfirmasi Password <span class="text-red-500">*</span>
          </label>
          <div class="relative">
            <input 
              type="password" 
              id="password_confirmation" 
              name="password_confirmation" 
              required 
              class="w-full border border-gray-300 rounded-lg px-4 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              placeholder="Ulangi password"
            />
            <button 
              type="button" 
              onclick="togglePassword('password_confirmation')"
              class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700"
            >
              <i class="fas fa-eye" id="togglePasswordConfirmation"></i>
            </button>
          </div>
        </div>

        <!-- Submit Button -->
        <button 
          type="submit" 
          class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 rounded-lg transition-colors mt-6"
        >
          Daftar
        </button>
      </form>

      <!-- Divider -->
      <div class="border-t border-gray-200 pt-4 mt-6">
        <p class="text-center text-sm text-gray-600">
          Sudah punya akun? 
          <a href="/loginuser" class="text-blue-600 font-medium hover:underline">
            Masuk Sekarang!
          </a>
        </p>
      </div>
    </div>
  </div>
</main>

<script>
function togglePassword(fieldId) {
  const field = document.getElementById(fieldId);
  const toggleIcon = fieldId === 'password' ? document.getElementById('togglePassword') : document.getElementById('togglePasswordConfirmation');
  
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
