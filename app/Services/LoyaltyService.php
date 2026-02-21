<?php

namespace App\Services;

use App\Models\User;
use App\Models\Booking;
use Illuminate\Support\Facades\Log;

/**
 * Service de gestion des points de fidélité
 * 
 * Règles:
 * - 1 point = 1000 XOF dépensés
 * - Points attribués après paiement confirmé
 * - Points utilisables: 100 points = 10,000 XOF de réduction
 */
class LoyaltyService
{
    /**
     * Taux de conversion: 1 point pour X XOF dépensés
     */
    const POINTS_PER_AMOUNT = 1000; // 1 point par 1000 XOF

    /**
     * Valeur d'un point en XOF lors de l'utilisation
     */
    const POINT_VALUE = 100; // 1 point = 100 XOF de réduction

    /**
     * Attribuer des points de fidélité après une réservation
     * 
     * @param Booking $booking
     * @return int Nombre de points attribués
     */
    public function awardPoints(Booking $booking): int
    {
        if (!$booking->user_id) {
            return 0; // Pas de points pour les réservations sans compte
        }

        try {
            $user = User::find($booking->user_id);
            
            if (!$user) {
                return 0;
            }

            // Convertir le montant en XOF si nécessaire
            $amountInXOF = $this->convertToXOF($booking->total_amount, $booking->currency);
            
            // Calculer les points (1 point par 1000 XOF)
            $points = floor($amountInXOF / self::POINTS_PER_AMOUNT);
            
            if ($points > 0) {
                // Ajouter les points
                $user->increment('loyalty_points', $points);
                
                Log::info("Points de fidélité attribués", [
                    'user_id' => $user->id,
                    'booking_id' => $booking->id,
                    'amount' => $amountInXOF,
                    'points_awarded' => $points,
                    'total_points' => $user->loyalty_points
                ]);
                
                return $points;
            }
            
            return 0;
        } catch (\Exception $e) {
            Log::error('Erreur attribution points fidélité: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Utiliser des points de fidélité pour une réduction
     * 
     * @param User $user
     * @param int $pointsToUse
     * @return array ['success' => bool, 'discount' => float, 'message' => string]
     */
    public function usePoints(User $user, int $pointsToUse): array
    {
        if ($pointsToUse <= 0) {
            return [
                'success' => false,
                'discount' => 0,
                'message' => 'Nombre de points invalide'
            ];
        }

        if ($user->loyalty_points < $pointsToUse) {
            return [
                'success' => false,
                'discount' => 0,
                'message' => 'Points insuffisants'
            ];
        }

        // Calculer la réduction (1 point = 100 XOF)
        $discount = $pointsToUse * self::POINT_VALUE;
        
        // Déduire les points
        $user->decrement('loyalty_points', $pointsToUse);
        
        Log::info("Points de fidélité utilisés", [
            'user_id' => $user->id,
            'points_used' => $pointsToUse,
            'discount_amount' => $discount,
            'remaining_points' => $user->loyalty_points
        ]);
        
        return [
            'success' => true,
            'discount' => $discount,
            'message' => "Réduction de {$discount} XOF appliquée"
        ];
    }

    /**
     * Calculer la réduction potentielle avec les points
     * 
     * @param User $user
     * @param float $orderAmount
     * @return array
     */
    public function calculatePotentialDiscount(User $user, float $orderAmount): array
    {
        $availablePoints = $user->loyalty_points;
        $maxDiscount = $availablePoints * self::POINT_VALUE;
        
        // Limiter la réduction à 20% du montant total
        $maxAllowedDiscount = $orderAmount * 0.20;
        $actualMaxDiscount = min($maxDiscount, $maxAllowedDiscount);
        $maxPointsUsable = floor($actualMaxDiscount / self::POINT_VALUE);
        
        return [
            'available_points' => $availablePoints,
            'max_points_usable' => $maxPointsUsable,
            'max_discount' => $actualMaxDiscount,
            'point_value' => self::POINT_VALUE
        ];
    }

    /**
     * Convertir un montant en XOF
     * 
     * @param float $amount
     * @param string $currency
     * @return float
     */
    protected function convertToXOF(float $amount, string $currency): float
    {
        // Taux de conversion approximatifs
        $rates = [
            'XOF' => 1,
            'EUR' => 655.957,
            'USD' => 600,
            'GBP' => 750,
        ];
        
        $rate = $rates[$currency] ?? 1;
        return $amount * $rate;
    }

    /**
     * Obtenir l'historique des points d'un utilisateur
     * 
     * @param User $user
     * @return array
     */
    public function getPointsHistory(User $user): array
    {
        $bookings = Booking::where('user_id', $user->id)
            ->where('payment_status', 'paid')
            ->orderBy('created_at', 'desc')
            ->get();
        
        $history = [];
        foreach ($bookings as $booking) {
            $amountInXOF = $this->convertToXOF($booking->total_amount, $booking->currency);
            $points = floor($amountInXOF / self::POINTS_PER_AMOUNT);
            
            $history[] = [
                'date' => $booking->created_at->format('d/m/Y'),
                'booking_reference' => $booking->booking_reference,
                'amount' => $booking->total_amount . ' ' . $booking->currency,
                'points_earned' => $points,
                'type' => 'earned'
            ];
        }
        
        return $history;
    }
}
