@extends('Backend.Layout.admin')

@push('css')
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

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
                    <h1 class="m-0">Tipe Aktivitas</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Tipe Aktivitas</li>
                    </ol>
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
                            <h3 class="card-title">Daftar Tipe Aktivitas</h3>
                        </div>
                        <div class="col-md-6">
                            <div class="row justify-content-end">
                                <div class="col-auto">
                                    <form action="{{ route('activity-types.index') }}" method="GET" class="form-inline">
                                        <div class="input-group">
                                            <input type="search" name="search" class="form-control" placeholder="Cari tipe aktivitas..." value="{{ request('search') }}">
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
                                    <th>Nama</th>
                                    <th>Judul</th>
                                    <th>Icon</th>
                                    <th width="20%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($activityTypes as $type)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $type->name }}</td>
                                        <td>{{ $type->title }}</td>
                                        <td><i class="{{ $type->icon }}"></i> {{ $type->icon }}</td>
                                        <td>
                                            <a href="{{ route('activity-types.edit', $type->id) }}" class="btn btn-info btn-sm">Edit</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Data tidak ditemukan</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<!-- Bootstrap Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
function deleteActivityType(id) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data tipe aktivitas akan dihapus permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Create a form to submit the delete request
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("activity-types.destroy", ":id") }}'.replace(':id', id);

            var csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

            var methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';

            form.appendChild(csrfToken);
            form.appendChild(methodInput);
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>

<!-- Toastr Alerts -->
<script>
  @if (Session::has('success'))
    toastr.success("{{ Session::get('success') }}");
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
