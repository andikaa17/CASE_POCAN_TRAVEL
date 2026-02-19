<?php
namespace App\Http\Controllers;

use App\Models\Booking;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function generate($id)
    {
        $booking = Booking::with(['user', 'schedule.bus', 'schedule.route', 'payment'])
            ->where('user_id', auth()->id())
            ->find($id);

        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking tidak ditemukan'
            ], 404);
        }

        $data = [
            'booking' => $booking,
            'company' => [
                'name' => 'CAN Travel',
                'address' => 'Jl. Contoh No. 123, Jakarta',
                'phone' => '021-1234567',
                'email' => 'info@cantravel.com'
            ],
            'date' => now()->format('d/m/Y H:i:s')
        ];

        $pdf = Pdf::loadView('pdf.invoice', $data);
        
        return $pdf->download('invoice-'.$booking->booking_code.'.pdf');
    }

    public function preview($id)
    {
        $booking = Booking::with(['user', 'schedule.bus', 'schedule.route', 'payment'])
            ->where('user_id', auth()->id())
            ->find($id);

        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking tidak ditemukan'
            ], 404);
        }

        $data = [
            'booking' => $booking,
            'company' => [
                'name' => 'CAN Travel',
                'address' => 'Jl. Contoh No. 123, Jakarta',
                'phone' => '021-1234567',
                'email' => 'info@cantravel.com'
            ],
            'date' => now()->format('d/m/Y H:i:s')
        ];

        $pdf = Pdf::loadView('pdf.invoice', $data);
        
        return $pdf->stream('invoice-'.$booking->booking_code.'.pdf');
    }
}