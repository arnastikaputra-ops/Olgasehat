# Dokumentasi Refactoring Layout Unified

## ✅ Yang Sudah Dilakukan

### 1. Layout Unified Dibuat
- **File**: `resources/views/layouts/app.blade.php`
- Layout baru yang menggabungkan `FRONTEND/layout/frontend.blade.php` dan `user/layout/frontenduser.blade.php`
- Menggunakan conditional rendering berdasarkan `Auth::check()` dan `Auth::user()->role === 'user'`
- Header menampilkan:
  - **Guest**: Login/Daftar buttons
  - **User**: Avatar dropdown dengan menu user
- Navigation links otomatis menyesuaikan:
  - Guest: `/venue`, `/healthy`, `/community`, `/blog-news`
  - User: `/venueuser`, `/healthyuser`, `/communityuser`, `/bloguser_news` (akan di-redirect ke route utama)

### 2. Routes Diupdate
- Route `/` sekarang handle conditional logic untuk guest dan user
- Route duplikat di-redirect ke route utama:
  - `/homeuser` → `/`
  - `/venueuser` → `/venue`
  - `/venueuser_detail/{id}` → `/venue-detail/{id}`
  - `/service-detail-user` → `/service-detail`
  - `/communityuser` → `/community`
  - `/communityuser_detail/{id}` → `/community-detail/{id}`
  - `/bloguser_news` → `/blog-news`
  - `/bloguser_detail/{id}` → `/blog-news-detail/{id}`
  - `/healthyuser` → `/healthy`
  - `/tentang_user` → `/tentang`
  - `/membership-user-detail` → `/membership-detail`
  - `/venue_management_user` → `/venue-management`

### 3. View Home Diupdate
- `resources/views/FRONTEND/home.blade.php` sekarang menggunakan `@extends('layouts.app')`
- Menambahkan section activities yang hanya muncul jika user sudah login
- Route `/` sekarang pass `$activities` jika user login

## 📋 Yang Perlu Dilakukan

### 1. Update Semua View Files
Gunakan search & replace untuk mengubah:
- `@extends('FRONTEND.layout.frontend')` → `@extends('layouts.app')`
- `@extends('user.layout.frontenduser')` → `@extends('layouts.app')`

**File yang perlu diupdate:**
- `resources/views/FRONTEND/*.blade.php` (15 files)
- `resources/views/user/*.blade.php` (13 files)

### 2. Update Controller Methods
Beberapa controller method perlu diupdate untuk handle conditional logic:

**ActivityController:**
- `index()` - gabungkan logic `index()` dan `indexUser()`
- `showDetail()` - gabungkan logic `showDetail()` dan `showUser()`

**BeritaController:**
- `index()` - gabungkan logic `index()` dan `indexUser()`
- `show()` - gabungkan logic `show()` dan `showUser()`

**VenueFrontendController:**
- `index()` - sudah bisa digunakan untuk guest dan user
- `show()` - sudah bisa digunakan untuk guest dan user

### 3. Update View untuk Conditional Logic
Beberapa view perlu conditional logic:

**FRONTEND/healthy.blade.php:**
- Update link ke `/service-detail` (bukan `/service-detail-user`)

**FRONTEND/service_detail.blade.php:**
- Update untuk handle guest dan user
- Jika user login, bisa langsung booking
- Jika guest, redirect ke login

**FRONTEND/community.blade.php:**
- Update link ke `/community-detail/{id}` (bukan `/communityuser_detail/{id}`)

**FRONTEND/blog-news.blade.php:**
- Update link ke `/blog-news-detail/{id}` (bukan `/bloguser_detail/{id}`)

### 4. Hapus File yang Tidak Digunakan

**Layout Lama (bisa dihapus setelah semua view diupdate):**
- `resources/views/FRONTEND/layout/frontend.blade.php`
- `resources/views/user/layout/frontenduser.blade.php`

**View Duplikat (bisa dihapus setelah route redirect bekerja):**
- `resources/views/user/homeuser.blade.php` (sudah digabung ke `FRONTEND/home.blade.php`)
- `resources/views/user/venueuser.blade.php` (gunakan `FRONTEND/venue.blade.php`)
- `resources/views/user/venueuser_detail.blade.php` (gunakan `FRONTEND/venue_detail.blade.php`)
- `resources/views/user/service_detail_user.blade.php` (gunakan `FRONTEND/service_detail.blade.php`)
- `resources/views/user/communityuser.blade.php` (gunakan `FRONTEND/community.blade.php`)
- `resources/views/user/communityuser_detail.blade.php` (gunakan `FRONTEND/community_detail.blade.php`)
- `resources/views/user/bloguser_news.blade.php` (gunakan `FRONTEND/blog-news.blade.php`)
- `resources/views/user/bloguser_detail.blade.php` (gunakan `FRONTEND/blog&news_detail.blade.php`)
- `resources/views/user/healthyuser.blade.php` (gunakan `FRONTEND/healthy.blade.php`)
- `resources/views/user/tentang_user.blade.php` (gunakan `FRONTEND/tentang.blade.php`)
- `resources/views/user/membershipuser_detail.blade.php` (gunakan `FRONTEND/membership_detail.blade.php`)
- `resources/views/user/venue_management_user.blade.php` (gunakan `FRONTEND/venue_management.blade.php`)

**Catatan:** File seperti `confirmuser.blade.php`, `paymentuser.blade.php`, `success_user.blade.php` mungkin masih diperlukan karena berbeda logic untuk user yang sudah login.

## 🔄 Alur Kerja Setelah Refactoring

1. **Guest (Belum Login):**
   - Akses `/` → melihat home tanpa activities
   - Akses `/venue` → melihat venue
   - Klik "Buat Janji" → redirect ke login
   - Header menampilkan Login/Daftar buttons

2. **User (Sudah Login):**
   - Akses `/` → melihat home dengan activities section
   - Akses `/venue` → melihat venue (sama seperti guest, tapi bisa booking langsung)
   - Klik "Buat Janji" → langsung ke form booking
   - Header menampilkan avatar dropdown dengan menu user

## ⚠️ Catatan Penting

1. **Backward Compatibility:** Route lama di-redirect ke route baru untuk memastikan link lama masih bekerja
2. **Testing:** Pastikan semua fitur bekerja untuk guest dan user setelah refactoring
3. **URL Consistency:** Semua URL sekarang konsisten (tidak ada `/user` suffix)
4. **Conditional Logic:** Gunakan `@if(Auth::check() && Auth::user()->role === 'user')` untuk menampilkan konten khusus user

## 🚀 Next Steps

1. Update semua view files menggunakan search & replace
2. Test semua route untuk guest dan user
3. Update controller methods yang duplikat
4. Hapus file yang tidak digunakan
5. Update dokumentasi API jika ada

