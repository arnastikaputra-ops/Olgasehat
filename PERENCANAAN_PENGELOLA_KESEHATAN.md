# PERENCANAAN PENGELOLA KESEHATAN

## Overview
Membuat sistem registrasi, login, dan backoffice untuk **Pengelola Kesehatan** yang mirip dengan sistem **Pemilik Lapangan** yang sudah ada.

---

## 1. STRUKTUR DATABASE

### 1.1 Update Users Table
- Tambahkan role `pengelolakesehatan` ke tabel users
- Status: `pending`, `approved`, `rejected` (sama seperti pemilik lapangan)

### 1.2 Mitras Table
- Tabel `mitras` sudah ada dan bisa digunakan untuk pengelola kesehatan
- Tambahkan field `tipe_mitra` untuk membedakan: `pemilik_lapangan` atau `pengelola_kesehatan`
- Atau buat tabel terpisah `mitras_kesehatan` (opsional, lebih baik extend mitras)

**Rekomendasi**: Gunakan tabel `mitras` yang sama dengan field `tipe_mitra`

---

## 2. ROUTES YANG DIBUTUHKAN

### 2.1 Public Routes (Tanpa Auth)
```
GET  /regispengelolakesehatan    -> Form registrasi awal
GET  /isidatakesehatan            -> Form isi data lengkap
POST /isidatakesehatan            -> Submit data registrasi
GET  /loginpengelolakesehatan     -> Form login
```

### 2.2 Authenticated Routes (Role: pengelolakesehatan)
```
GET  /pengelolakesehatan/dashboard        -> Dashboard utama
GET  /pengelolakesehatan/analytics        -> Analytics
GET  /pengelolakesehatan/pengaturan       -> Pengaturan akun
```

### 2.3 Super Admin Routes
```
GET  /tempat-sehat                        -> List semua pengelola kesehatan (verifikasi)
GET  /tempat-sehat/{id}                   -> Detail pengelola kesehatan
PUT  /tempat-sehat/{id}/verify            -> Verifikasi pengelola kesehatan
DELETE /tempat-sehat/{id}                 -> Hapus pengelola kesehatan
```

---

## 3. CONTROLLERS

### 3.1 PengelolaKesehatanController
**File**: `app/Http/Controllers/PengelolaKesehatanController.php`

**Methods**:
- `create()` - Tampilkan form isi data
- `store()` - Simpan data registrasi
- `pengaturan()` - Tampilkan halaman pengaturan
- `updatePengaturan()` - Update pengaturan

### 3.2 Update LoginController
- Tambahkan handling untuk role `pengelolakesehatan` di method `loginproses()`
- Redirect ke `/pengelolakesehatan/dashboard` jika status approved

### 3.3 TempatSehatController (Super Admin)
**File**: `app/Http/Controllers/TempatSehatController.php`

**Methods**:
- `index()` - List semua pengelola kesehatan dengan filter status
- `show($id)` - Detail pengelola kesehatan
- `verify($id)` - Verifikasi pengelola kesehatan
- `destroy($id)` - Hapus pengelola kesehatan

---

## 4. VIEWS YANG DIBUTUHKAN

### 4.1 Registrasi & Login
```
resources/views/pemilikkesehatan/
├── regispengelolakesehatan.blade.php    (mirip regispengelola.blade.php)
├── isidatakesehatan.blade.php            (mirip isidata.blade.php)
└── loginpengelolakesehatan.blade.php     (update yang sudah ada)
```

### 4.2 Dashboard & Backoffice
```
resources/views/pemilikkesehatan/
├── Layout/
│   └── pengelolakesehatan.blade.php      (mirip ownervenue.blade.php)
├── Dashboard/
│   └── dashboard.blade.php               (mirip pemiliklapangan/Dashboard/dashboard.blade.php)
├── Analytics/
│   └── index.blade.php                   (mirip pemiliklapangan/Analytics/index.blade.php)
└── Pengaturan/
    └── index.blade.php                   (mirip pemiliklapangan/Pengaturan/index.blade.php)
```

