@extends('pemiliklapangan.layout.ownervenue')

@section('content')
<div class="content-wrapper bg-light">
  <section class="content pt-4 pb-5">
    <div class="container-fluid">

      <div class="row">
        <div class="col-12">
          <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white border-0 pb-0">
              <h5 class="mb-1 font-weight-bold text-dark">Kelola Jadwal Lapangan</h5>
            </div>
            <div class="card-body pt-3">
              <div class="owner-highlight position-relative border rounded-4 p-4">
                <div class="d-flex align-items-start">
                  <div class="highlight-icon mr-3">
                    <i class="fas fa-calendar-check"></i>
                  </div>
                  <div>
                    <h6 class="font-weight-bold text-primary mb-2">Panduan Kelola Jadwal</h6>
                    <p class="mb-0 text-muted small">
                      Pilih venue Anda di bawah ini untuk mengelola jadwal operasional, menetapkan tarif sewa per jam, memblokir slot waktu tertentu, atau menggunakan fitur <strong>Generate Jadwal Bulk</strong> secara otomatis untuk rentang waktu hingga satu tahun.
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        @forelse ($venues as $venue)
          <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
            <div class="card owner-card h-100 border-0 shadow-sm">
              <div class="card-body d-flex flex-column text-center">
                <div class="venue-image mb-3 mx-auto">
                  <img
                    src="{{ image_url($venue->logo) }}"
                    alt="{{ $venue->namavenue }}"
                    class="img-fluid rounded">
                </div>
                <h5 class="font-weight-bold text-dark mb-1">{{ $venue->namavenue }}</h5>
                <span class="text-muted small mb-3">
                  {{ \Illuminate\Support\Str::slug($venue->namavenue, '_') }}
                </span>
                
                @php
                  $kategoriList = is_array($venue->kategori) ? $venue->kategori : ($venue->kategori ? [$venue->kategori] : []);
                @endphp
                <div class="mb-3 d-flex flex-wrap justify-content-center gap-1">
                  @foreach(array_slice($kategoriList, 0, 2) as $kat)
                    <span class="badge badge-pill badge-light text-primary border small px-2 py-1">{{ $kat }}</span>
                  @endforeach
                  @if(count($kategoriList) > 2)
                    <span class="badge badge-pill badge-light text-secondary border small px-2 py-1">+{{ count($kategoriList) - 2 }}</span>
                  @endif
                </div>

                <div class="mt-auto">
                  <a href="{{ route('papan.venue.detail', $venue->id) }}" class="btn btn-primary btn-block font-weight-bold shadow-sm rounded-lg py-2">
                    <i class="fas fa-calendar mr-2"></i> Kelola Jadwal
                  </a>
                </div>
              </div>
            </div>
          </div>
        @empty
          <div class="col-12">
            <div class="alert alert-info border-0 shadow-sm rounded-4 py-4 text-center">
              <i class="fas fa-info-circle fa-2x mb-2 text-info"></i>
              <h6 class="font-weight-bold">Belum ada venue yang terdaftar</h6>
              <p class="text-muted mb-0 small">Daftarkan venue Anda terlebih dahulu pada menu <strong>Kelola Fasilitas</strong>.</p>
            </div>
          </div>
        @endforelse
      </div>

    </div>
  </section>
</div>

<style>
  .rounded-4 {
    border-radius: 16px !important;
  }
  .owner-highlight {
    background-color: #f0f7ff;
    border: 2px dashed #8cc2ff !important;
    border-radius: 12px;
  }
  .owner-highlight .highlight-icon {
    width: 48px;
    height: 48px;
    border-radius: 16px;
    background: rgba(1, 61, 157, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #013d9d;
    font-size: 18px;
  }
  .owner-card {
    border-radius: 18px;
    transition: transform .3s ease, box-shadow .3s ease;
  }
  .owner-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 16px 28px rgba(1, 61, 157, 0.12) !important;
  }
  .owner-card .venue-image {
    width: 120px;
    height: 120px;
    border-radius: 20px;
    overflow: hidden;
    background: rgba(1,61,157,0.08);
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    border: 2px solid #fff;
  }
  .owner-card .venue-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  .gap-1 {
    gap: 0.25rem;
  }
  .rounded-lg {
    border-radius: 10px !important;
  }
</style>
@endsection
