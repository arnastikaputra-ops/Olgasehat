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
                <h3 class="card-title m-0 text-dark fw-bold">Edit About Us</h3>
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

                {{-- Form edit About Us --}}
                <form action="{{ route('about-us.update', $aboutUs->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="title" class="form-label fw-bold">Title</label>
                        <input type="text"
                               name="title"
                               id="title"
                               class="form-control"
                               value="{{ old('title', $aboutUs->title) }}"
                               required>
                    </div>

                    <div class="mb-3">
                        <label for="content" class="form-label fw-bold">Content</label>
                        <textarea name="content"
                                  id="content"
                                  class="form-control"
                                  rows="10"
                                  required>{{ old('content', $aboutUs->content) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="link_youtube" class="form-label fw-bold">Link Youtube</label>
                        <input type="url"
                               name="link_youtube"
                               id="link_youtube"
                               class="form-control"
                               value="{{ old('link_youtube', $aboutUs->link_youtube) }}">
                        <small class="form-text text-muted">Link YouTube untuk ditampilkan (opsional)</small>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('about-us.index') }}" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

