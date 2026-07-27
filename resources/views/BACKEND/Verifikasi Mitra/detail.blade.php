@extends('backend.layout.admin')

@push('css')
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Toastr CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
@endpush

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Detail Mitra Pemilik Lapangan</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('mitra.index') }}">Verifikasi Mitra</a></li>
                        <li class="breadcrumb-item active">Detail</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Detail Mitra</h5>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted">Informasi Pribadi</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td width="30%"><strong>Nama Mitra:</strong></td>
                                    <td>{{ $mitra->nama_anda }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>{{ $mitra->user->email ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Role:</strong></td>
                                    <td>{{ $mitra->user->role ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status User:</strong></td>
                                    <td>
                                        @if($mitra->user->status ?? null === 'approved')
                                            <span class="badge bg-success">Approved</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Informasi Bisnis</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td width="30%"><strong>Nama Bisnis:</strong></td>
                                    <td>{{ $mitra->nama_bisnis }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email Bisnis:</strong></td>
                                    <td>{{ $mitra->email_bisnis }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Kontak Bisnis:</strong></td>
                                    <td>
                                        @php
                                            $kontakBisnis = $mitra->kontak_bisnis ?? '';
                                            // Format nomor telepon dengan +62
                                            if ($kontakBisnis) {
                                                $rawPhone = trim($kontakBisnis);
                                                if (\Illuminate\Support\Str::startsWith($rawPhone, '+62')) {
                                                    $displayPhone = $rawPhone;
                                                } elseif (\Illuminate\Support\Str::startsWith($rawPhone, '62')) {
                                                    $displayPhone = '+' . $rawPhone;
                                                } else {
                                                    $displayPhone = '+62' . $rawPhone;
                                                }
                                            } else {
                                                $displayPhone = 'Belum diisi';
                                            }
                                        @endphp
                                        {{ $displayPhone }}
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Tipe Venue:</strong></td>
                                    <td>{{ $mitra->tipe_venue }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status Mitra:</strong></td>
                                    <td>
                                        @if($mitra->status === 'approved')
                                            <span class="badge bg-success">Approved</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @endif
                                    </td>
                                 </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="p-3 bg-light rounded border">
                                <h6 class="font-weight-bold text-primary mb-3">
                                    <i class="fas fa-percentage mr-1"></i> Pengaturan Bagi Hasil / Komisi Platform (OlgaSehat)
                                </h6>
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label font-weight-bold">Tipe Bagi Hasil / Komisi:</label>
                                        <select name="komisi_tipe" id="komisi_tipe" class="form-control" form="verifyForm">
                                            <option value="none" {{ ($mitra->komisi_tipe ?? 'none') == 'none' ? 'selected' : '' }}>Tanpa Bagi Hasil (0%)</option>
                                            <option value="percentage" {{ ($mitra->komisi_tipe ?? '') == 'percentage' ? 'selected' : '' }}>Bagi Hasil Persentase (%)</option>
                                            <option value="fixed" {{ ($mitra->komisi_tipe ?? '') == 'fixed' ? 'selected' : '' }}>Nominal Tetap (Rp)</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label font-weight-bold">Nilai Komisi Platform:</label>
                                        <input type="number" step="0.01" min="0" name="komisi_nilai" id="komisi_nilai" value="{{ (float)($mitra->komisi_nilai ?? 0) }}" class="form-control" placeholder="Contoh: 5 untuk 5% atau 5000 untuk Rp 5.000" form="verifyForm">
                                    </div>
                                </div>
                                <small class="text-muted">
                                    * Atur apakah penghasilan transaksi booking venue ini akan dipotong sekian persen/nominal untuk pemilik platform OlgaSehat.
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="text-muted">Informasi Tambahan</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td width="20%"><strong>Dibuat Pada:</strong></td>
                                    <td>{{ $mitra->created_at->format('d M Y, H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Diupdate Pada:</strong></td>
                                    <td>{{ $mitra->updated_at->format('d M Y, H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <a href="{{ route('mitra.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    @if($mitra->status === 'pending')
                    <form action="{{ route('mitra.verify', $mitra->id) }}" method="POST" id="verifyForm" class="d-inline" onsubmit="return confirm('Yakin ingin verifikasi mitra ini dengan skema bagi hasil yang dipilih?');">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-success font-weight-bold">
                            <i class="fas fa-check"></i> Verifikasi Mitra
                        </button>
                    </form>
                    @elseif($mitra->status === 'approved')
                    <span class="badge bg-success py-2 px-3 align-middle me-2">
                        <i class="fas fa-check-circle mr-1"></i> Sudah Disetujui
                    </span>
                    @endif
                    <form action="{{ route('mitra.destroy', $mitra->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus mitra ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Toastr -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Toastr Alerts -->
<script>
  @if (Session::has('success'))
    toastr.success("{{ Session::get('success') }}");
  @endif

  @if (Session::has('error'))
    toastr.error("{{ Session::get('error') }}");
  @endif
</script>
@endpush
