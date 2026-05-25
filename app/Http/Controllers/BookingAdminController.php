<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'timeSlot.track']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('track_id')) {
            $query->whereHas('timeSlot', function ($q) use ($request) {
                $q->where('track_id', $request->track_id);
            });
        }
        if ($request->filled('date')) {
            $query->whereHas('timeSlot', function ($q) use ($request) {
                $q->where('date', $request->date);
            });
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(15);
        $tracks = \App\Models\Track::all();
        $statuses = BookingStatus::cases();

        if ($request->wantsJson()) {
            return response()->json($bookings);
        }

        return view('admin.bookings.index', compact('bookings', 'tracks', 'statuses'));
    }

    public function confirm(Booking $booking)
    {
        $booking->update(['status' => BookingStatus::Confirmed]);
        return redirect()->route('admin.bookings.index')->with('success', 'Бронирование подтверждено!');
    }

    public function cancel(Booking $booking)
    {
        $booking->update(['status' => BookingStatus::Cancelled]);
        return redirect()->route('admin.bookings.index')->with('success', 'Бронирование отклонено!');
    }
}