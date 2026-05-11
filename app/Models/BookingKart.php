<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingKart extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'kart_type_id',
        'quantity',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function kartType(): BelongsTo
    {
        return $this->belongsTo(KartType::class);
    }
}
