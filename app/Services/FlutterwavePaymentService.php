<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Booking;
use App\Models\Payment;
use Exception;

class FlutterwavePaymentService
{
    protected $baseUrl;
    protected $secretKey;
    protected $publicKey;

    public function __construct()
    {
        $this->baseUrl = 'https://api.flutterwave.com/v3';
        $this->secretKey = config('services.flutterwave.secret_key');
        $this->publicKey = config('services.flutterwave.public_key');
    }

    /**
     * Initialize payment
     */
    public function initializePayment($data)
    {
        try {
            $payload = [
                'tx_ref' => $data['tx_ref'],
                'amount' => $data['amount'],
                'currency' => $data['currency'],
                'redirect_url' => $data['redirect_url'],
                'payment_options' => 'card,mobilemoney,ussd',
                'customer' => [
                    'email' => $data['customer']['email'],
                    'phonenumber' => $data['customer']['phone'] ?? '',
                    'name' => $data['customer']['name'],
                ],
                'customizations' => [
                    'title' => $data['customizations']['title'],
                    'description' => $data['customizations']['description'],
                    'logo' => $data['customizations']['logo'] ?? '',
                ],
                'meta' => $data['meta'] ?? [],
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/payments', $payload);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json(),
                ];
            }

            return [
                'success' => false,
                'error' => $response->json()['message'] ?? 'Payment initialization failed',
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Verify payment
     */
    public function verifyPayment($transactionId)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
            ])->get($this->baseUrl . '/transactions/' . $transactionId . '/verify');

            if ($response->successful()) {
                $data = $response->json();

                return [
                    'success' => true,
                    'status' => $data['data']['status'],
                    'amount' => $data['data']['amount'],
                    'currency' => $data['data']['currency'],
                    'tx_ref' => $data['data']['tx_ref'],
                    'data' => $data['data'],
                ];
            }

            return [
                'success' => false,
                'error' => $response->json()['message'] ?? 'Payment verification failed',
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Handle webhook
     */
    public function handleWebhook($payload)
    {
        try {
            // Verify webhook signature if needed
            $data = json_decode($payload, true);

            if ($data['event'] === 'charge.completed' && $data['data']['status'] === 'successful') {
                $this->handleSuccessfulPayment($data['data']);
            }

            return ['success' => true];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Handle successful payment
     */
    private function handleSuccessfulPayment($paymentData)
    {
        $txRef = $paymentData['tx_ref'];
        $bookingId = explode('_', $txRef)[1] ?? null; // Extract booking ID from tx_ref

        if ($bookingId) {
            $booking = Booking::find($bookingId);
            if ($booking) {
                $booking->update([
                    'payment_status' => 'paid',
                    'status' => 'confirmed',
                    'confirmed_at' => now(),
                ]);

                // Create or update payment record
                Payment::updateOrCreate(
                    [
                        'booking_id' => $booking->id,
                        'transaction_id' => $paymentData['id'],
                    ],
                    [
                        'amount' => $paymentData['amount'],
                        'currency' => $paymentData['currency'],
                        'payment_method' => 'flutterwave',
                        'status' => 'completed',
                        'payment_date' => now(),
                        'gateway_response' => json_encode($paymentData),
                    ]
                );

                // Send confirmation emails
                $this->sendConfirmationEmails($booking);
            }
        }
    }

    /**
     * Send confirmation emails
     */
    private function sendConfirmationEmails($booking)
    {
        try {
            // Send appropriate confirmation email based on booking type
            switch ($booking->booking_type) {
                case 'event':
                    if ($booking->eventBooking) {
                        \Illuminate\Support\Facades\Mail::to($booking->eventBooking->user_email)
                            ->send(new \App\Mail\EventBookingConfirmation($booking->eventBooking));
                    }
                    break;
                case 'flight':
                    if ($booking->flightBooking) {
                        $passengerName = $this->extractPassengerName($booking);
                        \Illuminate\Support\Facades\Mail::to($booking->user->email)
                            ->send(new \App\Mail\FlightBookingConfirmation($booking, $booking->flightBooking, $passengerName));
                    }
                    break;
                case 'package':
                    $passengerName = $this->extractPassengerName($booking);
                    \Illuminate\Support\Facades\Mail::to($booking->user->email)
                        ->send(new \App\Mail\PackageBookingConfirmation($booking, $passengerName));
                    break;
            }
        } catch (Exception $e) {
            \Log::error('Email sending failed: ' . $e->getMessage());
        }
    }

    /**
     * Extract passenger name from booking details
     */
    private function extractPassengerName($booking)
    {
        $passengerDetails = $booking->passenger_details;

        if (is_array($passengerDetails) && !empty($passengerDetails)) {
            $firstPassenger = $passengerDetails[0];
            if (isset($firstPassenger['name'])) {
                return $firstPassenger['name'];
            } elseif (isset($firstPassenger['first_name']) && isset($firstPassenger['last_name'])) {
                return $firstPassenger['first_name'] . ' ' . $firstPassenger['last_name'];
            }
        }

        return 'Client';
    }
}
