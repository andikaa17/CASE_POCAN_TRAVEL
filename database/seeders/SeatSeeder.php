<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeatSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua bus
        $buses = DB::table('buses')->get();
        
        if ($buses->isEmpty()) {
            $this->command->error('❌ TIDAK ADA DATA BUS! Jalankan BusSeeder dulu.');
            return;
        }
        
        foreach ($buses as $bus) {
            // Hapus kursi lama untuk bus ini
            DB::table('seats')->where('bus_id', $bus->id)->delete();
            
            // Gunakan total_seats dari tabel buses
            $totalSeats = $bus->total_seats ?? 40;
            $this->command->info("🚌 Memproses bus: {$bus->bus_number} (total kursi: {$totalSeats})");
            
            $inserted = 0;
            
            // Buat kursi sesuai total_seats
            for ($i = 1; $i <= $totalSeats; $i++) {
                // Hitung baris (A, B, C, D...)
                $row = chr(64 + ceil($i / 4));
                // Hitung kolom (1,2,3,4)
                $col = (($i - 1) % 4) + 1;
                
                // Insert sesuai struktur tabel
                DB::table('seats')->insert([
                    'bus_id' => $bus->id,
                    'seat_number' => $row . $col,
                    'floor' => '1', // Default lantai 1
                    'is_available' => 1, // 1 = tersedia
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                $inserted++;
            }
            
            $this->command->info("✅ Berhasil insert {$inserted} kursi untuk bus {$bus->bus_number}");
        }
        
        // Verifikasi total
        $total = DB::table('seats')->count();
        $this->command->info("📊 TOTAL KURSI DI DATABASE: {$total}");
    }
}