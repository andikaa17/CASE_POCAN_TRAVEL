<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    protected $fillable = [
        'bus_id', 'seat_number', 'floor', 'is_available'
    ];

    protected $casts = [
        'is_available' => 'boolean'
    ];

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }
}