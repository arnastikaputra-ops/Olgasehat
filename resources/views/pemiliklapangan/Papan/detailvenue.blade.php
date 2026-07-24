@extends('pemiliklapangan.layout.ownervenue')

@section('content')
<div class="content-wrapper bg-light">
  <section class="content pt-4 pb-5">
    <div class="container-fluid">

      <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
          <div>
            <h5 class="text-uppercase text-muted mb-1 small">Kelola Jadwal Venue</h5>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb bg-transparent p-0 mb-0">
                <li class="breadcrumb-item"><a href="{{ route('papan') }}">Kelola Jadwal</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detail Venue</li>
              </ol>
            </nav>
          </div>
          <a href="{{ route('papan') }}" class="btn btn-outline-secondary btn-sm rounded-lg">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
          </a>
        </div>
      </div>

      <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
          <div class="row align-items-center">
            <div class="col-lg-10 d-flex align-items-center">
              <div class="venue-logo mr-4 flex-shrink-0">
                <img
                  src="{{ $venue->logo ? asset('storage/' . $venue->logo) : asset('assets/olgasehat-icon.png') }}"
                  alt="{{ $venue->namavenue }}"
                  class="img-fluid">
              </div>
              <div class="flex-grow-1">
                <div class="d-flex align-items-center mb-1">
                  <h3 class="font-weight-bold text-dark mb-0">{{ $venue->namavenue }}</h3>
                </div>
                <p class="text-muted mb-2 small">
                  <i class="fas fa-map-marker-alt text-primary mr-1"></i> {{ $venue->lokasi ? $venue->lokasi : 'Lokasi belum diisi.' }}
                </p>
                <div class="d-flex flex-wrap align-items-center icon-badges">
                  @php
                    $kategoriList = is_array($venue->kategori) ? $venue->kategori : ($venue->kategori ? [$venue->kategori] : []);
                  @endphp
                  @foreach($kategoriList as $kat)
                    <span class="badge badge-pill badge-light border text-primary mr-2 mb-1 px-3 py-1 font-weight-semibold">
                      <i class="fas fa-futbol mr-1"></i> {{ $kat }}
                    </span>
                  @endforeach
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
          <h5 class="font-weight-bold text-dark mb-4"><i class="fas fa-list mr-2 text-primary"></i>Daftar Lapangan</h5>

          <div class="row">
            @forelse($venue->lapangans as $lapangan)
              <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                <div class="card h-100 border-0 shadow-sm lapangan-card">
                  <div class="card-body d-flex flex-column p-4">
                    <div class="lapangan-icon mb-3">
                      <i class="fas fa-futbol"></i>
                    </div>
                    <h5 class="font-weight-bold text-dark mb-2">{{ $lapangan->nama }}</h5>
                    <p class="text-muted small mb-4">Pilih lapangan ini untuk mengelola slot waktu, harga sewa, dan status pemesanan.</p>
                    <div class="mt-auto">
                      <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge badge-light text-primary font-weight-bold px-3 py-2 rounded-pill">
                          {{ $venue->namavenue }}
                        </span>
                        <small class="text-muted small">
                          Active
                        </small>
                      </div>
                      <a href="{{ route('fasilitas.lapangan.jadwal', [$venue->id, $lapangan->id]) }}" class="btn btn-primary btn-block font-weight-bold py-2 rounded-lg shadow-sm">
                        <i class="fas fa-calendar-alt mr-2"></i> Kelola Jadwal
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            @empty
              <div class="col-12 text-center py-5">
                <i class="fas fa-futbol fa-3x text-muted mb-3"></i>
                <h6 class="font-weight-bold text-dark mb-1">Belum ada lapangan yang ditambahkan</h6>
                <p class="text-muted small">Silakan tambahkan lapangan terlebih dahulu melalui menu <a href="{{ route('fasilitas.detail', $venue->id) }}" class="text-primary font-weight-bold">Kelola Fasilitas</a>.</p>
              </div>
            @endforelse
          </div>
        </div>
      </div>

    </div>
  </section>
</div>

<style>
  .rounded-4 {
    border-radius: 16px !important;
  }
  .rounded-lg {
    border-radius: 10px !important;
  }
  .venue-logo {
    width: 96px;
    height: 96px;
    border-radius: 20px;
    background: rgba(1, 61, 157, 0.08);
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    border: 2px solid #fff;
  }
  .venue-logo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  .lapangan-card {
    border-radius: 16px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    transition: transform .3s ease, box-shadow .3s ease, border-color .3s ease;
  }
  .lapangan-card:hover {
    transform: translateY(-4px);
    border-color: #cbd5e1;
    box-shadow: 0 16px 28px rgba(1, 61, 157, 0.08) !important;
  }
  .lapangan-icon {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    background: rgba(1, 61, 157, 0.1);
    color: #013d9d;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
  }
</style>
@endsection
