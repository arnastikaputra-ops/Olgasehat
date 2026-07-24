@extends('BACKEND.Layout.admin')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 font-weight-bold text-dark">Selamat Datang di BackOffice OlgaSehat</h1>
                    <p class="text-muted">Sistem Manajemen Terpadu Lapangan & Layanan Kesehatan</p>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <!-- Row 1: Key Metrics -->
            <div class="row">
                <!-- User Registrations -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $userCount ?? 0 }}</h3>
                            <p>Pengguna Terdaftar</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <a href="{{ route('admin.users.list') }}" class="small-box-footer">Kelola User <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <!-- Total Active Venues -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $venueCount ?? 0 }}</h3>
                            <p>Venue Terverifikasi</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-running"></i>
                        </div>
                        <a href="{{ route('admin.venue.list') }}" class="small-box-footer">Lihat Venue <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <!-- Total Active Health Partners -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $healthCount ?? 0 }}</h3>
                            <p>Klinik & Layanan Kesehatan</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user-md"></i>
                        </div>
                        <a href="{{ route('health.clinics.index') }}" class="small-box-footer">Lihat Layanan <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <!-- Total Pending Approvals -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ $totalPendingCount ?? 0 }}</h3>
                            <p>Perlu Verifikasi (Pending)</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <a href="{{ route('mitra.datapemiliklapangan') }}" class="small-box-footer">Proses Verifikasi <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>

            <!-- Row 2: Finance & Transactions -->
            <div class="row mt-3">
                <div class="col-lg-6 col-12">
                    <div class="card card-outline card-primary shadow-sm">
                        <div class="card-header border-0">
                            <h3 class="card-title font-weight-bold">
                                <i class="fas fa-shopping-cart text-primary mr-2"></i> Ringkasan Transaksi Booking
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
                                <div>
                                    <h6 class="mb-0 text-muted">Total Transaksi Booking</h6>
                                    <h4 class="font-weight-bold text-dark mb-0">{{ $totalBookings ?? 0 }} Transaksi</h4>
                                </div>
                                <span class="badge badge-primary p-2"><i class="fas fa-receipt fa-2x"></i></span>
                            </div>
                            <p class="text-muted small">Transaksi gabungan dari reservasi lapangan venue dan pendaftaran layanan kesehatan pasien.</p>
                            <a href="{{ route('admin.keuangan') }}" class="btn btn-outline-primary btn-block font-weight-bold">
                                Lihat Rincian Transaksi
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-12">
                    <div class="card card-outline card-success shadow-sm">
                        <div class="card-header border-0">
                            <h3 class="card-title font-weight-bold">
                                <i class="fas fa-coins text-success mr-2"></i> Pendapatan Komisi OlgaSehat
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
                                <div>
                                    <h6 class="mb-0 text-muted">Total Bagi Hasil / Komisi Platform</h6>
                                    <h4 class="font-weight-bold text-success mb-0">Rp {{ number_format($totalKomisiPlatform ?? 0, 0, ',', '.') }}</h4>
                                </div>
                                <span class="badge badge-success p-2"><i class="fas fa-wallet fa-2x"></i></span>
                            </div>
                            <p class="text-muted small">Potongan komisi bersih platform OlgaSehat dari mitra venue dan fasilitas kesehatan.</p>
                            <a href="{{ route('admin.keuangan') }}" class="btn btn-outline-success btn-block font-weight-bold">
                                Buka Laporan Keuangan
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>
@endsection

