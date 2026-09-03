@extends('pemilikkesehatan.Layout.pengelolakesehatan')

@section('content')
<div class="content-wrapper" style="background: #f4f8ff; min-height: 100vh;">
    <div class="content-header border-0 pb-0">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h1 class="page-title mb-1" style="font-weight: 700; color: #1b2b5a;">Dashboard Pengelola Kesehatan</h1>
                    <p class="text-muted mb-0">Ringkasan aktivitas dan statistik klinik Anda</p>
                </div>
                <ol class="breadcrumb float-md-right mt-2 mt-md-0">
                    <li class="breadcrumb-item"><a href="{{ route('pengelola.dashboard') }}">Dashboard</a></li>
                </ol>
            </div>
        </div>
    </div>

    <div class="content pt-3">
    <div class="container-fluid">
        <!-- Statistik Cards -->
        <div class="row">
            <div class="col-6 col-lg-3 mb-3">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $totalClinics }}</h3>
                        <p>Total Klinik</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-hospital"></i>
                    </div>
                    <a href="{{ route('pengelola.clinics') }}" class="small-box-footer">
                        Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-6 col-lg-3 mb-3">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $totalDoctors }}</h3>
                        <p>Total Dokter</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <a href="{{ route('pengelola.doctors.index') }}" class="small-box-footer">
                        Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-6 col-lg-3 mb-3">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $pendingBookings }}</h3>
                        <p>Booking Pending</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <a href="{{ route('pengelola.bookings.index', ['status' => 'pending']) }}" class="small-box-footer">
                        Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-6 col-lg-3 mb-3">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $todayBookings }}</h3>
                        <p>Booking Hari Ini</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                    <a href="{{ route('pengelola.bookings.index', ['tanggal' => today()->format('Y-m-d')]) }}" class="small-box-footer">
                        Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- FILTER & SEARCH CONTROL PANEL UNTUK PENGELOLA KLINIK -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
            <div class="card-body p-3 bg-white" style="border-radius: 15px;">
                <div class="row align-items-center">
                    <div class="col-lg-4 col-md-12 mb-2 mb-lg-0">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light border-right-0"><i class="fas fa-search text-muted"></i></span>
                            </div>
                            <input type="text" id="inputSearchClinic" class="form-control bg-light border-left-0" placeholder="Cari Nama Klinik, Spesialisasi, atau Alamat...">
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-4 mb-2 mb-lg-0">
                        <select id="selectFilterClinicHours" class="form-control bg-light">
                            <option value="all">🔍 Semua Jam Operasional</option>
                            <option value="set">🟢 Ada Jam Operasional</option>
                            <option value="unset">🔴 Belum Diatur</option>
                        </select>
                    </div>

                    <div class="col-lg-3 col-md-4 mb-2 mb-lg-0">
                        <select id="selectFilterClinicMembership" class="form-control bg-light">
                            <option value="all">👑 Semua VIP Membership</option>
                            <option value="yes">👑 Ada VIP Membership</option>
                            <option value="no">⚪ Tanpa Membership</option>
                        </select>
                    </div>

                    <div class="col-lg-2 col-md-4">
                        <select id="selectFilterClinicDoctors" class="form-control bg-light">
                            <option value="all">👨‍⚕️ Semua Dokter</option>
                            <option value="yes">👨‍⚕️ Ada Dokter</option>
                            <option value="no">⚪ Belum Ada Dokter</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- DAFTAR KLINIK & STATUS TERDRAFT -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="font-weight-bold mb-0" style="color: #1b2b5a;"><i class="fas fa-hospital mr-2 text-info"></i>Daftar Klinik Kesehatan Anda</h5>
            <a href="{{ route('pengelola.clinics.create') }}" class="btn btn-sm btn-info font-weight-bold shadow-xs">
                <i class="fas fa-plus mr-1"></i> Tambah Klinik Baru
            </a>
        </div>

        <div class="row mb-4" id="clinicCardContainer">
            @forelse($clinics as $c)
                @php
                    $bukaLabel = is_array($c->jam_buka) ? implode(':', $c->jam_buka) : (string)($c->jam_buka ?? '');
                    $tutupLabel = is_array($c->jam_tutup) ? implode(':', $c->jam_tutup) : (string)($c->jam_tutup ?? '');
                    $hasHours = !empty($bukaLabel) && !empty($tutupLabel);

                    $cMemberships = isset($clinicMemberships) ? $clinicMemberships->where('clinic_id', $c->id) : collect();
                    $hasMembership = $c->is_membership_discount || $cMemberships->count() > 0;
                    $docCount = $c->doctors ? $c->doctors->count() : 0;
                    $hasDoctors = $docCount > 0;

                    $cFoto = asset('assets/klnk.png');
                    if (!empty($c->logo)) {
                        $rawFoto = $c->logo;
                        $fPath = is_array($rawFoto) ? ($rawFoto[0] ?? '') : (string)$rawFoto;
                        if (!empty($fPath) && is_string($fPath)) {
                            if (\Illuminate\Support\Str::startsWith($fPath, 'http')) {
                                $cFoto = $fPath;
                            } elseif (\Illuminate\Support\Str::startsWith($fPath, 'storage/')) {
                                $cFoto = asset($fPath);
                            } elseif (\Illuminate\Support\Str::startsWith($fPath, 'fotoklinik/')) {
                                $cFoto = asset($fPath);
                            } else {
                                $cFoto = asset('fotoklinik/' . $fPath);
                            }
                        }
                    } elseif (!empty($c->foto_utama)) {
                        $rawFoto = $c->foto_utama;
                        $fPath = is_array($rawFoto) ? ($rawFoto[0] ?? '') : (string)$rawFoto;
                        if (!empty($fPath) && is_string($fPath)) {
                            if (\Illuminate\Support\Str::startsWith($fPath, 'http')) {
                                $cFoto = $fPath;
                            } elseif (\Illuminate\Support\Str::startsWith($fPath, 'storage/')) {
                                $cFoto = asset($fPath);
                            } elseif (\Illuminate\Support\Str::startsWith($fPath, 'fotoklinik/')) {
                                $cFoto = asset($fPath);
                            } else {
                                $cFoto = asset('fotoklinik/' . $fPath);
                            }
                        }
                    } elseif ($c->galleries && $c->galleries->count() > 0) {
                        $firstGal = $c->galleries->first()->foto;
                        if (!empty($firstGal) && is_string($firstGal)) {
                            if (\Illuminate\Support\Str::startsWith($firstGal, 'http')) {
                                $cFoto = $firstGal;
                            } elseif (\Illuminate\Support\Str::startsWith($firstGal, 'storage/')) {
                                $cFoto = asset($firstGal);
                            } elseif (\Illuminate\Support\Str::startsWith($firstGal, 'clinic_galleries/')) {
                                $cFoto = asset('storage/' . $firstGal);
                            } elseif (\Illuminate\Support\Str::startsWith($firstGal, 'fotoklinik/')) {
                                $cFoto = asset($firstGal);
                            } else {
                                $cFoto = asset('fotoklinik/' . $firstGal);
                            }
                        }
                    }

                    $namaLabel = is_array($c->nama) ? implode(' ', $c->nama) : (string)($c->nama ?? 'Klinik Kesehatan');
                    $alamatLabel = is_array($c->alamat) ? implode(', ', $c->alamat) : (string)($c->alamat ?? 'Alamat Klinik');
                    $searchKey = strtolower($namaLabel . ' ' . $alamatLabel);
                @endphp
                <div class="col-lg-4 col-md-6 mb-3 clinic-card-item"
                     data-name="{{ $searchKey }}"
                     data-hours="{{ $hasHours ? 'set' : 'unset' }}"
                     data-membership="{{ $hasMembership ? 'yes' : 'no' }}"
                     data-doctors="{{ $hasDoctors ? 'yes' : 'no' }}">
                    <div class="card h-100 border-0 shadow-sm overflow-hidden" style="border-radius: 16px;">
                        <div class="position-relative bg-dark" style="height: 160px; overflow: hidden;">
                            <img src="{{ $cFoto }}" 
                                 onerror="this.onerror=null;this.src='{{ asset('assets/klnk.png') }}';" 
                                 alt="{{ $namaLabel }}" 
                                 class="w-100 h-100" 
                                 style="object-fit: cover; opacity: 0.95;">
                                 
                            @if($hasMembership)
                                <div class="position-absolute" style="top: 10px; right: 10px; z-index: 3;">
                                    <span class="badge badge-warning text-dark font-weight-bold px-2.5 py-1.5 shadow-sm" style="font-size: 11px;">
                                        <i class="fas fa-crown mr-1"></i>VIP Member
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="card-body d-flex flex-column p-3 bg-white">
                            <h6 class="font-weight-bold text-dark mb-1 text-truncate" title="{{ $namaLabel }}">{{ $namaLabel }}</h6>
                            <p class="text-muted small mb-2"><i class="fas fa-map-marker-alt text-danger mr-1"></i>{{ $alamatLabel }}</p>
                            
                            <hr class="my-2">

                            <div class="space-y-1 mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="small font-weight-semibold text-muted"><i class="fas fa-clock text-info mr-1"></i> Operasional:</span>
                                    @if($hasHours)
                                        <span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle mr-1"></i>{{ $bukaLabel }} - {{ $tutupLabel }}</span>
                                    @else
                                        <span class="badge badge-danger px-2 py-1"><i class="fas fa-exclamation-triangle mr-1"></i>Belum Diatur</span>
                                    @endif
                                </div>

                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <span class="small font-weight-semibold text-muted"><i class="fas fa-crown text-warning mr-1"></i> VIP Member:</span>
                                    @if($hasMembership)
                                        <span class="badge badge-warning text-dark px-2 py-1"><i class="fas fa-percentage mr-1"></i>Diskon {{ (float)($c->membership_discount_percent ?? 10) }}%</span>
                                    @else
                                        <span class="badge badge-light text-muted border px-2 py-1">Belum Ada</span>
                                    @endif
                                </div>

                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <span class="small font-weight-semibold text-muted"><i class="fas fa-user-md text-success mr-1"></i> Dokter:</span>
                                    <span class="badge badge-info px-2 py-1 font-weight-bold"><i class="fas fa-stethoscope mr-1"></i>{{ $docCount }} Dokter</span>
                                </div>
                            </div>

                            <div class="mt-auto pt-2 border-top">
                                <div class="row g-1">
                                    <div class="col-6">
                                        <a href="{{ route('pengelola.clinics.edit', $c->id) }}" class="btn btn-sm btn-outline-info btn-block font-weight-bold">
                                            <i class="fas fa-edit mr-1"></i> Edit Klinik
                                        </a>
                                    </div>
                                    <div class="col-6">
                                        <a href="{{ route('pengelola.doctors.index') }}" class="btn btn-sm btn-info btn-block font-weight-bold">
                                            <i class="fas fa-user-md mr-1"></i> Dokter
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-light text-center border py-4" style="border-radius: 15px;">
                        <i class="fas fa-clinic-medical fa-2x text-muted mb-2"></i>
                        <h6 class="font-weight-bold text-dark mb-1">Belum Ada Klinik Kesehatan</h6>
                        <small class="text-muted d-block mb-3">Tambahkan klinik kesehatan Anda untuk mulai melayani pasien secara online.</small>
                        <a href="{{ route('pengelola.clinics.create') }}" class="btn btn-info btn-sm font-weight-bold px-3">
                            <i class="fas fa-plus mr-1"></i> Tambah Klinik Baru
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <div class="row">
            <!-- Booking Hari Ini -->
            <div class="col-12 col-lg-8 mb-3 mb-lg-0">
                <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                    <div class="card-header border-transparent" style="background: white; border-radius: 20px 20px 0 0;">
                        <h3 class="card-title mb-0" style="font-weight: 700; color: #1b2b5a;">Booking Hari Ini</h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table m-0">
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <th>Pasien</th>
                                        <th class="d-none d-md-table-cell">Dokter</th>
                                        <th>Waktu</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($bookingsToday as $booking)
                                    <tr>
                                        <td data-label="Kode"><a href="{{ route('pengelola.bookings.show', $booking->id) }}">{{ $booking->kode_booking }}</a></td>
                                        <td data-label="Pasien">{{ $booking->nama_pasien }}</td>
                                        <td data-label="Dokter" class="d-none d-md-table-cell">{{ $booking->doctor->nama_lengkap ?? $booking->doctor->nama }}</td>
                                        <td data-label="Waktu">{{ $booking->jam }}</td>
                                        <td data-label="Status">
                                            @if($booking->status == 'pending')
                                                <span class="badge badge-warning">Pending</span>
                                            @elseif($booking->status == 'confirmed')
                                                <span class="badge badge-info">Confirmed</span>
                                            @elseif($booking->status == 'completed')
                                                <span class="badge badge-success">Completed</span>
                                            @elseif($booking->status == 'cancelled')
                                                <span class="badge badge-danger">Cancelled</span>
                                            @else
                                                <span class="badge badge-secondary">{{ $booking->status }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Tidak ada booking hari ini</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer clearfix">
                        <a href="{{ route('pengelola.bookings.index') }}" class="btn btn-sm btn-info float-none float-md-right">Lihat Semua Booking</a>
                    </div>
                </div>
            </div>

            <!-- Booking Pending -->
            <div class="col-12 col-lg-4">
                <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                    <div class="card-header" style="background: white; border-radius: 20px 20px 0 0;">
                        <h3 class="card-title mb-0" style="font-weight: 700; color: #1b2b5a;">Booking Pending</h3>
                    </div>
                    <div class="card-body p-0">
                        <ul class="products-list product-list-in-card pl-2 pr-2">
                            @forelse($bookingsPending as $booking)
                            <li class="item">
                                <div class="product-info">
                                    <a href="{{ route('pengelola.bookings.show', $booking->id) }}" class="product-title">
                                        {{ $booking->kode_booking }}
                                        <span class="badge badge-warning float-right">Pending</span>
                                    </a>
                                    <span class="product-description">
                                        {{ $booking->nama_pasien }} - {{ $booking->doctor->nama_lengkap ?? $booking->doctor->nama }}
                                        <br>
                                        <small>{{ $booking->tanggal->format('d M Y') }} - {{ $booking->jam }}</small>
                                    </span>
                                </div>
                            </li>
                            @empty
                            <li class="item">
                                <div class="product-info">
                                    <span class="product-description">Tidak ada booking pending</span>
                                </div>
                            </li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="card-footer text-center">
                        <a href="{{ route('pengelola.bookings.index', ['status' => 'pending']) }}" class="uppercase">Lihat Semua</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .page-title {
        font-weight: 700;
        color: #1b2b5a;
    }
    .content-wrapper {
        background: #f4f8ff;
    }
    /* Responsive improvements for dashboard */
    @media (max-width: 768px) {
        .small-box {
            margin-bottom: 1rem;
        }
        .small-box .inner {
            padding: 15px;
        }
        .small-box .inner h3 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }
        .small-box .inner p {
            font-size: 0.875rem;
        }
        .small-box .icon {
            font-size: 3rem;
            top: 15px;
            right: 15px;
        }
        .card-title {
            font-size: 1rem;
        }
        .table-responsive {
            font-size: 0.875rem;
        }
        .table th,
        .table td {
            padding: 0.5rem;
        }
        .card-footer .btn {
            width: 100%;
            margin-top: 0.5rem;
        }
    }
    
    @media (max-width: 576px) {
        .content-header h1 {
            font-size: 1.25rem;
        }
        .small-box .inner h3 {
            font-size: 1.25rem;
        }
        .small-box .inner p {
            font-size: 0.75rem;
        }
        .small-box .icon {
            font-size: 2.5rem;
        }
        .table {
            font-size: 0.75rem;
        }
        .badge {
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem;
        }
    }
    
    /* Better card spacing */
    .row {
        margin-left: -7.5px;
        margin-right: -7.5px;
    }
    .row > * {
        padding-left: 7.5px;
        padding-right: 7.5px;
    }
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const inputSearch = document.getElementById('inputSearchClinic');
    const selectHours = document.getElementById('selectFilterClinicHours');
    const selectMembership = document.getElementById('selectFilterClinicMembership');
    const selectDoctors = document.getElementById('selectFilterClinicDoctors');
    const cards = document.querySelectorAll('.clinic-card-item');

    function filterClinics() {
        const query = inputSearch.value.toLowerCase().trim();
        const hoursVal = selectHours.value;
        const membershipVal = selectMembership.value;
        const doctorsVal = selectDoctors.value;

        cards.forEach(card => {
            const nameData = card.getAttribute('data-name');
            const hoursData = card.getAttribute('data-hours');
            const membershipData = card.getAttribute('data-membership');
            const doctorsData = card.getAttribute('data-doctors');

            const matchSearch = query === '' || nameData.includes(query);
            const matchHours = hoursVal === 'all' || hoursData === hoursVal;
            const matchMembership = membershipVal === 'all' || membershipData === membershipVal;
            const matchDoctors = doctorsVal === 'all' || doctorsData === doctorsVal;

            if (matchSearch && matchHours && matchMembership && matchDoctors) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    if (inputSearch) inputSearch.addEventListener('keyup', filterClinics);
    if (selectHours) selectHours.addEventListener('change', filterClinics);
    if (selectMembership) selectMembership.addEventListener('change', filterClinics);
    if (selectDoctors) selectDoctors.addEventListener('change', filterClinics);
});
</script>
@endpush
@endsection

