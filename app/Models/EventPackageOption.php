<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventPackageOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_package_id',
        'option_label_fr',
        'option_label_en',
        'option_context_fr',
        'option_context_en',
        'option_date',
        'price',
        'currency',
        'available_quantity',
        'max_per_order',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'option_date' => 'date',
        'price' => 'decimal:2',
        'available_quantity' => 'integer',
        'max_per_order' => 'integer',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function package()
    {
        return $this->belongsTo(EventPackage::class, 'event_package_id');
    }

    public function getLabelAttribute(): string
    {
        $locale = app()->getLocale();

        return $locale === 'fr'
            ? ($this->option_label_fr ?? $this->option_label_en ?? '')
            : ($this->option_label_en ?? $this->option_label_fr ?? '');
    }

    public function getContextAttribute(): ?string
    {
        $locale = app()->getLocale();

        return $locale === 'fr'
            ? ($this->option_context_fr ?? $this->option_context_en)
            : ($this->option_context_en ?? $this->option_context_fr);
    }

    public function getFullLabelAttribute(): string
    {
        return collect([$this->label, $this->context])
            ->filter()
            ->implode(' - ');
    }

    public function getFormattedPriceAttribute(): string
    {
        $currency = $this->currency ?? 'XAF';

        return number_format((float) $this->price, 0, ',', ' ') . ' ' . $currency;
    }
}
