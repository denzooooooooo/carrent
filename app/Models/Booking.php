<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_number',
        'user_id',
        'booking_type',
        'flight_id',
        'event_id',
        'package_id',
        'seat_zone_id',
        'booking_date',
        'travel_date',
        'number_of_passengers',
        'passenger_details',
        'seat_class',
        'seat_numbers',
        'total_amount',
        'currency',
        'discount_amount',
        'tax_amount',
        'final_amount',
        'status',
        'payment_status',
        'special_requests',
        'cancellation_reason',
        'cancelled_at',
        'confirmed_at',
    ];

    protected $casts = [
        'passenger_details' => 'array',
        'booking_date' => 'datetime',
        'travel_date' => 'date',
        'cancelled_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'total_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'final_amount' => 'decimal:2',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function flight()
    {
        return $this->belongsTo(Flight::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function package()
    {
        return $this->belongsTo(TourPackage::class, 'package_id');
    }

    public function seatZone()
    {
        return $this->belongsTo(EventSeatZone::class, 'seat_zone_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
