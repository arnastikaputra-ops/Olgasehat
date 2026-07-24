@extends('pemiliklapangan.layout.ownervenue')

@section('content')
<div class="content-wrapper dashboard-onboarding">
    <div class="content-header border-0 pb-0">
        <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-between flex-wrap">
                <div>
                    <p class="breadcrumb-dashboard mb-1 text-muted">Onboarding • Informasi Venue</p>
                    <h1 class="page-title mb-0">Informasi Venue</h1>
                    <p class="text-muted mb-0">Isi detail umum venue dan kontak utama untuk memulai.</p>
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
                                <li class="timeline-item timeline-active">
                                    <span class="dot">1</span>
                                    <div class="timeline-content">
                                        <h6>Informasi Venue</h6>
                                        <p class="text-muted mb-0 small">Isi detail umum venue dan kontak utama.</p>
                                    </div>
                                </li>
                                <li class="timeline-item">
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
                    <!-- Tampilkan pesan error/success -->
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="card border-0 shadow-sm info-card">
                        <div class="card-body p-4">
                        <form action="{{ route('insertinform') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="image" class="form-label">Gambar Banner</label>
                                <input type="file" id="image" name="logo" class="form-control @error('logo') is-invalid @enderror" accept="image/*">
                                @error('logo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <div class="image-preview mt-2" id="thumbnailInput">
                                    <img src="#" alt="Image Preview" id="previewImage">
                                    <span class="preview-text" id="previewText">No image selected</span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="judulProgram" class="form-label">Nama Venue</label>
                                <input type="text" name="namavenue" class="form-control @error('namavenue') is-invalid @enderror" value="{{ old('namavenue') }}">
                                @error('namavenue')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="provinsi" class="form-label">Pilih Provinsi</label>
                                <select class="form-control @error('provinsi') is-invalid @enderror" id="provinsi" name="provinsi">
                                    <option value="">-- Pilih Provinsi --</option>
                                    <option value="Bali" {{ old('provinsi') == 'Bali' ? 'selected' : '' }}>Bali</option>
                                    <option value="Jawa Timur" {{ old('provinsi') == 'Jawa Timur' ? 'selected' : '' }}>Jawa Timur</option>
                                    <option value="DKI Jakarta" {{ old('provinsi') == 'DKI Jakarta' ? 'selected' : '' }}>DKI Jakarta</option>
                                    <option value="Jawa Barat" {{ old('provinsi') == 'Jawa Barat' ? 'selected' : '' }}>Jawa Barat</option>
                                    <option value="Sumatera Utara" {{ old('provinsi') == 'Sumatera Utara' ? 'selected' : '' }}>Sumatera Utara</option>
                                </select>
                                @error('provinsi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="kabupaten" class="form-label">Pilih Kabupaten/Kota</label>
                                <select class="form-control @error('kota') is-invalid @enderror" id="kabupaten" name="kota">
                                    <option value="">-- Pilih Kabupaten/Kota --</option>
                                </select>
                                @error('kota')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
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
                                        $selectedKategori = old('kategori', []);
                                        if (!is_array($selectedKategori)) {
                                            $selectedKategori = [];
                                        }
                                    @endphp
                                    @foreach($olahragaOptions as $olahraga)
                                        <div class="col-md-4 col-sm-6 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input kategori-checkbox" 
                                                       type="checkbox" 
                                                       name="kategori[]" 
                                                       value="{{ $olahraga }}" 
                                                       id="kategori-{{ Str::slug($olahraga) }}"
                                                       {{ in_array($olahraga, $selectedKategori) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="kategori-{{ Str::slug($olahraga) }}">
                                                    {{ $olahraga }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="mt-2">
                                    <small class="text-muted" id="kategori-count">Terpilih: 0/5</small>
                                </div>
                                @error('kategori')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                                @error('kategori.*')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                                
                                {{-- Custom Olahraga --}}
                                <div class="mt-3">
                                    <label class="form-label">Olahraga Lainnya (Custom)</label>
                                    <div id="custom-olahraga-container">
                                        @if(old('custom_olahraga'))
                                            @foreach(old('custom_olahraga') as $index => $customOlahraga)
                                                <div class="custom-olahraga-item mb-2">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control custom-olahraga-input" 
                                                               name="custom_olahraga[]" 
                                                               value="{{ $customOlahraga }}" 
                                                               placeholder="Masukkan nama olahraga lainnya">
                                                        <button type="button" class="btn btn-outline-danger remove-custom-olahraga" type="button">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="add-custom-olahraga">
                                        <i class="fas fa-plus"></i> Tambah Olahraga Lainnya
                                    </button>
                                    <small class="text-muted d-block mt-1">Tambahkan olahraga yang tidak ada dalam daftar</small>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="nomor_telepon" class="form-label">Nomor Telepon <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">+62</span>
                                    </div>
                                    @php
                                        // Ambil nomor telepon dari mitra jika ada, atau dari old input
                                        $defaultPhone = old('nomor_telepon');
                                        if (!$defaultPhone && $mitra && $mitra->kontak_bisnis) {
                                            $rawPhone = trim($mitra->kontak_bisnis);
                                            // Hilangkan +62 atau 62 di depan jika ada
                                            if (\Illuminate\Support\Str::startsWith($rawPhone, '+62')) {
                                                $defaultPhone = substr($rawPhone, 3);
                                            } elseif (\Illuminate\Support\Str::startsWith($rawPhone, '62')) {
                                                $defaultPhone = substr($rawPhone, 2);
                                            } else {
                                                $defaultPhone = $rawPhone;
                                            }
                                        }
                                    @endphp
                                    <input type="text" id="nomor_telepon" name="nomor_telepon" class="form-control @error('nomor_telepon') is-invalid @enderror" 
                                           placeholder="81234567890" value="{{ $defaultPhone }}">
                                </div>
                                @error('nomor_telepon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Masukkan nomor telepon tanpa kode negara (+62)</small>
                            </div>

                            <div class="mb-3">
                                <label for="email_venue" class="form-label">Email Venue <span class="text-danger">*</span></label>
                                @php
                                    // Ambil email dari mitra jika ada, atau dari old input
                                    $defaultEmail = old('email_venue');
                                    if (!$defaultEmail && $mitra && $mitra->email_bisnis) {
                                        $defaultEmail = $mitra->email_bisnis;
                                    }
                                @endphp
                                <input type="email" id="email_venue" name="email_venue" class="form-control @error('email_venue') is-invalid @enderror" 
                                       placeholder="venue@example.com" value="{{ $defaultEmail }}">
                                @error('email_venue')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Email untuk kontak venue</small>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                                <a href="/pemiliklapangan/dashboard" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                                </a>
                                <button type="submit" class="btn btn-primary btn-lg px-5">
                                    Selanjutnya <i class="fas fa-arrow-right ml-2"></i>
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
    .image-preview {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 300px;
        border: 2px dashed #ddd;
        border-radius: 12px;
        position: relative;
        overflow: hidden;
        background-color: #f8f9fa;
        padding: 10px;
        transition: all 0.3s ease;
    }
    .image-preview:hover {
        border-color: #0096ff;
        background-color: #f0f7ff;
    }
    .image-preview img {
        max-height: 100%;
        max-width: 100%;
        object-fit: cover;
        display: none;
        border-radius: 8px;
    }
    .preview-text {
        font-size: 16px;
        color: #6c757d;
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

<script>
// Image preview handling
document.addEventListener("DOMContentLoaded", function () {
    const imageInput = document.getElementById("image");
    const previewImage = document.getElementById("previewImage");
    const previewText = document.getElementById("previewText");

    imageInput.addEventListener("change", function () {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                previewImage.src = e.target.result;
                previewImage.style.display = "block";
                previewText.style.display = "none";
            };
            reader.readAsDataURL(file);
        } else {
            previewImage.style.display = "none";
            previewText.style.display = "block";
        }
    });
});

// Script untuk dropdown wilayah
document.addEventListener('DOMContentLoaded', function () {
    const dataWilayah = {
        "Bali": ["Denpasar", "Badung", "Gianyar", "Tabanan", "Bangli", "Klungkung", "Karangasem", "Buleleng", "Jembrana"],
        "Jawa Timur": ["Surabaya", "Malang", "Kediri", "Sidoarjo", "Banyuwangi", "Blitar"],
        "DKI Jakarta": ["Jakarta Selatan", "Jakarta Barat", "Jakarta Timur", "Jakarta Utara", "Jakarta Pusat"],
        "Jawa Barat": ["Bandung", "Bogor", "Bekasi", "Cirebon", "Sukabumi"],
        "Sumatera Utara": ["Medan", "Binjai", "Tebing Tinggi", "Pematangsiantar"]
    };

    const provinsiSelect = document.getElementById('provinsi');
    const kabupatenSelect = document.getElementById('kabupaten');

    // Set nilai sebelumnya jika ada
    const oldProvinsi = "{{ old('provinsi') }}";
    const oldKota = "{{ old('kota') }}";
    
    if (oldProvinsi) {
        provinsiSelect.value = oldProvinsi;
        // Trigger change untuk memuat kabupaten
        setTimeout(() => {
            provinsiSelect.dispatchEvent(new Event('change'));
            
            // Set nilai kota setelah dropdown terisi
            setTimeout(() => {
                if (oldKota) {
                    kabupatenSelect.value = oldKota;
                }
            }, 100);
        }, 100);
    }

    provinsiSelect.addEventListener('change', function() {
        const provinsi = this.value;
        kabupatenSelect.innerHTML = '<option value="">-- Pilih Kabupaten/Kota --</option>';

        if (provinsi && dataWilayah[provinsi]) {
            dataWilayah[provinsi].forEach(kab => {
                const option = document.createElement('option');
                option.value = kab;
                option.textContent = kab;
                kabupatenSelect.appendChild(option);
            });
        }
    });

    // Handle kategori checkbox - maksimal 5 pilihan
    const kategoriCheckboxes = document.querySelectorAll('.kategori-checkbox');
    const kategoriCount = document.getElementById('kategori-count');
    
    function updateKategoriCount() {
        const checked = document.querySelectorAll('.kategori-checkbox:checked').length;
        const customCount = document.querySelectorAll('.custom-olahraga-input').length;
        const total = checked + customCount;
        kategoriCount.textContent = `Terpilih: ${total}/5`;
        
        if (total >= 5) {
            // Disable unchecked checkboxes
            kategoriCheckboxes.forEach(cb => {
                if (!cb.checked) {
                    cb.disabled = true;
                }
            });
        } else {
            // Enable all checkboxes
            kategoriCheckboxes.forEach(cb => {
                cb.disabled = false;
            });
        }
    }
    
    kategoriCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const checked = document.querySelectorAll('.kategori-checkbox:checked').length;
            const customCount = document.querySelectorAll('.custom-olahraga-input').length;
            const total = checked + customCount;
            
            if (this.checked && total > 5) {
                this.checked = false;
                alert('Maksimal 5 cabang olahraga yang dapat dipilih!');
                return;
            }
            
            updateKategoriCount();
        });
    });

    // Handle custom olahraga
    const customOlahragaContainer = document.getElementById('custom-olahraga-container');
    const addCustomOlahragaBtn = document.getElementById('add-custom-olahraga');

    if (addCustomOlahragaBtn && customOlahragaContainer) {
        addCustomOlahragaBtn.addEventListener('click', function() {
            const checked = document.querySelectorAll('.kategori-checkbox:checked').length;
            const customCount = document.querySelectorAll('.custom-olahraga-input').length;
            const total = checked + customCount;
            
            if (total >= 5) {
                alert('Maksimal 5 cabang olahraga yang dapat dipilih!');
                return;
            }
            
            const newItem = document.createElement('div');
            newItem.className = 'custom-olahraga-item mb-2';
            newItem.innerHTML = `
                <div class="input-group">
                    <input type="text" class="form-control custom-olahraga-input" 
                           name="custom_olahraga[]" 
                           placeholder="Masukkan nama olahraga lainnya">
                    <button type="button" class="btn btn-outline-danger remove-custom-olahraga" type="button">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            customOlahragaContainer.appendChild(newItem);
            updateKategoriCount();
        });

        // Remove custom olahraga
        document.addEventListener('click', function(e) {
            if (e.target.closest('.remove-custom-olahraga')) {
                e.target.closest('.custom-olahraga-item').remove();
                updateKategoriCount();
            }
        });
    }

    // Update count on page load
    updateKategoriCount();

    // Form validation
    const form = document.querySelector('form[action="{{ route('insertinform') }}"]');
    if (form) {
        form.addEventListener('submit', function(e) {
            const checked = document.querySelectorAll('.kategori-checkbox:checked').length;
            const customInputs = document.querySelectorAll('.custom-olahraga-input');
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
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection