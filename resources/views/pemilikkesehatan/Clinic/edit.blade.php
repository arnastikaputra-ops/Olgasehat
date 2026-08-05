@extends('pemilikkesehatan.Layout.pengelolakesehatan')

@section('content')
<div class="content-wrapper" style="background: #f4f8ff; min-height: 100vh;">
    <div class="content-header border-0 pb-0">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h1 class="page-title mb-1" style="font-weight: 700; color: #1b2b5a;">Edit Klinik</h1>
                    <p class="text-muted mb-0">Edit informasi klinik atau fasilitas kesehatan</p>
                </div>
                <ol class="breadcrumb float-md-right mt-2 mt-md-0">
                    <li class="breadcrumb-item"><a href="{{ route('pengelola.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('pengelola.clinics') }}">Klinik</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="content pt-3">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                        <div class="card-header" style="background: white; border-radius: 20px 20px 0 0;">
                            <h3 class="card-title mb-0" style="font-weight: 700; color: #1b2b5a;">Form Edit Klinik</h3>
                        </div>
                    <form action="{{ route('pengelola.clinics.update', $clinic->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label>Nama Klinik <span class="text-danger">*</span></label>
                                        <input type="text" name="nama" class="form-control" value="{{ old('nama', $clinic->nama) }}" required>
                                        @error('nama')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label>Tipe <span class="text-danger">*</span></label>
                                        <select name="tipe" class="form-control" required>
                                            <option value="klinik" {{ old('tipe', $clinic->tipe) == 'klinik' ? 'selected' : '' }}>Klinik</option>
                                            <option value="layanan" {{ old('tipe', $clinic->tipe) == 'layanan' ? 'selected' : '' }}>Layanan Kesehatan</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Jenis Layanan <span class="text-danger">*</span></label>
                                <div id="jenisLayananContainer">
                                    @if(old('jenis_layanan'))
                                        @foreach(old('jenis_layanan') as $index => $layanan)
                                            <div class="input-group mb-2 jenis-layanan-item">
                                                <input type="text" name="jenis_layanan[]" class="form-control" value="{{ $layanan }}" placeholder="Contoh: Konsultasi Dokter Umum" required>
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-danger btn-remove-layanan" style="display: {{ $loop->first && count(old('jenis_layanan')) == 1 ? 'none' : 'block' }};">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    @elseif($clinic->layanan_tersedia && count($clinic->layanan_tersedia) > 0)
                                        @foreach($clinic->layanan_tersedia as $index => $layanan)
                                            <div class="input-group mb-2 jenis-layanan-item">
                                                <input type="text" name="jenis_layanan[]" class="form-control" value="{{ $layanan }}" placeholder="Contoh: Konsultasi Dokter Umum" required>
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-danger btn-remove-layanan" style="display: {{ $loop->first && count($clinic->layanan_tersedia) == 1 ? 'none' : 'block' }};">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="input-group mb-2 jenis-layanan-item">
                                            <input type="text" name="jenis_layanan[]" class="form-control" placeholder="Contoh: Konsultasi Dokter Umum" required>
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-danger btn-remove-layanan" style="display: none;">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="btnTambahLayanan">
                                    <i class="fas fa-plus"></i> Tambah Jenis Layanan
                                </button>
                                <small class="form-text text-muted d-block mt-2">
                                    Masukkan jenis layanan yang tersedia di klinik Anda (contoh: Konsultasi, Medical Check-Up, Fisioterapi, dll)
                                </small>
                                @error('jenis_layanan')
                                    <small class="text-danger d-block">{{ $message }}</small>
                                @enderror
                            </div>

                        <div class="form-group">
                            <label>Fasilitas (Opsional)</label>
                            <div id="fasilitasContainer">
                                @php
                                    $existingFasilitas = old('fasilitas', $clinic->fasilitas ?? []);
                                @endphp
                                @if($existingFasilitas && count($existingFasilitas) > 0)
                                    @foreach($existingFasilitas as $fasilitas)
                                        <div class="input-group mb-2 fasilitas-item">
                                            <input type="text" name="fasilitas[]" class="form-control" value="{{ $fasilitas }}" placeholder="Contoh: Parkir Luas">
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-danger btn-remove-fasilitas" style="display: {{ $loop->first && count($existingFasilitas) == 1 ? 'none' : 'block' }};">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="input-group mb-2 fasilitas-item">
                                        <input type="text" name="fasilitas[]" class="form-control" placeholder="Contoh: Parkir Luas">
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-danger btn-remove-fasilitas" style="display: none;">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-success mt-2" id="btnTambahFasilitas">
                                <i class="fas fa-plus"></i> Tambah Fasilitas
                            </button>
                            <small class="form-text text-muted d-block mt-2">
                                Gunakan fasilitas untuk menonjolkan keunggulan klinik (contoh: Laboratorium internal, Ruang tunggu nyaman, Wifi gratis).
                            </small>
                            @error('fasilitas')
                                <small class="text-danger d-block">{{ $message }}</small>
                            @enderror
                            @error('fasilitas.*')
                                <small class="text-danger d-block">{{ $message }}</small>
                            @enderror
                        </div>

                            <div class="form-group">
                                <label>Motto</label>
                                <input type="text" name="motto" class="form-control" value="{{ old('motto', $clinic->motto) }}">
                            </div>

                            <div class="form-group">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label class="font-weight-bold" style="color: #1b2b5a;">
                                        <i class="fas fa-align-left text-primary mr-1"></i> Deskripsi Klinik
                                    </label>
                                    <button type="button" class="btn btn-xs btn-outline-info rounded-pill px-2 py-1" id="btnIsiContohDeskripsi" style="font-size: 0.8rem;">
                                        <i class="fas fa-magic mr-1"></i> Gunakan Contoh Deskripsi
                                    </button>
                                </div>
                                <div class="p-2.5 mb-2 rounded bg-light border-left border-info" style="border-left: 4px solid #17a2b8 !important; background-color: #f4faff !important;">
                                    <small class="text-secondary d-block">
                                        <i class="fas fa-info-circle text-info mr-1"></i> <strong>Apa itu Deskripsi Klinik?</strong>
                                        Deskripsi ini akan ditampilkan pada halaman profil klinik Anda untuk memberikan informasi singkat kepada pasien mengenai profil klinik, keunggulan layanan, dokter spesialis, serta fasilitas yang tersedia.
                                    </small>
                                </div>
                                <textarea name="deskripsi" id="inputDeskripsi" class="form-control" rows="4" 
                                    placeholder="Contoh: Klinik Medika Sehat adalah fasilitas kesehatan terpercaya yang berkomitmen memberikan pelayanan medis terbaik, cepat, dan ramah untuk keluarga Anda. Kami menyediakan layanan dokter umum & spesialis, laboratorium internal, pemeriksaan kesehatan (MCU), serta ruang tunggu yang bersih dan nyaman. Pendaftaran dapat dilakukan secara langsung maupun online.">{{ old('deskripsi', $clinic->deskripsi) }}</textarea>
                                <small class="form-text text-muted">
                                    💡 <strong>Tips:</strong> Jelaskan profil singkat, keunggulan medis, dokter spesialis, serta kemudahan pendaftaran.
                                </small>
                            </div>

                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold" style="color: #1b2b5a;">
                                            <i class="fas fa-map-marked-alt text-primary mr-1"></i> Alamat Lengkap
                                        </label>
                                        <input type="text" name="alamat" class="form-control" value="{{ old('alamat', $clinic->alamat) }}" placeholder="Contoh: Jl. Gatot Subroto No. 88, Kecamatan Denpasar Barat">
                                    </div>
                                </div>
                                <div class="col-12 col-md-3">
                                    <div class="form-group">
                                        <label class="font-weight-bold" style="color: #1b2b5a;">
                                            <i class="fas fa-city text-primary mr-1"></i> Kota / Kabupaten
                                        </label>
                                        <input type="text" name="kota" id="inputKota" class="form-control" list="kotaList" 
                                               value="{{ old('kota', $clinic->kota) }}" placeholder="Contoh: Denpasar / Jakarta Selatan">
                                        <datalist id="kotaList">
                                            <option value="Denpasar">
                                            <option value="Badung">
                                            <option value="Gianyar">
                                            <option value="Tabanan">
                                            <option value="Buleleng">
                                            <option value="Jakarta Selatan">
                                            <option value="Jakarta Pusat">
                                            <option value="Jakarta Barat">
                                            <option value="Jakarta Timur">
                                            <option value="Jakarta Utara">
                                            <option value="Surabaya">
                                            <option value="Bandung">
                                            <option value="Medan">
                                            <option value="Semarang">
                                            <option value="Makassar">
                                            <option value="Tangerang">
                                            <option value="Tangerang Selatan">
                                            <option value="Bekasi">
                                            <option value="Depok">
                                            <option value="Bogor">
                                            <option value="Yogyakarta">
                                            <option value="Surakarta (Solo)">
                                            <option value="Malang">
                                            <option value="Palembang">
                                            <option value="Balikpapan">
                                        </datalist>
                                        <small class="form-text text-muted">Ketik atau pilih kota/kabupaten.</small>
                                    </div>
                                </div>
                                <div class="col-12 col-md-3">
                                    <div class="form-group">
                                        <label class="font-weight-bold" style="color: #1b2b5a;">
                                            <i class="fas fa-map text-primary mr-1"></i> Provinsi
                                        </label>
                                        <input type="text" name="provinsi" class="form-control" value="{{ old('provinsi', $clinic->provinsi) }}" placeholder="Contoh: Bali / DKI Jakarta">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <label class="font-weight-bold" style="color: #1b2b5a;">
                                            <i class="fas fa-phone text-primary mr-1"></i> Nomor Telepon
                                        </label>
                                        <input type="text" name="nomor_telepon" class="form-control" value="{{ old('nomor_telepon', $clinic->nomor_telepon) }}" placeholder="Contoh: 081234567890">
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <label class="font-weight-bold" style="color: #1b2b5a;">
                                            <i class="fas fa-envelope text-primary mr-1"></i> Email Klinik
                                        </label>
                                        <input type="email" name="email" class="form-control" value="{{ old('email', $clinic->email) }}" placeholder="Contoh: info@klinikmedika.com">
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <label class="font-weight-bold" style="color: #1b2b5a;">
                                            <i class="fas fa-globe text-primary mr-1"></i> Website (Opsional)
                                        </label>
                                        <input type="text" name="website" class="form-control" value="{{ old('website', $clinic->website) }}" placeholder="Contoh: https://klinikmedika.com">
                                    </div>
                                </div>
                            </div>

                            <!-- HARI OPERASIONAL -->
                            <div class="card p-3 border-0 rounded-lg mb-3" style="background: #f8fafc; border: 1.5px solid #e2e8f0 !important;">
                                <div class="d-flex justify-content-between align-items-center flex-wrap mb-2">
                                    <label class="font-weight-bold mb-1" style="color: #1b2b5a; font-size: 1rem;">
                                        <i class="fas fa-calendar-alt text-primary mr-1"></i> Hari Operasional Klinik
                                    </label>
                                    <div class="btn-group btn-group-sm mb-1" role="group" aria-label="Preset Hari">
                                        <button type="button" class="btn btn-primary btn-sm rounded-left" id="btnHariSetiapHari" style="font-weight: 600;">
                                            <i class="fas fa-check-double mr-1"></i> Setiap Hari (Senin - Minggu)
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary btn-sm" id="btnHariKerja">
                                            <i class="fas fa-briefcase mr-1"></i> Senin - Jumat
                                        </button>
                                        <button type="button" class="btn btn-outline-info btn-sm" id="btnHariWeekend">
                                            <i class="fas fa-coffee mr-1"></i> Sabtu - Minggu
                                        </button>
                                        <button type="button" class="btn btn-outline-danger btn-sm rounded-right" id="btnHariReset">
                                            <i class="fas fa-undo mr-1"></i> Reset
                                        </button>
                                    </div>
                                </div>
                                <small class="text-muted d-block mb-3">Klik tombol <strong>Setiap Hari</strong> di atas atau pilih hari operasional klinik secara manual:</small>
                                
                                <div class="row" id="hariOperasionalContainer">
                                    @foreach(['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'] as $hari)
                                    <div class="col-6 col-sm-4 col-md-3 mb-2">
                                        <label class="hari-card d-flex align-items-center p-2 rounded-lg border w-100 mb-0" 
                                               style="cursor: pointer; transition: all 0.2s ease; border: 1.5px solid #cbd5e1; background: white;"
                                               for="hari_{{ $hari }}">
                                            <input class="form-check-input hari-checkbox position-static mt-0 mr-2" type="checkbox" name="hari_operasional[]" value="{{ $hari }}" id="hari_{{ $hari }}"
                                                {{ in_array($hari, old('hari_operasional', $clinic->hari_operasional ?? [])) ? 'checked' : '' }}>
                                            <span class="font-weight-bold text-dark" style="font-size: 0.95rem;">{{ ucfirst($hari) }}</span>
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- JAM BUKA & JAM TUTUP -->
                            <div class="card p-3 border-0 rounded-lg mb-3" style="background: #f8fafc; border: 1.5px solid #e2e8f0 !important;">
                                <div class="d-flex justify-content-between align-items-center flex-wrap mb-2">
                                    <label class="font-weight-bold mb-1" style="color: #1b2b5a; font-size: 1rem;">
                                        <i class="far fa-clock text-primary mr-1"></i> Jam Buka & Jam Tutup Klinik
                                    </label>
                                    @php
                                        $bukaFormatted = $clinic->jam_buka ? date('H:i', strtotime($clinic->jam_buka)) : '';
                                        $tutupFormatted = $clinic->jam_tutup ? date('H:i', strtotime($clinic->jam_tutup)) : '';
                                        $is24Jam = (old('jam_buka', $bukaFormatted) == '00:00') && (old('jam_tutup', $tutupFormatted) == '23:59');
                                    @endphp
                                    <div class="custom-control custom-switch mb-1">
                                        <input type="checkbox" class="custom-control-input" id="switch24Jam" {{ $is24Jam ? 'checked' : '' }}>
                                        <label class="custom-control-label font-weight-bold text-success" for="switch24Jam" style="cursor: pointer;">
                                            <i class="fas fa-bolt text-warning mr-1"></i> Buka 24 Jam Nonstop
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <small class="text-muted d-block mb-1">Pilihan cepat jam operasional:</small>
                                    <button type="button" class="btn btn-sm btn-outline-success mr-1 mb-1 preset-jam-24 font-weight-bold">
                                        <i class="fas fa-bolt mr-1"></i> Buka 24 Jam
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary mr-1 mb-1 preset-jam" data-buka="08:00" data-tutup="17:00">
                                        <i class="far fa-sun mr-1"></i> 08:00 - 17:00 (Standard)
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary mr-1 mb-1 preset-jam" data-buka="08:00" data-tutup="21:00">
                                        <i class="fas fa-moon mr-1"></i> 08:00 - 21:00 (Panjang)
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary mr-1 mb-1 preset-jam" data-buka="07:00" data-tutup="22:00">
                                        <i class="far fa-clock mr-1"></i> 07:00 - 22:00
                                    </button>
                                </div>

                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <div class="form-group mb-0">
                                            <label class="small font-weight-bold text-secondary mb-1">Jam Buka</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-white"><i class="far fa-clock text-primary"></i></span>
                                                </div>
                                                <input type="time" name="jam_buka" id="inputJamBuka" class="form-control" value="{{ old('jam_buka', $bukaFormatted) }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 mt-2 mt-md-0">
                                        <div class="form-group mb-0">
                                            <label class="small font-weight-bold text-secondary mb-1">Jam Tutup</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-white"><i class="far fa-clock text-danger"></i></span>
                                                </div>
                                                <input type="time" name="jam_tutup" id="inputJamTutup" class="form-control" value="{{ old('jam_tutup', $tutupFormatted) }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div id="info24Jam" class="alert alert-success py-2 px-3 mb-0 mt-3 rounded-lg d-none align-items-center" style="border-left: 4px solid #28a745;">
                                    <i class="fas fa-check-circle mr-2 fa-lg"></i>
                                    <div>
                                        <strong>Status: Klinik Beroperasi 24 Jam Nonstop</strong>
                                        <div class="small">Jam Buka diatur otomatis ke 00:00 dan Jam Tutup ke 23:59.</div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold" style="color: #1b2b5a;">Banner Klinik</label>
                                @if($clinic->logo)
                                <div class="mb-2">
                                    <img src="{{ asset('fotoklinik/' . $clinic->logo) }}" alt="Banner" class="img-thumbnail" style="max-height: 150px; width: auto;">
                                </div>
                                @endif
                                <input type="file" name="banner" class="form-control-file" accept="image/*">
                                <small class="text-muted">Kosongkan jika tidak ingin mengubah</small>
                            </div>

                            <div class="form-group">
                                <label>Galeri Klinik</label>
                                @if($clinic->galleries && $clinic->galleries->count() > 0)
                                <div class="mb-3">
                                    <p class="small text-muted mb-2">Galeri yang sudah ada:</p>
                                    <div class="row">
                                        @foreach($clinic->galleries as $gallery)
                                        <div class="col-md-3 col-sm-4 mb-2">
                                            <div class="position-relative">
                                                <img src="{{ strpos($gallery->foto, 'clinic_galleries') !== false ? asset('storage/' . $gallery->foto) : asset('fotoklinik/' . $gallery->foto) }}" 
                                                     alt="Gallery {{ $loop->iteration }}" 
                                                     class="img-thumbnail" style="width: 100%; height: 120px; object-fit: cover;">
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                                <input type="file" name="galeri_foto[]" id="galeri_foto" class="form-control-file" accept="image/*" multiple>
                                <small class="text-muted">Pilih multiple gambar untuk menambah galeri (maksimal 10 gambar total, format: JPG, PNG, maksimal 2MB per gambar)</small>
                                <div id="galeriPreview" class="mt-3 row"></div>
                            </div>
                        </div>
                        <div class="card-footer" style="background: white; border-radius: 0 0 20px 20px;">
                            <div class="d-flex justify-content-end">
                                <a href="{{ route('pengelola.clinics') }}" class="btn btn-light mr-3" style="border-radius: 10px;">
                                    <i class="fas fa-times"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-primary" style="background: #28a745; border-color: #28a745; border-radius: 10px;">
                                    <i class="fas fa-save"></i> Update
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('jenisLayananContainer');
    const btnTambah = document.getElementById('btnTambahLayanan');
    
    // Tambah input baru
    btnTambah.addEventListener('click', function() {
        const newItem = document.createElement('div');
        newItem.className = 'input-group mb-2 jenis-layanan-item';
        newItem.innerHTML = `
            <input type="text" name="jenis_layanan[]" class="form-control" placeholder="Contoh: Medical Check-Up" required>
            <div class="input-group-append">
                <button type="button" class="btn btn-danger btn-remove-layanan">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        container.appendChild(newItem);
        updateRemoveButtons();
    });
    
    // Hapus input
    container.addEventListener('click', function(e) {
        if (e.target.closest('.btn-remove-layanan')) {
            const item = e.target.closest('.jenis-layanan-item');
            if (container.children.length > 1) {
                item.remove();
                updateRemoveButtons();
            }
        }
    });
    
    // Update visibility tombol remove
    function updateRemoveButtons() {
        const items = container.querySelectorAll('.jenis-layanan-item');
        items.forEach((item, index) => {
            const btnRemove = item.querySelector('.btn-remove-layanan');
            if (items.length > 1) {
                btnRemove.style.display = 'block';
            } else {
                btnRemove.style.display = 'none';
            }
        });
    }
    
    // Initialize
    updateRemoveButtons();

    // Preview galeri foto
    const galeriInput = document.getElementById('galeri_foto');
    const galeriPreview = document.getElementById('galeriPreview');
    
    if (galeriInput) {
        galeriInput.addEventListener('change', function(e) {
            galeriPreview.innerHTML = '';
            const files = e.target.files;
            const existingCount = {{ $clinic->galleries ? $clinic->galleries->count() : 0 }};
            
            if (existingCount + files.length > 10) {
                alert('Total galeri tidak boleh lebih dari 10 gambar. Anda sudah memiliki ' + existingCount + ' gambar.');
                galeriInput.value = '';
                return;
            }
            
            for (let i = 0; i < files.length && i < 10; i++) {
                const file = files[i];
                if (file.size > 2 * 1024 * 1024) {
                    alert(`File ${file.name} terlalu besar. Maksimal 2MB per gambar.`);
                    continue;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    const col = document.createElement('div');
                    col.className = 'col-md-3 col-sm-4 mb-2';
                    col.innerHTML = `
                        <div class="position-relative">
                            <img src="${e.target.result}" alt="Preview ${i + 1}" 
                                 class="img-thumbnail" style="width: 100%; height: 120px; object-fit: cover;">
                        </div>
                    `;
                    galeriPreview.appendChild(col);
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Fasilitas dynamic inputs
    const fasilitasContainer = document.getElementById('fasilitasContainer');
    const btnTambahFasilitas = document.getElementById('btnTambahFasilitas');

    function updateRemoveFasilitasButtons() {
        const items = fasilitasContainer.querySelectorAll('.fasilitas-item');
        items.forEach(item => {
            const btnRemove = item.querySelector('.btn-remove-fasilitas');
            btnRemove.style.display = items.length > 1 ? 'block' : 'none';
        });
    }

    btnTambahFasilitas.addEventListener('click', function() {
        const newItem = document.createElement('div');
        newItem.className = 'input-group mb-2 fasilitas-item';
        newItem.innerHTML = `
            <input type="text" name="fasilitas[]" class="form-control" placeholder="Contoh: Parkir Luas">
            <div class="input-group-append">
                <button type="button" class="btn btn-danger btn-remove-fasilitas">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        fasilitasContainer.appendChild(newItem);
        updateRemoveFasilitasButtons();
    });

    fasilitasContainer.addEventListener('click', function(e) {
        if (e.target.closest('.btn-remove-fasilitas')) {
            const item = e.target.closest('.fasilitas-item');
            if (fasilitasContainer.children.length > 1) {
                item.remove();
                updateRemoveFasilitasButtons();
            }
        }
    });

    updateRemoveFasilitasButtons();

    // ==========================================
    // JS HARI OPERASIONAL (AUTO RANGE & MANUAL UNCHECK)
    // ==========================================
    const dayOrder = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'];
    const hariCheckboxes = document.querySelectorAll('.hari-checkbox');

    function updateHariCardStyles() {
        hariCheckboxes.forEach(cb => {
            const label = cb.closest('.hari-card');
            if (!label) return;
            if (cb.checked) {
                label.style.background = '#eef5ff';
                label.style.borderColor = '#2563eb';
                label.style.color = '#1d4ed8';
                label.classList.add('shadow-sm');
            } else {
                label.style.background = '#ffffff';
                label.style.borderColor = '#cbd5e1';
                label.style.color = '#334155';
                label.classList.remove('shadow-sm');
            }
        });
    }

    hariCheckboxes.forEach(cb => {
        cb.addEventListener('click', function() {
            const dayVal = this.value;
            const targetIdx = dayOrder.indexOf(dayVal);
            
            if (this.checked) {
                // Auto-check dari Senin (0) sampai hari yang dipilih (targetIdx)
                for (let i = 0; i <= targetIdx; i++) {
                    const targetDay = dayOrder[i];
                    const targetCb = document.querySelector(`.hari-checkbox[value="${targetDay}"]`);
                    if (targetCb) {
                        targetCb.checked = true;
                    }
                }
            } else {
                // Membatalkan (uncheck) hanya hari yang diklik saja
                this.checked = false;
            }
            updateHariCardStyles();
        });
    });
    updateHariCardStyles();

    document.getElementById('btnHariSetiapHari')?.addEventListener('click', function(e) {
        if (e) e.preventDefault();
        hariCheckboxes.forEach(cb => cb.checked = true);
        updateHariCardStyles();
    });

    document.getElementById('btnHariKerja')?.addEventListener('click', function(e) {
        if (e) e.preventDefault();
        const kerjaDays = ['senin', 'selasa', 'rabu', 'kamis', 'jumat'];
        hariCheckboxes.forEach(cb => {
            cb.checked = kerjaDays.includes(cb.value);
        });
        updateHariCardStyles();
    });

    document.getElementById('btnHariWeekend')?.addEventListener('click', function(e) {
        if (e) e.preventDefault();
        const weekendDays = ['sabtu', 'minggu'];
        hariCheckboxes.forEach(cb => {
            cb.checked = weekendDays.includes(cb.value);
        });
        updateHariCardStyles();
    });

    document.getElementById('btnHariReset')?.addEventListener('click', function(e) {
        if (e) e.preventDefault();
        hariCheckboxes.forEach(cb => cb.checked = false);
        updateHariCardStyles();
    });

    // ==========================================
    // JS JAM BUKA & JAM TUTUP (24 JAM)
    // ==========================================
    const switch24Jam = document.getElementById('switch24Jam');
    const inputJamBuka = document.getElementById('inputJamBuka');
    const inputJamTutup = document.getElementById('inputJamTutup');
    const info24Jam = document.getElementById('info24Jam');

    function sync24JamState() {
        if (!switch24Jam || !inputJamBuka || !inputJamTutup) return;
        
        if (switch24Jam.checked) {
            inputJamBuka.value = '00:00';
            inputJamTutup.value = '23:59';
            inputJamBuka.readOnly = true;
            inputJamTutup.readOnly = true;
            inputJamBuka.style.backgroundColor = '#e2e8f0';
            inputJamTutup.style.backgroundColor = '#e2e8f0';
            if (info24Jam) {
                info24Jam.classList.remove('d-none');
                info24Jam.classList.add('d-flex');
            }
        } else {
            inputJamBuka.readOnly = false;
            inputJamTutup.readOnly = false;
            inputJamBuka.style.backgroundColor = '#ffffff';
            inputJamTutup.style.backgroundColor = '#ffffff';
            if (info24Jam) {
                info24Jam.classList.add('d-none');
                info24Jam.classList.remove('d-flex');
            }
        }
    }

    switch24Jam?.addEventListener('change', sync24JamState);

    // Initial check for 24 jam
    if (inputJamBuka && inputJamTutup) {
        const bukaVal = inputJamBuka.value;
        const tutupVal = inputJamTutup.value;
        if ((bukaVal === '00:00' || bukaVal === '00:00:00') && (tutupVal === '23:59' || tutupVal === '23:59:00')) {
            if (switch24Jam) switch24Jam.checked = true;
        }
        sync24JamState();
    }

    // Preset jam buttons
    document.querySelectorAll('.preset-jam').forEach(btn => {
        btn.addEventListener('click', function(e) {
            if (e) e.preventDefault();
            if (switch24Jam) switch24Jam.checked = false;
            sync24JamState();
            const buka = this.getAttribute('data-buka');
            const tutup = this.getAttribute('data-tutup');
            if (buka && inputJamBuka) inputJamBuka.value = buka;
            if (tutup && inputJamTutup) inputJamTutup.value = tutup;
        });
    });

    document.querySelectorAll('.preset-jam-24').forEach(btn => {
        btn.addEventListener('click', function(e) {
            if (e) e.preventDefault();
            if (switch24Jam) switch24Jam.checked = true;
            sync24JamState();
        });
    });

    // ==========================================
    // JS CONTOH DESKRIPSI
    // ==========================================
    document.getElementById('btnIsiContohDeskripsi')?.addEventListener('click', function() {
        const textarea = document.getElementById('inputDeskripsi');
        if (textarea) {
            textarea.value = "Klinik Medika Sehat adalah fasilitas kesehatan terpercaya yang berkomitmen memberikan pelayanan medis terbaik, cepat, dan ramah untuk keluarga Anda. Kami menyediakan layanan dokter umum & spesialis, laboratorium internal, pemeriksaan kesehatan (MCU), serta ruang tunggu yang bersih dan nyaman. Pendaftaran dapat dilakukan secara langsung maupun online.";
            textarea.focus();
        }
    });
});
</script>
@endsection

