<?php

namespace App\Enums;

enum Difficulty: string
{
    case Easy = 'Easy';
    case Medium = 'Medium';
    case Hard = 'Hard';

    public function label(): string
    {
        return match ($this) {
            self::Easy => 'Лёгкая',
            self::Medium => 'Средняя',
            self::Hard => 'Сложная',
        };
    }

    public function badgeColor(): string
    {
        return match ($this) {
            self::Easy => 'green',
            self::Medium => 'yellow',
            self::Hard => 'red',
        };
    }
}
