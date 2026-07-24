<?php

namespace App\Http\Controllers;

use App\Models\ContactUs;
use Illuminate\Http\Request;

class ContactUsController extends Controller
{
    public function index()
    {
        $contactUs = ContactUs::latest()->get();
        return view('BACKEND.Extra.ContactUs.index', compact('contactUs'));
    }

    public function create()
    {
        return view('BACKEND.Extra.ContactUs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'kontak' => 'required|string|max:255',
            'type' => 'required|string|max:255',
        ]);

        ContactUs::create([
            'title' => $request->title,
            'kontak' => $request->kontak,
            'type' => $request->type,
        ]);

        return redirect()->route('contact-us.index')->with('success', 'Contact Us berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $contactUs = ContactUs::findOrFail($id);
        return view('BACKEND.Extra.ContactUs.edit', compact('contactUs'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'kontak' => 'required|string|max:255',
            'type' => 'required|string|max:255',
        ]);

        $contactUs = ContactUs::findOrFail($id);
        $contactUs->update([
            'title' => $request->title,
            'kontak' => $request->kontak,
            'type' => $request->type,
        ]);

        return redirect()->route('contact-us.index')->with('success', 'Contact Us berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $contactUs = ContactUs::findOrFail($id);
        $contactUs->delete();

        return redirect()->route('contact-us.index')->with('success', 'Contact Us berhasil dihapus.');
    }
}

