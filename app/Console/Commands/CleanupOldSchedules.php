<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Schedule;
use Carbon\Carbon;

class CleanupOldSchedules extends Command
{
    protected $signature = 'schedules:cleanup';
    protected $description = 'Delete schedules older than today';

    public function handle()
    {
        $deleted = Schedule::where('departure_date', '<', Carbon::now()->format('Y-m-d'))
            ->delete();
            
        $this->info("Deleted {$deleted} old schedules");
    }
}