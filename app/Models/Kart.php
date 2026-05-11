<?php

namespace App\Models;

use App\Enums\KartStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kart extends Model
{
    use HasFactory;

    protected $fillable = [
        'kart_type_id',
        'number',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => KartStatus::class,
        ];
    }

    public function kartType(): BelongsTo
    {
        return $this->belongsTo(KartType::class);
    }
}
