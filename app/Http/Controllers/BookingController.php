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
            'seat_ids' => 'required|array|min:1',
            'seat_ids.*' => 'exists:seats,id',
            'passengers_data' => 'required|array|min:1',
            'passengers_data.*.name' => 'required|string|max:255',
            'passengers_data.*.age' => 'required|integer|min:1|max:100',
            'passengers_data.*.id_card' => 'required|string|max:16',
            'passengers_data.*.phone' => 'nullable|string|max:14',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $seatIds = $request->seat_ids;
        
        // Cek ketersediaan kursi
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

        $schedule = Schedule::with('bus.seats')->find($request->schedule_id);
        
        if ($schedule->available_seats < count($seatIds)) {
            return response()->json([
                'success' => false,
                'message' => 'Kursi tidak mencukupi'
            ], 400);
        }

        $totalPrice = $schedule->price * count($seatIds);

        $booking = Booking::create([
            'booking_code' => 'BOOK-' . strtoupper(Str::random(8)),
            'user_id' => $request->user()->id,
            'schedule_id' => $request->schedule_id,
            'seat_ids' => $seatIds,
            'passengers_data' => $request->passengers_data,
            'total_passengers' => count($seatIds),
            'total_price' => $totalPrice,
            'status' => 'pending'
        ]);

        // Update status kursi
        Seat::whereIn('id', $seatIds)->update(['is_available' => false]);
        
        // Update available seats di schedule
        $schedule->decrement('available_seats', count($seatIds));

        // Ambil data kursi lengkap untuk response
        $seatsData = Seat::whereIn('id', $seatIds)->get();

        return response()->json([
            'success' => true,
            'message' => 'Booking berhasil',
            'data' => [
                'id' => $booking->id,
                'booking_code' => $booking->booking_code,
                'schedule_id' => $booking->schedule_id,
                'seat_ids' => $booking->seat_ids,
                'seats' => $seatsData->map(function($seat) {
                    return [
                        'id' => $seat->id,
                        'seat_number' => $seat->seat_number,
                        'floor' => $seat->floor
                    ];
                }),
                'total_passengers' => $booking->total_passengers,
                'total_price' => $booking->total_price,
                'status' => $booking->status,
                'created_at' => $booking->created_at
            ]
        ], 201);
    }

    public function myBookings(Request $request)
    {
        $bookings = Booking::where('user_id', $request->user()->id)
            ->with(['schedule.bus', 'schedule.route'])
            ->orderBy('created_at', 'desc')
            ->get();

        $formattedBookings = $bookings->map(function($booking) {
            // Ambil data kursi lengkap berdasarkan seat_ids
            $seats = Seat::whereIn('id', $booking->seat_ids ?? [])->get();
            
            return [
                'id' => $booking->id,
                'booking_code' => $booking->booking_code,
                'schedule_id' => $booking->schedule_id,
                'seat_ids' => $booking->seat_ids,
                'seats' => $seats->map(function($seat) {
                    return [
                        'id' => $seat->id,
                        'seat_number' => $seat->seat_number,
                        'floor' => $seat->floor
                    ];
                }),
                'passengers_data' => $booking->passengers_data,
                'total_passengers' => $booking->total_passengers,
                'total_price' => $booking->total_price,
                'status' => $booking->status,
                'created_at' => $booking->created_at,
                'schedule' => $booking->schedule ? [
                    'id' => $booking->schedule->id,
                    'departure_time' => $booking->schedule->departure_time,
                    'departure_date' => $booking->schedule->departure_date,
                    'price' => $booking->schedule->price,
                    'bus' => $booking->schedule->bus ? [
                        'id' => $booking->schedule->bus->id,
                        'name' => $booking->schedule->bus->name,
                        'total_seats' => $booking->schedule->bus->total_seats
                    ] : null,
                    'route' => $booking->schedule->route ? [
                        'id' => $booking->schedule->route->id,
                        'origin' => $booking->schedule->route->origin,
                        'destination' => $booking->schedule->route->destination
                    ] : null
                ] : null,
                'payment' => $booking->payment ? [
                    'id' => $booking->payment->id,
                    'payment_code' => $booking->payment->payment_code,
                    'amount' => $booking->payment->amount,
                    'status' => $booking->payment->status,
                    'payment_method' => $booking->payment->payment_method
                ] : null
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Data booking',
            'data' => $formattedBookings
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
            'data' => [
                'id' => $booking->id,
                'booking_code' => $booking->booking_code,
                'status' => $booking->status
            ]
        ], 200);
    }

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
                    'paid_at' => $booking->payment->paid_at
                ] : null,
                'is_paid' => $booking->payment && $booking->payment->status === 'paid'
            ]
        ], 200);
    }
}