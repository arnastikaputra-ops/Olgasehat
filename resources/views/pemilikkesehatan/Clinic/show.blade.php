@extends('pemilikkesehatan.Layout.pengelolakesehatan')

@section('content')
<div class="content-wrapper" style="background: #f4f8ff; min-height: 100vh;">
    <div class="content-header border-0 pb-0">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h1 class="page-title mb-1" style="font-weight: 700; color: #1b2b5a;">Detail Klinik</h1>
                    <p class="text-muted mb-0">Informasi lengkap klinik atau fasilitas kesehatan</p>
                </div>
                <ol class="breadcrumb float-md-right mt-2 mt-md-0">
                    <li class="breadcrumb-item"><a href="{{ route('pengelola.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('pengelola.clinics') }}">Klinik</a></li>
                    <li class="breadcrumb-item active">Detail</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="content pt-3">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                        @php
                            $cFoto = asset('assets/klnk.png');
                            if (!empty($clinic->logo)) {
                                $fPath = is_array($clinic->logo) ? ($clinic->logo[0] ?? '') : (string)$clinic->logo;
                                if (!empty($fPath)) {
                                    if (\Illuminate\Support\Str::startsWith($fPath, 'http')) {
                                        $cFoto = $fPath;
                                    } elseif (\Illuminate\Support\Str::startsWith($fPath, 'storage/')) {
                                        $cFoto = asset($fPath);
                                    } elseif (\Illuminate\Support\Str::startsWith($fPath, 'fotoklinik/')) {
                                        $cFoto = asset($fPath);
                                    } else {
                                        $cFoto = asset('fotoklinik/' . $fPath);
                                    }
                                }
                            } elseif (!empty($clinic->foto_utama)) {
                                $fPath = is_array($clinic->foto_utama) ? ($clinic->foto_utama[0] ?? '') : (string)$clinic->foto_utama;
                                if (!empty($fPath)) {
                                    if (\Illuminate\Support\Str::startsWith($fPath, 'http')) {
                                        $cFoto = $fPath;
                                    } elseif (\Illuminate\Support\Str::startsWith($fPath, 'storage/')) {
                                        $cFoto = asset($fPath);
                                    } elseif (\Illuminate\Support\Str::startsWith($fPath, 'fotoklinik/')) {
                                        $cFoto = asset($fPath);
                                    } else {
                                        $cFoto = asset('fotoklinik/' . $fPath);
                                    }
                                }
                            } elseif ($clinic->galleries && $clinic->galleries->count() > 0) {
                                $firstGal = $clinic->galleries->first()->foto;
                                if (!empty($firstGal)) {
                                    if (\Illuminate\Support\Str::startsWith($firstGal, 'http')) {
                                        $cFoto = $firstGal;
                                    } elseif (\Illuminate\Support\Str::startsWith($firstGal, 'storage/')) {
                                        $cFoto = asset($firstGal);
                                    } elseif (\Illuminate\Support\Str::startsWith($firstGal, 'clinic_galleries/')) {
                                        $cFoto = asset('storage/' . $firstGal);
                                    } elseif (\Illuminate\Support\Str::startsWith($firstGal, 'fotoklinik/')) {
                                        $cFoto = asset($firstGal);
                                    } else {
                                        $cFoto = asset('fotoklinik/' . $firstGal);
                                    }
                                }
                            }
                        @endphp
                        <div class="mb-3 overflow-hidden rounded" style="max-height: 200px;">
                            <img src="{{ $cFoto }}" onerror="this.onerror=null;this.src='{{ asset('assets/klnk.png') }}';" alt="{{ $clinic->nama }}" class="img-fluid rounded w-100" style="object-fit: cover; max-height: 200px;">
                        </div>
                        <h4 class="font-weight-bold">{{ $clinic->nama }}</h4>
                        <p class="text-muted">
                            @if($clinic->category)
                                <span class="badge badge-info">{{ $clinic->category->nama }}</span>
                            @endif
                            <span class="badge badge-{{ $clinic->tipe == 'klinik' ? 'primary' : 'success' }}">
                                {{ ucfirst($clinic->tipe) }}
                            </span>
                        </p>
                        <p>
                            @if($clinic->status == 'approved')
                                <span class="badge badge-success">Disetujui</span>
                            @elseif($clinic->status == 'pending')
                                <span class="badge badge-warning">Menunggu Verifikasi</span>
                            @else
                                <span class="badge badge-danger">Ditolak</span>
                            @endif
                        </p>
                        <div class="mt-3">
                            <a href="{{ route('pengelola.clinics.edit', $clinic->id) }}" class="btn btn-primary btn-block">
                                <i class="fas fa-edit"></i> Edit Klinik
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                    <div class="card-header" style="background: white; border-radius: 20px 20px 0 0;">
                        <h3 class="card-title mb-0" style="font-weight: 700; color: #1b2b5a;">Informasi Klinik</h3>
                    </div>
                    <div class="card-body">
                        @if($clinic->motto)
                        <div class="mb-3">
                            <strong>Motto:</strong>
                            <p class="mb-0">{{ $clinic->motto }}</p>
                        </div>
                        @endif

                        @if($clinic->deskripsi)
                        <div class="mb-3">
                            <strong>Deskripsi:</strong>
                            <p class="mb-0">{{ $clinic->deskripsi }}</p>
                        </div>
                        @endif

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong><i class="fas fa-map-marker-alt mr-2"></i>Alamat:</strong>
                                <p class="mb-0">{{ $clinic->alamat ?? '-' }}</p>
                                <p class="mb-0">{{ $clinic->kota ?? '' }} {{ $clinic->provinsi ?? '' }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong><i class="fas fa-phone mr-2"></i>Kontak:</strong>
                                <p class="mb-0">{{ $clinic->nomor_telepon ?? '-' }}</p>
                                <p class="mb-0">{{ $clinic->email ?? '-' }}</p>
                            </div>
                        </div>

                        @if($clinic->hari_operasional)
                        <div class="mb-3">
                            <strong><i class="fas fa-calendar-alt mr-2 text-primary"></i>Hari Operasional:</strong>
                            <p class="mb-1 mt-1">
                                @if(count($clinic->hari_operasional) == 7)
                                    <span class="badge badge-primary px-2 py-1"><i class="fas fa-check-double mr-1"></i> Setiap Hari (Senin - Minggu)</span>
                                @else
                                    @foreach($clinic->hari_operasional as $hari)
                                        <span class="badge badge-info px-2 py-1">{{ ucfirst($hari) }}</span>
                                    @endforeach
                                @endif
                            </p>
                            @if($clinic->jam_buka && $clinic->jam_tutup)
                            <p class="mb-0 mt-2">
                                <strong><i class="far fa-clock mr-1 text-primary"></i>Jam Operasional:</strong> 
                                @if(($clinic->jam_buka == '00:00:00' || $clinic->jam_buka == '00:00') && ($clinic->jam_tutup == '23:59:00' || $clinic->jam_tutup == '23:59'))
                                    <span class="badge badge-success px-2 py-1 ml-1"><i class="fas fa-bolt text-warning mr-1"></i> Buka 24 Jam Nonstop</span>
                                @else
                                    <span class="badge badge-light border px-2 py-1 ml-1 font-weight-bold">{{ date('H:i', strtotime($clinic->jam_buka)) }} - {{ date('H:i', strtotime($clinic->jam_tutup)) }}</span>
                                @endif
                            </p>
                            @endif
                        </div>
                        @endif

                        <hr>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="info-box">
                                    <span class="info-box-icon bg-info"><i class="fas fa-user-md"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Total Dokter</span>
                                        <span class="info-box-number">{{ $clinic->doctors->count() }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-box">
                                    <span class="info-box-icon bg-success"><i class="fas fa-heartbeat"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Total Layanan</span>
                                        <span class="info-box-number">{{ $clinic->services->count() }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-box">
                                    <span class="info-box-icon bg-warning"><i class="fas fa-calendar-check"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Total Booking</span>
                                        <span class="info-box-number">{{ $clinic->bookings->count() }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

