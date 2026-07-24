@extends('pemiliklapangan.Layout.ownervenue')

@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<style>
    .select-card {
        border: 2px solid transparent;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }
    .select-card.selected {
        border-color: #0ea5e9;
        box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.2);
        position: relative;
    }
    .select-card.selected::after {
        content: "\f058";
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        position: absolute;
        top: 1rem;
        right: 1rem;
        color: #16a34a;
        font-size: 1.25rem;
    }
    .select-card:not(.selected):hover {
        border-color: #bae6fd;
    }
</style>
@endpush

@section('content')

<main class="pt-20 min-h-screen pb-8 px-4 md:px-8 lg:px-10 bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="max-w-4xl mx-auto">

        <h2 class="text-3xl md:text-4xl font-bold text-slate-800">Buat Komunitas Baru</h2>
        <p class="text-lg text-slate-600 mt-2 border-b pb-4">Sebagai Pengelola Kesehatan, buat komunitas olahraga untuk mempromosikan gaya hidup sehat.</p>

        <section class="mt-8">
            <div class="bg-white p-8 rounded-2xl shadow-xl glass border border-slate-100">
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

                <form action="{{ route('activities.store.pengelola') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="jenis" value="komunitas">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Komunitas <span class="text-red-500">*</span></label>
                            <input name="nama" class="w-full rounded-lg border border-slate-300 p-3 focus:border-sky-500 focus:ring-sky-500" placeholder="Contoh: Komunitas Lari Pagi Sehat" required value="{{ old('nama') }}">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Kategori Olahraga <span class="text-red-500">*</span></label>
                            <select name="kategori" class="w-full rounded-lg border border-slate-300 p-3 focus:border-sky-500 focus:ring-sky-500" required>
                                <option value="">Pilih Kategori</option>
                                <option value="Lari" {{ old('kategori') == 'Lari' ? 'selected' : '' }}>Lari</option>
                                <option value="Yoga" {{ old('kategori') == 'Yoga' ? 'selected' : '' }}>Yoga</option>
                                <option value="Pilates" {{ old('kategori') == 'Pilates' ? 'selected' : '' }}>Pilates</option>
                                <option value="Renang" {{ old('kategori') == 'Renang' ? 'selected' : '' }}>Renang</option>
                                <option value="Sepeda" {{ old('kategori') == 'Sepeda' ? 'selected' : '' }}>Sepeda</option>
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
                                    <input type="radio" name="biaya" value="gratis" class="form-radio text-sky-600" {{ old('biaya', 'gratis') == 'gratis' ? 'checked' : '' }}>
                                    <span class="text-sm text-slate-600">Gratis</span>
                                </label>
                                <label class="flex items-center gap-2">
                                    <input type="radio" name="biaya" value="berbayar" class="form-radio text-sky-600" {{ old('biaya') == 'berbayar' ? 'checked' : '' }}>
                                    <span class="text-sm text-slate-600">Berbayar (misal: iuran kesehatan)</span>
                                </label>
                            </div>
                        </div>
                        <div id="harga-komunitas-container" class="{{ old('biaya') == 'berbayar' ? '' : 'hidden' }}">
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Harga (Rp) <span class="text-red-500">*</span></label>
                            <input type="number" name="harga" class="w-full rounded-lg border border-slate-300 p-3 focus:border-sky-500 focus:ring-sky-500" placeholder="Contoh: 50000" min="0" value="{{ old('harga') }}" {{ old('biaya') == 'berbayar' ? 'required' : '' }}>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Deskripsi Lengkap <span class="text-red-500">*</span></label>
                            <textarea name="deskripsi" rows="4" class="w-full rounded-lg border border-slate-300 p-3 focus:border-sky-500 focus:ring-sky-500" placeholder="Tulis ringkasan komunitas, jadwal, manfaat kesehatan, dan informasi penting lainnya" required>{{ old('deskripsi') }}</textarea>
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
                            <a href="{{ route('pengelola.dashboard') }}" class="border border-slate-300 text-slate-600 hover:bg-slate-100 px-6 py-3 rounded-xl transition duration-200">
                                Batal
                            </a>
                        </div>
                    </div>
                </form>
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

    document.getElementById('komunitas-image').addEventListener('change', e => previewImage(e.target, '#komunitas-image-preview'));

    // Toggle Harga Input Based on Biaya Selection
    function initializeHargaToggle() {
        const hargaKomunitasContainer = document.getElementById('harga-komunitas-container');
        const hargaKomunitasInput = hargaKomunitasContainer?.querySelector('input[name="harga"]');

        // Add listeners to all radio buttons
        document.querySelectorAll('input[name="biaya"]').forEach(radio => {
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
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        initializeHargaToggle();
    });
</script>
@endpush

@endsection
