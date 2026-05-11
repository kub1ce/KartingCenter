<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            KartTypeSeeder::class,
            TrackSeeder::class,
            KartSeeder::class,
            TimeSlotSeeder::class,
            UserSeeder::class,
        ]);
    }
}
