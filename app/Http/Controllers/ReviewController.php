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

    public function storeFromUser(Request $request)
    {
        $request->validate([
            'ulasan'      => 'required|string|min:3|max:1000',
            'rate'        => 'required|integer|min:1|max:5',
            'tipe_target' => 'nullable|string|in:platform,venue,klinik,komunitas,event',
            'target_id'   => 'nullable|integer',
            'company'     => 'nullable|string|max:255',
            'foto'        => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = auth()->user();
        $fotoPath = null;

        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('fotoreview', 'public');
        } elseif ($user && $user->image) {
            $fotoPath = $user->image;
        }

        $tipeTarget = $request->input('tipe_target', 'platform');
        $companyLabel = $request->input('company');
        if (!$companyLabel) {
            if ($tipeTarget == 'venue') {
                $companyLabel = 'Ulasan Venue Olahraga';
            } elseif ($tipeTarget == 'klinik') {
                $companyLabel = 'Ulasan Layanan Kesehatan';
            } elseif ($tipeTarget == 'komunitas') {
                $companyLabel = 'Ulasan Komunitas';
            } elseif ($tipeTarget == 'event') {
                $companyLabel = 'Ulasan Event Olahraga';
            } else {
                $companyLabel = 'Pengguna Olga Sehat';
            }
        }

        Review::create([
            'user_id'     => $user ? $user->id : null,
            'tipe_target' => $tipeTarget,
            'target_id'   => $request->input('target_id'),
            'nama'        => $user ? $user->name : ($request->input('nama') ?? 'Pengguna Olga Sehat'),
            'ulasan'      => $request->ulasan,
            'rate'        => $request->rate,
            'company'     => $companyLabel,
            'foto'        => $fotoPath,
        ]);

        return redirect()->back()->with('success', 'Terima kasih! Ulasan dan rating Anda berhasil dikirim.');
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
