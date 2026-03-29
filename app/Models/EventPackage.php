<?php

namespace App\Models;

use App\Traits\HasImageUrl;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
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
        'venue_details_fr',
        'venue_details_en',
        'description_included_fr',
        'description_included_en',
        'price',
        'currency',
        'minimum_quantity',
        'available_quantity',
        'max_per_order',
        'image',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'minimum_quantity' => 'integer',
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

    public function options(): HasMany
    {
        return $this->hasMany(EventPackageOption::class, 'event_package_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('option_date');
    }

    public function allOptions(): HasMany
    {
        return $this->hasMany(EventPackageOption::class, 'event_package_id')
            ->orderBy('sort_order')
            ->orderBy('option_date');
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

    public function getVenueDetailsAttribute(): ?string
    {
        $locale = app()->getLocale();

        return $locale === 'fr'
            ? ($this->venue_details_fr ?? $this->venue_details_en)
            : ($this->venue_details_en ?? $this->venue_details_fr);
    }

    public function getHasOptionsAttribute(): bool
    {
        if ($this->relationLoaded('options')) {
            return $this->options->isNotEmpty();
        }

        return $this->options()->exists();
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
