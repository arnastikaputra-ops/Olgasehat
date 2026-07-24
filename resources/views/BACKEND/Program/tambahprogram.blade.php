@extends('backend.layout.admin')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
          </div>
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <h1 class="text-center mb-4">Tambah Program</h1>
    <div class="container">
        <div class="row justify-content-center">
          <div class="col-8">
            <div class="card">
              <div class="card-body">
                <form action="/insertprogram" method="post">
                  @csrf
                <div class="mb-3">
                    <label for="judulProgram" class="form-label">Judul</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                </div>

                <div class="mb-3">
                    <label for="icon" class="form-label">Icon (Font Awesome Class)</label>
                    <input type="text" name="icon" class="form-control" value="{{ old('icon') }}" placeholder="Contoh: fas fa-handshake" required>
                    <small class="form-text text-muted">Gunakan class Font Awesome, contoh: fas fa-handshake, fas fa-shield-alt, fas fa-users, fas fa-calendar-alt</small>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Deskripsi</label>
                    <textarea name="description" class="form-control" rows="4" placeholder="Masukkan deskripsi program" required>{{ old('description') }}</textarea>
                </div>
                
                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-primary">Save Change</button>
                </div>
                </form>
              </div>
             </div>
          </div>
        </div>
    </div>
</div>
@endsection