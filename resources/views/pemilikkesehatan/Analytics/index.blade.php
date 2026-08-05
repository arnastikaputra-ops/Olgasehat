@extends('pemilikkesehatan.Layout.pengelolakesehatan')

@section('content')
<div class="content-wrapper owner-analytics">
    <!-- Header Halaman -->
    <div class="content-header border-0 pb-0 no-print">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h1 class="page-title mb-1">Analytics Layanan Kesehatan</h1>
                    <p class="text-muted mb-0">Lihat performa fasilitas & klinik kesehatan berdasarkan transaksi, revenue, dan pertumbuhan pasien baru.</p>
                </div>
                <div class="mt-3 mt-md-0 d-flex flex-wrap align-items-center">
                    <!-- Dropdown Download Laporan -->
                    <div class="btn-group mr-2 mb-2">
                        <button type="button" class="btn btn-outline-primary btn-pill dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-download mr-2"></i>Download Laporan
                        </button>
                        <div class="dropdown-menu dropdown-menu-right shadow-sm border-0" style="border-radius: 12px; font-weight: 600;">
                            <a class="dropdown-item py-2" href="javascript:void(0)" onclick="triggerPrint()">
                                <i class="fas fa-file-pdf mr-2 text-danger"></i>Cetak PDF
                            </a>
                            <a class="dropdown-item py-2" href="{{ route('pengelola.analytics.export_csv', request()->query()) }}">
                                <i class="fas fa-file-excel mr-2 text-success"></i>Unduh CSV
                            </a>
                        </div>
                    </div>
                    
                    <button class="btn btn-primary btn-pill mb-2" data-toggle="modal" data-target="#emailModal">
                        <i class="fas fa-envelope-open-text mr-2"></i>Kirim via Email
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Hanya muncul saat halaman dicetak (PDF) -->
    <div class="print-header d-none">
        <h1 class="font-weight-bold text-center mb-1" style="color: #1b2b5a;">OLGA SEHAT</h1>
        <h3 class="text-center text-muted mb-4">Laporan Hasil Analitik Performa Fasilitas Kesehatan</h3>
        <hr class="mb-4">
        <div class="row mb-4">
            <div class="col-6">
                <strong>Filter Penerapan:</strong><br>
                Klinik: {{ $clinicFilter === 'all' ? 'Semua Klinik' : ($clinics->firstWhere('id', $clinicFilter)->nama ?? 'Klinik') }}<br>
                Dokter: {{ $doctorFilter === 'all' ? 'Semua Dokter' : ($doctors->firstWhere('id', $doctorFilter)->nama_lengkap ?? $doctors->firstWhere('id', $doctorFilter)->nama ?? 'Dokter') }}
            </div>
            <div class="col-6 text-right">
                <strong>Rentang Periode Laporan:</strong><br>
                {{ \Carbon\Carbon::parse($mulaiDari)->translatedFormat('d F Y') }} s.d. {{ \Carbon\Carbon::parse($sampaiDengan)->translatedFormat('d F Y') }}
            </div>
        </div>
    </div>

    <div class="content pt-3">
        <div class="container-fluid">
            <!-- Form Filter (no-print) -->
            <div class="card border-0 shadow-sm filter-card mb-4 no-print">
                <div class="card-body">
                    <form action="{{ route('pengelola.analytics') }}" method="GET" id="filterForm">
                        <div class="form-row align-items-end">
                            <div class="form-group col-lg-3 col-md-6 mb-3">
                                <label for="clinicSelect">Klinik <span class="text-danger">*</span></label>
                                <select name="clinic_id" id="clinicSelect" class="form-control custom-select">
                                    <option value="all" {{ $clinicFilter === 'all' ? 'selected' : '' }}>Semua Klinik</option>
                                    @foreach($clinics as $c)
                                        <option value="{{ $c->id }}" {{ $clinicFilter == $c->id ? 'selected' : '' }}>{{ $c->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-lg-3 col-md-6 mb-3">
                                <label for="doctorSelect">Dokter <span class="text-danger">*</span></label>
                                <select name="doctor_id" id="doctorSelect" class="form-control custom-select">
                                    <option value="all" {{ $doctorFilter === 'all' ? 'selected' : '' }}>Semua Dokter</option>
                                    @foreach($doctors as $d)
                                        @if($clinicFilter === 'all' || $d->clinic_id == $clinicFilter)
                                            <option value="{{ $d->id }}" {{ $doctorFilter == $d->id ? 'selected' : '' }}>{{ $d->nama_lengkap ?? $d->nama }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-lg-3 col-md-6 mb-3">
                                <label for="startDateInput">Mulai Dari <span class="text-danger">*</span></label>
                                <input type="date" name="mulai_dari" id="startDateInput" class="form-control" value="{{ $mulaiDari }}" required>
                            </div>
                            <div class="form-group col-lg-3 col-md-6 mb-3">
                                <label for="endDateInput">Sampai Dengan <span class="text-danger">*</span></label>
                                <input type="date" name="sampai_dengan" id="endDateInput" class="form-control" value="{{ $sampaiDengan }}" required>
                            </div>
                        </div>
                        <div class="form-row mt-2">
                            <div class="form-group col-12 d-flex align-items-center justify-content-end flex-wrap mb-0">
                                <button class="btn btn-light btn-pill mr-2" type="button" onclick="resetFilters()">Reset</button>
                                <button class="btn btn-primary btn-pill" type="submit">Terapkan Filter</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Ringkasan Statistik -->
            <div class="row">
                <div class="col-lg-3 col-sm-6 mb-4">
                    <div class="card border-0 shadow-sm stat-card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <span class="text-muted text-uppercase text-xs font-weight-bold tracking-wider">Total Pendapatan</span>
                                <div class="stat-icon bg-success-light text-success">
                                    <i class="fas fa-wallet"></i>
                                </div>
                            </div>
                            <h3 class="font-weight-bold mb-1" style="color: #1b2b5a;">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                            <p class="text-muted text-xs mb-0">Periode terfilter</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-sm-6 mb-4">
                    <div class="card border-0 shadow-sm stat-card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <span class="text-muted text-uppercase text-xs font-weight-bold tracking-wider">Total Transaksi</span>
                                <div class="stat-icon bg-primary-light text-primary">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                            </div>
                            <h3 class="font-weight-bold mb-1" style="color: #1b2b5a;">{{ number_format($totalTransactions) }} <small class="text-sm font-weight-normal text-muted">Janji</small></h3>
                            <p class="text-muted text-xs mb-0">Janji pemeriksaan terdaftar</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-sm-6 mb-4">
                    <div class="card border-0 shadow-sm stat-card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <span class="text-muted text-uppercase text-xs font-weight-bold tracking-wider">Pengguna Baru</span>
                                <div class="stat-icon bg-info-light text-info">
                                    <i class="fas fa-user-plus"></i>
                                </div>
                            </div>
                            <h3 class="font-weight-bold mb-1" style="color: #1b2b5a;">{{ number_format($totalUsers) }} <small class="text-sm font-weight-normal text-muted">Pasien</small></h3>
                            <p class="text-muted text-xs mb-0">Registrasi pasien baru</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-sm-6 mb-4">
                    <div class="card border-0 shadow-sm stat-card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <span class="text-muted text-uppercase text-xs font-weight-bold tracking-wider">Rata-rata / Transaksi</span>
                                <div class="stat-icon bg-warning-light text-warning">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                            </div>
                            @php
                                $avgRev = $totalTransactions > 0 ? round($totalRevenue / $totalTransactions) : 0;
                            @endphp
                            <h3 class="font-weight-bold mb-1" style="color: #1b2b5a;">Rp {{ number_format($avgRev, 0, ',', '.') }}</h3>
                            <p class="text-muted text-xs mb-0">Rata-rata pendapatan per janji</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chart Revenue & Transaksi Harian -->
            <div class="card border-0 shadow-sm chart-card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h5 class="mb-1 font-weight-bold" style="color: #1b2b5a;">Tren Revenue & Transaksi Harian</h5>
                            <p class="text-muted small mb-0">Perkembangan nilai pendapatan dan jumlah janji pemeriksaan harian.</p>
                        </div>
                    </div>
                    <div style="position: relative; height: 350px;">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Chart Pertumbuhan User Baru -->
            <div class="card border-0 shadow-sm chart-card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h5 class="mb-1 font-weight-bold" style="color: #1b2b5a;">Pertumbuhan Pasien Baru</h5>
                            <p class="text-muted small mb-0">Grafik akumulasi pengguna/pasien terdaftar baru.</p>
                        </div>
                    </div>
                    <div style="position: relative; height: 260px;">
                        <canvas id="usersChart"></canvas>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Modal Kirim Email -->
<div class="modal fade" id="emailModal" tabindex="-1" role="dialog" aria-labelledby="emailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title font-weight-bold" id="emailModalLabel" style="color: #1b2b5a;">Kirim Ringkasan Laporan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body py-4">
                <p class="text-muted small mb-3">Masukkan alamat email tujuan untuk menerima ringkasan laporan analitik dalam rentang periode ini.</p>
                <form id="emailForm">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="emailInput" class="font-weight-bold text-xs uppercase text-muted">Email Tujuan</label>
                        <input type="email" class="form-control font-weight-bold" id="emailInput" name="email" value="{{ Auth::user()->email }}" placeholder="contoh@domain.com" required>
                    </div>
                    <input type="hidden" name="clinic_id" value="{{ $clinicFilter }}">
                    <input type="hidden" name="doctor_id" value="{{ $doctorFilter }}">
                    <input type="hidden" name="mulai_dari" value="{{ $mulaiDari }}">
                    <input type="hidden" name="sampai_dengan" value="{{ $sampaiDengan }}">
                    <button type="submit" class="btn btn-primary btn-block btn-pill py-2 font-weight-bold mt-4" id="sendEmailBtn">
                        <i class="fas fa-paper-plane mr-2"></i>Kirim Laporan Sekarang
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    .owner-analytics {
        background-color: #f4f8ff !important;
        min-height: 100vh;
    }
    .owner-analytics .page-title {
        font-weight: 800;
        color: #1b2b5a;
    }
    .owner-analytics .btn-pill {
        border-radius: 50px;
        padding: 0.5rem 1.25rem;
        font-weight: 600;
        font-size: 0.875rem;
    }
    .owner-analytics .filter-card, 
    .owner-analytics .stat-card, 
    .owner-analytics .chart-card {
        border-radius: 20px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .owner-analytics .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.08) !important;
    }
    .owner-analytics .stat-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }
    .bg-success-light { background: rgba(40, 167, 69, 0.12); }
    .bg-primary-light { background: rgba(0, 123, 255, 0.12); }
    .bg-info-light { background: rgba(23, 162, 184, 0.12); }
    .bg-warning-light { background: rgba(255, 193, 7, 0.15); }
    
    @media print {
        .no-print, .main-sidebar, .main-header, .main-footer {
            display: none !important;
        }
        .content-wrapper {
            margin-left: 0 !important;
            background: white !important;
        }
        .print-header {
            display: block !important;
        }
        .stat-card, .chart-card {
            box-shadow: none !important;
            border: 1px solid #ddd !important;
            break-inside: avoid;
        }
    }
</style>

<script>
    // Data untuk Chart dari Controller
    const chartLabels = {!! json_encode($chartData['labels']) !!};
    const chartRevenue = {!! json_encode($chartData['revenue']) !!};
    const chartTransactions = {!! json_encode($chartData['transactions']) !!};
    const chartUsers = {!! json_encode($chartData['users']) !!};

    // Mapping dokter per klinik untuk filter dinamis
    const clinicDoctorsMap = {!! json_encode($clinicDoctors) !!};

    document.getElementById('clinicSelect').addEventListener('change', function() {
        const clinicId = this.value;
        const doctorSelect = document.getElementById('doctorSelect');
        doctorSelect.innerHTML = '<option value="all">Semua Dokter</option>';

        if (clinicId === 'all') {
            @foreach($doctors as $d)
                doctorSelect.innerHTML += `<option value="{{ $d->id }}">{{ addslashes($d->nama_lengkap ?? $d->nama) }}</option>`;
            @endforeach
        } else if (clinicDoctorsMap[clinicId]) {
            clinicDoctorsMap[clinicId].forEach(function(doc) {
                const docName = doc.nama_lengkap || doc.nama;
                doctorSelect.innerHTML += `<option value="${doc.id}">${docName}</option>`;
            });
        }
    });

    // Render Revenue & Transaksi Chart
    const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(ctxRevenue, {
        type: 'line',
        data: {
            labels: chartLabels,
            datasets: [
                {
                    label: 'Pendapatan (Rp)',
                    data: chartRevenue,
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.08)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.35,
                    yAxisID: 'yRevenue'
                },
                {
                    label: 'Jumlah Janji / Booking',
                    data: chartTransactions,
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.08)',
                    borderWidth: 2,
                    borderDash: [5, 5],
                    fill: false,
                    tension: 0.35,
                    yAxisID: 'yTransactions'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            scales: {
                x: {
                    grid: { display: false }
                },
                yRevenue: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                },
                yTransactions: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    grid: { drawOnChartArea: false },
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });

    // Render User Growth Chart
    const ctxUsers = document.getElementById('usersChart').getContext('2d');
    const usersChart = new Chart(ctxUsers, {
        type: 'bar',
        data: {
            labels: chartLabels,
            datasets: [{
                label: 'Pasien Terdaftar Baru',
                data: chartUsers,
                backgroundColor: 'rgba(23, 162, 184, 0.75)',
                borderColor: '#17a2b8',
                borderWidth: 1,
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: { grid: { display: false } },
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0 }
                }
            }
        }
    });

    // Handle Reset Filter
    function resetFilters() {
        const today = new Date().toISOString().split('T')[0];
        const firstDayOfMonth = new Date(new Date().getFullYear(), new Date().getMonth(), 1).toISOString().split('T')[0];
        
        document.getElementById('clinicSelect').value = 'all';
        document.getElementById('doctorSelect').value = 'all';
        document.getElementById('startDateInput').value = firstDayOfMonth;
        document.getElementById('endDateInput').value = today;
        document.getElementById('filterForm').submit();
    }

    // Trigger Print PDF
    function triggerPrint() {
        window.print();
    }

    // Submit Form Send Email via AJAX
    document.getElementById('emailForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = document.getElementById('sendEmailBtn');
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengirim...';

        const formData = new FormData(this);

        fetch("{{ route('pengelola.analytics.send_email') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = originalText;
            $('#emailModal').modal('hide');

            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil Sent',
                    text: data.message,
                    confirmButtonColor: '#007bff'
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Mengirim',
                    text: data.message,
                    confirmButtonColor: '#007bff'
                });
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = originalText;
            $('#emailModal').modal('hide');
            Swal.fire({
                icon: 'error',
                title: 'Error Server',
                text: 'Terjadi kesalahan saat menghubungi server.',
                confirmButtonColor: '#007bff'
            });
        });
    });
</script>
@endpush
@endsection
