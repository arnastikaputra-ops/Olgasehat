@extends('pemiliklapangan.layout.ownervenue')

@section('content')
<div class="content-wrapper schedule-page bg-light">
  <section class="content pt-4 pb-5">
    <div class="container-fluid">

      @php
        $backUrl = route('fasilitas.detail', $venue->id);
        $contextLabel = 'Kelola Fasilitas';
        $referer = request()->headers->get('referer');
        if ($referer && (str_contains($referer, '/papan/venue') || str_contains($referer, '/papan'))) {
            $backUrl = route('papan.venue.detail', $venue->id);
            $contextLabel = 'Kelola Jadwal';
        }
      @endphp
      <div class="d-flex align-items-center mb-4">
        <a href="{{ $backUrl }}" class="btn btn-light border rounded-circle mr-3">
          <i class="fas fa-arrow-left"></i>
        </a>
        <div>
          <p class="text-muted mb-1 small">{{ $contextLabel }} &nbsp; • &nbsp; {{ $venue->namavenue }}</p>
          <h3 class="mb-0 font-weight-bold text-dark">Detail Jadwal</h3>
        </div>
      </div>

      <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body d-flex flex-column flex-lg-row align-items-lg-center justify-content-between">
          <div class="d-flex align-items-center mb-3 mb-lg-0">
            <div class="lapangan-avatar mr-3">
              <i class="fas fa-futbol"></i>
            </div>
            <div>
              @php
                $kategoriList = is_array($venue->kategori) ? $venue->kategori : ($venue->kategori ? [$venue->kategori] : []);
                $kategoriDisplay = !empty($kategoriList) ? implode(', ', $kategoriList) : 'Olahraga';
              @endphp
              <span class="badge badge-pill badge-light text-primary font-weight-semibold px-3 py-2 mb-2">{{ $kategoriDisplay }}</span>
              <h4 class="text-dark font-weight-bold mb-1">{{ $lapangan->nama }}</h4>
              <p class="text-muted mb-0 small">Bagikan jadwal dan kelola harga untuk lapangan ini.</p>
            </div>
          </div>
          <div class="w-100 w-lg-auto d-flex align-items-center justify-content-lg-end flex-wrap">
            <label class="text-muted small font-weight-semibold mr-2 mb-2 mb-lg-0" for="lapanganSelector">Pilih Lapangan</label>
            <select id="lapanganSelector" class="form-control custom-select shadow-sm w-auto" onchange="window.location.href = this.value;">
              @foreach($availableLapangans as $item)
                <option value="{{ route('fasilitas.lapangan.jadwal', [$venue->id, $item->id]) }}" {{ $item->id === $lapangan->id ? 'selected' : '' }}>
                  {{ $venue->namavenue }} - {{ $item->nama }}
                </option>
              @endforeach
            </select>
          </div>
        </div>
      </div>

      @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center">
          <i class="fas fa-check-circle text-success mr-2"></i>
          <span>{{ session('success') }}</span>
        </div>
      @endif

      @if($errors->any())
        <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4">
          <strong>Terjadi kesalahan.</strong> Silakan periksa kembali input jadwal.
        </div>
      @endif

      <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body">
          <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between mb-4">
            <div class="d-flex align-items-center flex-wrap mb-3 mb-lg-0">
              <label for="datePicker" class="text-muted small font-weight-semibold mr-2 mr-lg-3 mb-2 mb-lg-0">Tanggal</label>
              
              {{-- Navigation Buttons --}}
              <div class="btn-group mr-2 mb-2 mb-lg-0" role="group">
                <button type="button" class="btn btn-outline-secondary btn-sm" id="prevDayBtn" title="Hari Sebelumnya">
                  <i class="fas fa-chevron-left"></i>
                </button>
                <button type="button" class="btn btn-outline-secondary btn-sm" id="nextDayBtn" title="Hari Selanjutnya">
                  <i class="fas fa-chevron-right"></i>
                </button>
              </div>
              
              {{-- Quick Jump Buttons --}}
              <div class="btn-group mr-2 mb-2 mb-lg-0" role="group">
                <button type="button" class="btn btn-outline-primary btn-sm" id="todayBtn" title="Hari Ini">Hari Ini</button>
                <button type="button" class="btn btn-outline-primary btn-sm" id="tomorrowBtn" title="Besok">Besok</button>
              </div>
              
              {{-- Date Picker --}}
              <form action="{{ route('fasilitas.lapangan.jadwal', [$venue->id, $lapangan->id]) }}" method="GET" id="dateForm" class="d-flex align-items-center mb-2 mb-lg-0">
                <div class="input-group date-picker shadow-sm border rounded">
                  <input
                    type="date"
                    id="datePicker"
                    name="date"
                    value="{{ $date->format('Y-m-d') }}"
                    class="form-control border-0">
                  <div class="input-group-append">
                    <span class="input-group-text bg-white border-0"><i class="far fa-calendar"></i></span>
                  </div>
                </div>
              </form>
              
              {{-- Loading Indicator --}}
              <div id="dateLoading" class="ml-2 d-none">
                <i class="fas fa-spinner fa-spin text-primary"></i>
              </div>
            </div>
            <div class="d-flex flex-column flex-sm-row gap-2">
              <button type="button" id="toggleBulkForm" class="btn btn-success font-weight-bold shadow-sm">
                <i class="fas fa-calendar-alt mr-2"></i><span class="d-none d-sm-inline">Generate Jadwal Bulk</span><span class="d-sm-none">Bulk</span>
              </button>
              <button type="button" id="toggleSlotForm" class="btn btn-primary font-weight-bold shadow-sm">
                <i class="fas fa-plus mr-2"></i>Tambah Slot
              </button>
              <button type="button" id="deleteDaySlotsBtn" class="btn btn-outline-danger font-weight-bold shadow-sm" data-toggle="modal" data-target="#deleteDaySlotsModal">
                <i class="fas fa-trash-alt mr-2"></i><span id="deleteDaySlotsBtnText">Hapus Semua Slot ({{ $date->format('d M') }})</span>
              </button>
            </div>
          </div>

          {{-- Form Generate Jadwal Bulk --}}
          <form action="{{ route('fasilitas.lapangan.jadwal.bulk', [$venue->id, $lapangan->id]) }}" method="POST" id="bulkForm" class="card border-0 shadow-sm rounded-4 bg-light slot-form-wrapper mb-4 d-none">
            @csrf
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0 font-weight-bold text-dark">Generate Jadwal Bulk (1 Bulan - 1 Tahun)</h6>
                <span class="badge badge-pill badge-success">Auto Generate</span>
              </div>
              <div class="alert alert-info border-0 rounded-lg mb-3">
                <i class="fas fa-info-circle mr-2"></i>
                <strong>Fitur Generate Bulk:</strong> Sistem akan otomatis membuat slot per 1 jam untuk setiap hari dalam rentang tanggal yang dipilih. Sangat cocok untuk membuat jadwal 1 bulan hingga 1 tahun sekaligus.
              </div>
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="small font-weight-semibold text-muted">Tanggal Mulai <span class="text-danger">*</span></label>
                  <input type="date" name="tanggal_mulai" id="bulk_tanggal_mulai" class="form-control rounded-lg @error('tanggal_mulai') is-invalid @enderror" value="{{ old('tanggal_mulai', $date->format('Y-m-d')) }}" required>
                  @error('tanggal_mulai')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
                <div class="col-md-6 mb-3">
                  <label class="small font-weight-semibold text-muted">Tanggal Akhir <span class="text-danger">*</span></label>
                  <input type="date" name="tanggal_akhir" id="bulk_tanggal_akhir" class="form-control rounded-lg @error('tanggal_akhir') is-invalid @enderror" value="{{ old('tanggal_akhir') }}" required>
                  @error('tanggal_akhir')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                  <small class="form-text text-muted">Maksimal 1 tahun dari tanggal mulai</small>
                  <small class="form-text text-danger d-none" id="bulk_tanggal_akhir_error">Tanggal akhir tidak boleh lebih dari 1 tahun dari tanggal mulai</small>
                </div>
                <div class="col-md-4 mb-3">
                  <label class="small font-weight-semibold text-muted">Jam Operasional Mulai <span class="text-danger">*</span></label>
                  <input type="time" name="jam_mulai" id="bulk_jam_mulai" class="form-control rounded-lg @error('jam_mulai') is-invalid @enderror" value="{{ old('jam_mulai', '08:00') }}" required>
                  @error('jam_mulai')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
                <div class="col-md-4 mb-3">
                  <label class="small font-weight-semibold text-muted">Jam Operasional Selesai <span class="text-danger">*</span></label>
                  <input type="time" name="jam_selesai" id="bulk_jam_selesai" class="form-control rounded-lg @error('jam_selesai') is-invalid @enderror" value="{{ old('jam_selesai', '22:00') }}" required>
                  @error('jam_selesai')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
                <div class="col-md-4 mb-3">
                  <label class="small font-weight-semibold text-muted">Harga Default (Rp) <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text bg-white border-right-0">Rp</span>
                    </div>
                    <input type="number" name="harga" id="bulk_harga" class="form-control border-left-0 rounded-right @error('harga') is-invalid @enderror" value="{{ old('harga', '225000') }}" min="0" required>
                    @error('harga')
                      <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="small font-weight-semibold text-muted">Skip Hari Tertentu</label>
                  <div class="d-flex flex-wrap gap-2">
                    <div class="form-check">
                      <input type="checkbox" name="skip_days[]" value="0" class="form-check-input" id="skipSunday">
                      <label class="form-check-label" for="skipSunday">Minggu</label>
                    </div>
                    <div class="form-check">
                      <input type="checkbox" name="skip_days[]" value="6" class="form-check-input" id="skipSaturday">
                      <label class="form-check-label" for="skipSaturday">Sabtu</label>
                    </div>
                    <div class="form-check">
                      <input type="checkbox" name="skip_days[]" value="weekend" class="form-check-input" id="skipWeekend">
                      <label class="form-check-label" for="skipWeekend">Weekend (Sabtu & Minggu)</label>
                    </div>
                  </div>
                  <small class="form-text text-muted">Pilih hari yang ingin di-skip dari jadwal</small>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="small font-weight-semibold text-muted">Status Default</label>
                  <select name="status" id="bulk_status" class="form-control rounded-lg @error('status') is-invalid @enderror">
                    <option value="available" {{ old('status', 'available') === 'available' ? 'selected' : '' }}>Tersedia</option>
                    <option value="blocked" {{ old('status') === 'blocked' ? 'selected' : '' }}>Blokir</option>
                  </select>
                  @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>
              <div class="mb-3" id="bulkPreviewSlots" style="display: none;">
                <label class="small font-weight-semibold text-muted mb-2 d-block">Preview:</label>
                <div class="bg-white border rounded-lg p-3" id="bulkPreviewContent">
                  <p class="text-muted small mb-0">Isi semua field untuk melihat preview</p>
                </div>
              </div>
              <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-outline-secondary mr-2" id="cancelBulkForm">Batal</button>
                <button type="submit" class="btn btn-success font-weight-bold">
                  <i class="fas fa-magic mr-2"></i>Generate Jadwal
                </button>
              </div>
            </div>
          </form>

          <form action="{{ route('fasilitas.lapangan.jadwal.store', [$venue->id, $lapangan->id]) }}" method="POST" id="slotForm" class="card border-0 shadow-sm rounded-4 bg-light slot-form-wrapper mb-4 d-none">
            @csrf
            <input type="hidden" name="tanggal" id="slotFormTanggal" value="{{ $date->format('Y-m-d') }}">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0 font-weight-bold text-dark">Tambah Jadwal Slot</h6>
                <span class="badge badge-pill badge-secondary">Optional</span>
              </div>
              <div class="alert alert-info border-0 rounded-lg mb-3 d-none" id="generateInfo">
                <i class="fas fa-info-circle mr-2"></i>
                <strong>Mode Generate Multiple Slots:</strong> Sistem akan otomatis membuat slot per 1 jam dari jam mulai sampai jam selesai dengan harga yang sama.
              </div>
              <div class="row">
                <div class="col-12 mb-3">
                  <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="generateMultiple" name="generate_multiple" value="1" {{ old('generate_multiple') ? 'checked' : '' }}>
                    <label class="custom-control-label font-weight-semibold text-dark" for="generateMultiple">
                      <i class="fas fa-magic mr-2 text-primary"></i>Generate Multiple Slots (per 1 jam)
                    </label>
                    <small class="form-text text-muted d-block mt-1">Aktifkan untuk membuat slot otomatis per 1 jam dari jam mulai sampai jam selesai</small>
                  </div>
                </div>
                <div class="col-md-3 mb-3">
                  <label class="small font-weight-semibold text-muted">Jam Mulai</label>
                  <input type="time" name="jam_mulai" id="jam_mulai" class="form-control rounded-lg @error('jam_mulai') is-invalid @enderror" value="{{ old('jam_mulai') }}" placeholder="08:00" required>
                  @error('jam_mulai')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
                <div class="col-md-3 mb-3">
                  <label class="small font-weight-semibold text-muted">Jam Selesai</label>
                  <input type="time" name="jam_selesai" id="jam_selesai" class="form-control rounded-lg @error('jam_selesai') is-invalid @enderror" value="{{ old('jam_selesai') }}" placeholder="09:00" required>
                  @error('jam_selesai')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
                <div class="col-md-3 mb-3">
                  <label class="small font-weight-semibold text-muted">Harga Awal / Normal <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text bg-white border-right-0">Rp</span>
                    </div>
                    <input type="number" name="harga_awal" id="harga_awal" class="form-control border-left-0 rounded-right @error('harga_awal') is-invalid @enderror" value="{{ old('harga_awal') }}" placeholder="Contoh: 50000" min="0" required>
                    @error('harga_awal')
                      <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
                <div class="col-md-3 mb-3">
                  <label class="small font-weight-semibold text-muted">
                    Harga Promo / Setelah Diskon
                    <span class="badge badge-pill badge-secondary">Optional</span>
                  </label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text bg-white border-right-0">Rp</span>
                    </div>
                    <input type="number" name="harga" id="harga" class="form-control border-left-0 rounded-right @error('harga') is-invalid @enderror" value="{{ old('harga') }}" placeholder="Kosongkan jika tidak promo" min="0">
                    @error('harga')
                      <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                  </div>
                  <small class="form-text text-muted">Harga promo akan menjadi harga jual (harga awal dicoret)</small>
                </div>
                <div class="col-md-3 mb-3">
                  <label class="small font-weight-semibold text-muted">Status Ketersediaan</label>
                  <select name="status" class="form-control rounded-lg @error('status') is-invalid @enderror">
                    <option value="available" {{ old('status', 'available') === 'available' ? 'selected' : '' }}>Tersedia</option>
                    <option value="booked" {{ old('status') === 'booked' ? 'selected' : '' }}>Sudah Dibooking</option>
                    <option value="blocked" {{ old('status') === 'blocked' ? 'selected' : '' }}>Blokir</option>
                  </select>
                  @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
                <div class="col-md-3 mb-3">
                  <label class="small font-weight-semibold text-muted">Status Promo</label>
                  <select name="promo_status" class="form-control rounded-lg @error('promo_status') is-invalid @enderror">
                    <option value="none" {{ old('promo_status', 'none') === 'none' ? 'selected' : '' }}>Tidak Ada</option>
                    <option value="promo" {{ old('promo_status') === 'promo' ? 'selected' : '' }}>Promo</option>
                  </select>
                  @error('promo_status')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
                <div class="col-md-9 mb-3">
                  <label class="small font-weight-semibold text-muted">Catatan (opsional)</label>
                  <input type="text" name="catatan" class="form-control rounded-lg @error('catatan') is-invalid @enderror" value="{{ old('catatan') }}" placeholder="Contoh: Diskon 20% untuk member komunitas">
                  @error('catatan')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>
              <div class="mb-3" id="previewSlots" style="display: none;">
                <label class="small font-weight-semibold text-muted mb-2 d-block">Preview Slot yang Akan Dibuat:</label>
                <div class="bg-white border rounded-lg p-3" id="previewContent">
                  <p class="text-muted small mb-0">Isi jam mulai, jam selesai, dan harga untuk melihat preview</p>
                </div>
              </div>
              <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-outline-secondary mr-2" id="cancelSlotForm">Batal</button>
                <button type="submit" class="btn btn-primary font-weight-bold">Simpan Slot</button>
              </div>
            </div>
          </form>

          <div id="slotsContainer">
            @if($timeslots->isEmpty())
              <div class="empty-state border-0 rounded-4 text-center py-5">
                <div class="empty-icon mx-auto mb-3">
                  <i class="fas fa-calendar-plus"></i>
                </div>
                <h5 class="font-weight-bold text-dark mb-2">Jadwal lapangan belum tersedia</h5>
                <p class="text-muted small mb-0 px-4">
                  Klik tombol <strong>Tambah Slot</strong> untuk membuat jadwal pertama Anda. Tentukan rentang waktu, harga, status ketersediaan, dan promo bila diperlukan.
                </p>
              </div>
            @else
              <div class="d-flex flex-wrap schedule-slot-grid">
                @foreach($timeslots as $slot)
                @php
                  $statusClass = [
                    'available' => 'slot-available',
                    'booked' => 'slot-booked',
                    'blocked' => 'slot-blocked',
                  ][$slot->status] ?? 'slot-available';
                @endphp
                <div class="schedule-slot {{ $statusClass }}">
                  <div class="slot-actions">
                    <button type="button" class="btn btn-sm btn-light edit-slot-btn" data-slot-id="{{ $slot->id }}" data-toggle="modal" data-target="#editSlotModal" title="Edit Slot">
                      <i class="fas fa-edit"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-light delete-slot-btn" data-slot-id="{{ $slot->id }}" title="Hapus Slot">
                      <i class="fas fa-trash text-danger"></i>
                    </button>
                  </div>
                  @if($slot->is_promo)
                    <span class="slot-pill">Promo</span>
                  @endif
                  <h6 class="slot-time mb-1">{{ \Illuminate\Support\Carbon::parse($slot->jam_mulai)->format('H:i') }} - {{ \Illuminate\Support\Carbon::parse($slot->jam_selesai)->format('H:i') }}</h6>
                  @if($slot->status === 'booked')
                    <p class="slot-price text-muted mb-1" style="text-decoration: line-through; text-decoration-color: #6c757d; text-decoration-thickness: 2px; opacity: 0.7;">Rp {{ number_format($slot->harga, 0, ',', '.') }}</p>
                    <span class="slot-status">Booked</span>
                  @elseif($slot->status === 'blocked')
                    <p class="slot-price text-muted mb-1">Rp {{ number_format($slot->harga, 0, ',', '.') }}</p>
                    <span class="slot-status text-danger font-weight-bold">Blokir</span>
                  @else
                    @if(($slot->harga_awal && $slot->harga_awal > $slot->harga) || ($slot->is_promo && $slot->harga_awal && $slot->harga_awal >= $slot->harga))
                      <p class="slot-price text-muted mb-1" style="font-size: 0.85rem; text-decoration: line-through; text-decoration-color: #6c757d; text-decoration-thickness: 2px; opacity: 0.7;">Rp {{ number_format($slot->harga_awal, 0, ',', '.') }}</p>
                      <p class="slot-price mb-1 text-primary font-weight-bold">Rp {{ number_format($slot->harga, 0, ',', '.') }}</p>
                    @else
                      <p class="slot-price mb-1">Rp {{ number_format($slot->harga, 0, ',', '.') }}</p>
                    @endif
                    <span class="slot-status text-success font-weight-bold">Tersedia</span>
                  @endif
                  @if($slot->catatan)
                    <p class="slot-note text-muted small mt-2 mb-0">
                      <i class="fas fa-info-circle mr-1"></i>{{ $slot->catatan }}
                    </p>
                  @endif
                </div>
                @endforeach
              </div>
            @endif
          </div>
        </div>
      </div>

    </div>
  </section>
