@extends('pemiliklapangan.layout.ownervenue')

@section('content')
<div class="content-wrapper bg-light">
  <section class="content pt-4 pb-5">
    <div class="container-fluid">
      
      <!-- Header Section -->
      <div class="row mb-4">
        <div class="col-12">
          <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between mb-4">
            <div class="mb-3 mb-md-0">
              <h2 class="text-3xl font-weight-bold text-dark mb-2 border-l-4 border-blue-700 pl-3">
                Promo Venue
              </h2>
              <p class="text-gray-600 mb-0 text-lg">
                Jangan lewatkan kesempatan! Kelola promo dan diskon untuk menarik lebih banyak pelanggan ke venue Anda.
              </p>
            </div>
            <button 
              type="button"
              class="btn btn-primary btn-lg font-weight-bold px-4 shadow-sm"
              data-toggle="modal"
              data-target="#buatPromoModal"
            >
              <i class="fas fa-plus mr-2"></i>
              Buat Promo Baru
            </button>
          </div>
        </div>
      </div>

      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-lg mb-4" role="alert">
          <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      @endif

      @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-lg mb-4" role="alert">
          <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      @endif

      @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-lg mb-4" role="alert">
          <ul class="mb-0 pl-3">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      @endif

      <!-- Promo Cards Section -->
      @if($promos->count() > 0)
        <div class="row">
          <div class="col-12">
            <div 
              class="d-flex flex-wrap gap-4 overflow-x-auto pb-4 promo-scroll-container"
              style="scrollbar-width: thin; scrollbar-color: #cbd5e0 #f7fafc;"
            >
              @foreach($promos as $promo)
                <a 
                  href="{{ route('fasilitas.detail', $promo['venue_id']) }}" 
                  class="flex-shrink-0 promo-card-link"
                  style="min-width: 300px; max-width: 300px;"
                >
                  <article class="bg-white rounded-xl overflow-hidden shadow-xl hover:shadow-2xl transform hover:-translate-y-1 transition duration-300 border border-gray-100 h-100 d-flex flex-column">
                    <div class="relative position-relative" style="height: 160px; overflow: hidden;">
                      <img 
                        alt="{{ $promo['judul'] }}" 
                        class="w-100 h-100" 
                        style="object-fit: cover;" 
                        src="{{ $promo['image_url'] }}" 
                        onerror="this.src='{{ asset('assets/olgasehat-icon.png') }}'"
                      />
                      <span class="position-absolute top-0 right-0 m-3 bg-danger text-white text-xs font-weight-bold rounded-pill px-3 py-1 shadow-md" style="font-size: 10px; line-height: 1.2;">
                        🔥 PROMO SPESIAL
                      </span>
                    </div>
                    <div class="p-4 d-flex flex-column flex-grow-1">
                      <h4 class="font-weight-bold text-base text-dark mb-1 line-clamp-2" style="min-height: 48px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                        {{ $promo['judul'] }}
                      </h4>
                      <p class="text-sm text-primary mb-2 font-weight-medium">{{ $promo['venue_name'] }}</p>
                      @if($promo['lapangan_name'])
                        <p class="text-xs text-muted mb-2">
                          <i class="fas fa-futbol mr-1"></i>
                          {{ $promo['lapangan_name'] }}
                        </p>
                      @endif
                      @if($promo['diskon_percent'] > 0)
                        <div class="mb-2">
                          <span class="badge badge-success font-weight-bold">
                            Diskon {{ $promo['diskon_percent'] }}%
                          </span>
                          @if($promo['harga_awal'])
                            <div class="text-xs text-muted mt-1">
                              <span class="text-decoration-line-through">Rp {{ number_format($promo['harga_awal'], 0, ',', '.') }}</span>
                              <span class="text-success font-weight-bold ml-1">Rp {{ number_format($promo['harga'], 0, ',', '.') }}</span>
                            </div>
                          @endif
                        </div>
                      @endif
                      <div class="d-flex align-items-center text-xs text-muted pt-2 border-top border-gray-100 mt-auto">
                        <i class="far fa-calendar-alt mr-2"></i>
                        <p class="mb-0">
                          Periode 
                          @php
                            $tglMin = \Carbon\Carbon::parse($promo['tanggal_min']);
                            $tglMax = \Carbon\Carbon::parse($promo['tanggal_max']);
                          @endphp
                          {{ $tglMin->format('d M') }} - {{ $tglMax->format('d M Y') }}
                        </p>
                      </div>
                      <div class="text-xs text-muted mt-1">
                        <i class="fas fa-clock mr-1"></i>
                        {{ $promo['total_slots'] }} slot tersedia
                      </div>
                    </div>
                  </article>
                </a>
              @endforeach
            </div>
          </div>
        </div>
      @else
        <!-- Empty State -->
        <div class="row">
          <div class="col-12">
            <div class="card border-0 shadow-sm">
              <div class="card-body text-center py-5">
                <div class="empty-promo-icon mb-4 mx-auto">
                  <i class="fas fa-percent text-muted" style="font-size: 64px; opacity: 0.3;"></i>
                </div>
                <h5 class="font-weight-bold text-dark mb-2">Belum Ada Promo Aktif</h5>
                <p class="text-muted mb-4">
                  Mulai buat promo menarik untuk meningkatkan penjualan venue Anda. 
                  Promo dapat dibuat saat mengatur jadwal lapangan.
                </p>
                <a href="{{ route('fasilitas') }}" class="btn btn-primary font-weight-bold px-4">
                  <i class="fas fa-plus mr-2"></i>
                  Buat Promo Pertama
                </a>
              </div>
            </div>
          </div>
        </div>
      @endif

      <!-- Info Section -->
      <div class="row mt-5">
        <div class="col-12">
          <div class="card border-0 shadow-sm bg-light">
            <div class="card-body">
              <div class="row align-items-center">
                <div class="col-md-1 text-center mb-3 mb-md-0">
                  <div class="info-icon mx-auto">
                    <i class="fas fa-info-circle text-primary"></i>
                  </div>
                </div>
                <div class="col-md-11">
                  <h6 class="font-weight-bold text-dark mb-2">Tips Membuat Promo yang Efektif</h6>
                  <ul class="text-muted mb-0 pl-3" style="line-height: 1.8;">
                    <li>Buat promo dengan diskon yang menarik (minimal 10-20%) untuk menarik perhatian pelanggan</li>
                    <li>Tentukan periode promo yang tepat, misalnya akhir pekan atau hari libur</li>
                    <li>Tambahkan catatan yang jelas dan menarik untuk menjelaskan promo Anda</li>
                    <li>Pastikan harga awal lebih besar dari harga promo untuk menunjukkan nilai diskon</li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>
