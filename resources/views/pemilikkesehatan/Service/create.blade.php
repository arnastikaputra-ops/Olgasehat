@extends('pemilikkesehatan.Layout.pengelolakesehatan')

@section('content')
<div class="content-wrapper" style="background: #f4f8ff; min-height: 100vh;">
    <div class="content-header border-0 pb-0">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h1 class="page-title mb-1" style="font-weight: 700; color: #1b2b5a;">Tambah Layanan</h1>
                    <p class="text-muted mb-0">Tambah layanan kesehatan baru</p>
                </div>
                <ol class="breadcrumb float-md-right mt-2 mt-md-0">
                    <li class="breadcrumb-item"><a href="{{ route('pengelola.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('pengelola.services.index') }}">Layanan</a></li>
                    <li class="breadcrumb-item active">Tambah</li>
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
                            <h3 class="card-title mb-0" style="font-weight: 700; color: #1b2b5a;">Form Tambah Layanan</h3>
                        </div>
                    <form action="{{ route('pengelola.services.store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nama Layanan <span class="text-danger">*</span></label>
                                        <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required placeholder="Contoh: Konsultasi Dokter Umum">
                                        @error('nama')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Kategori <span class="text-danger">*</span></label>
                                        <input type="text" name="kategori" class="form-control" value="{{ old('kategori') }}" required placeholder="Contoh: Konsultasi, Medical Check-Up">
                                        @error('kategori')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Klinik <span class="text-danger">*</span></label>
                                        <select name="clinic_id" class="form-control" required id="clinic_id">
                                            <option value="">Pilih Klinik</option>
                                            @foreach($clinics as $clinic)
                                                <option value="{{ $clinic->id }}" {{ old('clinic_id') == $clinic->id ? 'selected' : '' }}>
                                                    {{ $clinic->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('clinic_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Dokter (Opsional)</label>
                                        <select name="doctor_id" class="form-control" id="doctor_id">
                                            <option value="">Pilih Dokter (Opsional)</option>
                                            @foreach($doctors as $doctor)
                                                <option value="{{ $doctor->id }}" data-clinic="{{ $doctor->clinic_id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                                    {{ $doctor->nama_lengkap }} - {{ $doctor->clinic->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="text-muted">Kosongkan jika layanan umum tanpa dokter spesifik</small>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Deskripsi</label>
                                <textarea name="deskripsi" class="form-control" rows="3" placeholder="Deskripsi layanan">{{ old('deskripsi') }}</textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Tipe Harga <span class="text-danger">*</span></label>
                                        <select name="tipe_harga" class="form-control" required id="tipe_harga">
                                            <option value="gratis" {{ old('tipe_harga') == 'gratis' ? 'selected' : '' }}>Gratis</option>
                                            <option value="berbayar" {{ old('tipe_harga') == 'berbayar' ? 'selected' : '' }}>Berbayar</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group" id="harga_group">
                                        <label>Harga <span class="text-danger" id="harga_required">*</span></label>
                                        <input type="number" name="harga" class="form-control" value="{{ old('harga') }}" min="0" id="harga_input">
                                        <small class="text-muted">Dalam Rupiah</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Durasi (menit)</label>
                                        <input type="number" name="durasi" class="form-control" value="{{ old('durasi', 30) }}" min="15" max="480">
                                        <small class="text-muted">Default: 30 menit</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer" style="background: white; border-radius: 0 0 20px 20px;">
                            <div class="d-flex justify-content-end">
                                <a href="{{ route('pengelola.services.index') }}" class="btn btn-light mr-3" style="border-radius: 10px;">
                                    <i class="fas fa-times"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-primary" style="background: #28a745; border-color: #28a745; border-radius: 10px;">
                                    <i class="fas fa-save"></i> Simpan
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
// Show/hide harga field based on tipe_harga
document.getElementById('tipe_harga').addEventListener('change', function() {
    const hargaGroup = document.getElementById('harga_group');
    const hargaInput = document.getElementById('harga_input');
    const hargaRequired = document.getElementById('harga_required');
    
    if (this.value === 'berbayar') {
        hargaGroup.style.display = 'block';
        hargaInput.required = true;
        hargaRequired.style.display = 'inline';
    } else {
        hargaGroup.style.display = 'block';
        hargaInput.required = false;
        hargaInput.value = '';
        hargaRequired.style.display = 'none';
    }
});

// Filter doctors based on selected clinic
document.getElementById('clinic_id').addEventListener('change', function() {
    const clinicId = this.value;
    const doctorSelect = document.getElementById('doctor_id');
    const options = doctorSelect.querySelectorAll('option');
    
    options.forEach(option => {
        if (option.value === '') {
            option.style.display = 'block';
        } else {
            const doctorClinicId = option.getAttribute('data-clinic');
            if (clinicId && doctorClinicId !== clinicId) {
                option.style.display = 'none';
            } else {
                option.style.display = 'block';
            }
        }
    });
    
    // Reset doctor selection if clinic changed
    if (clinicId) {
        doctorSelect.value = '';
    }
});
</script>
@endsection

