@extends('pemilikkesehatan.Layout.pengelolakesehatan')

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
                    <p class="text-muted mb-0">Kelola keamanan akun, profil pengelola, dan informasi fasilitas kesehatan anda.</p>
                </div>
                <div class="d-flex align-items-center mt-3 mt-md-0">
                    <span class="badge badge-soft-primary mr-2"><i class="fas fa-user-shield mr-1"></i> Pengelola Aktif</span>
                    <span class="badge badge-soft-success"><i class="fas fa-check-circle mr-1"></i> Terverifikasi</span>
                </div>
            </div>
        </div>
    </div>

    <div class="content pt-3">
        <div class="container-fluid">
            <div class="card border-0 shadow-sm owner-summary mb-4">
                <div class="card-body d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between">
                    <div class="d-flex align-items-start">
                        <div class="owner-avatar mr-3">
                            <span>{{ strtoupper(substr(optional($user)->name ?? 'PK', 0, 1)) }}</span>
                        </div>
                        <div>
                            <h5 class="mb-1">{{ optional($mitra)->nama_bisnis ?? 'Nama Fasilitas' }}</h5>
                            <p class="text-muted mb-2">{{ optional($user)->name ?? '-' }}</p>
                            <div class="text-muted small d-flex align-items-center">
                                <i class="fas fa-phone-alt mr-2"></i>
                                <span>+62 {{ $businessPhoneDisplay ?: 'Belum diisi' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 mt-md-0 text-md-right">
                        <p class="text-muted small mb-1">ID Fasilitas</p>
                        <h6 class="mb-0 font-weight-bold">#{{ optional($mitra)->id ? str_pad($mitra->id, 6, '0', STR_PAD_LEFT) : '000000' }}</h6>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <ul class="nav nav-tabs owner-tabs px-4 pt-4" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="account-tab" data-toggle="tab" href="#account-security" role="tab" aria-controls="account-security" aria-selected="true">Keamanan Akun</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-user-tab" data-toggle="tab" href="#profile-user" role="tab" aria-controls="profile-user" aria-selected="false">Profil User</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="business-profile-tab" data-toggle="tab" href="#business-profile" role="tab" aria-controls="business-profile" aria-selected="false">Profil Fasilitas</a>
                        </li>
                    </ul>

                    <div class="tab-content p-4">
                        <div class="tab-pane fade show active" id="account-security" role="tabpanel" aria-labelledby="account-tab">
                            <h5 class="section-title">Ubah Password</h5>
                            <form action="javascript:void(0)" class="mt-4">
                                <div class="form-group">
                                    <label>Password Lama <span class="text-danger">*</span></label>
                                    <div class="input-group input-password">
                                        <input type="password" class="form-control" placeholder="Masukkan password lama">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-light toggle-password" type="button"><i class="fas fa-eye-slash"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Password Baru <span class="text-danger">*</span></label>
                                    <div class="input-group input-password">
                                        <input type="password" class="form-control" placeholder="Masukkan password baru">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-light toggle-password" type="button"><i class="fas fa-eye-slash"></i></button>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted">Gunakan 8 karakter atau lebih dengan kombinasi huruf, angka, atau simbol.</small>
                                </div>
                                <div class="form-group mb-4">
                                    <label>Ulangi Password Baru <span class="text-danger">*</span></label>
                                    <div class="input-group input-password">
                                        <input type="password" class="form-control" placeholder="Ulangi password baru">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-light toggle-password" type="button"><i class="fas fa-eye-slash"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="button" class="btn btn-light mr-3">Batalkan</button>
                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane fade" id="profile-user" role="tabpanel" aria-labelledby="profile-user-tab">
                            <h5 class="section-title">Profil User</h5>
                            <form action="javascript:void(0)" class="mt-4">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>Nama <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" value="{{ optional($user)->name ?? '' }}" placeholder="Masukkan nama">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" value="{{ optional($user)->email ?? '' }}" placeholder="Masukkan email">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>No. Telepon <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light">+62</span>
                                        </div>
                                        <input type="text" class="form-control" value="{{ $businessPhoneDisplay }}" placeholder="81234567890">
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="button" class="btn btn-light mr-3">Batalkan</button>
                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane fade" id="business-profile" role="tabpanel" aria-labelledby="business-profile-tab">
                            <h5 class="section-title">Profil Fasilitas Kesehatan</h5>
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
                            <form action="{{ route('pengelola.pengaturan.update') }}" method="POST" class="mt-4">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>Nama Fasilitas <span class="text-danger">*</span></label>
                                        <input type="text" name="nama_bisnis" class="form-control @error('nama_bisnis') is-invalid @enderror" value="{{ old('nama_bisnis', optional($mitra)->nama_bisnis ?? '') }}" placeholder="Masukkan nama fasilitas kesehatan" required>
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
                                    <label>Alamat Fasilitas</label>
                                    <textarea class="form-control" rows="3" placeholder="Masukkan alamat lengkap"></textarea>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
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
    .toggle-password {
        border: none;
        background: #f7f9ff;
    }
    .toggle-password:focus {
        box-shadow: none;
    }
</style>

<script>
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

