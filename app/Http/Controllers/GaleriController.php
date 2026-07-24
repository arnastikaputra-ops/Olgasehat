<?php

namespace App\Http\Controllers;

use App\Models\Galeri;
use HTMLPurifier;
use HTMLPurifier_Config;
use Illuminate\Http\Request;
use PDF;


class GaleriController extends Controller
{
    public function galeri(Request $request) {
        $query = Galeri::query();
        
        // Filter berdasarkan kategori
        if($request->has('kategori') && $request->kategori != ''){
            $query->where('kategori', $request->kategori);
        }
        
        // Search (jika ada field title di masa depan)
        if($request->has('search') && $request->search != ''){
            $query->where('foto', 'LIKE', '%'.$request->search.'%');
        }
        
        $data = $query->orderBy('kategori', 'asc')
                      ->orderBy('urutan', 'asc')
                      ->orderBy('created_at', 'desc')
                      ->paginate(10);
    
        return view('backend.galeri.galeri', compact('data'));
    }

    public function homeBanner(Request $request) {
        $query = Galeri::where('kategori', 'home_banner');
        
        if($request->has('search') && $request->search != ''){
            $query->where('foto', 'LIKE', '%'.$request->search.'%');
        }
        
        $data = $query->orderBy('urutan', 'asc')
                      ->orderBy('created_at', 'desc')
                      ->paginate(10);
    
        return view('backend.galeri.galeri', compact('data'))->with('kategori', 'home_banner')->with('kategoriLabel', 'Home Banner');
    }

    public function lapanganBanner(Request $request) {
        $query = Galeri::where('kategori', 'lapangan_banner');
        
        if($request->has('search') && $request->search != ''){
            $query->where('foto', 'LIKE', '%'.$request->search.'%');
        }
        
        $data = $query->orderBy('urutan', 'asc')
                      ->orderBy('created_at', 'desc')
                      ->paginate(10);
    
        return view('backend.galeri.galeri', compact('data'))->with('kategori', 'lapangan_banner')->with('kategoriLabel', 'Lapangan Banner');
    }

    public function kesehatanBanner(Request $request) {
        $query = Galeri::where('kategori', 'kesehatan_banner');
        
        if($request->has('search') && $request->search != ''){
            $query->where('foto', 'LIKE', '%'.$request->search.'%');
        }
        
        $data = $query->orderBy('urutan', 'asc')
                      ->orderBy('created_at', 'desc')
                      ->paginate(10);
    
        return view('backend.galeri.galeri', compact('data'))->with('kategori', 'kesehatan_banner')->with('kategoriLabel', 'Kesehatan Banner');
    }

    public function venueBanner(Request $request) {
        $query = Galeri::where('kategori', 'venue_banner');
        
        if($request->has('search') && $request->search != ''){
            $query->where('foto', 'LIKE', '%'.$request->search.'%');
        }
        
        $data = $query->orderBy('urutan', 'asc')
                      ->orderBy('created_at', 'desc')
                      ->paginate(10);
    
        return view('backend.galeri.galeri', compact('data'))->with('kategori', 'venue_banner')->with('kategoriLabel', 'Venue Banner');
    }

    public function tambahgaleri($kategori = null){
        return view('backend.galeri.tambahgaleri', compact('kategori'));
    }
    public function insertgaleri(Request $request){
    
        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);

        $data = new Galeri();
        $data->kategori = $request->input('kategori');
        $data->urutan = $request->input('urutan', 0);

        // Proses upload gambar jika ada
        if ($request->hasFile('foto')) {
            // Pastikan folder tujuan sudah ada (gunakan public_path untuk keamanan)
            $imageName = time() . '_' . $request->file('foto')->getClientOriginalName();
            $request->file('foto')->move(public_path('fotogaleri'), $imageName);
    
            // Simpan nama gambar ke database
            $data->foto = $imageName;
        } else {
            return redirect()->route('tambahgaleri')->with('error', 'Gambar wajib diisi!');
        }
        $data->save();  // Simpan data setelah nama gambar ditambahkan
        
