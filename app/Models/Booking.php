<?php

namespace App\Models;

use App\Enums\BookingStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'time_slot_id',
        'participants_count',
        'status',
        'total_price',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'status'      => BookingStatus::class,
            'total_price' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function timeSlot(): BelongsTo
    {
        return $this->belongsTo(TimeSlot::class);
    }

    public function bookingKarts(): HasMany
    {
        return $this->hasMany(BookingKart::class);
    }

    public function recalculateTotal(): float
    {
        $base  = $this->timeSlot->track->price_per_slot;
        $total = 0;

        foreach ($this->bookingKarts as $bk) {
            $total += $base * $bk->kartType->price_modifier * $bk->quantity;
        }

        return $total;
    }

    public function isCancellable(): bool
    {
        return in_array($this->status, [BookingStatus::Pending, BookingStatus::Confirmed]);
    }
}
