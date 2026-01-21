<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CinetPayService
{
    protected $apiKey;
    protected $siteId;
    protected $secretKey;
    protected $baseUrl;
    protected $notifyUrl;
    protected $returnUrl;

    /**
     * Canaux de paiement supportés (valeurs pour l'API CinetPay)
     */
    // Canaux pour l'API CinetPay (valeurs acceptées par l'API)
    const CHANNEL_MOBILE_MONEY = 'MOBILE_MONEY';
    const CHANNEL_WALLET = 'WALLET';
    const CHANNEL_CREDIT_CARD_API = 'CREDIT_CARD';
    const CHANNEL_ALL = 'ALL';

    // Constantes internes pour l'interface utilisateur
    const CHANNEL_ORANGE_MONEY = 'ORANGE_MONEY';
    const CHANNEL_MTN_MONEY = 'MTN_MONEY';
    const CHANNEL_MOOV_MONEY = 'MOOV_MONEY';
    const CHANNEL_WAVE = 'WAVE';
    const CHANNEL_CREDIT_CARD = 'CREDIT_CARD';

    /**
     * Mapper le canal interne vers le canal API CinetPay
     *
     * @param string $channel
     * @return string
     */
    public function mapChannelToApi(string $channel): string
    {
        $mapping = [
            self::CHANNEL_ORANGE_MONEY => self::CHANNEL_MOBILE_MONEY,
            self::CHANNEL_MTN_MONEY => self::CHANNEL_MOBILE_MONEY,
            self::CHANNEL_MOOV_MONEY => self::CHANNEL_MOBILE_MONEY,
            self::CHANNEL_WAVE => self::CHANNEL_MOBILE_MONEY, // Wave est du Mobile Money
            self::CHANNEL_CREDIT_CARD => self::CHANNEL_CREDIT_CARD_API,
            self::CHANNEL_CREDIT_CARD_API => self::CHANNEL_CREDIT_CARD_API,
        ];

        return $mapping[$channel] ?? self::CHANNEL_ALL;
    }

    /**
     * Liste des canaux valides pour l'API
     */
    private function getValidApiChannels(): array
    {
        return [
            self::CHANNEL_MOBILE_MONEY,
            self::CHANNEL_WALLET,
            self::CHANNEL_CREDIT_CARD_API,
            self::CHANNEL_ALL,
        ];
    }

    public function __construct()
    {
        $this->apiKey = config('services.cinetpay.api_key');
        $this->siteId = config('services.cinetpay.site_id');
        $this->secretKey = config('services.cinetpay.secret_key');
        $this->baseUrl = config('services.cinetpay.base_url', 'https://api-checkout.cinetpay.com/v2');
        $this->notifyUrl = config('services.cinetpay.notify_url', route('payment.cinetpay.notify'));
        $this->returnUrl = config('services.cinetpay.return_url', route('home'));
    }

    /**
     * Vérifier si un canal de paiement est valide (canaux internes)
     *
     * @param string $channel
     * @return bool
     */
    public function isValidChannel(string $channel): bool
    {
        return in_array($channel, [
            self::CHANNEL_ORANGE_MONEY,
            self::CHANNEL_MTN_MONEY,
            self::CHANNEL_MOOV_MONEY,
            self::CHANNEL_WAVE,
            self::CHANNEL_CREDIT_CARD,
            self::CHANNEL_ALL,
            self::CHANNEL_MOBILE_MONEY,
            self::CHANNEL_WALLET,
            self::CHANNEL_CREDIT_CARD_API,
        ]);
    }

    /**
     * Obtenir les canaux de paiement disponibles pour l'interface utilisateur
     *
     * @return array
     */
    public function getAvailableChannels(): array
    {
        return [
            self::CHANNEL_ORANGE_MONEY => [
                'name' => 'Orange Money',
                'label' => 'Orange Money',
                'icon' => '📱',
                'color' => 'orange',
                'description' => 'Paiement via Orange Money Côte d\'Ivoire'
            ],
            self::CHANNEL_MTN_MONEY => [
                'name' => 'MTN Money',
                'label' => 'MTN Money',
                'icon' => '📱',
                'color' => 'yellow',
                'description' => 'Paiement via MTN Money Côte d\'Ivoire'
            ],
            self::CHANNEL_MOOV_MONEY => [
                'name' => 'Moov Money',
                'label' => 'Moov Money',
                'icon' => '📱',
                'color' => 'blue',
                'description' => 'Paiement via Moov Money Côte d\'Ivoire'
            ],
            self::CHANNEL_WAVE => [
                'name' => 'Wave',
                'label' => 'Wave',
                'icon' => '🌊',
                'color' => 'pink',
                'description' => 'Paiement via Wave Côte d\'Ivoire'
            ],
            self::CHANNEL_CREDIT_CARD => [
                'name' => 'Carte Bancaire',
                'label' => 'Visa / Mastercard',
                'icon' => '💳',
                'color' => 'indigo',
                'description' => 'Paiement par carte bancaire sécurisée'
            ],
            self::CHANNEL_ALL => [
                'name' => 'Tous',
                'label' => 'Tous les moyens',
                'icon' => '💰',
                'color' => 'gray',
                'description' => 'Choisir le moyen de paiement sur la page suivante'
            ],
        ];
    }

    /**
     * Initialize a payment
     *
     * @param array $data
     * @return array
     */
    public function initializePayment(array $data)
    {
        try {
            // Valider le canal de paiement
            $channel = $data['channel'] ?? self::CHANNEL_ALL;
            
            if (!$this->isValidChannel($channel)) {
                Log::warning('CinetPay: Canal de paiement invalide', ['channel' => $channel]);
                return [
                    'success' => false,
                    'message' => 'Canal de paiement non supporté: ' . $channel,
                ];
            }

            // Mapper le canal interne vers le canal API CinetPay
            $apiChannel = $this->mapChannelToApi($channel);

            // Générer un ID de transaction unique si non fourni
            $transactionId = $data['transaction_id'] ?? $this->generateTransactionId();

            // Formater le montant (doit être un entier)
            $amount = $this->formatAmount($data['amount'], $data['currency'] ?? 'XOF');
            
            // Forcer XOF car le compte CinetPay est configuré pour XOF
            $currency = 'XOF';
            
            // Convertir le montant si la devise d'origine était XAF
            $originalCurrency = $data['currency'] ?? 'XOF';
            if ($originalCurrency === 'XAF') {
                // 1 XAF = 1 XOF ( même valeur, même taux)
                $amount = (int) round($data['amount']);
            }

            // Nettoyer le numéro de téléphone (enlever les espaces et caractères spéciaux)
            $customerPhone = preg_replace('/[^0-9+]/', '', ($data['customer_phone'] ?? ''));
            
            // Préparer le payload selon la documentation CinetPay v2
            $payload = [
                'apikey' => $this->apiKey,
                'site_id' => $this->siteId,
                'transaction_id' => $transactionId,
                'amount' => $amount,
                'currency' => $currency,
                'description' => substr($data['description'] ?? 'Paiement Carré Premium', 0, 255),
                'customer_id' => substr($data['customer_id'] ?? '', 0, 50),
                'customer_name' => substr($data['customer_name'] ?? '', 0, 100),
                'customer_surname' => substr($data['customer_surname'] ?? '', 0, 100),
                'customer_email' => substr($data['customer_email'] ?? '', 0, 100),
                'customer_phone_number' => $customerPhone,
                'customer_address' => substr($data['customer_address'] ?? '', 0, 255),
                'customer_city' => substr($data['customer_city'] ?? 'Abidjan', 0, 100),
                'customer_country' => strtoupper($data['customer_country'] ?? 'CI'),
                'customer_state' => strtoupper($data['customer_state'] ?? 'CI'),
                'customer_zip_code' => substr($data['customer_zip_code'] ?? '', 0, 20),
                'notify_url' => $this->notifyUrl,
                'return_url' => $data['return_url'] ?? $this->returnUrl,
                'channels' => $apiChannel,
                'metadata' => json_encode($data['metadata'] ?? []),
                'lang' => substr($data['lang'] ?? app()->getLocale() ?? 'fr', 0, 5),
            ];

            Log::info('CinetPay: Initialisation du paiement', [
                'transaction_id' => $transactionId,
                'amount' => $amount,
                'currency' => $currency,
                'original_currency' => $originalCurrency,
                'channel' => $channel,
                'api_channel' => $apiChannel,
                'customer_phone' => $customerPhone,
            ]);

            $response = Http::timeout(30)->post("{$this->baseUrl}/payment", $payload);

            // Log la réponse complète pour le débogage
            $responseBody = $response->body();
            Log::info('CinetPay: Réponse brute', [
                'status' => $response->status(),
                'body' => $responseBody,
            ]);

            if ($response->successful()) {
                $result = $response->json();
                
                Log::info('CinetPay: Réponse API', ['code' => $result['code'] ?? null, 'data' => $result['data'] ?? null]);

                if (isset($result['code']) && $result['code'] == '201') {
                    return [
                        'success' => true,
                        'payment_url' => $result['data']['payment_url'] ?? null,
                        'payment_token' => $result['data']['payment_token'] ?? null,
                        'transaction_id' => $transactionId,
                        'data' => $result['data']
                    ];
                }

                return [
                    'success' => false,
                    'message' => $result['message'] ?? 'Erreur lors de l\'initialisation du paiement',
                    'code' => $result['code'] ?? null,
                    'data' => $result
                ];
            }

            $errorBody = $response->body();
            Log::error('CinetPay: Erreur HTTP', [
                'status' => $response->status(),
                'body' => $errorBody,
            ]);

            return [
                'success' => false,
                'message' => 'Erreur de connexion à CinetPay (HTTP ' . $response->status() . ')',
                'status' => $response->status()
            ];

        } catch (\Exception $e) {
            Log::error('CinetPay initialization error: ' . $e->getMessage(), [
                'exception' => $e->getTraceAsString(),
            ]);
            return [
                'success' => false,
                'message' => 'Erreur système: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Check payment status
     *
     * @param string $transactionId
     * @return array
     */
    public function checkPaymentStatus(string $transactionId)
    {
        try {
            $payload = [
                'apikey' => $this->apiKey,
                'site_id' => $this->siteId,
                'transaction_id' => $transactionId,
            ];

            Log::info('CinetPay: Vérification statut', ['transaction_id' => $transactionId]);

            $response = Http::timeout(30)->post("{$this->baseUrl}/payment/check", $payload);

            if ($response->successful()) {
                $result = $response->json();
                
                $status = $result['data']['status'] ?? 'PENDING';
                $isSuccess = in_array($status, ['ACCEPTED', 'SUCCESSFUL']);

                Log::info('CinetPay: Statut vérifié', [
                    'transaction_id' => $transactionId,
                    'status' => $status,
                    'is_success' => $isSuccess,
                ]);

                return [
                    'success' => true,
                    'status' => $status,
                    'is_success' => $isSuccess,
                    'amount' => $result['data']['amount'] ?? 0,
                    'currency' => $result['data']['currency'] ?? 'XOF',
                    'payment_method' => $result['data']['payment_method'] ?? '',
                    'operator_id' => $result['data']['operator_id'] ?? '',
                    'payment_date' => $result['data']['payment_date'] ?? null,
                    'data' => $result['data'] ?? []
                ];
            }

            return [
                'success' => false,
                'message' => 'Erreur lors de la vérification du paiement (HTTP ' . $response->status() . ')'
            ];

        } catch (\Exception $e) {
            Log::error('CinetPay check status error: ' . $e->getMessage(), [
                'exception' => $e->getTraceAsString(),
            ]);
            return [
                'success' => false,
                'message' => 'Erreur système: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Verify webhook signature
     *
     * @param array $data
     * @return bool
     */
    public function verifyWebhookSignature(array $data)
    {
        if (!isset($data['cpm_trans_id']) || !isset($data['signature'])) {
            Log::warning('CinetPay: Signature manquante', array_keys($data));
            return false;
        }

        // CinetPay signature verification
        // La signature est calculée comme: md5(cpm_trans_id + site_id + secret_key)
        $expectedSignature = md5(
            $data['cpm_trans_id'] . 
            $this->siteId . 
            $this->secretKey
        );
        
        $isValid = $expectedSignature === $data['signature'];
        
        if (!$isValid) {
            Log::warning('CinetPay: Signature invalide', [
                'expected' => $expectedSignature,
                'received' => $data['signature'],
            ]);
        }
        
        return $isValid;
    }

    /**
     * Parse webhook data and return structured payment info
     *
     * @param array $data
     * @return array
     */
    public function parseWebhookData(array $data): array
    {
        return [
            'transaction_id' => $data['cpm_trans_id'] ?? null,
            'amount' => $data['cpm_amount'] ?? 0,
            'currency' => $data['cpm_currency'] ?? 'XOF',
            'payment_method' => $data['payment_method'] ?? '',
            'result' => $data['cpm_result'] ?? '',
            'status' => $data['cpm_trans_status'] ?? '',
            'operator_id' => $data['operator_id'] ?? '',
            'phone' => $data['cpm_phone'] ?? '',
        ];
    }

    /**
     * Generate unique transaction ID
     *
     * @return string
     */
    protected function generateTransactionId()
    {
        return 'CP_' . strtoupper(uniqid()) . '_' . time();
    }

    /**
     * Format amount for CinetPay (no decimals for XOF)
     *
     * @param float $amount
     * @param string $currency
     * @return int
     */
    public function formatAmount($amount, $currency = 'XOF')
    {
        if ($currency === 'XOF') {
            return (int) round($amount);
        }
        return (int) round($amount * 100); // For currencies with decimals
    }

    /**
     * Test API connection
     *
     * @return array
     */
    public function testConnection()
    {
        try {
            $testTransactionId = 'TEST_' . time();
            
            $payload = [
                'apikey' => $this->apiKey,
                'site_id' => $this->siteId,
                'transaction_id' => $testTransactionId,
                'amount' => 100,
                'currency' => 'XOF',
                'description' => 'Test de connexion',
                'channels' => self::CHANNEL_ALL,
                'return_url' => $this->returnUrl,
                'notify_url' => $this->notifyUrl,
            ];

            $response = Http::timeout(10)->post("{$this->baseUrl}/payment", $payload);
            $result = $response->json();

            return [
                'success' => $response->successful() && isset($result['code']),
                'response' => $result,
                'message' => $result['message'] ?? 'Connexion réussie'
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ];
        }
    }
}
