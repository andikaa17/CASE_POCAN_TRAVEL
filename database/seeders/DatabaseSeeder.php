<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@cantravel.com',
            'password' => Hash::make('admin123'),
            'phone' => '08123456789',
            'role' => 'admin'
        ]);

        User::create([
            'name' => 'Customer',
            'email' => 'customer@example.com',
            'password' => Hash::make('customer123'),
            'phone' => '08123456788',
            'role' => 'customer'
        ]);

        $this->call([
            BusSeeder::class,
            RouteSeeder::class,
            ScheduleSeeder::class,
        ]);
    }
}