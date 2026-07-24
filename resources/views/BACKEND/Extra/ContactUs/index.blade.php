@extends('Backend.Layout.admin')

@push('css')
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" 
      rel="stylesheet" 
      integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" 
      crossorigin="anonymous">

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
                    <h1 class="m-0">Data Contact Us</h1>
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
                            <a href="{{ route('contact-us.create') }}" class="btn btn-success">
                                <i class="fas fa-plus"></i> Tambah Data
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                {{-- Notifikasi sukses --}}
                @if (session('success'))
                    <script>
                        window.addEventListener('DOMContentLoaded', () => {
                            toastr.success("{{ session('success') }}");
                        });
                    </script>
                @endif

                {{-- Tabel Contact Us --}}
                <div class="table-responsive">
                    <table class="table table-bordered align-middle text-center">
                        <thead class="table-secondary">
                            <tr>
                                <th style="width: 50px">No</th>
                                <th>Title</th>
                                <th>Kontak</th>
                                <th>Type</th>
                                <th style="width: 150px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @forelse ($contactUs as $row)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $row->title }}</td>
                                <td>{{ $row->kontak }}</td>
                                <td>{{ $row->type }}</td>
                                <td>
                                    <a href="{{ route('contact-us.edit', $row->id) }}" 
                                       class="btn btn-info btn-sm text-white">Edit</a>
                                    <form action="{{ route('contact-us.destroy', $row->id) }}" 
                                          method="POST" 
                                          class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    Belum ada data Contact Us
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
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

<script>
    document.querySelectorAll('.delete-form').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: "Data ini tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // Toastr Alerts
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

