<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PricingRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_type',
        'rule_name',
        'category',
        'margin_type',
        'margin_value',
        'min_price',
        'max_price',
        'is_active',
        'priority',
        'description',
    ];

    protected $casts = [
        'margin_value' => 'decimal:2',
        'min_price' => 'decimal:2',
        'max_price' => 'decimal:2',
        'is_active' => 'boolean',
        'priority' => 'integer',
    ];

    /**
     * Scope pour les règles actives
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope pour un type de produit spécifique
     */
    public function scopeForProduct($query, $productType)
    {
        return $query->where('product_type', $productType);
    }

    /**
     * Scope pour une catégorie spécifique
     */
    public function scopeForCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope pour trier par priorité
     */
    public function scopeByPriority($query)
    {
        return $query->orderBy('priority', 'desc');
    }

    /**
     * Obtenir la règle applicable pour un produit et un prix
     */
    public static function getApplicableRule($productType, $price, $category = null)
    {
        $query = self::active()
            ->forProduct($productType)
            ->byPriority();

        if ($category) {
            $query->where(function ($q) use ($category) {
                $q->forCategory($category)
                  ->orWhereNull('category');
            });
        }

        $query->where(function ($q) use ($price) {
            $q->where(function ($subQ) use ($price) {
                $subQ->whereNull('min_price')
                     ->orWhere('min_price', '<=', $price);
            })->where(function ($subQ) use ($price) {
                $subQ->whereNull('max_price')
                     ->orWhere('max_price', '>=', $price);
            });
        });

        return $query->first();
    }

    /**
     * Calculer la marge pour un prix donné
     */
    public function calculateMargin($basePrice)
    {
        if ($this->margin_type === 'percentage') {
            return ($basePrice * $this->margin_value) / 100;
        }
        
        return $this->margin_value;
    }

    /**
     * Calculer le prix final avec marge
     */
    public function calculateFinalPrice($basePrice)
    {
        return $basePrice + $this->calculateMargin($basePrice);
    }
}
