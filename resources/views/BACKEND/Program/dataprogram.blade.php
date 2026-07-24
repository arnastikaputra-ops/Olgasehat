@extends('backend.layout.admin')
@push('css')
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

<!-- Toastr CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
@endpush
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Data Program</h1>
                </div>
            </div>
        </div>
    </div>    
  <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6">
                            <a href="/tambahprogram" class="btn btn-success">
                                <i class="fas fa-plus"></i> Tambah Data
                            </a>
                        </div>
                        <div class="col-md-6">
                            <div class="row justify-content-end">
                                <div class="col-auto">
                                    <form action="/programs" method="GET" class="form-inline">
                                        <div class="input-group">
                                            <input type="search" name="search" class="form-control" placeholder="Cari Program...">
                                            <div class="input-group-append">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="20%">Judul</th>
                                    <th width="15%">Icon</th>
                                    <th width="40%">Deskripsi</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $index => $row)
                                <tr>
                                    <td>{{ $index + $data->firstItem() }}</td>
                                    <td>{{ $row->title }}</td>
                                    <td>
                                        @if($row->icon)
                                            <i class="{{ $row->icon }}"></i> 
                                            <small class="d-block text-muted">{{ $row->icon }}</small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($row->description)
                                            {{ Str::limit($row->description, 50) }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="/tampilkanprogram/{{ $row->id }}" type="button" class="btn btn-info btn-sm">Edit</a>
                                        <a href="/deleteprogram/{{ $row->id }}" class="btn btn-danger btn-sm delete">Delete</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                          
                    </div>
                </div>
            </div>
        </div>
      </div>
    </section>
@endsection

@push('scripts')
<!-- jQuery first -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

<!-- Toastr Alerts -->
<script>
  @if (Session::has('Success'))
    toastr.success("{{ Session::get('Success') }}");
  @endif

  @if (Session::has('update'))
    toastr.success("{{ Session::get('update') }}");
  @endif

  @if (Session::has('delete'))
    toastr.success("{{ Session::get('delete') }}");
  @endif

  @if (Session::has('error'))
    toastr.error("{{ Session::get('error') }}");
  @endif
</script>
@endpush