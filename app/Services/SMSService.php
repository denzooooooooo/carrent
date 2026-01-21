<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SMSService
{
    protected $provider;
    protected $config;

    public function __construct()
    {
        $this->provider = config('services.sms.provider', 'smsup'); // smsup, twilio, etc.
        $this->config = config('services.sms.' . $this->provider, []);
    }

    /**
     * Envoyer un SMS
     *
     * @param string $to Numéro de téléphone (format international)
     * @param string $message Message à envoyer
     * @return array
     */
    public function send(string $to, string $message): array
    {
        try {
            // Nettoyer le numéro de téléphone
            $to = $this->formatPhoneNumber($to);

            switch ($this->provider) {
                case 'smsup':
                    return $this->sendViaSMSUP($to, $message);
                case 'twilio':
                    return $this->sendViaTwilio($to, $message);
                default:
                    return $this->sendViaSMSUP($to, $message);
            }
        } catch (\Exception $e) {
            Log::error('SMS sending error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Erreur lors de l\'envoi du SMS: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Envoyer via SMSUP (Côte d'Ivoire)
     */
    protected function sendViaSMSUP(string $to, string $message): array
    {
        $apiKey = $this->config['token'] ?? '';
        $sender = $this->config['sender'] ?? 'CarrePremium';

        if (empty($apiKey)) {
            return [
                'success' => false,
                'message' => 'SMSUP API key not configured'
            ];
        }

        $response = Http::post('https://api.smsup.ci/v1/send', [
            'api_key' => $apiKey,
            'sender' => $sender,
            'to' => $to,
            'message' => $message,
        ]);

        if ($response->successful()) {
            $result = $response->json();
            
            if (isset($result['status']) && $result['status'] === 'success') {
                return [
                    'success' => true,
                    'message_id' => $result['message_id'] ?? null,
                    'provider' => 'smsup'
                ];
            }

            return [
                'success' => false,
                'message' => $result['message'] ?? 'Erreur SMSUP inconnue'
            ];
        }

        return [
            'success' => false,
            'message' => 'Erreur de connexion à SMSUP',
            'status' => $response->status()
        ];
    }

    /**
     * Envoyer via Twilio
     */
    protected function sendViaTwilio(string $to, string $message): array
    {
        $accountSid = $this->config['sid'] ?? '';
        $authToken = $this->config['token'] ?? '';
        $from = $this->config['from'] ?? '';

        if (empty($accountSid) || empty($authToken) || empty($from)) {
            return [
                'success' => false,
                'message' => 'Twilio credentials not configured'
            ];
        }

        $response = Http::withBasicAuth($accountSid, $authToken)
            ->asForm()
            ->post("https://api.twilio.com/2010-04-01/Accounts/{$accountSid}/Messages.json", [
                'From' => $from,
                'To' => $to,
                'Body' => $message,
            ]);

        if ($response->successful()) {
            $result = $response->json();
            
            return [
                'success' => true,
                'message_id' => $result['sid'] ?? null,
                'provider' => 'twilio'
            ];
        }

        return [
            'success' => false,
            'message' => 'Erreur Twilio',
            'status' => $response->status()
        ];
    }

    /**
     * Envoyer un code de vérification
     *
     * @param string $to
     * @param string $code
     * @return array
     */
    public function sendVerificationCode(string $to, string $code): array
    {
        $message = "Votre code de vérification Carré Premium est: {$code}. Ce code expire dans 15 minutes.";
        return $this->send($to, $message);
    }

    /**
     * Formater le numéro de téléphone au format international
     *
     * @param string $phone
     * @return string
     */
    protected function formatPhoneNumber(string $phone): string
    {
        // Supprimer tous les caractères non numériques
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Si le numéro commence par 0, le remplacer par +225 (Côte d'Ivoire)
        if (substr($phone, 0, 1) === '0') {
            $phone = '225' . substr($phone, 1);
        }

        // Ajouter le + si nécessaire
        if (substr($phone, 0, 1) !== '+') {
            $phone = '+' . $phone;
        }

        return $phone;
    }

    /**
     * Vérifier si le service SMS est configuré
     *
     * @return bool
     */
    public function isConfigured(): bool
    {
        switch ($this->provider) {
            case 'smsup':
                return !empty($this->config['token']);
            case 'twilio':
                return !empty($this->config['sid']) && 
                       !empty($this->config['token']) && 
                       !empty($this->config['from']);
            default:
                return false;
        }
    }
}
