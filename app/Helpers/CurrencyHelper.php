<?php

namespace App\Helpers;

class CurrencyHelper
{
    /**
     * Convert amount from XOF to other currencies
     */
    public static function convert($amount, $fromCurrency = 'XOF', $toCurrency = null)
    {
        if ($toCurrency === null) {
            $toCurrency = session('currency', 'XOF');
        }

        if ($fromCurrency === $toCurrency) {
            return $amount;
        }

        // Exchange rates (approximate, you should use real-time rates in production)
        $rates = [
            'XOF' => [
                'EUR' => 0.00152, // 1 XOF = 0.00152 EUR
                'USD' => 0.00162, // 1 XOF = 0.00162 USD
                'GBP' => 0.00129, // 1 XOF = 0.00129 GBP
            ],
            'EUR' => [
                'XOF' => 655.0,   // 1 EUR = 655 XOF
                'USD' => 1.06,    // 1 EUR = 1.06 USD
                'GBP' => 0.85,    // 1 EUR = 0.85 GBP
            ],
            'USD' => [
                'XOF' => 617.0,   // 1 USD = 617 XOF
                'EUR' => 0.94,    // 1 USD = 0.94 EUR
                'GBP' => 0.80,    // 1 USD = 0.80 GBP
            ],
            'GBP' => [
                'XOF' => 775.0,   // 1 GBP = 775 XOF
                'EUR' => 1.18,    // 1 GBP = 1.18 EUR
                'USD' => 1.25,    // 1 GBP = 1.25 USD
            ],
        ];

        if (!isset($rates[$fromCurrency][$toCurrency])) {
            return $amount; // Return original amount if conversion not available
        }

        return round($amount * $rates[$fromCurrency][$toCurrency], 2);
    }

    /**
     * Format amount with currency symbol
     */
    public static function format($amount, $currency = null)
    {
        if ($currency === null) {
            $currency = session('currency', 'XOF');
        }

        $symbols = [
            'XOF' => 'FCFA',
            'EUR' => '€',
            'USD' => '$',
            'GBP' => '£',
        ];

        $symbol = $symbols[$currency] ?? $currency;

        if ($currency === 'XOF') {
            return number_format($amount, 0, ',', ' ') . ' ' . $symbol;
        }

        return $symbol . number_format($amount, 2, ',', ' ');
    }

    /**
     * Get current currency
     */
    public static function current()
    {
        return session('currency', 'XOF');
    }
}
