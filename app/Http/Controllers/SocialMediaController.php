<?php

namespace App\Http\Controllers;

use App\Models\SocialMedia;
use Illuminate\Http\Request;

class SocialMediaController extends Controller
{
    public function index()
    {
        $socialMedia = SocialMedia::latest()->get();
        return view('BACKEND.Extra.SocialMedia.index', compact('socialMedia'));
    }

    public function create()
    {
        return view('BACKEND.Extra.SocialMedia.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'icon' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'url' => 'required|url|max:255',
        ]);

        SocialMedia::create([
            'icon' => $request->icon,
            'title' => $request->title,
            'url' => $request->url,
        ]);

        return redirect()->route('social-media.index')->with('success', 'Social Media berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $socialMedia = SocialMedia::findOrFail($id);
        return view('BACKEND.Extra.SocialMedia.edit', compact('socialMedia'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'icon' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'url' => 'required|url|max:255',
        ]);

        $socialMedia = SocialMedia::findOrFail($id);
        $socialMedia->update([
            'icon' => $request->icon,
            'title' => $request->title,
            'url' => $request->url,
        ]);

        return redirect()->route('social-media.index')->with('success', 'Social Media berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $socialMedia = SocialMedia::findOrFail($id);
        $socialMedia->delete();

        return redirect()->route('social-media.index')->with('success', 'Social Media berhasil dihapus.');
    }
}

