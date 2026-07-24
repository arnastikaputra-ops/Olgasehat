@extends('pemiliklapangan.layout.ownervenue')

@section('content')
<div class="content-wrapper p-4">
  <div class="container-fluid">
    <div class="mb-4">
      <h4 class="font-weight-bold mb-1">Riwayat Transaksi Fasilitas</h4>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent p-0 mb-0">
          <li class="breadcrumb-item"><a href="#">Keuangan</a></li>
          <li class="breadcrumb-item">Riwayat Transaksi</li>
          <li class="breadcrumb-item active" aria-current="page">Fasilitas</li>
        </ol>
      </nav>
    </div>

    <div class="card border-0 shadow-sm mb-4">
      <div class="card-body py-4">
        <div class="alert alert-info border-dashed border-primary text-primary bg-primary-50" role="alert">
          Data ringkasan diambil berdasarkan tanggal transaksi.
        </div>
        <div class="row">
          <div class="col-md-3 mb-3">
            <div class="summary-card bg-blue-50 border-left border-primary shadow-xs h-100 p-3 rounded-lg">
              <p class="text-muted mb-1 small font-weight-semibold">Transaksi Bulan Ini</p>
              <h3 class="font-weight-bold mb-0">{{ $totalTransaksiBulanIni ?? 0 }}</h3>
            </div>
          </div>
          <div class="col-md-3 mb-3">
            <div class="summary-card bg-green-50 border-left border-success shadow-xs h-100 p-3 rounded-lg">
              <p class="text-muted mb-1 small font-weight-semibold">Pendapatan Bersih Bulan Ini</p>
              <h3 class="font-weight-bold mb-0">Rp {{ number_format($totalPendapatanBulanIni ?? 0, 0, ',', '.') }}</h3>
            </div>
          </div>
          <div class="col-md-3 mb-3">
            <div class="summary-card bg-warning-50 border-left border-warning shadow-xs h-100 p-3 rounded-lg">
              <p class="text-muted mb-1 small font-weight-semibold">Komisi Platform (OlgaSehat)</p>
              <h3 class="font-weight-bold mb-0">Rp {{ number_format($totalKomisiBulanIni ?? 0, 0, ',', '.') }}</h3>
            </div>
          </div>
          <div class="col-md-3 mb-3">
            <div class="summary-card bg-purple-50 border-left border-primary shadow-xs h-100 p-3 rounded-lg">
              <p class="text-muted mb-1 small font-weight-semibold">Pendapatan Hari Ini</p>
              <h3 class="font-weight-bold mb-0">Rp {{ number_format($totalPendapatanHariIni ?? 0, 0, ',', '.') }}</h3>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="card border-0 shadow-sm">
      <div class="card-body">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
          <div>
            <h5 class="font-weight-bold mb-1">Riwayat Transaksi Booking Lapangan</h5>
            <p class="text-muted small mb-0">List rincian transaksi booking dan bagi hasil platform</p>
          </div>
        </div>

        <div class="table-responsive">
          <table class="table table-hover align-middle">
            <thead class="thead-light">
              <tr>
                <th>No</th>
                <th>Tanggal Transaksi</th>
                <th>Kode Booking</th>
                <th>Pemesan</th>
                <th>Kontak</th>
                <th>Metode Pembayaran</th>
                <th>Total Transaksi</th>
                <th>Komisi Platform</th>
                <th>Pendapatan Bersih</th>
                <th>Bukti Transfer</th>
                <th>Status</th>
                <th>Aksi Persetujuan</th>
              </tr>
            </thead>
            <tbody>
              @forelse($bookings ?? [] as $index => $b)
              <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $b->created_at->format('d/m/Y H:i') }}</td>
                <td><strong class="text-primary">{{ $b->kode_booking }}</strong></td>
                <td>{{ $b->nama_pemesan }}</td>
                <td>{{ $b->nomor_telepon }}</td>
                <td>
                  <span class="badge badge-info">{{ $b->bank_code ?? 'VA' }}</span>
                  <br><small class="text-muted font-mono">{{ $b->virtual_account }}</small>
                </td>
                <td><strong>Rp {{ number_format($b->total_harga, 0, ',', '.') }}</strong></td>
                <td class="text-warning font-weight-bold">
                  Rp {{ number_format($b->komisi_platform, 0, ',', '.') }}
                  @if($b->komisi_tipe == 'percentage')
                    <small>({{ (float)$b->komisi_nilai }}%)</small>
                  @elseif($b->komisi_tipe == 'fixed')
                    <small>(Fixed)</small>
                  @else
                    <small>(0% - 100% Mitra)</small>
                  @endif
                </td>
                <td class="text-success font-weight-bold">Rp {{ number_format($b->pendapatan_mitra, 0, ',', '.') }}</td>
                <td>
                  @if($b->bukti_pembayaran)
                    <a href="{{ asset('bukti_pembayaran/' . $b->bukti_pembayaran) }}" target="_blank" class="btn btn-sm btn-outline-info">
                      <i class="fas fa-file-image mr-1"></i> Lihat Foto
                    </a>
                  @else
                    <span class="text-muted small">Tanpa Berkas</span>
                  @endif
                </td>
                <td>
                  @if($b->status_pembayaran == 'paid')
                    <span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle mr-1"></i>Lunas (Disetujui)</span>
                  @elseif($b->status_pembayaran == 'pending_acc' || $b->status_pembayaran == 'pending')
                    <span class="badge badge-warning px-2 py-1"><i class="fas fa-clock mr-1"></i>Menunggu ACC</span>
                  @elseif($b->status_pembayaran == 'rejected')
                    <span class="badge badge-danger px-2 py-1"><i class="fas fa-times-circle mr-1"></i>Ditolak</span>
                  @else
                    <span class="badge badge-secondary px-2 py-1">{{ strtoupper($b->status_pembayaran) }}</span>
                  @endif
                </td>
                <td>
                  @if($b->status_pembayaran == 'pending_acc' || $b->status_pembayaran == 'pending')
                    <div class="d-flex gap-1">
                      <form action="{{ route('booking.approve', $b->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-success font-weight-bold text-white shadow-xs" onclick="return confirm('Setujui transaksi booking ini (ACC) dan konfirmasi pembayaran?')">
                          <i class="fas fa-check-circle mr-1"></i> ACC (Setujui)
                        </button>
                      </form>
                      <form action="{{ route('booking.reject', $b->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-danger font-weight-bold shadow-xs ml-1" onclick="return confirm('Tolak transaksi booking ini?')">
                          <i class="fas fa-times-circle mr-1"></i> Tolak
                        </button>
                      </form>
                    </div>
                  @else
                    <span class="text-muted small"><i class="fas fa-check-double text-success mr-1"></i>Selesai</span>
                  @endif
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="12" class="text-center text-muted py-5">
                  <i class="fas fa-receipt mb-2 d-block" style="font-size: 24px;"></i>
                  Belum ada data transaksi booking fasilitas.
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

@push('styles')
<style>
  .summary-card {
    border-radius: 16px;
  }
  .border-dashed {
    border-style: dashed !important;
  }
  .bg-primary-50 {
    background-color: rgba(1, 61, 157, 0.08);
  }
  .bg-blue-50 {
    background-color: rgba(37, 99, 235, 0.08);
  }
  .bg-green-50 {
    background-color: rgba(34, 197, 94, 0.08);
  }
  .bg-warning-50 {
    background-color: rgba(234, 179, 8, 0.12);
  }
  .bg-purple-50 {
    background-color: rgba(168, 85, 247, 0.1);
  }
  .shadow-xs {
    box-shadow: 0 8px 16px rgba(15, 23, 42, 0.08);
  }
</style>
@endpush
@endsection

