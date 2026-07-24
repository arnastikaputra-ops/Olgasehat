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
                    <h1 class="m-0">Data Image Galeri @if(isset($kategoriLabel)) - {{ $kategoriLabel }} @endif</h1>
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
                            @if(isset($kategori))
                                <a href="/tambahgaleri/{{ $kategori }}" class="btn btn-success">
                                    <i class="fas fa-plus"></i> Tambah Data
                                </a>
                            @else
                                <a href="/tambahgaleri" class="btn btn-success">
                                    <i class="fas fa-plus"></i> Tambah Data
                                </a>
                            @endif
                        </div>
                        @if(!isset($kategori))
                        <div class="col-md-6">
                            <form action="/galeri" method="GET" class="form-inline">
                                <div class="row">
                                    <div class="col-md-5">
                                        <select name="kategori" class="form-control">
                                            <option value="">-- Semua Kategori --</option>
                                            <option value="home_banner" {{ request('kategori') == 'home_banner' ? 'selected' : '' }}>Home Banner</option>
                                            <option value="lapangan_banner" {{ request('kategori') == 'lapangan_banner' ? 'selected' : '' }}>Lapangan Banner</option>
                                            <option value="kesehatan_banner" {{ request('kategori') == 'kesehatan_banner' ? 'selected' : '' }}>Kesehatan Banner</option>
                                        </select>
                                    </div>
                                    <div class="col-md-5">
                                        <input type="search" name="search" class="form-control" placeholder="Cari..." value="{{ request('search') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search"></i> Filter
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        @else
                        <div class="col-md-6">
                            <form action="/galeri/{{ $kategori == 'home_banner' ? 'home-banner' : ($kategori == 'lapangan_banner' ? 'lapangan-banner' : 'kesehatan-banner') }}" method="GET" class="form-inline">
                                <div class="row">
                                    <div class="col-md-10">
                                        <input type="search" name="search" class="form-control" placeholder="Cari..." value="{{ request('search') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search"></i> Cari
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="20%">Gambar</th>
                                    @if(!isset($kategori))
                                    <th width="20%">Kategori</th>
                                    @endif
                                    <th width="10%">Urutan</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                              @foreach ($data as $index => $row)
                                <tr>  
                                    <td>{{ $index + $data->firstItem() }}</td>
                                    <td>
                                        <img src="{{ asset('fotogaleri/'.$row->foto) }}" alt="" class="img-thumbnail" style="max-height: 60px;">
                                    </td>
                                    @if(!isset($kategori))
                                    <td>
                                        @if($row->kategori == 'home_banner')
                                            <span class="badge badge-primary">Home Banner</span>
                                        @elseif($row->kategori == 'lapangan_banner')
                                            <span class="badge badge-success">Lapangan Banner</span>
                                        @elseif($row->kategori == 'kesehatan_banner')
                                            <span class="badge badge-info">Kesehatan Banner</span>
                                        @else
                                            <span class="badge badge-secondary">-</span>
                                        @endif
                                    </td>
                                    @endif
                                    <td>{{ $row->urutan ?? 0 }}</td>
                                    <td>
                                        <a href="/tampilkangaleri/{{ $row->id }}" type="button" class="btn btn-info btn-sm">Edit</a>
                                        <a href="/deletegaleri/{{ $row->id }}" class="btn btn-danger btn-sm delete">Delete</a>
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