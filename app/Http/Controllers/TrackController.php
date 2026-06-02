<?php

namespace App\Http\Controllers;

use App\Models\Track;
use App\Models\TimeSlot;
use Carbon\Carbon;
use Illuminate\View\View;

class TrackController extends Controller
{
    public function index(): View
    {
        $tracks = Track::orderBy('difficulty')->orderBy('length')->get();
        return view('tracks.index', compact('tracks'));
    }

    public function show(Track $track): View
    {
        $today = today();
        $tomorrow = \Carbon\Carbon::tomorrow();

        $slots = TimeSlot::with(['bookings' => fn($q) => $q->whereIn('status', ['Pending', 'Confirmed'])])
            ->where('track_id', $track->id)
            ->where('is_blocked', false)
            ->where(function ($q) use ($today, $tomorrow) {
                $q->where(function ($subQ) use ($today) {
                    $subQ->where('date', $today->toDateString())
                        ->where('start_time', '>', now()->format('H:i:s'));
                })->orWhere('date', $tomorrow->toDateString());
            })
            ->orderBy('date')
            ->orderBy('start_time')
            ->get()
            ->groupBy(fn($slot) => $slot->date->toDateString());

        return view('tracks.show', compact('track', 'slots'));
    }
}