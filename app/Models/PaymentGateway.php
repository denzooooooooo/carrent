<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentGateway extends Model
{
    use HasFactory;

    protected $fillable = [
        'gateway_name',
        'gateway_type',
        'api_key',
        'api_secret',
        'merchant_id',
        'webhook_url',
        'is_active',
        'supported_currencies',
        'transaction_fee_percentage',
        'transaction_fee_fixed',
        'configuration',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'supported_currencies' => 'array',
        'transaction_fee_percentage' => 'decimal:2',
        'transaction_fee_fixed' => 'decimal:2',
        'configuration' => 'array',
    ];

    /**
     * Scope pour les passerelles actives
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope pour un type de passerelle
     */
    public function scopeType($query, $type)
    {
        return $query->where('gateway_type', $type);
    }

    /**
     * Vérifier si une devise est supportée
     */
    public function supportsCurrency($currency)
    {
        if (empty($this->supported_currencies)) {
            return true; // Si aucune restriction, toutes les devises sont supportées
        }

        return in_array($currency, $this->supported_currencies);
    }

    /**
     * Calculer les frais de transaction
     */
    public function calculateFees($amount)
    {
        $percentageFee = ($amount * $this->transaction_fee_percentage) / 100;
        $totalFee = $percentageFee + $this->transaction_fee_fixed;

        return round($totalFee, 2);
    }

    /**
     * Calculer le montant total avec frais
     */
    public function calculateTotalWithFees($amount)
    {
        return $amount + $this->calculateFees($amount);
    }

    /**
     * Obtenir les passerelles actives pour une devise
     */
    public static function getAvailableForCurrency($currency)
    {
        return self::active()
            ->get()
            ->filter(function ($gateway) use ($currency) {
                return $gateway->supportsCurrency($currency);
            });
    }
}
