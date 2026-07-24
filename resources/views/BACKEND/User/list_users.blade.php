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
                    <h1 class="m-0">Data User Registrations</h1>
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
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
                            </a>
                        </div>
                        <div class="col-md-6">
                            <form method="GET" action="{{ route('admin.users.list') }}" class="d-flex">
                                <input type="text" 
                                       name="search" 
                                       class="form-control me-2" 
                                       placeholder="Cari nama atau email..." 
                                       value="{{ $search }}">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                            </form>
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

                @if (session('error'))
                    <script>
                        window.addEventListener('DOMContentLoaded', () => {
                            toastr.error("{{ session('error') }}");
                        });
                    </script>
                @endif

                {{-- Tabel User --}}
                <div class="table-responsive">
                    <table class="table table-bordered align-middle text-center">
                        <thead class="table-secondary">
                            <tr>
                                <th style="width: 50px">No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Dibuat</th>
                                <th style="width: 200px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = ($users->currentPage() - 1) * $users->perPage() + 1; @endphp
                            @forelse ($users as $user)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td class="fw-semibold">{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
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
                                <td>{{ $user->created_at->format('d M Y, H:i') }}</td>
                                <td>
                                    <a href="{{ route('admin.users.show', $user->id) }}" 
                                       class="btn btn-info btn-sm text-white">
                                        <i class="fas fa-eye"></i> Lihat
                                    </a>
                                    <form action="{{ route('admin.users.delete', $user->id) }}" 
                                          method="POST" 
                                          class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    Belum ada user terdaftar
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="d-flex justify-content-center mt-4">
                    {{ $users->links() }}
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

    // Toastr Alerts
    @if (Session::has('success'))
        toastr.success("{{ Session::get('success') }}");
    @endif

    @if (Session::has('error'))
        toastr.error("{{ Session::get('error') }}");
    @endif
</script>
@endpush

