# DIAGRAM ALUR: SISTEM FASILITAS DINAMIS VENUE

## 🔄 ALUR LENGKAP: DARI INPUT KE TAMPILAN

```
┌─────────────────────────────────────────────────────────────────────────┐
│                         BACKOFFICE (PEMILIK VENUE)                      │
└─────────────────────────────────────────────────────────────────────────┘
                                    │
                                    │ 1. Login sebagai Pemilik
                                    ▼
                    ┌───────────────────────────────┐
                    │  /fasilitas/venue/{id}/edit   │
                    │  (Edit Venue Form)            │
                    └───────────────┬───────────────┘
                                    │
                                    │ 2. Pilih Fasilitas
                                    │    (Checkbox: 12 pilihan)
                                    ▼
                    ┌───────────────────────────────┐
                    │  fasilitas_venue[] = [         │
                    │    "Area Parkir",              │
                    │    "Toilet/Kamar Mandi",       │
                    │    "Ruang Ganti/Transit"       │
                    │  ]                             │
                    └───────────────┬───────────────┘
                                    │
                                    │ 3. Submit Form
                                    │    POST /fasilitas/venue/{id}/update
                                    ▼
┌─────────────────────────────────────────────────────────────────────────┐
│                    PendaftaranController::updateVenue()                  │
│                                                                           │
│  $venue->fasilitas = json_encode($request->fasilitas_venue);            │
│  $venue->save();                                                         │
└─────────────────────────────────────────────────────────────────────────┘
                                    │
                                    │ 4. Save to Database
                                    ▼
┌─────────────────────────────────────────────────────────────────────────┐
│                            DATABASE (MySQL)                              │
│                                                                           │
│  Table: pendaftarans                                                     │
│  ┌─────────────────────────────────────────────────────┐                │
│  │ id │ namavenue │ kota │ fasilitas (JSON)            │                │
│  ├────┼───────────┼──────┼─────────────────────────────┤                │
│  │ 1  │ MU Sport │ Denp │ ["Area Parkir", "Toilet"]   │                │
│  │ 2  │ Imbo     │ Denp │ ["Wi-Fi", "Parkir"]          │                │
│  └─────────────────────────────────────────────────────┘                │
│                                                                           │
│  Format JSON: ["Area Parkir", "Toilet/Kamar Mandi", ...]                │
└─────────────────────────────────────────────────────────────────────────┘
                                    │
                                    │ 5. User Request Frontend
                                    ▼
┌─────────────────────────────────────────────────────────────────────────┐
│                         FRONTEND (PUBLIC USER)                           │
└─────────────────────────────────────────────────────────────────────────┘
                                    │
                                    │ 6. Buka /venue-detail/{id}
                                    ▼
                    ┌───────────────────────────────┐
                    │  Route: GET /venue-detail/{id}│
                    └───────────────┬───────────────┘
                                    │
                                    │ 7. Call Controller
                                    ▼
┌─────────────────────────────────────────────────────────────────────────┐
│              VenueFrontendController::show($id)                           │
│                                                                           │
│  1. Query: Pendaftaran::find($id)                                        │
│  2. Parse: json_decode($venue->fasilitas, true)                        │
│  3. Map Icons: $iconMap[$fasilitasItem]                                  │
│  4. Return: view('venue_detail', compact(...))                          │
└─────────────────────────────────────────────────────────────────────────┘
                                    │
                                    │ 8. Render View
                                    ▼
                    ┌───────────────────────────────┐
                    │  venue_detail.blade.php        │
                    │                                │
                    │  @foreach($fasilitas as $item) │
                    │    <i class="fas {{ icon }}">  │
                    │    <span>{{ $item }}</span>    │
                    │  @endforeach                   │
                    └────────────────────────────────┘
                                    │
                                    │ 9. Display to User
                                    ▼
                    ┌───────────────────────────────┐
                    │  USER SEES:                   │
                    │  ✅ Area Parkir               │
                    │  ✅ Toilet/Kamar Mandi        │
                    │  ✅ Ruang Ganti/Transit       │
                    └───────────────────────────────┘
```

---

## 📋 BREAKDOWN PER KOMPONEN

### 1️⃣ BACKOFFICE INPUT FLOW

```
Pemilik Login
    │
    ▼
Dashboard → Fasilitas → Edit Venue
    │
    ▼
Form Edit Venue
    │
    ├─ Nama Venue
    ├─ Lokasi
    ├─ Kategori
    └─ ☑️ Fasilitas Venue (Checkbox)
         │
         ├─ ☑️ Area Parkir
         ├─ ☑️ Toilet/Kamar Mandi
         ├─ ☐ Ruang Ganti/Transit
         ├─ ☑️ Tempat Ibadah (Musholla)
         └─ ... (12 pilihan)
    │
    ▼
Submit Button
    │
    ▼
POST /fasilitas/venue/{id}/update
    │
    ▼
PendaftaranController::updateVenue()
    │
    ├─ Validate Input
    ├─ Convert Array to JSON
    └─ Save to Database
```

### 2️⃣ DATABASE STORAGE

