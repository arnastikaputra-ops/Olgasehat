# Cara Akses dan Menggunakan Sistem Pengelola Kesehatan

## 📋 Status Implementasi

**Fitur yang sudah diimplementasikan:**
✅ Dashboard dengan statistik
✅ Manajemen Klinik (CRUD)
✅ Manajemen Dokter (CRUD)
✅ Manajemen Jadwal Dokter (CRUD)
✅ Manajemen Layanan (CRUD)
✅ Manajemen Booking (View & Update Status)

**Semua fitur sudah terintegrasi dengan layout yang ada di `/pengelolakesehatan`**

---

## 🚀 Cara Akses

### 1. Login sebagai Pengelola Kesehatan

1. Pastikan user memiliki role `pengelolakesehatan`
2. Login ke sistem
3. Akses URL: `http://127.0.0.1:8000/pengelolakesehatan`
   - Atau: `http://127.0.0.1:8000/pengelolakesehatan/dashboard`

### 2. Menu yang Tersedia

Setelah login, di sidebar kiri akan muncul menu:
- **Dashboard** - Statistik & overview
- **Klinik** - Kelola klinik/layanan kesehatan
- **Dokter** - Kelola dokter
- **Jadwal Dokter** - Kelola jadwal praktek
- **Layanan** - Kelola layanan kesehatan
- **Booking** - Lihat & kelola booking pasien
- **Analytics** - (jika sudah dibuat)
- **Pengaturan** - Pengaturan akun

---

## 📝 Cara Menggunakan

### 1. Dashboard

**URL:** `/pengelolakesehatan/dashboard`

**Fitur:**
- Menampilkan statistik:
  - Total Klinik
  - Total Dokter
  - Booking Pending
  - Booking Hari Ini
- Menampilkan booking hari ini
- Menampilkan booking pending terbaru

---

### 2. Kelola Klinik

**URL:** `/pengelolakesehatan/klinik`

**Fitur:**
- **List Klinik** - Lihat semua klinik yang dimiliki
- **Tambah Klinik** - Klik tombol "Tambah Klinik"
- **Edit Klinik** - Klik tombol "Edit" pada klinik
- **Detail Klinik** - Klik tombol "Detail" pada klinik

**Form Tambah/Edit Klinik:**
- Nama Klinik (wajib)
- Tipe (Klinik / Layanan Kesehatan)
- Kategori
- Motto
- Deskripsi
- Alamat, Kota, Provinsi
- Nomor Telepon, Email, Website
- Hari Operasional (checkbox)
- Jam Buka & Jam Tutup
- Logo Klinik (upload)
- Foto Utama (upload)

**Catatan:**
- Klinik yang baru ditambahkan akan berstatus "Pending" dan menunggu verifikasi admin
- Setelah disetujui admin, status akan berubah menjadi "Approved"

---

### 3. Kelola Dokter

**URL:** `/pengelolakesehatan/dokter`

**Fitur:**
- **List Dokter** - Lihat semua dokter di klinik Anda
- **Tambah Dokter** - Klik tombol "Tambah Dokter"
- **Edit Dokter** - Klik tombol "Edit"
- **Hapus Dokter** - Klik tombol "Hapus" (dengan konfirmasi)

**Form Tambah/Edit Dokter:**
- Klinik (pilih dari dropdown)
- Nama Dokter (wajib)
- Gelar (dr., drg., Sp.PD, dll)
- Spesialisasi (wajib)
- Nomor STR
- Pendidikan
- Deskripsi
- Pengalaman
- Foto Dokter (upload)

**Catatan:**
- Dokter yang baru ditambahkan akan berstatus "Pending" dan menunggu verifikasi admin
- Setelah disetujui admin, status akan berubah menjadi "Approved"

---

### 4. Kelola Jadwal Dokter

**URL:** `/pengelolakesehatan/jadwal-dokter`

**Fitur:**
- **List Jadwal** - Lihat semua jadwal dokter
- **Tambah Jadwal** - Klik tombol "Tambah Jadwal"
- **Edit Jadwal** - Klik tombol "Edit"
- **Hapus Jadwal** - Klik tombol "Hapus" (dengan konfirmasi)

**Form Tambah/Edit Jadwal:**
- Dokter (pilih dari dropdown)
- Klinik (pilih dari dropdown)
- Hari (Senin - Minggu)
- Jam Mulai (wajib)
- Jam Selesai (wajib)
- Durasi Konsultasi (menit, default: 30)
- Kuota per Hari (default: 20)

