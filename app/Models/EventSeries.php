<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasImageUrl;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class EventSeries extends Model implements HasMedia
{
    use HasFactory, HasImageUrl, InteractsWithMedia;

    protected $fillable = [
        'name_fr',
        'name_en',
        'slug',
        'description_fr',
        'description_en',
        'venue_name',
        'venue_address',
        'city',
        'country',
        'main_image',
        'cover_image',
        'start_date',
        'end_date',
        'organizer',
        'sport_type',
        'is_featured',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('main_image')->useDisk('avatars');
        $this->addMediaCollection('cover_image')->useDisk('avatars');
    }

    /**
     * Get the localized name
     */
    public function getNameAttribute(): string
    {
        $locale = app()->getLocale();
        return $locale === 'fr' ? $this->name_fr : ($this->name_en ?? $this->name_fr);
    }

    /**
     * Get all events in this series
     */
    public function events()
    {
        return $this->hasMany(Event::class, 'event_series_id');
    }

    /**
     * Get upcoming events in this series
     */
    public function upcomingEvents()
    {
        return $this->hasMany(Event::class, 'event_series_id')
            ->where('event_date', '>=', now()->toDateString())
            ->where('is_active', true)
            ->orderBy('event_date', 'asc');
    }

    /**
     * Get featured events in this series
     */
    public function featuredEvents()
    {
        return $this->hasMany(Event::class, 'event_series_id')
            ->where('is_featured', true)
            ->where('is_active', true);
    }

    /**
     * Get the lowest price among all events in the series
     */
    public function getLowestPriceAttribute()
    {
        return $this->events()->min('min_price');
    }

    /**
     * Get the main image URL
     */
    public function getMainImageUrlAttribute(): string
    {
        $imageUrl = $this->getFirstMediaUrl('main_image');
        return $imageUrl ?: 'https://placehold.co/800x400/4c1d95/ffffff?text=' . urlencode($this->name_fr);
    }

    /**
     * Get the cover image URL
     */
    public function getCoverImageUrlAttribute(): string
    {
        $imageUrl = $this->getFirstMediaUrl('cover_image');
        return $imageUrl ?: $this->main_image_url;
    }
}

