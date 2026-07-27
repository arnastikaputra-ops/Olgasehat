@extends('BACKEND.Layout.admin')

@section('content')
<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0"><i class="fas fa-stethoscope mr-2 text-primary"></i>Verifikasi & Janji Temu Klinik</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
            <li class="breadcrumb-item active">Janji Temu Klinik</li>
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

      <!-- Summary Stats Cards -->
      <div class="row mb-3">
        <div class="col-lg-3 col-6">
          <div class="small-box bg-warning shadow-sm">
            <div class="inner">
              <h3>{{ $countPending }}</h3>
              <p class="font-weight-bold">Menunggu ACC Admin/Klinik</p>
            </div>
            <div class="icon">
              <i class="fas fa-clock"></i>
            </div>
            <a href="{{ route('health.bookings.index', ['status' => 'pending']) }}" class="small-box-footer">
              Filter Menunggu <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>

        <div class="col-lg-3 col-6">
          <div class="small-box bg-success shadow-sm">
            <div class="inner">
              <h3>{{ $countConfirmed }}</h3>
              <p class="font-weight-bold">Disetujui / Lunas</p>
            </div>
            <div class="icon">
              <i class="fas fa-check-circle"></i>
            </div>
            <a href="{{ route('health.bookings.index', ['status' => 'confirmed']) }}" class="small-box-footer">
              Filter Disetujui <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>

        <div class="col-lg-3 col-6">
          <div class="small-box bg-info shadow-sm">
            <div class="inner">
              <h3>{{ $countCompleted }}</h3>
              <p class="font-weight-bold">Pemeriksaan Selesai</p>
            </div>
            <div class="icon">
              <i class="fas fa-user-md"></i>
            </div>
            <a href="{{ route('health.bookings.index', ['status' => 'completed']) }}" class="small-box-footer">
              Filter Selesai <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>

        <div class="col-lg-3 col-6">
          <div class="small-box bg-secondary shadow-sm">
            <div class="inner">
              <h3>{{ $countTotal }}</h3>
              <p class="font-weight-bold">Total Pengajuan Janji</p>
            </div>
            <div class="icon">
              <i class="fas fa-calendar-alt"></i>
            </div>
            <a href="{{ route('health.bookings.index') }}" class="small-box-footer">
              Tampilkan Semua <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
      </div>

      <!-- Filter Card -->
      <div class="card card-outline card-primary shadow-sm mb-4">
        <div class="card-header">
          <h3 class="card-title"><i class="fas fa-filter mr-1"></i> Filter Data Booking</h3>
        </div>
        <div class="card-body">
          <form method="GET" action="{{ route('health.bookings.index') }}">
            <div class="row">
              <div class="col-md-3 mb-2">
                <input type="text" name="search" class="form-control" placeholder="Cari Kode Booking / Nama / Telepon..." value="{{ request('search') }}">
              </div>
              <div class="col-md-3 mb-2">
                <select name="clinic_id" class="form-control">
                  <option value="">-- Semua Klinik --</option>
                  @foreach($clinics as $c)
                    <option value="{{ $c->id }}" {{ request('clinic_id') == $c->id ? 'selected' : '' }}>{{ $c->nama }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-2 mb-2">
                <select name="status" class="form-control">
                  <option value="">-- Semua Status --</option>
                  <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu ACC</option>
                  <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Disetujui (Paid)</option>
                  <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                  <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Ditolak</option>
                </select>
              </div>
              <div class="col-md-2 mb-2">
                <input type="date" name="tanggal" class="form-control" value="{{ request('tanggal') }}">
              </div>
              <div class="col-md-2 mb-2">
                <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-search mr-1"></i> Cari</button>
              </div>
            </div>
          </form>
        </div>
      </div>

      <!-- Booking List Table -->
      <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
          <h3 class="card-title text-bold text-dark"><i class="fas fa-list mr-2"></i>Daftar Pemesanan Janji Klinik</h3>
        </div>
        <div class="card-body p-0 table-responsive">
          <table class="table table-hover table-striped mb-0">
            <thead class="thead-dark">
              <tr>
                <th>No</th>
                <th>Kode Booking</th>
                <th>Pasien</th>
                <th>Klinik & Dokter</th>
                <th>Jadwal Sesi</th>
                <th>Total & VA</th>
                <th>Bukti Transfer</th>
                <th>Status</th>
                <th class="text-center">Aksi Verifikasi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($bookings as $idx => $b)
                <tr>
                  <td>{{ $bookings->firstItem() + $idx }}</td>
                  <td>
                    <span class="badge badge-primary font-mono text-sm px-2 py-1">{{ $b->kode_booking }}</span>
                    <br><small class="text-muted">{{ $b->created_at ? $b->created_at->format('d M Y H:i') : '-' }}</small>
                  </td>
                  <td>
                    <strong>{{ $b->nama_pasien }}</strong>
                    <br><small class="text-muted"><i class="fas fa-phone mr-1"></i>{{ $b->nomor_telepon }}</small>
                  </td>
                  <td>
                    <strong class="text-indigo">{{ $b->clinic->nama ?? 'Klinik' }}</strong>
                    <br><small class="text-success"><i class="fas fa-user-md mr-1"></i>{{ $b->doctor->nama_lengkap ?? ($b->doctor->nama ?? 'Dokter') }}</small>
                    <br><span class="badge badge-light border">{{ $b->service->nama ?? 'Layanan' }}</span>
                  </td>
                  <td>
                    <span class="badge badge-info"><i class="fas fa-calendar-day mr-1"></i>{{ \Carbon\Carbon::parse($b->tanggal)->format('d M Y') }}</span>
                    <br><small class="font-weight-bold text-dark"><i class="fas fa-clock mr-1"></i>{{ $b->jam }} WIB</small>
                  </td>
                  <td>
                    <span class="font-weight-bold text-success">Rp {{ number_format($b->total_harga, 0, ',', '.') }}</span>
                    <br><small class="text-muted font-mono">VA: {{ $b->virtual_account }} ({{ $b->bank_code }})</small>
                  </td>
                  <td>
                    @if($b->bukti_pembayaran)
                      <a href="{{ asset('bukti_pembayaran/' . $b->bukti_pembayaran) }}" target="_blank" class="btn btn-sm btn-outline-info">
                        <i class="fas fa-image mr-1"></i> Lihat Bukti
                      </a>
                    @else
                      <span class="badge badge-secondary">Belum Upload</span>
                    @endif
                  </td>
                  <td>
                    @if($b->status_pembayaran == 'pending_acc' || $b->status == 'pending')
                      <span class="badge badge-warning text-dark px-2 py-1"><i class="fas fa-clock mr-1"></i>MENUNGGU ACC</span>
                    @elseif($b->status_pembayaran == 'paid' || $b->status == 'confirmed' || $b->status == 'approved')
                      <span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle mr-1"></i>DISETUJUI / LUNAS</span>
                    @elseif($b->status == 'completed')
                      <span class="badge badge-info px-2 py-1"><i class="fas fa-user-check mr-1"></i>SELESAI</span>
                    @elseif($b->status_pembayaran == 'rejected' || $b->status == 'cancelled')
                      <span class="badge badge-danger px-2 py-1"><i class="fas fa-times-circle mr-1"></i>DITOLAK</span>
                    @else
                      <span class="badge badge-secondary px-2 py-1">{{ strtoupper($b->status) }}</span>
                    @endif
                  </td>
                  <td class="text-center">
                    <div class="btn-group-vertical btn-group-sm">
                      @if($b->status_pembayaran == 'pending_acc' || $b->status == 'pending')
                        <form action="{{ route('health.bookings.update-status', $b->id) }}" method="POST" class="d-inline mb-1">
                          @csrf
                          <input type="hidden" name="status" value="confirmed">
                          <button type="submit" class="btn btn-success btn-sm btn-block" onclick="return confirm('Setujui dan ACC janji klinik ini?')">
                            <i class="fas fa-check mr-1"></i> Setujui (ACC)
                          </button>
                        </form>

                        <button type="button" class="btn btn-danger btn-sm btn-block" onclick="openRejectModal({{ $b->id }}, '{{ addslashes($b->kode_booking) }}')">
                          <i class="fas fa-times mr-1"></i> Tolak
                        </button>
                      @elseif($b->status == 'confirmed')
                        <form action="{{ route('health.bookings.update-status', $b->id) }}" method="POST" class="d-inline">
                          @csrf
                          <input type="hidden" name="status" value="completed">
                          <button type="submit" class="btn btn-info btn-sm btn-block" onclick="return confirm('Tandai pemeriksaan telah selesai dilakukan?')">
                            <i class="fas fa-check-double mr-1"></i> Tandai Selesai
                          </button>
                        </form>
                      @else
                        <a href="{{ route('health.bookings.show', $b->id) }}" class="btn btn-secondary btn-sm">
                          <i class="fas fa-eye mr-1"></i> Detail
                        </a>
                      @endif
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="9" class="text-center py-4 text-muted">
                    <i class="fas fa-inbox fa-3x mb-3 d-block text-secondary"></i>
                    Belum ada pengajuan janji temu klinik yang sesuai filter.
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        <div class="card-footer bg-white">
          {{ $bookings->links() }}
        </div>
      </div>
    </div>
  </section>
</div>

<!-- Modal Tolak Booking -->
<div class="modal fade" id="rejectBookingModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="rejectForm" method="POST" action="">
        @csrf
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title"><i class="fas fa-times-circle mr-1"></i> Tolak Pengajuan Janji Klinik</h5>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p>Tolak booking <strong id="rejectKode"></strong>?</p>
          <input type="hidden" name="status" value="cancelled">
          <div class="form-group">
            <label>Alasan Penolakan <span class="text-danger">*</span></label>
            <textarea name="alasan_reject" class="form-control" rows="3" required placeholder="Contoh: Bukti transfer tidak valid atau jadwal dokter telah penuh."></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-danger">Tolak Booking</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function openRejectModal(id, kode) {
    document.getElementById('rejectKode').textContent = kode;
    document.getElementById('rejectForm').action = '/health/bookings/' + id + '/update-status';
    $('#rejectBookingModal').modal('show');
}
</script>
@endsection
