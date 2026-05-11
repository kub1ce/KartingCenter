<?php

namespace App\Models;

use App\Enums\Difficulty;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Track extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'length',
        'difficulty',
        'max_participants',
        'price_per_slot',
    ];

    protected function casts(): array
    {
        return [
            'difficulty' => Difficulty::class,
            'price_per_slot' => 'decimal:2',
        ];
    }

    public function timeSlots(): HasMany
    {
        return $this->hasMany(TimeSlot::class);
    }
}
