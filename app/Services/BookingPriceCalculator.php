<?php

namespace App\Services;

use App\Models\KartType;
use App\Models\TimeSlot;

class BookingPriceCalculator
{
    public function calculate(TimeSlot $slot, array $karts): float
    {
        $basePrice = (float)$slot->track->price_per_slot;
        $total = 0.0;

        foreach ($karts as $kart) {
            $type = KartType::find($kart['kart_type_id']);
            if (!$type) {
                continue;
            }
            $total += $basePrice * (float)$type->price_modifier * (int)$kart['quantity'];
        }

        return round($total, 2);
    }

    public function calculateFromObjects(float $basePricePerSlot, array $karts): float
    {
        $total = 0.0;

        foreach ($karts as $kart) {
            $total += $basePricePerSlot * (float)$kart['kart_type']->price_modifier * (int)$kart['quantity'];
        }

        return round($total, 2);
    }
}
