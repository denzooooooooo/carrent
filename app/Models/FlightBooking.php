<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlightBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'pnr',
        'eticket_number',
        'amadeus_booking_ref',
        'flight_segments',
        'base_price',
        'taxes',
        'margin_amount',
        'margin_percentage',
        'final_price',
        'ticket_status',
        'ticket_pdf_path',
        'cancellation_reason',
        'issued_at',
        'cancelled_at',
    ];

    protected $casts = [
        'flight_segments' => 'array',
        'base_price' => 'decimal:2',
        'taxes' => 'decimal:2',
        'margin_amount' => 'decimal:2',
        'margin_percentage' => 'decimal:2',
        'final_price' => 'decimal:2',
        'issued_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    /**
     * Relation avec la réservation principale
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Scope pour les billets émis
     */
    public function scopeIssued($query)
    {
        return $query->where('ticket_status', 'issued');
    }

    /**
     * Scope pour les billets annulés
     */
    public function scopeCancelled($query)
    {
        return $query->where('ticket_status', 'cancelled');
    }

    /**
     * Vérifier si le billet est émis
     */
    public function isIssued()
    {
        return $this->ticket_status === 'issued';
    }

    /**
     * Vérifier si le billet peut être annulé
     */
    public function canBeCancelled()
    {
        return in_array($this->ticket_status, ['issued', 'pending']);
    }
}
