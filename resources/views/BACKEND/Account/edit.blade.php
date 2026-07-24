@extends('BACKEND.Layout.admin')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                </div>
            </div>
        </div>
    </div>    

    <h1 class="text-center mb-4">Edit Akun</h1>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-8">
                <div class="card">
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="/updateacc/{{ $data->id }}" method="POST">
                            @csrf
                            
                            {{-- Nama --}}
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama</label>
                                <input type="text" id="name" name="name" class="form-control" 
                                       value="{{ old('name', $data->name) }}" required>
                            </div>

                            {{-- Email --}}
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" id="email" name="email" class="form-control" 
                                       value="{{ old('email', $data->email) }}" required>
                            </div>

                            {{-- Role --}}
                            <div class="mb-3">
                                <label for="role" class="form-label">Role Akun</label>
                                <select name="role" id="role" class="form-control" required>
                                    <option value="superadmin" {{ old('role', $data->role) == 'superadmin' ? 'selected' : '' }}>Super Admin</option>
                                    <option value="pemiliklapangan" {{ old('role', $data->role) == 'pemiliklapangan' ? 'selected' : '' }}>Pemilik Lapangan / Venue</option>
                                    <option value="pengelolakesehatan" {{ old('role', $data->role) == 'pengelolakesehatan' ? 'selected' : '' }}>Pengelola Kesehatan / Klinik</option>
                                    <option value="user" {{ old('role', $data->role) == 'user' ? 'selected' : '' }}>User Pengguna</option>
                                </select>
                            </div>

                            {{-- Password (Opsional) --}}
                            <div class="mb-3">
                                <label for="password" class="form-label">Password Baru (Opsional)</label>
                                <input type="password" id="password" name="password" class="form-control" 
                                       placeholder="Biarkan kosong jika tidak ingin mengganti password">
                                <small class="text-muted">* Isi hanya jika Anda ingin memperbarui password user ini.</small>
                            </div>

                            {{-- Tombol Submit --}}
                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('akun') }}" class="btn btn-secondary">Kembali</a>
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
