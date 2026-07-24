@extends('BACKEND.Layout.admin')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Daftar Venue</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Daftar Venue</li>
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
            <a href="{{ route('admin.venue.list', ['status' => 'pending']) }}" class="small-box-footer">
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
            <a href="{{ route('admin.venue.list', ['status' => 'approved']) }}" class="small-box-footer">
              Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="small-box bg-info">
            <div class="inner">
              <h3>{{ $countAll ?? 0 }}</h3>
              <p>Total Venue</p>
            </div>
            <div class="icon">
              <i class="fas fa-list"></i>
            </div>
            <a href="{{ route('admin.venue.list', ['status' => 'all']) }}" class="small-box-footer">
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
                  Data Venue Menunggu Verifikasi
                @elseif($status == 'approved')
                  Data Venue Sudah Diverifikasi
                @else
                  Semua Data Venue
                @endif
              </h3>
            </div>
            <div class="col-md-6">
              <div class="row justify-content-end">
                <div class="col-auto">
                  <!-- Tab Filter Status -->
                  <div class="btn-group mb-2" role="group">
                    <a href="{{ route('admin.venue.list', ['status' => 'pending']) }}" 
                       class="btn btn-sm {{ $status == 'pending' ? 'btn-warning' : 'btn-outline-warning' }}">
                      <i class="fas fa-clock"></i> Pending ({{ $countPending ?? 0 }})
                    </a>
                    <a href="{{ route('admin.venue.list', ['status' => 'approved']) }}" 
                       class="btn btn-sm {{ $status == 'approved' ? 'btn-success' : 'btn-outline-success' }}">
                      <i class="fas fa-check"></i> Approved ({{ $countApproved ?? 0 }})
                    </a>
                    <a href="{{ route('admin.venue.list', ['status' => 'all']) }}" 
                       class="btn btn-sm {{ $status == 'all' ? 'btn-info' : 'btn-outline-info' }}">
                      <i class="fas fa-list"></i> Semua
                    </a>
                  </div>
                </div>
                <div class="col-auto">
                  <form action="{{ route('admin.venue.list') }}" method="GET" class="form-inline">
                    <input type="hidden" name="status" value="{{ $status }}">
                    <div class="input-group">
                      <input type="search" name="search" class="form-control form-control-sm" placeholder="Cari venue..." value="{{ request('search') }}">
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
                <th>Nama Venue</th>
                <th>Pemilik</th>
                <th>Email</th>
                <th>Provinsi</th>
                <th>Kota</th>
                <th>Kategori</th>
                <th>Tanggal Dibuat</th>
                <th>Komisi Platform</th>
                <th>Status</th>
                <th class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($venues as $index => $venue)
              <tr>
                <td>{{ ($venues->currentPage() - 1) * $venues->perPage() + $index + 1 }}</td>
                <td>
                  <strong>{{ $venue->namavenue }}</strong>
                  @if($venue->lapangans->count() > 0)
                    <br><small class="text-muted">{{ $venue->lapangans->count() }} lapangan</small>
                  @endif
                </td>
                <td>{{ $venue->user->name ?? '-' }}</td>
                <td>{{ $venue->user->email ?? '-' }}</td>
                <td>{{ $venue->provinsi }}</td>
                <td>{{ $venue->kota }}</td>
                <td>
                  @php
                    $kategoriList = is_array($venue->kategori) ? $venue->kategori : ($venue->kategori ? [$venue->kategori] : []);
                    $kategoriDisplay = !empty($kategoriList) ? implode(', ', array_slice($kategoriList, 0, 2)) : '-';
                    if (count($kategoriList) > 2) {
                      $kategoriDisplay .= ' +' . (count($kategoriList) - 2);
                    }
                  @endphp
                  <small>{{ $kategoriDisplay }}</small>
                </td>
                <td>{{ $venue->created_at->format('d M Y') }}</td>
                <td>
                  @if(($venue->komisi_tipe ?? 'none') == 'percentage' && ($venue->komisi_nilai ?? 0) > 0)
                    <span class="badge badge-warning">{{ (float)$venue->komisi_nilai }}% Komisi</span>
                  @elseif(($venue->komisi_tipe ?? 'none') == 'fixed' && ($venue->komisi_nilai ?? 0) > 0)
                    <span class="badge badge-primary">Rp {{ number_format($venue->komisi_nilai, 0, ',', '.') }}</span>
                  @else
                    <span class="badge badge-success">100% Mitra (0% Komisi)</span>
                  @endif
                  <button class="btn btn-xs btn-outline-primary ml-1" data-toggle="modal" data-target="#komisiModal{{ $venue->id }}" title="Pengaturan Skema Komisi">
                    <i class="fas fa-cog"></i> Atur
                  </button>

                  <!-- Modal Pengaturan Komisi -->
                  <div class="modal fade" id="komisiModal{{ $venue->id }}" tabindex="-1" role="dialog" aria-labelledby="komisiModalLabel{{ $venue->id }}" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content text-left" style="white-space: normal;">
                        <form action="{{ route('admin.venue.update-commission', $venue->id) }}" method="POST">
                          @csrf
                          <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title font-weight-bold" id="komisiModalLabel{{ $venue->id }}">
                              <i class="fas fa-percentage mr-2"></i> Skema Komisi: {{ $venue->namavenue }}
                            </h5>
                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <div class="alert alert-info small">
                              <i class="fas fa-info-circle mr-1"></i> Pilih <strong>100% Untuk Mitra (0% Komisi)</strong> untuk mode sementara, atau tetapkan persentase komisi platform saat verifikasi persetujuan mitra.
                            </div>
                            
                            <div class="form-group text-left">
                              <label class="font-weight-bold">Skema Komisi Platform</label>
                              <select name="komisi_tipe" class="form-control">
                                <option value="none" {{ ($venue->komisi_tipe ?? 'none') == 'none' ? 'selected' : '' }}>Mode Sementara: 100% Untuk Mitra (0% Komisi)</option>
                                <option value="percentage" {{ ($venue->komisi_tipe ?? '') == 'percentage' ? 'selected' : '' }}>Potongan Persentase (%)</option>
                                <option value="fixed" {{ ($venue->komisi_tipe ?? '') == 'fixed' ? 'selected' : '' }}>Potongan Nominal Tetap (Rp)</option>
                              </select>
                            </div>

                            <div class="form-group text-left">
                              <label class="font-weight-bold">Nilai Komisi Platform (% atau Rp)</label>
                              <input type="number" step="0.01" min="0" name="komisi_nilai" class="form-control" value="{{ (float)($venue->komisi_nilai ?? 0) }}" placeholder="Contoh: 10 untuk 10%, atau 10000">
                              <small class="text-muted">Jika memilih persentase %, isikan angka persennya saja (misal: 10).</small>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary font-weight-bold">
                              <i class="fas fa-save mr-1"></i> Simpan Skema Komisi
                            </button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                </td>
                <td>
                  @if($venue->syarat_disetujui)
                    <span class="badge badge-success">Disetujui</span>
                  @else
                    <span class="badge badge-warning">Menunggu Verifikasi</span>
                  @endif
                </td>
                <td class="text-center">
                  <a href="{{ route('admin.venue.show', $venue->id) }}" class="btn btn-sm btn-info" title="Detail">
                    <i class="fas fa-eye"></i>
                  </a>
                  <a href="{{ route('admin.venue.edit', $venue->id) }}" class="btn btn-sm btn-warning" title="Edit">
                    <i class="fas fa-edit"></i>
                  </a>
                  @if(!$venue->syarat_disetujui)
                    <form action="{{ route('admin.venue.verify', $venue->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui venue ini? Venue akan muncul di frontend setelah disetujui.');">
                      @csrf
                      @method('PUT')
                      <button type="submit" class="btn btn-sm btn-success" title="Setujui">
                        <i class="fas fa-check"></i>
                      </button>
                    </form>
                  @else
                    <form action="{{ route('admin.venue.reject', $venue->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menolak venue ini? Venue tidak akan muncul di frontend setelah ditolak.');">
                      @csrf
                      @method('PUT')
                      <button type="submit" class="btn btn-sm btn-danger" title="Tolak">
                        <i class="fas fa-times"></i>
                      </button>
                    </form>
                  @endif
                  <form action="{{ route('admin.venue.delete', $venue->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus venue ini? Semua data terkait (lapangan, jadwal, dll) akan ikut terhapus.');">
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
                <td colspan="10" class="text-center py-4">
                  @if($status == 'pending')
                    Tidak ada venue yang menunggu verifikasi.
                  @elseif($status == 'approved')
                    Tidak ada venue yang sudah diverifikasi.
                  @else
                    Tidak ada data venue.
                  @endif
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        <!-- /.card-body -->
        @if($venues->hasPages())
        <div class="card-footer">
          <div class="d-flex justify-content-center">
            {{ $venues->appends(request()->query())->links() }}
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