</div>

<!-- Modal Edit Slot -->
<div class="modal fade" id="editSlotModal" tabindex="-1" role="dialog" aria-labelledby="editSlotModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content rounded-4 border-0 shadow-lg">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title font-weight-bold" id="editSlotModalLabel">Edit Jadwal Slot</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="editSlotForm" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-body pt-0">
          <input type="hidden" name="tanggal" id="editSlotFormTanggal" value="{{ $date->format('Y-m-d') }}">
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="small font-weight-semibold text-muted">Jam Mulai</label>
              <input type="time" name="jam_mulai" id="edit_jam_mulai" class="form-control rounded-lg" required>
            </div>
            <div class="col-md-6 mb-3">
              <label class="small font-weight-semibold text-muted">Jam Selesai</label>
              <input type="time" name="jam_selesai" id="edit_jam_selesai" class="form-control rounded-lg" required>
            </div>
            <div class="col-md-6 mb-3">
              <label class="small font-weight-semibold text-muted">Harga Awal / Normal <span class="text-danger">*</span></label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text bg-white border-right-0">Rp</span>
                </div>
                <input type="number" name="harga_awal" id="edit_harga_awal" class="form-control border-left-0 rounded-right" min="0" required placeholder="Contoh: 50000">
              </div>
            </div>
            <div class="col-md-6 mb-3">
              <label class="small font-weight-semibold text-muted">Harga Diskon / Promo <span class="badge badge-pill badge-secondary">Optional</span></label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text bg-white border-right-0">Rp</span>
                </div>
                <input type="number" name="harga" id="edit_harga" class="form-control border-left-0 rounded-right" min="0" placeholder="Kosongkan jika tidak promo">
              </div>
              <small class="form-text text-muted">Isi jika ada promo (harus < harga awal)</small>
            </div>
            <div class="col-md-6 mb-3">
              <label class="small font-weight-semibold text-muted">Status Ketersediaan</label>
              <select name="status" id="edit_status" class="form-control rounded-lg">
                <option value="available">Tersedia</option>
                <option value="booked">Sudah Dibooking</option>
                <option value="blocked">Blokir</option>
              </select>
            </div>
            <div class="col-md-6 mb-3">
              <label class="small font-weight-semibold text-muted">Status Promo</label>
              <select name="promo_status" id="edit_promo_status" class="form-control rounded-lg">
                <option value="none">Tidak Ada</option>
                <option value="promo">Promo</option>
              </select>
            </div>
            <div class="col-md-12 mb-3">
              <label class="small font-weight-semibold text-muted">Catatan (opsional)</label>
              <input type="text" name="catatan" id="edit_catatan" class="form-control rounded-lg" placeholder="Contoh: Diskon 20% untuk member komunitas">
            </div>
          </div>
        </div>
        <div class="modal-footer border-0 pt-0">
          <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary font-weight-bold">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Delete Confirmation -->
