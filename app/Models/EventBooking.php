<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventBooking extends Model
{
    protected $fillable = [
        'event_id',
        'zone_id',
        'package_id',
        'user_name',
        'user_email',
        'user_phone',
        'quantity',
        'unit_price',
        'total_price',
        'booking_reference',
        'status',
        'booking_date',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'booking_date' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function zone()
    {
        return $this->belongsTo(EventSeatZone::class, 'zone_id');
    }

    public function package()
    {
        return $this->belongsTo(EventPackage::class, 'package_id');
    }
}
