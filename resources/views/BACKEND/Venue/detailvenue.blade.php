@extends('BACKEND.Layout.admin')

@section('content')
<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Detail Venue</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.venue.list') }}">Data Venue</a></li>
            <li class="breadcrumb-item active">Detail</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
              <h5 class="mb-0">Informasi Venue</h5>
              <div>
                <a href="{{ route('admin.venue.edit', $venue->id) }}" class="btn btn-warning btn-sm">
                  <i class="fas fa-edit"></i> Edit
                </a>
                <a href="{{ route('admin.venue.list') }}" class="btn btn-secondary btn-sm">
                  <i class="fas fa-arrow-left"></i> Kembali
                </a>
              </div>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-4 text-center mb-4">
                  @if($venue->logo)
                    <img src="{{ asset('storage/' . $venue->logo) }}" alt="{{ $venue->namavenue }}" class="img-fluid rounded" style="max-height: 300px;">
                  @else
                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 300px;">
                      <i class="fas fa-image fa-5x text-muted"></i>
                    </div>
                  @endif
                </div>
                <div class="col-md-8">
                  <table class="table table-borderless">
                    <tr>
                      <td width="30%"><strong>Nama Venue:</strong></td>
                      <td>{{ $venue->namavenue }}</td>
                    </tr>
                    <tr>
                      <td><strong>Pemilik:</strong></td>
                      <td>{{ $venue->user->name ?? '-' }}</td>
                    </tr>
                    <tr>
                      <td><strong>Email Pemilik:</strong></td>
                      <td>{{ $venue->user->email ?? '-' }}</td>
                    </tr>
                    <tr>
                      <td><strong>Provinsi:</strong></td>
                      <td>{{ $venue->provinsi }}</td>
                    </tr>
                    <tr>
                      <td><strong>Kota:</strong></td>
                      <td>{{ $venue->kota }}</td>
                    </tr>
                    <tr>
                      <td><strong>Kategori:</strong></td>
                      <td>
                        @php
                          $kategoriList = is_array($venue->kategori) ? $venue->kategori : ($venue->kategori ? [$venue->kategori] : []);
                        @endphp
                        @if(!empty($kategoriList))
                          @foreach($kategoriList as $kat)
                            <span class="badge badge-primary mr-1">{{ $kat }}</span>
                          @endforeach
                        @else
                          -
                        @endif
                      </td>
                    </tr>
                    <tr>
                      <td><strong>Status:</strong></td>
                      <td>
                        @if($venue->syarat_disetujui)
                          <span class="badge badge-success">Disetujui</span>
                        @else
                          <span class="badge badge-warning">Menunggu Verifikasi</span>
                        @endif
                      </td>
                    </tr>
                    <tr>
                      <td><strong>Tanggal Dibuat:</strong></td>
                      <td>{{ $venue->created_at->format('d M Y H:i') }}</td>
                    </tr>
                  </table>
                </div>
              </div>

              @if($venue->detail || $venue->aturan || $venue->video_review || $venue->lokasi)
              <hr>
              <h6 class="text-muted mb-3">Informasi Tambahan</h6>
              <div class="row">
                @if($venue->video_review)
                <div class="col-md-6 mb-3">
                  <strong>Video Review:</strong><br>
                  <a href="{{ $venue->video_review }}" target="_blank" class="btn btn-sm btn-danger">
                    <i class="fab fa-youtube"></i> Lihat di YouTube
                  </a>
                </div>
                @endif
                @if($venue->lokasi)
                <div class="col-md-6 mb-3">
                  <strong>Lokasi (Google Maps):</strong><br>
                  <a href="{{ $venue->lokasi }}" target="_blank" class="btn btn-sm btn-primary">
                    <i class="fas fa-map-marker-alt"></i> Lihat di Maps
                  </a>
                </div>
                @endif
                @if($venue->detail)
                <div class="col-md-12 mb-3">
                  <strong>Detail:</strong>
                  <div class="border rounded p-3 bg-light">
                    {!! $venue->detail !!}
                  </div>
                </div>
                @endif
                @if($venue->aturan)
                <div class="col-md-12 mb-3">
                  <strong>Aturan:</strong>
                  <div class="border rounded p-3 bg-light">
                    {!! $venue->aturan !!}
                  </div>
                </div>
                @endif
              </div>
              @endif

              @if(!empty($fasilitas))
              <hr>
              <h6 class="text-muted mb-3">Fasilitas</h6>
              <div class="row">
                @foreach($fasilitas as $fas)
                <div class="col-md-3 mb-2">
                  <span class="badge badge-secondary p-2"><i class="fas fa-check mr-1"></i>{{ $fas }}</span>
                </div>
                @endforeach
              </div>
              @endif

              @if($venue->lapangans->count() > 0)
              <hr>
              <h6 class="text-muted mb-3">Lapangan ({{ $venue->lapangans->count() }})</h6>
              <div class="table-responsive">
                <table class="table table-sm table-bordered">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Nama Lapangan</th>
                      <th>Tanggal Dibuat</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($venue->lapangans as $index => $lapangan)
                    <tr>
                      <td>{{ $index + 1 }}</td>
                      <td>{{ $lapangan->nama }}</td>
                      <td>{{ $lapangan->created_at->format('d M Y') }}</td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
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

