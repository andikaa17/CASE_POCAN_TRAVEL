<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ScheduleTemplate;
use App\Models\Schedule;
use Carbon\Carbon;

class GenerateSchedules extends Command
{
    protected $signature = 'schedules:generate {days=7 : Jumlah hari ke depan}';
    protected $description = 'Generate schedules from templates for next X days';

    public function handle()
    {
        $days = (int) $this->argument('days');
        $startDate = Carbon::now()->addDay();
        $endDate = Carbon::now()->addDays($days);
        
        $this->info("Generating schedules from {$startDate->format('Y-m-d')} to {$endDate->format('Y-m-d')}");
        
        $templates = ScheduleTemplate::with(['bus', 'route'])
            ->where('status', 'active')
            ->get();
            
        if ($templates->isEmpty()) {
            $this->error('No active templates found!');
            return Command::FAILURE;
        }
        
        $created = 0;
        $skipped = 0;
        
        for ($date = clone $startDate; $date <= $endDate; $date->addDay()) {
            $dayOfWeek = $date->dayOfWeek;
            
            foreach ($templates as $template) {
                $daysOfWeek = $template->days_of_week ?? [1,2,3,4,5];
                
                if (!in_array($dayOfWeek, $daysOfWeek)) {
                    continue;
                }
                
                $exists = Schedule::where('template_id', $template->id)
                    ->whereDate('departure_date', $date)
                    ->exists();
                
                if ($exists) {
                    $skipped++;
                    continue;
                }
                
                // ===== INI YANG SUDAH FIX =====
                $duration = $template->route->estimated_duration ?? 2;
                
                // AMBIL JAMNYA AJA (H:i:s)
                $jam = $template->departure_time->format('H:i:s');
                
                $departureDateTime = Carbon::parse($date->format('Y-m-d') . ' ' . $jam);
                $arrivalDateTime = $departureDateTime->copy()->addHours((int) $duration);
                // ===== END FIX =====
                
                Schedule::create([
                    'template_id' => $template->id,
                    'bus_id' => $template->bus_id,
                    'route_id' => $template->route_id,
                    'departure_date' => $date->format('Y-m-d'),
                    'departure_time' => $jam, // SIMPAN JAM DOANG
                    'arrival_time' => $arrivalDateTime->format('H:i:s'),
                    'price' => $template->price,
                    'available_seats' => $template->bus->capacity ?? 40,
                    'status' => 'scheduled'
                ]);
                
                $created++;
            }
        }
        
        $this->info("✅ Created: {$created} new schedules");
        $this->info("⏭️  Skipped: {$skipped} (already exist)");
        
        return Command::SUCCESS;
    }
}