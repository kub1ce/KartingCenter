<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Models\TimeSlot;
use App\Models\Track;
use App\Services\KartAvailabilityService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminSlotApiController extends Controller
{
    public function __construct(
        private readonly KartAvailabilityService $availabilityService
    ) {}

    public function tracksByDate(Request $request): JsonResponse
    {
        $request->validate(['date' => 'required|date']);
        
        $trackIds = TimeSlot::where('date', $request->date)
            ->where('is_blocked', false)
            ->whereDoesntHave('bookings', fn($q) => $q->whereIn('status', [BookingStatus::Pending, BookingStatus::Confirmed]))
            ->pluck('track_id')
            ->unique();

        return response()->json(
            Track::whereIn('id', $trackIds)->get(['id', 'name'])
        );
    }

    public function slotsByTrack(Request $request): JsonResponse
    {
        $request->validate([
            'date' => 'required|date', 
            'track_id' => 'required|exists:tracks,id'
        ]);

        $slots = TimeSlot::where('date', $request->date)
            ->where('track_id', $request->track_id)
            ->where('is_blocked', false)
            ->whereDoesntHave('bookings', fn($q) => $q->whereIn('status', [BookingStatus::Pending, BookingStatus::Confirmed]))
            ->orderBy('start_time')
            ->get();

        return response()->json(
            $slots->map(fn ($slot) => [
                'id' => $slot->id,
                'time_range' => Carbon::parse($slot->start_time)->format('H:i') . ' – ' . Carbon::parse($slot->end_time)->format('H:i')
            ])
        );
    }

    public function slotDetails(TimeSlot $slot): JsonResponse
    {
        if (!$slot || $slot->is_blocked) {
            return response()->json(['basePrice' => 0, 'limits' => []]);
        }

        $kartLimits = $this->availabilityService->getAvailableKartsCountForSlot($slot);

        return response()->json([
            'basePrice' => (float) $slot->track->price_per_slot, 
            'limits' => $kartLimits
        ]);
    }
}