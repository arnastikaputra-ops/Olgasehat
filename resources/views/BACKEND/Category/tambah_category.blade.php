@extends('Backend.Layout.admin')

@push('css')
<style>
    /* Biar isi halaman geser ke kanan saat sidebar muncul */
    .content-wrapper {
        margin-left: 250px; /* samakan dengan lebar sidebar */
        padding: 20px;
        transition: all 0.3s;
    }
    /* Kalau sidebar di-collapse, isi geser */
    .sidebar-collapsed .content-wrapper {
        margin-left: 80px; 
    }
</style>
@endpush

@section('content')
<div class="content-wrapper">
    <div class="container-fluid mt-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h3 class="card-title m-0 text-dark fw-bold">Tambah Kategori</h3>
            </div>

            <div class="card-body">
                {{-- Pesan error --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Form tambah kategori --}}
                <form action="{{ route('category.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="title" class="form-label fw-bold">Nama Kategori</label>
                        <input type="text" 
                               name="title" 
                               id="title" 
                               class="form-control" 
                               placeholder="Masukkan nama kategori"
                               value="{{ old('title') }}" 
                               required>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('category.index') }}" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
