@extends('BACKEND.Layout.admin')

@section('content')
<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Edit Venue</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.venue.list') }}">Data Venue</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.venue.show', $venue->id) }}">Detail</a></li>
            <li class="breadcrumb-item active">Edit</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

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

      @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <ul class="mb-0">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      @endif

      <form action="{{ route('admin.venue.update', $venue->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="card">
          <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Informasi Venue</h5>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Nama Venue <span class="text-danger">*</span></label>
                  <input type="text" name="namavenue" class="form-control" value="{{ old('namavenue', $venue->namavenue) }}" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Status Verifikasi</label>
                  <select name="syarat_disetujui" class="form-control">
                    <option value="0" {{ !$venue->syarat_disetujui ? 'selected' : '' }}>Menunggu Verifikasi</option>
                    <option value="1" {{ $venue->syarat_disetujui ? 'selected' : '' }}>Disetujui</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="card p-3 bg-light border mb-3">
              <h6 class="font-weight-bold text-primary mb-3">
                <i class="fas fa-percentage mr-1"></i> Pengaturan Bagi Hasil / Komisi Platform (OlgaSehat)
              </h6>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group mb-0">
                    <label class="font-weight-bold">Tipe Bagi Hasil / Komisi</label>
                    <select name="komisi_tipe" class="form-control">
                      <option value="none" {{ old('komisi_tipe', $venue->komisi_tipe ?? 'none') == 'none' ? 'selected' : '' }}>Tanpa Bagi Hasil (0%)</option>
                      <option value="percentage" {{ old('komisi_tipe', $venue->komisi_tipe ?? '') == 'percentage' ? 'selected' : '' }}>Bagi Hasil Persentase (%)</option>
                      <option value="fixed" {{ old('komisi_tipe', $venue->komisi_tipe ?? '') == 'fixed' ? 'selected' : '' }}>Nominal Tetap (Rp)</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group mb-0">
                    <label class="font-weight-bold">Nilai Komisi Platform</label>
                    <input type="number" step="0.01" min="0" name="komisi_nilai" class="form-control" value="{{ old('komisi_nilai', (float)($venue->komisi_nilai ?? 0)) }}" placeholder="Contoh: 5 untuk 5% atau 5000 untuk Rp 5.000">
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Provinsi <span class="text-danger">*</span></label>
                  <input type="text" name="provinsi" class="form-control" value="{{ old('provinsi', $venue->provinsi) }}" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Kota <span class="text-danger">*</span></label>
                  <input type="text" name="kota" class="form-control" value="{{ old('kota', $venue->kota) }}" required>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Nomor Telepon</label>
                  <input type="text" name="nomor_telepon" class="form-control" value="{{ old('nomor_telepon', $venue->nomor_telepon) }}">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Email Venue</label>
                  <input type="email" name="email_venue" class="form-control" value="{{ old('email_venue', $venue->email_venue) }}">
                </div>
              </div>
            </div>

            <div class="form-group">
              <label>Video Review (Link YouTube)</label>
              <input type="url" name="video_review" class="form-control" value="{{ old('video_review', $venue->video_review) }}" placeholder="https://www.youtube.com/watch?v=...">
            </div>

            <div class="form-group">
              <label>Lokasi (Link Google Maps)</label>
              <input type="url" name="lokasi" class="form-control" value="{{ old('lokasi', $venue->lokasi) }}" placeholder="https://www.google.com/maps/place/...">
            </div>

            <div class="form-group">
              <label>Detail Venue</label>
              <textarea name="detail" class="form-control" rows="5">{{ old('detail', $venue->detail) }}</textarea>
            </div>

            <div class="form-group">
              <label>Aturan Venue</label>
              <textarea name="aturan" class="form-control" rows="5">{{ old('aturan', $venue->aturan) }}</textarea>
            </div>
          </div>
        </div>

        <div class="card-footer">
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Simpan Perubahan
          </button>
          <a href="{{ route('admin.venue.show', $venue->id) }}" class="btn btn-secondary">
            <i class="fas fa-times"></i> Batal
          </a>
        </div>
      </form>
    </div>
  </section>
</div>
@endsection

