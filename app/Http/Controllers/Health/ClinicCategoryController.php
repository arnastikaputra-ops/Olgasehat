<?php

namespace App\Http\Controllers\Health;

use App\Http\Controllers\Controller;
use App\Models\ClinicCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ClinicCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = ClinicCategory::orderBy('urutan')->orderBy('nama')->paginate(10);
        return view('BACKEND.Health.Category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('BACKEND.Health.Category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'urutan' => 'nullable|integer|min:0',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->nama);
        $data['aktif'] = $request->has('aktif') ? true : false;

        ClinicCategory::create($data);

        return redirect()->route('health.categories.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $category = ClinicCategory::findOrFail($id);
        return view('BACKEND.Health.Category.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'urutan' => 'nullable|integer|min:0',
        ]);

        $category = ClinicCategory::findOrFail($id);
        $data = $request->all();
        $data['slug'] = Str::slug($request->nama);
        $data['aktif'] = $request->has('aktif') ? true : false;

        $category->update($data);

        return redirect()->route('health.categories.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category = ClinicCategory::findOrFail($id);
        $category->delete();

        return redirect()->route('health.categories.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}

