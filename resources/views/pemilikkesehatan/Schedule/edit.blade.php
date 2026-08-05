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

                            <!-- HARI OPERASIONAL PRAKTIK -->
                            <div class="card p-3 border-0 rounded-lg mb-3" style="background: #f8fafc; border: 1.5px solid #e2e8f0 !important;">
                                <div class="d-flex justify-content-between align-items-center flex-wrap mb-2">
                                    <label class="font-weight-bold mb-1" style="color: #1b2b5a; font-size: 1rem;">
                                        <i class="fas fa-calendar-alt text-primary mr-1"></i> Hari Praktik Dokter <span class="text-danger">*</span>
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
                                <small class="text-muted d-block mb-3">Klik tombol <strong>Setiap Hari</strong> di atas atau pilih hari praktik secara manual:</small>
                                
                                <div class="row" id="hariOperasionalContainer">
                                    @foreach(['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'] as $hari)
                                    <div class="col-6 col-sm-4 col-md-3 mb-2">
                                        <label class="hari-card d-flex align-items-center p-2 rounded-lg border w-100 mb-0" 
                                               style="cursor: pointer; transition: all 0.2s ease; border: 1.5px solid #cbd5e1; background: white;"
                                               for="hari_{{ $hari }}">
                                            <input class="form-check-input hari-checkbox position-static mt-0 mr-2" type="checkbox" name="hari[]" value="{{ $hari }}" id="hari_{{ $hari }}"
                                                {{ old('hari') ? (in_array($hari, old('hari', [])) ? 'checked' : '') : ($schedule->hari == $hari ? 'checked' : '') }}>
                                            <span class="font-weight-bold text-dark" style="font-size: 0.95rem;">{{ ucfirst($hari) }}</span>
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                                @error('hari')
                                    <small class="text-danger d-block mt-2">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- JAM MULAI & JAM SELESAI -->
                            @php
                                $mulaiFormatted = $schedule->jam_mulai ? date('H:i', strtotime($schedule->jam_mulai)) : '';
                                $selesaiFormatted = $schedule->jam_selesai ? date('H:i', strtotime($schedule->jam_selesai)) : '';
                                $is24Jam = (old('jam_mulai', $mulaiFormatted) == '00:00') && (old('jam_selesai', $selesaiFormatted) == '23:59');
                            @endphp
                            <div class="card p-3 border-0 rounded-lg mb-3" style="background: #f8fafc; border: 1.5px solid #e2e8f0 !important;">
                                <div class="d-flex justify-content-between align-items-center flex-wrap mb-2">
                                    <label class="font-weight-bold mb-1" style="color: #1b2b5a; font-size: 1rem;">
                                        <i class="far fa-clock text-primary mr-1"></i> Jam Praktik Dokter <span class="text-danger">*</span>
                                    </label>
                                    <div class="custom-control custom-switch mb-1">
                                        <input type="checkbox" class="custom-control-input" id="switch24Jam" {{ $is24Jam ? 'checked' : '' }}>
                                        <label class="custom-control-label font-weight-bold text-success" for="switch24Jam" style="cursor: pointer;">
                                            <i class="fas fa-bolt text-warning mr-1"></i> Praktik 24 Jam Nonstop
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <small class="text-muted d-block mb-1">Pilihan cepat sesi jam praktik:</small>
                                    <button type="button" class="btn btn-sm btn-outline-success mr-1 mb-1 preset-jam-24 font-weight-bold">
                                        <i class="fas fa-bolt mr-1"></i> Praktik 24 Jam
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary mr-1 mb-1 preset-jam" data-buka="08:00" data-tutup="12:00">
                                        <i class="far fa-sun mr-1"></i> 08:00 - 12:00 (Sesi Pagi)
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary mr-1 mb-1 preset-jam" data-buka="13:00" data-tutup="17:00">
                                        <i class="fas fa-sun mr-1"></i> 13:00 - 17:00 (Sesi Siang)
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary mr-1 mb-1 preset-jam" data-buka="17:00" data-tutup="21:00">
                                        <i class="fas fa-moon mr-1"></i> 17:00 - 21:00 (Sesi Malam)
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary mr-1 mb-1 preset-jam" data-buka="08:00" data-tutup="17:00">
                                        <i class="far fa-clock mr-1"></i> 08:00 - 17:00 (Full Day)
                                    </button>
                                </div>

                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <div class="form-group mb-0">
                                            <label class="small font-weight-bold text-secondary mb-1">Jam Mulai <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-white"><i class="far fa-clock text-primary"></i></span>
                                                </div>
                                                <input type="time" name="jam_mulai" id="inputJamBuka" class="form-control" value="{{ old('jam_mulai', $mulaiFormatted) }}" required>
                                            </div>
                                            @error('jam_mulai')
                                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 mt-2 mt-md-0">
                                        <div class="form-group mb-0">
                                            <label class="small font-weight-bold text-secondary mb-1">Jam Selesai <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-white"><i class="far fa-clock text-danger"></i></span>
                                                </div>
                                                <input type="time" name="jam_selesai" id="inputJamTutup" class="form-control" value="{{ old('jam_selesai', $selesaiFormatted) }}" required>
                                            </div>
                                            @error('jam_selesai')
                                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <div id="info24Jam" class="alert alert-success py-2 px-3 mb-0 mt-3 rounded-lg d-none align-items-center" style="border-left: 4px solid #28a745;">
                                    <i class="fas fa-check-circle mr-2 fa-lg"></i>
                                    <div>
                                        <strong>Status: Dokter Berpraktik 24 Jam Nonstop</strong>
                                        <div class="small">Jam Mulai diatur otomatis ke 00:00 dan Jam Selesai ke 23:59.</div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold" style="color: #1b2b5a;">
                                            <i class="fas fa-hourglass-half text-primary mr-1"></i> Durasi Konsultasi (menit)
                                        </label>
                                        <input type="number" name="durasi_konsultasi" class="form-control" value="{{ old('durasi_konsultasi', $schedule->durasi_konsultasi) }}" min="15" max="120">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold" style="color: #1b2b5a;">
                                            <i class="fas fa-users text-primary mr-1"></i> Kuota Pasien per Hari
                                        </label>
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
                                    <i class="fas fa-save"></i> Update Jadwal
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
    // JS JAM MULAI & JAM SELESAI (24 JAM)
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
});
</script>
@endsection