### 4.3 Super Admin Views
```
resources/views/BACKEND/Tempat Sehat/
├── index.blade.php                       (list pengelola kesehatan)
└── detail.blade.php                      (detail pengelola kesehatan)
```

---

## 5. MODEL UPDATES

### 5.1 Update Mitra Model
- Tambahkan scope untuk filter berdasarkan `tipe_mitra`
- Atau buat model terpisah `MitraKesehatan` (opsional)

**Rekomendasi**: Tambahkan field `tipe_mitra` ke tabel mitras dan gunakan model Mitra yang sama

---

## 6. MIDDLEWARE & AUTHENTICATION

### 6.1 Role Middleware
- Pastikan middleware `role:pengelolakesehatan` sudah ada atau buat baru
- Update `app/Http/Middleware/CheckRole.php` jika perlu

### 6.2 Login Flow
1. User daftar → status `pending`
2. Super admin verifikasi di `/tempat-sehat`
3. Status berubah jadi `approved`
4. User bisa login → redirect ke dashboard

---

## 7. FRONTEND UPDATES

### 7.1 Header Navigation
- Update `resources/views/FRONTEND/layout/frontend.blade.php`
- Tambahkan link "Akun Pengelola Kesehatan" dan "Masuk Pengelola Kesehatan"

### 7.2 Homepage
- Link sudah ada di dropdown "Masuk" button

---

## 8. IMPLEMENTASI STEP-BY-STEP

### Phase 1: Database & Model
1. ✅ Buat migration untuk update mitras table (tambah tipe_mitra)
2. ✅ Update Mitra model

### Phase 2: Registration & Login
3. ✅ Buat PengelolaKesehatanController
4. ✅ Buat view registrasi (regispengelolakesehatan.blade.php)
5. ✅ Buat view isi data (isidatakesehatan.blade.php)
6. ✅ Update view login (loginpengelolakesehatan.blade.php)
7. ✅ Update LoginController untuk handle login pengelola kesehatan

### Phase 3: Dashboard & Backoffice
8. ✅ Buat layout pengelolakesehatan.blade.php
9. ✅ Buat dashboard pengelolakesehatan
10. ✅ Buat halaman analytics
11. ✅ Buat halaman pengaturan

### Phase 4: Super Admin
12. ✅ Buat TempatSehatController
13. ✅ Buat view list pengelola kesehatan di /tempat-sehat
14. ✅ Buat view detail pengelola kesehatan

### Phase 5: Routes & Integration
15. ✅ Update routes/web.php
16. ✅ Update frontend header links
17. ✅ Testing end-to-end

---

## 9. PERBEDAAN DENGAN PEMILIK LAPANGAN

### 9.1 Sama
- Flow registrasi dan verifikasi
- Struktur dashboard
- Sistem login/logout
- Halaman pengaturan

### 9.2 Beda (Nanti bisa dikembangkan)
- Tipe venue → Tipe fasilitas kesehatan (Klinik, Rumah Sakit, dll)
- Lapangan → Layanan kesehatan (Konsultasi, Pemeriksaan, dll)
- Jadwal lapangan → Jadwal layanan kesehatan

---

## 10. TESTING CHECKLIST

- [ ] Registrasi pengelola kesehatan berhasil
- [ ] Data masuk ke super admin /tempat-sehat
- [ ] Super admin bisa verifikasi
- [ ] Login setelah verifikasi berhasil
- [ ] Dashboard pengelola kesehatan bisa diakses
- [ ] Halaman pengaturan berfungsi
- [ ] Logout berfungsi

---

## CATATAN PENTING

1. **URL Super Admin**: Data pengelola kesehatan masuk ke `/tempat-sehat` (bukan `/verifikasi-mitra`)
2. **Role**: Gunakan `pengelolakesehatan` sebagai role di users table
3. **Tipe Mitra**: Gunakan field `tipe_mitra` di tabel mitras untuk membedakan
4. **Status**: Sama seperti pemilik lapangan (pending → approved/rejected)

