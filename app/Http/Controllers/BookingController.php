<?php
namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Schedule;
use App\Models\Seat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function bookTicket(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'schedule_id' => 'required|exists:schedules,id',
            'passengers' => 'required|array|min:1',
            'passengers.*.seat_id' => 'required|exists:seats,id',
            'passengers.*.passenger_name' => 'required|string|max:255',
            'passengers.*.passenger_phone' => 'required|string|max:15',
            'passengers.*.passenger_email' => 'required|email'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $seatIds = collect($request->passengers)->pluck('seat_id')->toArray();
        
        $unavailableSeats = Seat::whereIn('id', $seatIds)
            ->where('is_available', false)
            ->get();

        if ($unavailableSeats->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Beberapa kursi tidak tersedia',
                'data' => $unavailableSeats->pluck('seat_number')
            ], 400);
        }

        $schedule = Schedule::with('route')->find($request->schedule_id);
        
        if ($schedule->available_seats < count($request->passengers)) {
            return response()->json([
                'success' => false,
                'message' => 'Kursi tidak mencukupi',
                'data' => null
            ], 400);
        }

        $totalPrice = $schedule->price * count($request->passengers);

        $booking = Booking::create([
            'booking_code' => 'BOOK-' . strtoupper(Str::random(8)),
            'user_id' => $request->user()->id,
            'schedule_id' => $request->schedule_id,
            'seat_ids' => $seatIds,
            'passengers_data' => $request->passengers,
            'total_passengers' => count($request->passengers),
            'total_price' => $totalPrice,
            'status' => 'pending'
        ]);

        Seat::whereIn('id', $seatIds)->update(['is_available' => false]);
        $schedule->decrement('available_seats', count($request->passengers));

        return response()->json([
            'success' => true,
            'message' => 'Booking berhasil',
            'data' => $booking
        ], 201);
    }

    public function myBookings(Request $request)
    {
        $bookings = Booking::where('user_id', $request->user()->id)
            ->with(['schedule.bus', 'schedule.route', 'payment'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Data booking',
            'data' => $bookings
        ], 200);
    }

    public function cancelBooking(Request $request, $id)
    {
        $booking = Booking::where('user_id', $request->user()->id)
            ->where('id', $id)
            ->first();

        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking tidak ditemukan',
                'data' => null
            ], 404);
        }

        if ($booking->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Booking tidak bisa dibatalkan',
                'data' => null
            ], 400);
        }

        Seat::whereIn('id', $booking->seat_ids)->update(['is_available' => true]);
        Schedule::where('id', $booking->schedule_id)
            ->increment('available_seats', $booking->total_passengers);

        $booking->update(['status' => 'cancelled']);

        return response()->json([
            'success' => true,
            'message' => 'Booking dibatalkan',
            'data' => $booking
        ], 200);
    }

    public function checkAvailability(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'schedule_id' => 'required|exists:schedules,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $schedule = Schedule::with(['bus.seats' => function($query) {
            $query->where('is_available', true);
        }])->find($request->schedule_id);

        return response()->json([
            'success' => true,
            'message' => 'Cek ketersediaan',
            'data' => [
                'schedule' => $schedule,
                'available_seats' => $schedule->bus->seats
            ]
        ], 200);
    }

    // METHOD UNTUK PAYMENT 
    public function checkPaymentStatus(Request $request, $id)
    {
        $booking = Booking::with('payment')->find($id);
        
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

        return response()->json([
            'success' => true,
            'message' => 'Status pembayaran',
            'data' => [
                'booking_id' => $booking->id,
                'booking_code' => $booking->booking_code,
                'total_price' => $booking->total_price,
                'status' => $booking->status,
                'payment' => $booking->payment ? [
                    'payment_code' => $booking->payment->payment_code,
                    'amount' => $booking->payment->amount,
                    'method' => $booking->payment->payment_method,
                    'payment_status' => $booking->payment->status,
                    'paid_at' => $booking->payment->paid_at,
                    'expired_at' => $booking->payment->expired_at
                ] : null,
                'is_paid' => $booking->payment && $booking->payment->status === 'success'
            ]
        ], 200);
    }
}