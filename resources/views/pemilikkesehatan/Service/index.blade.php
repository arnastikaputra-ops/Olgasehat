@extends('pemilikkesehatan.Layout.pengelolakesehatan')

@section('content')
<div class="content-wrapper" style="background: #f4f8ff; min-height: 100vh;">
    <div class="content-header border-0 pb-0">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h1 class="page-title mb-1" style="font-weight: 700; color: #1b2b5a;">Daftar Layanan</h1>
                    <p class="text-muted mb-0">Kelola layanan kesehatan yang ditawarkan</p>
                </div>
                <ol class="breadcrumb float-md-right mt-2 mt-md-0">
                    <li class="breadcrumb-item"><a href="{{ route('pengelola.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Layanan</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="content pt-3">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-12">
                    <a href="{{ route('pengelola.services.create') }}" class="btn btn-primary btn-sm btn-block btn-md-inline-block" style="background: #28a745; border-color: #28a745; border-radius: 10px;">
                        <i class="fas fa-plus"></i> Tambah Layanan
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                        <div class="card-header" style="background: white; border-radius: 20px 20px 0 0;">
                            <h3 class="card-title mb-0" style="font-weight: 700; color: #1b2b5a;">Daftar Layanan Kesehatan</h3>
                        </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover m-0">
                                <thead>
                                    <tr>
                                        <th>Nama Layanan</th>
                                        <th>Kategori</th>
                                        <th class="d-none d-md-table-cell">Klinik</th>
                                        <th class="d-none d-lg-table-cell">Dokter</th>
                                        <th>Harga</th>
                                        <th class="d-none d-lg-table-cell">Durasi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($services as $service)
                                    <tr>
                                        <td data-label="Nama Layanan">
                                            <strong>{{ $service->nama }}</strong>
                                            <br><small class="text-muted d-md-none">{{ $service->clinic->nama }}</small>
                                        </td>
                                        <td data-label="Kategori">
                                            <span class="badge badge-info">{{ $service->kategori }}</span>
                                        </td>
                                        <td data-label="Klinik" class="d-none d-md-table-cell">{{ $service->clinic->nama }}</td>
                                        <td data-label="Dokter" class="d-none d-lg-table-cell">
                                            {{ $service->doctor ? $service->doctor->nama_lengkap : '-' }}
                                        </td>
                                        <td data-label="Harga">
                                            @if(isset($service->tipe_harga) && $service->tipe_harga == 'gratis')
                                                <span class="badge badge-success">Gratis</span>
                                            @elseif($service->harga)
                                                Rp {{ number_format($service->harga, 0, ',', '.') }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td data-label="Durasi" class="d-none d-lg-table-cell">
                                            {{ $service->durasi ? $service->durasi . ' menit' : '-' }}
                                        </td>
                                        <td data-label="Aksi">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('pengelola.services.edit', $service->id) }}" class="btn btn-sm btn-primary" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('pengelola.services.destroy', $service->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus layanan ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <i class="fas fa-heartbeat fa-2x text-muted mb-2"></i>
                                            <p class="text-muted mb-0">Belum ada layanan yang terdaftar</p>
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
    </div>
</div>
@endsection

