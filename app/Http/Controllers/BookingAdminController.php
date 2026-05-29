<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Models\TimeSlot;
use App\Models\User;

class BookingAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'timeSlot.track', 'bookingKarts.kartType']);

        if (!$request->filled('show_past')) {
            $query->whereHas('timeSlot', function ($q) {
                $q->where('date', '>=', now()->toDateString());
            });
        }

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
        $statuses = \App\Enums\BookingStatus::cases();

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

    public function create()
    {
        $users = User::where('role_id', \App\Enums\Role::User)->get();
        
        $freeSlots = TimeSlot::where('is_blocked', false)
            ->whereDoesntHave('bookings', function ($query) {
                $query->whereIn('status', [BookingStatus::Pending, BookingStatus::Confirmed]);
            })
            ->with('track')
            ->get();

        return view('admin.bookings.create', compact('users', 'freeSlots'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'time_slot_id' => 'required|exists:time_slots,id',
            'participants_count' => 'required|integer|min:1',
        ]);

        $slot = TimeSlot::find($validated['time_slot_id']);
        if ($slot->is_blocked) {
            return back()->withErrors('Этот слот заблокирован!')->withInput();
        }

        Booking::create([
            'user_id' => $validated['user_id'],
            'time_slot_id' => $validated['time_slot_id'],
            'participants_count' => $validated['participants_count'],
            'status' => BookingStatus::Confirmed,
            'created_by' => auth()->id(),
            'total_price' => $slot->track->price_per_slot * $validated['participants_count'],
        ]);

        return redirect()->route('admin.bookings.index')->with('success', 'Бронирование успешно создано!');
    }
}