```
Table: pendaftarans
┌────┬──────────────┬──────────────┬────────────────────────────────────┐
│ id │ namavenue    │ kota         │ fasilitas (JSON)                    │
├────┼──────────────┼──────────────┼────────────────────────────────────┤
│ 1  │ MU Sport     │ Denpasar     │ ["Area Parkir", "Toilet/Kamar..."]│
│    │ Center       │              │                                     │
├────┼──────────────┼──────────────┼────────────────────────────────────┤
│ 2  │ Imbo Sport   │ Denpasar     │ ["Wi-Fi", "Parkir", "AC"]         │
│    │ Center       │              │                                     │
└────┴──────────────┴──────────────┴────────────────────────────────────┘

JSON Format Example:
{
  "fasilitas": ["Area Parkir", "Toilet/Kamar Mandi", "Ruang Ganti/Transit"]
}
```

### 3️⃣ FRONTEND DISPLAY FLOW

```
User Request
    │
    ▼
GET /venue-detail/1
    │
    ▼
VenueFrontendController::show(1)
    │
    ├─ Query Database
    │   └─ Pendaftaran::find(1)
    │
    ├─ Parse JSON
    │   └─ json_decode($venue->fasilitas, true)
    │       → ["Area Parkir", "Toilet/Kamar Mandi", ...]
    │
    ├─ Map Icons
    │   └─ $iconMap = [
    │         "Area Parkir" => "fa-car",
    │         "Toilet/Kamar Mandi" => "fa-toilet",
    │         ...
    │       ]
    │
    └─ Pass to View
        └─ compact('venue', 'fasilitas', 'iconMap')
    │
    ▼
View: venue_detail.blade.php
    │
    ├─ Display Venue Info
    │   ├─ Nama: {{ $venue->namavenue }}
    │   ├─ Lokasi: {{ $venue->kota }}
    │   └─ Kategori: {{ $venue->kategori }}
    │
    └─ Display Fasilitas
        │
        ├─ Check if Empty
        │   └─ @if(empty($fasilitas))
        │       → "Belum ada fasilitas"
        │
        └─ Loop Fasilitas
            └─ @foreach($fasilitas as $item)
                ├─ Icon: <i class="fas {{ $iconMap[$item] }}"></i>
                └─ Label: <span>{{ $item }}</span>
    │
    ▼
HTML Output
    │
    ▼
User Browser
    │
    └─ Rendered Page with Dynamic Facilities
```

---

## 🔀 COMPARISON: BEFORE vs AFTER

### ❌ BEFORE (Static/Hardcoded)

```blade
<!-- venue_detail.blade.php -->
<ul>
    <li><i class="fas fa-shopping-basket"></i> Jual Minuman</li>
    <li><i class="fas fa-mosque"></i> Musholla</li>
    <li><i class="fas fa-car"></i> Parkir Mobil</li>
    <!-- Hardcoded, tidak bisa diubah -->
</ul>
```

**Masalah:**
- ❌ Tidak terhubung dengan database
- ❌ Pemilik tidak bisa mengubah
- ❌ Semua venue tampil sama
- ❌ Perlu edit code untuk ubah fasilitas

### ✅ AFTER (Dynamic/Database-Driven)

```blade
<!-- venue_detail.blade.php -->
@if(empty($fasilitas))
    <p>Belum ada fasilitas tersedia.</p>
@else
    <ul>
        @foreach($fasilitas as $item)
            <li>
                <i class="fas {{ $iconMap[$item] ?? 'fa-check' }}"></i>
                {{ $item }}
            </li>
        @endforeach
    </ul>
@endif
```

**Keuntungan:**
- ✅ Terhubung dengan database
- ✅ Pemilik bisa mengubah via backoffice
- ✅ Setiap venue punya fasilitas sendiri
- ✅ Tidak perlu edit code

---

## 🎯 POINT OF INTEGRATION

```
┌─────────────────────────────────────────────────────────────┐
│                    POINT OF INTEGRATION                     │
│                                                              │
│  BACKOFFICE          DATABASE          FRONTEND            │
│      │                  │                  │               │
│      │                  │                  │               │
│  Input Form ──────► JSON Column ──────► Parse & Display   │
│  (Checkbox)         (pendaftarans      (View Loop)         │
│                     .fasilitas)                            │
│                                                              │
│  Controller:        Storage:            Controller:         │
│  updateVenue()      Database            show()              │
│                                                              │
└─────────────────────────────────────────────────────────────┘
```

**Key Integration Points:**
1. **Backoffice → Database**: `PendaftaranController::updateVenue()` saves JSON
2. **Database → Frontend**: `VenueFrontendController::show()` reads & parses JSON
3. **Frontend Display**: View loops through parsed array

---

## 🔄 REAL-TIME UPDATE FLOW

```
Pemilik Edit Fasilitas di Backoffice
    │
    ▼ (Save)
Database Updated
    │
    ▼ (User Refresh Page)
Frontend Query Database
    │
    ▼ (Parse JSON)
New Facilities Displayed
    │
    ▼
User Sees Updated Facilities
```

**Time to Update**: Instant (setelah user refresh page)

---

## 📊 DATA TRANSFORMATION

```
INPUT (Form)              STORAGE (DB)           OUTPUT (View)
─────────────            ──────────────         ──────────────

Checkbox Array            JSON String            HTML List
    │                         │                      │
    │                         │                      │
["Area Parkir",          '["Area Parkir",        <ul>
 "Toilet"]               "Toilet"]'              <li>Area Parkir</li>
    │                         │                  <li>Toilet</li>
    │                         │                  </ul>
    └─────────► json_encode ─┴──► json_decode ────┘
```

---

**Diagram ini membantu memahami alur data dari input backoffice hingga tampilan frontend.**

