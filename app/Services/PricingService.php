<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

/**
 * Service de tarification pour calculer les commissions
 * 
 * Commission variable selon la classe (v2):
 * - Economy: 15%
 * - Business: 12%
 * - First: 10%
 */
class PricingService
{
    // Commission par défaut pour Economy
    const COMMISSION_RATE = 0.15;
    
    // Taux de conversion EUR → XOF (approximatif)
    const EUR_TO_XOF_RATE = 655.957;

    /**
     * Taux de commission par classe (v2)
     */
    const COMMISSION_RATES = [
        'economy' => 0.15,
        'business' => 0.12,
        'first' => 0.10,
    ];

    /**
     * Calculer le prix avec commission
     * 
     * @param float $basePrice Prix de base (en XOF ou EUR)
     * @param string $currency Devise du prix de base
     * @param float|null $commissionRate Taux de commission personnalisé
     * @return array
     */
    public function calculatePriceWithCommission(
        float $basePrice, 
        string $currency = 'XOF',
        ?float $commissionRate = null
    ): array {
        $rate = $commissionRate ?? self::COMMISSION_RATE;
        
        // Si le prix est en EUR, convertir en XOF
        $priceInXof = $this->convertToXof($basePrice, $currency);
        
        // Calculer la commission
        $commission = round($priceInXof * $rate);
        $totalPrice = $priceInXof + $commission;

        return [
            'base_price' => $priceInXof,
            'base_currency' => 'XOF',
            'original_price' => $basePrice,
            'original_currency' => $currency,
            'commission_amount' => $commission,
            'commission_percentage' => $rate * 100,
            'total_price' => $totalPrice,
            'currency' => 'XOF',
            'breakdown' => [
                'billet' => $priceInXof,
                'commission' => $commission,
                'total' => $totalPrice,
            ],
        ];
    }

    /**
     * 💰 Calculer le prix avec commission par classe (v2)
     * 
     * @param float $basePrice Prix de base
     * @param string $currency Devise
     * @param string $cabinClass Classe (economy, business, first)
     * @return array
     */
    public function calculatePriceWithCommissionV2(
        float $basePrice,
        string $currency = 'XOF',
        string $cabinClass = 'economy'
    ): array {
        $commissionRate = self::COMMISSION_RATES[strtolower($cabinClass)] ?? self::COMMISSION_RATE;
        return $this->calculatePriceWithCommission($basePrice, $currency, $commissionRate);
    }

    /**
     * Calculer les montants pour la répartition des paiements
     * 
     * @param float $totalPaid Montant total payé
     * @return array
     */
    public function calculatePaymentSplit(float $totalPaid): array
    {
        $commission = round($totalPaid * self::COMMISSION_RATE);
        $netToDuffel = $totalPaid - $commission;

        return [
            'total_paid' => $totalPaid,
            'commission_amount' => $commission,
            'commission_percentage' => self::COMMISSION_RATE * 100,
            'net_to_duffel' => $netToDuffel,
            'currency' => 'XOF',
            'breakdown' => [
                'carre_premium' => $commission,
                'duffel' => $netToDuffel,
                'total' => $totalPaid,
            ],
        ];
    }

    /**
     * 💰 Répartition des paiements v2 avec commission par classe
     */
    public function calculatePaymentSplitV2(float $totalPaid, string $cabinClass = 'economy'): array
    {
        $commissionRate = self::COMMISSION_RATES[strtolower($cabinClass)] ?? self::COMMISSION_RATE;
        $commission = round($totalPaid * $commissionRate);
        $netToDuffel = $totalPaid - $commission;

        return [
            'total_paid' => $totalPaid,
            'commission_amount' => $commission,
            'commission_rate' => $commissionRate,
            'commission_percentage' => $commissionRate * 100,
            'net_to_duffel' => $netToDuffel,
            'currency' => 'XOF',
            'cabin_class' => $cabinClass,
            'breakdown' => [
                'carre_premium' => $commission,
                'duffel' => $netToDuffel,
                'total' => $totalPaid,
            ],
        ];
    }

    /**
     * Calculer juste la commission
     * 
     * @param float $basePrice Prix de base
     * @param string $currency Devise
     * @return float
     */
    public function calculateCommission(float $basePrice, string $currency = 'XOF'): float
    {
        $priceInXof = $this->convertToXof($basePrice, $currency);
        return round($priceInXof * self::COMMISSION_RATE);
    }

    /**
     * 💰 Calculer la commission par classe (v2)
     */
    public function calculateCommissionV2(float $basePrice, string $currency = 'XOF', string $cabinClass = 'economy'): float
    {
        $commissionRate = self::COMMISSION_RATES[strtolower($cabinClass)] ?? self::COMMISSION_RATE;
        $priceInXof = $this->convertToXof($basePrice, $currency);
        return round($priceInXof * $commissionRate);
    }

