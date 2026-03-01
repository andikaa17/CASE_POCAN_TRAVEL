<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Route;

class RouteSeeder extends Seeder
{
    public function run(): void
    {
        //route kota ke kota
        $newRoutes = [
          
            [
                'origin' => 'Bandung',
                'destination' => 'Jakarta',
                'distance_km' => 150,
                'estimated_duration' => 180,
                'base_price' => 100000
            ],
            [
                'origin' => 'Surabaya',
                'destination' => 'Jakarta',
                'distance_km' => 800,
                'estimated_duration' => 480,
                'base_price' => 350000
            ],
            
           
            [
                'origin' => 'Bandung',
                'destination' => 'Surabaya',
                'distance_km' => 600,
                'estimated_duration' => 420,
                'base_price' => 250000
            ],
            [
                'origin' => 'Surabaya',
                'destination' => 'Bandung',
                'distance_km' => 600,
                'estimated_duration' => 420,
                'base_price' => 250000
            ],
            
            
            [
                'origin' => 'Jakarta',
                'destination' => 'Yogyakarta',
                'distance_km' => 550,
                'estimated_duration' => 480,
                'base_price' => 300000
            ],
            [
                'origin' => 'Yogyakarta',
                'destination' => 'Jakarta',
                'distance_km' => 550,
                'estimated_duration' => 480,
                'base_price' => 300000
            ],
            [
                'origin' => 'Bandung',
                'destination' => 'Yogyakarta',
                'distance_km' => 400,
                'estimated_duration' => 360,
                'base_price' => 200000
            ],
            [
                'origin' => 'Surabaya',
                'destination' => 'Yogyakarta',
                'distance_km' => 300,
                'estimated_duration' => 240,
                'base_price' => 150000
            ],
        ];

        foreach ($newRoutes as $data) {
            
            $exists = Route::where('origin', $data['origin'])
                ->where('destination', $data['destination'])
                ->exists();
            
            if (!$exists) {
                Route::create($data);
                $this->command->info("✓ Menambah rute: {$data['origin']} → {$data['destination']}");
            } else {
                $this->command->warn("⚠ Rute {$data['origin']} → {$data['destination']} sudah ada, skip");
            }
        }

    }
}