@extends('pemilikkesehatan.Layout.pengelolakesehatan')

@section('content')
<div class="content-wrapper" style="background: #f4f8ff; min-height: 100vh;">
    <div class="content-header border-0 pb-0">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h1 class="page-title mb-1" style="font-weight: 700; color: #1b2b5a;">Daftar Dokter</h1>
                    <p class="text-muted mb-0">Kelola data dokter di klinik Anda</p>
                </div>
                <ol class="breadcrumb float-md-right mt-2 mt-md-0">
                    <li class="breadcrumb-item"><a href="{{ route('pengelola.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Dokter</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="content pt-3">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-12">
                    <a href="{{ route('pengelola.doctors.create') }}" class="btn btn-primary btn-sm btn-block btn-md-inline-block" style="background: #28a745; border-color: #28a745; border-radius: 10px;">
                        <i class="fas fa-plus"></i> Tambah Dokter
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                        <div class="card-header" style="background: white; border-radius: 20px 20px 0 0;">
                            <h3 class="card-title mb-0" style="font-weight: 700; color: #1b2b5a;">Daftar Dokter</h3>
                        </div>
                        <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>Foto</th>
                                    <th>Nama</th>
                                    <th class="d-none d-md-table-cell">Spesialisasi</th>
                                    <th class="d-none d-lg-table-cell">Klinik</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($doctors as $doctor)
                                <tr>
                                    <td data-label="Foto">
                                        @if($doctor->foto)
                                            <img src="{{ asset('fotodokter/' . $doctor->foto) }}" alt="{{ $doctor->nama }}" class="img-circle img-size-32">
                                        @else
                                            <div class="img-circle img-size-32 bg-secondary d-flex align-items-center justify-content-center">
                                                <i class="fas fa-user-md text-white"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td data-label="Nama">
                                        <strong>{{ $doctor->nama_lengkap }}</strong>
                                        @if($doctor->nomor_str)
                                            <br><small class="text-muted">STR: {{ $doctor->nomor_str }}</small>
                                        @endif
                                    </td>
                                    <td data-label="Spesialisasi" class="d-none d-md-table-cell">
                                        <span class="badge badge-info">{{ $doctor->spesialisasi }}</span>
                                    </td>
                                    <td data-label="Klinik" class="d-none d-lg-table-cell">{{ $doctor->clinic->nama }}</td>
                                    <td data-label="Aksi">
                                        <a href="{{ route('pengelola.doctors.edit', $doctor->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('pengelola.doctors.destroy', $doctor->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus dokter ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">Belum ada dokter yang terdaftar</td>
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
@endsection

