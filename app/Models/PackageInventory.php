<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageInventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'package_id',
        'available_date',
        'max_participants',
        'booked_participants',
        'available_spots',
        'price_override',
        'is_available',
        'notes',
    ];

    protected $casts = [
        'available_date' => 'date',
        'max_participants' => 'integer',
        'booked_participants' => 'integer',
        'available_spots' => 'integer',
        'price_override' => 'decimal:2',
        'is_available' => 'boolean',
    ];

    /**
     * Relation avec le package
     */
    public function package()
    {
        return $this->belongsTo(TourPackage::class, 'package_id');
    }

    /**
     * Scope pour les dates disponibles
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true)
                     ->where('available_spots', '>', 0);
    }

    /**
     * Scope pour une date spécifique
     */
    public function scopeForDate($query, $date)
    {
        return $query->where('available_date', $date);
    }

    /**
     * Vérifier la disponibilité
     */
    public function hasAvailability($participants = 1)
    {
        return $this->is_available && $this->available_spots >= $participants;
    }

    /**
     * Réserver des places
     */
    public function book($participants)
    {
        if (!$this->hasAvailability($participants)) {
            return false;
        }

        $this->increment('booked_participants', $participants);
        $this->decrement('available_spots', $participants);

        if ($this->available_spots <= 0) {
            $this->update(['is_available' => false]);
        }

        return true;
    }

    /**
     * Annuler une réservation
     */
    public function cancelBooking($participants)
    {
        $this->decrement('booked_participants', $participants);
        $this->increment('available_spots', $participants);
        
        if ($this->available_spots > 0) {
            $this->update(['is_available' => true]);
        }
    }

    /**
     * Obtenir le prix effectif
     */
    public function getEffectivePrice()
    {
        return $this->price_override ?? $this->package->price;
    }
}
