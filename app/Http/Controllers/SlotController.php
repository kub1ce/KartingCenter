<?php

namespace App\Http\Controllers;

use App\Models\TimeSlot;
use App\Models\Track;
use Illuminate\Http\Request;

class SlotController extends Controller
{
    public function index(Request $request)
    {
        $query = TimeSlot::with('track');

        // фильтеры
        if ($request->filled('track_id')) {
            $query->where('track_id', $request->track_id);
        }
        if ($request->filled('date')) {
            $query->where('date', $request->date);
        }

        $slots = $query->orderBy('date', 'asc')->orderBy('start_time', 'asc')->paginate(30);
        $tracks = Track::all();

        return view('admin.slots.index', compact('slots', 'tracks'));
    }

    public function edit(TimeSlot $slot)
    {
        return view('admin.slots.edit', compact('slot'));
    }

    public function update(Request $request, TimeSlot $slot)
    {
        $validated = $request->validate([
            'is_blocked' => 'required|boolean',
        ]);

        $slot->update($validated);

        return redirect()->route('admin.slots.index')->with('success', 'Статус слота обновлен!');
    }
}