<?php

namespace App\Enums;

enum KartStatus: string
{
    case Available = 'Available';
    case Maintenance = 'Maintenance';
    case Reserved = 'Reserved';

    public function label(): string
    {
        return match ($this) {
            self::Available => 'Доступен',
            self::Maintenance => 'На техническом обслуживании',
            self::Reserved => 'Забронирован',
        };
    }
}
