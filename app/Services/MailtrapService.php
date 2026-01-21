<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Mailtrap Sending API Service
 * Pour envoyer de vrais emails en production
 */
class MailtrapService
{
    protected $apiToken;
    protected $baseUrl = 'https://send.api.mailtrap.io/api';

    public function __construct()
    {
        $this->apiToken = config('services.mailtrap.api_token');
    }

    /**
     * Send email using Mailtrap Sending API
     *
     * @param array $data
     * @return array
     */
    public function sendEmail(array $data)
    {
        try {
            $payload = [
                'from' => [
                    'email' => $data['from_email'] ?? config('mail.from.address'),
                    'name' => $data['from_name'] ?? config('mail.from.name'),
                ],
                'to' => [
                    [
                        'email' => $data['to_email'],
                        'name' => $data['to_name'] ?? '',
                    ]
                ],
                'subject' => $data['subject'],
                'text' => $data['text'] ?? '',
                'html' => $data['html'] ?? '',
                'category' => $data['category'] ?? 'General',
            ];

            // Add CC if provided
            if (isset($data['cc'])) {
                $payload['cc'] = is_array($data['cc']) ? $data['cc'] : [['email' => $data['cc']]];
            }

            // Add BCC if provided
            if (isset($data['bcc'])) {
                $payload['bcc'] = is_array($data['bcc']) ? $data['bcc'] : [['email' => $data['bcc']]];
            }

            // Add attachments if provided
            if (isset($data['attachments'])) {
                $payload['attachments'] = $data['attachments'];
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiToken,
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}/send", $payload);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message_id' => $response->json('message_ids')[0] ?? null,
                    'data' => $response->json(),
                ];
            }

            Log::error('Mailtrap API Error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return [
                'success' => false,
                'message' => 'Failed to send email',
                'error' => $response->json('errors') ?? $response->body(),
            ];

        } catch (\Exception $e) {
            Log::error('Mailtrap Service Exception: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Exception: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Send verification code email
     *
     * @param string $email
     * @param string $name
     * @param string $code
     * @return array
     */
    public function sendVerificationCode(string $email, string $name, string $code)
    {
        $html = view('emails.verification-code', [
            'code' => $code,
            'name' => $name,
        ])->render();

        return $this->sendEmail([
            'to_email' => $email,
            'to_name' => $name,
            'subject' => 'Code de vérification - Carré Premium',
            'html' => $html,
            'text' => "Votre code de vérification est: {$code}",
            'category' => 'Verification',
        ]);
    }

    /**
     * Send booking confirmation email
     *
     * @param string $email
     * @param string $name
     * @param array $bookingData
     * @return array
     */
    public function sendBookingConfirmation(string $email, string $name, array $bookingData)
    {
        $html = view('emails.booking-confirmation', $bookingData)->render();

        return $this->sendEmail([
            'to_email' => $email,
            'to_name' => $name,
            'subject' => 'Confirmation de réservation - Carré Premium',
            'html' => $html,
            'category' => 'Booking',
        ]);
    }

    /**
     * Test connection to Mailtrap API
     *
     * @return array
     */
    public function testConnection()
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiToken,
            ])->get("{$this->baseUrl}/accounts");

            return [
                'success' => $response->successful(),
                'status' => $response->status(),
                'message' => $response->successful() ? 'Connection successful' : 'Connection failed',
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Exception: ' . $e->getMessage(),
            ];
        }
    }
}
