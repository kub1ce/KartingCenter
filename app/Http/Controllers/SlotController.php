<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Models\TimeSlot;
use App\Models\Track;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SlotController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'track_id' => 'nullable|array',
            'track_id.*' => 'exists:tracks,id',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'status' => 'nullable|in:free,blocked,booked,past',
            'week' => 'nullable|integer',
        ]);

        $isSearchMode = !empty($request->track_id) || $request->filled('status') || $request->filled('date_from') || $request->filled('date_to');
        
        $showPast = $request->filled('show_past') || $request->status === 'past';

        $activeStatuses = [BookingStatus::Pending, BookingStatus::Confirmed];

        $query = TimeSlot::with(['track', 'bookings' => fn($q) => $q->whereIn('status', $activeStatuses)]);

        if (!empty($request->track_id)) {
            $query->whereIn('track_id', $request->track_id);
        }

        if ($request->status === 'free') {
            $query->where('is_blocked', false)
                  ->whereDoesntHave('bookings', fn($q) => $q->whereIn('status', $activeStatuses));
        } elseif ($request->status === 'blocked') {
            $query->where('is_blocked', true);
        } elseif ($request->status === 'booked') {
            $query->whereHas('bookings', fn($q) => $q->whereIn('status', $activeStatuses));
        } elseif ($request->status === 'past') {
            $query->where('date', '<', today());
        }

        if (!$showPast) {
            $query->where('date', '>=', today()->toDateString());
        }

        if ($isSearchMode) {
            if ($request->filled('date_from')) {
                $query->where('date', '>=', $request->date_from);
            }
            if ($request->filled('date_to')) {
                $query->where('date', '<=', $request->date_to);
            }

            $paginatedSlots = $query->orderBy('date')->orderBy('start_time')->paginate(30)->withQueryString();
            $groupedSlots = $paginatedSlots->getCollection()->groupBy('date');
            
            $weekOffset = null;
            $startOfWeek = null;
            $endOfWeek = null;
        } 
        else {
            $weekOffset = (int) $request->input('week', 0);
            $startOfWeek = \Carbon\Carbon::now()->addWeeks($weekOffset)->startOfWeek();
            $endOfWeek = \Carbon\Carbon::now()->addWeeks($weekOffset)->endOfWeek();
            
            $query->whereBetween('date', [$startOfWeek->format('Y-m-d'), $endOfWeek->format('Y-m-d')]);
            
            $groupedSlots = $query->orderBy('date')->orderBy('start_time')->get()->groupBy('date');
            $paginatedSlots = null;
        }

        $tracks = Track::orderBy('name')->get();

        return view('admin.slots.index', compact('groupedSlots', 'tracks', 'weekOffset', 'startOfWeek', 'endOfWeek', 'paginatedSlots', 'isSearchMode'));
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

    public function createGenerate(): View
    {
        $tracks = Track::orderBy('name')->get();
        
        $timeSlots = [
            '10:00-11:30', '11:30-13:00', '13:00-14:30', '14:30-16:00',
            '16:00-17:30', '17:30-19:00', '19:00-20:30', '20:30-22:00'
        ];

        return view('admin.slots.generate', compact('tracks', 'timeSlots'));
    }

    public function storeGenerate(Request $request): RedirectResponse
    {
        $request->validate([
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
            'days' => 'required|array',
            'days.*' => 'in:1,2,3,4,5,6,7',
            'tracks' => 'required|array',
            'tracks.*' => 'exists:tracks,id',
            'times' => 'required|array',
            'times.*' => 'string'
        ]);

        $start = Carbon::parse($request->date_from);
        $end = Carbon::parse($request->date_to);
        $days = array_map('intval', $request->days);
        $tracks = $request->tracks;
        $times = $request->times;

        $createdCount = 0;

        while ($start->lte($end)) {
            if (in_array($start->dayOfWeekIso, $days)) {
                
                foreach ($tracks as $trackId) {
                    foreach ($times as $timeRange) {
                        $timeParts = explode('-', $timeRange);
                        if (count($timeParts) !== 2) continue;

                        $startTime = trim($timeParts[0]);
                        $endTime = trim($timeParts[1]);

                        TimeSlot::firstOrCreate(
                            [
                                'track_id' => $trackId,
                                'date' => $start->toDateString(),
                                'start_time' => $startTime,
                            ],
                            [
                                'end_time' => $endTime,
                                'is_blocked' => false,
                            ]
                        );
                        $createdCount++;
                    }
                }
            }
            $start->addDay();
        }

        return redirect()->route('admin.slots.index')->with('success', "Успешно сгенерировано/обновлено {$createdCount} слотов.");
    }
}