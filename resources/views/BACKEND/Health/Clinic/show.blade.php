@extends('BACKEND.Layout.admin')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Detail Klinik - {{ $clinic->nama }}</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('health.clinics.index') }}">Klinik</a></li>
            <li class="breadcrumb-item active">Detail</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          {{ session('success') }}
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      @endif

      @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          {{ session('error') }}
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      @endif

      <div class="row">
        <!-- Left Column - Clinic Info -->
        <div class="col-md-8">
          <!-- Clinic Information Card -->
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Informasi Klinik</h3>
            </div>
            <div class="card-body">
              <div class="row mb-3">
                <div class="col-md-4">
                  @if($clinic->logo)
                    <img src="{{ asset('fotoklinik/' . $clinic->logo) }}" alt="{{ $clinic->nama }}" class="img-fluid rounded" style="max-height: 200px;">
                  @else
                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 200px;">
                      <i class="fas fa-hospital fa-4x text-muted"></i>
                    </div>
                  @endif
                </div>
                <div class="col-md-8">
                  <h4 class="font-weight-bold">{{ $clinic->nama }}</h4>
                  <p class="text-muted mb-2">
                    <span class="badge badge-{{ $clinic->tipe == 'klinik' ? 'primary' : 'info' }}">
                      {{ ucfirst($clinic->tipe) }}
                    </span>
                    @if($clinic->status == 'approved')
                      <span class="badge badge-success">Disetujui</span>
                    @elseif($clinic->status == 'pending')
                      <span class="badge badge-warning">Menunggu Verifikasi</span>
                    @else
                      <span class="badge badge-danger">Ditolak</span>
                    @endif
                  </p>
                  @if($clinic->motto)
                    <p class="text-muted"><em>{{ $clinic->motto }}</em></p>
                  @endif
                </div>
              </div>

              <hr>

              <div class="row">
                <div class="col-md-6">
                  <h5 class="font-weight-bold mb-3">Informasi Dasar</h5>
                  <table class="table table-sm">
                    <tr>
                      <td width="40%"><strong>Nama Klinik:</strong></td>
                      <td>{{ $clinic->nama }}</td>
                    </tr>
                    <tr>
                      <td><strong>Tipe:</strong></td>
                      <td>{{ ucfirst($clinic->tipe) }}</td>
                    </tr>
                    <tr>
                      <td><strong>Status:</strong></td>
                      <td>
                        @if($clinic->status == 'approved')
                          <span class="badge badge-success">Disetujui</span>
                        @elseif($clinic->status == 'pending')
                          <span class="badge badge-warning">Menunggu Verifikasi</span>
                        @else
                          <span class="badge badge-danger">Ditolak</span>
                        @endif
                      </td>
                    </tr>
                    @if($clinic->verified_at)
                    <tr>
                      <td><strong>Tanggal Verifikasi:</strong></td>
                      <td>{{ $clinic->verified_at->format('d M Y H:i') }}</td>
                    </tr>
                    @endif
                    @if($clinic->alasan_reject)
                    <tr>
                      <td><strong>Alasan Ditolak:</strong></td>
                      <td class="text-danger">{{ $clinic->alasan_reject }}</td>
                    </tr>
                    @endif
                  </table>
                </div>
                <div class="col-md-6">
                  <h5 class="font-weight-bold mb-3">Kontak & Lokasi</h5>
                  <table class="table table-sm">
                    <tr>
                      <td width="40%"><strong>Alamat:</strong></td>
                      <td>{{ $clinic->alamat ?? '-' }}</td>
                    </tr>
                    <tr>
                      <td><strong>Kota:</strong></td>
                      <td>{{ $clinic->kota ?? '-' }}</td>
                    </tr>
                    <tr>
                      <td><strong>Provinsi:</strong></td>
                      <td>{{ $clinic->provinsi ?? '-' }}</td>
                    </tr>
                    <tr>
                      <td><strong>Telepon:</strong></td>
                      <td>{{ $clinic->nomor_telepon ?? '-' }}</td>
                    </tr>
                    <tr>
                      <td><strong>Email:</strong></td>
                      <td>{{ $clinic->email ?? '-' }}</td>
                    </tr>
                    <tr>
                      <td><strong>Website:</strong></td>
                      <td>
                        @if($clinic->website)
                          <a href="{{ $clinic->website }}" target="_blank">{{ $clinic->website }}</a>
                        @else
                          -
                        @endif
                      </td>
                    </tr>
                  </table>
                </div>
              </div>

              @if($clinic->deskripsi)
              <hr>
              <div>
                <h5 class="font-weight-bold mb-3">Deskripsi</h5>
                <p>{{ $clinic->deskripsi }}</p>
              </div>
              @endif

              @if($clinic->layanan_tersedia && count($clinic->layanan_tersedia) > 0)
              <hr>
              <div>
                <h5 class="font-weight-bold mb-3">Jenis Layanan</h5>
                <div class="d-flex flex-wrap" style="gap: 0.5rem;">
                  @foreach($clinic->layanan_tersedia as $layanan)
                    <span class="badge badge-info" style="font-size: 0.9rem; padding: 0.5rem 0.75rem;">{{ $layanan }}</span>
                  @endforeach
                </div>
              </div>
              @else
              <hr>
              <div>
                <h5 class="font-weight-bold mb-3">Jenis Layanan</h5>
                <p class="text-muted">Belum ada jenis layanan yang diinput</p>
              </div>
              @endif

              @if($clinic->hari_operasional && count($clinic->hari_operasional) > 0)
              <hr>
              <div>
                <h5 class="font-weight-bold mb-3">Hari Operasional</h5>
                <p>
                  @foreach($clinic->hari_operasional as $hari)
                    <span class="badge badge-primary">{{ ucfirst($hari) }}</span>
                  @endforeach
                  @if($clinic->jam_buka && $clinic->jam_tutup)
                    <br><small class="text-muted mt-2 d-block">
                      Jam: {{ date('H:i', strtotime($clinic->jam_buka)) }} - {{ date('H:i', strtotime($clinic->jam_tutup)) }}
                    </small>
                  @endif
                </p>
              </div>
              @endif

              <hr>
              <div>
                <h5 class="font-weight-bold mb-3">Informasi Pemilik</h5>
                <table class="table table-sm">
                  <tr>
                    <td width="40%"><strong>Nama:</strong></td>
                    <td>{{ $clinic->user->name ?? '-' }}</td>
                  </tr>
                  <tr>
                    <td><strong>Email:</strong></td>
                    <td>{{ $clinic->user->email ?? '-' }}</td>
                  </tr>
                  <tr>
                    <td><strong>Tanggal Daftar:</strong></td>
                    <td>{{ $clinic->created_at->format('d M Y H:i') }}</td>
                  </tr>
                </table>
              </div>
            </div>
          </div>

          <!-- Statistics Card -->
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Statistik</h3>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-4">
                  <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="fas fa-user-md"></i></span>
                    <div class="info-box-content">
                      <span class="info-box-text">Total Dokter</span>
                      <span class="info-box-number">{{ $clinic->doctors->count() }}</span>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="info-box">
                    <span class="info-box-icon bg-success"><i class="fas fa-heartbeat"></i></span>
                    <div class="info-box-content">
                      <span class="info-box-text">Total Layanan</span>
                      <span class="info-box-number">{{ $clinic->services->count() }}</span>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="info-box">
                    <span class="info-box-icon bg-warning"><i class="fas fa-calendar-check"></i></span>
                    <div class="info-box-content">
                      <span class="info-box-text">Total Booking</span>
                      <span class="info-box-number">{{ $clinic->bookings->count() }}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Right Column - Verification Actions -->
        <div class="col-md-4">
          <!-- Verification Card -->
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Aksi Verifikasi</h3>
            </div>
            <div class="card-body">
              @if($clinic->status == 'pending')
                <div class="alert alert-warning">
                  <i class="fas fa-exclamation-triangle"></i> Klinik ini menunggu verifikasi
                </div>

                <!-- Approve Form -->
                <form action="{{ route('health.clinics.approve', $clinic->id) }}" method="POST" class="mb-3">
                  @csrf
                  <div class="p-3 bg-light border rounded mb-3">
                    <h6 class="font-weight-bold text-primary mb-2">
                      <i class="fas fa-percentage mr-1"></i> Pengaturan Bagi Hasil Platform
                    </h6>
                    <div class="form-group mb-2">
                      <label class="font-weight-bold">Tipe Bagi Hasil:</label>
                      <select name="komisi_tipe" class="form-control form-control-sm">
                        <option value="none" {{ ($clinic->komisi_tipe ?? 'none') == 'none' ? 'selected' : '' }}>Tanpa Bagi Hasil (0%)</option>
                        <option value="percentage" {{ ($clinic->komisi_tipe ?? '') == 'percentage' ? 'selected' : '' }}>Bagi Hasil Persentase (%)</option>
                        <option value="fixed" {{ ($clinic->komisi_tipe ?? '') == 'fixed' ? 'selected' : '' }}>Nominal Tetap (Rp)</option>
                      </select>
                    </div>
                    <div class="form-group mb-0">
                      <label class="font-weight-bold">Nilai Komisi Platform:</label>
                      <input type="number" step="0.01" min="0" name="komisi_nilai" value="{{ (float)($clinic->komisi_nilai ?? 0) }}" class="form-control form-control-sm" placeholder="Contoh: 5 untuk 5% atau 5000">
                    </div>
                  </div>
                  <button type="submit" class="btn btn-success btn-block btn-lg" onclick="return confirm('Apakah Anda yakin ingin menyetujui klinik ini dengan skema bagi hasil yang dipilih?');">
                    <i class="fas fa-check-circle"></i> Setujui Klinik
                  </button>
                </form>

                <!-- Reject Form -->
                <form action="{{ route('health.clinics.reject', $clinic->id) }}" method="POST" id="rejectForm">
                  @csrf
                  <div class="form-group">
                    <label>Alasan Penolakan <span class="text-danger">*</span></label>
                    <textarea name="alasan_reject" class="form-control" rows="4" placeholder="Masukkan alasan penolakan..." required></textarea>
                  </div>
                  <button type="submit" class="btn btn-danger btn-block btn-lg" onclick="return confirm('Apakah Anda yakin ingin menolak klinik ini?');">
                    <i class="fas fa-times-circle"></i> Tolak Klinik
                  </button>
                </form>
              @elseif($clinic->status == 'approved')
                <div class="alert alert-success">
                  <i class="fas fa-check-circle"></i> Klinik ini sudah disetujui
                </div>
                <p class="text-muted">
                  <strong>Tanggal Verifikasi:</strong><br>
                  {{ $clinic->verified_at->format('d M Y H:i') }}
                </p>
                <form action="{{ route('health.clinics.approve', $clinic->id) }}" method="POST">
                  @csrf
                  <button type="submit" class="btn btn-warning btn-block" onclick="return confirm('Ubah status kembali ke pending?');">
                    <i class="fas fa-undo"></i> Reset ke Pending
                  </button>
                </form>
              @else
                <div class="alert alert-danger">
                  <i class="fas fa-times-circle"></i> Klinik ini ditolak
                </div>
                @if($clinic->alasan_reject)
                  <div class="alert alert-danger">
                    <strong>Alasan Penolakan:</strong><br>
                    {{ $clinic->alasan_reject }}
                  </div>
                @endif
                <form action="{{ route('health.clinics.approve', $clinic->id) }}" method="POST">
                  @csrf
                  <button type="submit" class="btn btn-success btn-block" onclick="return confirm('Setujui klinik ini?');">
                    <i class="fas fa-check-circle"></i> Setujui Klinik
                  </button>
                </form>
              @endif
            </div>
          </div>

          <!-- Quick Actions Card -->
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Aksi Cepat</h3>
            </div>
            <div class="card-body">
              <a href="{{ route('health.clinics.edit', $clinic->id) }}" class="btn btn-primary btn-block mb-2">
                <i class="fas fa-edit"></i> Edit Klinik
              </a>
              <a href="{{ route('health.clinics.index') }}" class="btn btn-default btn-block">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
@endsection

