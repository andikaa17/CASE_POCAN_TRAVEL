<?php
namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::with(['bus', 'route'])->get();
        
        return response()->json([
            'success' => true,
            'message' => 'Daftar jadwal',
            'data' => $schedules
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
        
        return response()->json([
            'success' => true,
            'message' => 'Detail jadwal',
            'data' => $schedule
        ]);
    }
}