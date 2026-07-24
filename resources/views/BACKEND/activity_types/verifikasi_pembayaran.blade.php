@extends('BACKEND.Layout.admin')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Verifikasi Pembayaran Event</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Verifikasi Pembayaran</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

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

      <!-- Statistik Cards -->
      <div class="row mb-3">
        <div class="col-lg-3 col-6">
          <div class="small-box bg-warning">
            <div class="inner">
              <h3>{{ $countPending ?? 0 }}</h3>
              <p>Menunggu Verifikasi</p>
            </div>
            <div class="icon">
              <i class="fas fa-clock"></i>
            </div>
            <a href="{{ route('activities.verifikasi-pembayaran', ['status' => 'pending']) }}" class="small-box-footer">
              Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="small-box bg-success">
            <div class="inner">
              <h3>{{ $countApproved ?? 0 }}</h3>
              <p>Sudah Diverifikasi</p>
            </div>
            <div class="icon">
              <i class="fas fa-check-circle"></i>
            </div>
            <a href="{{ route('activities.verifikasi-pembayaran', ['status' => 'approved']) }}" class="small-box-footer">
              Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="small-box bg-danger">
            <div class="inner">
              <h3>{{ $countRejected ?? 0 }}</h3>
              <p>Ditolak</p>
            </div>
            <div class="icon">
              <i class="fas fa-times-circle"></i>
            </div>
            <a href="{{ route('activities.verifikasi-pembayaran', ['status' => 'rejected']) }}" class="small-box-footer">
              Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="small-box bg-info">
            <div class="inner">
              <h3>{{ $countAll ?? 0 }}</h3>
              <p>Total Pendaftaran</p>
            </div>
            <div class="icon">
              <i class="fas fa-list"></i>
            </div>
            <a href="{{ route('activities.verifikasi-pembayaran', ['status' => 'all']) }}" class="small-box-footer">
              Lihat Semua <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
      </div>

      <!-- Table -->
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-md-6">
              <h3 class="card-title">
                @if($status == 'pending')
                  Data Pembayaran Menunggu Verifikasi
                @elseif($status == 'approved')
                  Data Pembayaran Sudah Diverifikasi
                @elseif($status == 'rejected')
                  Data Pembayaran Ditolak
                @else
                  Semua Data Pembayaran
                @endif
              </h3>
            </div>
            <div class="col-md-6">
              <div class="row justify-content-end">
                <div class="col-auto">
                  <!-- Tab Filter Status -->
                  <div class="btn-group mb-2" role="group">
                    <a href="{{ route('activities.verifikasi-pembayaran', ['status' => 'pending']) }}" 
                       class="btn btn-sm {{ $status == 'pending' ? 'btn-warning' : 'btn-outline-warning' }}">
                      <i class="fas fa-clock"></i> Pending ({{ $countPending ?? 0 }})
                    </a>
                    <a href="{{ route('activities.verifikasi-pembayaran', ['status' => 'approved']) }}" 
                       class="btn btn-sm {{ $status == 'approved' ? 'btn-success' : 'btn-outline-success' }}">
                      <i class="fas fa-check"></i> Approved ({{ $countApproved ?? 0 }})
                    </a>
                    <a href="{{ route('activities.verifikasi-pembayaran', ['status' => 'rejected']) }}" 
                       class="btn btn-sm {{ $status == 'rejected' ? 'btn-danger' : 'btn-outline-danger' }}">
                      <i class="fas fa-times"></i> Rejected ({{ $countRejected ?? 0 }})
                    </a>
                    <a href="{{ route('activities.verifikasi-pembayaran', ['status' => 'all']) }}" 
                       class="btn btn-sm {{ $status == 'all' ? 'btn-info' : 'btn-outline-info' }}">
                      <i class="fas fa-list"></i> Semua
                    </a>
                  </div>
                </div>
                <div class="col-auto">
                  <form action="{{ route('activities.verifikasi-pembayaran') }}" method="GET" class="form-inline">
                    <input type="hidden" name="status" value="{{ $status }}">
                    <div class="input-group">
                      <input type="search" name="search" class="form-control form-control-sm" placeholder="Cari peserta..." value="{{ request('search') }}">
                      <div class="input-group-append">
                        <button type="submit" class="btn btn-primary btn-sm">
                          <i class="fas fa-search"></i>
                        </button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body table-responsive p-0">
          <table class="table table-hover text-nowrap">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama Peserta</th>
                <th>Event</th>
                <th>Kategori</th>
                <th>User</th>
                <th>Biaya</th>
                <th>Bukti Pembayaran</th>
                <th>Tanggal Daftar</th>
                <th>Status</th>
                <th class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($participants as $index => $participant)
              <tr>
                <td>{{ ($participants->currentPage() - 1) * $participants->perPage() + $index + 1 }}</td>
                <td><strong>{{ $participant->nama_peserta }}</strong></td>
                <td>
                  <strong>{{ $participant->activity->nama }}</strong><br>
                  <small class="text-muted">{{ $participant->activity->lokasi ?? '-' }}</small>
                </td>
                <td>
                  <span class="badge badge-info">{{ $participant->activity->kategori }}</span>
                </td>
                <td>
                  @if($participant->user)
                    {{ $participant->user->name }}<br>
                    <small class="text-muted">{{ $participant->user->email }}</small>
                  @else
                    -
                  @endif
                </td>
                <td>
                  @if($participant->activity->biaya_bergabung == 'gratis')
                    <span class="badge badge-success">Gratis</span>
                  @else
                    <strong>Rp {{ number_format($participant->activity->harga, 0, ',', '.') }}</strong>
                  @endif
                </td>
                <td>
                  @if($participant->bukti_pembayaran)
                    <a href="{{ asset('bukti_pembayaran/'.$participant->bukti_pembayaran) }}" target="_blank" class="btn btn-sm btn-info">
                      <i class="fas fa-eye"></i> Lihat Bukti
                    </a>
                  @else
                    <span class="text-muted">-</span>
                  @endif
                </td>
                <td>{{ $participant->created_at->format('d M Y H:i') }}</td>
                <td>
                  @if($participant->status == 'pending')
                    <span class="badge badge-warning">Menunggu Verifikasi</span>
                  @elseif($participant->status == 'approved')
                    <span class="badge badge-success">Disetujui</span>
                  @else
                    <span class="badge badge-danger">Ditolak</span>
                    @if($participant->catatan)
                      <br><small class="text-muted" data-toggle="tooltip" data-placement="top" title="{{ $participant->catatan }}">
                        <i class="fas fa-info-circle"></i> Ada catatan
                      </small>
                    @endif
                  @endif
                </td>
                <td class="text-center">
                  <div class="btn-group" role="group">
                    @if($participant->status == 'pending')
                      <form action="{{ route('activities.approve-pembayaran', $participant->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui pembayaran ini?');">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-sm btn-success" title="Setujui Pembayaran">
                          <i class="fas fa-check"></i> Setujui
                        </button>
                      </form>
                      <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#rejectModal{{ $participant->id }}" title="Tolak Pembayaran">
                        <i class="fas fa-times"></i> Tolak
                      </button>
                    @elseif($participant->status == 'approved')
                      <span class="badge badge-success">
                        <i class="fas fa-check-circle"></i> Sudah Disetujui
                      </span>
                    @elseif($participant->status == 'rejected')
                      @if($participant->catatan)
                        <button type="button" class="btn btn-sm btn-secondary" data-toggle="tooltip" data-placement="top" title="Alasan: {{ $participant->catatan }}">
                          <i class="fas fa-info-circle"></i> Lihat Alasan
                        </button>
                      @endif
                    @endif
                  </div>
                </td>
              </tr>

              <!-- Modal Reject -->
              @if($participant->status == 'pending')
              <div class="modal fade" id="rejectModal{{ $participant->id }}" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Tolak Pembayaran</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <form action="{{ route('activities.reject-pembayaran', $participant->id) }}" method="POST">
                      @csrf
                      @method('PUT')
                      <div class="modal-body">
                        <div class="form-group">
                          <label>Alasan Penolakan <span class="text-danger">*</span></label>
                          <textarea name="alasan_reject" class="form-control" rows="4" required placeholder="Masukkan alasan penolakan pembayaran..."></textarea>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Tolak Pembayaran</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
              @endif
              @empty
              <tr>
                <td colspan="10" class="text-center py-4">
                  @if($status == 'pending')
                    Tidak ada pembayaran yang menunggu verifikasi.
                  @elseif($status == 'approved')
                    Tidak ada pembayaran yang sudah diverifikasi.
                  @elseif($status == 'rejected')
                    Tidak ada pembayaran yang ditolak.
                  @else
                    Tidak ada data pembayaran.
                  @endif
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        <!-- /.card-body -->
        @if($participants->hasPages())
        <div class="card-footer">
          <div class="d-flex justify-content-center">
            {{ $participants->appends(request()->query())->links() }}
          </div>
        </div>
        @endif
      </div>
      <!-- /.card -->
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script>
  // Initialize tooltips
  $(function () {
    $('[data-toggle="tooltip"]').tooltip()
  })
</script>
@endsection

