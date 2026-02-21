<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasImageUrl;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Event extends Model implements HasMedia
{
    use HasFactory, HasImageUrl, InteractsWithMedia;

    /**
     * Attributs remplissables en masse.
     */
    protected $fillable = [
        'category_id',
        'type_id',
        'title_fr',
        'title_en',
        'slug',
        'description_fr',
        'description_en',
        'venue_name',
        'venue_address',
        'city',
        'country',
        'event_date',
        'event_time',
        'end_date',
        'end_time',
        'image',
        'gallery',
        'video_url',
        'organizer',
        'min_price',
        'max_price',
        'total_seats',
        'available_seats',
        'is_featured',
        'is_active',
        'meta_title_fr',
        'meta_title_en',
        'meta_description_fr',
        'meta_description_en',
    ];

    /**
     * Casts automatiques des colonnes.
     */
    protected $casts = [
        'event_date' => 'date',
        'end_date' => 'date',
        'gallery' => 'array',
        'min_price' => 'decimal:2',
        'max_price' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
            ->useDisk('avatars'); // Défini dans config/filesystems.php
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('small')
            ->width(368)
            ->nonQueued();

        $this->addMediaConversion('normal')
            ->width(800)
            ->nonQueued();
    }

    /**
     * Catégorie (musique, sport, etc.)
     */
    public function category()
    {
        return $this->belongsTo(EventCategory::class, 'category_id');
    }

    /**
     * Type (match de foot, concert pop, théâtre classique, etc.)
     */
    public function type()
    {
        return $this->belongsTo(EventType::class, 'type_id');
    }

    /**
     * Zones de sièges pour la billetterie.
     */
    public function seatZones()
    {
        return $this->hasMany(EventSeatZone::class);
    }

    /**
     * Tickets disponibles pour l'événement.
     */
    public function tickets()
    {
        return $this->hasMany(EventTicket::class);
    }

    /**
     * Réservations effectuées sur cet événement.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Avis associés à cet événement.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class, 'item_id')
                    ->where('item_type', 'event');
    }

    /**
     * Inventaire spécifique à l'événement.
     */
    public function inventory()
    {
        return $this->hasOne(EventInventory::class);
    }

    /**
     * Retourne le titre localisé selon la langue actuelle.
     */
    public function getTitleAttribute(): string
    {
        $locale = app()->getLocale();
        return $locale === 'fr' ? $this->title_fr : ($this->title_en ?? $this->title_fr);
    }

    /**
     * Vérifie si l’événement est passé.
     */
    public function getIsPastAttribute(): bool
    {
        return $this->event_date->isPast();
    }
}
