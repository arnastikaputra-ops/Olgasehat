@extends('BACKEND.Layout.admin')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Verifikasi Klinik</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('health.clinics.index') }}">Klinik</a></li>
            <li class="breadcrumb-item active">Verifikasi</li>
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

      <!-- Statistik Cards -->
      <div class="row mb-3">
        <div class="col-lg-3 col-6">
          <div class="small-box bg-warning">
            <div class="inner">
              <h3>{{ $countPending }}</h3>
              <p>Menunggu Verifikasi</p>
            </div>
            <div class="icon">
              <i class="fas fa-clock"></i>
            </div>
            <a href="{{ route('health.clinics.index', ['status' => 'pending']) }}" class="small-box-footer">
              Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="small-box bg-success">
            <div class="inner">
              <h3>{{ $countApproved }}</h3>
              <p>Sudah Diverifikasi</p>
            </div>
            <div class="icon">
              <i class="fas fa-check-circle"></i>
            </div>
            <a href="{{ route('health.clinics.index', ['status' => 'approved']) }}" class="small-box-footer">
              Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="small-box bg-danger">
            <div class="inner">
              <h3>{{ $countRejected }}</h3>
              <p>Ditolak</p>
            </div>
            <div class="icon">
              <i class="fas fa-times-circle"></i>
            </div>
            <a href="{{ route('health.clinics.index', ['status' => 'rejected']) }}" class="small-box-footer">
              Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="small-box bg-info">
            <div class="inner">
              <h3>{{ $countPending + $countApproved + $countRejected }}</h3>
              <p>Total Klinik</p>
            </div>
            <div class="icon">
              <i class="fas fa-hospital"></i>
            </div>
            <a href="{{ route('health.clinics.index') }}" class="small-box-footer">
              Lihat Semua <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
      </div>

      <!-- Filter Card -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Filter & Pencarian</h3>
        </div>
        <div class="card-body">
          <form method="GET" action="{{ route('health.clinics.index') }}">
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label>Status</label>
                  <select name="status" class="form-control">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                  </select>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label>Tipe</label>
                  <select name="tipe" class="form-control">
                    <option value="">Semua Tipe</option>
                    <option value="klinik" {{ request('tipe') == 'klinik' ? 'selected' : '' }}>Klinik</option>
                    <option value="layanan" {{ request('tipe') == 'layanan' ? 'selected' : '' }}>Layanan Kesehatan</option>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Pencarian</label>
                  <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Nama, Alamat, Kota">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <button type="submit" class="btn btn-primary">
                  <i class="fas fa-search"></i> Filter
                </button>
                <a href="{{ route('health.clinics.index') }}" class="btn btn-default">
                  <i class="fas fa-redo"></i> Reset
                </a>
              </div>
            </div>
          </form>
        </div>
      </div>

      <!-- Table -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Daftar Klinik</h3>
        </div>
        <div class="card-body table-responsive p-0">
          <table class="table table-hover text-nowrap">
            <thead>
              <tr>
                <th>ID</th>
                <th>Nama Klinik</th>
                <th>Tipe</th>
                <th>Pemilik</th>
                <th>Kota</th>
                <th>Status</th>
                <th>Tanggal Daftar</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($clinics as $clinic)
              <tr>
                <td>{{ $clinic->id }}</td>
                <td>
                  <strong>{{ $clinic->nama }}</strong>
                  @if($clinic->motto)
                    <br><small class="text-muted">{{ $clinic->motto }}</small>
                  @endif
                </td>
                <td>
                  <span class="badge badge-{{ $clinic->tipe == 'klinik' ? 'primary' : 'info' }}">
                    {{ ucfirst($clinic->tipe) }}
                  </span>
                </td>
                <td>{{ $clinic->user->name ?? '-' }}</td>
                <td>{{ $clinic->kota ?? '-' }}</td>
                <td>
                  @if($clinic->status == 'approved')
                    <span class="badge badge-success">Disetujui</span>
                  @elseif($clinic->status == 'pending')
                    <span class="badge badge-warning">Menunggu Verifikasi</span>
                  @else
                    <span class="badge badge-danger">Ditolak</span>
                  @endif
                </td>
                <td>{{ $clinic->created_at->format('d M Y') }}</td>
                <td>
                  <a href="{{ route('health.clinics.show', $clinic->id) }}" class="btn btn-sm btn-info">
                    <i class="fas fa-eye"></i> Detail
                  </a>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="8" class="text-center">Tidak ada data klinik</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        <div class="card-footer">
          {{ $clinics->links() }}
        </div>
      </div>
    </div>
  </section>
</div>
@endsection

