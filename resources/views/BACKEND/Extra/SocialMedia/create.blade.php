@extends('Backend.Layout.admin')

@push('css')
<style>
    .content-wrapper {
        margin-left: 250px;
        padding: 20px;
        transition: all 0.3s;
    }
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
                <h3 class="card-title m-0 text-dark fw-bold">Tambah Social Media</h3>
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

                {{-- Form tambah Social Media --}}
                <form action="{{ route('social-media.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="icon" class="form-label fw-bold">Icon (Font Awesome Class)</label>
                        <input type="text" 
                               name="icon" 
                               id="icon" 
                               class="form-control" 
                               placeholder="Contoh: fab fa-facebook, fab fa-instagram, fab fa-twitter"
                               value="{{ old('icon') }}" 
                               required>
                        <small class="form-text text-muted">Gunakan class Font Awesome, contoh: fab fa-facebook</small>
                    </div>

                    <div class="mb-3">
                        <label for="title" class="form-label fw-bold">Title (Opsional)</label>
                        <input type="text" 
                               name="title" 
                               id="title" 
                               class="form-control" 
                               placeholder="Masukkan title social media (opsional)"
                               value="{{ old('title') }}">
                        <small class="form-text text-muted">Title tidak akan ditampilkan di frontend, hanya untuk referensi admin</small>
                    </div>

                    <div class="mb-3">
                        <label for="url" class="form-label fw-bold">URL</label>
                        <input type="url" 
                               name="url" 
                               id="url" 
                               class="form-control" 
                               placeholder="https://example.com"
                               value="{{ old('url') }}" 
                               required>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('social-media.index') }}" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