<div class="modal fade" id="deleteSlotModal" tabindex="-1" role="dialog" aria-labelledby="deleteSlotModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content rounded-4 border-0 shadow-lg">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title font-weight-bold text-danger" id="deleteSlotModalLabel">Hapus Slot</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="deleteSlotForm" method="POST">
        @csrf
        @method('DELETE')
        <div class="modal-body pt-0">
          <p class="mb-0">Apakah Anda yakin ingin menghapus slot jadwal ini? Tindakan ini tidak dapat dibatalkan.</p>
        </div>
        <div class="modal-footer border-0 pt-0">
          <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-danger font-weight-bold">Hapus</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Delete Day Slots Confirmation -->
<div class="modal fade" id="deleteDaySlotsModal" tabindex="-1" role="dialog" aria-labelledby="deleteDaySlotsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content rounded-4 border-0 shadow-lg">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title font-weight-bold text-danger" id="deleteDaySlotsModalLabel">Hapus Semua Slot ({{ $date->format('d M') }})</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="deleteDaySlotsForm" action="{{ route('fasilitas.lapangan.jadwal.delete_by_date', [$venue->id, $lapangan->id]) }}" method="POST">
        @csrf
        @method('DELETE')
        <div class="modal-body pt-2">
          <input type="hidden" name="tanggal" id="deleteDaySlotsTanggal" value="{{ $date->format('Y-m-d') }}">
          <p class="mb-0">Apakah Anda yakin ingin menghapus **semua slot jadwal** pada tanggal <strong id="deleteDaySlotsModalDate">{{ $date->format('d M Y') }}</strong>? Tindakan ini akan menghapus seluruh slot kosong dan booked pada hari tersebut dan tidak dapat dibatalkan.</p>
        </div>
        <div class="modal-footer border-0 pt-0">
          <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-danger font-weight-bold">Ya, Hapus Semua</button>
        </div>
      </form>
    </div>
  </div>
</div>

