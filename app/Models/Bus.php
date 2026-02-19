<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    protected $fillable = [
        'bus_number', 'name', 'type', 'total_seats', 'facilities', 'status'
    ];

    protected $casts = [
        'facilities' => 'array'
    ];

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function seats()
    {
        return $this->hasMany(Seat::class);
    }
}