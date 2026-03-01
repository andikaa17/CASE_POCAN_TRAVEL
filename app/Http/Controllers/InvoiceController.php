<?php
namespace App\Http\Controllers;

use App\Models\Booking;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InvoiceController extends Controller
{
    public function generate($id)
    {
        try {
            Log::info('========== GENERATE INVOICE ==========');
            Log::info('Invoice ID: ' . $id);
            
            // Ambil booking dengan semua relasi yang diperlukan
            $booking = Booking::with([
                'user', 
                'schedule.route', 
                'schedule.bus',
                'payment'
            ])
            ->where('user_id', auth()->id())
            ->find($id);

            if (!$booking) {
                Log::error('❌ Booking not found for ID: ' . $id);
                return response()->json([
                    'success' => false,
                    'message' => 'Booking tidak ditemukan'
                ], 404);
            }

            Log::info('✅ Booking found:', [
                'id' => $booking->id,
                'code' => $booking->booking_code,
                'status' => $booking->status,
                'passengers_count' => count($booking->passengers_data ?? []),
                'seats' => $booking->seat_ids
            ]);

            // Cek status booking
            if ($booking->status !== 'paid') {
                Log::error('❌ Booking not paid. Status: ' . $booking->status);
                return response()->json([
                    'success' => false,
                    'message' => 'Invoice hanya tersedia untuk booking yang sudah lunas'
                ], 400);
            }

            // Data perusahaan
            $company = [
                'name' => 'CAN TRAVEL',
                'address' => 'Jl. Raya No. 123, Jakarta',
                'phone' => '021-1234567',
                'email' => 'info@cantravel.com'
            ];

            $data = [
                'booking' => $booking,
                'company' => $company,
                'date' => now()->format('d/m/Y H:i:s')
            ];

            Log::info('✅ Generating PDF with data');
            
            $pdf = Pdf::loadView('pdf.invoice', $data);
            $filename = 'invoice-' . $booking->booking_code . '.pdf';
            
            Log::info('✅ PDF generated: ' . $filename);
            
            return $pdf->download($filename);

        } catch (\Exception $e) {
            Log::error('❌ Error generating invoice: ' . $e->getMessage());
            Log::error('❌ Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal generate invoice: ' . $e->getMessage()
            ], 500);
        }
    }
}