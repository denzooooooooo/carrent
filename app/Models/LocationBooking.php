<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LocationBooking extends Model
{
    protected $fillable = [
        'location_id',
        'booking_id',
        'user_name',
        'user_email',
        'user_phone',
        'start_date',
        'end_date',
        'days',
        'unit_price',
        'total_price',
        'status',
        'special_requests',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
