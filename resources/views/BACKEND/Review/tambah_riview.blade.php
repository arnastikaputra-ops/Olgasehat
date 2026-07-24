@extends('backend.layout.admin')

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid text-center">
            <h2>Tambah Review</h2>
        </div>
    </section>

    <!-- Main Content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">

                        <form action="{{ route('review.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="card-body">
                                {{-- Notifikasi error --}}
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <strong>Periksa kembali inputan anda!</strong>
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <div class="form-group">
                                    <label>Nama</label>
                                    <input type="text" name="nama" class="form-control" placeholder="Masukkan nama" required>
                                </div>

                                <div class="form-group">
                                    <label>Ulasan</label>
                                    <textarea name="ulasan" class="form-control" rows="3" placeholder="Tulis ulasan" required></textarea>
                                </div>

                                <div class="form-group">
                                    <label>Company</label>
                                    <input type="text" name="company" class="form-control" placeholder="Masukkan nama perusahaan">
                                </div>

                                <div class="form-group">
                                    <label>Foto</label>
                                    <input type="file" name="foto" class="form-control" accept="image/*">
                                    <small class="form-text text-muted">Format: JPG, PNG, GIF. Maksimal 2MB.</small>
                                </div>

                                <div class="form-group">
                                    <label>Rating</label>
                                    <select name="rate" class="form-control" required>
                                        <option value="">-- Pilih Rating --</option>
                                        <option value="1">1 ⭐</option>
                                        <option value="2">2 ⭐⭐</option>
                                        <option value="3">3 ⭐⭐⭐</option>
                                        <option value="4">4 ⭐⭐⭐⭐</option>
                                        <option value="5">5 ⭐⭐⭐⭐⭐</option>
                                    </select>
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-success">Simpan</button>
                                <a href="{{ route('review.index') }}" class="btn btn-secondary">Kembali</a>
                            </div>
                        </form>
                        
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
