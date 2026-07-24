@extends('pemiliklapangan.layout.ownervenue')

@section('content')
<div class="content-wrapper bg-light">
  <section class="content pt-4 pb-5">
    <div class="container-fluid">

      <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
          <div>
            <h5 class="text-uppercase text-muted mb-1 small">Edit Venue</h5>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb bg-transparent p-0 mb-0">
                <li class="breadcrumb-item"><a href="{{ route('fasilitas') }}">Kelola Fasilitas</a></li>
                <li class="breadcrumb-item"><a href="{{ route('fasilitas.detail', $venue->id) }}">Detail Venue</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Venue</li>
              </ol>
            </nav>
          </div>
          <a href="{{ route('fasilitas.detail', $venue->id) }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
          </a>
        </div>
      </div>

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

      <form action="{{ route('fasilitas.update', $venue->id) }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="card border-0 shadow-sm rounded-3 mb-4">
          <div class="card-header bg-white border-0 pb-0">
            <h5 class="font-weight-bold mb-0">Informasi Venue</h5>
            <p class="text-muted small mb-0">Edit informasi dasar venue</p>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Gambar Banner <span class="text-danger">*</span></label>
                <input type="file" name="logo" class="form-control" accept="image/*">
                <small class="text-muted">Kosongkan jika tidak ingin mengubah gambar</small>
                @if($venue->logo)
                  <div class="mt-2">
                    <img src="{{ asset('storage/' . $venue->logo) }}" alt="Current Logo" class="img-thumbnail" style="max-height: 150px;">
                    <p class="text-muted small mb-0 mt-1">Gambar saat ini</p>
                  </div>
                @endif
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Nama Venue <span class="text-danger">*</span></label>
                <input type="text" name="namavenue" class="form-control" value="{{ old('namavenue', $venue->namavenue) }}" required>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Provinsi <span class="text-danger">*</span></label>
                <select class="form-control" id="provinsi" name="provinsi" required>
                  <option value="">-- Pilih Provinsi --</option>
                  <option value="Bali" {{ old('provinsi', $venue->provinsi) == 'Bali' ? 'selected' : '' }}>Bali</option>
                  <option value="Jawa Timur" {{ old('provinsi', $venue->provinsi) == 'Jawa Timur' ? 'selected' : '' }}>Jawa Timur</option>
                  <option value="DKI Jakarta" {{ old('provinsi', $venue->provinsi) == 'DKI Jakarta' ? 'selected' : '' }}>DKI Jakarta</option>
                  <option value="Jawa Barat" {{ old('provinsi', $venue->provinsi) == 'Jawa Barat' ? 'selected' : '' }}>Jawa Barat</option>
                  <option value="Sumatera Utara" {{ old('provinsi', $venue->provinsi) == 'Sumatera Utara' ? 'selected' : '' }}>Sumatera Utara</option>
                </select>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Kota <span class="text-danger">*</span></label>
                <select class="form-control" id="kota" name="kota" required>
                  <option value="">-- Pilih Kota --</option>
                </select>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12 mb-3">
                <label class="form-label">Cabang Olahraga <span class="text-danger">*</span></label>
                <small class="text-muted d-block mb-2">Pilih 1-5 cabang olahraga (maksimal 5 pilihan)</small>
                <div class="row border rounded p-3" style="max-height: 300px; overflow-y: auto;">
                  @php
                    $olahragaOptions = [
                      'Sepak Bola', 'Futsal', 'Bola Basket', 'Bola Voli', 'Bola Tangan',
                      'Badminton', 'Tennis', 'Table Tennis', 'Squash', 'Rugby',
                      'Baseball', 'Softball', 'Sepak Takraw', 'Pencak Silat', 'Karate',
                      'Taekwondo', 'Judo', 'Muay Thai', 'Boxing', 'Bola Bowling',
                      'Billiard', 'Snooker', 'Sepakbola Pantai', 'Voli Pantai', 'Swimming',
                      'Fitness', 'Gymnastics', 'Cycling', 'Running Track'
                    ];
                    // Handle both old format (string) and new format (array)
                    $selectedKategori = old('kategori', $venue->kategori ?? []);
                    if (!is_array($selectedKategori)) {
                      if (!empty($selectedKategori)) {
                        $selectedKategori = [$selectedKategori];
                      } else {
                        $selectedKategori = [];
                      }
                    }
                    // Get custom olahraga (not in standard list)
                    $customOlahraga = [];
                    $standardKategori = [];
                    foreach ($selectedKategori as $kat) {
                      if (!in_array($kat, $olahragaOptions)) {
                        $customOlahraga[] = $kat;
                      } else {
                        $standardKategori[] = $kat;
                      }
                    }
                  @endphp
                  @foreach($olahragaOptions as $olahraga)
                    <div class="col-md-4 col-sm-6 mb-2">
                      <div class="form-check">
                        <input class="form-check-input kategori-checkbox-edit" 
                               type="checkbox" 
                               name="kategori[]" 
                               value="{{ $olahraga }}" 
                               id="kategori-edit-{{ Str::slug($olahraga) }}"
                               {{ in_array($olahraga, $standardKategori) ? 'checked' : '' }}>
                        <label class="form-check-label" for="kategori-edit-{{ Str::slug($olahraga) }}">
                          {{ $olahraga }}
                        </label>
                      </div>
                    </div>
                  @endforeach
                </div>
                <div class="mt-2">
                  <small class="text-muted" id="kategori-count-edit">Terpilih: {{ count($selectedKategori) }}/5</small>
                </div>
                
                {{-- Custom Olahraga --}}
                <div class="mt-3">
                  <label class="form-label">Olahraga Lainnya (Custom)</label>
                  <div id="custom-olahraga-container-edit">
                    @if(!empty($customOlahraga))
                      @foreach($customOlahraga as $index => $customOlah)
                        <div class="custom-olahraga-item mb-2">
                          <div class="input-group">
                            <input type="text" class="form-control custom-olahraga-input-edit" 
                                   name="custom_olahraga[]" 
                                   value="{{ $customOlah }}" 
                                   placeholder="Masukkan nama olahraga lainnya">
                            <button type="button" class="btn btn-outline-danger remove-custom-olahraga-edit" type="button">
                              <i class="fas fa-times"></i>
                            </button>
                          </div>
                        </div>
                      @endforeach
                    @endif
                  </div>
                  <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="add-custom-olahraga-edit">
                    <i class="fas fa-plus"></i> Tambah Olahraga Lainnya
                  </button>
                  <small class="text-muted d-block mt-1">Tambahkan olahraga yang tidak ada dalam daftar</small>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Nomor Telepon <span class="text-danger">*</span></label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">+62</span>
                  </div>
                  <input type="text" name="nomor_telepon" class="form-control" 
                         value="{{ old('nomor_telepon', $venue->nomor_telepon) }}" 
                         placeholder="81234567890" required>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Email Venue <span class="text-danger">*</span></label>
                <input type="email" name="email_venue" class="form-control" 
                       value="{{ old('email_venue', $venue->email_venue) }}" 
                       placeholder="venue@example.com" required>
              </div>
            </div>
          </div>
        </div>

        <div class="card border-0 shadow-sm rounded-3 mb-4">
          <div class="card-header bg-white border-0 pb-0">
            <h5 class="font-weight-bold mb-0">Detail Venue</h5>
            <p class="text-muted small mb-0">Edit detail dan informasi tambahan venue</p>
          </div>
          <div class="card-body">
            <div class="mb-3">
              <label class="form-label">Video Review (Link YouTube)</label>
              <input type="url" name="video_review" class="form-control" 
                     value="{{ old('video_review', $venue->video_review) }}" 
                     placeholder="https://www.youtube.com/watch?v=abc123">
              <small class="text-muted">Masukkan link YouTube</small>
              @if($venue->video_review)
                @php
                  $videoId = null;
                  if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $venue->video_review, $matches)) {
                    $videoId = $matches[1];
                  }
                @endphp
                @if($videoId)
                  <div class="mt-2">
                    <div class="embed-responsive embed-responsive-16by9" style="max-width: 400px;">
                      <iframe class="embed-responsive-item" 
                              src="https://www.youtube.com/embed/{{ $videoId }}" 
                              allowfullscreen></iframe>
                    </div>
                  </div>
                @endif
              @endif
            </div>

            <div class="mb-3">
              <label class="form-label">Detail Venue</label>
              <textarea id="summernote-detail" name="detail" class="form-control">{{ old('detail', $venue->detail) }}</textarea>
            </div>

            <div class="mb-3">
              <label class="form-label">Aturan Venue</label>
              <textarea id="summernote-aturan" name="aturan" class="form-control">{{ old('aturan', $venue->aturan) }}</textarea>
            </div>

            <div class="mb-3">
              <label class="form-label">Lokasi (Link Google Maps)</label>
              <input type="url" name="lokasi" class="form-control" 
                     value="{{ old('lokasi', $venue->lokasi) }}" 
                     placeholder="https://www.google.com/maps/place/...">
            </div>

            <div class="mb-3">
              <label class="form-label d-block">Fasilitas Venue</label>
              <div class="row">
                @php
                  $allFasilitas = [
                    'Area Parkir', 'Toilet/Kamar Mandi', 'Ruang Ganti/Transit', 
                    'Tempat Ibadah (Musholla)', 'Kantin/Area Catering', 'AC/Pendingin Udara',
                    'Sistem Tata Suara (Sound System)', 'Proyektor & Layar/LED', 
                    'Akses Internet (Wi-Fi)', 'Akses Listrik Cadangan (Genset)',
                    'Area Registrasi/Lobi', 'Keamanan (Security) & P3K'
                  ];
                  $selectedFasilitas = $fasilitas ?? [];
                  $customFasilitas = [];
                  if (isset($venue) && $venue->fasilitas) {
                    // Fasilitas sudah di-cast sebagai array di model
                    if (is_array($venue->fasilitas)) {
                      $allSelected = $venue->fasilitas;
                    } elseif (is_string($venue->fasilitas)) {
                      $decoded = json_decode($venue->fasilitas, true);
                      $allSelected = is_array($decoded) ? $decoded : [];
                    } else {
                      $allSelected = [];
                    }
                    foreach ($allSelected as $fas) {
                      if (!in_array($fas, $allFasilitas)) {
                        $customFasilitas[] = $fas;
                      }
                    }
                  }
                @endphp
                @foreach ($allFasilitas as $item)
                  <div class="col-md-4 col-sm-6 mb-2">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" 
                             name="fasilitas_venue[]" 
                             value="{{ $item }}" 
                             id="check-{{ Str::slug($item) }}"
                             {{ in_array($item, $selectedFasilitas) ? 'checked' : '' }}>
                      <label class="form-check-label" for="check-{{ Str::slug($item) }}">
                        {{ $item }}
                      </label>
                    </div>
                  </div>
                @endforeach
              </div>
              
              {{-- Custom Fasilitas --}}
              <div class="mt-3">
                <label class="form-label">Fasilitas Lainnya (Custom)</label>
                <div id="custom-fasilitas-container">
                  @if(!empty($customFasilitas))
                    @foreach($customFasilitas as $index => $customFas)
                      <div class="custom-fasilitas-item mb-2">
                        <div class="input-group">
                          <input type="text" class="form-control" name="custom_fasilitas[]" 
                                 value="{{ $customFas }}" placeholder="Nama fasilitas lainnya">
                          <button type="button" class="btn btn-outline-danger remove-custom-fasilitas" type="button">
                            <i class="fas fa-times"></i>
                          </button>
                        </div>
                      </div>
                    @endforeach
                  @endif
                </div>
                <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="add-custom-fasilitas">
                  <i class="fas fa-plus"></i> Tambah Fasilitas Lainnya
                </button>
                <small class="text-muted d-block mt-1">Tambahkan fasilitas yang tidak ada dalam daftar di atas</small>
              </div>
            </div>

            {{-- Jam Operasional Dinamis --}}
            <div class="card border-0 bg-light rounded-lg p-4 mb-4">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <label class="form-label font-weight-bold mb-0 text-dark">
                  <i class="fas fa-clock text-primary mr-2"></i>Waktu Operasional Venue
                </label>
                <button type="button" class="btn btn-sm btn-primary font-weight-bold shadow-sm" onclick="tambahJamOperasional()">
                  <i class="fas fa-plus mr-1"></i> Tambah Hari
                </button>
              </div>
              <p class="text-muted small mb-3">Tentukan hari dan jam buka-tutup operasional untuk venue Anda.</p>
              
              <div id="jam-operasional-container">
                @php
                  $existingJam = isset($venue) && is_array($venue->jam_operasional) ? $venue->jam_operasional : [];
                @endphp
                @forelse($existingJam as $index => $item)
                  <div class="row align-items-center mb-2 operasional-row">
                    <div class="col-md-4 mb-2 mb-md-0">
                      <select name="jam_operasional_hari[]" class="form-control form-select select-hari" required>
                        <option value="">-- Pilih Hari --</option>
                        <option value="Setiap Hari" {{ $item['hari'] == 'Setiap Hari' ? 'selected' : '' }}>Setiap Hari</option>
                        <option value="Senin - Jumat" {{ $item['hari'] == 'Senin - Jumat' ? 'selected' : '' }}>Senin - Jumat</option>
                        <option value="Sabtu - Minggu" {{ $item['hari'] == 'Sabtu - Minggu' ? 'selected' : '' }}>Sabtu - Minggu</option>
                        <option value="Senin" {{ $item['hari'] == 'Senin' ? 'selected' : '' }}>Senin</option>
                        <option value="Selasa" {{ $item['hari'] == 'Selasa' ? 'selected' : '' }}>Selasa</option>
                        <option value="Rabu" {{ $item['hari'] == 'Rabu' ? 'selected' : '' }}>Rabu</option>
                        <option value="Kamis" {{ $item['hari'] == 'Kamis' ? 'selected' : '' }}>Kamis</option>
                        <option value="Jumat" {{ $item['hari'] == 'Jumat' ? 'selected' : '' }}>Jumat</option>
                        <option value="Sabtu" {{ $item['hari'] == 'Sabtu' ? 'selected' : '' }}>Sabtu</option>
                        <option value="Minggu" {{ $item['hari'] == 'Minggu' ? 'selected' : '' }}>Minggu</option>
                      </select>
                    </div>
                    <div class="col-md-3 col-6">
                      <div class="input-group">
                        <input type="time" name="jam_operasional_buka[]" class="form-control" value="{{ $item['buka'] ?? '08:00' }}" required>
                      </div>
                    </div>
                    <div class="col-md-3 col-6">
                      <div class="input-group">
                        <input type="time" name="jam_operasional_tutup[]" class="form-control" value="{{ $item['tutup'] ?? '22:00' }}" required>
                      </div>
                    </div>
                    <div class="col-md-2 text-right text-md-center">
                      <button type="button" class="btn btn-outline-danger btn-sm rounded-circle hapus-operasional" onclick="hapusRow(this)">
                        <i class="fas fa-trash-alt"></i>
                      </button>
                    </div>
                  </div>
                @empty
                  <div class="row align-items-center mb-2 operasional-row">
                    <div class="col-md-4 mb-2 mb-md-0">
                      <select name="jam_operasional_hari[]" class="form-control form-select select-hari" required>
                        <option value="">-- Pilih Hari --</option>
                        <option value="Setiap Hari" selected>Setiap Hari</option>
                        <option value="Senin - Jumat">Senin - Jumat</option>
                        <option value="Sabtu - Minggu">Sabtu - Minggu</option>
                        <option value="Senin">Senin</option>
                        <option value="Selasa">Selasa</option>
                        <option value="Rabu">Rabu</option>
                        <option value="Kamis">Kamis</option>
                        <option value="Jumat">Jumat</option>
                        <option value="Sabtu">Sabtu</option>
                        <option value="Minggu">Minggu</option>
                      </select>
                    </div>
                    <div class="col-md-3 col-6">
                      <div class="input-group">
                        <input type="time" name="jam_operasional_buka[]" class="form-control" value="08:00" required>
                      </div>
                    </div>
                    <div class="col-md-3 col-6">
                      <div class="input-group">
                        <input type="time" name="jam_operasional_tutup[]" class="form-control" value="22:00" required>
                      </div>
                    </div>
                    <div class="col-md-2 text-right text-md-center">
                      <button type="button" class="btn btn-outline-danger btn-sm rounded-circle hapus-operasional" onclick="hapusRow(this)">
                        <i class="fas fa-trash-alt"></i>
                      </button>
                    </div>
                  </div>
                @endforelse
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label d-block">Galeri Foto Venue</label>
              <input type="file" name="galeri_foto[]" id="galeri_foto" class="form-control" 
                     accept="image/*" multiple>
              <small class="text-muted">Pilih multiple gambar untuk menambah galeri (maksimal 10 gambar, format: JPG, PNG, maksimal 2MB per gambar)</small>
              
              @if($venue->galleries && $venue->galleries->count() > 0)
                <div class="mt-3">
                  <p class="small text-muted mb-2">Galeri yang sudah ada:</p>
                  <div class="row">
                    @foreach($venue->galleries as $gallery)
                      <div class="col-md-3 col-sm-4 mb-2">
                        <div class="position-relative">
                          <img src="{{ asset('storage/' . $gallery->foto) }}" 
                               alt="Gallery {{ $loop->iteration }}" 
                               class="img-thumbnail" style="width: 100%; height: 120px; object-fit: cover;">
                        </div>
                      </div>
                    @endforeach
                  </div>
                </div>
              @endif

              <div id="galeriPreview" class="mt-3 row"></div>
            </div>
          </div>
        </div>

        <div class="d-flex justify-content-between">
          <a href="{{ route('fasilitas.detail', $venue->id) }}" class="btn btn-light">
            <i class="fas fa-times mr-1"></i> Batal
          </a>
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-save mr-1"></i> Simpan Perubahan
          </button>
        </div>
      </form>

    </div>
  </section>
