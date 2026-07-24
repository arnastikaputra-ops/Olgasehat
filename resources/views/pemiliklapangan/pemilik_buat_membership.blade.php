@extends('pemiliklapangan.Layout.ownervenue')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Buat Paket Membership</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/pemiliklapangan/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item active">Buat Membership</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-warning">
                        <div class="card-header">
                            <h3 class="card-title">Detail Paket Membership</h3>
                        </div>
                        <form>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nama">Nama Paket Membership <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="nama" name="nama" placeholder="Contoh: GOLD PASS Bulanan" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="lokasi">Lokasi Lapangan <span class="text-danger">*</span></label>
                                            <select class="form-control" id="lokasi" name="lokasi" required>
                                                <option value="">-- Pilih Lapangan Terdaftar Anda --</option>
                                                <option value="Lapangan A - Denpasar">Lapangan A - Denpasar</option>
                                                <option value="Bayu Gym - Kuta">Bayu Gym - Kuta</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="harga">Harga (Rp) <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" id="harga" name="harga" placeholder="245000" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="durasi">Durasi Paket <span class="text-danger">*</span></label>
                                            <select class="form-control" id="durasi" name="durasi" required>
                                                <option value="">Pilih Durasi</option>
                                                <option>1 Minggu</option>
                                                <option>1 Bulan</option>
                                                <option>3 Bulan</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="fasilitas">Fasilitas & Keuntungan</label>
                                    <textarea class="form-control" id="fasilitas" name="fasilitas" rows="3" placeholder="Tuliskan semua keuntungan yang didapat member, cth: akses malam, diskon sewa lapangan, free sparing"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="banner">Upload Banner Kartu (Opsional)</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="banner" name="banner" accept="image/*">
                                            <label class="custom-file-label" for="banner">Pilih file</label>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted">Format: JPG, PNG, GIF. Maksimal 2MB</small>
                                </div>
                                <div class="form-group">
                                    <h4>Preview Paket</h4>
                                    <div class="card bg-warning">
                                        <div class="card-body">
                                            <h5 id="preview-title">GOLD PASS Bulanan</h5>
                                            <p class="mb-1" id="preview-lokasi">Lapangan A - Denpasar</p>
                                            <p class="mb-1" id="preview-fasilitas">Fasilitas: akses malam · diskon sewa · free sparing</p>
                                            <h4 class="text-bold" id="preview-harga">Rp 245.000 / Bulan</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-save"></i> Simpan Membership
                                </button>
                                <a href="/pemiliklapangan/dashboard" class="btn btn-secondary ml-2">
                                    <i class="fas fa-times"></i> Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const namaInput = document.getElementById('nama');
    const lokasiSelect = document.getElementById('lokasi');
    const hargaInput = document.getElementById('harga');
    const durasiSelect = document.getElementById('durasi');
    const fasilitasTextarea = document.getElementById('fasilitas');

    function updatePreview() {
        const nama = namaInput.value || 'GOLD PASS Bulanan';
        const lokasi = lokasiSelect.value || 'Lapangan A - Denpasar';
        const harga = hargaInput.value ? Number(hargaInput.value).toLocaleString('id-ID') : '0';
        const durasi = durasiSelect.value || 'Bulan';
        const fasilitas = fasilitasTextarea.value || 'akses malam · diskon sewa · free sparing';

        document.getElementById('preview-title').textContent = nama;
        document.getElementById('preview-lokasi').textContent = lokasi;
        document.getElementById('preview-harga').textContent = `Rp ${harga} / ${durasi.replace('1 ', '')}`;
        document.getElementById('preview-fasilitas').textContent = 'Fasilitas: ' + fasilitas;
    }

    [namaInput, lokasiSelect, hargaInput, durasiSelect, fasilitasTextarea].forEach(element => {
        element.addEventListener('input', updatePreview);
        element.addEventListener('change', updatePreview);
    });

    updatePreview();

    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            window.location.href = '/pemiliklapangan/dashboard?success_membership=1';
        });
    }
});
</script>
@endsection
