<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventInventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'seat_zone_id',
        'total_tickets',
        'sold_tickets',
        'reserved_tickets',
        'available_tickets',
        'last_updated',
    ];

    protected $casts = [
        'total_tickets' => 'integer',
        'sold_tickets' => 'integer',
        'reserved_tickets' => 'integer',
        'available_tickets' => 'integer',
        'last_updated' => 'datetime',
    ];

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
     * Vérifier la disponibilité
     */
    public function hasAvailability($quantity = 1)
    {
        return $this->available_tickets >= $quantity;
    }

    /**
     * Réserver des billets
     */
    public function reserve($quantity)
    {
        if (!$this->hasAvailability($quantity)) {
            return false;
        }

        $this->increment('reserved_tickets', $quantity);
        $this->decrement('available_tickets', $quantity);
        $this->update(['last_updated' => now()]);

        return true;
    }

    /**
     * Confirmer la vente
     */
    public function confirmSale($quantity)
    {
        $this->increment('sold_tickets', $quantity);
        $this->decrement('reserved_tickets', $quantity);
        $this->update(['last_updated' => now()]);
    }

    /**
     * Annuler une réservation
     */
    public function cancelReservation($quantity)
    {
        $this->decrement('reserved_tickets', $quantity);
        $this->increment('available_tickets', $quantity);
        $this->update(['last_updated' => now()]);
    }
}
