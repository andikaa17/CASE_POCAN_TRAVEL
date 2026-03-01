<?php
namespace App\Http\Controllers;

use App\Services\FirebaseService;
use App\Models\Payment;
use App\Models\Booking;
use App\Models\Seat;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $firebase;

    public function __construct(FirebaseService $firebase)
    {
        $this->firebase = $firebase;
    }

    public function process(Request $request, $booking_id)
    {
        // ... (method process yang udah ada)
    }

    public function processQris(Request $request, $booking_id)
    {
        Log::info('========== QRIS PROCESS DEBUG ==========');
        Log::info('Booking ID: ' . $booking_id);
        Log::info('User ID: ' . $request->user()->id);
        Log::info('Request data: ', $request->all());
        
        $validator = Validator::make($request->all(), [
            'payment_method' => 'required|in:qris'
        ]);

        if ($validator->fails()) {
            Log::info('❌ Validasi gagal: ', $validator->errors()->toArray());
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $booking = Booking::with(['schedule.route', 'user'])->find($booking_id);
        
        if (!$booking) {
            Log::info('❌ Booking tidak ditemukan: ' . $booking_id);
            return response()->json([
                'success' => false,
                'message' => 'Booking tidak ditemukan',
                'data' => null
            ], 404);
        }
        
        Log::info('✅ Booking ditemukan: ', [
            'id' => $booking->id, 
            'user_id' => $booking->user_id, 
            'status' => $booking->status,
            'total_price' => $booking->total_price
        ]);

        if ($booking->user_id !== $request->user()->id) {
            Log::info('❌ Unauthorized: booking user_id=' . $booking->user_id . ' vs request user_id=' . $request->user()->id);
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
                'data' => null
            ], 403);
        }

        // CEK APAKAH SUDAH ADA PAYMENT
        $existingPayment = Payment::where('booking_id', $booking_id)->first();
        if ($existingPayment) {
            // CEK APAKAH PAYMENT SUDAH EXPIRED
            if ($existingPayment->expired_at < now()) {
                // KALAU EXPIRED, HAPUS OTOMATIS
                Log::info('⚠️ Payment expired, auto delete: ', $existingPayment->toArray());
                $existingPayment->delete();
                
                // LANJUT BUAT PAYMENT BARU (lanjut ke bawah)
                Log::info('✅ Payment expired dihapus, lanjut buat baru');
            } else {
                // MASIH BERLAKU, KASIH TAU USER
                $minutesLeft = now()->diffInMinutes($existingPayment->expired_at);
                Log::info('❌ Payment masih aktif: ', $existingPayment->toArray());
                
                return response()->json([
                    'success' => false,
                    'message' => "Payment masih berlaku. Sisa waktu: {$minutesLeft} menit",
                    'data' => [
                        'existing_payment' => $existingPayment,
                        'expires_in_minutes' => $minutesLeft,
                        'expired_at' => $existingPayment->expired_at,
                        'can_regenerate' => false // Tidak perlu regenerate karena masih aktif
                    ]
                ], 400);
            }
        }

        if ($booking->status === 'paid') {
            Log::info('❌ Booking sudah lunas');
            return response()->json([
                'success' => false,
                'message' => 'Booking sudah lunas',
                'data' => null
            ], 400);
        }

        if ($booking->status === 'cancelled') {
            Log::info('❌ Booking sudah dibatalkan');
            return response()->json([
                'success' => false,
                'message' => 'Booking sudah dibatalkan',
                'data' => null
            ], 400);
        }

        Log::info('✅ Semua validasi lolos, lanjut generate QRIS');
        
        $qrData = json_encode([
            'booking_code' => $booking->booking_code,
            'amount' => $booking->total_price,
            'timestamp' => now()->timestamp
        ]);
        
        $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($qrData);

        // BUAT PAYMENT BARU DENGAN EXPIRED 30 MENIT (UBAH DARI 15 JADI 30)
        $payment = Payment::create([
            'payment_code' => 'PAY-' . strtoupper(Str::random(8)),
            'booking_id' => $booking->id,
            'amount' => $booking->total_price,
            'payment_method' => 'qris',
            'status' => 'pending',
            'paid_at' => null,
            'expired_at' => now()->addMinutes(30) // <-- UBAH JADI 30 MENIT!
        ]);

        Log::info('✅ Payment created: ', [
            'payment_id' => $payment->id, 
            'payment_code' => $payment->payment_code,
            'expired_at' => $payment->expired_at
        ]);

        try {
            $this->firebase->updatePaymentStatus($booking->id, 'pending', [
                'qr_url' => $qrCodeUrl,
                'amount' => $booking->total_price,
                'booking_code' => $booking->booking_code,
                'payment_id' => $payment->id,
                'expires_at' => $payment->expired_at->toIso8601String()
            ]);
            Log::info('✅ Firebase updated');
        } catch (\Exception $e) {
            Log::error('❌ Firebase error: ' . $e->getMessage());
            // Tetap lanjut meskipun firebase error
        }

        return response()->json([
            'success' => true,
            'message' => 'QRIS payment generated',
            'data' => [
                'payment_id' => $payment->id,
                'qr_code' => $qrCodeUrl,
                'expired_at' => $payment->expired_at,
                'expires_in_minutes' => 30,
                'firebase_path' => "payments/{$booking->id}",
                'amount' => $booking->total_price,
                'booking_code' => $booking->booking_code
            ]
        ], 201);
    }

    /**
     * REGENERATE QRIS - HAPUS PAYMENT LAMA BUAT YANG BARU
     */
    public function regenerateQris(Request $request, $booking_id)
    {
        Log::info('========== QRIS REGENERATE DEBUG ==========');
        Log::info('Booking ID: ' . $booking_id);
        Log::info('User ID: ' . $request->user()->id);
        
        $booking = Booking::find($booking_id);
        
        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking tidak ditemukan'
            ], 404);
        }

        if ($booking->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        // HAPUS PAYMENT LAMA (tanpa cek expired)
        $deleted = Payment::where('booking_id', $booking_id)->delete();
        Log::info('✅ Payment lama dihapus: ' . $deleted . ' row(s)');

        // PANGGIL PROCESS QRIS LAGI
        return $this->processQris($request, $booking_id);
    }

    public function simulatePaymentSuccess(Request $request, $booking_id)
    {
        Log::info('========== SIMULATE PAYMENT SUCCESS ==========');
        Log::info('Booking ID: ' . $booking_id);
        
        $payment = Payment::where('booking_id', $booking_id)->first();
        
        if (!$payment) {
            Log::info('❌ Payment not found for booking: ' . $booking_id);
            return response()->json([
                'success' => false,
                'message' => 'Payment not found'
            ], 404);
        }

        Log::info('✅ Payment found: ', $payment->toArray());

        $payment->update([
            'status' => 'success',
            'paid_at' => now()
        ]);
        
        $payment->booking->update(['status' => 'paid']);

        try {
            $this->firebase->updatePaymentStatus($booking_id, 'success');
            Log::info('✅ Firebase updated');
        } catch (\Exception $e) {
            Log::error('❌ Firebase error: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Payment success',
            'data' => $payment
        ]);
    }

    public function checkStatus($booking_id)
    {
        // ... (method yang udah ada)
    }

    public function show(Request $request, $id)
    {
        // ... (method yang udah ada)
    }

    public function history(Request $request)
    {
        // ... (method yang udah ada)
    }

    public function refund(Request $request, $id)
    {
        // ... (method yang udah ada)
    }

    public function statistics()
    {
        // ... (method yang udah ada)
    }
}