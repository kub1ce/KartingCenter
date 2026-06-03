<?php

namespace App\Http\Controllers;

use App\Enums\Difficulty;
use App\Models\Track;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

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

    public function update(Request $request, Track $track): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'length' => 'required|integer|min:1',
            'difficulty' => 'required|in:Easy,Medium,Hard',
            'max_participants' => 'required|integer|min:1',
            'price_per_slot' => 'required|numeric|min:0',
            'svg_code' => 'nullable|string',
        ]);

        $track->update($validated);

        return redirect()->route('admin.tracks.index')->with('success', 'Трасса успешно обновлена!');
    }

    public function create(): View
    {
        $difficulties = \App\Enums\Difficulty::cases();
        return view('admin.tracks.create', compact('difficulties'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'length' => 'required|integer|min:1',
            'difficulty' => 'required|in:Easy,Medium,Hard',
            'max_participants' => 'required|integer|min:1',
            'price_per_slot' => 'required|numeric|min:0',
            'svg_code' => 'nullable|string',
        ]);

        \App\Models\Track::create($validated);

        return redirect()->route('admin.tracks.index')->with('success', 'Трасса успешно создана!');
    }
}