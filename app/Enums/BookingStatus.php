<?php

namespace App\Enums;

enum BookingStatus: string
{
    case Pending = 'Pending';
    case Confirmed = 'Confirmed';
    case Cancelled = 'Cancelled';
    case Completed = 'Completed';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Ожидает подтверждения',
            self::Confirmed => 'Подтверждено',
            self::Cancelled => 'Отменено',
            self::Completed => 'Завершено',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Pending => 'yellow',
            self::Confirmed => 'green',
            self::Cancelled => 'red',
            self::Completed => 'gray',
        };
    }
}
