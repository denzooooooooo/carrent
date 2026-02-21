<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    use HasFactory;

    protected $fillable = [
        'airline_id',
        'flight_number',
        'departure_airport_id',
        'arrival_airport_id',
        'departure_date',
        'departure_time',
        'arrival_date',
        'arrival_time',
        'duration',
        'aircraft_type',
        'economy_seats',
        'business_seats',
        'first_class_seats',
        'economy_price',
        'business_price',
        'first_class_price',
        'available_economy',
        'available_business',
        'available_first_class',
        'status',
        'is_active',
    ];

    protected $casts = [
        'departure_date' => 'date',
        'arrival_date' => 'date',
        'economy_price' => 'decimal:2',
        'business_price' => 'decimal:2',
        'first_class_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function airline()
    {
        return $this->belongsTo(Airline::class);
    }

    public function departureAirport()
    {
        return $this->belongsTo(Airport::class, 'departure_airport_id');
    }

    public function arrivalAirport()
    {
        return $this->belongsTo(Airport::class, 'arrival_airport_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