        // Redirect berdasarkan kategori
        $kategori = $request->input('kategori');
        if($kategori == 'home_banner') {
            return redirect()->route('galeri.home-banner')->with('Success', 'Data berhasil ditambahkan!');
        } elseif($kategori == 'lapangan_banner') {
            return redirect()->route('galeri.lapangan-banner')->with('Success', 'Data berhasil ditambahkan!');
        } elseif($kategori == 'kesehatan_banner') {
            return redirect()->route('galeri.kesehatan-banner')->with('Success', 'Data berhasil ditambahkan!');
        } elseif($kategori == 'venue_banner') {
            return redirect()->route('galeri.venue-banner')->with('Success', 'Data berhasil ditambahkan!');
        }
        
        return redirect()->route('galeri')->with('Success', 'Data berhasil ditambahkan!');
    }
    public function store(Request $request)
    {
    $validated = $request->validate([
        'foto' => 'image|mimes:jpeg,png,jpg|max:2048',
        // validasi lainnya
    ]);

    if ($request->hasFile('foto')) {
        $path = $request->file('foto')->store('fotogaleri', 'public');
        $validated['foto'] = $path;
    }

    Galeri::create($validated);
    // redirect dll
    }
    public function tampilkangaleri($id){

    $data = Galeri::find($id);
    return view('backend.galeri.editgaleri', compact('data'));

    }
    public function updategaleri(Request $request, $id){
    // Ambil data berita yang ingin diperbarui
    $data = Galeri::find($id);

    // Jika data tidak ditemukan, redirect dengan pesan error
    if (!$data) {
        return redirect()->route('galeri')->with('error', 'Data not found!');
    }

    // Memurnikan konten (jika ada HTML)
    $config = HTMLPurifier_Config::createDefault();
    $purifier = new HTMLPurifier($config);

    // Update kategori dan urutan
    $data->kategori = $request->input('kategori');
    $data->urutan = $request->input('urutan', 0);

    // Proses upload gambar jika ada
    if ($request->hasFile('foto')) {
        // Hapus gambar lama (jika ada) sebelum menyimpan gambar baru
        if ($data->foto && file_exists(public_path('fotogaleri/' . $data->foto))) {
            unlink(public_path('fotogaleri/' . $data->foto));
        }

        // Simpan gambar baru
        $imageName = time() . '_' . $request->file('foto')->getClientOriginalName();
        $request->file('foto')->move(public_path('fotogaleri'), $imageName);

        // Update nama gambar di database
        $data->foto = $imageName;
    }

    $data->save();  // Simpan semua perubahan

    // Redirect berdasarkan kategori
    $kategori = $request->input('kategori');
    if($kategori == 'home_banner') {
        return redirect()->route('galeri.home-banner')->with('update', 'Data Berhasil Diedit');
    } elseif($kategori == 'lapangan_banner') {
        return redirect()->route('galeri.lapangan-banner')->with('update', 'Data Berhasil Diedit');
    } elseif($kategori == 'kesehatan_banner') {
        return redirect()->route('galeri.kesehatan-banner')->with('update', 'Data Berhasil Diedit');
    } elseif($kategori == 'venue_banner') {
        return redirect()->route('galeri.venue-banner')->with('update', 'Data Berhasil Diedit');
    }
    
    return redirect()->route('galeri')->with('update', 'Data Berhasil Diedit');
}
    public function deletegaleri($id)
    {
        $data = Galeri::find($id); 

        if ($data) {
            $kategori = $data->kategori;
            $data->delete();
            
            // Redirect berdasarkan kategori
            if($kategori == 'home_banner') {
                return redirect()->route('galeri.home-banner')->with('delete', 'Data Berhasil Dihapus');
            } elseif($kategori == 'lapangan_banner') {
                return redirect()->route('galeri.lapangan-banner')->with('delete', 'Data Berhasil Dihapus');
            } elseif($kategori == 'kesehatan_banner') {
                return redirect()->route('galeri.kesehatan-banner')->with('delete', 'Data Berhasil Dihapus');
            } elseif($kategori == 'venue_banner') {
                return redirect()->route('galeri.venue-banner')->with('delete', 'Data Berhasil Dihapus');
            }
            
            return redirect()->route('galeri')->with('delete', 'Data Berhasil Dihapus');
        } else {
            return redirect()->route('galeri')->with('error', 'Data tidak ditemukan');
        }
    }

}
