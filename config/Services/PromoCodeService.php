<?php

namespace App\Services;

use App\Models\PromoCode;
use App\Models\PromoCodeUsage;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

/**
 * Service de gestion des codes promotionnels
 * 
 * Gère la validation et l'application des codes promo
 */
class PromoCodeService
{
    /**
     * Valider un code promo
     * 
     * @param string $code
     * @param float $orderAmount
     * @param string $bookingType (flight, event, package)
     * @param int|null $userId
     * @return array
     */
    public function validatePromoCode(string $code, float $orderAmount, string $bookingType, ?int $userId = null): array
    {
        try {
            // Rechercher le code promo
            $promoCode = PromoCode::where('code', strtoupper($code))
                ->where('is_active', true)
                ->first();

            if (!$promoCode) {
                return [
                    'valid' => false,
                    'message' => 'Code promo invalide'
                ];
            }

            // Vérifier les dates de validité
            $now = Carbon::now();
            if ($now->lt($promoCode->valid_from) || $now->gt($promoCode->valid_until)) {
                return [
                    'valid' => false,
                    'message' => 'Code promo expiré'
                ];
            }

            // Vérifier le type de produit
            if ($promoCode->applicable_to !== 'all' && $promoCode->applicable_to !== $bookingType) {
                return [
                    'valid' => false,
                    'message' => 'Code promo non applicable à ce type de réservation'
                ];
            }

            // Vérifier le montant minimum
            if ($promoCode->min_purchase_amount && $orderAmount < $promoCode->min_purchase_amount) {
                return [
                    'valid' => false,
                    'message' => "Montant minimum requis: {$promoCode->min_purchase_amount} XOF"
                ];
            }

            // Vérifier la limite d'utilisation globale
            if ($promoCode->usage_limit && $promoCode->used_count >= $promoCode->usage_limit) {
                return [
                    'valid' => false,
                    'message' => 'Code promo épuisé'
                ];
            }

            // Vérifier si l'utilisateur a déjà utilisé ce code
            if ($userId) {
                $alreadyUsed = PromoCodeUsage::where('promo_code_id', $promoCode->id)
                    ->where('user_id', $userId)
                    ->exists();
                
                if ($alreadyUsed) {
                    return [
                        'valid' => false,
                        'message' => 'Vous avez déjà utilisé ce code promo'
                    ];
                }
            }

            // Calculer la réduction
            $discount = $this->calculateDiscount($promoCode, $orderAmount);

            return [
                'valid' => true,
                'promo_code_id' => $promoCode->id,
                'discount_amount' => $discount,
                'discount_type' => $promoCode->discount_type,
                'discount_value' => $promoCode->discount_value,
                'message' => "Réduction de {$discount} XOF appliquée"
            ];

        } catch (\Exception $e) {
            Log::error('Erreur validation code promo: ' . $e->getMessage());
            return [
                'valid' => false,
                'message' => 'Erreur lors de la validation du code'
            ];
        }
    }

    /**
     * Calculer le montant de la réduction
     * 
     * @param PromoCode $promoCode
     * @param float $orderAmount
     * @return float
     */
    protected function calculateDiscount(PromoCode $promoCode, float $orderAmount): float
    {
        if ($promoCode->discount_type === 'percentage') {
            // Réduction en pourcentage
            $discount = ($orderAmount * $promoCode->discount_value) / 100;
        } else {
            // Réduction en montant fixe
            $discount = $promoCode->discount_value;
        }

        // Appliquer le plafond de réduction si défini
        if ($promoCode->max_discount_amount) {
            $discount = min($discount, $promoCode->max_discount_amount);
        }

        // La réduction ne peut pas dépasser le montant de la commande
        $discount = min($discount, $orderAmount);

        return round($discount, 2);
    }

    /**
     * Enregistrer l'utilisation d'un code promo
     * 
     * @param int $promoCodeId
     * @param int $bookingId
     * @param int|null $userId
     * @param float $discountAmount
     * @return void
     */
    public function recordUsage(int $promoCodeId, int $bookingId, ?int $userId, float $discountAmount): void
    {
        try {
            // Créer l'enregistrement d'utilisation
            PromoCodeUsage::create([
                'promo_code_id' => $promoCodeId,
                'user_id' => $userId,
                'booking_id' => $bookingId,
                'discount_amount' => $discountAmount,
                'used_at' => now()
            ]);

            // Incrémenter le compteur d'utilisation
            PromoCode::where('id', $promoCodeId)->increment('used_count');

            Log::info("Code promo utilisé", [
                'promo_code_id' => $promoCodeId,
                'booking_id' => $bookingId,
                'user_id' => $userId,
                'discount_amount' => $discountAmount
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur enregistrement utilisation code promo: ' . $e->getMessage());
        }
    }

    /**
     * Obtenir les codes promo actifs pour un type de réservation
     * 
     * @param string $bookingType
     * @return array
     */
    public function getActivePromoCodes(string $bookingType = 'all'): array
    {
        $query = PromoCode::where('is_active', true)
            ->where('valid_from', '<=', now())
            ->where('valid_until', '>=', now());

        if ($bookingType !== 'all') {
            $query->where(function($q) use ($bookingType) {
                $q->where('applicable_to', 'all')
                  ->orWhere('applicable_to', $bookingType);
            });
        }

        return $query->get()->map(function($promo) {
            return [
                'code' => $promo->code,
                'description' => $promo->description_fr,
                'discount_type' => $promo->discount_type,
                'discount_value' => $promo->discount_value,
                'min_purchase' => $promo->min_purchase_amount,
                'valid_until' => $promo->valid_until->format('d/m/Y')
            ];
        })->toArray();
    }
}
