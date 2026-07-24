<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pendaftaran;
use App\Models\VenueGallery;
use App\Models\Mitra;
use App\Models\Lapangan;
use App\Models\LapanganSlot;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class PendaftaranController extends Controller
{
    public function informasi(){
        $user = auth()->user();
        $mitra = null;
        
        if ($user) {
            $mitra = Mitra::where('user_id', $user->id)->first();
        }
        
        return view('pemiliklapangan.Informasi.informasi', compact('mitra'));
    }

    public function detail($id = null){
        // Jika ada ID dari parameter URL, gunakan itu
        if ($id) {
            $venue = Pendaftaran::with('galleries')
                ->where('user_id', auth()->id())
                ->find($id);
            if ($venue) {
                // Simpan ke session juga
                session(['venue_id' => $venue->id]);
                return view('pemiliklapangan.Detail.detail', compact('venue'));
            }
        }
        
        // Jika tidak ada ID, coba dari session
        $venueId = session('venue_id');
        if ($venueId) {
            $venue = Pendaftaran::with('galleries')
                ->where('user_id', auth()->id())
                ->find($venueId);
            if ($venue) {
                return view('pemiliklapangan.Detail.detail', compact('venue'));
            }
        }
        
        // Jika tidak ada data, redirect ke informasi
        return redirect()->route('informasi')
                         ->with('error', 'Silakan lengkapi informasi venue terlebih dahulu.');
    }

    public function syarat(){
        $venueId = session('venue_id');
        if (!$venueId) {
            return redirect()->route('informasi')
                             ->with('error', 'Silakan lengkapi informasi venue terlebih dahulu.');
        }
        
        $venue = Pendaftaran::where('user_id', auth()->id())->find($venueId);
        if (!$venue) {
            return redirect()->route('informasi')
                             ->with('error', 'Venue tidak ditemukan atau tidak memiliki akses.');
        }
        
        return view('pemiliklapangan.Syarat.syarat', compact('venue'));
    }

    public function end(){
        $venueId = session('venue_id');
        if (!$venueId) {
            return redirect()->route('informasi')
                             ->with('error', 'Silakan lengkapi informasi venue terlebih dahulu.');
        }
        
        $venue = Pendaftaran::where('user_id', auth()->id())->find($venueId);
        if (!$venue) {
            return redirect()->route('informasi')
                             ->with('error', 'Venue tidak ditemukan atau tidak memiliki akses.');
        }
        
        return view('pemiliklapangan.Dashboard.end', compact('venue'));
    }

    public function venue(){
        // Hanya tampilkan venue milik user yang login
        $venues = Pendaftaran::where('user_id', auth()->id())->get();
        return view('pemiliklapangan.Fasilitas.venue', compact('venues'));
    }

    public function showVenue($id)
    {
        $venue = Pendaftaran::with(['galleries', 'lapangans'])->where('user_id', auth()->id())->findOrFail($id);
        
        // Fasilitas sudah di-cast sebagai array di model
        $fasilitas = $venue->fasilitas;
        if (!is_array($fasilitas)) {
            if (empty($fasilitas)) {
                $fasilitas = [];
            } elseif (is_string($fasilitas)) {
                // Fallback untuk data lama yang masih JSON string
                $decoded = json_decode($fasilitas, true);
                $fasilitas = is_array($decoded) ? $decoded : [];
            } else {
                $fasilitas = [];
            }
        }
        
        return view('pemiliklapangan.Fasilitas.detailvenue', compact('venue', 'fasilitas'));
    }

    public function storeLapangan(Request $request, $id)
    {
        $venue = Pendaftaran::where('user_id', auth()->id())->findOrFail($id);

        $validated = $request->validate([
            'nama_lapangan' => 'required|string|max:255',
        ]);

        Lapangan::create([
            'pendaftaran_id' => $venue->id,
            'nama' => $validated['nama_lapangan'],
        ]);

        return redirect()
            ->route('fasilitas.detail', $venue->id)
            ->with('success', 'Lapangan berhasil ditambahkan!');
    }

    public function papan()
    {
        // Hanya tampilkan venue milik user yang login
        $venues = Pendaftaran::where('user_id', auth()->id())->get();
        return view('pemiliklapangan.Papan.index', compact('venues'));
    }

    public function papanVenueDetail($id)
    {
        $venue = Pendaftaran::with(['galleries', 'lapangans'])->where('user_id', auth()->id())->findOrFail($id);
        return view('pemiliklapangan.Papan.detailvenue', compact('venue'));
    }

    public function showLapanganSchedule(Request $request, $venueId, $lapanganId)
    {
        $venue = Pendaftaran::where('user_id', auth()->id())->findOrFail($venueId);
        $lapangan = $venue->lapangans()->findOrFail($lapanganId);
        $availableLapangans = $venue->lapangans()->get();

        $date = $request->filled('date') ? Carbon::parse($request->input('date')) : now();

        $timeslots = $lapangan->slots()
            ->whereDate('tanggal', $date->toDateString())
            ->valid() // Hanya tampilkan jadwal yang masih berlaku
            ->orderBy('jam_mulai')
            ->get();

        // Get dates that have slots (for calendar indicator) - bypassed for performance
        $datesWithSlots = [];

        // If AJAX request, return JSON
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'date' => $date->format('Y-m-d'),
                'date_formatted' => $date->format('d/m/Y'),
                'timeslots' => $timeslots->map(function($slot) {
                    return [
                        'id' => $slot->id,
                        'jam_mulai' => \Carbon\Carbon::parse($slot->jam_mulai)->format('H:i'),
                        'jam_selesai' => \Carbon\Carbon::parse($slot->jam_selesai)->format('H:i'),
                        'harga' => (int) $slot->harga,
                        'harga_awal' => $slot->harga_awal ? (int) $slot->harga_awal : null,
                        'status' => $slot->status,
                        'is_promo' => (bool) $slot->is_promo,
                        'catatan' => $slot->catatan ?? '',
                    ];
                }),
                'dates_with_slots' => $datesWithSlots,
            ]);
        }

        return view('pemiliklapangan.Papan.papan', compact(
            'venue',
            'lapangan',
            'availableLapangans',
            'date',
            'timeslots',
            'datesWithSlots'
        ));
    }

    /**
     * API endpoint untuk get slots by date (AJAX)
     */
    public function getSlotsByDate(Request $request, $venueId, $lapanganId)
    {
        $venue = Pendaftaran::where('user_id', auth()->id())->findOrFail($venueId);
        $lapangan = $venue->lapangans()->findOrFail($lapanganId);

        // Parse date from request
        $dateInput = $request->input('date');
        if ($dateInput) {
            try {
                $date = Carbon::parse($dateInput)->startOfDay();
            } catch (\Exception $e) {
                $date = now()->startOfDay();
            }
        } else {
            $date = now()->startOfDay();
        }

        // Use whereDate with proper date format
        $dateString = $date->format('Y-m-d');
        
        // Query slots for the specific date
        // Try multiple query methods for better compatibility
        $timeslots = $lapangan->slots()
            ->whereDate('tanggal', $dateString)
            ->valid() // Hanya tampilkan jadwal yang masih berlaku
            ->orderBy('jam_mulai')
            ->get();
        
        // Debug: Log query if no results found
        if ($timeslots->isEmpty()) {
            \Log::info('No slots found for date', [
                'date' => $dateString,
                'lapangan_id' => $lapangan->id,
                'total_slots' => $lapangan->slots()->count(),
                'sample_dates' => $lapangan->slots()->select('tanggal')->distinct()->limit(5)->pluck('tanggal')->toArray()
            ]);
        }

        // Get dates that have slots (for calendar indicator) - bypassed for performance
        $datesWithSlots = [];

        return response()->json([
            'success' => true,
            'date' => $date->format('Y-m-d'),
            'date_formatted' => $date->format('d/m/Y'),
            'date_input' => $dateInput ?? null,
            'timeslots' => $timeslots->map(function($slot) {
                return [
                    'id' => $slot->id,
                    'tanggal' => $slot->tanggal ? \Carbon\Carbon::parse($slot->tanggal)->format('Y-m-d') : null,
                    'jam_mulai' => \Carbon\Carbon::parse($slot->jam_mulai)->format('H:i'),
                    'jam_selesai' => \Carbon\Carbon::parse($slot->jam_selesai)->format('H:i'),
                    'harga' => (int) $slot->harga,
                    'harga_awal' => $slot->harga_awal ? (int) $slot->harga_awal : null,
                    'status' => $slot->status,
                    'is_promo' => (bool) $slot->is_promo,
                    'catatan' => $slot->catatan ?? '',
                ];
            }),
            'dates_with_slots' => $datesWithSlots,
            'debug' => [
                'query_date' => $dateString,
                'total_slots_found' => $timeslots->count(),
                'lapangan_id' => $lapangan->id,
            ],
        ]);
    }

    public function storeBulkLapanganSlots(Request $request, $venueId, $lapanganId)
    {
        $venue = Pendaftaran::where('user_id', auth()->id())->findOrFail($venueId);
        $lapangan = $venue->lapangans()->findOrFail($lapanganId);

        $validated = $request->validate([
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_akhir' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'jam_mulai' => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i', 'after:jam_mulai'],
            'harga' => ['required', 'integer', 'min:0'],
            'status' => ['required', Rule::in(['available', 'booked', 'blocked'])],
            'skip_days' => ['nullable', 'array'],
            'skip_days.*' => ['nullable', 'string'],
        ]);

        $tanggalMulai = Carbon::parse($validated['tanggal_mulai']);
        $tanggalAkhir = Carbon::parse($validated['tanggal_akhir']);
        
        // Check if range is more than 1 year
        $daysDiff = $tanggalMulai->diffInDays($tanggalAkhir);
        if ($daysDiff > 365) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Rentang tanggal maksimal 1 tahun (365 hari).',
                    'errors' => ['tanggal_akhir' => ['Rentang tanggal maksimal 1 tahun (365 hari).']]
                ], 422);
            }
            return redirect()->back()
                ->withErrors(['tanggal_akhir' => 'Rentang tanggal maksimal 1 tahun (365 hari).'])
                ->withInput();
        }

        // Parse jam operasional
        $jamMulai = Carbon::createFromFormat('H:i', $validated['jam_mulai']);
        $jamSelesai = Carbon::createFromFormat('H:i', $validated['jam_selesai']);
        
        // Calculate slots per day (1 hour intervals)
        $startTime = $jamMulai->copy();
        $slotsPerDay = [];
        while ($startTime < $jamSelesai) {
            $slotStart = $startTime->copy();
            $slotEnd = $startTime->copy()->addHour();
            
            if ($slotEnd > $jamSelesai) {
                $slotEnd = $jamSelesai->copy();
            }
            
            $slotsPerDay[] = [
                'start' => $slotStart->format('H:i'),
                'end' => $slotEnd->format('H:i'),
            ];
            
            $startTime = $slotEnd->copy();
            
            if ($startTime >= $jamSelesai) {
                break;
            }
        }

        // Get skip days
        $skipDays = [];
        $skipDaysArray = $validated['skip_days'] ?? [];
        
        if (in_array('weekend', $skipDaysArray)) {
            $skipDays = [0, 6]; // Sunday and Saturday
        } else {
            foreach ($skipDaysArray as $day) {
                if ($day === '0' || $day === '6' || is_numeric($day)) {
                    $skipDays[] = (int) $day;
                }
            }
        }
        $skipDays = array_unique($skipDays);

        // Generate slots for all dates
        $allSlots = [];
        $currentDate = $tanggalMulai->copy();
        $harga = $validated['harga'];
        $status = $validated['status'];
        $isPromo = false; // Default no promo for bulk
        $catatan = 'Generated via Bulk Schedule';

        while ($currentDate <= $tanggalAkhir) {
            // Carbon dayOfWeek: 0 = Sunday, 1 = Monday, ..., 6 = Saturday
            $dayOfWeek = $currentDate->dayOfWeek;
            
            // Skip if day is in skip list
            if (!in_array($dayOfWeek, $skipDays)) {
                // Ensure date is in Y-m-d format (start of day to avoid timezone issues)
                $tanggal = $currentDate->copy()->startOfDay()->format('Y-m-d');
                
                // Create slots for this day
                foreach ($slotsPerDay as $slot) {
                    // Check if slot already exists using whereDate for date comparison
                    $existingSlot = LapanganSlot::where('lapangan_id', $lapangan->id)
                        ->whereDate('tanggal', $tanggal)
                        ->where('jam_mulai', $slot['start'])
                        ->where('jam_selesai', $slot['end'])
                        ->first();
                    
                    // Skip if slot already exists
                    if (!$existingSlot) {
                        $allSlots[] = [
                            'lapangan_id' => $lapangan->id,
                            'tanggal' => $tanggal,
                            'jam_mulai' => $slot['start'],
                            'jam_selesai' => $slot['end'],
                            'harga' => $harga,
                            'harga_awal' => null,
                            'status' => $status,
                            'is_promo' => $isPromo,
                            'catatan' => $catatan,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
            }
            
            $currentDate->addDay();
        }

        // Insert all slots in batches (to avoid memory issues with large datasets)
        $batchSize = 500;
        $totalCreated = 0;
        
        if (!empty($allSlots)) {
            foreach (array_chunk($allSlots, $batchSize) as $chunk) {
                LapanganSlot::insert($chunk);
                $totalCreated += count($chunk);
            }
        }

        $validDays = $tanggalMulai->copy();
        $dayCount = 0;
        while ($validDays <= $tanggalAkhir) {
            if (!in_array($validDays->dayOfWeek, $skipDays)) {
                $dayCount++;
            }
            $validDays->addDay();
        }

        // Get list of dates that were created (for display)
        $createdDates = [];
        $checkDate = $tanggalMulai->copy();
        while ($checkDate <= $tanggalAkhir) {
            if (!in_array($checkDate->dayOfWeek, $skipDays)) {
                $createdDates[] = $checkDate->format('Y-m-d');
            }
            $checkDate->addDay();
        }

        $message = "Berhasil membuat {$totalCreated} slot jadwal untuk {$dayCount} hari ({$tanggalMulai->format('d M Y')} - {$tanggalAkhir->format('d M Y')}).";

        // If AJAX request, return JSON
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'created' => $totalCreated,
                'days' => $dayCount,
                'date' => $tanggalMulai->format('Y-m-d'), // Return first date for reload
                'date_range' => [
                    'start' => $tanggalMulai->format('Y-m-d'),
                    'end' => $tanggalAkhir->format('Y-m-d'),
                ],
                'created_dates' => $createdDates, // List of all dates that have slots now
            ]);
        }

        return redirect()
            ->route('fasilitas.lapangan.jadwal', [
                $venue->id,
                $lapangan->id,
                'date' => $tanggalMulai->format('Y-m-d'),
            ])
            ->with('success', $message);
    }

    public function storeLapanganSlot(Request $request, $venueId, $lapanganId)
    {
        $venue = Pendaftaran::where('user_id', auth()->id())->findOrFail($venueId);
        $lapangan = $venue->lapangans()->findOrFail($lapanganId);

        $validated = $request->validate([
            'tanggal' => ['required', 'date'],
            'jam_mulai' => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i', 'after:jam_mulai'],
            'harga_awal' => ['required', 'integer', 'min:0'],
            'harga' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', Rule::in(['available', 'booked', 'blocked'])],
            'promo_status' => ['nullable', Rule::in(['none', 'promo'])],
            'catatan' => ['nullable', 'string', 'max:255'],
            'generate_multiple' => ['nullable', 'boolean'],
        ]);

        $hargaAwalInput = (int) $validated['harga_awal'];
        $hargaDiskonInput = $request->filled('harga') ? (int) $validated['harga'] : null;

        // Custom validation: jika harga promo diisi, harus lebih kecil dari harga awal
        if ($hargaDiskonInput !== null && $hargaDiskonInput >= $hargaAwalInput) {
            $msg = 'Harga setelah diskon harus lebih kecil dari harga awal.';
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $msg,
                    'errors' => ['harga' => [$msg]]
                ], 422);
            }
            return redirect()->back()
                ->withErrors(['harga' => $msg])
                ->withInput();
        }

        if ($hargaDiskonInput !== null && $hargaDiskonInput < $hargaAwalInput) {
            $harga = $hargaDiskonInput;
            $hargaAwal = $hargaAwalInput;
            $isPromo = true;
        } else {
            $harga = $hargaAwalInput;
            $hargaAwal = null;
            $isPromo = false;
        }

        $generateMultiple = $request->has('generate_multiple') && $request->generate_multiple == '1';
        $tanggal = Carbon::parse($validated['tanggal'])->toDateString();
        $status = $validated['status'];
        $catatan = $validated['catatan'] ?? null;

        if ($generateMultiple) {
            // Generate multiple slots per 1 jam
            $jamMulai = Carbon::createFromFormat('H:i', $validated['jam_mulai']);
            $jamSelesai = Carbon::createFromFormat('H:i', $validated['jam_selesai']);
            
            $slots = [];
            $current = $jamMulai->copy();
            
            while ($current < $jamSelesai) {
                $slotStart = $current->copy();
                $slotEnd = $current->copy()->addHour();
                
                // Jika slotEnd melebihi jamSelesai, set ke jamSelesai dan break loop
                if ($slotEnd > $jamSelesai) {
                    $slotEnd = $jamSelesai->copy();
                }
                
                $slots[] = [
                    'lapangan_id' => $lapangan->id,
                    'tanggal' => $tanggal,
                    'jam_mulai' => $slotStart->format('H:i'),
                    'jam_selesai' => $slotEnd->format('H:i'),
                    'harga' => $harga,
                    'harga_awal' => $hargaAwal,
                    'status' => $status,
                    'is_promo' => $isPromo,
                    'catatan' => $catatan,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                
                // Update current untuk loop berikutnya
                $current = $slotEnd->copy();
                
                // Jika sudah mencapai atau melebihi jamSelesai, break
                if ($current >= $jamSelesai) {
                    break;
                }
            }
            
            // Insert semua slots sekaligus
            if (!empty($slots)) {
                LapanganSlot::insert($slots);
                $slotCount = count($slots);
                
                // If AJAX request, return JSON
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => "Berhasil membuat {$slotCount} slot jadwal secara otomatis.",
                        'date' => Carbon::parse($validated['tanggal'])->format('Y-m-d'),
                    ]);
                }
                
                return redirect()
                    ->route('fasilitas.lapangan.jadwal', [
                        $venue->id,
                        $lapangan->id,
                        'date' => Carbon::parse($validated['tanggal'])->format('Y-m-d'),
                    ])
                    ->with('success', "Berhasil membuat {$slotCount} slot jadwal secara otomatis.");
            }
        } else {
            // Single slot (behavior lama)
            $slot = LapanganSlot::create([
                'lapangan_id' => $lapangan->id,
                'tanggal' => $tanggal,
                'jam_mulai' => $validated['jam_mulai'],
                'jam_selesai' => $validated['jam_selesai'],
                'harga' => $harga,
                'harga_awal' => $hargaAwal,
                'status' => $status,
                'is_promo' => $isPromo,
                'catatan' => $catatan,
            ]);

            // If AJAX request, return JSON
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Slot jadwal berhasil ditambahkan.',
                    'date' => Carbon::parse($validated['tanggal'])->format('Y-m-d'),
                    'slot' => [
                        'id' => $slot->id,
                        'jam_mulai' => $slot->jam_mulai,
                        'jam_selesai' => $slot->jam_selesai,
                        'harga' => (int) $slot->harga,
                        'harga_awal' => $slot->harga_awal ? (int) $slot->harga_awal : null,
                        'status' => $slot->status,
                        'is_promo' => (bool) $slot->is_promo,
                        'catatan' => $slot->catatan ?? '',
                    ]
                ]);
            }

            return redirect()
                ->route('fasilitas.lapangan.jadwal', [
                    $venue->id,
                    $lapangan->id,
                    'date' => Carbon::parse($validated['tanggal'])->format('Y-m-d'),
                ])
                ->with('success', 'Slot jadwal berhasil ditambahkan.');
        }
    }

    public function editLapanganSlot(Request $request, $venueId, $lapanganId, $slotId)
    {
        $venue = Pendaftaran::where('user_id', auth()->id())->findOrFail($venueId);
        $lapangan = $venue->lapangans()->findOrFail($lapanganId);
        $slot = $lapangan->slots()->findOrFail($slotId);

        // Return JSON for AJAX request
        if ($request->ajax() || $request->wantsJson() || $request->header('Accept') === 'application/json') {
            // Format waktu ke HH:mm untuk input type="time"
            $jamMulai = $slot->jam_mulai;
            $jamSelesai = $slot->jam_selesai;
            
            // Jika format time dari database, pastikan format HH:mm
            if ($jamMulai instanceof \DateTime || $jamMulai instanceof \Carbon\Carbon) {
                $jamMulai = $jamMulai->format('H:i');
            } elseif (is_string($jamMulai)) {
                // Parse string time ke format HH:mm
                $parts = explode(':', $jamMulai);
                if (count($parts) >= 2) {
                    $jamMulai = str_pad($parts[0], 2, '0', STR_PAD_LEFT) . ':' . str_pad($parts[1], 2, '0', STR_PAD_LEFT);
                }
            }
            
            if ($jamSelesai instanceof \DateTime || $jamSelesai instanceof \Carbon\Carbon) {
                $jamSelesai = $jamSelesai->format('H:i');
            } elseif (is_string($jamSelesai)) {
                $parts = explode(':', $jamSelesai);
                if (count($parts) >= 2) {
                    $jamSelesai = str_pad($parts[0], 2, '0', STR_PAD_LEFT) . ':' . str_pad($parts[1], 2, '0', STR_PAD_LEFT);
                }
            }
            
            return response()->json([
                'success' => true,
                'slot' => [
                    'id' => $slot->id,
                    'jam_mulai' => $jamMulai,
                    'jam_selesai' => $jamSelesai,
                    'harga' => (int) $slot->harga,
                    'harga_awal' => $slot->harga_awal ? (int) $slot->harga_awal : null,
                    'status' => $slot->status,
                    'is_promo' => (bool) $slot->is_promo,
                    'catatan' => $slot->catatan ?? '',
                ]
            ]);
        }

        // Fallback for non-AJAX requests
        return redirect()
            ->route('fasilitas.lapangan.jadwal', [
                $venue->id,
                $lapangan->id,
                'date' => $slot->tanggal->format('Y-m-d'),
            ]);
    }

    public function updateLapanganSlot(Request $request, $venueId, $lapanganId, $slotId)
    {
        $venue = Pendaftaran::where('user_id', auth()->id())->findOrFail($venueId);
        $lapangan = $venue->lapangans()->findOrFail($lapanganId);
        $slot = $lapangan->slots()->findOrFail($slotId);

        $validated = $request->validate([
            'tanggal' => ['required', 'date'],
            'jam_mulai' => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i', 'after:jam_mulai'],
            'harga_awal' => ['required', 'integer', 'min:0'],
            'harga' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', Rule::in(['available', 'booked', 'blocked'])],
            'promo_status' => ['nullable', Rule::in(['none', 'promo'])],
            'catatan' => ['nullable', 'string', 'max:255'],
        ]);

        $hargaAwalInput = (int) $validated['harga_awal'];
        $hargaDiskonInput = $request->filled('harga') ? (int) $validated['harga'] : null;

        if ($hargaDiskonInput !== null && $hargaDiskonInput >= $hargaAwalInput) {
            $msg = 'Harga setelah diskon harus lebih kecil dari harga awal.';
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $msg,
                    'errors' => ['harga' => [$msg]]
                ], 422);
            }
            return redirect()->back()->withErrors(['harga' => $msg])->withInput();
        }

        if ($hargaDiskonInput !== null && $hargaDiskonInput < $hargaAwalInput) {
            $harga = $hargaDiskonInput;
            $hargaAwal = $hargaAwalInput;
            $isPromo = true;
        } else {
            $harga = $hargaAwalInput;
            $hargaAwal = null;
            $isPromo = false;
        }

        // Simpan tanggal asli untuk response
        $tanggalAsli = $slot->tanggal->format('Y-m-d');

        $slot->update([
            'tanggal' => $slot->tanggal,
            'jam_mulai' => $validated['jam_mulai'],
            'jam_selesai' => $validated['jam_selesai'],
            'harga' => $harga,
            'harga_awal' => $hargaAwal,
            'status' => $validated['status'],
            'is_promo' => $isPromo,
            'catatan' => $validated['catatan'] ?? null,
        ]);

        // If AJAX request, return JSON
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Slot jadwal berhasil diperbarui.',
                'date' => $tanggalAsli,
            ]);
        }

        return redirect()
            ->route('fasilitas.lapangan.jadwal', [
                $venue->id,
                $lapangan->id,
                'date' => $tanggalAsli,
            ])
            ->with('success', 'Slot jadwal berhasil diperbarui.');
    }

    public function deleteLapanganSlot(Request $request, $venueId, $lapanganId, $slotId)
    {
        $venue = Pendaftaran::where('user_id', auth()->id())->findOrFail($venueId);
        $lapangan = $venue->lapangans()->findOrFail($lapanganId);
        $slot = $lapangan->slots()->findOrFail($slotId);

        $tanggal = $slot->tanggal->format('Y-m-d');
        $slot->delete();

        // If AJAX request, return JSON
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Slot jadwal berhasil dihapus.',
                'date' => $tanggal,
            ]);
        }

        return redirect()
            ->route('fasilitas.lapangan.jadwal', [
                $venue->id,
                $lapangan->id,
                'date' => $tanggal,
            ])
            ->with('success', 'Slot jadwal berhasil dihapus.');
    }

    public function deleteSlotsByDate(Request $request, $venueId, $lapanganId)
    {
        $venue = Pendaftaran::where('user_id', auth()->id())->findOrFail($venueId);
        $lapangan = $venue->lapangans()->findOrFail($lapanganId);

        $validated = $request->validate([
            'tanggal' => ['required', 'date'],
        ]);

        $tanggal = \Carbon\Carbon::parse($validated['tanggal'])->toDateString();

        // Delete all slots on this date for this court
        $deletedCount = $lapangan->slots()->whereDate('tanggal', $tanggal)->delete();

        // If AJAX request, return JSON
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Berhasil menghapus {$deletedCount} slot jadwal.",
                'date' => $tanggal,
            ]);
        }

        return redirect()
            ->route('fasilitas.lapangan.jadwal', [
                $venue->id,
                $lapangan->id,
                'date' => $tanggal,
            ])
            ->with('success', "Berhasil menghapus {$deletedCount} slot jadwal.");
    }

    /**
     * Tampilkan form edit venue
     */
    public function editVenue($id)
    {
        $venue = Pendaftaran::with('galleries')->findOrFail($id);
        
        // Fasilitas sudah di-cast sebagai array di model
        $fasilitas = $venue->fasilitas;
        if (!is_array($fasilitas)) {
            if (empty($fasilitas)) {
                $fasilitas = [];
            } elseif (is_string($fasilitas)) {
                // Fallback untuk data lama yang masih JSON string
                $decoded = json_decode($fasilitas, true);
                $fasilitas = is_array($decoded) ? $decoded : [];
            } else {
                $fasilitas = [];
            }
        }
        
        return view('pemiliklapangan.Fasilitas.editvenue', compact('venue', 'fasilitas'));
    }

    /**
     * Update data venue
     */
    public function updateVenue(Request $request, $id)
    {
        // Hanya bisa update venue milik user yang login
        $venue = Pendaftaran::where('user_id', auth()->id())->findOrFail($id);
        
        // Validasi input
        $request->validate([
            'namavenue' => 'required|string|max:255',
            'provinsi' => 'required|string|max:100',
            'kota' => 'required|string|max:100',
            'kategori' => 'required|array|min:1|max:5',
            'kategori.*' => 'required|string|max:100',
            'custom_olahraga' => 'nullable|array|max:5',
            'custom_olahraga.*' => 'nullable|string|max:100',
            'nomor_telepon' => 'required|string|max:20',
            'email_venue' => 'required|email|max:255',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'video_review' => 'nullable|url',
            'detail' => 'nullable|string',
            'aturan' => 'nullable|string',
            'lokasi' => 'nullable|string',
            'fasilitas_venue' => 'nullable|array',
            'custom_fasilitas' => 'nullable|array',
            'custom_fasilitas.*' => 'nullable|string|max:255',
            'galeri_foto' => 'nullable|array',
            'galeri_foto.*' => 'image|mimes:jpg,jpeg,png|max:2048',
            'jam_operasional_hari' => 'nullable|array',
            'jam_operasional_hari.*' => 'nullable|string',
            'jam_operasional_buka' => 'nullable|array',
            'jam_operasional_buka.*' => 'nullable|string',
            'jam_operasional_tutup' => 'nullable|array',
            'jam_operasional_tutup.*' => 'nullable|string',
        ]);

        // Handle kategori: gabungkan kategori[] dan custom_olahraga[]
        $allKategori = [];
        if ($request->has('kategori') && is_array($request->kategori)) {
            $allKategori = array_merge($allKategori, $request->kategori);
        }
        if ($request->has('custom_olahraga') && is_array($request->custom_olahraga)) {
            // Filter out empty values
            $customOlahraga = array_filter($request->custom_olahraga, function($value) {
                return !empty(trim($value));
            });
            $allKategori = array_merge($allKategori, $customOlahraga);
        }

        // Validate total kategori (1-5)
        if (empty($allKategori) || count($allKategori) > 5) {
            return redirect()->back()
                ->withErrors(['kategori' => 'Pilih 1-5 cabang olahraga'])
                ->withInput();
        }

        // Update logo jika ada file baru
        if ($request->hasFile('logo')) {
            // Hapus logo lama jika ada
            if ($venue->logo && Storage::disk('public')->exists($venue->logo)) {
                Storage::disk('public')->delete($venue->logo);
            }
            $pathLogo = $request->file('logo')->store('logovenue', 'public');
            $venue->logo = $pathLogo;
        }

        // Update data informasi
        $venue->namavenue = $request->namavenue;
        $venue->provinsi = $request->provinsi;
        $venue->kota = $request->kota;
        $venue->kategori = $allKategori; // Array of kategori
        $venue->nomor_telepon = $request->nomor_telepon;
        $venue->email_venue = $request->email_venue;

        // Handle fasilitas: gabungkan fasilitas_venue dan custom_fasilitas
        $allFasilitas = [];
        if ($request->has('fasilitas_venue') && is_array($request->fasilitas_venue)) {
            $allFasilitas = array_merge($allFasilitas, $request->fasilitas_venue);
        }
        if ($request->has('custom_fasilitas') && is_array($request->custom_fasilitas)) {
            // Filter out empty values
            $customFasilitas = array_filter($request->custom_fasilitas, function($value) {
                return !empty(trim($value));
            });
            $allFasilitas = array_merge($allFasilitas, $customFasilitas);
        }

        // Handle jam operasional
        $jamOperasional = [];
        if ($request->has('jam_operasional_hari') && is_array($request->jam_operasional_hari)) {
            $haris = $request->jam_operasional_hari;
            $bukas = $request->jam_operasional_buka ?? [];
            $tutups = $request->jam_operasional_tutup ?? [];
            
            foreach ($haris as $index => $hari) {
                if (!empty($hari)) {
                    $jamOperasional[] = [
                        'hari' => $hari,
                        'buka' => $bukas[$index] ?? '',
                        'tutup' => $tutups[$index] ?? '',
                    ];
                }
            }
        }

        // Update data detail
        $venue->video_review = $request->video_review;
        $venue->detail = $request->detail;
        $venue->aturan = $request->aturan;
        $venue->lokasi = $request->lokasi;
        $venue->fasilitas = !empty($allFasilitas) ? json_encode($allFasilitas) : null;
        $venue->jam_operasional = !empty($jamOperasional) ? $jamOperasional : null;
        
        $venue->save();

        // Handle upload galeri foto multiple baru
        if ($request->hasFile('galeri_foto')) {
            $existingCount = $venue->galleries()->count();
            foreach ($request->file('galeri_foto') as $index => $file) {
                $pathFoto = $file->store('venue_galleries', 'public');
                
                VenueGallery::create([
                    'pendaftaran_id' => $venue->id,
                    'foto' => $pathFoto,
                    'urutan' => $existingCount + $index + 1,
                ]);
            }
        }

        return redirect()->route('fasilitas.detail', $venue->id)
                         ->with('success', 'Data venue berhasil diperbarui!');
    }

    /**
     * Menyimpan data dari form Informasi Venue
     */
    public function insertinform(Request $request)
    {
        $validatedData = $request->validate([
            'logo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'namavenue' => 'required|string|max:255',
            'provinsi' => 'required|string|max:100',
            'kota' => 'required|string|max:100',
            'kategori' => 'required|array|min:1|max:5',
            'kategori.*' => 'required|string|max:100',
            'custom_olahraga' => 'nullable|array|max:5',
            'custom_olahraga.*' => 'nullable|string|max:100',
            'nomor_telepon' => 'required|string|max:20',
            'email_venue' => 'required|email|max:255',
        ]);

        // Handle kategori: gabungkan kategori[] dan custom_olahraga[]
        $allKategori = [];
        if ($request->has('kategori') && is_array($request->kategori)) {
            $allKategori = array_merge($allKategori, $request->kategori);
        }
        if ($request->has('custom_olahraga') && is_array($request->custom_olahraga)) {
            // Filter out empty values
            $customOlahraga = array_filter($request->custom_olahraga, function($value) {
                return !empty(trim($value));
            });
            $allKategori = array_merge($allKategori, $customOlahraga);
        }

        // Validate total kategori (1-5)
        if (empty($allKategori) || count($allKategori) > 5) {
            return redirect()->back()
                ->withErrors(['kategori' => 'Pilih 1-5 cabang olahraga'])
                ->withInput();
        }

        // Upload logo (banner)
        $pathLogo = $request->file('logo')->store('logovenue', 'public');

        // Simpan ke database
        $pendaftaran = new Pendaftaran();
        $pendaftaran->user_id = auth()->id(); // Simpan user_id dari user yang login
        $pendaftaran->logo = $pathLogo;
        $pendaftaran->namavenue = $validatedData['namavenue'];
        $pendaftaran->provinsi = $validatedData['provinsi'];
        $pendaftaran->kota = $validatedData['kota'];
        $pendaftaran->kategori = $allKategori; // Array of kategori
        $pendaftaran->nomor_telepon = $validatedData['nomor_telepon'];
        $pendaftaran->email_venue = $validatedData['email_venue'];
        $pendaftaran->syarat_disetujui = false; // Default menunggu verifikasi admin
        $pendaftaran->save();

        // Simpan ID venue di session untuk step berikutnya
        session(['venue_id' => $pendaftaran->id]);

        // Redirect ke halaman detail sambil kirim ID-nya
        return redirect()->route('detail', $pendaftaran->id)
                         ->with('success', 'Data venue berhasil disimpan!');
    }

    /**
     * Simpan data detail venue.
     */
    public function insertdetail(Request $request)
    {
        // Validasi input
        $request->validate([
            'venue_id' => 'required|exists:pendaftarans,id',
            'video_review' => 'nullable|url',
            'detail' => 'nullable|string',
            'aturan' => 'nullable|string',
            'lokasi' => 'nullable|string',
            'fasilitas_venue' => 'nullable|array',
            'custom_fasilitas' => 'nullable|array',
            'custom_fasilitas.*' => 'nullable|string|max:255',
            'galeri_foto' => 'nullable|array',
            'galeri_foto.*' => 'image|mimes:jpg,jpeg,png|max:2048',
            'jam_operasional_hari' => 'nullable|array',
            'jam_operasional_hari.*' => 'nullable|string',
            'jam_operasional_buka' => 'nullable|array',
            'jam_operasional_buka.*' => 'nullable|string',
            'jam_operasional_tutup' => 'nullable|array',
            'jam_operasional_tutup.*' => 'nullable|string',
        ]);

        // Dapatkan ID venue dari request
        $venueId = $request->venue_id;

        // Pastikan venue milik user yang login
        $pendaftaran = Pendaftaran::where('user_id', auth()->id())->findOrFail($venueId);

        // Handle fasilitas: gabungkan fasilitas_venue dan custom_fasilitas
        $allFasilitas = [];
        if ($request->has('fasilitas_venue') && is_array($request->fasilitas_venue)) {
            $allFasilitas = array_merge($allFasilitas, $request->fasilitas_venue);
        }
        if ($request->has('custom_fasilitas') && is_array($request->custom_fasilitas)) {
            // Filter out empty values
            $customFasilitas = array_filter($request->custom_fasilitas, function($value) {
                return !empty(trim($value));
            });
            $allFasilitas = array_merge($allFasilitas, $customFasilitas);
        }

        // Handle jam operasional
        $jamOperasional = [];
        if ($request->has('jam_operasional_hari') && is_array($request->jam_operasional_hari)) {
            $haris = $request->jam_operasional_hari;
            $bukas = $request->jam_operasional_buka ?? [];
            $tutups = $request->jam_operasional_tutup ?? [];
            
            foreach ($haris as $index => $hari) {
                if (!empty($hari)) {
                    $jamOperasional[] = [
                        'hari' => $hari,
                        'buka' => $bukas[$index] ?? '',
                        'tutup' => $tutups[$index] ?? '',
                    ];
                }
            }
        }

        // Update data
        $pendaftaran->video_review = $request->video_review;
        $pendaftaran->detail = $request->detail;
        $pendaftaran->aturan = $request->aturan;
        $pendaftaran->lokasi = $request->lokasi;
        $pendaftaran->fasilitas = !empty($allFasilitas) ? json_encode($allFasilitas) : null;
        $pendaftaran->jam_operasional = !empty($jamOperasional) ? $jamOperasional : null;
        $pendaftaran->save();

        // Handle upload galeri foto multiple
        if ($request->hasFile('galeri_foto')) {
            foreach ($request->file('galeri_foto') as $index => $file) {
                $pathFoto = $file->store('venue_galleries', 'public');
                
                VenueGallery::create([
                    'pendaftaran_id' => $venueId,
                    'foto' => $pathFoto,
                    'urutan' => $index + 1,
                ]);
            }
        }

        // Update session dengan ID terbaru
        session(['venue_id' => $venueId]);

        // Arahkan ke halaman "Selesai" (Onboarding End)
        return redirect()->route('end')->with('success', 'Detail venue berhasil disimpan!');
    }

    /**
     * Menampilkan halaman promo/diskon
     */
    public function promo()
    {
        $userId = auth()->id();
        
        // Ambil semua venue milik user
        $venues = Pendaftaran::where('user_id', $userId)
            ->with(['lapangans.slots', 'galleries'])
            ->get();
        
        // Kumpulkan semua promo dari semua venue
        $promos = collect();
        
        foreach ($venues as $venue) {
            foreach ($venue->lapangans as $lapangan) {
                // Ambil slot promo (is_promo = true atau harga_awal > harga)
                $promoSlots = $lapangan->slots()
                    ->where(function($query) {
                        $query->where('is_promo', true)
                              ->orWhere(function($q) {
                                  $q->whereNotNull('harga_awal')
                                    ->whereColumn('harga_awal', '>', 'harga');
                              });
                    })
                    ->where('status', 'available')
                    ->get();
                
                if ($promoSlots->isEmpty()) {
                    continue;
                }
                
                // Hitung periode promo (dari tanggal terawal sampai terakhir)
                $tanggalMin = $promoSlots->min('tanggal');
                $tanggalMax = $promoSlots->max('tanggal');
                
                // Ambil slot pertama untuk info promo
                $firstSlot = $promoSlots->first();
                
                // Hitung diskon
                $diskonPercent = 0;
                if ($firstSlot->harga_awal && $firstSlot->harga_awal > $firstSlot->harga) {
                    $diskonPercent = round((($firstSlot->harga_awal - $firstSlot->harga) / $firstSlot->harga_awal) * 100);
                }
                
                // Ambil gambar venue (logo atau gallery pertama)
                $imageUrl = $venue->logo ? asset('storage/' . $venue->logo) : null;
                if (!$imageUrl && $venue->galleries->isNotEmpty()) {
                    $imageUrl = asset('fotogaleri/' . $venue->galleries->first()->foto);
                }
                if (!$imageUrl) {
                    $imageUrl = asset('assets/olgasehat-icon.png');
                }
                
                // Judul promo
                $judulPromo = $firstSlot->catatan 
                    ? $firstSlot->catatan 
                    : ($diskonPercent > 0 ? "Diskon {$diskonPercent}%" : "Promo Spesial");
                
                $promos->push([
                    'venue_id' => $venue->id,
                    'venue_name' => $venue->namavenue,
                    'lapangan_name' => $lapangan->nama,
                    'image_url' => $imageUrl,
                    'judul' => $judulPromo,
                    'tanggal_min' => $tanggalMin,
                    'tanggal_max' => $tanggalMax,
                    'harga' => $firstSlot->harga,
                    'harga_awal' => $firstSlot->harga_awal,
                    'diskon_percent' => $diskonPercent,
                    'total_slots' => $promoSlots->count(),
                ]);
            }
        }
        
        // Sort by tanggal terbaru
        $promos = $promos->sortByDesc('tanggal_min')->values();

        // Ambil semua event milik pemilik lapangan
        $events = \App\Models\Activity::where('pemilik_id', $userId)
            ->where('jenis', 'event')
            ->get();

        // Ambil semua komunitas milik pemilik lapangan
        $komunitas = \App\Models\Activity::where('pemilik_id', $userId)
            ->where('jenis', 'komunitas')
            ->get();
        
        return view('pemiliklapangan.Promo.promo', compact('promos', 'venues', 'events', 'komunitas'));
    }

    /**
     * Menyimpan promo baru
     */
    public function storePromo(Request $request)
    {
        $request->validate([
            'tipe_promo' => 'required|in:venue,event,komunitas',
            'target_id' => 'required|integer',
            'diskon' => 'required|integer|min:1|max:100',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date',
            'catatan' => 'required|string|max:255',
        ]);
        
        $diskon = $request->diskon;
        $catatan = $request->catatan;
        
        if ($request->tipe_promo === 'venue') {
            $lapangan = \App\Models\Lapangan::whereHas('venue', function($query) {
                $query->where('user_id', auth()->id());
            })->findOrFail($request->target_id);
            
            $startDate = $request->tanggal_mulai ?? now()->toDateString();
            $endDate = $request->tanggal_selesai ?? now()->toDateString();
            
            $slots = $lapangan->slots()
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->get();
                
            if ($slots->isEmpty()) {
                return redirect()->back()->with('error', 'Tidak ada slot jadwal yang ditemukan pada rentang tanggal tersebut.');
            }
            
            foreach ($slots as $slot) {
                if (empty($slot->harga_awal)) {
                    $slot->harga_awal = $slot->harga;
                }
                $slot->harga = $slot->harga_awal - ($slot->harga_awal * $diskon / 100);
                $slot->is_promo = true;
                $slot->catatan = $catatan;
                $slot->save();
            }
            
            return redirect()->back()->with('success', 'Promo booking venue berhasil diterapkan pada slot jadwal terkait!');
            
        } else {
            // event or komunitas
            $activity = \App\Models\Activity::where('pemilik_id', auth()->id())
                ->where('jenis', $request->tipe_promo)
                ->findOrFail($request->target_id);
                
            if ($activity->biaya_bergabung === 'gratis') {
                return redirect()->back()->with('error', 'Aktivitas gratis tidak dapat diberi diskon.');
            }
            
            if ($activity->harga <= 0) {
                return redirect()->back()->with('error', 'Harga aktivitas tidak valid.');
            }
            
            // Apply discount
            $hargaBaru = $activity->harga - ($activity->harga * $diskon / 100);
            $activity->harga = $hargaBaru;
            
            if (!str_contains($activity->deskripsi, "[PROMO DISKON")) {
                $activity->deskripsi = "[PROMO DISKON {$diskon}%: {$catatan}]\n\n" . $activity->deskripsi;
            }
            $activity->save();
            
            return redirect()->back()->with('success', 'Promo diskon berhasil diterapkan pada aktivitas terkait!');
        }
    }
}