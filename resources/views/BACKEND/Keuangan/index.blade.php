@extends('BACKEND.Layout.admin')

@section('content')
<div class="content-wrapper">
  <!-- Content Header -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0"><i class="fas fa-wallet text-primary mr-2"></i> Laporan Keuangan & Komisi Platform</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Keuangan</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <!-- Main Content -->
  <section class="content">
    <div class="container-fluid">
      
      <!-- Metric Cards -->
      <div class="row">
        <!-- Total Omset -->
        <div class="col-lg-3 col-6">
          <div class="small-box bg-info">
            <div class="inner">
              <h3>Rp {{ number_format($totalOmsetKeseluruhan, 0, ',', '.') }}</h3>
              <p>Total Transaksi Keseluruhan</p>
            </div>
            <div class="icon">
              <i class="fas fa-cash-register"></i>
            </div>
          </div>
        </div>

        <!-- Total Komisi Platform -->
        <div class="col-lg-3 col-6">
          <div class="small-box bg-success">
            <div class="inner">
              <h3>Rp {{ number_format($totalKomisiPlatform, 0, ',', '.') }}</h3>
              <p>Komisi OlgaSehat (Platform)</p>
            </div>
            <div class="icon">
              <i class="fas fa-percentage"></i>
            </div>
          </div>
        </div>

        <!-- Venue Earnings & Commission -->
        <div class="col-lg-3 col-6">
          <div class="small-box bg-warning">
            <div class="inner">
              <h3>Rp {{ number_format($totalOmsetVenue, 0, ',', '.') }}</h3>
              <p>Total Booking Venue ({{ count($venueBookings) }})</p>
            </div>
            <div class="icon">
              <i class="fas fa-running"></i>
            </div>
          </div>
        </div>

        <!-- Health Earnings & Commission -->
        <div class="col-lg-3 col-6">
          <div class="small-box bg-danger">
            <div class="inner">
              <h3>Rp {{ number_format($totalOmsetHealth, 0, ',', '.') }}</h3>
              <p>Total Booking Kesehatan ({{ count($healthBookings) }})</p>
            </div>
            <div class="icon">
              <i class="fas fa-user-md"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Ringkasan Skema Bagi Hasil -->
      <div class="row mb-3">
        <div class="col-md-6">
          <div class="card card-outline card-primary shadow-sm mb-3">
            <div class="card-header">
              <h5 class="card-title font-weight-bold"><i class="fas fa-building mr-1"></i> Ringkasan Keuangan Booking Venue</h5>
            </div>
            <div class="card-body">
              <table class="table table-sm table-borderless">
                <tr>
                  <td>Total Bruto Booking Venue:</td>
                  <td class="text-right font-weight-bold">Rp {{ number_format($totalOmsetVenue, 0, ',', '.') }}</td>
                </tr>
                <tr>
                  <td>Hak Mitra Venue (Bersih):</td>
                  <td class="text-right text-success font-weight-bold">Rp {{ number_format($totalPendapatanMitraVenue, 0, ',', '.') }}</td>
                </tr>
                <tr>
                  <td>Komisi Platform OlgaSehat:</td>
                  <td class="text-right text-primary font-weight-bold">Rp {{ number_format($totalKomisiVenue, 0, ',', '.') }}</td>
                </tr>
              </table>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="card card-outline card-danger shadow-sm mb-3">
            <div class="card-header">
              <h5 class="card-title font-weight-bold"><i class="fas fa-clinic-medical mr-1"></i> Ringkasan Keuangan Layanan Kesehatan</h5>
            </div>
            <div class="card-body">
              <table class="table table-sm table-borderless">
                <tr>
                  <td>Total Bruto Booking Kesehatan:</td>
                  <td class="text-right font-weight-bold">Rp {{ number_format($totalOmsetHealth, 0, ',', '.') }}</td>
                </tr>
                <tr>
                  <td>Hak Mitra Klinik (Bersih):</td>
                  <td class="text-right text-success font-weight-bold">Rp {{ number_format($totalPendapatanMitraHealth, 0, ',', '.') }}</td>
                </tr>
                <tr>
                  <td>Komisi Platform OlgaSehat:</td>
                  <td class="text-right text-primary font-weight-bold">Rp {{ number_format($totalKomisiHealth, 0, ',', '.') }}</td>
                </tr>
              </table>
            </div>
          </div>
        </div>
      </div>

      <!-- Tabbed Tables -->
      <div class="card card-primary card-outline card-tabs">
        <div class="card-header p-0 pt-1 border-bottom-0">
          <ul class="nav nav-tabs" id="keuangan-tabs" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" id="tab-venue-link" data-toggle="pill" href="#tab-venue" role="tab">
                <i class="fas fa-map-marker-alt mr-1"></i> Transaksi Booking Venue ({{ count($venueBookings) }})
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="tab-health-link" data-toggle="pill" href="#tab-health" role="tab">
                <i class="fas fa-heartbeat mr-1"></i> Transaksi Layanan Kesehatan ({{ count($healthBookings) }})
              </a>
            </li>
          </ul>
        </div>
        <div class="card-body">
          <div class="tab-content" id="keuangan-tabs-content">
            
            <!-- TAB 1: VENUE BOOKINGS -->
            <div class="tab-pane fade show active" id="tab-venue" role="tabpanel">
              <div class="table-responsive">
                <table class="table table-hover table-striped">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>ID Booking</th>
                      <th>Venue</th>
                      <th>Pemesan</th>
                      <th>Total Bayar</th>
                      <th>Komisi Platform</th>
                      <th>Pendapatan Mitra</th>
                      <th>Status Pembayaran</th>
                      <th>Tanggal</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($venueBookings as $idx => $b)
                      <tr>
                        <td>{{ $idx + 1 }}</td>
                        <td><code>#VB-{{ $b->id }}</code></td>
                        <td><strong>{{ $b->venue->namavenue ?? 'Venue N/A' }}</strong></td>
                        <td>{{ $b->user->name ?? $b->nama_pemesan ?? 'Guest' }}</td>
                        <td class="font-weight-bold">Rp {{ number_format($b->total_harga, 0, ',', '.') }}</td>
                        <td class="text-primary font-weight-bold">Rp {{ number_format($b->komisi_platform, 0, ',', '.') }}</td>
                        <td class="text-success font-weight-bold">Rp {{ number_format($b->pendapatan_mitra, 0, ',', '.') }}</td>
                        <td>
                          @if($b->status_pembayaran == 'paid')
                            <span class="badge badge-success">Lunas</span>
                          @elseif($b->status_pembayaran == 'pending')
                            <span class="badge badge-warning">Menunggu ACC</span>
                          @else
                            <span class="badge badge-danger">Ditolak</span>
                          @endif
                        </td>
                        <td>{{ $b->created_at->format('d M Y H:i') }}</td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="9" class="text-center text-muted py-4">Belum ada transaksi booking venue.</td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>

            <!-- TAB 2: HEALTH BOOKINGS -->
            <div class="tab-pane fade" id="tab-health" role="tabpanel">
              <div class="table-responsive">
                <table class="table table-hover table-striped">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Kode Booking</th>
                      <th>Klinik / Layanan</th>
                      <th>Pasien</th>
                      <th>Total Bayar</th>
                      <th>Komisi Platform</th>
                      <th>Pendapatan Mitra</th>
                      <th>Status</th>
                      <th>Tanggal Jadwal</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($healthBookings as $idx => $hb)
                      <tr>
                        <td>{{ $idx + 1 }}</td>
                        <td><code>{{ $hb->booking_code ?? '#HB-'.$hb->id }}</code></td>
                        <td><strong>{{ $hb->clinic->nama ?? 'Klinik N/A' }}</strong></td>
                        <td>{{ $hb->user->name ?? $hb->nama_pasien ?? 'Guest' }}</td>
                        <td class="font-weight-bold">Rp {{ number_format($hb->harga, 0, ',', '.') }}</td>
                        <td class="text-primary font-weight-bold">Rp {{ number_format($hb->komisi_platform ?? 0, 0, ',', '.') }}</td>
                        <td class="text-success font-weight-bold">Rp {{ number_format($hb->pendapatan_mitra ?? $hb->harga, 0, ',', '.') }}</td>
                        <td>
                          @if($hb->status == 'approved' || $hb->status == 'completed')
                            <span class="badge badge-success">{{ ucfirst($hb->status) }}</span>
                          @elseif($hb->status == 'pending')
                            <span class="badge badge-warning">Pending</span>
                          @else
                            <span class="badge badge-danger">{{ ucfirst($hb->status) }}</span>
                          @endif
                        </td>
                        <td>{{ $hb->tanggal }} {{ $hb->jam }}</td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="9" class="text-center text-muted py-4">Belum ada transaksi booking kesehatan.</td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>

          </div>
        </div>
      </div>

    </div>
  </section>
</div>
@endsection
