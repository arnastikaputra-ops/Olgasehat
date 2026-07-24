@extends('pemiliklapangan.layout.ownervenue')

@section('content')
<div class="content-wrapper dashboard-onboarding">
    <div class="content-header border-0 pb-0">
        <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-between flex-wrap">
                <div>
                    <p class="breadcrumb-dashboard mb-1 text-muted">Onboarding • Detail & Jam Operasional</p>
                    <h1 class="page-title mb-0">Detail & Jam Operasional</h1>
                    <p class="text-muted mb-0">Tambahkan fasilitas, galeri, deskripsi, serta atur jam operasional & slot pemesanan untuk venue Anda.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="content pt-3">
        <div class="container-fluid">
            <div class="row">
                <!-- Stepper -->
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <div class="card border-0 shadow-sm h-100 stepper-card">
                        <div class="card-body">
                            <h5 class="mb-4 font-weight-bold text-primary">Langkah Onboarding</h5>
                            <ul class="timeline list-unstyled mb-0">
                                <li class="timeline-item">
                                    <span class="dot"><i class="fas fa-handshake"></i></span>
                                    <div class="timeline-content">
                                        <h6>Selamat Datang</h6>
                                        <p class="text-muted mb-0 small">Mulai perjalanan mengelola venue anda.</p>
                                    </div>
                                </li>
                                <li class="timeline-item">
                                    <span class="dot">1</span>
                                    <div class="timeline-content">
                                        <h6>Informasi Venue</h6>
                                        <p class="text-muted mb-0 small">Isi detail umum venue dan kontak utama.</p>
                                    </div>
                                </li>
                                <li class="timeline-item timeline-active">
                                    <span class="dot">2</span>
                                    <div class="timeline-content">
                                        <h6>Detail & Jam Operasional</h6>
                                        <p class="text-muted mb-0 small">Tambahkan fasilitas, galeri, deskripsi, serta atur jam operasional & slot pemesanan.</p>
                                    </div>
                                </li>
                                <li class="timeline-item">
                                    <span class="dot"><i class="fas fa-check"></i></span>
                                    <div class="timeline-content">
                                        <h6>Selesai</h6>
                                        <p class="text-muted mb-0 small">Verifikasi & terbitkan venue anda.</p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Konten Utama -->
                <div class="col-lg-8">
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                    <div class="card border-0 shadow-sm info-card">
                        <div class="card-body p-4">
                            <form action="{{ route('insertdetail') }}" method="post" enctype="multipart/form-data" id="detailForm">
                            @csrf

                            {{-- Hidden Venue ID --}}
                            @if(isset($venue))
                                <input type="hidden" name="venue_id" value="{{ $venue->id }}">
                                <div class="alert alert-info">
                                    <strong>Info:</strong> Anda sedang mengedit venue: <strong>{{ $venue->namavenue }}</strong>
                                </div>
                            @elseif(session('venue_id'))
                                <input type="hidden" name="venue_id" value="{{ session('venue_id') }}">
                            @else
                                <div class="alert alert-warning">
                                    <strong>Peringatan!</strong> Data venue tidak ditemukan. 
                                    <a href="{{ route('informasi') }}" class="alert-link">Klik di sini untuk membuat venue baru</a>.
                                </div>
                            @endif

                            {{-- Video Review --}}
                            <div class="mb-3">
                                <label for="video_link" class="form-label">Video Review (Link YouTube)</label>
                                <input type="url" id="video_link" name="video_review" class="form-control @error('video_review') is-invalid @enderror"
                                    placeholder="https://www.youtube.com/watch?v=abc123"
                                    value="{{ old('video_review', isset($venue) ? $venue->video_review : '') }}">
                                @error('video_review')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Masukkan link YouTube (bukan upload video).</small>

                                <div class="video-preview mt-3 text-center" id="videoPreview" style="display:none;">
                                    <iframe width="100%" height="315" src="" frameborder="0" allowfullscreen></iframe>
                                </div>
                            </div>

                            {{-- Detail Venue --}}
                            <div class="mb-3">
                                <label class="form-label">Detail Venue</label>
                                <textarea id="summernote-detail" name="detail" class="form-control">{{ old('detail', isset($venue) ? $venue->detail : '') }}</textarea>
                                @error('detail')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Aturan Venue --}}
                            <div class="mb-3">
                                <label class="form-label">Aturan Venue</label>
                                <textarea id="summernote-aturan" name="aturan" class="form-control">{{ old('aturan', isset($venue) ? $venue->aturan : '') }}</textarea>
                                @error('aturan')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Lokasi --}}
                            <div class="mb-3">
                                <label class="form-label">Lokasi Venue (Link Google Maps)</label>
                                <input type="url" name="lokasi" class="form-control @error('lokasi') is-invalid @enderror"
                                    placeholder="https://www.google.com/maps/place/..."
                                    value="{{ old('lokasi', isset($venue) ? $venue->lokasi : '') }}">
                                @error('lokasi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Fasilitas --}}
                            <div class="mb-3">
                                <label class="form-label d-block">Fasilitas Venue</label>
                                <div class="row">
                                    @php
                                        $fasilitas = [
                                            'Area Parkir', 'Toilet/Kamar Mandi', 'Ruang Ganti/Transit', 
                                            'Tempat Ibadah (Musholla)', 'Kantin/Area Catering', 'AC/Pendingin Udara',
                                            'Sistem Tata Suara (Sound System)', 'Proyektor & Layar/LED', 
                                            'Akses Internet (Wi-Fi)', 'Akses Listrik Cadangan (Genset)',
                                            'Area Registrasi/Lobi', 'Keamanan (Security) & P3K'
                                        ];
                                        $selectedFasilitas = [];
                                        $customFasilitas = [];
                                        if (old('fasilitas_venue')) {
                                            $selectedFasilitas = old('fasilitas_venue');
                                        } elseif (isset($venue) && $venue->fasilitas) {
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
                                                if (in_array($fas, $fasilitas)) {
                                                    $selectedFasilitas[] = $fas;
                                                } else {
                                                    $customFasilitas[] = $fas;
                                                }
                                            }
                                        }
                                    @endphp

                                    @foreach ($fasilitas as $item)
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

                            {{-- Galeri Foto Multiple --}}
                            <div class="mb-3">
                                <label class="form-label d-block">Galeri Foto Venue</label>
                                <input type="file" name="galeri_foto[]" id="galeri_foto" class="form-control @error('galeri_foto') is-invalid @enderror" 
                                       accept="image/*" multiple>
                                @error('galeri_foto')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                                @error('galeri_foto.*')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Pilih multiple gambar untuk galeri venue (maksimal 10 gambar, format: JPG, PNG, maksimal 2MB per gambar)</small>
                                
                                {{-- Preview galeri yang sudah ada --}}
                                @if(isset($venue) && $venue->galleries->count() > 0)
                                    <div class="mt-3">
                                        <p class="small text-muted mb-2">Galeri yang sudah diupload:</p>
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

                                {{-- Preview gambar baru yang dipilih --}}
                                <div id="galeriPreview" class="mt-3 row"></div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                                <a href="{{ route('informasi') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                                </a>
                                <button type="submit" class="btn btn-primary btn-lg px-5" {{ !isset($venue) && !session('venue_id') ? 'disabled' : '' }}>
                                    Simpan & Selesai <i class="fas fa-arrow-right ml-2"></i>
                                </button>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .dashboard-onboarding {
        background: linear-gradient(180deg, #f6f9ff 0%, #ffffff 100%);
        min-height: 100vh;
    }
    .dashboard-onboarding .content-wrapper {
        background: transparent;
    }
    .breadcrumb-dashboard {
        font-size: 0.85rem;
        letter-spacing: 0.02em;
    }
    .page-title {
        font-weight: 700;
        color: #1d2c5b;
    }
    .stepper-card {
        border-radius: 18px;
    }
    .timeline {
        position: relative;
        padding-left: 1.5rem;
    }
    .timeline::before {
        content: "";
        position: absolute;
        left: 18px;
        top: 5px;
        bottom: 5px;
        width: 2px;
        background: linear-gradient(180deg, #d7e5ff 0%, #edf2ff 100%);
    }
    .timeline-item {
        position: relative;
        padding-left: 2.5rem;
        margin-bottom: 1.5rem;
    }
    .timeline-item:last-child {
        margin-bottom: 0;
    }
    .timeline-item .dot {
        position: absolute;
        left: 0;
        top: 0;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #e0e9ff;
        color: #4a63ff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        box-shadow: inset 0 0 0 4px #f7f9ff;
    }
    .timeline-item.timeline-active .dot {
        background: linear-gradient(135deg, #0096ff 0%, #00c6ff 100%);
        color: #fff;
        box-shadow: 0 8px 18px rgba(0, 150, 255, 0.35);
    }
    .timeline-content h6 {
        font-weight: 700;
        margin-bottom: 0.2rem;
        color: #152345;
    }
    .info-card {
        border-radius: 18px;
    }
    .form-control, .form-select {
        border-radius: 10px;
        border: 1px solid #e0e0e0;
        transition: all 0.3s ease;
    }
    .form-control:focus, .form-select:focus {
        border-color: #0096ff;
        box-shadow: 0 0 0 0.2rem rgba(0, 150, 255, 0.15);
    }
    .btn-primary {
        background: linear-gradient(135deg, #0096ff 0%, #00c6ff 100%);
        border: none;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 150, 255, 0.3);
    }
    .video-preview iframe {
        border-radius: 12px;
        max-width: 560px;
        width: 100%;
        height: 315px;
    }
    .note-editor.note-frame {
        border: 1px solid #e0e0e0;
        border-radius: 10px;
    }
    .note-toolbar {
        background-color: #f8f9fa !important;
        border-bottom: 1px solid #e0e0e0 !important;
    }
    @media (max-width: 991.98px) {
        .timeline::before {
            left: 16px;
        }
        .timeline-item .dot {
            width: 32px;
            height: 32px;
        }
    }
</style>

{{-- Tambahkan CSS Summernote --}}
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">

{{-- Script Summernote --}}
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

    // Preview Video
    const videoInput = document.getElementById('video_link');
    const videoPreview = document.getElementById('videoPreview');
    const iframe = videoPreview.querySelector('iframe');

    if (videoInput.value) updateVideoPreview(videoInput.value);

    videoInput.addEventListener('input', function () {
        updateVideoPreview(this.value);
    });

    function updateVideoPreview(url) {
        const videoId = extractYouTubeId(url);
        if (videoId) {
            iframe.src = `https://www.youtube.com/embed/${videoId}`;
            videoPreview.style.display = 'block';
        } else {
            videoPreview.style.display = 'none';
            iframe.src = '';
        }
    }

    function extractYouTubeId(url) {
        const regex = /(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/watch\?v=|youtu\.be\/)([\w\-]+)/;
        const match = url.match(regex);
        return match && match[1] ? match[1] : null;
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
