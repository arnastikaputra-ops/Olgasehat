@extends('pemiliklapangan.layout.ownervenue')

@section('content')
<div class="content-wrapper bg-light">
  <section class="content pt-4 pb-5">
    <div class="container-fluid">

      <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
          <div>
            <h5 class="text-uppercase text-muted mb-1 small">Detail Venue</h5>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb bg-transparent p-0 mb-0">
                <li class="breadcrumb-item"><a href="{{ route('fasilitas') }}">Kelola Fasilitas</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detail Venue</li>
              </ol>
            </nav>
          </div>
          <a href="{{ route('fasilitas') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
          </a>
        </div>
      </div>

      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      @endif

      <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-4">
          <div class="row align-items-start">
            <div class="col-lg-9 d-flex">
              <div class="venue-logo mr-4 flex-shrink-0">
                <img
                  src="{{ image_url($venue->logo) }}"
                  alt="{{ $venue->namavenue }}"
                  class="img-fluid">
              </div>
              <div class="flex-grow-1">
                <div class="d-flex align-items-center mb-2">
                  <h3 class="font-weight-bold text-dark mb-0 mr-2">{{ $venue->namavenue }}</h3>
                  <a href="{{ route('fasilitas.edit', $venue->id) }}" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-pencil-alt mr-1"></i> Edit
                  </a>
                </div>
                <p class="text-muted mb-3">
                  {{ $venue->lokasi ? $venue->lokasi : 'Lokasi belum diisi.' }}
                </p>
                <div class="row small text-muted mb-3">
                  <div class="col-md-4 mb-2">
                    <span class="text-uppercase text-secondary d-block font-weight-bold">Username</span>
                    <span class="text-primary font-weight-bold">{{ \Illuminate\Support\Str::slug($venue->namavenue, '_') }}</span>
                  </div>
                  <div class="col-md-4 mb-2">
                    <span class="text-uppercase text-secondary d-block font-weight-bold">Kontak</span>
                    <span class="font-weight-bold">
                      @if($venue->nomor_telepon)
                        +62{{ $venue->nomor_telepon }}
                      @else
                        +62XXXXXXXXXX
                      @endif
                    </span>
                  </div>
                  <div class="col-md-4 mb-2">
                    <span class="text-uppercase text-secondary d-block font-weight-bold">Lokasi</span>
                    <span class="font-weight-bold">{{ ucfirst($venue->kota) }}, {{ ucfirst($venue->provinsi) }}</span>
                  </div>
                </div>
                <div class="d-flex flex-wrap align-items-center icon-badges">
                  @if(isset($fasilitas) && count($fasilitas) > 0)
                    @php
                      $fasilitasIcons = [
                        'Area Parkir' => 'fa-parking',
                        'Toilet/Kamar Mandi' => 'fa-toilet',
                        'Ruang Ganti/Transit' => 'fa-tshirt',
                        'Tempat Ibadah (Musholla)' => 'fa-mosque',
                        'Kantin/Area Catering' => 'fa-utensils',
                        'AC/Pendingin Udara' => 'fa-snowflake',
                        'Sistem Tata Suara (Sound System)' => 'fa-volume-up',
                        'Proyektor & Layar/LED' => 'fa-tv',
                        'Akses Internet (Wi-Fi)' => 'fa-wifi',
                        'Akses Listrik Cadangan (Genset)' => 'fa-plug',
                        'Area Registrasi/Lobi' => 'fa-door-open',
                        'Keamanan (Security) & P3K' => 'fa-shield-alt'
                      ];
                      $fasilitasLabels = [
                        'Area Parkir' => 'Parkir',
                        'Toilet/Kamar Mandi' => 'Toilet',
                        'Ruang Ganti/Transit' => 'Ruang Ganti',
                        'Tempat Ibadah (Musholla)' => 'Musholla',
                        'Kantin/Area Catering' => 'Kantin',
                        'AC/Pendingin Udara' => 'AC',
                        'Sistem Tata Suara (Sound System)' => 'Sound System',
                        'Proyektor & Layar/LED' => 'Proyektor',
                        'Akses Internet (Wi-Fi)' => 'WiFi',
                        'Akses Listrik Cadangan (Genset)' => 'Genset',
                        'Area Registrasi/Lobi' => 'Lobi',
                        'Keamanan (Security) & P3K' => 'Security'
                      ];
                    @endphp
                    @foreach($fasilitas as $fas)
                      @if(isset($fasilitasIcons[$fas]))
                        <span class="badge badge-pill badge-light border mr-2 mb-2">
                          <i class="fas {{ $fasilitasIcons[$fas] }} mr-1 text-primary"></i> 
                          {{ $fasilitasLabels[$fas] ?? $fas }}
                        </span>
                      @endif
                    @endforeach
                  @else
                    <span class="text-muted small">Belum ada fasilitas yang ditambahkan.</span>
                  @endif
                </div>
              </div>
            </div>
            <div class="col-lg-3 d-flex justify-content-lg-end mt-3 mt-lg-0">
              <div class="btn-group-vertical w-100">
                <button type="button" class="btn btn-primary font-weight-bold mb-2 shadow-sm rounded-lg" data-toggle="modal" data-target="#qrCodeModal">
                  <i class="fas fa-qrcode mr-2"></i> QR Code
                </button>
                <a href="{{ route('frontend.venue.detail', $venue->id) }}" target="_blank" class="btn btn-outline-primary font-weight-bold mb-2 shadow-sm rounded-lg text-center">
                  <i class="fas fa-external-link-alt mr-2"></i> Preview Venue
                </a>
                <div class="dropdown w-100">
                  <button class="btn btn-light border font-weight-bold dropdown-toggle w-100 shadow-sm rounded-lg" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-h mr-2"></i> Lainnya
                  </button>
                  <div class="dropdown-menu dropdown-menu-right w-100 shadow-sm rounded-lg" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item py-2" href="javascript:void(0);" onclick="copyShareableLink()">
                      <i class="fas fa-copy mr-2 text-primary"></i> Salin Link Share
                    </a>
                    <a class="dropdown-item py-2" href="{{ route('papan.venue.detail', $venue->id) }}">
                      <i class="fas fa-calendar-alt mr-2 text-success"></i> Kelola Jadwal
                    </a>
                    <a class="dropdown-item py-2" href="{{ route('fasilitas.edit', $venue->id) }}">
                      <i class="fas fa-edit mr-2 text-warning"></i> Edit Informasi
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Modal QR Code -->
          <div class="modal fade" id="qrCodeModal" tabindex="-1" aria-labelledby="qrCodeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content rounded-lg border-0 shadow-lg" style="border-radius: 16px !important;">
                <div class="modal-header border-0 pb-0">
                  <h5 class="modal-title font-weight-bold text-dark" id="qrCodeModalLabel">QR Code Venue</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body text-center py-4">
                  <p class="text-muted small mb-4">Scan QR code ini untuk membuka halaman pemesanan venue di aplikasi pelanggan.</p>
                  <div class="qr-code-wrapper p-3 bg-white border d-inline-block shadow-sm mb-4" style="border-radius: 12px;">
                    @php
                      $shareUrl = route('frontend.venue.detail', $venue->id);
                      $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=" . urlencode($shareUrl);
                    @endphp
                    <img src="{{ $qrUrl }}" alt="QR Code Venue" class="img-fluid" style="width: 250px; height: 250px;">
                  </div>
                  <div class="d-flex justify-content-center gap-2">
                    <a href="{{ $qrUrl }}" target="_blank" class="btn btn-primary font-weight-bold px-4 rounded-lg mr-2 shadow-sm">
                      <i class="fas fa-external-link-alt mr-1"></i> Buka QR
                    </a>
                    <button type="button" class="btn btn-outline-secondary font-weight-bold px-4 rounded-lg shadow-sm" onclick="copyShareableLink()">
                      <i class="fas fa-copy mr-1"></i> Salin Link
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="mt-4">
            <ul class="nav nav-tabs owner-tabs" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#tab-lapangan" role="tab" aria-selected="true">Lapangan</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#tab-experience" role="tab" aria-selected="false">Experience</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#tab-operasional" role="tab" aria-selected="false">Jam Operasional</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#tab-deskripsi" role="tab" aria-selected="false">Deskripsi</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#tab-syarat" role="tab" aria-selected="false">Syarat dan Ketentuan</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#tab-galeri" role="tab" aria-selected="false">Galeri</a>
              </li>
            </ul>
          </div>

          <div class="tab-content pt-4">
            <div class="tab-pane fade show active" id="tab-lapangan" role="tabpanel">
              @if($venue->kategori && !empty($venue->kategori))
                @php
                  // Handle both array and string format
                  $kategoriList = is_array($venue->kategori) ? $venue->kategori : [$venue->kategori];
                  $kategoriDisplay = implode(', ', $kategoriList);
                @endphp
                <div class="mb-4">
                  <h5 class="font-weight-bold mb-3">Informasi Lapangan</h5>
                  <div class="card border-0 shadow-sm">
                    <div class="card-body">
                      <div class="row align-items-center">
                        <div class="col-md-8">
                          <h6 class="font-weight-bold text-dark mb-2">
                            <i class="fas fa-futbol text-primary mr-2"></i>
                            Cabang Olahraga
                          </h6>
                          <p class="text-muted mb-0">{{ $kategoriDisplay }}</p>
                        </div>
                        <div class="col-md-4 text-right">
                          @foreach($kategoriList as $kat)
                            <span class="badge badge-primary badge-pill px-3 py-2 mr-1 mb-1">
                              {{ $kat }}
                            </span>
                          @endforeach
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              @endif
              
              <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-12">
                  <div class="card h-100 border border-primary border-dashed text-center add-box">
                    <div class="card-body d-flex flex-column justify-content-center">
                      <div class="add-icon mx-auto mb-3">
                        <i class="fas fa-plus"></i>
                      </div>
                      <h5 class="font-weight-bold text-dark mb-2">Tambah Lapangan</h5>
                      <p class="text-muted small mb-3 px-2">
                        Anda dapat menambahkan lapangan di venue yang Anda miliki dengan menekan tombol tambah.
                      </p>
                      <button type="button" class="btn btn-primary font-weight-bold px-4" data-toggle="modal" data-target="#modalTambahLapangan">
                        Tambah
                      </button>
                    </div>
                  </div>
                </div>
                @if($venue->lapangans->isNotEmpty())
                  @foreach($venue->lapangans as $lapangan)
                    <div class="col-lg-4 col-md-6 col-sm-12">
                      <div class="card h-100 border-0 shadow-sm lapangan-card">
                        <div class="card-body d-flex flex-column">
                          <div class="lapangan-icon mb-3">
                            <i class="fas fa-futbol"></i>
                          </div>
                          <h5 class="font-weight-bold text-dark mb-2">{{ $lapangan->nama }}</h5>
                          @php
                            $kategoriList = is_array($venue->kategori) ? $venue->kategori : ($venue->kategori ? [$venue->kategori] : []);
                            $kategoriDisplay = !empty($kategoriList) ? implode(', ', $kategoriList) : '-';
                          @endphp
                          <p class="text-muted small mb-4">Lapangan utama untuk kategori {{ $kategoriDisplay }}</p>
                          <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                              <span class="badge badge-light text-primary font-weight-bold px-3 py-2">
                                {{ $venue->namavenue }}
                              </span>
                              <small class="text-muted">
                                Ditambahkan {{ optional($lapangan->created_at)->format('d M Y') }}
                              </small>
                            </div>
                            <a href="{{ route('fasilitas.lapangan.jadwal', [$venue->id, $lapangan->id]) }}" class="btn btn-outline-primary btn-block font-weight-bold">
                              Kelola Jadwal
                            </a>
                          </div>
                        </div>
                      </div>
                    </div>
                  @endforeach
                @else
                  <div class="col-12 mt-3">
                    <div class="alert alert-light border text-muted text-center">
                      Belum ada lapangan yang ditambahkan. Mulai dengan menambahkan lapangan pertama Anda.
                    </div>
                  </div>
                @endif
              </div>
            </div>

            <div class="tab-pane fade" id="tab-experience" role="tabpanel">
              @if($venue->video_review)
                <div class="mb-3">
                  <h5 class="font-weight-bold mb-3">Video Review</h5>
                  @php
                    // Extract YouTube video ID
                    $videoId = null;
                    if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $venue->video_review, $matches)) {
                      $videoId = $matches[1];
                    }
                  @endphp
                  @if($videoId)
                    <div class="embed-responsive embed-responsive-16by9" style="max-width: 800px;">
                      <iframe class="embed-responsive-item" 
                              src="https://www.youtube.com/embed/{{ $videoId }}" 
                              allowfullscreen></iframe>
                    </div>
                    <p class="mt-2">
                      <a href="{{ $venue->video_review }}" target="_blank" class="text-primary">
                        <i class="fab fa-youtube mr-1"></i> Buka di YouTube
                      </a>
                    </p>
                  @else
                    <p class="text-muted">Link video tidak valid.</p>
                  @endif
                </div>
              @else
                <p class="text-muted mb-0">Belum ada video review yang ditambahkan.</p>
              @endif
            </div>
            <div class="tab-pane fade" id="tab-operasional" role="tabpanel">
              @if($venue->jam_operasional && is_array($venue->jam_operasional) && count($venue->jam_operasional) > 0)
                <div class="row">
                  <div class="col-md-6 col-lg-5">
                    <div class="card border-0 shadow-sm rounded-lg overflow-hidden">
                      <table class="table mb-0 table-hover">
                        <thead class="bg-primary text-white">
                          <tr>
                            <th class="border-0 font-weight-bold px-4 py-3"><i class="far fa-calendar-alt mr-2"></i>Hari</th>
                            <th class="border-0 font-weight-bold px-4 py-3 text-right"><i class="far fa-clock mr-2"></i>Jam Operasional</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach($venue->jam_operasional as $item)
                            <tr>
                              <td class="px-4 py-3 font-weight-bold text-dark">{{ $item['hari'] }}</td>
                              <td class="px-4 py-3 text-right text-primary font-weight-bold">
                                {{ \Carbon\Carbon::parse($item['buka'])->format('H:i') }} - {{ \Carbon\Carbon::parse($item['tutup'])->format('H:i') }}
                              </td>
                            </tr>
                          @endforeach
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              @else
                <div class="text-center py-5">
                  <i class="fas fa-clock fa-3x text-muted mb-3"></i>
                  <p class="text-muted mb-0">Jam operasional belum diatur.</p>
                  <p class="text-muted small">Atur jam operasional melalui tombol <strong>Edit</strong> di atas.</p>
                </div>
              @endif
            </div>
            <div class="tab-pane fade" id="tab-deskripsi" role="tabpanel">
              @if($venue->detail)
                <div class="venue-content">
                  {!! $venue->detail !!}
                </div>
              @else
                <p class="text-muted mb-0">Belum ada deskripsi venue.</p>
              @endif
            </div>
            <div class="tab-pane fade" id="tab-syarat" role="tabpanel">
              @if($venue->aturan)
                <div class="venue-content">
                  {!! $venue->aturan !!}
                </div>
              @else
                <p class="text-muted mb-0">Belum ada syarat dan ketentuan.</p>
              @endif
            </div>
            <div class="tab-pane fade" id="tab-galeri" role="tabpanel">
              @if($venue->galleries && $venue->galleries->count() > 0)
                <div class="row">
                  @foreach($venue->galleries as $gallery)
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                      <div class="card border-0 shadow-sm h-100">
                        <div class="card-img-top position-relative" style="height: 200px; overflow: hidden;">
                          <img src="{{ asset('storage/' . $gallery->foto) }}" 
                               alt="Gallery {{ $loop->iteration }}" 
                               class="w-100 h-100" 
                               style="object-fit: cover;">
                        </div>
                        <div class="card-body p-2 text-center">
                          <small class="text-muted">Foto {{ $loop->iteration }}</small>
                        </div>
                      </div>
                    </div>
                  @endforeach
                </div>
              @else
                <div class="text-center py-5">
                  <i class="fas fa-images fa-3x text-muted mb-3"></i>
                  <p class="text-muted mb-0">Galeri belum tersedia.</p>
                  <p class="text-muted small">Tambahkan foto galeri melalui form Detail Venue.</p>
                </div>
              @endif
            </div>
          </div>

        </div>
      </div>

    </div>
  </section>
