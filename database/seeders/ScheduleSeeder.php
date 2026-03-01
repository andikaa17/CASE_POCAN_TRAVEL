<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('schedules')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // JADWAL 
        $schedules = [
            
            [
                'bus_id' => 1,
                'route_id' => 3, 
                'departure_date' => '2026-02-21',
                'departure_time' => '06:00:00',
                'arrival_time' => '09:00:00',
                'price' => 100000,
                'available_seats' => 40,
                'status' => 'scheduled'
            ],
           
            
            [
                'bus_id' => 2,
                'route_id' => 2, 
                'departure_date' => '2026-02-21',
                'departure_time' => '08:00:00',
                'arrival_time' => '20:00:00',
                'price' => 350000,
                'available_seats' => 36,
                'status' => 'scheduled'
            ],
          
          
            [
                'bus_id' => 1,
                'route_id' => 1,
                'departure_date' => '2026-02-21',
                'departure_time' => '15:00:00',
                'arrival_time' => '18:00:00',
                'price' => 120000,
                'available_seats' => 40,
                'status' => 'scheduled'
            ],
           
      
            [
                'bus_id' => 2,
                'route_id' => 4, 
                'departure_date' => '2026-02-21',
                'departure_time' => '09:00:00',
                'arrival_time' => '21:00:00',
                'price' => 350000,
                'available_seats' => 36,
                'status' => 'scheduled'
            ],
           
            
            [
                'bus_id' => 1,
                'route_id' => 5,
                'departure_date' => '2026-02-21',
                'departure_time' => '19:00:00',
                'arrival_time' => '07:00:00',
                'price' => 250000,
                'available_seats' => 40,
                'status' => 'scheduled'
            ],
           
        
            [
                'bus_id' => 2,
                'route_id' => 7,
                'departure_date' => '2026-02-22',
                'departure_time' => '07:00:00',
                'arrival_time' => '15:00:00',
                'price' => 300000,
                'available_seats' => 36,
                'status' => 'scheduled'
            ],
         
           
            [
                'bus_id' => 1,
                'route_id' => 8,
                'departure_date' => '2026-02-22',
                'departure_time' => '16:00:00',
                'arrival_time' => '00:00:00',
                'price' => 300000,
                'available_seats' => 40,
                'status' => 'scheduled'
            ],
        ];

        foreach ($schedules as $schedule) {
            DB::table('schedules')->insert([
                'bus_id' => $schedule['bus_id'],
                'route_id' => $schedule['route_id'],
                'departure_date' => $schedule['departure_date'],
                'departure_time' => $schedule['departure_time'],
                'arrival_time' => $schedule['arrival_time'],
                'price' => $schedule['price'],
                'available_seats' => $schedule['available_seats'],
                'status' => $schedule['status'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

       
    }
}