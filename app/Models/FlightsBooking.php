<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlightsBooking extends Model
{
    use HasFactory;

    protected $table = 'flights_bookings';
    protected $fillable = [
        'booking_id',
        'pnr',
        'eticket_number',
        'booking_token',
        'departure_token',
        'flight_details',
        'flight_segments',
        'passenger_info',
        'booking_options',
        'base_price',
        'taxes',
        'margin_amount',
        'margin_percentage',
        'final_price',
        'currency',
        'ticket_status',
        'ticket_pdf_path',
        'cancellation_reason',
        'issued_at',
        'cancelled_at',
    ];

    protected $casts = [
        'flight_details' => 'array',
        'flight_segments' => 'array',
        'passenger_info' => 'array',
        'booking_options' => 'array',
        'base_price' => 'decimal:2',
        'taxes' => 'decimal:2',
        'margin_amount' => 'decimal:2',
        'margin_percentage' => 'decimal:2',
        'final_price' => 'decimal:2',
        'issued_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function scopeIssued($query)
    {
        return $query->where('ticket_status', 'issued');
    }

    public function scopePending($query)
    {
        return $query->where('ticket_status', 'pending');
    }

    public function scopeCancelled($query)
    {
        return $query->where('ticket_status', 'cancelled');
    }

    public function isIssued()
    {
        return $this->ticket_status === 'issued';
    }

    public function canBeCancelled()
    {
        return in_array($this->ticket_status, ['issued', 'pending', 'confirmed']);
    }
}