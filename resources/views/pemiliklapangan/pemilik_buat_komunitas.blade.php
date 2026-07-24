@extends('pemiliklapangan.Layout.ownervenue')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Buat Komunitas Baru</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/pemiliklapangan/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item active">Buat Komunitas</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Detail Komunitas Baru</h3>
                        </div>
                        <form action="{{ route('activities.store.pemilik') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                @if(session('success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        {{ session('success') }}
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endif

                                @if($errors->any())
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <ul class="mb-0">
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
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nama">Nama Komunitas <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="nama" name="nama" placeholder="Contoh: Kumpulan Pemuda Futsal" required value="{{ old('nama') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="kategori">Kategori Olahraga <span class="text-danger">*</span></label>
                                            <select class="form-control" id="kategori" name="kategori" required>
                                                <option value="">Pilih Kategori</option>
                                                <option value="Futsal" {{ old('kategori') == 'Futsal' ? 'selected' : '' }}>Futsal</option>
                                                <option value="Basket" {{ old('kategori') == 'Basket' ? 'selected' : '' }}>Basket</option>
                                                <option value="Yoga" {{ old('kategori') == 'Yoga' ? 'selected' : '' }}>Yoga</option>
                                                <option value="Gym" {{ old('kategori') == 'Gym' ? 'selected' : '' }}>Gym</option>
                                                <option value="Lainnya" {{ old('kategori') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="lokasi">Lokasi Kegiatan</label>
                                            <input type="text" class="form-control" id="lokasi" name="lokasi" placeholder="Kota Denpasar, Bali" value="{{ old('lokasi') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Biaya Bergabung</label>
                                            <div class="icheck-primary d-inline">
                                                <input type="radio" id="gratis" name="biaya_bergabung" value="gratis" {{ old('biaya_bergabung', 'gratis') == 'gratis' ? 'checked' : '' }}>
                                                <label for="gratis">Gratis</label>
                                            </div>
                                            <div class="icheck-primary d-inline ml-3">
                                                <input type="radio" id="berbayar" name="biaya_bergabung" value="berbayar" {{ old('biaya_bergabung') == 'berbayar' ? 'checked' : '' }}>
                                                <label for="berbayar">Berbayar (misal: iuran)</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="harga-container" style="display: {{ old('biaya_bergabung') == 'berbayar' ? 'block' : 'none' }};">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="harga">Harga (Rp) <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rp</span>
                                                </div>
                                                <input type="number" class="form-control" id="harga" name="harga" placeholder="Contoh: 50000" min="0" value="{{ old('harga') }}" {{ old('biaya_bergabung') == 'berbayar' ? 'required' : '' }}>
                                            </div>
                                            <small class="form-text text-muted">Masukkan harga untuk bergabung komunitas</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="deskripsi">Deskripsi Lengkap <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4" placeholder="Tulis ringkasan komunitas, jadwal, dan manfaatnya" required>{{ old('deskripsi') }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="link">
                                        <i class="fas fa-link text-primary mr-1"></i>Link Grup WhatsApp (Untuk Bergabung)
                                    </label>
                                    <input type="text" class="form-control" id="link" name="link_kontak" placeholder="https://chat.whatsapp.com/..." value="{{ old('link_kontak') }}">
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle mr-1"></i>Link ini akan digunakan untuk tombol "Bergabung"
                                    </small>
                                </div>
                                <div class="form-group">
                                    <label for="link_kontak_2">
                                        <i class="fas fa-phone-alt text-primary mr-1"></i>Kontak & Informasi Lainnya (Opsional)
                                    </label>
                                    <input type="text" class="form-control" id="link_kontak_2" name="link_kontak_2" placeholder="https://wa.me/.. atau @akuninstagram atau link lainnya" value="{{ old('link_kontak_2') }}">
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle mr-1"></i>Link kontak tambahan (Instagram, WhatsApp personal, dll.)
                                    </small>
                                </div>
                                <div class="form-group">
                                    <label for="banner">Upload Banner Komunitas (Max 2MB)</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="banner" name="banner" accept="image/*">
                                            <label class="custom-file-label" for="banner">Pilih file</label>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted">Format: JPG, PNG, GIF. Maksimal 2MB</small>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Simpan Komunitas
                                </button>
                                <a href="/pemiliklapangan/dashboard" class="btn btn-secondary ml-2">
                                    <i class="fas fa-times"></i> Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const gratisRadio = document.getElementById('gratis');
        const berbayarRadio = document.getElementById('berbayar');
        const hargaContainer = document.getElementById('harga-container');
        const hargaInput = document.getElementById('harga');

        function toggleHargaInput() {
            if (berbayarRadio.checked) {
                hargaContainer.style.display = 'block';
                hargaInput.setAttribute('required', 'required');
            } else {
                hargaContainer.style.display = 'none';
                hargaInput.removeAttribute('required');
                hargaInput.value = '';
            }
        }

        // Event listeners
        if (gratisRadio) {
            gratisRadio.addEventListener('change', toggleHargaInput);
        }
        if (berbayarRadio) {
            berbayarRadio.addEventListener('change', toggleHargaInput);
        }

        // Initialize on page load
        toggleHargaInput();
    });
</script>
@endsection
