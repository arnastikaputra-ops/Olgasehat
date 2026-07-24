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
@endsection

