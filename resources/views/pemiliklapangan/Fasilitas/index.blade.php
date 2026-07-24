@extends('pemiliklapangan.layout.ownervenue')

@section('content')
<div class="content-wrapper bg-light">
  <section class="content pt-4 pb-5">
    <div class="container-fluid">

      <div class="row">
        <div class="col-12">
          <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-header bg-white border-0 pb-0">
              <h5 class="mb-1 font-weight-bold text-dark">Basic Information</h5>
            </div>
            <div class="card-body pt-3">
              <div class="owner-highlight position-relative border rounded-3 p-4">
                <div class="d-flex align-items-start">
                  <div class="highlight-icon mr-3">
                    <i class="fas fa-chalkboard-teacher"></i>
                  </div>
                  <div>
                    <h6 class="font-weight-bold text-primary mb-2">Cara membuat Venue</h6>
                    <p class="mb-0 text-muted">
                      Ikuti pelatihan tata cara menggunakan sistem Olga secara gratis untuk memudahkan Anda menguasai produk dan
                      servis pada sistem. Apabila Anda membutuhkan informasi atau pertanyaan, segera hubungi Whatsapp Customer
                      Service Olga <a href="https://wa.me/628123456789" target="_blank" rel="noopener" class="text-primary font-weight-bold">di sini</a>.
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
                    src="{{ $venue->logo ? asset('storage/' . $venue->logo) : asset('assets/olgasehat-icon.png') }}"
                    alt="{{ $venue->namavenue }}"
                    class="img-fluid rounded">
                </div>
                <h5 class="font-weight-bold text-dark mb-1">{{ $venue->namavenue }}</h5>
                <span class="text-muted small mb-3">
                  {{ \Illuminate\Support\Str::slug($venue->namavenue, '_') }}
                </span>
                <div class="mt-auto">
                  <a href="{{ route('fasilitas.detail', $venue->id) }}" class="btn btn-primary btn-block font-weight-bold">
                    Lihat Lapangan
                  </a>
                </div>
              </div>
            </div>
          </div>
        @empty
          <div class="col-12">
            <div class="alert alert-info border-0 shadow-sm">
              Belum ada venue yang terdaftar. Mulai dengan menambahkan venue pertama Anda.
            </div>
          </div>
        @endforelse

        <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
          <div class="card owner-card add-card h-100 border border-primary border-dashed">
            <div class="card-body d-flex flex-column text-center justify-content-center">
              <div class="add-icon mb-3 mx-auto">
                <i class="fas fa-plus"></i>
              </div>
              <h5 class="font-weight-bold text-dark mb-2">Tambah Venue</h5>
              <p class="text-muted small mb-3 px-3">
                Anda dapat menambahkan venue yang Anda miliki dengan menekan tombol tambah.
              </p>
              <a href="{{ route('informasi') }}" class="btn btn-outline-primary font-weight-bold px-4">
                Tambah
              </a>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>
</div>

<style>
  .owner-highlight {
    background-color: #f0f7ff;
    border: 2px dashed #8cc2ff;
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
    border-radius: 16px;
    transition: transform .2s ease, box-shadow .2s ease;
  }
  .owner-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px rgba(1, 61, 157, 0.12);
  }
  .owner-card .venue-image {
    width: 120px;
    height: 120px;
    border-radius: 16px;
    overflow: hidden;
    background: rgba(1,61,157,0.08);
  }
  .owner-card .venue-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  .owner-card.add-card {
    border-style: dashed;
    background: #f5faff;
    color: #013d9d;
  }
  .owner-card.add-card .add-icon {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    background: rgba(1, 61, 157, 0.15);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
  }
</style>
@endsection

