<?php

namespace Database\Seeders;

use App\Models\Track;
use Illuminate\Database\Seeder;

class TrackSeeder extends Seeder
{
    public function run(): void
    {
        $tracks = [
            [
                'name' => 'Ринг «Старт»',
                'description' => 'Короткая трасса для начинающих гонщиков. Плавные повороты и широкие прямые идеально подходят для первого знакомства с картингом.',
                'length' => 380,
                'difficulty' => 'Easy',
                'max_participants' => 8,
                'price_per_slot' => 1200.00,
            ],
            [
                'name' => 'Трасса «Торнадо»',
                'description' => 'Трасса среднего уровня с техничными поворотами и небольшими подъёмами. Подойдёт тем, кто уже освоил азы управления картом.',
                'length' => 620,
                'difficulty' => 'Medium',
                'max_participants' => 10,
                'price_per_slot' => 1800.00,
            ],
            [
                'name' => 'Гран-при «Ультра»',
                'description' => 'Профессиональная трасса для опытных гонщиков. Длинные прямые, крутые шпильки и скоростные связки требуют мастерства и концентрации.',
                'length' => 950,
                'difficulty' => 'Hard',
                'max_participants' => 12,
                'price_per_slot' => 2500.00,
            ],
        ];

        foreach ($tracks as $track) {
            Track::firstOrCreate(['name' => $track['name']], $track);
        }
    }
}
