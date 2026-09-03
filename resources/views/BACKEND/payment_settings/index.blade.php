@extends('BACKEND.Layout.admin')

@section('content')
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 font-weight-bold text-dark"><i class="fas fa-university mr-2 text-primary"></i>Pengaturan Rekening & E-Wallet</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Pengaturan Rekening</li>
          </ol>
        </div>
      </div>
    </div>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">

      @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      @endif

      @if($errors->any())
      <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
        <i class="fas fa-exclamation-triangle mr-2"></i><strong>Terjadi kesalahan:</strong>
        <ul class="mb-0 mt-1 pl-3">
          @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
          @endforeach
        </ul>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      @endif

      <!-- Info Boxes -->
      <div class="row mb-4">
        <div class="col-md-4 col-sm-6 col-12 mb-3 mb-md-0">
          <div class="info-box shadow-sm mb-0">
            <span class="info-box-icon bg-primary"><i class="fas fa-university"></i></span>
            <div class="info-box-content">
              <span class="info-box-text font-weight-bold">Transfer Bank</span>
              <span class="info-box-number text-lg">{{ $paymentSettings->where('category', 'bank')->count() }} Metode</span>
            </div>
          </div>
        </div>

        <div class="col-md-4 col-sm-6 col-12 mb-3 mb-md-0">
          <div class="info-box shadow-sm mb-0">
            <span class="info-box-icon bg-success"><i class="fas fa-wallet"></i></span>
            <div class="info-box-content">
              <span class="info-box-text font-weight-bold">E-Wallet</span>
              <span class="info-box-number text-lg">{{ $paymentSettings->where('category', 'ewallet')->count() }} Metode</span>
            </div>
          </div>
        </div>

        <div class="col-md-4 col-sm-6 col-12">
          <div class="info-box shadow-sm mb-0">
            <span class="info-box-icon bg-warning text-white"><i class="fas fa-check-circle"></i></span>
            <div class="info-box-content">
              <span class="info-box-text font-weight-bold">Metode Aktif</span>
              <span class="info-box-number text-lg">{{ $paymentSettings->where('is_active', true)->count() }} dari {{ $paymentSettings->count() }}</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Main Card Table -->
      <div class="card card-outline card-primary shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
          <h3 class="card-title font-weight-bold my-1">
            <i class="fas fa-credit-card mr-1 text-primary"></i> Daftar Rekening Pembayaran Website
          </h3>
          <div class="card-tools d-flex align-items-center gap-2 my-1">
            <button type="button" class="btn btn-success btn-sm font-weight-bold shadow-sm mr-2" data-toggle="modal" data-target="#modalTambahRekening">
              <i class="fas fa-plus-circle mr-1"></i> Tambah Rekening / E-Wallet
            </button>
            <form action="{{ route('admin.payment-settings.index') }}" method="GET" class="form-inline">
              <div class="input-group input-group-sm" style="width: 200px;">
                <input type="text" name="search" class="form-control" placeholder="Cari bank/rekening..." value="{{ $search }}">
                <div class="input-group-append">
                  <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i>
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>

        <div class="card-body p-0 table-responsive">
          <table class="table table-hover table-striped mb-0 text-nowrap">
            <thead class="thead-dark">
              <tr>
                <th style="width: 60px;">No</th>
                <th>Kode</th>
                <th>Nama Bank / E-Wallet</th>
                <th>Kategori</th>
                <th>Nomor Rekening / Virtual Account</th>
                <th>Atas Nama (Pemilik)</th>
                <th>Status</th>
                <th class="text-center" style="width: 150px;">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($paymentSettings as $index => $setting)
              <tr>
                <td>{{ $index + 1 }}</td>
                <td><span class="badge badge-secondary px-2 py-1 font-weight-bold">{{ $setting->bank_code }}</span></td>
                <td>
                  <strong class="text-dark">{{ $setting->bank_name }}</strong>
                </td>
                <td>
                  @if($setting->category == 'bank')
                    <span class="badge badge-primary"><i class="fas fa-university mr-1"></i>Bank</span>
                  @else
                    <span class="badge badge-success"><i class="fas fa-wallet mr-1"></i>E-Wallet</span>
                  @endif
                </td>
                <td>
                  <span class="font-weight-bold text-primary font-mono" style="letter-spacing: 0.5px;">{{ $setting->account_number }}</span>
                </td>
                <td>{{ $setting->account_holder }}</td>
                <td>
                  <form action="{{ route('admin.payment-settings.toggle', $setting->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PATCH')
                    @if($setting->is_active)
                      <button type="submit" class="btn btn-xs btn-success font-weight-bold shadow-sm" title="Klik untuk menonaktifkan">
                        <i class="fas fa-check-circle mr-1"></i>Aktif
                      </button>
                    @else
                      <button type="submit" class="btn btn-xs btn-secondary font-weight-bold shadow-sm" title="Klik untuk mengaktifkan">
                        <i class="fas fa-times-circle mr-1"></i>Non-Aktif
                      </button>
                    @endif
                  </form>
                </td>
                <td class="text-center">
                  <button type="button" class="btn btn-sm btn-info font-weight-bold shadow-sm mr-1" data-toggle="modal" data-target="#modalEditRekening{{ $setting->id }}">
                    <i class="fas fa-edit"></i> Edit
                  </button>
                  <form action="{{ route('admin.payment-settings.destroy', $setting->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus rekening {{ $setting->bank_name }} ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger font-weight-bold shadow-sm">
                      <i class="fas fa-trash"></i> Hapus
                    </button>
                  </form>
                </td>
              </tr>

              <!-- Modal Edit Rekening -->
              <div class="modal fade" id="modalEditRekening{{ $setting->id }}" tabindex="-1" role="dialog" aria-labelledby="modalEditRekeningLabel{{ $setting->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                  <div class="modal-content">
                    <div class="modal-header bg-info text-white">
                      <h5 class="modal-title font-weight-bold" id="modalEditRekeningLabel{{ $setting->id }}">
                        <i class="fas fa-edit mr-2"></i>Edit Rekening - {{ $setting->bank_code }}
                      </h5>
                      <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <form action="{{ route('admin.payment-settings.update', $setting->id) }}" method="POST">
                      @csrf
                      @method('PUT')
                      <div class="modal-body">
                        <div class="form-group">
                          <label class="font-weight-bold">Kode Singkat <span class="text-danger">*</span></label>
                          <input type="text" name="bank_code" class="form-control" value="{{ old('bank_code', $setting->bank_code) }}" placeholder="Contoh: BCA, MANDIRI, DANA" required style="text-transform: uppercase;">
                        </div>
                        <div class="form-group">
                          <label class="font-weight-bold">Nama Bank / E-Wallet <span class="text-danger">*</span></label>
                          <input type="text" name="bank_name" class="form-control" value="{{ old('bank_name', $setting->bank_name) }}" placeholder="Contoh: Bank Central Asia (BCA)" required>
                        </div>
                        <div class="form-group">
                          <label class="font-weight-bold">Kategori Pembayaran <span class="text-danger">*</span></label>
                          <select name="category" class="form-control" required>
                            <option value="bank" {{ $setting->category == 'bank' ? 'selected' : '' }}>Transfer Bank</option>
                            <option value="ewallet" {{ $setting->category == 'ewallet' ? 'selected' : '' }}>E-Wallet / Dompet Digital</option>
                          </select>
                        </div>
                        <div class="form-group">
                          <label class="font-weight-bold">Nomor Rekening / Virtual Account <span class="text-danger">*</span></label>
                          <input type="text" name="account_number" class="form-control" value="{{ old('account_number', $setting->account_number) }}" placeholder="Masukkan nomor rekening atau VA" required>
                        </div>
                        <div class="form-group">
                          <label class="font-weight-bold">Atas Nama Pemilik Rekening <span class="text-danger">*</span></label>
                          <input type="text" name="account_holder" class="form-control" value="{{ old('account_holder', $setting->account_holder) }}" placeholder="Contoh: PT OlgaSehat Indonesia" required>
                        </div>
                        <div class="custom-control custom-checkbox mt-2">
                          <input type="checkbox" name="is_active" class="custom-control-input" id="is_active_edit_{{ $setting->id }}" {{ $setting->is_active ? 'checked' : '' }}>
                          <label class="custom-control-label font-weight-bold" for="is_active_edit_{{ $setting->id }}">Aktifkan Metode Pembayaran Ini</label>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-info font-weight-bold"><i class="fas fa-save mr-1"></i>Simpan Perubahan</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
              @empty
              <tr>
                <td colspan="8" class="text-center py-4 text-muted">
                  <i class="fas fa-info-circle fa-2x mb-2 d-block"></i>
                  Belum ada data rekening pembayaran. Silakan tambah rekening baru!
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

