@extends('pemiliklapangan.Layout.ownervenue')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Buat Event Olahraga</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/pemiliklapangan/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item active">Buat Event</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-danger">
                        <div class="card-header">
                            <h3 class="card-title">Detail Event Olahraga</h3>
                        </div>
                        <form action="{{ route('activities.store.pemilik.event') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                           <input type="hidden" name="tipe_aktivitas" value="event">
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
                                            <label for="nama">Nama Event <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="nama" name="nama" placeholder="Contoh: Turnamen Futsal Bali 2025" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="jenis">Jenis Olahraga <span class="text-danger">*</span></label>
                                            <select class="form-control" id="jenis" name="kategori" required>
                                                <option value="">Pilih Jenis Olahraga</option>
                                                <option>Futsal</option>
                                                <option>Basket</option>
                                                <option>Run / Fun Run</option>
                                                <option>Gym Challenge</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="waktu">Tanggal & Waktu <span class="text-danger">*</span></label>
                                            <input type="datetime-local" class="form-control" id="waktu" name="waktu" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="lokasi">Lokasi Event <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="lokasi" name="lokasi" placeholder="Lapangan GOR Ngurah Rai" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="kapasitas">Kapasitas Peserta</label>
                                            <input type="number" class="form-control" id="kapasitas" name="kapasitas" placeholder="100">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Biaya Bergabung</label>
                                            <div class="icheck-danger d-inline">
                                                <input type="radio" id="gratis_event" name="biaya_bergabung" value="gratis" {{ old('biaya_bergabung', 'gratis') == 'gratis' ? 'checked' : '' }}>
                                                <label for="gratis_event">Gratis</label>
                                            </div>
                                            <div class="icheck-danger d-inline ml-3">
                                                <input type="radio" id="berbayar_event" name="biaya_bergabung" value="berbayar" {{ old('biaya_bergabung') == 'berbayar' ? 'checked' : '' }}>
                                                <label for="berbayar_event">Berbayar</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="harga-event-container" style="display: {{ old('biaya_bergabung') == 'berbayar' ? 'block' : 'none' }};">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="harga">Harga (Rp) <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rp</span>
                                                </div>
                                                <input type="number" class="form-control" id="harga" name="harga" placeholder="Contoh: 100000" min="0" value="{{ old('harga') }}" {{ old('biaya_bergabung') == 'berbayar' ? 'required' : '' }}>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="deskripsi">Deskripsi & Aturan Lengkap <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4" placeholder="Detail event, hadiah, aturan pendaftaran" required></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="link_kontak">
                                        <i class="fas fa-link text-danger mr-1"></i>Link Grup WhatsApp (Untuk Bergabung)
                                    </label>
                                    <input type="text" class="form-control" id="link_kontak" name="link_kontak" placeholder="https://chat.whatsapp.com/..." value="{{ old('link_kontak') }}">
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle mr-1"></i>Link ini akan digunakan untuk kontak tambahan
                                    </small>
                                </div>
                                <div class="form-group">
                                    <label for="banner">Upload Poster Event (Max 2MB)</label>
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
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-save"></i> Simpan Event
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
        const gratisRadio = document.getElementById('gratis_event');
        const berbayarRadio = document.getElementById('berbayar_event');
        const hargaContainer = document.getElementById('harga-event-container');
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

        if (gratisRadio) {
            gratisRadio.addEventListener('change', toggleHargaInput);
        }
        if (berbayarRadio) {
            berbayarRadio.addEventListener('change', toggleHargaInput);
        }

        toggleHargaInput();
    });
</script>
@endsection
