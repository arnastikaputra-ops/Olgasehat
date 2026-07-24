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
                                <label>Deskripsi</label>
                                <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $clinic->deskripsi) }}</textarea>
                            </div>

                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label>Alamat</label>
                                        <input type="text" name="alamat" class="form-control" value="{{ old('alamat', $clinic->alamat) }}">
                                    </div>
                                </div>
                                <div class="col-12 col-md-3">
                                    <div class="form-group">
                                        <label>Kota</label>
                                        <input type="text" name="kota" class="form-control" value="{{ old('kota', $clinic->kota) }}">
                                    </div>
                                </div>
                                <div class="col-12 col-md-3">
                                    <div class="form-group">
                                        <label>Provinsi</label>
                                        <input type="text" name="provinsi" class="form-control" value="{{ old('provinsi', $clinic->provinsi) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <label>Nomor Telepon</label>
                                        <input type="text" name="nomor_telepon" class="form-control" value="{{ old('nomor_telepon', $clinic->nomor_telepon) }}">
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" name="email" class="form-control" value="{{ old('email', $clinic->email) }}">
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <label>Website</label>
                                        <input type="text" name="website" class="form-control" value="{{ old('website', $clinic->website) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Hari Operasional</label>
                                <div class="row">
                                    @foreach(['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'] as $hari)
                                    <div class="col-6 col-md-3 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="hari_operasional[]" value="{{ $hari }}" 
                                                {{ in_array($hari, old('hari_operasional', $clinic->hari_operasional ?? [])) ? 'checked' : '' }}>
                                            <label class="form-check-label">{{ ucfirst($hari) }}</label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label>Jam Buka</label>
                                        <input type="time" name="jam_buka" class="form-control" value="{{ old('jam_buka', $clinic->jam_buka ? date('H:i', strtotime($clinic->jam_buka)) : '') }}">
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label>Jam Tutup</label>
                                        <input type="time" name="jam_tutup" class="form-control" value="{{ old('jam_tutup', $clinic->jam_tutup ? date('H:i', strtotime($clinic->jam_tutup)) : '') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Banner Klinik</label>
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
});
</script>
@endsection

