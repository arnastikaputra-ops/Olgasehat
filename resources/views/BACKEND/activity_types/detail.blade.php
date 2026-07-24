@extends('BACKEND.Layout.admin')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Detail Aktivitas</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('activity-types.daftar') }}">Daftar Aktivitas</a></li>
            <li class="breadcrumb-item active">Detail</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-8">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Informasi Aktivitas</h3>
            </div>
            <div class="card-body">
              <table class="table table-bordered">
                <tr>
                  <th width="30%">Nama Aktivitas</th>
                  <td>{{ $activity->nama }}</td>
                </tr>
                <tr>
                  <th>Jenis</th>
                  <td>
                    @if($activity->activityType)
                      <span class="badge badge-info">{{ $activity->activityType->title }}</span>
                    @else
                      <span class="badge badge-secondary">{{ ucfirst($activity->jenis) }}</span>
                    @endif
                  </td>
                </tr>
                <tr>
                  <th>Kategori</th>
                  <td>{{ $activity->kategori }}</td>
                </tr>
                <tr>
                  <th>Lokasi</th>
                  <td>{{ $activity->lokasi ?? '-' }}</td>
                </tr>
                <tr>
                  <th>Biaya Bergabung</th>
                  <td>
                    @if($activity->biaya_bergabung == 'gratis')
                      <span class="badge badge-success">Gratis</span>
                    @else
                      <span class="badge badge-warning">Berbayar</span>
                    @endif
                  </td>
                </tr>
                <tr>
                  <th>Deskripsi</th>
                  <td>{{ $activity->deskripsi }}</td>
                </tr>
                <tr>
                  <th>Link Kontak</th>
                  <td>
                    @if($activity->link_kontak)
                      <a href="{{ $activity->link_kontak }}" target="_blank" class="text-blue-600">{{ $activity->link_kontak }}</a>
                    @else
                      -
                    @endif
                  </td>
                </tr>
                <tr>
                  <th>Dibuat Oleh</th>
                  <td>
                    @if($activity->user)
                      User: {{ $activity->user->name }} ({{ $activity->user->email }})
                    @elseif($activity->pemilik)
                      Pemilik: {{ $activity->pemilik->name }} ({{ $activity->pemilik->email }})
                    @else
                      -
                    @endif
                  </td>
                </tr>
                <tr>
                  <th>Tanggal Dibuat</th>
                  <td>{{ $activity->created_at->format('d M Y H:i') }}</td>
                </tr>
                <tr>
                  <th>Status</th>
                  <td>
                    @if($activity->status == 'pending')
                      <span class="badge badge-warning">Menunggu Verifikasi</span>
                    @elseif($activity->status == 'approved')
                      <span class="badge badge-success">Disetujui</span>
                    @else
                      <span class="badge badge-danger">Ditolak</span>
                    @endif
                  </td>
                </tr>
              </table>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Banner</h3>
            </div>
            <div class="card-body">
              @if($activity->banner)
                <img src="{{ asset('fotoaktivitas/'.$activity->banner) }}" alt="{{ $activity->nama }}" class="img-fluid">
              @else
                <p class="text-muted">Tidak ada banner</p>
              @endif
            </div>
          </div>

          <div class="card mt-3">
            <div class="card-header">
              <h3 class="card-title">Aksi</h3>
            </div>
            <div class="card-body">
              @if($activity->status == 'pending')
                <form action="{{ route('activities.approve', $activity->id) }}" method="POST" class="mb-2" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui aktivitas ini?');">
                  @csrf
                  @method('PUT')
                  <button type="submit" class="btn btn-success btn-block">
                    <i class="fas fa-check"></i> Setujui
                  </button>
                </form>
                <button type="button" class="btn btn-danger btn-block" data-toggle="modal" data-target="#rejectModal">
                  <i class="fas fa-times"></i> Tolak
                </button>
              @elseif($activity->status == 'approved')
                <p class="text-success"><i class="fas fa-check-circle"></i> Aktivitas sudah disetujui</p>
                <p class="text-muted small">Disetujui pada: {{ $activity->verified_at ? $activity->verified_at->format('d M Y H:i') : '-' }}</p>
              @else
                <p class="text-danger"><i class="fas fa-times-circle"></i> Aktivitas ditolak</p>
                @if($activity->alasan_reject)
                  <p class="text-muted small"><strong>Alasan:</strong> {{ $activity->alasan_reject }}</p>
                @endif
              @endif
              <a href="{{ route('activity-types.daftar') }}" class="btn btn-secondary btn-block mt-2">
                <i class="fas fa-arrow-left"></i> Kembali
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<!-- Modal Reject -->
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog">
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
@endsection

