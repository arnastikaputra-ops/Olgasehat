@extends('pemiliklapangan.layout.ownervenue')

@section('content')
@php
    $rawBusinessPhone = trim(optional($mitra)->kontak_bisnis ?? '');
    $businessPhoneDisplay = $rawBusinessPhone;
    if (\Illuminate\Support\Str::startsWith($rawBusinessPhone, '+62')) {
        $businessPhoneDisplay = substr($rawBusinessPhone, 3);
    } elseif (\Illuminate\Support\Str::startsWith($rawBusinessPhone, '62')) {
        $businessPhoneDisplay = substr($rawBusinessPhone, 2);
    }
@endphp

<div class="content-wrapper owner-settings">
    <div class="content-header border-0 pb-0">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h1 class="page-title mb-1">Pengaturan Bisnis</h1>
                    <p class="text-muted mb-0">Kelola keamanan akun, profil pemilik, dan informasi bisnis anda.</p>
                </div>
                <div class="d-flex align-items-center mt-3 mt-md-0">
                    <span class="badge badge-soft-primary mr-2"><i class="fas fa-user-shield mr-1"></i> Pemilik Aktif</span>
                    <span class="badge badge-soft-success"><i class="fas fa-check-circle mr-1"></i> Terverifikasi</span>
                </div>
            </div>
        </div>
    </div>

    <div class="content pt-3">
        <div class="container-fluid">
            <div class="card border-0 shadow-sm owner-summary mb-4">
                <div class="card-body d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="owner-avatar mr-3 position-relative d-flex align-items-center justify-content-center" style="width: 70px; height: 70px; border-radius: 20px; overflow: hidden; background: linear-gradient(135deg, #0096ff 0%, #00c6ff 100%);">
                            @if(optional($user)->image)
                                <img src="{{ asset($user->image) }}" alt="Foto Profil" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <span style="color: white; font-weight: 700; font-size: 1.8rem;">{{ strtoupper(substr(optional($user)->name ?? 'P', 0, 1)) }}</span>
                            @endif
                        </div>
                        <div>
                            <h5 class="mb-1 font-weight-bold" style="color: #1b2b5a;">{{ optional($mitra)->nama_bisnis ?? 'Nama Bisnis' }}</h5>
                            <p class="text-muted mb-1 font-weight-600"><i class="fas fa-user-circle text-primary mr-1"></i> {{ optional($user)->name ?? '-' }}</p>
                            <div class="text-muted small d-flex align-items-center">
                                <i class="fas fa-envelope text-info mr-2"></i>
                                <span>{{ optional($user)->email ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 mt-md-0 text-md-right">
                        <p class="text-muted small mb-1">ID Bisnis</p>
                        <h6 class="mb-0 font-weight-bold text-primary">#{{ optional($mitra)->id ? str_pad($mitra->id, 6, '0', STR_PAD_LEFT) : '000000' }}</h6>
                    </div>
                </div>
            </div>

            <!-- GLOBAL ALERT MESSAGES -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-lg mb-4" role="alert" style="border-left: 4px solid #28a745;">
                    <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show rounded-lg mb-4" role="alert" style="border-left: 4px solid #dc3545;">
                    <i class="fas fa-exclamation-triangle mr-2"></i> <strong>Terjadi Kesalahan:</strong>
                    <ul class="mb-0 mt-1 pl-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                <div class="card-body p-0">
                    <ul class="nav nav-tabs owner-tabs px-4 pt-4" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="profile-user-tab" data-toggle="tab" href="#profile-user" role="tab" aria-controls="profile-user" aria-selected="true">
                                <i class="fas fa-user-edit mr-1"></i> Profil User & Foto
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="account-tab" data-toggle="tab" href="#account-security" role="tab" aria-controls="account-security" aria-selected="false">
                                <i class="fas fa-lock mr-1"></i> Keamanan Akun
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="business-profile-tab" data-toggle="tab" href="#business-profile" role="tab" aria-controls="business-profile" aria-selected="false">
                                <i class="fas fa-store mr-1"></i> Profil Bisnis
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content p-4">
                        <!-- TAB 1: PROFIL USER & FOTO PROFIL -->
                        <div class="tab-pane fade show active" id="profile-user" role="tabpanel" aria-labelledby="profile-user-tab">
                            <h5 class="section-title mb-3"><i class="fas fa-id-card text-primary mr-2"></i> Profil User & Foto Profil</h5>
                            
                            <form action="{{ route('pemilik.pengaturan.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                
                                <!-- EDIT FOTO PROFIL CONTAINER -->
                                <div class="card p-3 border-0 rounded-lg mb-4" style="background: #f8fafc; border: 1.5px solid #e2e8f0 !important;">
                                    <label class="font-weight-bold mb-2" style="color: #1b2b5a; font-size: 1rem;">
                                        <i class="fas fa-camera text-primary mr-1"></i> Foto Profil Pemilik Venue
                                    </label>
                                    <div class="d-flex align-items-center flex-wrap">
                                        <div class="position-relative mr-4 mb-2 mb-md-0" style="width: 100px; height: 100px;">
                                            <div id="fotoProfilWrapper" class="w-100 h-100 rounded-circle shadow-sm overflow-hidden d-flex align-items-center justify-content-center border" style="border: 3px solid #0096ff !important; background: linear-gradient(135deg, #0096ff 0%, #00c6ff 100%);">
                                                <img id="previewFotoProfil" src="{{ optional($user)->image ? asset($user->image) : '' }}" 
                                                     class="{{ optional($user)->image ? '' : 'd-none' }}" 
                                                     alt="Preview Foto" 
                                                     style="width: 100%; height: 100%; object-fit: cover;">
                                                <span id="fallbackInitialsFotoProfil" class="{{ optional($user)->image ? 'd-none' : '' }}" style="font-size: 2.2rem; font-weight: 700; color: white;">
                                                    {{ strtoupper(substr(optional($user)->name ?? 'P', 0, 1)) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="mb-2">
                                                <input type="file" name="image" id="inputFotoProfil" class="d-none" accept="image/jpeg,image/png,image/jpg,image/webp">
                                                <label for="inputFotoProfil" class="btn btn-primary rounded-pill px-3 py-2 shadow-sm mb-0" style="cursor: pointer; font-size: 0.9rem;">
                                                    <i class="fas fa-upload mr-1"></i> Upload Foto Baru
                                                </label>
                                            </div>
                                            <small class="text-muted d-block">
                                                <i class="fas fa-info-circle mr-1"></i> Format: <strong>JPG, PNG, WEBP</strong> (Maksimal 4 MB).<br>
                                                Foto akan langsung terlihat dan tersimpan dengan rapi.
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label class="font-weight-bold text-dark">Nama Lengkap Pemilik <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" value="{{ old('name', optional($user)->name) }}" placeholder="Masukkan nama pemilik" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="font-weight-bold text-dark">Email Akun <span class="text-danger">*</span></label>
                                        <input type="email" name="email" class="form-control" value="{{ old('email', optional($user)->email) }}" placeholder="Masukkan email pemilik" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="font-weight-bold text-dark">No. Telepon / WhatsApp <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light">+62</span>
                                        </div>
                                        <input type="text" name="kontak_bisnis" class="form-control" value="{{ old('kontak_bisnis', $businessPhoneDisplay) }}" placeholder="81234567890" required>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end mt-4">
                                    <button type="submit" class="btn btn-success px-4" style="background: #28a745; border-color: #28a745; border-radius: 10px; font-weight: 600;">
                                        <i class="fas fa-save mr-1"></i> Simpan Profil & Foto
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- TAB 2: KEAMANAN AKUN -->
                        <div class="tab-pane fade" id="account-security" role="tabpanel" aria-labelledby="account-tab">
                            <h5 class="section-title mb-3"><i class="fas fa-shield-alt text-primary mr-2"></i> Ubah Password</h5>
                            <form action="{{ route('pemilik.pengaturan.update') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark">Password Saat Ini <span class="text-danger">*</span></label>
                                    <div class="input-group input-password">
                                        <input type="password" name="password_lama" class="form-control" placeholder="Masukkan password saat ini">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-light toggle-password" type="button"><i class="fas fa-eye-slash"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark">Password Baru <span class="text-danger">*</span></label>
                                    <div class="input-group input-password">
                                        <input type="password" name="password_baru" class="form-control" placeholder="Masukkan password baru">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-light toggle-password" type="button"><i class="fas fa-eye-slash"></i></button>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted">Minimal 8 karakter.</small>
                                </div>
                                <div class="d-flex justify-content-end mt-4">
                                    <button type="submit" class="btn btn-primary px-4" style="border-radius: 10px; font-weight: 600;">
                                        <i class="fas fa-key mr-1"></i> Update Password
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- TAB 3: PROFIL BISNIS -->
                        <div class="tab-pane fade" id="business-profile" role="tabpanel" aria-labelledby="business-profile-tab">
                            <h5 class="section-title mb-3"><i class="fas fa-store text-primary mr-2"></i> Profil Bisnis Venue</h5>
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
                            <form action="{{ route('pemilik.pengaturan.update') }}" method="POST" class="mt-4">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>Nama Bisnis <span class="text-danger">*</span></label>
                                        <input type="text" name="nama_bisnis" class="form-control @error('nama_bisnis') is-invalid @enderror" value="{{ old('nama_bisnis', optional($mitra)->nama_bisnis ?? '') }}" placeholder="Masukkan nama bisnis" required>
                                        @error('nama_bisnis')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>No. Telepon <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-light">+62</span>
                                            </div>
                                            <input type="text" name="kontak_bisnis" class="form-control @error('kontak_bisnis') is-invalid @enderror" value="{{ old('kontak_bisnis', $businessPhoneDisplay) }}" placeholder="81234567890" required>
                                        </div>
                                        @error('kontak_bisnis')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Masukkan nomor telepon tanpa kode negara (+62)</small>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Alamat Bisnis</label>
                                    <textarea class="form-control" rows="3" placeholder="Masukkan alamat lengkap"></textarea>
                                </div>

                                <div class="mt-4">
                                    <h6 class="font-weight-bold mb-3"><i class="fas fa-university text-primary mr-2"></i>Rekening Bank Pemilik Venue (Untuk Penerimaan Pembayaran Membership)</h6>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>Nama Pemilik Rekening <span class="text-danger">*</span></label>
                                            <input type="text" name="nama_pemilik_rekening" class="form-control" placeholder="Nama sesuai buku tabungan" value="{{ old('nama_pemilik_rekening', $venue->nama_pemilik_rekening ?? '') }}">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Nama Bank <span class="text-danger">*</span></label>
                                            <input type="text" name="nama_bank" class="form-control" placeholder="Contoh: BCA / Mandiri / BRI" value="{{ old('nama_bank', $venue->nama_bank ?? '') }}">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label>No. Rekening <span class="text-danger">*</span></label>
                                            <input type="text" name="nomor_rekening" class="form-control" placeholder="Contoh: 1234567890" value="{{ old('nomor_rekening', $venue->nomor_rekening ?? '') }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane fade" id="business-manager" role="tabpanel" aria-labelledby="manager-tab">
                            <h5 class="section-title">Pengelola Bisnis</h5>
                            <p class="text-muted small mb-4">Tambahkan pengelola untuk membantu mengelola jadwal, transaksi, dan laporan bisnis.</p>

                            <div class="table-responsive">
                                <table class="table owner-table mb-4">
                                    <thead>
                                        <tr>
                                            <th>Nama</th>
                                            <th>Email</th>
                                            <th>Peran</th>
                                            <th>Status</th>
                                            <th class="text-right">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ optional($user)->name ?? 'Pengelola Utama' }}</td>
                                            <td>{{ optional($user)->email ?? '-' }}</td>
                                            <td>Pemilik</td>
                                            <td><span class="badge badge-status badge-success">Aktif</span></td>
                                            <td class="text-right">
                                                <button class="btn btn-sm btn-outline-secondary" type="button">
                                                    <i class="fas fa-ellipsis-h"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">Tambah pengelola baru untuk menampilkan data di sini.</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <button class="btn btn-outline-primary"><i class="fas fa-user-plus mr-1"></i> Tambah Pengelola</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .owner-settings {
        background: #f4f8ff;
        min-height: 100vh;
    }
    .owner-settings .content-wrapper {
        background: transparent;
    }
    .page-title {
        font-weight: 700;
        color: #1b2b5a;
    }
    .badge-soft-primary {
        background: rgba(0, 149, 255, 0.15);
        color: #007bff;
        border-radius: 999px;
        padding: 0.5rem 1rem;
        font-weight: 600;
    }
    .badge-soft-success {
        background: rgba(40, 200, 120, 0.15);
        color: #2ac078;
        border-radius: 999px;
        padding: 0.5rem 1rem;
        font-weight: 600;
    }
    .owner-summary {
        border-radius: 20px;
    }
    .owner-avatar {
        width: 60px;
        height: 60px;
        border-radius: 16px;
        background: linear-gradient(135deg, #0096ff 0%, #00c6ff 100%);
        color: #fff;
        font-weight: 700;
        font-size: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 12px 24px rgba(0, 150, 255, 0.2);
    }
    .owner-tabs .nav-link {
        border: none;
        color: #6f7a94;
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        position: relative;
        margin-right: 1rem;
    }
    .owner-tabs .nav-link.active {
        color: #1b2b5a;
    }
    .owner-tabs .nav-link.active::after {
        content: '';
        position: absolute;
        left: 0;
        right: 0;
        bottom: -1px;
        height: 3px;
        border-radius: 999px;
        background: linear-gradient(90deg, #0096ff 0%, #00c6ff 100%);
    }
    .section-title {
        font-weight: 700;
        color: #1b2b5a;
    }
    .input-password .btn {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
        color: #7081a9;
    }
    .input-password .btn:hover {
        color: #1b2b5a;
    }
    .owner-table thead th {
        border: none;
        color: #6f7a94;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .owner-table tbody td {
        border: none;
        padding: 1.25rem 0.75rem;
        vertical-align: middle;
        color: #1b2b5a;
        font-weight: 500;
    }
    .badge-status {
        border-radius: 999px;
        padding: 0.35rem 0.75rem;
        font-weight: 600;
        font-size: 0.75rem;
    }
    .badge-status.badge-success {
        background: rgba(40, 200, 120, 0.15);
        color: #2ac078;
    }
    .toggle-password {
        border: none;
        background: #f7f9ff;
    }
    .toggle-password:focus {
        box-shadow: none;
    }
    @media (max-width: 767.98px) {
        .owner-tabs .nav-link {
            margin-right: 0;
            padding: 0.75rem 1rem;
        }
        .owner-summary {
            text-align: center;
        }
        .owner-summary .owner-avatar {
            margin: 0 auto 1rem;
        }
        .owner-summary .text-md-right {
            text-align: center !important;
        }
    }
</style>

<script>
    // Live preview for profile image upload
    document.getElementById('inputFotoProfil')?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(evt) {
                const preview = document.getElementById('previewFotoProfil');
                const fallback = document.getElementById('fallbackInitialsFotoProfil');
                if (preview) {
                    preview.src = evt.target.result;
                    preview.classList.remove('d-none');
                }
                if (fallback) {
                    fallback.classList.add('d-none');
                }
            };
            reader.readAsDataURL(file);
        }
    });

    document.querySelectorAll('.toggle-password').forEach(function(button) {
        button.addEventListener('click', function () {
            const input = this.closest('.input-group').querySelector('input');
            const icon = this.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                input.type = 'password';
                icon.classList.add('fa-eye-slash');
                icon.classList.remove('fa-eye');
            }
        });
    });
</script>
@endsection

