@extends('pemiliklapangan.layout.ownervenue')

@section('content')
<div class="content-wrapper dashboard-onboarding">
    <div class="content-header border-0 pb-0">
        <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-between flex-wrap">
                <div>
                    <p class="breadcrumb-dashboard mb-1 text-muted">Onboarding • Selesai</p>
                    <h1 class="page-title mb-0">Onboarding Selesai</h1>
                    <p class="text-muted mb-0">Verifikasi & terbitkan venue Anda.</p>
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
                                <li class="timeline-item">
                                    <span class="dot">2</span>
                                    <div class="timeline-content">
                                        <h6>Detail & Jam Operasional</h6>
                                        <p class="text-muted mb-0 small">Tambahkan fasilitas, galeri, deskripsi, serta atur jam operasional & slot pemesanan.</p>
                                    </div>
                                </li>
                                <li class="timeline-item timeline-active">
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
                    <div class="card border-0 shadow-sm info-card">
                        <div class="card-body p-4 p-md-5 text-center">
                            <div class="mb-4">
                                <div class="icon-wrapper-success mx-auto mb-3">
                                    <i class="fas fa-check text-white"></i>
                                </div>
                            </div>
                            <h2 class="font-weight-bold text-gray-900 mb-3" style="font-size: 1.5rem;">Selamat! Onboarding Selesai</h2>
                            <p class="text-gray-600 mb-3 mx-auto" style="max-width: 500px; font-size: 0.95rem;">
                                Terima kasih telah memberikan kepercayaan kepada kami untuk membantu bisnis Anda dengan baik.
                            </p>
                            <p class="text-gray-600 mb-4 mx-auto" style="max-width: 500px; font-size: 0.95rem;">
                                Proses pengisian data sudah selesai. Tim kami akan melakukan verifikasi dan venue Anda akan segera tayang.
                            </p>
                            <div class="d-flex flex-column flex-sm-row gap-2 gap-md-3 justify-content-center">
                                <a href="/pemiliklapangan/dashboard" class="btn btn-primary px-4 px-md-5 py-2">
                                    <i class="fas fa-home mr-2"></i>Kembali ke Dashboard
                                </a>
                                <a href="{{ route('fasilitas') }}" class="btn btn-outline-primary px-4 px-md-5 py-2">
                                    <i class="fas fa-cog mr-2"></i>Kelola Venue
                                </a>
                            </div>
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
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: #fff;
        box-shadow: 0 8px 18px rgba(16, 185, 129, 0.35);
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
    .icon-wrapper-success {
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 8px 20px rgba(16, 185, 129, 0.25);
    }
    .icon-wrapper-success i {
        font-size: 2.5rem;
    }
    @media (max-width: 991.98px) {
        .timeline::before {
            left: 16px;
        }
        .timeline-item .dot {
            width: 32px;
            height: 32px;
        }
        .icon-wrapper-success {
            width: 80px;
            height: 80px;
        }
        .icon-wrapper-success i {
            font-size: 2rem;
        }
        .content-header {
            padding-top: 1rem !important;
        }
        .content {
            padding-top: 0.5rem !important;
        }
    }
    @media (max-width: 575.98px) {
        .page-title {
            font-size: 1.5rem !important;
        }
        .card-body {
            padding: 1.5rem !important;
        }
        .btn {
            font-size: 0.9rem;
            padding: 0.5rem 1rem !important;
        }
    }
</style>
@endsection
