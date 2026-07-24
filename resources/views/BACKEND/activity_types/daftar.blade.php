@extends('BACKEND.Layout.admin')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Daftar Aktivitas</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Daftar Aktivitas</li>
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
            <a href="{{ route('activity-types.daftar', ['status' => 'pending']) }}" class="small-box-footer">
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
            <a href="{{ route('activity-types.daftar', ['status' => 'approved']) }}" class="small-box-footer">
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
            <a href="{{ route('activity-types.daftar', ['status' => 'rejected']) }}" class="small-box-footer">
              Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="small-box bg-info">
            <div class="inner">
              <h3>{{ $countAll ?? 0 }}</h3>
              <p>Total Aktivitas</p>
            </div>
            <div class="icon">
              <i class="fas fa-list"></i>
            </div>
            <a href="{{ route('activity-types.daftar', ['status' => 'all']) }}" class="small-box-footer">
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
                  Data Aktivitas Menunggu Verifikasi
                @elseif($status == 'approved')
                  Data Aktivitas Sudah Diverifikasi
                @elseif($status == 'rejected')
                  Data Aktivitas Ditolak
                @else
                  Semua Data Aktivitas
                @endif
              </h3>
            </div>
            <div class="col-md-6">
              <div class="row justify-content-end">
                <div class="col-auto">
                  <!-- Tab Filter Status -->
                  <div class="btn-group mb-2" role="group">
                    <a href="{{ route('activity-types.daftar', ['status' => 'pending']) }}" 
                       class="btn btn-sm {{ $status == 'pending' ? 'btn-warning' : 'btn-outline-warning' }}">
                      <i class="fas fa-clock"></i> Pending ({{ $countPending ?? 0 }})
                    </a>
                    <a href="{{ route('activity-types.daftar', ['status' => 'approved']) }}" 
                       class="btn btn-sm {{ $status == 'approved' ? 'btn-success' : 'btn-outline-success' }}">
                      <i class="fas fa-check"></i> Approved ({{ $countApproved ?? 0 }})
                    </a>
                    <a href="{{ route('activity-types.daftar', ['status' => 'rejected']) }}" 
                       class="btn btn-sm {{ $status == 'rejected' ? 'btn-danger' : 'btn-outline-danger' }}">
                      <i class="fas fa-times"></i> Rejected ({{ $countRejected ?? 0 }})
                    </a>
                    <a href="{{ route('activity-types.daftar', ['status' => 'all']) }}" 
                       class="btn btn-sm {{ $status == 'all' ? 'btn-info' : 'btn-outline-info' }}">
                      <i class="fas fa-list"></i> Semua
                    </a>
                  </div>
                </div>
                <div class="col-auto">
                  <form action="{{ route('activity-types.daftar') }}" method="GET" class="form-inline">
                    <input type="hidden" name="status" value="{{ $status }}">
                    <div class="input-group">
                      <input type="search" name="search" class="form-control form-control-sm" placeholder="Cari aktivitas..." value="{{ request('search') }}">
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
                <th>Nama Aktivitas</th>
                <th>Jenis</th>
                <th>Kategori</th>
                <th>Lokasi</th>
                <th>Dibuat Oleh</th>
                <th>Tanggal Dibuat</th>
                <th>Status</th>
                <th class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($activities as $index => $activity)
              <tr>
                <td>{{ ($activities->currentPage() - 1) * $activities->perPage() + $index + 1 }}</td>
                <td>{{ $activity->nama }}</td>
                <td>
                  @if($activity->activityType)
                    @if($activity->activityType->name == 'open-class')
                      <span class="badge badge-success">{{ $activity->activityType->title }}</span>
                    @elseif($activity->activityType->name == 'klub')
                      <span class="badge badge-warning">{{ $activity->activityType->title }}</span>
                    @elseif($activity->activityType->name == 'event')
                      <span class="badge badge-primary">{{ $activity->activityType->title }}</span>
                    @endif
                  @else
                    <span class="badge badge-secondary">{{ ucfirst($activity->jenis) }}</span>
                  @endif
                </td>
                <td>{{ $activity->kategori }}</td>
                <td>{{ $activity->lokasi ?? '-' }}</td>
                <td>
                  @if($activity->user)
                    User: {{ $activity->user->name }}
                  @elseif($activity->pemilik)
                    Pemilik: {{ $activity->pemilik->name }}
                  @else
                    -
                  @endif
                </td>
                <td>{{ $activity->created_at->format('d M Y') }}</td>
                <td>
                  @if($activity->status == 'pending')
                    <span class="badge badge-warning">Menunggu Verifikasi</span>
                  @elseif($activity->status == 'approved')
                    <span class="badge badge-success">Disetujui</span>
                    @if($activity->verified_at)
                      <br><small class="text-muted">{{ $activity->verified_at->format('d M Y H:i') }}</small>
                    @endif
                  @else
                    <span class="badge badge-danger">Ditolak</span>
                  @endif
                </td>
                <td class="text-center">
                  <div class="btn-group" role="group">
                    <a href="{{ route('activities.show', $activity->id) }}" class="btn btn-sm btn-info" title="Detail">
                      <i class="fas fa-eye"></i>
                    </a>
                    @if($activity->status == 'pending')
                      <form action="{{ route('activities.approve', $activity->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui aktivitas ini?');">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-sm btn-success" title="Setujui">
                          <i class="fas fa-check"></i>
                        </button>
                      </form>
                      <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#rejectModal{{ $activity->id }}" title="Tolak">
                        <i class="fas fa-times"></i>
                      </button>
                    @elseif($activity->status == 'approved')
                      <span class="badge badge-success">
                        <i class="fas fa-check-circle"></i> Sudah Disetujui
                      </span>
                    @elseif($activity->status == 'rejected')
                      @if($activity->alasan_reject)
                        <button type="button" class="btn btn-sm btn-secondary" data-toggle="tooltip" data-placement="top" title="Alasan: {{ $activity->alasan_reject }}">
                          <i class="fas fa-info-circle"></i> Lihat Alasan
                        </button>
                      @endif
                    @endif
                    <form action="{{ route('activities.destroy', $activity->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus aktivitas ini? Tindakan ini tidak dapat dibatalkan.');">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                        <i class="fas fa-trash"></i>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>

              <!-- Modal Reject -->
              @if($activity->status == 'pending')
              <div class="modal fade" id="rejectModal{{ $activity->id }}" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Tolak Aktivitas</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <form action="{{ route('activities.reject', $activity->id) }}" method="POST">
                      @csrf
                      @method('PUT')
                      <div class="modal-body">
                        <div class="form-group">
                          <label>Alasan Penolakan <span class="text-danger">*</span></label>
                          <textarea name="alasan_reject" class="form-control" rows="4" required placeholder="Masukkan alasan penolakan..."></textarea>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Tolak Aktivitas</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
              @endif
              @empty
              <tr>
                <td colspan="9" class="text-center py-4">
                  @if($status == 'pending')
                    Tidak ada aktivitas yang menunggu verifikasi.
                  @elseif($status == 'approved')
                    Tidak ada aktivitas yang sudah diverifikasi.
                  @elseif($status == 'rejected')
                    Tidak ada aktivitas yang ditolak.
                  @else
                    Tidak ada data aktivitas.
                  @endif
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        <!-- /.card-body -->
        @if($activities->hasPages())
        <div class="card-footer">
          <div class="d-flex justify-content-center">
            {{ $activities->appends(request()->query())->links() }}
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
