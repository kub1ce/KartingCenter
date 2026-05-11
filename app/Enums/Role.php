<?php

namespace App\Enums;

enum Role: int
{
    case Administrator = 0;
    case User = 1;
    case ContentManager = 2;

    public function label(): string
    {
        return match ($this) {
            self::Administrator => 'Администратор',
            self::User => 'Клиент',
            self::ContentManager => 'Контент-менеджер',
        };
    }
}
