@extends('BACKEND.Layout.admin')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                </div>
            </div>
        </div>
    </div>

    <h1 class="text-center mb-4">Tambah Akun</h1>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-8">
                <div class="card">
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="/insertacc" method="POST">
                            @csrf

                            {{-- Nama --}}
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama</label>
                                <input type="text" id="name" name="name" class="form-control"
                                       value="{{ old('name') }}" required>
                            </div>

                            {{-- Email --}}
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" id="email" name="email" class="form-control"
                                       value="{{ old('email') }}" required>
                            </div>

                            {{-- Role --}}
                            <div class="mb-3">
                                <label for="role" class="form-label">Role Akun</label>
                                <select name="role" id="role" class="form-control" required>
                                    <option value="" disabled selected>-- Pilih Role --</option>
                                    <option value="superadmin" {{ old('role') == 'superadmin' ? 'selected' : '' }}>Super Admin</option>
                                    <option value="pemiliklapangan" {{ old('role') == 'pemiliklapangan' ? 'selected' : '' }}>Pemilik Lapangan / Venue</option>
                                    <option value="pengelolakesehatan" {{ old('role') == 'pengelolakesehatan' ? 'selected' : '' }}>Pengelola Kesehatan / Klinik</option>
                                    <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User Pengguna</option>
                                </select>
                            </div>

                            {{-- Password --}}
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" id="password" name="password" class="form-control"
                                       required>
                            </div>

                            {{-- Konfirmasi Password --}}
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control"
                                       required>
                            </div>

                            {{-- Tombol Submit --}}
                            <div class="d-flex justify-content-end mt-4">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
