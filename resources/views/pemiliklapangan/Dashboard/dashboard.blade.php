@extends('pemiliklapangan.layout.ownervenue')

@section('content')
<div class="content-wrapper dashboard-onboarding bg-light pb-5">
    <!-- Header Welcome -->
    <div class="content-header border-0 pb-0">
        <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-between flex-wrap">
                <div>
                    <p class="breadcrumb-dashboard mb-1 text-muted">Kelola Fasilitas • Dashboard Pemilik</p>
                    <h1 class="page-title mb-0 font-weight-bold text-dark">Selamat Datang, {{ Auth::user()->name }}</h1>
                    <p class="text-muted mb-0">Kami siapkan pengalaman onboarding yang mudah dan cepat agar venue Anda segera tayang.</p>
                </div>
                <div class="welcome-badge mt-3 mt-md-0">
                    <span class="badge badge-primary px-3 py-2 font-weight-bold shadow-xs">
                        <i class="fas fa-bolt mr-1"></i> Quick Start
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="content pt-3">
        <div class="container-fluid">
            <!-- ======================================================== -->
            <!-- TAMPILAN AWAL (HERO & ONBOARDING STEPPER)                -->
            <!-- ======================================================== -->
            <div class="row mb-4">
                <!-- Stepper Onboarding Card -->
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <div class="card border-0 shadow-sm h-100 stepper-card" style="border-radius: 18px;">
                        <div class="card-body p-4">
                            <h5 class="mb-4 font-weight-bold text-primary"><i class="fas fa-tasks mr-2"></i>Langkah Onboarding</h5>
                            <ul class="timeline list-unstyled mb-0">
                                <li class="timeline-item timeline-active">
                                    <span class="dot"><i class="fas fa-handshake"></i></span>
                                    <div class="timeline-content">
                                        <h6 class="font-weight-bold mb-1">Selamat Datang</h6>
                                        <p class="text-muted mb-0 small">Mulai perjalanan mengelola venue Anda.</p>
                                    </div>
                                </li>
                                <li class="timeline-item">
                                    <span class="dot">1</span>
                                    <div class="timeline-content">
                                        <h6 class="font-weight-bold mb-1">Informasi Venue</h6>
                                        <p class="text-muted mb-0 small">Isi detail umum venue dan kontak utama.</p>
                                    </div>
                                </li>
                                <li class="timeline-item">
                                    <span class="dot">2</span>
                                    <div class="timeline-content">
                                        <h6 class="font-weight-bold mb-1">Detail & Jam Operasional</h6>
                                        <p class="text-muted mb-0 small">Tambahkan fasilitas, galeri, deskripsi, serta atur jam operasional & slot pemesanan.</p>
                                    </div>
                                </li>
                                <li class="timeline-item">
                                    <span class="dot"><i class="fas fa-check"></i></span>
                                    <div class="timeline-content">
                                        <h6 class="font-weight-bold mb-1">Selesai & Terbit</h6>
                                        <p class="text-muted mb-0 small">Verifikasi & terbitkan venue Anda.</p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Welcome Hero Banner & Tips -->
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm hero-card mb-4 text-white bg-gradient-primary" style="border-radius: 20px; background: linear-gradient(135deg, #1b2b5a 0%, #0056b3 100%); overflow: hidden;">
                        <div class="card-body p-4 p-md-5 d-flex flex-column flex-lg-row align-items-center justify-content-between">
                            <div class="hero-content pr-lg-4">
                                <span class="badge badge-light text-primary font-weight-bold px-3 py-1 mb-3 shadow-xs">Tersisa 2 langkah lagi</span>
                                <h3 class="font-weight-bold mb-3 text-white">Mari tampilkan venue terbaik Anda kepada ribuan pengguna OLGA Sehat.</h3>
                                <p class="text-light mb-4 opacity-90 small">Dengan melengkapi beberapa informasi penting, pelanggan dapat menemukan fasilitas Anda dan melakukan pemesanan secara online.</p>
                                <a href="{{ route('informasi') }}" class="btn btn-light text-primary btn-lg font-weight-bold shadow-md">
                                    Mulai Lengkapi Data <i class="fas fa-arrow-right ml-2"></i>
                                </a>
                                <div class="hero-footnote text-white-50 mt-3 small">
                                    <i class="fas fa-shield-alt mr-2 text-warning"></i>Data Anda terlindungi dan hanya digunakan untuk keperluan verifikasi.
                                </div>
                            </div>
                            <div class="hero-illustration mt-4 mt-lg-0 text-center">
                                <img src="{{ asset('assets/ilus.png') }}" alt="Welcome Illustration" style="max-height: 180px; width: auto;" class="img-fluid">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <div class="card border-0 shadow-sm h-100 info-card" style="border-radius: 15px;">
                                <div class="card-body">
                                    <div class="icon-wrapper bg-light text-primary p-2 rounded-circle d-inline-block mb-2">
                                        <i class="fas fa-lightbulb fa-lg"></i>
                                    </div>
                                    <h6 class="font-weight-bold text-dark">Tips Cepat</h6>
                                    <p class="text-muted small mb-3">Gunakan foto berkualitas tinggi dan highlight fasilitas unggulan untuk menarik perhatian calon pelanggan.</p>
                                    <a href="{{ route('informasi') }}" class="text-primary small font-weight-bold">
                                        Kelola Venue <i class="fas fa-arrow-right ml-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100 info-card" style="border-radius: 15px;">
                                <div class="card-body">
                                    <div class="icon-wrapper bg-light text-success p-2 rounded-circle d-inline-block mb-2">
                                        <i class="fas fa-headset fa-lg"></i>
                                    </div>
                                    @php
                                        $waContact = \App\Models\ContactUs::where('type', 'whatsapp')
                                            ->orWhere('title', 'like', '%whatsapp%')
                                            ->orWhere('title', 'like', '%wa%')
                                            ->first();
                                        $rawWa = $waContact->kontak ?? '0812-3456-7890';
                                        $cleanWa = preg_replace('/[^0-9]/', '', $rawWa);
                                        if (\Illuminate\Support\Str::startsWith($cleanWa, '0')) {
                                            $cleanWa = '62' . substr($cleanWa, 1);
                                        }
                                        $waLink = "https://wa.me/{$cleanWa}?text=" . urlencode("Halo CS OlgaSehat, saya butuh bantuan mengenai fasilitas venue");
                                    @endphp
                                    <a href="{{ $waLink }}" target="_blank" class="text-success small font-weight-bold">
                                        Hubungi CS WA <i class="fab fa-whatsapp ml-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PENUNJUK SCROLL KE BAWAH -->
            <div class="text-center my-4 py-2">
                <div class="d-inline-flex align-items-center bg-white px-4 py-2 rounded-pill shadow-xs border">
                    <i class="fas fa-chevron-down text-primary mr-2 animate-bounce"></i>
                    <span class="font-weight-bold text-dark small">Gulir ke bawah untuk Filter & Daftar Venue Lapangan Anda</span>
                    <i class="fas fa-chevron-down text-primary ml-2 animate-bounce"></i>
                </div>
            </div>

            <hr class="my-4 border-top">

            <!-- 1. STATISTIK RINGKASAN VENUE & FASILITAS -->
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card border-0 shadow-sm bg-gradient-primary text-white h-100" style="border-radius: 15px;">
                        <div class="card-body p-3 d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-white-50 text-uppercase font-weight-bold">Total Venue Lapangan</small>
                                <h2 class="font-weight-bold mb-0 mt-1">{{ $totalVenues }} <span class="h6 font-weight-normal">Venue</span></h2>
                                <small class="text-white-50">({{ $totalLapangan }} Sub-Lapangan)</small>
                            </div>
                            <div class="icon-circle bg-white-20 p-3 rounded-circle">
                                <i class="fas fa-map-marker-alt fa-2x text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card border-0 shadow-sm bg-gradient-success text-white h-100" style="border-radius: 15px;">
                        <div class="card-body p-3 d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-white-50 text-uppercase font-weight-bold">Jam Operasional</small>
                                <h2 class="font-weight-bold mb-0 mt-1">{{ $venuesWithHours }} / {{ $totalVenues }}</h2>
                                <small class="text-white-50">Sudah Mengatur Jam Buka/Tutup</small>
                            </div>
                            <div class="icon-circle bg-white-20 p-3 rounded-circle">
                                <i class="fas fa-clock fa-2x text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card border-0 shadow-sm bg-gradient-warning text-dark h-100" style="border-radius: 15px;">
                        <div class="card-body p-3 d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-dark-50 text-uppercase font-weight-bold">Status VIP Membership</small>
                                <h2 class="font-weight-bold mb-0 mt-1">{{ $venuesWithMembership }} <span class="h6 font-weight-normal">Mitra VIP</span></h2>
                                <small class="text-dark-50">{{ $memberships->count() }} Paket Membership Aktif</small>
                            </div>
                            <div class="icon-circle bg-black-10 p-3 rounded-circle">
                                <i class="fas fa-crown fa-2x text-dark"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card border-0 shadow-sm bg-gradient-info text-white h-100" style="border-radius: 15px;">
                        <div class="card-body p-3 d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-white-50 text-uppercase font-weight-bold">Komunitas & Mabar</small>
                                <h2 class="font-weight-bold mb-0 mt-1">{{ $communities->count() }} <span class="h6 font-weight-normal">Sesi</span></h2>
                                <small class="text-white-50">Komunitas & Mabar Terbuka</small>
                            </div>
                            <div class="icon-circle bg-white-20 p-3 rounded-circle">
                                <i class="fas fa-users fa-2x text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. FILTER & SEARCH CONTROL PANEL -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                <div class="card-body p-3 bg-white" style="border-radius: 15px;">
                    <div class="row align-items-center">
                        <!-- Search Box -->
                        <div class="col-lg-4 col-md-12 mb-2 mb-lg-0">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light border-right-0"><i class="fas fa-search text-muted"></i></span>
                                </div>
                                <input type="text" id="inputSearchVenue" class="form-control bg-light border-left-0" placeholder="Cari Nama Venue, Kategori, atau Alamat...">
                            </div>
                        </div>
                        
                        <!-- Filter Jam Operasional -->
                        <div class="col-lg-3 col-md-4 mb-2 mb-lg-0">
                            <select id="selectFilterHours" class="form-control bg-light">
                                <option value="all">🔍 Semua Jam Operasional</option>
                                <option value="set">🟢 Sudah Ada Jam Operasional</option>
                                <option value="unset">🔴 Belum Diatur</option>
                            </select>
                        </div>

                        <!-- Filter Membership -->
                        <div class="col-lg-3 col-md-4 mb-2 mb-lg-0">
                            <select id="selectFilterMembership" class="form-control bg-light">
                                <option value="all">👑 Semua VIP Membership</option>
                                <option value="yes">👑 Ada VIP Membership</option>
                                <option value="no">⚪ Tanpa Membership</option>
                            </select>
                        </div>

                        <!-- Filter Komunitas -->
                        <div class="col-lg-2 col-md-4">
                            <select id="selectFilterCommunity" class="form-control bg-light">
                                <option value="all">👥 Semua Komunitas</option>
                                <option value="yes">👥 Ada Komunitas</option>
                                <option value="no">⚪ Tanpa Komunitas</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. DAFTAR KARTU VENUE & FASILITAS LAPANGAN -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="font-weight-bold text-dark mb-0"><i class="fas fa-list-ul mr-2 text-primary"></i>Daftar Venue Lapangan Anda</h5>
                <span class="badge badge-secondary font-weight-normal px-3 py-1" id="venueCountBadge">Menampilkan {{ $venues->count() }} Venue</span>
            </div>

            <div class="row" id="venueCardContainer">
                @forelse($venues as $v)
                    @php
                        $bukaLabel = is_array($v->jam_buka) ? implode(':', $v->jam_buka) : (string)($v->jam_buka ?? '');
                        $tutupLabel = is_array($v->jam_tutup) ? implode(':', $v->jam_tutup) : (string)($v->jam_tutup ?? '');
                        $hasHours = !empty($v->jam_operasional) || (!empty($bukaLabel) && !empty($tutupLabel));
                        if (is_array($v->jam_operasional) && count($v->jam_operasional) > 0) {
                            $firstOp = $v->jam_operasional[0];
                            $bukaLabel = $firstOp['buka'] ?? '';
                            $tutupLabel = $firstOp['tutup'] ?? '';
                        }
                        
                        $vMemberships = $memberships->where('pendaftaran_id', $v->id);
                        $hasMembership = $v->is_membership_discount || $vMemberships->count() > 0;
                        $vCommunities = $communities->where('pendaftaran_id', $v->id);
                        $hasCommunity = $vCommunities->count() > 0;
                        
                        // Deteksi Gambar Venue (Logo utama atau Galeri Foto)
                        $fotoUrl = asset('assets/olgasehat-icon.png');
                        if (!empty($v->logo)) {
                            $rawFoto = $v->logo;
                            $fPath = is_array($rawFoto) ? ($rawFoto[0] ?? '') : (string)$rawFoto;
                            if (!empty($fPath) && is_string($fPath)) {
                                if (\Illuminate\Support\Str::startsWith($fPath, 'http')) {
                                    $fotoUrl = $fPath;
                                } elseif (\Illuminate\Support\Str::startsWith($fPath, 'storage/')) {
                                    $fotoUrl = asset($fPath);
                                } else {
                                    $fotoUrl = asset('storage/' . $fPath);
                                }
                            }
                        } elseif ($v->galleries && $v->galleries->count() > 0) {
                            $firstGal = $v->galleries->first()->foto;
                            if (!empty($firstGal) && is_string($firstGal)) {
                                if (\Illuminate\Support\Str::startsWith($firstGal, 'http')) {
                                    $fotoUrl = $firstGal;
                                } elseif (\Illuminate\Support\Str::startsWith($firstGal, 'storage/')) {
                                    $fotoUrl = asset($firstGal);
                                } else {
                                    $fotoUrl = asset('storage/' . $firstGal);
                                }
                            }
                        }

                        $katLabel = is_array($v->kategori) ? implode(', ', $v->kategori) : (string)($v->kategori ?? 'Venue');
                        $kabLabel = is_array($v->kota) ? implode(', ', $v->kota) : (string)($v->kota ?? '');
                        $namaLabel = is_array($v->namavenue) ? implode(' ', $v->namavenue) : (string)($v->namavenue ?? 'Venue Olahraga');
                        $searchKey = strtolower($namaLabel . ' ' . $katLabel . ' ' . $kabLabel);
                    @endphp
                    <div class="col-lg-6 col-xl-4 mb-4 venue-card-item" 
                         data-name="{{ $searchKey }}"
                         data-hours="{{ $hasHours ? 'set' : 'unset' }}"
                         data-membership="{{ $hasMembership ? 'yes' : 'no' }}"
                         data-community="{{ $hasCommunity ? 'yes' : 'no' }}">
                        <div class="card h-100 border-0 shadow-sm overflow-hidden" style="border-radius: 16px;">
                            <!-- Clean Venue Card Header -->
                            <div class="position-relative bg-dark" style="height: 170px; overflow: hidden;">
                                <img src="{{ $fotoUrl }}" 
                                     onerror="this.onerror=null;this.src='{{ asset('assets/olgasehat-icon.png') }}';" 
                                     alt="{{ $namaLabel }}" 
                                     class="w-100 h-100" 
                                     style="object-fit: cover; opacity: 0.95;">
                                     
                                <!-- Badge Top Left: Kategori -->
                                <div class="position-absolute" style="top: 10px; left: 10px; z-index: 3;">
                                    <span class="badge badge-info font-weight-bold text-uppercase px-2.5 py-1.5 shadow-sm" style="font-size: 11px;">
                                        <i class="fas fa-tag mr-1"></i>{{ $katLabel }}
                                    </span>
                                </div>

                                <!-- Badge Top Right: VIP Member -->
                                @if($hasMembership)
                                    <div class="position-absolute" style="top: 10px; right: 10px; z-index: 3;">
                                        <span class="badge badge-warning text-dark font-weight-bold px-2.5 py-1.5 shadow-sm" style="font-size: 11px;">
                                            <i class="fas fa-crown mr-1"></i>VIP Member
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <!-- Card Body (Clean Non-Overlapping Padding) -->
                            <div class="card-body p-3 d-flex flex-column bg-white">
                                <h5 class="font-weight-bold text-dark mb-1 text-truncate" title="{{ $namaLabel }}">{{ $namaLabel }}</h5>
                                <p class="text-muted small mb-3">
                                    <i class="fas fa-map-marker-alt text-danger mr-1"></i>{{ $kabLabel ?: 'Kota/Kab' }}, {{ is_array($v->provinsi) ? implode(', ', $v->provinsi) : ($v->provinsi ?? '') }}
                                </p>

                                <div class="border-top pt-2 mb-3">
                                    <!-- 1. Jam Operasional -->
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="small font-weight-bold text-secondary"><i class="fas fa-clock text-info mr-1"></i> Jam Operasional:</span>
                                        @if($hasHours)
                                            <span class="badge badge-success px-2 py-1 font-weight-bold" style="font-size: 11px;">
                                                <i class="fas fa-check-circle mr-1"></i>{{ $bukaLabel }} - {{ $tutupLabel }}
                                            </span>
                                        @else
                                            <span class="badge badge-danger px-2 py-1 font-weight-bold" style="font-size: 11px;">
                                                <i class="fas fa-exclamation-triangle mr-1"></i>Belum Diatur
                                            </span>
                                        @endif
                                    </div>

                                    <!-- 2. Status Membership -->
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="small font-weight-bold text-secondary"><i class="fas fa-crown text-warning mr-1"></i> VIP Membership:</span>
                                        @if($hasMembership)
                                            <span class="badge badge-warning text-dark px-2 py-1 font-weight-bold" style="font-size: 11px;">
                                                <i class="fas fa-percentage mr-1"></i>Diskon {{ (float)($v->membership_discount_percent ?? 10) }}%
                                            </span>
                                        @else
                                            <span class="badge badge-light text-muted border px-2 py-1" style="font-size: 11px;">
                                                Belum Ada Paket
                                            </span>
                                        @endif
                                    </div>

                                    <!-- 3. Status Komunitas / Mabar -->
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="small font-weight-bold text-secondary"><i class="fas fa-users text-primary mr-1"></i> Komunitas & Mabar:</span>
                                        @if($hasCommunity)
                                            <span class="badge badge-primary px-2 py-1 font-weight-bold" style="font-size: 11px;">
                                                <i class="fas fa-check mr-1"></i>{{ $vCommunities->count() }} Sesi Aktif
                                            </span>
                                        @else
                                            <span class="badge badge-light text-muted border px-2 py-1" style="font-size: 11px;">
                                                Belum Ada Sesi
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Quick Action Buttons (Uniform Equal Heights) -->
                                <div class="mt-auto border-top pt-3">
                                    <div class="row row-cols-2 g-2">
                                        <div class="col mb-2">
                                            <a href="{{ route('detail', $v->id) }}" class="btn btn-sm btn-outline-primary btn-block font-weight-bold py-1.5 px-1 text-nowrap" style="font-size: 11px;">
                                                <i class="fas fa-edit mr-1"></i> Edit Detail
                                            </a>
                                        </div>
                                        <div class="col mb-2">
                                            <a href="{{ route('fasilitas.detail', $v->id) }}" class="btn btn-sm btn-primary btn-block font-weight-bold py-1.5 px-1 text-nowrap shadow-xs" style="font-size: 11px;">
                                                <i class="fas fa-eye mr-1"></i> Detail Lapangan
                                            </a>
                                        </div>
                                        <div class="col">
                                            <a href="{{ route('pemilik.membership') }}" class="btn btn-sm btn-outline-warning text-dark btn-block font-weight-bold py-1.5 px-1 text-nowrap" style="font-size: 11px;">
                                                <i class="fas fa-crown mr-1"></i> Member
                                            </a>
                                        </div>
                                        <div class="col">
                                            <a href="{{ route('pemilik.komunitas') }}" class="btn btn-sm btn-outline-info btn-block font-weight-bold py-1.5 px-1 text-nowrap" style="font-size: 11px;">
                                                <i class="fas fa-plus mr-1"></i> Sesi Mabar
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-light text-center border py-5" style="border-radius: 15px;">
                            <i class="fas fa-store-slash fa-3x text-muted mb-3"></i>
                            <h5 class="font-weight-bold text-dark">Belum Ada Venue Lapangan</h5>
                            <p class="text-muted small">Anda belum menambahkan venue lapangan. Klik tombol di bawah untuk mendaftarkan venue pertama Anda.</p>
                            <a href="{{ route('informasi') }}" class="btn btn-primary font-weight-bold px-4">
                                <i class="fas fa-plus mr-1"></i> Tambah Venue Baru
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- 4. DAFTAR KOMUNITAS & SESI MABAR TERHUBUNG -->
            <div class="card border-0 shadow-sm mt-4" style="border-radius: 15px;">
                <div class="card-header bg-white font-weight-bold d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-users text-info mr-2"></i>Daftar Komunitas & Sesi Mabar Lapangan Anda</span>
                    <a href="{{ route('pemilik.komunitas') }}" class="btn btn-sm btn-success font-weight-bold">
                        <i class="fas fa-plus mr-1"></i> Buat Komunitas / Sesi Mabar Baru
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Sesi / Komunitas</th>
                                    <th>Jenis</th>
                                    <th>Harga Patungan / Sesi</th>
                                    <th>Link WA / Kontak</th>
                                    <th>Status Admin</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($communities as $idx => $act)
                                    <tr>
                                        <td>{{ $idx + 1 }}</td>
                                        <td><strong class="text-dark">{{ $act->nama }}</strong></td>
                                        <td><span class="badge badge-info uppercase">{{ $act->jenis }}</span></td>
                                        <td><strong>Rp {{ number_format($act->harga ?? 0, 0, ',', '.') }}</strong></td>
                                        <td>
                                            @if($act->link_kontak)
                                                <a href="{{ $act->link_kontak }}" target="_blank" class="btn btn-xs btn-outline-success">
                                                    <i class="fab fa-whatsapp mr-1"></i> Buka WA
                                                </a>
                                            @else
                                                <span class="text-muted small">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($act->status == 'approved')
                                                <span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle mr-1"></i>Tayang</span>
                                            @elseif($act->status == 'pending')
                                                <span class="badge badge-warning px-2 py-1"><i class="fas fa-clock mr-1"></i>Verifikasi</span>
                                            @else
                                                <span class="badge badge-secondary px-2 py-1">{{ strtoupper($act->status) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">Belum ada Komunitas / Sesi Mabar yang dibuat.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
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
        padding-left: 0.5rem;
    }
    .timeline::before {
        content: "";
        position: absolute;
        left: 22px;
        top: 5px;
        bottom: 5px;
        width: 2px;
        background: linear-gradient(180deg, #d7e5ff 0%, #edf2ff 100%);
    }
    .timeline-item {
        position: relative;
        padding-left: 3rem;
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
        z-index: 2;
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
            left: 20px;
        }
        .timeline-item .dot {
            width: 32px;
            height: 32px;
        }
    }
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const inputSearch = document.getElementById('inputSearchVenue');
    const selectHours = document.getElementById('selectFilterHours');
    const selectMembership = document.getElementById('selectFilterMembership');
    const selectCommunity = document.getElementById('selectFilterCommunity');
    const cards = document.querySelectorAll('.venue-card-item');
    const badgeCount = document.getElementById('venueCountBadge');

    function filterVenues() {
        const query = inputSearch.value.toLowerCase().trim();
        const hoursVal = selectHours.value;
        const membershipVal = selectMembership.value;
        const communityVal = selectCommunity.value;

        let visibleCount = 0;

        cards.forEach(card => {
            const nameData = card.getAttribute('data-name');
            const hoursData = card.getAttribute('data-hours');
            const membershipData = card.getAttribute('data-membership');
            const communityData = card.getAttribute('data-community');

            const matchSearch = query === '' || nameData.includes(query);
            const matchHours = hoursVal === 'all' || hoursData === hoursVal;
            const matchMembership = membershipVal === 'all' || membershipData === membershipVal;
            const matchCommunity = communityVal === 'all' || communityData === communityVal;

            if (matchSearch && matchHours && matchMembership && matchCommunity) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        if (badgeCount) {
            badgeCount.textContent = `Menampilkan ${visibleCount} dari ${cards.length} Venue`;
        }
    }

    if (inputSearch) inputSearch.addEventListener('keyup', filterVenues);
    if (selectHours) selectHours.addEventListener('change', filterVenues);
    if (selectMembership) selectMembership.addEventListener('change', filterVenues);
    if (selectCommunity) selectCommunity.addEventListener('change', filterVenues);
});
</script>
@endpush
@endsection
