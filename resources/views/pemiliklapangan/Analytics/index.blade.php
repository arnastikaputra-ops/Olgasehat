@extends('pemiliklapangan.layout.ownervenue')

@section('content')
<div class="content-wrapper owner-analytics">
    <!-- Header Halaman -->
    <div class="content-header border-0 pb-0 no-print">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h1 class="page-title mb-1">Analytics</h1>
                    <p class="text-muted mb-0">Lihat performa venue anda berdasarkan transaksi, revenue, dan pertumbuhan user baru.</p>
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
                            <a class="dropdown-item py-2" href="{{ route('pemilik.analytics.export_csv', request()->query()) }}">
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
        <h3 class="text-center text-muted mb-4">Laporan Hasil Analitik Performa Venue</h3>
        <hr class="mb-4">
        <div class="row mb-4">
            <div class="col-6">
                <strong>Filter Penerapan:</strong><br>
                Venue: {{ $venueFilter === 'all' ? 'Semua Venue' : $venues->firstWhere('id', $venueFilter)->namavenue ?? 'Venue' }}<br>
                Lapangan: {{ $lapanganFilter === 'all' ? 'Semua Lapangan' : $lapangans->firstWhere('id', $lapanganFilter)->nama ?? 'Lapangan' }}
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
                    <form action="{{ route('pemilik.analytics') }}" method="GET" id="filterForm">
                        <div class="form-row align-items-end">
                            <div class="form-group col-lg-3 col-md-6 mb-3">
                                <label for="venueSelect">Venue <span class="text-danger">*</span></label>
                                <select name="venue_id" id="venueSelect" class="form-control custom-select">
                                    <option value="all" {{ $venueFilter === 'all' ? 'selected' : '' }}>Semua Venue</option>
                                    @foreach($venues as $v)
                                        <option value="{{ $v->id }}" {{ $venueFilter == $v->id ? 'selected' : '' }}>{{ $v->namavenue }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-lg-3 col-md-6 mb-3">
                                <label for="lapanganSelect">Lapangan <span class="text-danger">*</span></label>
                                <select name="lapangan_id" id="lapanganSelect" class="form-control custom-select">
                                    <option value="all" {{ $lapanganFilter === 'all' ? 'selected' : '' }}>Semua Lapangan</option>
                                    @foreach($lapangans as $l)
                                        <!-- render lapangans that belong to current venue if filtered, else all -->
                                        @if($venueFilter === 'all' || $l->pendaftaran_id == $venueFilter)
                                            <option value="{{ $l->id }}" {{ $lapanganFilter == $l->id ? 'selected' : '' }}>{{ $l->nama }}</option>
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

            <!-- Card Summary Rangkuman Data Laporan (Selalu Tampil / Bagus untuk PDF) -->
            <div class="row mb-4">
                <div class="col-md-4 mb-3">
                    <div class="card border-0 shadow-sm metric-card-info" style="border-left: 5px solid #28c778; border-radius: 16px;">
                        <div class="card-body py-3">
                            <p class="text-muted small uppercase font-weight-bold mb-1">Total Pendapatan</p>
                            <h3 class="font-weight-bold text-success mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card border-0 shadow-sm metric-card-info" style="border-left: 5px solid #4f8bff; border-radius: 16px;">
                        <div class="card-body py-3">
                            <p class="text-muted small uppercase font-weight-bold mb-1">Jumlah Transaksi</p>
                            <h3 class="font-weight-bold text-primary mb-0">{{ number_format($totalTransactions, 0, ',', '.') }} Kali Booking</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card border-0 shadow-sm metric-card-info" style="border-left: 5px solid #a56bff; border-radius: 16px;">
                        <div class="card-body py-3">
                            <p class="text-muted small uppercase font-weight-bold mb-1">User Baru Terdaftar</p>
                            <h3 class="font-weight-bold mb-0" style="color: #a56bff;">{{ number_format($totalUsers, 0, ',', '.') }} Pelanggan Baru</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Diagram Revenue -->
            <div class="card border-0 shadow-sm chart-card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="mb-1 font-weight-bold text-primary-dark">Revenue</h5>
                            <p class="text-muted small mb-0">Statistik pendapatan harian berdasarkan pemesanan lapangan.</p>
                        </div>
                    </div>
                    <div class="chart-container" style="position: relative; height: 320px; width: 100%;">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Row Diagram Transaksi & User -->
            <div class="row">
                <div class="col-lg-6 mb-4">
                    <div class="card border-0 shadow-sm chart-card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h5 class="mb-1 font-weight-bold text-primary-dark">Transaksi</h5>
                                    <p class="text-muted small mb-0">Grafik frekuensi transaksi harian.</p>
                                </div>
                                <span class="legend-dot legend-blue"></span>
                            </div>
                            <div class="chart-container" style="position: relative; height: 260px; width: 100%;">
                                <canvas id="transactionChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mb-4">
                    <div class="card border-0 shadow-sm chart-card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h5 class="mb-1 font-weight-bold text-primary-dark">User</h5>
                                    <p class="text-muted small mb-0">Grafik registrasi pelanggan baru di platform.</p>
                                </div>
                                <span class="legend-dot legend-purple"></span>
                            </div>
                            <div class="chart-container" style="position: relative; height: 260px; width: 100%;">
                                <canvas id="userChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Kirim Email (no-print) -->
<div class="modal fade no-print" id="emailModal" tabindex="-1" role="dialog" aria-labelledby="emailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title font-weight-bold" id="emailModalLabel" style="color: #1b2b5a;">Kirim Laporan via Email</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="emailReportForm" onsubmit="sendEmailReport(event)">
                @csrf
                <div class="modal-body pt-3">
                    <input type="hidden" name="venue_id" value="{{ $venueFilter }}">
                    <input type="hidden" name="lapangan_id" value="{{ $lapanganFilter }}">
                    <input type="hidden" name="mulai_dari" value="{{ $mulaiDari }}">
                    <input type="hidden" name="sampai_dengan" value="{{ $sampaiDengan }}">
                    
                    <div class="form-group">
                        <label for="emailInput" class="font-weight-bold small text-muted">Alamat Email Penerima <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="emailInput" class="form-control" placeholder="nama@email.com" value="{{ Auth::user()->email }}" required style="border-radius: 12px; border-color: #dbe4ff; background: #f7f9ff;">
                    </div>
                    <div id="emailAlert" class="alert d-none" style="border-radius: 12px; font-weight: 500;"></div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light btn-pill" data-dismiss="modal">Batal</button>
                    <button type="submit" id="btnSubmitEmail" class="btn btn-primary btn-pill">
                        <span id="btnEmailText"><i class="fas fa-paper-plane mr-2"></i>Kirim</span>
                        <span id="btnEmailLoading" class="d-none"><i class="fas fa-spinner fa-spin mr-2"></i>Mengirim Laporan...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .owner-analytics {
        background: linear-gradient(180deg, #f6f9ff 0%, #ffffff 100%);
        min-height: 100vh;
    }
    .owner-analytics .content-wrapper {
        background: transparent;
    }
    .page-title {
        font-weight: 700;
        color: #1b2b5a;
    }
    .text-primary-dark {
        color: #1b2b5a;
    }
    .btn-pill {
        border-radius: 999px;
        font-weight: 600;
    }
    .filter-card {
        border-radius: 24px;
    }
    .filter-card .form-group label {
        font-weight: 600;
        color: #1b2b5a;
        font-size: 0.85rem;
    }
    .filter-card .custom-select,
    .filter-card .form-control {
        border-radius: 14px;
        border-color: #dbe4ff;
        background: #f7f9ff;
        color: #1b2b5a;
        font-weight: 500;
    }
    .chart-card {
        border-radius: 22px;
        border: none;
    }
    .legend-dot {
        width: 14px;
        height: 14px;
        border-radius: 50%;
        display: inline-block;
    }
    .legend-blue {
        background: #4f8bff;
    }
    .legend-purple {
        background: #a56bff;
    }

    /* Print Styles (Cetak PDF Bersih) */
    @media print {
        body, .owner-analytics, .content-wrapper {
            background: #ffffff !important;
            color: #000000 !important;
        }
        .no-print {
            display: none !important;
        }
        .print-header {
            display: block !important;
        }
        .main-header, .main-sidebar, .main-footer {
            display: none !important;
        }
        .content-wrapper {
            margin-left: 0 !important;
            padding-top: 0 !important;
        }
        .chart-card {
            box-shadow: none !important;
            border: 1px solid #ddd !important;
            page-break-inside: avoid;
            margin-bottom: 25px !important;
        }
        canvas {
            max-width: 100% !important;
            height: auto !important;
        }
    }
</style>

<!-- Integrasi Logika Script ChartJS & Dinamika Dropdown -->
<script>
    // Membawa mapping venue ke lapangan dari Laravel Controller
    const venueLapangans = @json($venueLapangans);

    document.addEventListener("DOMContentLoaded", function () {
        const venueSelect = document.getElementById('venueSelect');
        const lapanganSelect = document.getElementById('lapanganSelect');

        // Handler perubahan dropdown Venue
        if (venueSelect && lapanganSelect) {
            venueSelect.addEventListener('change', function () {
                const venueId = this.value;
                
                // Reset dropdown lapangan
                lapanganSelect.innerHTML = '<option value="all">Semua Lapangan</option>';

                if (venueId !== 'all' && venueLapangans[venueId]) {
                    venueLapangans[venueId].forEach(function (lapangan) {
                        const option = document.createElement('option');
                        option.value = lapangan.id;
                        option.textContent = lapangan.nama;
                        lapanganSelect.appendChild(option);
                    });
                }
            });
        }

        // Render Grafik Visualisasi Analitik Menggunakan ChartJS
        renderCharts();
    });

    function resetFilters() {
        document.getElementById('venueSelect').value = 'all';
        document.getElementById('lapanganSelect').innerHTML = '<option value="all">Semua Lapangan</option>';
        document.getElementById('startDateInput').value = "{{ \Carbon\Carbon::now()->startOfMonth()->toDateString() }}";
        document.getElementById('endDateInput').value = "{{ \Carbon\Carbon::now()->toDateString() }}";
        document.getElementById('filterForm').submit();
    }

    function triggerPrint() {
        window.print();
    }

    // Ajax Handler Kirim Email Laporan
    function sendEmailReport(event) {
        event.preventDefault();
        
        const form = document.getElementById('emailReportForm');
        const email = document.getElementById('emailInput').value;
        const btnSubmit = document.getElementById('btnSubmitEmail');
        const textNormal = document.getElementById('btnEmailText');
        const textLoading = document.getElementById('btnEmailLoading');
        const alertBox = document.getElementById('emailAlert');

        // Ubah button ke state loading
        btnSubmit.disabled = true;
        textNormal.classList.add('d-none');
        textLoading.classList.remove('d-none');

        // Sembunyikan alert sebelumnya
        alertBox.classList.add('d-none');
        alertBox.className = 'alert';

        const formData = {
            email: email,
            venue_id: form.querySelector('input[name="venue_id"]').value,
            lapangan_id: form.querySelector('input[name="lapangan_id"]').value,
            mulai_dari: form.querySelector('input[name="mulai_dari"]').value,
            sampai_dengan: form.querySelector('input[name="sampai_dengan"]').value,
        };

        fetch("{{ route('pemilik.analytics.send_email') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify(formData)
        })
        .then(response => response.json().then(data => ({ status: response.status, body: data })))
        .then(res => {
            btnSubmit.disabled = false;
            textNormal.classList.remove('d-none');
            textLoading.classList.add('d-none');

            alertBox.classList.remove('d-none');
            if (res.status === 200) {
                alertBox.classList.add('alert-success');
                alertBox.textContent = res.body.message;
                // Auto close modal setelah sukses
                setTimeout(() => {
                    $('#emailModal').modal('hide');
                    alertBox.classList.add('d-none');
                }, 3000);
            } else {
                alertBox.classList.add('alert-danger');
                alertBox.textContent = res.body.message || 'Terjadi kesalahan sistem.';
            }
        })
        .catch(err => {
            btnSubmit.disabled = false;
            textNormal.classList.remove('d-none');
            textLoading.classList.add('d-none');

            alertBox.classList.remove('d-none');
            alertBox.classList.add('alert-danger');
            alertBox.textContent = 'Gagal terhubung ke server. Periksa koneksi internet Anda.';
            console.error(err);
        });
    }

    // Inisialisasi ChartJS Diagrams
    function renderCharts() {
        const chartData = @json($chartData);
        
        // 1. Line Chart Revenue (Pendapatan)
        const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
        new Chart(ctxRevenue, {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: chartData.revenue,
                    borderColor: '#28c778',
                    backgroundColor: 'rgba(40, 199, 120, 0.08)',
                    borderWidth: 3,
                    pointBackgroundColor: '#28c778',
                    pointBorderColor: '#ffffff',
                    pointHoverRadius: 6,
                    lineTension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    display: false
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                            },
                            fontColor: '#8a96c2',
                            fontSize: 11
                        },
                        gridLines: {
                            color: '#eef2ff'
                        }
                    }],
                    xAxes: [{
                        ticks: {
                            fontColor: '#8a96c2',
                            fontSize: 10
                        },
                        gridLines: {
                            display: false
                        }
                    }]
                },
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem, data) {
                            var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                            return 'Pendapatan: Rp ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        }
                    }
                }
            }
        });

        // 2. Bar Chart Transaksi (Booking)
        const ctxTransaction = document.getElementById('transactionChart').getContext('2d');
        new Chart(ctxTransaction, {
            type: 'bar',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'Jumlah Transaksi',
                    data: chartData.transactions,
                    backgroundColor: 'rgba(79, 139, 255, 0.85)',
                    borderColor: '#4f8bff',
                    borderWidth: 1,
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    display: false
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            stepSize: 1,
                            fontColor: '#8a96c2',
                            fontSize: 11
                        },
                        gridLines: {
                            color: '#eef2ff'
                        }
                    }],
                    xAxes: [{
                        ticks: {
                            fontColor: '#8a96c2',
                            fontSize: 10
                        },
                        gridLines: {
                            display: false
                        }
                    }]
                }
            }
        });

        // 3. Line Chart User Baru
        const ctxUser = document.getElementById('userChart').getContext('2d');
        new Chart(ctxUser, {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'User Baru Terdaftar',
                    data: chartData.users,
                    borderColor: '#a56bff',
                    backgroundColor: 'rgba(165, 107, 255, 0.08)',
                    borderWidth: 3,
                    pointBackgroundColor: '#a56bff',
                    pointBorderColor: '#ffffff',
                    pointHoverRadius: 6,
                    lineTension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    display: false
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            stepSize: 1,
                            fontColor: '#8a96c2',
                            fontSize: 11
                        },
                        gridLines: {
                            color: '#eef2ff'
                        }
                    }],
                    xAxes: [{
                        ticks: {
                            fontColor: '#8a96c2',
                            fontSize: 10
                        },
                        gridLines: {
                            display: false
                        }
                    }]
                }
            }
        });
    }
</script>
@endsection
