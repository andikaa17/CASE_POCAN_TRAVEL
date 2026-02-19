<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    protected $fillable = [
        'origin', 'destination', 'distance_km', 'estimated_duration', 'base_price'
    ];

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}