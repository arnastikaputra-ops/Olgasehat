@extends('pemilikkesehatan.Layout.pengelolakesehatan')

@section('content')
<div class="content-wrapper" style="background: #f4f8ff; min-height: 100vh;">
    <div class="content-header border-0 pb-0">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h1 class="page-title mb-1" style="font-weight: 700; color: #1b2b5a;">Tambah Dokter</h1>
                    <p class="text-muted mb-0">Tambah data dokter baru ke klinik Anda</p>
                </div>
                <ol class="breadcrumb float-md-right mt-2 mt-md-0">
                    <li class="breadcrumb-item"><a href="{{ route('pengelola.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('pengelola.doctors.index') }}">Dokter</a></li>
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
                            <h3 class="card-title mb-0" style="font-weight: 700; color: #1b2b5a;">Form Tambah Dokter</h3>
                        </div>
                    <form action="{{ route('pengelola.doctors.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label>Klinik <span class="text-danger">*</span></label>
                                        <select name="clinic_id" class="form-control" required>
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
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label>Nama Dokter <span class="text-danger">*</span></label>
                                        <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required>
                                        @error('nama')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <label>Gelar</label>
                                        <input type="text" name="gelar" class="form-control" value="{{ old('gelar') }}" placeholder="dr., drg., Sp.PD">
                                        <small class="text-muted">Contoh: dr., drg., Sp.PD</small>
                                    </div>
                                </div>
                                <div class="col-12 col-md-8">
                                    <div class="form-group">
                                        <label>Spesialisasi <span class="text-danger">*</span></label>
                                        <input type="text" name="spesialisasi" class="form-control" value="{{ old('spesialisasi') }}" required placeholder="Dokter Umum, Dokter Gigi, dll">
                                        @error('spesialisasi')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label>Nomor STR</label>
                                        <input type="text" name="nomor_str" class="form-control" value="{{ old('nomor_str') }}" placeholder="Surat Tanda Registrasi">
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label>Pendidikan</label>
                                        <input type="text" name="pendidikan" class="form-control" value="{{ old('pendidikan') }}" placeholder="Contoh: S1 Kedokteran">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label class="font-weight-bold" style="color: #1b2b5a;">
                                        <i class="fas fa-user-md text-primary mr-1"></i> Deskripsi / Biografi Dokter
                                    </label>
                                    <button type="button" class="btn btn-xs btn-outline-info rounded-pill px-2 py-1" id="btnIsiContohDeskripsiDokter" style="font-size: 0.8rem;">
                                        <i class="fas fa-magic mr-1"></i> Gunakan Contoh Deskripsi
                                    </button>
                                </div>
                                <div class="p-2.5 mb-2 rounded bg-light border-left border-info" style="border-left: 4px solid #17a2b8 !important; background-color: #f4faff !important;">
                                    <small class="text-secondary d-block">
                                        <i class="fas fa-info-circle text-info mr-1"></i> <strong>Apa fungsi Deskripsi Dokter?</strong>
                                        Deskripsi ini akan ditampilkan pada profil Dokter untuk menjelaskan latar belakang medis, keahlian utama, fokus pelayanan, dan pendekatan konsultasi Dokter kepada pasien.
                                    </small>
                                </div>
                                <textarea name="deskripsi" id="inputDeskripsiDokter" class="form-control" rows="4" 
                                    placeholder="Contoh: dr. Budi Santoso, Sp.PD adalah dokter spesialis penyakit dalam dengan pengalaman lebih dari 8 tahun dalam menangani masalah kesehatan degeneratif, metabolisme, serta konsultasi kesehatan umum. Beliau berdedikasi memberikan konsultasi medis yang ramah, informatif, dan solutif bagi setiap pasien.">{{ old('deskripsi') }}</textarea>
                                <small class="form-text text-muted">
                                    💡 <strong>Tips:</strong> Jelaskan keahlian spesifik, filosofi pelayanan medis, atau sikap profesional Dokter.
                                </small>
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold" style="color: #1b2b5a;">
                                    <i class="fas fa-briefcase text-primary mr-1"></i> Pengalaman Kerja & Karir
                                </label>
                                <textarea name="pengalaman" class="form-control" rows="3" 
                                    placeholder="Contoh: Dokter Spesialis Penyakit Dalam di RS Medika Sehat (2020 - Sekarang), Residen Penyakit Dalam RSUP Sanglah (2015 - 2020), Anggota Ikatan Dokter Indonesia (IDI).">{{ old('pengalaman') }}</textarea>
                                <small class="form-text text-muted">Tuliskan riwayat tempat praktik sebelumnya, pelatihan khusus, atau organisasi profesi.</small>
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold" style="color: #1b2b5a;">Foto Dokter</label>
                                <input type="file" name="foto" class="form-control-file" accept="image/*">
                                <small class="text-muted">Format: JPG, PNG, GIF. Maksimal 2MB</small>
                            </div>
                        </div>
                        <div class="card-footer" style="background: white; border-radius: 0 0 20px 20px;">
                            <div class="d-flex justify-content-end">
                                <a href="{{ route('pengelola.doctors.index') }}" class="btn btn-light mr-3" style="border-radius: 10px;">
                                    <i class="fas fa-times"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-primary" style="background: #28a745; border-color: #28a745; border-radius: 10px;">
                                    <i class="fas fa-save"></i> Simpan Dokter
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
    document.getElementById('btnIsiContohDeskripsiDokter')?.addEventListener('click', function() {
        const textarea = document.getElementById('inputDeskripsiDokter');
        if (textarea) {
            textarea.value = "dr. Budi Santoso, Sp.PD adalah dokter spesialis penyakit dalam dengan pengalaman lebih dari 8 tahun dalam menangani masalah kesehatan degeneratif, metabolisme, serta konsultasi kesehatan umum. Beliau berdedikasi memberikan konsultasi medis yang ramah, informatif, dan solutif bagi setiap pasien.";
            textarea.focus();
        }
    });
});
</script>
@endsection

