<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlightBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'flight_number',
        'airline',
        'departure_airport',
        'arrival_airport',
        'departure_time',
        'arrival_time',
        'departure_date',
        'cabin_class',
        'duffel_offer_id',
        'duffel_order_id',
        'duffel_booking_reference',  // <-- ADDED
        'duration',
        'stops',
        'base_price',
        'total_price',
        'commission_amount',
        'passengers_count',
        'adults_count',
        'children_count',
        'infants_count',
        'ticket_status',
        'ticket_number',
        'duffel_confirmed_at',
        'payment_split_status',
    ];

    protected $casts = [
        'departure_time' => 'datetime',
        'arrival_time' => 'datetime',
        'departure_date' => 'date',
        'duffel_confirmed_at' => 'datetime',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}

