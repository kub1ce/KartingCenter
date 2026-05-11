<?php

namespace Database\Seeders;

use App\Models\Kart;
use App\Models\KartType;
use Illuminate\Database\Seeder;

class KartSeeder extends Seeder
{
    public function run(): void
    {
        $child = KartType::where('name', 'Детский')->first();
        $single = KartType::where('name', 'Одноместный')->first();
        $double = KartType::where('name', 'Двухместный')->first();

        $karts = [
            ['kart_type_id' => $child->id, 'number' => 'D-01', 'status' => 'Available'],
            ['kart_type_id' => $child->id, 'number' => 'D-02', 'status' => 'Available'],
            ['kart_type_id' => $child->id, 'number' => 'D-03', 'status' => 'Available'],
            ['kart_type_id' => $child->id, 'number' => 'D-04', 'status' => 'Maintenance'],
            ['kart_type_id' => $single->id, 'number' => 'S-01', 'status' => 'Available'],
            ['kart_type_id' => $single->id, 'number' => 'S-02', 'status' => 'Available'],
            ['kart_type_id' => $single->id, 'number' => 'S-03', 'status' => 'Available'],
            ['kart_type_id' => $single->id, 'number' => 'S-04', 'status' => 'Available'],
            ['kart_type_id' => $single->id, 'number' => 'S-05', 'status' => 'Available'],
            ['kart_type_id' => $single->id, 'number' => 'S-06', 'status' => 'Maintenance'],
            ['kart_type_id' => $double->id, 'number' => 'T-01', 'status' => 'Available'],
            ['kart_type_id' => $double->id, 'number' => 'T-02', 'status' => 'Available'],
            ['kart_type_id' => $double->id, 'number' => 'T-03', 'status' => 'Available'],
            ['kart_type_id' => $double->id, 'number' => 'T-04', 'status' => 'Available'],
        ];

        foreach ($karts as $kart) {
            Kart::firstOrCreate(['number' => $kart['number']], $kart);
        }
    }
}
