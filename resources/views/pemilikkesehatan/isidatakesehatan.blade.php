 <!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1" name="viewport"/>
  <title>Olga Sehat Free Trial - Pengelola Kesehatan</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet"/>
  <style>
    body {
      font-family: 'Inter', sans-serif;
    }
  </style>
</head>
<body class="bg-gray-50">
  <div class="min-h-screen flex flex-col md:flex-row justify-center items-start md:items-center p-6 md:p-12 gap-8 md:gap-16">
   <!-- Left side -->
<div class="max-w-md w-full">
  <div class="flex items-center md:justify-start justify-center gap-3 mb-6 text-center">
    <div class="w-52 h-52 rounded-lg flex items-center justify-center">
      <img src="assets/olgasehat-icon.png" alt="Olga Sehat Logo" class="mx-auto">
    </div>
  </div>
 
  <h2 class="font-bold text-xl mb-2 text-gray-800 text-center md:text-left">
    Welcome, Guest!
  </h2>
  <p class="text-gray-600 text-sm mb-4 leading-relaxed text-center md:text-left">
    Nikmati akses free trial dan jelajahi semua fitur unggulan kami<br/>
    Software Fasilitas Kesehatan Di Indonesia
  </p>

  <div class="bg-blue-50 p-4 rounded-lg mb-6 text-center md:text-left">
    <div class="text-xs">
      <p class="mb-1">
        Email yang anda daftarkan salah? 
        <a class="text-blue-600 font-medium hover:underline" href="/regispengelolakesehatan">Klik disini</a>
      </p>
      <p>
        Sudah mempunyai akun? 
        <a class="text-blue-600 font-medium hover:underline" href="/loginpengelolakesehatan">Login di sini</a>
      </p>
    </div>
  </div>

  <div class="hidden md:block mt-8">
    <img alt="Illustration of a person walking in a city with wifi and location icons around" 
         class="w-full max-w-xs mx-auto md:mx-0" 
         src="assets/ilus.png"/>
  </div>
</div>

    
    <!-- Right side form -->
<div class="bg-white shadow-lg rounded-xl p-6 w-full max-w-sm">
  <div class="text-center mb-6">
    <h3 class="font-bold text-lg text-gray-800">Mitra yang disediakan PT. indo Apps Solusindo</h3>
    <p class="text-sm text-gray-500">Olga Sehat Smart Health</p>
  </div>
      <form action="{{ route('pengelolakesehatan.store') }}" method="POST" autocomplete="off" novalidate="">
        @csrf
        
        <div class="mb-4">
          <label class="text-xs font-semibold text-gray-700 mb-1 block" for="nama-anda">Nama Anda</label>
          <input name="nama_anda" class="w-full text-sm rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                 id="nama-anda" 
                 placeholder="Masukkan nama lengkap" 
                 type="text"
                 value="{{ old('nama_anda') }}"
                 required/>
          @error('nama_anda')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>
        
        <div class="mb-4">
          <label class="text-xs font-semibold text-gray-700 mb-1 block" for="nama-bisnis">Nama Bisnis</label>
          <input name="nama_bisnis" class="w-full text-sm rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                 id="nama-bisnis" 
                 placeholder="Masukkan nama bisnis" 
                 type="text"
                 value="{{ old('nama_bisnis') }}"
                 required/>
          @error('nama_bisnis')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>
        
        <div class="mb-4">
          <label class="text-xs font-semibold text-gray-700 mb-1 block" for="tipe-venue">Tipe Fasilitas Kesehatan</label>
          <select name="tipe_venue" class="w-full text-sm rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                  id="tipe-venue"
                  required>
            <option value="">Pilih tipe fasilitas kesehatan</option>
            <option value="Klinik" {{ old('tipe_venue') == 'Klinik' ? 'selected' : '' }}>Klinik</option>
            <option value="Rumah Sakit" {{ old('tipe_venue') == 'Rumah Sakit' ? 'selected' : '' }}>Rumah Sakit</option>
            <option value="Puskesmas" {{ old('tipe_venue') == 'Puskesmas' ? 'selected' : '' }}>Puskesmas</option>
            <option value="Laboratorium" {{ old('tipe_venue') == 'Laboratorium' ? 'selected' : '' }}>Laboratorium</option>
            <option value="Apotek" {{ old('tipe_venue') == 'Apotek' ? 'selected' : '' }}>Apotek</option>
            <option value="Praktik Dokter" {{ old('tipe_venue') == 'Praktik Dokter' ? 'selected' : '' }}>Praktik Dokter</option>
            <option value="Fasilitas Kesehatan Lainnya" {{ old('tipe_venue') == 'Fasilitas Kesehatan Lainnya' ? 'selected' : '' }}>Fasilitas Kesehatan Lainnya</option>
          </select>
          @error('tipe_venue')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>
        
        <div class="mb-4">
          <label class="text-xs font-semibold text-gray-700 mb-1 block" for="kontak-bisnis">Kontak Bisnis <span class="text-red-500">*</span></label>
          <div class="flex">
            <div class="flex items-center px-3 rounded-l-lg border border-r-0 border-gray-300 bg-gray-100 text-gray-700 text-sm font-medium">
              +62
            </div>
            <input name="kontak_bisnis" class="w-full text-sm rounded-r-lg border border-gray-300 bg-gray-50 px-4 py-3 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                   id="kontak-bisnis" 
                   placeholder="81234567890" 
                   type="tel"
                   value="{{ old('kontak_bisnis') }}"
                   required/>
          </div>
          <small class="text-gray-500 text-xs mt-1 block">Masukkan nomor telepon tanpa kode negara (+62)</small>
          @error('kontak_bisnis')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>
        
        <div class="mb-4">
          <label class="text-xs font-semibold text-gray-700 mb-1 block" for="email-bisnis">Email Bisnis</label>
          <input name="email_bisnis" class="w-full text-sm rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                 id="email-bisnis" 
                 placeholder="Masukkan email bisnis" 
                 type="email"
                 value="{{ old('email_bisnis') }}"
                 required/>
          @error('email_bisnis')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>
        
        <div class="mb-4">
          <label class="text-xs font-semibold text-gray-700 mb-1 block" for="password">Password</label>
          <div class="relative">
            <input name="password" class="w-full text-sm rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 pr-10" 
                   id="password" 
                   placeholder="Buat password" 
                   type="password"
                   required/>
            <span class="absolute right-3 top-3.5 text-gray-400 cursor-pointer hover:text-gray-600">
            </span>
          </div>
          @error('password')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>
        
        <div class="mb-6">
          <label class="text-xs font-semibold text-gray-700 mb-1 block" for="konfirmasi-password">Konfirmasi Password</label>
          <div class="relative">
            <input name="password_confirmation" class="w-full text-sm rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 pr-10" 
                   id="konfirmasi-password" 
                   placeholder="Konfirmasi password" 
                   type="password"
                   required/>
            <span class="absolute right-3 top-3.5 text-gray-400 cursor-pointer hover:text-gray-600">
            </span>
          </div>
        </div>
        
        <div class="flex items-start mb-6">
          <input class="mt-1 mr-2 rounded focus:ring-blue-500"
                 id="terms"
                 type="checkbox"
                 name="terms"
                 required/>
          <label class="text-xs text-gray-600" for="terms">
            Saya menyetujui
            <a class="text-blue-600 hover:underline font-medium" href="#" onclick="showTermsModal()">
              Syarat & Ketentuan
            </a>
          </label>
        </div>
        
        <button class="w-full bg-blue-600 text-white font-semibold py-3 rounded-lg hover:bg-blue-700 transition shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2" 
                type="submit">
          Submit
        </button>
      </form>
    </div>
  </div>
  
  
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: '{{ session('success') }}',
        confirmButtonText: 'OK'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '/loginpengelolakesehatan';
        }
    });
