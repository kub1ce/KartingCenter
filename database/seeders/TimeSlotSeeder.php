<?php

namespace Database\Seeders;

use App\Models\TimeSlot;
use App\Models\Track;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TimeSlotSeeder extends Seeder
{
    public function run(): void
    {
        $tracks = Track::all();
        $today = Carbon::today();

        $slotTimes = [];
        $start = Carbon::createFromTimeString('10:00');
        for ($i = 0; $i < 8; $i++) {
            $end = $start->copy()->addMinutes(90);
            $slotTimes[] = [
                'start' => $start->format('H:i:s'),
                'end' => $end->format('H:i:s'),
            ];
            $start->addMinutes(90);
        }

        $inserts = [];
        $now = now();

        for ($day = 0; $day < 14; $day++) {
            $date = $today->copy()->addDays($day)->format('Y-m-d');

            foreach ($tracks as $track) {
                foreach ($slotTimes as $slot) {
                    $inserts[] = [
                        'track_id' => $track->id,
                        'date' => $date,
                        'start_time' => $slot['start'],
                        'end_time' => $slot['end'],
                        'is_blocked' => false,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }
        }

        foreach (array_chunk($inserts, 50) as $chunk) {
            TimeSlot::upsert(
                $chunk,
                ['track_id', 'date', 'start_time'],
                ['end_time', 'is_blocked', 'updated_at']
            );
        }
    }
}
