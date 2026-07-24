<?php

namespace App\Http\Controllers;

use App\Models\ActivityType;
use Illuminate\Http\Request;

class ActivityTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $activityTypes = ActivityType::all();
        return view('backend.activity_types.index', compact('activityTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort(403, 'Tidak diizinkan menambah tipe aktivitas baru.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        abort(403, 'Tidak diizinkan menambah tipe aktivitas baru.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $activityType = ActivityType::findOrFail($id);
        return view('backend.activity_types.edit', compact('activityType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $activityType = ActivityType::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:activity_types,name,' . $id,
            'title' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
        ]);

        $activityType->update($request->all());

        return redirect()->route('activity-types.index')->with('success', 'Tipe Aktivitas berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        abort(403, 'Tidak diizinkan menghapus tipe aktivitas.');
    }

    /**
     * Display a listing of activities for daftar view with dummy data.
     */
    public function daftar()
    {
        $activities = [
            [
                'id' => 1,
                'title' => 'Kumpulan Pemuda Futsal',
                'type' => 'Komunitas',
                'location' => 'Denpasar',
                'date' => '10 Oktober 2025',
            ],
            [
                'id' => 2,
                'title' => 'The SportMan Club Denpasar',
                'type' => 'Membership',
                'location' => 'Renon, Bali',
                'date' => '2 Oktober 2025',
            ],
            [
                'id' => 3,
                'title' => 'Badminton Warriors BDG Championship',
                'type' => 'Event Olahraga',
                'location' => 'Jimbaran',
                'date' => '15 Oktober 2025',
            ],
        ];

        return view('backend.activity_types.daftar', compact('activities'));
    }
}
