<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Route;

class RouteSeeder extends Seeder
{
    public function run(): void
    {
        $routes = [
            [
                'origin' => 'Jakarta',
                'destination' => 'Bandung',
                'distance_km' => 150,
                'estimated_duration' => 180,
                'base_price' => 100000
            ],
            [
                'origin' => 'Jakarta',
                'destination' => 'Surabaya',
                'distance_km' => 800,
                'estimated_duration' => 480,
                'base_price' => 350000
            ],
        ];

        foreach ($routes as $data) {
            Route::create($data);
        }
    }
}