</div>

{{-- Script Summernote --}}
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Inisialisasi Summernote
    $('#summernote-detail').summernote({
        placeholder: 'Deskripsikan detail venue Anda...',
        tabsize: 2,
        height: 200,
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link', 'picture']],
            ['view', ['codeview']]
        ]
    });

    $('#summernote-aturan').summernote({
        placeholder: 'Tuliskan aturan-aturan yang berlaku di venue...',
        tabsize: 2,
        height: 200,
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link']],
            ['view', ['codeview']]
        ]
    });

    // Script untuk dropdown wilayah
    const dataWilayah = {
        "Bali": ["Denpasar", "Badung", "Gianyar", "Tabanan", "Bangli", "Klungkung", "Karangasem", "Buleleng", "Jembrana"],
        "Jawa Timur": ["Surabaya", "Malang", "Kediri", "Sidoarjo", "Banyuwangi", "Blitar"],
        "DKI Jakarta": ["Jakarta Selatan", "Jakarta Barat", "Jakarta Timur", "Jakarta Utara", "Jakarta Pusat"],
        "Jawa Barat": ["Bandung", "Bogor", "Bekasi", "Cirebon", "Sukabumi"],
        "Sumatera Utara": ["Medan", "Binjai", "Tebing Tinggi", "Pematangsiantar"]
    };

    const provinsiSelect = document.getElementById('provinsi');
    const kotaSelect = document.getElementById('kota');
    const currentProvinsi = "{{ old('provinsi', $venue->provinsi) }}";
    const currentKota = "{{ old('kota', $venue->kota) }}";

    // Fungsi untuk populate kota
    function populateKota(provinsi, selectedKota = null) {
        kotaSelect.innerHTML = '<option value="">-- Pilih Kota --</option>';
        if (provinsi && dataWilayah[provinsi]) {
            dataWilayah[provinsi].forEach(kab => {
                const option = document.createElement('option');
                option.value = kab;
                option.textContent = kab;
                if (selectedKota && kab === selectedKota) {
                    option.selected = true;
                }
                kotaSelect.appendChild(option);
            });
        }
    }

    // Set nilai awal saat halaman dimuat
    if (currentProvinsi) {
        provinsiSelect.value = currentProvinsi;
        populateKota(currentProvinsi, currentKota);
    }

    provinsiSelect.addEventListener('change', function() {
        const provinsi = this.value;
        populateKota(provinsi);
    });

    // Handle kategori checkbox - maksimal 5 pilihan (Edit)
    const kategoriCheckboxesEdit = document.querySelectorAll('.kategori-checkbox-edit');
    const kategoriCountEdit = document.getElementById('kategori-count-edit');
    
    function updateKategoriCountEdit() {
        const checked = document.querySelectorAll('.kategori-checkbox-edit:checked').length;
        const customCount = document.querySelectorAll('.custom-olahraga-input-edit').length;
        const total = checked + customCount;
        if (kategoriCountEdit) {
            kategoriCountEdit.textContent = `Terpilih: ${total}/5`;
        }
        
        if (total >= 5) {
            // Disable unchecked checkboxes
            kategoriCheckboxesEdit.forEach(cb => {
                if (!cb.checked) {
                    cb.disabled = true;
                }
            });
        } else {
            // Enable all checkboxes
            kategoriCheckboxesEdit.forEach(cb => {
                cb.disabled = false;
            });
        }
    }
    
    kategoriCheckboxesEdit.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const checked = document.querySelectorAll('.kategori-checkbox-edit:checked').length;
            const customCount = document.querySelectorAll('.custom-olahraga-input-edit').length;
            const total = checked + customCount;
            
            if (this.checked && total > 5) {
                this.checked = false;
                alert('Maksimal 5 cabang olahraga yang dapat dipilih!');
                return;
            }
            
            updateKategoriCountEdit();
        });
    });

    // Handle custom olahraga (Edit)
    const customOlahragaContainerEdit = document.getElementById('custom-olahraga-container-edit');
    const addCustomOlahragaBtnEdit = document.getElementById('add-custom-olahraga-edit');

    if (addCustomOlahragaBtnEdit && customOlahragaContainerEdit) {
        addCustomOlahragaBtnEdit.addEventListener('click', function() {
            const checked = document.querySelectorAll('.kategori-checkbox-edit:checked').length;
            const customCount = document.querySelectorAll('.custom-olahraga-input-edit').length;
            const total = checked + customCount;
            
            if (total >= 5) {
                alert('Maksimal 5 cabang olahraga yang dapat dipilih!');
                return;
            }
            
            const newItem = document.createElement('div');
            newItem.className = 'custom-olahraga-item mb-2';
            newItem.innerHTML = `
                <div class="input-group">
                    <input type="text" class="form-control custom-olahraga-input-edit" 
                           name="custom_olahraga[]" 
                           placeholder="Masukkan nama olahraga lainnya">
                    <button type="button" class="btn btn-outline-danger remove-custom-olahraga-edit" type="button">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            customOlahragaContainerEdit.appendChild(newItem);
            updateKategoriCountEdit();
        });

        // Remove custom olahraga
        document.addEventListener('click', function(e) {
            if (e.target.closest('.remove-custom-olahraga-edit')) {
                e.target.closest('.custom-olahraga-item').remove();
                updateKategoriCountEdit();
            }
        });
    }

    // Update count on page load
    updateKategoriCountEdit();

    // Form validation (Edit)
    const formEdit = document.querySelector('form[action*="fasilitas.update"]');
    if (formEdit) {
        formEdit.addEventListener('submit', function(e) {
            const checked = document.querySelectorAll('.kategori-checkbox-edit:checked').length;
            const customInputs = document.querySelectorAll('.custom-olahraga-input-edit');
            const customCount = Array.from(customInputs).filter(input => input.value.trim() !== '').length;
            const total = checked + customCount;
            
            if (total === 0) {
                e.preventDefault();
                alert('Pilih minimal 1 cabang olahraga!');
                return false;
            }
            
            if (total > 5) {
                e.preventDefault();
                alert('Maksimal 5 cabang olahraga yang dapat dipilih!');
                return false;
            }
        });
    }

    // Handle custom fasilitas
    const addCustomFasilitasBtn = document.getElementById('add-custom-fasilitas');
    const customFasilitasContainer = document.getElementById('custom-fasilitas-container');

    if (addCustomFasilitasBtn && customFasilitasContainer) {
        addCustomFasilitasBtn.addEventListener('click', function() {
            const newItem = document.createElement('div');
            newItem.className = 'custom-fasilitas-item mb-2';
            newItem.innerHTML = `
                <div class="input-group">
                    <input type="text" class="form-control" name="custom_fasilitas[]" 
                           placeholder="Nama fasilitas lainnya">
                    <button type="button" class="btn btn-outline-danger remove-custom-fasilitas" type="button">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            customFasilitasContainer.appendChild(newItem);
        });

        // Remove custom fasilitas
        document.addEventListener('click', function(e) {
            if (e.target.closest('.remove-custom-fasilitas')) {
                e.target.closest('.custom-fasilitas-item').remove();
            }
        });
    }

    // Preview galeri foto multiple
    const galeriInput = document.getElementById('galeri_foto');
    const galeriPreview = document.getElementById('galeriPreview');
    
    if (galeriInput) {
        galeriInput.addEventListener('change', function(e) {
            galeriPreview.innerHTML = '';
            const files = e.target.files;
            
            if (files.length > 10) {
                alert('Maksimal 10 gambar yang dapat diupload');
                e.target.value = '';
                return;
            }
            
            Array.from(files).forEach((file, index) => {
                if (file.size > 2048 * 1024) {
                    alert(`File ${file.name} terlalu besar (maksimal 2MB)`);
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    const col = document.createElement('div');
                    col.className = 'col-md-3 col-sm-4 mb-2';
                    col.innerHTML = `
                        <div class="position-relative">
                            <img src="${e.target.result}" 
                                 alt="Preview ${index + 1}" 
                                 class="img-thumbnail" 
                                 style="width: 100%; height: 120px; object-fit: cover;">
                            <small class="d-block text-center text-muted mt-1">${file.name}</small>
                        </div>
                    `;
                    galeriPreview.appendChild(col);
                };
                reader.readAsDataURL(file);
            });
        });
    }
});

