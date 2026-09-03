@extends('pemiliklapangan.Layout.ownervenue')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 font-weight-bold text-dark"><i class="fas fa-id-card text-warning mr-2"></i>Kelola Paket Membership</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/pemiliklapangan/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item active">Buat Membership</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">

            {{-- Info Banner Alur Membership --}}
            <div class="alert alert-warning border-warning mb-4 shadow-sm" style="background-color: #fff9e6; border-left: 5px solid #ffc107;">
                <div class="d-flex align-items-center">
                    <div class="mr-3">
                        <i class="fas fa-info-circle text-warning fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="font-weight-bold text-dark mb-1">
                            Alur Terbit & Verifikasi Paket Membership Venue
                        </h6>
                        <p class="mb-0 small text-secondary">
                            1. Isi form pembuatan paket di bawah ini & hubungkan dengan <strong>Venue Lapangan</strong> Anda.<br>
                            2. Paket baru akan berstatus <strong><span class="badge badge-warning text-dark">Pending</span> (Menunggu Verifikasi Admin)</strong>.<br>
                            3. Setelah <strong>Admin menyetujui (Approve)</strong>, paket akan aktif & tayang di halaman platform sehingga user dapat membeli paket membership Anda.
                        </p>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fas fa-exclamation-triangle mr-1"></i> <strong>Terjadi kesalahan:</strong>
                    <ul class="mb-0 mt-1 pl-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="row">
                {{-- Form Buat Paket --}}
                <div class="col-lg-7">
                    <div class="card card-warning card-outline shadow-sm">
                        <div class="card-header bg-white border-bottom">
                            <h3 class="card-title font-weight-bold text-dark">
                                <i class="fas fa-plus-circle text-warning mr-1"></i> Form Buat Paket Membership Baru
                            </h3>
                        </div>
                        <form action="{{ route('activities.store.pemilik') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="jenis" value="membership">
                            <input type="hidden" name="tipe_aktivitas" value="membership">
                            <input type="hidden" name="biaya_bergabung" value="berbayar">

                            <div class="card-body">
                                {{-- Pilih Venue Lapangan --}}
                                <div class="form-group mb-3">
                                    <label for="pendaftaran_id" class="font-weight-semibold text-dark">Pilih Venue / Lapangan Anda <span class="text-danger">*</span></label>
                                    <select class="form-control" id="pendaftaran_id" name="pendaftaran_id" required>
                                        <option value="">-- Pilih Venue Lapangan --</option>
                                        @if(isset($venues) && $venues->count() > 0)
                                            @foreach($venues as $v)
                                                <option value="{{ $v->id }}" data-lokasi="{{ $v->alamat_venue ?? $v->kota ?? 'Denpasar' }}" {{ old('pendaftaran_id') == $v->id ? 'selected' : '' }}>
                                                    {{ $v->nama_venue }} ({{ $v->kota ?? 'Venue' }})
                                                </option>
                                            @endforeach
                                        @else
                                            <option value="" disabled>Belum ada venue terdaftar. Silakan daftarkan venue terlebih dahulu.</option>
                                        @endif
                                    </select>
                                    <small class="form-text text-muted">Paket membership akan diterbitkan khusus untuk venue yang dipilih.</small>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="nama" class="font-weight-semibold text-dark">Nama Paket Membership <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="nama" name="nama" placeholder="Contoh: GOLD VIP PASS Bulanan" required value="{{ old('nama') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="kategori" class="font-weight-semibold text-dark">Kategori Olahraga <span class="text-danger">*</span></label>
                                            <select class="form-control" id="kategori" name="kategori" required>
                                                <option value="Futsal" {{ old('kategori') == 'Futsal' ? 'selected' : '' }}>Futsal</option>
                                                <option value="Badminton" {{ old('kategori') == 'Badminton' ? 'selected' : '' }}>Badminton</option>
                                                <option value="Basket" {{ old('kategori') == 'Basket' ? 'selected' : '' }}>Basket</option>
                                                <option value="Gym & Fitness" {{ old('kategori') == 'Gym & Fitness' ? 'selected' : '' }}>Gym & Fitness</option>
                                                <option value="Multi-Sport VIP" {{ old('kategori') == 'Multi-Sport VIP' ? 'selected' : '' }}>Multi-Sport VIP</option>
                                                <option value="Membership" {{ old('kategori') == 'Membership' ? 'selected' : '' }}>Umum / All Sports</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="harga" class="font-weight-semibold text-dark">Harga Paket Membership (Rp) <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" id="harga" name="harga" placeholder="250000" required value="{{ old('harga') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="membership_discount_percent" class="font-weight-semibold text-dark">Persentase Diskon Sewa Member (%) <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="number" step="0.1" min="1" max="100" class="form-control font-weight-bold text-success" id="membership_discount_percent" name="membership_discount_percent" placeholder="Contoh: 15" required value="{{ old('membership_discount_percent', 10) }}">
                                                <div class="input-group-append">
                                                    <span class="input-group-text font-weight-bold">%</span>
                                                </div>
                                            </div>
                                            <small class="form-text text-muted">Tentukan persentase potongan sewa otomatis untuk VIP Member di venue ini.</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="fasilitas" class="font-weight-semibold text-dark">Fasilitas & Keuntungan Member <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="fasilitas" name="deskripsi" rows="3" placeholder="Tuliskan keuntungan member, misal: Diskon booking 15%, prioritas slot main malam, free jersey/sparing" required>{{ old('deskripsi') }}</textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="link_kontak" class="font-weight-semibold text-dark">
                                                <i class="fab fa-whatsapp text-success mr-1"></i>Link Grup WA Member
                                            </label>
                                            <input type="text" class="form-control" id="link_kontak" name="link_kontak" placeholder="https://chat.whatsapp.com/..." value="{{ old('link_kontak') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="link_kontak_2" class="font-weight-semibold text-dark">
                                                <i class="fas fa-phone-alt text-info mr-1"></i>Kontak PIC / Instagram
                                            </label>
                                            <input type="text" class="form-control" id="link_kontak_2" name="link_kontak_2" placeholder="https://wa.me/... atau @instagram" value="{{ old('link_kontak_2') }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="banner" class="font-weight-semibold text-dark">Upload Banner / Kartu Membership (Opsional)</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="banner" name="banner" accept="image/*">
                                        <label class="custom-file-label" for="banner">Pilih foto (JPG, PNG max 2MB)</label>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer bg-light text-right">
                                <a href="/pemiliklapangan/dashboard" class="btn btn-secondary mr-2">
                                    <i class="fas fa-times mr-1"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-warning font-weight-bold px-4">
                                    <i class="fas fa-paper-plane mr-1"></i> Ajukan Paket Membership
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Live Preview Card --}}
                <div class="col-lg-5">
                    <div class="card card-outline card-secondary shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h3 class="card-title font-weight-bold text-dark">
                                <i class="fas fa-eye text-primary mr-1"></i> Preview Tampilan Kartu Membership
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="p-4 rounded-xl shadow-lg text-white" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); border: 1px solid rgba(245, 158, 11, 0.4); border-radius: 16px;">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="badge badge-warning text-dark font-weight-bold px-3 py-1">
                                        <i class="fas fa-crown mr-1"></i> MEMBER VIP
                                    </span>
                                    <span class="small text-amber-300 font-weight-bold" id="preview-kategori">Futsal</span>
                                </div>
                                <h4 class="font-weight-bold text-warning mb-1" id="preview-title">GOLD VIP PASS Bulanan</h4>
                                <p class="small text-light mb-3" id="preview-lokasi">
                                    <i class="fas fa-map-marker-alt text-warning mr-1"></i> Pilih Venue di atas
                                </p>
                                <div class="p-2 rounded mb-3" style="background: rgba(255,255,255,0.08); border: 1px border-dashed rgba(255,255,255,0.2);">
                                    <small class="text-amber-200 font-weight-bold d-block mb-1">Manfaat Keanggotaan:</small>
                                    <p class="small mb-0 text-white" id="preview-fasilitas" style="line-height: 1.4;">
                                        Diskon booking 15%, prioritas slot main malam, free jersey/sparing
                                    </p>
                                </div>
                                <div class="d-flex justify-content-between align-items-end pt-2 border-top border-secondary">
                                    <div>
                                        <small class="text-muted d-block">Harga Berlangganan</small>
                                        <h4 class="font-weight-bold text-warning mb-0" id="preview-harga">Rp 250.000</h4>
                                    </div>
                                    <span class="btn btn-warning btn-sm font-weight-bold disabled">
                                        Beli Membership
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Table Paket Membership Saya --}}
            <div class="card card-outline card-warning shadow-sm mt-3">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h3 class="card-title font-weight-bold text-dark">
                        <i class="fas fa-list text-warning mr-1"></i> Daftar Paket Membership Saya & Status Verifikasi Admin
                    </h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Paket</th>
                                    <th>Venue Terkait</th>
                                    <th>Harga</th>
                                    <th>Fasilitas</th>
                                    <th>Status Verifikasi Admin</th>
                                    <th>Tanggal Dibuat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($myMemberships ?? [] as $index => $m)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <strong class="text-dark">{{ $m->nama }}</strong>
                                        <br>
                                        <span class="badge badge-secondary">{{ $m->kategori }}</span>
                                    </td>
                                    <td>
                                        <span class="font-weight-semibold text-primary">
                                            <i class="fas fa-building mr-1"></i> {{ $m->pendaftaran->nama_venue ?? $m->lokasi ?? 'Semua Venue' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="font-weight-bold text-success">
                                            Rp {{ number_format($m->harga ?? 0, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted" style="max-width: 200px; display: inline-block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                            {{ $m->deskripsi }}
                                        </small>
                                    </td>
                                    <td>
                                        @if($m->status === 'approved')
                                            <span class="badge badge-success px-3 py-1">
                                                <i class="fas fa-check-circle mr-1"></i> Disetujui (Tayang)
                                            </span>
                                        @elseif($m->status === 'pending')
                                            <span class="badge badge-warning text-dark px-3 py-1">
                                                <i class="fas fa-clock mr-1"></i> Pending Verifikasi Admin
                                            </span>
                                        @else
                                            <span class="badge badge-danger px-3 py-1">
                                                <i class="fas fa-times-circle mr-1"></i> Ditolak
                                            </span>
                                            @if($m->alasan_reject)
                                                <br><small class="text-danger">Ket: {{ $m->alasan_reject }}</small>
                                            @endif
                                        @endif
                                    </td>
                                    <td>{{ $m->created_at->format('d M Y, H:i') }}</td>
                                    <td>
                                        <form action="{{ route('pemilik.membership.delete', $m->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus paket membership ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Paket">
                                                <i class="fas fa-trash-alt"></i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">
                                        <i class="fas fa-id-card fa-3x text-gray-300 mb-3"></i>
                                        <p class="mb-0 font-weight-bold">Belum Ada Paket Membership Dibuat</p>
                                        <small>Gunakan form di atas untuk menerbitkan paket membership pertama venue Anda.</small>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const pendaftaranSelect = document.getElementById('pendaftaran_id');
    const namaInput = document.getElementById('nama');
    const lokasiInput = document.getElementById('lokasi');
    const hargaInput = document.getElementById('harga');
    const kategoriSelect = document.getElementById('kategori');
    const fasilitasTextarea = document.getElementById('fasilitas');

    function updatePreview() {
        const selectedVenueOption = pendaftaranSelect.options[pendaftaranSelect.selectedIndex];
        const venueText = selectedVenueOption && selectedVenueOption.value ? selectedVenueOption.text : 'Pilih Venue di atas';
        
        if (selectedVenueOption && selectedVenueOption.dataset.lokasi) {
            lokasiInput.value = selectedVenueOption.dataset.lokasi;
        }

        const nama = namaInput.value || 'GOLD VIP PASS Bulanan';
        const lokasi = lokasiInput.value || venueText;
        const harga = hargaInput.value ? Number(hargaInput.value).toLocaleString('id-ID') : '250.000';
        const kategori = kategoriSelect.value || 'Futsal';
        const fasilitas = fasilitasTextarea.value || 'Diskon booking 15%, prioritas slot main malam, free jersey/sparing';

        document.getElementById('preview-title').textContent = nama;
        document.getElementById('preview-lokasi').innerHTML = '<i class="fas fa-map-marker-alt text-warning mr-1"></i> ' + lokasi;
        document.getElementById('preview-harga').textContent = `Rp ${harga}`;
        document.getElementById('preview-kategori').textContent = kategori;
        document.getElementById('preview-fasilitas').textContent = fasilitas;
    }

    [pendaftaranSelect, namaInput, lokasiInput, hargaInput, kategoriSelect, fasilitasTextarea].forEach(element => {
        if (element) {
            element.addEventListener('input', updatePreview);
            element.addEventListener('change', updatePreview);
        }
    });

    updatePreview();
});
</script>
@endsection
