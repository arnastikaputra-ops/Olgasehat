@extends('pemilikkesehatan.Layout.pengelolakesehatan')

@section('content')
<div class="content-wrapper" style="background: #f4f8ff; min-height: 100vh;">
    <div class="content-header border-0 pb-0">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h1 class="page-title mb-1" style="font-weight: 700; color: #1b2b5a;">Daftar Klinik</h1>
                    <p class="text-muted mb-0">Kelola klinik dan fasilitas kesehatan Anda</p>
                </div>
                <ol class="breadcrumb float-md-right mt-2 mt-md-0">
                    <li class="breadcrumb-item"><a href="{{ route('pengelola.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Klinik</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="content pt-3">
        <div class="container-fluid">
        <div class="row">
            @forelse($clinics as $clinic)
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                <div class="card h-100 border-0 shadow-sm" style="border-radius: 20px;">
                    <div class="card-body text-center">
                        @if($clinic->logo)
                        <img src="{{ asset('fotoklinik/' . $clinic->logo) }}" alt="{{ $clinic->nama }}" class="img-fluid rounded mb-3" style="max-height: 150px;">
                        @else
                        <div class="bg-light rounded mb-3 d-flex align-items-center justify-content-center" style="height: 150px;">
                            <i class="fas fa-hospital fa-3x text-muted"></i>
                        </div>
                        @endif
                        <h5 class="font-weight-bold">{{ $clinic->nama }}</h5>
                        <p class="text-muted small mb-2">
                            @if($clinic->category)
                                <span class="badge badge-info">{{ $clinic->category->nama }}</span>
                            @endif
                            <span class="badge badge-{{ $clinic->tipe == 'klinik' ? 'primary' : 'success' }}">
                                {{ ucfirst($clinic->tipe) }}
                            </span>
                        </p>
                        <p class="text-muted small">
                            @if($clinic->status == 'approved')
                                <span class="badge badge-success">Disetujui</span>
                            @elseif($clinic->status == 'pending')
                                <span class="badge badge-warning">Menunggu Verifikasi</span>
                            @else
                                <span class="badge badge-danger">Ditolak</span>
                            @endif
                        </p>
                        <div class="mt-3">
                            <a href="{{ route('pengelola.clinics.show', $clinic->id) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                            <a href="{{ route('pengelola.clinics.edit', $clinic->id) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Belum ada klinik yang terdaftar.
                </div>
            </div>
            @endforelse

            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                <div class="card h-100 border-0 shadow-sm" style="border: 2px dashed #28a745; border-radius: 20px; background: rgba(40, 167, 69, 0.05);">
                    <div class="card-body d-flex flex-column text-center justify-content-center">
                        <div class="mb-3">
                            <i class="fas fa-plus-circle fa-3x" style="color: #28a745;"></i>
                        </div>
                        <h5 class="font-weight-bold" style="color: #1b2b5a;">Tambah Klinik</h5>
                        <p class="text-muted small mb-3">
                            Tambahkan klinik atau layanan kesehatan baru
                        </p>
                        <a href="{{ route('pengelola.clinics.create') }}" class="btn" style="background: #28a745; color: white; border-radius: 10px;">
                            <i class="fas fa-plus"></i> Tambah Klinik
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

