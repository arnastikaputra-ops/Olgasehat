@extends('pemilikkesehatan.Layout.pengelolakesehatan')

@section('content')
<div class="content-wrapper" style="background: #f4f8ff; min-height: 100vh;">
    <div class="content-header border-0 pb-0">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h1 class="page-title mb-1" style="font-weight: 700; color: #1b2b5a;">Jadwal Dokter</h1>
                    <p class="text-muted mb-0">Kelola jadwal praktik dokter di klinik Anda</p>
                </div>
                <ol class="breadcrumb float-md-right mt-2 mt-md-0">
                    <li class="breadcrumb-item"><a href="{{ route('pengelola.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Jadwal Dokter</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="content pt-3">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-12">
                    <a href="{{ route('pengelola.schedules.create') }}" class="btn btn-primary btn-sm btn-block btn-md-inline-block" style="background: #28a745; border-color: #28a745; border-radius: 10px;">
                        <i class="fas fa-plus"></i> Tambah Jadwal
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                        <div class="card-header" style="background: white; border-radius: 20px 20px 0 0;">
                            <h3 class="card-title mb-0" style="font-weight: 700; color: #1b2b5a;">Daftar Jadwal Dokter</h3>
                        </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover m-0">
                                <thead>
                                    <tr>
                                        <th>Dokter</th>
                                        <th class="d-none d-md-table-cell">Klinik</th>
                                        <th>Hari</th>
                                        <th>Jam</th>
                                        <th class="d-none d-lg-table-cell">Durasi</th>
                                        <th class="d-none d-lg-table-cell">Kuota</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($schedules as $schedule)
                                    <tr>
                                        <td data-label="Dokter">
                                            <strong>{{ $schedule->doctor->nama_lengkap ?? $schedule->doctor->nama }}</strong>
                                            <br><small class="text-muted d-md-none">{{ $schedule->clinic->nama }}</small>
                                            <br><small class="text-muted">{{ $schedule->doctor->spesialisasi }}</small>
                                        </td>
                                        <td data-label="Klinik" class="d-none d-md-table-cell">{{ $schedule->clinic->nama }}</td>
                                        <td data-label="Hari">
                                            <span class="badge badge-primary">{{ $schedule->hari_indonesia }}</span>
                                        </td>
                                        <td data-label="Jam">
                                            {{ $schedule->jam_mulai }} - {{ $schedule->jam_selesai }}
                                        </td>
                                        <td data-label="Durasi" class="d-none d-lg-table-cell">{{ $schedule->durasi_konsultasi }} menit</td>
                                        <td data-label="Kuota" class="d-none d-lg-table-cell">{{ $schedule->kuota_per_hari }} pasien/hari</td>
                                        <td data-label="Aksi">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('pengelola.schedules.edit', $schedule->id) }}" class="btn btn-sm btn-primary" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('pengelola.schedules.destroy', $schedule->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?');">
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
                                            <i class="fas fa-calendar-times fa-2x text-muted mb-2"></i>
                                            <p class="text-muted mb-0">Belum ada jadwal yang terdaftar</p>
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

