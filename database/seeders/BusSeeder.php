<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bus;
use App\Models\Seat;

class BusSeeder extends Seeder
{
    public function run(): void
    {
        $buses = [
            [
                'bus_number' => 'BUS-001',
                'name' => 'CAN Executive',
                'type' => 'eksekutif',
                'total_seats' => 40,
                'facilities' => json_encode(['AC', 'TV', 'Toilet']),
                'status' => 'active'
            ],
            [
                'bus_number' => 'BUS-002',
                'name' => 'CAN Business',
                'type' => 'bisnis',
                'total_seats' => 36,
                'facilities' => json_encode(['AC', 'TV', 'WiFi']),
                'status' => 'active'
            ],
        ];

        foreach ($buses as $data) {
            $bus = Bus::create($data);
            
            // Buat kursi
            for ($i = 1; $i <= $bus->total_seats; $i++) {
                Seat::create([
                    'bus_id' => $bus->id,
                    'seat_number' => 'A' . str_pad($i, 2, '0', STR_PAD_LEFT),
                    'floor' => $i <= 20 ? 1 : 2,
                    'is_available' => true
                ]);
            }
        }
    }
}