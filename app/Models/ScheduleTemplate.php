<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduleTemplate extends Model
{
    protected $fillable = [
        'bus_id', 'route_id', 'departure_time', 
        'price', 'days_of_week', 'status'
    ];
    
    protected $casts = [
        'days_of_week' => 'array',
        'departure_time' => 'datetime:H:i:s',
    ];
    
    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }
    
    public function route()
    {
        return $this->belongsTo(Route::class);
    }
    
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}