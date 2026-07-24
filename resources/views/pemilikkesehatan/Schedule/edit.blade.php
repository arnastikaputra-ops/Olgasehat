@extends('pemilikkesehatan.Layout.pengelolakesehatan')

@section('content')
<div class="content-wrapper" style="background: #f4f8ff; min-height: 100vh;">
    <div class="content-header border-0 pb-0">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h1 class="page-title mb-1" style="font-weight: 700; color: #1b2b5a;">Edit Jadwal Dokter</h1>
                    <p class="text-muted mb-0">Edit jadwal praktik dokter</p>
                </div>
                <ol class="breadcrumb float-md-right mt-2 mt-md-0">
                    <li class="breadcrumb-item"><a href="{{ route('pengelola.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('pengelola.schedules.index') }}">Jadwal Dokter</a></li>
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
                            <h3 class="card-title mb-0" style="font-weight: 700; color: #1b2b5a;">Form Edit Jadwal</h3>
                        </div>
                    <form action="{{ route('pengelola.schedules.update', $schedule->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Dokter <span class="text-danger">*</span></label>
                                        <select name="doctor_id" class="form-control" required>
                                            <option value="">Pilih Dokter</option>
                                            @foreach($doctors as $doctor)
                                                <option value="{{ $doctor->id }}" {{ old('doctor_id', $schedule->doctor_id) == $doctor->id ? 'selected' : '' }}>
                                                    {{ $doctor->nama_lengkap }} - {{ $doctor->clinic->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Klinik <span class="text-danger">*</span></label>
                                        <select name="clinic_id" class="form-control" required>
                                            <option value="">Pilih Klinik</option>
                                            @foreach($clinics as $clinic)
                                                <option value="{{ $clinic->id }}" {{ old('clinic_id', $schedule->clinic_id) == $clinic->id ? 'selected' : '' }}>
                                                    {{ $clinic->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Hari Operasional <span class="text-danger">*</span></label>
                                        <div class="row">
                                            @foreach(['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'] as $hari)
                                            <div class="col-6 col-md-3 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="hari[]" value="{{ $hari }}" id="hari_{{ $hari }}" 
                                                        {{ old('hari') ? (in_array($hari, old('hari', [])) ? 'checked' : '') : ($schedule->hari == $hari ? 'checked' : '') }}>
                                                    <label class="form-check-label" for="hari_{{ $hari }}">{{ ucfirst($hari) }}</label>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                        @error('hari')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Jam Mulai <span class="text-danger">*</span></label>
                                        <input type="time" name="jam_mulai" class="form-control" value="{{ old('jam_mulai', $schedule->jam_mulai) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Jam Selesai <span class="text-danger">*</span></label>
                                        <input type="time" name="jam_selesai" class="form-control" value="{{ old('jam_selesai', $schedule->jam_selesai) }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Durasi Konsultasi (menit)</label>
                                        <input type="number" name="durasi_konsultasi" class="form-control" value="{{ old('durasi_konsultasi', $schedule->durasi_konsultasi) }}" min="15" max="120">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Kuota per Hari</label>
                                        <input type="number" name="kuota_per_hari" class="form-control" value="{{ old('kuota_per_hari', $schedule->kuota_per_hari) }}" min="1" max="100">
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
@endsection

