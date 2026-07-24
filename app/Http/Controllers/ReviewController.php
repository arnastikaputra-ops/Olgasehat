<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;

class ReviewController extends Controller
{
    public function index()
    {
        $data = Review::all();
        return view('BACKEND.Review.review', compact('data')); 
    }

    public function create()
    {
        return view('BACKEND.Review.tambah_riview'); 
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'   => 'required',
            'ulasan' => 'required',
            'rate'   => 'required|integer|min:1|max:5',
            'company' => 'nullable|string',
            'foto'   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('fotoreview', 'public');
        }

        Review::create([
            'nama'   => $request->nama,
            'ulasan' => $request->ulasan,
            'rate'   => $request->rate,
            'company' => $request->company,
            'foto'   => $fotoPath,
        ]);

        return redirect()->route('review.index')->with('success', 'Ulasan berhasil ditambahkan');
    }

    public function edit($id)
    {
        $review = Review::findOrFail($id);
        return view('BACKEND.Review.edit_riview', compact('review')); 
    }

    public function update(Request $request, $id)
    {
        $review = Review::findOrFail($id);

        $request->validate([
            'nama'   => 'required',
            'ulasan' => 'required',
            'rate'   => 'required|integer|min:1|max:5',
            'company' => 'nullable|string',
            'foto'   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $fotoPath = $review->foto;
        if ($request->hasFile('foto')) {
            // Delete old file if exists
            if ($fotoPath && \Storage::disk('public')->exists($fotoPath)) {
                \Storage::disk('public')->delete($fotoPath);
            }
            $fotoPath = $request->file('foto')->store('fotoreview', 'public');
        }

        $review->update([
            'nama'   => $request->nama,
            'ulasan' => $request->ulasan,
            'rate'   => $request->rate,
            'company' => $request->company,
            'foto'   => $fotoPath,
        ]);

        return redirect()->route('review.index')->with('success', 'Ulasan berhasil diupdate');
    }

    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();

        return redirect()->route('review.index')->with('success', 'Ulasan berhasil dihapus');
    }
}
