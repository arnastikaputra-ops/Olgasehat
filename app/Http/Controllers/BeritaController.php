<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\Category;
use HTMLPurifier;
use HTMLPurifier_Config;
use Illuminate\Http\Request;
use PDF;

use App\Models\Galeri;

class BeritaController extends Controller
{

    public function newss(Request $request) {
        if($request->has('search')){
            $data = Berita::where('title','LIKE','%'.$request->search.'%')
                        ->orderBy('created_at', 'desc') // changed back to descending for newest first
                        ->paginate(6);
        } else {
            $data = Berita::orderBy('created_at', 'desc')->paginate(6); // changed back to descending for newest first
        }
    
        return view('BACKEND.News.databerita', compact('data'));
    }

    public function tambahdata(){
        $categories = Category::all();
        return view('Backend.News.tambahdata', compact('categories'));
    }
    public function insertdata(Request $request){
    
        $content = $request->input('content');
        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);
        $clean_content = $purifier->purify($content);

        $data = new Berita();
        $data->title = $request->input('title');
        $data->name = $request->input('name');
        $data->excerpt = $request->input('excerpt');
        $data->content = $clean_content;
        $data->category_id = $request->input('category_id');
        $data->date = $request->input('date');
        $data->hit = $request->input('hit');
    
        // Proses upload gambar jika ada
        if ($request->hasFile('foto')) {
            // Pastikan folder tujuan sudah ada (gunakan public_path untuk keamanan)
            $imageName = $request->file('foto')->getClientOriginalName();
            $request->file('foto')->move(public_path('fotoberita'), $imageName);
    
            // Simpan nama gambar ke database
            $data->foto = $imageName;
        }
        $data->save();  // Simpan data setelah nama gambar ditambahkan
        
        // Redirect ke halaman berita dengan pesan sukses
        return redirect()->route('newss')->with('Success', 'Data berhasil ditambahkan!');
    }
    public function store(Request $request)
{
    $validated = $request->validate([
        'title' => 'required',
        'foto' => 'image|mimes:jpeg,png,jpg|max:2048',
        // validasi lainnya
    ]);

    if ($request->hasFile('foto')) {
        $path = $request->file('foto')->store('fotoberita', 'public');
        $validated['foto'] = $path;
    }

    Berita::create($validated);
    // redirect dll
}
public function tampilkandata($id){

    $data = Berita::find($id);
    $categories = Category::all();
    return view('Backend.News.editberita', compact('data', 'categories'));

}
public function updatedata(Request $request, $id){
    // Ambil data berita yang ingin diperbarui
    $data = Berita::find($id);

    // Jika data tidak ditemukan, redirect dengan pesan error
    if (!$data) {
        return redirect()->route('newss')->with('error', 'Data not found!');
    }

    // Memurnikan konten (jika ada HTML)
    $content = $request->input('content');
    $config = HTMLPurifier_Config::createDefault();
    $purifier = new HTMLPurifier($config);
    $clean_content = $purifier->purify($content);

    // Perbarui data selain konten dan gambar, pastikan untuk tidak memperbarui kolom 'id' dan 'image' secara langsung
    $data->update([
        'title' => $request->input('title'),
        'name' => $request->input('name'),
        'excerpt' => $request->input('excerpt'),
        'content' => $clean_content,  // Content yang sudah dipurifikasi
        'category_id' => $request->input('category_id'),
        'date' => $request->input('date'),
        'hit' => $request->input('hit'),
    ]);

    // Proses upload gambar jika ada
    if ($request->hasFile('foto')) {
        // Hapus gambar lama (jika ada) sebelum menyimpan gambar baru
        if ($data->foto && file_exists(public_path('fotoberita/' . $data->foto))) {
            unlink(public_path('fotoberita/' . $data->foto));
        }

        // Simpan gambar baru
        $imageName = $request->file('foto')->getClientOriginalName();
        $request->file('foto')->move(public_path('fotoberita'), $imageName);

        // Update nama gambar di database
        $data->foto= $imageName;
        $data->save();  // Jangan lupa simpan setelah gambar diperbarui
    }

    // Redirect setelah berhasil memperbarui
    return redirect()->route('newss')->with('update', 'Data Berhasil Diedit');
}
    public function deletenews($id)
    {
        $data = Berita::find($id); 

        if ($data) {
            $data->delete();
            return redirect()->route('newss')->with('delete', 'Data Berhasil Dihapus');
        } else {
            return redirect()->route('newss')->with('error', 'Data tidak ditemukan');
        }
    }
    public function exportpdf(){
        $data = Berita::orderBy('created_at', 'desc')->get();

        view()->share('data', $data);
        $pdf = PDF::loadView('BACKEND.News.databerita-pdf');
        return $pdf->download('Data News.pdf');
    }

    // Frontend: Display list of news (unified for guest and user)
    public function index(Request $request) {
        $query = Berita::with('category');

        if($request->has('search')){
            $query->where('title','LIKE','%'.$request->search.'%');
        }

        if($request->has('category') && $request->category != ''){
            $query->whereHas('category', function($q) use ($request) {
                $q->where('name', $request->category);
            });
        }

        $beritas = $query->orderBy('created_at', 'desc')->paginate(6);

        // Get trending posts (top 5 by hit count)
        $trendingBeritas = Berita::with('category')
                                ->orderBy('hit', 'desc')
                                ->limit(5)
                                ->get();

        // Gunakan view yang sama untuk guest dan user
        return view('FRONTEND.blog-news', compact('beritas', 'trendingBeritas'));
    }

    // Frontend: Display specific news detail (unified for guest and user)
    public function show($id) {
        $berita = Berita::with('category')->findOrFail($id);

        // Increment hit count
        $berita->increment('hit');

        // Get latest articles (top 5 newest, excluding current article)
        $latestBeritas = Berita::with('category')
                              ->where('id', '!=', $id)
                              ->orderBy('created_at', 'desc')
                              ->limit(5)
                              ->get();

        // Gunakan view yang sama untuk guest dan user
        return view('FRONTEND.blog&news_detail', compact('berita', 'latestBeritas'));
    }

    // User: Display list of news for logged-in users
    // @deprecated Use index() instead - redirect to main route
    public function indexUser(Request $request) {
        // Redirect ke route utama
        return redirect()->route('frontend.blog-news');
    }

    // User: Display specific news detail for logged-in users
    // @deprecated Use show() instead - redirect to main route
    public function showUser($id) {
        // Redirect ke route utama
        return redirect()->route('frontend.blog-news-detail', $id);
    }
}
