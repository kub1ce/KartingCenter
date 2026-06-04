<?php

namespace App\Models;

use App\Enums\BookingStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TimeSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'track_id',
        'date',
        'start_time',
        'end_time',
        'is_blocked',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'is_blocked' => 'boolean',
        ];
    }

    public function track(): BelongsTo
    {
        return $this->belongsTo(Track::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function isAvailable(): bool
    {
        if ($this->is_blocked) {
            return false;
        }

        $activeStatuses = [BookingStatus::Pending, BookingStatus::Confirmed];

        if ($this->relationLoaded('bookings')) {
            return $this->bookings
                ->whereIn('status', $activeStatuses)
                ->isEmpty();
        }

        return !$this->bookings()
            ->whereIn('status', $activeStatuses)
            ->exists();
    }
}