@extends('BACKEND.Layout.admin')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Daftar Pengelola Kesehatan</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Daftar Pengelola Kesehatan</li>
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
            <a href="{{ route('tempat-sehat.index', ['status' => 'pending']) }}" class="small-box-footer">
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
            <a href="{{ route('tempat-sehat.index', ['status' => 'approved']) }}" class="small-box-footer">
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
            <a href="{{ route('tempat-sehat.index', ['status' => 'rejected']) }}" class="small-box-footer">
              Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="small-box bg-info">
            <div class="inner">
              <h3>{{ $countAll ?? 0 }}</h3>
              <p>Total Pengelola Kesehatan</p>
            </div>
            <div class="icon">
              <i class="fas fa-list"></i>
            </div>
            <a href="{{ route('tempat-sehat.index', ['status' => 'all']) }}" class="small-box-footer">
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
                  Data Pengelola Kesehatan Menunggu Verifikasi
                @elseif($status == 'approved')
                  Data Pengelola Kesehatan Sudah Diverifikasi
                @elseif($status == 'rejected')
                  Data Pengelola Kesehatan Ditolak
                @else
                  Semua Data Pengelola Kesehatan
                @endif
              </h3>
            </div>
            <div class="col-md-6">
              <div class="row justify-content-end">
                <div class="col-auto">
                  <!-- Tab Filter Status -->
                  <div class="btn-group mb-2" role="group">
                    <a href="{{ route('tempat-sehat.index', ['status' => 'pending']) }}" 
                       class="btn btn-sm {{ $status == 'pending' ? 'btn-warning' : 'btn-outline-warning' }}">
                      <i class="fas fa-clock"></i> Pending ({{ $countPending ?? 0 }})
                    </a>
                    <a href="{{ route('tempat-sehat.index', ['status' => 'approved']) }}" 
                       class="btn btn-sm {{ $status == 'approved' ? 'btn-success' : 'btn-outline-success' }}">
                      <i class="fas fa-check"></i> Approved ({{ $countApproved ?? 0 }})
                    </a>
                    <a href="{{ route('tempat-sehat.index', ['status' => 'rejected']) }}" 
                       class="btn btn-sm {{ $status == 'rejected' ? 'btn-danger' : 'btn-outline-danger' }}">
                      <i class="fas fa-times"></i> Rejected ({{ $countRejected ?? 0 }})
                    </a>
                    <a href="{{ route('tempat-sehat.index', ['status' => 'all']) }}" 
                       class="btn btn-sm {{ $status == 'all' ? 'btn-info' : 'btn-outline-info' }}">
                      <i class="fas fa-list"></i> Semua
                    </a>
                  </div>
                </div>
                <div class="col-auto">
                  <form action="{{ route('tempat-sehat.index') }}" method="GET" class="form-inline">
                    <input type="hidden" name="status" value="{{ $status }}">
                    <div class="input-group">
                      <input type="search" name="search" class="form-control form-control-sm" placeholder="Cari pengelola kesehatan..." value="{{ request('search') }}">
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
                <th>Nama Fasilitas</th>
                <th>Nama Pengelola</th>
                <th>Email</th>
                <th>Tipe Fasilitas</th>
                <th>Kontak</th>
                <th>Tanggal Dibuat</th>
                <th>Status</th>
                <th class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($mitras as $index => $mitra)
              <tr>
                <td>{{ ($mitras->currentPage() - 1) * $mitras->perPage() + $index + 1 }}</td>
                <td>{{ $mitra->nama_bisnis }}</td>
                <td>{{ $mitra->nama_anda }}</td>
                <td>{{ $mitra->email_bisnis }}</td>
                <td>{{ $mitra->tipe_venue }}</td>
                <td>{{ $mitra->kontak_bisnis ?? '-' }}</td>
                <td>{{ $mitra->created_at->format('d M Y') }}</td>
                <td>
                  @if($mitra->status == 'pending')
                    <span class="badge badge-warning">Menunggu Verifikasi</span>
                  @elseif($mitra->status == 'approved')
                    <span class="badge badge-success">Disetujui</span>
                  @elseif($mitra->status == 'rejected')
                    <span class="badge badge-danger">Ditolak</span>
                  @else
                    <span class="badge badge-secondary">{{ ucfirst($mitra->status) }}</span>
                  @endif
                </td>
                <td class="text-center">
                  <a href="{{ route('tempat-sehat.show', $mitra->id) }}" class="btn btn-sm btn-info" title="Detail">
                    <i class="fas fa-eye"></i>
                  </a>
                  @if($mitra->status == 'pending')
                    <form action="{{ route('tempat-sehat.verify', $mitra->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui pengelola kesehatan ini?');">
                      @csrf
                      @method('PUT')
                      <button type="submit" class="btn btn-sm btn-success" title="Setujui">
                        <i class="fas fa-check"></i>
                      </button>
                    </form>
                  @elseif($mitra->status == 'approved')
                    <span class="badge badge-success">
                      <i class="fas fa-check-circle"></i> Sudah Disetujui
                    </span>
                  @endif
                  <form action="{{ route('tempat-sehat.destroy', $mitra->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengelola kesehatan ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                      <i class="fas fa-trash"></i>
                    </button>
                  </form>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="9" class="text-center py-4">
                  @if($status == 'pending')
                    Tidak ada pengelola kesehatan yang menunggu verifikasi.
                  @elseif($status == 'approved')
                    Tidak ada pengelola kesehatan yang sudah diverifikasi.
                  @elseif($status == 'rejected')
                    Tidak ada pengelola kesehatan yang ditolak.
                  @else
                    Tidak ada data pengelola kesehatan.
                  @endif
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        <!-- /.card-body -->
        @if($mitras->hasPages())
        <div class="card-footer">
          <div class="d-flex justify-content-center">
            {{ $mitras->appends(request()->query())->links() }}
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
@endsection