    /**
     * 🧾 Calculer le montant total avec taxes
     * 
     * @param float $basePrice Prix de base
     * @param float $taxAmount Montant des taxes
     * @param string $currency Devise
     * @param string $cabinClass Classe
     * @return array
     */
    public function calculateTotalWithTaxes(
        float $basePrice,
        float $taxAmount = 0,
        string $currency = 'XOF',
        string $cabinClass = 'economy'
    ): array {
        $commissionRate = self::COMMISSION_RATES[strtolower($cabinClass)] ?? self::COMMISSION_RATE;
        
        $baseInXof = $this->convertToXof($basePrice, $currency);
        $taxInXof = $this->convertToXof($taxAmount, $currency);
        
        $subtotal = $baseInXof + $taxInXof;
        $commission = round($subtotal * $commissionRate);
        $totalPrice = $subtotal + $commission;

        return [
            'base_price' => $baseInXof,
            'tax_amount' => $taxInXof,
            'subtotal' => $subtotal,
            'commission_amount' => $commission,
            'commission_rate' => $commissionRate,
            'commission_percentage' => $commissionRate * 100,
            'total_price' => $totalPrice,
            'currency' => 'XOF',
        ];
    }

    /**
     * 💸 Calculer le montant de remboursement
     * 
     * @param float $originalPrice Prix original
     * @param array $conditions Conditions de remboursement
     * @param string $currency Devise
     * @return array
     */
    public function calculateRefundAmount(float $originalPrice, array $conditions, string $currency = 'XOF'): array
    {
        $priceInXof = $this->convertToXof($originalPrice, $currency);
        
        $refundAllowed = $conditions['refund_before_departure']['allowed'] ?? false;
        $penaltyAmount = $conditions['refund_before_departure']['penalty_amount'] ?? 0;
        $penaltyCurrency = $conditions['refund_before_departure']['penalty_currency'] ?? 'XOF';
        
        if (!$refundAllowed) {
            return [
                'refund_allowed' => false,
                'refund_amount' => 0,
                'penalty_amount' => $priceInXof,
                'penalty_percentage' => 100,
                'message' => 'Remboursement non autorisé pour ce tarif',
            ];
        }

        // Convertir la pénalité si nécessaire
        $penaltyInXof = $this->convertToXof($penaltyAmount, $penaltyCurrency);
        $refundAmount = max(0, $priceInXof - $penaltyInXof);
        $penaltyPercentage = ($penaltyInXof / $priceInXof) * 100;

        return [
            'refund_allowed' => true,
            'refund_amount' => $refundAmount,
            'penalty_amount' => $penaltyInXof,
            'penalty_percentage' => round($penaltyPercentage, 2),
            'message' => $penaltyAmount > 0 
                ? "Remboursement de {$refundAmount} XOF après pénalité de {$penaltyInXof} XOF"
                : 'Remboursement intégral disponible',
        ];
    }

    /**
     * 🔄 Calculer les frais de modification
     * 
     * @param float $originalPrice Prix original
     * @param array $conditions Conditions de modification
     * @param string $currency Devise
     * @return array
     */
    public function calculateChangeFee(float $originalPrice, array $conditions, string $currency = 'XOF'): array
    {
        $priceInXof = $this->convertToXof($originalPrice, $currency);
        
        $changeAllowed = $conditions['change_before_departure']['allowed'] ?? false;
        $penaltyAmount = $conditions['change_before_departure']['penalty_amount'] ?? 0;
        $penaltyCurrency = $conditions['change_before_departure']['penalty_currency'] ?? 'XOF';
        
        if (!$changeAllowed) {
            return [
                'change_allowed' => false,
                'change_fee' => 0,
                'message' => 'Modification non autorisée pour ce tarif',
            ];
        }

        // Convertir la pénalité si nécessaire
        $feeInXof = $this->convertToXof($penaltyAmount, $penaltyCurrency);

        return [
            'change_allowed' => true,
            'change_fee' => $feeInXof,
            'message' => $feeInXof > 0
                ? "Frais de modification: {$feeInXof} XOF"
                : 'Modification gratuite',
        ];
    }

    /**
     * Convertir un prix en XOF
     * 
     * @param float $amount Montant
     * @param string $currency Devise source
     * @return float
     */
    public function convertToXof(float $amount, string $currency): float
    {
        if (strtoupper($currency) === 'XOF') {
            return $amount;
        }
        
        if (strtoupper($currency) === 'EUR') {
            return round($amount * self::EUR_TO_XOF_RATE);
        }

        // Par défaut, retourner le montant tel quel
        Log::warning('PricingService: Devise non reconnue, pas de conversion', [
            'currency' => $currency,
        ]);
        return $amount;
    }

