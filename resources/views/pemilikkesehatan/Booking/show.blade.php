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
                <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                    <div class="card-header" style="background: white; border-radius: 20px 20px 0 0;">
                        <h3 class="card-title mb-0" style="font-weight: 700; color: #1b2b5a;">Update Status</h3>
                    </div>
                    <form action="{{ route('pengelola.bookings.update-status', $booking->id) }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label>Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-control" required>
                                    <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                    <option value="completed" {{ $booking->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    <option value="no_show" {{ $booking->status == 'no_show' ? 'selected' : '' }}>No Show</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Catatan Dokter</label>
                                <textarea name="catatan_dokter" class="form-control" rows="4" placeholder="Catatan dari dokter...">{{ old('catatan_dokter', $booking->catatan_dokter) }}</textarea>
                            </div>

                            @if($booking->total_harga)
                            <div class="form-group">
                                <strong>Total Harga:</strong>
                                <p class="mb-0">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</p>
                            </div>
                            @endif

                            @if($booking->metode_pembayaran)
                            <div class="form-group">
                                <strong>Metode Pembayaran:</strong>
                                <p class="mb-0">{{ ucfirst($booking->metode_pembayaran) }}</p>
                            </div>
                            @endif
                        </div>
                        <div class="card-footer" style="background: white; border-radius: 0 0 20px 20px;">
                            <button type="submit" class="btn btn-primary btn-block" style="background: #28a745; border-color: #28a745; border-radius: 10px;">
                                <i class="fas fa-save"></i> Update Status
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

