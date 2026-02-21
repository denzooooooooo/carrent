<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Models\Booking;
use App\Models\Payment;
use App\Mail\BookingConfirmation;
use Illuminate\Support\Facades\Mail;
use Exception;

class MobileMoneyService
{
    /**
     * Initialize Orange Money payment
     */
    public function initializeOrangeMoneyPayment($amount, $phone, $bookingNumber)
    {
        try {
            $token = $this->getOrangeMoneyToken();
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json'
            ])->post(config('services.orange_money.api_url') . '/webpayment/v1/transactionInit', [
                'merchant_key' => config('services.orange_money.merchant_key'),
                'currency' => 'XOF',
                'order_id' => $bookingNumber,
                'amount' => $amount,
                'return_url' => config('services.orange_money.return_url'),
                'cancel_url' => config('services.orange_money.return_url'),
                'notif_url' => route('payment.orange.callback'),
                'lang' => 'fr',
                'reference' => $bookingNumber
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'payment_url' => $response->json('payment_url'),
                    'payment_token' => $response->json('payment_token'),
                    'transaction_id' => $response->json('transaction_id')
                ];
            }

            return [
                'success' => false,
                'error' => 'Erreur lors de l\'initialisation du paiement Orange Money'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Initialize MTN Mobile Money payment
     */
    public function initializeMTNPayment($amount, $phone, $bookingNumber)
    {
        try {
            $referenceId = $this->generateUUID();
            
            $response = Http::withHeaders([
                'X-Reference-Id' => $referenceId,
                'X-Target-Environment' => config('services.mtn_momo.environment', 'sandbox'),
                'Ocp-Apim-Subscription-Key' => config('services.mtn_momo.subscription_key'),
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->getMTNToken()
            ])->post('https://sandbox.momodeveloper.mtn.com/collection/v1_0/requesttopay', [
                'amount' => (string)$amount,
                'currency' => 'XOF',
                'externalId' => $bookingNumber,
                'payer' => [
                    'partyIdType' => 'MSISDN',
                    'partyId' => $phone
                ],
                'payerMessage' => 'Paiement Carré Premium',
                'payeeNote' => 'Réservation ' . $bookingNumber
            ]);

            if ($response->successful() || $response->status() === 202) {
                return [
                    'success' => true,
                    'transaction_id' => $referenceId,
                    'message' => 'Paiement initié. Veuillez confirmer sur votre téléphone.'
                ];
            }

            return [
                'success' => false,
                'error' => 'Erreur lors de l\'initialisation du paiement MTN'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Check Orange Money payment status
     */
    public function checkOrangeMoneyStatus($transactionId)
    {
        try {
            $token = $this->getOrangeMoneyToken();
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token
            ])->get(config('services.orange_money.api_url') . '/webpayment/v1/transactionStatus/' . $transactionId);

            if ($response->successful()) {
                $status = $response->json('status');
                return [
                    'success' => true,
                    'status' => $status,
                    'paid' => $status === 'SUCCESS'
                ];
            }

            return ['success' => false];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Check MTN Mobile Money payment status
     */
    public function checkMTNStatus($transactionId)
    {
        try {
            $response = Http::withHeaders([
                'X-Target-Environment' => config('services.mtn_momo.environment', 'sandbox'),
                'Ocp-Apim-Subscription-Key' => config('services.mtn_momo.subscription_key'),
                'Authorization' => 'Bearer ' . $this->getMTNToken()
            ])->get('https://sandbox.momodeveloper.mtn.com/collection/v1_0/requesttopay/' . $transactionId);

            if ($response->successful()) {
                $status = $response->json('status');
                return [
                    'success' => true,
                    'status' => $status,
                    'paid' => $status === 'SUCCESSFUL'
                ];
            }

            return ['success' => false];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Check payment status (generic)
     */
    public function checkPaymentStatus($transactionId, $provider = 'orange')
    {
        if ($provider === 'orange') {
            return $this->checkOrangeMoneyStatus($transactionId);
        } elseif ($provider === 'mtn') {
            return $this->checkMTNStatus($transactionId);
        }

        return ['success' => false, 'error' => 'Provider not supported'];
    }

    /**
     * Handle Orange Money callback
     */
    public function handleOrangeMoneyCallback($transactionId, $status)
    {
        try {
            $payment = Payment::where('transaction_id', $transactionId)->first();
            
            if (!$payment) {
                return ['success' => false, 'error' => 'Payment not found'];
            }

            if ($status === 'SUCCESS') {
                $payment->update([
                    'status' => 'completed',
                    'payment_date' => now()
                ]);

                $booking = $payment->booking;
                $booking->update([
                    'payment_status' => 'paid',
                    'status' => 'confirmed',
                    'confirmed_at' => now()
                ]);

                // Envoyer email de confirmation
                try {
                    Mail::to($booking->user->email)->send(new BookingConfirmation($booking));
                } catch (Exception $e) {
                    \Log::error('Email sending failed: ' . $e->getMessage());
                }

                return ['success' => true, 'message' => 'Payment confirmed'];
            } else {
                $payment->update([
                    'status' => 'failed',
                    'failure_reason' => 'Payment cancelled or failed'
                ]);

                return ['success' => false, 'message' => 'Payment failed'];
            }
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Get Orange Money OAuth token
     */
    private function getOrangeMoneyToken()
    {
        return Cache::remember('orange_money_token', 3500, function () {
            try {
                $response = Http::asForm()->post(
                    config('services.orange_money.auth_url', 'https://api.orange.com/oauth/v3/token'),
                    [
                        'grant_type' => 'client_credentials'
                    ],
                    [
                        'Authorization' => 'Basic ' . base64_encode(
                            config('services.orange_money.client_id') . ':' . config('services.orange_money.client_secret')
                        )
                    ]
                );

                if ($response->successful()) {
                    return $response->json('access_token');
                }

                return null;
            } catch (Exception $e) {
                \Log::error('Orange Money token error: ' . $e->getMessage());
                return null;
            }
        });
    }

    /**
     * Get MTN Mobile Money token
     */
    private function getMTNToken()
    {
        return Cache::remember('mtn_momo_token', 3500, function () {
            try {
                $response = Http::withHeaders([
                    'Ocp-Apim-Subscription-Key' => config('services.mtn_momo.subscription_key')
                ])->post('https://sandbox.momodeveloper.mtn.com/collection/token/', [
                    'grant_type' => 'client_credentials'
                ]);

                if ($response->successful()) {
                    return $response->json('access_token');
                }

                return null;
            } catch (Exception $e) {
                \Log::error('MTN MoMo token error: ' . $e->getMessage());
                return null;
            }
        });
    }

    /**
     * Generate UUID v4
     */
    private function generateUUID()
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }
}
