<?php

namespace Database\Seeders;

use App\Models\KartType;
use Illuminate\Database\Seeder;

class KartTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'name' => 'Детский',
                'min_age' => 5,
                'max_age' => 12,
                'min_height' => 120,
                'seats' => 1,
                'price_modifier' => 0.80,
            ],
            [
                'name' => 'Одноместный',
                'min_age' => 12,
                'max_age' => null,
                'min_height' => 150,
                'seats' => 1,
                'price_modifier' => 1.00,
            ],
            [
                'name' => 'Двухместный',
                'min_age' => 5,
                'max_age' => null,
                'min_height' => 120,
                'seats' => 2,
                'price_modifier' => 1.50,
            ],
        ];

        foreach ($types as $type) {
            KartType::firstOrCreate(['name' => $type['name']], $type);
        }
    }
}