</script>
@endif

@if($errors->any())
<script>
    Swal.fire({
        icon: 'error',
        title: 'Terjadi Kesalahan',
        html: `
            <ul style="text-align: left;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        `,
        confirmButtonText: 'OK'
    });
</script>
@endif

<!-- Modal Syarat & Ketentuan -->
<div id="termsModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
  <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[80vh] overflow-y-auto">
    <div class="p-6">
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold text-gray-800">Syarat & Ketentuan Olga Sehat</h2>
        <button onclick="closeTermsModal()" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
      </div>
      <div class="text-sm text-gray-700 space-y-4">
        <p><strong>1. Penerimaan Syarat & Ketentuan</strong></p>
        <p>Dengan mengakses dan menggunakan layanan Olga Sehat, Anda menyetujui untuk terikat oleh Syarat & Ketentuan ini. Jika Anda tidak setuju dengan syarat ini, mohon untuk tidak menggunakan layanan kami.</p>

        <p><strong>2. Penggunaan Layanan</strong></p>
        <p>Layanan Olga Sehat disediakan untuk membantu pemilik venue olahraga dan kesehatan dalam mengelola fasilitas mereka. Anda bertanggung jawab untuk memastikan bahwa informasi yang Anda berikan akurat dan terkini.</p>

        <p><strong>3. Kewajiban Pengguna</strong></p>
        <ul class="list-disc list-inside ml-4">
          <li>Menyediakan informasi yang benar dan akurat saat pendaftaran</li>
          <li>Menjaga kerahasiaan akun dan password</li>
          <li>Tidak menggunakan layanan untuk tujuan ilegal</li>
          <li>Mematuhi semua peraturan dan kebijakan yang berlaku</li>
        </ul>

        <p><strong>4. Hak Kekayaan Intelektual</strong></p>
        <p>Semua konten, fitur, dan fungsionalitas layanan Olga Sehat adalah milik PT. Indo Apps Solusindo dan dilindungi oleh undang-undang hak cipta.</p>

        <p><strong>5. Pembatasan Tanggung Jawab</strong></p>
        <p>Olga Sehat tidak bertanggung jawab atas kerugian langsung atau tidak langsung yang timbul dari penggunaan layanan ini.</p>

        <p><strong>6. Perubahan Syarat & Ketentuan</strong></p>
        <p>Kami berhak untuk mengubah Syarat & Ketentuan ini kapan saja. Perubahan akan diberitahukan melalui platform kami.</p>

        <p><strong>7. Hukum yang Berlaku</strong></p>
        <p>Syarat & Ketentuan ini diatur oleh hukum Republik Indonesia.</p>
      </div>
      <div class="mt-6 flex justify-end">
        <button onclick="closeTermsModal()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
          Saya Mengerti
        </button>
      </div>
    </div>
  </div>
</div>

<script>
function showTermsModal() {
    document.getElementById('termsModal').classList.remove('hidden');
}

function closeTermsModal() {
    document.getElementById('termsModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('termsModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeTermsModal();
    }
});
</script>

</body>
</html>

