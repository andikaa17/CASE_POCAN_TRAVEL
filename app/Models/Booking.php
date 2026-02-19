<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'booking_code', 'user_id', 'schedule_id', 'seat_ids',
        'passengers_data', 'total_passengers', 'total_price', 'status'
    ];

    //JSON otomatis jadi array
    protected $casts = [
        'seat_ids' => 'array',
        'passengers_data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}