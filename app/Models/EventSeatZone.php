<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventSeatZone extends Model
{
    protected $fillable = [
        'event_id',
        'zone_name_fr',
        'zone_name_en',
        'zone_code',
        'price',
        'total_seats',
        'available_seats',
        'description_fr',
        'description_en',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Événement associé à cette zone de sièges.
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Tickets associés à cette zone.
     */
    public function tickets()
    {
        return $this->hasMany(EventTicket::class, 'seat_zone_id');
    }

    /**
     * Inventaire associé à cette zone.
     */
    public function inventory()
    {
        return $this->hasOne(EventInventory::class, 'seat_zone_id');
    }

    /**
     * Retourne le nom de la zone localisé selon la langue actuelle.
     */
    public function getZoneNameAttribute(): string
    {
        $locale = app()->getLocale();
        return $locale === 'fr' ? $this->zone_name_fr : ($this->zone_name_en ?? $this->zone_name_fr);
    }

    /**
     * Retourne la description localisée selon la langue actuelle.
     */
    public function getDescriptionAttribute(): string
    {
        $locale = app()->getLocale();
        return $locale === 'fr' ? $this->description_fr : ($this->description_en ?? $this->description_fr);
    }
}
