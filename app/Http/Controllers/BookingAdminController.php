<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\KartType;
use App\Models\TimeSlot;
use App\Models\User;
use App\Services\BookingPriceCalculator;
use App\Services\KartAvailabilityService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BookingAdminController extends Controller
{
    public function __construct(
        private readonly BookingPriceCalculator $priceCalculator
    ) {}

    public function index(Request $request): View
    {
        $statuses = BookingStatus::cases();
        $tracks = \App\Models\Track::orderBy('name')->get();

        $query = Booking::with(['user', 'timeSlot.track', 'bookingKarts.kartType']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('track_id')) {
            $query->whereHas('timeSlot', fn($q) => $q->where('track_id', $request->track_id));
        }

        $showPast = $request->filled('show_past') || $request->status === 'Cancelled' || $request->status === 'Completed';
        
        if ($request->filled('date')) {
            $query->whereHas('timeSlot', fn($q) => $q->where('date', $request->date));
        } elseif (!$showPast) {
            $query->whereHas('timeSlot', fn($q) => $q->where('date', '>=', today()->toDateString()));
        }

        $bookings = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        return view('admin.bookings.index', compact('bookings', 'statuses', 'tracks'));
    }

    public function create(Request $request): View
    {
        $users = User::where('role_id', 1)->orderBy('name')->get();
        $kartTypes = KartType::all();
        
        $selectedSlot = null;
        if ($request->filled('slot_id')) {
            $selectedSlot = TimeSlot::with('track')->find($request->slot_id);
        }

        return view('admin.bookings.create', compact('users', 'kartTypes', 'selectedSlot'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'time_slot_id' => 'required|exists:time_slots,id',
            'participants_count' => 'required|integer|min:1',
            'karts' => 'nullable|array',
            'karts.*.kart_type_id' => 'required_with:karts|exists:kart_types,id',
            'karts.*.quantity' => 'required_with:karts|integer|min:0',
        ]);

        $booking = DB::transaction(function () use ($validated) {
            $slot = TimeSlot::with('track')
                ->lockForUpdate()
                ->findOrFail($validated['time_slot_id']);

            if ($slot->is_blocked) {
                return null;
            }

            $kartsData = collect($validated['karts'] ?? [])
                ->filter(fn($k) => (int)($k['quantity'] ?? 0) > 0)
                ->values()
                ->toArray();

            $availabilityService = new KartAvailabilityService();
            $availableKarts = $availabilityService->getAvailableKartsCountForSlot($slot);

            foreach ($kartsData as $kart) {
                $typeId = $kart['kart_type_id'];
                $requestedQty = $kart['quantity'];
                $freeQty = $availableKarts[$typeId]['max'] ?? 0;

                if ($requestedQty > $freeQty) {
                    $validator = Validator::make([], []);
                    $validator->errors()->add('karts', "Недостаточно свободных картов выбранного типа. Доступно: {$freeQty}");
                    throw new \Illuminate\Validation\ValidationException($validator);
                }
            }

            $totalPrice = $this->priceCalculator->calculate($slot, $kartsData);

            $booking = Booking::create([
                'user_id' => $validated['user_id'],
                'time_slot_id' => $slot->id,
                'participants_count' => $validated['participants_count'],
                'status' => BookingStatus::Confirmed->value,
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
            return back()->withInput()->withErrors(['time_slot_id' => 'Слот заблокирован или недоступен.']);
        }

        return redirect()->route('admin.bookings.index')->with('success', 'Бронь успешно создана и подтверждена!');
    }

    public function confirm(Booking $booking): RedirectResponse
    {
        $booking->update(['status' => BookingStatus::Confirmed->value]);
        return back()->with('success', 'Бронь подтверждена.');
    }

    public function cancel(Booking $booking): RedirectResponse
    {
        $booking->update(['status' => BookingStatus::Cancelled->value]);
        return back()->with('success', 'Бронь отменена.');
    }
}