</div>

<!-- Modal Tambah Lapangan -->
<div class="modal fade" id="modalTambahLapangan" tabindex="-1" role="dialog" aria-labelledby="modalTambahLapanganLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header border-0">
        <h5 class="modal-title font-weight-bold" id="modalTambahLapanganLabel">Tambah Lapangan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('fasilitas.lapangan.store', $venue->id) }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="form-group">
            <label for="nama_lapangan" class="font-weight-semibold">Nama Lapangan</label>
            <input
              type="text"
              name="nama_lapangan"
              id="nama_lapangan"
              value="{{ old('nama_lapangan') }}"
              class="form-control @error('nama_lapangan') is-invalid @enderror"
              placeholder="Contoh: Lapangan A">
            @error('nama_lapangan')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
          </div>
        </div>
        <div class="modal-footer border-0">
          <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary font-weight-bold">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<style>
  .venue-logo {
    width: 112px;
    height: 112px;
    border-radius: 24px;
    background: rgba(1, 61, 157, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
  }
  .venue-logo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  .owner-tabs .nav-link {
    font-weight: 600;
    color: #6c7a92;
    padding: 0.75rem 1.5rem;
    border: none;
    border-bottom: 2px solid transparent;
    transition: all .2s ease;
  }
  .owner-tabs .nav-link:hover {
    color: #013d9d;
  }
  .owner-tabs .nav-link.active {
    color: #013d9d;
    border-bottom-color: #2b8af7;
  }
  .border-dashed {
    border-style: dashed !important;
  }
  .add-box {
    background: #f5faff;
    border-radius: 16px;
    transition: transform .2s ease, box-shadow .2s ease;
  }
  .add-box:hover {
    transform: translateY(-4px);
    box-shadow: 0 18px 30px rgba(1, 61, 157, 0.15);
  }
  .add-box .add-icon {
    width: 64px;
    height: 64px;
    border-radius: 16px;
    background: rgba(43, 138, 247, 0.18);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    color: #2b8af7;
  }
  .venue-content {
    line-height: 1.8;
    color: #495057;
  }
  .venue-content p {
    margin-bottom: 1rem;
  }
  .venue-content ul, .venue-content ol {
    margin-bottom: 1rem;
    padding-left: 2rem;
  }
  .venue-content img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    margin: 1rem 0;
  }
  .lapangan-card {
    border-radius: 16px;
    background: #ffffff;
    transition: transform .2s ease, box-shadow .2s ease;
  }
  .lapangan-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 18px 30px rgba(1, 61, 157, 0.12);
  }
  .lapangan-card .lapangan-icon {
    width: 56px;
    height: 56px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(43, 138, 247, 0.18);
    color: #2b8af7;
    font-size: 24px;
  }
  .custom-toast {
    position: fixed;
    bottom: 24px;
    right: 24px;
    background: #10b981;
    color: #fff;
    padding: 12px 24px;
    border-radius: 8px;
    box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -4px rgba(0,0,0,0.1);
    z-index: 9999;
    font-weight: 600;
    font-size: 14px;
    display: flex;
    align-items: center;
    opacity: 0;
    transform: translateY(20px);
    transition: opacity 0.3s ease, transform 0.3s ease;
  }
  .custom-toast.show {
    opacity: 1;
    transform: translateY(0);
  }
</style>

<script>
function copyShareableLink() {
    const link = "{{ route('frontend.venue.detail', $venue->id) }}";
    navigator.clipboard.writeText(link).then(function() {
        const toast = document.createElement('div');
        toast.className = 'custom-toast';
        toast.innerHTML = '<i class="fas fa-check-circle mr-2"></i> Link shareable berhasil disalin ke clipboard!';
        document.body.appendChild(toast);
        setTimeout(() => {
            toast.classList.add('show');
        }, 100);
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 2500);
    }).catch(function(err) {
        console.error('Could not copy text: ', err);
    });
}
</script>

@if($errors->has('nama_lapangan'))
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      $('#modalTambahLapangan').modal('show');
    });
  </script>
@endif
@endsection