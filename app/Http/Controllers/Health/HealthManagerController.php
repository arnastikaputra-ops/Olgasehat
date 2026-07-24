<?php

namespace App\Http\Controllers\Health;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\ClinicGallery;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\HealthService;
use App\Models\HealthBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class HealthManagerController extends Controller
{
    /**
     * Dashboard pengelola kesehatan
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Ambil klinik yang dimiliki user
        $clinics = Clinic::where('user_id', $user->id)->get();
        $clinicIds = $clinics->pluck('id');
        
        // Statistik
        $totalClinics = $clinics->count();
        $totalDoctors = Doctor::whereIn('clinic_id', $clinicIds)->count();
        $totalBookings = HealthBooking::whereIn('clinic_id', $clinicIds)->count();
        $pendingBookings = HealthBooking::whereIn('clinic_id', $clinicIds)
            ->where('status', 'pending')
            ->count();
        $todayBookings = HealthBooking::whereIn('clinic_id', $clinicIds)
            ->whereDate('tanggal', today())
            ->count();
        
        // Booking hari ini
        $bookingsToday = HealthBooking::whereIn('clinic_id', $clinicIds)
            ->whereDate('tanggal', today())
            ->with(['clinic', 'doctor', 'user'])
            ->orderBy('jam')
            ->get();
        
        // Booking pending
        $bookingsPending = HealthBooking::whereIn('clinic_id', $clinicIds)
            ->where('status', 'pending')
            ->with(['clinic', 'doctor', 'user'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        return view('pemilikkesehatan.Dashboard.dashboard', compact(
            'totalClinics',
            'totalDoctors',
            'totalBookings',
            'pendingBookings',
            'todayBookings',
            'bookingsToday',
            'bookingsPending',
            'clinics'
        ));
    }

    /**
     * List klinik yang dimiliki
     */
    public function clinics()
    {
        $user = Auth::user();
        $clinics = Clinic::where('user_id', $user->id)
            ->with(['category'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('pemilikkesehatan.Clinic.index', compact('clinics'));
    }

    /**
     * Form tambah klinik
     */
    public function createClinic()
    {
        return view('pemilikkesehatan.Clinic.create');
    }

    /**
     * Store klinik baru
     */
    public function storeClinic(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'tipe' => 'required|in:klinik,layanan',
            'deskripsi' => 'nullable|string',
            'motto' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',
            'kota' => 'nullable|string',
            'provinsi' => 'nullable|string',
            'nomor_telepon' => 'nullable|string',
            'email' => 'nullable|email',
            'hari_operasional' => 'nullable|array',
            'jam_buka' => 'nullable',
            'jam_tutup' => 'nullable',
            'jenis_layanan' => 'required|array|min:1',
            'jenis_layanan.*' => 'required|string|max:255',
            'fasilitas' => 'nullable|array',
            'fasilitas.*' => 'nullable|string|max:255',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'galeri_foto' => 'nullable|array|max:10',
            'galeri_foto.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->nama);
        $data['status'] = 'pending';
        $data['user_id'] = Auth::id();
        
        // Handle jenis layanan - simpan sebagai array JSON
        if ($request->has('jenis_layanan')) {
            $jenisLayanan = array_filter($request->jenis_layanan); // Hapus yang kosong
            $data['layanan_tersedia'] = array_values($jenisLayanan); // Re-index array
        }
        
        if ($request->has('fasilitas')) {
            $fasilitas = array_filter(array_map('trim', $request->fasilitas));
            $data['fasilitas'] = array_values($fasilitas);
        }

        // Hapus jenis_layanan dari data karena tidak ada di database
        unset($data['jenis_layanan']);

        // Handle upload banner (simpan di field logo untuk backward compatibility)
        if ($request->hasFile('banner')) {
            $image = $request->file('banner');
            $imageName = time() . '_banner_' . $image->getClientOriginalName();
            $image->move(public_path('fotoklinik'), $imageName);
            $data['logo'] = $imageName; // Simpan banner di field logo
        }

        // Buat clinic terlebih dahulu
        $clinic = Clinic::create($data);

        // Handle upload galeri foto multiple
        if ($request->hasFile('galeri_foto')) {
            $files = $request->file('galeri_foto');
            if (count($files) > 10) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Maksimal 10 gambar untuk galeri.');
            }
            
            foreach ($files as $index => $file) {
                $pathFoto = $file->store('clinic_galleries', 'public');
                
                ClinicGallery::create([
                    'clinic_id' => $clinic->id,
                    'foto' => $pathFoto,
                    'urutan' => $index + 1,
                ]);
            }
        }

        return redirect()->route('pengelola.clinics')
            ->with('success', 'Klinik berhasil ditambahkan dan menunggu verifikasi admin.');
    }

    /**
     * Edit klinik
     */
    public function editClinic($id)
    {
        $clinic = Clinic::where('user_id', Auth::id())->findOrFail($id);
        return view('pemilikkesehatan.Clinic.edit', compact('clinic'));
    }

    /**
     * Update klinik
     */
    public function updateClinic(Request $request, $id)
    {
        $clinic = Clinic::where('user_id', Auth::id())->findOrFail($id);
        
        $request->validate([
            'nama' => 'required|string|max:255',
            'tipe' => 'required|in:klinik,layanan',
            'deskripsi' => 'nullable|string',
            'motto' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',
            'kota' => 'nullable|string',
            'provinsi' => 'nullable|string',
            'nomor_telepon' => 'nullable|string',
            'email' => 'nullable|email',
            'hari_operasional' => 'nullable|array',
            'jam_buka' => 'nullable',
            'jam_tutup' => 'nullable',
            'jenis_layanan' => 'required|array|min:1',
            'jenis_layanan.*' => 'required|string|max:255',
            'fasilitas' => 'nullable|array',
            'fasilitas.*' => 'nullable|string|max:255',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'galeri_foto' => 'nullable|array|max:10',
            'galeri_foto.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->nama);
        
        // Handle jenis layanan - simpan sebagai array JSON
        if ($request->has('jenis_layanan')) {
            $jenisLayanan = array_filter($request->jenis_layanan); // Hapus yang kosong
            $data['layanan_tersedia'] = array_values($jenisLayanan); // Re-index array
        }
        
        if ($request->has('fasilitas')) {
            $fasilitas = array_filter(array_map('trim', $request->fasilitas));
            $data['fasilitas'] = array_values($fasilitas);
        }

        // Hapus jenis_layanan dari data karena tidak ada di database
        unset($data['jenis_layanan']);

        // Handle upload banner (simpan di field logo untuk backward compatibility)
        if ($request->hasFile('banner')) {
            // Hapus banner lama jika ada
            if ($clinic->logo && file_exists(public_path('fotoklinik/' . $clinic->logo))) {
                unlink(public_path('fotoklinik/' . $clinic->logo));
            }
            
            $image = $request->file('banner');
            $imageName = time() . '_banner_' . $image->getClientOriginalName();
            $image->move(public_path('fotoklinik'), $imageName);
            $data['logo'] = $imageName; // Simpan banner di field logo
        }

        $clinic->update($data);

        // Handle upload galeri foto multiple baru
        if ($request->hasFile('galeri_foto')) {
            $existingCount = $clinic->galleries()->count();
            $files = $request->file('galeri_foto');
            
            if ($existingCount + count($files) > 10) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Total galeri tidak boleh lebih dari 10 gambar.');
            }
            
            foreach ($files as $index => $file) {
                $pathFoto = $file->store('clinic_galleries', 'public');
                
                ClinicGallery::create([
                    'clinic_id' => $clinic->id,
                    'foto' => $pathFoto,
                    'urutan' => $existingCount + $index + 1,
                ]);
            }
        }

        return redirect()->route('pengelola.clinics')
            ->with('success', 'Klinik berhasil diperbarui.');
    }

    /**
     * Detail klinik
     */
    public function showClinic($id)
    {
        $clinic = Clinic::where('user_id', Auth::id())
            ->with(['doctors', 'services', 'bookings'])
            ->findOrFail($id);
        
        return view('pemilikkesehatan.Clinic.show', compact('clinic'));
    }
}

