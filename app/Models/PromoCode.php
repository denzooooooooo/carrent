<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoCode extends Model
{
    protected $fillable = [
        'code',
        'description_fr',
        'description_en',
        'discount_type',
        'discount_value',
        'min_purchase_amount',
        'max_discount_amount',
        'usage_limit',
        'used_count',
        'valid_from',
        'valid_until',
        'applicable_to',
        'is_active',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'min_purchase_amount' => 'decimal:2',
        'max_discount_amount' => 'decimal:2',
        'usage_limit' => 'integer',
        'used_count' => 'integer',
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function usages()
    {
        return $this->hasMany(PromoCodeUsage::class);
    }

    public function getRemainingUsesAttribute(): ?int
    {
        if ($this->usage_limit === null) {
            return null;
        }

        return max($this->usage_limit - $this->used_count, 0);
    }

    public function getIsCurrentlyValidAttribute(): bool
    {
        $now = now();

        return $this->is_active
            && $now->greaterThanOrEqualTo($this->valid_from)
            && $now->lessThanOrEqualTo($this->valid_until)
            && ($this->usage_limit === null || $this->used_count < $this->usage_limit);
    }
}
