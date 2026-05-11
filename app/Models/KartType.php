<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KartType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'min_age',
        'max_age',
        'min_height',
        'seats',
        'price_modifier',
    ];

    protected function casts(): array
    {
        return [
            'price_modifier' => 'decimal:2',
        ];
    }

    public function karts(): HasMany
    {
        return $this->hasMany(Kart::class);
    }

    public function bookingKarts(): HasMany
    {
        return $this->hasMany(BookingKart::class);
    }
}
