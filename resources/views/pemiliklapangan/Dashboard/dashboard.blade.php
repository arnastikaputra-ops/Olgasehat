@extends('pemiliklapangan.layout.ownervenue')

@section('content')
<div class="content-wrapper dashboard-onboarding">
    <div class="content-header border-0 pb-0">
        <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-between flex-wrap">
                <div>
                    <p class="breadcrumb-dashboard mb-1 text-muted">Kelola Fasilitas • Dashboard Pemilik</p>
                    <h1 class="page-title mb-0">Selamat Datang, {{ Auth::user()->name }}</h1>
                    <p class="text-muted mb-0">Kami siapkan pengalaman onboarding yang mudah dan cepat agar venue anda segera tayang.</p>
                </div>
                <div class="welcome-badge mt-3 mt-md-0">
                    <i class="fas fa-bolt"></i> Quick Start
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
                                <li class="timeline-item timeline-active">
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

                <!-- Welcome Hero -->
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm hero-card mb-4">
                        <div class="card-body d-flex flex-column flex-lg-row align-items-center">
                            <div class="hero-content pr-lg-4">
                                <div class="badge badge-soft-light mb-3">Tersisa 2 langkah lagi</div>
                                <h2 class="font-weight-bold mb-3 text-white">Mari tampilkan venue terbaik anda kepada ribuan pengguna OLGA Sehat.</h2>
                                <p class="text-light mb-4">Dengan melengkapi beberapa informasi penting, pelanggan dapat menemukan fasilitas anda dan melakukan pemesanan secara online.</p>
                                <a href="{{ route('informasi') }}" class="btn btn-light btn-lg btn-continue">
                                    Mulai Lengkapi Data
                                    <span class="ml-2"><i class="fas fa-arrow-right"></i></span>
                                </a>
                                <div class="hero-footnote text-light-50 mt-3">
                                    <i class="fas fa-shield-alt mr-2"></i>Data anda terlindungi dan hanya digunakan untuk keperluan verifikasi.
                                </div>
                            </div>
                            <div class="hero-illustration mt-4 mt-lg-0">
                                <img src="{{ asset('assets/ilus.png') }}" alt="Welcome Illustration">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="card border-0 shadow-sm h-100 info-card">
                                <div class="card-body">
                                    <div class="icon-wrapper bg-soft-blue mb-3">
                                        <i class="fas fa-lightbulb"></i>
                                    </div>
                                    <h6 class="font-weight-bold">Tips Cepat</h6>
                                    <p class="text-muted small mb-3">Gunakan foto berkualitas tinggi dan highlight fasilitas unggulan untuk menarik perhatian calon pelanggan.</p>
                                    <a href="#" class="text-primary small font-weight-bold">
                                        Lihat panduan <i class="fas fa-arrow-right ml-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="card border-0 shadow-sm h-100 info-card">
                                <div class="card-body">
                                    <div class="icon-wrapper bg-soft-green mb-3">
                                        <i class="fas fa-headset"></i>
                                    </div>
                                    <h6 class="font-weight-bold">Bantuan 24/7</h6>
                                    <p class="text-muted small mb-3">Tim support kami siap membantu kapan saja melalui WhatsApp atau live chat.</p>
                                    <a href="#" class="text-success small font-weight-bold">
                                        Hubungi tim support <i class="fas fa-arrow-right ml-1"></i>
                                    </a>
                                </div>
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
    .welcome-badge {
        background: rgba(0, 150, 255, 0.15);
        color: #0075ff;
        border-radius: 999px;
        padding: 0.4rem 1rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
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
    .hero-card {
        background: linear-gradient(135deg, #0096ff 0%, #00c6ff 100%);
        border-radius: 22px;
        overflow: hidden;
    }
    .hero-content {
        flex: 1 1 60%;
    }
    .hero-content .badge {
        border-radius: 999px;
        padding: 0.45rem 1rem;
        font-weight: 600;
    }
    .badge-soft-light {
        background: rgba(255,255,255,0.2);
        color: #f2f7ff;
    }
    .btn-continue {
        font-weight: 700;
        padding: 0.75rem 1.8rem;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        box-shadow: 0 12px 25px rgba(0, 0, 0, 0.18);
    }
    .hero-footnote {
        font-size: 0.85rem;
        opacity: 0.85;
    }
    .text-light-50 {
        color: rgba(255, 255, 255, 0.7);
    }
    .hero-illustration img {
        max-width: 280px;
        width: 100%;
    }
    .info-card {
        border-radius: 18px;
    }
    .icon-wrapper {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
    }
    .bg-soft-blue {
        background: rgba(0, 149, 255, 0.12);
        color: #0075ff;
    }
    .bg-soft-green {
        background: rgba(46, 204, 113, 0.15);
        color: #1f9451;
    }
    @media (max-width: 991.98px) {
        .timeline::before {
            left: 16px;
        }
        .timeline-item .dot {
            width: 32px;
            height: 32px;
        }
        .hero-card {
            text-align: center;
        }
        .hero-content {
            padding-right: 0;
        }
        .hero-content .btn-continue {
            justify-content: center;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('success_membership')) {
        const toast = document.createElement('div');
        toast.className = 'custom-toast';
        toast.innerHTML = '<i class="fas fa-check-circle mr-2"></i> Paket Membership berhasil disimpan & menunggu verifikasi!';
        document.body.appendChild(toast);
        
        const style = document.createElement('style');
        style.innerHTML = `
            .custom-toast {
                position: fixed;
                bottom: 24px;
                right: 24px;
                background: #10b981;
                color: #fff;
                padding: 12px 24px;
                border-radius: 8px;
                box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -4px rgba(0,0,0,0.1);
                z-index: 9999;
                font-weight: 600;
                font-size: 14px;
                display: flex;
                align-items: center;
                opacity: 0;
                transform: translateY(20px);
                transition: opacity 0.3s ease, transform 0.3s ease;
            }
            .custom-toast.show {
                opacity: 1;
                transform: translateY(0);
            }
        `;
        document.head.appendChild(style);
        
        setTimeout(() => {
            toast.classList.add('show');
        }, 100);
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
        
        window.history.replaceState({}, document.title, window.location.pathname);
    }
});
</script>
@endsection
