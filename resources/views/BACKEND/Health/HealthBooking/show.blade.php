@extends('BACKEND.Layout.admin')

@section('content')
<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0"><i class="fas fa-file-medical mr-2 text-primary"></i>Detail Janji Klinik</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('health.bookings.index') }}">Janji Temu Klinik</a></li>
            <li class="breadcrumb-item active">{{ $booking->kode_booking }}</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      @endif

      <div class="row">
        <div class="col-md-8">
          <div class="card card-primary card-outline shadow-sm">
            <div class="card-header">
              <h3 class="card-title"><i class="fas fa-info-circle mr-1"></i> Informasi Janji Temu</h3>
              <div class="card-tools">
                <span class="badge badge-primary font-mono text-base px-3 py-1">{{ $booking->kode_booking }}</span>
              </div>
            </div>
            <div class="card-body">
              <div class="row mb-4">
                <div class="col-md-6 border-right">
                  <h5 class="text-primary font-weight-bold"><i class="fas fa-user-injured mr-1"></i> Data Pasien</h5>
                  <table class="table table-sm table-borderless">
                    <tr>
                      <th width="120">Nama Pasien:</th>
                      <td><strong>{{ $booking->nama_pasien }}</strong></td>
                    </tr>
                    <tr>
                      <th>No. Telepon:</th>
                      <td><a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $booking->nomor_telepon) }}" target="_blank" class="btn btn-xs btn-outline-success"><i class="fab fa-whatsapp mr-1"></i>{{ $booking->nomor_telepon }}</a></td>
                    </tr>
                    <tr>
                      <th>Akun User:</th>
                      <td>{{ $booking->user->name ?? '-' }} ({{ $booking->user->email ?? '-' }})</td>
                    </tr>
                  </table>
                </div>

                <div class="col-md-6">
                  <h5 class="text-success font-weight-bold"><i class="fas fa-hospital mr-1"></i> Klinik & Dokter</h5>
                  <table class="table table-sm table-borderless">
                    <tr>
                      <th width="120">Klinik:</th>
                      <td><strong>{{ $booking->clinic->nama ?? 'Klinik' }}</strong></td>
                    </tr>
                    <tr>
                      <th>Dokter:</th>
                      <td><span class="text-success font-weight-bold">{{ $booking->doctor->nama_lengkap ?? ($booking->doctor->nama ?? 'Tim Dokter') }}</span></td>
                    </tr>
                    <tr>
                      <th>Layanan:</th>
                      <td><span class="badge badge-info">{{ $booking->service->nama ?? 'Layanan' }}</span></td>
                    </tr>
                    <tr>
                      <th>Jadwal Sesi:</th>
                      <td><strong>{{ \Carbon\Carbon::parse($booking->tanggal)->format('d F Y') }}</strong> ({{ $booking->jam }} WIB)</td>
                    </tr>
                  </table>
                </div>
              </div>

              <hr>

              <div class="row">
                <div class="col-md-6 border-right">
                  <h5 class="text-info font-weight-bold"><i class="fas fa-receipt mr-1"></i> Detail Pembayaran</h5>
                  <table class="table table-sm table-borderless">
                    <tr>
                      <th width="140">Total Pembayaran:</th>
                      <td><h4 class="text-success font-weight-bold mb-0">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</h4></td>
                    </tr>
                    <tr>
                      <th>Metode:</th>
                      <td>{{ $booking->bank_code }} ({{ $booking->metode_pembayaran }})</td>
                    </tr>
                    <tr>
                      <th>Virtual Account:</th>
                      <td><code class="font-weight-bold text-dark">{{ $booking->virtual_account }}</code></td>
                    </tr>
                    <tr>
                      <th>Status Pembayaran:</th>
                      <td>
                        @if($booking->status_pembayaran == 'paid' || $booking->status == 'confirmed')
                          <span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle mr-1"></i>DISETUJUI / LUNAS</span>
                        @elseif($booking->status_pembayaran == 'pending_acc' || $booking->status == 'pending')
                          <span class="badge badge-warning text-dark px-2 py-1"><i class="fas fa-clock mr-1"></i>MENUNGGU ACC</span>
                        @else
                          <span class="badge badge-danger px-2 py-1"><i class="fas fa-times-circle mr-1"></i>DITOLAK / BATAL</span>
                        @endif
                      </td>
                    </tr>
                  </table>
                </div>

                <div class="col-md-6">
                  <h5 class="text-secondary font-weight-bold"><i class="fas fa-file-medical-alt mr-1"></i> Catatan Medis & Rejeksi</h5>
                  @if($booking->alasan_reject)
                    <div class="alert alert-danger">
                      <strong>Alasan Penolakan:</strong><br>{{ $booking->alasan_reject }}
                    </div>
                  @endif

                  @if($booking->catatan_dokter)
                    <div class="alert alert-info">
                      <strong>Catatan Dokter:</strong><br>{{ $booking->catatan_dokter }}
                    </div>
                  @else
                    <p class="text-muted italic">Belum ada catatan medis dokter.</p>
                  @endif
                </div>
              </div>

              <!-- Form Update Status -->
              <div class="mt-4 p-3 bg-light rounded border">
                <h5 class="font-weight-bold text-dark mb-3"><i class="fas fa-edit mr-1"></i> Update Status Janji & Catatan</h5>
                <form action="{{ route('health.bookings.update-status', $booking->id) }}" method="POST">
                  @csrf
                  <div class="form-row">
                    <div class="col-md-4 mb-2">
                      <label>Status Janji:</label>
                      <select name="status" class="form-control font-weight-bold">
                        <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Menunggu ACC</option>
                        <option value="confirmed" {{ in_array($booking->status, ['confirmed', 'approved']) ? 'selected' : '' }}>Setujui / ACC (Confirmed)</option>
                        <option value="completed" {{ $booking->status == 'completed' ? 'selected' : '' }}>Selesai (Completed)</option>
                        <option value="cancelled" {{ in_array($booking->status, ['cancelled', 'rejected']) ? 'selected' : '' }}>Tolak / Batalkan</option>
                      </select>
                    </div>
                    <div class="col-md-8 mb-2">
                      <label>Catatan Dokter / Hasil Pemeriksaan:</label>
                      <input type="text" name="catatan_dokter" class="form-control" placeholder="Tuliskan catatan hasil pemeriksaan atau saran obat..." value="{{ $booking->catatan_dokter }}">
                    </div>
                  </div>
                  <div class="text-right mt-2">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan Perubahan Status</button>
                  </div>
                </form>
              </div>

            </div>
            <div class="card-footer">
              <a href="{{ route('health.bookings.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar</a>
            </div>
          </div>
        </div>

        <!-- Right Side: Bukti Transfer Preview -->
        <div class="col-md-4">
          <div class="card card-info card-outline shadow-sm">
            <div class="card-header">
              <h3 class="card-title"><i class="fas fa-image mr-1"></i> Bukti Transfer Pasien</h3>
            </div>
            <div class="card-body text-center">
              @if($booking->bukti_pembayaran)
                <a href="{{ asset('bukti_pembayaran/' . $booking->bukti_pembayaran) }}" target="_blank">
                  <img src="{{ asset('bukti_pembayaran/' . $booking->bukti_pembayaran) }}" alt="Bukti Transfer" class="img-fluid rounded border shadow-sm hover:opacity-90" style="max-height: 400px; object-fit: contain;">
                </a>
                <p class="text-xs text-muted mt-2">Klik gambar untuk melihat dalam ukuran penuh.</p>
              @else
                <div class="py-5 text-muted">
                  <i class="fas fa-file-image fa-4x mb-3 text-secondary"></i>
                  <p>Pasien belum mengunggah bukti pembayaran.</p>
                </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
@endsection
