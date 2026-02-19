<?php
namespace App\Http\Controllers;

use App\Models\Bus;
use Illuminate\Http\Request;

class BusController extends Controller
{
    public function index()
    {
        $buses = Bus::with('seats')->get();
        
        return response()->json([
            'success' => true,
            'message' => 'Daftar bus',
            'data' => $buses
        ]);
    }
}