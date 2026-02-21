<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EventTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'event_id',
        'ticket_number',
        'qr_code',
        'qr_data',
        'seat_zone_id',
        'seat_number',
        'base_price',
        'margin_amount',
        'final_price',
        'ticket_status',
        'used_at',
        'used_by',
        'ticket_pdf_path',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'margin_amount' => 'decimal:2',
        'final_price' => 'decimal:2',
        'used_at' => 'datetime',
    ];

    /**
     * Générer un numéro de billet unique
     */
    public static function generateTicketNumber()
    {
        do {
            $number = 'TKT-' . strtoupper(Str::random(10));
        } while (self::where('ticket_number', $number)->exists());

        return $number;
    }

    /**
     * Relation avec la réservation
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Relation avec l'événement
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Relation avec la zone de siège
     */
    public function seatZone()
    {
        return $this->belongsTo(EventSeatZone::class, 'seat_zone_id');
    }

    /**
     * Scope pour les billets valides
     */
    public function scopeValid($query)
    {
        return $query->where('ticket_status', 'valid');
    }

    /**
     * Scope pour les billets utilisés
     */
    public function scopeUsed($query)
    {
        return $query->where('ticket_status', 'used');
    }

    /**
     * Vérifier si le billet est valide
     */
    public function isValid()
    {
        return $this->ticket_status === 'valid';
    }

    /**
     * Marquer le billet comme utilisé
     */
    public function markAsUsed($usedBy = null)
    {
        $this->update([
            'ticket_status' => 'used',
            'used_at' => now(),
            'used_by' => $usedBy,
        ]);
    }

    /**
     * Annuler le billet
     */
    public function cancel()
    {
        $this->update(['ticket_status' => 'cancelled']);
    }
}
