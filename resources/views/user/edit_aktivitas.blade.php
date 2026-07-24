<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>OLGA SEHAT — Edit Aktivitas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        .glass { background: rgba(255,255,255,0.9); backdrop-filter: blur(8px); }
    </style>
</head>
<body class="bg-slate-50 min-h-screen font-sans">
    
    <div class="bg-sky-700 py-4 shadow-md">
        <header class="max-w-5xl mx-auto px-6 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-white">OLGA SEHAT</h1>
            <a href="{{ route('user.riwayat-komunitas') }}" class="text-white/80 hover:text-white transition">Kembali ke Riwayat</a>
        </header>
    </div>

    <main class="max-w-5xl mx-auto px-6 pt-10 pb-24">
        <h2 class="text-3xl md:text-4xl font-bold text-slate-800">Edit Aktivitas</h2>
        <p class="text-lg text-slate-600 mt-2 border-b pb-4">Perbarui informasi aktivitas kamu.</p>

        <section class="mt-8">
            <form action="{{ route('user.aktivitas.update', $activity->id) }}" method="POST" enctype="multipart/form-data" class="bg-white p-8 rounded-2xl shadow-xl glass border border-slate-100">
                @csrf
                @method('PUT')
                <input type="hidden" name="jenis" value="{{ $activity->jenis }}">
                
                <h2 class="text-2xl font-bold text-sky-800 border-b pb-3 mb-4">
                    @if($activity->jenis == 'komunitas')
                        Detail Komunitas
                    @elseif($activity->jenis == 'membership')
                        Detail Paket Membership
                    @else
                        Detail Event Olahraga
                    @endif
                </h2>
                
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

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">
                            @if($activity->jenis == 'komunitas')
                                Nama Komunitas
                            @elseif($activity->jenis == 'membership')
                                Nama Paket Membership
                            @else
                                Nama Event
                            @endif
                            <span class="text-red-500">*</span>
                        </label>
                        <input name="nama" 
                               class="w-full rounded-lg border border-slate-300 p-3 focus:border-sky-500 focus:ring-sky-500" 
                               placeholder="Contoh: Kumpulan Pemuda Futsal" 
                               required 
                               value="{{ old('nama', $activity->nama) }}">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Kategori Olahraga <span class="text-red-500">*</span></label>
                        <select name="kategori" class="w-full rounded-lg border border-slate-300 p-3 focus:border-sky-500 focus:ring-sky-500" required>
                            <option value="">Pilih Kategori</option>
                            <option value="Futsal" {{ old('kategori', $activity->kategori) == 'Futsal' ? 'selected' : '' }}>Futsal</option>
                            <option value="Basket" {{ old('kategori', $activity->kategori) == 'Basket' ? 'selected' : '' }}>Basket</option>
                            <option value="Yoga" {{ old('kategori', $activity->kategori) == 'Yoga' ? 'selected' : '' }}>Yoga</option>
                            <option value="Gym" {{ old('kategori', $activity->kategori) == 'Gym' ? 'selected' : '' }}>Gym</option>
                            @if($activity->jenis == 'event')
                            <option value="Run / Fun Run" {{ old('kategori', $activity->kategori) == 'Run / Fun Run' ? 'selected' : '' }}>Run / Fun Run</option>
                            <option value="Gym Challenge" {{ old('kategori', $activity->kategori) == 'Gym Challenge' ? 'selected' : '' }}>Gym Challenge</option>
                            @endif
                            <option value="Lainnya" {{ old('kategori', $activity->kategori) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Lokasi</label>
                        <input name="lokasi" 
                               class="w-full rounded-lg border border-slate-300 p-3 focus:border-sky-500 focus:ring-sky-500" 
                               placeholder="Kota Denpasar, Bali" 
                               value="{{ old('lokasi', $activity->lokasi) }}">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Biaya Bergabung</label>
                        <div class="flex items-center gap-4 mt-2 h-[48px]">
                            <label class="flex items-center gap-2">
                                <input type="radio" name="biaya" value="gratis" class="form-radio text-sky-600 biaya-radio" {{ old('biaya', $activity->biaya_bergabung) == 'gratis' ? 'checked' : '' }}>
                                <span class="text-sm text-slate-600">Gratis</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="radio" name="biaya" value="berbayar" class="form-radio text-sky-600 biaya-radio" {{ old('biaya', $activity->biaya_bergabung) == 'berbayar' ? 'checked' : '' }}>
                                <span class="text-sm text-slate-600">Berbayar</span>
                            </label>
                        </div>
                    </div>
                    
                    <div id="harga-container" class="{{ old('biaya', $activity->biaya_bergabung) == 'berbayar' ? '' : 'hidden' }}">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Harga (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" 
                               name="harga" 
                               class="w-full rounded-lg border border-slate-300 p-3 focus:border-sky-500 focus:ring-sky-500" 
                               placeholder="Contoh: 50000" 
                               min="0" 
                               value="{{ old('harga', $activity->harga) }}" 
                               {{ old('biaya', $activity->biaya_bergabung) == 'berbayar' ? 'required' : '' }}>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Deskripsi Lengkap <span class="text-red-500">*</span></label>
                        <textarea name="deskripsi" 
                                  rows="4" 
                                  class="w-full rounded-lg border border-slate-300 p-3 focus:border-sky-500 focus:ring-sky-500" 
                                  placeholder="Tulis ringkasan aktivitas" 
                                  required>{{ old('deskripsi', $activity->deskripsi) }}</textarea>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Link Grup / Kontak (WhatsApp / IG)</label>
                        <input name="link"
                               class="w-full rounded-lg border border-slate-300 p-3 focus:border-sky-500 focus:ring-sky-500"
                               placeholder="https://wa.me/.. atau @akuninstagram"
                               value="{{ old('link', $activity->link_kontak) }}">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">
                            <i class="fas fa-phone-alt mr-2 text-sky-600"></i>Kontak & Informasi Lainnya (Opsional)
                        </label>
                        <input name="link_kontak_2"
                               class="w-full rounded-lg border border-slate-300 p-3 focus:border-sky-500 focus:ring-sky-500"
                               placeholder="https://wa.me/.. atau @akuninstagram atau link lainnya"
                               value="{{ old('link_kontak_2', $activity->link_kontak_2) }}">
                        <p class="text-xs text-gray-500 mt-1">
                            <i class="fas fa-info-circle mr-1"></i>Link kontak tambahan (Instagram, WhatsApp personal, dll.)
                        </p>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Upload Banner (Max 2MB)</label>
                        @if($activity->banner)
                        <div class="mb-3">
                            <p class="text-sm text-gray-600 mb-2">Banner saat ini:</p>
                            <img src="{{ asset('fotoaktivitas/'.$activity->banner) }}" 
                                 alt="Current Banner" 
                                 class="max-h-40 rounded-lg shadow-md">
                        </div>
                        @endif
                        <input type="file" 
                               name="banner" 
                               id="banner-image" 
                               class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100" 
                               accept="image/*">
                        <img id="banner-image-preview" class="mt-4 rounded-lg max-h-40 object-cover hidden shadow-md" alt="Preview Banner">
                        <p class="text-xs text-gray-500 mt-2">Kosongkan jika tidak ingin mengubah banner</p>
                    </div>

                    <div class="md:col-span-2">
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                            <p class="text-sm text-yellow-800">
                                <i class="fas fa-info-circle mr-2"></i>
                                <strong>Perhatian:</strong> Jika aktivitas kamu sudah disetujui (approved), setelah edit status akan kembali menjadi pending dan memerlukan verifikasi ulang dari admin.
                            </p>
                        </div>
                    </div>

                    <div class="md:col-span-2 flex items-center gap-4 pt-4">
                        <button type="submit" class="bg-sky-600 hover:bg-sky-700 text-white font-bold px-6 py-3 rounded-xl transition duration-200">
                            <i class="fas fa-save mr-2"></i> Simpan Perubahan
                        </button>
                        <a href="{{ route('user.riwayat-komunitas') }}" class="border border-slate-300 text-slate-600 hover:bg-slate-100 px-6 py-3 rounded-xl transition duration-200">
                            Batal
                        </a>
                    </div>
                </div>
            </form>
        </section>
    </main>

    <script>
        // Image preview
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

        document.getElementById('banner-image').addEventListener('change', e => previewImage(e.target, '#banner-image-preview'));

        // Toggle harga input
        function initializeHargaToggle() {
            const hargaContainer = document.getElementById('harga-container');
            const hargaInput = hargaContainer?.querySelector('input[name="harga"]');
            
            document.querySelectorAll('.biaya-radio').forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.value === 'berbayar') {
                        hargaContainer?.classList.remove('hidden');
                        if (hargaInput) {
                            hargaInput.required = true;
                            hargaInput.setAttribute('required', 'required');
                        }
                    } else {
                        hargaContainer?.classList.add('hidden');
                        if (hargaInput) {
                            hargaInput.required = false;
                            hargaInput.removeAttribute('required');
                            hargaInput.value = '';
                        }
                    }
                });
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            initializeHargaToggle();
        });
    </script>
</body>
</html>

