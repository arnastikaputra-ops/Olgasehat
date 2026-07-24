# Analisis Perbandingan Form Venue dengan Frontend Display

## 📋 Field yang Sudah Ada di Form

### Form `/informasi` (Step 1):
1. ✅ **Logo/Gambar Banner** - `logo` (file upload)
2. ✅ **Nama Venue** - `namavenue` (text)
3. ✅ **Provinsi** - `provinsi` (select)
4. ✅ **Kota** - `kota` (select)
5. ✅ **Cabang Olahraga** - `kategori` (select)

### Form `/detail` (Step 2):
6. ✅ **Video Review** - `video_review` (YouTube URL)
7. ✅ **Detail Venue** - `detail` (rich text editor)
8. ✅ **Aturan Venue** - `aturan` (rich text editor)
9. ✅ **Lokasi** - `lokasi` (Google Maps URL)
10. ✅ **Fasilitas** - `fasilitas` (checkbox multiple, JSON)

---

## 🔍 Field yang Ditampilkan di Frontend Tapi TIDAK ADA di Form

### Di Halaman `venue.blade.php` (Listing):
1. ❌ **Harga Per Sesi** - Ditampilkan sebagai "Mulai Rp250,000 /Sesi"
2. ❌ **Jadwal Tersedia** - Ditampilkan sebagai tombol waktu (08.00, 18.00, 20.00, 22.00)

### Di Halaman `venue_detail.blade.php` (Detail):
3. ❌ **Harga Per Jam** - Ditampilkan sebagai "Rp 100,000 / jam"
4. ❌ **Jadwal & Booking** - Ditampilkan sebagai time slots untuk booking
5. ❌ **Galeri Foto** - Di frontend ada multiple images, di form hanya 1 logo

---

## 💡 SARAN FIELD YANG PERLU DITAMBAHKAN

### 1. **HARGA (PRIORITAS TINGGI)**
   - **Field yang perlu ditambahkan:**
     - `harga_minimal` atau `harga_per_jam` (integer/decimal)
     - Atau lebih baik: Sistem harga berdasarkan waktu (pagi/siang/malam)
   
   - **Lokasi penambahan:** Form `/informasi` atau form baru untuk "Harga & Jadwal"
   
   - **Alasan:** Harga adalah informasi kritis untuk user memutuskan booking

### 2. **JADWAL OPERASIONAL (PRIORITAS TINGGI)**
   - **Field yang perlu ditambahkan:**
     - `jam_buka` (time) - Jam buka venue
     - `jam_tutup` (time) - Jam tutup venue
     - `hari_operasional` (array) - Hari buka (Senin-Minggu)
     - Atau lebih detail: `jadwal_per_hari` (JSON) - Jadwal per hari dengan slot waktu
   
   - **Lokasi penambahan:** Form baru atau di form `/detail`
   
   - **Alasan:** User perlu tahu kapan venue buka dan slot waktu yang tersedia

### 3. **GALERI FOTO (PRIORITAS SEDANG)**
   - **Field yang perlu ditambahkan:**
     - `galeri_foto` (multiple file upload atau relasi ke tabel galeri)
     - Atau buat tabel terpisah `venue_galleries` dengan relasi
   
   - **Lokasi penambahan:** Form `/detail` atau form terpisah
   
   - **Alasan:** Frontend menampilkan multiple images, form hanya punya 1 logo

### 4. **INFORMASI TAMBAHAN (PRIORITAS RENDAH)**
   - **Field opsional yang bisa ditambahkan:**
     - `nomor_telepon` - Kontak venue
     - `email_venue` - Email kontak
     - `rating` - Rating venue (auto dari review)
     - `status_verifikasi` - Status apakah venue sudah diverifikasi admin

---

## 📊 REKOMENDASI IMPLEMENTASI

### **Opsi 1: Tambahkan di Form `/informasi` (Step 1)**
Tambahkan field:
- Harga minimal per jam
- Nomor telepon kontak

### **Opsi 2: Buat Form Baru "Harga & Jadwal" (Step 3)**
Buat step baru antara `/detail` dan `/syarat`:
- Harga per jam (atau per slot waktu)
- Jam operasional (buka-tutup)
- Hari operasional
- Slot waktu yang tersedia

### **Opsi 3: Tambahkan di Form `/detail` (Step 2)**
Tambahkan section:
- Galeri foto (multiple upload)
- Harga dasar
- Jam operasional dasar

---

## ✅ KESIMPULAN

**Field yang WAJIB ditambahkan:**
1. ✅ Harga (minimal harga per jam)
2. ✅ Jadwal operasional (jam buka-tutup, hari operasional)

**Field yang DISARANKAN ditambahkan:**
3. ✅ Galeri foto (multiple images)
4. ✅ Nomor telepon kontak

**Field yang OPSIONAL:**
5. Email kontak
6. Status verifikasi

---

## 🔧 MIGRATION YANG DIPERLUKAN

```php
// Tambahkan kolom ke tabel pendaftarans:
- harga_per_jam (decimal 10,2 nullable)
- jam_buka (time nullable)
- jam_tutup (time nullable)
- hari_operasional (json nullable) // ['senin', 'selasa', ...]
- nomor_telepon (string nullable)
- email_venue (string nullable)
```

atau buat tabel terpisah untuk:
- `venue_schedules` (untuk jadwal detail)
- `venue_prices` (untuk harga per slot waktu)
- `venue_galleries` (untuk galeri foto)

