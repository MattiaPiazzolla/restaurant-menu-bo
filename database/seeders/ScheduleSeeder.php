<?php

namespace Database\Seeders;

use App\Models\Schedule;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $days = [
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday',
            'Saturday',
            'Sunday',
        ];

        foreach ($days as $day) {
            Schedule::create([
                'day' => $day,
                'is_open' => true,
                'lunch_opening' => '11:30:00',
                'lunch_closing' => '14:30:00',
                'dinner_opening' => '18:00:00',
                'dinner_closing' => '22:00:00',
            ]);
        }
    }
}