**Catatan:**
- Sistem akan mencegah duplikasi jadwal di hari dan waktu yang sama
- Pastikan dokter sudah disetujui sebelum membuat jadwal

---

### 5. Kelola Layanan

**URL:** `/pengelolakesehatan/layanan`

**Fitur:**
- **List Layanan** - Lihat semua layanan kesehatan
- **Tambah Layanan** - Klik tombol "Tambah Layanan"
- **Edit Layanan** - Klik tombol "Edit"
- **Hapus Layanan** - Klik tombol "Hapus" (dengan konfirmasi)

**Form Tambah/Edit Layanan:**
- Nama Layanan (wajib)
- Kategori (wajib)
- Klinik (pilih dari dropdown, wajib)
- Dokter (opsional - kosongkan jika layanan umum)
- Deskripsi
- Tipe Harga (Gratis / Berbayar)
- Harga (wajib jika berbayar)
- Durasi (menit, default: 30)

**Catatan:**
- Jika memilih "Berbayar", field harga akan menjadi wajib
- Dokter bisa dikosongkan untuk layanan umum (tanpa dokter spesifik)

---

### 6. Kelola Booking

**URL:** `/pengelolakesehatan/booking`

**Fitur:**
- **List Booking** - Lihat semua booking pasien
- **Filter Booking:**
  - Status (Pending, Confirmed, Completed, Cancelled, No Show)
  - Klinik
  - Tanggal
  - Search (Kode, Nama, Telepon)
- **Detail Booking** - Klik tombol "Detail"

**Detail Booking:**
- Informasi booking (kode, status, tanggal, waktu)
- Informasi klinik & dokter
- Informasi pasien (nama, telepon, email, tanggal lahir, jenis kelamin)
- Keluhan, riwayat penyakit, alergi
- Catatan dokter

**Update Status Booking:**
- Pilih status baru
- Tambahkan catatan dokter (opsional)
- Klik "Update Status"

**Status Booking:**
- **Pending** - Menunggu konfirmasi
- **Confirmed** - Sudah dikonfirmasi
- **Completed** - Selesai
- **Cancelled** - Dibatalkan
- **No Show** - Pasien tidak datang

---

## 🔧 Setup Awal

### 1. Jalankan Migration

```bash
php artisan migrate
```

Ini akan membuat tabel:
- `clinic_categories`
- `clinics`
- `doctors`
- `doctor_schedules`
- `health_services`
- `health_bookings`

### 2. Pastikan Folder Upload Ada

Folder sudah dibuat otomatis:
- `public/fotoklinik/` - untuk logo & foto klinik
- `public/fotodokter/` - untuk foto dokter

### 3. Buat User dengan Role Pengelola Kesehatan

Pastikan user memiliki role `pengelolakesehatan` di database.

---

## 📌 Tips Penggunaan

1. **Urutan Setup:**
   - Tambah Klinik dulu
   - Setelah klinik disetujui, tambah Dokter
   - Setelah dokter disetujui, buat Jadwal
   - Tambah Layanan
   - Kelola Booking yang masuk

2. **Verifikasi:**
   - Klinik dan Dokter perlu verifikasi admin terlebih dahulu
   - Setelah disetujui, baru bisa digunakan

3. **Jadwal Dokter:**
   - Pastikan tidak ada duplikasi jadwal
   - Setiap dokter bisa punya beberapa jadwal di hari berbeda

4. **Layanan:**
   - Bisa dibuat tanpa dokter spesifik (layanan umum)
   - Atau dikaitkan dengan dokter tertentu

5. **Booking:**
   - Gunakan filter untuk mencari booking tertentu
   - Update status secara berkala
   - Tambahkan catatan dokter untuk riwayat pasien

---

## 🐛 Troubleshooting

### Error: Route tidak ditemukan
- Pastikan sudah menjalankan `php artisan route:clear`
- Cek apakah route sudah terdaftar: `php artisan route:list | grep pengelola`

### Error: View tidak ditemukan
- Pastikan semua views sudah dibuat di `resources/views/pengelolakesehatan/`

### Error: Model tidak ditemukan
- Pastikan sudah menjalankan `php artisan migrate`
- Cek namespace model di `app/Models/`

### Foto tidak muncul
- Pastikan folder `public/fotoklinik/` dan `public/fotodokter/` ada
- Cek permission folder (harus writable)
- Pastikan path di view sudah benar

---

## 📞 Support

Jika ada masalah atau pertanyaan, silakan hubungi tim development.

