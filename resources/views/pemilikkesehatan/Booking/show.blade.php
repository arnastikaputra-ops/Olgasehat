@extends('pemilikkesehatan.Layout.pengelolakesehatan')

@section('content')
<div class="content-wrapper" style="background: #f4f8ff; min-height: 100vh;">
    <div class="content-header border-0 pb-0">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h1 class="page-title mb-1" style="font-weight: 700; color: #1b2b5a;">Detail Booking</h1>
                    <p class="text-muted mb-0">Informasi lengkap booking pasien</p>
                </div>
                <ol class="breadcrumb float-md-right mt-2 mt-md-0">
                    <li class="breadcrumb-item"><a href="{{ route('pengelola.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('pengelola.bookings.index') }}">Booking</a></li>
                    <li class="breadcrumb-item active">Detail</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="content pt-3">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                        <div class="card-header" style="background: white; border-radius: 20px 20px 0 0;">
                            <h3 class="card-title mb-0" style="font-weight: 700; color: #1b2b5a;">Informasi Booking</h3>
                        </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Kode Booking:</strong>
                                <p class="mb-0">{{ $booking->kode_booking }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong>Status:</strong>
                                <p class="mb-0">
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
                                </p>
                            </div>
                        </div>

                        <hr>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong><i class="fas fa-hospital mr-2"></i>Klinik:</strong>
                                <p class="mb-0">{{ $booking->clinic->nama }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong><i class="fas fa-user-md mr-2"></i>Dokter:</strong>
                                <p class="mb-0">{{ $booking->doctor->nama_lengkap ?? $booking->doctor->nama }}</p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong><i class="fas fa-calendar mr-2"></i>Tanggal:</strong>
                                <p class="mb-0">{{ $booking->tanggal->format('d M Y') }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong><i class="fas fa-clock mr-2"></i>Waktu:</strong>
                                <p class="mb-0">{{ $booking->jam }}</p>
                            </div>
                        </div>

                        @if($booking->service)
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <strong><i class="fas fa-heartbeat mr-2"></i>Layanan:</strong>
                                <p class="mb-0">{{ $booking->service->nama }}</p>
                            </div>
                        </div>
                        @endif

                        <hr>

                        <h5 class="mb-3">Informasi Pasien</h5>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Nama Pasien:</strong>
                                <p class="mb-0">{{ $booking->nama_pasien }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong>Nomor Telepon:</strong>
                                <p class="mb-0">{{ $booking->nomor_telepon }}</p>
                            </div>
                        </div>

                        @if($booking->email)
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Email:</strong>
                                <p class="mb-0">{{ $booking->email }}</p>
                            </div>
                        </div>
                        @endif

                        @if($booking->tanggal_lahir || $booking->jenis_kelamin)
                        <div class="row mb-3">
                            @if($booking->tanggal_lahir)
                            <div class="col-md-6">
                                <strong>Tanggal Lahir:</strong>
                                <p class="mb-0">{{ $booking->tanggal_lahir->format('d M Y') }}</p>
                            </div>
                            @endif
                            @if($booking->jenis_kelamin)
                            <div class="col-md-6">
                                <strong>Jenis Kelamin:</strong>
                                <p class="mb-0">{{ $booking->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                            </div>
                            @endif
                        </div>
                        @endif

                        @if($booking->keluhan)
                        <div class="mb-3">
                            <strong>Keluhan:</strong>
                            <p class="mb-0">{{ $booking->keluhan }}</p>
                        </div>
                        @endif

                        @if($booking->riwayat_penyakit)
                        <div class="mb-3">
                            <strong>Riwayat Penyakit:</strong>
                            <p class="mb-0">{{ $booking->riwayat_penyakit }}</p>
                        </div>
                        @endif

                        @if($booking->alergi)
                        <div class="mb-3">
                            <strong>Alergi:</strong>
                            <p class="mb-0 text-danger">{{ $booking->alergi }}</p>
                        </div>
                        @endif

                        @if($booking->catatan_dokter)
                        <hr>
                        <div class="mb-3">
                            <strong>Catatan Dokter:</strong>
                            <div class="bg-light p-3 rounded">
                                <p class="mb-0">{{ $booking->catatan_dokter }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <!-- CARD UPDATE STATUS -->
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px;">
                    <div class="card-header" style="background: white; border-radius: 20px 20px 0 0;">
                        <h3 class="card-title mb-0" style="font-weight: 700; color: #1b2b5a;">Update Status Booking</h3>
                    </div>
                    <form action="{{ route('pengelola.bookings.update-status', $booking->id) }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label class="font-weight-bold">Status Booking <span class="text-danger">*</span></label>
                                <select name="status" class="form-control" required>
                                    <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Pending (Menunggu Konfirmasi)</option>
                                    <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Confirmed (Disetujui)</option>
                                    <option value="completed" {{ $booking->status == 'completed' ? 'selected' : '' }}>Completed (Selesai)</option>
                                    <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Cancelled (Dibatalkan)</option>
                                    <option value="no_show" {{ $booking->status == 'no_show' ? 'selected' : '' }}>No Show (Tidak Datang)</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">Catatan Dokter / Keterangan</label>
                                <textarea name="catatan_dokter" class="form-control" rows="3" placeholder="Tuliskan resep, diagnosa, atau catatan medis pasien...">{{ old('catatan_dokter', $booking->catatan_dokter) }}</textarea>
                            </div>

                            <hr>

                            <div class="mb-2">
                                <strong class="text-muted d-block text-xs uppercase tracking-wider">Total Pembayaran:</strong>
                                <h4 class="mb-0 font-weight-bold text-success">Rp {{ number_format($booking->total_harga ?? 0, 0, ',', '.') }}</h4>
                            </div>

                            <div class="mb-2">
                                <strong class="text-muted d-block text-xs uppercase tracking-wider">Metode Pembayaran:</strong>
                                <span class="badge badge-info px-2 py-1 font-weight-bold">{{ $booking->bank_code ?? 'BANK' }}</span>
                                <small class="text-muted">({{ ucfirst($booking->metode_pembayaran ?? 'virtualAccount') }})</small>
                            </div>

                            @if($booking->virtual_account)
                            <div class="mb-2">
                                <strong class="text-muted d-block text-xs uppercase tracking-wider">Nomor Virtual Account:</strong>
                                <code class="font-weight-bold text-primary p-1 bg-light rounded" style="font-size: 0.95rem;">{{ $booking->virtual_account }}</code>
                            </div>
                            @endif
                        </div>
                        <div class="card-footer" style="background: white; border-radius: 0 0 20px 20px;">
                            <button type="submit" class="btn btn-success btn-block py-2 font-weight-bold shadow-sm" style="background: #28a745; border-color: #28a745; border-radius: 10px;">
                                <i class="fas fa-save mr-1"></i> Update Status & Catatan
                            </button>
                        </div>
                <!-- CARD RINCIAN RUMUS BAGI HASIL KLINIK -->
                @php
                    $totalBruto = (float)($booking->harga ?? $booking->total_harga ?? 0);
                    $clinic = $booking->clinic;
                    $rate = (float)($clinic->komisi_nilai ?? 0);
                    $tipe = $clinic->komisi_tipe ?? 'none';
                    if ($tipe === 'percentage' && $rate > 0) {
                        $komisi = ($totalBruto * $rate) / 100;
                    } elseif ($tipe === 'fixed' && $rate > 0) {
                        $komisi = min($rate, $totalBruto);
                    } else {
                        $komisi = 0;
                    }
                    $pendapatanMitra = max(0, $totalBruto - $komisi);
                @endphp
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px;">
                    <div class="card-header bg-info text-white font-weight-bold" style="border-radius: 20px 20px 0 0;">
                        <i class="fas fa-calculator mr-2"></i> Rincian Rumus Pembagian Hasil Klinik
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-bordered mb-2">
                            <tr>
                                <td>Total Pembayaran Pasien (Bruto)</td>
                                <td class="text-right font-weight-bold">Rp {{ number_format($totalBruto, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td class="text-primary">
                                    (-) Komisi Platform OlgaSehat
                                    @if($tipe === 'percentage')
                                        <span class="badge badge-warning">({{ $rate }}%)</span>
                                    @elseif($tipe === 'fixed')
                                        <span class="badge badge-warning">(Fixed)</span>
                                    @else
                                        <span class="badge badge-success">(0% Utuh)</span>
                                    @endif
                                </td>
                                <td class="text-right text-primary font-weight-bold">- Rp {{ number_format($komisi, 0, ',', '.') }}</td>
                            </tr>
                            <tr class="bg-light">
                                <td class="text-success font-weight-bold">(=) Pendapatan Bersih Klinik (Ditransfer)</td>
                                <td class="text-right text-success font-weight-bold">Rp {{ number_format($pendapatanMitra, 0, ',', '.') }}</td>
                            </tr>
                        </table>
                        <small class="text-muted"><i class="fas fa-shield-alt text-success mr-1"></i>Kalkulasi otomatis transparan & akurat oleh sistem OlgaSehat.</small>
                    </div>
                </div>

                <!-- CARD BUKTI TRANSFER PEMBAYARAN PASIEN -->
                <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                    <div class="card-header d-flex justify-content-between align-items-center" style="background: white; border-radius: 20px 20px 0 0;">
                        <h3 class="card-title mb-0" style="font-weight: 700; color: #1b2b5a;">
                            <i class="fas fa-file-invoice-dollar text-primary mr-1"></i> Bukti Transfer Pasien
                        </h3>
                    </div>
                    <div class="card-body text-center p-3">
                        @if($booking->bukti_pembayaran)
                            @php
                                $buktiUrl = asset('bukti_pembayaran/' . $booking->bukti_pembayaran);
                            @endphp
                            <div class="position-relative overflow-hidden rounded-lg border mb-3 shadow-sm bg-light" style="max-height: 380px;">
                                <a href="{{ $buktiUrl }}" target="_blank" title="Klik untuk membuka ukuran penuh">
                                    <img src="{{ $buktiUrl }}" alt="Bukti Transfer Pasien" class="img-fluid rounded" style="max-height: 360px; width: 100%; object-fit: contain; background: #f8fafc;">
                                </a>
                            </div>
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ $buktiUrl }}" target="_blank" class="btn btn-outline-primary btn-sm font-weight-bold rounded-pill px-3 mr-1">
                                    <i class="fas fa-external-link-alt mr-1"></i> Buka Foto Full
                                </a>
                                <a href="{{ $buktiUrl }}" download class="btn btn-primary btn-sm font-weight-bold rounded-pill px-3 shadow-sm">
                                    <i class="fas fa-download mr-1"></i> Unduh Foto
                                </a>
                            </div>
                            <small class="text-muted d-block mt-2 font-italic">
                                <i class="fas fa-search-plus mr-1"></i> Klik gambar di atas untuk melihat dalam tab baru.
                            </small>
                        @else
                            <div class="py-5 text-center text-muted">
                                <div class="mb-3">
                                    <i class="fas fa-receipt fa-4x text-secondary opacity-50"></i>
                                </div>
                                <h6 class="font-weight-bold text-dark mb-1">Bukti Transfer Belum Ada</h6>
                                <p class="small mb-0">Pasien belum mengunggah foto resi / bukti pembayaran.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

