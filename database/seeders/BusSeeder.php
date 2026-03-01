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
            [
                'bus_number' => 'BUS-003',
                'name' => 'CAN Super Executive',
                'type' => 'eksekutif', // <-- UBAH DARI 'vip' JADI 'eksekutif'
                'total_seats' => 30,
                'facilities' => json_encode(['AC', 'TV', 'Toilet', 'WiFi', 'Snack']),
                'status' => 'active'
            ],
            [
                'bus_number' => 'BUS-004',
                'name' => 'CAN Economy',
                'type' => 'ekonomi',
                'total_seats' => 50,
                'facilities' => json_encode(['AC', 'Toilet']),
                'status' => 'active'
            ],
            [
                'bus_number' => 'BUS-005',
                'name' => 'CAN VIP',  // Nama tetap VIP
                'type' => 'eksekutif', // <-- UBAH DARI 'vip' JADI 'eksekutif'
                'total_seats' => 24,
                'facilities' => json_encode(['AC', 'TV', 'Toilet', 'WiFi', 'Makanan', 'Minuman']),
                'status' => 'active'
            ],
            [
                'bus_number' => 'BUS-006',
                'name' => 'CAN Double Decker',
                'type' => 'eksekutif',
                'total_seats' => 60,
                'facilities' => json_encode(['AC', 'TV', 'Toilet', 'WiFi', '2 Lantai']),
                'status' => 'active'
            ],
        ];

        foreach ($buses as $data) {
            $bus = Bus::create($data);
            
            // Buat kursi sesuai total_seats
            for ($i = 1; $i <= $bus->total_seats; $i++) {
                // Tentukan lantai (1 atau 2)
                $floor = 1;
                if ($bus->total_seats > 40) {
                    // Untuk bus besar, kursi 1-30 lantai 1, sisanya lantai 2
                    $floor = $i <= 30 ? 1 : 2;
                } else if ($bus->total_seats > 30) {
                    // Untuk bus sedang, kursi 1-20 lantai 1, sisanya lantai 2
                    $floor = $i <= 20 ? 1 : 2;
                }
                
                // Format seat number (A01, A02, dst)
                $seatNumber = 'A' . str_pad($i, 2, '0', STR_PAD_LEFT);
                
                Seat::create([
                    'bus_id' => $bus->id,
                    'seat_number' => $seatNumber,
                    'floor' => $floor,
                    'is_available' => true
                ]);
            }
            
            $this->command->info("Bus {$bus->name} created with {$bus->total_seats} seats");
        }
        
        $this->command->info('Total ' . count($buses) . ' buses created successfully!');
    }
}