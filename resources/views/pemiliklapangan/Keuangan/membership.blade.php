@extends('pemiliklapangan.Layout.ownervenue')

@section('content')
<div class="content-wrapper p-4">
  <div class="container-fluid">
    <div class="mb-4">
      <h4 class="font-weight-bold mb-1">Keuangan & Member Terdaftar</h4>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent p-0 mb-0">
          <li class="breadcrumb-item"><a href="/pemiliklapangan/dashboard">Keuangan</a></li>
          <li class="breadcrumb-item active" aria-current="page">Membership</li>
        </ol>
      </nav>
    </div>

    <!-- Metric Cards -->
    <div class="row mb-4">
      <div class="col-md-4 mb-3">
        <div class="card border-0 shadow-sm border-left border-warning p-3">
          <p class="text-muted mb-1 small font-weight-semibold">Paket Membership Aktif</p>
          <h3 class="font-weight-bold mb-0 text-warning">{{ $membershipActivities->count() }} Paket</h3>
        </div>
      </div>
      <div class="col-md-4 mb-3">
        <div class="card border-0 shadow-sm border-left border-success p-3">
          <p class="text-muted mb-1 small font-weight-semibold">Total Pendapatan Membership</p>
          <h3 class="font-weight-bold mb-0 text-success">Rp {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}</h3>
        </div>
      </div>
      <div class="col-md-4 mb-3">
        <div class="card border-0 shadow-sm border-left border-primary p-3">
          <p class="text-muted mb-1 small font-weight-semibold">Total Member Terdaftar</p>
          <h3 class="font-weight-bold mb-0 text-primary">{{ $participants->count() }} Peserta</h3>
        </div>
      </div>
    </div>

    <!-- Table Section -->
    <div class="card border-0 shadow-sm">
      <div class="card-body">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
          <div>
            <h5 class="font-weight-bold mb-1">Daftar Member & Transaksi Membership</h5>
            <p class="text-muted small mb-0">Riwayat pendaftaran member di paket keanggotaan Anda</p>
          </div>
          <a href="/pemiliklapangan/membership" class="btn btn-warning font-weight-bold">
            <i class="fas fa-plus mr-1"></i> Buat Paket Membership
          </a>
        </div>

        <div class="table-responsive">
          <table class="table table-hover align-middle">
            <thead class="bg-light">
              <tr>
                <th>No</th>
                <th>Paket Membership</th>
                <th>Nama Member</th>
                <th>Harga Paket</th>
                <th>Status Pembayaran</th>
                <th>Tanggal Daftar</th>
              </tr>
            </thead>
            <tbody>
              @forelse($participants as $index => $p)
              <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                  <span class="font-weight-bold text-dark">{{ $p->activity->nama ?? 'Paket Membership' }}</span>
                </td>
                <td>
                  <div>
                    <span class="font-weight-semibold">{{ $p->nama_peserta }}</span>
                    <br>
                    <small class="text-muted">{{ $p->user->email ?? '-' }}</small>
                  </div>
                </td>
                <td>
                  <span class="font-weight-bold text-success">
                    Rp {{ number_format($p->activity->harga ?? 0, 0, ',', '.') }}
                  </span>
                </td>
                <td>
                  @if($p->status === 'approved')
                    <span class="badge badge-success px-3 py-2">Disetujui / Aktif</span>
                  @elseif($p->status === 'pending')
                    <span class="badge badge-warning text-dark px-3 py-2">Menunggu Verifikasi</span>
                  @else
                    <span class="badge badge-danger px-3 py-2">Ditolak</span>
                  @endif
                </td>
                <td>{{ $p->created_at->format('d M Y, H:i') }}</td>
              </tr>
              @empty
              <tr>
                <td colspan="6" class="text-center py-5 text-muted">
                  <i class="fas fa-users-slash fa-3x mb-3 text-gray-300"></i>
                  <p class="mb-0 font-weight-bold">Belum Ada Transaksi Member</p>
                  <small>Member yang mendaftar ke paket membership Anda akan muncul di sini.</small>
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>
</div>
@endsection
