<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'template_id', 'bus_id', 'route_id', 
        'departure_date', 'departure_time', 'arrival_time',
        'price', 'available_seats', 'status'
    ];
    
    protected $casts = [
    'departure_date' => 'date:Y-m-d', 
    'departure_time' => 'string',
];
    public function template()
    {
        return $this->belongsTo(ScheduleTemplate::class);
    }
    
    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }
    
    public function route()
    {
        return $this->belongsTo(Route::class);
    }
}