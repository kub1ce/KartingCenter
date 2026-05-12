<?php

namespace App\Http\Controllers;

use App\Models\TimeSlot;
use App\Models\Track;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'track_id' => ['nullable', 'exists:tracks,id'],
            'date' => ['nullable', 'date', 'after_or_equal:today'],
        ]);

        $query = TimeSlot::query()
            ->with([
                'track',
                'bookings' => fn($q) => $q->whereIn('status', ['Pending', 'Confirmed']),
            ])
            ->where('is_blocked', false)
            ->where('date', '>=', today())
            ->where('date', '<=', today()->addDays(14))
            ->orderBy('date')
            ->orderBy('start_time');

        if ($request->filled('track_id')) {
            $query->where('track_id', $request->track_id);
        }

        if ($request->filled('date')) {
            $query->where('date', $request->date);
        }

        $slots = $query->get()->groupBy('date');
        $tracks = Track::orderBy('name')->get();

        return view('schedule.index', compact('slots', 'tracks'));
    }
}