</div>

<style>
  .promo-scroll-container {
    scrollbar-width: thin;
    scrollbar-color: #cbd5e0 #f7fafc;
  }
  
  .promo-scroll-container::-webkit-scrollbar {
    height: 8px;
  }
  
  .promo-scroll-container::-webkit-scrollbar-track {
    background: #f7fafc;
    border-radius: 4px;
  }
  
  .promo-scroll-container::-webkit-scrollbar-thumb {
    background: #cbd5e0;
    border-radius: 4px;
  }
  
  .promo-scroll-container::-webkit-scrollbar-thumb:hover {
    background: #a0aec0;
  }

  .promo-card-link {
    text-decoration: none;
    color: inherit;
  }

  .promo-card-link:hover {
    text-decoration: none;
    color: inherit;
  }

  .info-icon {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: rgba(1, 61, 157, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
  }

  .empty-promo-icon {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background: rgba(108, 117, 125, 0.05);
    display: flex;
    align-items: center;
    justify-content: center;
  }

  @media (max-width: 768px) {
    .promo-card-link {
      min-width: 280px !important;
      max-width: 280px !important;
    }
  }
</style>

<!-- Modal Buat Promo Baru -->
<div class="modal fade" id="buatPromoModal" tabindex="-1" aria-labelledby="buatPromoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg" style="border-radius: 16px !important;">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title font-weight-bold text-dark" id="buatPromoModalLabel">Buat Promosi Baru</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('pemilik.promo.store') }}" method="POST">
        @csrf
        <div class="modal-body py-4">
          
          <div class="form-group mb-3">
            <label class="small font-weight-semibold text-muted">Tipe Promosi</label>
            <select name="tipe_promo" id="tipe_promo" class="form-control rounded-lg" required>
              <option value="venue">Sewa Lapangan (Booking Venue)</option>
              <option value="event">Event Olahraga</option>
              <option value="komunitas">Komunitas / Open Class</option>
            </select>
          </div>

          <!-- Target Lapangan (Untuk Tipe Venue) -->
          <div class="form-group mb-3" id="target_venue_container">
            <label class="small font-weight-semibold text-muted">Pilih Lapangan</label>
            <select name="target_id_venue" id="target_id_venue" class="form-control rounded-lg">
              <option value="">-- Pilih Lapangan --</option>
              @foreach($venues as $v)
                <optgroup label="{{ $v->namavenue }}">
                  @foreach($v->lapangans as $lap)
                    <option value="{{ $lap->id }}">{{ $lap->nama }}</option>
                  @endforeach
                </optgroup>
              @endforeach
            </select>
          </div>

          <!-- Target Event (Untuk Tipe Event) -->
          <div class="form-group mb-3" id="target_event_container" style="display: none;">
            <label class="small font-weight-semibold text-muted">Pilih Event</label>
            <select name="target_id_event" id="target_id_event" class="form-control rounded-lg">
              <option value="">-- Pilih Event --</option>
              @forelse($events as $ev)
                <option value="{{ $ev->id }}">{{ $ev->nama }} (Rp {{ number_format($ev->harga, 0, ',', '.') }})</option>
              @empty
                <option value="" disabled>Belum ada event berbayar yang dibuat</option>
              @endforelse
            </select>
          </div>

          <!-- Target Komunitas (Untuk Tipe Komunitas) -->
          <div class="form-group mb-3" id="target_komunitas_container" style="display: none;">
            <label class="small font-weight-semibold text-muted">Pilih Komunitas / Kelas</label>
            <select name="target_id_komunitas" id="target_id_komunitas" class="form-control rounded-lg">
              <option value="">-- Pilih Komunitas/Kelas --</option>
              @forelse($komunitas as $kom)
                <option value="{{ $kom->id }}">{{ $kom->nama }} (Rp {{ number_format($kom->harga, 0, ',', '.') }})</option>
              @empty
                <option value="" disabled>Belum ada kelas komunitas berbayar yang dibuat</option>
              @endforelse
            </select>
          </div>

          <!-- Hidden target_id that gets updated before submit -->
          <input type="hidden" name="target_id" id="target_id">

          <div class="form-group mb-3">
            <label class="small font-weight-semibold text-muted">Diskon (%)</label>
            <div class="input-group">
              <input type="number" name="diskon" class="form-control" min="1" max="100" placeholder="Contoh: 15" required>
              <div class="input-group-append">
                <span class="input-group-text bg-white">%</span>
              </div>
            </div>
          </div>

          <!-- Periode Tanggal (Hanya Untuk Venue) -->
          <div class="row" id="periode_tanggal_container">
            <div class="col-6 form-group mb-3">
              <label class="small font-weight-semibold text-muted">Mulai Tanggal</label>
              <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control rounded-lg" value="{{ date('Y-m-d') }}">
            </div>
            <div class="col-6 form-group mb-3">
              <label class="small font-weight-semibold text-muted">Selesai Tanggal</label>
              <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control rounded-lg" value="{{ date('Y-m-d') }}">
            </div>
          </div>

          <div class="form-group mb-3">
            <label class="small font-weight-semibold text-muted">Catatan / Judul Promo</label>
            <input type="text" name="catatan" class="form-control rounded-lg" placeholder="Contoh: Promo Happy Hour, Diskon Weekend" required>
          </div>

        </div>
        <div class="modal-footer border-0 pt-0">
          <button type="button" class="btn btn-outline-secondary rounded-lg" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary font-weight-bold rounded-lg px-4 shadow-sm" id="btnSubmitPromo">Terapkan Promo</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tipePromo = document.getElementById('tipe_promo');
    const targetVenue = document.getElementById('target_venue_container');
    const targetEvent = document.getElementById('target_event_container');
    const targetKomunitas = document.getElementById('target_komunitas_container');
    const dateContainer = document.getElementById('periode_tanggal_container');
    
    const selectVenue = document.getElementById('target_id_venue');
    const selectEvent = document.getElementById('target_id_event');
    const selectKomunitas = document.getElementById('target_id_komunitas');
    const hiddenTargetId = document.getElementById('target_id');
    const form = document.querySelector('#buatPromoModal form');
    
    function toggleContainers() {
        const val = tipePromo.value;
        if (val === 'venue') {
            targetVenue.style.display = 'block';
            targetEvent.style.display = 'none';
            targetKomunitas.style.display = 'none';
            dateContainer.style.display = 'flex';
            
            selectVenue.setAttribute('required', 'required');
            selectEvent.removeAttribute('required');
            selectKomunitas.removeAttribute('required');
            
            document.getElementById('tanggal_mulai').setAttribute('required', 'required');
            document.getElementById('tanggal_selesai').setAttribute('required', 'required');
        } else if (val === 'event') {
            targetVenue.style.display = 'none';
            targetEvent.style.display = 'block';
            targetKomunitas.style.display = 'none';
            dateContainer.style.display = 'none';
            
            selectVenue.removeAttribute('required');
            selectEvent.setAttribute('required', 'required');
            selectKomunitas.removeAttribute('required');
            
            document.getElementById('tanggal_mulai').removeAttribute('required');
            document.getElementById('tanggal_selesai').removeAttribute('required');
        } else if (val === 'komunitas') {
            targetVenue.style.display = 'none';
            targetEvent.style.display = 'none';
            targetKomunitas.style.display = 'block';
            dateContainer.style.display = 'none';
            
            selectVenue.removeAttribute('required');
            selectEvent.removeAttribute('required');
            selectKomunitas.setAttribute('required', 'required');
            
            document.getElementById('tanggal_mulai').removeAttribute('required');
            document.getElementById('tanggal_selesai').removeAttribute('required');
        }
    }
    
    tipePromo.addEventListener('change', toggleContainers);
    toggleContainers();
    
    form.addEventListener('submit', function(e) {
        const val = tipePromo.value;
        if (val === 'venue') {
            hiddenTargetId.value = selectVenue.value;
        } else if (val === 'event') {
            hiddenTargetId.value = selectEvent.value;
        } else if (val === 'komunitas') {
            hiddenTargetId.value = selectKomunitas.value;
        }
        
        if (!hiddenTargetId.value) {
            e.preventDefault();
            alert('Silakan pilih target promosi terlebih dahulu.');
        }
    });
});
</script>
@endsection

