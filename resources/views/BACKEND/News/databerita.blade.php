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
                    <h1 class="m-0">Data Berita</h1>
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
                            <a href="/tambahdata" class="btn btn-success">
                                <i class="fas fa-plus"></i> Tambah Data
                            </a>
                        </div>
                        <div class="col-md-6">
                            <div class="row justify-content-end">
                                <div class="col-auto">
                                    <form action="/newss" method="GET" class="form-inline">
                                        <div class="input-group">
                                            <input type="search" name="search" class="form-control" placeholder="Cari berita...">
                                            <div class="input-group-append">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-auto">
                                    <a href="/exportpdf" class="btn btn-info">
                                        <i class="fas fa-file-pdf"></i> Export PDF
                                    </a>
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
                                    <th width="15%">Gambar</th>
                                    <th>Kontributor</th>
                                    <th>Judul</th>
                                    <th>Excerpt</th>
                                    <th width="15%">Tanggal</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                               @foreach ($data as $index => $row)
                                <tr>
                                    <td>{{ $index + $data->firstItem() }}</td>
                                    <td>
                                        <img src="{{ asset('fotoberita/'.$row->foto) }}" alt="{{ $row->title }}" class="img-thumbnail" style="max-height: 60px;">
                                    </td>
                                    <td>{{ $row->name }}</td>
                                    <td>{{ $row->title }}</td>
                                    <td>{{ $row->excerpt }}</td>
                                    <td>{{ \Carbon\Carbon::parse($row->date)->format('d M Y') }}</td>
                                    <td>
                                        <a href="/tampilkandata/{{ $row->id }}" type="button" class="btn btn-info">Edit</a>
                                        <a href="/deletenews/{{ $row->id }}" class="btn btn-danger btn-sm delete">Delete</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                </table>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <p>
                                Showing {{ $data->firstItem() }} to {{ $data->lastItem() }} of {{ $data->total() }} entries
                            </p>
                        </div>
                        <div class="col-md-6">
                            <nav aria-label="Page navigation">
                                <ul class="pagination justify-content-end">
                                    @if ($data->onFirstPage())
                                        <li class="page-item disabled"><span class="page-link">Previous</span></li>
                                    @else
                                        <li class="page-item"><a class="page-link" href="{{ $data->previousPageUrl() }}">Previous</a></li>
                                    @endif

                                    @for ($page = 1; $page <= $data->lastPage(); $page++)
                                        <li class="page-item {{ $data->currentPage() == $page ? 'active' : '' }}">
                                            <a class="page-link" href="{{ $data->url($page) }}">{{ $page }}</a>
                                        </li>
                                    @endfor

                                    @if ($data->hasMorePages())
                                        <li class="page-item"><a class="page-link" href="{{ $data->nextPageUrl() }}">Next</a></li>
                                    @else
                                        <li class="page-item disabled"><span class="page-link">Next</span></li>
                                    @endif
                                </ul>
                            </nav>
                        </div>
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