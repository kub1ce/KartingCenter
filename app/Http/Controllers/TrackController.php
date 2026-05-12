<?php

namespace App\Http\Controllers;

use App\Models\Track;

class TrackController extends Controller
{
    public function index()
    {
        $tracks = Track::withCount(['timeSlots' => function ($query) {
            $query->where('is_blocked', false)
                ->where('date', '>=', today())
                ->where('date', '<=', today()->addDays(14));
        }])->get();

        return view('tracks.index', compact('tracks'));
    }

    public function show(Track $track)
    {
        $slots = $track->timeSlots()
            ->with(['bookings' => fn($q) => $q->whereIn('status', ['Pending', 'Confirmed'])])
            ->where('is_blocked', false)
            ->where('date', '>=', today())
            ->where('date', '<=', today()->addDays(14))
            ->orderBy('date')
            ->orderBy('start_time')
            ->get()
            ->groupBy('date');

        return view('tracks.show', compact('track', 'slots'));
    }
}
