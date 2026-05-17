<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\KartType;
use App\Models\TimeSlot;
use App\Services\BookingPriceCalculator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function __construct(
        private readonly BookingPriceCalculator $priceCalculator
    )
    {
    }

    public function index(Request $request): View
    {
        $user = auth()->user();

        $upcoming = Booking::with(['timeSlot.track', 'bookingKarts.kartType'])
            ->where('user_id', $user->id)
            ->whereIn('status', [BookingStatus::Pending->value, BookingStatus::Confirmed->value])
            ->whereHas('timeSlot', fn($q) => $q->where('date', '>=', today()))
            ->orderBy(
                TimeSlot::select('date')
                    ->whereColumn('time_slots.id', 'bookings.time_slot_id')
                    ->limit(1)
            )
            ->get();

        $history = Booking::with(['timeSlot.track', 'bookingKarts.kartType'])
            ->where('user_id', $user->id)
            ->where(function ($q) {
                $q->whereIn('status', [BookingStatus::Cancelled->value, BookingStatus::Completed->value])
                    ->orWhereHas('timeSlot', fn($q2) => $q2->where('date', '<', today()));
            })
            ->orderByDesc(
                TimeSlot::select('date')
                    ->whereColumn('time_slots.id', 'bookings.time_slot_id')
                    ->limit(1)
            )
            ->get();

        $tab = $request->get('tab', 'upcoming');

        return view('bookings.index', compact('upcoming', 'history', 'tab'));
    }

    public function create(Request $request): View|RedirectResponse
    {
        $slotId = $request->query('slot_id');

        if (!$slotId) {
            return redirect()->route('schedule.index')
                ->with('error', 'Не выбран временной слот. Выберите слот из расписания.');
        }

        $slot = TimeSlot::with('track')->find($slotId);

        if (!$slot) {
            return redirect()->route('schedule.index')
                ->with('error', 'Выбранный слот не найден.');
        }

        if ($slot->is_blocked) {
            return redirect()->route('schedule.index')
                ->with('error', 'Выбранный слот заблокирован.');
        }

        if ($slot->date < today()) {
            return redirect()->route('schedule.index')
                ->with('error', 'Выбранный слот уже прошёл.');
        }

        if ($slot->bookings()->whereIn('status', ['Pending', 'Confirmed'])->exists()) {
            return redirect()->route('schedule.index')
                ->with('error', 'К сожалению, этот слот уже занят.');
        }

        $kartTypes = KartType::all();

        return view('bookings.create', compact('slot', 'kartTypes'));
    }

    public function store(StoreBookingRequest $request): RedirectResponse
    {
        $booking = DB::transaction(function () use ($request) {
            $slot = TimeSlot::with('track')
                ->lockForUpdate()
                ->findOrFail($request->time_slot_id);

            if ($slot->is_blocked) {
                return null;
            }
            if ($slot->bookings()->whereIn('status', ['Pending', 'Confirmed'])->exists()) {
                return null;
            }

            $kartsData = collect($request->karts)
                ->filter(fn($k) => (int)($k['quantity'] ?? 0) > 0)
                ->values()
                ->toArray();

            $totalPrice = $this->priceCalculator->calculate($slot, $kartsData);

            $booking = Booking::create([
                'user_id' => auth()->id(),
                'time_slot_id' => $slot->id,
                'participants_count' => $request->participants_count,
                'status' => BookingStatus::Pending->value,
                'total_price' => $totalPrice,
                'created_by' => auth()->id(),
            ]);

            foreach ($kartsData as $kart) {
                $booking->bookingKarts()->create([
                    'kart_type_id' => $kart['kart_type_id'],
                    'quantity' => $kart['quantity'],
                ]);
            }

            return $booking;
        });

        if (!$booking) {
            return back()
                ->withInput()
                ->withErrors(['time_slot_id' => 'Слот уже был занят другим пользователем. Пожалуйста, выберите другой.']);
        }

        return redirect()
            ->route('bookings.show', $booking)
            ->with('success', 'Бронь успешно создана! Ожидайте подтверждения администратором.');
    }

    public function show(Booking $booking): View
    {
        $this->authorize('view', $booking);

        $booking->load(['timeSlot.track', 'bookingKarts.kartType', 'creator']);

        return view('bookings.show', compact('booking'));
    }

    public function cancel(Booking $booking): RedirectResponse
    {
        $this->authorize('cancel', $booking);

        $booking->update(['status' => BookingStatus::Cancelled->value]);

        return back()->with('success', 'Бронь успешно отменена.');
    }
}
