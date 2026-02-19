<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'bus_id', 'route_id', 'departure_date', 'departure_time',
        'arrival_time', 'price', 'available_seats', 'status'
    ];

    protected $casts = [
        'departure_date' => 'date',
        'departure_time' => 'datetime',
        'arrival_time' => 'datetime'
    ];

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}