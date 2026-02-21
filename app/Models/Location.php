<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasImageUrl;

class Location extends Model
{
    use HasImageUrl;

    protected $fillable = [
        'name_fr',
        'name_en',
        'description_fr',
        'description_en',
        'category',
        'type',
        'price_per_day',
        'capacity',
        'image',
        'features',
        'is_active'
    ];

    protected $casts = [
        'features' => 'array',
        'price_per_day' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    public function getNameAttribute()
    {
        return app()->getLocale() === 'fr' ? $this->name_fr : $this->name_en;
    }

    public function getDescriptionAttribute()
    {
        return app()->getLocale() === 'fr' ? $this->description_fr : $this->description_en;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }
}
