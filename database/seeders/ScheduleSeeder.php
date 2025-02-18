<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Schedule;

class ScheduleSeeder extends Seeder
{
    public function run()
    {
        $days = ['Thursday', 'Friday'];
        $hours = ['10:00', '11:00', '12:00', '14:00', '15:00', '16:00', '18:00', '19:00'];
        $eventTypes = ['Conference', 'Workshop']; 

        foreach ($days as $day) {
            foreach ($hours as $hour) {
                foreach ($eventTypes as $type) {
                    Schedule::create([
                        'day' => $day,
                        'time' => $hour,
                        'event_type' => $type,
                        'event_id' => null 
                    ]);
                }
            }
        }
    }
}