    /**
     * Formater le prix pour l'affichage
     * 
     * @param float $amount Montant
     * @param string $currency Devise
     * @return string
     */
    public function formatPrice(float $amount, string $currency = 'XOF'): string
    {
        $formatted = number_format($amount, 0, ',', ' ');
        $currencySymbol = strtoupper($currency) === 'XOF' ? 'XOF' : $currency;
        return $formatted . ' ' . $currencySymbol;
    }

    /**
     * Calculer le prix de base à partir du prix total (inverse)
     * Utile pour vérifier les calculs
     * 
     * @param float $totalPrice Prix total avec commission
     * @return array
     */
    public function extractFromTotal(float $totalPrice): array
    {
        $basePrice = round($totalPrice / (1 + self::COMMISSION_RATE));
        $commission = $totalPrice - $basePrice;

        return [
            'base_price' => $basePrice,
            'commission' => $commission,
            'commission_percentage' => self::COMMISSION_RATE * 100,
            'total_price' => $totalPrice,
            'currency' => 'XOF',
        ];
    }

    /**
     * Vérifier si le prix affiché correspond au prix de base + commission
     * 
     * @param float $basePrice Prix de base annoncé
     * @param float $displayedPrice Prix affiché
     * @return bool
     */
    public function verifyPricing(float $basePrice, float $displayedPrice): bool
    {
        $expectedTotal = $basePrice + $this->calculateCommission($basePrice);
        return abs($displayedPrice - $expectedTotal) < 1; // Tolérance de 1 XOF
    }

    /**
     * Obtenir le taux de commission actuel
     * 
     * @return float
     */
    public function getCommissionRate(): float
    {
        return self::COMMISSION_RATE;
    }

    /**
     * 💰 Obtenir le taux de commission par classe (v2)
     */
    public function getCommissionRateV2(string $cabinClass = 'economy'): float
    {
        return self::COMMISSION_RATES[strtolower($cabinClass)] ?? self::COMMISSION_RATE;
    }

    /**
     * Obtenir le taux de change EUR → XOF
     * 
     * @return float
     */
    public function getExchangeRate(): float
    {
        return self::EUR_TO_XOF_RATE;
    }

    /**
     * Calculer les prix pour plusieurs passagers
     * 
     * @param float $pricePerPassenger Prix par passager
     * @param int $passengers Nombre de passagers
     * @param string $currency Devise
     * @return array
     */
    public function calculateForMultiplePassengers(
        float $pricePerPassenger, 
        int $passengers,
        string $currency = 'XOF'
    ): array {
        $baseTotal = $pricePerPassenger * $passengers;
        $pricing = $this->calculatePriceWithCommission($baseTotal, $currency);

        return [
            'per_passenger' => [
                'base' => $pricePerPassenger,
                'with_commission' => round($pricing['total_price'] / $passengers),
            ],
            'total' => [
                'base' => $baseTotal,
                'with_commission' => $pricing['total_price'],
            ],
            'commission' => $pricing['commission_amount'],
            'passengers' => $passengers,
            'currency' => $pricing['currency'],
        ];
    }

    /**
     * 💰 Calculer les prix pour plusieurs passagers v2
     */
    public function calculateForMultiplePassengersV2(
        float $pricePerPassenger,
        int $passengers,
        string $currency = 'XOF',
        string $cabinClass = 'economy'
    ): array {
        $baseTotal = $pricePerPassenger * $passengers;
        $pricing = $this->calculatePriceWithCommissionV2($baseTotal, $currency, $cabinClass);

        return [
            'per_passenger' => [
                'base' => $pricePerPassenger,
                'with_commission' => round($pricing['total_price'] / $passengers),
            ],
            'total' => [
                'base' => $baseTotal,
                'with_commission' => $pricing['total_price'],
            ],
            'commission' => $pricing['commission_amount'],
            'commission_rate' => $pricing['commission_percentage'],
            'passengers' => $passengers,
            'currency' => $pricing['currency'],
            'cabin_class' => $cabinClass,
        ];
    }

    /**
     * 📊 Obtenir tous les taux de commission
     * 
     * @return array
     */
    public function getAllCommissionRates(): array
    {
        return [
            'economy' => self::COMMISSION_RATES['economy'] * 100,
            'business' => self::COMMISSION_RATES['business'] * 100,
            'first' => self::COMMISSION_RATES['first'] * 100,
        ];
    }
}

