<?php
namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Booking;
use App\Models\Seat;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    // PROSES PEMBAYARAN 
    public function process(Request $request, $booking_id)
    {
        $validator = Validator::make($request->all(), [
            'payment_method' => 'required|in:qris,credit_card,bank_transfer,ovo,gopay'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $booking = Booking::with('schedule.route')->find($booking_id);
        
        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking tidak ditemukan',
                'data' => null
            ], 404);
        }

        if ($booking->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
                'data' => null
            ], 403);
        }

        $existingPayment = Payment::where('booking_id', $booking_id)->first();
        if ($existingPayment) {
            return response()->json([
                'success' => false,
                'message' => 'Booking sudah memiliki pembayaran',
                'data' => $existingPayment
            ], 400);
        }

        if ($booking->status === 'paid') {
            return response()->json([
                'success' => false,
                'message' => 'Booking sudah lunas',
                'data' => null
            ], 400);
        }

        if ($booking->status === 'cancelled') {
            return response()->json([
                'success' => false,
                'message' => 'Booking sudah dibatalkan',
                'data' => null
            ], 400);
        }

        $payment = Payment::create([
            'payment_code' => 'PAY-' . strtoupper(Str::random(8)),
            'booking_id' => $booking->id,
            'amount' => $booking->total_price,
            'payment_method' => $request->payment_method,
            'status' => 'success',
            'paid_at' => now(),
            'expired_at' => now()->addHours(24)
        ]);

        $booking->update(['status' => 'paid']);

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran berhasil',
            'data' => [
                'payment' => [
                    'id' => $payment->id,
                    'payment_code' => $payment->payment_code,
                    'amount' => $payment->amount,
                    'method' => $payment->payment_method,
                    'status' => $payment->status,
                    'paid_at' => $payment->paid_at
                ],
                'booking' => [
                    'id' => $booking->id,
                    'booking_code' => $booking->booking_code,
                    'total_price' => $booking->total_price,
                    'status' => $booking->status,
                    'total_passengers' => $booking->total_passengers
                ]
            ]
        ], 201);
    }

    // CEK STATUS PEMBAYARAN
    public function checkStatus($booking_id)
    {
        $payment = Payment::where('booking_id', $booking_id)->first();
        
        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'Belum ada pembayaran',
                'data' => null
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Status pembayaran',
            'data' => [
                'payment_code' => $payment->payment_code,
                'amount' => $payment->amount,
                'method' => $payment->payment_method,
                'status' => $payment->status,
                'paid_at' => $payment->paid_at
            ]
        ], 200);
    }

    // LIHAT DETAIL PAYMENT
    public function show(Request $request, $id)
    {
        $payment = Payment::with('booking')->find($id);
        
        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'Pembayaran tidak ditemukan',
                'data' => null
            ], 404);
        }

        if ($payment->booking->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
                'data' => null
            ], 403);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail pembayaran',
            'data' => $payment
        ], 200);
    }

   

    //RIWAYAT PEMBAYARAN
    public function history(Request $request)
    {
        $payments = Payment::whereHas('booking', function($q) use ($request) {
            $q->where('user_id', $request->user()->id);
        })->with('booking.schedule.route')->orderBy('created_at', 'desc')->get();
        
        return response()->json([
            'success' => true,
            'message' => 'Riwayat pembayaran',
            'data' => $payments
        ], 200);
    }

    // REFUND PEMBAYARAN
    public function refund(Request $request, $id)
    {
        $payment = Payment::with('booking')->find($id);
        
        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'Pembayaran tidak ditemukan',
                'data' => null
            ], 404);
        }
        
        if ($payment->booking->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
                'data' => null
            ], 403);
        }
        
        if ($payment->status !== 'success') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya pembayaran sukses yang bisa direfund',
                'data' => null
            ], 400);
        }
        
        // Update status
        $payment->update(['status' => 'refunded']);
        $payment->booking->update(['status' => 'refunded']);
        
        // Kembalikan kursi
        Seat::whereIn('id', $payment->booking->seat_ids)->update(['is_available' => true]);
        Schedule::where('id', $payment->booking->schedule_id)
            ->increment('available_seats', $payment->booking->total_passengers);
        
        return response()->json([
            'success' => true,
            'message' => 'Refund berhasil',
            'data' => $payment
        ], 200);
    }

    // STATISTIK PEMBAYARAN (Admin)
    public function statistics()
    {
        $stats = [
            'total_payments' => Payment::count(),
            'total_success' => Payment::where('status', 'success')->count(),
            'total_pending' => Payment::where('status', 'pending')->count(),
            'total_refunded' => Payment::where('status', 'refunded')->count(),
            'total_amount' => Payment::where('status', 'success')->sum('amount'),
        ];
        
        return response()->json([
            'success' => true,
            'message' => 'Statistik pembayaran',
            'data' => $stats
        ], 200);
    }
}