function tambahJamOperasional() {
    let container = document.getElementById('jam-operasional-container');
    let newRow = document.createElement('div');
    newRow.className = 'row align-items-center mb-2 operasional-row';
    newRow.innerHTML = `
        <div class="col-md-4 mb-2 mb-md-0">
            <select name="jam_operasional_hari[]" class="form-control form-select select-hari" required>
                <option value="">-- Pilih Hari --</option>
                <option value="Setiap Hari">Setiap Hari</option>
                <option value="Senin - Jumat">Senin - Jumat</option>
                <option value="Sabtu - Minggu">Sabtu - Minggu</option>
                <option value="Senin">Senin</option>
                <option value="Selasa">Selasa</option>
                <option value="Rabu">Rabu</option>
                <option value="Kamis">Kamis</option>
                <option value="Jumat">Jumat</option>
                <option value="Sabtu">Sabtu</option>
                <option value="Minggu">Minggu</option>
            </select>
        </div>
        <div class="col-md-3 col-6">
            <div class="input-group">
                <input type="time" name="jam_operasional_buka[]" class="form-control" value="08:00" required>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="input-group">
                <input type="time" name="jam_operasional_tutup[]" class="form-control" value="22:00" required>
            </div>
        </div>
        <div class="col-md-2 text-right text-md-center">
            <button type="button" class="btn btn-outline-danger btn-sm rounded-circle hapus-operasional" onclick="hapusRow(this)">
                <i class="fas fa-trash-alt"></i>
            </button>
        </div>
    `;
    container.appendChild(newRow);
}

function hapusRow(btn) {
    let row = btn.closest('.operasional-row');
    let container = document.getElementById('jam-operasional-container');
    if (container.getElementsByClassName('operasional-row').length > 1) {
        row.remove();
    } else {
        alert('Minimal harus ada satu jadwal operasional!');
    }
}
</script>

<style>
  .note-editor.note-frame {
    border: 1px solid #dee2e6;
    border-radius: 6px;
  }
  .note-toolbar {
    background-color: #f8f9fa !important;
    border-bottom: 1px solid #dee2e6 !important;
  }
</style>
@endsection

