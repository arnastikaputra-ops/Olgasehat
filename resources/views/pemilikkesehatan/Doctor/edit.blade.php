@extends('pemilikkesehatan.Layout.pengelolakesehatan')

@section('content')
<div class="content-wrapper" style="background: #f4f8ff; min-height: 100vh;">
    <div class="content-header border-0 pb-0">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h1 class="page-title mb-1" style="font-weight: 700; color: #1b2b5a;">Edit Dokter</h1>
                    <p class="text-muted mb-0">Edit informasi dokter</p>
                </div>
                <ol class="breadcrumb float-md-right mt-2 mt-md-0">
                    <li class="breadcrumb-item"><a href="{{ route('pengelola.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('pengelola.doctors.index') }}">Dokter</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="content pt-3">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                        <div class="card-header" style="background: white; border-radius: 20px 20px 0 0;">
                            <h3 class="card-title mb-0" style="font-weight: 700; color: #1b2b5a;">Form Edit Dokter</h3>
                        </div>
                    <form action="{{ route('pengelola.doctors.update', $doctor->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label>Klinik <span class="text-danger">*</span></label>
                                        <select name="clinic_id" class="form-control" required>
                                            <option value="">Pilih Klinik</option>
                                            @foreach($clinics as $clinic)
                                                <option value="{{ $clinic->id }}" {{ old('clinic_id', $doctor->clinic_id) == $clinic->id ? 'selected' : '' }}>
                                                    {{ $clinic->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label>Nama Dokter <span class="text-danger">*</span></label>
                                        <input type="text" name="nama" class="form-control" value="{{ old('nama', $doctor->nama) }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <label>Gelar</label>
                                        <input type="text" name="gelar" class="form-control" value="{{ old('gelar', $doctor->gelar) }}" placeholder="dr., drg., Sp.PD">
                                    </div>
                                </div>
                                <div class="col-12 col-md-8">
                                    <div class="form-group">
                                        <label>Spesialisasi <span class="text-danger">*</span></label>
                                        <input type="text" name="spesialisasi" class="form-control" value="{{ old('spesialisasi', $doctor->spesialisasi) }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label>Nomor STR</label>
                                        <input type="text" name="nomor_str" class="form-control" value="{{ old('nomor_str', $doctor->nomor_str) }}">
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label>Pendidikan</label>
                                        <input type="text" name="pendidikan" class="form-control" value="{{ old('pendidikan', $doctor->pendidikan) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Deskripsi</label>
                                <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $doctor->deskripsi) }}</textarea>
                            </div>

                            <div class="form-group">
                                <label>Pengalaman</label>
                                <textarea name="pengalaman" class="form-control" rows="3">{{ old('pengalaman', $doctor->pengalaman) }}</textarea>
                            </div>

                            <div class="form-group">
                                <label>Foto Dokter</label>
                                @if($doctor->foto)
                                <div class="mb-2">
                                    <img src="{{ asset('fotodokter/' . $doctor->foto) }}" alt="Foto" class="img-thumbnail" style="max-height: 100px;">
                                </div>
                                @endif
                                <input type="file" name="foto" class="form-control-file" accept="image/*">
                                <small class="text-muted">Kosongkan jika tidak ingin mengubah</small>
                            </div>
                        </div>
                        <div class="card-footer" style="background: white; border-radius: 0 0 20px 20px;">
                            <div class="d-flex justify-content-end">
                                <a href="{{ route('pengelola.doctors.index') }}" class="btn btn-light mr-3" style="border-radius: 10px;">
                                    <i class="fas fa-times"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-primary" style="background: #28a745; border-color: #28a745; border-radius: 10px;">
                                    <i class="fas fa-save"></i> Update
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

