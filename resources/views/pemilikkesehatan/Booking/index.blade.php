@extends('pemilikkesehatan.Layout.pengelolakesehatan')

@section('content')
<div class="content-wrapper" style="background: #f4f8ff; min-height: 100vh;">
    <div class="content-header border-0 pb-0">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h1 class="page-title mb-1" style="font-weight: 700; color: #1b2b5a;">Daftar Booking</h1>
                    <p class="text-muted mb-0">Kelola dan pantau booking pasien</p>
                </div>
                <ol class="breadcrumb float-md-right mt-2 mt-md-0">
                    <li class="breadcrumb-item"><a href="{{ route('pengelola.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Booking</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="content pt-3">
        <div class="container-fluid">
            <!-- Filter -->
            <div class="card border-0 shadow-sm collapsed-card mb-3" style="border-radius: 20px;">
                <div class="card-header" style="background: white; border-radius: 20px 20px 0 0;">
                    <h3 class="card-title mb-0" style="font-weight: 700; color: #1b2b5a;">Filter</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('pengelola.bookings.index') }}">
                    <div class="row">
                        <div class="col-12 col-md-6 col-lg-3 mb-3">
                            <div class="form-group mb-0">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="">Semua Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    <option value="no_show" {{ request('status') == 'no_show' ? 'selected' : '' }}>No Show</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3 mb-3">
                            <div class="form-group mb-0">
                                <label>Klinik</label>
                                <select name="clinic_id" class="form-control">
                                    <option value="">Semua Klinik</option>
                                    @foreach($clinics as $clinic)
                                        <option value="{{ $clinic->id }}" {{ request('clinic_id') == $clinic->id ? 'selected' : '' }}>
                                            {{ $clinic->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3 mb-3">
                            <div class="form-group mb-0">
                                <label>Tanggal</label>
                                <input type="date" name="tanggal" class="form-control" value="{{ request('tanggal') }}">
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3 mb-3">
                            <div class="form-group mb-0">
                                <label>Search</label>
                                <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Kode, Nama, Telepon">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="fas fa-search"></i> Filter
                            </button>
                            <a href="{{ route('pengelola.bookings.index') }}" class="btn btn-default btn-sm">
                                <i class="fas fa-redo"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                    <div class="card-header" style="background: white; border-radius: 20px 20px 0 0;">
                        <h3 class="card-title mb-0" style="font-weight: 700; color: #1b2b5a;">Daftar Booking</h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover m-0">
                                <thead>
                                    <tr>
                                        <th>Kode Booking</th>
                                        <th>Pasien</th>
                                        <th class="d-none d-md-table-cell">Dokter</th>
                                        <th class="d-none d-lg-table-cell">Klinik</th>
                                        <th>Tanggal & Waktu</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($bookings as $booking)
                                    <tr>
                                        <td data-label="Kode Booking">
                                            <strong>{{ $booking->kode_booking }}</strong>
                                        </td>
                                        <td data-label="Pasien">
                                            {{ $booking->nama_pasien }}
                                            <br><small class="text-muted">{{ $booking->nomor_telepon_pasien ?? $booking->nomor_telepon ?? '-' }}</small>
                                            <br><small class="text-muted d-md-none">{{ $booking->doctor->nama_lengkap ?? $booking->doctor->nama ?? '-' }}</small>
                                            <br><small class="text-muted d-lg-none">{{ $booking->clinic->nama }}</small>
                                        </td>
                                        <td data-label="Dokter" class="d-none d-md-table-cell">
                                            {{ $booking->doctor->nama_lengkap ?? $booking->doctor->nama ?? '-' }}
                                        </td>
                                        <td data-label="Klinik" class="d-none d-lg-table-cell">{{ $booking->clinic->nama }}</td>
                                        <td data-label="Tanggal & Waktu">
                                            {{ $booking->tanggal->format('d M Y') }}
                                            <br><small class="text-muted">{{ $booking->jam }}</small>
                                        </td>
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
                                        <td data-label="Aksi">
                                            <a href="{{ route('pengelola.bookings.show', $booking->id) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> <span class="d-none d-md-inline">Detail</span>
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <i class="fas fa-calendar-check fa-2x text-muted mb-2"></i>
                                            <p class="text-muted mb-0">Tidak ada booking ditemukan</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if($bookings->hasPages())
                    <div class="card-footer">
                        {{ $bookings->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

