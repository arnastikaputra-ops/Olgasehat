@extends('pemiliklapangan.Layout.ownervenue')

@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<style>
    .membership-card {
        @apply bg-gradient-to-br from-blue-600 to-blue-800 text-white p-6 rounded-xl shadow-2xl;
    }
</style>
@endpush

@section('content')

<main class="pt-20 min-h-screen pb-8 px-4 md:px-8 lg:px-10 bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="max-w-6xl mx-auto">

        <h2 class="text-3xl md:text-4xl font-bold text-slate-800 flex items-center">
            <i class="fas fa-heartbeat text-pink-500 mr-3"></i>Kelola Membership Klinik & Kesehatan
        </h2>
        <p class="text-base text-slate-600 mt-1 border-b pb-4">Sebagai Pengelola Kesehatan, buat dan kelola paket membership layanan kesehatan Anda.</p>

        {{-- Info Banner Alur Membership Klinik --}}
        <div class="mt-6 p-4 bg-amber-50 border-l-4 border-amber-400 rounded-xl shadow-sm flex items-start space-x-3">
            <i class="fas fa-info-circle text-amber-500 text-xl mt-0.5"></i>
            <div class="text-sm text-slate-700">
                <strong class="text-slate-900 font-bold block mb-1">Alur Terbit Membership Klinik:</strong>
                1. Pilih klinik kesehatan Anda & isi detail paket di form berikut.<br>
                2. Status awal paket adalah <span class="bg-amber-200 text-amber-900 font-bold text-xs px-2 py-0.5 rounded">Pending Admin</span>.<br>
                3. Setelah diverifikasi oleh Admin, paket membership akan aktif dan pengguna dapat berlangganan ke klinik Anda.
            </div>
        </div>

        @if(session('success'))
            <div class="mt-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-xl shadow-sm flex items-center">
                <i class="fas fa-check-circle mr-2 text-lg"></i> {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mt-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-xl shadow-sm">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mt-8">
            {{-- Form Section (2 cols) --}}
            <div class="lg:col-span-2 bg-white p-8 rounded-2xl shadow-xl border border-slate-100">
                <h3 class="text-xl font-bold text-sky-800 border-b pb-3 mb-6 flex items-center">
                    <i class="fas fa-plus-circle text-sky-600 mr-2"></i> Form Buat Paket Membership Klinik Baru
                </h3>

                <form action="{{ route('activities.store.pengelola') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="jenis" value="membership">

                    <div class="space-y-5">
                        {{-- Dropdown Clinic --}}
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Pilih Klinik / Tempat Sehat <span class="text-red-500">*</span></label>
                            <select name="clinic_id" id="clinic_id" class="w-full rounded-xl border border-slate-300 p-3 focus:border-sky-500 focus:ring-sky-500 text-sm font-semibold" required>
                                <option value="">-- Pilih Klinik Anda --</option>
                                @if(isset($clinics) && $clinics->count() > 0)
                                    @foreach($clinics as $c)
                                        <option value="{{ $c->id }}" data-alamat="{{ $c->alamat ?? $c->kota }}" {{ old('clinic_id') == $c->id ? 'selected' : '' }}>
                                            {{ $c->nama }} ({{ $c->kota ?? 'Klinik' }})
                                        </option>
                                    @endforeach
                                @else
                                    <option value="" disabled>Belum ada klinik terdaftar. Daftarkan klinik Anda terlebih dahulu.</option>
                                @endif
                            </select>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1">Nama Paket Membership <span class="text-red-500">*</span></label>
                                <input name="nama" id="nama" class="w-full rounded-xl border border-slate-300 p-3 focus:border-sky-500 focus:ring-sky-500 text-sm" placeholder="Contoh: PREMIUM HEALTH CARE PASS" required value="{{ old('nama') }}">
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1">Kategori Layanan <span class="text-red-500">*</span></label>
                                <select name="kategori" id="kategori" class="w-full rounded-xl border border-slate-300 p-3 focus:border-sky-500 focus:ring-sky-500 text-sm" required>
                                    <option value="Kesehatan Umum" {{ old('kategori') == 'Kesehatan Umum' ? 'selected' : '' }}>Kesehatan Umum</option>
                                    <option value="Kesehatan Gigi" {{ old('kategori') == 'Kesehatan Gigi' ? 'selected' : '' }}>Kesehatan Gigi</option>
                                    <option value="Fisioterapi" {{ old('kategori') == 'Fisioterapi' ? 'selected' : '' }}>Fisioterapi</option>
                                    <option value="Konsultasi Gizi" {{ old('kategori') == 'Konsultasi Gizi' ? 'selected' : '' }}>Konsultasi Gizi</option>
                                    <option value="Membership VIP" {{ old('kategori') == 'Membership VIP' ? 'selected' : '' }}>Lainnya / VIP Sehat</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1">Harga Berlangganan (Rp) <span class="text-red-500">*</span></label>
                                <input type="number" name="harga" id="harga" class="w-full rounded-xl border border-slate-300 p-3 focus:border-sky-500 focus:ring-sky-500 text-sm" placeholder="350000" required value="{{ old('harga') }}">
                                <input type="hidden" name="biaya" value="berbayar">
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1">Persentase Diskon Layanan Member (%) <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <input type="number" step="0.1" min="1" max="100" name="membership_discount_percent" class="w-full rounded-xl border border-slate-300 p-3 focus:border-sky-500 focus:ring-sky-500 text-sm font-bold text-emerald-600 pr-8" placeholder="Contoh: 15" required value="{{ old('membership_discount_percent', 10) }}">
                                    <span class="absolute right-3 top-3 font-bold text-slate-400">%</span>
                                </div>
                                <small class="text-[11px] text-slate-500 mt-1 block">Persentase diskon potongan otomatis layanan kesehatan bagi member.</small>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Fasilitas & Keuntungan Member <span class="text-red-500">*</span></label>
                            <textarea name="deskripsi" id="deskripsi" rows="3" class="w-full rounded-xl border border-slate-300 p-3 focus:border-sky-500 focus:ring-sky-500 text-sm" placeholder="Tuliskan keuntungan member, misal: Konsultasi kesehatan gratis 2x/bulan, diskon 20% pemeriksaan, bebas antrean prioritas" required>{{ old('deskripsi') }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1">
                                    <i class="fab fa-whatsapp text-emerald-600 mr-1"></i>Link Grup WA Member
                                </label>
                                <input name="link" class="w-full rounded-xl border border-slate-300 p-3 focus:border-sky-500 focus:ring-sky-500 text-sm" placeholder="https://chat.whatsapp.com/..." value="{{ old('link') }}">
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1">
                                    <i class="fas fa-phone-alt text-sky-600 mr-1"></i>Kontak & Sosmed (Opsional)
                                </label>
                                <input name="link_kontak_2" class="w-full rounded-xl border border-slate-300 p-3 focus:border-sky-500 focus:ring-sky-500 text-sm" placeholder="https://wa.me/.. atau @instagram" value="{{ old('link_kontak_2') }}">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Upload Banner Kartu (Max 2MB)</label>
                            <input type="file" name="banner" id="membership-image" class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100" accept="image/*">
                        </div>

                        <div class="pt-4 flex items-center justify-end space-x-3">
                            <a href="{{ route('pengelola.dashboard') }}" class="border border-slate-300 text-slate-600 hover:bg-slate-100 font-bold px-5 py-2.5 rounded-xl text-sm transition">
                                Batal
                            </a>
                            <button type="submit" class="bg-sky-600 hover:bg-sky-700 text-white font-bold px-6 py-2.5 rounded-xl text-sm shadow-md transition">
                                <i class="fas fa-paper-plane mr-2"></i> Simpan Paket Membership
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Live Preview Section --}}
            <div class="lg:col-span-1">
                <div class="bg-white p-6 rounded-2xl shadow-xl border border-slate-100 sticky top-24">
                    <h3 class="text-base font-bold text-slate-800 mb-4 flex items-center">
                        <i class="fas fa-eye text-sky-600 mr-2"></i> Preview Kartu Membership
                    </h3>
                    <div class="membership-card bg-gradient-to-br from-blue-700 to-indigo-900 text-white p-6 rounded-2xl shadow-lg border border-blue-400/30">
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-[10px] font-black tracking-widest px-2.5 py-1 bg-white/20 rounded-full uppercase text-amber-300">KARTU VIP SEHAT</span>
                            <span class="text-xs font-bold text-sky-200" id="preview-kategori">Kesehatan Umum</span>
                        </div>

                        <h3 class="text-xl font-black text-amber-300 mb-1" id="preview-title">PREMIUM HEALTH CARE PASS</h3>
                        <p class="text-xs text-blue-100 mb-4" id="preview-lokasi">Denpasar, Bali</p>

                        <div class="p-3 bg-white/10 rounded-xl mb-4 backdrop-blur-sm border border-white/10">
                            <p class="text-[11px] font-bold text-amber-300 uppercase tracking-wider mb-1">Manfaat Keanggotaan:</p>
                            <p class="text-xs text-blue-50 leading-relaxed" id="preview-deskripsi">
                                Konsultasi kesehatan gratis 2x/bulan, diskon 20% pemeriksaan, bebas antrean.
                            </p>
                        </div>

                        <div class="flex justify-between items-end pt-2 border-t border-white/20">
                            <div>
                                <p class="text-[10px] uppercase text-blue-200">Biaya Berlangganan</p>
                                <p class="text-lg font-black text-amber-300" id="preview-harga">Rp 350.000</p>
                            </div>
                            <span class="text-xs bg-amber-400 text-slate-950 font-black px-3 py-1.5 rounded-lg shadow">
                                VIP Member
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Table Membership Saya --}}
        <section class="mt-10">
            <div class="bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden">
                <div class="p-6 bg-slate-900 text-white flex justify-between items-center">
                    <div>
                        <h3 class="text-xl font-bold text-white flex items-center">
                            <i class="fas fa-list text-amber-400 mr-2"></i> Paket Membership Klinik Saya
                        </h3>
                        <p class="text-xs text-slate-300 mt-1">Daftar paket membership yang telah Anda buat beserta status verifikasi Admin.</p>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-100 text-slate-700 uppercase text-xs">
                            <tr>
                                <th class="p-4">No</th>
                                <th class="p-4">Nama Paket</th>
                                <th class="p-4">Klinik Terkait</th>
                                <th class="p-4">Harga</th>
                                <th class="p-4">Status Verifikasi Admin</th>
                                <th class="p-4">Tanggal Dibuat</th>
                                <th class="p-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @forelse($myMemberships ?? [] as $index => $m)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="p-4 font-bold text-slate-500">{{ $index + 1 }}</td>
                                <td class="p-4">
                                    <span class="font-bold text-slate-900 block">{{ $m->nama }}</span>
                                    <span class="text-xs text-slate-500">{{ $m->kategori }}</span>
                                </td>
                                <td class="p-4">
                                    <span class="font-semibold text-sky-700">
                                        <i class="fas fa-hospital mr-1"></i> {{ $m->clinic->nama ?? $m->lokasi ?? 'Klinik Utama' }}
                                    </span>
                                </td>
                                <td class="p-4 font-bold text-emerald-600">
                                    Rp {{ number_format($m->harga ?? 0, 0, ',', '.') }}
                                </td>
                                <td class="p-4">
                                    @if($m->status === 'approved')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                            <i class="fas fa-check-circle mr-1"></i> Disetujui (Tayang)
                                        </span>
                                    @elseif($m->status === 'pending')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800">
                                            <i class="fas fa-clock mr-1"></i> Pending Verifikasi Admin
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800">
                                            <i class="fas fa-times-circle mr-1"></i> Ditolak
                                        </span>
                                    @endif
                                </td>
                                <td class="p-4 text-xs text-slate-500">{{ $m->created_at->format('d M Y, H:i') }}</td>
                                <td class="p-4">
                                    <form action="{{ route('pengelola.membership.delete', $m->id) }}" method="POST" onsubmit="return confirm('Hapus paket membership ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 font-semibold text-xs border border-red-200 px-3 py-1 rounded-lg hover:bg-red-50 transition">
                                            <i class="fas fa-trash-alt mr-1"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="p-8 text-center text-slate-500">
                                    <i class="fas fa-heartbeat text-4xl text-slate-300 mb-2 block"></i>
                                    Belum ada paket membership klinik yang dibuat.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

    </div>
</main>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const clinicSelect = document.getElementById('clinic_id');
    const namaInput = document.getElementById('nama');
    const hargaInput = document.getElementById('harga');
    const lokasiInput = document.getElementById('lokasi');
    const kategoriSelect = document.getElementById('kategori');
    const deskripsiTextarea = document.getElementById('deskripsi');

    function updatePreview() {
        const selectedOption = clinicSelect.options[clinicSelect.selectedIndex];
        const clinicText = selectedOption && selectedOption.value ? selectedOption.text : 'Denpasar, Bali';

        if (selectedOption && selectedOption.dataset.alamat) {
            lokasiInput.value = selectedOption.dataset.alamat;
        }

        const nama = namaInput.value || 'PREMIUM HEALTH CARE PASS';
        const harga = hargaInput.value ? Number(hargaInput.value).toLocaleString('id-ID') : '350.000';
        const lokasi = lokasiInput.value || clinicText;
        const kategori = kategoriSelect.value || 'Kesehatan Umum';
        const deskripsi = deskripsiTextarea.value || 'Konsultasi kesehatan gratis 2x/bulan, diskon 20% pemeriksaan, bebas antrean.';

        document.getElementById('preview-title').textContent = nama;
        document.getElementById('preview-harga').textContent = `Rp ${harga}`;
        document.getElementById('preview-lokasi').textContent = lokasi;
        document.getElementById('preview-kategori').textContent = kategori;
        document.getElementById('preview-deskripsi').textContent = deskripsi;
    }

    [clinicSelect, namaInput, hargaInput, lokasiInput, kategoriSelect, deskripsiTextarea].forEach(el => {
        if (el) {
            el.addEventListener('input', updatePreview);
            el.addEventListener('change', updatePreview);
        }
    });

    updatePreview();
});
</script>
@endpush

@endsection
