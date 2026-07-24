# STRUKTUR BACKEND LAYANAN KESEHATAN - SIMPEL SEPERTI HELLOKLINIKME

## 📋 DAFTAR ISI
1. [Konsep Sistem](#1-konsep-sistem)
2. [Struktur Database](#2-struktur-database)
3. [Backend/Admin Panel](#3-backendadmin-panel)
4. [Frontend Structure](#4-frontend-structure)
5. [Flow Sistem](#5-flow-sistem)

---

## 1. KONSEP SISTEM

### 1.1 Model HelloKlinikMe
Sistem yang **sederhana** dengan fokus pada:
- **Klinik/Fasilitas Kesehatan** sebagai entitas utama
- **Dokter** yang bekerja di klinik
- **Layanan Kesehatan** yang ditawarkan
- **Booking** yang simpel dan langsung

### 1.2 Tema Fleksibel
Sistem mendukung **2 jenis entitas**:
1. **Klinik** - Fasilitas kesehatan fisik (seperti HelloKlinikMe)
2. **Layanan Kesehatan** - Layanan kesehatan umum (bisa tanpa klinik fisik)

Keduanya menggunakan struktur yang sama, hanya berbeda di:
- Klinik: punya alamat fisik, foto klinik, fasilitas
- Layanan: lebih fokus pada jenis layanan, bisa mobile/home visit

---

## 2. STRUKTUR DATABASE

### 2.1 Tabel Utama

#### A. **clinics** - Tabel Klinik/Fasilitas Kesehatan
```php
Schema::create('clinics', function (Blueprint $table) {
    $table->id();
    $table->string('nama'); // Nama klinik
    $table->enum('tipe', ['klinik', 'layanan'])->default('klinik');
    // tipe: 'klinik' = klinik fisik, 'layanan' = layanan kesehatan umum
    
    // Informasi Dasar
    $table->string('slug')->unique(); // URL friendly
    $table->text('deskripsi')->nullable();
    $table->string('motto')->nullable(); // "MELAYANI DENGAN SENANG HATI"
    
    // Kontak & Lokasi
    $table->string('alamat')->nullable();
    $table->string('kota')->nullable(); // "Kota Jakarta Timur"
    $table->string('provinsi')->nullable(); // "DKI Jakarta"
    $table->string('kode_pos')->nullable();
    $table->string('nomor_telepon')->nullable(); // "087877254639"
    $table->string('email')->nullable();
    $table->string('website')->nullable();
    
    // Operasional
    $table->json('hari_operasional')->nullable(); // ["senin", "selasa", ...]
    $table->time('jam_buka')->nullable();
    $table->time('jam_tutup')->nullable();
    
    // Media
    $table->string('logo')->nullable(); // Logo klinik
    $table->string('foto_utama')->nullable(); // Foto utama klinik
    $table->json('galeri')->nullable(); // Array foto galeri
    
    // Kategori
    $table->string('kategori')->nullable(); // "Klinik Umum", "Klinik Gigi", "Klinik Kecantikan"
    $table->json('layanan_tersedia')->nullable(); // ["Konsultasi", "Medical Check-Up", ...]
    
    // Status & Verifikasi
    $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
    $table->text('alasan_reject')->nullable();
    $table->timestamp('verified_at')->nullable();
    
    // Relasi
    $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
    // user_id = pemilik/admin klinik
    
    $table->timestamps();
});
```

#### B. **doctors** - Tabel Dokter
```php
Schema::create('doctors', function (Blueprint $table) {
    $table->id();
    
    // Informasi Dokter
    $table->string('nama'); // "dr. Kukuh Prasetyo"
    $table->string('gelar')->nullable(); // "dr.", "drg.", "Sp.PD"
    $table->string('spesialisasi'); // "Dokter Umum", "Dokter Gigi"
    $table->text('deskripsi')->nullable();
    $table->string('foto')->nullable();
    
    // Kualifikasi
    $table->string('nomor_str')->nullable(); // Surat Tanda Registrasi
    $table->string('pendidikan')->nullable();
    $table->text('pengalaman')->nullable();
    
    // Relasi
    $table->foreignId('clinic_id')->constrained('clinics')->onDelete('cascade');
    // Satu dokter bisa bekerja di beberapa klinik (many-to-many)
    // Tapi untuk simpel, kita pakai one-to-many dulu
    
    // Status
    $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
    $table->boolean('aktif')->default(true);
    
    $table->timestamps();
});
```

#### C. **doctor_schedules** - Jadwal Dokter
```php
Schema::create('doctor_schedules', function (Blueprint $table) {
    $table->id();
    
    $table->foreignId('doctor_id')->constrained('doctors')->onDelete('cascade');
    $table->foreignId('clinic_id')->constrained('clinics')->onDelete('cascade');
    
    // Jadwal
    $table->enum('hari', ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu']);
    $table->time('jam_mulai'); // "08:00"
    $table->time('jam_selesai'); // "14:00"
    
    // Kuota & Durasi
    $table->integer('durasi_konsultasi')->default(30); // Menit
    $table->integer('kuota_per_hari')->default(20); // Maksimal pasien per hari
    
    $table->boolean('aktif')->default(true);
    
    $table->timestamps();
    
    // Unique: satu dokter tidak bisa double jadwal di hari & waktu yang sama
    $table->unique(['doctor_id', 'clinic_id', 'hari', 'jam_mulai']);
});
```

#### D. **health_services** - Layanan Kesehatan
```php
Schema::create('health_services', function (Blueprint $table) {
    $table->id();
    
    $table->string('nama'); // "Konsultasi Dokter Umum"
    $table->text('deskripsi')->nullable();
    $table->string('kategori'); // "Konsultasi", "Medical Check-Up", "Fisioterapi"
    
    // Harga
    $table->enum('tipe_harga', ['gratis', 'berbayar'])->default('berbayar');
    $table->decimal('harga', 15, 2)->nullable();
    $table->integer('durasi')->default(30); // Menit
    
    // Relasi
    $table->foreignId('clinic_id')->constrained('clinics')->onDelete('cascade');
    $table->foreignId('doctor_id')->nullable()->constrained('doctors')->onDelete('set null');
    // Bisa layanan umum tanpa dokter spesifik
    
    // Status
    $table->boolean('aktif')->default(true);
    
    $table->timestamps();
});
```

#### E. **health_bookings** - Booking Kesehatan
```php
Schema::create('health_bookings', function (Blueprint $table) {
    $table->id();
    
    // Kode Booking
    $table->string('kode_booking')->unique(); // "BK-20250118-001"
    
    // Relasi
    $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
    $table->foreignId('clinic_id')->constrained('clinics')->onDelete('cascade');
    $table->foreignId('doctor_id')->constrained('doctors')->onDelete('cascade');
    $table->foreignId('service_id')->nullable()->constrained('health_services')->onDelete('set null');
    
    // Jadwal
    $table->date('tanggal');
    $table->time('jam');
    $table->integer('durasi')->default(30); // Menit
    
    // Informasi Pasien
    $table->string('nama_pasien');
    $table->string('nomor_telepon');
    $table->string('email')->nullable();
    $table->date('tanggal_lahir')->nullable();
    $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
    $table->text('keluhan')->nullable();
    $table->text('riwayat_penyakit')->nullable();
    $table->text('alergi')->nullable();
    
    // Status
    $table->enum('status', [
        'pending',      // Menunggu konfirmasi
        'confirmed',    // Dikonfirmasi
        'completed',    // Selesai
        'cancelled',    // Dibatalkan
        'no_show'       // Tidak datang
    ])->default('pending');
    
    // Pembayaran
    $table->enum('metode_pembayaran', ['cash', 'bpjs', 'asuransi', 'transfer'])->nullable();
    $table->decimal('total_harga', 15, 2)->nullable();
    $table->enum('status_pembayaran', ['pending', 'paid', 'refunded'])->default('pending');
    
    // Catatan
    $table->text('catatan')->nullable();
    $table->text('catatan_dokter')->nullable();
    
    $table->timestamps();
    
    // Index untuk performa
    $table->index(['clinic_id', 'tanggal', 'status']);
    $table->index(['doctor_id', 'tanggal', 'status']);
});
```

#### F. **clinic_categories** - Kategori Klinik/Layanan
```php
Schema::create('clinic_categories', function (Blueprint $table) {
    $table->id();
    $table->string('nama'); // "Klinik Umum", "Klinik Gigi", "Klinik Kecantikan"
    $table->string('icon')->nullable(); // Icon untuk kategori
    $table->string('slug')->unique();
    $table->text('deskripsi')->nullable();
    $table->integer('urutan')->default(0);
    $table->boolean('aktif')->default(true);
    $table->timestamps();
});
```

### 2.2 Relasi Database

```
clinics (1) -----> (many) doctors
clinics (1) -----> (many) health_services
clinics (1) -----> (many) health_bookings
clinics (many) <----> (many) clinic_categories (pivot table)

doctors (1) -----> (many) doctor_schedules
doctors (1) -----> (many) health_bookings
doctors (1) -----> (many) health_services

health_services (1) -----> (many) health_bookings
```

---

## 3. BACKEND/ADMIN PANEL

### 3.1 Super Admin Dashboard

#### Menu Utama:
1. **Dashboard**
   - Total klinik terdaftar
   - Total dokter terdaftar
   - Total booking hari ini
   - Grafik booking per bulan

2. **Manajemen Klinik**
   - **Daftar Klinik** (`/admin/klinik`)
     - Tabel: Nama, Tipe, Kategori, Status, Aksi
     - Filter: Status, Kategori, Tipe
     - Search: Nama, Alamat
     - Aksi: View, Edit, Approve, Reject, Delete
   
   - **Tambah Klinik** (`/admin/klinik/create`)
     - Form: Nama, Tipe, Kategori, Deskripsi, Kontak, Lokasi, Jam Operasional, Upload Logo/Foto
   
   - **Detail Klinik** (`/admin/klinik/{id}`)
     - Tab: Informasi, Dokter, Layanan, Booking, Galeri

3. **Manajemen Dokter**
   - **Daftar Dokter** (`/admin/dokter`)
     - Tabel: Nama, Spesialisasi, Klinik, Status, Aksi
     - Filter: Klinik, Spesialisasi, Status
   
   - **Tambah Dokter** (`/admin/dokter/create`)
     - Form: Nama, Gelar, Spesialisasi, Klinik, STR, Foto, Deskripsi
   
   - **Jadwal Dokter** (`/admin/dokter/{id}/jadwal`)
     - Kalender jadwal
     - Form tambah jadwal per hari

4. **Manajemen Layanan**
   - **Daftar Layanan** (`/admin/layanan`)
     - Tabel: Nama, Kategori, Klinik, Harga, Status
   
   - **Tambah Layanan** (`/admin/layanan/create`)
     - Form: Nama, Kategori, Klinik, Dokter (opsional), Harga, Durasi

5. **Manajemen Booking**
   - **Daftar Booking** (`/admin/booking`)
     - Tabel: Kode, Pasien, Dokter, Klinik, Tanggal, Status
     - Filter: Status, Tanggal, Klinik
   
   - **Detail Booking** (`/admin/booking/{id}`)
     - Informasi lengkap booking
     - Update status
     - Catatan dokter

6. **Kategori**
   - **Daftar Kategori** (`/admin/kategori-klinik`)
     - Tabel: Nama, Icon, Urutan, Aksi
   
   - **Tambah Kategori** (`/admin/kategori-klinik/create`)
     - Form: Nama, Icon, Deskripsi, Urutan

### 3.2 Klinik Dashboard (Backoffice untuk Klinik)

#### Menu Utama:
1. **Dashboard Klinik**
   - Statistik hari ini
   - Booking pending
   - Grafik kunjungan

2. **Profil Klinik**
   - Edit informasi klinik
   - Upload logo & foto
   - Set jam operasional

3. **Manajemen Dokter**
   - Daftar dokter di klinik
   - Tambah dokter
   - Set jadwal dokter
   - Edit profil dokter

4. **Manajemen Layanan**
   - Daftar layanan
   - Tambah layanan
   - Edit harga & durasi

5. **Manajemen Booking**
   - Daftar booking
   - Konfirmasi booking
   - Update status
   - Input catatan dokter

6. **Jadwal**
   - Kalender jadwal
   - Set jam operasional
   - Block waktu

---

## 4. FRONTEND STRUCTURE

### 4.1 Halaman Utama Layanan Kesehatan

#### A. **List Klinik/Layanan** (`/layanan-kesehatan`)
**Layout:**
```
┌─────────────────────────────────────────┐
│  Header: Logo, Nav, Login              │
├─────────────────────────────────────────┤
│  Filter:                                 │
│  - Search: [___________]                │
│  - Kategori: [Dropdown]                 │
│  - Lokasi: [Dropdown]                    │
│  - Tanggal: [Date Picker]               │
├─────────────────────────────────────────┤
│  Grid Klinik/Layanan:                   │
│  ┌─────┐ ┌─────┐ ┌─────┐               │
│  │Card │ │Card │ │Card │               │
│  └─────┘ └─────┘ └─────┘               │
└─────────────────────────────────────────┘
```

**Card Klinik/Layanan:**
- Foto utama
- Nama klinik
- Kategori (badge)
- Lokasi
- Rating (opsional)
- Tombol "Lihat Detail"

#### B. **Detail Klinik/Layanan** (`/layanan-kesehatan/{slug}`)

**Layout seperti HelloKlinikMe:**
```
┌─────────────────────────────────────────┐
│  Header                                  │
├─────────────────────────────────────────┤
│  ┌───────────┐ ┌───────────┐          │
│  │Foto Utama │ │Logo Klinik│          │
│  │           │ │           │          │
│  │           │ │           │          │
│  └───────────┘ └───────────┘          │
│  ┌─────┐ ┌─────┐ ┌─────┐              │
│  │Foto │ │Foto │ │Foto │              │
│  └─────┘ └─────┘ └─────┘              │
├─────────────────────────────────────────┤
│  Info Klinik:                           │
│  - Nama: Klinik Bersama                 │
│  - Telepon: 087877254639                │
│  - Operasional: Senin-Sabtu             │
├─────────────────────────────────────────┤
│  Tab: [Informasi] [Dokter]             │
├─────────────────────────────────────────┤
│  Tab Content:                            │
│  - Tentang                               │
│  - Jadwal Operasional                   │
│  - Daftar Dokter & Jadwal               │
└─────────────────────────────────────────┘
```

**Tab Informasi:**
- Tentang (motto/deskripsi)
- Jadwal Operasional
- Daftar Dokter dengan jadwal:
  ```
  dr. Kukuh Prasetyo
  Senin, 08:00 - 14:00
  
  drg. RISA HANDAYANI HUTAURUK
  Selasa, 17:00 - 20:00
  ```

**Tab Dokter:**
- Grid card dokter:
  ```
  ┌─────────────────┐
  │ [Icon]          │
  │ dr. Kukuh...    │
  │ [Dokter Umum]   │
  │ [Buat Janji]    │
  └─────────────────┘
  ```

#### C. **Booking Form** (`/layanan-kesehatan/{slug}/booking`)

**Form Booking:**
1. Pilih Dokter (jika ada beberapa)
2. Pilih Tanggal
3. Pilih Waktu (berdasarkan jadwal dokter)
4. Isi Data Pasien:
   - Nama
   - Telepon
   - Email (opsional)
   - Tanggal Lahir (opsional)
   - Jenis Kelamin (opsional)
   - Keluhan
5. Pilih Layanan (jika ada pilihan)
6. Konfirmasi & Submit

#### D. **Pilih Jenis Klinik** (`/pilih-jenis-klinik`)

**Layout seperti gambar:**
```
┌─────────────────────────────────────────┐
│  [Illustration]                         │
│  Pilih jenis klinik                     │
│  Berbagai pilihan jenis klinik...       │
├─────────────────────────────────────────┤
│  ┌─────────┐ ┌─────────┐ ┌─────────┐  │
│  │[Icon]   │ │[Icon]   │ │[Icon]   │  │
│  │Klinik   │ │Klinik   │ │Klinik   │  │
│  │Umum     │ │Gigi     │ │Kecantikan│ │
│  └─────────┘ └─────────┘ └─────────┘  │
└─────────────────────────────────────────┘
```

---

## 5. FLOW SISTEM

### 5.1 Flow User Booking

```
1. User masuk ke /layanan-kesehatan
   ↓
2. User pilih kategori atau search
   ↓
3. User lihat list klinik/layanan
   ↓
4. User klik "Lihat Detail"
   ↓
5. User lihat detail klinik:
   - Tab Informasi: Info klinik, jadwal dokter
   - Tab Dokter: List dokter dengan tombol "Buat Janji"
   ↓
6. User klik "Buat Janji" atau "Pilih Dokter"
   ↓
7. User isi form booking:
   - Pilih tanggal & waktu
   - Isi data pasien
   - Isi keluhan
   ↓
8. User konfirmasi booking
   ↓
9. Sistem generate kode booking
   ↓
10. Notifikasi ke user & klinik
   ↓
11. Klinik konfirmasi booking
   ↓
12. User mendapat konfirmasi final
```

### 5.2 Flow Admin/Klinik

```
1. Klinik mendaftar
   ↓
2. Admin verifikasi & approve
   ↓
3. Klinik login ke dashboard
   ↓
4. Klinik tambah dokter
   ↓
5. Klinik set jadwal dokter
   ↓
6. Klinik tambah layanan (opsional)
   ↓
7. Klinik terima booking
   ↓
8. Klinik konfirmasi booking
   ↓
9. Setelah kunjungan, update status jadi "completed"
```

---

## 6. IMPLEMENTASI SEDERHANA

### 6.1 Prioritas Fitur (MVP)

**Phase 1 - Basic:**
1. ✅ CRUD Klinik
2. ✅ CRUD Dokter
3. ✅ Set Jadwal Dokter
4. ✅ List & Detail Klinik (Frontend)
5. ✅ Booking System Basic
6. ✅ Konfirmasi Booking

**Phase 2 - Enhancement:**
1. Kategori Klinik
2. Layanan Kesehatan
3. Payment Integration
4. Notifikasi
5. Riwayat Booking

**Phase 3 - Advanced:**
1. EMR (Rekam Medis)
2. Resep Digital
3. Review & Rating
4. Mobile App

### 6.2 Controller Structure

```
App\Http\Controllers\Health\
├── ClinicController.php      // CRUD Klinik
├── DoctorController.php       // CRUD Dokter
├── DoctorScheduleController.php // Jadwal Dokter
├── HealthServiceController.php  // Layanan Kesehatan
├── HealthBookingController.php // Booking
└── ClinicCategoryController.php // Kategori
```

### 6.3 Model Structure

```
App\Models\
├── Clinic.php
├── Doctor.php
├── DoctorSchedule.php
├── HealthService.php
├── HealthBooking.php
└── ClinicCategory.php
```

---

## 7. KESIMPULAN

Sistem ini dirancang **sederhana** seperti HelloKlinikMe dengan:
- ✅ Struktur database yang jelas
- ✅ Backend yang mudah digunakan
- ✅ Frontend yang user-friendly
- ✅ Flow yang simpel dan langsung

**Keunggulan:**
- Fleksibel: Support klinik fisik & layanan kesehatan umum
- Scalable: Bisa dikembangkan bertahap
- Simple: Tidak terlalu kompleks untuk MVP

**Siap untuk diimplementasikan!** 🚀

