@extends('pemiliklapangan.Layout.ownervenue')

@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<style>
    .membership-card {
        @apply bg-gradient-to-br from-blue-600 to-blue-800 text-white p-6 rounded-xl shadow-2xl;
    }
    .membership-feature {
        @apply flex items-start text-sm mb-2;
    }
</style>
@endpush

@section('content')

<main class="pt-20 min-h-screen pb-8 px-4 md:px-8 lg:px-10 bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="max-w-4xl mx-auto">

        <h2 class="text-3xl md:text-4xl font-bold text-slate-800">Buat Paket Membership</h2>
        <p class="text-lg text-slate-600 mt-2 border-b pb-4">Sebagai Pengelola Kesehatan, buat paket membership untuk layanan kesehatan Anda.</p>

        <section class="mt-8">
            <div class="bg-white p-8 rounded-2xl shadow-xl glass border border-slate-100">
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

                <form action="{{ route('activities.store.pengelola') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="jenis" value="membership">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Paket Membership <span class="text-red-500">*</span></label>
                            <input name="nama" class="w-full rounded-lg border border-slate-300 p-3 focus:border-sky-500 focus:ring-sky-500" placeholder="Contoh: PREMIUM HEALTH CARE" required value="{{ old('nama') }}">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Kategori Layanan <span class="text-red-500">*</span></label>
                            <select name="kategori" class="w-full rounded-lg border border-slate-300 p-3 focus:border-sky-500 focus:ring-sky-500" required>
                                <option value="">Pilih Kategori</option>
                                <option value="Kesehatan Umum" {{ old('kategori') == 'Kesehatan Umum' ? 'selected' : '' }}>Kesehatan Umum</option>
                                <option value="Kesehatan Gigi" {{ old('kategori') == 'Kesehatan Gigi' ? 'selected' : '' }}>Kesehatan Gigi</option>
                                <option value="Fisioterapi" {{ old('kategori') == 'Fisioterapi' ? 'selected' : '' }}>Fisioterapi</option>
                                <option value="Konsultasi Gizi" {{ old('kategori') == 'Konsultasi Gizi' ? 'selected' : '' }}>Konsultasi Gizi</option>
                                <option value="Lainnya" {{ old('kategori') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Lokasi Klinik <span class="text-red-500">*</span></label>
                            <input name="lokasi" class="w-full rounded-lg border border-slate-300 p-3 focus:border-sky-500 focus:ring-sky-500" placeholder="Jl. Sudirman No. 123, Denpasar" required value="{{ old('lokasi') }}">
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
                            <textarea name="deskripsi" rows="4" class="w-full rounded-lg border border-slate-300 p-3 focus:border-sky-500 focus:ring-sky-500" placeholder="Tuliskan semua keuntungan yang didapat member, cth: konsultasi kesehatan gratis, diskon layanan, akses prioritas" required>{{ old('deskripsi') }}</textarea>
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
                            <a href="{{ route('pengelola.dashboard') }}" class="border border-slate-300 text-slate-600 hover:bg-slate-100 px-6 py-3 rounded-xl transition duration-200">
                                Batal
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </section>

        {{-- Preview Card --}}
        <section class="mt-8">
            <div class="bg-white p-6 rounded-2xl shadow-xl border border-slate-100">
                <h3 class="text-lg font-semibold text-slate-800 mb-4">Preview Kartu Membership</h3>
                <div class="membership-card">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-extrabold flex items-center">
                            <i class="fas fa-heartbeat mr-2 text-pink-300"></i> PREMIUM HEALTH CARE
                        </h3>
                        <span class="text-xs font-semibold px-3 py-1 bg-white bg-opacity-20 rounded-full">Klinik Sehat</span>
                    </div>

                    <h2 class="text-2xl font-bold mt-3">Klinik Kesehatan Terpadu</h2>

                    <div class="mt-4 pt-3 border-t border-white border-opacity-30">
                        <p class="text-xs uppercase font-semibold opacity-80">Masa Aktif</p>
                        <p class="text-base font-bold">5 Okt 2025 - 5 Des 2025</p>
                        <p class="text-yellow-300 text-sm mt-1">Sisa 52 Hari</p>
                    </div>

                    <div class="mt-5 space-y-2">
                        <p class="font-semibold mb-2">Manfaat Keanggotaan:</p>
                        <div class="flex items-start text-sm"><i class="fas fa-check-circle text-green-300 mr-2 mt-0.5"></i> Konsultasi kesehatan gratis 2x/bulan</div>
                        <div class="flex items-start text-sm"><i class="fas fa-check-circle text-green-300 mr-2 mt-0.5"></i> Diskon 30% untuk semua layanan</div>
                        <div class="flex items-start text-sm"><i class="fas fa-check-circle text-green-300 mr-2 mt-0.5"></i> Akses prioritas booking jadwal</div>
                        <div class="flex items-start text-sm"><i class="fas fa-check-circle text-green-300 mr-2 mt-0.5"></i> Free check-up kesehatan rutin</div>
                    </div>
                </div>
            </div>
        </section>

    </div>
</main>

@push('scripts')
<script>
    // Image Preview Helper
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

    document.getElementById('membership-image').addEventListener('change', e => previewImage(e.target, '#membership-image-preview'));
</script>
@endpush

@endsection
