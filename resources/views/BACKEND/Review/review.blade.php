@extends('Backend.Layout.admin')

@section('content')
<div class="content-wrapper review-page">
  <section class="content pt-4">
    <div class="container-fluid">
      <div class="card shadow-sm border-0">
        <div class="card-header bg-white">
          <h3 class="card-title fw-bold text-center fs-4">Data Ulasan Pengguna</h3>
        </div>

        <div class="card-body">
          <a href="/tambah_review" class="btn btn-success mb-3">+ Tambah Data</a>

          <div class="table-responsive">
            <table class="table table-bordered align-middle text-center">
              <thead class="table-secondary">
                <tr>
                  <th>No</th>
                  <th>Nama</th>
                  <th>Company</th>
                  <th>Ulasan</th>
                  <th>Rate</th>
                  <th>Foto</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                @php $no = 1; @endphp
                @forelse ($data as $row)
                  <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $row->nama }}</td>
                    <td>{{ $row->company ?? '-' }}</td>
                    <td class="text-start">{{ $row->ulasan }}</td>
                    <td>⭐ {{ $row->rate }}</td>
                    <td>
                      @if($row->foto)
                        <img src="{{ asset('storage/' . $row->foto) }}" alt="Foto" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                      @else
                        -
                      @endif
                    </td>
                    <td>
                      <a href="{{ route('review.edit', $row->id) }}" class="btn btn-info btn-sm text-white">Edit</a>

                      <!-- tombol yang memicu SweetAlert -->
                      <button class="btn btn-danger btn-sm delete" data-id="{{ $row->id }}">Delete</button>

                      <!-- form tersembunyi -->
                      <form id="delete-form-{{ $row->id }}" action="{{ route('review.destroy', $row->id) }}" method="POST" style="display:none;">
                        @csrf
                        @method('DELETE')
                      </form>
                    </td>
                  </tr>
                @empty
                  <tr><td colspan="7" class="text-center text-muted py-4">Belum ada ulasan pengguna</td></tr>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  // SweetAlert konfirmasi delete
  document.querySelectorAll('.delete').forEach(function(btn){
    btn.addEventListener('click', function(e){
      e.preventDefault();
      const id = this.dataset.id;
      Swal.fire({
        title: 'Yakin ingin menghapus?',
        text: "Data ini tidak bisa dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          document.getElementById('delete-form-' + id).submit();
        }
      });
    });
  });

  // toastr feedback (pastikan toastr.js sudah include di layout)
  @if(session('delete'))
    toastr.success("{{ session('delete') }}");
  @endif
  @if(session('error'))
    toastr.error("{{ session('error') }}");
  @endif
});
</script>
@endpush
