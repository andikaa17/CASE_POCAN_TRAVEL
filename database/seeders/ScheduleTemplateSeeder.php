<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ScheduleTemplateSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('schedule_templates')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $templates = [
            // BUS 1 - RUTE 3 (Jakarta-Bandung)
            [
                'bus_id' => 1,
                'route_id' => 3,
                'departure_time' => '06:00:00',
                'price' => 100000,
                'days_of_week' => json_encode([1,2,3,4,5,6,0]), // Setiap hari
                'status' => 'active'
            ],
            // BUS 1 - RUTE 1 (Jakarta-Bogor)
            [
                'bus_id' => 1,
                'route_id' => 1,
                'departure_time' => '15:00:00',
                'price' => 120000,
                'days_of_week' => json_encode([1,2,3,4,5,6,0]), // Setiap hari
                'status' => 'active'
            ],
            // BUS 1 - RUTE 5 (Jakarta-Cirebon)
            [
                'bus_id' => 1,
                'route_id' => 5,
                'departure_time' => '19:00:00',
                'price' => 250000,
                'days_of_week' => json_encode([1,2,3,4,5]), // Senin-Jumat aja
                'status' => 'active'
            ],
            
            // BUS 2 - RUTE 2 (Jakarta-Bandung via Cipularang)
            [
                'bus_id' => 2,
                'route_id' => 2,
                'departure_time' => '08:00:00',
                'price' => 350000,
                'days_of_week' => json_encode([1,2,3,4,5,6,0]), // Setiap hari
                'status' => 'active'
            ],
            // BUS 2 - RUTE 4 (Jakarta-Bandung via Puncak)
            [
                'bus_id' => 2,
                'route_id' => 4,
                'departure_time' => '09:00:00',
                'price' => 350000,
                'days_of_week' => json_encode([1,2,3,4,5,6,0]), // Setiap hari
                'status' => 'active'
            ],
            // BUS 2 - RUTE 7 (Jakarta-Cianjur)
            [
                'bus_id' => 2,
                'route_id' => 7,
                'departure_time' => '07:00:00',
                'price' => 300000,
                'days_of_week' => json_encode([1,2,3,4,5,6,0]), // Setiap hari
                'status' => 'active'
            ],
            
            // BUS 1 - RUTE 8 (Jakarta-Sukabumi)
            [
                'bus_id' => 1,
                'route_id' => 8,
                'departure_time' => '16:00:00',
                'price' => 300000,
                'days_of_week' => json_encode([6,0]), // Sabtu-Minggu aja
                'status' => 'active'
            ],
        ];

        foreach ($templates as $template) {
            DB::table('schedule_templates')->insert([
                'bus_id' => $template['bus_id'],
                'route_id' => $template['route_id'],
                'departure_time' => $template['departure_time'],
                'price' => $template['price'],
                'days_of_week' => $template['days_of_week'],
                'status' => $template['status'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        $this->command->info('Schedule templates seeded successfully!');
    }
}