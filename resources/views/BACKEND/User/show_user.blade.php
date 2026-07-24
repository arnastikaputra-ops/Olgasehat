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
                    <h1 class="m-0">Detail User</h1>
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
                            <a href="{{ route('admin.users.list') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                        <div class="col-md-6 text-end">
                            <form action="{{ route('admin.users.delete', $user->id) }}" 
                                  method="POST" 
                                  class="d-inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash"></i> Hapus User
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th style="width: 200px">ID</th>
                                        <td>{{ $user->id }}</td>
                                    </tr>
                                    <tr>
                                        <th>Nama</th>
                                        <td>{{ $user->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Email</th>
                                        <td>{{ $user->email }}</td>
                                    </tr>
                                    <tr>
                                        <th>Role</th>
                                        <td>
                                            <span class="badge bg-primary">{{ ucfirst($user->role) }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>
                                            @if($user->status == 'approved')
                                                <span class="badge bg-success">Approved</span>
                                            @elseif($user->status == 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif($user->status == 'rejected')
                                                <span class="badge bg-danger">Rejected</span>
                                            @else
                                                <span class="badge bg-secondary">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Foto Profil</th>
                                        <td>
                                            @if($user->image)
                                                <img src="{{ asset('storage/' . $user->image) }}" 
                                                     alt="{{ $user->name }}" 
                                                     class="img-thumbnail" 
                                                     style="max-width: 200px; max-height: 200px;">
                                            @else
                                                <span class="text-muted">Tidak ada foto</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Dibuat</th>
                                        <td>{{ $user->created_at->format('d M Y, H:i:s') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Update Terakhir</th>
                                        <td>{{ $user->updated_at->format('d M Y, H:i:s') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
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
                text: "Data user ini tidak bisa dikembalikan!",
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
</script>
@endpush

