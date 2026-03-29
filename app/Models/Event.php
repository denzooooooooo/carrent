<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasImageUrl;
use Illuminate\Support\Facades\Schema;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Event extends Model implements HasMedia
{
    use HasFactory, HasImageUrl, InteractsWithMedia;

    protected static ?bool $hasFamilyColumn = null;

    protected static ?bool $hasLegacyEventTypeColumn = null;

    protected static ?bool $hasLegacySportTypeColumn = null;

    /**
     * Attributs remplissables en masse.
     */
    protected $fillable = [
        'category_id',
        'type_id',
        'event_series_id',
        'match_number',
        'is_home_team_match',
        'title_fr',
        'title_en',
        'slug',
        'family',
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
        'is_home_team_match' => 'boolean',
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
     * Get the series this event belongs to
     */
    public function series()
    {
        return $this->belongsTo(EventSeries::class, 'event_series_id');
    }

    /**
     * Get packages for this event
     */
    public function packages()
    {
        return $this->hasMany(EventPackage::class)->where('is_active', true)->orderBy('sort_order');
    }

    /**
     * Get all packages (including inactive)
     */
    public function allPackages()
    {
        return $this->hasMany(EventPackage::class)->orderBy('sort_order');
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

    public function getFamilyAttribute($value): string
    {
        if (filled($value)) {
            return self::normalizeFamily($value);
        }

        return $this->inferFamily();
    }

    public function getLocationLabelAttribute(): string
    {
        $segments = array_filter([$this->venue_name, $this->city, $this->country]);

        return implode(', ', $segments);
    }

    public function getCoverImageUrlAttribute(): string
    {
        return $this->getFirstMediaUrl('avatar', 'normal')
            ?: 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?w=800&h=600&fit=crop';
    }

    public function getAvailabilityStateAttribute(): string
    {
        if ($this->is_sold_out) {
            return 'sold_out';
        }

        if ($this->available_seats > 0 && $this->available_seats <= 20) {
            return 'limited';
        }

        return 'available';
    }

    public function getIsSoldOutAttribute(): bool
    {
        return $this->total_seats > 0 && $this->available_seats <= 0;
    }

    public function getShortDateLabelAttribute(): ?string
    {
        return $this->event_date?->translatedFormat('d M Y');
    }

    public function scopeCatalog(Builder $query): Builder
    {
        return $query->with(['category', 'type']);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeMatchingSearch(Builder $query, ?string $term): Builder
    {
        $term = trim((string) $term);

        if ($term === '') {
            return $query;
        }

        $like = '%' . $term . '%';

        return $query->where(function (Builder $builder) use ($like) {
            $builder
                ->where('title_fr', 'like', $like)
                ->orWhere('title_en', 'like', $like)
                ->orWhere('description_fr', 'like', $like)
                ->orWhere('description_en', 'like', $like)
                ->orWhere('venue_name', 'like', $like)
                ->orWhere('city', 'like', $like)
                ->orWhere('country', 'like', $like)
                ->orWhere('organizer', 'like', $like)
                ->orWhere('slug', 'like', $like);
        });
    }

    public function scopeInCategory(Builder $query, $categoryId): Builder
    {
        if (blank($categoryId)) {
            return $query;
        }

        return $query->where('category_id', (int) $categoryId);
    }

    public function scopeInCity(Builder $query, ?string $city): Builder
    {
        $city = trim((string) $city);

        if ($city === '') {
            return $query;
        }

        return $query->where('city', 'like', '%' . $city . '%');
    }

    public function scopeInCountry(Builder $query, ?string $country): Builder
    {
        $country = trim((string) $country);

        if ($country === '') {
            return $query;
        }

        return $query->where('country', 'like', '%' . $country . '%');
    }

    public function scopeAtVenue(Builder $query, ?string $venue): Builder
    {
        $venue = trim((string) $venue);

        if ($venue === '') {
            return $query;
        }

        return $query->where('venue_name', 'like', '%' . $venue . '%');
    }

    public function scopeWithinBudget(Builder $query, $min = null, $max = null): Builder
    {
        if (filled($min)) {
            $query->where('min_price', '>=', $min);
        }

        if (filled($max)) {
            $query->where('max_price', '<=', $max);
        }

        return $query;
    }

    public function scopeOnDate(Builder $query, $date = null): Builder
    {
        if (blank($date)) {
            return $query;
        }

        try {
            $parsedDate = Carbon::parse($date)->toDateString();
        } catch (\Throwable $exception) {
            return $query;
        }

        return $query->whereDate('event_date', $parsedDate);
    }

    public function scopeBetweenDates(Builder $query, $startDate = null, $endDate = null): Builder
    {
        $start = $this->parseDate($startDate);
        $end = $this->parseDate($endDate);

        if ($start && $end && $start->gt($end)) {
            [$start, $end] = [$end, $start];
        }

        if ($start) {
            $query->whereDate('event_date', '>=', $start->toDateString());
        }

        if ($end) {
            $query->whereDate('event_date', '<=', $end->toDateString());
        }

        return $query;
    }

    public function scopeAvailableOnly(Builder $query, bool $availableOnly = true): Builder
    {
        if (!$availableOnly) {
            return $query;
        }

        return $query->where(function (Builder $builder) {
            $builder
                ->whereNull('total_seats')
                ->orWhere('total_seats', '=', 0)
                ->orWhere('available_seats', '>', 0);
        });
    }

    public function scopeFeaturedOnly(Builder $query, bool $featuredOnly = true): Builder
    {
        if (!$featuredOnly) {
            return $query;
        }

        return $query->where('is_featured', true);
    }

    public function scopeInFamily(Builder $query, string $family, array $categoryIds = [], array $typeIds = []): Builder
    {
        $family = self::normalizeFamily($family);

        if ($family === 'all') {
            return $query;
        }

        if (self::hasFamilyColumn()) {
            return $query->where('family', $family);
        }

        if (empty($categoryIds)) {
            $categoryIds = self::resolveFamilyCategoryIds($family);
        }

        if (empty($typeIds)) {
            $typeIds = self::resolveFamilyTypeIds($family);
        }

        $hasEventTypeColumn = self::hasLegacyEventTypeColumn();
        $hasSportTypeColumn = self::hasLegacySportTypeColumn();

        if (empty($categoryIds) && empty($typeIds) && !$hasEventTypeColumn && !$hasSportTypeColumn) {
            return $query;
        }

        return $query->where(function (Builder $builder) use (
            $family,
            $categoryIds,
            $typeIds,
            $hasEventTypeColumn,
            $hasSportTypeColumn
        ) {
            $hasConstraint = false;

            if (!empty($categoryIds)) {
                $builder->whereIn('category_id', $categoryIds);
                $hasConstraint = true;
            }

            if (!empty($typeIds)) {
                $hasConstraint ? $builder->orWhereIn('type_id', $typeIds) : $builder->whereIn('type_id', $typeIds);
                $hasConstraint = true;
            }

            if ($family === 'sportif') {
                if ($hasEventTypeColumn) {
                    $hasConstraint ? $builder->orWhere('event_type', 'sport') : $builder->where('event_type', 'sport');
                    $hasConstraint = true;
                }

                if ($hasSportTypeColumn) {
                    $hasConstraint ? $builder->orWhereNotNull('sport_type') : $builder->whereNotNull('sport_type');
                    $hasConstraint = true;
                }
            }

            if ($family === 'culturel' && $hasEventTypeColumn) {
                $cultureTypes = ['concert', 'theater', 'festival', 'other'];
                $hasConstraint ? $builder->orWhereIn('event_type', $cultureTypes) : $builder->whereIn('event_type', $cultureTypes);
                $hasConstraint = true;
            }

            if (!$hasConstraint && $family === 'culturel' && $hasSportTypeColumn) {
                $builder->whereNull('sport_type');
            }
        });
    }

    public function scopeSortForCatalog(Builder $query, string $sort = 'soonest'): Builder
    {
        $sort = self::normalizeSort($sort);
        $today = Carbon::today()->toDateString();

        return match ($sort) {
            'latest' => $query
                ->orderByDesc('event_date')
                ->orderByDesc('event_time'),
            'price_low' => $query
                ->orderByRaw('CASE WHEN min_price IS NULL THEN 1 ELSE 0 END')
                ->orderBy('min_price')
                ->orderBy('event_date')
                ->orderBy('event_time'),
            'price_high' => $query
                ->orderByRaw('CASE WHEN max_price IS NULL THEN 1 ELSE 0 END')
                ->orderByDesc('max_price')
                ->orderBy('event_date')
                ->orderBy('event_time'),
            'featured' => $query
                ->orderByDesc('is_featured')
                ->orderByRaw('CASE WHEN event_date < ? THEN 1 ELSE 0 END', [$today])
                ->orderBy('event_date')
                ->orderBy('event_time'),
            default => $query
                ->orderByRaw('CASE WHEN event_date < ? THEN 1 ELSE 0 END', [$today])
                ->orderBy('event_date')
                ->orderBy('event_time'),
        };
    }

    public static function normalizeFamily(?string $family): string
    {
        $normalized = strtolower(trim((string) $family));

        return match ($normalized) {
            'sport', 'sports', 'sportif', 'sportive' => 'sportif',
            'culture', 'cultural', 'culturel', 'culturelle', 'music', 'musique', 'concert' => 'culturel',
            default => 'all',
        };
    }

    public static function normalizeSort(?string $sort): string
    {
        $normalized = strtolower(trim((string) $sort));

        return in_array($normalized, ['soonest', 'latest', 'price_low', 'price_high', 'featured'], true)
            ? $normalized
            : 'soonest';
    }

    public static function resolveFamilyCategoryIds(string $family): array
    {
        $keywords = match (self::normalizeFamily($family)) {
            'sportif' => ['sport'],
            'culturel' => ['music', 'musique', 'culture', 'culturel', 'concert', 'festival', 'show', 'theatre', 'théâtre', 'spectacle', 'art'],
            default => [],
        };

        if (empty($keywords)) {
            return [];
        }

        return EventCategory::query()
            ->where(function (Builder $builder) use ($keywords) {
                foreach ($keywords as $keyword) {
                    $like = '%' . $keyword . '%';
                    $builder
                        ->orWhere('name_fr', 'like', $like)
                        ->orWhere('name_en', 'like', $like)
                        ->orWhere('slug', 'like', $like);
                }
            })
            ->pluck('id')
            ->all();
    }

    public static function resolveFamilyTypeIds(string $family): array
    {
        $keywords = match (self::normalizeFamily($family)) {
            'sportif' => ['sport', 'football', 'foot', 'soccer', 'tennis', 'basket', 'rugby', 'golf', 'formula', 'formule', 'match', 'moto', 'race', 'prix', 'fifa', 'uefa'],
            'culturel' => ['concert', 'music', 'musique', 'festival', 'show', 'theatre', 'théâtre', 'opera', 'opéra', 'spectacle', 'cinema', 'cinéma', 'comedy', 'comédie'],
            default => [],
        };

        if (empty($keywords)) {
            return [];
        }

        return EventType::query()
            ->where(function (Builder $builder) use ($keywords) {
                foreach ($keywords as $keyword) {
                    $like = '%' . $keyword . '%';
                    $builder
                        ->orWhere('name_fr', 'like', $like)
                        ->orWhere('name_en', 'like', $like)
                        ->orWhere('slug', 'like', $like);
                }
            })
            ->pluck('id')
            ->all();
    }

    protected function inferFamily(): string
    {
        $categorySource = strtolower(trim((string) ($this->category?->name_fr ?? $this->category?->name_en ?? $this->category?->slug)));
        $typeSource = strtolower(trim((string) ($this->type?->name_fr ?? $this->type?->name_en ?? $this->type?->slug)));
        $legacyType = strtolower(trim((string) $this->getRawOriginal('event_type')));
        $legacySportType = strtolower(trim((string) $this->getRawOriginal('sport_type')));
        $haystack = implode(' ', array_filter([$categorySource, $typeSource, $legacyType, $legacySportType]));

        foreach (['sport', 'football', 'tennis', 'basket', 'rugby', 'f1', 'formula', 'match', 'moto', 'golf'] as $keyword) {
            if (str_contains($haystack, $keyword)) {
                return 'sportif';
            }
        }

        return 'culturel';
    }

    protected static function hasFamilyColumn(): bool
    {
        return self::$hasFamilyColumn ??= Schema::hasColumn('events', 'family');
    }

    protected static function hasLegacyEventTypeColumn(): bool
    {
        return self::$hasLegacyEventTypeColumn ??= Schema::hasColumn('events', 'event_type');
    }

    protected static function hasLegacySportTypeColumn(): bool
    {
        return self::$hasLegacySportTypeColumn ??= Schema::hasColumn('events', 'sport_type');
    }

    protected function parseDate($date): ?Carbon
    {
        if (blank($date)) {
            return null;
        }

        try {
            return Carbon::parse($date);
        } catch (\Throwable $exception) {
            return null;
        }
    }
}
