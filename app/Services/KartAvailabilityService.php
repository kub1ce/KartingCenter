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
        $limits = [];

        $overlappingSlotIds = TimeSlot::where('date', $slot->date->format('Y-m-d'))
            ->where('start_time', '<', $slot->end_time)
            ->where('end_time', '>', $slot->start_time)
            ->pluck('id');

        $activeBookings = \App\Models\Booking::whereIn('time_slot_id', $overlappingSlotIds)
            ->whereIn('status', ['Pending', 'Confirmed'])
            ->with('bookingKarts')
            ->get();

        foreach ($kartTypes as $type) {
            $totalInFleet = $type->karts()->count();
            
            $onMaintenance = $type->karts()->where('status', KartStatus::Maintenance)->count();
            
            $physicallyAvailable = $totalInFleet - $onMaintenance;

            $bookedSameTime = 0;
            foreach ($activeBookings as $booking) {
                $kartRecord = $booking->bookingKarts->firstWhere('kart_type_id', $type->id);
                if ($kartRecord) {
                    $bookedSameTime += $kartRecord->quantity;
                }
            }

            $maxAvailable = max(0, $physicallyAvailable - $bookedSameTime);

            $showWarning = $bookedSameTime > 0;

            $limits[$type->id] = [
                'max' => $maxAvailable,
                'showWarning' => $showWarning
            ];
        }

        return $limits;
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