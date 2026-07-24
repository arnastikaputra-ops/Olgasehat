@extends('pemilikkesehatan.Layout.pengelolakesehatan')

@section('content')
<div class="content-wrapper" style="background: #f4f8ff; min-height: 100vh;">
    <div class="content-header border-0 pb-0">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h1 class="page-title mb-1" style="font-weight: 700; color: #1b2b5a;">Tambah Jadwal Dokter</h1>
                    <p class="text-muted mb-0">Tambah jadwal praktik dokter baru</p>
                </div>
                <ol class="breadcrumb float-md-right mt-2 mt-md-0">
                    <li class="breadcrumb-item"><a href="{{ route('pengelola.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('pengelola.schedules.index') }}">Jadwal Dokter</a></li>
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
                            <h3 class="card-title mb-0" style="font-weight: 700; color: #1b2b5a;">Form Tambah Jadwal</h3>
                        </div>
                    <form action="{{ route('pengelola.schedules.store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Dokter <span class="text-danger">*</span></label>
                                        <select name="doctor_id" class="form-control" required id="doctor_id">
                                            <option value="">Pilih Dokter</option>
                                            @foreach($doctors as $doctor)
                                                <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                                    {{ $doctor->nama_lengkap }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('doctor_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
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
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Hari <span class="text-danger">*</span></label>
                                        <div class="row">
                                            @foreach(['senin' => 'Senin', 'selasa' => 'Selasa', 'rabu' => 'Rabu', 'kamis' => 'Kamis', 'jumat' => 'Jumat', 'sabtu' => 'Sabtu', 'minggu' => 'Minggu'] as $key => $day)
                                                <div class="col-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="hari[]" value="{{ $key }}" id="hari_{{ $key }}" {{ in_array($key, old('hari', [])) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="hari_{{ $key }}">
                                                            {{ $day }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        @error('hari')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Jam Mulai <span class="text-danger">*</span></label>
                                        <input type="time" name="jam_mulai" class="form-control" value="{{ old('jam_mulai') }}" required>
                                        @error('jam_mulai')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Jam Selesai <span class="text-danger">*</span></label>
                                        <input type="time" name="jam_selesai" class="form-control" value="{{ old('jam_selesai') }}" required>
                                        @error('jam_selesai')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Durasi Konsultasi (menit) <span class="text-danger">*</span></label>
                                        <input type="number" name="durasi_konsultasi" class="form-control" value="{{ old('durasi_konsultasi', 30) }}" min="15" max="120" required>
                                        <small class="text-muted">Jadwal akan dibuat otomatis berdasarkan durasi ini (misal: 09:00, 09:30, dll.)</small>
                                        @error('durasi_konsultasi')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer" style="background: white; border-radius: 0 0 20px 20px;">
                            <div class="d-flex justify-content-end">
                                <a href="{{ route('pengelola.schedules.index') }}" class="btn btn-light mr-3" style="border-radius: 10px;">
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
document.addEventListener('DOMContentLoaded', function () {
    // Auto select clinic based on doctor
    const doctorSelect = document.getElementById('doctor_id');
    const clinicSelect = document.getElementById('clinic_id');

    doctorSelect.addEventListener('change', function() {
        const doctorId = this.value;
        if (doctorId) {
            const option = this.options[this.selectedIndex];
            const clinicName = option.text.split(' - ')[1];
            for (let i = 0; i < clinicSelect.options.length; i++) {
                if (clinicSelect.options[i].text === clinicName) {
                    clinicSelect.value = clinicSelect.options[i].value;
                    break;
                }
            }
        }
    });

    // Mapping hari
    const dayMap = ['minggu','senin','selasa','rabu','kamis','jumat','sabtu'];
    const dayLabelMap = {
        minggu: 'Minggu',
        senin: 'Senin',
        selasa: 'Selasa',
        rabu: 'Rabu',
        kamis: 'Kamis',
        jumat: 'Jumat',
        sabtu: 'Sabtu'
    };

    const tanggalInput = document.getElementById('tanggalPertemuan');
    const hariValueInput = document.getElementById('hariValue');
    const hariLabel = document.getElementById('hariLabel');

    tanggalInput.addEventListener('change', function () {
        if (!this.value) {
            hariValueInput.value = '';
            hariLabel.textContent = 'Belum memilih tanggal';
            return;
        }
        const date = new Date(this.value + 'T00:00:00');
        const daySlug = dayMap[date.getDay()];
        hariValueInput.value = daySlug;
        hariLabel.textContent = dayLabelMap[daySlug] || daySlug;
    });
    if (hariValueInput.value) {
        const display = dayLabelMap[hariValueInput.value] || hariValueInput.value;
        hariLabel.textContent = display;
    } else if (tanggalInput.value) {
        tanggalInput.dispatchEvent(new Event('change'));
    }

    // Custom time inputs - no additional logic needed
});
</script>
@endsection

