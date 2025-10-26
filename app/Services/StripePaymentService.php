<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Webhook;
use App\Models\Booking;
use App\Models\Payment;
use App\Mail\BookingConfirmation;
use App\Mail\PaymentConfirmation;
use Illuminate\Support\Facades\Mail;
use Exception;

class StripePaymentService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Create a payment intent
     */
    public function createPaymentIntent($amount, $currency, $metadata = [])
    {
        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => $amount * 100, // Stripe utilise les centimes
                'currency' => strtolower($currency),
                'metadata' => $metadata,
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ]);

            return [
                'success' => true,
                'client_secret' => $paymentIntent->client_secret,
                'payment_intent_id' => $paymentIntent->id
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Confirm payment
     */
    public function confirmPayment($paymentIntentId)
    {
        try {
            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);
            
            return [
                'success' => true,
                'status' => $paymentIntent->status,
                'amount' => $paymentIntent->amount / 100,
                'currency' => strtoupper($paymentIntent->currency)
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Handle webhook
     */
    public function handleWebhook($payload, $signature)
    {
        try {
            $event = Webhook::constructEvent(
                $payload,
                $signature,
                config('services.stripe.webhook_secret')
            );

            switch ($event->type) {
                case 'payment_intent.succeeded':
                    $paymentIntent = $event->data->object;
                    $this->handleSuccessfulPayment($paymentIntent);
                    break;

                case 'payment_intent.payment_failed':
                    $paymentIntent = $event->data->object;
                    $this->handleFailedPayment($paymentIntent);
                    break;
            }

            return ['success' => true];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Handle successful payment
     */
    private function handleSuccessfulPayment($paymentIntent)
    {
        $bookingId = $paymentIntent->metadata->booking_id ?? null;
        
        if ($bookingId) {
            $booking = Booking::find($bookingId);
            if ($booking) {
                $booking->update([
                    'payment_status' => 'paid',
                    'status' => 'confirmed',
                    'confirmed_at' => now()
                ]);

                // Mettre à jour le paiement
                Payment::where('booking_id', $booking->id)
                    ->where('transaction_id', $paymentIntent->id)
                    ->update([
                        'status' => 'completed',
                        'payment_date' => now()
                    ]);

                // Envoyer les emails
                try {
                    Mail::to($booking->user->email)->send(new BookingConfirmation($booking));
                    Mail::to($booking->user->email)->send(new PaymentConfirmation($booking));
                } catch (Exception $e) {
                    \Log::error('Email sending failed: ' . $e->getMessage());
                }
            }
        }
    }

    /**
     * Handle failed payment
     */
    private function handleFailedPayment($paymentIntent)
    {
        $bookingId = $paymentIntent->metadata->booking_id ?? null;
        
        if ($bookingId) {
            $booking = Booking::find($bookingId);
            if ($booking) {
                $booking->update([
                    'payment_status' => 'failed'
                ]);

                Payment::where('booking_id', $booking->id)
                    ->where('transaction_id', $paymentIntent->id)
                    ->update([
                        'status' => 'failed',
                        'failure_reason' => $paymentIntent->last_payment_error->message ?? 'Payment failed'
                    ]);
            }
        }
    }

    /**
     * Create refund
     */
    public function createRefund($paymentIntentId, $amount = null)
    {
        try {
            $refund = \Stripe\Refund::create([
                'payment_intent' => $paymentIntentId,
                'amount' => $amount ? $amount * 100 : null
            ]);

            return [
                'success' => true,
                'refund_id' => $refund->id,
                'status' => $refund->status
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}
