<?php

namespace App\Http\Controllers;

use App\Enums\Difficulty;
use App\Models\Track;
use Illuminate\Http\Request;

class AdminTrackController extends Controller
{
    public function index()
    {
        $tracks = Track::all();
        return view('admin.tracks.index', compact('tracks'));
    }

    public function edit(Track $track)
    {
        $difficulties = Difficulty::cases();
        return view('admin.tracks.edit', compact('track', 'difficulties'));
    }

    public function update(Request $request, Track $track)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'length' => 'required|integer|min:1',
            'difficulty' => 'required|string',
            'max_participants' => 'required|integer|min:1',
            'price_per_slot' => 'required|numeric|min:0',
        ]);

        $track->update($validated);

        return redirect()->route('admin.tracks.index')->with('success', 'Трасса обновлена!');
    }
}