protected function schedule(Schedule $schedule)
{
    // Generate jadwal setiap jam 00:01
    $schedule->command('schedules:generate 7')->dailyAt('00:01');
    
    // Hapus jadwal yang sudah lewat (cleanup)
    $schedule->command('schedules:cleanup')->dailyAt('02:00');
    
    // TAMBAH INI: Cancel expired bookings setiap MENIT
    $schedule->command('bookings:cancel-expired')->everyMinute();
}