<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasImageUrl;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class EventPackage extends Model implements HasMedia
{
    use HasFactory, HasImageUrl, InteractsWithMedia;

    protected $fillable = [
        'event_id',
        'package_name_fr',
        'package_name_en',
        'package_code',
        'description_fr',
        'description_included_fr',
        'description_included_en',
        'price',
        'currency',
        'available_quantity',
        'max_per_order',
        'image',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'available_quantity' => 'integer',
        'max_per_order' => 'integer',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')->useDisk('avatars');
    }

    /**
     * Get the localized package name
     */
    public function getNameAttribute(): string
    {
        $locale = app()->getLocale();
        return $locale === 'fr' ? $this->package_name_fr : ($this->package_name_en ?? $this->package_name_fr);
    }

    /**
     * Get the event this package belongs to
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the series this package belongs to (through event)
     */
    public function series()
    {
        return $this->hasOneThrough(EventSeries::class, Event::class, 'id', 'id', 'event_id', 'event_series_id');
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute(): string
    {
        $currency = $this->currency ?? 'XAF';
        return number_format($this->price, 0, ',', ' ') . ' ' . $currency;
    }

    /**
     * Check if package is available
     */
    public function getIsAvailableAttribute(): bool
    {
        return $this->is_active && $this->available_quantity > 0;
    }

    /**
     * Get the image URL
     */
    public function getImageUrlAttribute(): string
    {
        $imageUrl = $this->getFirstMediaUrl('image');
        return $imageUrl ?: 'https://placehold.co/400x300/4c1d95/ffffff?text=' . urlencode($this->package_name_fr);
    }
}

