<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Models\TimeSlot;
use App\Models\Track;
use App\Models\Booking;
use App\Models\KartType;
use App\Services\KartAvailabilityService;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function __construct(
        private readonly KartAvailabilityService $availabilityService
    ) {}

    public function index(Request $request)
    {
        $request->validate([
            'track_id' => ['nullable', 'exists:tracks,id'],
            'date' => ['nullable', 'date', 'after_or_equal:today'],
            'times' => ['nullable', 'array'],
            'times.*' => ['string'],
        ]);

        $query = TimeSlot::query()
            ->with([
                'track',
                'bookings' => fn($q) => $q->whereIn('status', [BookingStatus::Pending, BookingStatus::Confirmed]),
            ])
            ->where('is_blocked', false)
            ->where('date', '>=', today())
            ->where('date', '<=', today()->addDays(14))
            ->where(function ($q) {
                $q->where('date', '>', today())
                ->orWhere(function ($subQ) {
                    $subQ->where('date', today())
                        ->where('start_time', '>', now()->format('H:i:s'));
                });
            })
            ->orderBy('date')
            ->orderBy('start_time');

        if ($request->filled('track_id')) {
            $query->where('track_id', $request->track_id);
        }

        if ($request->filled('date')) {
            $query->where('date', $request->date);
        }

        $timeOptions = TimeSlot::where('is_blocked', false)
            ->where('date', '>=', today())
            ->where('date', '<=', today()->addDays(14))
            ->where(function ($q) {
                $q->where('date', '>', today())
                ->orWhere(function ($subQ) {
                    $subQ->where('date', today())
                        ->where('start_time', '>', now()->format('H:i:s'));
                });
            })
            ->when($request->filled('track_id'), fn($q) => $q->where('track_id', $request->track_id))
            ->when($request->filled('date'), fn($q) => $q->where('date', $request->date))
            ->select('start_time', 'end_time')
            ->distinct()
            ->orderBy('start_time')
            ->get();

        if ($request->filled('times')) {
            $query->whereIn('start_time', $request->times);
        }

        $slotsData = $query->get()->map(function ($slot) {
            $isBusy = $slot->bookings->isNotEmpty();
            
            $limits = $this->availabilityService->getAvailableKartsCountForSlot($slot);
            
            $availableKartsList = [];
            foreach ($limits as $limitData) {
                $availableKartsList[] = [
                    'name' => $limitData['name'],
                    'count' => $limitData['max']
                ];
            }

            $timeKey = \Carbon\Carbon::parse($slot->start_time)->format('H:i') . ' - ' . \Carbon\Carbon::parse($slot->end_time)->format('H:i');

            return [
                'id' => $slot->id,
                'date' => $slot->date->toDateString(),
                'time_key' => $timeKey,
                'track' => $slot->track,
                'is_busy' => $isBusy,
                'available_karts' => $availableKartsList
            ];
        })->groupBy('date')->map(function ($dateGroup) {
            return $dateGroup->groupBy('time_key')->sortKeys()->map(function ($timeGroup) {
                return $timeGroup->sortBy('is_busy');
            });
        });

        $tracks = Track::orderBy('name')->get();

        $dates = collect();
        for ($i = 0; $i < 14; $i++) {
            $dates->push(\Carbon\Carbon::today()->addDays($i));
        }

        return view('schedule.index', compact('slotsData', 'tracks', 'dates', 'timeOptions'));
    }

    public function welcome()
    {
        $promotions = \App\Models\Promotion::where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        $tracks = \App\Models\Track::all();

        return view('welcome', compact('promotions', 'tracks'));
    }
}
