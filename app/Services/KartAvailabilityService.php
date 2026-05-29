<?php

namespace App\Services;

use App\Enums\KartStatus;
use App\Models\KartType;
use App\Models\TimeSlot;

class KartAvailabilityService
{
    public function getAvailableKartsCountForSlot(TimeSlot $slot): array
    {
        $kartTypes = KartType::all();
        $availability = [];

        foreach ($kartTypes as $type) {
            $totalInFleet = $type->karts()->count();

            $onMaintenance = $type->karts()->where('status', KartStatus::Maintenance)->count();

            $physicallyAvailable = $totalInFleet - $onMaintenance;

            $booked = $slot->bookings()
                ->whereIn('status', ['Pending', 'Confirmed'])
                ->with('bookingKarts')
                ->get()
                ->sum(function ($booking) use ($type) {
                    $kartRecord = $booking->bookingKarts->firstWhere('kart_type_id', $type->id);
                    return $kartRecord ? $kartRecord->quantity : 0;
                });

            $availability[$type->id] = max(0, $physicallyAvailable - $booked);
        }

        return $availability;
    }

    public function checkKartMaintenanceFeasibility(int $kartId): array
    {
        $kart = \App\Models\Kart::with('kartType')->find($kartId);

        if (!$kart) {
            return ['can_maintain' => false, 'message' => 'Карт не найден'];
        }

        if ($kart->status === KartStatus::Maintenance) {
            return ['can_maintain' => true, 'needs_warning' => false, 'message' => ''];
        }

        $totalInFleet = $kart->kartType->karts()->count();
        $currentlyOnMaintenance = $kart->kartType->karts()->where('status', KartStatus::Maintenance)->count();
        $availableAfterAction = $totalInFleet - ($currentlyOnMaintenance + 1);

        $futureBookings = \App\Models\Booking::whereHas('timeSlot', function ($q) {
            $q->where('date', '>=', now()->toDateString());
        })
        ->whereIn('status', ['Pending', 'Confirmed'])
        ->whereHas('bookingKarts', fn($q) => $q->where('kart_type_id', $kart->kart_type_id))
        ->with(['timeSlot', 'bookingKarts' => fn($q) => $q->where('kart_type_id', $kart->kart_type_id)])
        ->get();

        $conflictSlots = [];
        foreach ($futureBookings->groupBy('time_slot_id') as $slotId => $bookings) {
            $demandInSlot = $bookings->sum(function ($booking) {
                return $booking->bookingKarts->first()->quantity ?? 0;
            });

            if ($demandInSlot > $availableAfterAction) {
                $slot = $bookings->first()->timeSlot;
                $conflictSlots[] = $slot->date->format('d.m.Y') . ' ' . \Carbon\Carbon::parse($slot->start_time)->format('H:i');
            }
        }

        if (!empty($conflictSlots)) {
            return [
                'can_maintain' => true,
                'needs_warning' => true,
                'message' => "Внимание! Если поставить карт на ТО, не хватит картов для броней в слоты: " . implode(', ', $conflictSlots)
            ];
        }

        return [
            'can_maintain' => true, 
            'needs_warning' => false,
            'message' => 'Карт можно безопасно отправить на ТО.'
        ];
    }
}