<!-- Modal Tambah Rekening -->
<div class="modal fade" id="modalTambahRekening" tabindex="-1" role="dialog" aria-labelledby="modalTambahRekeningLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title font-weight-bold" id="modalTambahRekeningLabel">
          <i class="fas fa-plus-circle mr-2"></i>Tambah Rekening / E-Wallet Pembayaran
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('admin.payment-settings.store') }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="form-group">
            <label class="font-weight-bold">Kode Singkat <span class="text-danger">*</span></label>
            <input type="text" name="bank_code" class="form-control" placeholder="Contoh: BCA, MANDIRI, DANA, GOPAY" required style="text-transform: uppercase;">
            <small class="text-muted">Kode unik identifier bank/e-wallet.</small>
          </div>
          <div class="form-group">
            <label class="font-weight-bold">Nama Bank / E-Wallet <span class="text-danger">*</span></label>
            <input type="text" name="bank_name" class="form-control" placeholder="Contoh: Bank Mandiri / DANA E-Wallet" required>
          </div>
          <div class="form-group">
            <label class="font-weight-bold">Kategori Pembayaran <span class="text-danger">*</span></label>
            <select name="category" class="form-control" required>
              <option value="bank">Transfer Bank</option>
              <option value="ewallet">E-Wallet / Dompet Digital</option>
            </select>
          </div>
          <div class="form-group">
            <label class="font-weight-bold">Nomor Rekening / Virtual Account <span class="text-danger">*</span></label>
            <input type="text" name="account_number" class="form-control" placeholder="Contoh: 88008819203847" required>
          </div>
          <div class="form-group">
            <label class="font-weight-bold">Atas Nama Pemilik Rekening <span class="text-danger">*</span></label>
            <input type="text" name="account_holder" class="form-control" value="PT OlgaSehat Indonesia" placeholder="Contoh: PT OlgaSehat Indonesia" required>
          </div>
          <div class="custom-control custom-checkbox mt-2">
            <input type="checkbox" name="is_active" class="custom-control-input" id="is_active_tambah" checked>
            <label class="custom-control-label font-weight-bold" for="is_active_tambah">Langsung Aktifkan Metode Pembayaran Ini</label>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-success font-weight-bold"><i class="fas fa-plus-circle mr-1"></i>Tambah Rekening</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
