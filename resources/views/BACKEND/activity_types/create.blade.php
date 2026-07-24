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

  <body>
    <h1 class="text-center mb-4">Tambah Tipe Aktivitas</h1>
    <div class="container">
        <div class="row justify-content-center">
          <div class="col-8">
            <div class="card">
              <div class="card-body">
                <form action="{{ route('activity-types.store') }}" method="POST">
                  @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Nama</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Masukkan nama tipe aktivitas (contoh: open-class)">
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="title" class="form-label">Judul</label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" placeholder="Masukkan judul tampilan (contoh: Komunitas)">
                    @error('title')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="icon" class="form-label">Icon (FontAwesome)</label>
                    <input type="text" class="form-control @error('icon') is-invalid @enderror" id="icon" name="icon" value="{{ old('icon') }}" placeholder="Masukkan class FontAwesome (contoh: fas fa-users)">
                    @error('icon')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <small class="form-text text-muted">Contoh: fas fa-users, fas fa-calendar-alt, dll. Lihat di <a href="https://fontawesome.com/icons" target="_blank">FontAwesome</a></small>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-primary">Save Change</button>
                    <a href="{{ route('activity-types.index') }}" class="btn btn-secondary ms-2">Kembali</a>
                </div>
                </form>
              </div>
             </div>
          </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
  </body>
</div>
@endsection
