<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kart extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'type_id',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => \App\Enums\KartStatus::class,
        ];
    }

    public function kartType()
    {
        return $this->belongsTo(KartType::class, 'type_id');
    }
}