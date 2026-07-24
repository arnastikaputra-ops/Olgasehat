@extends('BACKEND.Layout.admin')

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Detail Pengelola Kesehatan</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('tempat-sehat.index') }}">Tempat Sehat</a></li>
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
                    <h5 class="mb-0">Detail Pengelola Kesehatan</h5>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted">Informasi Pribadi</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td width="30%"><strong>Nama Pengelola:</strong></td>
                                    <td>{{ $mitra->nama_anda }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>{{ $mitra->user->email ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Role:</strong></td>
                                    <td>{{ ucwords(str_replace('_', ' ', $mitra->user->role ?? 'N/A')) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status User:</strong></td>
                                    <td>
                                        @if(($mitra->user->status ?? null) === 'approved')
                                            <span class="badge bg-success">Approved</span>
                                        @elseif(($mitra->user->status ?? null) === 'rejected')
                                            <span class="badge bg-danger">Rejected</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Informasi Fasilitas Kesehatan</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td width="30%"><strong>Nama Fasilitas:</strong></td>
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
                                    <td><strong>Tipe Fasilitas:</strong></td>
                                    <td>{{ $mitra->tipe_venue }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tipe Mitra:</strong></td>
                                    <td>{{ ucwords(str_replace('_', ' ', $mitra->tipe_mitra ?? 'N/A')) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status Mitra:</strong></td>
                                    <td>
                                        @if($mitra->status === 'approved')
                                            <span class="badge bg-success">Approved</span>
                                        @elseif($mitra->status === 'rejected')
                                            <span class="badge bg-danger">Rejected</span>
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
                    <a href="{{ route('tempat-sehat.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    @if($mitra->status === 'pending')
                    <form action="{{ route('tempat-sehat.verify', $mitra->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin verifikasi pengelola kesehatan ini?');">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check"></i> Verifikasi
                        </button>
                    </form>
                    @endif
                    <form action="{{ route('tempat-sehat.destroy', $mitra->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus pengelola kesehatan ini?');">
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

