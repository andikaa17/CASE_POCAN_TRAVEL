<?php
namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Seat;
use App\Models\Booking;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $query = Schedule::with(['bus', 'route']);
        
        // Filter by origin
        if ($request->has('origin') && $request->origin) {
            $query->whereHas('route', function($q) use ($request) {
                $q->where('origin', 'LIKE', '%' . $request->origin . '%');
            });
        }
        
        // Filter by destination
        if ($request->has('destination') && $request->destination) {
            $query->whereHas('route', function($q) use ($request) {
                $q->where('destination', 'LIKE', '%' . $request->destination . '%');
            });
        }
        
        // Filter by date
        if ($request->has('date') && $request->date) {
            $query->whereDate('departure_date', $request->date);
            
            // 🔥 FILTER JAM YANG MASIH BERLAKU (HANYA UNTUK HARI INI)
            if ($request->date == Carbon::today()->toDateString()) {
                $query->whereTime('departure_time', '>', Carbon::now()->format('H:i:s'));
            }
        }
        
        $schedules = $query->get();
        
        // ===== PERBAIKAN: FORMAT DATA UNTUK FRONTEND =====
        $formattedSchedules = $schedules->map(function($schedule) {
    // 🔥 GABUNGIN TANGGAL + JAM, BARU PARSE
    $departureDateTime = Carbon::createFromFormat(
        'Y-m-d H:i:s',
        $schedule->departure_date->format('Y-m-d') . ' ' . $schedule->departure_time,
        'Asia/Jakarta'
    );
    
    return [
        'id' => $schedule->id,
        'departure_time' => $departureDateTime->format('H:i'),
        'departure_datetime' => $departureDateTime->toDateTimeString(), // "2026-03-01 15:00:00"
        'departure_date' => $departureDateTime->format('Y-m-d'),
        'available_seats' => $schedule->available_seats,
        'price' => $schedule->price,
        'route' => [
            'origin' => $schedule->route->origin,
            'destination' => $schedule->route->destination
        ],
        'bus' => [
            'name' => $schedule->bus->name,
            'total_seats' => $schedule->bus->total_seats
        ]
    ];
});
        
        return response()->json([
            'success' => true,
            'message' => 'Daftar jadwal',
            'data' => $formattedSchedules
        ]);
    }

    public function show($id)
    {
        $schedule = Schedule::with(['bus', 'route'])->find($id);
        
        if (!$schedule) {
            return response()->json([
                'success' => false,
                'message' => 'Jadwal tidak ditemukan',
                'data' => null
            ], 404);
        }
        
        // ===== FORMAT DATA UNTUK DETAIL =====
        $formattedSchedule = [
            'id' => $schedule->id,
            'departure_time' => Carbon::parse($schedule->departure_time)->format('H:i'),
            'departure_datetime' => $schedule->departure_date . ' ' . $schedule->departure_time,
            'departure_date' => $schedule->departure_date,
            'available_seats' => $schedule->available_seats,
            'price' => $schedule->price,
            'route' => [
                'origin' => $schedule->route->origin,
                'destination' => $schedule->route->destination
            ],
            'bus' => [
                'name' => $schedule->bus->name,
                'total_seats' => $schedule->bus->total_seats
            ]
        ];
        
        return response()->json([
            'success' => true,
            'message' => 'Detail jadwal',
            'data' => $formattedSchedule
        ]);
    }
    
    public function checkAvailability(Request $request)
    {
        $scheduleId = $request->schedule_id;
        $date = $request->date;
        
        $schedule = Schedule::with(['bus', 'route'])->find($scheduleId);
        
        if (!$schedule) {
            return response()->json([
                'success' => false,
                'message' => 'Jadwal tidak ditemukan',
                'data' => null
            ], 404);
        }
        
        // ===== FORMAT SCHEDULE =====
        $formattedSchedule = [
            'id' => $schedule->id,
            'departure_time' => Carbon::parse($schedule->departure_time)->format('H:i'),
            'departure_datetime' => $schedule->departure_date . ' ' . $schedule->departure_time,
            'departure_date' => $schedule->departure_date,
            'available_seats' => $schedule->available_seats,
            'price' => $schedule->price,
            'route' => [
                'origin' => $schedule->route->origin,
                'destination' => $schedule->route->destination
            ],
            'bus' => [
                'name' => $schedule->bus->name,
                'total_seats' => $schedule->bus->total_seats
            ]
        ];
        
        // Ambil kursi yang sudah dibooking
        $bookedSeats = Booking::where('schedule_id', $scheduleId)
            ->whereIn('status', ['pending', 'paid'])
            ->get()
            ->pluck('seat_ids')
            ->flatten()
            ->toArray();
        
        // Ambil semua kursi
        $seats = Seat::where('bus_id', $schedule->bus_id)->get();
        
        // Format kursi
        $seatsData = $seats->map(function($seat) use ($bookedSeats) {
            return [
                'id' => $seat->id,
                'seat_number' => $seat->seat_number,
                'is_available' => !in_array($seat->id, $bookedSeats)
            ];
        });
        
        return response()->json([
            'success' => true,
            'message' => 'Cek ketersediaan kursi',
            'data' => [
                'schedule' => $formattedSchedule,
                'seats' => $seatsData
            ]
        ]);
    }
}