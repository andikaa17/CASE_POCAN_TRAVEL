<?php

namespace App\Console\Commands;

use App\Models\Payment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CancelExpiredBookings extends Command
{
    protected $signature = 'bookings:cancel-expired';
    protected $description = 'Cancel bookings with expired payments';

    public function handle()
    {
        $expiredPayments = Payment::with('booking')
            ->where('status', 'pending')
            ->where('expired_at', '<', now())
            ->get();

        $count = 0;
        foreach ($expiredPayments as $payment) {
            if ($payment->booking) {
                $payment->booking->update(['status' => 'cancelled']);
                $payment->update(['status' => 'expired']);
                
                Log::info('Booking expired & cancelled: ' . $payment->booking->booking_code);
                $count++;
            }
        }

        $this->info("✅ {$count} expired bookings cancelled.");
    }
}