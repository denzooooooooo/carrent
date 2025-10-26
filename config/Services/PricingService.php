<?php

namespace App\Services;

use App\Models\PricingRule;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;

/**
 * Service de calcul des prix et marges
 * 
 * Gère le calcul automatique des prix finaux en appliquant:
 * - Les marges configurées
 * - Les taxes
 * - Les frais de service
 * - Les promotions
 */
class PricingService
{
    /**
     * Calculer le prix final pour un vol
     * 
     * @param float $basePrice Prix de base Amadeus
     * @param string $flightType 'domestic' ou 'international'
     * @param string $travelClass 'ECONOMY', 'BUSINESS', 'FIRST'
     * @return array
     */
    public function calculateFlightPrice(float $basePrice, string $flightType = 'international', string $travelClass = 'ECONOMY')
    {
        // Récupérer la règle de pricing applicable
        $rule = PricingRule::where('product_type', 'flight')
            ->where('category', $flightType)
            ->where('is_active', true)
            ->orderBy('priority', 'desc')
            ->first();

        // Si pas de règle spécifique, utiliser une règle par défaut
        if (!$rule) {
            $rule = PricingRule::where('product_type', 'flight')
                ->where('category', null)
                ->where('is_active', true)
                ->first();
        }

        // Calculer la marge
        $marginAmount = 0;
        $marginPercentage = 0;

        if ($rule) {
            if ($rule->margin_type === 'percentage') {
                $marginPercentage = $rule->margin_value;
                $marginAmount = ($basePrice * $marginPercentage) / 100;
            } else {
                $marginAmount = $rule->margin_value;
                $marginPercentage = ($marginAmount / $basePrice) * 100;
            }
        }

        // Ajouter une marge supplémentaire pour business/first class
        if (in_array($travelClass, ['BUSINESS', 'FIRST'])) {
            $extraMargin = $travelClass === 'FIRST' ? 5 : 3; // % supplémentaire
            $extraAmount = ($basePrice * $extraMargin) / 100;
            $marginAmount += $extraAmount;
            $marginPercentage += $extraMargin;
        }

        // Calculer les taxes
        $taxRate = (float)(Setting::where('setting_key', 'tax_rate')->value('setting_value') ?? 0.18);
        $taxAmount = (($basePrice + $marginAmount) * $taxRate);

        // Frais de réservation
        $bookingFee = (float)(Setting::where('setting_key', 'booking_fee')->value('setting_value') ?? 5000);

        // Prix final
        $finalPrice = $basePrice + $marginAmount + $taxAmount + $bookingFee;

        return [
            'base_price' => round($basePrice, 2),
            'margin_amount' => round($marginAmount, 2),
            'margin_percentage' => round($marginPercentage, 2),
            'tax_amount' => round($taxAmount, 2),
            'tax_rate' => $taxRate,
            'booking_fee' => $bookingFee,
            'final_price' => round($finalPrice, 2),
            'breakdown' => [
                'Prix de base' => round($basePrice, 2),
                'Marge (' . round($marginPercentage, 2) . '%)' => round($marginAmount, 2),
                'Taxes (' . ($taxRate * 100) . '%)' => round($taxAmount, 2),
                'Frais de service' => $bookingFee,
                'Total' => round($finalPrice, 2)
            ]
        ];
    }

    /**
     * Calculer le prix final pour un événement
     * 
     * @param float $basePrice
     * @param string $eventType 'sport', 'concert', 'theater', etc.
     * @param string $zoneType 'vip', 'standard', 'economy'
     * @return array
     */
    public function calculateEventPrice(float $basePrice, string $eventType = 'sport', string $zoneType = 'standard')
    {
        // Récupérer la règle de pricing
        $rule = PricingRule::where('product_type', 'event')
            ->where('category', $eventType)
            ->where('is_active', true)
            ->orderBy('priority', 'desc')
            ->first();

        if (!$rule) {
            $rule = PricingRule::where('product_type', 'event')
                ->where('category', null)
                ->where('is_active', true)
                ->first();
        }

        // Calculer la marge
        $marginAmount = 0;
        $marginPercentage = 0;

        if ($rule) {
            if ($rule->margin_type === 'percentage') {
                $marginPercentage = $rule->margin_value;
                $marginAmount = ($basePrice * $marginPercentage) / 100;
            } else {
                $marginAmount = $rule->margin_value;
                $marginPercentage = ($marginAmount / $basePrice) * 100;
            }
        }

        // Marge supplémentaire pour VIP
        if ($zoneType === 'vip') {
            $extraMargin = 10; // % supplémentaire pour VIP
            $extraAmount = ($basePrice * $extraMargin) / 100;
            $marginAmount += $extraAmount;
            $marginPercentage += $extraMargin;
        }

        // Taxes
        $taxRate = (float)(Setting::where('setting_key', 'tax_rate')->value('setting_value') ?? 0.18);
        $taxAmount = (($basePrice + $marginAmount) * $taxRate);

        // Prix final
        $finalPrice = $basePrice + $marginAmount + $taxAmount;

        return [
            'base_price' => round($basePrice, 2),
            'margin_amount' => round($marginAmount, 2),
            'margin_percentage' => round($marginPercentage, 2),
            'tax_amount' => round($taxAmount, 2),
            'tax_rate' => $taxRate,
            'final_price' => round($finalPrice, 2),
            'breakdown' => [
                'Prix de base' => round($basePrice, 2),
                'Marge (' . round($marginPercentage, 2) . '%)' => round($marginAmount, 2),
                'Taxes (' . ($taxRate * 100) . '%)' => round($taxAmount, 2),
                'Total' => round($finalPrice, 2)
            ]
        ];
    }

