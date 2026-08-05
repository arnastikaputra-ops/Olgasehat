<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pendaftaran;
use App\Models\LapanganSlot;
use App\Models\Lapangan;
use App\Models\Galeri;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class VenueFrontendController extends Controller
{
    /**
     * Menampilkan list semua venue
     */
    public function index(Request $request)
    {
        // Deteksi apakah request dari /venue atau /venueuser
        $isUserView = $request->is('venueuser') || $request->routeIs('user.venue');
        
        // Check if there are filter parameters
        $query = $request->input('q', '');
        $kota = $request->input('kota', '');
        $kategori = $request->input('kategori', '');
        $tanggal = $request->input('tanggal', '');
        $jam = $request->input('jam', '');
        
        $venues = Pendaftaran::with(['lapangans.slots', 'galleries'])
            ->whereNotNull('namavenue')
            ->where('namavenue', '!=', '');
        
        // Filter berdasarkan query (nama venue, provinsi, nama lapangan, kota)
        if (!empty($query)) {
            $venues->where(function($q) use ($query) {
                $q->where('namavenue', 'like', "%{$query}%")
                  ->orWhere('kota', 'like', "%{$query}%")
                  ->orWhere('provinsi', 'like', "%{$query}%")
                  ->orWhere('lokasi', 'like', "%{$query}%")
                  ->orWhereHas('lapangans', function($lapanganQuery) use ($query) {
                      $lapanganQuery->where('nama', 'like', "%{$query}%");
                  });
            });
        }
        
        // Filter berdasarkan kota (terpisah dari query search)
        if (!empty($kota)) {
            $venues->where('kota', 'like', "%{$kota}%");
        }
        
        // Filter berdasarkan kategori (kategori sekarang JSON array)
        if (!empty($kategori) && $kategori !== 'all') {
            $venues->whereJsonContains('kategori', $kategori);
        }
        
        // Filter berdasarkan tanggal dan jam (jika ada slot available pada tanggal/jam tersebut)
        if (!empty($tanggal) || (!empty($jam) && $jam !== 'all')) {
            $venues->whereHas('lapangans.slots', function($slotQuery) use ($tanggal, $jam) {
                if (!empty($tanggal)) {
                    $slotQuery->whereDate('tanggal', $tanggal);
                }
                if (!empty($jam) && $jam !== 'all') {
                    $slotQuery->where(function($q) use ($jam) {
                        $q->where('jam_mulai', 'like', "{$jam}%")
                          ->orWhere(function($sub) use ($jam) {
                              $sub->where('jam_mulai', '<=', $jam)
                                  ->where('jam_selesai', '>', $jam);
                          });
                    });
                }
                $slotQuery->where('status', 'available')->valid();
            });
        }
        
        $venues = $venues->orderBy('created_at', 'desc')
            ->paginate(16);
        
        // Append query parameters untuk pagination
        $venues->appends($request->query());
        
        // Hitung harga minimum & siapkan preview slots per venue
        foreach ($venues as $venue) {
            $allAvailableSlots = $venue->lapangans->flatMap(function($lapangan) {
                return $lapangan->slots;
            })->where('status', 'available');

            $filteredSlots = $allAvailableSlots;

            if (!empty($tanggal)) {
                $filteredSlots = $filteredSlots->filter(function($slot) use ($tanggal) {
                    return \Carbon\Carbon::parse($slot->tanggal)->toDateString() === $tanggal;
                });
            }

            if (!empty($jam) && $jam !== 'all') {
                $filteredSlots = $filteredSlots->filter(function($slot) use ($jam) {
                    $jamStart = \Carbon\Carbon::parse($slot->jam_mulai)->format('H:i');
                    return \Illuminate\Support\Str::startsWith($jamStart, $jam) || ($jamStart <= $jam && \Carbon\Carbon::parse($slot->jam_selesai)->format('H:i') > $jam);
                });
            }

            $minPrice = $allAvailableSlots->min('harga');
            $venue->min_price = ($minPrice && $minPrice > 0) ? $minPrice : 120000;
            $venue->preview_slots = $filteredSlots->take(4);
        }
        
        // Ambil venue banner untuk ditampilkan
        $venueBanners = Galeri::where('kategori', 'venue_banner')
            ->orderBy('urutan', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Return view sesuai route
        $viewName = $isUserView ? 'user.venueuser' : 'FRONTEND.venue';
        return view($viewName, compact('venues', 'venueBanners'));
    }
    
    /**
     * Menampilkan detail venue berdasarkan ID
     */
    public function show(Request $request, $id)
    {
        try {
            // Deteksi apakah request dari /venue-detail atau /venueuser_detail
            $isUserView = $request->is('venueuser_detail/*') || $request->routeIs('user.venue.detail');
            
            // Hanya tampilkan venue yang sudah diverifikasi
            $venue = Pendaftaran::with(['galleries', 'lapangans.slots'])
                ->where('syarat_disetujui', true)
                ->findOrFail($id);
            
            // Parse fasilitas (sudah array karena cast di model)
            $fasilitas = $venue->fasilitas ?? [];
            if (!is_array($fasilitas) && !empty($fasilitas)) {
                // Fallback untuk data lama yang masih JSON string
                $fasilitas = json_decode($fasilitas, true) ?? [];
            }
            
            // Mapping icon fasilitas
            $iconMap = [
                'Area Parkir' => 'fa-car',
                'Toilet/Kamar Mandi' => 'fa-toilet',
                'Ruang Ganti/Transit' => 'fa-tshirt',
                'Tempat Ibadah (Musholla)' => 'fa-mosque',
                'Kantin/Area Catering' => 'fa-utensils',
                'AC/Pendingin Udara' => 'fa-snowflake',
                'Sistem Tata Suara (Sound System)' => 'fa-volume-up',
                'Proyektor & Layar/LED' => 'fa-tv',
                'Akses Internet (Wi-Fi)' => 'fa-wifi',
                'Akses Listrik Cadangan (Genset)' => 'fa-plug',
                'Area Registrasi/Lobi' => 'fa-door-open',
                'Keamanan (Security) & P3K' => 'fa-shield-alt',
            ];
            
            // Hitung harga minimum - hanya dari jadwal yang masih berlaku
            $minPrice = $venue->lapangans->flatMap(function($lapangan) {
                return $lapangan->slots;
            })
            ->filter(function($slot) {
                // Filter jadwal yang masih berlaku
                $today = now()->startOfDay();
                $slotDate = \Carbon\Carbon::parse($slot->tanggal)->startOfDay();
                
                if ($slotDate->greaterThan($today)) {
                    return true; // Tanggal lebih besar dari hari ini
                } elseif ($slotDate->equalTo($today)) {
                    // Jika tanggal sama, cek jam selesai
                    $slotEndTime = \Carbon\Carbon::parse($slot->jam_selesai)->format('H:i:s');
                    $nowTime = now()->format('H:i:s');
                    return $slotEndTime >= $nowTime;
                }
                return false; // Tanggal sudah lewat
            })
            ->where('status', 'available')
            ->min('harga');
            
            $venue->min_price = $minPrice ?? 0;
            
            // Ambil lapangan pertama sebagai default jika ada
            $defaultLapangan = $venue->lapangans->first();
            $defaultDate = now();
            
            // Ambil slots untuk tanggal hari ini dan lapangan pertama (jika ada)
            $timeslots = collect();
            if ($defaultLapangan) {
                $timeslots = $defaultLapangan->slots()
                    ->whereDate('tanggal', $defaultDate->toDateString())
                    ->valid() // Hanya tampilkan jadwal yang masih berlaku
                    ->orderBy('jam_mulai')
                    ->get();
            }
            
            // Return view sesuai route
            $viewName = $isUserView ? 'user.venueuser_detail' : 'FRONTEND.venue_detail';
            return view($viewName, compact('venue', 'fasilitas', 'iconMap', 'defaultLapangan', 'defaultDate', 'timeslots'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404, 'Venue tidak ditemukan');
        }
    }
    
    /**
     * API untuk mengambil slots berdasarkan tanggal dan lapangan (AJAX)
     */
    public function getSlots(Request $request, $venueId)
    {
        try {
            $venue = Pendaftaran::with(['lapangans.slots'])
                ->findOrFail($venueId);
            
            $lapanganId = $request->input('lapangan_id');
            $date = $request->filled('date') ? Carbon::parse($request->input('date')) : now();
            
            if (!$lapanganId) {
                // Jika tidak ada lapangan_id, ambil lapangan pertama
                $lapangan = $venue->lapangans->first();
            } else {
                $lapangan = $venue->lapangans()->findOrFail($lapanganId);
            }
            
            if (!$lapangan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lapangan tidak ditemukan',
                    'timeslots' => []
                ]);
            }
            
            $timeslots = $lapangan->slots()
                ->whereDate('tanggal', $date->toDateString())
                ->valid() // Hanya tampilkan jadwal yang masih berlaku
                ->orderBy('jam_mulai')
                ->get();
            
            return response()->json([
                'success' => true,
                'date' => $date->format('Y-m-d'),
                'date_formatted' => $date->format('d/m/Y'),
                'timeslots' => $timeslots->map(function($slot) {
                    return [
                        'id' => $slot->id,
                        'jam_mulai' => Carbon::parse($slot->jam_mulai)->format('H:i'),
                        'jam_selesai' => Carbon::parse($slot->jam_selesai)->format('H:i'),
                        'harga' => (int) $slot->harga,
                        'harga_awal' => $slot->harga_awal ? (int) $slot->harga_awal : null,
                        'status' => $slot->status,
                        'is_promo' => (bool) $slot->is_promo,
                        'catatan' => $slot->catatan ?? '',
                    ];
                }),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'timeslots' => []
            ], 500);
        }
    }
    
    /**
     * Search venue berdasarkan query (AJAX)
     * Mencari berdasarkan: nama venue, kategori, kota, provinsi, nama lapangan
     */
    public function search(Request $request)
    {
        $query = $request->input('q', '');
        $limit = $request->input('limit', 10);
        
        if (strlen($query) < 2) {
            return response()->json([
                'success' => true,
                'results' => []
            ]);
        }
        
        $venues = Pendaftaran::with(['lapangans'])
            ->where(function($q) use ($query) {
                $q->where('namavenue', 'like', "%{$query}%")
                  ->orWhere('kota', 'like', "%{$query}%")
                  ->orWhere('provinsi', 'like', "%{$query}%")
                  ->orWhereJsonContains('kategori', $query) // Search in JSON array
                  ->orWhereHas('lapangans', function($lapanganQuery) use ($query) {
                      $lapanganQuery->where('nama', 'like', "%{$query}%");
                  });
            })
            ->where('syarat_disetujui', true) // Hanya venue yang sudah diverifikasi
            ->limit($limit)
            ->get();
        
        $results = $venues->map(function($venue) {
            // Ambil semua nama lapangan
            $lapanganList = $venue->lapangans->pluck('nama')->toArray();
            
            // Format alamat
            $alamat = trim($venue->kota . ', ' . $venue->provinsi);
            
            return [
                'id' => $venue->id,
                'nama' => $venue->namavenue,
                'kategori' => $venue->kategori,
                'alamat' => $alamat,
                'kota' => $venue->kota,
                'provinsi' => $venue->provinsi,
                'lapangan' => $lapanganList, // Array semua lapangan
                'lapangan_count' => count($lapanganList),
            ];
        });
        
        return response()->json([
            'success' => true,
            'results' => $results
        ]);
    }
    
    /**
     * Ambil semua kategori olahraga unik dari database
     */
    public function getCategories()
    {
        // Ambil semua venue
        $venues = Pendaftaran::where(function($query) {
                $query->where('syarat_disetujui', true)
                      ->orWhereNotNull('namavenue');
            })
            ->whereNotNull('kategori')
            ->get();
        
        // Extract semua kategori dari array dan flatten
        $allCategories = collect();
        foreach ($venues as $venue) {
            $kategori = $venue->kategori;
            // Handle both array (new format) and string (old format)
            if (is_array($kategori)) {
                $allCategories = $allCategories->merge($kategori);
            } elseif (is_string($kategori) && !empty($kategori)) {
                $allCategories->push($kategori);
            }
        }
        
        // Get unique categories, filter empty, sort, and get values
        $categories = $allCategories->unique()->filter()->sort()->values();
        
        return response()->json([
            'success' => true,
            'categories' => $categories
        ]);
    }
    
    /**
     * Filter venue berdasarkan kategori, query, dan tanggal
     */
    public function filter(Request $request)
    {
        $query = $request->input('q', '');
        $kota = $request->input('kota', '');
        $kategori = $request->input('kategori', '');
        $tanggal = $request->input('tanggal', '');
        $jam = $request->input('jam', '');
        
        $venues = Pendaftaran::with(['lapangans.slots', 'galleries'])
            ->where('syarat_disetujui', true); // Hanya venue yang sudah diverifikasi
        
        // Filter berdasarkan query (nama venue, provinsi, nama lapangan, kota)
        if (!empty($query)) {
            $venues->where(function($q) use ($query) {
                $q->where('namavenue', 'like', "%{$query}%")
                  ->orWhere('kota', 'like', "%{$query}%")
                  ->orWhere('provinsi', 'like', "%{$query}%")
                  ->orWhere('lokasi', 'like', "%{$query}%")
                  ->orWhereHas('lapangans', function($lapanganQuery) use ($query) {
                      $lapanganQuery->where('nama', 'like', "%{$query}%");
                  });
            });
        }
        
        // Filter berdasarkan kota (terpisah dari query search)
        if (!empty($kota)) {
            $venues->where('kota', 'like', "%{$kota}%");
        }
        
        // Filter berdasarkan kategori (kategori sekarang JSON array)
        if (!empty($kategori) && $kategori !== 'all') {
            $venues->whereJsonContains('kategori', $kategori);
        }
        
        // Filter berdasarkan tanggal dan jam (jika ada slot available pada tanggal/jam tersebut)
        if (!empty($tanggal) || (!empty($jam) && $jam !== 'all')) {
            $venues->whereHas('lapangans.slots', function($slotQuery) use ($tanggal, $jam) {
                if (!empty($tanggal)) {
                    $slotQuery->whereDate('tanggal', $tanggal);
                }
                if (!empty($jam) && $jam !== 'all') {
                    $slotQuery->where(function($q) use ($jam) {
                        $q->where('jam_mulai', 'like', "{$jam}%")
                          ->orWhere(function($sub) use ($jam) {
                              $sub->where('jam_mulai', '<=', $jam)
                                  ->where('jam_selesai', '>', $jam);
                          });
                    });
                }
                $slotQuery->where('status', 'available')->valid();
            });
        }
        
        $venues = $venues->orderBy('created_at', 'desc')
            ->paginate(16);
        
        // Append query parameters untuk pagination
        $venues->appends($request->query());
        
        // Hitung harga minimum per venue
        foreach ($venues as $venue) {
            $allAvailableSlots = $venue->lapangans->flatMap(function($lapangan) {
                return $lapangan->slots;
            })->where('status', 'available');

            $filteredSlots = $allAvailableSlots;

            if (!empty($tanggal)) {
                $filteredSlots = $filteredSlots->filter(function($slot) use ($tanggal) {
                    return \Carbon\Carbon::parse($slot->tanggal)->toDateString() === $tanggal;
                });
            }

            if (!empty($jam) && $jam !== 'all') {
                $filteredSlots = $filteredSlots->filter(function($slot) use ($jam) {
                    $jamStart = \Carbon\Carbon::parse($slot->jam_mulai)->format('H:i');
                    return \Illuminate\Support\Str::startsWith($jamStart, $jam) || ($jamStart <= $jam && \Carbon\Carbon::parse($slot->jam_selesai)->format('H:i') > $jam);
                });
            }

            $venue->min_price = $allAvailableSlots->min('harga') ?? 0;
            $venue->preview_slots = $filteredSlots->take(4);
        }
        
        // Deteksi apakah request dari /venue atau /venueuser
        $isUserView = $request->is('venueuser') || $request->routeIs('user.venue');
        
        // Ambil venue banner
        $venueBanners = Galeri::where('kategori', 'venue_banner')
            ->orderBy('urutan', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Return view sesuai route
        $viewName = $isUserView ? 'user.venueuser' : 'FRONTEND.venue';
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'html' => view('FRONTEND.partials.venue-cards', compact('venues'))->render(),
                'pagination' => view('FRONTEND.partials.venue-pagination', compact('venues'))->render(),
            ]);
        }
        
        return view($viewName, compact('venues', 'venueBanners'));
    }

    public function bookSlots(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan login terlebih dahulu untuk melakukan pemesanan.'
            ], 401);
        }

        // Decode slot_ids if passed as JSON string (from FormData)
        $rawSlotIds = $request->input('slot_ids');
        if (is_string($rawSlotIds)) {
            $decoded = json_decode($rawSlotIds, true);
            if (is_array($decoded)) {
                $request->merge(['slot_ids' => $decoded]);
            }
        }

        $request->validate([
            'slot_ids' => 'required|array',
            'slot_ids.*' => 'exists:lapangan_slots,id',
            'customer_name' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'notes' => 'nullable|string',
            'payment_method' => 'nullable|string',
            'bank_code' => 'nullable|string|in:BCA,BRI,BPD,DANA,GOPAY',
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg,webp|max:4096',
        ], [
            'bukti_pembayaran.required' => 'Wajib mengunggah foto bukti pembayaran untuk menyelesaikan pendaftaran.',
            'bukti_pembayaran.image' => 'Bukti pembayaran harus berupa berkas foto/gambar.',
        ]);

        $user = Auth::user();
        $slotIds = $request->input('slot_ids');

        // Fetch available slots with lapangan and venue (pendaftaran)
        $slots = LapanganSlot::with('lapangan.pendaftaran')
            ->whereIn('id', $slotIds)
            ->where('status', 'available')
            ->get();

        if ($slots->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Jadwal yang dipilih sudah tidak tersedia atau telah dipesan pengguna lain.'
            ], 422);
        }

        $subtotal = $slots->sum('harga');
        $firstSlot = $slots->first();
        $venue = optional($firstSlot->lapangan)->pendaftaran;

        // Check if user has an active approved membership
        $isMember = \App\Models\ActivityParticipant::where('user_id', $user->id)
            ->where('status', 'approved')
            ->whereHas('activity', function($q) {
                $q->where('jenis', 'membership')
                  ->orWhereHas('activityType', function($at) {
                      $at->whereIn('name', ['klub', 'membership']);
                  });
            })
            ->exists();

        // Member discount 10% if user is an active member
        $diskonMember = $isMember ? round($subtotal * 0.10) : 0;
        $totalHarga = max(0, $subtotal - $diskonMember);

        // Calculate platform commission and net venue payout
        $komisiTipe = $venue->komisi_tipe ?? 'none';
        $komisiNilai = (float) ($venue->komisi_nilai ?? 0);
        $komisiPlatform = 0;

        if ($komisiTipe === 'percentage' && $komisiNilai > 0) {
            $komisiPlatform = ($totalHarga * $komisiNilai) / 100;
        } elseif ($komisiTipe === 'fixed' && $komisiNilai > 0) {
            $komisiPlatform = min($komisiNilai, $totalHarga);
        }

        $pendapatanMitra = max(0, $totalHarga - $komisiPlatform);

        // Generate Virtual Account Number based on bank code
        $bankCode = strtoupper($request->input('bank_code', 'BCA'));
        $paymentSetting = \App\Models\PaymentSetting::where('bank_code', strtoupper($bankCode))->where('is_active', true)->first();
        $virtualAccount = $paymentSetting ? $paymentSetting->account_number : ('88008' . mt_rand(10000000, 99999999));

        // Handle upload bukti pembayaran
        $buktiPembayaranName = null;
        if ($request->hasFile('bukti_pembayaran')) {
            $file = $request->file('bukti_pembayaran');
            $buktiPembayaranName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('bukti_pembayaran'), $buktiPembayaranName);
        }

        // Update slots to booked
        LapanganSlot::whereIn('id', $slots->pluck('id'))
            ->update([
                'status' => 'booked',
                'user_id' => $user->id
            ]);

        // Create VenueBooking record
        $booking = \App\Models\VenueBooking::create([
            'user_id' => $user->id,
            'pendaftaran_id' => $venue ? $venue->id : 1,
            'slot_ids' => $slots->pluck('id')->toArray(),
            'nama_pemesan' => $request->input('customer_name', $user->name),
            'nomor_telepon' => $request->input('customer_phone', $user->phone ?? '08123456789'),
            'email' => $request->input('email', $user->email),
            'subtotal' => $subtotal,
            'komisi_tipe' => $komisiTipe,
            'komisi_nilai' => $komisiNilai,
            'komisi_platform' => $komisiPlatform,
            'pendapatan_mitra' => $pendapatanMitra,
            'total_harga' => $totalHarga,
            'metode_pembayaran' => $request->input('payment_method', 'virtualAccount'),
            'bank_code' => $bankCode,
            'virtual_account' => $virtualAccount,
            'status_pembayaran' => 'pending_acc',
            'catatan' => $request->input('notes') . ($isMember ? ' [Diskon Member 10% Diterapkan]' : ''),
            'bukti_pembayaran' => $buktiPembayaranName,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pemesanan berhasil diproses!',
            'booking' => $booking
        ]);
    }
}
