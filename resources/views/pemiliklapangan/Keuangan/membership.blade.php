@extends('pemiliklapangan.Layout.ownervenue')

@section('content')
<div class="content-wrapper p-4">
  <div class="container-fluid">
    <div class="mb-4">
      <h4 class="font-weight-bold mb-1"><i class="fas fa-wallet text-warning mr-2"></i>Keuangan & Verifikasi Member</h4>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent p-0 mb-0">
          <li class="breadcrumb-item"><a href="/pemiliklapangan/dashboard">Keuangan</a></li>
          <li class="breadcrumb-item active" aria-current="page">Membership</li>
        </ol>
      </nav>
    </div>

    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
        <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif

    @if(session('error'))
      <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
        <i class="fas fa-exclamation-circle mr-1"></i> {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif

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
            <h5 class="font-weight-bold mb-1">Daftar Member & Verifikasi Pembayaran</h5>
            <p class="text-muted small mb-0">Kelola pendaftaran member & verifikasi bukti pembayaran untuk paket keanggotaan Anda</p>
          </div>
          <a href="/pemiliklapangan/membership" class="btn btn-warning font-weight-bold shadow-sm">
            <i class="fas fa-plus mr-1"></i> Buat Paket Membership Baru
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
                <th>Pendapatan Bersih</th>
                <th>Status Pembayaran</th>
                <th>Bukti Bayar</th>
                <th>Tanggal Daftar</th>
                <th class="text-center">Aksi Verifikasi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($participants as $index => $p)
              @php
                $harga = (float)($p->activity->harga ?? 0);
                $venue = \App\Models\Pendaftaran::where('user_id', $p->activity->pemilik_id ?? 0)->first();
                $rate = (float)($venue->membership_komisi_nilai ?? 0);
                $tipe = $venue->membership_komisi_tipe ?? 'percentage';
                
                if ($tipe === 'percentage') {
                    $komisi = ($harga * $rate) / 100;
                } else if ($tipe === 'fixed') {
                    $komisi = $rate;
                } else {
                    $komisi = 0;
                }
                $bersih = max(0, $harga - $komisi);
              @endphp
              <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                  <span class="font-weight-bold text-dark d-block">{{ $p->activity->nama ?? 'Paket Membership' }}</span>
                  <small class="text-muted"><i class="fas fa-building mr-1"></i>{{ $p->activity->pendaftaran->nama_venue ?? $p->activity->lokasi ?? 'Venue' }}</small>
                </td>
                <td>
                  <div>
                    <span class="font-weight-semibold text-dark">{{ $p->nama_peserta }}</span>
                    <br>
                    <small class="text-muted">{{ $p->user->email ?? '-' }}</small>
                  </div>
                </td>
                <td>
                  <span class="font-weight-bold text-dark">
                    Rp {{ number_format($harga, 0, ',', '.') }}
                  </span>
                </td>
                <td>
                  <span class="font-weight-bold text-success d-block">
                    Rp {{ number_format($bersih, 0, ',', '.') }}
                  </span>
                  <button type="button" class="btn btn-xs btn-outline-info font-weight-bold mt-1" data-toggle="modal" data-target="#modalRumusMem{{ $p->id }}">
                    <i class="fas fa-calculator mr-1"></i> Rincian Rumus
                  </button>

                  <!-- Modal Rincian Hitung-Hitungan Membership -->
                  <div class="modal fade" id="modalRumusMem{{ $p->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                      <div class="modal-content">
                        <div class="modal-header bg-info text-white">
                          <h5 class="modal-title font-weight-bold"><i class="fas fa-calculator mr-2"></i>Rincian Hitung-Hitungan Membership</h5>
                          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body text-left">
                          <div class="alert alert-light border mb-3">
                            <strong class="d-block text-dark">Paket: {{ $p->activity->nama ?? 'Membership' }}</strong>
                            <small class="text-muted">Member: {{ $p->nama_peserta }} ({{ $p->user->email ?? '-' }})</small>
                          </div>

                          <h6 class="font-weight-bold text-dark mb-2">Formula Rumus Bagi Hasil:</h6>
                          <table class="table table-sm table-bordered">
                            <tr>
                              <td>Harga Paket Membership (Bruto)</td>
                              <td class="text-right font-weight-bold">Rp {{ number_format($harga, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                              <td class="text-primary">
                                (-) Komisi Platform 
                                @if($rate == 0 || $tipe === 'none')
                                  <span class="badge badge-success">0% (Utuh 100%)</span>
                                @else
                                  <span class="badge badge-warning">{{ $rate }}{{ $tipe === 'percentage' ? '%' : ' Rp' }}</span>
                                @endif
                              </td>
                              <td class="text-right text-primary font-weight-bold">- Rp {{ number_format($komisi, 0, ',', '.') }}</td>
                            </tr>
                            <tr class="bg-light">
                              <td class="text-success font-weight-bold">(=) Pendapatan Bersih Mitra</td>
                              <td class="text-right text-success font-weight-bold">Rp {{ number_format($bersih, 0, ',', '.') }}</td>
                            </tr>
                          </table>

                          <div class="p-2 bg-light rounded border">
                            <small class="text-muted d-block">Status Transfer Admin: 
                              @if($p->status === 'approved')
                                <strong class="text-success"><i class="fas fa-check-circle mr-1"></i>Telah Dikonfirmasi / Siap Ditarik</strong>
                              @else
                                <strong class="text-warning"><i class="fas fa-clock mr-1"></i>Menunggu Verifikasi</strong>
                              @endif
                            </small>
                          </div>
                        </div>
                        <div class="modal-footer bg-light">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </td>
                <td>
                  @if($p->status === 'approved')
                    <span class="badge badge-success px-3 py-1 font-weight-bold">
                      <i class="fas fa-check-circle mr-1"></i> Disetujui / Member Aktif
                    </span>
                  @elseif($p->status === 'pending')
                    <span class="badge badge-warning text-dark px-3 py-1 font-weight-bold">
                      <i class="fas fa-clock mr-1"></i> Menunggu Verifikasi
                    </span>
                  @else
                    <span class="badge badge-danger px-3 py-1 font-weight-bold">
                      <i class="fas fa-times-circle mr-1"></i> Ditolak
                    </span>
                    @if($p->catatan)
                      <br><small class="text-danger">{{ $p->catatan }}</small>
                    @endif
                  @endif
                </td>
                <td>
                  @if($p->bukti_pembayaran)
                    <button type="button" class="btn btn-sm btn-outline-info" data-toggle="modal" data-target="#modalBukti{{ $p->id }}">
                      <i class="fas fa-image mr-1"></i> Lihat Bukti
                    </button>

                    <!-- Modal Bukti Bayar -->
                    <div class="modal fade" id="modalBukti{{ $p->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                          <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title font-weight-bold"><i class="fas fa-receipt mr-2"></i>Bukti Pembayaran - {{ $p->nama_peserta }}</h5>
                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body text-center">
                            <img src="{{ asset('bukti_pembayaran/' . $p->bukti_pembayaran) }}" class="img-fluid rounded shadow-sm" alt="Bukti Pembayaran" style="max-height: 450px;">
                            @if($p->catatan)
                              <p class="mt-2 text-muted small mb-0">{{ $p->catatan }}</p>
                            @endif
                          </div>
                          <div class="modal-footer bg-light">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  @else
                    <span class="text-muted small">Gratis / Tanpa Bukti</span>
                  @endif
                </td>
                <td>{{ $p->created_at->format('d M Y, H:i') }}</td>
                <td class="text-center">
                  @if($p->status === 'pending')
                    <div class="d-flex justify-content-center gap-1">
                      {{-- Form Approve --}}
                      <form action="{{ route('pemilik.membership.participant.approve', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Setujui pembayaran & aktifkan status member ini?')">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-success font-weight-bold shadow-sm">
                          <i class="fas fa-check mr-1"></i> Setujui
                        </button>
                      </form>
                      {{-- Modal Reject Button --}}
                      <button type="button" class="btn btn-sm btn-danger font-weight-bold shadow-sm ml-1" data-toggle="modal" data-target="#modalReject{{ $p->id }}">
                        <i class="fas fa-times mr-1"></i> Tolak
                      </button>
                    </div>

                    <!-- Modal Reject Form -->
                    <div class="modal fade" id="modalReject{{ $p->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                          <form action="{{ route('pemilik.membership.participant.reject', $p->id) }}" method="POST">
                            @csrf
                            <div class="modal-header bg-danger text-white">
                              <h5 class="modal-title font-weight-bold"><i class="fas fa-times-circle mr-2"></i>Tolak Pendaftaran Member</h5>
                              <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body text-left">
                              <p class="text-dark font-weight-bold mb-2">Member: {{ $p->nama_peserta }}</p>
                              <div class="form-group">
                                <label for="alasan_reject_{{ $p->id }}" class="font-weight-semibold">Alasan Penolakan <span class="text-danger">*</span></label>
                                <textarea name="alasan_reject" id="alasan_reject_{{ $p->id }}" class="form-control" rows="3" placeholder="Contoh: Bukti pembayaran tidak valid / nominal tidak sesuai" required></textarea>
                              </div>
                            </div>
                            <div class="modal-footer bg-light">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                              <button type="submit" class="btn btn-danger font-weight-bold">Tolak Pembayaran</button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                  @elseif($p->status === 'approved')
                    <span class="text-success font-weight-bold small"><i class="fas fa-check-double mr-1"></i>Telah Diverifikasi</span>
                  @else
                    <span class="text-danger font-weight-bold small"><i class="fas fa-ban mr-1"></i>Ditolak</span>
                  @endif
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="9" class="text-center py-5 text-muted">
                  <i class="fas fa-users-slash fa-3x mb-3 text-gray-300"></i>
                  <p class="mb-0 font-weight-bold">Belum Ada Transaksi Member</p>
                  <small>Member yang mendaftar ke paket membership Anda akan muncul di sini untuk verifikasi.</small>
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
