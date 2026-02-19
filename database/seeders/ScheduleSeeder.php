<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Schedule;
use Carbon\Carbon;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $schedules = [
            [
                'bus_id' => 1,
                'route_id' => 1,
                'departure_date' => Carbon::now()->addDays(1)->format('Y-m-d'),
                'departure_time' => '08:00:00',
                'arrival_time' => '11:00:00',
                'price' => 100000,
                'available_seats' => 40,
                'status' => 'scheduled'
            ],
            [
                'bus_id' => 2,
                'route_id' => 2,
                'departure_date' => Carbon::now()->addDays(1)->format('Y-m-d'),
                'departure_time' => '20:00:00',
                'arrival_time' => '04:00:00',
                'price' => 350000,
                'available_seats' => 36,
                'status' => 'scheduled'
            ],
        ];

        foreach ($schedules as $data) {
            Schedule::create($data);
        }
    }
}