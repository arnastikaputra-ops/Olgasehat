<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // Tampilkan halaman index (daftar kategori)
    public function category()
    {
        // ambil semua kategori (urut terbaru dulu)
        $categories = Category::latest()->get();

        // kirim ke view Backend.Category.index
        return view('Backend.Category.category', compact('categories'));
    }

    public function create()
    {
        return view('Backend.Category.tambah_category');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        Category::create([
            'title' => $request->title,
        ]);

        return redirect()->route('category.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

  public function edit($id)
{
    $category = Category::findOrFail($id);
    return view('Backend.Category.edit_category', compact('category'));
}


    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $category = Category::findOrFail($id);
        $category->update(['title' => $request->title]);

        return redirect()->route('category.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('category.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