<style>
  .schedule-page {
    min-height: 100vh;
  }
  .rounded-4 {
    border-radius: 20px;
  }
  .font-weight-semibold {
    font-weight: 600;
  }
  .lapangan-avatar {
    width: 64px;
    height: 64px;
    border-radius: 18px;
    background: rgba(1, 61, 157, 0.12);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #013d9d;
    font-size: 26px;
  }
  .custom-select {
    min-width: 260px;
    border-radius: 12px;
  }
  .date-picker input {
    border-radius: 12px 0 0 12px;
    padding: 0.75rem 1rem;
  }
  .date-picker .input-group-text {
    border-radius: 0 12px 12px 0;
  }
  .schedule-slot-grid {
    gap: 16px;
  }
  .schedule-slot {
    width: 160px;
    border-radius: 16px;
    padding: 18px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    position: relative;
    text-align: center;
    background: #f8faff;
    border: 1px solid transparent;
    box-shadow: 0 6px 14px rgba(1, 61, 157, 0.08);
    transition: transform 0.2s, box-shadow 0.2s;
  }
  .schedule-slot:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(1, 61, 157, 0.12);
  }
  .slot-actions {
    position: absolute;
    top: 8px;
    right: 8px;
    display: flex;
    gap: 4px;
    opacity: 0;
    transition: opacity 0.2s;
  }
  .schedule-slot:hover .slot-actions {
    opacity: 1;
  }
  .slot-actions .btn {
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 0.75rem;
    line-height: 1;
  }
  .slot-available {
    border-color: rgba(43, 138, 247, 0.18);
  }
  .slot-booked {
    background: #f5f5f5;
    border-color: #e0e0e0;
  }
  .slot-blocked {
    background: #fdf3f3;
    border-color: #f0c0c0;
  }
  .slot-pill {
    position: absolute;
    top: -10px;
    padding: 4px 10px;
    border-radius: 999px;
    font-size: 0.65rem;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    background: #12d18e;
    color: #fff;
    font-weight: 700;
  }
  .schedule-slot .slot-time {
    font-weight: 700;
    color: #1d2c5b;
  }
  .slot-price {
    font-weight: 700;
    color: #1d2c5b;
  }
  .slot-price[style*="line-through"] {
    text-decoration: line-through !important;
    text-decoration-color: #6c757d !important;
    text-decoration-thickness: 2px !important;
    opacity: 0.7;
  }
  .slot-status {
    font-size: 0.8rem;
    font-weight: 600;
  }
  .slot-note {
    max-width: 180px;
  }
  .slot-form-wrapper {
    border: 1px dashed rgba(1, 61, 157, 0.2) !important;
  }
  .rounded-lg {
    border-radius: 12px !important;
  }
  .empty-state {
    background: #f8faff;
    border: 1px dashed rgba(1, 61, 157, 0.2);
  }
  .empty-icon {
    width: 64px;
    height: 64px;
    border-radius: 16px;
    background: rgba(43, 138, 247, 0.15);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #2b8af7;
    font-size: 26px;
  }
  @media (max-width: 575.98px) {
    .schedule-slot {
      width: calc(50% - 8px);
    }
  }
  @media (max-width: 420px) {
    .schedule-slot {
      width: 100%;
    }
    .custom-select {
      min-width: 100%;
    }
  }
  .custom-switch .custom-control-label {
    cursor: pointer;
  }
  #previewSlots .badge {
    font-size: 0.75rem;
    font-weight: 600;
  }
  .gap-2 {
    gap: 0.5rem;
  }
  
  /* Responsive Alert Styles */
  .alert-bulk-success,
  .alert-bulk-dates {
    padding: 0.875rem 1rem;
    font-size: 0.9rem;
  }
  
  .alert-bulk-success i {
    font-size: 1.1rem;
  }
  
  .alert-bulk-dates {
    padding: 1rem;
  }
  
  .alert-bulk-dates strong {
    font-size: 0.95rem;
  }
  
  .date-badges-container {
    gap: 0.375rem;
  }
  
  .date-badges-container .badge {
    font-size: 0.75rem;
    padding: 0.375rem 0.625rem;
    font-weight: 500;
  }
  
  /* Mobile Responsive */
  @media (max-width: 768px) {
    .alert-bulk-success,
    .alert-bulk-dates {
      padding: 0.75rem;
      font-size: 0.85rem;
    }
    
    .alert-bulk-dates strong {
      font-size: 0.9rem;
    }
    
    .date-badges-container .badge {
      font-size: 0.7rem;
      padding: 0.3rem 0.5rem;
    }
    
    .alert-bulk-dates .d-flex {
      flex-direction: column;
    }
    
    .alert-bulk-dates i {
      margin-bottom: 0.5rem;
    }
    
    .date-picker {
      min-width: 160px;
    }
    
    .date-picker input {
      font-size: 0.875rem;
      padding: 0.5rem 0.75rem;
    }
    
    .btn-group .btn-sm {
      padding: 0.375rem 0.5rem;
      font-size: 0.8rem;
    }
  }
  
  @media (max-width: 576px) {
    .alert-bulk-success,
    .alert-bulk-dates {
      padding: 0.625rem;
      font-size: 0.8rem;
    }
    
    .date-badges-container {
      gap: 0.25rem;
    }
    
    .date-badges-container .badge {
      font-size: 0.65rem;
      padding: 0.25rem 0.4rem;
    }
    
    .date-picker {
      min-width: 140px;
    }
    
    .date-picker input {
      font-size: 0.8rem;
      padding: 0.4rem 0.6rem;
    }
    
    .btn-group .btn-sm {
      padding: 0.3rem 0.4rem;
      font-size: 0.75rem;
    }
    
    #toggleBulkForm,
    #toggleSlotForm {
      font-size: 0.875rem;
      padding: 0.5rem 0.75rem;
    }
    
    #toggleBulkForm i,
    #toggleSlotForm i {
      margin-right: 0.5rem !important;
    }
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    var toggleButton = document.getElementById('toggleSlotForm');
    var cancelButton = document.getElementById('cancelSlotForm');
    var slotForm = document.getElementById('slotForm');
    var generateMultiple = document.getElementById('generateMultiple');
    var generateInfo = document.getElementById('generateInfo');
    var jamMulai = document.getElementById('jam_mulai');
    var jamSelesai = document.getElementById('jam_selesai');
    var harga = document.getElementById('harga');
    var previewSlots = document.getElementById('previewSlots');
    var previewContent = document.getElementById('previewContent');
    
    // Bulk Form Elements
    var toggleBulkButton = document.getElementById('toggleBulkForm');
    var cancelBulkButton = document.getElementById('cancelBulkForm');
    var bulkForm = document.getElementById('bulkForm');
    var bulkTanggalMulai = document.getElementById('bulk_tanggal_mulai');
    var bulkTanggalAkhir = document.getElementById('bulk_tanggal_akhir');
    var bulkJamMulai = document.getElementById('bulk_jam_mulai');
    var bulkJamSelesai = document.getElementById('bulk_jam_selesai');
    var bulkHarga = document.getElementById('bulk_harga');
    var bulkPreviewSlots = document.getElementById('bulkPreviewSlots');
    var bulkPreviewContent = document.getElementById('bulkPreviewContent');
    
    // Date Navigation Variables
    var datePicker = document.getElementById('datePicker');
    var dateForm = document.getElementById('dateForm');
    var dateLoading = document.getElementById('dateLoading');
    var slotsContainer = document.getElementById('slotsContainer');
    var prevDayBtn = document.getElementById('prevDayBtn');
    var nextDayBtn = document.getElementById('nextDayBtn');
    var todayBtn = document.getElementById('todayBtn');
    var tomorrowBtn = document.getElementById('tomorrowBtn');
    var currentDate = new Date(datePicker.value);
    
    // API URL
    var apiUrl = '{{ route("fasilitas.lapangan.jadwal.api", [$venue->id, $lapangan->id]) }}';
    
    // Function to format date to Y-m-d
    function formatDate(date) {
      var year = date.getFullYear();
      var month = String(date.getMonth() + 1).padStart(2, '0');
      var day = String(date.getDate()).padStart(2, '0');
      return year + '-' + month + '-' + day;
    }
    
    // Function to load slots via AJAX
    function loadSlotsByDate(date, updateUrl = true) {
      if (dateLoading) dateLoading.classList.remove('d-none');
      
      // Ensure date is a Date object
      if (!(date instanceof Date)) {
        if (typeof date === 'string') {
          date = new Date(date);
        } else {
          console.error('Invalid date format:', date);
          if (dateLoading) dateLoading.classList.add('d-none');
          return;
        }
      }
      
      // Check if date is valid
      if (isNaN(date.getTime())) {
        console.error('Invalid date:', date);
        if (dateLoading) dateLoading.classList.add('d-none');
        return;
      }
      
      var dateStr = formatDate(date);
      
      // Debug log
      console.log('Loading slots for date:', dateStr);
      
      fetch(apiUrl + '?date=' + dateStr, {
        method: 'GET',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json',
        }
      })
      .then(response => {
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        return response.json();
      })
      .then(data => {
        console.log('Slots loaded:', data);
        console.log('Debug info:', data.debug);
        console.log('Date input:', data.date_input);
        console.log('Query date:', data.date);
        console.log('Total slots found:', data.timeslots ? data.timeslots.length : 0);
        
        if (data.success) {
          // Update date picker
          if (datePicker) {
            datePicker.value = dateStr;
          }
          currentDate = new Date(date);
          
          // Update URL without reload
          if (updateUrl) {
            var newUrl = new URL(window.location.href);
            newUrl.searchParams.set('date', dateStr);
            window.history.pushState({path: newUrl.href}, '', newUrl.href);
          }
          
          // Update form tanggal field
          var slotFormTanggal = document.getElementById('slotFormTanggal');
          if (slotFormTanggal) {
            slotFormTanggal.value = dateStr;
          }
          
          // Update edit form tanggal field
          var editSlotFormTanggal = document.getElementById('editSlotFormTanggal');
          if (editSlotFormTanggal) {
            editSlotFormTanggal.value = dateStr;
          }
          
          // Update bulk form tanggal field
          var bulkTanggalMulai = document.getElementById('bulk_tanggal_mulai');
          if (bulkTanggalMulai && !bulkTanggalMulai.value) {
            bulkTanggalMulai.value = dateStr;
          }
          
          // Update delete day slots form date values
          var deleteDaySlotsTanggal = document.getElementById('deleteDaySlotsTanggal');
          if (deleteDaySlotsTanggal) {
            deleteDaySlotsTanggal.value = dateStr;
          }
          
          var deleteDaySlotsModalDate = document.getElementById('deleteDaySlotsModalDate');
          if (deleteDaySlotsModalDate) {
            var d = new Date(dateStr + 'T00:00:00');
            deleteDaySlotsModalDate.textContent = d.toLocaleDateString('id-ID', {day: '2-digit', month: 'short', year: 'numeric'});
          }
          
          var deleteDaySlotsBtnText = document.getElementById('deleteDaySlotsBtnText');
          if (deleteDaySlotsBtnText) {
            var d = new Date(dateStr + 'T00:00:00');
            deleteDaySlotsBtnText.textContent = 'Hapus Semua Slot (' + d.toLocaleDateString('id-ID', {day: '2-digit', month: 'short'}) + ')';
          }
          
          var deleteDaySlotsModalLabel = document.getElementById('deleteDaySlotsModalLabel');
          if (deleteDaySlotsModalLabel) {
            var d = new Date(dateStr + 'T00:00:00');
            deleteDaySlotsModalLabel.textContent = 'Hapus Semua Slot (' + d.toLocaleDateString('id-ID', {day: '2-digit', month: 'short'}) + ')';
          }
          
          // Render slots
          if (data.timeslots && data.timeslots.length > 0) {
            console.log('Rendering', data.timeslots.length, 'slots');
            renderSlots(data.timeslots);
          } else {
            console.warn('No slots found for date:', dateStr);
            renderSlots([]);
          }
        } else {
          console.error('Failed to load slots:', data);
          renderSlots([]);
        }
      })
      .catch(error => {
        console.error('Error loading slots:', error);
        alert('Terjadi kesalahan saat memuat jadwal. Silakan refresh halaman.');
      })
      .finally(() => {
        if (dateLoading) dateLoading.classList.add('d-none');
      });
    }
    
    // Function to render slots
    function renderSlots(timeslots) {
      if (!slotsContainer) return;
      
      if (timeslots.length === 0) {
        slotsContainer.innerHTML = `
          <div class="empty-state border-0 rounded-4 text-center py-5">
            <div class="empty-icon mx-auto mb-3">
              <i class="fas fa-calendar-plus"></i>
            </div>
            <h5 class="font-weight-bold text-dark mb-2">Jadwal lapangan belum tersedia</h5>
            <p class="text-muted small mb-0 px-4">
              Klik tombol <strong>Tambah Slot</strong> untuk membuat jadwal pertama Anda.
            </p>
          </div>
        `;
        return;
      }
      
      var html = '<div class="d-flex flex-wrap schedule-slot-grid">';
      timeslots.forEach(function(slot) {
        var statusClass = {
          'available': 'slot-available',
          'booked': 'slot-booked',
          'blocked': 'slot-blocked'
        }[slot.status] || 'slot-available';
        
        var promoBadge = slot.is_promo ? '<span class="slot-pill">Promo</span>' : '';
        var statusText = slot.status === 'booked' ? 'Booked' : 
                        slot.status === 'blocked' ? '<span class="text-danger font-weight-bold">Blokir</span>' : 
                        '<span class="text-success font-weight-bold">Tersedia</span>';
        
        var priceHtml = '';
        if (slot.status === 'booked') {
          priceHtml = '<p class="slot-price text-muted mb-1" style="text-decoration: line-through; text-decoration-color: #6c757d; text-decoration-thickness: 2px; opacity: 0.7;">Rp ' + formatNumber(slot.harga) + '</p>';
        } else if ((slot.harga_awal && slot.harga_awal > slot.harga) || (slot.is_promo && slot.harga_awal && slot.harga_awal >= slot.harga)) {
          priceHtml = '<p class="slot-price text-muted mb-1" style="font-size: 0.85rem; text-decoration: line-through; text-decoration-color: #6c757d; text-decoration-thickness: 2px; opacity: 0.7;">Rp ' + formatNumber(slot.harga_awal) + '</p>' +
                      '<p class="slot-price mb-1 text-primary font-weight-bold">Rp ' + formatNumber(slot.harga) + '</p>';
        } else {
          priceHtml = '<p class="slot-price mb-1">Rp ' + formatNumber(slot.harga) + '</p>';
        }
        
        var noteHtml = slot.catatan ? '<p class="slot-note text-muted small mt-2 mb-0"><i class="fas fa-info-circle mr-1"></i>' + slot.catatan + '</p>' : '';
        
        html += `
          <div class="schedule-slot ${statusClass}">
            <div class="slot-actions">
              <button type="button" class="btn btn-sm btn-light edit-slot-btn" data-slot-id="${slot.id}" data-toggle="modal" data-target="#editSlotModal" title="Edit Slot">
                <i class="fas fa-edit"></i>
              </button>
              <button type="button" class="btn btn-sm btn-light delete-slot-btn" data-slot-id="${slot.id}" title="Hapus Slot">
                <i class="fas fa-trash text-danger"></i>
              </button>
            </div>
            ${promoBadge}
            <h6 class="slot-time mb-1">${slot.jam_mulai} - ${slot.jam_selesai}</h6>
            ${priceHtml}
            <span class="slot-status">${statusText}</span>
            ${noteHtml}
          </div>
        `;
      });
      html += '</div>';
      
      slotsContainer.innerHTML = html;
      
      // Re-attach event listeners for edit/delete buttons
      attachSlotEventListeners();
    }
    
    // Function to format number
    function formatNumber(num) {
      return new Intl.NumberFormat('id-ID').format(num);
    }
    
    // Function to attach event listeners for slot buttons
    function attachSlotEventListeners() {
      // Re-attach edit button listeners
      var editButtons = document.querySelectorAll('.edit-slot-btn');
      editButtons.forEach(function(btn) {
        btn.addEventListener('click', function(e) {
          e.preventDefault();
          var slotId = this.getAttribute('data-slot-id');
          var editUrl = `{{ route('fasilitas.lapangan.jadwal.edit', [$venue->id, $lapangan->id, ':slotId']) }}`.replace(':slotId', slotId);
          
          var submitBtn = editSlotForm.querySelector('button[type="submit"]');
          var originalText = submitBtn ? submitBtn.innerHTML : '';
          if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Memuat...';
          }
          
          fetch(editUrl, {
            method: 'GET',
            headers: {
              'X-Requested-With': 'XMLHttpRequest',
              'Accept': 'application/json',
            }
          })
          .then(response => response.json())
          .then(data => {
            if (data.success && data.slot) {
              var slot = data.slot;
              var formatTime = function(timeStr) {
                if (!timeStr) return '';
                if (timeStr.match(/^\d{2}:\d{2}$/)) return timeStr;
                var parts = timeStr.split(':');
                if (parts.length >= 2) {
                  return parts[0].padStart(2, '0') + ':' + parts[1].padStart(2, '0');
                }
                return timeStr;
              };
              
              document.getElementById('edit_jam_mulai').value = formatTime(slot.jam_mulai);
              document.getElementById('edit_jam_selesai').value = formatTime(slot.jam_selesai);
              
              // Set harga_awal (Harga Normal): jika ada slot.harga_awal gunakan, jika tidak gunakan slot.harga
              var hargaAwalVal = slot.harga_awal || slot.harga || 0;
              document.getElementById('edit_harga_awal').value = hargaAwalVal;
              
              // Set harga (Harga Promo/Diskon): jika promo & harga < harga_awal, gunakan slot.harga, jika tidak kosongkan
              var hargaDiskonVal = (slot.is_promo && slot.harga_awal && slot.harga < slot.harga_awal) ? slot.harga : '';
              document.getElementById('edit_harga').value = hargaDiskonVal;
              
              document.getElementById('edit_status').value = slot.status || 'available';
              document.getElementById('edit_promo_status').value = slot.is_promo ? 'promo' : 'none';
              document.getElementById('edit_catatan').value = slot.catatan || '';
              
              var updateUrl = `{{ route('fasilitas.lapangan.jadwal.update', [$venue->id, $lapangan->id, ':slotId']) }}`.replace(':slotId', slotId);
              editSlotForm.action = updateUrl;
              
              if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
              }
              
              if (typeof $ !== 'undefined' && $.fn.modal) {
                $(editSlotModal).modal('show');
              }
            }
          })
          .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memuat data slot.');
            if (submitBtn) {
              submitBtn.disabled = false;
              submitBtn.innerHTML = originalText;
            }
          });
        });
      });
      
      // Re-attach delete button listeners
      var deleteButtons = document.querySelectorAll('.delete-slot-btn');
      deleteButtons.forEach(function(btn) {
        btn.addEventListener('click', function(e) {
          e.preventDefault();
          e.stopPropagation();
          
          var slotId = this.getAttribute('data-slot-id');
          if (!slotId) {
            console.error('Slot ID tidak ditemukan');
            return false;
          }
          
          var deleteUrl = `{{ route('fasilitas.lapangan.jadwal.delete', [$venue->id, $lapangan->id, ':slotId']) }}`.replace(':slotId', slotId);
          deleteSlotForm.action = deleteUrl;
          
          if (typeof $ !== 'undefined' && $.fn.modal) {
            $(deleteSlotModal).modal('show');
          }
          
          return false;
        });
      });
    }
    
    // Date picker change event - Auto submit via AJAX
    if (datePicker) {
      datePicker.addEventListener('change', function() {
        var selectedDate = new Date(this.value);
        if (!isNaN(selectedDate.getTime())) {
          loadSlotsByDate(selectedDate);
        }
      });
    }
    
    // Previous day button
    if (prevDayBtn) {
      prevDayBtn.addEventListener('click', function() {
        var prevDate = new Date(currentDate);
        prevDate.setDate(prevDate.getDate() - 1);
        loadSlotsByDate(prevDate);
      });
    }
    
    // Next day button
    if (nextDayBtn) {
      nextDayBtn.addEventListener('click', function() {
        var nextDate = new Date(currentDate);
        nextDate.setDate(nextDate.getDate() + 1);
        loadSlotsByDate(nextDate);
      });
    }
    
    // Today button
    if (todayBtn) {
      todayBtn.addEventListener('click', function() {
        loadSlotsByDate(new Date());
      });
    }
    
    // Tomorrow button
    if (tomorrowBtn) {
      tomorrowBtn.addEventListener('click', function() {
        var tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        loadSlotsByDate(tomorrow);
      });
    }

    function toggleForm(show) {
      if (!slotForm) return;
      if (show) {
        slotForm.classList.remove('d-none');
        slotForm.scrollIntoView({ behavior: 'smooth', block: 'start' });
      } else {
        slotForm.classList.add('d-none');
      }
    }

    function updatePreview() {
      if (!generateMultiple || !generateMultiple.checked) {
        previewSlots.style.display = 'none';
        return;
      }

      var hargaAwalElem = document.getElementById('harga_awal');
      var hargaPromoElem = document.getElementById('harga');

      var hargaAwalVal = hargaAwalElem ? parseInt(hargaAwalElem.value) || 0 : 0;
      var hargaPromoVal = hargaPromoElem ? parseInt(hargaPromoElem.value) || 0 : 0;
      var finalPrice = (hargaPromoVal > 0 && hargaPromoVal < hargaAwalVal) ? hargaPromoVal : hargaAwalVal;

      if (!startTime || !endTime || !hargaAwalVal) {
        previewSlots.style.display = 'none';
        return;
      }

      // Parse waktu
      var start = new Date('2000-01-01T' + startTime + ':00');
      var end = new Date('2000-01-01T' + endTime + ':00');

      if (start >= end) {
        previewContent.innerHTML = '<p class="text-danger small mb-0">Jam selesai harus lebih besar dari jam mulai</p>';
        previewSlots.style.display = 'block';
        return;
      }

      // Generate slots per 1 jam
      var slots = [];
      var current = new Date(start);
      
      while (current < end) {
        var slotStart = new Date(current);
        var slotEnd = new Date(current);
        slotEnd.setHours(slotEnd.getHours() + 1);

        if (slotEnd > end) {
          slotEnd = new Date(end);
        }

        var startStr = String(slotStart.getHours()).padStart(2, '0') + ':' + String(slotStart.getMinutes()).padStart(2, '0');
        var endStr = String(slotEnd.getHours()).padStart(2, '0') + ':' + String(slotEnd.getMinutes()).padStart(2, '0');

        slots.push({
          start: startStr,
          end: endStr
        });

        current.setHours(current.getHours() + 1);
      }

      if (slots.length === 0) {
        previewContent.innerHTML = '<p class="text-muted small mb-0">Tidak ada slot yang dapat dibuat</p>';
        previewSlots.style.display = 'block';
        return;
      }

      // Format harga
      var formattedPrice = new Intl.NumberFormat('id-ID').format(finalPrice);

      // Render preview
      var html = '<div class="d-flex flex-wrap gap-2">';
      slots.forEach(function(slot, index) {
        html += '<span class="badge badge-primary px-3 py-2 mb-2">' + slot.start + ' - ' + slot.end + '</span>';
      });
      html += '</div>';
      html += '<p class="text-muted small mt-2 mb-0"><strong>' + slots.length + ' slot</strong> akan dibuat dengan harga <strong>Rp ' + formattedPrice + '</strong> per slot' + ((hargaPromoVal > 0 && hargaPromoVal < hargaAwalVal) ? ' <span class="badge badge-warning text-dark ml-1"><i class="fas fa-tag mr-1"></i>Harga Promo (Harga Awal Rp ' + new Intl.NumberFormat('id-ID').format(hargaAwalVal) + ')</span>' : '') + '</p>';

      previewContent.innerHTML = html;
      previewSlots.style.display = 'block';
    }

    // Toggle generate info
    if (generateMultiple && generateInfo) {
      generateMultiple.addEventListener('change', function() {
        if (this.checked) {
          generateInfo.classList.remove('d-none');
          updatePreview();
        } else {
          generateInfo.classList.add('d-none');
          previewSlots.style.display = 'none';
        }
      });

      // Initialize
      if (generateMultiple.checked) {
        generateInfo.classList.remove('d-none');
        updatePreview();
      }
    }

    // Update preview on input change
    var hargaAwalInputElem = document.getElementById('harga_awal');
    if (jamMulai) {
      jamMulai.addEventListener('change', updatePreview);
      jamMulai.addEventListener('input', updatePreview);
    }
    if (jamSelesai) {
      jamSelesai.addEventListener('change', updatePreview);
      jamSelesai.addEventListener('input', updatePreview);
    }
    if (hargaAwalInputElem) {
      hargaAwalInputElem.addEventListener('change', updatePreview);
      hargaAwalInputElem.addEventListener('input', updatePreview);
    }
    if (harga) {
      harga.addEventListener('change', updatePreview);
      harga.addEventListener('input', updatePreview);
    }

    if (toggleButton) {
      toggleButton.addEventListener('click', function () {
        var isHidden = slotForm.classList.contains('d-none');
        toggleForm(isHidden);
      });
    }

    if (cancelButton) {
      cancelButton.addEventListener('click', function () {
        toggleForm(false);
      });
    }

    @if($errors->any())
      toggleForm(true);
    @endif

    // Bulk Form Toggle
    function toggleBulkForm(show) {
      if (show) {
        bulkForm.classList.remove('d-none');
        slotForm.classList.add('d-none');
      } else {
        bulkForm.classList.add('d-none');
      }
    }

    if (toggleBulkButton) {
      toggleBulkButton.addEventListener('click', function () {
        var isHidden = bulkForm.classList.contains('d-none');
        toggleBulkForm(isHidden);
        if (isHidden) {
          slotForm.classList.add('d-none');
        }
      });
    }

    if (cancelBulkButton) {
      cancelBulkButton.addEventListener('click', function () {
        toggleBulkForm(false);
      });
    }

    // Calculate and preview bulk slots
    function calculateBulkPreview() {
      if (!bulkTanggalMulai || !bulkTanggalAkhir || !bulkJamMulai || !bulkJamSelesai) {
        return;
      }

      var tanggalMulai = bulkTanggalMulai.value;
      var tanggalAkhir = bulkTanggalAkhir.value;
      var jamMulai = bulkJamMulai.value;
      var jamSelesai = bulkJamSelesai.value;

      if (!tanggalMulai || !tanggalAkhir || !jamMulai || !jamSelesai) {
        bulkPreviewSlots.style.display = 'none';
        return;
      }

      var startDate = new Date(tanggalMulai + 'T00:00:00');
      var endDate = new Date(tanggalAkhir + 'T00:00:00');
      
      if (endDate < startDate) {
        bulkPreviewContent.innerHTML = '<p class="text-danger small mb-0"><i class="fas fa-exclamation-triangle mr-1"></i>Tanggal akhir harus setelah tanggal mulai</p>';
        bulkPreviewSlots.style.display = 'block';
        return;
      }

      // Check if range is more than 1 year
      var daysDiff = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24));
      if (daysDiff > 365) {
        bulkPreviewContent.innerHTML = '<p class="text-danger small mb-0"><i class="fas fa-exclamation-triangle mr-1"></i>Rentang tanggal maksimal 1 tahun (365 hari)</p>';
        bulkPreviewSlots.style.display = 'block';
        return;
      }

      // Parse time
      var [startHour, startMin] = jamMulai.split(':').map(Number);
      var [endHour, endMin] = jamSelesai.split(':').map(Number);
      var startTime = startHour * 60 + startMin;
      var endTime = endHour * 60 + endMin;

      if (endTime <= startTime) {
        bulkPreviewContent.innerHTML = '<p class="text-danger small mb-0"><i class="fas fa-exclamation-triangle mr-1"></i>Jam selesai harus setelah jam mulai</p>';
        bulkPreviewSlots.style.display = 'block';
        return;
      }

      // Get skip days
      var skipDays = [];
      var skipWeekend = document.getElementById('skipWeekend');
      var skipSaturday = document.getElementById('skipSaturday');
      var skipSunday = document.getElementById('skipSunday');
      
      if (skipWeekend && skipWeekend.checked) {
        skipDays.push(0, 6); // Sunday and Saturday
      } else {
        if (skipSaturday && skipSaturday.checked) skipDays.push(6);
        if (skipSunday && skipSunday.checked) skipDays.push(0);
      }

      // Calculate slots per day (1 hour intervals)
      var slotsPerDay = Math.floor((endTime - startTime) / 60);

      // Count valid days
      var validDays = 0;
      var currentDate = new Date(startDate);
      while (currentDate <= endDate) {
        var dayOfWeek = currentDate.getDay();
        if (!skipDays.includes(dayOfWeek)) {
          validDays++;
        }
        currentDate.setDate(currentDate.getDate() + 1);
      }

      var totalSlots = validDays * slotsPerDay;
      var harga = bulkHarga ? parseInt(bulkHarga.value) || 0 : 0;

      bulkPreviewContent.innerHTML = `
        <div class="row">
          <div class="col-6">
            <p class="small mb-1"><strong>Total Hari:</strong> ${validDays} hari</p>
            <p class="small mb-1"><strong>Slot per Hari:</strong> ${slotsPerDay} slot</p>
          </div>
          <div class="col-6">
            <p class="small mb-1"><strong>Total Slot:</strong> <span class="badge badge-primary">${totalSlots}</span></p>
            <p class="small mb-0"><strong>Harga Total:</strong> Rp ${harga.toLocaleString('id-ID')}</p>
          </div>
        </div>
        <hr class="my-2">
        <p class="small text-muted mb-0">
          <i class="fas fa-info-circle mr-1"></i>
          Sistem akan membuat ${slotsPerDay} slot per hari (${jamMulai} - ${jamSelesai}) untuk ${validDays} hari.
        </p>
      `;
      bulkPreviewSlots.style.display = 'block';
    }

    // Attach preview calculation listeners
    if (bulkTanggalMulai) {
      bulkTanggalMulai.addEventListener('change', function() {
        // Set max date to 1 year from start date
        if (this.value) {
          var startDate = new Date(this.value);
          var maxDate = new Date(startDate);
          maxDate.setFullYear(maxDate.getFullYear() + 1);
          var maxDateStr = maxDate.toISOString().split('T')[0];
          
          if (bulkTanggalAkhir) {
            bulkTanggalAkhir.setAttribute('min', this.value);
            bulkTanggalAkhir.setAttribute('max', maxDateStr);
            
            // Validate current end date
            if (bulkTanggalAkhir.value && new Date(bulkTanggalAkhir.value) > maxDate) {
              bulkTanggalAkhir.value = maxDateStr;
            }
          }
        }
        calculateBulkPreview();
      });
      bulkTanggalMulai.addEventListener('input', function() {
        if (this.value) {
          var startDate = new Date(this.value);
          var maxDate = new Date(startDate);
          maxDate.setFullYear(maxDate.getFullYear() + 1);
          var maxDateStr = maxDate.toISOString().split('T')[0];
          
          if (bulkTanggalAkhir) {
            bulkTanggalAkhir.setAttribute('min', this.value);
            bulkTanggalAkhir.setAttribute('max', maxDateStr);
          }
        }
        calculateBulkPreview();
      });
    }
    if (bulkTanggalAkhir) {
      bulkTanggalAkhir.addEventListener('change', function() {
        // Validate against start date
        if (bulkTanggalMulai && bulkTanggalMulai.value) {
          var startDate = new Date(bulkTanggalMulai.value);
          var endDate = new Date(this.value);
          var maxDate = new Date(startDate);
          maxDate.setFullYear(maxDate.getFullYear() + 1);
          
          var errorMsg = document.getElementById('bulk_tanggal_akhir_error');
          if (endDate > maxDate) {
            this.classList.add('is-invalid');
            if (errorMsg) errorMsg.classList.remove('d-none');
            this.setCustomValidity('Tanggal akhir tidak boleh lebih dari 1 tahun dari tanggal mulai');
          } else {
            this.classList.remove('is-invalid');
            if (errorMsg) errorMsg.classList.add('d-none');
            this.setCustomValidity('');
          }
        }
        calculateBulkPreview();
      });
      bulkTanggalAkhir.addEventListener('input', calculateBulkPreview);
    }
    if (bulkJamMulai) {
      bulkJamMulai.addEventListener('change', calculateBulkPreview);
      bulkJamMulai.addEventListener('input', calculateBulkPreview);
    }
    if (bulkJamSelesai) {
      bulkJamSelesai.addEventListener('change', calculateBulkPreview);
      bulkJamSelesai.addEventListener('input', calculateBulkPreview);
    }
    if (bulkHarga) {
      bulkHarga.addEventListener('change', calculateBulkPreview);
      bulkHarga.addEventListener('input', calculateBulkPreview);
    }

    // Listen to skip days checkboxes
    var skipCheckboxes = document.querySelectorAll('input[name="skip_days[]"]');
    skipCheckboxes.forEach(function(checkbox) {
      checkbox.addEventListener('change', function() {
        // Handle weekend checkbox
        if (this.value === 'weekend' && this.checked) {
          var skipSat = document.getElementById('skipSaturday');
          var skipSun = document.getElementById('skipSunday');
          if (skipSat) skipSat.checked = false;
          if (skipSun) skipSun.checked = false;
        } else if ((this.value === '0' || this.value === '6') && this.checked) {
          var skipWeekend = document.getElementById('skipWeekend');
          if (skipWeekend) skipWeekend.checked = false;
        }
        calculateBulkPreview();
      });
    });

    // Handle Bulk Form Submission
    if (bulkForm) {
      bulkForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        var submitBtn = this.querySelector('button[type="submit"]');
        var originalText = submitBtn ? submitBtn.innerHTML : '';
        if (submitBtn) {
          submitBtn.disabled = true;
          submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Membuat Jadwal...';
        }
        
        var formData = new FormData(this);
        
        fetch(this.action, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '',
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
          },
          body: formData
        })
        .then(response => {
          const contentType = response.headers.get("content-type");
          if (contentType && contentType.includes("application/json")) {
            return response.json();
          } else {
            window.location.reload();
            return null;
          }
        })
        .then(data => {
          if (!data) return;
          
          if (data.success) {
            // Show success message (compact version)
            if (data.message) {
              var successAlert = document.createElement('div');
              successAlert.className = 'alert alert-success border-0 shadow-sm rounded-lg mb-3 alert-bulk-success';
              successAlert.innerHTML = `
                <div class="d-flex align-items-center flex-wrap">
                  <i class="fas fa-check-circle mr-2"></i>
                  <span class="flex-grow-1">${data.message}</span>
                </div>
              `;
              var cardBody = document.querySelector('.card-body');
              if (cardBody) {
                cardBody.insertBefore(successAlert, cardBody.firstChild);
                setTimeout(() => successAlert.remove(), 5000);
              }
            }
            
            // Show information about created dates (compact and responsive)
            if (data.created_dates && data.created_dates.length > 0) {
              console.log('Generated slots for dates:', data.created_dates);
              console.log('Total dates with slots:', data.created_dates.length);
              
              // Create compact info box showing which dates were created
              var datesInfo = document.createElement('div');
              datesInfo.className = 'alert alert-info border-0 shadow-sm rounded-lg mb-3 alert-bulk-dates';
              datesInfo.innerHTML = `
                <div class="d-flex align-items-start">
                  <i class="fas fa-calendar-check mr-2 mt-1 flex-shrink-0"></i>
                  <div class="flex-grow-1">
                    <strong class="d-block mb-2">Jadwal berhasil dibuat untuk ${data.days} hari</strong>
                    <div class="mb-2">
                      <small class="text-muted d-block mb-1">Tanggal yang sudah di-generate:</small>
                      <div class="d-flex flex-wrap date-badges-container">
                        ${data.created_dates.slice(0, 7).map(function(date) {
                          var d = new Date(date + 'T00:00:00');
                          return '<span class="badge badge-primary mr-1 mb-1">' + 
                                 d.toLocaleDateString('id-ID', {day: '2-digit', month: 'short', year: 'numeric'}) + 
                                 '</span>';
                        }).join('')}
                        ${data.created_dates.length > 7 ? '<span class="badge badge-secondary mr-1 mb-1">+ ' + (data.created_dates.length - 7) + ' lainnya</span>' : ''}
                      </div>
                    </div>
                    <small class="text-muted d-block">
                      <i class="fas fa-info-circle mr-1"></i>
                      Gunakan navigasi tanggal di atas untuk melihat jadwal.
                    </small>
                  </div>
                </div>
              `;
              var cardBody = document.querySelector('.card-body');
              if (cardBody) {
                var existingAlert = cardBody.querySelector('.alert-success');
                if (existingAlert && existingAlert.nextSibling) {
                  cardBody.insertBefore(datesInfo, existingAlert.nextSibling);
                } else {
                  cardBody.insertBefore(datesInfo, cardBody.firstChild);
                }
                setTimeout(() => datesInfo.remove(), 8000);
              }
            }
            
            // Reload slots for the first date
            if (data.date) {
              // Parse date string (Y-m-d format) to Date object
              var dateParts = data.date.split('-');
              var reloadDate = new Date(parseInt(dateParts[0]), parseInt(dateParts[1]) - 1, parseInt(dateParts[2]));
              
              // Update date picker
              if (datePicker) {
                datePicker.value = data.date;
              }
              
              // Update currentDate
              currentDate = reloadDate;
              
              // Load slots for this date
              loadSlotsByDate(reloadDate, true);
            }
            
            // Hide form
            toggleBulkForm(false);
            
            // Reset form
            this.reset();
            bulkPreviewSlots.style.display = 'none';
          } else {
            // Show error message
            if (data.message) {
              alert(data.message);
            }
            if (data.errors) {
              // Handle validation errors
              var errorMsg = 'Terjadi kesalahan:\n';
              for (var field in data.errors) {
                errorMsg += '- ' + data.errors[field][0] + '\n';
              }
              alert(errorMsg);
            }
            
            if (submitBtn) {
              submitBtn.disabled = false;
              submitBtn.innerHTML = originalText;
            }
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Terjadi kesalahan saat membuat jadwal bulk.');
          if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
          }
        });
      });
    }

    // Handle Edit Slot - Initialize on page load
    var editSlotForm = document.getElementById('editSlotForm');
    var editSlotModal = document.getElementById('editSlotModal');
    var deleteSlotForm = document.getElementById('deleteSlotForm');
    var deleteSlotModal = document.getElementById('deleteSlotModal');
    
    // Initial attach for edit/delete buttons
    attachSlotEventListeners();
    
    // Original edit button handlers (for initial page load)
    var editButtons = document.querySelectorAll('.edit-slot-btn');
    editButtons.forEach(function(btn) {
      btn.addEventListener('click', function(e) {
        e.preventDefault();
        var slotId = this.getAttribute('data-slot-id');
        var editUrl = `{{ route('fasilitas.lapangan.jadwal.edit', [$venue->id, $lapangan->id, ':slotId']) }}`.replace(':slotId', slotId);
        
        // Show loading state
        var submitBtn = editSlotForm.querySelector('button[type="submit"]');
        var originalText = submitBtn ? submitBtn.innerHTML : '';
        if (submitBtn) {
          submitBtn.disabled = true;
          submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Memuat...';
        }
        
        // Fetch slot data via AJAX
        fetch(editUrl, {
          method: 'GET',
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : ''
          }
        })
        .then(response => {
          if (!response.ok) {
            throw new Error('Network response was not ok');
          }
          return response.json();
        })
        .then(data => {
          if (data.success && data.slot) {
            var slot = data.slot;
            
            // Format waktu untuk input type="time" (HH:mm)
            var formatTime = function(timeStr) {
              if (!timeStr) return '';
              // Jika sudah format HH:mm, langsung return
              if (timeStr.match(/^\d{2}:\d{2}$/)) {
                return timeStr;
              }
              // Jika format lain, convert ke HH:mm
              var parts = timeStr.split(':');
              if (parts.length >= 2) {
                return parts[0].padStart(2, '0') + ':' + parts[1].padStart(2, '0');
              }
              return timeStr;
            };
            
            // Fill form fields
            document.getElementById('edit_jam_mulai').value = formatTime(slot.jam_mulai);
            document.getElementById('edit_jam_selesai').value = formatTime(slot.jam_selesai);
            
            // Set harga_awal: jika ada gunakan, jika tidak gunakan harga sebagai harga_awal
            var hargaAwal = slot.harga_awal || slot.harga || 0;
            document.getElementById('edit_harga_awal').value = hargaAwal;
            
            // Set harga (diskon): jika ada harga_awal, gunakan harga, jika tidak kosongkan
            document.getElementById('edit_harga').value = slot.harga || 0;
            
            document.getElementById('edit_status').value = slot.status || 'available';
            document.getElementById('edit_promo_status').value = slot.is_promo ? 'promo' : 'none';
            document.getElementById('edit_catatan').value = slot.catatan || '';
            
            // Update form action
            var updateUrl = `{{ route('fasilitas.lapangan.jadwal.update', [$venue->id, $lapangan->id, ':slotId']) }}`.replace(':slotId', slotId);
            editSlotForm.action = updateUrl;
            
            // Reset submit button
            if (submitBtn) {
              submitBtn.disabled = false;
              submitBtn.innerHTML = originalText;
            }
            
            // Show modal
            $(editSlotModal).modal('show');
          } else {
            throw new Error('Data tidak valid');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Terjadi kesalahan saat memuat data slot. Silakan coba lagi.');
          if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
          }
        });
      });
    });
    
    // Handle form submission for delete - reload data after delete
    if (deleteSlotForm) {
      deleteSlotForm.addEventListener('submit', function(e) {
        var submitBtn = this.querySelector('button[type="submit"]');
        if (submitBtn) {
          submitBtn.disabled = true;
          submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Menghapus...';
        }
        
        // After successful delete, reload slots for current date
        fetch(this.action, {
          method: 'DELETE',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '',
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
          },
          body: new FormData(this)
        })
        .then(response => {
          if (!response.ok) {
            return response.text().then(text => {
              try {
                var json = JSON.parse(text);
                throw { response: json, status: response.status };
              } catch (e) {
                throw { message: 'Server Error (' + response.status + '): ' + (text.substring(0, 100) || response.statusText) };
              }
            });
          }
          return response.text().then(text => {
            try {
              return JSON.parse(text);
            } catch (e) {
              throw { message: 'Response is not valid JSON' };
            }
          });
        })
        .then(data => {
          if (data.success) {
            // Close modal safely first
            if (typeof $ !== 'undefined' && $.fn.modal) {
              $(deleteSlotModal).modal('hide');
            } else {
              deleteSlotModal.classList.remove('show');
              deleteSlotModal.style.display = 'none';
              document.body.classList.remove('modal-open');
              var backdrop = document.querySelector('.modal-backdrop');
              if (backdrop) backdrop.remove();
            }
            // Reload slots for current date
            loadSlotsByDate(currentDate);
          } else {
            alert(data.message || 'Terjadi kesalahan saat menghapus slot.');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          var errorMsg = error.message || 'Terjadi kesalahan saat menghapus slot.';
          if (error.response && error.response.message) {
            errorMsg = error.response.message;
          }
          alert(errorMsg);
        })
        .finally(() => {
          if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Hapus';
          }
        });
        
        e.preventDefault();
        return false;
      });
    }
    
    // Handle form submission for edit - reload data after update
    if (editSlotForm) {
      editSlotForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        var submitBtn = this.querySelector('button[type="submit"]');
        var originalText = submitBtn ? submitBtn.innerHTML : 'Simpan Perubahan';
        if (submitBtn) {
          submitBtn.disabled = true;
          submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Menyimpan...';
        }
        
        // Create FormData and ensure _method is set
        var formData = new FormData(this);
        formData.append('_method', 'PUT');
        
        // Ensure harga_awal is always sent (even if readonly)
        var hargaAwalInput = document.getElementById('edit_harga_awal');
        if (hargaAwalInput && hargaAwalInput.value) {
          formData.set('harga_awal', hargaAwalInput.value);
        }
        
        // After successful update, reload slots for current date
        fetch(this.action, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '',
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
          },
          body: formData
        })
        .then(response => {
          if (!response.ok) {
            return response.text().then(text => {
              try {
                var json = JSON.parse(text);
                throw { response: json, status: response.status };
              } catch (e) {
                throw { message: 'Server Error (' + response.status + '): ' + (text.substring(0, 100) || response.statusText) };
              }
            });
          }
          return response.text().then(text => {
            try {
              return JSON.parse(text);
            } catch (e) {
              throw { message: 'Response is not valid JSON' };
            }
          });
        })
        .then(data => {
          if (data.success) {
            // Close modal safely first
            if (typeof $ !== 'undefined' && $.fn.modal) {
              $(editSlotModal).modal('hide');
            } else {
              editSlotModal.classList.remove('show');
              editSlotModal.style.display = 'none';
              document.body.classList.remove('modal-open');
              var backdrop = document.querySelector('.modal-backdrop');
              if (backdrop) backdrop.remove();
            }
            // Reload slots for current date
            loadSlotsByDate(currentDate);
          } else {
            var errorMsg = data.message || 'Terjadi kesalahan saat menyimpan perubahan.';
            if (data.errors) {
              var errorList = Object.values(data.errors).flat().join('\n');
              errorMsg = errorList || errorMsg;
            }
            alert(errorMsg);
          }
        })
        .catch(error => {
          console.error('Error:', error);
          var errorMsg = error.message || 'Terjadi kesalahan saat menyimpan perubahan.';
          if (error.response) {
            if (error.response.errors) {
              var errorList = Object.values(error.response.errors).flat().join('\n');
              errorMsg = errorList || error.response.message || errorMsg;
            } else if (error.response.message) {
              errorMsg = error.response.message;
            }
          }
          alert(errorMsg);
        })
        .finally(() => {
          if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
          }
        });
        
        return false;
      });
    }
    
    // Handle form submission for delete day slots - bulk delete for date
    var deleteDaySlotsForm = document.getElementById('deleteDaySlotsForm');
    var deleteDaySlotsModal = document.getElementById('deleteDaySlotsModal');
    if (deleteDaySlotsForm) {
      deleteDaySlotsForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        var submitBtn = this.querySelector('button[type="submit"]');
        if (submitBtn) {
          submitBtn.disabled = true;
          submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Menghapus...';
        }
        
        fetch(this.action, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '',
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
          },
          body: new FormData(this)
        })
        .then(response => {
          if (!response.ok) {
            return response.text().then(text => {
              try {
                var json = JSON.parse(text);
                throw { response: json, status: response.status };
              } catch (e) {
                throw { message: 'Server Error (' + response.status + '): ' + (text.substring(0, 100) || response.statusText) };
              }
            });
          }
          return response.text().then(text => {
            try {
              return JSON.parse(text);
            } catch (e) {
              throw { message: 'Response is not valid JSON' };
            }
          });
        })
        .then(data => {
          if (data.success) {
            // Close modal safely first
            if (typeof $ !== 'undefined' && $.fn.modal) {
              $(deleteDaySlotsModal).modal('hide');
            } else {
              deleteDaySlotsModal.classList.remove('show');
              deleteDaySlotsModal.style.display = 'none';
              document.body.classList.remove('modal-open');
              var backdrop = document.querySelector('.modal-backdrop');
              if (backdrop) backdrop.remove();
            }
            // Reload slots for current date
            loadSlotsByDate(currentDate);
          } else {
            alert(data.message || 'Terjadi kesalahan saat menghapus jadwal.');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          var errorMsg = error.message || 'Terjadi kesalahan saat menghapus jadwal.';
          if (error.response && error.response.message) {
            errorMsg = error.response.message;
          }
          alert(errorMsg);
        })
        .finally(() => {
          if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Ya, Hapus Semua';
          }
        });
        
        return false;
      });
    }
    
    // Handle form submission for add slot - reload data after add
    if (slotForm) {
      slotForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        var submitBtn = this.querySelector('button[type="submit"]');
        var originalText = submitBtn ? submitBtn.innerHTML : '';
        if (submitBtn) {
          submitBtn.disabled = true;
          submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Menyimpan...';
        }
        
        var formData = new FormData(this);
        
        fetch(this.action, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '',
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
          },
          body: formData
        })
        .then(response => {
          // Check if response is JSON
          const contentType = response.headers.get("content-type");
          if (contentType && contentType.includes("application/json")) {
            return response.json();
          } else {
            // If not JSON, might be validation error - reload page
            window.location.reload();
            return null;
          }
        })
        .then(data => {
          if (!data) return; // If reload was triggered
          
          if (data.success) {
            // Show success message
            if (data.message) {
              // Show success alert
              var successAlert = document.createElement('div');
              successAlert.className = 'alert alert-success border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center';
              successAlert.innerHTML = '<i class="fas fa-check-circle text-success mr-2"></i><span>' + data.message + '</span>';
              var cardBody = document.querySelector('.card-body');
              if (cardBody) {
                cardBody.insertBefore(successAlert, cardBody.firstChild);
                setTimeout(() => successAlert.remove(), 3000);
              }
            }
            
            // Reload slots for current date
            loadSlotsByDate(currentDate);
            
            // Reset form
            this.reset();
            toggleForm(false);
            
            // Update hidden tanggal field
            var tanggalInput = document.getElementById('slotFormTanggal');
            if (tanggalInput) {
              tanggalInput.value = formatDate(currentDate);
            }
          } else {
            var errorMsg = data.message || 'Terjadi kesalahan saat menyimpan slot.';
            if (data.errors) {
              errorMsg = Object.values(data.errors).flat().join('\n');
            }
            alert(errorMsg);
          }
        })
        .catch(error => {
          console.error('Error:', error);
          // If error, reload page to show validation errors
          window.location.reload();
        })
        .finally(() => {
          if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
          }
        });
        
        return false;
      });
    }
  });
</script>
@endsection