    /**
     * Calculer le prix final pour un package touristique
     * 
     * @param float $basePrice
     * @param string $packageType 'helicopter', 'private_jet', 'cruise', etc.
     * @param int $participants
     * @return array
     */
    public function calculatePackagePrice(float $basePrice, string $packageType = 'standard', int $participants = 1)
    {
        // Récupérer la règle de pricing
        $rule = PricingRule::where('product_type', 'package')
            ->where('category', $packageType)
            ->where('is_active', true)
            ->orderBy('priority', 'desc')
            ->first();

        if (!$rule) {
            $rule = PricingRule::where('product_type', 'package')
                ->where('category', null)
                ->where('is_active', true)
                ->first();
        }

        // Prix de base total pour tous les participants
        $totalBasePrice = $basePrice * $participants;

        // Calculer la marge
        $marginAmount = 0;
        $marginPercentage = 0;

        if ($rule) {
            if ($rule->margin_type === 'percentage') {
                $marginPercentage = $rule->margin_value;
                $marginAmount = ($totalBasePrice * $marginPercentage) / 100;
            } else {
                $marginAmount = $rule->margin_value * $participants;
                $marginPercentage = ($marginAmount / $totalBasePrice) * 100;
            }
        }

        // Réduction pour groupe (si plus de 5 participants)
        $groupDiscount = 0;
        if ($participants >= 5) {
            $discountPercentage = min(15, ($participants - 4) * 2); // Max 15%
            $groupDiscount = ($totalBasePrice * $discountPercentage) / 100;
        }

        // Taxes
        $taxRate = (float)(Setting::where('setting_key', 'tax_rate')->value('setting_value') ?? 0.18);
        $taxAmount = (($totalBasePrice + $marginAmount - $groupDiscount) * $taxRate);

        // Prix final
        $finalPrice = $totalBasePrice + $marginAmount - $groupDiscount + $taxAmount;

        return [
            'base_price' => round($basePrice, 2),
            'base_price_per_person' => round($basePrice, 2),
            'total_base_price' => round($totalBasePrice, 2),
            'participants' => $participants,
            'margin_amount' => round($marginAmount, 2),
            'margin_percentage' => round($marginPercentage, 2),
            'group_discount' => round($groupDiscount, 2),
            'tax_amount' => round($taxAmount, 2),
            'tax_rate' => $taxRate,
            'final_price' => round($finalPrice, 2),
            'price_per_person' => round($finalPrice / $participants, 2),
            'breakdown' => [
                'Prix de base (' . $participants . ' pers.)' => round($totalBasePrice, 2),
                'Marge (' . round($marginPercentage, 2) . '%)' => round($marginAmount, 2),
                'Réduction groupe' => -round($groupDiscount, 2),
                'Taxes (' . ($taxRate * 100) . '%)' => round($taxAmount, 2),
                'Total' => round($finalPrice, 2)
            ]
        ];
    }

    /**
     * Appliquer un code promo
     * 
     * @param float $price
     * @param string $promoCode
     * @return array
     */
    public function applyPromoCode(float $price, string $promoCode)
    {
        // Cette méthode sera implémentée avec le modèle PromoCode
        // Pour l'instant, retourner le prix sans modification
        return [
            'original_price' => $price,
            'discount' => 0,
            'final_price' => $price,
            'promo_applied' => false
        ];
    }

    /**
     * Calculer les frais d'annulation
     * 
     * @param float $totalPrice
     * @param string $productType
     * @param int $daysBeforeDeparture
     * @return array
     */
    public function calculateCancellationFees(float $totalPrice, string $productType, int $daysBeforeDeparture)
    {
        $feePercentage = 0;

        // Politique d'annulation selon le délai
        if ($daysBeforeDeparture >= 30) {
            $feePercentage = 10; // 10% de frais
        } elseif ($daysBeforeDeparture >= 15) {
            $feePercentage = 25; // 25% de frais
        } elseif ($daysBeforeDeparture >= 7) {
            $feePercentage = 50; // 50% de frais
        } elseif ($daysBeforeDeparture >= 3) {
            $feePercentage = 75; // 75% de frais
        } else {
            $feePercentage = 100; // Non remboursable
        }

        $feeAmount = ($totalPrice * $feePercentage) / 100;
        $refundAmount = $totalPrice - $feeAmount;

        return [
            'total_price' => $totalPrice,
            'fee_percentage' => $feePercentage,
            'fee_amount' => round($feeAmount, 2),
            'refund_amount' => round($refundAmount, 2),
            'days_before_departure' => $daysBeforeDeparture,
            'is_refundable' => $feePercentage < 100
        ];
    }

    /**
     * Convertir un prix dans une autre devise
     * 
     * @param float $amount
     * @param string $fromCurrency
     * @param string $toCurrency
     * @return float
     */
    public function convertCurrency(float $amount, string $fromCurrency, string $toCurrency)
    {
        if ($fromCurrency === $toCurrency) {
            return $amount;
        }

        // Récupérer les taux de change depuis la table currencies
        $fromRate = \App\Models\Currency::where('code', $fromCurrency)->value('exchange_rate') ?? 1;
        $toRate = \App\Models\Currency::where('code', $toCurrency)->value('exchange_rate') ?? 1;

        // Convertir en devise de base (XOF) puis dans la devise cible
        $amountInBase = $amount / $fromRate;
        $convertedAmount = $amountInBase * $toRate;

        return round($convertedAmount, 2);
    }
}
